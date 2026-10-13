<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h2>Quote Requests (<?= (int) $total ?>)</h2>
    <div class="tabs">
      <a class="<?= $filter === '' ? 'is-active' : '' ?>" href="<?= base_url('control/quotes') ?>">All</a>
      <?php foreach ($statuses as $s): ?>
        <a class="<?= $filter === $s ? 'is-active' : '' ?>" href="?status=<?= $s ?>"><?= ucwords(str_replace('_', ' ', $s)) ?></a>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Status</th>
          <th>Name</th>
          <th>Company</th>
          <th>Service</th>
          <th>Budget</th>
          <th>Received</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$rows): ?>
        <tr><td colspan="7" class="help">No quote requests found.</td></tr>
      <?php endif; ?>
      <?php foreach ($rows as $r): ?>
        <tr data-quote-id="<?= (int) $r['id'] ?>">
          <td><span class="badge <?= e($r['status'] ?? 'new') ?>"><?= e(str_replace('_', ' ', $r['status'] ?? 'new')) ?></span></td>
          <td><?= e($r['first_name'] . ' ' . $r['last_name']) ?><br><span class="help"><?= e($r['email']) ?></span></td>
          <td><?= e($r['company'] ?? '-') ?></td>
          <td><?= e($r['service_needed'] ?? '-') ?></td>
          <td><?= e($r['budget'] ?? '-') ?></td>
          <td><?= e(date('d M Y', strtotime($r['created_at']))) ?></td>
          <td class="actions">
            <a class="btn btn-outline btn-sm" href="<?= base_url('control/quotes/view/' . (int) $r['id']) ?>">
              <i class="fa-solid fa-eye"></i> Open
            </a>

            <form method="post" action="<?= base_url('control/quotes/delete') ?>" style="display:inline" data-confirm="Delete this quote request permanently?">
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
        <a class="<?= $i === $page ? 'is-active' : '' ?>" href="?<?= $filter ? 'status=' . $filter . '&' : '' ?>page=<?= $i ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Quote modal -->
<!-- <div class="modal" id="quoteModal" hidden>
  <div class="modal-backdrop" data-close-modal></div>
  <div class="modal-body">
    <button type="button" class="modal-close" data-close-modal aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
    <div id="quoteModalContent">
      <p class="text-muted">Loading&hellip;</p>
    </div>
  </div>
</div> -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
  const modal   = document.getElementById('quoteModal');
  const content = document.getElementById('quoteModalContent');
  const base    = '<?= base_url('control/quotes/view') ?>';
  const statuses = <?= json_encode($statuses) ?>;

  document.querySelectorAll('[data-open-quote]').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.getAttribute('data-open-quote');
      content.innerHTML = '<p class="text-muted">Loading&hellip;</p>';
      modal.hidden = false;
      try {
        const r = await fetch(base + '/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const d = await r.json();
        if (d.error) { content.innerHTML = '<p class="text-muted">Quote not found.</p>'; return; }

        const options = statuses.map(s =>
          `<option value="${s}" ${s === d.status ? 'selected' : ''}>${s.replace('_', ' ')}</option>`
        ).join('');

        content.innerHTML = `
          <p class="eyebrow gold">Quote #${d.id}</p>
          <h2 style="margin:0 0 .25rem">${escapeHtml(d.first_name + ' ' + d.last_name)}</h2>
          <p class="text-muted" style="margin:0 0 1rem;font-size:.85rem">
            ${escapeHtml(d.email)}
            ${d.phone ? ' &middot; ' + escapeHtml(d.phone) : ''}
            ${d.whatsapp ? ' &middot; WhatsApp: ' + escapeHtml(d.whatsapp) : ''}
            &middot; ${escapeHtml(d.created_at)}
          </p>

          <dl class="quote-meta" style="display:grid;grid-template-columns:140px 1fr;gap:.5rem;margin:0 0 1.25rem">
            <dt class="text-muted">Company</dt><dd style="margin:0">${escapeHtml(d.company || '—')}</dd>
            <dt class="text-muted">Service</dt><dd style="margin:0">${escapeHtml(d.service_needed || '—')}</dd>
            <dt class="text-muted">Budget</dt><dd style="margin:0">${escapeHtml(d.budget || '—')}</dd>
          </dl>

          <h3 style="margin:1rem 0 .5rem;font-size:1rem">Project Description</h3>
          <div class="rich" style="white-space:pre-wrap">${escapeHtml(d.description)}</div>

          <form method="post" action="<?= base_url('control/quotes/status') ?>" style="margin-top:1.5rem;display:flex;gap:.5rem;align-items:center">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="${d.id}">
            <label class="text-muted" style="font-size:.85rem">Update status:</label>
            <select name="status" class="form-control" style="max-width:200px">${options}</select>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
          </form>
        `;
      } catch (e) {
        content.innerHTML = '<p class="text-muted">Failed to load quote.</p>';
      }
    });
  });

  document.querySelectorAll('[data-close-modal]').forEach(el => {
    el.addEventListener('click', () => { modal.hidden = true; });
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') modal.hidden = true; });

  function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  }
})();
</script>
<?= $this->endSection() ?>