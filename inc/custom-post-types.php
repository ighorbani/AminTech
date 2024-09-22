<?php

/**
 * Register custom post types.
 */
function theme_register_custom_post_types()
{

}

/**
 * Register custom taxonomies.
 */
function theme_register_custom_taxonomies()
{
}

add_action('init', 'theme_register_custom_taxonomies');
add_action('init', 'theme_register_custom_post_types');
