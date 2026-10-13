<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProjectModel;
use App\Models\CategoryModel;

class Projects extends BaseController
{
    private array $fields = [
        'title', 'slug', 'category_id', 'client_name', 'short_description',
        'overview', 'problem', 'challenge', 'solution', 'technologies',
        'features', 'results', 'project_url', 'year', 'sort_order',
        'is_featured', 'is_active',
    ];

    public function index()
    {
        $model = new ProjectModel();
        return view('admin/projects/index', [
            'admin_title' => 'Projects',
            'projects'    => $model->withCategory(),
        ]);
    }

    public function create()
    {
        $catModel = new CategoryModel();
        return view('admin/projects/form', [
            'admin_title' => 'New Project',
            'edit'        => $this->blank(),
            'categories'  => $catModel->ordered(),
            'errors'      => [],
        ]);
    }

    public function edit(int $id)
    {
        $model = new ProjectModel();
        $row   = $model->find($id);
        if (!$row) {
            return redirect()->to('control/projects')->with('admin_error', 'Project not found.');
        }
        $catModel = new CategoryModel();
        return view('admin/projects/form', [
            'admin_title' => 'Edit Project',
            'edit'        => $row,
            'categories'  => $catModel->ordered(),
            'errors'      => [],
        ]);
    }

    public function store()
    {
        return $this->save(0);
    }

    public function update(int $id)
    {
        return $this->save($id);
    }

    private function save(int $id)
    {
        $model    = new ProjectModel();
        $catModel = new CategoryModel();
        $req      = $this->request;

        $errors = [];
        $d      = [];

        foreach ($this->fields as $f) {
            $d[$f] = (string) ($req->getPost($f) ?? '');
        }

        // Rich text fields: keep safe formatting
        foreach (['overview', 'problem', 'challenge', 'solution', 'results'] as $rich) {
            $d[$rich] = sanitize_rich_html($req->getPost($rich) ?? '');
        }

        $d['title']             = clean_text($d['title'], 255);
        $d['slug']              = slugify($d['slug'] !== '' ? $d['slug'] : $d['title']);
        $d['short_description'] = clean_text($d['short_description'], 400);
        $d['sort_order']        = (int) $d['sort_order'];
        $d['is_featured']       = $req->getPost('is_featured') ? 1 : 0;
        $d['is_active']         = $req->getPost('is_active') ? 1 : 0;

        $catIds            = array_map(fn($c) => (int) $c['id'], $catModel->findAll());
        $d['category_id']  = in_array((int) $d['category_id'], $catIds, true) ? (int) $d['category_id'] : null;

        // Validation
        if (mb_strlen($d['title']) < 2)             $errors[] = 'Title is required.';
        if ($d['slug'] === '')                      $errors[] = 'Slug is required.';
        if (mb_strlen($d['short_description']) < 10) $errors[] = 'Short description is required (min 10 characters).';
        if ($d['project_url'] !== '' && !filter_var($d['project_url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Project URL is invalid.';
        }
        if ($model->slugInUse($d['slug'], $id)) {
            $errors[] = 'Slug already in use.';
        }

        // Image
        $uploadErr = null;
        $newImage  = handle_image_upload('image', $uploadErr);
        if ($uploadErr) $errors[] = $uploadErr;

        if ($errors) {
            return view('admin/projects/form', [
                'admin_title' => $id ? 'Edit Project' : 'New Project',
                'edit'        => array_merge($d, ['id' => $id, 'image' => null]),
                'categories'  => $catModel->ordered(),
                'errors'      => $errors,
            ]);
        }

        $nullable = fn($v) => ($v === '' || $v === null) ? null : $v;

        $payload = [
            'category_id'       => $d['category_id'],
            'slug'              => $d['slug'],
            'title'             => $d['title'],
            'client_name'       => $nullable($d['client_name']),
            'short_description' => $d['short_description'],
            'overview'          => $nullable($d['overview']),
            'problem'           => $nullable($d['problem']),
            'challenge'         => $nullable($d['challenge']),
            'solution'          => $nullable($d['solution']),
            'technologies'      => $nullable($d['technologies']),
            'features'          => $nullable($d['features']),
            'results'           => $nullable($d['results']),
            'project_url'       => $nullable($d['project_url']),
            'year'              => $nullable($d['year']),
            'is_featured'       => $d['is_featured'],
            'is_active'         => $d['is_active'],
            'sort_order'        => $d['sort_order'],
        ];

        if ($newImage) {
            $payload['image'] = $newImage;
        }

        if ($id > 0) {
            // Delete old image if a new one was uploaded
            if ($newImage) {
                $old = $model->find($id);
                if (!empty($old['image']) && is_file(FCPATH . 'uploads/' . $old['image'])) {
                    @unlink(FCPATH . 'uploads/' . $old['image']);
                }
            }
            $model->update($id, $payload);
            return redirect()->to('control/projects')->with('admin_success', 'Project updated.');
        }

        $model->insert($payload);
        return redirect()->to('control/projects')->with('admin_success', 'Project created.');
    }

    public function delete()
    {
        $id    = (int) $this->request->getPost('id');
        $model = new ProjectModel();

        $row = $model->find($id);
        if (!$row) {
            return redirect()->to('control/projects')->with('admin_error', 'Project not found.');
        }

        if (!empty($row['image']) && is_file(FCPATH . 'uploads/' . $row['image'])) {
            @unlink(FCPATH . 'uploads/' . $row['image']);
        }

        $model->delete($id);
        return redirect()->to('control/projects')->with('admin_success', 'Project deleted.');
    }

    private function blank(): array
    {
        return array_fill_keys($this->fields, '') + [
            'id' => 0, 'image' => null, 'is_active' => 1, 'is_featured' => 0, 'sort_order' => 0,
        ];
    }
}