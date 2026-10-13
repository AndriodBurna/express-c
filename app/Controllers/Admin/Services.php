<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServiceModel;

class Services extends BaseController
{
    private const ACCENTS = ['blue', 'light', 'gold'];

    private array $fields = [
        'title', 'slug', 'label', 'icon', 'accent',
        'short_description', 'long_description', 'features',
        'sort_order', 'is_active',
    ];

    public function index()
    {
        $model = new ServiceModel();

        // Modal editor: detect ?edit=ID or ?new=1
        $edit = null;
        if ($id = (int) $this->request->getGet('edit')) {
            $edit = $model->find($id);
        } elseif ($this->request->getGet('new')) {
            $edit = $this->blank();
        }

        return view('admin/services/index', [
            'admin_title' => 'Services',
            'services'    => $model->ordered(false),
            'edit'        => $edit,
            'accents'     => self::ACCENTS,
            'errors'      => session()->getFlashdata('service_errors') ?? [],
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
        $model = new ServiceModel();
        $req   = $this->request;

        $errors = [];
        $d      = [];

        foreach ($this->fields as $f) {
            $d[$f] = (string) ($req->getPost($f) ?? '');
        }

        $d['long_description'] = sanitize_rich_html($req->getPost('long_description') ?? '');
        $d['slug']             = slugify($d['slug'] !== '' ? $d['slug'] : $d['title']);
        $d['accent']           = in_array($d['accent'], self::ACCENTS, true) ? $d['accent'] : 'blue';
        $d['icon']             = preg_match('/^fa-[a-z]+ fa-[a-z0-9\-]+$/', $d['icon'])
                                    ? $d['icon']
                                    : 'fa-solid fa-cube';
        $d['sort_order']       = (int) $d['sort_order'];
        $d['is_active']        = $req->getPost('is_active') ? 1 : 0;

        if (mb_strlen($d['title']) < 2)                $errors[] = 'Title is required.';
        if (mb_strlen($d['short_description']) < 10)   $errors[] = 'Short description is required.';
        if ($model->slugInUse($d['slug'], $id))        $errors[] = 'Slug already in use.';

        if ($errors) {
            session()->setFlashdata('service_errors', $errors);
            return redirect()->back();
        }

        $payload = [
            'slug'              => $d['slug'],
            'title'             => $d['title'],
            'label'             => $d['label'] ?: null,
            'icon'              => $d['icon'],
            'accent'            => $d['accent'],
            'short_description' => $d['short_description'],
            'long_description'  => $d['long_description'] ?: null,
            'features'          => $d['features'] ?: null,
            'sort_order'        => $d['sort_order'],
            'is_active'         => $d['is_active'],
        ];

        if ($id > 0) {
            $model->update($id, $payload);
            return redirect()->to('control/services')->with('admin_success', 'Service updated.');
        }
        $model->insert($payload);
        return redirect()->to('control/services')->with('admin_success', 'Service created.');
    }

    public function delete()
    {
        $id    = (int) $this->request->getPost('id');
        $model = new ServiceModel();

        if (!$model->find($id)) {
            return redirect()->to('control/services')->with('admin_error', 'Service not found.');
        }

        $model->delete($id);
        return redirect()->to('control/services')->with('admin_success', 'Service deleted.');
    }

    private function blank(): array
    {
        return array_fill_keys($this->fields, '') + [
            'id' => 0, 'accent' => 'blue', 'icon' => 'fa-solid fa-cube',
            'is_active' => 1, 'sort_order' => 0,
        ];
    }
}