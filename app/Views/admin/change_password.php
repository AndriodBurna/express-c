<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="admin-page-header">
  <h2>Change Password</h2>
  <?php if (!empty($forced)): ?>
    <p class="text-muted">You must set a new password before continuing.</p>
  <?php endif; ?>
</div>

<?php if (!empty($error)): ?>
  <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
  <div class="alert alert-success"><?= e($success) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('control/change-password') ?>" class="admin-form" style="max-width:520px">
  <?= csrf_field() ?>

  <div class="form-group">
    <label class="form-label">Current Password</label>
    <input class="form-control" type="password" name="current_password" required>
  </div>
  <div class="form-group">
    <label class="form-label">New Password</label>
    <input class="form-control" type="password" name="new_password" required minlength="10">
    <small class="text-muted">At least 10 characters with uppercase, lowercase and a number.</small>
  </div>
  <div class="form-group">
    <label class="form-label">Confirm New Password</label>
    <input class="form-control" type="password" name="confirm_password" required>
  </div>

  <button type="submit" class="btn btn-primary">Update Password <i class="fa-solid fa-check"></i></button>
</form>

<?= $this->endSection() ?>