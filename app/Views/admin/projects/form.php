<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <h2><?= $edit['id'] ? 'Edit Project' : 'New Project' ?></h2>
    <a class="btn btn-outline btn-sm" href="<?= base_url('control/projects') ?>">Cancel</a>
  </div>

  <form method="post"
        enctype="multipart/form-data"
        action="<?= $edit['id']
            ? base_url('control/projects/update/' . $edit['id'])
            : base_url('control/projects/store') ?>"
        class="form-grid">
    <?= csrf_field() ?>

    <div class="form-group">
      <label for="title">Title *</label>
      <input class="form-control" id="title" name="title" required value="<?= e($edit['title']) ?>">
    </div>

    <div class="form-group">
      <label for="slug">Slug (URL)</label>
      <input class="form-control" id="slug" name="slug" value="<?= e($edit['slug']) ?>">
      <span class="editor-hint">Leave empty to auto-generate from title.</span>
    </div>

    <div class="form-group">
      <label for="category_id">Category</label>
      <select class="form-control" id="category_id" name="category_id">
        <option value="">None</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= (int) $c['id'] ?>" <?= (int) $edit['category_id'] === (int) $c['id'] ? ' selected' : '' ?>>
            <?= e($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="client_name">Client (optional)</label>
      <input class="form-control" id="client_name" name="client_name" value="<?= e($edit['client_name']) ?>">
    </div>

    <div class="form-group full">
      <label for="short_description">Short description *</label>
      <textarea class="form-control" id="short_description" name="short_description"
                maxlength="400" style="min-height:70px" required><?= e($edit['short_description']) ?></textarea>
    </div>

    <div class="form-group full">
      <label for="overview">Overview</label>
      <textarea class="form-control js-editor" id="overview" name="overview"
                data-placeholder="Set the scene: what was this project about?"><?= e($edit['overview']) ?></textarea>
      <span class="editor-hint">Formatting toolbar available: headings, lists, quotes, links.</span>
    </div>

    <div class="form-group">
      <label for="problem">Problem</label>
      <textarea class="form-control js-editor" id="problem" name="problem"
                data-placeholder="What problem did the client face?"><?= e($edit['problem']) ?></textarea>
    </div>

    <div class="form-group">
      <label for="challenge">Challenge</label>
      <textarea class="form-control js-editor" id="challenge" name="challenge"
                data-placeholder="What made it difficult?"><?= e($edit['challenge']) ?></textarea>
    </div>

    <div class="form-group full">
      <label for="solution">Solution</label>
      <textarea class="form-control js-editor" id="solution" name="solution"
                data-placeholder="How did you solve it?"><?= e($edit['solution']) ?></textarea>
    </div>

    <div class="form-group">
      <label for="technologies">Technologies (comma separated)</label>
      <input class="form-control" id="technologies" name="technologies" value="<?= e($edit['technologies']) ?>">
    </div>

    <div class="form-group">
      <label for="features">Key features (one per line)</label>
      <textarea class="form-control" id="features" name="features"><?= e($edit['features']) ?></textarea>
    </div>

    <div class="form-group full">
      <label for="results">Results (only genuine, verifiable outcomes)</label>
      <textarea class="form-control js-editor" id="results" name="results"
                data-placeholder="What measurable outcomes were achieved?"><?= e($edit['results']) ?></textarea>
    </div>

    <div class="form-group">
      <label for="project_url">Live URL</label>
      <input class="form-control" id="project_url" name="project_url" type="url" value="<?= e($edit['project_url']) ?>">
    </div>

    <div class="form-group">
      <label for="year">Year</label>
      <input class="form-control" id="year" name="year" maxlength="10" value="<?= e($edit['year']) ?>">
    </div>

    <div class="form-group">
      <label for="image">Image (JPG/PNG/WEBP, max 2 MB)</label>
      <input class="form-control" id="image" name="image" type="file" accept="image/*">
      <?php if (!empty($edit['image'])): ?>
        <img class="thumb" src="<?= e(upload_url($edit['image'])) ?>" alt="" style="margin-top:.5rem;max-width:120px;border-radius:8px;">
      <?php endif; ?>
    </div>

    <div class="form-group">
      <label for="sort_order">Sort order</label>
      <input class="form-control" id="sort_order" name="sort_order" type="number" value="<?= (int) $edit['sort_order'] ?>">
    </div>

    <div class="form-group full" style="display:flex;gap:2rem">
      <label><input type="checkbox" name="is_featured" value="1" <?= !empty($edit['is_featured']) ? 'checked' : '' ?>> Featured on homepage</label>
      <label><input type="checkbox" name="is_active" value="1" <?= !empty($edit['is_active']) ? 'checked' : '' ?>> Published</label>
    </div>

    <div class="form-group full">
      <button class="btn btn-primary" type="submit">Save Project</button>
    </div>
  </form>
</div>

<?= $this->endSection() ?>