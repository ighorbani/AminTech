<?php

// Define global variables
define('THEME_PATH', get_template_directory());
define('CART_LINK', get_field('cart_link', 'option'));
define('ACCOUNT_LINK', get_field('account_link', 'option'));
define('CHECKOUT_LINK', get_field('checkout_link', 'option'));
define('BRANDS', [
    "Apple" => "اپل",
    "Apacer" => "اپیسر",
    "HP" => "اچ پی",
    "HPE" => "اچ پی ای",
    "Acer" => "ایسر",
    "ASUS" => "ایسوس",
    "Intel" => "اینتل",
    "Dell" => "دل",
    "Sony" => "سونی",
    "Xiaomi" => "شیائومی",
    "Fujitsu" => "فوجیتسو",
    "Lenovo" => "لنوو",
    "Vtech" => "وی تک",
    "Wiwu" => "ویوو",
    "Huawei" => "هوآوی"
]);

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


// Set sender email
add_action('phpmailer_init', 'custom_smtp_settings');
function custom_smtp_settings($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host = 'mail.amin-rayaneh.ir';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 465;
    $phpmailer->Username = 'contact@amin-rayaneh.ir';
    $phpmailer->Password = '@=ILA7=T&&9N';
    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->From = 'contact@amin-rayaneh.ir';
    $phpmailer->FromName = 'امین رایانه';
    $phpmailer->CharSet = 'UTF-8';
}

// $to = 'imanwpexpert@gmail.com';
// $subject = 'Test Email';
// $body = 'This is a test email sent from WordPress.';
// $headers = array('Content-Type: text/html; charset=UTF-8');

// // ارسال ایمیل
// wp_mail($to, $subject, $body, $headers);