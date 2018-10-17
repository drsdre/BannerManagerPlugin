(function ($) {
    "use strict";

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    var destObj = false;
    var oldSendTo;

    $(window).load(function () {

        $(".upload_image_button").click(function () {
            formfield = $(this).prev().attr("name");
            destObj = $(this).prev();
            tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
            return false;
        });

        oldSendTo = window.send_to_editor;
        window.send_to_editor = function (html) {
            if (destObj !== false) {
                var imgurl = $("img", html).attr("src");
                $(destObj).val(imgurl);
                $(destObj).parent().find("img").attr("src", imgurl);
                tb_remove();
                destObj = false;
            } else {
                oldSendTo(html);
            }
        };

        $(".clear_field_button").click(function () {
            $(this).prev().prev().val("");
        });
    });

})(jQuery);
