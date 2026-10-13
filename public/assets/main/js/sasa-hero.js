/* SASA Packaging — Hero slider (standalone, automatic, no dots / arrows).
   Load AFTER sasa-premium.js. It only touches <section data-sx-hero>,
   so the old slider code inside sasa-premium.js is simply never used. */
(function () {
  "use strict";
  var doc = document;
  var reduceMotion =
    window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  doc.documentElement.classList.add("sx-js");

  function initSlider(el) {
    var SWEEP = 1100; // ms – must match --h-sweep (1.1s) in sasa-hero.css
    var SWAP_AT = 0.5; // swap slides when the curtain fully covers (50%)
    var slides = [].slice.call(el.querySelectorAll(".sx-slide"));
    if (!slides.length) return;

    // give every slide its own colour tone (1-4), whatever the markup says
    slides.forEach(function (s, i) {
      s.classList.remove(
        "sx-slide--c1",
        "sx-slide--c2",
        "sx-slide--c3",
        "sx-slide--c4",
      );
      s.classList.add("sx-slide--c" + ((i % 4) + 1));
    });
    if (slides.length < 2) {
      slides[0].classList.add("is-active");
      return;
    }

    var interval = parseInt(el.getAttribute("data-interval"), 10) || 5000;
    var index = 0,
      timer = null,
      busy = false,
      focusPaused = false;

    var sweep = doc.createElement("div");
    sweep.className = "sx-sweep";
    sweep.setAttribute("aria-hidden", "true");
    el.appendChild(sweep);

    function show(i) {
      slides.forEach(function (s, k) {
        var on = k === i;
        s.classList.toggle("is-active", on);
        s.setAttribute("aria-hidden", on ? "false" : "true");
        s.querySelectorAll("a,button").forEach(function (n) {
          n.tabIndex = on ? 0 : -1;
        });
      });
      index = i;
    }
    show(0);

    function go(next) {
      if (busy || next === index) return;
      busy = true;
      if (reduceMotion) {
        show(next);
        busy = false;
        return;
      }

      sweep.className = "sx-sweep";
      void sweep.offsetWidth; // restart the animation
      sweep.className = "sx-sweep is-run sx-sweep--to" + ((next % 4) + 1);

      setTimeout(function () {
        show(next);
      }, SWEEP * SWAP_AT);
      setTimeout(function () {
        sweep.className = "sx-sweep";
        busy = false;
      }, SWEEP + 50);
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

    // optional swipe on touch screens
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

  function boot() {
    doc.querySelectorAll("[data-sx-hero]").forEach(initSlider);
  }
  if (doc.readyState !== "loading") boot();
  else doc.addEventListener("DOMContentLoaded", boot);
})();
