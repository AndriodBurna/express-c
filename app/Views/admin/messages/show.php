<?= $this->extend('layouts/admin') ?>

<?= $this->section('header_actions') ?>
    <a href="<?= url('control/messages') ?>" class="btn btn-outline btn-sm">Back to list</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="admin-card">
    <h2><?= esc($message['subject'] ?? 'No subject') ?></h2>
    <table class="admin-table">
        <tr><th>From</th><td><?= esc(($message['first_name'] ?? '') . ' ' . ($message['last_name'] ?? '')) ?></td></tr>
        <tr><th>Email</th><td><a href="mailto:<?= esc($message['email']) ?>"><?= esc($message['email']) ?></a></td></tr>
        <tr><th>Phone</th><td><?= esc($message['phone'] ?? '—') ?></td></tr>
        <tr><th>Received</th><td><?= esc(date('M j, Y H:i', strtotime($message['created_at'] ?? 'now'))) ?></td></tr>
        <tr><th>IP</th><td><?= esc($message['ip_address'] ?? '—') ?></td></tr>
        <tr><th>Status</th><td><span class="badge badge-<?= esc($message['status'] ?? 'new') ?>"><?= esc($message['status'] ?? 'new') ?></span></td></tr>
    </table>
</div>

<div class="admin-card">
    <h2>Message</h2>
    <p style="white-space:pre-wrap"><?= esc($message['message'] ?? '') ?></p>
</div>

<div class="admin-card">
    <h2>Update Status</h2>
    <form method="post" action="<?= url('control/messages/' . $message['id'] . '/status') ?>" style="display:flex;gap:.5rem;flex-wrap:wrap">
        <?= csrf_field() ?>
        <select name="status" class="form-control" style="max-width:200px">
            <?php foreach (['new','read','replied','closed'] as $s): ?>
                <option value="<?= $s ?>" <?= ($message['status'] ?? 'new') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-primary">Save</button>
    </form>
    <form method="post" action="<?= url('control/messages/' . $message['id'] . '/delete') ?>" onsubmit="return confirm('Delete this message permanently?')" style="margin-top:1rem">
        <?= csrf_field() ?>
        <button class="btn btn-outline" style="color:#c00;border-color:#c00">Delete</button>
    </form>
</div>

<?= $this->endSection() ?>