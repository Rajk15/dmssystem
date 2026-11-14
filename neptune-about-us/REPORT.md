# Neptune About Us – full-page contrast + mobile fix

**Live URL:** https://neptunesolutionaus.com/designs/about-us/

## Issues
- Sticky header turned light while menu links stayed light → unreadable
- Global white paragraph color collided with theme light sections (`#F8F8F8`, white cards)
- Intro right-side image clipped on mobile
- Marquee / feature strips had near-invisible text

## Fix (deployed)
Page-scoped CSS in `wp-content/themes/faren/functions.php` (`NSA_ABOUT_US_FIX_*`):
- Surfaces: `#120F0B`
- Text/links/headings: `#ffffff` (accent hover `#be883d`)
- Sticky header + mobile menu forced dark with white labels
- About/features/team/service/choose/FAQ/testimonial/brand cards darkened
- Intro image stacked/visible under 1024px

See `about-us-fix.css` for the live payload.
