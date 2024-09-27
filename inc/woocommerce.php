<?php

add_filter('woocommerce_account_menu_items', 'remove_downloads_account_menu_item');
function remove_downloads_account_menu_item($items)
{
    unset($items['downloads']);
    return $items;
}

function woocommerce_ajax_add_to_cart()
{
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    if (!$product_id) {
        wp_send_json_error();
    }
    $added = WC()->cart->add_to_cart($product_id);

    if ($added) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
    wp_die();
}

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
