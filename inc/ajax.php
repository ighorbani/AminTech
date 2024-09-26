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

            // بازیابی شیء محصول
            $product = wc_get_product(get_the_ID());

            // گرفتن قیمت محصول
            $product_price = strip_tags(wc_price($product->get_price()));

            // گرفتن آدرس تصویر محصول
            $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'small')[0];

            // نمایش محصول با قیمت
            echo '
                    <a href="' . get_permalink() . '" class="item">
                        <img src="' . $image_url . '" alt="' . get_the_title() . '">
                        <p class="title">' . get_the_title() . '<span class="price">' . $product_price . '</span></p>
                    </a>';
        }
        wp_reset_postdata();
    } else {
        echo 'محصولی یافت نشد.';
    }

    wp_die();
}

add_action('wp_ajax_search_products', 'search_products');
add_action('wp_ajax_nopriv_search_products', 'search_products');