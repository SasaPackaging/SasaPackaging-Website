/* SASA Packaging — Premium UI behaviour (vanilla JS, no dependencies).
   Handles: sticky header state, mobile menu + submenus, hero slider,
   back-to-top, gentle scroll reveal. Safe to load alongside the legacy
   jQuery theme scripts: it only touches elements with "sx-" classes. */
(function () {
  'use strict';
  var doc = document, root = doc.documentElement;
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  root.classList.add('sx-js');

  function ready(fn) { if (doc.readyState !== 'loading') fn(); else doc.addEventListener('DOMContentLoaded', fn); }

  /* ---------- Header ---------- */
  function initHeader() {
    var header = doc.getElementById('sxHeader');
    if (!header) return;
    var burger = header.querySelector('.sx-burger');
    var nav = doc.getElementById('sxNav');
    var mq = window.matchMedia('(max-width: 1199px)');

    function setOpen(open) {
      header.classList.toggle('is-open', open);
      if (burger) {
        burger.setAttribute('aria-expanded', open ? 'true' : 'false');
        burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      }
    }
    if (burger) burger.addEventListener('click', function () { setOpen(!header.classList.contains('is-open')); });

    // submenu accordions (mobile) – and tap-to-open on touch laptops
    header.querySelectorAll('.sx-sub-toggle').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var li = btn.closest('li');
        var open = !li.classList.contains('is-open');
        li.classList.toggle('is-open', open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    });

    // close on Escape / outside click / navigating to an on-page anchor
    doc.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        setOpen(false);
        header.querySelectorAll('li.is-open').forEach(function (li) { li.classList.remove('is-open'); });
      }
    });
    doc.addEventListener('click', function (e) {
      if (!header.contains(e.target)) {
        setOpen(false);
        header.querySelectorAll('li.is-open').forEach(function (li) { li.classList.remove('is-open'); });
      }
    });
    if (nav) nav.addEventListener('click', function (e) {
      var a = e.target.closest('a');
      if (a && a.getAttribute('href') && a.getAttribute('href').indexOf('#') > -1) setOpen(false);
    });
    var onChange = function () { if (!mq.matches) { setOpen(false); header.querySelectorAll('li.is-open').forEach(function (li) { li.classList.remove('is-open'); }); } };
    if (mq.addEventListener) mq.addEventListener('change', onChange); else if (mq.addListener) mq.addListener(onChange);

    var ticking = false;
    function onScroll() {
      if (ticking) return; ticking = true;
      window.requestAnimationFrame(function () {
        header.classList.toggle('is-scrolled', window.pageYOffset > 8);
        ticking = false;
      });
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------- Hero slider ---------- */
  function initSlider(el) {
    var slides = [].slice.call(el.querySelectorAll('.sx-slide'));
    if (slides.length < 2) { el.classList.add('is-single'); if (slides[0]) slides[0].classList.add('is-active'); return; }
    var dotsWrap = el.querySelector('.sx-dots');
    var prev = el.querySelector('.sx-arrow--prev');
    var next = el.querySelector('.sx-arrow--next');
    var pauseBtn = el.querySelector('.sx-pause');
    var interval = parseInt(el.getAttribute('data-interval'), 10) || 6000;
    var index = 0, timer = null, userPaused = reduceMotion, hoverPaused = false;
    var dots = [];

    slides.forEach(function (s, i) {
      if (dotsWrap) {
        var d = doc.createElement('button');
        d.type = 'button'; d.className = 'sx-dot';
        d.setAttribute('aria-label', 'Go to slide ' + (i + 1));
        d.addEventListener('click', function () { go(i, true); });
        dotsWrap.appendChild(d); dots.push(d);
      }
    });

    function render() {
      slides.forEach(function (s, i) {
        var on = i === index;
        s.classList.toggle('is-active', on);
        s.setAttribute('aria-hidden', on ? 'false' : 'true');
        // keep hidden slides out of the tab order
        s.querySelectorAll('a,button').forEach(function (n) { n.tabIndex = on ? 0 : -1; });
      });
      dots.forEach(function (d, i) { d.setAttribute('aria-current', i === index ? 'true' : 'false'); });
    }
    function go(i, manual) {
      index = (i + slides.length) % slides.length;
      render();
      if (manual) restart();
    }
    function stop() { if (timer) { clearInterval(timer); timer = null; } }
    function start() { stop(); if (!userPaused && !hoverPaused && !doc.hidden) timer = setInterval(function () { go(index + 1); }, interval); }
    function restart() { start(); }

    if (prev) prev.addEventListener('click', function () { go(index - 1, true); });
    if (next) next.addEventListener('click', function () { go(index + 1, true); });

    if (pauseBtn) {
      if (userPaused) pauseBtn.classList.add('is-paused');
      pauseBtn.setAttribute('aria-pressed', userPaused ? 'true' : 'false');
      pauseBtn.addEventListener('click', function () {
        userPaused = !userPaused;
        pauseBtn.classList.toggle('is-paused', userPaused);
        pauseBtn.setAttribute('aria-pressed', userPaused ? 'true' : 'false');
        pauseBtn.setAttribute('aria-label', userPaused ? 'Play slideshow' : 'Pause slideshow');
        start();
      });
    }

    // pause while hovering / focusing inside the hero
    el.addEventListener('mouseenter', function () { hoverPaused = true; stop(); });
    el.addEventListener('mouseleave', function () { hoverPaused = false; start(); });
    el.addEventListener('focusin', function () { hoverPaused = true; stop(); });
    el.addEventListener('focusout', function () { hoverPaused = false; start(); });
    doc.addEventListener('visibilitychange', function () { if (doc.hidden) stop(); else start(); });

    // keyboard arrows when the hero has focus
    el.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') { go(index - 1, true); }
      else if (e.key === 'ArrowRight') { go(index + 1, true); }
    });

    // touch / swipe (horizontal only; vertical scrolling is untouched)
    var sx = 0, sy = 0, tracking = false;
    el.addEventListener('touchstart', function (e) {
      if (e.touches.length !== 1) return;
      sx = e.touches[0].clientX; sy = e.touches[0].clientY; tracking = true; hoverPaused = true; stop();
    }, { passive: true });
    el.addEventListener('touchend', function (e) {
      if (!tracking) return; tracking = false; hoverPaused = false;
      var t = e.changedTouches[0], dx = t.clientX - sx, dy = t.clientY - sy;
      if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy) * 1.3) go(index + (dx < 0 ? 1 : -1), true);
      else start();
    }, { passive: true });
    el.addEventListener('touchcancel', function () { tracking = false; hoverPaused = false; start(); }, { passive: true });

    render();
    start();
  }

  /* ---------- Back to top ---------- */
  function initToTop() {
    var btn = doc.getElementById('sxToTop');
    if (!btn) return;
    function toggle() { btn.classList.toggle('is-visible', window.pageYOffset > 600); }
    window.addEventListener('scroll', toggle, { passive: true });
    btn.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' }); });
    toggle();
  }

  /* ---------- Scroll reveal ---------- */
  function initReveal() {
    var items = [].slice.call(doc.querySelectorAll('.sx-reveal'));
    if (!items.length) return;
    if (reduceMotion || !('IntersectionObserver' in window)) { items.forEach(function (n) { n.classList.add('is-in'); }); return; }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); } });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });
    items.forEach(function (n) { io.observe(n); });
    // safety net: never leave content hidden
    setTimeout(function () { items.forEach(function (n) { n.classList.add('is-in'); }); }, 4000);
  }

  ready(function () {
    initHeader();
    doc.querySelectorAll('[data-sx-slider]').forEach(initSlider);
    initToTop();
    initReveal();
  });
})();
