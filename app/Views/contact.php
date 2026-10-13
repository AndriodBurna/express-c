<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="page-hero bg-grid">
  <div class="hero-glow blue" style="opacity:.35"></div>
  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span>/</span><span>Contact</span></nav>
    <h1 class="h-display reveal" style="--delay:.1s">Let&rsquo;s Build the Solution.</h1>
    <p class="lead reveal" style="--delay:.2s;max-width:720px">Tell us about your business challenge. We will respond with clear next steps.</p>
  </div>
</section>

<section class="section" style="padding-top:0" id="contact-info">
  <div class="container" style="display:grid;grid-template-columns:1fr 1.1fr;gap:clamp(2rem,5vw,4rem);align-items:start">
    <aside class="reveal from-left">
      <div class="contact-card">
        <p class="eyebrow gold">Talk to Ephraitech</p>
        <h2 class="h3" style="font-size:1.6rem">Contact Information</h2>
        <ul class="contact-list">
          <?php if (setting('contact_phone') !== ''): ?>
            <li><i class="fa-solid fa-phone"></i>
              <div><small>Phone</small><a href="tel:<?= e(phone_digits(setting('contact_phone'))) ?>"><?= e(setting('contact_phone')) ?></a></div>
            </li>
          <?php endif; ?>
          <?php if (setting('contact_whatsapp') !== ''): ?>
            <li><i class="fa-brands fa-whatsapp"></i>
              <div><small>WhatsApp</small><a href="https://wa.me/<?= e(ltrim(phone_digits(setting('contact_whatsapp')), '+')) ?>" target="_blank" rel="noopener"><?= e(setting('contact_whatsapp')) ?></a></div>
            </li>
          <?php endif; ?>
          <?php if (setting('contact_email') !== ''): ?>
            <li><i class="fa-solid fa-envelope"></i>
              <div><small>Email</small><a href="mailto:<?= e(setting('contact_email')) ?>"><?= e(setting('contact_email')) ?></a></div>
            </li>
          <?php endif; ?>
          <?php if (setting('contact_location') !== ''): ?>
            <li><i class="fa-solid fa-location-dot"></i>
              <div><small>Location</small><span><?= e(setting('contact_location')) ?></span></div>
            </li>
          <?php endif; ?>
          <?php if (setting('business_hours') !== ''): ?>
            <li><i class="fa-regular fa-clock"></i>
              <div><small>Business Hours</small><span><?= e(setting('business_hours')) ?></span></div>
            </li>
          <?php endif; ?>
          <?php if (setting('service_coverage') !== ''): ?>
            <li><i class="fa-solid fa-earth-africa"></i>
              <div><small>Service Coverage</small><span><?= e(setting('service_coverage')) ?></span></div>
            </li>
          <?php endif; ?>
        </ul>
      </div>
      <?php if (setting('map_embed_url') !== '' && str_starts_with(setting('map_embed_url'), 'https://www.google.com/maps/embed')): ?>
        <div class="contact-card" style="padding:0;overflow:hidden;margin-top:1.25rem;aspect-ratio:4/3">
          <iframe src="<?= e(setting('map_embed_url')) ?>" title="Ephraitech location map" width="100%" height="100%" style="border:0;display:block;filter:grayscale(1) invert(.9)" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </div>
      <?php endif; ?>
    </aside>

    <div class="contact-card reveal from-right" id="message">
      <p class="eyebrow gold">Have a Question?</p>
      <h2 class="h3" style="font-size:1.6rem">Send a Message</h2>
      <?php if ($message_success): ?><div class="alert alert-success" role="status"><i class="fa-solid fa-check"></i> <?= e($message_success) ?></div><?php endif; ?>
      <?php if (!empty($message_errors['form'])): ?><div class="alert alert-error" role="alert"><?= e($message_errors['form']) ?></div><?php endif; ?>
      <form method="post" action="<?= url('contact#message') ?>" class="form-grid" novalidate>
        <?= csrf_field() ?><input type="hidden" name="form_type" value="message">
        <div class="form-group"><label class="form-label" for="message_first_name">First Name <span class="req">*</span></label><input class="form-control" id="message_first_name" name="message_first_name" required></div>
        <div class="form-group"><label class="form-label" for="message_last_name">Last Name <span class="req">*</span></label><input class="form-control" id="message_last_name" name="message_last_name" required></div>
        <div class="form-group full"><label class="form-label" for="message_email">Email <span class="req">*</span></label><input class="form-control" id="message_email" name="message_email" type="email" required></div>
        <div class="form-group full"><label class="form-label" for="message_subject">Subject</label><input class="form-control" id="message_subject" name="message_subject" maxlength="190"></div>
        <div class="form-group full"><label class="form-label" for="message_body">Message <span class="req">*</span></label><textarea class="form-control<?= isset($message_errors['message']) ? ' is-invalid' : '' ?>" id="message_body" name="message" required></textarea><?php if (isset($message_errors['message'])): ?><span class="form-error"><?= e($message_errors['message']) ?></span><?php endif; ?></div>
        <div class="form-group full"><button type="submit" class="btn btn-primary">Send Message <i class="fa-solid fa-arrow-right"></i></button></div>
      </form>
    </div>
  </div>
</section>

<style>
  @media (max-width: 900px) {
    .page-contact #quote .container { grid-template-columns: 1fr !important; }
  }
</style>

<?= $this->endSection() ?>