$(function () {
    window.getFormData = getFormData;
    window.activateFileInput = activateFileInput;


    function activateFileInput() {
        var fileManagerLauncher = $('[role="filemanager-launch"]');
        fileManagerLauncher.unbind("click");
        fileManagerLauncher.on("click", function (e) {
            e.preventDefault();

            var modal = $('[data-btn-id="' + $(this).attr("id") + '"]'),
                iframe = $('<iframe src="' + modal.attr("data-frame-src")
                    + '" id="' + modal.attr("data-frame-id") + '" frameborder="0" role="filemanager-frame"></iframe>');

            iframe.on("load", frameHandler);
            modal.find(".modal-body").html(iframe);
            modal.modal("show");
        });

        var fileManagerInputClearer = $('[role="clear-input"]');
        fileManagerInputClearer.unbind("click");
        fileManagerInputClearer.on("click", function (e) {
            e.preventDefault();

            $("#" + $(this).attr("data-clear-element-id")).val("");
            $($(this).attr("data-image-container")).empty();
        });
    }

    function getFormData(form) {
        var formArray = form.serializeArray(),
            modelMap = {
                'Mediafile[alt]': 'alt',
                'Mediafile[description]': 'description',
                url: 'url',
                id: 'id'
            },
            data = [];

        for (var i = 0; formArray.length > i; i++) {
            if (modelMap[formArray[i].name]) {
                data[modelMap[formArray[i].name]] = formArray[i].value;
            }
        }

        return data;
    }

    function frameHandler(e) {
        var modal = $(this).parents('[role="filemanager-modal"]'),
            imageContainer = $(modal.attr("data-image-container")),
            pasteData = modal.attr("data-paste-data"),
            input = $("#" + modal.attr("data-input-id"));

        $(this).contents().find(".dashboard").on("click", "#insert-btn", function (e) {
            e.preventDefault();

            var data = getFormData($(this).parents("#control-form"));

            input.trigger("fileInsert", [data]);

            if (imageContainer) {
                imageContainer.html('<img src="' + data.url + '" alt="' + data.alt + '">');
            }

            input.val(data[pasteData]);
            modal.modal("hide");
        });
    }


});