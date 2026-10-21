/* SASA Admin — News & events form: event date / place show for "Event", the positions list for "Hiring". */
(function () {
  "use strict";
  var form = document.querySelector("[data-news-form]");
  if (!form) { return; }
  var type = form.querySelector("[data-news-type]");
  var box = form.querySelector("[data-news-event]");
  var hiring = form.querySelector("[data-news-hiring]");
  if (!type || !box) { return; }
  function sync() {
    box.hidden = type.value !== "event";
    if (hiring) { hiring.hidden = type.value !== "hiring"; }
  }
  type.addEventListener("change", sync);
  sync();
})();
