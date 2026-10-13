<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="page-hero bg-grid" style="padding-bottom:0">
  <div class="hero-glow blue" style="opacity:.3"></div>
  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span>/</span><a href="<?= url('portfolio') ?>">Portfolio</a><span>/</span><span><?= e($project['title']) ?></span></nav>
    <p class="eyebrow gold reveal"><?= e($project['category_name'] ?? 'Project') ?> &middot; Case Study</p>
    <h1 class="h-display reveal" style="--delay:.1s"><?= e($project['title']) ?></h1>
    <p class="lead reveal" style="--delay:.2s;max-width:760px"><?= e($project['short_description']) ?></p>

    <div class="case-hero-media reveal zoom" style="--delay:.3s">
      <?php $fallback = !empty($project['image']) ? '' : default_project_image($project['slug'] ?? ''); ?>
      <?php if (!empty($project['image'])): ?>
        <img src="<?= e(upload_url($project['image'])) ?>" alt="<?= e($project['title']) ?> screenshot" width="1200" height="514">
      <?php elseif ($fallback !== ''): ?>
        <img src="<?= e(asset('images/' . $fallback)) ?>" alt="<?= e($project['title']) ?> screenshot" width="1200" height="514">
      <?php else: ?>
        <div class="project-placeholder" aria-hidden="true">
          <div class="mock" style="width:55%">
            <div class="mock-bar w40"></div><div class="mock-bar w80"></div><div class="mock-bar w60"></div><div class="mock-bar gold"></div>
            <div class="mock-grid"><span></span><span></span><span></span></div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section" style="padding-top:3rem">
  <div class="container">
    <div class="case-meta reveal">
      <div><small>Category</small><strong><?= e($project['category_name'] ?? 'Project') ?></strong></div>
      <?php if (!empty($project['client_name'])): ?><div><small>Client</small><strong><?= e($project['client_name']) ?></strong></div><?php endif; ?>
      <?php if (!empty($project['year'])): ?><div><small>Year</small><strong><?= e($project['year']) ?></strong></div><?php endif; ?>
      <?php if ($tech): ?><div><small>Technologies</small><strong><?= e(implode(', ', $tech)) ?></strong></div><?php endif; ?>
      <?php if (!empty($project['project_url'])): ?><div><small>Live</small><a class="link-arrow" href="<?= e($project['project_url']) ?>" target="_blank" rel="noopener">Visit <i class="fa-solid fa-arrow-up-right-from-square"></i></a></div><?php endif; ?>
    </div>

    <?php
    $blocks = [
      ['01', 'Overview',     $project['overview']],
      ['02', 'The Problem',  $project['problem']],
      ['03', 'The Challenge',$project['challenge']],
      ['04', 'Our Solution', $project['solution']],
    ];
    foreach ($blocks as [$n, $title, $text]): if (trim((string) $text) === '') continue; ?>
      <div class="case-block reveal">
        <div><p class="eyebrow"><?= $n ?></p><h2><?= e($title) ?></h2></div>
        <div class="lead rich" style="font-size:1.05rem"><?= rich_text($text) ?></div>
      </div>
    <?php endforeach; ?>

    <?php if ($features): ?>
      <div class="case-block reveal">
        <div><p class="eyebrow">05</p><h2>Key Features</h2></div>
        <ul class="feature-list grid grid-2" style="gap:.25rem 2rem">
          <?php foreach ($features as $f): ?><li><i class="fa-solid fa-circle"></i><span><?= e($f) ?></span></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($tech): ?>
      <div class="case-block reveal">
        <div><p class="eyebrow">06</p><h2>Technologies</h2></div>
        <div class="tag-list"><?php foreach ($tech as $t): ?><span class="tag" style="font-size:.8rem;padding:.5rem .9rem"><?= e($t) ?></span><?php endforeach; ?></div>
      </div>
    <?php endif; ?>

    <?php if (trim((string) $project['results']) !== ''): ?>
      <div class="case-block reveal">
        <div><p class="eyebrow gold">07</p><h2>Results</h2></div>
        <div class="lead rich" style="font-size:1.05rem"><?= rich_text($project['results']) ?></div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php if ($others): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-header reveal" style="display:flex;justify-content:space-between;align-items:flex-end;max-width:none;flex-wrap:wrap;gap:1rem">
      <h2 class="h2" style="margin:0">More Projects.</h2>
      <a href="<?= url('portfolio') ?>" class="link-arrow">All projects <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="portfolio-grid" data-stagger="0.1">
      <?php foreach ($others as $i => $p): ?>
        <?= view('partials/project-card', ['p' => $p, 'i' => $i, 'wide_first' => false]) ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section cta">
  <div class="container reveal">
    <h2>Need Something Similar? <span class="text-gradient">Let&rsquo;s Build It.</span></h2>
    <div class="cta-actions">
      <a href="<?= url('contact') ?>" class="btn btn-primary btn-lg">Start a Project <i class="fa-solid fa-arrow-right"></i></a>
      <a href="<?= url('portfolio') ?>" class="btn btn-outline btn-lg">Back to Portfolio</a>
    </div>
  </div>
</section>

<?= $this->endSection() ?>