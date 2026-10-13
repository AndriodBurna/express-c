<?= $this->extend('layouts/main') ?>


<?php
// Data the homepage expects (mirrors the old index.php top block)
$services    = $services    ?? get_services();
$projects    = $projects    ?? get_projects(true, 6);
$statistics  = $statistics  ?? get_statistics();
$pages       = $pages       ?? get_pages(true);
$socials     = $socials     ?? get_social_links();
$categories  = $categories  ?? get_project_categories();
$training    = $training    ?? get_training_courses();
$featuredPages = $featuredPages ?? (new \App\Models\PageModel())->getFeatured();
?>

<?= $this->section('content') ?>

   <section class="hero bg-grid" id="home">
  <div class="hero-glow blue"></div>
  <div class="hero-glow gold"></div>
  <div class="container">
    <div class="hero-content">
      <p class="eyebrow">Ephraitech Unified Solutions &middot; Uganda</p>
      <h1 class="h-display hero-title">
        <?php
        // Split headline into up to three animated lines.
        $headline = setting('hero_headline', 'Technology That Moves Your Business Forward.');
        $words = explode(' ', $headline);
        $chunks = array_chunk($words, (int) ceil(count($words) / 3));
        foreach ($chunks as $i => $chunk):
          $cls = $i === count($chunks) - 1 ? ' class="text-gradient"' : '';
        ?>
          <span class="line">
            <span<?= $cls ?>><?= e(implode(' ', $chunk)) ?>
          </span></span>
        <?php endforeach; ?>
      </h1>
      <p class="lead hero-lead reveal" style="--delay:.35s"><?= e(setting('hero_subtext')) ?></p>
      <div class="hero-actions reveal" style="--delay:.5s">
        <a href="<?= url('contact') ?>" class="btn btn-primary btn-lg">Start a Project <i class="fa-solid fa-arrow-right"></i></a>
        <a href="#services" class="btn btn-outline btn-lg">Explore Our Solutions</a>
      </div>
    </div>

    <div class="hero-visual reveal zoom" style="--delay:.3s" aria-hidden="true">
      <div class="ring"></div>
      <div class="ring r2"></div>
      <svg class="hero-lines" viewBox="0 0 400 400" preserveAspectRatio="none">
        <path d="M40 80 C 120 60, 180 140, 260 120 S 360 200, 380 260" />
        <path d="M20 300 C 100 260, 160 320, 240 280 S 340 220, 390 240" />
      </svg>
      <div class="float-card c1">
        <div class="fc-label">Business Dashboard</div>
        <div class="bars"><span style="height:40%"></span><span style="height:65%"></span><span style="height:50%"></span><span style="height:85%"></span><span style="height:60%"></span><span style="height:95%"></span></div>
      </div>
      <div class="float-card c2">
        <div class="fc-label">Network Nodes</div>
        <div class="nodes"><span></span><span></span><span></span><span></span><span></span><span></span></div>
      </div>
      <div class="float-card c3">
        <div class="fc-label">Unified Systems</div>
        <div class="status-row"><span>Software</span><span class="status-dot"></span></div>
        <div class="status-row"><span>Cloud &amp; Network</span><span class="status-dot blue"></span></div>
        <div class="status-row"><span>Support</span><span class="status-dot gold"></span></div>
      </div>
    </div>
  </div>
  <a href="#about" class="hero-scroll"><span class="dot"></span> Discover Ephraitech</a>
</section>

