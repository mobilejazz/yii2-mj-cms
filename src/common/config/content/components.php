<?php

use mobilejazz\yii2\cms\common\models\Components;
use mobilejazz\yii2\cms\common\models\Fields;

/**
 * Production components.
 */
return [
    /**
     * BUTTON
     * Fields:
     *      - Link Name: The name to display.
     *      - CMS Color Palette: A color palette that will be the background of the button.
     *      - Link Url: The Url of the Link.
     *      - Link Target: Where should the link open? New window or current window.
     */
    Components::COMP_BUTTON            => [
        'name'   => Yii::t('app', 'Button'),
        'fields' => [
            Fields::FIELD_LINK_NAME                          => [ ],
            Fields::FIELD_LINK_COLOR => [
                'name' => Yii::t('app', 'Button Color'),
            ],
            Fields::FIELD_LINK_URL                           => [ ],
            Fields::FIELD_LINK_TARGET                        => [ ],
            Fields::FIELD_BUTTON_ALIGNMENT                   => [ ],
        ],
    ],

    /**
     * LINK.
     * A simple link (text, no button).
     */
    Components::COMP_LINK              => [
        'name'   => Yii::t('app', 'Link'),
        'fields' => [
            Fields::FIELD_LINK_NAME   => [ ],
            Fields::FIELD_LINK_URL    => [ ],
            Fields::FIELD_LINK_TARGET => [ ],
        ],
    ],

    /**
     * INTRODUCTION TEXT.
     *
     * Notes: This is an introduction text that has a bold title, a non bold (in the same line)
     * title and a paragraph (or multiple) to the right of it.
     *
     *
     * Fields:
     *      - Title: Title that is bold
     *      - Subtitle: Subtitle that is the same size as the Title but not bold.
     *      - Text: Should not be rich text area but it will be because of client demands.
     */
    Components::COMP_INTRODUCTION_TEXT => [
        'name'   => Yii::t('app', 'Introduction Text'),
        'fields' => [
            Fields::FIELD_TITLE_BOLD                   => [
                'name' => \Yii::t('app', 'Bold Title Part'),
            ],
            Fields::FIELD_TITLE_NON_BOLD               => [
                'name' => \Yii::t('app', 'NON Bold title part'),
            ],
            Fields::FIELD_CMS_COLOR_PALETTE_TEXT => [
                'name' => \Yii::t('app', 'Title Text Color'),
            ],
            Fields::FIELD_TEXT_AREA                    => [
                'name' => \Yii::t('app', 'Introduction Paragraphs'),
            ]
        ],
    ],

    Components::COMP_INTRODUCTION_TEXT_NO_TITLE                       => [
        'name'   => \Yii::t('app', 'Introduction Text with no Title'),
        'fields' => [
            Fields::FIELD_TEXT_AREA => [
                'name' => \Yii::t('app', 'Introduction Paragraphs'),
            ]
        ],
    ],

    /**
     * INTRODUCTION TEXT TITLE ONLY.
     *
     * Notes:
     *      - This should be full width. It is the same as the previous Introduction text but without the text.
     *        In this case the the title spans the full width (not dividing into two columns like the previous
     *        case).
     * Fields:
     *      - Title.
     *      - Subtitle.
     */
    Components::COMP_INTRODUCTION_TEXT_TITLE_ONLY                     => [
        'name'   => \Yii::t('app', 'Introduction Text Title (only title)'),
        'fields' => [
            Fields::FIELD_TITLE_BOLD                   => [
                'name' => \Yii::t('app', 'Bold Title Part'),
            ],
            Fields::FIELD_TITLE_NON_BOLD               => [
                'name' => \Yii::t('app', 'NON Bold title part'),
            ],
            Fields::FIELD_CMS_COLOR_PALETTE_TEXT => [
                'name' => \Yii::t('app', 'Text Color'),
            ],
        ],
    ],

    /**
     * INTRODUCTION TEXT TITLE ONLY.
     *
     * Notes:
     *      - This should be full width. It is the same as the previous Introduction text but without the text.
     *        In this case the the title spans the full width (not dividing into two columns like the previous
     *        case).
     * Fields:
     *      - Title.
     *      - Subtitle.
     */
    Components::COMP_INTRODUCTION_TEXT_TITLE_ONLY_SINGLE_LINE         => [
        'name'   => \Yii::t('app', 'Introduction Text Title (only title - single line)'),
        'fields' => [
            Fields::FIELD_TITLE_BOLD                   => [
                'name' => \Yii::t('app', 'Bold Title Part'),
            ],
            Fields::FIELD_TITLE_NON_BOLD               => [
                'name' => \Yii::t('app', 'NON Bold title part'),
            ],
            Fields::FIELD_CMS_COLOR_PALETTE_TEXT => [
                'name' => \Yii::t('app', 'Text Color'),
            ],
        ],
    ],

    /**
     * INTRODUCTION TEXT TWO LINES.
     *
     * Notes:
     *      - This should be the introduction text like the ones before but splitted into different lines.
     *      - The Bold title is in one line (full width).
     *      - The Non Bold title is in the next line (full width).
     *      - The paragraph or paragraphs in the next line (full width).
     *
     *
     * Fields:
     *      - Bold title.
     *      - Non bold title.
     *      - Paragraphs.
     *
     */
    Components::COMP_INTRODUCTION_TEXT_TWO_LINES                      => [
        'name'   => \Yii::t('app', 'Introduction Text Two Lines'),
        'fields' => [
            Fields::FIELD_TITLE_BOLD                   => [
                'name' => \Yii::t('app', 'Bold Title Part'),
            ],
            Fields::FIELD_TITLE_NON_BOLD               => [
                'name' => \Yii::t('app', 'NON Bold title part'),
            ],
            Fields::FIELD_CMS_COLOR_PALETTE_TEXT => [
                'name' => \Yii::t('app', 'Title Color'),
            ],
            Fields::FIELD_TEXT_AREA                    => [
                'name' => \Yii::t('app', 'Introduction Paragraphs'),
            ]
        ],
    ],

    /**
     * TEXT WITH HEADING.
     *
     * Notes:
     *      - This should be basically a hading (of which you can select the color) underlined
     *        and bold (if wanted) followed by a rich text area.
     *
     *
     * Fields:
     *      - Text box: Heading
     *      - CMS Color Palette: the color of the title.
     *      - Bold Selector: is the title bold?
     *      - Text Area: The actual text of the section.
     */
    Components::COMP_TEXT_WITH_HEADING                                => [
        'name'   => Yii::t('app', 'Text with Heading'),
        'fields' => [
            Fields::FIELD_TEXT_BOX                     => [
                'name'     => Yii::t('app', 'Heading text'),
                'required' => true,
            ],
            Fields::FIELD_CMS_COLOR_PALETTE_TEXT => [
                'name'     => \Yii::t('app', 'Title Color'),
                'required' => true,
            ],
            Fields::FIELD_BOLD_SELECTOR                => [
                'name' => \Yii::t('app', 'Title is bold?'),
            ],
            Fields::FIELD_TEXT_AREA                    => [
                'required' => true,
            ],
        ],
    ],

    /**
     * TEXT WITH HEADING 2 COLS.
     *
     * Notes:
     *      - This is basically the same as before but splitted into two different columns.
     *        Therefore in reality, what this is is two (or more) text with headings.
     *
     *
     * Fields:
     *      - none
     *
     * Inner Components:
     *      - Text With Heading.
     */
    Components::COMP_TEXT_WITH_HEADING_2_COLS                         => [
        'name'             => Yii::t('app', 'Text with Heading 2 cols'),
        'inner-components' => [
            Components::COMP_TEXT_WITH_HEADING => [
                'repeatable' => true,
            ],
        ],
    ],

    /**
     * TEXT WITH HEADING AND NO LINE WITH SELECTABLE TITLE COLOR
     *
     * Note:
     *      - The same as before but with no line.
     *
     *
     * Fields:
     *      - Text Box (heading)
     *      - Color Palette: Title Color
     *      - Bold Selector: Title bold?
     *      - Text area: The actual text.
     */
    Components::COMP_TEXT_WITH_HEADING_NO_LINE_SELECTABLE_TITLE_COLOR => [
        'name'   => Yii::t('app', 'Text with Heading - no line - selectable title color'),
        'fields' => [
            Fields::FIELD_TEXT_BOX                     => [
                'name'     => Yii::t('app', 'Heading text'),
                'required' => true,
            ],
            Fields::FIELD_CMS_COLOR_PALETTE_TEXT => [
                'name'     => \Yii::t('app', 'Title Color'),
                'required' => true,
            ],
            Fields::FIELD_BOLD_SELECTOR                => [
                'name' => \Yii::t('app', 'Title is bold?'),
            ],
            Fields::FIELD_TEXT_AREA                    => [
                'required' => true,
            ],
        ],
    ],

    /**
     * EXPANDABLE CONTENT
     *
     * Note:
     *      - A wrapper for several Expandable Inner Components.
     *
     *
     * Fields:
     *      - None
     *
     * Inner Components:
     *      - Expandable Content Inner
     */
    Components::COMP_EXPANDABLE_CONTENT                               => [
        'name'             => \Yii::t('app', 'Expandable Content'),
        'title'            => true,
        'inner-components' => [
            Components::COMP_EXPANDABLE_CONTENT_INNER => [ ],
        ],
    ],

    /**
     * EXPANDABLE INNER CONTENT.
     *
     * Note:
     *      - The actual content of the expandable wrapper. This is basically a closed
     *        accordion with some text underneath it.
     *
     *
     * Fields:
     *      - Title of the expandable content.
     *      - Text Area: The actual content of this expandable.
     */
    Components::COMP_EXPANDABLE_CONTENT_INNER                         => [
        'name'       => \Yii::t('app', 'Inner Expandable Content'),
        'repeatable' => true,
        'groupable'  => true,
        'fields'     => [
            Fields::FIELD_TITLE     => [
                'name' => \Yii::t('app', 'Content Title'),
            ],
            Fields::FIELD_TEXT_AREA => [
                'name' => \Yii::t('app', 'Actual Content'),
            ],
            Fields::FIELD_IS_OPEN   => [
                'name' => \Yii::t('app', 'Is it opened By Default?'),
            ],
        ],
    ],

    /**
     * TEXT WITHOUT HEADING.
     *
     * Note:
     *      - Text with no heading whatsoever. It does have to be full width and have the
     *        margins defined for normal content.
     *
     * Example: ??
     *
     * Fields:
     *      - Text Area.
     */
    Components::COMP_TEXT_WITHOUT_HEADING                             => [
        'name'   => \Yii::t('app', 'Text With no Heading'),
        'fields' => [
            Fields::FIELD_TEXT_AREA => [
                'name' => \Yii::t('app', 'Content'),
            ],
        ],
    ],

    /**
     * HTML CODE
     *
     * Note:
     *      - Used in some cases where they provide a chunk of code such as svg or iframes.
     *
     * Example: ??
     *
     * Fields:
     *      - Html Code Field.
     */
    Components::COMP_HTML_CODE                                        => [
        'name'   => \Yii::t('app', 'HTML Component'),
        'fields' => [
            Fields::FIELD_HTML_CODE => [
                'name' => \Yii::t('app', 'HTML Code'),
            ],
        ],
    ],

    /**
     * HEADING
     *
     * Note:
     *      - A Single heading component.
     *
     * Exapmle: ??
     *
     * Fields:
     *      - Title
     *      - Bold Selector
     *      - Header Color.
     */
    Components::COMP_HEADING                                          => [
        'name'   => \Yii::t('app', 'Heading Only'),
        'fields' => [
            Fields::FIELD_TITLE                        => [
                'name' => \Yii::t('app', 'Heading Text'),
            ],
            Fields::FIELD_BOLD_SELECTOR                => [
                'name' => \Yii::t('app', 'Bold Heading?'),
            ],
            Fields::FIELD_CMS_COLOR_PALETTE_TEXT => [
                'name' => \Yii::t('app', 'Heading Title'),
            ],
        ],
    ],
    
    /**
     * TEXT WITH MEDIA.
     *
     * Note:
     *      - A text with a media resource on the right/left of it. The media Resource
     *        The media resource spans the whole height and half of the width.
     *
     *
     * Fields:
     *      - Title
     *      - Description
     *      - Image
     *      - Left or right: The image position
     */

    Components::COMP_TEXT_WITH_MEDIA                   => [
        'name'   => Yii::t('app', 'Text with media'),
        'fields' => [
            Fields::FIELD_TEXT_BOX         => [
                'name'     => \Yii::t('app', 'Title'),
                'required' => false,
            ],
            Fields::FIELD_TEXT_AREA        => [
                'name' => \Yii::t('app', 'Description'),
            ],
            Fields::FIELD_IMAGE            => [
                'name' => \Yii::t('app', 'Image'),
            ],
            Fields::FIELD_LTR_SWITCH_INPUT => [
                'name' => \Yii::t('app', 'Image shown on the Left or on the Right?'),
            ],
        ],
    ],

    /**
     * TEXT WITH MEDIA AND AN EXTRA BUTTON
     *
     * Note:
     *      - The same as above but with an actual button afterwards.
     *
     *
     * Fields:
     *      - Title
     *      - Description
     *      - Image
     *      - Left or right: The image position
     *      - Link Name
     *      - Link URL
     *      - Link Target
     *      - Link Color
     */
    Components::COMP_TEXT_WITH_MEDIA_WITH_BUTTON       => [
        'name'   => Yii::t('app', 'Text with media plus button'),
        'fields' => [
            Fields::FIELD_TEXT_BOX         => [
                'name' => \Yii::t('app', 'Title'),
            ],
            Fields::FIELD_TEXT_AREA        => [
                'name' => \Yii::t('app', 'Description'),
            ],
            Fields::FIELD_IMAGE            => [
                'name' => \Yii::t('app', 'Image'),
            ],
            Fields::FIELD_LTR_SWITCH_INPUT => [
                'name' => \Yii::t('app', 'Image shown on the Left or on the Right?'),
            ],
            Fields::FIELD_LINK_NAME        => [
                'name' => \Yii::t('app', 'Link Title'),
            ],
            Fields::FIELD_LINK_URL         => [
                'name' => \Yii::t('app', 'Link URL'),
            ],
            Fields::FIELD_LINK_TARGET      => [
                'name' => \Yii::t('app', 'Link Target'),
            ],
            Fields::FIELD_LINK_COLOR       => [
                'name' => \Yii::t('app', 'Link Color'),
            ],
        ],
    ],

    /**
     * CONTENT WITH BACKGROUND
     *
     * Note:
     *      - An Image with a Caption and a Background color full width as a wrapper.
     *
     *
     * Fields:
     *      - Color Palette for the background
     *      - Image: Image with a caption.
     */
    Components::COMP_IMAGE_WITH_CAPTION_AND_BACKGROUND => [
        'name'   => Yii::t('app', 'Content With Background'),
        'fields' => [
            Fields::FIELD_CMS_COLOR_PALETTE_BACKGROUND => [
                'name' => \Yii::t('app', 'Background Color'),
            ],
            Fields::FIELD_IMAGE                              => [
                'name' => \Yii::t('app', 'Image'),
            ],
            Fields::FIELD_TEXT_BOX                           => [
                'name' => \Yii::t('app', 'Image Caption'),
            ],
        ],
    ],

    /**
     * COMPONENT IMAGE
     *
     * Note:
     *      - An Image with a Caption.
     *
     *
     * Fields:
     *      - Image: Image.
     *      - Caption
     */
    Components::COMP_IMAGE                             => [
        'name'   => Yii::t('app', 'Image Component'),
        'fields' => [
            Fields::FIELD_IMAGE    => [
                'name' => \Yii::t('app', 'Image'),
            ],
            Fields::FIELD_TEXT_BOX => [
                'name' => \Yii::t('app', 'Image Caption'),
            ],
        ],
    ],

    /**
     * TEXT RESOURCE GROUP.
     */
    Components::COMP_TEXT_RESOURCE_GROUP               => [
        'name'             => \Yii::t('app', 'Text Resource Group'),
        'inner-components' => [
            Components::COMP_TEXT_RESOURCE => [
                'repeatable' => true,
            ],
        ],
    ],

    /**
     * TEXT RESOURCE
     *
     * Note:
     *      - Similar to "Product": An Image, A title a description and a Button
     *
     *
     * Fields:
     *      - Image
     *      - Text Box
     */
    Components::COMP_TEXT_RESOURCE                     => [
        'name'   => Yii::t('app', 'Text Resource'),
        'fields' => [
            Fields::FIELD_IMAGE       => [
                'name' => \Yii::t('app', 'Resource Image'),
            ],
            Fields::FIELD_TEXT_BOX    => [
                'name' => \Yii::t('app', 'Resource Title'),
            ],
            Fields::FIELD_TEXT_AREA   => [
                'name' => \Yii::t('app', 'Resource Description'),
            ],
            Fields::FIELD_LINK_NAME   => [
                'name' => \Yii::t('app', 'Button Name'),
            ],
            Fields::FIELD_LINK_URL    => [
                'name' => \Yii::t('app', 'Button URL'),
            ],
            Fields::FIELD_LINK_COLOR  => [
                'name' => \Yii::t('app', 'Button Color'),
            ],
            Fields::FIELD_LINK_TARGET => [
                'name' => \Yii::t('app', 'Button Target'),
            ],
        ],
    ],

    /**
     * HIGHLIGHTED IMAGE
     *
     * Note:
     *      - This is the main "Heading" for each page. It has the Page title and some other things.
     *        For references look on the header of the example.
     *
     *
     * Fields:
     *      - Title.
     *      - Image
     *      - Background Color
     *      - Frame Color
     */
    Components::COMP_HIGHLIGHTED_IMAGE                 => [
        'name'   => Yii::t('app', 'Highlighted Image'),
        'fields' => [
            Fields::FIELD_TITLE_BOLD                         => [
                'name' => \Yii::t('app', 'Bold Title'),
            ],
            Fields::FIELD_TITLE_NON_BOLD                     => [
                'name' => \Yii::t('app', 'Non Bold Title'),
            ],
            Fields::FIELD_IMAGE                              => [
                'name' => \Yii::t('app', 'Image'),
            ],
            Fields::FIELD_CMS_COLOR_PALETTE_BACKGROUND => [
                'name' => \Yii::t('app', 'Background Color'),
            ],
            Fields::FIELD_FRAME_COLOR                        => [
                'name' => \Yii::t('app', 'Frame Color'),
            ],
        ],
    ],

    /**
     * REFERENCES
     *
     * Note:
     *      - References for each page (pretty much each page at least).
     *
     *
     * Fields:
     *      - Title
     *      - Text Area: The actual references.
     */
    Components::COMP_REFERENCES                        => [
        'name'   => Yii::t('app', 'References'),
        'title'  => true,
        'fields' => [
            Fields::FIELD_TEXT_AREA => [ ],
        ],
    ],

    /**
     * GRID
     *
     * Note:
     *      - References for each page (pretty much each page at least).
     *
     *
     * Fields:
     *      - None
     *
     * Inner Components:
     *      - Grid element
     */
    Components::COMP_GRID                              => [
        'name'             => Yii::t('app', 'Grid'),
        'inner-components' => [
            Components::COMP_GRID_ELEMENT => [
                'repeatable' => true,
            ],
        ],
    ],

    /**
     * GRID ELEMENT
     *
     * Note:
     *      - References for each page (pretty much each page at least).
     *
     *
     * Fields:
     *      TBD
     */
    Components::COMP_GRID_ELEMENT                      => [
        'name'   => Yii::t('app', 'Grid Element'),
        'fields' => [
            Fields::FIELD_TITLE_BOLD                         => [
                'name' => \Yii::t('app', 'Bold Title'),
            ],
            Fields::FIELD_TITLE_NON_BOLD                     => [
                'name' => \Yii::t('app', 'Non Bold Title'),
            ],
            Fields::FIELD_IMAGE                              => [
                'name' => \Yii::t('app', 'Image'),
            ],
            Fields::FIELD_CMS_COLOR_PALETTE_BACKGROUND => [
                'name' => \Yii::t('app', 'Background Color'),
            ],
            Fields::FIELD_LINK_NAME                          => [ ],
            Fields::FIELD_LINK_URL                           => [ ],
            Fields::FIELD_LINK_TARGET                        => [ ],
        ],
    ]

];