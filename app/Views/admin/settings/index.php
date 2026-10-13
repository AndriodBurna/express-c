<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('control/settings/save') ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <?php foreach ($groups as $g => $items): ?>
    <div class="card">
      <div class="card-header"><h2><?= e($group_titles[$g] ?? ucfirst($g)) ?></h2></div>
      <div class="form-grid">

        <?php foreach ($items as $s):
          $key   = $s['setting_key'];
          $full  = $s['input_type'] === 'textarea';
          $type  = $s['input_type'];
          $inputType = match ($type) {
              'url'   => 'url',
              'email' => 'email',
              'number'=> 'number',
              default => 'text',
          };
        ?>
          <div class="form-group<?= $full ? ' full' : '' ?>">
            <label for="<?= e($key) ?>"><?= e($s['label']) ?></label>

            <?php if ($key === 'visible_pages'): ?>
              <div class="checkbox-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.75rem .9rem;">
                <?php foreach ([
                  ['about',     'About'],
                  ['services',  'Services'],
                  ['portfolio', 'Portfolio'],
                  ['training',  'Training'],
                  ['quote',     'Quote'],
                  ['contact',   'Contact'],
                ] as [$slug, $label]): ?>
                  <?php $visible = in_array($slug, visible_page_keys(), true); ?>
                  <label style="display:flex;align-items:center;gap:.5rem;">
                    <input type="checkbox" name="visible_pages[]" value="<?= e($slug) ?>" <?= $visible ? 'checked' : '' ?>>
                    <span><?= e($label) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
              <span class="help">Tick the pages you want visible on the website. Unticked pages will be hidden from the menu and blocked from direct access.</span>

            <?php elseif ($full): ?>
              <textarea class="form-control" id="<?= e($key) ?>" name="<?= e($key) ?>"><?= e($s['setting_value']) ?></textarea>

            <?php else: ?>
              <input class="form-control"
                     id="<?= e($key) ?>"
                     name="<?= e($key) ?>"
                     type="<?= $inputType ?>"
                     value="<?= e($s['setting_value']) ?>">
            <?php endif; ?>

            <?php if ($key === 'logo_path'): ?>
              <input class="form-control" type="file" name="logo_file" accept="image/png,image/jpeg,image/webp,image/svg+xml">
              <span class="help">Upload a logo (PNG/SVG recommended, max 2 MB). Leave empty to use the text logo.</span>
              <?php if (setting('logo_path') !== ''): ?>
                <img class="thumb" src="<?= e(upload_url(setting('logo_path'))) ?>" alt="Logo preview" style="max-width:160px;margin-top:.5rem;">
              <?php endif; ?>
            <?php endif; ?>

            <?php if ($key === 'about_image_path'): ?>
              <input class="form-control" type="file" name="about_image_file" accept="image/png,image/jpeg,image/webp,image/svg+xml">
              <span class="help">Upload the homepage feature image. It will cover the whole card and the text stays on top.</span>
              <?php if (setting('about_image_path') !== ''): ?>
                <img class="thumb" src="<?= e(upload_url(setting('about_image_path'))) ?>" alt="About image preview" style="max-width:200px;margin-top:.5rem;">
              <?php endif; ?>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="card">
    <div class="form-group full" style="display:flex;justify-content:flex-end">
      <button class="btn btn-primary" type="submit">Save Settings</button>
    </div>
  </div>
</form>

<?= $this->endSection() ?>