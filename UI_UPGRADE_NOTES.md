# ePermit UI Upgrade

This UI upgrade keeps the existing PHP/MySQL workflow and changes the visual presentation to match the previously generated ePermit reference mockup more closely.

## Changed files
- `assets/css/style.css` — full dashboard design system, sidebar, cards, tables, forms, responsive layout
- `includes/header.php` — role-based dark sidebar + top application bar
- `includes/footer.php` — app-shell closing markup and mobile sidebar behavior
- `login.php` — split-screen login design
- `register.php` — matching split-screen registration design
- `applicant/new_application.php` — stepper + discipline selection cards

## No database changes required
If you are already using the updated V2 database, you do NOT need to import `schema.sql` again just for the UI upgrade.

## Safest way to apply to your existing V2 project
1. Back up your current `epermit` folder.
2. Copy the changed files above into the same locations in your current project.
3. Keep your existing `config/database.php` and `config/app.php` if they are already working.
4. Hard refresh the browser with `Ctrl + F5`.

## Local XAMPP
If your project is at `C:\xampp\htdocs\epermit`, your working `config/app.php` should normally use:

```php
define("APP_BASE", "/epermit");
define("APP_URL", "http://localhost/epermit");
```

Do not replace a working database configuration just to install this UI update.
