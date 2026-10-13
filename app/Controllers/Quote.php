<?php

namespace App\Controllers;

class Quote extends BaseController
{
    private array $budgets = ['Under UGX 2M', 'UGX 2M - 5M', 'UGX 5M - 15M', 'UGX 15M - 50M', 'Above UGX 50M', 'Not sure yet'];

    public function index()
    {
        return view('quote', [
            'page_title'       => 'Request a Quote | Ephraitech Uganda',
            'page_description' => 'Request a free quote for software development, cloud infrastructure, networking, hardware servicing, IT consulting or ICT training from Ephraitech in Uganda.',
            'body_class'       => 'theme-dark page-quote',
            'services'         => get_services(),
            'budgets'          => $this->budgets,
            'errors'           => [],
            'old'              => $this->emptyOld(),
            'success'          => session()->getFlashdata('quote_success'),
        ]);
    }

    public function submit()
    {
        $old = [];
        foreach ($this->emptyOld() as $k => $_) {
            $old[$k] = post($k);
        }

        $errors = [];
        $req    = $this->request;

        // Honeypot & time trap
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
            $db = \Config\Database::connect();
            try {
                $db->table('quote_requests')->insert([
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
                return redirect()->to('quote#quote')->with('quote_success', 'Thank you, ' . $first . '. Your request has been received. We will get back to you shortly.');
            } catch (\Throwable $e) {
                $errors['form'] = 'Something went wrong. Please try again or contact us directly.';
            }
        }

        return view('quote', [
            'page_title'       => 'Request a Quote | Ephraitech',
            'page_description' => 'Request a quote from Ephraitech.',
            'body_class'       => 'theme-dark page-quote',
            'services'         => get_services(),
            'budgets'          => $this->budgets,
            'errors'           => $errors,
            'old'              => $old,
            'success'          => null,
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