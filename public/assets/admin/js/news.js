/* SASA Admin — News & events form: the event date / place only show when the type is "Event". */
(function () {
  "use strict";
  var form = document.querySelector("[data-news-form]");
  if (!form) { return; }
  var type = form.querySelector("[data-news-type]");
  var event = form.querySelector("[data-news-event]");
  if (!type) { return; }
  function sync() { if (event) { event.hidden = type.value !== "event"; } }
  type.addEventListener("change", sync);
  sync();
})();
