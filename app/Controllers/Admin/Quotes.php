<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\QuoteModel;

class Quotes extends BaseController
{
    private const PER_PAGE = 20;
    private const STATUSES = ['new', 'contacted', 'in_progress', 'closed'];

    public function index()
    {
        $model  = new QuoteModel();
        $status = (string) ($this->request->getGet('status') ?? '');
        if ($status !== '' && !in_array($status, self::STATUSES, true)) {
            $status = '';
        }

        $page  = max(1, (int) ($this->request->getGet('page') ?? 1));
        $data  = $model->paginateRows($status, $page, self::PER_PAGE);
        $pages = max(1, (int) ceil($data['total'] / self::PER_PAGE));

        return view('admin/quotes/index', [
            'admin_title' => 'Quote Requests',
            'rows'        => $data['rows'],
            'total'       => $data['total'],
            'page'        => $page,
            'pages'       => $pages,
            'statuses'    => self::STATUSES,
            'filter'      => $status,
        ]);
    }

   public function view(int $id)
    {
        $model = new QuoteModel();
        $row   = $model->find($id);

        if (!$row) {
            return redirect()->to('control/quotes')->with('admin_error', 'Quote request not found.');
        }

        return view('admin/quotes/view', [
            'admin_title' => 'Quote #' . $id,
            'quote'       => $row,
            'statuses'    => self::STATUSES,
        ]);
    }

    public function updateStatus()
    {
        $id     = (int) $this->request->getPost('id');
        $status = (string) $this->request->getPost('status');

        if (!in_array($status, self::STATUSES, true)) {
            return redirect()->to('control/quotes')->with('admin_error', 'Invalid status.');
        }

        $model = new QuoteModel();
        if (!$model->find($id)) {
            return redirect()->to('control/quotes')->with('admin_error', 'Quote not found.');
        }

        $model->update($id, ['status' => $status]);
        return redirect()->to('control/quotes')->with('admin_success', 'Status updated.');
    }

    public function delete()
    {
        $id    = (int) $this->request->getPost('id');
        $model = new QuoteModel();

        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to('control/quotes')->with('admin_success', 'Quote request deleted.');
        }

        return redirect()->to('control/quotes')->with('admin_error', 'Quote not found.');
    }
}