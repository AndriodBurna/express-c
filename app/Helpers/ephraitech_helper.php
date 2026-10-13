<?php

/**
 * Ephraitech global helper functions.
 * Direct CI4 port of the old includes/functions.php
 */

use Config\Database;

/* -------------------------------------------------------------------------
 | Output escaping
 |------------------------------------------------------------------------- */
if (!function_exists('e')) {
    function e(?string $value): string
    {
        return esc($value ?? '', 'html');
    }
}

if (!function_exists('paragraphs')) {
    function paragraphs(?string $text): string
    {
        $parts = preg_split('/\R{2,}/', trim((string) $text)) ?: [];
        $html = '';
        foreach ($parts as $p) {
            if ($p === '') continue;
            $html .= '<p>' . nl2br(e($p)) . '</p>';
        }
        return $html;
    }
}

if (!function_exists('lines')) {
    function lines(?string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $text) ?: []), 'strlen'));
    }
}

if (!function_exists('tags')) {
    function tags(?string $text): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $text)), 'strlen'));
    }
}

/* -------------------------------------------------------------------------
 | Rich text (from admin WYSIWYG)
 |------------------------------------------------------------------------- */
if (!function_exists('rt_clean_nodes')) {
    function rt_clean_nodes(DOMNode $parent, array $allowed): void
    {
        for ($child = $parent->firstChild; $child !== null; ) {
            $next = $child->nextSibling;
            if ($child instanceof DOMElement) {
                $tag = strtolower($child->nodeName);
                if (!isset($allowed[$tag])) {
                    rt_clean_nodes($child, $allowed);
                    while ($child->firstChild !== null) {
                        $parent->insertBefore($child->firstChild, $child);
                    }
                    $parent->removeChild($child);
                } else {
                    for ($a = $child->attributes->length - 1; $a >= 0; $a--) {
                        $attr = $child->attributes->item($a);
                        if (!in_array(strtolower($attr->nodeName), $allowed[$tag], true)) {
                            $child->removeAttributeNode($attr);
                        }
                    }
                    if ($tag === 'a') {
                        $href = trim($child->getAttribute('href'));
                        if ($href === '' || preg_match('#^\s*(javascript|vbscript|data)\s*:#i', $href)) {
                            $child->removeAttribute('href');
                        } else {
                            $child->setAttribute('rel', 'noopener');
                        }
                        if (strtolower($child->getAttribute('target')) !== '_blank') {
                            $child->removeAttribute('target');
                        }
                    }
                    rt_clean_nodes($child, $allowed);
                }
            } elseif (!$child instanceof DOMText) {
                $parent->removeChild($child);
            }
            $child = $next;
        }
    }
}

if (!function_exists('sanitize_rich_html')) {
    function sanitize_rich_html(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') return '';

        $allowed = [
            'p' => [], 'br' => [], 'b' => [], 'strong' => [], 'i' => [], 'em' => [],
            'u' => [], 's' => [], 'ul' => [], 'ol' => [], 'li' => [], 'h3' => [], 'h4' => [],
            'blockquote' => [],
            'a' => ['href', 'title', 'target'],
        ];

        if (!class_exists('DOMDocument')) {
            $stripped = strip_tags($html, '<p><br><b><strong><i><em><u><s><ul><ol><li><h3><h4><blockquote><a>');
            return preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $stripped) ?? '';
        }

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>' . $html . '</body></html>');
        libxml_clear_errors();

        $body = $doc->getElementsByTagName('body')->item(0);
        if ($body === null) return '';
        rt_clean_nodes($body, $allowed);

        $out = '';
        foreach ($body->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }
        return trim($out);
    }
}

if (!function_exists('rich_text')) {
    function rich_text(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '') return '';
        if (preg_match('/<\s*\/?\s*(p|br|ul|ol|li|h[1-6]|strong|b|em|i|u|s|blockquote|a|div|span)\b/i', $value)) {
            return sanitize_rich_html($value);
        }
        return paragraphs($value);
    }
}

/* -------------------------------------------------------------------------
 | URL helpers
 |------------------------------------------------------------------------- */
if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        return base_url(ltrim($path, '/'));
    }
}

if (!function_exists('admin_url')) {
    function admin_url(string $path = ''): string
    {
        return base_url('control/' . ltrim($path, '/'));
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $version = '1.1.1';
        return base_url('assets/' . ltrim($path, '/')) . '?v=' . $version;
    }
}

if (!function_exists('upload_url')) {
    function upload_url(?string $filename): string
    {
        if ($filename === null || trim($filename) === '') {
            return '';
        }
        return base_url('uploads/' . ltrim($filename, '/'));
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
        return trim($text, '-');
    }
}

if (!function_exists('phone_digits')) {
    function phone_digits(string $phone): string
    {
        return preg_replace('/[^0-9+]/', '', $phone) ?? '';
    }
}

