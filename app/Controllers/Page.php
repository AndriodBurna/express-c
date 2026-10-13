<?php

namespace App\Controllers;

use App\Models\PageModel;

class Page extends BaseController
{
    public function show(string $slug)
    {
        $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));
        $page = $slug !== '' ? (new PageModel())->findBySlug($slug) : null;

        if ($page === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('page', [
            'page_title'       => $page['title'] . ' | Ephraitech Unified Solutions',
            'page_description' => mb_substr(strip_tags($page['content'] ?? ''), 0, 155),
            'body_class'       => 'theme-dark page-custom',
            'page'             => $page,
        ]);
    }
}