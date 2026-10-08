# ePermit — Updated Concept Paper Build (PHP + MySQL)

**Verify. Approve. Build.**

This starter project is aligned with the revised ePermit concept:

- Applicant / Building Owner portal
- Licensed Professional portal
- Professional verification
- PECE as the professional for applicable Electronics Engineering documents
- Discipline-level technical review tracking
- Reviewer routing / work queue
- Correction requests and resubmission
- Document version control
- Approved Plan of Record
- Building Official permit issuance
- Digital permit verification record
- QR verification link
- Field inspection records
- In-app notifications
- Audit trail
- Admin management for LGU users, professionals, disciplines, and reviewer assignments

## Technology
- PHP 8+
- MySQL / MariaDB
- HTML/CSS/JavaScript
- PDO prepared statements
- Session-based authentication

## Installation

1. Create a MySQL database.
2. Import `schema.sql`.
3. Edit:
   - `config/database.php`
   - `config/app.php`
4. Make sure `uploads/` is writable.
5. Open `/setup_admin.php` once and create the first administrator.
6. Delete `setup_admin.php` from the server after creating the admin.
7. Log in.

## InfinityFree notes

Set the database values supplied by InfinityFree in `config/database.php`.

In `config/app.php`, set:
- `APP_BASE` to `""` if ePermit is installed in the site root.
- `APP_URL` to your live site, for example `https://epermit.infy.click`.

## Suggested demo flow

1. Admin creates Technical Reviewer, Building Official, and Inspector accounts.
2. A Professional registers.
3. Admin verifies the Professional profile.
4. An Applicant registers and creates a permit application.
5. Applicant assigns a verified professional per discipline.
6. Applicant or assigned professional uploads a technical document.
7. Admin assigns a reviewer to each required discipline.
8. Reviewer reviews the latest version.
9. Reviewer either approves or requests correction.
10. Applicant / professional uploads a new version after correction.
11. Once all required disciplines are approved, the Building Official issues the permit.
12. The permit page generates a QR code linked to the official verification record.
13. Inspector opens the verification link and records field findings.
14. Admin can review the audit trail.

## Production hardening still recommended

This is a strong MVP / competition prototype. Before production deployment, add:
- email verification and password reset
- LGU-specific workflow templates
- authorized external professional-license verification integration
- malware scanning for uploaded files
- private object storage for technical plans
- stronger logging/retention policies
- rate limiting
- backup automation
- privacy policy / consent
- HTTPS-only cookies
- detailed permit types and requirements matrix
