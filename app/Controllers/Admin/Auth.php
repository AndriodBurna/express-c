<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    private const MAX_ATTEMPTS = 5;
    private const LOCK_MINUTES = 15;

    public function login()
    {
        if (session()->get('admin')) {
            return redirect()->to('control/dashboard');
        }
        return view('admin/login', [
            'error' => session()->getFlashdata('login_error'),
        ]);
    }

    public function attemptLogin()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        if ($username === '' || $password === '') {
            return redirect()->back()->with('login_error', 'Please enter both username and password.');
        }

        $model = new UserModel();
        $user  = $model->findByUsername($username);

        if (!$user) {
            return redirect()->back()->with('login_error', 'Invalid username or password.');
        }

        // Locked?
        if (!empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {
            return redirect()->back()->with('login_error', 'Account temporarily locked. Try again in a few minutes.');
        }

        // Wrong password
        if (!password_verify($password, $user['password_hash'])) {
            $attempts = (int) $user['failed_attempts'] + 1;
            $lock     = $attempts >= self::MAX_ATTEMPTS ? date('Y-m-d H:i:s', time() + self::LOCK_MINUTES * 60) : null;
            $model->update($user['id'], [
                'failed_attempts' => $lock ? 0 : $attempts,
                'locked_until'    => $lock,
            ]);
            return redirect()->back()->with('login_error', 'Invalid username or password.');
        }

        // Success
        if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
            $model->update($user['id'], ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        }
        $model->update($user['id'], [
            'failed_attempts' => 0,
            'locked_until'    => null,
            'last_login_at'   => date('Y-m-d H:i:s'),
        ]);

        session()->regenerate(true);
        session()->set('admin', [
            'id'                   => (int) $user['id'],
            'username'             => $user['username'],
            'full_name'            => $user['full_name'],
            'role'                 => $user['role'],
            'must_change_password' => (int) $user['must_change_password'],
        ]);
        session()->set('admin_last_activity', time());

        return redirect()->to('control/dashboard');
    }

    public function logout()
    {
        session()->remove(['admin', 'admin_last_activity']);
        session()->regenerate(true);
        return redirect()->to('control/login');
    }

    public function changePassword()
    {
        return view('admin/change_password', [
            'error'   => session()->getFlashdata('pw_error'),
            'success' => session()->getFlashdata('pw_success'),
            'forced'  => !empty(session()->get('admin')['must_change_password']),
        ]);
    }

    public function savePassword()
    {
        $current = (string) $this->request->getPost('current_password');
        $new     = (string) $this->request->getPost('new_password');
        $confirm = (string) $this->request->getPost('confirm_password');

        $session = session();
        $admin   = $session->get('admin');
        $model   = new UserModel();
        $user    = $model->find($admin['id']);

        if (!$user || !password_verify($current, $user['password_hash'])) {
            return redirect()->back()->with('pw_error', 'Current password is incorrect.');
        }
        if (strlen($new) < 10
            || !preg_match('/[A-Z]/', $new)
            || !preg_match('/[a-z]/', $new)
            || !preg_match('/[0-9]/', $new)
        ) {
            return redirect()->back()->with('pw_error', 'Password must be at least 10 characters with uppercase, lowercase and a number.');
        }
        if ($new !== $confirm) {
            return redirect()->back()->with('pw_error', 'New passwords do not match.');
        }

        $model->update($user['id'], [
            'password_hash'        => password_hash($new, PASSWORD_DEFAULT),
            'must_change_password' => 0,
        ]);

        // Update session
        $admin['must_change_password'] = 0;
        $session->set('admin', $admin);

        return redirect()->to('control/dashboard')->with('admin_success', 'Password updated successfully.');
    }
}