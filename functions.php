<?php

// Check if the ACF class exists
if (!class_exists('acf')) {
    add_action('admin_notices', function () {
        echo '
         <div class="notice notice-error is-dismissible">
            <p>برای استفاده از قالب وب سایت تراست، لطفا افزونه ACF را فعال نمایید.</p>
        </div>';
    });
    return;
}

// Add helper functions.
require_once('inc/helpers.php');

// Theme setup and initialization.
require_once('inc/initialization.php');

// Custom post types registration.
require_once('inc/custom-post-types.php');

// Register Advanced Custom Fields options pages.
require_once('inc/acf-options-pages.php');

// Styles and scripts management.
require_once('inc/styles-and-scripts.php');

// Add SVGs
require_once('inc/svg-elements.php');

// WooCommerce functionalities
require_once('inc/woocommerce.php');

// Ajax functionalities
require_once('inc/ajax.php');


