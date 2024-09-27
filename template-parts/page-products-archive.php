<?php
/**
 * Template Name: Products Archive
 * Template Post Type: page
 */

// Define filter values
$brand_filter = isset($_GET['brand']) ? (array) $_GET['brand'] : array();
$avalability_filter = isset($_GET['availability_status']) ? $_GET['availability_status'] : '';
$avalability_filter_checked = ($avalability_filter === 'only-available') ? 'checked' : '';

$price_range = isset($_GET['price_range']) ? intval($_GET['price_range']) : 10000;
$min_price = $price_range * 0.25;
$max_price = $price_range * 4;

get_header(); ?>
<div class="archive-page">

    <div class="general-filter-form">
        <div class="filter-title-flx">
            <h3>فیلتر محصولات</h3>
            <?php echo get_svg('filter'); ?>
        </div>

        <form method="get">
            <label>
                <blockquote>دسته‌بندی محصولات</blockquote>
                <?php echo get_svg('filter'); ?>
            </label>
            <?php
            // Get all product categories
            $categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
            ));

            // Get selected product categories from the URL
            $product_categories_filter = isset($_GET['product_categories']) ? $_GET['product_categories'] : array();

            // Check if any categories exist
            if (!empty($categories) && !is_wp_error($categories)) {
                foreach ($categories as $category) {
                    $checked = in_array($category->slug, (array) $product_categories_filter, true) ? 'checked' : ''; ?>
                    <span class="checkbox-span">
                        <input type="checkbox" name="product_categories[]" value="<?php echo esc_attr($category->slug); ?>"
                            <?php echo $checked; ?>>
                        <?php echo esc_html($category->name); ?>
                    </span>
                <?php }
            }
            ?>

            <label for="brand">
                <blockquote>انتخاب برند</blockquote>
                <?php echo get_svg('filter'); ?>
            </label>
            <?php

            // Display checkboxes for brands
            foreach (BRANDS as $key => $value):
                $checked = in_array($key, $brand_filter) ? 'checked' : ''; ?>
                <span class="checkbox-span">
                    <input type="checkbox" name="brand[]" value="<?php echo esc_attr($key); ?>" <?php echo $checked; ?>>
                    <?php echo esc_html($value); ?>
                </span>
            <?php endforeach; ?>

            <label for="availability_status">
                <blockquote>وضعیت موجودی</blockquote>
                <?php echo get_svg('filter'); ?>
            </label>
            <span class="checkbox-span">
                <input type="checkbox" name="availability_status" value="only-available" <?php echo $avalability_filter_checked; ?>>
                فقط کالاهای موجود
            </span>

            <!-- <label for="price_range">
                <blockquote>محدوده قیمت</blockquote>
                <input type="range" name="price_range" min="10000" max="100000000" step="1000"
                    value="<?php echo isset($_GET['price_range']) ? esc_attr($_GET['price_range']) : 10000; ?>"
                    oninput="updatePriceRange(this.value)">
                <div>
                    <span>کمترین قیمت: </span><output id="min_price_output">10000</output> تومان
                    <span>بیشترین قیمت: </span><output id="max_price_output">100000000</output> تومان
                </div>
            </label> -->

            <input type="submit" value="فیلتر کن!">
        </form>
    </div>

    <div class="content">
        <div class="items-flx">
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'order' => 'DESC',
                'posts_per_page' => 6,
                'paged' => $paged,
                'meta_query' => array(
                    'relation' => 'AND',
                ),
                'tax_query' => array(),
            );

            // Filter by Product Categories
            if (!empty($product_categories_filter)) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $product_categories_filter,
                    'operator' => 'IN',
                );
            }

            // Price filter
            // $args['meta_query'][] = array(
            //     'key' => '_price',
            //     'value' => array($min_price, $max_price),
            //     'type' => 'NUMERIC',
            //     'compare' => 'BETWEEN',
            // );
            
            // Filter by brand if selected
            if (!empty($brand_filter)) {
                $args['meta_query'][] = array(
                    'key' => 'brand',
                    'value' => $brand_filter,
                    'compare' => 'IN',
                );
            }

            // Filter by Availability Status
            if ($avalability_filter) {
                $args['meta_query'][] = array(
                    'key' => '_stock_status',
                    'value' => 'instock',
                    'compare' => '='
                );
            }

            $products = new WP_Query($args);
            while ($products->have_posts()):
                $products->the_post();

                $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'single-post-thumbnail');
                $stock_quantity = $product->get_stock_quantity();
                $product_price = strip_tags(wc_price($product->get_price()));

                $regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();
                ?>

                <a href="<?php the_permalink(); ?>" class="product-item swiper-slide">
                    <?php if ($sale_price && $regular_price > $sale_price): ?>
                        <div class="discount-time"><?php echo get_svg('discount'); ?></div>
                    <?php endif; ?>

                    <div class="image" style="background-image: url('<?php echo $product_image[0]; ?>');"></div>
                    <?php $main_features = get_field('main_features'); ?>
                    <?php if ($main_features): ?>
                        <div class="features-summary">
                            <?php foreach ($main_features as $main_feature): ?>
                                <div><img src="<?php echo $main_feature['icon']; ?>" />
                                    <p><?php echo $main_feature['title']; ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h3><?php echo get_the_title(); ?></h3>
                    <div class="product-info">
                        <span class="count <?php echo $stock_quantity ? 'available' : ''; ?>">
                            <?php echo ($stock_quantity ? $stock_quantity . ' عدد موجود' : 'ناموجود'); ?>
                        </span>

                        <?php if ($regular_price && $sale_price):
                            $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                            ?>
                            <div class="discount-flex">
                                <span class="discount-percent">
                                    <span><?php echo $discount_percentage; ?>%</span>
                                </span>
                                <span class="previous-price"><?php echo $product_price; ?></span>
                            </div>
                            <span class="price"><?php echo strip_tags(wc_price($sale_price)); ?></span>
                        <?php else: ?>
                            <span class="price"><?php echo $product_price; ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endwhile;
            ?>
        </div>

        <?php if ($products->max_num_pages > 1) { ?>
            <div class='navigation'>
                <h6>صفحه بندی محصولات</h6>
                <div class='navigation-flex'>

                    <?php
                    $big = PHP_INT_MAX;
                    echo paginate_links(array(
                        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
                        'format' => '?paged=%#%',
                        'current' => max(1, get_query_var('paged')),
                        'total' => $products->max_num_pages,
                        'show_all' => False,
                        'end_size' => 1,
                        'mid_size' => 0,
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;'
                    ));
                    ?>

                </div>
            </div>
        <?php } ?>
        <?php wp_reset_postdata(); ?>
    </div>

</div>
<?php get_footer(); ?>