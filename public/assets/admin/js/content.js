/* SASA Admin — Landing content pages (vanilla JS)

   [data-list]            repeatable rows: [data-list-add] adds, [data-list-remove] removes,
                          the new row comes from <template data-list-template> (__ROW__ = row number)
   [data-rich]            simple rich-text editor: buttons use data-cmd (and data-arg), [data-rich-code] shows the HTML
*/
(function () {
  "use strict";

  /* ---------- repeatable lists ---------- */
  document.querySelectorAll("[data-list]").forEach(function (box) {
    var rows = box.querySelector("[data-list-rows]");
    var tpl = box.querySelector("[data-list-template]");
    var next = rows.querySelectorAll("[data-list-row]").length;

    box.addEventListener("click", function (e) {
      if (e.target.closest("[data-list-add]")) {
        var html = tpl.innerHTML.replace(/__ROW__/g, "n" + next++);
        rows.insertAdjacentHTML("beforeend", html);
        var first = rows.lastElementChild.querySelector("input, textarea");
        if (first) { first.focus(); }
      }
      var rm = e.target.closest("[data-list-remove]");
      if (rm) { rm.closest("[data-list-row]").remove(); }
    });
  });

  /* ---------- rich text ---------- */
  document.querySelectorAll("[data-rich]").forEach(function (box) {
    var area = box.querySelector("[data-rich-area]");
    var src = box.querySelector("[data-rich-src]");
    var codeMode = false;

    function toSource() { src.value = area.innerHTML; }
    function toArea() { area.innerHTML = src.value; }

    area.addEventListener("input", toSource);
    toSource();

    box.querySelector("[data-rich-code]").addEventListener("click", function () {
      codeMode = !codeMode;
      if (codeMode) { toSource(); }
      else { toArea(); }
      src.hidden = !codeMode;
      area.style.display = codeMode ? "none" : "";
    });

    box.querySelector(".adm-rich__bar").addEventListener("mousedown", function (e) {
      var btn = e.target.closest("[data-cmd]");
      if (!btn) { return; }
      e.preventDefault(); // keep the text selection
      if (codeMode) { return; }
      area.focus();
      var cmd = btn.getAttribute("data-cmd");
      var arg = btn.getAttribute("data-arg") || null;
      if (cmd === "createLink") {
        arg = window.prompt("Link address (for example https://... or /about-us.html)");
        if (!arg) { return; }
      }
      document.execCommand(cmd, false, arg);
      toSource();
    });

    // whatever mode is open, the saved value is always the latest
    var form = box.closest("form");
    if (form) {
      form.addEventListener("submit", function () { if (!codeMode) { toSource(); } });
    }
  });
})();
