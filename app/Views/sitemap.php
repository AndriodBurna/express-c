<?= '<?xml version="1.0" encoding="UTF-8"?>' . "\n" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($static as $path => $priority): ?>
  <url><loc><?= e(url($path)) ?></loc><priority><?= $priority ?></priority></url>
<?php endforeach; ?>
<?php foreach ($projects as $p): ?>
  <url><loc><?= e(url('project/' . urlencode($p['slug']))) ?></loc><lastmod><?= date('Y-m-d', strtotime($p['updated_at'])) ?></lastmod><priority>0.6</priority></url>
<?php endforeach; ?>
</urlset>