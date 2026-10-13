<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<?php if ($edit !== null): ?>
<div class="modal-overlay is-open" id="serviceFormModal" data-clear-params="edit,new">
  <div class="modal modal-md" role="dialog" aria-modal="true" aria-labelledby="serviceFormTitle">
    <div class="modal-header">
      <h2 id="serviceFormTitle"><i class="fa-solid fa-layer-group" aria-hidden="true"></i> <?= $edit['id'] ? 'Edit Service' : 'New Service' ?></h2>
      <a class="modal-close" href="<?= base_url('control/services') ?>" aria-label="Close"><i class="fa-solid fa-xmark" aria-hidden="true"></i></a>
    </div>

    <form method="post"
          action="<?= $edit['id']
              ? base_url('control/services/update/' . $edit['id'])
              : base_url('control/services/store') ?>"
          class="form-grid modal-body">
      <?= csrf_field() ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error full"><?= e(implode(' ', $errors)) ?></div>
      <?php endif; ?>

      <div class="form-group"><label for="title">Title *</label><input class="form-control" id="title" name="title" required value="<?= e($edit['title']) ?>"></div>
      <div class="form-group"><label for="slug">Slug</label><input class="form-control" id="slug" name="slug" value="<?= e($edit['slug']) ?>"></div>
      <div class="form-group"><label for="label">Short label (e.g. Software)</label><input class="form-control" id="label" name="label" value="<?= e($edit['label']) ?>"></div>
      <div class="form-group"><label for="icon">Font Awesome icon class</label><input class="form-control" id="icon" name="icon" value="<?= e($edit['icon']) ?>"><span class="help">Example: fa-solid fa-code</span></div>
      <div class="form-group"><label for="accent">Accent color</label>
        <select class="form-control" id="accent" name="accent">
          <?php foreach ($accents as $a): ?>
            <option value="<?= $a ?>" <?= $edit['accent'] === $a ? 'selected' : '' ?>><?= ucfirst($a) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group"><label for="sort_order">Sort order</label><input class="form-control" id="sort_order" name="sort_order" type="number" value="<?= (int) $edit['sort_order'] ?>"></div>
      <div class="form-group full"><label for="short_description">Short description *</label><textarea class="form-control" id="short_description" name="short_description" maxlength="400" style="min-height:70px" required><?= e($edit['short_description']) ?></textarea></div>
      <div class="form-group full"><label for="long_description">Long description</label><textarea class="form-control js-editor" id="long_description" name="long_description" data-placeholder="Describe this service in detail…"><?= e($edit['long_description']) ?></textarea><span class="editor-hint">Use the toolbar for headings, lists, quotes and links.</span></div>
      <div class="form-group full"><label for="features">Features (one per line)</label><textarea class="form-control" id="features" name="features"><?= e($edit['features']) ?></textarea></div>
      <div class="form-group full"><label><input type="checkbox" name="is_active" value="1" <?= !empty($edit['is_active']) ? 'checked' : '' ?>> Active</label></div>
      <div class="form-group full" style="display:flex;gap:.6rem;justify-content:flex-end">
        <a class="btn btn-outline" href="<?= base_url('control/services') ?>">Cancel</a>
        <button class="btn btn-primary" type="submit">Save Service</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <h2>All Services (<?= count($services) ?>)</h2>
    <a class="btn btn-primary btn-sm" href="?new=1"><i class="fa-solid fa-plus"></i> New Service</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Icon</th><th>Title</th><th>Accent</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($services as $s): ?>
        <tr>
          <td><?= (int) $s['sort_order'] ?></td>
          <td><i class="<?= e($s['icon']) ?>"></i></td>
          <td><strong><?= e($s['title']) ?></strong><br><span class="help">/<?= e($s['slug']) ?></span></td>
          <td><span class="badge"><?= e($s['accent']) ?></span></td>
          <td><span class="badge <?= $s['is_active'] ? 'active' : 'inactive' ?>"><?= $s['is_active'] ? 'Active' : 'Hidden' ?></span></td>
          <td class="actions">
            <a class="btn btn-outline btn-sm" href="?edit=<?= (int) $s['id'] ?>">Edit</a>
            <form method="post" action="<?= base_url('control/services/delete') ?>" style="display:inline" data-confirm="Delete this service? Quote requests linked to it will keep their service name.">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
              <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>