/* -------------------------------------------------------------------------
 | Site settings (cached per request)
 |------------------------------------------------------------------------- */
if (!function_exists('settings')) {
    function settings(): array
    {
        static $cache = null;
        if ($cache === null) {
            $cache = [];
            try {
                $rows = Database::connect()
                    ->table('site_settings')
                    ->select('setting_key, setting_value')
                    ->get()
                    ->getResultArray();
                foreach ($rows as $row) {
                    $cache[$row['setting_key']] = $row['setting_value'];
                }
            } catch (\Throwable $e) {
                // table missing — return empty
            }
        }
        return $cache;
    }
}

if (!function_exists('setting')) {
    function setting(string $key, string $default = ''): string
    {
        $value = settings()[$key] ?? '';
        return trim((string) $value) !== '' ? (string) $value : $default;
    }
}

if (!function_exists('visible_page_keys')) {
    function visible_page_keys(): array
    {
        $raw = strtolower(trim((string) setting('visible_pages', 'about,services,portfolio,quote,contact,training')));
        if ($raw === '') return [];
        $keys = array_map('trim', explode(',', $raw));
        return array_values(array_filter($keys, fn($v) => $v !== ''));
    }
}

if (!function_exists('page_visible')) {
    function page_visible(string $key): bool
    {
        $key = strtolower(trim($key));
        if ($key === '') return true;
        $visible = visible_page_keys();
        if ($visible === []) return true;
        return in_array($key, $visible, true);
    }
}

/* -------------------------------------------------------------------------
 | Data access (services, projects, categories, training, pages)
 |------------------------------------------------------------------------- */
if (!function_exists('get_services')) {
    function get_services(bool $activeOnly = true): array
    {
        $db = Database::connect();
        $sql = 'SELECT * FROM services' . ($activeOnly ? " WHERE is_active = 1 AND slug <> 'ict-training'" : '') . ' ORDER BY sort_order, id';
        return $db->query($sql)->getResultArray();
    }
}

if (!function_exists('get_service_by_slug')) {
    function get_service_by_slug(string $slug): ?array
    {
        $db = Database::connect();
        $row = $db->table('services')->where('slug', $slug)->where('is_active', 1)->limit(1)->get()->getRowArray();
        return $row ?: null;
    }
}

if (!function_exists('get_project_categories')) {
    function get_project_categories(): array
    {
        return Database::connect()->query('SELECT * FROM project_categories ORDER BY sort_order, id')->getResultArray();
    }
}

if (!function_exists('get_projects')) {
    function get_projects(bool $featuredOnly = false, ?int $limit = null): array
    {
        $db = Database::connect();
        $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug
                FROM projects p
                LEFT JOIN project_categories c ON c.id = p.category_id
                WHERE p.is_active = 1' . ($featuredOnly ? ' AND p.is_featured = 1' : '') . '
                ORDER BY p.sort_order, p.id';
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        return $db->query($sql)->getResultArray();
    }
}

if (!function_exists('get_project_by_slug')) {
    function get_project_by_slug(string $slug): ?array
    {
        $db = Database::connect();
        $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug
                FROM projects p
                LEFT JOIN project_categories c ON c.id = p.category_id
                WHERE p.slug = ? AND p.is_active = 1 LIMIT 1';
        return $db->query($sql, [$slug])->getRowArray() ?: null;
    }
}

if (!function_exists('get_training_courses')) {
    function get_training_courses(bool $activeOnly = true): array
    {
        $db = Database::connect();
        $sql = 'SELECT * FROM training_courses' . ($activeOnly ? ' WHERE is_active = 1' : '') . ' ORDER BY sort_order, id';
        return $db->query($sql)->getResultArray();
    }
}

if (!function_exists('get_pages')) {
    function get_pages(bool $featuredOnly = false): array
    {
        try {
            $db = Database::connect();
            $sql = 'SELECT * FROM pages' . ($featuredOnly ? ' WHERE is_featured = 1' : '') . ' ORDER BY sort_order, id';
            return $db->query($sql)->getResultArray();
        } catch (\Throwable $e) {
            return [];
        }
    }
}

if (!function_exists('default_service_image')) {
    function default_service_image(string $slug): string
    {
        $map = [
            'custom-software-development' => 'customsoftware.jpg',
            'cloud-infrastructure' => 'cloud1.jpg',
            'networking' => 'network.jpg',
            'hardware-servicing' => 'hardware.jpg',
            'it-consulting' => 'consulting.jpg',
            'ict-training' => 'techwithpurpose.jpg',
        ];
        return $map[$slug] ?? '';
    }
}

