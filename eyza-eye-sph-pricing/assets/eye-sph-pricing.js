/**
 * Eyza Eye SPH Conditional Pricing — frontend display.
 *
 * Multiplies the ThemeComplete EPO product base price by 1 or 2 when OD/OS SPH
 * values differ. Always recalculates from the original base price (never compounds).
 * Cart/checkout amounts are enforced server-side in PHP.
 */
(function ($) {
	'use strict';

	if (typeof eyzaEyeSphPricing === 'undefined') {
		return;
	}

	var cfg = eyzaEyeSphPricing;
	var fields = cfg.fieldNames || {};
	var productId = parseInt(cfg.productId, 10) || 0;
	var originalBase = parseFloat(cfg.basePrice);
	var filterRegistered = false;

	if (isNaN(originalBase)) {
		originalBase = null;
	}

	function normalizeSph(raw) {
		if (raw === null || typeof raw === 'undefined') {
			return null;
		}
		var value = String(raw).trim();
		if (!value) {
			return null;
		}
		// EPO select values: "-1.00_12"
		value = value.replace(/_\d+$/, '');
		value = value.replace(/\s+/g, '').toLowerCase();
		return value || null;
	}

	function fieldSelector(name) {
		if (!name) {
			return $();
		}
		return $('select[name="' + name + '"], input[name="' + name + '"]');
	}

	function isSamePowerMode() {
		var $chk = fieldSelector(fields.same_power);
		if ($chk.filter(':checked').length) {
			return true;
		}
		var $both = fieldSelector(fields.both).filter('select');
		if ($both.length && $both.filter(':visible').length) {
			return true;
		}
		return false;
	}

	function getMultiplier() {
		if (isSamePowerMode()) {
			return 1;
		}

		var $od = fieldSelector(fields.od).filter('select');
		var $os = fieldSelector(fields.os).filter('select');

		var odVal = normalizeSph($od.filter(':visible').first().val() || $od.first().val());
		var osVal = normalizeSph($os.filter(':visible').first().val() || $os.first().val());

		if (!odVal || !osVal) {
			return 1;
		}

		return odVal === osVal ? 1 : 2;
	}

	function getTotalsContainer() {
		var $totals = $('.tm-epo-totals.tm-product-id-' + productId);
		if (!$totals.length) {
			$totals = $('.tm-epo-totals').first();
		}
		return $totals;
	}

	function ensureOriginalBase($totals) {
		if (originalBase !== null && !isNaN(originalBase) && originalBase > 0) {
			return originalBase;
		}

		var stored = $totals.attr('data-eyza-sph-base');
		if (stored) {
			originalBase = parseFloat(stored);
		} else {
			originalBase = parseFloat($totals.data('price'));
		}

		if (isNaN(originalBase)) {
			originalBase = 0;
		}

		$totals.attr('data-eyza-sph-base', originalBase);
		return originalBase;
	}

	/**
	 * Prefer EPO's filter API so the product price is multiplied at the source
	 * instead of patching the DOM after each recalculation.
	 */
	function registerEpoFilter() {
		if (filterRegistered) {
			return;
		}
		if (!$.epoAPI || typeof $.epoAPI.addFilter !== 'function') {
			return;
		}

		$.epoAPI.addFilter(
			'tc_calculate_product_price',
			function (price, totals) {
				var $totals = totals && totals.length ? totals : getTotalsContainer();
				if (!$totals.length) {
					return price;
				}

				// Only affect this product's totals box.
				var tid = parseInt($totals.attr('data-product-id') || $totals.data('product-id') || 0, 10);
				if (!tid) {
					var cls = $totals.attr('class') || '';
					var m = cls.match(/tm-product-id-(\d+)/);
					if (m) {
						tid = parseInt(m[1], 10);
					}
				}
				if (tid && tid !== productId) {
					return price;
				}

				var base = ensureOriginalBase($totals);
				if (!base) {
					return price;
				}

				return base * getMultiplier();
			},
			20,
			2
		);

		filterRegistered = true;
	}

	function triggerEpoUpdate() {
		$(window).trigger('tm-do-epo-update');
	}

	function bind() {
		registerEpoFilter();

		var selectors = [
			'select[name="' + (fields.od || '') + '"]',
			'select[name="' + (fields.os || '') + '"]',
			'select[name="' + (fields.both || '') + '"]',
			'input[name="' + (fields.same_power || '') + '"]',
			'input[name="tmcp_radio_0"]'
		].join(', ');

		$(document).on('change', selectors, function () {
			setTimeout(function () {
				registerEpoFilter();
				triggerEpoUpdate();
			}, 30);
		});

		$(window).on('cpflogicdone', function () {
			setTimeout(function () {
				registerEpoFilter();
				triggerEpoUpdate();
			}, 40);
		});

		// Capture original base once EPO totals exist, then refresh.
		setTimeout(function () {
			registerEpoFilter();
			ensureOriginalBase(getTotalsContainer());
			triggerEpoUpdate();
		}, 300);
	}

	$(function () {
		if (!productId) {
			return;
		}
		if (!$('body').hasClass('postid-' + productId) && !$('.tm-epo-totals.tm-product-id-' + productId).length) {
			return;
		}
		bind();
	});
})(jQuery);
