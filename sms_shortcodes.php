<?php
function sms_shortcode($options) {
    // generate_frontend is echoing content, capture that as string for shortcode
    return sms_function_output_as_str('do_action', 'sms_generate_frontend');
}