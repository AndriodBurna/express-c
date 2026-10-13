<?php

namespace App\Controllers;

class Portfolio extends BaseController
{
   public function index()
{
    return view('portfolio', [
        'page_title'       => 'Portfolio | Software Development Projects Uganda | Ephraitech',
        'page_description' => 'Explore Ephraitech Unified Solutions projects: business software, school management platforms, web platforms and technology solutions built for organizations in Uganda.',
        'body_class'       => 'theme-dark page-portfolio',
        'projects'         => get_projects(),
        'categories'       => get_project_categories(),
        'wide_first'       => true,
    ]);
}

    public function show(string $slug)
    {
        $slug    = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));
        $project = $slug !== '' ? get_project_by_slug($slug) : null;

        if ($project === null) {
            return redirect()->to('portfolio')->with('error', 'Project not found.');
        }

        $tech     = tags($project['technologies'] ?? '');
        $features = lines($project['features'] ?? '');

        $others = array_values(array_filter(get_projects(), fn($p) => $p['id'] !== $project['id']));
        $others = array_slice($others, 0, 2);

       return view('project', [
        'page_title'       => $project['title'] . ' | Case Study | Ephraitech',
        'page_description' => $project['short_description'],
        'body_class'       => 'theme-dark page-project',
        'project'          => $project,
        'tech'             => $tech,
        'features'         => $features,
        'others'           => $others,
        'wide_first'       => false,
        'page_image'       => !empty($project['image']) ? upload_url($project['image']) : null,
        'schema'           => [
            '@context'    => 'https://schema.org',
            '@type'       => 'CreativeWork',
            'name'        => $project['title'],
            'description' => $project['short_description'],
            'url'         => base_url('project/' . $project['slug']),
            'image'       => !empty($project['image']) ? upload_url($project['image']) : null,
            'creator'     => [
                '@type' => 'Organization',
                'name'  => 'Ephraitech Unified Solutions',
                'url'   => base_url('/'),
            ],
            'about' => $project['category_name'] ?? null,
            'dateCreated' => $project['year'] ?? null,
        ],
    ]);
    }
}