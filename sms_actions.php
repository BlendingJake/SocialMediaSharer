<?php
function sms_register_social_option($name, $icon, $panel_display_action, $panel_save_action, $display_action,
                                    $is_icon_url=false, $priority=-1) {
    $options = sms_get_option(REGISTERED_OPTIONS, []);


    $new_option = [
        'icon' => $icon,
        'display_panel' => $panel_display_action,
        'save_panel' => $panel_save_action,
        'display' => $display_action,
        'is_icon_url' => $is_icon_url,

        'active' => false
        ];

    $options[$name] = $new_option;

    sms_set_option_priority($name, $priority);
    sms_update_option(REGISTERED_OPTIONS, $options);
}

function sms_set_option_priority($name, $priority) {
    $priorities = sms_get_option(OPTION_PRIORITIES, []);

    if ($priority == -1 || $priority > count($priorities) || $priority < 1)   // place at end
        array_push($priorities, $name);
    else
        array_splice($priorities, $priority - 1, 0, [$name]);

    sms_update_option(OPTION_PRIORITIES, $priorities);
}
