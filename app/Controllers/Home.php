<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
{
    return view('home', [
        'page_title'       => '', // Homepage uses default below
        'page_description' => 'Ephraitech Unified Solutions is an ICT company in Uganda offering custom software development, cloud infrastructure, networking, hardware servicing and IT consulting for businesses, schools and organizations.',
        'schema'           => $this->organizationSchema(),
    ]);
}

private function organizationSchema(): array
{
    return [
        '@context'    => 'https://schema.org',
        '@type'       => 'LocalBusiness',
        '@id'         => base_url('/') . '#organization',
        'name'        => 'Ephraitech Unified Solutions',
        'alternateName' => 'Ephraitech',
        'url'         => base_url('/'),
        'logo'        => base_url('assets/images/logo%20no%20bg.png'),
        'image'       => base_url('assets/images/og-default.png'),
        'description' => setting('site_tagline', 'An ICT company helping businesses in Uganda and East Africa use technology to grow.'),
        'telephone'   => setting('contact_phone') ?: null,
        'email'       => setting('contact_email') ?: null,
        'address'     => [
            '@type'           => 'PostalAddress',
            'addressCountry'  => 'UG',
            'addressLocality' => 'Kampala',
            'streetAddress'   => setting('contact_location') ?: 'Kampala, Uganda',
        ],
        'geo' => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => 0.3476,
            'longitude' => 32.5825,
        ],
        'openingHours' => setting('business_hours') ?: null,
        'priceRange'   => '$$',
        'areaServed'   => ['Uganda', 'East Africa'],
        'sameAs'       => array_map(fn($s) => $s['url'], get_social_links()),
    ];
}
}