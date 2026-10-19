(function () {
  'use strict';

  var toggle = document.querySelector('[data-smp-nav-toggle]');
  var nav = document.querySelector('[data-smp-nav]');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    nav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var targets = document.querySelectorAll('.smp-section, .smp-cta, .smp-hero__copy, .smp-hero__visual');
  targets.forEach(function (el) {
    el.classList.add('smp-reveal');
  });

  if (reduce) {
    targets.forEach(function (el) {
      el.classList.add('is-visible');
    });
    return;
  }

  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
    );
    targets.forEach(function (el) {
      io.observe(el);
    });
  } else {
    targets.forEach(function (el) {
      el.classList.add('is-visible');
    });
  }
})();
