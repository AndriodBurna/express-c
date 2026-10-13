<?= $this->extend('layouts/admin') ?>

<?= $this->section('header_actions') ?>
    <a href="<?= url('control/pages') ?>" class="btn btn-outline btn-sm">Back to list</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php $action = $page ? url('control/pages/' . $page['id'] . '/update') : url('control/pages'); ?>

<div class="admin-card">
    <form method="post" action="<?= $action ?>" class="form-grid">
        <?= csrf_field() ?>

        <div class="form-group full">
            <label class="form-label" for="title">Title <span class="req">*</span></label>
            <input class="form-control" id="title" name="title" type="text" maxlength="150" required
                   value="<?= esc(old('title', $page['title'] ?? '')) ?>">
        </div>

        <div class="form-group full">
            <label class="form-label" for="slug">Slug</label>
            <input class="form-control" id="slug" name="slug" type="text" maxlength="120"
                   placeholder="Leave blank to auto-generate from title"
                   value="<?= esc(old('slug', $page['slug'] ?? '')) ?>">
            <p class="text-muted" style="font-size:.8rem;margin:.25rem 0 0">
                Public URL will be <code>/page/<?= esc($page['slug'] ?? 'your-slug') ?></code>
            </p>
        </div>

        <div class="form-group full">
            <label class="form-label" for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="14"><?= esc(old('content', $page['content'] ?? '')) ?></textarea>
            <p class="text-muted" style="font-size:.8rem;margin:.25rem 0 0">HTML is allowed and will be sanitized.</p>
        </div>

        <div class="form-group">
            <label class="form-label" for="sort_order">Sort Order</label>
            <input class="form-control" id="sort_order" name="sort_order" type="number" min="0"
                   value="<?= esc(old('sort_order', $page['sort_order'] ?? 0)) ?>">
        </div>

        <div class="form-group">
            <label class="form-label">
                <input type="checkbox" name="is_featured" value="1" <?= !empty($page['is_featured']) ? 'checked' : '' ?>>
                Feature on homepage
            </label>
            <label class="form-label" style="margin-top:.5rem">
                <input type="checkbox" name="is_active" value="1" <?= (!isset($page) || !empty($page['is_active'])) ? 'checked' : '' ?>>
                Active (visible on site)
            </label>
        </div>

        <div class="form-group full" style="display:flex;justify-content:flex-end;gap:.75rem;margin-top:1rem">
            <a href="<?= url('control/pages') ?>" class="btn btn-outline" style="width:auto;min-width:120px">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary" style="width:auto;min-width:160px">
                <i class="fa-solid fa-<?= $page ? 'floppy-disk' : 'plus' ?>"></i>
                <?= $page ? 'Save Changes' : 'Create Page' ?>
            </button>
        </div>                                                                                                                  
    </form>
</div>

<?= $this->endSection() ?>