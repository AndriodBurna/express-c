<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>


<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;gap:1rem;flex-wrap:wrap">
    <p class="text-muted" style="margin:0">
        <?= count($pages) ?> page<?= count($pages) === 1 ? '' : 's' ?> total
    </p>
    <a href="<?= url('control/pages/create') ?>" class="btn btn-primary btn-sm" style="width:auto">
        <i class="fa-solid fa-plus"></i> New Page
    </a>
</div>

<?php if ($pages): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Featured</th>
                <th>Active</th>
                <th>Order</th>
                <th>Updated</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($pages as $p): ?>
            <tr>
                <td><?= esc($p['title']) ?></td>
                <td><code><?= esc($p['slug']) ?></code></td>
                <td><?= $p['is_featured'] ? '★' : '—' ?></td>
                <td><?= $p['is_active'] ? '✅' : '⛔' ?></td>
                <td><?= (int) $p['sort_order'] ?></td>
                <td><?= esc(date('M j, Y', strtotime($p['updated_at'] ?? 'now'))) ?></td>
                <td>
                    <a href="<?= url('control/pages/' . $p['id'] . '/edit') ?>" class="btn btn-outline btn-sm">Edit</a>
                    <form method="post" action="<?= url('control/pages/' . $p['id'] . '/delete') ?>" style="display:inline" onsubmit="return confirm('Delete this page?')">
                        <?= csrf_field() ?>
                        <button class="btn btn-outline btn-sm" style="color:#c00;border-color:#c00">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="admin-card" style="text-align:center;padding:3rem 1.5rem">
            <i class="fa-solid fa-file-lines" style="font-size:2.5rem;color:var(--c-blue);opacity:.5;margin-bottom:1rem;display:block"></i>
            <h3 style="margin:0 0 .5rem">No pages yet</h3>
            <p class="text-muted" style="margin:0 0 1.5rem">Create your first custom page to get started.</p>
            <a href="<?= url('control/pages/create') ?>" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Create First Page
            </a>
        </div>
    <?php endif; ?>

<?= $this->endSection() ?>