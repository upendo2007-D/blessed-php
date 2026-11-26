<div id="footer-container">
  <footer class="site-footer">
    <div class="footer-top">
      <div class="footer-brand">
        <div class="footer-logo">
          <img src="<?= escape(setting('logo_path')) ?>" alt="<?= escape(setting('school_name')) ?> logo" />
        </div>
        <h2><?= escape(setting('school_name')) ?></h2>
        <p><?= escape(setting('school_description')) ?></p>
      </div>
      <div class="footer-col">
        <h3>Quick links</h3>
        <ul>
          <li><a href="/pages/home.php">Home</a></li>
          <li><a href="/pages/aboutus.php">About Us</a></li>
          <li><a href="/pages/academics.php">Academics</a></li>
          <li><a href="/pages/gallery.php">Gallery</a></li>
          <li><a href="/pages/contactus.php">Contact</a></li>
        </ul>
      </div>
      <div class="footer-col footer-contact">
        <h3>Contact</h3>
        <p>Phone: <?= escape(setting('phone')) ?></p>
        <p>Email: <?= escape(setting('email')) ?></p>
        <p>Address: <?= escape(setting('address')) ?></p>
        <p>Office hours: <?= escape(setting('office_hours')) ?></p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> <?= escape(setting('school_name')) ?>. All rights reserved.</p>
    </div>
  </footer>
</div>
