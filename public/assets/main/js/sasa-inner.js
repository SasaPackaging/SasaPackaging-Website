/* SASA Packaging — inner pages behaviour (vanilla JS, no dependencies).
   Used only by Products & Services / Category / Product detail.
   Features: split-word headline, scroll reveal, tile tilt + spotlight, hero parallax,
   category filter, product gallery (thumbs, hover-zoom, lightbox). */
(function () {
  "use strict";
  var doc = document;
  var reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var fine = window.matchMedia && window.matchMedia("(hover: hover) and (pointer: fine)").matches;
  function $(s, c) { return (c || doc).querySelector(s); }
  function $$(s, c) { return [].slice.call((c || doc).querySelectorAll(s)); }
  function ready(fn) { if (doc.readyState !== "loading") fn(); else doc.addEventListener("DOMContentLoaded", fn); }

  /* ---- headline: wrap each word so it can slide up ---- */
  function splitWords() {
    $$("[data-split]").forEach(function (el) {
      var i = 0;
      (function walk(node) {
        [].slice.call(node.childNodes).forEach(function (n) {
          if (n.nodeType === 3) {
            var frag = doc.createDocumentFragment();
            n.textContent.split(/(\s+)/).forEach(function (part) {
              if (!part) return;
              if (/^\s+$/.test(part)) { frag.appendChild(doc.createTextNode(" ")); return; }
              var w = doc.createElement("span"); w.className = "w";
              var s = doc.createElement("span"); s.style.setProperty("--i", i++); s.textContent = part;
              w.appendChild(s); frag.appendChild(w);
            });
            node.replaceChild(frag, n);
          } else if (n.nodeType === 1) walk(n);
        });
      })(el);
      el.classList.add("sxi-split");
    });
  }

  /* ---- reveal on scroll ---- */
  function initReveal() {
    var items = $$(".sxi-reveal");
    if (!items.length) return;
    function show(el, delay) {
      el.style.transitionDelay = delay + "ms";
      el.classList.add("sxi-in");
      setTimeout(function () { el.style.transitionDelay = ""; }, 1100 + delay);
    }
    if (reduce || !("IntersectionObserver" in window)) { items.forEach(function (el) { el.classList.add("sxi-in"); }); return; }
    var batch = 0, timer = null;
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        io.unobserve(e.target);
        show(e.target, Math.min(batch++, 8) * 80);
        clearTimeout(timer); timer = setTimeout(function () { batch = 0; }, 140);
      });
    }, { rootMargin: "0px 0px -6% 0px", threshold: 0.08 });
    items.forEach(function (el) { io.observe(el); });
  }

  /* ---- tile tilt + cursor spotlight ---- */
  function initTiles(scope) {
    if (!fine || reduce) return;
    $$(".sxi-tile", scope).forEach(function (t) {
      if (t._sxi) return; t._sxi = true;
      t.addEventListener("mousemove", function (e) {
        var r = t.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width, y = (e.clientY - r.top) / r.height;
        t.style.setProperty("--mx", x * 100 + "%"); t.style.setProperty("--my", y * 100 + "%");
        t.style.setProperty("--ry", ((x - 0.5) * 7).toFixed(2) + "deg");
        t.style.setProperty("--rx", ((0.5 - y) * 7).toFixed(2) + "deg");
      });
      t.addEventListener("mouseleave", function () { t.style.setProperty("--rx", "0deg"); t.style.setProperty("--ry", "0deg"); });
    });
  }

  /* ---- hero parallax (mouse + scroll) ---- */
  function initHero() {
    var hero = $(".sxi-hero");
    if (!hero || reduce) return;
    var layers = $$("[data-depth]", hero);
    if (fine) hero.addEventListener("mousemove", function (e) {
      var r = hero.getBoundingClientRect();
      var x = (e.clientX - r.left) / r.width - 0.5, y = (e.clientY - r.top) / r.height - 0.5;
      layers.forEach(function (l) { var d = parseFloat(l.getAttribute("data-depth")) || 10; l.style.transform = "translate(" + (x * d).toFixed(1) + "px," + (y * d).toFixed(1) + "px)"; });
    });
    var tick = false;
    window.addEventListener("scroll", function () {
      if (tick) return; tick = true;
      requestAnimationFrame(function () { hero.style.setProperty("--sy", Math.min(window.pageYOffset, 600) * 0.12 + "px"); tick = false; });
    }, { passive: true });
  }

  /* ---- category filter ---- */
  function initFilter() {
    var bar = $("[data-filter-bar]"); var grid = $("[data-filter-grid]");
    if (!bar || !grid) return;
    var chips = $$(".sxi-chip", bar), tiles = $$(".sxi-tile", grid);
    var empty = $("[data-filter-empty]"); var count = $("[data-filter-count]");
    function setFeature() {
      if (!grid.hasAttribute("data-feature")) return;
      var first = true;
      tiles.forEach(function (t) { t.classList.remove("is-feature"); if (!t.hidden && first) { t.classList.add("is-feature"); first = false; } });
    }
    chips.forEach(function (chip) {
      chip.addEventListener("click", function () {
        var f = chip.getAttribute("data-filter");
        chips.forEach(function (c) { c.classList.toggle("is-active", c === chip); });
        var n = 0;
        tiles.forEach(function (t) { var ok = f === "all" || t.getAttribute("data-cat") === f; t._ok = ok; if (ok) n++; if (!ok) t.classList.add("is-out"); });
        setTimeout(function () {
          var k = 0;
          tiles.forEach(function (t) {
            t.hidden = !t._ok;
            if (t._ok) {
              t.classList.remove("is-out");
              t.classList.remove("sxi-in"); t.style.transitionDelay = (k++ % 10) * 45 + "ms";
              void t.offsetWidth; t.classList.add("sxi-in");
              (function (el) { setTimeout(function () { el.style.transitionDelay = ""; }, 900); })(t);
            }
          });
          setFeature();
        }, reduce ? 0 : 260);
        if (empty) empty.classList.toggle("is-on", n === 0);
        if (count) count.textContent = n;
      });
    });
  }

  /* ---- product gallery ---- */
  function initGallery() {
    var main = $("[data-gal-main]"); if (!main) return;
    var img = $("img", main), thumbs = $$("[data-gal-thumb]"), srcs = thumbs.map(function (t) { return t.getAttribute("data-src"); });
    if (!srcs.length && img) srcs = [img.getAttribute("src")];
    var cur = 0;
    function go(i) {
      if (!srcs.length) return;
      cur = (i + srcs.length) % srcs.length;
      thumbs.forEach(function (t, k) { t.classList.toggle("is-active", k === cur); });
      if (!img) return;
      main.classList.add("is-swap");
      setTimeout(function () { img.src = srcs[cur]; main.classList.remove("is-swap"); }, reduce ? 0 : 220);
    }
    thumbs.forEach(function (t, k) { t.addEventListener("click", function () { go(k); }); });
    if (fine && !reduce) main.addEventListener("mousemove", function (e) {
      var r = main.getBoundingClientRect();
      main.style.setProperty("--zx", ((e.clientX - r.left) / r.width) * 100 + "%");
      main.style.setProperty("--zy", ((e.clientY - r.top) / r.height) * 100 + "%");
    });
    // lightbox
    var lb = $("[data-lb]"); if (!lb || !img) return;
    var lbImg = $("img", lb);
    function open() { lbImg.src = srcs[cur] || img.src; lb.classList.add("is-on"); doc.body.style.overflow = "hidden"; }
    function close() { lb.classList.remove("is-on"); doc.body.style.overflow = ""; }
    function step(d) { go(cur + d); lbImg.src = srcs[cur]; }
    main.addEventListener("click", open);
    $("[data-lb-x]", lb).addEventListener("click", close);
    $("[data-lb-p]", lb).addEventListener("click", function () { step(-1); });
    $("[data-lb-n]", lb).addEventListener("click", function () { step(1); });
    lb.addEventListener("click", function (e) { if (e.target === lb) close(); });
    doc.addEventListener("keydown", function (e) {
      if (!lb.classList.contains("is-on")) return;
      if (e.key === "Escape") close(); if (e.key === "ArrowLeft") step(-1); if (e.key === "ArrowRight") step(1);
    });
    if (srcs.length < 2) { $("[data-lb-p]", lb).style.display = "none"; $("[data-lb-n]", lb).style.display = "none"; }
  }

  ready(function () {
    splitWords(); initReveal(); initTiles(); initHero(); initFilter(); initGallery();
  });
})();