if (!function_exists('default_project_image')) {
    function default_project_image(string $slug): string
    {
        $map = [
            'hr-staff-management-system' => 'hr_system.jpg',
            'mission-management-system' => 'mission.jpg',
            'hardware-management-system' => 'hardware.jpg',
            'ephraschool' => 'techwithpurpose.jpg',
            'real-estate-platform' => 'real_estate.jpg',
            'myfinance' => 'customsoftware.jpg',
        ];
        return $map[$slug] ?? '';
    }
}

if (!function_exists('get_statistics')) {
    function get_statistics(): array
    {
        $stats = [];
        for ($i = 1; $i <= 4; $i++) {
            $value = setting("stat_{$i}_value");
            $label = setting("stat_{$i}_label");
            if ($value !== '' && $label !== '') {
                $stats[] = ['value' => $value, 'label' => $label, 'suffix' => setting("stat_{$i}_suffix")];
            }
        }
        return $stats;
    }
}

if (!function_exists('get_social_links')) {
    function get_social_links(): array
    {
        $map = [
            'social_facebook'  => ['icon' => 'fa-brands fa-facebook-f', 'label' => 'Facebook'],
            'social_twitter'   => ['icon' => 'fa-brands fa-x-twitter', 'label' => 'X (Twitter)'],
            'social_linkedin'  => ['icon' => 'fa-brands fa-linkedin-in', 'label' => 'LinkedIn'],
            'social_instagram' => ['icon' => 'fa-brands fa-instagram', 'label' => 'Instagram'],
            'social_youtube'   => ['icon' => 'fa-brands fa-youtube', 'label' => 'YouTube'],
            'social_whatsapp'  => ['icon' => 'fa-brands fa-whatsapp', 'label' => 'WhatsApp'],
        ];
        $links = [];
        foreach ($map as $key => $meta) {
            $url = setting($key);
            if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL)) {
                $links[] = $meta + ['url' => $url];
            }
        }
        return $links;
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Render the CSRF hidden input.
     * Uses the token name configured in app/Config/Security.php.
     */
    function csrf_field(): string
    {
        $name = csrf_token();   // returns the token *name* (e.g. "csrf_token")
        $hash = csrf_hash();    // returns the token *value*
        return '<input type="hidden" name="' . $name . '" value="' . $hash . '">';
    }
}

if (!function_exists('csrf_verify')) {
    /**
     * Legacy shim: the CI4 Security filter already validated the token
     * before the controller ran, so we can safely return true.
     */
    function csrf_verify(?string $token = null): bool
    {
        return true;
    }
}

if (!function_exists('post')) {
    function post(string $key, string $default = ''): string
    {
        $value = service('request')->getPost($key);
        return is_string($value) ? trim($value) : $default;
    }
}

if (!function_exists('clean_text')) {
    function clean_text(string $value, int $max = 5000): string
    {
        $value = strip_tags($value);
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
        return mb_substr(trim($value), 0, $max);
    }
}

if (!function_exists('valid_email')) {
    function valid_email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false && strlen($email) <= 190;
    }
}

if (!function_exists('valid_phone')) {
    function valid_phone(string $phone): bool
    {
        return $phone === '' || preg_match('/^[+0-9()\-\s]{7,25}$/', $phone) === 1;
    }
}

if (!function_exists('client_ip')) {
    function client_ip(): string
    {
        return substr((string) service('request')->getIPAddress(), 0, 45);
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $message = null): ?string
    {
        if ($message !== null) {
            session()->setFlashdata($key, $message);
            return null;
        }
        return session()->getFlashdata($key);
    }
}

/* -------------------------------------------------------------------------
 | Admin: image upload handler (CI4 UploadedFile)
 |------------------------------------------------------------------------- */
if (!function_exists('handle_image_upload')) {
    /**
     * @param string      $field    Form field name
     * @param string|null $error    Output error message
     * @return string|null          Stored filename, or null if no file
     */
    function handle_image_upload(string $field, ?string &$error = null): ?string
    {
        $file = service('request')->getFile($field);

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        // Size check (2 MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            $error = 'Image must be smaller than 2 MB.';
            return null;
        }

        // MIME check
        $mime = $file->getMimeType();
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ];
        if (!isset($allowed[$mime])) {
            $error = 'Only JPG, PNG, WEBP or SVG images are allowed.';
            return null;
        }

        // Verify raster images actually decode
        if ($mime !== 'image/svg+xml' && @getimagesize($file->getTempName()) === false) {
            $error = 'The file is not a valid image.';
            return null;
        }

        $name = date('Ymd-His') . '-' . bin2hex(random_bytes(6)) . '.' . $allowed[$mime];

        $dest = FCPATH . 'uploads/';
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        try {
            $file->move($dest, $name);
        } catch (\Throwable $e) {
            $error = 'Could not save the uploaded file.';
            return null;
        }

        return $name;
    }
}