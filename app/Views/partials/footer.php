<?php
$footer_services = get_services();
$social_links    = get_social_links();
$phone           = setting('contact_phone');
$whatsapp        = setting('contact_whatsapp');
$email           = setting('contact_email');
$location        = setting('contact_location');
$hours           = setting('business_hours');
?>
<footer class="site-footer">
  <div class="container">
    <div class="footer-top">
      <div class="footer-brand">
        <a class="brand" href="<?= url('/') ?>">
          <img src="<?= e(asset('images/logo%20no%20bg.png')) ?>" alt="<?= e(setting('site_name', 'Ephraitech')) ?>" class="brand-logo" width="180" height="48">
          <span class="brand-text">
            <span class="brand-name">EPHRAITECH</span>
            <span class="brand-sub">Unified Solutions</span>
          </span>
        </a>
        <p class="footer-desc"><?= e(setting('footer_description', 'An ICT company helping businesses, schools and organizations in Uganda and East Africa use technology to grow, operate efficiently and compete. One technology partner, multiple solutions.')) ?></p>
        <?php if ($social_links): ?>
          <ul class="social-list" aria-label="Social media">
            <?php foreach ($social_links as $s): ?>
              <li><a href="<?= e($s['url']) ?>" target="_blank" rel="noopener" aria-label="<?= e($s['label']) ?>"><i class="<?= e($s['icon']) ?>"></i></a></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <div class="footer-col">
        <h4 class="footer-heading">Solutions</h4>
        <ul class="footer-links">
          <?php foreach ($footer_services as $s): ?>
            <li><a href="<?= url('services#' . e($s['slug'])) ?>"><?= e($s['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

    <div class="footer-col">
    <h4 class="footer-heading">Company</h4>
    <ul class="footer-links">
        <li><a href="<?= url('about') ?>">About Ephraitech</a></li>
        <li><a href="<?= url('portfolio') ?>">Portfolio</a></li>
        <li><a href="<?= url('quote') ?>">Request a Quote</a></li>
        <li><a href="<?= url('contact') ?>">Contact</a></li>
    </ul>
    <h4 class="footer-heading" style="margin-top:1.5rem">Legal</h4>
    <ul class="footer-links">
        <li><a href="<?= url('page/privacy-policy') ?>">Privacy Policy</a></li>
        <li><a href="<?= url('page/terms-of-service') ?>">Terms of Service</a></li>
        <li><a href="<?= url('page/cookie-policy') ?>">Cookie Policy</a></li>
        <li><a href="<?= url('page/refund-policy') ?>">Refund Policy</a></li>
    </ul>
</div>
      <div class="footer-col">
        <h4 class="footer-heading">Contact</h4>
        <ul class="footer-contact">
          <?php if ($phone !== ''): ?>
            <li><i class="fa-solid fa-phone"></i><a href="tel:<?= e(phone_digits($phone)) ?>"><?= e($phone) ?></a></li>
          <?php endif; ?>
          <?php if ($whatsapp !== ''): ?>
            <li><i class="fa-brands fa-whatsapp"></i><a href="https://wa.me/<?= e(ltrim(phone_digits($whatsapp), '+')) ?>" target="_blank" rel="noopener"><?= e($whatsapp) ?></a></li>
          <?php endif; ?>
          <?php if ($email !== ''): ?>
            <li><i class="fa-solid fa-envelope"></i><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li>
          <?php endif; ?>
          <?php if ($location !== ''): ?>
            <li><i class="fa-solid fa-location-dot"></i><span><?= e($location) ?></span></li>
          <?php endif; ?>
          <?php if ($hours !== ''): ?>
            <li><i class="fa-regular fa-clock"></i><span><?= e($hours) ?></span></li>
          <?php endif; ?>
          <?php if ($phone === '' && $email === '' && $location === ''): ?>
            <li class="text-muted">Contact details are managed in Admin &rarr; Settings.</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p class="footer-faith">Built on Faith. Driven by Excellence. <span class="footer-sdg"></span></p>
      <p class="footer-copy">&copy; <?= date('Y') ?> <?= e(setting('site_name', 'Ephraitech Unified Solutions')) ?>. All rights reserved.</p>
    </div>
  </div>
</footer>