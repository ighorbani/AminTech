<?php

function search_products()
{
    $search_term = sanitize_text_field($_POST['term']);
    $args = array(
        'post_type' => 'product',
        's' => $search_term,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get the URL of the full-sized image (you can specify a custom image size)
            $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'small')[0];

            echo '<a href="' . get_permalink() . '">' . get_the_title() . '<img src="' . $image_url . '" alt="Thumbnail"></a>';
        }
        wp_reset_postdata();
    } else {
        echo 'محصولی یافت نشد.';
    }

    wp_die();
}

add_action('wp_ajax_search_products', 'search_products');
add_action('wp_ajax_nopriv_search_products', 'search_products');

