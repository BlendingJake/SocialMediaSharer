<?php
function sms_register_social_option($name, $default_options, $fields, $priority=-1, $misc=[]) {
    $options = sms_get_option(REGISTERED_OPTIONS, []);

    $new_options = array_merge($default_options, ['active'=>false]);
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
function sms_facebook_display_panel() {
    return;
}
function sms_facebook_save_panel() {
    return;
}
function sms_facebook_display() {
    return;
}
function sms_facebook_include_api() {
    ?>
    <script>
        $(document).ready(function() {
           $.ajax({cache:true});
           $.getScript('//connect.facebook.net/en_US/sdk.js', function() {
               FB.init({
                   appId: '114801585966021',
                   version: 'v2.8'
               });
               $('#loginbutton,#feedbutton').removeAttr('disabled');
           });
        });
    </script>
    <?php
}