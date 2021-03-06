<?php
/*
Plugin Name: Social Media Sharer
Description: Allows easy sharing of posts and other pages to social media platforms
Version: 0.1
Author: Jacob Morris
*/

include_once "sms_shortcodes.php";
include_once "sms_actions.php";
include_once "sms_menu.php";
include_once "sms_functions.php";

define("OPTIONS", "sms_options");
define("REGISTERED_OPTIONS", "registered_options");
define("OPTION_PRIORITIES", "option_priorities");
define("OPTION_FIELDS", "option_fields");
define("REQUIRED_SCRIPTS", "required_scripts");
define("REQUIRED_STYLES", "required_styles");

function sms_activation() {
    $sms_options = [
        // basic settings
        'name' => 'Social Media Sharer',
        'version' => [0, 0, 1],

        'enqueue_font_awesome' => true,
        'enqueue_jquery' => false,
        'icon_only' => false,

        // registered options
        REGISTERED_OPTIONS => [],
        OPTION_PRIORITIES => [],
        OPTION_FIELDS => [],
        REQUIRED_SCRIPTS => [],
        REQUIRED_STYLES => []
    ];

    add_option(OPTIONS, $sms_options);

    // add default options
    sms_add_default_options();
}

function sms_deactivation() {
    delete_option(OPTIONS);
}

function sms_general_enqueue() {
    if (sms_get_option('enqueue_font_awesome', true))
        wp_enqueue_script('font-awesome', "https://use.fontawesome.com/c8a1fa0026.js");

    if (sms_get_option('enqueue_jquery', true)) {
        wp_deregister_script( 'jquery' );
        wp_enqueue_script( 'jquery', "//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js", array(),
            '2.0.0' );
    }
}

function sms_frontend_enqueue() {
    sms_general_enqueue();
    wp_enqueue_style("sms_frontend_style", plugins_url("/css/frontend_stylesheet.css", __FILE__));
    wp_enqueue_script("sms_default_platforms_js", plugins_url('/javascript/default_platforms.js', __FILE__));

//    $styles = sms_get_option(REQUIRED_STYLES);
//    $scripts = sms_get_option(REQUIRED_SCRIPTS);
}

function sms_backend_enqueue() {
    sms_general_enqueue();
    wp_enqueue_style("sms_backend_style", plugins_url("/css/backend_stylesheet.css", __FILE__));
}

function sms_add_menu() {
    add_menu_page("Social Media Sharer", "Social Media Sharer",
        "manage_options", "sms-menu", "sms_menu", null, 99);
}

// add actions
// internal
add_action("register_social_media_option", "sms_register_social_option", 20, 5);
add_action("set_social_media_priority", "sms_set_option_priority", 20, 2);
add_action("sms_generate_frontend", "sms_generate_frontend");

if (!has_action("sms_the_image"))  // only load if needed
    add_action("sms_the_image", "sms_default_the_image");

if (!has_action("sms_the_description"))
    add_action("sms_the_description", "sms_default_the_description");

// enqueue
add_action("wp_enqueue_scripts", "sms_frontend_enqueue");
add_action("admin_enqueue_scripts", "sms_backend_enqueue");

// panel
add_action("admin_menu", "sms_add_menu");

// shortcodes
add_shortcode('social-media-sharer', 'sms_shortcode');

// hooks
register_activation_hook(__FILE__, "sms_activation");
register_deactivation_hook(__FILE__, "sms_deactivation");