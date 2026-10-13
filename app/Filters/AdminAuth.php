<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Not logged in
        if (!$session->get('admin')) {
            return redirect()->to('control/login');
        }

        // Idle timeout (30 minutes)
        $last = (int) $session->get('admin_last_activity');
        if ($last > 0 && (time() - $last) > 30 * 60) {
            $session->remove(['admin', 'admin_last_activity']);
            $session->setFlashdata('login_error', 'Your session expired. Please log in again.');
            return redirect()->to('control/login');
        }
        $session->set('admin_last_activity', time());

        // Forced password change
        $user = $session->get('admin');
        $path = trim($request->getUri()->getPath(), '/');
        if (!empty($user['must_change_password']) && $path !== 'control/change-password' && $path !== 'control/logout') {
            return redirect()->to('control/change-password');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}