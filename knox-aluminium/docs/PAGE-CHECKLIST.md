# Knox Aluminium Windows — Page-by-Page Change Checklist

**Site:** https://smartscrapmetals.com.au/kaluminium/  
**CMS:** WordPress + Elementor + Windoor Child + AIOSEO  
**Source of truth:** `Knox Aluminium Windows1 (1)` PDF (57 pages)  
**Audit date:** 19 Oct 2026  
**Status:** Phase 1 complete — implement only after this checklist

---

## Stack audit (verified)

| Item | Finding |
|------|---------|
| Parent theme | Windoor |
| Active theme | Windoor Child v1.0.0 |
| Page builder | Elementor (pages use `elementor_header_footer` / Elementor canvas) |
| SEO | All in One SEO (AIOSEO) 5.0.2 |
| Forms | Contact Form 7 present |
| Front page | Static: **Home-windoor** (`/`, ID 15212) |
| Branding | Knox logo in media; theme demo “windoor” content still dominant |
| Approach | Continue Elementor where practical; for clean alternating layouts use a maintainable shortcode + CSS component (plugin) rendered in page content / HTML widgets so pages stay editable |

---

## Critical issues found (pre-change)

1. **Process** nav → `/pages__trashed/about-us/` (broken)
2. **Request a Quote / Contact Us** → `/pages__trashed/contact-us-multi-map/` (Contact Us page ID 5209 is **trashed**; `/contact-us/` 301s to trash URL)
3. **Doors** nav → `/our-services/baran-door/` (demo product, not PDF door range)
4. **Window** nav → `/our-services/` (generic demo Services, not PDF Windows page)
5. **Commercial Projects**, **Process**, **Windows**, **Doors**, **Warranty**, dedicated **Gallery** pages: **missing** (404) or only demo posts
6. Many demo child pages (Fiberglass Cupboard, Wooden Cabinet, Rolling Shutters, etc.) — keep URLs; do not promote in primary nav
7. WP admin: **“There are some invalid menu items”**

---

## PDF page inventory (complete)

### Core website pages (PDF “WEBSITE PAGE 1–7”)

| # | PDF page | Required content (summary) | Existing WP page | Action | Images | SEO |
|---|----------|----------------------------|------------------|--------|--------|-----|
| 1 | **Homepage** | H1 architectural aluminium; intro (25+ yrs, AU-made); CTAs Quote / Site Consultation / Product Range; Why Choose (8 bullets incl. 10-yr warranties); Product Range (Windows / Doors / Commercial / Security); Built for Melbourne; closing CTA | `/` Home-windoor (15212) | **UPDATE** content + alternating sections; keep URL & header/footer | Prefer company photos `IMG_2626`, `IMG_2650`, `IMG_2472`, `IMG_2192` + existing windoor service images where product-relevant | Unique title/meta for Knox home; H1 once |
| 2 | **About Us** | Melbourne manufacturer; 25+ yrs; builders/glaziers; local fab; 10-yr product + installation warranty | `/about-us/` (6108) Elementor | **UPDATE** body; preserve slug | Project/team/install photos from media | Unique About title/meta |
| 3 | **Windows** | Architectural Aluminium Windows; systems: Pro-Vista™ Sashless Double Hung, View-Max® Commercial Sliding, ProTilt® Awning & Casement, Standard Awning & Casement, Fixed Architectural | **None** (`/windows/` 404). Closest demos: Casement/Framed/Pivoted under Services | **CREATE** `/windows/` with alternating product sections; optionally retarget Window nav; leave demo pages unpublished-from-nav only | Window product/install images | Title/meta for aluminium windows Melbourne |
| 4 | **Doors** | Architectural Aluminium Doors; Hawkesbury® Multi-Fold & Top Hung; ProGlide® HP & Ultra-Flat; Altitude® Apartment; Swan Evo™ & Torrens shopfront | **None** (`/doors/` 404). Nav wrongly uses Baran Door | **CREATE** `/doors/`; fix nav | Door/shopfront images | Title/meta for aluminium doors |
| 5 | **Commercial Projects** | Commercial systems list (shopfront, curtain wall, architectural framing, thermally broken, commercial doors, screens, windows); builders/architects/developers | **None** (`/commercial/` 404). Menu item “commercial Projects” invalid/broken | **CREATE** `/commercial-projects/` | Commercial/shopfront images | Commercial aluminium systems SEO |
| 6 | **Process** | 5 steps: Measure → Quote → Manufacture → Install → Inspect | **None** (`/process/` 404); nav broken to trash | **CREATE** `/process/`; fix nav | Process/install photos | Process page SEO |
| 7 | **Contact** | Phone 0439 202 020; sales@knoxaluminium.com.au; 4/573 Burwood Hwy, Knoxfield VIC; hours Mon–Thu 8–4, Fri 8–1:30 | Contact Us **trashed** (5209); Multi Map (11472) exists; `/contact-us/` redirects to trash | **RESTORE or CREATE** working `/contact-us/`; keep CF7/enquiry; fix redirects & nav | Map optional; store photo if available | Contact SEO; NAP consistency |

### Additional PDF pages

