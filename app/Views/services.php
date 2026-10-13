<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="page-hero bg-grid">
  <div class="hero-glow blue" style="opacity:.35"></div>
  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span>/</span><span>Services</span></nav>
    <h1 class="h-display reveal" style="--delay:.1s">Our Services.</h1>
    <p class="lead reveal" style="--delay:.2s;max-width:720px">Complete technology capability under one roof: software, cloud, networking, hardware and consulting for organizations across Uganda and East Africa.</p>
  </div>
</section>

<?php foreach ($services as $i => $s):
  $features = lines($s['features'] ?? '');
  $alt = $i % 2 === 1;
  $service_image = default_service_image($s['slug'] ?? '');
?>
  <section class="section<?= $alt ? ' section-alt' : '' ?>" id="<?= e($s['slug']) ?>">
    <div class="container split">
      <div class="reveal <?= $alt ? 'from-right' : 'from-left' ?>" style="<?= $alt ? 'order:2' : '' ?>">
        <p class="eyebrow<?= $s['accent'] === 'gold' ? ' gold' : '' ?>">0<?= $i + 1 ?> &middot; <?= e($s['label'] ?? 'Service') ?></p>
        <h2 class="h2"><?= e($s['title']) ?></h2>
        <p class="lead"><?= e($s['short_description']) ?></p>
        <div class="rich"><?= rich_text($s['long_description'] ?? '') ?></div>
        <?php if ($features): ?>
          <ul class="check-list">
            <?php foreach ($features as $f): ?><li><i class="fa-solid fa-check"></i><span><?= e($f) ?></span></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <a href="<?= url('quote?service=' . urlencode($s['slug'])) ?>" class="btn btn-primary" style="margin-top:1rem">Request This Service <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="reveal zoom" style="<?= $alt ? 'order:1' : '' ?>">
        <div class="service-card is-dark" data-accent="<?= e($s['accent']) ?>" style="min-height:340px;justify-content:center;align-items:center;text-align:center;border-color:rgba(255,255,255,.12);background-image:linear-gradient(135deg, rgba(10,10,11,0.55), rgba(10,10,11,0.72)), url('<?= e($service_image !== '' ? asset('images/' . $service_image) : '') ?>');background-size:cover;background-position:center;background-repeat:no-repeat;">
          <span class="service-icon" style="width:84px;height:84px;font-size:2rem;border-radius:24px"><i class="<?= e($s['icon']) ?>"></i></span>
          <span class="service-num" style="position:static;margin-top:1rem"><?= e($s['label'] ?? '') ?></span>
        </div>
      </div>
    </div>
  </section>
<?php endforeach; ?>

<section class="section cta">
  <div class="container reveal">
    <h2>Not Sure Which Service You Need? <span class="text-gradient">Let&rsquo;s Talk.</span></h2>
    <div class="cta-actions">
      <a href="<?= url('contact') ?>" class="btn btn-primary btn-lg">Start a Project <i class="fa-solid fa-arrow-right"></i></a>
      <a href="<?= url('contact#contact-info') ?>" class="btn btn-outline btn-lg">Talk to Ephraitech</a>
    </div>
  </div>
</section>

<?= $this->endSection() ?>