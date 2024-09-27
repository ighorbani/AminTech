<?php

add_filter('woocommerce_account_menu_items', 'remove_downloads_account_menu_item');
function remove_downloads_account_menu_item($items)
{
    unset($items['downloads']);
    return $items;
}