<?php
function sms_get_option($name, $default_value) {
    $options = get_option(OPTIONS, []);

    if (key_exists($name, $options))
        return $options[$name];
    else
        return $default_value;
}
function sms_update_option($name, $value) {
    $options = get_option(OPTIONS, []);

    $options[$name] = $value;
    update_option(OPTIONS, $options);
}