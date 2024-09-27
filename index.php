<?php
/**
 * Template Name: Home
 * Template Post Type: page
 */

get_header(); ?>

<div class="banner-suggestion-section">
    <div class="slider one-item-slider swiper-container">

        <div class="swiper-wrapper">
            <?php $ad_banners = get_field('ad_banners'); ?>
            <?php foreach ($ad_banners as $banner): ?>
                <a href="<?php echo $banner['link']; ?>" class="swiper-slide">
                    <img class="banner" src="<?php echo $banner['banner']; ?>">
                </a>
            <?php endforeach; ?>
        </div>

        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"><?php echo get_svg('chevron'); ?></div>
        <div class="swiper-button-prev"><?php echo get_svg('chevron'); ?></div>
    </div>
</div>

<div class="products-categories-section">
    <div class="slider">
        <?php $product_categories = get_field('product_categories', 'option'); ?>
        <?php foreach ($product_categories as $category): ?>
            <a class="category-item" href="<?php echo $category['link']; ?>">
                <div class="image">
                    <span style="background-image: url('<?php echo $category['image']; ?>');"></span>
                </div>
                <span class="name"><?php echo $category['title']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="slider-products-section">
    <div class="title-flex">
        <h2 class="regular-title right">امین تایم</h2>
        <a href="" class="more-link"><span>لیست محصولات</span> <?php echo get_svg('chevron'); ?></a>
    </div>

    <div class="items-slider tree-items-slider swiper-container">
        <div class="swiper-wrapper">
            <?php
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 10,
            );

            $loop = new WP_Query($args);
            if ($loop->have_posts()):
                while ($loop->have_posts()):
                    $loop->the_post();
                    global $product;

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
                    <?php
                endwhile;
                ?>
            </div>

            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"><?php echo get_svg('chevron'); ?></div>
            <div class="swiper-button-prev"><?php echo get_svg('chevron'); ?></div>
            <?php
            endif;
            wp_reset_postdata();
            ?>
    </div>
</div>

<div class="full-width-ad">
    <a href="<?php echo get_field('single_ad_banner_link'); ?>">
        <img src="<?php echo get_field('single_ad_banner'); ?>">
    </a>
</div>

<div class="page-products-section">
    <div class="title-flex">
        <h2 class="regular-title right">محصولات ما</h2>
        <a href="" class="more-link"><span>لیست محصولات</span> <?php echo get_svg('chevron'); ?></a>
    </div>

    <div class="products-container">
        <?php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 9,
        );

        $loop = new WP_Query($args);
        if ($loop->have_posts()):
            while ($loop->have_posts()):
                $loop->the_post();
                global $product;
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
                <?php
            endwhile;
        endif;
        wp_reset_postdata();
        ?>
    </div>
</div>


<?php get_footer(); ?>