/**
 * Created by polbatllo on 23/08/16.
 */
(function ($) {
    window.refreshContentUi = refreshContetUI;
    window.showContentLoaderActivator = showContentLoaderActivator;

    function showContentLoaderActivator(show) {
        var loaderActivator = $(".content-loader-activator");
        var modalContent = $("#modalContent");
        if (show) {
            loaderActivator.removeClass("hidden");
            modalContent.addClass("hidden");
        }
        else {
            loaderActivator.addClass("hidden");
            modalContent.removeClass("hidden");
        }
    }

    function refreshContetUI(id, msg, opened_boxes) {
        var block = $('.actualcontent');
        block.empty();
        block.load('update-partial?id=' + id, function () {
            window.reloadAllHandlers();
            showContentLoaderActivator(false);
            var last_opened = $(document);
            // OPEN THE BOXES REQUIRED.
            if (Object.prototype.toString.call( opened_boxes ) === '[object Array]' && opened_boxes.length > 1) {
                $.each(opened_boxes, function (index, value) {
                    if (typeof value != 'undefined') {
                        last_opened = $('.actualcontent').find("#" + value + " [data-widget='collapse']").first();
                        last_opened.click();
                    }
                });
            }
            else { // IF NO BOXES ARE SPECIFIED, OPEN THE FIRST ONE.
                $('.actualcontent').find("[data-widget='collapse']").first().click();
            }
            $("html, body").animate({scrollTop: last_opened.height()}, 1000);
            window.toastr.info(msg);
        });
    }
})(jQuery);