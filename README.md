# Blessed Sai website

## Local XAMPP setup

1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Open phpMyAdmin at `http://localhost/phpmyadmin/` (not `/php/MyAdmin/`).
3. In phpMyAdmin, select **Import**, choose `C:\xampp\htdocs\blessed.sai\database\schema.sql`, then click **Go**. The script creates the `blessed_sai` database, its content tables, and starter content. Re-importing it does not duplicate the seeded rows. After import, select **blessed_sai** in the left sidebar to view its tables.
4. Database connection defaults are for a local XAMPP install (`127.0.0.1`, database `blessed_sai`, user `root`, no password). Configure `BLESSED_DB_HOST`, `BLESSED_DB_PORT`, `BLESSED_DB_NAME`, `BLESSED_DB_USER`, and `BLESSED_DB_PASSWORD` for other environments. The PHP settings are in `config/config.php`.
5. From the project folder in PowerShell, create the first admin account:

   ```powershell
   .\adminCMS\create-admin.ps1 -Email admin@example.com
   ```

   Enter a password of at least 12 characters when prompted. To add another account, run the same command with its email.
6. Open `http://blessed.sai/adminCMS/` to sign in. The dashboard manages school settings, page copy, home page cards, staff, news, courses, gallery entries, and contact messages. School settings are saved to `config/site.php`, so PHP/the Apache account must be allowed to write to the `config/` directory.
7. To change an admin password, sign in, open **Change password** in the dashboard, and enter the current password and a new password of at least 12 characters.

The contact form stores messages in the CMS inbox; it does not send email. Gallery entries must point to an image already stored under `assets/img/`.

School name, motto, logo path, contact details, and map URL are centralized in `config/site.php`. Public pages retrieve them with `setting('school_name')`, `setting('school_slogan')`, and similar calls. The admin dashboard edits those same settings file values; database connection credentials remain separately configured in `config/config.php`.

## Folder layout

- `adminCMS/` - authenticated content dashboard and admin-account CLI tool
- `assets/` - public stylesheets and images
- `components/` - shared page head, header, and footer
- `config/` - database connection settings and school/site settings, denied for direct web requests
- `database/` - MySQL schema and initial content, denied for direct web requests
- `includes/` - shared database and output/CSRF helpers, denied for direct web requests
- `pages/` - public site pages
- `blessedbackup/` - retained static backup
