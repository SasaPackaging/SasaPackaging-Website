/* SASA Packaging — About section behaviour (standalone; load after sasa-premium.js).
   1) Gentle entrance when the section scrolls into view.
   2) The office video starts by itself when the visitor reaches the section
      (muted, looping, no controls) and pauses when it leaves the screen.
      Works with a direct .mp4/.webm link or a YouTube link (shown with no player UI). */
(function () {
  "use strict";
  var doc = document;
  var reduceMotion =
    window.matchMedia &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function initAbout(sec) {
    /* ---------- entrance ---------- */
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
    } else {
      sec.classList.add("is-in");
    }
    setTimeout(function () {
      sec.classList.add("is-in");
    }, 5000); // safety net: never leave content hidden

    /* ---------- video ---------- */
    var frame = sec.querySelector("[data-sx-frame]");
    if (!frame || reduceMotion) return; // reduced motion: poster only, no autoplay

    var file = frame.querySelector("[data-sx-video]");
    var ytBox = frame.querySelector("[data-sx-yt]");
    var visible = false,
      ytReady = false,
      ytFrame = null;

    function onPlaying() {
      frame.classList.add("is-playing");
    }

    /* --- direct file --- */
    function playFile() {
      var p = file.play();
      if (p && p.catch) p.catch(function () {});
    }
    if (file) {
      file.muted = true;
      file.defaultMuted = true;
      file.setAttribute("playsinline", "");
      file.addEventListener("playing", onPlaying);
    }

    /* --- YouTube (no branding, no controls, clicks blocked) --- */
    function ytCmd(fn) {
      if (ytFrame && ytFrame.contentWindow) {
        ytFrame.contentWindow.postMessage(
          JSON.stringify({ event: "command", func: fn, args: [] }),
          "*",
        );
      }
    }
    function buildYT() {
      if (ytFrame) return;
      var id = ytBox.getAttribute("data-sx-yt");
      var q = [
        "enablejsapi=1",
        "autoplay=1",
        "mute=1",
        "controls=0",
        "loop=1",
        "playlist=" + id,
        "modestbranding=1",
        "rel=0",
        "playsinline=1",
        "iv_load_policy=3",
        "disablekb=1",
        "fs=0",
        "cc_load_policy=0",
        "origin=" + encodeURIComponent(location.origin),
      ].join("&");
      ytFrame = doc.createElement("iframe");
      ytFrame.src = "https://www.youtube-nocookie.com/embed/" + id + "?" + q;
      ytFrame.title = ytBox.getAttribute("data-title") || "Video";
      ytFrame.setAttribute(
        "allow",
        "autoplay; encrypted-media; picture-in-picture",
      );
      ytFrame.setAttribute("referrerpolicy", "strict-origin-when-cross-origin");
      ytFrame.setAttribute("tabindex", "-1");
      ytFrame.setAttribute("aria-hidden", "true");
      ytFrame.addEventListener("load", function () {
        setTimeout(function () {
          ytReady = true;
          onPlaying();
          if (!visible) ytCmd("pauseVideo");
        }, 900);
      });
      ytBox.appendChild(ytFrame);
    }

    function setVisible(v) {
      visible = v;
      if (file) {
        if (v) playFile();
        else file.pause();
      }
      if (ytBox) {
        if (v) {
          if (!ytFrame) buildYT();
          else if (ytReady) ytCmd("playVideo");
        } else if (ytReady) ytCmd("pauseVideo");
      }
    }

    if ("IntersectionObserver" in window) {
      new IntersectionObserver(
        function (en) {
          setVisible(en[0].isIntersecting);
        },
        { threshold: 0.35 },
      ).observe(frame);
    } else {
      setVisible(true);
    }
    doc.addEventListener("visibilitychange", function () {
      if (doc.hidden) {
        if (file) file.pause();
        if (ytReady) ytCmd("pauseVideo");
      } else if (visible) {
        setVisible(true);
      }
    });
  }

  function boot() {
    doc.querySelectorAll("[data-sx-about]").forEach(initAbout);
  }
  if (doc.readyState !== "loading") boot();
  else doc.addEventListener("DOMContentLoaded", boot);
})();
