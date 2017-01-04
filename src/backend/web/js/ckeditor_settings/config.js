/**
 * Created by polbatllo on 09/05/16.
 */
CKEDITOR.editorConfig = function (config) {
    config.toolbarGroups = [
        {"name":"basicstyles","groups":["basicstyles"]},
        {"name":"links","groups":["links"]},
        {"name":"paragraph","groups":["list","blocks"]},
        {"name":"document","groups":["mode"]},
        {"name":"insert","groups":["insert"]},
        {"name":"styles","groups":["styles"]},
        {"name":"about","groups":["about"]}
    ];
    config.removeButtons = 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Save,NewPage,Preview,Print,Flash,Smiley,PageBreak,Font,About,Blockquote,CreateDiv';
};