<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$pageClass = 'page-gallery';
$pageTitle = 'Gallery - ' . setting('school_name');
$intro = page_content('gallery', 'intro');
$galleryItems = page_rows('gallery_items');

require __DIR__ . '/../components/meta.php';
require __DIR__ . '/../components/header.php';
?>
<main>
  <section class="gallery-page">
    <div class="container">
      <div class="gallery-header">
        <h1><?= escape($intro['title']) ?></h1>
        <p><?= escape($intro['body']) ?></p>
      </div>
      <div class="gallery-grid">
        <?php foreach ($galleryItems as $item): ?>
          <figure>
            <img src="<?= escape($item['image_path']) ?>" alt="<?= escape($item['alt_text']) ?>">
            <figcaption><?= escape($item['caption']) ?></figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
