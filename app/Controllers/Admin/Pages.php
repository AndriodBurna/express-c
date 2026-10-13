<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PageModel;

class Pages extends BaseController
{
    public function index()
    {
        $model = new PageModel();
        return view('admin/pages/index', [
            'page_title' => 'Pages',
            'pages'      => $model->orderBy('sort_order', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('admin/pages/form', [
            'page_title' => 'New Page',
            'page'       => null,
        ]);
    }

    public function store()
    {
        $rules = [
            'title' => 'required|min_length[2]|max_length[150]',
            'slug'  => 'permit_empty|alpha_dash|max_length[120]|is_unique[pages.slug]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $slug = $this->request->getPost('slug') ?: slugify((string) $this->request->getPost('title'));

        $model = new PageModel();
        $model->insert([
            'title'       => (string) $this->request->getPost('title'),
            'slug'        => $slug,
            'content'     => (string) $this->request->getPost('content'),
            'sort_order'  => (int) $this->request->getPost('sort_order'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('control/pages')->with('success', 'Page created.');
    }

    public function edit(int $id)
    {
        $model = new PageModel();
        $page  = $model->find($id);
        if (!$page) {
            return redirect()->to('control/pages')->with('error', 'Page not found.');
        }
        return view('admin/pages/form', [
            'page_title' => 'Edit Page',
            'page'       => $page,
        ]);
    }

    public function update(int $id)
    {
        $model = new PageModel();
        $page  = $model->find($id);
        if (!$page) {
            return redirect()->to('control/pages')->with('error', 'Page not found.');
        }

        $rules = [
            'title' => 'required|min_length[2]|max_length[150]',
            'slug'  => "permit_empty|alpha_dash|max_length[120]|is_unique[pages.slug,id,{$id}]",
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $slug = $this->request->getPost('slug') ?: slugify((string) $this->request->getPost('title'));

        $model->update($id, [
            'title'       => (string) $this->request->getPost('title'),
            'slug'        => $slug,
            'content'     => (string) $this->request->getPost('content'),
            'sort_order'  => (int) $this->request->getPost('sort_order'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('control/pages')->with('success', 'Page updated.');
    }

    public function delete(int $id)
    {
        (new PageModel())->delete($id);
        return redirect()->to('control/pages')->with('success', 'Page deleted.');
    }
}