<?php

require_once __DIR__ . '/auth.php';
require_admin();

$passwordNotices = [
    'changed' => ['success', 'Password changed successfully.'],
    'current' => ['error', 'The current password is incorrect.'],
    'mismatch' => ['error', 'The new passwords do not match.'],
    'short' => ['error', 'The new password must be at least 12 characters.'],
];
$passwordStatus = $_GET['password'] ?? '';
$passwordNotice = is_string($passwordStatus) ? ($passwordNotices[$passwordStatus] ?? null) : null;
$settings = site_settings();
$blocks = database()->query('SELECT id, page_key, block_key, title, body FROM page_content ORDER BY page_key, id')->fetchAll();
$collections = [
    'programs' => ['label' => 'Home: academic programmes', 'columns' => ['icon' => 'Icon', 'title' => 'Title', 'description' => 'Description', 'sort_order' => 'Order']],
    'admissions' => ['label' => 'Home: admissions cards', 'columns' => ['label' => 'Label', 'title' => 'Title', 'description' => 'Description', 'sort_order' => 'Order']],
    'staff_members' => ['label' => 'Home: staff', 'columns' => ['name' => 'Name', 'role' => 'Role', 'initials' => 'Initials', 'sort_order' => 'Order']],
    'news_items' => ['label' => 'Home: news and events', 'columns' => ['published_at' => 'Date (YYYY-MM-DD)', 'title' => 'Title', 'description' => 'Description', 'sort_order' => 'Order', 'is_published' => 'Published']],
    'academic_courses' => ['label' => 'Academics: courses', 'columns' => ['title' => 'Title', 'description' => 'Description', 'details' => 'List items (one per line)', 'sort_order' => 'Order']],
    'gallery_items' => ['label' => 'Gallery', 'columns' => ['image_path' => 'Image path under /assets/img/', 'alt_text' => 'Alt text', 'caption' => 'Caption', 'sort_order' => 'Order', 'is_published' => 'Published']],
];
$items = [];
foreach ($collections as $table => $definition) {
    $items[$table] = database()->query("SELECT * FROM `$table` ORDER BY sort_order, id")->fetchAll();
}
$messages = database()->query('SELECT id, name, email, message, created_at, is_read FROM contact_messages ORDER BY created_at DESC LIMIT 100')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage website content</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-page">
  <header class="admin-header">
    <div><strong>Website admin</strong><span><?= escape($_SESSION['admin_email']) ?></span></div>
    <form method="post" action="/adminCMS/logout.php"><?= csrf_field() ?><button class="button-link" type="submit">Sign out</button></form>
  </header>
  <div class="admin-layout">
    <aside class="admin-sidebar" aria-label="Dashboard sections">
      <h2>Dashboard</h2>
      <nav>
        <a href="#school-settings">School settings</a>
        <a href="#page-copy">Page headings and copy</a>
        <a href="#change-password">Change password</a>
        <?php foreach ($collections as $table => $definition): ?>
          <a href="#<?= escape($table) ?>"><?= escape($definition['label']) ?></a>
        <?php endforeach; ?>
        <a href="#contact-messages">Contact messages</a>
      </nav>
    </aside>
    <main class="admin-main">
      <h1>Manage website</h1>
      <?php if (isset($_GET['saved'])): ?><p class="notice success" role="status">Changes saved.</p><?php endif; ?>

    <section class="admin-section" id="change-password">
      <h2>Change password</h2>
      <?php if ($passwordNotice !== null): ?><p class="notice <?= escape($passwordNotice[0]) ?>" role="<?= $passwordNotice[0] === 'error' ? 'alert' : 'status' ?>"><?= escape($passwordNotice[1]) ?></p><?php endif; ?>
      <form method="post" action="/adminCMS/change_password.php" class="settings-grid">
        <?= csrf_field() ?>
        <label>Current password
          <input type="password" name="current_password" autocomplete="current-password" required>
        </label>
        <label>New password
          <input type="password" name="new_password" autocomplete="new-password" minlength="12" required>
        </label>
        <label>Confirm new password
          <input type="password" name="confirm_password" autocomplete="new-password" minlength="12" required>
        </label>
        <button type="submit">Change password</button>
      </form>
    </section>

    <section class="admin-section" id="school-settings">
      <h2>School settings</h2>
      <form method="post" action="/adminCMS/save_content.php" class="settings-grid">
        <?= csrf_field() ?><input type="hidden" name="action" value="settings">
        <?php foreach (['school_name' => 'School name', 'school_slogan' => 'Motto / slogan', 'school_description' => 'Description', 'logo_path' => 'Logo image path', 'phone' => 'Phone', 'email' => 'Email', 'address' => 'Address', 'office_hours' => 'Office hours', 'map_embed_url' => 'Google Maps embed URL'] as $key => $label): ?>
          <label><?= escape($label) ?>
            <?php if (in_array($key, ['school_description', 'map_embed_url'], true)): ?>
              <textarea name="settings[<?= escape($key) ?>]" rows="3"><?= escape($settings[$key]) ?></textarea>
            <?php else: ?>
              <input type="text" name="settings[<?= escape($key) ?>]" value="<?= escape($settings[$key]) ?>" required>
            <?php endif; ?>
          </label>
        <?php endforeach; ?>
        <button type="submit">Save settings</button>
      </form>
    </section>

    <section class="admin-section" id="page-copy">
      <h2>Page headings and copy</h2>
      <?php foreach ($blocks as $block): ?>
        <form method="post" action="/adminCMS/save_content.php" class="block-editor">
          <?= csrf_field() ?><input type="hidden" name="action" value="blocks">
          <h3><?= escape(ucfirst($block['page_key']) . ' / ' . str_replace('_', ' ', $block['block_key'])) ?></h3>
          <input type="hidden" name="blocks[<?= (int) $block['id'] ?>][id]" value="<?= (int) $block['id'] ?>">
          <label>Title<input type="text" name="blocks[<?= (int) $block['id'] ?>][title]" value="<?= escape($block['title']) ?>"></label>
          <label>Text<textarea name="blocks[<?= (int) $block['id'] ?>][body]" rows="3"><?= escape($block['body']) ?></textarea></label>
          <button type="submit">Save this copy</button>
        </form>
      <?php endforeach; ?>
    </section>

    <?php foreach ($collections as $table => $definition): ?>
      <section class="admin-section" id="<?= escape($table) ?>">
        <h2><?= escape($definition['label']) ?></h2>
        <?php foreach ($items[$table] as $item): ?>
          <div class="item-editor">
            <form method="post" action="/adminCMS/save_content.php">
              <?= csrf_field() ?><input type="hidden" name="action" value="save_item">
              <input type="hidden" name="collection" value="<?= escape($table) ?>">
              <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
              <div class="item-fields">
                <?php foreach ($definition['columns'] as $column => $label): ?>
                  <label><?= escape($label) ?>
                    <?php if ($column === 'is_published'): ?>
                      <input type="checkbox" name="item[is_published]" value="1" <?= $item[$column] ? 'checked' : '' ?>>
                    <?php elseif (in_array($column, ['description', 'details'], true)): ?>
                      <textarea name="item[<?= escape($column) ?>]" rows="3"><?= escape((string) $item[$column]) ?></textarea>
                    <?php else: ?>
                      <input type="<?= $column === 'published_at' ? 'date' : 'text' ?>" name="item[<?= escape($column) ?>]" value="<?= escape((string) $item[$column]) ?>" required>
                    <?php endif; ?>
                  </label>
                <?php endforeach; ?>
              </div>
              <button type="submit">Save</button>
            </form>
            <form method="post" action="/adminCMS/save_content.php" onsubmit="return confirm('Delete this item?')">
              <?= csrf_field() ?><input type="hidden" name="action" value="delete_item">
              <input type="hidden" name="collection" value="<?= escape($table) ?>">
              <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
              <button class="danger" type="submit">Delete</button>
            </form>
          </div>
        <?php endforeach; ?>
        <details class="new-item">
          <summary>Add item</summary>
          <form method="post" action="/adminCMS/save_content.php">
            <?= csrf_field() ?><input type="hidden" name="action" value="save_item">
            <input type="hidden" name="collection" value="<?= escape($table) ?>">
            <div class="item-fields">
              <?php foreach ($definition['columns'] as $column => $label): ?>
                <label><?= escape($label) ?>
                  <?php if ($column === 'is_published'): ?>
                    <input type="checkbox" name="item[is_published]" value="1" checked>
                  <?php elseif (in_array($column, ['description', 'details'], true)): ?>
                    <textarea name="item[<?= escape($column) ?>]" rows="3"></textarea>
                  <?php else: ?>
                    <input type="<?= $column === 'published_at' ? 'date' : 'text' ?>" name="item[<?= escape($column) ?>]" value="<?= $column === 'sort_order' ? '0' : '' ?>" required>
                  <?php endif; ?>
                </label>
              <?php endforeach; ?>
            </div>
            <button type="submit">Add item</button>
          </form>
        </details>
      </section>
    <?php endforeach; ?>

    <section class="admin-section" id="contact-messages">
      <h2>Contact messages (latest 100)</h2>
      <?php if (!$messages): ?><p>No messages have been received.</p><?php endif; ?>
      <?php foreach ($messages as $message): ?>
        <article class="message-card <?= $message['is_read'] ? '' : 'unread' ?>">
          <p><strong><?= escape($message['name']) ?></strong> &lt;<?= escape($message['email']) ?>&gt; - <?= escape($message['created_at']) ?></p>
          <p><?= nl2br(escape($message['message'])) ?></p>
          <?php if (!$message['is_read']): ?>
            <form method="post" action="/adminCMS/save_content.php">
              <?= csrf_field() ?><input type="hidden" name="action" value="mark_message_read">
              <input type="hidden" name="id" value="<?= (int) $message['id'] ?>">
              <button type="submit">Mark read</button>
            </form>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </section>
    </main>
  </div>
</body>
</html>