<!-- ================= TRUST & STATISTICS ================= -->
<section class="stats" aria-label="Company highlights">
  <div class="container">
    <?php if ($statistics): ?>
      <div class="stats-grid" data-stagger="0.1">
        <?php foreach ($statistics as $s): ?>
          <div class="stat reveal">
            <div class="stat-value"><span data-count="<?= e($s['value']) ?>">0</span><?php if ($s['suffix'] !== ''): ?><sup><?= e($s['suffix']) ?></sup><?php endif; ?></div>
            <div class="stat-label"><?= e($s['label']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="trust-strip reveal">
      <span><i class="fa-solid fa-circle"></i> Software</span>
      <span><i class="fa-solid fa-circle"></i> Cloud</span>
      <span><i class="fa-solid fa-circle"></i> Networking</span>
      <span><i class="fa-solid fa-circle"></i> Hardware</span>
      <span><i class="fa-solid fa-circle"></i> Consulting</span>
    </div>
  </div>
</section>

<!-- ================= ABOUT ================= -->
<section class="section" id="about">
  <div class="container split">
    <div class="about-visual reveal from-left">
      <div class="about-frame" data-parallax="0.05">
        <img class="about-image" src="<?= e((setting('about_image_path') !== '' ? UPLOAD_URL . setting('about_image_path') : asset('images/techwithpurpose.jpg'))) ?>" alt="Technology leaders collaborating in a connected digital workspace">
        <p class="eyebrow gold">Who We Are</p>
        <p class="about-quote">One technology partner for software, infrastructure, support and skills.</p>
      </div>
      <div class="about-badge reveal" style="--delay:.3s">
        <strong>Unified</strong>
        <small>Technology Partner</small>
      </div>
    </div>
    <div class="reveal from-right">
      <p class="eyebrow">01 &middot; About Ephraitech</p>
      <h2 class="h2">Technology With Purpose, Built for How East African Organizations Work.</h2>
      <p class="lead"><?= e(setting('about_intro')) ?></p>
      <ul class="check-list">
        <li><i class="fa-solid fa-check"></i><span>Helping businesses grow, operate efficiently and compete through technology.</span></li>
        <li><i class="fa-solid fa-check"></i><span>Serving businesses, schools, offices, organizations and institutions.</span></li>
        <li><i class="fa-solid fa-check"></i><span>Built on Faith. Driven by Excellence.</span></li>
      </ul>
      <a href="<?= url('about') ?>" class="btn btn-outline">Learn More About Us <i class="fa-solid fa-arrow-right"></i></a>
    </div>
  </div>
</section>

<!-- ================= SERVICES ================= -->
<section class="section section-alt" id="services">
  <div class="container">
    <div class="section-header reveal">
      <p class="eyebrow">02 &middot; What We Do</p>
      <h2 class="h2">Complete Technology Capability Under One Roof.</h2>
      <p class="lead">From the software your team uses every day to the network it runs on and the skills to make the most of it.</p>
    </div>
    <div class="services-grid" data-stagger="0.08">
      <?php foreach ($services as $i => $s):
        $classes = 'service-card reveal';
        if ($i === 0) $classes .= ' is-large';
        if ($i === 3) $classes .= ' is-dark';
      ?>
        <a href="<?= url('services' . e($s['slug'])) ?>" class="<?= $classes ?>" data-accent="<?= e($s['accent']) ?>">
          <span class="service-num">0<?= $i + 1 ?></span>
          <div class="service-body" style="display:flex;flex-direction:column;gap:1rem;flex:1">
            <span class="service-icon"><i class="<?= e($s['icon']) ?>"></i></span>
            <h3><?= e($s['title']) ?></h3>
            <p><?= e($s['short_description']) ?></p>
            <span class="service-arrow">Explore <i class="fa-solid fa-arrow-right"></i></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= UNIFIED ECOSYSTEM ================= -->
<section class="section unified bg-grid" id="unified">
  <div class="container unified-wrap">
    <div class="reveal from-left">
      <p class="eyebrow gold">03 &middot; Unified Solutions</p>
      <h2 class="h2">One Technology Partner. <span class="text-gradient">Multiple Solutions.</span></h2>
      <p class="lead">Most organizations juggle separate vendors for software, networks, hardware and training. Ephraitech connects them into one ecosystem, so every part works together and one team stays accountable.</p>
      <ul class="unified-list">
        <li><span class="num-label">01</span><strong>Software</strong><span class="desc">Systems built for your operations</span></li>
        <li><span class="num-label">02</span><strong>Cloud</strong><span class="desc">Hosting, data and availability</span></li>
        <li><span class="num-label">03</span><strong>Network</strong><span class="desc">Reliable, secure connectivity</span></li>
        <li><span class="num-label">04</span><strong>Hardware</strong><span class="desc">Equipment that keeps running</span></li>
        <li><span class="num-label">05</span><strong>Consulting</strong><span class="desc">Decisions aligned with goals</span></li>
      </ul>
    </div>
    <div class="orbit reveal zoom" aria-hidden="true">
      <svg viewBox="0 0 100 100">
        <defs>
          <linearGradient id="flowGrad" x1="0" x2="1">
            <stop offset="0" stop-color="#4FB3FF" />
            <stop offset="1" stop-color="#C9A227" />
          </linearGradient>
        </defs>
        <circle class="orbit-line" cx="50" cy="50" r="42" />
        <path class="orbit-line" d="M50 50 L50 8 M50 50 L86 29 M50 50 L86 71 M50 50 L50 92 M50 50 L14 71 M50 50 L14 29" />
        <path class="orbit-flow" d="M50 50 L50 8" />
        <path class="orbit-flow" d="M50 50 L86 29" />
        <path class="orbit-flow" d="M50 50 L86 71" />
        <path class="orbit-flow" d="M50 50 L50 92" />
        <path class="orbit-flow" d="M50 50 L14 71" />
        <path class="orbit-flow" d="M50 50 L14 29" />
      </svg>
      <div class="orbit-center">
        <div><strong>EPHRAITECH</strong><small>Unified</small></div>
      </div>
      <div class="orbit-node n1"><i class="fa-solid fa-code"></i><span>Software</span></div>
      <div class="orbit-node n2"><i class="fa-solid fa-cloud"></i><span>Cloud</span></div>
      <div class="orbit-node n3"><i class="fa-solid fa-network-wired"></i><span>Network</span></div>
      <div class="orbit-node n4"><i class="fa-solid fa-microchip"></i><span>Hardware</span></div>
      <div class="orbit-node n5"><i class="fa-solid fa-compass"></i><span>Consulting</span></div>
      <div class="orbit-node n6"><i class="fa-solid fa-graduation-cap"></i><span>Training</span></div>
    </div>
  </div>
</section>

<!-- ================= BUSINESS SOLUTIONS ================= -->
<section class="section section-alt" id="solutions">
  <div class="container">
    <div class="section-header reveal">
      <p class="eyebrow">04 &middot; Business Solutions</p>
      <h2 class="h2">Real Problems. Practical Technology Answers.</h2>
      <p class="lead">We work with organizations of every size and shape across Uganda and East Africa.</p>
    </div>
    <div class="audience-strip reveal">
      <?php foreach (['Startups', 'SMEs', 'Schools', 'Organizations', 'Offices', 'Growing Businesses', 'Professional Institutions'] as $a): ?>
        <span class="chip"><?= e($a) ?></span>
      <?php endforeach; ?>
    </div>
    <div class="solutions-grid" data-stagger="0.08">
      <div class="solution-card reveal">
        <div class="solution-from">Manual processes <i class="fa-solid fa-arrow-right"></i> Custom software</div>
        <h3>Custom Business Software</h3>
        <p>Replace spreadsheets, paper files and repeated manual work with a system designed around your operations.</p>
      </div>
      <div class="solution-card reveal">
        <div class="solution-from">Infrastructure problems <i class="fa-solid fa-arrow-right"></i> Cloud &amp; networking</div>
        <h3>Reliable Infrastructure</h3>
        <p>Stable networks, secure cloud environments and dependable hardware so your team can simply work.</p>
      </div>
      <div class="solution-card reveal">
        <div class="solution-from">Downtime <i class="fa-solid fa-arrow-right"></i> Technical support</div>
        <h3>Hardware Servicing &amp; Support</h3>
        <p>Repairs, upgrades and preventive maintenance that keep equipment running and interruptions short.</p>
      </div>
      <div class="solution-card reveal">
        <div class="solution-from">School administration <i class="fa-solid fa-arrow-right"></i> Institutional systems</div>
        <h3>Systems for Schools</h3>
        <p>Digital management of students, staff and academic records built for educational institutions.</p>
      </div>
      <div class="solution-card reveal">
        <div class="solution-from">Unclear IT decisions <i class="fa-solid fa-arrow-right"></i> IT consulting</div>
        <h3>Technology Strategy</h3>
        <p>Honest, practical guidance to align technology investments with your goals and budget.</p>
      </div>
      <div class="solution-card reveal">
        <div class="solution-from">Skills gaps <i class="fa-solid fa-arrow-right"></i> ICT training</div>
        <h3>Team Capability</h3>
        <p>Hands-on training so your people can confidently use, maintain and build with technology.</p>
      </div>
    </div>
  </div>
</section>

<!-- ================= PORTFOLIO PREVIEW ================= -->
<?php if ($projects): ?>
  <section class="section" id="portfolio">
    <div class="container">
      <div class="section-header reveal" style="display:flex;justify-content:space-between;align-items:flex-end;gap:2rem;max-width:none;flex-wrap:wrap">
        <div style="max-width:640px">
          <p class="eyebrow gold">05 &middot; Portfolio</p>
          <h2 class="h2" style="margin:0">Selected Work.</h2>
        </div>
        <a href="<?= url('portfolio') ?>" class="link-arrow">View all projects <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="portfolio-grid" data-stagger="0.1">
        <?php foreach ($projects as $p): ?>
            <?= view('partials/project-card', ['p' => $p]) ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- ================= WHY EPHRAITECH ================= -->
<section class="section section-alt" id="why">
  <div class="container">
    <div class="section-header reveal">
      <p class="eyebrow">06 &middot; Why Ephraitech</p>
      <h2 class="h2">Why Businesses Choose Ephraitech.</h2>
    </div>
    <div class="why-grid" data-stagger="0.07">
      <div class="why-item reveal"><i class="fa-solid fa-earth-africa"></i>
        <h3>East African Focus</h3>
        <p>Solutions designed for the realities of businesses and institutions in Uganda and the region.</p>
      </div>
      <div class="why-item reveal"><i class="fa-solid fa-layer-group"></i>
        <h3>Full Technology Capability</h3>
        <p>Software, cloud, networking, hardware and consulting from a single accountable partner.</p>
      </div>
      <div class="why-item reveal"><i class="fa-solid fa-shield-halved"></i>
        <h3>Integrity</h3>
        <p>Honest advice and transparent work. We recommend what you need, not what is easiest to sell.</p>
      </div>
      <div class="why-item reveal"><i class="fa-solid fa-gem"></i>
        <h3>Excellence</h3>
        <p>Careful engineering and quality in every deliverable, from a network cable to a full platform.</p>
      </div>
      <div class="why-item reveal"><i class="fa-solid fa-handshake"></i>
        <h3>Long-Term Support</h3>
        <p>We stay with you after launch, keeping systems maintained, secure and improving.</p>
      </div>
      <div class="why-item reveal"><i class="fa-solid fa-bullseye"></i>
        <h3>Technology With Purpose</h3>
        <p>Every solution exists to serve people, strengthen organizations and create real value.</p>
      </div>
    </div>
  </div>
</section>

<!-- ================= VALUES ================= -->
<section class="section" id="values">
  <div class="container">
    <div class="section-header center reveal">
      <p class="eyebrow" style="justify-content:center">07 &middot; Our Values</p>
      <h2 class="h2">What Guides Our Work.</h2>
    </div>
    <div class="values-row" data-stagger="0.06">
      <?php foreach (['Integrity', 'Excellence', 'Service', 'Local Focus', 'Stewardship'] as $i => $v): ?>
        <div class="value reveal"><span class="num-label">0<?= $i + 1 ?></span><strong><?= $v ?></strong></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= PURPOSE ================= -->
<section class="section section-alt purpose">
  <div class="container reveal">
    <div class="purpose-line"></div>
    <p class="purpose-text">Built on Faith. Driven by Excellence.</p>
    <p class="purpose-sub">Soli Deo Gloria</p>
  </div>
</section>

<!-- ================= PROCESS ================= -->
<section class="section section-alt process" id="process">
  <div class="container">
    <div class="section-header reveal">
      <p class="eyebrow">09 &middot; How We Work</p>
      <h2 class="h2">A Clear Path From Idea to Impact.</h2>
    </div>
    <div class="process-track" data-stagger="0.15">
      <div class="process-progress"></div>
      <?php
      $steps = [
        ['Discover', 'We listen, learn how you work and define the real problem to solve.'],
        ['Plan', 'We design the solution, scope, timeline and technology choices with you.'],
        ['Build', 'We develop, configure and test with regular check-ins and demos.'],
        ['Deploy', 'We launch carefully, migrate data, train your team and go live.'],
        ['Support', 'We maintain, secure and improve your systems for the long term.'],
      ];
      foreach ($steps as $i => [$title, $desc]): ?>
        <div class="step reveal">
          <span class="step-dot"></span>
          <div class="step-num">0<?= $i + 1 ?></div>
          <h3><?= e($title) ?></h3>
          <p><?= e($desc) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

  <?php if ($featuredPages): ?>
  <section class="section section-alt" id="quick-links">
    <div class="container">
      <div class="section-header reveal">
        <p class="eyebrow gold">Explore</p>
        <h2 class="h2">More From Ephraitech.</h2>
      </div>
      <div class="grid grid-3" data-stagger="0.08">
        <?php foreach ($featuredPages as $fp): ?>
          <a href="<?= url('page/' . $fp['slug']) ?>" class="solution-card reveal" style="text-decoration:none">
            <h3><?= e($fp['title']) ?></h3>
            <p><?= e(mb_substr(strip_tags($fp['content'] ?? ''), 0, 120)) ?>…</p>
            <span class="link-arrow">Read more <i class="fa-solid fa-arrow-right"></i></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

<!-- ================= CTA ================= -->
<section class="section cta">
  <div class="container reveal">
    <p class="eyebrow gold" style="justify-content:center">Let&rsquo;s Talk</p>
    <h2>Have a Business Challenge? <span class="text-gradient">Let&rsquo;s Build the Solution.</span></h2>
    <p class="lead" style="max-width:600px;margin:0 auto">Tell us what is slowing your organization down. We will help you find the right technology answer.</p>
    <div class="cta-actions">
      <a href="<?= url('contact') ?>" class="btn btn-primary btn-lg">Start a Project <i class="fa-solid fa-arrow-right"></i></a>
      <a href="<?= url('contact.php#contact-info') ?>" class="btn btn-outline btn-lg">Talk to Ephraitech</a>
    </div>
  </div>
</section>

<?= $this->endSection() ?>