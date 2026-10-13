<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="page-hero bg-grid">
  <div class="hero-glow blue" style="opacity:.35"></div>
  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span>/</span><span>Portfolio</span></nav>
    <h1 class="h-display reveal" style="--delay:.1s">Our Work.</h1>
    <p class="lead reveal" style="--delay:.2s;max-width:720px">Systems and platforms built to solve real operational problems for businesses, schools and organizations.</p>
  </div>
</section>

<section class="section" style="padding-top:0">
  <div class="container">
    <?php if ($projects): ?>
      <div class="filters reveal" data-filters role="group" aria-label="Filter projects">
        <button type="button" class="filter-btn is-active" data-filter="all">All</button>
        <?php foreach ($categories as $c): ?>
          <button type="button" class="filter-btn" data-filter="<?= e($c['slug']) ?>"><?= e($c['name']) ?></button>
        <?php endforeach; ?>
      </div>
      <div class="portfolio-grid" data-stagger="0.08">
        <?php foreach ($projects as $i => $p): ?>
          <?= view('partials/project-card', ['p' => $p, 'i' => $i, 'wide_first' => $wide_first ?? false]) ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="lead reveal">Projects are being added. Please check back soon or <a href="<?= url('contact') ?>" class="text-accent">contact us</a> to discuss your requirements.</p>
    <?php endif; ?>
  </div>
</section>

<section class="section cta">
  <div class="container reveal">
    <h2>Have a Similar Challenge? <span class="text-gradient">Let&rsquo;s Build the Solution.</span></h2>
    <div class="cta-actions">
      <a href="<?= url('contact') ?>" class="btn btn-primary btn-lg">Start a Project <i class="fa-solid fa-arrow-right"></i></a>
      <a href="<?= url('services') ?>" class="btn btn-outline btn-lg">Explore Services</a>
    </div>
  </div>
</section>

<?= $this->endSection() ?>