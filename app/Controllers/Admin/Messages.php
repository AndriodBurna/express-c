<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MessageModel;

class Messages extends BaseController
{
    private const PER_PAGE = 20;

    public function index()
    {
        $model = new MessageModel();

        $page  = max(1, (int) ($this->request->getGet('page') ?? 1));
        $data  = $model->paginateRows($page, self::PER_PAGE);
        $pages = max(1, (int) ceil($data['total'] / self::PER_PAGE));

        return view('admin/messages/index', [
            'admin_title' => 'Contact Messages',
            'rows'        => $data['rows'],
            'total'       => $data['total'],
            'page'        => $page,
            'pages'       => $pages,
        ]);
    }

   public function view(int $id)
    {
        $model = new MessageModel();
        $row   = $model->find($id);

        if (!$row) {
            return redirect()->to('control/messages')->with('admin_error', 'Message not found.');
        }

        // Mark as read automatically
        if (empty($row['is_read'])) {
            $model->markRead($id, true);
            $row['is_read'] = 1;
        }

        return view('admin/messages/view', [
            'admin_title' => 'Message #' . $id,
            'message'     => $row,
        ]);
    }

    public function toggleRead()
    {
        $id     = (int) $this->request->getPost('id');
        $model  = new MessageModel();
        $row    = $model->find($id);

        if (!$row) {
            return redirect()->to('control/messages')->with('admin_error', 'Message not found.');
        }

        $model->update($id, ['is_read' => $row['is_read'] ? 0 : 1]);

        return redirect()->to('control/messages')->with('admin_success', 'Message updated.');
    }

    public function delete()
    {
        $id    = (int) $this->request->getPost('id');
        $model = new MessageModel();

        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to('control/messages')->with('admin_success', 'Message deleted.');
        }

        return redirect()->to('control/messages')->with('admin_error', 'Message not found.');
    }
}