<?php
    $settings->add(new admin_setting_heading(
        "headerconfig",
        get_string("settings:label_header", "block_event_tracker"),
        get_string("settings:label_desc", "block_event_tracker")
    ));

    $settings->add(new admin_setting_configcheckbox(
        "event_tracker/Allow_HTML",
        get_string("settings:label_allow_html", "block_event_tracker"),
        get_string("settings:label_desc_allow_html", "block_event_tracker"),
        "0"
    ));
?>
