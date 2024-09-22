<?php

// Add helper functions.
require_once('helpers.php');

// Add ACF option pages.
if (is_acf_active()) {
    acf_add_options_page();
    acf_add_options_sub_page('Theme Settings');
}


// Modify the admin menu
add_action('admin_init', 'customize_acf_menu_label');

function customize_acf_menu_label()
{
    global $menu;
    foreach ($menu as $key => $item) {
        if (isset($item[2]) && $item[2] === 'acf-options-theme-settings') {
            $menu[$key][0] = 'تنظیمات قالب';
            $menu[$key][6] = 'dashicons-edit';
            break;
        }
    }
}