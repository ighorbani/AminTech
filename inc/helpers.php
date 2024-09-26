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

function product_breadcrumb($terms)
{
    // اولین دسته‌بندی محصول را دریافت کن
    $category = array_shift($terms); 

    // بررسی اگر دسته والد دارد
    if ($category->parent != 0) {
        $parent = get_term($category->parent, 'product_cat'); // دریافت دسته والد
        echo '<a href="' . get_term_link($parent->term_id, 'product_cat') . '">' . $parent->name . '</a> / ';
    }

    // دسته محصول فعلی
    echo '<a href="' . get_term_link($category->term_id, 'product_cat') . '">' . $category->name . '</a>';

}