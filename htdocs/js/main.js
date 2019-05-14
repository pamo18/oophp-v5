/**
 * To show off JS works and can be integrated.
 */
(function() {
    "use strict";

    console.info("main.js ready and loaded.");
    var check = document.getElementById("check");
    var publish = document.getElementById("publish");

    check.addEventListener("change", function() {
        if (this.checked) {
            publish.value = new Date().toLocaleString("sv-SWE");
        } else {
            publish.value = "";
        }
    });
})();
