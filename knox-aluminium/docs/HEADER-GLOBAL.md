# Homepage Header Applied Sitewide

## Root cause
Windoor page setting `_windoor_custom_settings[header]` was set to **Home-1-header (`15461`)** only on the homepage. Other pages left “Choose Header” empty and rendered an empty `header-11` placeholder.

## Shared component
Existing Windoor CPT header: **`wdt_headers` #15461 — Home-1-header** (Elementor-built topbar, logo, menus, CTAs). Not duplicated.

## Implementation
Plugin `knox-aluminium-content` v1.1.0:
- `includes/header.php` — forces Home-1-header via `get_post_metadata` for public content + persists meta with **Tools → Apply homepage header sitewide**
- Wired into content sync

## Coverage
All public pages/posts under `/kaluminium/` (verified: Home, About, Windows, Doors, Contact, Process, Services, Casement Windows, News, posts, locations, gallery, warranty).
