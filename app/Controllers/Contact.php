<?php

namespace App\Controllers;

class Contact extends BaseController
{
    private array $budgets = ['Under UGX 2M', 'UGX 2M - 5M', 'UGX 5M - 15M', 'UGX 15M - 50M', 'Above UGX 50M', 'Not sure yet'];

    public function index()
    {
        return view('contact', [
            'page_title'       => 'Contact Ephraitech | IT Company in Kampala, Uganda',
            'page_description' => 'Contact Ephraitech Unified Solutions in Uganda for software development, cloud, networking, hardware servicing, IT consulting and ICT training. Request a quote today.',
            'body_class'       => 'theme-dark page-contact',
            'services'         => get_services(),
            'budgets'          => $this->budgets,
            'errors'           => [],
            'message_errors'   => [],
            'old'              => $this->emptyOld(),
            'success'          => session()->getFlashdata('quote_success'),
            'message_success'  => session()->getFlashdata('message_success'),
            'schema'           => [
                '@context' => 'https://schema.org',
                '@type'    => 'ContactPage',
                'name'     => 'Contact Ephraitech',
                'url'      => base_url('contact'),
            ],
        ]);
    }

    public function submit()
    {
        // Route handles both message and quote submissions (form_type field)
        if (post('form_type') === 'message') {
            return $this->submitMessage();
        }
        return $this->submitQuote();
    }

    private function submitMessage()
    {
        $errors = [];
        $first   = clean_text(post('message_first_name'), 80);
        $last    = clean_text(post('message_last_name'), 80);
        $email   = strtolower(clean_text(post('message_email'), 190));
        $subject = clean_text(post('message_subject'), 190);
        $body    = clean_text(post('message'), 5000);

        if (mb_strlen($first) < 2)   $errors['first_name'] = 'Please enter your first name.';
        if (mb_strlen($last) < 2)    $errors['last_name']  = 'Please enter your last name.';
        if (!valid_email($email))    $errors['email']      = 'Please enter a valid email address.';
        if (mb_strlen($body) < 10)   $errors['message']    = 'Please enter at least 10 characters.';

        if (!$errors) {
            try {
                \Config\Database::connect()->table('contact_messages')->insert([
                    'first_name' => $first,
                    'last_name'  => $last,
                    'email'      => $email,
                    'phone'      => clean_text(post('message_phone'), 40) ?: null,
                    'subject'    => $subject ?: null,
                    'message'    => $body,
                    'ip_address' => client_ip(),
                ]);
                return redirect()->to('contact#message')->with('message_success', 'Thank you. Your message has been received.');
            } catch (\Throwable $e) {
                $errors['form'] = 'Something went wrong. Please try again.';
            }
        }

        return view('contact', [
            'page_title'       => 'Contact Us | Ephraitech',
            'page_description' => 'Contact Ephraitech.',
            'body_class'       => 'theme-dark page-contact',
            'services'         => get_services(),
            'budgets'          => $this->budgets,
            'errors'           => [],
            'message_errors'   => $errors,
            'old'              => $this->emptyOld(),
            'success'          => null,
            'message_success'  => null,
        ]);
    }

    private function submitQuote()
    {
        $old = [];
        foreach ($this->emptyOld() as $k => $_) {
            $old[$k] = post($k);
        }

        $errors = [];

        if (post('website') !== '') {
            $errors['form'] = 'Submission rejected.';
        }
        $started = (int) post('form_started', '0');
        if ($started > 0 && (time() - $started) < 3) {
            $errors['form'] = 'Submission rejected. Please take a moment and try again.';
        }
        $lastAt = session()->get('last_quote_at');
        if ($lastAt && (time() - (int) $lastAt) < 60) {
            $errors['form'] = 'Please wait a minute before sending another request.';
        }

        $first   = clean_text($old['first_name'], 80);
        $last    = clean_text($old['last_name'], 80);
        $email   = strtolower(clean_text($old['email'], 190));
        $phone   = clean_text($old['phone'], 40);
        $wa      = clean_text($old['whatsapp'], 40);
        $comp    = clean_text($old['company'], 150);
        $desc    = clean_text($old['description'], 5000);
        $budget  = in_array($old['budget'], $this->budgets, true) ? $old['budget'] : '';
        $serviceRow = $old['service'] !== '' ? get_service_by_slug($old['service']) : null;

        if (mb_strlen($first) < 2) $errors['first_name'] = 'Please enter your first name.';
        if (mb_strlen($last) < 2)  $errors['last_name']  = 'Please enter your last name.';
        if (!valid_email($email))  $errors['email']      = 'Please enter a valid email address.';
        if (!valid_phone($phone))  $errors['phone']      = 'Please enter a valid phone number.';
        if (!valid_phone($wa))     $errors['whatsapp']   = 'Please enter a valid WhatsApp number.';
        if ($old['service'] !== '' && $serviceRow === null) $errors['service'] = 'Please choose a valid service.';
        if (mb_strlen($desc) < 20) $errors['description'] = 'Please describe your project in at least 20 characters.';

        if (!$errors) {
            try {
                \Config\Database::connect()->table('quote_requests')->insert([
                    'first_name'     => $first,
                    'last_name'      => $last,
                    'email'          => $email,
                    'phone'          => $phone !== '' ? $phone : null,
                    'whatsapp'       => $wa !== '' ? $wa : null,
                    'company'        => $comp !== '' ? $comp : null,
                    'service_id'     => $serviceRow['id'] ?? null,
                    'service_needed' => $serviceRow['title'] ?? null,
                    'budget'         => $budget !== '' ? $budget : null,
                    'description'    => $desc,
                    'ip_address'     => client_ip(),
                ]);
                session()->set('last_quote_at', time());
                return redirect()->to('contact#quote')->with('quote_success', 'Thank you, ' . $first . '. Your request has been received. We will get back to you shortly.');
            } catch (\Throwable $e) {
                $errors['form'] = 'Something went wrong. Please try again or contact us directly.';
            }
        }

        return view('contact', [
            'page_title'       => 'Contact Us | Ephraitech',
            'page_description' => 'Contact Ephraitech.',
            'body_class'       => 'theme-dark page-contact',
            'services'         => get_services(),
            'budgets'          => $this->budgets,
            'errors'           => $errors,
            'message_errors'   => [],
            'old'              => $old,
            'success'          => null,
            'message_success'  => null,
        ]);
    }

    private function emptyOld(): array
    {
        return [
            'first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '',
            'whatsapp' => '', 'company' => '', 'service' => '', 'budget' => '', 'description' => '',
        ];
    }
}