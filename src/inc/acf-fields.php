<?php
if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group(array(
    'key' => 'group_694fe6307c642',
    'title' => 'Theme Option',
    'fields' => array(

        // ================= HEADER TAB =================
        array(
            'key' => 'field_694fea3a82f22',
            'label' => 'Header',
            'type' => 'tab',
            'placement' => 'top',
        ),

        array(
            'key' => 'field_694fe638953dc',
            'label' => 'Logo',
            'name' => 'logo',
            'type' => 'image',
            'return_format' => 'url',
            'preview_size' => 'medium',
            'library' => 'all',
        ),

        array(
            'key' => 'field_694feb1c40f56',
            'label' => 'News Bar',
            'name' => 'news_bar',
            'type' => 'repeater',
            'layout' => 'table',
            'button_label' => 'Add Row',
            'sub_fields' => array(
                array(
                    'key' => 'field_694feb3d40f57',
                    'label' => 'News Bar Point',
                    'name' => 'news_bar_point',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_694fec1d1bba0',
                    'label' => 'News Bar Point Link',
                    'name' => 'news_bar_point_link',
                    'type' => 'text',
                ),
            ),
        ),

        array(
            'key' => 'field_694fe656953dd',
            'label' => 'Social Media',
            'name' => 'social_media',
            'type' => 'repeater',
            'layout' => 'table',
            'button_label' => 'Add Row',
            'sub_fields' => array(
                array(
                    'key' => 'field_694fe68c953de',
                    'label' => 'Social Media Icon',
                    'name' => 'social_media_icon',
                    'type' => 'font-awesome',
                    'icon_sets' => array('solid','regular','brands'),
                    'save_format' => 'element',
                    'allow_null' => 0,
                    'show_preview' => 1,
                ),
                array(
                    'key' => 'field_694fe6a2953df',
                    'label' => 'Social Media Link',
                    'name' => 'social_media_link',
                    'type' => 'text',
                ),
            ),
        ),

        array(
            'key' => 'field_694feab487378',
            'label' => 'Contact Button',
            'name' => 'contact_button',
            'type' => 'link',
            'return_format' => 'array',
        ),

        // ================= SIDEBAR TAB =================
        array(
            'key' => 'field_694fff5de2db8',
            'label' => 'Side Bar',
            'type' => 'tab',
            'placement' => 'top',
        ),

        array(
            'key' => 'field_694fff6ae2db9',
            'label' => 'Side Bar Text',
            'name' => 'side_bar_text',
            'type' => 'text',
        ),

        // ================= CONTACT INFO TAB =================
        array(
            'key' => 'field_695506d27db8c',
            'label' => 'Contact Info',
            'type' => 'tab',
            'placement' => 'top',
        ),

        array(
            'key' => 'field_695506e877870',
            'label' => 'Contact Info',
            'name' => 'contact_info',
            'type' => 'repeater',
            'layout' => 'table',
            'button_label' => 'Add Row',
            'sub_fields' => array(
                array(
                    'key' => 'field_6955071f77871',
                    'label' => 'Contact Info Heading',
                    'name' => 'contact_info_heading',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_6955073277872',
                    'label' => 'Contact Info Image',
                    'name' => 'contact_info_image',
                    'type' => 'image',
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_6955074a77873',
                    'label' => 'Contact Info Link',
                    'name' => 'contact_info_link',
                    'type' => 'link',
                    'return_format' => 'array',
                ),
            ),
        ),

        // ================= FOOTER TAB =================
        array(
            'key' => 'field_6957528601aea',
            'label' => 'Footer',
            'type' => 'tab',
            'placement' => 'top',
        ),

        array(
            'key' => 'field_6957529101aeb',
            'label' => 'Footer Logo',
            'name' => 'footer_logo_',
            'type' => 'image',
            'return_format' => 'url',
            'preview_size' => 'medium',
        ),

        array(
            'key' => 'field_695752a001aec',
            'label' => 'Footer Short Description',
            'name' => 'footer_short_description',
            'type' => 'textarea',
        ),

        array(
            'key' => 'field_695752c601aed',
            'label' => 'Footer First Heading',
            'name' => 'footer_frist_heading',
            'type' => 'text',
        ),

        array(
            'key' => 'field_695752d801aee',
            'label' => 'Footer Second Heading',
            'name' => 'footer_second_heading',
            'type' => 'text',
        ),

        array(
            'key' => 'field_695752e401aef',
            'label' => 'Footer Third Heading',
            'name' => 'footer_thrid_heading',
            'type' => 'text',
        ),

    ),

    'location' => array(
        array(
            array(
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'theme-options',
            ),
        ),
    ),

    'position' => 'normal',
    'style' => 'default',
    'active' => true,
));

endif;
