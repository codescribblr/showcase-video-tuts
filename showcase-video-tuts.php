<?php

/*
Plugin Name: Showcase Video Tuts
Plugin URI: https://github.com/codescribblr/showcase-video-tuts
Description: This plugin adds an options page in which you can add video tutorials from youtube or vimeo directly to the dashboard. It requires the advanced custom fields plugin to operate correctly.
Author: Codescribblr
Version: 1.6
Author URI: http://codescribblr.com/
*/

define('SHOWCASE_VIDEOTUTS_VERSION', '1.6');
define('DS', DIRECTORY_SEPARATOR);
define('SHOWCASE_VT_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('SHOWCASE_VT_PLUGIN_DIR', dirname( __FILE__ ));
define('SHOWCASE_VT_INCLUDE_PATH', SHOWCASE_VT_PLUGIN_DIR.DS.'includes'.DS);

function showcase_vt_init(){
    require_once(SHOWCASE_VT_INCLUDE_PATH.'register-fields.php');
}
add_action( 'plugins_loaded', 'showcase_vt_init' );

add_action('after_setup_theme', 'showcase_vt_modify_options_page_titles');
function showcase_vt_modify_options_page_titles() {
    add_filter('acf/options_page/settings', 'showcase_vt_setup_options_page_settings');
}

function showcase_vt_setup_options_page_settings( $settings ) {
    $settings['title'] = 'Site Options';
    $settings['menu'] = 'Site Options';
    $settings['pages'][] = 'Site Options';
    $settings['pages'][] = 'Video Tuts Settings';
    return $settings;
}

if( function_exists('acf_add_options_page') ) {
 
    acf_add_options_page(array(
        'page_title'    => 'Site Options',
        'menu_title'    => 'Site Options',
        'menu_slug'     => 'acf-options-site-options',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
 
    acf_add_options_sub_page(array(
        'page_title'    => 'Video Tuts Settings',
        'menu_title'    => 'Video Tuts Settings',
        'menu_slug'     => 'acf-options-video-tuts-settings',
        'parent_slug'   => 'acf-options-site-options',
    ));
 
}

/* ------------------------------------------------------------------
 * CREATE SUBMENU LINK ON PLUGINS PAGE
 * --------------------------------------------------------------- */

function showcase_vt_plugin_action_links($links, $file) {

    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    // check to make sure we are on the correct plugin
    if ($file == $this_plugin) {

        // link to what ever you want
        $plugin_links[] = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=acf-options-video-tuts-settings">'.__('Settings', 'showcase-video-tuts').'</a>';

        // add the links to the list of links already there
        foreach($plugin_links as $link) {
            array_unshift($links, $link);
        }
    }

    return $links;
}
add_filter('plugin_action_links', 'showcase_vt_plugin_action_links', 10, 2);

/* ------------------------------------------------------------------
 * CREATE DASHBOARD METABOX WITH TUTORIALS
 * --------------------------------------------------------------- */

add_action('wp_dashboard_setup', 'showcase_vt_custom_dashboard_widgets');
function showcase_vt_custom_dashboard_widgets() {
    global $wp_meta_boxes;
    if(current_user_can('manage_options')){
        wp_add_dashboard_widget('custom_help_widget', 'Video Walk-through & Support', 'showcase_vt_custom_dashboard_help');
    }
}

function showcase_vt_custom_dashboard_help() {
    
    the_field('showcase_vt_intro_text', 'options');

    $video_tutorials = get_field('showcase_vt_video_tutorials', 'options');
    
    if($video_tutorials):
        foreach($video_tutorials as $vt):

            $embed_link = showcase_vt_video_link($vt['showcase_vt_video_url']);

            echo '<h3>'.$vt['showcase_vt_video_title'].'</h3>';
            echo ($vt['showcase_vt_video_desc']) ? $vt['showcase_vt_video_desc'] : '';
            echo '<p class="video" style="max-width:100%"><iframe style="max-width:100%" width="640" height="480" src="'.$embed_link.'" frameborder="0" allowfullscreen></iframe></p>';
            echo '<p><a target="_blank" href="'.$vt['showcase_vt_video_url'].'">'.$vt['showcase_vt_video_title'].'</a></p>';

        endforeach;
    else:
        echo '<p>No tutorial videos yet. Check back soon!';
    endif;

}

function showcase_vt_video_link($link) {
    if(preg_match('~youtube~', $link)) {
        preg_match_all('/.*v=(.*)/', $link, $matches);
        if(!empty($matches[1][0])) {
            $link = '//www.youtube.com/embed/' . $matches[1][0];
        }
    }
    elseif(preg_match('~vimeo~', $link)) {
        preg_match('~vimeo.com/([\d]+)~', $link, $video_id);
        $link = '//player.vimeo.com/video/' . $video_id[1];
    }
    
    return $link;
}

/* ------------------------------------------------------------------
 * SETUP SCRIPTS AND STYLES
 * --------------------------------------------------------------- */

add_action( 'admin_enqueue_scripts', 'showcase_vt_scripts_and_styles', 10 );
function showcase_vt_scripts_and_styles(){
    //add fitvids script
    wp_register_script('fitvids', '//cdn.jsdelivr.net/fitvids/1.0.3/jquery.fitvids.js', array('jquery'), '1.0.3');
    wp_enqueue_script('fitvids');

    wp_register_script('showcase_vt_js', SHOWCASE_VT_PLUGIN_URL."/includes/js/scripts.js", array('jquery'), SHOWCASE_VIDEOTUTS_VERSION);
    wp_enqueue_script('showcase_vt_js');
}

/* ------------------------------------------------------------------
 * GITHUB HOSTING AND UPDATER
 * --------------------------------------------------------------- */

require_once(SHOWCASE_VT_INCLUDE_PATH.'updater.php');   
if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
    $config = array(
        'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
        'proper_folder_name' => 'showcase-video-tuts', // this is the name of the folder your plugin lives in
        'api_url' => 'https://api.github.com/repos/codescribblr/showcase-video-tuts', // the github API url of your github repo
        'raw_url' => 'https://raw.github.com/codescribblr/showcase-video-tuts/master', // the github raw url of your github repo
        'github_url' => 'https://github.com/codescribblr/showcase-video-tuts', // the github url of your github repo
        'zip_url' => 'https://github.com/codescribblr/showcase-video-tuts/zipball/master', // the zip url of the github repo
        'sslverify' => true, // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
        'requires' => '3.5', // which version of WordPress does your plugin require?
        'tested' => '4.0.1', // which version of WordPress is your plugin tested up to?
        'readme' => 'README.md', // which file to use as the readme for the version number
        'access_token' => '', // Access private repositories by authorizing under Appearance > Github Updates when this example plugin is installed
    );
    new WP_GitHub_Updater($config);
}