<?php

namespace App\Controllers;

class Sitemap extends BaseController
{
    public function index()
    {
        $static = [
            ''          => '1.0',
            'about'     => '0.8',
            'services'  => '0.9',
            'portfolio' => '0.8',
            'contact'   => '0.7',
        ];
        return response()
            ->setContentType('application/xml')
            ->setBody(view('sitemap', [
                'static'   => $static,
                'projects' => get_projects(),
            ]));
    }
}