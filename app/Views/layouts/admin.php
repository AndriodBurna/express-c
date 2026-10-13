<?php
$admin_user = session()->get('admin');
$current    = trim(service('request')->getUri()->getPath(), '/');
$menu = [
    ['control/dashboard',       'fa-solid fa-gauge',           'Dashboard'],
    ['control/messages',        'fa-solid fa-inbox',           'Messages'],
    ['control/quotes',          'fa-solid fa-file-invoice',    'Quote Requests'],
    ['control/pages',           'fa-solid fa-file-lines',      'Pages'],
    ['control/projects',        'fa-solid fa-diagram-project', 'Projects'],
    ['control/services',        'fa-solid fa-layer-group',     'Services'],
    ['control/training',        'fa-solid fa-graduation-cap',  'Training'],
    ['control/settings',        'fa-solid fa-sliders',         'Settings'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($admin_title ?? 'Admin') ?> | Ephraitech Admin</title>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="admin-base" content="<?= base_url('control/') ?>">
<link rel="icon" type="image/svg+xml" href="<?= base_url('assets/images/favicon.svg') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
<link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin" data-page="<?= e(basename($current)) ?>">
<div class="admin-shell">
  <aside class="admin-sidebar">
    <a class="brand admin-brand" href="<?= base_url('control/dashboard') ?>">
      <img src="<?= base_url('assets/images/logo%20no%20bg.png') ?>" alt="Ephraitech" class="brand-logo" width="180" height="48">
      <span class="brand-text">
        <span class="brand-name">EPHRAITECH</span>
        <span class="brand-sub">Unified Solutions</span>
      </span>
    </a>
    <nav class="admin-nav">
      <?php foreach ($menu as [$path, $icon, $label]): ?>
        <a href="<?= base_url($path) ?>" class="<?= $current === $path ? 'is-active' : '' ?>"><i class="<?= $icon ?>"></i><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="admin-sidebar-footer">
      <a href="<?= base_url('/') ?>" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square"></i> View Website</a>
      <a href="<?= base_url('control/change-password') ?>"><i class="fa-solid fa-key"></i> Change Password</a>
      <a href="<?= base_url('control/logout') ?>"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>
  <div class="admin-main">
    <header class="admin-topbar">
      <button class="admin-menu-btn" onclick="document.body.classList.toggle('sidebar-open')" aria-label="Toggle menu"><i class="fa-solid fa-bars"></i></button>
      <h1 class="admin-title"><?= e($admin_title ?? 'Admin') ?></h1>
      <span class="admin-user"><i class="fa-solid fa-user-shield"></i> <?= e($admin_user['full_name'] ?: $admin_user['username']) ?></span>
    </header>
    <div class="admin-content">
      <?php if ($m = session()->getFlashdata('admin_success')): ?><div class="alert alert-success"><?= e($m) ?></div><?php endif; ?>
      <?php if ($m = session()->getFlashdata('admin_error')): ?><div class="alert alert-error"><?= e($m) ?></div><?php endif; ?>

      <?= $this->renderSection('content') ?>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>