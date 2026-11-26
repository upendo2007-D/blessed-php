<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$pageClass = 'page-academics';
$pageTitle = 'Academics - ' . setting('school_name');
$intro = page_content('academics', 'intro');
$courses = page_rows('academic_courses');
$courseImages = ['/assets/img/class.jpg', '/assets/img/research.jpg', '/assets/img/library.jpg', '/assets/img/Doctor.jpeg', '/assets/img/group.jpeg'];

require __DIR__ . '/../components/meta.php';
require __DIR__ . '/../components/header.php';
?>
<main>
  <section class="academics-hero">
    <div class="container">
      <div class="academics-intro">
        <h1><?= escape($intro['title']) ?></h1>
        <p><?= escape($intro['body']) ?></p>
      </div>
    </div>
  </section>
  <section class="course-sections">
    <div class="container">
      <?php foreach ($courses as $index => $course): ?>
        <article class="course-summary">
          <?php if (trim($course['title']) === 'Sports & Co-curricular'): ?>
            <img class="card-image" src="/assets/img/sports.jpg" alt="Sports and co-curricular activities">
          <?php elseif (trim($course['title']) === 'Science Department'): ?>
            <img class="card-image" src="/assets/img/lab.jpg" alt="Science laboratory">
          <?php elseif (trim($course['title']) === 'ICT & Technology'): ?>
            <img class="card-image" src="/assets/img/library.jpg" alt="ICT and technology facilities">
          <?php elseif (!in_array($index, [3, 4], true)): ?>
            <img class="card-image" src="<?= escape($courseImages[$index % count($courseImages)]) ?>" alt="<?= escape($course['title']) ?>">
          <?php endif; ?>
          <h2><?= escape($course['title']) ?></h2>
          <p><?= escape($course['description']) ?></p>
          <ul>
            <?php foreach (preg_split('/\R/', $course['details']) as $detail): ?>
              <?php if (trim($detail) !== ''): ?>
                <li><?= escape(trim($detail)) ?></li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php require __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
