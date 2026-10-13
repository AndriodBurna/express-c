<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TrainingModel;

class Training extends BaseController
{
    private array $fields = [
        'title', 'slug', 'icon', 'short_description', 'long_description',
        'topics', 'duration', 'level', 'sort_order', 'is_active',
    ];

    public function index()
    {
        $model = new TrainingModel();

        $edit = null;
        if ($id = (int) $this->request->getGet('edit')) {
            $edit = $model->find($id);
        } elseif ($this->request->getGet('new')) {
            $edit = $this->blank();
        }

        return view('admin/training/index', [
            'admin_title' => 'Training Courses',
            'courses'     => $model->ordered(false),
            'edit'        => $edit,
            'errors'      => session()->getFlashdata('training_errors') ?? [],
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
        $model = new TrainingModel();
        $req   = $this->request;

        $errors = [];
        $d      = [];

        foreach ($this->fields as $f) {
            $d[$f] = (string) ($req->getPost($f) ?? '');
        }

        $d['long_description'] = sanitize_rich_html($req->getPost('long_description') ?? '');
        $d['slug']             = slugify($d['slug'] !== '' ? $d['slug'] : $d['title']);
        $d['icon']             = preg_match('/^fa-[a-z]+ fa-[a-z0-9\-]+$/', $d['icon'])
                                    ? $d['icon']
                                    : 'fa-solid fa-book-open';
        $d['sort_order']       = (int) $d['sort_order'];
        $d['is_active']        = $req->getPost('is_active') ? 1 : 0;

        if (mb_strlen($d['title']) < 2)              $errors[] = 'Title is required.';
        if (mb_strlen($d['short_description']) < 10) $errors[] = 'Short description is required.';
        if ($model->slugInUse($d['slug'], $id))      $errors[] = 'Slug already in use.';

        if ($errors) {
            session()->setFlashdata('training_errors', $errors);
            return redirect()->back();
        }

        $payload = [
            'slug'              => $d['slug'],
            'title'             => $d['title'],
            'icon'              => $d['icon'],
            'short_description' => $d['short_description'],
            'long_description'  => $d['long_description'] ?: null,
            'topics'            => $d['topics'] ?: null,
            'duration'          => $d['duration'] ?: null,
            'level'             => $d['level'] ?: null,
            'sort_order'        => $d['sort_order'],
            'is_active'         => $d['is_active'],
        ];

        if ($id > 0) {
            $model->update($id, $payload);
            return redirect()->to('control/training')->with('admin_success', 'Course updated.');
        }
        $model->insert($payload);
        return redirect()->to('control/training')->with('admin_success', 'Course created.');
    }

    public function delete()
    {
        $id    = (int) $this->request->getPost('id');
        $model = new TrainingModel();

        if (!$model->find($id)) {
            return redirect()->to('control/training')->with('admin_error', 'Course not found.');
        }

        $model->delete($id);
        return redirect()->to('control/training')->with('admin_success', 'Course deleted.');
    }

    private function blank(): array
    {
        return array_fill_keys($this->fields, '') + [
            'id' => 0, 'icon' => 'fa-solid fa-book-open',
            'is_active' => 1, 'sort_order' => 0,
        ];
    }
}