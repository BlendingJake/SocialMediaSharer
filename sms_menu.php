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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // general settings
        sms_update_option('enqueue_font_awesome', isset($_POST['enqueue_font_awesome']));
        sms_update_option('enqueue_jquery', isset($_POST['enqueue_jquery']));
        sms_update_option('icon_only', isset($_POST['icon_only']));

        // registered options saving
        $options = sms_get_option(REGISTERED_OPTIONS, []);

        foreach ($options as $name=>$option) {
            $option['active'] = isset($_POST[sms_clean_for_id($name)]);

            if (has_action($option['save_panel']))
                do_action($option['save_panel']);

            $options[$name] = $option;
        }

        var_dump($options);
        sms_update_option(REGISTERED_OPTIONS, $options);
    }
}

function sms_display_fields() {
    $options = sms_get_option(REGISTERED_OPTIONS, []);?>

    <section class="sms-fields-group">
        <p>
            <input type="checkbox" id="enqueue_font_awesome" name="enqueue_font_awesome"
                <?php echo (sms_get_option('enqueue_font_awesome') ? 'checked' : '');?>>
            <label for="enqueue_font_awesome">Enqueue Font Awesome?</label>
        </p>
        <p>
            <input type="checkbox" id="enqueue_jquery" name="enqueue_jquery"
                <?php echo (sms_get_option('enqueue_jquery') ? 'checked' : '');?>>
            <label for="enqueue_jquery">Enqueue jQuery 2.0.0?</label>
        </p>
        <p>
            <input type="checkbox" id="icon_only" name="icon_only"
                <?php echo (sms_get_option('icon_only') ? 'checked' : '');?>>
            <label for="icon_only">Display Icon Only?</label>
        </p>
    </section>

    <section class="sms-fields-group">
    <?php foreach ($options as $name=>$option) : ?>
        <div class="sms-option-outer">
            <div class="sms-option-header" id="<?php echo sms_clean_for_id($name);?>"
                 onclick="/* fancy version to keep click checkbox from working*/
                         if (!$(event.target).is('input')){
                             $('#<?php echo sms_clean_for_id($name) . "_fields";?>').toggle();
                         }">

                <input type="checkbox"  name="<?php echo sms_clean_for_id($name);?>"
                    <?php echo ($option['active'] ? 'checked' : '');?>>

                <a class="sms-icon">
                    <?php sms_the_icon($option['icon'], $option['is_icon_url']); ?>
                </a>
                <a class="sms-option-name"><?php echo ucwords($name); ?></a>
            </div>

            <?php if (has_action($option['display_panel'])) : ?>
                <div class="sms-option-fields" style="display: none;" id="<?php echo sms_clean_for_id($name) . "_fields";?>">
                    <?php do_action($option['display_panel']); ?>
                </div>
            <?php endif; ?>
        </div>

    <?php endforeach;

    ?></section><?php
}