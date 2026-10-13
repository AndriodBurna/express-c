<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="page-hero bg-grid">
  <div class="hero-glow blue" style="opacity:.35"></div>
  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb"><a href="<?= url('/') ?>">Home</a><span>/</span><span>Request a Quote</span></nav>
    <h1 class="h-display reveal" style="--delay:.1s">Request a Quote</h1>
    <p class="lead reveal" style="--delay:.2s;max-width:720px">Tell us what you need and we will help you shape the right solution.</p>
  </div>
</section>

<section class="section" style="padding-top:0" id="quote">
  <div class="container" style="max-width:900px">
    <div class="contact-card reveal from-left">
      <?php if ($success): ?>
        <div class="alert alert-success" role="status"><i class="fa-solid fa-check"></i> <?= e($success) ?></div>
      <?php endif; ?>
      <?php if (!empty($errors['form'])): ?>
        <div class="alert alert-error" role="alert"><?= e($errors['form']) ?></div>
      <?php endif; ?>

      <form method="post" action="<?= url('quote#quote') ?>" class="form-grid" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="form_started" value="<?= time() ?>">
        <div class="honeypot" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

        <div class="form-group">
          <label class="form-label" for="first_name">First Name <span class="req">*</span></label>
          <input class="form-control<?= isset($errors['first_name']) ? ' is-invalid' : '' ?>" id="first_name" name="first_name" type="text" maxlength="80" required value="<?= e($old['first_name']) ?>">
          <?php if (isset($errors['first_name'])): ?><span class="form-error"><?= e($errors['first_name']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="form-label" for="last_name">Last Name <span class="req">*</span></label>
          <input class="form-control<?= isset($errors['last_name']) ? ' is-invalid' : '' ?>" id="last_name" name="last_name" type="text" maxlength="80" required value="<?= e($old['last_name']) ?>">
          <?php if (isset($errors['last_name'])): ?><span class="form-error"><?= e($errors['last_name']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="form-label" for="email">Email <span class="req">*</span></label>
          <input class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>" id="email" name="email" type="email" maxlength="190" required value="<?= e($old['email']) ?>">
          <?php if (isset($errors['email'])): ?><span class="form-error"><?= e($errors['email']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="form-label" for="phone">Phone</label>
          <input class="form-control<?= isset($errors['phone']) ? ' is-invalid' : '' ?>" id="phone" name="phone" type="tel" maxlength="40" placeholder="+256 ..." value="<?= e($old['phone']) ?>">
          <?php if (isset($errors['phone'])): ?><span class="form-error"><?= e($errors['phone']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="form-label" for="whatsapp">WhatsApp</label>
          <input class="form-control<?= isset($errors['whatsapp']) ? ' is-invalid' : '' ?>" id="whatsapp" name="whatsapp" type="tel" maxlength="40" placeholder="+256 ..." value="<?= e($old['whatsapp']) ?>">
          <?php if (isset($errors['whatsapp'])): ?><span class="form-error"><?= e($errors['whatsapp']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="form-label" for="company">Company / Organization</label>
          <input class="form-control" id="company" name="company" type="text" maxlength="150" value="<?= e($old['company']) ?>">
        </div>
        <div class="form-group">
          <label class="form-label" for="service">Service Needed</label>
          <select class="form-control<?= isset($errors['service']) ? ' is-invalid' : '' ?>" id="service" name="service">
            <option value="">Select a service</option>
            <?php foreach ($services as $s): ?>
              <option value="<?= e($s['slug']) ?>" <?= $old['service'] === $s['slug'] ? ' selected' : '' ?>><?= e($s['title']) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if (isset($errors['service'])): ?><span class="form-error"><?= e($errors['service']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="form-label" for="budget">Budget</label>
          <select class="form-control" id="budget" name="budget">
            <option value="">Select a range</option>
            <?php foreach ($budgets as $b): ?>
              <option value="<?= e($b) ?>" <?= $old['budget'] === $b ? ' selected' : '' ?>><?= e($b) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group full">
          <label class="form-label" for="description">Project Description <span class="req">*</span></label>
          <textarea class="form-control<?= isset($errors['description']) ? ' is-invalid' : '' ?>" id="description" name="description" maxlength="5000" required placeholder="What problem are you trying to solve? What does success look like?"><?= e($old['description']) ?></textarea>
          <?php if (isset($errors['description'])): ?><span class="form-error"><?= e($errors['description']) ?></span><?php endif; ?>
        </div>
        <div class="form-group full">
          <button type="submit" class="btn btn-primary btn-lg">Send Request <i class="fa-solid fa-arrow-right"></i></button>
          <p class="text-muted" style="font-size:.8rem;margin:.75rem 0 0">Your information is stored securely and used only to respond to your request.</p>
        </div>
      </form>
    </div>
  </div>
</section>

<?= $this->endSection() ?>