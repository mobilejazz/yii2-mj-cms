<?php

use mobilejazz\yii2\cms\common\models\Components;

/**
 * Attention, each component can have a repeatable => true and a groupable => true option.
 */
return [
    /**
     * minus 1- Cheat Sheet (everything here to develop components easier).
     */
    'cheatsheet' => [
        'name'       => \Yii::t('app', 'Cheat Sheet (Development purposes)'),
        'description'=> \Yii::t('app', 'Cheat Sheet Description (Development purposes)'),
        'icon'       => 'fa-arrow-circle-o-right',
        'components' => [
            Components::COMP_BUTTON,
            Components::COMP_EXPANDABLE_CONTENT,
            Components::COMP_EXPANDABLE_CONTENT_INNER,
            Components::COMP_HEADING,
            Components::COMP_HIGHLIGHTED_IMAGE,
            Components::COMP_HTML_CODE,
            Components::COMP_IMAGE,
            Components::COMP_IMAGE_WITH_CAPTION_AND_BACKGROUND,
            Components::COMP_INTRODUCTION_TEXT,
            Components::COMP_INTRODUCTION_TEXT_TITLE_ONLY,
            Components::COMP_INTRODUCTION_TEXT_TWO_LINES,
            Components::COMP_LINK,
            Components::COMP_TEXT_RESOURCE_GROUP,
            Components::COMP_TEXT_WITHOUT_HEADING,
            Components::COMP_TEXT_WITH_HEADING,
            Components::COMP_TEXT_WITH_HEADING_2_COLS,
            Components::COMP_TEXT_WITH_HEADING_NO_LINE_SELECTABLE_TITLE_COLOR,
            Components::COMP_TEXT_WITH_MEDIA,
            Components::COMP_TEXT_WITH_MEDIA_WITH_BUTTON
        ]
    ],

    'custom-content' => [
        'name'       => \Yii::t('app', 'Custom Content'),
        'icon'       => 'fa-arrow-circle-o-right',
        'components' => [ ],
    ],

    /**
     * 0 - Home Page
     * Highlighted Image
     * Introduction text with no title.
     * Grid (3 cells)
     * Grid (3 cells)
     * Grid (3 cells)
     * Grid (3 cells)
     */
    'home-page'      => [
        'name'       => \Yii::t('app', 'Home Page'),
        'icon'       => 'fa-arrow-circle-o-right',
        'components' => [
            Components::COMP_HIGHLIGHTED_IMAGE,
            Components::COMP_INTRODUCTION_TEXT_NO_TITLE,
            Components::COMP_GRID,
            Components::COMP_GRID,
            Components::COMP_GRID,
            Components::COMP_GRID,
        ],
    ],

    'contact-us'                          => [
        'name'       => \Yii::t('app', 'Contact Us'),
        'icon'       => 'fa-arrow-circle-o-right',
        'components' => [ ],
    ],

    /**
     * 0.1 - About Us
     * Highlighted Image
     * Text with heading.
     * Text with heading no line
     * Image.
     * Text with heading
     */
    'about-us'                            => [
        'name'       => \Yii::t('app', 'About Us'),
        'icon'       => 'fa-arrow-circle-o-right',
        'components' => [
            Components::COMP_HIGHLIGHTED_IMAGE,
            Components::COMP_TEXT_WITH_HEADING,
            Components::COMP_TEXT_WITH_HEADING_NO_LINE_SELECTABLE_TITLE_COLOR,
            Components::COMP_IMAGE,
            Components::COMP_TEXT_WITH_HEADING,
        ],
    ],

];