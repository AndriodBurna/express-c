<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Settings extends BaseController
{
    private const GROUP_TITLES = [
        'general'    => 'General Content',
        'contact'    => 'Contact Information',
        'social'     => 'Social Media (only real accounts)',
        'statistics' => 'Statistics (leave value empty to hide)',
    ];

    public function index()
    {
        $model = new SettingModel();

        // Ensure visible_pages exists
        $model->insertIfMissing(
            'visible_pages',
            'about,services,portfolio,quote,contact,training',
            'general',
            'Visible pages',
            'text',
            8
        );

        return view('admin/settings/index', [
            'admin_title'   => 'Website Settings',
            'groups'        => $model->grouped(),
            'group_titles'  => self::GROUP_TITLES,
            'errors'        => session()->getFlashdata('settings_errors') ?? [],
        ]);
    }

    public function save()
    {
        $model = new SettingModel();
        $req   = $this->request;

        $errors = [];
        $rows   = $model->findAll();

        // ---- File uploads ------------------------------------------------
        foreach ([
            'logo_file'        => 'logo_path',
            'about_image_file' => 'about_image_path',
        ] as $field => $key) {
            $uploaded = $req->getFile($field);
            if ($uploaded && $uploaded->isValid() && !$uploaded->hasMoved()) {
                $err  = null;
                $file = handle_image_upload($field, $err);
                if ($err) {
                    $errors[] = $err;
                } elseif ($file) {
                    $model->setValue($key, $file);
                }
            }
        }

        // ---- Visible pages (multi-checkbox) ------------------------------
        $selected = $req->getPost('visible_pages');
        $selected = is_array($selected) ? $selected : [];
        $selected = array_values(array_filter(array_map(
            fn($v) => strtolower(trim((string) $v)),
            $selected
        )));
        $model->setValue('visible_pages', implode(',', $selected));

        // ---- All other settings -----------------------------------------
        foreach ($rows as $row) {
            $key = $row['setting_key'];
            if ($key === 'visible_pages') continue;
            if (!$req->getPost($key) && $req->getPost($key) !== '') {
                // Not in POST at all → skip
                if (!array_key_exists($key, $_POST)) continue;
            }
            if (!array_key_exists($key, $_POST)) continue;

            $value = clean_text((string) $req->getPost($key), 5000);

            if ($value !== '') {
                $type = $row['input_type'];
                if ($type === 'email' && !valid_email($value)) {
                    $errors[] = "Invalid email for {$key}.";
                    continue;
                }
                if ($type === 'url' && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $errors[] = "Invalid URL for {$key}.";
                    continue;
                }
                if (str_starts_with($key, 'stat_') && str_ends_with($key, '_value')
                    && !preg_match('/^[0-9]+(\.[0-9]+)?$/', $value)) {
                    $errors[] = "Statistic values must be numbers only ({$key}).";
                    continue;
                }
            }

            $model->setValue($key, $value);
        }

        if ($errors) {
            return redirect()->to('control/settings')->with('settings_errors', $errors);
        }
        return redirect()->to('control/settings')->with('admin_success', 'Settings saved.');
    }
}