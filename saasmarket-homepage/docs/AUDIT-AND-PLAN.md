# SaaSMarket Pro Homepage — Audit & Plan

## Stage 1 findings

| Item | Reality on staging |
|------|--------------------|
| URL | https://saasmarket.pro/staging/ |
| CMS | WordPress + WooCommerce |
| Theme | Tamarind (restaurant), no child theme |
| Homepage | Static page `homepage` (ID 12677) via iMaginem Page Builder + Slider Revolution |
| Public catalogue | WooCommerce shop — currently **7 Spices Indian restaurant dishes** (~104 products) |
| Software inventory | **None** (no CRM/marketing/finance SaaS products) |

### Working routes used by the redesign

- Shop / Explore: `/index.php/shop/`
- Account / Get Started: `/index.php/my-account/`
- Login: `/wp-login.php`
- Contact: `/index.php/pages/contact-us/`
- About: `/index.php/pages/about-us/`
- Services: `/index.php/pages/our-services/`

## Stage 2 plan

Implement a self-contained plugin that swaps only the front-page template:

1. Premium SaaSMarket Pro branding and hero
2. Real category cards from `product_cat` (hide empty / uncategorized)
3. Real featured product cards from WooCommerce (no invented ratings/software claims)
4. Why / How / Use cases / Trust / Final CTA / Footer
5. Mobile nav + light motion
6. Rollback = deactivate plugin

## Honesty constraints applied

- Do **not** invent CRM/Marketing/HR category cards with zero inventory
- Do **not** invent software product names, prices, or testimonials
- Catalogue sections label content as live listings from WooCommerce
