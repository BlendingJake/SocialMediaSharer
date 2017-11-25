<?php
function sms_register_social_option($name, $default_options, $fields, $priority=-1, $misc=[]) {
    $options = sms_get_option(REGISTERED_OPTIONS, []);

    $new_options = array_merge($default_options, ['active'=>true]);
    $options[$name] = $new_options;

    sms_set_option_priority($name, $priority);
    sms_update_option_fields($name, $fields);
    sms_update_option(REGISTERED_OPTIONS, $options);

    // optional arguments
    if (key_exists('includes', $misc)) {
        foreach ($misc['includes'] as $include) {
            if ($include['type'] == "script")
                sms_add_script_include($include['name'], $include['src']);
            else
                sms_add_style_include($include['name'], $include['src']);
        }
    }
}

function sms_set_option_priority($name, $priority) {
    $priorities = sms_get_option(OPTION_PRIORITIES, []);

    if ($priority == -1 || $priority > count($priorities) || $priority < 1)   // place at end
        array_push($priorities, $name);
    else
        array_splice($priorities, $priority - 1, 0, [$name]);

    sms_update_option(OPTION_PRIORITIES, $priorities);
}

// default option actions
// DEFAULT
function sms_the_title() {
    echo get_bloginfo('name');
}
function sms_default_meta() {
    ?>
    <meta name="og:image" content="<?php do_action("sms_the_image");?>" />
    <meta name="og:title" content="<?php do_action('sms_the_title');?>" />
    <meta name="og:description" content="<?php do_action("sms_the_description");?>" />
    <?php
}

add_action("wp_head", "sms_default_meta");
if (!has_action('sms_the_title'))
    add_action('sms_the_title', 'sms_the_title');

// FACEBOOK
function sms_facebook_include_api() {
    ?>
    <script>
        jQuery(document).ready(function() {
            jQuery.ajax({cache:true});
            jQuery.getScript('//connect.facebook.net/en_US/sdk.js', function() {
               FB.init({
                   appId: '114801585966021',
                   version: 'v2.8'
               });
                jQuery('#loginbutton,#feedbutton').removeAttr('disabled');
           });
        });
    </script>
    <?php
}

add_action('wp_head', 'sms_facebook_include_api');

// TWITTER
function sms_twitter_generate_href() {
    $message = sms_get_option_field('twitter', 'message');
    $url = get_the_permalink();
    $base_url = "https://twitter.com/intent/tweet?text=";

    echo $base_url . urlencode($message) . "&url=" . urlencode($url);
}
function sms_twitter_display_panel() {
    $fields = sms_get_option_fields("twitter");
    ?>
    <p>
        <label for="twitter-username">Twitter Username</label>
        <input type='text' name="twitter-username" id="twitter-username" value="<?php echo $fields['username'];?>">
    </p>
    <p>
        <label for="twitter-message">Twitter Message</label><br>
        <textarea name="twitter-message" id="twitter-message" rows="4" cols="50"><?php echo $fields['message'];?></textarea>
    </p>
    <?php
}
function sms_twitter_save_panel() {
    $fields = sms_get_option_fields("twitter");
    if (isset($_POST['twitter-message'])) {
        $fields['message'] = $_POST['twitter-message'];
    }
    if (isset($_POST['twitter-username']))
        $fields['username'] = $_POST['twitter-username'];

    sms_update_option_fields('twitter', $fields);
}
function sms_twitter_meta() {
    ?>
    <meta name="twitter:card" content="photo" />
    <meta name="twitter:site" content="<?php do_action('sms_the_twitter_site_username');?>" />
    <meta name="twitter:creator" content="<?php do_action('sms_the_twitter_creator_username');?>" />
    <?php
}
function sms_twitter_site_username() {
    echo '@' . sms_get_option_field('twitter', 'username', '');
}

add_action('sms_twitter_generate_href', 'sms_twitter_generate_href');
add_action('sms_twitter_display_panel', 'sms_twitter_display_panel');
add_action('sms_twitter_save_panel', 'sms_twitter_save_panel');
if (!has_action('sms_the_twitter_site_username'))
    add_action('sms_the_twitter_site_username', 'sms_twitter_site_username');
add_action('wp_head', 'sms_twitter_meta');

// GOOGLE PLUS
function sms_google_plus_generate_href() {
    $url = get_the_permalink();
    $base_url = "https://plus.google.com/share?url=";

    echo $base_url . urlencode($url);
}

add_action('sms_google_plus_generate_href', 'sms_google_plus_generate_href');