# SaaSMarket Pro Homepage Redesign

Premium front-page experience for `https://saasmarket.pro/staging/`.

## Important inventory note

At implementation time, this staging WooCommerce catalogue still contains **7 Spices restaurant menu items** from earlier staging work — not CRM/marketing SaaS SKUs.

The homepage therefore:

- Brands and positions the site as **SaaSMarket Pro**
- Uses **only real routes** (shop, my-account, contact, about, login)
- Renders **real WooCommerce categories and products** without inventing software listings, ratings, or prices
- Avoids empty fake categories such as “CRM & Sales Management” unless those terms exist with inventory

## Plugin

`plugin/saasmarket-pro-homepage/`

Install zip → activate. It replaces front-page rendering via `template_include` while leaving other pages/theme templates intact.

## Rollback

Deactivate **SaaSMarket Pro Homepage** to restore the previous Tamarind/theme homepage.
