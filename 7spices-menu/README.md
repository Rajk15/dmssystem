# 7 Spices Menu Update

Updates the staging WooCommerce catalog at `https://saasmarket.pro/staging/` to match the scanned restaurant menu PDF.

## Architecture (inspected)

- **Public MENU** → WooCommerce Shop (`/index.php/shop/`)
- **Theme:** Tamarind (demo CPT Food Items exist but the Food Creator plugin is deactivated; not used for the public menu)
- **Source of truth:** `docs/menu-from-pdf.json` extracted from `Scan_20261012_201755_compressed.pdf`

## Plugin

`plugin/7spices-menu-sync/` — upload zip via WP Admin → Plugins → Add New → Upload.

Then: **WooCommerce → 7 Spices Menu Sync → Run menu sync now**.

Optional checkbox drafts catalog products that are not part of the PDF (does not delete).

## Notices

Sync stores allergy + 18% service-charge notices and shows them above the shop loop.
