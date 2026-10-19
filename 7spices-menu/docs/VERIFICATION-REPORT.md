# 7 Spices Menu Update — Verification Report

**Site:** https://saasmarket.pro/staging/  
**Date:** 2026-10-18  
**Source PDF:** Scan_20261012_201755_compressed.pdf (5 pages)

## Architecture inspected

| Item | Finding |
|------|---------|
| Public MENU | WooCommerce Shop |
| Theme | Tamarind (parent only) |
| CPT Food Items | Present (21 demo items) but **iMaginem Food Creator deactivated** — not used publicly |
| Active menu system | **WooCommerce products + product_cat** |
| Rollback | WooCommerce product CSV export taken before sync; unmatched items drafted (not deleted) |

## Extraction

- Structured JSON: `docs/menu-from-pdf.json`
- **22 categories / 104 items**
- Ambiguous OCR prices re-checked against page images; see `docs/ambiguous-flags.md`

## Sync results (live)

| Metric | Value |
|--------|------:|
| Created | 80 |
| Updated | 24 |
| Simple products | 76 |
| Variable products | 28 |
| Drafted (not in PDF) | 5 |
| Published on shop | 104 |

Plugin: **7 Spices Menu Sync 1.0.0** (WooCommerce → 7 Spices Menu Sync)

## Spot checks

| Item | Expected | Observed |
|------|----------|----------|
| BOMBAY BHEL | $8 | $8 |
| HARA BHARA KABAB | $10 | $10 |
| MIMOSA | $10 | $10 |
| TIKKA MASALA (variable) | Paneer 17 … Crab Kofta 26 | Match |
| Appetizers category | 7 items | 7 |
| Shop notices | Allergy + 18% service charge | Displayed |

## Soft notes / manual review

1. **MATTAR PANEER** description appears truncated on the scan (“Tomato-based”).
2. PDF spellings preserved (`potatoe`, `Budwiser`, `Tropcalia`, `Nuggest`, etc.).
3. Specialty Naan on page 2 (Chili Cheese / Truffle / Peshawari) and Bread Naan on page 4 are both present as separate category groups.
4. Demo CPT “Food Items” (Irish demo dishes) left untouched; Food Creator remains deactivated.
5. **Payment / order placement** not tested as part of this menu content update.

## Files

- `7spices-menu/plugin/7spices-menu-sync/` — sync plugin
- `7spices-menu/7spices-menu-sync.zip` — installable zip
- `7spices-menu/docs/menu-from-pdf.json` — source of truth
- `7spices-menu/docs/ambiguous-flags.md`
- `7spices-menu/docs/VERIFICATION-REPORT.md` — this file
