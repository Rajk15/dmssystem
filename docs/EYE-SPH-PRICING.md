# Aqualens OD/OS SPH Conditional Pricing

## Inspection findings

| Item | Value |
|---|---|
| Product | Aqualens 24Hrs NXT Monthly Disposable |
| WooCommerce product ID | **33360** |
| Regular price | ₹1,295.00 |
| Sale / selling price | **₹899.00** |
| EPO plugin | Extra Product Options & Add-Ons for WooCommerce (ThemeComplete) **6.4.4** |
| Theme | Bw Zenny (+ inactive child theme available) |

### How ThemeComplete EPO prices options today

EPO adds option/fee amounts on top of the WooCommerce product selling price and renders a live totals box (`#tm-epo-totals`, `data-price="899"`).

On this product, prescription entry has three SPH selects behind conditional logic:

| UI | EPO field `name` | Role |
|---|---|---|
| OD (Right Eye) SPH | `tmcp_tmcartfee_select_3` | Fee select; required when eyes differ |
| OS (Left Eye) SPH | `tmcp_select_4` | Option select; required when eyes differ |
| Left & Right SPH | `tmcp_select_5` | Shown when “I have same power for both eyes” is checked |
| Same-power checkbox | `tmcp_tmcartfee_checkbox_2_0` | Toggles combined vs separate SPH |

Most SPH options use **empty / fixed 0** prices. A few powers (e.g. `-10.00`) still have math formulas such as `{product_price_plus_options_total}`, which would incorrectly add another product price. The custom plugin zeros SPH row prices in the cart and multiplies from the original selling price so those leftover formulas cannot double-count.

**Important:** Selecting both dropdowns alone must not double the price. Only **different normalized SPH values** do.

## Approach

### Frontend (`assets/eye-sph-pricing.js`)

1. Read OD / OS / combined SPH selects by their EPO `name` attributes (not fragile generated IDs).
2. Normalize values (`-1.00_12` → `-1.00`, trim/case).
3. Multiplier `1` when same (or same-power mode); `2` when both valid and different.
4. Always recalculate from the original base (`data-eyza-sph-base` / localized `basePrice`).
5. Write the adjusted amount into EPO’s `data-price` and trigger `tm-do-epo-update`.

### Server (`eyza-eye-sph-pricing.php`)

1. On add-to-cart, store normalized OD/OS + multiplier in cart item data (`eyza_sph`).
2. On `woocommerce_before_calculate_totals` (priority 9999), set unit price to `wc_get_product()->get_price() * multiplier`.
3. Early in calculate totals, zero SPH-related `tmcartepo` / `tmcartfee` row prices to avoid EPO math double-counting.
4. Persist OD/OS metadata on the order line item.

JavaScript is display-only. Cart, checkout, and orders never trust a client-supplied price.

## Scope

Only product ID `33360`. Other products are untouched. Filterable via `eyza_sph_product_id` and `eyza_sph_field_names` if field order ever changes in EPO.

## Recommended EPO hygiene (optional but advised)

In product 33360 Extra Product Options builder, set **every** OD/OS/Left&Right SPH option to:

- Price type: **Fixed amount**
- Price / sale price: **empty (0)**
- OD may remain “Set to Fee: ON”; prices should still be 0

This plugin still works if leftover math formulas exist, but keeping SPH options at 0 is the cleanest configuration.
