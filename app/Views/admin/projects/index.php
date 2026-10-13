<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h2>All Projects (<?= count($projects) ?>)</h2>
    <a class="btn btn-primary btn-sm" href="<?= base_url('control/projects/create') ?>"><i class="fa-solid fa-plus"></i> New Project</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Image</th>
          <th>Title</th>
          <th>Category</th>
          <th>Featured</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($projects as $p): ?>
        <tr>
          <td><?= (int) $p['sort_order'] ?></td>
          <td>
            <?php if ($p['image']): ?>
              <img class="thumb" src="<?= e(upload_url($p['image'])) ?>" alt="">
            <?php else: ?>
              <span class="help">none</span>
            <?php endif; ?>
          </td>
          <td><strong><?= e($p['title']) ?></strong><br><span class="help">/<?= e($p['slug']) ?></span></td>
          <td><?= e($p['category_name'] ?? '-') ?></td>
          <td><?= $p['is_featured'] ? '<span class="badge active">Yes</span>' : '-' ?></td>
          <td><span class="badge <?= $p['is_active'] ? 'active' : 'inactive' ?>"><?= $p['is_active'] ? 'Published' : 'Hidden' ?></span></td>
          <td class="actions">
            <a class="btn btn-outline btn-sm" href="<?= url('project/' . urlencode($p['slug'])) ?>" target="_blank" rel="noopener">View</a>
            <a class="btn btn-outline btn-sm" href="<?= base_url('control/projects/edit/' . $p['id']) ?>">Edit</a>
            <form method="post" action="<?= base_url('control/projects/delete') ?>" style="display:inline" data-confirm="Delete this project permanently?">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
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