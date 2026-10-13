/* SASA Packaging — Mission & Values panels (standalone; load after sasa-premium.js).
   - Hover (mouse), tap/click, focus or Enter/Space opens a panel.
   - While the section is on screen and nobody is interacting, panels open one after another.
   - Phones: same panels, stacked as an accordion (auto-rotation is off there). */
(function () {
  "use strict";
  var doc = document;
  var reduceMotion =
    window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var mqDesktop = window.matchMedia("(min-width: 768px)");
  var canHover =
    window.matchMedia && window.matchMedia("(hover: hover)").matches;

  function init(sec) {
    var panels = [].slice.call(sec.querySelectorAll(".sx-vp"));
    if (!panels.length) return;
    var interval = parseInt(sec.getAttribute("data-interval"), 10) || 5500;
    var current = 0,
      timer = null,
      onScreen = false,
      interacting = false;

    sec.style.setProperty("--auto", interval / 1000 + "s");

    function open(i) {
      current = i;
      panels.forEach(function (p, k) {
        var on = k === i;
        p.classList.toggle("is-open", on);
        p.setAttribute("aria-expanded", on ? "true" : "false");
      });
      if (timer) restart();
    }

    function canAuto() {
      return (
        !reduceMotion &&
        mqDesktop.matches &&
        onScreen &&
        !interacting &&
        !doc.hidden
      );
    }
    function tick() {
      open((current + 1) % panels.length);
    }
    function stop() {
      if (timer) {
        clearInterval(timer);
        timer = null;
      }
      sec.classList.remove("is-auto");
    }
    function start() {
      stop();
      if (!canAuto()) return;
      sec.classList.remove("is-auto");
      void sec.offsetWidth;
      sec.classList.add("is-auto"); // restart the progress bar
      timer = setInterval(tick, interval);
    }
    function restart() {
      start();
    }

    panels.forEach(function (p, k) {
      if (canHover) {
        p.addEventListener("pointerenter", function () {
          if (mqDesktop.matches) open(k);
        });
      }
      p.addEventListener("click", function () {
        // on phones a second tap closes the open panel; on desktop a panel always stays open
        if (!mqDesktop.matches && p.classList.contains("is-open")) {
          p.classList.remove("is-open");
          p.setAttribute("aria-expanded", "false");
          return;
        }
        open(k);
      });
      p.addEventListener("focus", function () {
        open(k);
      });
      p.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          open(k);
        } else if (e.key === "ArrowRight" || e.key === "ArrowDown") {
          e.preventDefault();
          panels[(k + 1) % panels.length].focus();
        } else if (e.key === "ArrowLeft" || e.key === "ArrowUp") {
          e.preventDefault();
          panels[(k - 1 + panels.length) % panels.length].focus();
        }
      });
    });

    var row = sec.querySelector(".sx-vl");
    if (row) {
      row.addEventListener("pointerenter", function () {
        interacting = true;
        stop();
      });
      row.addEventListener("pointerleave", function () {
        interacting = false;
        start();
      });
      row.addEventListener("focusin", function () {
        interacting = true;
        stop();
      });
      row.addEventListener("focusout", function () {
        interacting = false;
        start();
      });
    }
    doc.addEventListener("visibilitychange", function () {
      if (doc.hidden) stop();
      else start();
    });
    if (mqDesktop.addEventListener) mqDesktop.addEventListener("change", start);

    /* entrance + only run auto-rotation while visible */
    sec.classList.add("is-armed");
    if ("IntersectionObserver" in window) {
      var seen = new IntersectionObserver(
        function (en) {
          if (en[0].isIntersecting) {
            sec.classList.add("is-in");
            seen.disconnect();
          }
        },
        { threshold: 0.15 },
      );
      seen.observe(sec);
      new IntersectionObserver(
        function (en) {
          onScreen = en[0].isIntersecting;
          start();
        },
        { threshold: 0.35 },
      ).observe(sec);
    } else {
      sec.classList.add("is-in");
      onScreen = true;
      start();
    }
    setTimeout(function () {
      sec.classList.add("is-in");
    }, 5000); // safety net: never leave panels hidden
  }

  function boot() {
    doc.querySelectorAll("[data-sx-values]").forEach(init);
  }
  if (doc.readyState !== "loading") boot();
  else doc.addEventListener("DOMContentLoaded", boot);
})();
