# Neptune About Us – mobile + contrast fix

**Live URL:** https://neptunesolutionaus.com/designs/about-us/

## Problems
- Breadcrumb/title text was hard to read on the light breadcrumb background image.
- Global white paragraph color made text disappear on Elementor light sections (`#F8F8F8`).
- Marquee strip used near-invisible text color and overflowed on small screens.
- Several blocks needed tighter mobile typography/spacing.

## Fix (deployed on WordPress)
Injected page-scoped CSS via `wp-content/themes/faren/functions.php` (markers `NSA_ABOUT_US_FIX_START` / `NSA_ABOUT_US_FIX_END`):

- Force **color: #ffffff** and **background: #120F0B** on unreadable areas (breadcrumb, light Elementor sections, marquee).
- Mobile breakpoints for title, counters, marquee, choose-cards, and overflow clipping.
- Scoped to `.page-id-36` / About Us only.

See `about-us-fix.css` for the CSS payload currently enqueued on the live page.
