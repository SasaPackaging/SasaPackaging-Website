/* SASA Packaging — Contact + Quote forms (vanilla JS, jQuery is NOT needed).

   How a form opts in (see contact.blade.php / quote.blade.php):
     <form data-sxf-form data-endpoint="URL" novalidate> ...
     Required fields:  data-req   data-label="your name"   (email field also: data-type="email")
     File input:       inside <label class="sxf-drop">, with data-max-mb="15"
     Submit button:    data-sxf-submit  with <span data-sxf-label data-busy="Sending…">Send</span> inside
     Message area:     <p data-sxf-note></p>   (used only if the site's Toast popup is not available)
     Captcha:          <div class="cf-turnstile" data-sxf-captcha ...> (partial/captcha.blade.php). The form is not sent
                       until Cloudflare's token is present, and the widget is reset after every attempt
                       (a token works only once).

   The server answers JSON: { "status": "success" | "error", "message": "..." } — same as before. */
(function () {
  "use strict";
  var EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

  function box(el) {
    return el.type === "file" ? el.closest(".sxf-drop") || el : el;
  }

  function note(form) {
    return form.querySelector("[data-sxf-note]");
  }

  function notify(form, type, msg) {
    var T = window.Toast;
    if (T && typeof T[type] === "function") {
      try {
        T[type](msg);
        return;
      } catch (e) {
        /* fall through to the inline message */
      }
    }
    var n = note(form);
    if (n) {
      n.textContent = msg;
      n.className = "sxf-note is-" + type;
    }
  }

  function clearNote(form) {
    var n = note(form);
    if (n) {
      n.textContent = "";
      n.className = "sxf-note";
    }
  }

  function validate(form) {
    var els = [].slice.call(form.querySelectorAll("[data-req]"));
    var first = null,
      msg = "";
    els.forEach(function (el) {
      var isFile = el.type === "file";
      var val = isFile
        ? el.files && el.files.length
          ? "x"
          : ""
        : String(el.value || "").trim();
      var bad = val === "",
        badEmail = false,
        tooBig = false;

      if (
        !bad &&
        el.getAttribute("data-type") === "email" &&
        !EMAIL.test(val)
      ) {
        bad = true;
        badEmail = true;
      }
      if (!bad && isFile) {
        var max = parseFloat(el.getAttribute("data-max-mb")) || 0;
        if (max && el.files[0].size > max * 1024 * 1024) {
          bad = true;
          tooBig = true;
        }
      }
      box(el).classList.toggle("field_error", bad);

      if (bad && !first) {
        first = el;
        var label = el.getAttribute("data-label") || "this field";
        if (badEmail) {
          msg = "Please enter a valid email address";
        } else if (tooBig) {
          msg =
            "That file is too large (maximum " +
            el.getAttribute("data-max-mb") +
            " MB)";
        } else if (isFile) {
          msg = "Please upload " + label;
        } else if (el.tagName === "SELECT") {
          msg = "Please select " + label;
        } else {
          msg = "Please enter " + label;
        }
      }
    });

    if (first) {
      notify(form, "error", msg);
      try {
        box(first).scrollIntoView({ behavior: "smooth", block: "center" });
        if (first.type !== "file") {
          first.focus({ preventScroll: true });
        }
      } catch (e) {
        /* older browsers: ignore */
      }
      return false;
    }
    return checkCaptcha(form);
  }

  /* ---- Captcha (Cloudflare Turnstile). Does nothing on a form that has no widget. ---- */
  function captchaBox(form) {
    return form.querySelector("[data-sxf-captcha]");
  }

  function checkCaptcha(form) {
    var widget = captchaBox(form);
    if (!widget) {
      return true;
    }
    var wrap = widget.closest(".sxf-captcha") || widget;
    var field = form.querySelector('input[name="cf-turnstile-response"]');
    var ok = !!(field && field.value);

    wrap.classList.toggle("field_error", !ok);
    if (!ok) {
      notify(
        form,
        "error",
        window.turnstile
          ? "Please wait for the security check to finish, then send again."
          : "The security check could not load. Please refresh the page, or turn off any content blocker for this site, and try again.",
      );
      try {
        wrap.scrollIntoView({ behavior: "smooth", block: "center" });
      } catch (e) {
        /* older browsers: ignore */
      }
    }
    return ok;
  }

  /* A token can be used once. After every attempt (success or not) ask Cloudflare for a fresh one. */
  function resetCaptcha(form) {
    var widget = captchaBox(form);
    if (!widget) {
      return;
    }
    var wrap = widget.closest(".sxf-captcha") || widget;
    wrap.classList.remove("field_error");
    if (window.turnstile && typeof window.turnstile.reset === "function") {
      try {
        window.turnstile.reset(widget);
      } catch (e) {
        /* the widget was not ready: nothing to reset */
      }
    }
  }

  function setBusy(form, on) {
    var btn = form.querySelector("[data-sxf-submit]");
    if (!btn) {
      return;
    }
    var lab = btn.querySelector("[data-sxf-label]");
    btn.disabled = !!on;
    btn.classList.toggle("is-loading", !!on);
    if (!lab) {
      return;
    }
    if (on) {
      lab.setAttribute("data-idle", lab.textContent);
      lab.textContent = lab.getAttribute("data-busy") || "Sending…";
    } else if (lab.hasAttribute("data-idle")) {
      lab.textContent = lab.getAttribute("data-idle");
    }
  }

  function resetFiles(form) {
    [].slice.call(form.querySelectorAll(".sxf-drop")).forEach(function (d) {
      var n = d.querySelector("[data-sxf-name]");
      if (n) {
        n.textContent = n.getAttribute("data-default") || "";
      }
      d.classList.remove("field_error");
    });
  }

  function send(form) {
    var url = form.getAttribute("data-endpoint") || form.getAttribute("action");
    var multipart =
      (form.getAttribute("enctype") || "").indexOf("multipart") === 0;
    var fd = new FormData(form);
    var body = multipart ? fd : new URLSearchParams(fd);

    setBusy(form, true);
    fetch(url, {
      method: "POST",
      body: body,
      credentials: "same-origin",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
      },
    })
      .then(function (res) {
        return res.text().then(function (t) {
          var data = null;
          try {
            data = JSON.parse(t);
          } catch (e) {
            /* not JSON */
          }
          return { status: res.status, data: data };
        });
      })
      .then(function (r) {
        setBusy(form, false);
        resetCaptcha(form);
        if (r.data && r.data.status === "success") {
          notify(
            form,
            "success",
            r.data.message || "Thank you! We have received your message.",
          );
          form.reset();
          resetFiles(form);
        } else if (r.data && r.data.message) {
          notify(form, "error", r.data.message);
        } else if (r.status === 419) {
          notify(
            form,
            "error",
            "Your session expired. Please refresh the page and try again.",
          );
        } else {
          notify(form, "error", "Something went wrong. Please try again.");
        }
      })
      .catch(function () {
        setBusy(form, false);
        resetCaptcha(form);
        notify(
          form,
          "error",
          "We could not send this. Please check your connection and try again.",
        );
      });
  }

  function init(form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      clearNote(form);
      if (validate(form)) {
        send(form);
      }
    });

    /* take the red border off as soon as the person fixes the field */
    form.addEventListener("input", function (e) {
      box(e.target).classList.remove("field_error");
    });
    form.addEventListener("change", function (e) {
      var t = e.target;
      box(t).classList.remove("field_error");
      if (t.type === "file") {
        var d = t.closest(".sxf-drop"),
          n = d && d.querySelector("[data-sxf-name]");
        if (n) {
          n.textContent =
            t.files && t.files.length
              ? t.files[0].name
              : n.getAttribute("data-default") || "";
        }
      }
    });
  }

  function boot() {
    [].slice.call(document.querySelectorAll("[data-sxf-form]")).forEach(init);
  }
  if (document.readyState !== "loading") {
    boot();
  } else {
    document.addEventListener("DOMContentLoaded", boot);
  }
})();
