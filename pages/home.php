<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$pageClass = 'page-home';
$pageTitle = setting('school_name');
$content = [
    'hero_eyebrow' => page_content('home', 'hero_eyebrow'),
    'hero' => page_content('home', 'hero'),
    'hero_primary_link' => page_content('home', 'hero_primary_link'),
    'hero_secondary_link' => page_content('home', 'hero_secondary_link'),
    'programs' => page_content('home', 'programs'),
    'admissions_eyebrow' => page_content('home', 'admissions_eyebrow'),
    'admissions' => page_content('home', 'admissions'),
    'team_eyebrow' => page_content('home', 'team_eyebrow'),
    'team' => page_content('home', 'team'),
    'news_eyebrow' => page_content('home', 'news_eyebrow'),
    'news' => page_content('home', 'news'),
];
$programs = page_rows('programs');
$admissions = page_rows('admissions');
$staff = page_rows('staff_members');
$news = page_rows('news_items');

require __DIR__ . '/../components/meta.php';
require __DIR__ . '/../components/header.php';
?>
<main>
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <p class="eyebrow"><?= escape($content['hero_eyebrow']['body']) ?></p>
        <h1><?= escape(setting('school_name')) ?></h1>
        <p class="hero-copy"><?= escape($content['hero']['body']) ?></p>
        <div class="hero-actions">
          <a class="btn btn-primary" href="/pages/aboutus.php"><?= escape($content['hero_primary_link']['title']) ?></a>
          <a class="btn btn-secondary" href="/pages/contactus.php"><?= escape($content['hero_secondary_link']['title']) ?></a>
        </div>
      </div>
    </div>
  </section>

  <section class="programs-intro">
    <div class="container">
      <div class="programs-header">
        <h2><?= escape($content['programs']['title']) ?></h2>
        <p><?= escape($content['programs']['body']) ?></p>
      </div>
      <div class="programs-grid">
        <?php foreach ($programs as $program): ?>
          <article class="program-card">
            <span class="program-icon"><?= escape($program['icon']) ?></span>
            <h3><?= escape($program['title']) ?></h3>
            <p><?= escape($program['description']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="admissions-highlights">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow"><?= escape($content['admissions_eyebrow']['body']) ?></p>
        <h2><?= escape($content['admissions']['title']) ?></h2>
        <p><?= escape($content['admissions']['body']) ?></p>
      </div>
      <div class="admissions-grid">
        <?php foreach ($admissions as $admission): ?>
          <article class="admission-card">
            <span><?= escape($admission['label']) ?></span>
            <h3><?= escape($admission['title']) ?></h3>
            <p><?= escape($admission['description']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="team-section">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow"><?= escape($content['team_eyebrow']['body']) ?></p>
        <h2><?= escape($content['team']['title']) ?></h2>
        <p><?= escape($content['team']['body']) ?></p>
      </div>
      <div class="team-grid">
        <?php foreach ($staff as $member): ?>
          <article class="team-card">
            <div class="team-avatar"><?= escape($member['initials']) ?></div>
            <h3><?= escape($member['name']) ?></h3>
            <p><?= escape($member['role']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="news-section">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow"><?= escape($content['news_eyebrow']['body']) ?></p>
        <h2><?= escape($content['news']['title']) ?></h2>
        <p><?= escape($content['news']['body']) ?></p>
      </div>
      <div class="news-grid">
        <?php foreach ($news as $item): ?>
          <article class="news-card">
            <span class="news-date"><?= escape(date('M d, Y', strtotime($item['published_at']))) ?></span>
            <h3><?= escape($item['title']) ?></h3>
            <p><?= escape($item['description']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
