<?php
/**
 * Portfolio card partial. Expects $p (project row) and $i (index).
 */
$is_wide = isset($i) && $i === 0 && !empty($wide_first);
?>
<a href="<?= url('project/' . urlencode($p['slug'])) ?>" ...>
  <div class="project-media">
    <?php $fallback_image = !empty($p['image']) ? '' : default_project_image($p['slug'] ?? ''); ?>
      <?php if (!empty($p['image'])): ?>
        <img src="<?= e(upload_url($p['image'])) ?>" alt="<?= e($p['title']) ?> screenshot" loading="lazy" width="800" height="500">
      <?php elseif ($fallback_image !== ''): ?>
        <img src="<?= e(asset('images/' . $fallback_image)) ?>" alt="<?= e($p['title']) ?> screenshot" loading="lazy" width="800" height="500">
      <?php else: ?>
      <div class="project-placeholder" aria-hidden="true">
        <div class="mock">
          <div class="mock-bar w40"></div>
          <div class="mock-bar w80"></div>
          <div class="mock-bar w60"></div>
          <div class="mock-bar gold"></div>
          <div class="mock-grid"><span></span><span></span><span></span></div>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <div class="project-body">
    <div class="project-meta">
      <span class="num-label"><?= str_pad((string) ($p['sort_order'] ?: $p['id']), 2, '0', STR_PAD_LEFT) ?></span>
      <span><?= e($p['category_name'] ?? 'Project') ?></span>
    </div>
    <h3><?= e($p['title']) ?></h3>
    <p><?= e($p['short_description']) ?></p>
    <?php $tech = tags($p['technologies'] ?? ''); if ($tech): ?>
      <div class="tag-list">
        <?php foreach (array_slice($tech, 0, 4) as $t): ?><span class="tag"><?= e($t) ?></span><?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="project-view"><span>View Project</span><i class="fa-solid fa-arrow-right"></i></div>
  </div>
</a>
