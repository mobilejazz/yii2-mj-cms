jQuery.expr[':'].regex = function (elem, index, match) {
    var matchParams = match[3].split(','),
        validLabels = /^(data|css):/,
        attr = {
            method: matchParams[0].match(validLabels) ?
                matchParams[0].split(':')[0] : 'attr',
            property: matchParams.shift().replace(validLabels, '')
        },
        regexFlags = 'ig',
        regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g, ''), regexFlags);
    return regex.test(jQuery(elem)[attr.method](attr.property));
};

$(function () {

    $.AdminLTE.boxWidget = {
        selectors: $.AdminLTE.options.boxWidgetOptions.boxWidgetSelectors,
        icons: $.AdminLTE.options.boxWidgetOptions.boxWidgetIcons,
        animationSpeed: $.AdminLTE.options.animationSpeed,
        activate: function (_box) {
            var _this = this;
            if (!_box) {
                _box = document; // activate all boxes per default
            }
            //Listen for collapse event triggers
            $(_box).on('click', _this.selectors.collapse, function (e) {
                e.preventDefault();
                _this.collapse($(this));
            });

            $(_box).on('click', '.box-header', function (e) {
                e.preventDefault();
                _this.collapse($(this));
            });

            //Listen for remove event triggers
            $(_box).on('click', _this.selectors.remove, function (e) {
                e.preventDefault();
                _this.remove($(this));
            });
        },
        collapse: function (element) {

            var _this = this;
            //Find the box parent
            var box = element.parents(".box").first();
            //Find the body and the footer
            var box_content = box.find("> .box-body, > .box-footer, > form  >.box-body, > form > .box-footer");
            if (!box.hasClass("collapsed-box")) {
                //Convert minus into plus
                element.find("i")
                    .removeClass(_this.icons.collapse)
                    .addClass(_this.icons.open);
                //Hide the content
                box_content.slideUp(_this.animationSpeed, function () {
                    box.addClass("collapsed-box");
                });
            } else {
                //Convert plus into minus
                element.find("i")
                    .removeClass(_this.icons.open)
                    .addClass(_this.icons.collapse);
                //Show the content
                box_content.slideDown(_this.animationSpeed, function () {
                    box.removeClass("collapsed-box");
                });
            }
        },
        remove: function (element) {
            //Find the box parent
            var box = element.parents(".box").first();
            box.slideUp(this.animationSpeed);
        }
    };

});

