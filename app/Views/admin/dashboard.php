<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php if (isset($missing)): ?>
  <div class="alert alert-error"><strong>Setup reminder:</strong> the following contact details are empty and hidden on
    the website: <?= e(implode(', ', $missing)) ?>. <a href="<?= admin_url('settings.php') ?>"><u>Update settings</u></a>.
  </div>
<?php endif; ?>
<div class="stats">
  <div class="stat-card"><i class="fa-solid fa-file-invoice"></i>
    <div><strong><?= $stat['quotes_new'] ?></strong><span>New quote requests</span></div>
  </div>
  <div class="stat-card"><i class="fa-solid fa-inbox"></i>
    <div><strong><?= $stat['messages_unread'] ?></strong><span>Unread messages</span></div>
  </div>
  <div class="stat-card gold"><i class="fa-solid fa-diagram-project"></i>
    <div><strong><?= $stat['projects'] ?></strong><span>Published projects</span></div>
  </div>
  <div class="stat-card"><i class="fa-solid fa-layer-group"></i>
    <div><strong><?= $stat['services'] ?></strong><span>Active services</span></div>
  </div>
  <div class="stat-card gold"><i class="fa-solid fa-graduation-cap"></i>
    <div><strong><?= $stat['courses'] ?></strong><span>Training courses</span></div>
  </div>
</div>
<div class="card">
  <div class="card-header">
    <h2>Recent Quote Requests</h2><a class="btn btn-outline btn-sm" href="<?= admin_url('quotes.php') ?>">View all</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Service</th>
          <th>Budget</th>
          <th>Status</th>
          <th>Received</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$recent_quotes): ?>
          <tr>
            <td colspan="7" class="help">No quote requests yet.</td>
          </tr><?php endif; ?>
        <?php foreach ($recent_quotes as $q): ?>
          <tr>
            <td><?= e($q['first_name'] . ' ' . $q['last_name']) ?></td>
            <td><a href="mailto:<?= e($q['email']) ?>"><?= e($q['email']) ?></a></td>
            <td><?= e($q['service_needed'] ?? '-') ?></td>
            <td><?= e($q['budget'] ?? '-') ?></td>
            <td><span class="badge <?= e($q['status']) ?>"><?= e(str_replace('_', ' ', $q['status'])) ?></span></td>
            <td><?= e(date('d M Y, H:i', strtotime($q['created_at']))) ?></td>
            <td><a class="btn btn-outline btn-sm" href="<?= admin_url('quotes.php?view=' . (int) $q['id']) ?>">Open</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>