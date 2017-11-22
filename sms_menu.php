<?php
function sms_menu() {
    sms_save_fields();
    ?>
    <form class="sms-form" action="" method="POST">
        <div class="sms-menu-outer>">
            <header class="sms-menu-header">
                <h1><?php echo sms_get_name(); ?></h1>
            </header>

            <div class="sms-fields-outer">
                <?php sms_display_fields(); ?>
            </div>
        </div>

        <button type="submit">Save Changes</button>
    </form>
    <?php
}

function sms_save_fields() {
    // general settings

    // registered options saving
    $options = sms_get_option(REGISTERED_OPTIONS, []);

    foreach ($options as $option) {
        if (has_action($option['save_panel']))
            do_action($option['save_panel']);
    }
}

function sms_display_fields() {
    $options = sms_get_option(REGISTERED_OPTIONS, []);

    foreach ($options as $name=>$option) : ?>
        <div class="sms-option-outer">
            <header class="sms-option-header">
                <a class="sms-icon">
                    <?php sms_the_icon($option['icon'], $option['is_icon_url']); ?>
                </a>
                <a class="sms-option-name"><?php echo ucwords($name); ?></a>
            </header>

            <?php if (has_action($option['display_panel'])) : ?>
                <div class="sms-option-fields">
                    <?php do_action($option['display_panel']); ?>
                </div>
            <?php endif; ?>
        </div>

    <?php endforeach;
}