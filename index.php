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
                    ?>

                    <a href="<?php the_permalink(); ?>" class="product-item swiper-slide">
                        <!-- <div class="discount-time">97:32:41</div> -->
                        <div class="image" style="background-image: url('<?php echo $product_image[0]; ?>');"></div>
                        <h3><?php echo get_the_title(); ?></h3>
                        <span class="count"><?php echo ($stock_quantity ? $stock_quantity . ' عدد' : 'ناموجود'); ?></span>
                        <!-- <div class="discount-flex">
                            <span class="discount-percent">4%</span>
                            <span class="previous-price">768, 0000 تومان</span>
                        </div> -->
                        <span class="price"><?php echo $product_price; ?></span>
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
                ?>

                <a href="<?php the_permalink(); ?>" class="product-item swiper-slide">
                    <div class="image" style="background-image: url('<?php echo $product_image[0]; ?>');"></div>
                    <h3><?php echo get_the_title(); ?></h3>
                    <span class="count"><?php echo ($stock_quantity ? $stock_quantity . ' عدد' : 'ناموجود'); ?></span>
                    <span class="price"><?php echo $product_price; ?></span>
                </a>
                <?php
            endwhile;
        endif;
        wp_reset_postdata();
        ?>
    </div>
</div>


<?php get_footer(); ?>