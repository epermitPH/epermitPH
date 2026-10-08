# Migrating from the old ePermit build

Because the old public site may not expose its server-side source code or database schema,
this package is designed as a clean updated core.

If you want to preserve your existing UI:
1. Keep the old landing-page design.
2. Replace its Login/Register links with this project's `login.php` and `register.php`.
3. Reuse your images/logo in `assets/`.
4. Move existing users/projects only after comparing table structures.
5. Do not directly overwrite the old database before making a backup.

Recommended old-to-new mapping:

- Existing applicant account -> `users` role `applicant`
- Existing reviewer account -> `users` role `reviewer`
- Existing admin -> `users` role `admin`
- Existing project -> `projects`
- Existing permit application -> `permit_applications`
- Existing uploaded files -> `documents` + `document_versions`
- Existing comments/corrections -> `reviews` / `correction_requests`

New modules that should be added even if you keep the old UI:
- `professional_profiles`
- `application_professionals`
- `application_disciplines`
- `document_versions`
- `correction_requests`
- `permits`
- `inspections`
- `notifications`
- `audit_logs`
