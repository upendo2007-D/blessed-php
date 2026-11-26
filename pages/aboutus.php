<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$pageClass = 'page-about';
$pageTitle = 'About Us - ' . setting('school_name');
$intro = page_content('about', 'intro');
$stats = [
    page_content('about', 'stat_years'),
    page_content('about', 'stat_subjects'),
    page_content('about', 'stat_staff'),
];
$values = [
    page_content('about', 'mission'),
    page_content('about', 'vision'),
    page_content('about', 'values'),
];

require __DIR__ . '/../components/meta.php';
require __DIR__ . '/../components/header.php';
?>
<main>
  <section class="about-hero">
    <div class="container">
      <div class="about-intro">
        <h1><?= escape($intro['title']) ?></h1>
        <p><?= escape($intro['body']) ?></p>
      </div>
      <div class="about-gallery">
        <figure>
          <img src="/assets/img/school environment.jpeg" alt="School environment">
          <figcaption>Our school environment</figcaption>
        </figure>
        <figure>
          <img src="/assets/img/guidance.jpg" alt="Student guidance">
          <figcaption>Guidance and student support</figcaption>
        </figure>
        <figure>
          <img src="/assets/img/mdd.jpg" alt="Traditional dance and cultural activities">
          <figcaption>Traditional dance and cultural activities during music and drama day</figcaption>
        </figure>
        <figure>
          <img src="/assets/img/baking.jpg" alt="Students developing practical baking skills">
          <figcaption>DIT classes developing practical life and vocational skills through baking</figcaption>
        </figure>
        <figure>
          <img src="/assets/img/tailoring.jpg" alt="Students learning tailoring and sewing skills">
          <figcaption>DIT classes developing practical tailoring and sewing skills</figcaption>
        </figure>
        <figure>
          <img src="/assets/img/braiding.jpg" alt="Students learning hair braiding skills">
          <figcaption>Students in the DIT lessons getting practical skills</figcaption>
        </figure>
      </div>
      <figure class="about-gallery-night">
        <img src="/assets/img/Beautiful night❤️.jpg" alt="Students at a formal school evening event">
        <figcaption>Students taking part in a formal school pageant and evening celebration</figcaption>
      </figure>
      <div class="about-stats">
        <?php foreach ($stats as $stat): ?>
          <div class="stat-card">
            <strong><?= escape($stat['title']) ?></strong>
            <span><?= escape($stat['body']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <section class="school-values">
    <div class="container">
      <?php foreach ($values as $value): ?>
        <article class="value-card">
          <h2><?= escape($value['title']) ?></h2>
          <p><?= escape($value['body']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php require __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
