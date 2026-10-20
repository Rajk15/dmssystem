# Knox Aluminium Windows — Delivery Report

**Site:** https://smartscrapmetals.com.au/kaluminium/  
**Date:** 19 Oct 2026  
**Plugin:** `knox-aluminium-content` v1.0.1 (active on production)

---

## 1. PDF-listed pages reviewed

| PDF section | Status |
|-------------|--------|
| Website Page 1 — Homepage | Reviewed & updated |
| Website Page 2 — About Us | Reviewed & updated |
| Website Page 3 — Windows | Reviewed & created |
| Website Page 4 — Doors | Reviewed & created |
| Website Page 5 — Commercial Projects | Reviewed & created |
| Website Page 6 — Process | Reviewed & created |
| Website Page 7 — Contact | Reviewed & restored |
| Warranty | Reviewed & created |
| Gallery (text only) | Reviewed & created as Project Gallery |
| Location pages (20 suburbs) | Reviewed & created |
| Full Product Catalogue (deep specs) | Mapped; surfaced via Windows/Doors/Commercial system lists — full catalogue subpages not built as separate URLs in this pass |

Checklist: `knox-aluminium/docs/PAGE-CHECKLIST.md`

---

## 2. Pages updated / created and changes

| Page | URL | Change |
|------|-----|--------|
| Home | `/` (ID 15212) | Replaced Elementor demo body with PDF homepage content, hero CTAs, Why Choose, product range cards, Melbourne conditions, closing CTA |
| About | `/about-us/` (6108) | PDF manufacturer story + warranty CTAs; alternating sections |
| Windows | `/windows/` (26016) | New — all named window systems from PDF with alternating sections |
| Doors | `/doors/` (26017) | New — all named door systems from PDF with alternating sections |
| Commercial | `/commercial-projects/` (26018) | New — commercial solutions list + CTA |
| Process | `/process/` (26019) | New — 5-step process |
| Contact | `/contact-us/` (5209) | Restored from trash; NAP from PDF; Request a Quote CF7 form (ID 4191) |
| Warranty | `/warranty/` (26021) | New — 10-year product + installation warranty (PDF only) |
| Project Gallery | `/project-gallery/` (26022) | New — intro + media grid from library |
| Locations hub | `/locations/` (26013) | New parent |
| 20 suburb pages | `/locations/aluminium-windows-doors-{suburb}/` | PDF blurbs + CTAs + internal links |

**Navigation:** Main Menu rebuilt — Home, About Us, Windows, Doors, Commercial Projects, Process, Gallery, Contact Us, Request a Free Quote (all valid URLs; trash links removed).

---

## 3. Image assets used / missing

**Used (Media Library):**
- Company: `IMG_2626` (26005), `IMG_2650` (26001), `IMG_2472` (26000), `IMG_2192` (25995)
- Product/service: `windoor-serice-image-*`, `windoor-landing-inner-page-*`, casement/cascading window assets

**Missing / gaps:**
- No dedicated labelled photography for trademarked systems (Pro-Vista™, View-Max®, Hawkesbury®, etc.) — closest category images reused with descriptive alt text
- No fabricated project captions or testimonials added

---

## 4. Reusable components / files

Repository path: `knox-aluminium/plugin/knox-aluminium-content/`

| File | Role |
|------|------|
| `knox-aluminium-content.php` | Plugin bootstrap, assets, Tools → Knox Content Sync, REST `/knox-ac/v1/sync` |
| `assets/knox-content.css` | Alternating 50/50 grid, mobile stack, brand tokens (#000 / #EDC701), theme H1 visually deferral |
| `includes/content.php` | PDF page HTML builders |
| `includes/media.php` | Attachment map + responsive images |
| `includes/sync.php` | Create/update pages, disable Elementor on managed pages, menu rebuild |
| `includes/seo.php` | SEO meta helpers + `knox-ac-page` body class |

Child theme `functions.php` left in original state (enqueue parent stylesheet only). Temporary install hooks were removed after deploy.

---

## 5. SEO changes

- Unique title + meta description set per PDF page (AIOSEO-compatible meta + plugin fallback)
- Canonicals left to AIOSEO
- One logical content H1 per page (theme banner H1 visually deferred via CSS on managed pages)
- Internal links between Windows, Doors, Commercial, Process, Contact
- Descriptive image alt text on section images
- No invented reviews, ratings, or unsupported certifications

---

## 6. Responsive / functional tests

| Check | Result |
|-------|--------|
| Desktop content + alternating splits on Home/About/Windows/Doors/Commercial | Pass (verified HTML: expected split counts) |
| CSS enqueued (`knox-content.css?ver=1.0.1`) | Pass |
| Contact form (CF7) present | Pass |
| Phone / email / address / hours | Pass |
| Primary nav URLs (no `pages__trashed`) | Pass |
| Location sample Knoxfield + Templestowe | Pass HTTP 200 |
| Process / Windows / Doors / Commercial (previously 404) | Pass HTTP 200 |
| Contact `/contact-us/` (previously redirected to trash) | Pass HTTP 200 |
| Browser visual QA (desktop/tablet/mobile screenshots) | See walkthrough artifacts / computerUse pass |

---

## 7. Requirements not fully completed

| Item | Reason |
|------|--------|
| Full Product Catalogue as individual deep pages for every catalogue system | PDF catalogue is long (awning through accessories). Core named systems from Website Pages 3–5 are live; remaining catalogue copy is in the PDF for a follow-up pass if required |
| Dedicated product-matched photography per system | Not in Media Library; reused closest approved assets |
| Elementor visual editing retained on managed pages | Elementor builder data cleared on those pages so maintainable HTML + CSS components render; theme header/footer preserved |
| Perfect single-H1 DOM (theme still outputs banner H1) | Banner H1 visually hidden on managed pages; titles aligned with content H1 text |

---

## 8. Manual / remaining steps

1. Optional: upload dedicated product photos and update `includes/media.php` keys
2. Optional: build remaining catalogue detail pages from PDF FULL PRODUCT CATALOGUE section
3. Spot-check email delivery for CF7 “Request a Quote Form” in production inbox
4. Clear any CDN/page cache if a reverse proxy caches HTML
5. Re-run **Tools → Knox Content Sync** only when intentionally refreshing PDF content (avoid duplicate location pages — path lookup is fixed in v1.0.1+)
