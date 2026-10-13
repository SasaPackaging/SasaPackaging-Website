/* SASA Packaging — Services grid behaviour (standalone; load after sasa-premium.js).
   1) Cards fade/rise in one by one as they scroll into view.
   2) A soft light follows the pointer inside a hovered card (desktop only). */
(function () {
  "use strict";
  var doc = document;
  var reduceMotion =
    window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var canHover =
    window.matchMedia && window.matchMedia("(hover: hover)").matches;

  function init(sec) {
    var items = [].slice.call(sec.querySelectorAll(".sx-sv__item"));
    sec.classList.add("is-armed");

    if (!("IntersectionObserver" in window) || reduceMotion) {
      items.forEach(function (n) {
        n.classList.add("is-in");
      });
    } else {
      var io = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (en) {
            if (en.isIntersecting) {
              en.target.classList.add("is-in");
              io.unobserve(en.target);
            }
          });
        },
        { rootMargin: "0px 0px -6% 0px", threshold: 0.12 },
      );
      items.forEach(function (n) {
        io.observe(n);
      });
      setTimeout(function () {
        items.forEach(function (n) {
          n.classList.add("is-in");
        });
      }, 5000); // safety net
    }

    if (canHover && !reduceMotion) {
      sec.querySelectorAll(".sx-sv__card").forEach(function (card) {
        card.addEventListener("pointermove", function (e) {
          var r = card.getBoundingClientRect();
          card.style.setProperty("--mx", e.clientX - r.left + "px");
          card.style.setProperty("--my", e.clientY - r.top + "px");
        });
      });
    }
  }

  function boot() {
    doc.querySelectorAll("[data-sx-services]").forEach(init);
  }
  if (doc.readyState !== "loading") boot();
  else doc.addEventListener("DOMContentLoaded", boot);
})();