$(function () {
    var body = $('body');

    // Set toastr options.
    toastr.options.positionClass = "toast-bottom-right";

    // Declare global scoped functions.
    window.closeModal = closeModal;
    window.showLoader = showLoader;
    window.sort = sort;
    window.affix = affix;
    window.activateBoxes = activateBoxes;
    var modal = $('#modal');
    window.modal = modal;
    window.startCKE = startCKE;
    window.destroyCKE = destroyCKE;
    window.disableToolTips = disableToolTips;
    window.reloadAllHandlers = reloadAllHandlers;

    // Register Sorting.
    sort();
    // Register Affix Elements.
    affix();
    // Run some validation.
    validateForms();
    // Start CKE.
    // startCKE();
    // Reload All Handlers.
    reloadAllHandlers();
    // Set up the information saver.
    informationSaver();

    /**
     * Try to update the values of the contentfiels dinamically so we never loose information when editing stuff.
     * We still have to find a way to update data from special widgets.
     */
    function informationSaver() {
        var content_inputs = $(".actualcontent").find("[id^='componentfield-']");
        content_inputs.unbind('change');
        content_inputs.on("change", function (e) {
            var el = $(this);
            var id = el.attr("id");
            var field_id = id.match(/\d/g);
            field_id = field_id.join("");
            var val = el.val();
            var url = 'field-update';
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    'field': field_id,
                    'value': val
                }
            });
        });
    }

    /**
     * Reloads All handlers when required.
     */
    function reloadAllHandlers() {
        destroyCKE();
        closeModal();
        activateBoxes();
        startCKE();
        sort();
        disableToolTips();
        if (typeof activateFileInput != 'undefined') {
            activateFileInput();
        }
        informationSaver();
    }

    // Hide all tooltips.
    function disableToolTips() {
        var all = $('*');
        all.tooltip({
            track: true
        });
        all.tooltip('disable');
    }

    /**
     * Show and hide loader
     * @param show
     */
    function showLoader(show) {
        var loaderActivator = $(".loader-activator");
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

    /**
     * Close the Modal if open.
     */
    function closeModal() {
        showLoader(false);
        modal.find('#modalContent').empty();
        modal.modal('hide');
    }


    // MODALS
    $(document).on('click', '.showModalButton', function () {
        showLoader(true);
        modal.modal('show')
            .find('#modalContent')
            .load($(this).attr('data-value'), function () {
                showLoader(false);
            });
        //dynamically set the header for the modal
        document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('label') + '</h4>';
    });

    // FILTER SELECTOR.
    $("select[name=per-page]").change(function () {
        var val = $("select[name=per-page]").val();
        window.location.href = "?per-page=" + val;
    });

    // AFFIX Elements.
    function affix() {
        // AFFIX BOXES IF REQUIRED
        var element = $(".affix-element");

        if (element.length === 0) return;

        var left = element.offset().left;
        var width = element.width();
        var top = element.offset().top;
        var offsetTop = 9;

        element.affix({
            offset: {
                top: top - offsetTop
            }
        });

        element.on('affix.bs.affix', function () {
            element.width(width);
            element.css("left", left);
            element.css("top", offsetTop);
        });

        element.on('affix-top.bs.affix', function () {
            element.css('width', '');
            element.css('left', '');
            element.css('top', '');
        });
    }


    /**
     *  Activate the boxes displayed in the content.
     */
    function activateBoxes() {
        $('body').find('.box').activateBox();
    }

    /**
     * Go through the ActiveForm Fields and add error classes if needed.
     */
    function validateForms() {
        $(".help-block-error").filter(function () {
            return $(this).text().replace(/\s/g, "").length != 0;
        }).closest('.form-group').addClass('has-error');
    }

    // SORTING ABILITIES.
    function sort() {
        var sortable = $(".sortable-list");
        // Disable previous sorting features.
        if (sortable.data('sortable')) {
            sortable.sortable("disable");
        }

        // Enable them back.
        sortable.sortable({
            containment: sortable,
            cursor: 'move',
            revert: true,
            cancel: 'input, textarea, button, select, option, .fa, i, .non-sortable',
            start: function (event, ui) {
                // SET THE PLACEHOLDER HEIGHT.
                destroyCKE();
                ui.placeholder.css('height', '41px');
                // SET THE DRAGGED ITEM.
                var dragged = ui.item;
                dragged.addClass('collapsed-box');
                dragged.css('height', '41px');
                dragged.find('.non-sortable').css('display', 'none');
                var icon = dragged.find('[data-widget="collapse"]').find('.fa');
                icon.removeClass('fa-angle-up');
                icon.addClass('fa-angle-down');
            },
            stop: function (event, ui) {
                startCKE();
            },
            update: function () {
                var IDs = [];
                sortable.children().each(function () {
                    IDs.push($(this).attr('id'));
                });
                var list = JSON.stringify(IDs);
                var url = 'order-update?order=' + list;
                $.ajax({
                    url: url,
                    type: 'POST',
                    success: function (data) {
                        sort();
                        toastr.info(data.msg);
                    }
                });
            }

        });
    }

    function startCKE() {
        $("textarea").each(function () {
            var id = $(this).attr('id');
            if (id == null) return;
            // Skip the webformdetail-script text area.
            if ($(this).hasClass('ace-editor')) {
                return null;
            }
            if (id == 'webformsubmission-script') {
                return null;
            }
            else {
                var editor = CKEDITOR.instances[id];
                if (!editor) {
                    CKEDITOR.replace(id, {
                        skin: 'bootstrapck,/admin/js/skin/bootstrapck/',
                        allowedContent: true,
                        toolbarGroups: [
                            {"name": "basicstyles", "groups": ["basicstyles"]},
                            {"name": "links", "groups": ["links"]},
                            {"name": "paragraph", "groups": ["list", "blocks"]},
                            {"name": "document", "groups": ["mode"]},
                            {"name": "insert", "groups": ["insert"]},
                            {"name": "styles", "groups": ["styles"]},
                            {"name": "about", "groups": ["about"]}
                        ],
                        removeButtons: 'Underline,Strike,Anchor,Styles,Specialchar,Save,NewPage,Preview,Print,Flash,Smiley,PageBreak,Font,About,Blockquote,CreateDiv'
                    });
                }
            }
        });
        window.disableToolTips();
    }

    function destroyCKE() {
        for (var name in CKEDITOR.instances) {
            var editor = CKEDITOR.instances[name];
            if (editor.status === 'unloaded')
                continue;
            editor.destroy(true);
        }
    }
});
