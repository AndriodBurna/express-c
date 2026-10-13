<?php

namespace App\Controllers;

class Services extends BaseController
{
    public function index()
{
    return view('services', [
        'page_title'       => 'IT Services in Uganda | Software, Cloud, Networking | Ephraitech',
        'page_description' => 'Ephraitech offers custom software development, cloud infrastructure, networking services in Kampala, hardware servicing, computer repair and IT consulting across Uganda.',
        'body_class'       => 'theme-dark page-services',
        'services'         => get_services(),
        'schema'           => [
            '@context' => 'https://schema.org',
            '@type'    => 'ItemList',
            'name'     => 'IT Services by Ephraitech',
            'url'      => base_url('services'),
            'itemListElement' => array_map(function ($s, $i) {
                return [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'item'     => [
                        '@type'       => 'Service',
                        'name'        => $s['title'],
                        'description' => $s['short_description'],
                        'url'         => base_url('services#' . $s['slug']),
                        'provider'    => ['@type' => 'Organization', 'name' => 'Ephraitech Unified Solutions'],
                    ],
                ];
            }, get_services(), array_keys(get_services())),
        ],
    ]);
}
}