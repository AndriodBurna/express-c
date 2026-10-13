<?php
/** @var string $page_title */
$page_title = $page_title ?? 'Page Not Found';
$body_class = 'theme-dark';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($page_title) ?> | Ephraitech Unified Solutions</title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('assets/images/favicon.svg') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>?v=1.1.1">
    <link rel="stylesheet" href="<?= base_url('assets/css/components.css') ?>?v=1.1.1">
</head>
<body class="<?= esc($body_class) ?>">
    <a class="skip-link" href="#main">Skip to content</a>

    <?= view('partials/navbar') ?>

    <main id="main">
        <section class="page-hero bg-grid" style="min-height:70vh;display:flex;align-items:center">
          <div class="hero-glow blue" style="opacity:.35"></div>
          <div class="container">
            <p class="eyebrow gold">404</p>
            <h1 class="h-display">Page not found.</h1>
            <p class="lead" style="max-width:560px">The page you are looking for does not exist or has moved.</p>
            <a href="<?= base_url('/') ?>" class="btn btn-primary">Back to Home <i class="fa-solid fa-arrow-right"></i></a>
          </div>
        </section>
    </main>

    <?= view('partials/footer') ?>

    <script src="<?= base_url('assets/js/main.js') ?>?v=1.1.1" defer></script>
</body>
</html>