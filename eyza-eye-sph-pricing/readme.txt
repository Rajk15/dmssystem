=== Eyza Eye SPH Conditional Pricing ===
Contributors: eyzakart
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later

Doubles the Aqualens 24Hrs NXT Monthly Disposable selling price when OD and OS SPH values differ.

== Description ==

Scoped to WooCommerce product ID 33360 (Aqualens 24Hrs NXT Monthly Disposable).

Pricing rules:
* Same SPH for both eyes → 1 × selling price (including sale price)
* Different SPH for OD and OS → 2 × selling price
* "I have same power for both eyes" (combined Left & Right SPH) → 1 × selling price
* Incomplete required fields → does not double; EPO required validation still blocks add-to-cart

Frontend updates ThemeComplete Extra Product Options totals display.
Cart, checkout, and order line prices are calculated securely on the server — after ThemeComplete EPO — and stamped so later EPO resets cannot wipe the custom unit price.

== Installation ==

1. Upload the `eyza-eye-sph-pricing` folder to `/wp-content/plugins/`
   or upload the zip via Plugins → Add New → Upload Plugin.
2. Activate **Eyza Eye SPH Conditional Pricing**.
3. Confirm Extra Product Options SPH option prices are Fixed amount / empty (0)
   so EPO does not also add a product-price math formula on top of this plugin.
4. Test on the product page and in cart/checkout.

== Changelog ==

= 1.1.0 =
* Fix cart/checkout persistence: apply unit price after EPO (PHP_INT_MAX), stamp
  product objects, and filter get_price so ThemeComplete cannot reset to base price.
* Preserve working product-page JavaScript unchanged.

= 1.0.0 =
* Initial release: OD/OS SPH comparison pricing for product 33360.
