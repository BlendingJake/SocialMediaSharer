<?php
function sms_menu() {
    ?>
    <div class="wrap">
        <h1>Social Media Sharer</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields("social-media-sharer");
            do_settings_sections("sms-options");
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function sms_menu_fields() {
    add_settings_section("social-media-sharer", "All Settings", null, "sms-options");
}