<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<?php if ($edit !== null): ?>
<div class="modal-overlay is-open" id="trainingFormModal" data-clear-params="edit,new">
  <div class="modal modal-md" role="dialog" aria-modal="true" aria-labelledby="trainingFormTitle">
    <div class="modal-header">
      <h2 id="trainingFormTitle"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i> <?= $edit['id'] ? 'Edit Course' : 'New Course' ?></h2>
      <a class="modal-close" href="<?= base_url('control/training') ?>" aria-label="Close"><i class="fa-solid fa-xmark" aria-hidden="true"></i></a>
    </div>

    <form method="post"
          action="<?= $edit['id']
              ? base_url('control/training/update/' . $edit['id'])
              : base_url('control/training/store') ?>"
          class="form-grid modal-body">
      <?= csrf_field() ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error full"><?= e(implode(' ', $errors)) ?></div>
      <?php endif; ?>

      <div class="form-group"><label for="title">Title *</label><input class="form-control" id="title" name="title" required value="<?= e($edit['title']) ?>"></div>
      <div class="form-group"><label for="slug">Slug</label><input class="form-control" id="slug" name="slug" value="<?= e($edit['slug']) ?>"></div>
      <div class="form-group"><label for="icon">Font Awesome icon class</label><input class="form-control" id="icon" name="icon" value="<?= e($edit['icon']) ?>"></div>
      <div class="form-group"><label for="level">Level</label><input class="form-control" id="level" name="level" value="<?= e($edit['level']) ?>"></div>
      <div class="form-group"><label for="duration">Duration</label><input class="form-control" id="duration" name="duration" value="<?= e($edit['duration']) ?>"></div>
      <div class="form-group"><label for="sort_order">Sort order</label><input class="form-control" id="sort_order" name="sort_order" type="number" value="<?= (int) $edit['sort_order'] ?>"></div>
      <div class="form-group full"><label for="short_description">Short description *</label><textarea class="form-control" id="short_description" name="short_description" maxlength="400" style="min-height:70px" required><?= e($edit['short_description']) ?></textarea></div>
      <div class="form-group full"><label for="long_description">Long description</label><textarea class="form-control js-editor" id="long_description" name="long_description" data-placeholder="Describe what this course covers…"><?= e($edit['long_description']) ?></textarea><span class="editor-hint">Use the toolbar for headings, lists, quotes and links.</span></div>
      <div class="form-group full"><label for="topics">Topics (one per line)</label><textarea class="form-control" id="topics" name="topics"><?= e($edit['topics']) ?></textarea></div>
      <div class="form-group full"><label><input type="checkbox" name="is_active" value="1" <?= !empty($edit['is_active']) ? 'checked' : '' ?>> Active</label></div>
      <div class="form-group full" style="display:flex;gap:.6rem;justify-content:flex-end">
        <a class="btn btn-outline" href="<?= base_url('control/training') ?>">Cancel</a>
        <button class="btn btn-primary" type="submit">Save Course</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <h2>All Courses (<?= count($courses) ?>)</h2>
    <a class="btn btn-primary btn-sm" href="?new=1"><i class="fa-solid fa-plus"></i> New Course</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Icon</th><th>Title</th><th>Level</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($courses as $c): ?>
        <tr>
          <td><?= (int) $c['sort_order'] ?></td>
          <td><i class="<?= e($c['icon']) ?>"></i></td>
          <td><strong><?= e($c['title']) ?></strong><br><span class="help">/<?= e($c['slug']) ?></span></td>
          <td><?= e($c['level'] ?? '-') ?></td>
          <td><span class="badge <?= $c['is_active'] ? 'active' : 'inactive' ?>"><?= $c['is_active'] ? 'Active' : 'Hidden' ?></span></td>
          <td class="actions">
            <a class="btn btn-outline btn-sm" href="?edit=<?= (int) $c['id'] ?>">Edit</a>
            <form method="post" action="<?= base_url('control/training/delete') ?>" style="display:inline" data-confirm="Delete this course permanently?">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
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