| PDF section | Required | Existing | Action | Notes |
|-------------|----------|----------|--------|-------|
| **Warranty** | 10-year product + 10-year installation (when installed by Knox) | None (`/warranty/` 404) | **CREATE** `/warranty/`; link from About/Home | Do not invent extra warranty terms |
| **Gallery (text only)** | Intro line for residential/commercial/architectural projects | `/gallery/` → old blog post; trashed Galleries demos | **CREATE** `/project-gallery/` (avoid clashing slug) with text + available media grid | No fabricated testimonials/projects |
| **Location pages (20)** | Seo suburb blurbs for: Knoxfield, Wantirna, Rowville, Ferntree Gully, Boronia, Scoresby, Bayswater, Pakenham, Malvern, Glen Waverley, Mount Waverley, Chadstone, Clayton, Mulgrave, Ringwood, Vermont, Mitcham, Blackburn, Doncaster, Templestowe | None | **CREATE** under `/locations/{suburb}/` or `/aluminium-windows-doors-{suburb}/`; add internal links from Contact/Home | Thin unique copy from PDF only; no invented service claims |
| **Full Product Catalogue** | 20+ system categories with rewritten descriptions (Awning, Casement, Sliding, Fixed windows; Sliding/Stacking/Bi-fold/Hinged/Pivot doors; Shopfront/Curtain Wall/Architectural/Thermally Broken framing; Invisi-Gard, KidScreen, Flyscreens, Mesh; Louvres/Sun control; Balustrades; Hardware; Accessories) | Partial demo service children only | **Phase scope:** surface catalogue systems as sections/subpages linked from Windows/Doors/Commercial; full deep catalogue pages can be staged if time — **do not invent specs beyond PDF** | Preserve Alspec/system names from PDF |

---

## Alternating layout plan (applicable pages)

Pattern (desktop): L-image/R-content → L-content/R-image → repeat.  
Mobile: stack image then content in reading order.  
Skip forced split on forms, maps, contact details tables, pure galleries.

| Page | Sections (planned) |
|------|-------------------|
| Home | Hero (full-bleed OK) + Why Choose alt + Product Range cards/alt + Melbourne conditions alt + CTA |
| About | Intro alt + Team/craftsmanship alt + Warranty CTA |
| Windows | Intro + one alt section per named system (5) |
| Doors | Intro + one alt section per named system (7) |
| Commercial | Intro + solutions list alt + partnership CTA |
| Process | Intro + 5 step alts or numbered timeline with images |
| Contact | Content + form (no forced alt on form) |
| Warranty | Short content (may be single column) |
| Gallery | Intro + media grid |
| Location | Intro + CTA (light alt if image available) |

---

## Image asset plan

| Preference | Assets |
|------------|--------|
| Company photography (use first) | `IMG_2626.jpg` (26005), `IMG_2650.jpg` (26001), `IMG_2472.jpg` (26000), `IMG_2192-scaled-1.jpg` (25995) |
| Product/service stock already on site | `windoor-serice-image-*.jpg`, `windoor-landing-inner-page-*.jpg`, `offer-image-1a.png` |
| Logo | `knoxlogo.png` / cropped variants |
| Missing | Dedicated labelled shots per trademarked system (Pro-Vista, View-Max, etc.) — **gap**: reuse closest category image + note in delivery report; do not invent project captions |

Alt text: descriptive product/context; empty for decorative.

---

## SEO plan (every PDF-listed page)

- One H1 matching page subject  
- Unique AIOSEO title + meta description (AU English, no stuffing)  
- Preserve existing URLs where pages already exist  
- Canonical via AIOSEO defaults  
- Internal links: Home ↔ Windows/Doors/Commercial/Process/Contact/Quote  
- No fake reviews/ratings/local pack schema beyond accurate LocalBusiness NAP if already present  

Suggested title patterns:
- Home: `Aluminium Windows & Doors Melbourne \| Knox Aluminium`
- Windows: `Architectural Aluminium Windows Melbourne \| Knox Aluminium`
- Doors: `Architectural Aluminium Doors Melbourne \| Knox Aluminium`
- Commercial: `Commercial Aluminium Systems Melbourne \| Knox Aluminium`
- Process: `Our Process \| Knox Aluminium Windows & Doors`
- Contact: `Contact Knox Aluminium \| Knoxfield VIC`
- Location: `Aluminium Windows & Doors in {Suburb} \| Knox Aluminium`

---

## Navigation changes (required for acceptance)

Primary menu should become:

1. Home → `/`  
2. About Us → `/about-us/`  
3. Windows → `/windows/` *(new)*  
4. Doors → `/doors/` *(new)*  
5. Commercial Projects → `/commercial-projects/` *(new)*  
6. Process → `/process/` *(new)*  
7. Contact → `/contact-us/` *(restored/fixed)*  
8. CTA: Request a Free Quote → `/contact-us/` (or dedicated quote anchor)

Remove/repair invalid trash URLs. Keep demo service pages out of primary nav.

---

## Implementation order (manageable steps)

1. Backup exports of current page HTML / Elementor meta for Home + About + Contact  
2. Deploy reusable layout CSS/shortcode plugin (`knox-aluminium-content`)  
3. Restore/create Contact; create Windows, Doors, Commercial, Process, Warranty, Gallery  
4. Update Home + About content  
5. Create 20 location pages (template-driven)  
6. Fix Main Menu + quote CTAs  
7. Apply AIOSEO titles/descriptions  
8. Responsive QA desktop/tablet/mobile  
9. Delivery report  

---

## Out of scope / blocked (unless unblocked)

- Editing WordPress core / Elementor plugin files  
- Fabricating project photos, reviews, certifications beyond PDF  
- Publishing unsupported energy ratings / BAL / WERS claims not in PDF  
- Mass-deleting demo pages (preserve URLs; demote from nav only)  
- Hotlinking external copyrighted images  

---

## Acceptance mapping

| Criterion | Checklist coverage |
|-----------|-------------------|
| Every PDF page reviewed & mapped | This document |
| Content implemented or blocked documented | Delivery report after Phase 2–6 |
| Images + alt | Image plan + QA |
| Alternating L/R desktop | Layout plan + CSS component |
| Mobile stack | Responsive QA |
| Preserve branding/URLs/forms | Critical issues + nav plan |
| No unsupported claims | Content rules above |
