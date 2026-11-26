<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$pageClass = 'page-contact';
$pageTitle = 'Contact Us - ' . setting('school_name');
$intro = page_content('contact', 'intro');
$formCopy = [
    'name' => page_content('contact', 'name_label'),
    'email' => page_content('contact', 'email_label'),
    'message' => page_content('contact', 'message_label'),
    'submit' => page_content('contact', 'submit_label'),
    'success' => page_content('contact', 'success_message'),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if ($name === '' || strlen($name) > 180 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190 || $message === '' || strlen($message) > 10000) {
        $formError = 'Enter a valid name, email address, and message (up to 10,000 characters).';
    } else {
        $statement = database()->prepare('INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)');
        $statement->execute(['name' => $name, 'email' => $email, 'message' => $message]);
        header('Location: /pages/contactus.php?sent=1');
        exit;
    }
}

require __DIR__ . '/../components/meta.php';
require __DIR__ . '/../components/header.php';
?>
<main>
  <section class="contact-page">
    <div class="contact-row">
      <div class="contact-left">
        <div class="contact-info">
          <h1><?= escape($intro['title']) ?></h1>
          <p><?= escape($intro['body']) ?></p>
          <ul>
            <li><strong>Phone:</strong> <?= escape(setting('phone')) ?></li>
            <li><strong>Email:</strong> <?= escape(setting('email')) ?></li>
            <li><strong>Address:</strong> <?= escape(setting('address')) ?></li>
          </ul>
        </div>
      </div>
      <div class="contact-right">
        <div class="contact-form">
          <?php if (isset($_GET['sent'])): ?>
            <p role="status"><?= escape($formCopy['success']['body']) ?></p>
          <?php endif; ?>
          <?php if (isset($formError)): ?>
            <p role="alert"><?= escape($formError) ?></p>
          <?php endif; ?>
          <form method="post" action="/pages/contactus.php">
            <?= csrf_field() ?>
            <label>
              <?= escape($formCopy['name']['title']) ?>
              <input type="text" name="name" maxlength="180" value="<?= escape($_POST['name'] ?? '') ?>" required>
            </label>
            <label>
              <?= escape($formCopy['email']['title']) ?>
              <input type="email" name="email" maxlength="190" value="<?= escape($_POST['email'] ?? '') ?>" required>
            </label>
            <label>
              <?= escape($formCopy['message']['title']) ?>
              <textarea name="message" rows="6" maxlength="10000" required><?= escape($_POST['message'] ?? '') ?></textarea>
            </label>
            <button type="submit"><?= escape($formCopy['submit']['title']) ?></button>
          </form>
        </div>
      </div>
    </div>
  </section>
  <?php if (str_starts_with(setting('map_embed_url'), 'https://www.google.com/maps/embed?')): ?>
    <section class="map-section">
      <div class="container">
        <div class="map-responsive">
          <iframe src="<?= escape(setting('map_embed_url')) ?>" width="800" height="600" style="border: 0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </section>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
