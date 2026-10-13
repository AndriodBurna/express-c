<?php

namespace App\Controllers;

class About extends BaseController
{
    public function index()
{
    return view('about', [
        'page_title'       => 'About Ephraitech | ICT Company in Kampala, Uganda',
        'page_description' => 'Learn about Ephraitech Unified Solutions — an ICT company in Uganda helping businesses, schools and organizations use technology to grow, operate efficiently and compete.',
        'body_class'       => 'theme-dark page-about',
        'schema'           => [
            '@context' => 'https://schema.org',
            '@type'    => 'AboutPage',
            'name'     => 'About Ephraitech Unified Solutions',
            'url'      => base_url('about'),
            'mainEntity' => [
                '@type' => 'Organization',
                'name'  => 'Ephraitech Unified Solutions',
                'url'   => base_url('/'),
            ],
        ],
    ]);
}
}