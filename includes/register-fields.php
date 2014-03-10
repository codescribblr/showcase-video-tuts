<?php
/**
* @package Showcase Video Tuts
* @since version 1.0
*/

/* ------------------------------------------------------------------
* Do Not Allow Direct Script Access
* --------------------------------------------------------------- */
if (!function_exists ('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

/**
* Register field groups
* The register_field_group function accepts 1 array which holds the relevant data to register a field group
* You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
* This code must run every time the functions.php file is read
*/

if(function_exists("register_field_group")) {
    register_field_group(array (
        'id' => 'acf_dashboard-video-tutorials',
        'title' => 'Dashboard Video Tutorials',
        'fields' => array (
            array (
                'key' => 'field_52d0695f1594e',
                'label' => 'Intro Text',
                'name' => 'showcase_vt_intro_text',
                'type' => 'wysiwyg',
                'default_value' => '<p>Welcome to your website administration panel! Need help? Contact the developer <a href="mailto:websupport@createlaunchlead.com">here</a>.</p>
        <p>Each video is about 5 minutes long. We have included a link to each video below the video itself in case the videos are not showing up on the page. Also, if you want to view them in full screen mode, click the screen button at the bottom right corner of the video.</p>',
                'toolbar' => 'full',
                'media_upload' => 'no',
            ),
            array (
                'key' => 'field_52d068261594b',
                'label' => 'Video Tutorials',
                'name' => 'showcase_vt_video_tutorials',
                'type' => 'repeater',
                'sub_fields' => array (
                    array (
                        'key' => 'field_52d068471594c',
                        'label' => 'Video Title',
                        'name' => 'showcase_vt_video_title',
                        'type' => 'text',
                        'required' => 1,
                        'column_width' => '',
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'formatting' => 'none',
                        'maxlength' => '',
                    ),
                    array (
                        'key' => 'field_25',
                        'label' => 'Video Description',
                        'name' => 'showcase_vt_video_desc',
                        'type' => 'wysiwyg',
                        'column_width' => '',
                        'default_value' => '',
                        'toolbar' => 'full',
                        'media_upload' => 'no',
                    ),
                    array (
                        'key' => 'field_52d068661594d',
                        'label' => 'Video URL',
                        'name' => 'showcase_vt_video_url',
                        'type' => 'text',
                        'instructions' => 'Paste the YouTube video url here (e.g. http://www.youtube.com/watch?v=TfV_APBk16Q)',
                        'required' => 1,
                        'column_width' => '',
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'formatting' => 'none',
                        'maxlength' => '',
                    ),
                ),
                'row_min' => '',
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => 'Add Video',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options-video-tuts-settings',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
        ),
        'options' => array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' => array (
            ),
        ),
        'menu_order' => 0,
    ));
}
