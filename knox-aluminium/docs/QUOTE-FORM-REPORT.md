# Request a Free Rough Quote — Delivery Report

**Site:** https://smartscrapmetals.com.au/kaluminium/  
**Date:** 20 Oct 2026  
**Plugin:** `knox-aluminium-content` v1.2.1

---

## Shortcode

```
[contact-form-7 id="26161" title="Request a Free Rough Quote"]
```

CF7 form ID: **26161**

---

## Page

| Page | URL | Notes |
|------|-----|--------|
| Dedicated quote page | `/request-a-free-quote/` (ID 26162) | Full form + intro |
| Contact Us | `/contact-us/` | Embeds the same CF7 form + link to dedicated page |

Sitewide **Request a Free Quote** CTAs on managed content pages point to `/request-a-free-quote/`.

---

## Files / settings changed

| Path | Change |
|------|--------|
| `plugin/.../includes/quote-form.php` | CF7 template, mail, messages, page sync |
| `plugin/.../knox-aluminium-content.php` | Loads quote module; Tools + REST `/knox-ac/v1/sync-quote` |
| `plugin/.../assets/knox-content.css` | Scoped `.knox-quote-form` styles |
| `plugin/.../includes/content.php` | Quote CTAs → `/request-a-free-quote/` |
| `plugin/.../includes/sync.php` | Main Menu includes quote page; sync_all runs quote sync |

Live WordPress: CF7 form #26161 mail/messages updated; pages #26162 and Contact Us updated.

---

## Email configuration

| Setting | Value |
|---------|--------|
| Recipient | `sales@knoxaluminium.com.au` (business enquiry address already used on site) |
| Subject | New Free Rough Quote Request — Knox Aluminium |
| Sender | Knox Aluminium \<wordpress@smartscrapmetals.com.au\> |
| Reply-To | `[email-address]` (customer) |
| Bcc | `[_site_admin_email]` |
| Attachments | `[project-files]`, `[project-files-2]`, `[project-files-3]` (up to 3 × 10 MB; JPG/JPEG/PNG/WEBP/PDF) |
| Mail 2 | Confirmation to customer (active) |

---

## Conditional fields

No conditional-fields plugin is installed. Optional “Other / Custom” text fields remain always visible and clearly labelled. **No extra plugins installed.**

---

## Field notes (CF7 constraints)

- Job Type / Property Type / frame colour / glass / installation time / lead source use **select** (not radio) so optional blanks work and CF7 does not treat `/` or `&` in option labels as tag delimiters.
- Labels use “Supply and Installation” and “Replacement of Existing Windows or Doors” (PDF meaning preserved; slash/`&` avoided inside CF7 tags).
- Measurements: five optional rows; no Price column.

---

## Tests performed

| Check | Result |
|-------|--------|
| All required fields present on `/request-a-free-quote/` | Pass |
| Empty submit → `validation_failed` for required set incl. job-type & property-type | Pass |
| Optional fields not forced required | Pass |
| Full valid submit → `mail_sent` + configured success message | Pass |
| Invalid `.txt` upload rejected with filetype message | Pass |
| Contact page embeds form #26161 | Pass |
| CSS scoped under `.knox-quote-form` | Pass |

---

## Outstanding / host-side

- Confirm inbox delivery for `sales@knoxaluminium.com.au` (and customer Mail 2) on the live mailer.
- Server `upload_max_filesize` / `post_max_size` should allow ≥ 10 MB per attachment; large multi-file posts may hit PHP/mail limits — three separate file fields keep payloads manageable.
- Uploads attach to mail only; Flamingo/storage not configured — submissions are not claimed as stored in WP.
- Optional: install a conditional-fields add-on later (with approval) to hide Other/Custom inputs until selected.
- Optional: update Elementor header widget CTA if a separate header button still points at Contact Us.
