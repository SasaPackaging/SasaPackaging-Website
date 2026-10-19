/* SASA Admin — small behaviours shared by every admin page (vanilla JS, no jQuery).

   data-adm-menu-toggle        button that opens / closes the sidebar on phones
   data-adm-dismiss            button inside a flash message: closes it
   data-adm-confirm="text"     on a <form>: asks "Are you sure?" before sending
   data-adm-autosubmit         on a <select>/<input> inside a form: submits the form when it changes
*/
(function () {
  "use strict";

  var shell = document.querySelector("[data-adm-shell]");

  document.addEventListener("click", function (e) {
    var toggle = e.target.closest("[data-adm-menu-toggle]");
    if (toggle && shell) {
      shell.classList.toggle("is-menu-open");
      return;
    }
    if (shell && e.target.closest("[data-adm-overlay]")) {
      shell.classList.remove("is-menu-open");
      return;
    }
    var close = e.target.closest("[data-adm-dismiss]");
    if (close) {
      var box = close.closest(".adm-alert");
      if (box) {
        box.remove();
      }
    }
  });

  document.addEventListener("submit", function (e) {
    var text =
      e.target.getAttribute && e.target.getAttribute("data-adm-confirm");
    if (text && !window.confirm(text)) {
      e.preventDefault();
    }
  });

  document.addEventListener("change", function (e) {
    var el = e.target;
    if (el.hasAttribute && el.hasAttribute("data-adm-autosubmit") && el.form) {
      el.form.submit();
    }
  });
})();
