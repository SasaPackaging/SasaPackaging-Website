/* SASA Packaging - Careers: opens the apply drawer, 4 steps, conditional fields, AJAX submit. */
(function () {
  var $ = function (s, c) {
    return (c || document).querySelector(s);
  };
  var $$ = function (s, c) {
    return Array.prototype.slice.call((c || document).querySelectorAll(s));
  };
  var drawer = $("[data-drawer]"),
    ov = $("[data-ov]"),
    form = $("[data-form]");
  if (!drawer || !form) return;

  var steps = $$("[data-step]", form),
    rail = $$("[data-rail] li"),
    cur = 0,
    lastBtn = null;
  var errBox = $("[data-err]"),
    back = $("[data-back]"),
    next = $("[data-next]"),
    sub = $("[data-submit]");
  var exp = $("[data-exp]"),
    unpaid = $("[data-unpaid]"),
    salary = $("#c_ps");

  function esc(s) {
    var d = document.createElement("div");
    d.textContent = s;
    return d.innerHTML;
  }

  /* ---- role specific questions (from config/careers.php) ---- */
  function renderExtra(profile) {
    var qs = window.SXC.profiles[profile] || [],
      h = "";
    qs.forEach(function (q) {
      var n = "extra[" + q.key + "]",
        r = q.required ? " data-req" : "",
        lab = esc(q.label) + (q.required ? "" : " <em>(optional)</em>");
      h += '<div class="sxf-field sxf-field--full"><label>' + lab + "</label>";
      if (q.type === "textarea")
        h += '<textarea name="' + n + '" rows="4"></textarea>';
      else if (q.type === "select")
        h +=
          '<select name="' +
          n +
          '"' +
          r +
          ' data-label="' +
          esc(q.label) +
          '"><option value="">Select</option>' +
          q.options
            .map(function (o) {
              return "<option>" + esc(o) + "</option>";
            })
            .join("") +
          "</select>";
      else if (q.type === "checkboxes")
        h +=
          '<div class="sxc-chips"' +
          (q.required ? ' data-group data-label="' + esc(q.label) + '"' : "") +
          ">" +
          q.options
            .map(function (o) {
              return (
                '<label><input type="checkbox" name="' +
                n +
                '[]" value="' +
                esc(o) +
                '"><span>' +
                esc(o) +
                "</span></label>"
              );
            })
            .join("") +
          "</div>";
      else if (q.type === "yesno")
        h +=
          '<div class="sxc-chips"' +
          (q.required ? ' data-group data-label="' + esc(q.label) + '"' : "") +
          '><label><input type="radio" name="' +
          n +
          '" value="yes"><span>Yes</span></label><label><input type="radio" name="' +
          n +
          '" value="no"><span>No</span></label></div>';
      else
        h +=
          '<input type="' +
          (q.type === "url" ? "url" : "text") +
          '" name="' +
          n +
          '" placeholder="' +
          esc(q.placeholder || "") +
          '"' +
          r +
          ' data-label="' +
          esc(q.label) +
          '">';
      h += "</div>";
    });
    $("[data-extra]", form).innerHTML = h;
  }

  /* ---- experience: yes opens the extra fields, no keeps them closed + disabled ---- */
  function setUnpaid() {
    var on = unpaid.checked && !unpaid.disabled;
    salary.disabled = !exp.classList.contains("is-open") || on;
    salary.placeholder = on ? "N/A" : "";
    if (on) salary.value = "";
  }
  $$('[name="has_experience"]', form).forEach(function (r) {
    r.addEventListener("change", function () {
      var yes = form.elements.has_experience.value === "yes";
      exp.classList.toggle("is-open", yes);
      $$("input,select", exp).forEach(function (f) {
        f.disabled = !yes;
      });
      setUnpaid();
    });
  });
  unpaid.addEventListener("change", setUnpaid);
  $("#c_lv").addEventListener("change", function () {
    $("[data-yrlabel]").textContent = "Year completed";
  });

  /* ---- steps ---- */
  function show(i) {
    cur = i;
    steps.forEach(function (s, k) {
      s.hidden = k !== i;
    });
    rail.forEach(function (r, k) {
      r.classList.toggle("is-on", k <= i);
    });
    back.style.visibility = i === 0 ? "hidden" : "visible";
    next.hidden = i === steps.length - 1;
    sub.hidden = !next.hidden;
    errBox.textContent = "";
    var f = $(
      "input:not([type=hidden]):not(.sxc-hp),select,textarea",
      steps[i],
    );
    if (f && !f.disabled) f.focus({ preventScroll: true });
  }

  function validate(step) {
    var bad = null,
      msg = "";
    $$(".field_error", step).forEach(function (e) {
      e.classList.remove("field_error");
    });
    $$("[data-req],[data-group],[data-req-group]", step).forEach(function (f) {
      if (bad || f.disabled) return;
      var ok = true,
        v = (f.value || "").trim();
      if (f.hasAttribute("data-group")) ok = !!f.querySelector(":checked");
      else if (f.hasAttribute("data-req-group"))
        ok = !!form.elements[f.name].value;
      else if (f.type === "file") ok = f.files.length > 0;
      else ok = v !== "";
      if (ok && f.getAttribute("data-type") === "email")
        ok = /^[^@\s]+@[^@\s]+\.[^@\s]{2,}$/.test(v);
      if (ok && f.hasAttribute("data-num") && v !== "")
        ok = /^\d+$/.test(v.replace(/,/g, ""));
      if (!ok) {
        bad = f;
        msg =
          "Please enter " +
          (f.getAttribute("data-label") || "this field") +
          ".";
      }
    });
    var xs = $("#c_xs", step);
    if (
      !bad &&
      xs &&
      xs.value.trim() !== "" &&
      !/^\d+$/.test(xs.value.trim())
    ) {
      bad = xs;
      msg = "Expected salary should be a number.";
    }
    if (bad) {
      (bad.hasAttribute("data-group") || bad.hasAttribute("data-req-group")
        ? bad.closest(".sxc-choice, .sxc-chips") || bad
        : bad
      ).classList.add("field_error");
      errBox.textContent = msg;
      if (bad.focus && !bad.hasAttribute("data-group")) bad.focus();
      return false;
    }
    errBox.textContent = "";
    return true;
  }

  next.addEventListener("click", function () {
    if (validate(steps[cur])) show(cur + 1);
  });
  back.addEventListener("click", function () {
    if (cur > 0) show(cur - 1);
  });

  /* ---- open / close ---- */
  function open(btn) {
    lastBtn = btn;
    form.reset();
    form.hidden = false;
    $("[data-done]").hidden = true;
    exp.classList.remove("is-open");
    $$("input,select", exp).forEach(function (f) {
      f.disabled = true;
    });
    $("[data-title]").textContent = btn.getAttribute("data-title");
    form.elements.job_opening_id.value = btn.getAttribute("data-id") || "";
    renderExtra(btn.getAttribute("data-profile") || "general");
    $("[data-rail]").hidden = false;
    ov.hidden = false;
    drawer.hidden = false;
    document.body.classList.add("sxc-lock");
    requestAnimationFrame(function () {
      ov.classList.add("is-in");
      drawer.classList.add("is-in");
    });
    show(0);
  }
  function close() {
    ov.classList.remove("is-in");
    drawer.classList.remove("is-in");
    document.body.classList.remove("sxc-lock");
    setTimeout(function () {
      ov.hidden = true;
      drawer.hidden = true;
    }, 420);
    if (lastBtn) lastBtn.focus();
  }
  $$("[data-apply]").forEach(function (b) {
    b.addEventListener("click", function () {
      open(b);
    });
  });
  $$("[data-close]").forEach(function (b) {
    b.addEventListener("click", close);
  });
  ov.addEventListener("click", close);
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !drawer.hidden) close();
  });

  /* ---- submit ---- */
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    if (!validate(steps[cur])) return;
    sub.disabled = true;
    sub.textContent = "Sending...";
    fetch(form.action, {
      method: "POST",
      body: new FormData(form),
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
      },
    })
      .then(function (r) {
        return r.json().catch(function () {
          return {
            status: "error",
            message: "Something went wrong. Please try again.",
          };
        });
      })
      .then(function (d) {
        if (d.status === "success") {
          form.hidden = true;
          $("[data-rail]").hidden = true;
          $("[data-donemsg]").textContent = d.message;
          $("[data-done]").hidden = false;
        } else {
          errBox.textContent =
            d.message || "Please check the form and try again.";
        }
      })
      .catch(function () {
        errBox.textContent = "Network error. Please try again.";
      })
      .then(function () {
        sub.disabled = false;
        sub.textContent = "Submit application";
      });
  });
})();
