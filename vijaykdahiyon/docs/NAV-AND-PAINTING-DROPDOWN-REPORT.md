# VijayKDahiyon — Navigation & Painting Dropdown Fix Report

**Site:** https://vijaykdahiyon.com/  
**Detail page tested:** https://vijaykdahiyon.com/detail.php?id=27&cat=63  
**Date:** 24 Oct 2026 (Asia/Calcutta)  
**Deployed to:** `domains/vijaykdahiyon.com/public_html` via SSH

---

## 1. Root cause — homepage navigation

| Link | Destination | Problem |
|------|-------------|---------|
| Home | `#slides` | OK (section exists) |
| Portfolio | `#portfolio` | OK |
| Blog | `#blog` | OK |
| Contact | `#contact` | **Broken** — contact block had class `section-contact` but **no `id="contact"`**, so onePageNav / hash scroll had no target |

On portfolio **detail** pages, Portfolio / Blog / Contact used bare `#portfolio`, `#blog`, `#contact`. Those sections do not exist on `detail.php`, so the links did nothing useful.

---

## 2. Root cause — Painting dropdown

Sidebar uses Bootstrap collapse (`data-toggle="collapse"` → `#SubMenu56`).

Category tree build in `detail.php` initialized:

```php
'sub_cat' => ''
```

Then assigned children with `$productCat[$id]['sub_cat'][$childId] = [...]`.

On **PHP 8.2** this does **not** create a real nested array. `sub_cat` stayed a non-empty **string**, so:

- Painting still rendered as a dropdown toggle (parent thought it had children via a truthy string)
- `#SubMenu56` was emitted **empty** (foreach over the mangled string produced no COMPOSITIONS / PORTRAITS links)

DB confirms Painting (`56`) has children COMPOSITIONS (`63`) and PORTRAITS (`64`). After fixing `sub_cat` to `array()`, both links render inside `#SubMenu56`.

---

## 3. Files changed (production)

| File | Change |
|------|--------|
| `index.php` | Added `id="contact"` on contact section; sticky logo → `#slides`; cache-bust `scripts.js?v=20261024` |
| `detail.php` | `sub_cat` initialized/stored as arrays; top nav → `https://vijaykdahiyon.com/#slides|portfolio|blog|contact` with `class="external"`; cache-bust scripts |
| `js/scripts.js` | Scope onePageNav to `.navbar-nav.top-nav`; scrollOffset 80; hash scroll on load/hashchange; close mobile navbar after nav click |

**Backup:** `public_html/backup/nav-dropdown-YYYYMMDD_HHMMSS/` (index.php, detail.php, scripts.js)

Repo copies: `vijaykdahiyon/patches/{index.php,detail.php,scripts.js}`

---

## 4. Implementation summary

1. Contact section is now a valid hash target on the homepage.  
2. Detail-page menu items return to the homepage section hashes.  
3. Painting submenu lists COMPOSITIONS and PORTRAITS and uses existing `loadProducts()`.  
4. Smooth scroll respects sticky header offset; mobile menu collapses after selection.

---

## 5. Tests

| Test | Result |
|------|--------|
| Homepage has `id="slides|portfolio|blog|contact"` | Pass (live HTML) |
| Homepage nav hrefs `#slides/#portfolio/#blog/#contact` | Pass |
| Detail nav hrefs full `/#…` URLs | Pass (server-fetched HTML) |
| `#SubMenu56` contains COMPOSITIONS + PORTRAITS | Pass |
| `loadProducts(63)` / `loadProducts(64)` present | Pass |
| CLI / server-side `detail.php?id=27&cat=63` length ~17.6KB with submenu | Pass |
| Headless Chrome screenshots | Blocked by Hostinger “Checking your browser” interstitial from this environment |
| Admin / DB schema | Unchanged |

---

## 6. Remaining limitations

- Interactive click/toggle in a real browser should be confirmed by the owner (bot protection blocked automated GUI here).  
- Collapse open/close still depends on Bootstrap 3 already loaded on `detail.php` (unchanged).  
- Passwords were used only for SSH deploy and were not written into source or Git.

---

## 7. Backup & deployment status

- Backup taken before overwrite under `public_html/backup/nav-dropdown-*`  
- Production files updated over SSH (port 65002)  
- Rollback: restore the three files from that backup folder  
