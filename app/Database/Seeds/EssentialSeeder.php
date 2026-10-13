<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EssentialSeeder extends Seeder
{
    public function run()
    {
        // ----- Admin user -----
        // Default password: "password" (must_change_password = 1 → forced change on first login)
        $this->db->table('users')->insert([
            'username'             => 'admin',
            'email'                => 'admin@ephraitech.local',
            'password_hash'        => password_hash('password', PASSWORD_DEFAULT),
            'full_name'            => 'Site Administrator',
            'role'                 => 'admin',
            'must_change_password' => 1,
        ]);

        // ----- Site settings (matches original) -----
        $settings = [
            ['site_tagline',        'Ephraitech Unified Solutions is an ICT company helping businesses in Uganda and East Africa grow through custom software, cloud infrastructure, networking, hardware servicing, IT consulting and ICT training.', 'general', 'Site meta description', 'textarea', 1],
            ['hero_headline',       'Technology That Moves Your Business Forward.', 'general', 'Hero headline', 'text', 2],
            ['hero_subtext',        'Ephraitech Unified Solutions designs custom software, builds reliable cloud and network infrastructure, services hardware, advises on IT strategy and trains teams, so organizations across East Africa can operate efficiently and compete with confidence.', 'general', 'Hero supporting text', 'textarea', 3],
            ['about_intro',         'Ephraitech Unified Solutions is an ICT company that helps businesses, schools and organizations use technology to grow, operate efficiently and compete. We bring software, cloud, networking, hardware, consulting and training together as one unified technology partner.', 'general', 'About introduction', 'textarea', 4],
            ['footer_description',  'An ICT company helping businesses, schools and organizations in Uganda and East Africa use technology to grow, operate efficiently and compete. One technology partner, multiple solutions.', 'general', 'Footer description', 'textarea', 5],
            ['logo_path',           '', 'general', 'Logo file name', 'text', 6],
            ['about_image_path',    '', 'general', 'Homepage about image file name', 'text', 7],
            ['visible_pages',       'about,services,portfolio,training,quote,contact', 'general', 'Visible pages', 'text', 8],
            ['contact_phone',       '', 'contact', 'Phone number', 'tel', 1],
            ['contact_whatsapp',    '', 'contact', 'WhatsApp number', 'tel', 2],
            ['contact_email',       'info@ephraitech.com', 'contact', 'Email address', 'email', 3],
            ['contact_location',    '', 'contact', 'Office location', 'text', 4],
            ['business_hours',      '', 'contact', 'Business hours', 'text', 5],
            ['service_coverage',    '', 'contact', 'Service coverage', 'text', 6],
            ['map_embed_url',       '', 'contact', 'Google Maps embed URL', 'url', 7],
            ['social_facebook',     '', 'social', 'Facebook URL', 'url', 1],
            ['social_twitter',      '', 'social', 'X / Twitter URL', 'url', 2],
            ['social_linkedin',     '', 'social', 'LinkedIn URL', 'url', 3],
            ['social_instagram',    '', 'social', 'Instagram URL', 'url', 4],
            ['social_youtube',      '', 'social', 'YouTube URL', 'url', 5],
            ['social_whatsapp',     '', 'social', 'WhatsApp URL', 'url', 6],
            ['stat_1_value',        '', 'statistics', 'Statistic 1 value', 'text', 1],
            ['stat_1_suffix',       '+', 'statistics', 'Statistic 1 suffix', 'text', 2],
            ['stat_1_label',        'Projects Delivered', 'statistics', 'Statistic 1 label', 'text', 3],
            ['stat_2_value',        '', 'statistics', 'Statistic 2 value', 'text', 4],
            ['stat_2_suffix',       '+', 'statistics', 'Statistic 2 suffix', 'text', 5],
            ['stat_2_label',        'Years of Experience', 'statistics', 'Statistic 2 label', 'text', 6],
            ['stat_3_value',        '', 'statistics', 'Statistic 3 value', 'text', 7],
            ['stat_3_suffix',       '+', 'statistics', 'Statistic 3 suffix', 'text', 8],
            ['stat_3_label',        'Clients Supported', 'statistics', 'Statistic 3 label', 'text', 9],
            ['stat_4_value',        '6', 'statistics', 'Statistic 4 value', 'text', 10],
            ['stat_4_suffix',       '', 'statistics', 'Statistic 4 suffix', 'text', 11],
            ['stat_4_label',        'Unified Service Areas', 'statistics', 'Statistic 4 label', 'text', 12],
        ];

        foreach ($settings as [$key, $value, $group, $label, $type, $order]) {
            $this->db->table('site_settings')->insert([
                'setting_key'   => $key,
                'setting_value' => $value,
                'setting_group' => $group,
                'label'         => $label,
                'input_type'    => $type,
                'sort_order'    => $order,
            ]);
        }

        // ----- Project categories -----
        $categories = [
            ['software',   'Software',   1],
            ['web',        'Web',        2],
            ['networking', 'Networking', 3],
            ['consulting', 'Consulting', 4],
        ];
        foreach ($categories as [$slug, $name, $order]) {
            $this->db->table('project_categories')->insert([
                'slug' => $slug, 'name' => $name, 'sort_order' => $order,
            ]);
        }

        // ----- Services (matches original) -----
        $services = [
            ['custom-software-development', 'Custom Software Development', 'Software',  'fa-solid fa-code',           'blue',  'Purpose-built business software that replaces manual processes with reliable, efficient digital systems designed around how your organization actually works.', 'We design and build custom software for businesses, schools and organizations: management systems, web platforms and internal tools tailored to your operations.', "Business management systems\nWeb applications and platforms\nSchool and institutional systems\nProcess automation\nOngoing maintenance and support", 1],
            ['cloud-infrastructure',        'Cloud Infrastructure',        'Cloud',     'fa-solid fa-cloud',          'light', 'Secure, scalable cloud environments that keep your applications, data and teams connected and available wherever work happens.', 'We plan, set up and manage cloud infrastructure so your organization can host applications, store data safely and scale without heavy upfront hardware investment.', "Cloud setup and migration\nHosting and server management\nBackup and recovery\nSecurity and access control", 2],
            ['networking',                  'Networking',                  'Network',   'fa-solid fa-network-wired',  'blue',  'Structured, dependable networks for offices, schools and institutions, designed for performance, security and growth.', 'From structured cabling to wireless coverage and network security, we design and install networks that keep your people and systems reliably connected.', "Network design and installation\nStructured cabling\nWireless (Wi-Fi) solutions\nNetwork security and monitoring", 3],
            ['hardware-servicing',          'Hardware Servicing',          'Hardware',  'fa-solid fa-microchip',      'gold',  'Professional computer and hardware servicing that keeps your equipment running and your operations uninterrupted.', 'We diagnose, repair, upgrade and maintain computers and related hardware so downtime stays low and your investment lasts longer.', "Computer repair and upgrades\nPreventive maintenance\nHardware procurement advice\nDevice setup and configuration", 4],
            ['it-consulting',               'IT Consulting',               'Consulting','fa-solid fa-compass',        'light', 'Practical, honest technology advice that aligns your IT decisions with your business goals and budget.', 'We help leaders make confident technology decisions: assessing current systems, planning improvements and guiding implementation with clarity and integrity.', "Technology assessments\nIT strategy and planning\nSystem selection guidance\nDigital transformation support", 5],
            ['ict-training',                'ICT Training',                'Training',  'fa-solid fa-graduation-cap', 'gold',  'Hands-on ICT training that equips individuals and teams with practical, job-ready technology skills.', 'Our training programs cover software development, web development, networking and everyday ICT skills, delivered practically so learners can build and grow.', "Software development\nWeb development\nNetworking fundamentals\nPractical ICT skills", 6],
        ];
        foreach ($services as [$slug, $title, $label, $icon, $accent, $short, $long, $features, $order]) {
            $this->db->table('services')->insert([
                'slug' => $slug, 'title' => $title, 'label' => $label, 'icon' => $icon,
                'accent' => $accent, 'short_description' => $short, 'long_description' => $long,
                'features' => $features, 'sort_order' => $order, 'is_active' => 1,
            ]);
        }

        echo "Essential data seeded.\n";
    }
}