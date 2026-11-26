<?php $siteSettings = site_settings(); ?>
<nav>
  <div class="container">
    <div class="logo">
      <div class="logo-img">
        <img src="<?= escape($siteSettings['logo_path']) ?>" alt="<?= escape($siteSettings['school_name']) ?> logo" />
      </div>
      <div class="logo-text">
        <h1><?= escape($siteSettings['school_name']) ?></h1>
        <p><?= escape($siteSettings['school_slogan']) ?></p>
      </div>
    </div>
    <div class="nav-items">
      <a href="/pages/home.php" class="nav-link home-link">Home</a>
      <a href="/pages/aboutus.php" class="nav-link about-link">About us</a>
      <a href="/pages/academics.php" class="nav-link academics-link">Academics</a>
      <a href="/pages/gallery.php" class="nav-link gallery-link">Gallery</a>
      <a href="/pages/contactus.php" class="nav-link contact-link">Contact</a>
      <a href="/adminCMS/login.php" class="nav-link sign-in-link">Sign In</a>
    </div>
  </div>
</nav>
