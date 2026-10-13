<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h2><?= e($message['subject'] ?: '(No subject)') ?></h2>
    <a class="btn btn-outline btn-sm" href="<?= base_url('control/messages') ?>">
      <i class="fa-solid fa-arrow-left"></i> Back to Messages
    </a>
  </div>

  <div style="padding:1.5rem">

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.25rem;margin-bottom:1.75rem">
      <div>
        <small class="text-muted" style="display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;color:#888">From</small>
        <strong><?= e($message['first_name'] . ' ' . $message['last_name']) ?></strong>
      </div>
      <div>
        <small class="text-muted" style="display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;color:#888">Email</small>
        <a href="mailto:<?= e($message['email']) ?>"><?= e($message['email']) ?></a>
      </div>
      <?php if (!empty($message['phone'])): ?>
        <div>
          <small class="text-muted" style="display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;color:#888">Phone</small>
          <a href="tel:<?= e($message['phone']) ?>"><?= e($message['phone']) ?></a>
        </div>
      <?php endif; ?>
      <div>
        <small class="text-muted" style="display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;color:#888">Received</small>
        <?= e(date('d M Y, H:i', strtotime($message['created_at']))) ?>
      </div>
      <?php if (!empty($message['ip_address'])): ?>
        <div>
          <small class="text-muted" style="display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;color:#888">IP Address</small>
          <?= e($message['ip_address']) ?>
        </div>
      <?php endif; ?>
    </div>

    <hr style="border:0;border-top:1px solid #eee;margin:1.5rem 0">

    <h3 style="margin:0 0 .75rem;font-size:1rem">Message</h3>
    <div class="rich" style="white-space:pre-wrap;line-height:1.7;color:#333"><?= e($message['message']) ?></div>

    <div style="margin-top:2rem;display:flex;gap:.6rem;flex-wrap:wrap">
      <a class="btn btn-primary" href="mailto:<?= e($message['email']) ?>?subject=Re: <?= e($message['subject'] ?: 'Your message') ?>">
        <i class="fa-solid fa-reply"></i> Reply by Email
      </a>

      <form method="post" action="<?= base_url('control/messages/toggle-read') ?>" style="display:inline">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $message['id'] ?>">
        <button class="btn btn-outline" type="submit">
          <i class="fa-solid fa-envelope-open"></i> Mark as unread
        </button>
      </form>

      <form method="post" action="<?= base_url('control/messages/delete') ?>" style="display:inline" data-confirm="Delete this message permanently?">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $message['id'] ?>">
        <button class="btn btn-danger" type="submit">
          <i class="fa-solid fa-trash"></i> Delete Message
        </button>
      </form>
    </div>

  </div>
</div>

<?= $this->endSection() ?>