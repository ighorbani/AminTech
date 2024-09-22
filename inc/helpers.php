<?php

// Include plugin functions.
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

/**
 * Get a file version based on its modification time as a string.
 *
 * @param string $src File path.
 * @return string
 */
function get_file_ver($src = '')
{
    return date("ymd-Gis", filemtime($src));
}

/**
 * Is Advanced Custom Fields plugin active?
 */
function is_acf_active($pro_version = true)
{
    return $pro_version ? is_plugin_active('advanced-custom-fields-pro/acf.php') : is_plugin_active('advanced-custom-fields/acf.php');
}

/**
 * Is Ninja Forms plugin active?
 *
 * @return boolean
 */
function is_nf_active()
{
    return is_plugin_active('ninja-forms/ninja-forms.php');
}

/**
 * Show a message to inform about plugin inactivation.
 *
 * @param string $message Message to show.
 */
function show_plugin_warning($message)
{
    echo '<div class="plugin-required-warning">' . $message . '</div>';
}


// For Convert Persian To Latin Numbers
function convertNumbers($srting, $toPersian = false)
{
    $en_num = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $fa_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    if ($toPersian) return str_replace($en_num, $fa_num, $srting);
    else return str_replace($fa_num, $en_num, $srting);
}