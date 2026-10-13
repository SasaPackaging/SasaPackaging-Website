/* SASA Packaging — Hero behaviour (standalone).
   Load AFTER sasa-premium.js. Only touches <section data-sx-hero>.
   1) Text slider: auto-advances, no dots/arrows. Only the text moves.
   2) Pauses the gallery animation while the hero is off-screen (saves CPU/battery).
   The gallery itself is pure CSS – no JavaScript is needed for it to move. */
(function () {
  "use strict";
  var doc = document;
  var reduceMotion =
    window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  doc.documentElement.classList.add("sx-js");

  function initHero(el) {
    var slides = [].slice.call(el.querySelectorAll(".sx-hs"));
    var OUT = 420; // ms – text exit time (a little longer than sxFall in the css)

    /* ---- gallery: pause when off-screen ---- */
    if ("IntersectionObserver" in window) {
      new IntersectionObserver(
        function (entries) {
          el.classList.toggle("is-offscreen", !entries[0].isIntersecting);
        },
        { threshold: 0 },
      ).observe(el);
    }

    if (slides.length < 2) {
      if (slides[0]) slides[0].classList.add("is-active");
      return;
    }

    var interval = parseInt(el.getAttribute("data-interval"), 10) || 1000;
    var index = 0,
      timer = null,
      busy = false,
      focusPaused = false;

    function setA11y(i) {
      slides.forEach(function (s, k) {
        var on = k === i;
        s.setAttribute("aria-hidden", on ? "false" : "true");
        s.querySelectorAll("a,button").forEach(function (n) {
          n.tabIndex = on ? 0 : -1;
        });
      });
    }
    slides.forEach(function (s) {
      s.classList.remove("is-active", "is-leaving");
    });
    slides[0].classList.add("is-active");
    setA11y(0);

    function go(next) {
      if (busy || next === index) return;
      busy = true;
      var prev = slides[index],
        cur = slides[next];
      index = next;
      setA11y(next);

      if (reduceMotion) {
        prev.classList.remove("is-active");
        cur.classList.add("is-active");
        busy = false;
        return;
      }
      prev.classList.remove("is-active");
      prev.classList.add("is-leaving"); // old text lifts away
      setTimeout(function () {
        prev.classList.remove("is-leaving");
        cur.classList.add("is-active"); // new text rises in
        busy = false;
      }, OUT);
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
    start();
  }

  function boot() {
    doc.querySelectorAll("[data-sx-hero]").forEach(initHero);
  }
  if (doc.readyState !== "loading") boot();
  else doc.addEventListener("DOMContentLoaded", boot);
})();
