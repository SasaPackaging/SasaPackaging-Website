// /* SASA Packaging — About-us inner pages behaviour (vanilla JS).
//    Load BEFORE sasa-inner.js (it tags elements that sasa-inner.js then reveals).
//    Features: paragraph reveal, reading-progress bar, certificates (seal reveal + confetti,
//    celebrate button, certificate viewer), gallery viewer. */
// (function () {
//   "use strict";
//   var doc = document;
//   var reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
//   function $(s, c) { return (c || doc).querySelector(s); }
//   function $$(s, c) { return [].slice.call((c || doc).querySelectorAll(s)); }
//   function ready(fn) { if (doc.readyState !== "loading") fn(); else doc.addEventListener("DOMContentLoaded", fn); }

//   /* ---- every block of the CMS text fades in one after another ---- */
//   function proseReveal() {
//     $$("[data-prose]").forEach(function (box) {
//       [].slice.call(box.children).forEach(function (el, i) {
//         el.classList.add("sxi-reveal");
//         el.style.setProperty("--d", Math.min(i, 3) * 70 + "ms");
//       });
//     });
//   }

//   /* ---- reading progress (CEO letter) ---- */
//   function progress() {
//     var letter = $(".sxa-letter"); if (!letter) return;
//     var tick = false;
//     function upd() {
//       var r = letter.getBoundingClientRect(), vh = window.innerHeight;
//       var p = (vh * 0.55 - r.top) / Math.max(1, r.height);
//       letter.style.setProperty("--p", Math.max(0, Math.min(1, p)).toFixed(3));
//       tick = false;
//     }
//     window.addEventListener("scroll", function () { if (!tick) { tick = true; requestAnimationFrame(upd); } }, { passive: true });
//     upd();
//   }

//   /* ---- confetti engine (canvas, no library) ---- */
//   var canvas, ctx, parts = [], running = false, dpr = Math.min(window.devicePixelRatio || 1, 2);
//   var colors = ["#B8CFB3", "#84A282", "#F6E2E9", "#e6b3c4", "#c9a96a", "#ffffff", "#4d6b4f", "#FEFAF3"];
//   function setup() {
//     if (canvas) return;
//     canvas = doc.createElement("canvas");
//     canvas.setAttribute("aria-hidden", "true");
//     canvas.style.cssText = "position:fixed;left:0;top:0;width:100%;height:100%;pointer-events:none;z-index:9999";
//     doc.body.appendChild(canvas); ctx = canvas.getContext("2d"); size();
//     window.addEventListener("resize", size);
//   }
//   function size() { canvas.width = window.innerWidth * dpr; canvas.height = window.innerHeight * dpr; ctx.setTransform(dpr, 0, 0, dpr, 0, 0); }
//   function add(x, y, ang, spread, speed, n) {
//     for (var i = 0; i < n; i++) {
//       var a = ang + (Math.random() - 0.5) * spread, sp = speed * (0.45 + Math.random() * 0.75);
//       var shape = Math.random();
//       parts.push({
//         x: x, y: y, vx: Math.cos(a) * sp, vy: Math.sin(a) * sp,
//         w: 6 + Math.random() * 8, h: 4 + Math.random() * 7, r: Math.random() * 6.28, vr: (Math.random() - 0.5) * 0.45,
//         c: colors[(Math.random() * colors.length) | 0], life: 0, max: 110 + Math.random() * 80,
//         kind: shape < 0.55 ? "rect" : shape < 0.8 ? "dot" : shape < 0.93 ? "ribbon" : "star",
//         wob: Math.random() * 6.28
//       });
//     }
//     if (!running) { running = true; requestAnimationFrame(tick); }
//   }
//   function star(c, s) { c.beginPath(); for (var i = 0; i < 10; i++) { var rr = i % 2 ? s * 0.45 : s; var a = (i * Math.PI) / 5 - Math.PI / 2; c.lineTo(Math.cos(a) * rr, Math.sin(a) * rr); } c.closePath(); c.fill(); }
//   function tick() {
//     ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);
//     parts = parts.filter(function (p) { return p.life < p.max && p.y < window.innerHeight + 60; });
//     parts.forEach(function (p) {
//       p.life++; p.vx *= 0.985; p.vy = p.vy * 0.985 + 0.25; p.x += p.vx + Math.sin(p.wob + p.life * 0.1) * 0.4; p.y += p.vy; p.r += p.vr;
//       ctx.save(); ctx.globalAlpha = Math.min(1, (p.max - p.life) / 30);
//       ctx.translate(p.x, p.y); ctx.rotate(p.r); ctx.fillStyle = p.c;
//       if (p.kind === "dot") { ctx.beginPath(); ctx.arc(0, 0, p.w / 2.4, 0, 6.28); ctx.fill(); }
//       else if (p.kind === "star") { star(ctx, p.w * 0.8); }
//       else if (p.kind === "ribbon") { ctx.scale(1, Math.abs(Math.sin(p.life * 0.15)) + 0.2); ctx.fillRect(-p.w, -1.5, p.w * 2, 3); }
//       else { ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h); }
//       ctx.restore();
//     });
//     if (parts.length) requestAnimationFrame(tick); else { running = false; ctx.clearRect(0, 0, window.innerWidth, window.innerHeight); }
//   }
//   function burst(x, y, n) { if (reduce) return; setup(); add(x, y, -Math.PI / 2, Math.PI * 1.2, 13, n); }
//   function cannons(n) {
//     if (reduce) return; setup();
//     var w = window.innerWidth, h = window.innerHeight;
//     add(0, h, -Math.PI / 3.2, 0.7, 24, n); add(w, h, -Math.PI + Math.PI / 3.2, 0.7, 24, n);
//   }

