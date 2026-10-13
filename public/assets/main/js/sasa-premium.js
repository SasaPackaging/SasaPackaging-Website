/* SASA Packaging — Premium UI behaviour (vanilla JS, no dependencies).
   Handles: sticky header state, mobile menu + submenus, hero slider,
   back-to-top, gentle scroll reveal. Safe to load alongside the legacy
   jQuery theme scripts: it only touches elements with "sx-" classes. */
(function () {
  "use strict";
  var doc = document,
    root = doc.documentElement;
  var reduceMotion =
    window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  root.classList.add("sx-js");

  function ready(fn) {
    if (doc.readyState !== "loading") fn();
    else doc.addEventListener("DOMContentLoaded", fn);
  }

  /* ---------- Header ---------- */
  function initHeader() {
    var header = doc.getElementById("sxHeader");
    if (!header) return;
    var burger = header.querySelector(".sx-burger");
    var nav = doc.getElementById("sxNav");
    var mq = window.matchMedia("(max-width: 1199px)");

    function setOpen(open) {
      header.classList.toggle("is-open", open);
      if (burger) {
        burger.setAttribute("aria-expanded", open ? "true" : "false");
        burger.setAttribute("aria-label", open ? "Close menu" : "Open menu");
      }
    }
    if (burger)
      burger.addEventListener("click", function () {
        setOpen(!header.classList.contains("is-open"));
      });

    header.querySelectorAll(".sx-sub-toggle").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var li = btn.closest("li");
        var open = !li.classList.contains("is-open");
        li.classList.toggle("is-open", open);
        btn.setAttribute("aria-expanded", open ? "true" : "false");
      });
    });

    doc.addEventListener("keydown", function (e) {
      if (e.key === "Escape") {
        setOpen(false);
        header.querySelectorAll("li.is-open").forEach(function (li) {
          li.classList.remove("is-open");
        });
      }
    });
    doc.addEventListener("click", function (e) {
      if (!header.contains(e.target)) {
        setOpen(false);
        header.querySelectorAll("li.is-open").forEach(function (li) {
          li.classList.remove("is-open");
        });
      }
    });
    if (nav)
      nav.addEventListener("click", function (e) {
        var a = e.target.closest("a");
        if (
          a &&
          a.getAttribute("href") &&
          a.getAttribute("href").indexOf("#") > -1
        )
          setOpen(false);
      });
    var onChange = function () {
      if (!mq.matches) {
        setOpen(false);
        header.querySelectorAll("li.is-open").forEach(function (li) {
          li.classList.remove("is-open");
        });
      }
    };
    if (mq.addEventListener) mq.addEventListener("change", onChange);
    else if (mq.addListener) mq.addListener(onChange);

    var ticking = false;
    function onScroll() {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(function () {
        header.classList.toggle("is-scrolled", window.pageYOffset > 8);
        ticking = false;
      });
    }
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
  }

  /* ---------- Hero slider (automatic, no dots / arrows / buttons) ----------
     Classes used on each .sx-slide:
       is-active  -> the slide being shown (wipes in)
       is-leaving -> the previous slide, kept underneath while the wipe runs */
  function initSlider(el) {
    var WIPE = 900; // ms – a little longer than --h-wipe (0.8s) in sasa-hero.css
    var slides = [].slice.call(el.querySelectorAll(".sx-slide"));
    if (!slides.length) return;
    if (slides.length < 2) {
      slides[0].classList.add("is-active");
      return;
    }

    var interval = parseInt(el.getAttribute("data-interval"), 10) || 1000;
    var index = 0,
      timer = null,
      busy = false,
      focusPaused = false;

    slides.forEach(function (s, i) {
      s.setAttribute("aria-hidden", i === 0 ? "false" : "true");
      s.querySelectorAll("a,button").forEach(function (n) {
        n.tabIndex = i === 0 ? 0 : -1;
      });
    });
    slides[0].classList.add("is-active");

    function go(next) {
      if (busy || next === index) return;
      busy = true;
      var prev = slides[index];
      var cur = slides[next];

      prev.classList.remove("is-active");
      prev.classList.add("is-leaving");
      prev.setAttribute("aria-hidden", "true");
      prev.querySelectorAll("a,button").forEach(function (n) {
        n.tabIndex = -1;
      });

      void cur.offsetWidth; // let the browser register the starting state
      cur.classList.add("is-active");
      cur.setAttribute("aria-hidden", "false");
      cur.querySelectorAll("a,button").forEach(function (n) {
        n.tabIndex = 0;
      });
      index = next;

      setTimeout(
        function () {
          prev.classList.remove("is-leaving");
          busy = false;
        },
        reduceMotion ? 650 : WIPE,
      );
    }
    function nextSlide() {
      go((index + 1) % slides.length);
    }

    function stop() {
      if (timer) {
        clearInterval(timer);
        timer = null;
      }
    }
    function start() {
      stop();
      if (!focusPaused && !doc.hidden) timer = setInterval(nextSlide, interval);
    }

    // Only pause for keyboard users tabbing into the hero, or when the tab is hidden.
    el.addEventListener("focusin", function (e) {
      if (e.target.matches && e.target.matches(":focus-visible")) {
        focusPaused = true;
        stop();
      }
    });
    el.addEventListener("focusout", function () {
      focusPaused = false;
      start();
    });
    doc.addEventListener("visibilitychange", function () {
      if (doc.hidden) stop();
      else start();
    });

    // Optional swipe on touch screens (horizontal only; vertical scroll is untouched).
    var sx = 0,
      sy = 0,
      tracking = false;
    el.addEventListener(
      "touchstart",
      function (e) {
        if (e.touches.length !== 1) return;
        sx = e.touches[0].clientX;
        sy = e.touches[0].clientY;
        tracking = true;
        stop();
      },
      { passive: true },
    );
    el.addEventListener(
      "touchend",
      function (e) {
        if (!tracking) return;
        tracking = false;
        var t = e.changedTouches[0],
          dx = t.clientX - sx,
          dy = t.clientY - sy;
        if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy) * 1.3)
          go((index + (dx < 0 ? 1 : slides.length - 1)) % slides.length);
        start();
      },
      { passive: true },
    );
    el.addEventListener(
      "touchcancel",
      function () {
        tracking = false;
        start();
      },
      { passive: true },
    );

    start();
  }

  /* ---------- Back to top ---------- */
  function initToTop() {
    var btn = doc.getElementById("sxToTop");
    if (!btn) return;
    function toggle() {
      btn.classList.toggle("is-visible", window.pageYOffset > 600);
    }
    window.addEventListener("scroll", toggle, { passive: true });
    btn.addEventListener("click", function () {
      window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" });
    });
    toggle();
  }

  /* ---------- Scroll reveal ---------- */
  function initReveal() {
    var items = [].slice.call(doc.querySelectorAll(".sx-reveal"));
    if (!items.length) return;
    if (reduceMotion || !("IntersectionObserver" in window)) {
      items.forEach(function (n) {
        n.classList.add("is-in");
      });
      return;
    }
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting) {
            en.target.classList.add("is-in");
            io.unobserve(en.target);
          }
        });
      },
      { rootMargin: "0px 0px -8% 0px", threshold: 0.05 },
    );
    items.forEach(function (n) {
      io.observe(n);
    });
    setTimeout(function () {
      items.forEach(function (n) {
        n.classList.add("is-in");
      });
    }, 4000);
  }

  ready(function () {
    initHeader();
    doc.querySelectorAll("[data-sx-slider]").forEach(initSlider);
    initToTop();
    initReveal();
  });
})();
