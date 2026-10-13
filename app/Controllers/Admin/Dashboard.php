<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = Database::connect();


   




// $counts = [
//     'messages_unread' => (int) $db->query('SELECT COUNT(*) FROM contact_messages WHERE is_read = 0')->fetchColumn(),
//     'quotes_new'      => (int) $db->query("SELECT COUNT(*) FROM quote_requests WHERE status = 'new'")->fetchColumn(),
//     'projects'        => (int) $db->query('SELECT COUNT(*) FROM projects WHERE is_active = 1')->fetchColumn(),
//     'services'        => (int) $db->query('SELECT COUNT(*) FROM services WHERE is_active = 1')->fetchColumn(),
//     'courses'         => (int) $db->query('SELECT COUNT(*) FROM training_courses WHERE is_active = 1')->fetchColumn(),
// ];
// $recent_quotes = $db->query('SELECT id, first_name, last_name, email, service_needed, budget, status, created_at FROM quote_requests ORDER BY created_at DESC LIMIT 6')->fetchAll();
$missing = array_filter(['contact_phone' => 'Phone', 'contact_email' => 'Email', 'contact_location' => 'Location'], fn($k) => setting($k) === '', ARRAY_FILTER_USE_KEY);




        $stats = [
            'messages_unread' => (int) $db->table('contact_messages')->where('is_read', 0)->countAllResults(),
            'quotes_new'      => (int) $db->table('quote_requests')->where('status', 'new')->countAllResults(),
            'projects'        => (int) $db->table('projects')->where('is_active', 1)->countAllResults(),
            'services'        => (int) $db->table('services')->where('is_active', 1)->countAllResults(),
            'courses'         => (int) $db->table('training_courses')->where('is_active', 1)->countAllResults(),
        ];

        $recent_messages = $db->table('contact_messages')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $recent_quotes = $db->table('quote_requests')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        return view('admin/dashboard', [
            'admin_title'     => 'Dashboard',
            'stat'           => $stats,
            'recent_messages' => $recent_messages,
            'recent_quotes'   => $recent_quotes,
            'missing'         => $missing,
        ]);


    }
}