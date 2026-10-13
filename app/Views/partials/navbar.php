<?php
$nav_items = [
    ['Home',      '/',                null],
    ['About',     'about',            'about'],
    ['Services',  'services',         'services'],
    ['Solutions', '/#solutions',      null],
    ['Portfolio', 'portfolio',        'portfolio'],
    ['Contact',   'contact',          'contact'],
];

$nav_items = array_values(array_filter($nav_items, fn($n) => $n[2] === null || page_visible($n[2])));

// Determine current page for active state
$current = trim(service('request')->getUri()->getPath(), '/');
?>
<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a class="brand" href="<?= url('/') ?>" aria-label="<?= e(setting('site_name', 'Ephraitech Unified Solutions')) ?> home">
      <?php if (setting('logo_path') !== ''): ?>
        <img src="<?= e(upload_url(setting('logo_path'))) ?>" alt="<?= e(setting('site_name', 'Ephraitech')) ?>" class="brand-logo" width="160" height="40">
      <?php else: ?>
        <img src="<?= e(asset('images/logo%20no%20bg.png')) ?>" alt="<?= e(setting('site_name', 'Ephraitech')) ?>" class="brand-logo" width="180" height="48">
      <?php endif; ?>
      <span class="brand-text">
        <span class="brand-name">EPHRAITECH</span>
        <span class="brand-sub">Unified Solutions</span>
      </span>
    </a>

    <nav class="nav-desktop" aria-label="Primary">
      <ul>
        <?php foreach ($nav_items as [$label, $href, $key]): ?>
          <?php
            $isActive = $key !== null && $current === $key;
          ?>
          <li><a href="<?= url($href) ?>" class="nav-link<?= $isActive ? ' is-active' : '' ?>"><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="header-actions">
      <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to light mode" aria-pressed="false">
        <i class="fa-solid fa-sun" aria-hidden="true"></i><span class="theme-toggle-label">Light mode</span>
      </button>
      <a href="<?= url('quote') ?>" class="btn btn-primary btn-sm header-cta">Request a Quote <i class="fa-solid fa-arrow-right"></i></a>
      <button class="hamburger" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobileNav">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<div class="mobile-nav" id="mobileNav" aria-hidden="true">
  <div class="mobile-nav-inner">
    <ul class="mobile-links">
      <?php foreach ($nav_items as $i => [$label, $href]): ?>
        <li style="--i:<?= $i ?>"><a href="<?= url($href) ?>"><span class="mobile-num">0<?= $i + 1 ?></span><?= e($label) ?></a></li>
      <?php endforeach; ?>
    </ul>
    <div class="mobile-footer">
      <button class="theme-toggle theme-toggle-mobile" id="themeToggleMobile" type="button" aria-label="Switch to light mode" aria-pressed="false">
        <i class="fa-solid fa-sun" aria-hidden="true"></i><span class="theme-toggle-label">Light mode</span>
      </button>
      <a href="<?= url('quote') ?>" class="btn btn-primary btn-block">Request a Quote <i class="fa-solid fa-arrow-right"></i></a>
      <?php if (setting('contact_email') !== ''): ?>
        <a class="mobile-contact" href="mailto:<?= e(setting('contact_email')) ?>"><?= e(setting('contact_email')) ?></a>
      <?php endif; ?>
      <?php if (setting('contact_phone') !== ''): ?>
        <a class="mobile-contact" href="tel:<?= e(phone_digits(setting('contact_phone'))) ?>"><?= e(setting('contact_phone')) ?></a>
      <?php endif; ?>
      <p class="mobile-tag">Built on Faith. Driven by Excellence.</p>
    </div>
  </div>
</div>