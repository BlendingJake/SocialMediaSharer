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


$sms_options = [
    'registered-options' => [],
    'options-priority' => []
];

function sms_activation() {
    global $sms_options;

    add_option(OPTIONS, $sms_options);
}

function sms_deactivation() {
    delete_option(OPTIONS);
}

function sms_frontend_enqueue() {
    wp_enqueue_style("sms_frontend_style", plugins_url("/css/frontend_stylesheet.css", __FILE__));
}

function sms_backend_enqueue() {
    wp_enqueue_style("sms_backend_style", plugins_url("/css/backend_stylesheet.css", __FILE__));
}

function sms_add_menu() {
    add_menu_page("Social Media Sharer", "Social Media Sharer",
        "manage_options", "sms-menu", "sms_menu", null, 99);
}

// add actions
// internal
add_action("register_social_media_option", "sms_register_social_option", 20, 7);
add_action("set_social_media_priority", "sms_set_option_priority", 20, 2);

// enqueue
add_action("wp_enqueue_scripts", "sms_frontend_enqueue");
add_action("admin_enqueue_scripts", "sms_backend_enqueue");

// panel
add_action("admin_init", "sms_menu_fields");
add_action("admin_menu", "sms_add_menu");

// hooks
register_activation_hook(__FILE__, "sms_activation");
register_deactivation_hook(__FILE__, "sms_deactivation");