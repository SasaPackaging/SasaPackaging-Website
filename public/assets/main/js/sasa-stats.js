/* SASA Packaging — "SASA in numbers" behaviour (standalone; load after sasa-premium.js).
   Entrance animation + soft pointer light on the cards.
   The count-up of the numbers itself is handled by the script in landing/index.blade.php. */
(function () {
  "use strict";
  var doc = document;
  var reduceMotion =
    window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var canHover =
    window.matchMedia && window.matchMedia("(hover: hover)").matches;

  function init(sec) {
    var items = [].slice.call(sec.querySelectorAll(".sx-nm__item"));
    sec.classList.add("is-armed");

    if (!("IntersectionObserver" in window) || reduceMotion) {
      sec.classList.add("is-in");
      items.forEach(function (n) {
        n.classList.add("is-in");
      });
    } else {
      var head = new IntersectionObserver(
        function (en) {
          if (en[0].isIntersecting) {
            sec.classList.add("is-in");
            head.disconnect();
          }
        },
        { threshold: 0.12 },
      );
      head.observe(sec);
      var io = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (en) {
            if (en.isIntersecting) {
              en.target.classList.add("is-in");
              io.unobserve(en.target);
            }
          });
        },
        { rootMargin: "0px 0px -8% 0px", threshold: 0.15 },
      );
      items.forEach(function (n) {
        io.observe(n);
      });
      setTimeout(function () {
        sec.classList.add("is-in");
        items.forEach(function (n) {
          n.classList.add("is-in");
        });
      }, 5000); // safety net
    }

    if (canHover && !reduceMotion) {
      sec.querySelectorAll(".sx-nm__card").forEach(function (card) {
        card.addEventListener("pointermove", function (e) {
          var r = card.getBoundingClientRect();
          card.style.setProperty("--mx", e.clientX - r.left + "px");
          card.style.setProperty("--my", e.clientY - r.top + "px");
        });
      });
    }
  }

  function boot() {
    doc.querySelectorAll("[data-sx-numbers]").forEach(init);
  }
  if (doc.readyState !== "loading") boot();
  else doc.addEventListener("DOMContentLoaded", boot);
})();
