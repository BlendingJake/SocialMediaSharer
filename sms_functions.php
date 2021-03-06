<?php
// TODO: Add support/usage for priority, maybe frontend support

// get/update options
function sms_get_option($name, $default_value=[]) {
    $options = get_option(OPTIONS, []);

    if (isset($options[$name]))
        return $options[$name];
    else
        return $default_value;
}
function sms_update_option($name, $value) {
    $options = get_option(OPTIONS, []);

    $options[$name] = $value;
    update_option(OPTIONS, $options);
}

// quickly get name/title
function sms_get_name() {
    return sms_get_option("name", "Title");
}

// saving and getting fields for displaying in panel
function sms_get_option_fields($name, $default_value=[]) {
    $options = sms_get_option(OPTION_FIELDS, []);

    if (isset($options[$name]))
        return $options[$name];
    else
        return $default_value;
}
function sms_get_option_field($option_name, $field_name, $default_value="") {
    $fields = sms_get_option_fields($option_name);

    if (isset($fields[$field_name]))
        return $fields[$field_name];

    return $default_value;
}
function sms_update_option_fields($name, $fields) {
    $options = sms_get_option(OPTION_FIELDS, []);
    $options[$name] = $fields;

    sms_update_option(OPTION_FIELDS, $options);
}

// adding includes
function sms_add_script_include($name, $src) {
    $options = sms_get_option(REQUIRED_SCRIPTS, []);
    array_push($options, ['name'=>$name, 'src'=>$src]);
    sms_update_option(REQUIRED_SCRIPTS, $options);
}
function sms_add_style_include($name, $src) {
    $options = sms_get_option(REQUIRED_STYLES, []);
    array_push($options, ['name'=>$name, 'src'=>$src]);
    sms_update_option(REQUIRED_STYLES, $options);
}

// get icon
function sms_the_icon($icon, $is_icon_url) {
    if ($is_icon_url == true) : ?>
        <img src="<?php echo $icon; ?>">
    <?php else: ?>
        <i class="fa fa-<?php echo $icon; ?>" aria-hidden="true"></i>
    <?php endif;
}

// get function output
function sms_function_output_as_str($function, $param=null) {
    ob_start();

    if ($param)
        $function($param);
    else
        $function();

    $out = ob_get_contents();
    ob_end_clean();

    return $out;
}

// check if there is a need for an href attribute, if so call hook associated and return href
function sms_determine_href_tag($ops) {
    if (isset($ops['href_action']) && has_action($ops['href_action']))
        return " href='" . sms_function_output_as_str('do_action', $ops['href_action']) . "'";

    return " ";
}

// add default options
function sms_add_default_options() {
    if (!has_action("register_social_media_option"))
        return;

    $default_options = [
        "facebook" => [
            'options' => [
                'icon' => 'facebook-official',
                'display_panel' => 'sms_facebook_display_panel',
                'save_panel' => 'sms_facebook_save_panel',
                'display' => '',
                'is_icon_url' => false,
                'onclick' => 'openFacebookDialog()'
            ],
            'fields' => [],
            'priority' => -1,
            'misc' => [],
        ],
        "twitter" => [
            'options' => [
                'icon' => 'twitter',
                'display_panel' => 'sms_twitter_display_panel',
                'save_panel' => 'sms_twitter_save_panel',
                'display' => '',
                'is_icon_url' => false,
                'href_action' => 'sms_twitter_generate_href',
                'onclick' => 'openWindowDialog(this, event, \'Twitter\')',
                'tag_extras' => ''
            ],
            'fields' => [
                'message' => '',
                'username' => ''
            ],
            'priority' => -1,
            'misc' => [],
        ],
        "google+" => [
            'options' => [
                'icon' => 'google-plus-official',
                'display_panel' => '',
                'save_panel' => '',
                'display' => '',
                'is_icon_url' => false,
                'href_action' => 'sms_google_plus_generate_href',
                'onclick' => 'openWindowDialog(this, event, \'Google+\', 400, 500)',
                'tag_extras' => ''
            ],
            'fields' => [
                'message' => 'Check out this neat article!',
            ],
            'priority' => -1,
            'misc' => [],
        ]
    ];

    foreach ($default_options as $name=>$ops) {
        do_action('register_social_media_option', $name, $ops['options'], $ops['fields'], $ops['priority'],
            $ops['misc']);
    }
}

//generate output
function sms_generate_frontend() {
    ?>
    <div class="sms-outer">
        <?php $options = sms_get_option(REGISTERED_OPTIONS);
        foreach($options as $name=>$ops) : ?>
            <?php if ($ops['active']) : ?>
                <?php if ($ops['display']) :
                    do_action($ops['display']);
                else : ?>
                    <span class="<?php echo (sms_get_option('icon_only') ? "sms-platform-icon-only" : "sms-platform");?>"
                       onclick="<?php if (isset($ops['onclick'])) {echo $ops['onclick'];}?>"
                       id="<?php echo sms_clean_for_id($name);?>"
                        <?php echo sms_determine_href_tag($ops);
                        if (isset($ops['tag_extras'])) {echo " " . $ops['tag_extras'];}?>
                    >
                        <?php sms_the_icon($ops['icon'], $ops['is_icon_url']); ?>
                        <a><?php echo (sms_get_option('icon_only') ? "" : ucwords($name)); ?></a>
                    </span>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php
}

// cleans text so it can be used for an id in html
function sms_clean_for_id($text) {
    return preg_replace("/[^-a-zA-Z0-9_]/", "", $text);
}

// HOOKS FOR DEALING WITH META TAGS
function sms_default_the_image() {
    $featured_id = get_post_thumbnail_id();
    if ($featured_id !== "")
        echo wp_get_attachment_image_src($featured_id, 'full')[0];
    else
        echo "";
}

function sms_default_the_description() {
    global $post;

    if ($post) {
        if ($post->post_excerpt) {
            $str = $post->post_excerpt;
            $str = strlen($str) > 200 ? substr($str, 0, 197)."..." : $str;
            echo trim($str);
        } elseif ($post->post_content) {
            $str = addslashes(wp_strip_all_tags(strip_shortcodes($post->post_content))); // clean up content
            $str = strlen($str) > 200 ? substr($str, 0, 197)."..." : $str;
            echo trim($str);
        } else
            echo "";
    } else
        echo "";
}