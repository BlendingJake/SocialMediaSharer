<?php
function sms_menu() {
    sms_save_fields();
    ?>
    <div class="sms-menu-outer>">
        <header class="sms-menu-header">
            <h1><?php echo sms_get_title(); ?></h1>
        </header>

        <div class="sms-fields-outer">
            <?php sms_display_fields(); ?>
        </div>
    </div>
    <?php
}

function sms_save_fields() {
    $options = sms_get_option(REGISTERED_OPTIONS, []);

    foreach ($options as $option) {
        if (has_action($option['save_panel']))
            do_action($option['save_panel']);
    }
}

function sms_display_fields() {
    $options = sms_get_option(REGISTERED_OPTIONS, []);

    foreach ($options as $option) {
        ?>
        <div class="sms-option-outer">
            <header class="sms-option-header">
                <a class="sms-icon">
                    <?php if ($option['is_icon_url'] == true) : ?>
                        <img src="<?php echo $option['icon']; ?>">
                    <?php else: ?>
                        <i class="fa fa-<?php echo $option['icon']; ?>" aria-hidden="true"></i>
                    <?php endif; ?>
                </a>
            </header>

            <div class="sms-option-fields">
                <?php if (has_action($option['display_panel'])) {do_action($option['display_panel']);} ?>
            </div>
        </div>
        <?php
    }
}