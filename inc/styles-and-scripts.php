<?php

/**
 * Enqueue theme styles.
 */
function theme_enqueue_styles()
{
    wp_enqueue_style('swiper-bundle', get_template_directory_uri() . '/assets/css/swiper-bundle.min.css', [], get_file_ver(get_template_directory() . '/assets/css/swiper-bundle.min.css'));
    wp_enqueue_style('front', get_template_directory_uri() . '/assets/css/front.css', [], get_file_ver(get_template_directory() . '/assets/css/front.css'));
}

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

/**
 * Enqueue theme scripts.
 */
function theme_enqueue_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', ['jquery'], get_file_ver(get_template_directory() . '/assets/js/swiper-bundle.min.js'), true);
    wp_enqueue_script('front', get_template_directory_uri() . '/assets/js/front.js', ['jquery', 'swiper'], get_file_ver(get_template_directory() . '/assets/js/front.js'), true);

    wp_localize_script('front', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));

}

add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');
