<?php

// Define global variables
define('THEME_PATH', get_template_directory());

define('CART_LINK', get_field('cart_link', 'option'));
define('ACCOUNT_LINK', get_field('account_link', 'option'));
define('CHECKOUT_LINK', get_field('checkout_link', 'option'));


/**
 * Theme setup.
 */
function theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-style');

    register_nav_menus(array(
        'mainMenu' => 'منو اصلی'
    ));
}

add_action('after_setup_theme', 'theme_setup');

/**
 * Remove size attribute from admin.
 *
 * @param string $html HTML source.
 * @return string
 */
function remove_size_attribute($html)
{
    return preg_replace('/(width|height)="\d*"\s/', "", $html);
}

add_filter('post_thumbnail_html', 'remove_size_attribute');
add_filter('image_send_to_editor', 'remove_size_attribute');