//   /* ---- certificates ---- */
//   function certs() {
//     var stage = $("[data-ct]"); if (!stage) return;
//     var items = $$(".ct__item", stage);
//     function popFrom(el, n) { var r = $(".ct__shield", el).getBoundingClientRect(); burst(r.left + r.width / 2, r.top + r.height * 0.35, n); }
//     function reveal() {
//       items.forEach(function (it) { it.classList.add("is-in"); });
//       items.forEach(function (it, i) { setTimeout(function () { popFrom(it, 80); }, 1100 + i * 380); });
//       setTimeout(function () { cannons(60); }, 1300 + items.length * 380);
//     }
//     if ("IntersectionObserver" in window) {
//       var io = new IntersectionObserver(function (e) { if (e[0].isIntersecting) { reveal(); io.disconnect(); } }, { threshold: 0.4 });
//       io.observe(stage);
//     } else reveal();
//     items.forEach(function (it) {
//       var last = 0;
//       function again() { var now = Date.now(); if (now - last < 1400) return; last = now; popFrom(it, 60); }
//       it.addEventListener("click", again); it.addEventListener("mouseenter", again);
//       it.addEventListener("keydown", function (e) { if (e.key === "Enter" || e.key === " ") { e.preventDefault(); again(); } });
//     });
//     var btn = $("[data-celebrate]");
//     if (btn) btn.addEventListener("click", function () { cannons(90); items.forEach(function (it, i) { setTimeout(function () { popFrom(it, 40); }, i * 160); }); });
//   }

//   /* ---- simple image viewers ---- */
//   function viewer(lbSel, openSel, closeSel) {
//     var lb = $(lbSel); if (!lb) return;
//     var img = $("img", lb);
//     function close() { lb.classList.remove("is-on"); doc.body.style.overflow = ""; }
//     $$(openSel).forEach(function (b) {
//       function open() { img.src = b.getAttribute("data-src") || b.getAttribute("href"); lb.classList.add("is-on"); doc.body.style.overflow = "hidden"; }
//       b.addEventListener("click", function (e) { e.preventDefault(); open(); });
//       b.addEventListener("keydown", function (e) { if (e.key === "Enter" || e.key === " ") { e.preventDefault(); open(); } });
//     });
//     var x = $(closeSel, lb); if (x) x.addEventListener("click", close);
//     lb.addEventListener("click", function (e) { if (e.target === lb) close(); });
//     doc.addEventListener("keydown", function (e) { if (e.key === "Escape") close(); });
//   }

//   ready(function () {
//     proseReveal(); progress(); certs();
//     viewer("[data-cert-lb]", "[data-cert-view]", "[data-cert-x]");
//     if (!$("[data-gal-main]")) viewer("[data-lb]", "[data-gal-open]", "[data-lb-x]");
//   });
// })();
/* SASA Packaging — About-us inner pages behaviour (vanilla JS).
   Load BEFORE sasa-inner.js (it tags elements that sasa-inner.js then reveals).
   Features: paragraph reveal, reading-progress bar, certificate viewer, gallery viewer.
   (The confetti / seal-celebration code was removed on purpose. It belongs to the landing page only.) */
(function () {
  "use strict";
  var doc = document;
  function $(s, c) {
    return (c || doc).querySelector(s);
  }
  function $$(s, c) {
    return [].slice.call((c || doc).querySelectorAll(s));
  }
  function ready(fn) {
    if (doc.readyState !== "loading") fn();
    else doc.addEventListener("DOMContentLoaded", fn);
  }

  /* ---- every block of the CMS text fades in one after another ---- */
  function proseReveal() {
    $$("[data-prose]").forEach(function (box) {
      [].slice.call(box.children).forEach(function (el, i) {
        el.classList.add("sxi-reveal");
        el.style.setProperty("--d", Math.min(i, 3) * 70 + "ms");
      });
    });
  }

  /* ---- reading progress (CEO letter) ---- */
  function progress() {
    var letter = $(".sxa-letter");
    if (!letter) return;
    var tick = false;
    function upd() {
      var r = letter.getBoundingClientRect(),
        vh = window.innerHeight;
      var p = (vh * 0.55 - r.top) / Math.max(1, r.height);
      letter.style.setProperty("--p", Math.max(0, Math.min(1, p)).toFixed(3));
      tick = false;
    }
    window.addEventListener(
      "scroll",
      function () {
        if (!tick) {
          tick = true;
          requestAnimationFrame(upd);
        }
      },
      { passive: true },
    );
    upd();
  }

  /* ---- simple image viewers (certificate viewer + gallery viewer) ---- */
  function viewer(lbSel, openSel, closeSel) {
    var lb = $(lbSel);
    if (!lb) return;
    var img = $("img", lb);
    function close() {
      lb.classList.remove("is-on");
      doc.body.style.overflow = "";
    }
    $$(openSel).forEach(function (b) {
      function open() {
        img.src = b.getAttribute("data-src") || b.getAttribute("href");
        lb.classList.add("is-on");
        doc.body.style.overflow = "hidden";
      }
      b.addEventListener("click", function (e) {
        e.preventDefault();
        open();
      });
      b.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          open();
        }
      });
    });
    var x = $(closeSel, lb);
    if (x) x.addEventListener("click", close);
    lb.addEventListener("click", function (e) {
      if (e.target === lb) close();
    });
    doc.addEventListener("keydown", function (e) {
      if (e.key === "Escape") close();
    });
  }

  ready(function () {
    proseReveal();
    progress();
    viewer("[data-cert-lb]", "[data-cert-view]", "[data-cert-x]");
    if (!$("[data-gal-main]"))
      viewer("[data-lb]", "[data-gal-open]", "[data-lb-x]");
  });
})();
