<?php
function sms_shortcode($options) {
    // generate_frontend is echoing content, capture that as string for shortcode
    ob_start();
    sms_generate_frontend();
    $out = ob_get_contents();
    ob_end_clean();

    return $out;
}