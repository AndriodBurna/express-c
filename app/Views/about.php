<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="page-hero bg-grid">
  <div class="hero-glow blue" style="opacity:.35"></div>
  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span>/</span><span>About</span></nav>
    <h1 class="h-display reveal" style="--delay:.1s">Who We Are.</h1>
    <p class="lead reveal" style="--delay:.2s;max-width:720px"><?= e(setting('about_intro')) ?></p>
  </div>
</section>

<section class="section">
  <div class="container split">
    <div class="reveal from-left">
      <p class="eyebrow">01 &middot; Our Purpose</p>
      <h2 class="h2">Technology With Purpose.</h2>
      <p class="lead">We exist to help organizations in Uganda and East Africa use technology well: to grow, to operate efficiently and to compete with confidence.</p>
      <p class="text-muted">We combine technology, business understanding, innovation and reliability, and we do it with purpose. Our work is built on faith and driven by excellence, which shows up in how we listen, how we build and how we support our clients long after launch.</p>
    </div>
    <div class="reveal from-right">
      <div class="about-frame" data-parallax="0.05">
        <p class="eyebrow gold">Built on Faith</p>
        <p class="about-quote">Driven by Excellence. .</p>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="section-header reveal">
      <p class="eyebrow">02 &middot; What We Bring Together</p>
      <h2 class="h2">Unified Capability.</h2>
    </div>
    <div class="grid grid-3" data-stagger="0.08">
      <?php
      $pillars = [
        ['fa-solid fa-microchip', 'Technology', 'Modern software, cloud, networking and hardware expertise.'],
        ['fa-solid fa-briefcase', 'Business', 'Solutions shaped around how organizations actually operate.'],
        ['fa-solid fa-lightbulb', 'Innovation', 'Practical, forward-looking answers rather than trends for their own sake.'],
        ['fa-solid fa-shield-halved', 'Reliability', 'Systems and support you can depend on every working day.'],
        ['fa-solid fa-bullseye', 'Purpose', 'Work that serves people and strengthens the organizations we partner with.'],
        ['fa-solid fa-chart-line', 'Growth', 'Long-term support that helps organizations build capability and confidence.'],
      ];
      foreach ($pillars as [$icon, $title, $desc]): ?>
        <div class="solution-card reveal">
          <i class="<?= $icon ?>" style="color:var(--c-blue);font-size:1.3rem;margin-bottom:1rem;display:block"></i>
          <h3><?= e($title) ?></h3>
          <p><?= e($desc) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header center reveal">
      <p class="eyebrow" style="justify-content:center">03 &middot; Our Values</p>
      <h2 class="h2">What Guides Our Work.</h2>
    </div>
    <div class="values-row" data-stagger="0.06">
      <?php foreach (['Integrity', 'Excellence', 'Service', 'Local Focus', 'Stewardship'] as $i => $v): ?>
        <div class="value reveal"><span class="num-label">0<?= $i + 1 ?></span><strong><?= $v ?></strong></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="section-header reveal">
      <p class="eyebrow gold">04 &middot; Who We Serve</p>
      <h2 class="h2">Organizations Across East Africa.</h2>
      <p class="lead">Startups, SMEs, schools, offices, organizations, growing businesses and professional institutions in Uganda and the wider region.</p>
    </div>
    <div class="audience-strip reveal">
      <?php foreach (['Startups', 'SMEs', 'Schools', 'NGOs &amp; Organizations', 'Offices', 'Growing Businesses', 'Professional Institutions', 'Corporate Clients'] as $a): ?>
        <span class="chip"><?= $a ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section cta">
  <div class="container reveal">
    <h2>Let&rsquo;s Build Something <span class="text-gradient">That Lasts.</span></h2>
    <div class="cta-actions">
      <a href="<?= url('contact') ?>" class="btn btn-primary btn-lg">Start a Project <i class="fa-solid fa-arrow-right"></i></a>
      <a href="<?= url('services') ?>" class="btn btn-outline btn-lg">Explore Services</a>
    </div>
  </div>
</section>

<?= $this->endSection() ?>