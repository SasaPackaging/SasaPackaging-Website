/* SASA Shop: product page picture switcher + soft fade-in of the grid tiles. No library needed. */
(function () {
  "use strict";

  // 1. Product page: click a small picture to show it big
  var gallery = document.querySelector("[data-shp-gallery]");
  if (gallery) {
    var main = gallery.querySelector("[data-shp-main]");
    var thumbs = gallery.querySelectorAll("[data-shp-thumb]");
    Array.prototype.forEach.call(thumbs, function (btn) {
      btn.addEventListener("click", function () {
        main.src = btn.getAttribute("data-shp-thumb");
        Array.prototype.forEach.call(thumbs, function (b) { b.classList.remove("is-active"); });
        btn.classList.add("is-active");
      });
    });
  }

  // 2. Grid tiles fade in when they scroll into view (tiles are visible at once if the browser cannot do it)
  var cards = document.querySelectorAll("[data-shp-card]");
  if (!cards.length || !("IntersectionObserver" in window)) { return; }
  document.documentElement.classList.add("shp-anim");
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-in");
        io.unobserve(entry.target);
      }
    });
  }, { rootMargin: "0px 0px -6% 0px" });
  Array.prototype.forEach.call(cards, function (c) { io.observe(c); });
})();
