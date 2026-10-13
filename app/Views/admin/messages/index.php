<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header"><h2>All Messages (<?= (int) $total ?>)</h2></div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Status</th>
          <th>Name</th>
          <th>Email</th>
          <th>Subject</th>
          <th>Received</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$rows): ?>
        <tr><td colspan="6" class="help">No messages yet.</td></tr>
      <?php endif; ?>
      <?php foreach ($rows as $r): ?>
        <tr data-message-id="<?= (int) $r['id'] ?>">
          <td><span class="badge <?= $r['is_read'] ? 'closed' : 'new' ?>"><?= $r['is_read'] ? 'Read' : 'Unread' ?></span></td>
          <td><?= e($r['first_name'] . ' ' . $r['last_name']) ?></td>
          <td><?= e($r['email']) ?></td>
          <td><?= e(mb_strimwidth($r['subject'] ?? $r['message'], 0, 50, '...')) ?></td>
          <td><?= e(date('d M Y', strtotime($r['created_at']))) ?></td>
          <td class="actions">
            <a class="btn btn-outline btn-sm" href="<?= base_url('control/messages/view/' . (int) $r['id']) ?>">
              <i class="fa-solid fa-eye"></i> Open
            </a>

            <form method="post" action="<?= base_url('control/messages/toggle-read') ?>" style="display:inline">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
              <button class="btn btn-outline btn-sm" type="submit">
                <?= $r['is_read'] ? 'Mark unread' : 'Mark read' ?>
              </button>
            </form>

            <form method="post" action="<?= base_url('control/messages/delete') ?>" style="display:inline" data-confirm="Delete this message permanently?">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
              <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if ($pages > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a class="<?= $i === $page ? 'is-active' : '' ?>" href="?page=<?= $i ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Message modal -->
<div class="modal" id="messageModal" style="display:none;position:fixed;inset:0;z-index:1000;align-items:center;justify-content:center;padding:1.5rem;background:rgba(0,0,0,.6)">
  <div class="modal-backdrop" data-close-modal></div>
  <div class="modal-body">
    <button type="button" class="modal-close" data-close-modal aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
    <div id="messageModalContent">
      <p class="text-muted">Loading&hellip;</p>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
  const modal   = document.getElementById('messageModal');
  const content = document.getElementById('messageModalContent');
  const base    = '<?= base_url('control/messages/view') ?>';

  document.querySelectorAll('[data-open-message]').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.getAttribute('data-open-message');
      content.innerHTML = '<p class="text-muted">Loading&hellip;</p>';
      modal.hidden = false;
      try {
        const r = await fetch(base + '/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const d = await r.json();
        if (d.error) { content.innerHTML = '<p class="text-muted">Message not found.</p>'; return; }
        content.innerHTML = `
          <p class="eyebrow gold">Message #${d.id}</p>
          <h2 style="margin:0 0 .25rem">${escapeHtml(d.subject || '(no subject)')}</h2>
          <p class="text-muted" style="margin:0 0 1rem;font-size:.85rem">
            From <strong>${escapeHtml(d.first_name + ' ' + d.last_name)}</strong>
            &lt;${escapeHtml(d.email)}&gt;
            ${d.phone ? ' &middot; ' + escapeHtml(d.phone) : ''}
            &middot; ${escapeHtml(d.created_at)}
          </p>
          <div class="rich" style="white-space:pre-wrap">${escapeHtml(d.message)}</div>
          <div style="margin-top:1.25rem">
            <a class="btn btn-primary btn-sm" href="mailto:${escapeHtml(d.email)}">Reply by Email</a>
          </div>
        `;
        // Update badge in the table to "Read"
        const row = document.querySelector(`tr[data-message-id="${id}"]`);
        if (row) {
          const badge = row.querySelector('.badge');
          if (badge) { badge.classList.remove('new'); badge.classList.add('closed'); badge.textContent = 'Read'; }
        }
      } catch (e) {
        content.innerHTML = '<p class="text-muted">Failed to load message.</p>';
      }
    });
  });

  document.querySelectorAll('[data-close-modal]').forEach(el => {
    el.addEventListener('click', () => { modal.hidden = true; });
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') modal.hidden = true; });

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  }
})();
</script>
<?= $this->endSection() ?>