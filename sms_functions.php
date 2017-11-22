<?php
// get/update options
function sms_get_option($name, $default_value=[]) {
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

// quickly get name/title
function sms_get_name() {
    return sms_get_option("name", "Title");
}

// saving and getting fields for displaying in panel
function sms_get_option_fields($name, $default_value=[]) {
    $options = sms_get_option(OPTION_FIELDS, []);

    if (key_exists($name, $options))
        return $options[$name];
    else
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

// add default options
function sms_add_default_options() {
    if (!has_action("register_social_media_option"))
        return;

    $default_options = [
        "facebook" => [
            'options' => [
                'icon' => 'facebook-official',
                'display_panel' => 'facebook_display_panel',
                'save_panel' => 'facebook_save_panel',
                'display' => '',
                'is_icon_url' => false,
                'onclick' => ''
            ],
            'fields' => [],
            'priority' => -1,
            'misc' => [],
            'wp_head' => 'sms_facebook_include_api'
        ]
    ];

    foreach ($default_options as $name=>$ops) {
        do_action('register_social_media_option', $name, $ops['options'], $ops['fields'], $ops['priority'],
            $ops['misc']);

        if ($ops['wp_head'])
            add_action('wp_head', $ops['wp_head']);
    }
}

//generate output
function sms_generate_frontend() {
    ?>
    <div class="sms-outer">
        <?php $options = sms_get_option(REGISTERED_OPTIONS);
        foreach($options as $name=>$ops) : ?>
            <?php if ($ops['display']) :
                do_action($ops['display']);
            else : ?>
                <a class="sms-platform" onclick="<?php echo $ops['onclick'];?>">
                    <?php sms_the_icon($ops['icon'], $ops['is_icon_url']); ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php
}