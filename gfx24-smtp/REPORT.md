# gfx24.com portal SMTP fix

## Root cause

Member actions under `https://gfx24.com/portal/members/action.php` send OTP / signup mail through `portal/SMTPMailer.php`.

That mailer was configured for Hostinger:

- Host: `smtp.hostinger.com:465` (SSL)
- User: `sales@gfx24.com`
- Password stored in code was rejected with **`535 5.7.8 authentication failed`**

Connectivity was fine. Local PHP `mail()` / sendmail is disabled on this Hostinger account (`550 Local sendmail disabled`).

## Fix deployed (live server)

Path: `/home/u319400715/domains/gfx24.com/public_html/portal/`

1. Backed up broken mailer to `SMTPMailer.php.bak_20261106_082629`
2. Switched SMTP to the working Mailtrap Sending API already present in `members/smtpEmail/SMTPMailer.php`:
   - Host: `live.smtp.mailtrap.io:587` (TLS)
   - From: `mailtrap@vdollartechnologies.com` (Mailtrap-verified sender; `sales@gfx24.com` is not allowed on that account)
3. Moved credentials into `portal/conf/config_smtp.php` (web-denied via `.htaccess`)
4. Hardened mailer:
   - Load config via `__DIR__/conf/config_smtp.php` (works from `members/action.php`)
   - EHLO hostname falls back to `gfx24.com` when `HTTP_HOST` is empty
   - Fixed Message-ID generation (avoid PHP 8.2 `base_convert(microtime())` deprecation)
   - Redact AUTH payloads if a transaction log is shown
   - QUIT is best-effort after message accept

## Verification

| Test | Result |
|------|--------|
| CLI include path same as `action.php` (`include("../SMTPMailer.php")`) | `ACTION_PATH_SEND_OK` / `FINAL_OK` |
| HTTP smoke under `/portal/members/` | `WEB_SEND_OK` |
| OTP-shaped send to member `3876924` (`jiteshkrprasad@gmail.com`) | `OTP_SEND_OK` |
| Member login (`formaction=Login Form`) | Redirects to `main.php` (no SMTP error) |

## Follow-up (recommended)

Reset the Hostinger mailbox password for `sales@gfx24.com` in hPanel, then point `conf/config_smtp.php` back to:

```php
$cfg_server   = 'smtp.hostinger.com';
$cfg_port     = 465;
$cfg_secure   = 'ssl';
$cfg_username = 'sales@gfx24.com';
$cfg_password = 'NEW_PASSWORD';
$cfg_from_email = 'sales@gfx24.com';
$cfg_from_name  = 'GFX24';
```

Until then, outbound portal mail is delivered via Mailtrap from `mailtrap@vdollartechnologies.com`.

## Repo contents

- `SMTPMailer.php` — deployed mailer (no live secrets)
- `config_smtp.example.php` — example config (placeholder token)
