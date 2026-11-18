# Far Corners EU header fixes

## Live changes applied in admin
1. **Top header text** (Frontend Settings → Top Header Marketing):
   `Private Tours • Group Tours • Business Tours • Luxury Tour`
2. **Language box styling** loaded site-wide via Backend Settings → Tawk Embed URL
   (chat was disabled; embed URL temporarily points at this fix script).
   Original Tawk URL: `https://embed.tawk.to/658d2c0670c9f2407f83ed78/1hinn7v0j`

## Assets
- `header-fix.css` / `header-fix.js` — styles Google Translate for the dark top bar and scopes nested Luxury Tour CSS so global `*` / `body` resets no longer break the header language control.

## Note
FTP credentials provided did not authenticate, so file-level theme edits were not possible. Theme `custom_css` / `custom_js` fields also do not render on the live frontend.
