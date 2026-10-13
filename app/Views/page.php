<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="page-hero bg-grid">
  <div class="hero-glow blue" style="opacity:.35"></div>
  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb">
      <a href="<?= url('/') ?>">Home</a><span>/</span><span><?= e($page['title']) ?></span>
    </nav>
    <h1 class="h-display reveal" style="--delay:.1s"><?= e($page['title']) ?></h1>
  </div>
</section>

<section class="section" style="padding-top:3rem">
  <div class="container">
    <article class="legal-doc reveal">
      <div class="legal-doc-content rich">
        <?= rich_text($page['content'] ?? '') ?>
      </div>
    </article>
  </div>
</section>

<section class="section cta">
  <div class="container reveal">
    <h2>Have a Project in Mind? <span class="text-gradient">Let&rsquo;s Talk.</span></h2>
    <div class="cta-actions">
      <a href="<?= url('contact') ?>" class="btn btn-primary btn-lg">Start a Project <i class="fa-solid fa-arrow-right"></i></a>
      <a href="<?= url('services') ?>" class="btn btn-outline btn-lg">Explore Services</a>
    </div>
  </div>
</section>

<style>
/* ---------- Legal / Document Page Styling ---------- */
.legal-doc {
  max-width: 820px;
  margin: 0 auto;
  background: var(--c-surface, #ffffff);
  border: 1px solid var(--c-border, rgba(0,0,0,.08));
  border-radius: 18px;
  padding: clamp(1.75rem, 4vw, 3.5rem);
  box-shadow: 0 20px 60px rgba(0,0,0,.06);
}

.legal-doc-content {
  font-size: 1.02rem;
  line-height: 1.75;
  color: var(--c-text, #1a1a1a);
}

.legal-doc-content p {
  margin: 0 0 1.15rem;
}

.legal-doc-content > p:first-of-type {
  font-size: 1.1rem;
  color: var(--c-text-muted, #4a4a4a);
  padding-bottom: 1.25rem;
  border-bottom: 1px solid var(--c-border, rgba(0,0,0,.08));
  margin-bottom: 2rem;
}

.legal-doc-content h3 {
  font-family: 'Manrope', sans-serif;
  font-size: 1.15rem;
  font-weight: 700;
  letter-spacing: -.01em;
  margin: 2.25rem 0 .85rem;
  padding-left: 1rem;
  border-left: 3px solid var(--c-blue, #2563eb);
  color: var(--c-heading, #0a0a0b);
}

.legal-doc-content ul {
  list-style: none;
  padding: 0;
  margin: 0 0 1.25rem;
}

.legal-doc-content ul li {
  position: relative;
  padding: .35rem 0 .35rem 1.75rem;
  color: var(--c-text, #1a1a1a);
}

.legal-doc-content ul li::before {
  content: '';
  position: absolute;
  left: .35rem;
  top: .95rem;
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: var(--c-blue, #2563eb);
}

.legal-doc-content strong {
  color: var(--c-heading, #0a0a0b);
  font-weight: 600;
}

.legal-doc-content a {
  color: var(--c-blue, #2563eb);
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: border-color .2s;
}
.legal-doc-content a:hover {
  border-bottom-color: var(--c-blue, #2563eb);
}

.legal-doc-content em {
  color: var(--c-text-muted, #6b6b6b);
  font-style: normal;
  font-size: .9rem;
  letter-spacing: .01em;
  display: inline-block;
  padding: .3rem .75rem;
  background: var(--c-surface-alt, rgba(37,99,235,.06));
  border-radius: 999px;
  margin-bottom: .5rem;
}

/* ---------- Dark mode override ---------- */
body.theme-dark .legal-doc {
  background: rgba(255,255,255,.03);
  border-color: rgba(255,255,255,.08);
  box-shadow: 0 20px 60px rgba(0,0,0,.35);
}
body.theme-dark .legal-doc-content {
  color: rgba(255,255,255,.85);
}
body.theme-dark .legal-doc-content > p:first-of-type {
  color: rgba(255,255,255,.6);
  border-bottom-color: rgba(255,255,255,.1);
}
body.theme-dark .legal-doc-content h3 {
  color: #ffffff;
  border-left-color: #4FB3FF;
}
body.theme-dark .legal-doc-content ul li {
  color: rgba(255,255,255,.85);
}
body.theme-dark .legal-doc-content ul li::before {
  background: #4FB3FF;
}
body.theme-dark .legal-doc-content strong {
  color: #ffffff;
}
body.theme-dark .legal-doc-content a {
  color: #4FB3FF;
}
body.theme-dark .legal-doc-content a:hover {
  border-bottom-color: #4FB3FF;
}
body.theme-dark .legal-doc-content em {
  color: rgba(255,255,255,.7);
  background: rgba(79,179,255,.1);
}

@media (max-width: 600px) {
  .legal-doc { border-radius: 12px; }
  .legal-doc-content h3 { font-size: 1.05rem; }
}
</style>

<?= $this->endSection() ?>