import Velocity from "velocity-animate";
import cash from "cash-dom";

(function (cash) {
  "use strict";

  // Show accordion content
  cash("body").on("click", ".accordion-btn", function () {

    // Set active accordion
    if (!cash(this).hasClass("collapsed")) {
      Velocity(
        cash(this).closest(".accordion-item").find(".accordion-collapse"),
        "slideUp",
        {
          duration: 300,
          complete: function (el) {
            cash(el).removeClass("show");
            cash(el)
              .closest(".accordion-item")
              .find(".accordion-btn")
              .addClass("collapsed")
              .attr("aria-expanded", false);
          },
        }
      );
    } else {
      Velocity(
        cash(this).closest(".accordion-item").find(".accordion-collapse"),
        "slideDown",
        {
          duration: 300,
          complete: function (el) {
            cash(el).addClass("show");
            cash(el)
              .closest(".accordion-item")
              .find(".accordion-btn")
              .removeClass("collapsed")
              .attr("aria-expanded", true);
          },
        }
      );
    }
  });
})(cash);