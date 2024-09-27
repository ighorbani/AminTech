<?php get_header(); ?>

<?php while (have_posts()):
    the_post();

    global $product;
    $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'single-post-thumbnail');
    $gallery_images = $product->get_gallery_image_ids();
    $stock_quantity = $product->get_stock_quantity();
    $product_price = strip_tags(wc_price($product->get_price()));
    ?>

    <div class="single-product">
        <div class="product-header">
            <div class="image-slider">
                <div class="image" style="background-image: url('<?php echo $product_image[0]; ?>');"></div>
                <div class="thumbnails">
                    <?php if ($gallery_images): ?>
                        <?php foreach ($gallery_images as $image_id):
                            $image_url = wp_get_attachment_image_src($image_id, 'thumbnail')[0];
                            ?>
                            <div class="item" style="background-image: url('<?php echo $image_url; ?>');"
                                data-full="<?php echo esc_url(wp_get_attachment_url($image_id)); ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="info-price-flex">
                <div class="product-info">

                    <div class="breadcrumb">
                        <?php
                        // دریافت دسته‌بندی‌های محصول
                        $terms = get_the_terms(get_the_ID(), 'product_cat');

                        if ($terms && !is_wp_error($terms)) {
                            product_breadcrumb($terms);
                        }
                        ?>
                    </div>
                    <h1 class="main-title"><?php the_title(); ?></h1>

                    <?php $in_colors = get_field('in_colors'); ?>
                    <?php if ($in_colors): ?>
                        <div class="colors">
                            <p>رنگ:
                                <?php foreach ($in_colors as $color) {
                                    echo $color['label'] . '، ';
                                }
                                ?>
                            </p>
                            <div class="pallete">
                                <?php foreach ($in_colors as $color): ?>
                                    <div class="color <?php echo $color['value']; ?>"><?php echo get_svg('check'); ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($stock_quantity): ?>
                        <p class="count">
                            وضعیت موجودی: <?php echo ($stock_quantity ? $stock_quantity . ' عدد' : 'ناموجود'); ?>
                        </p>
                    <?php endif; ?>

                    <!-- <div class="guarantee"></div> -->
                    <div class="features-box">
                        <?php $main_features = get_field('main_features'); ?>
                        <?php if ($main_features): ?>
                            <?php foreach ($main_features as $main_feature): ?>
                                <div class="item">
                                    <span class="title"><?php echo $main_feature['title']; ?></span>
                                    <span class="value"><?php echo $main_feature['value']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="price-container">
                    <?php $product_guarantee_texts = get_field('product_guarantee_texts', 'option'); ?>
                    <?php if ($product_guarantee_texts): ?>
                        <?php foreach ($product_guarantee_texts as $guarantee_text): ?>
                            <div class="purchase-feature">
                                <?php echo get_svg($guarantee_text['icon']); ?>
                                <p><?php echo $guarantee_text['title']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="price-box">
                        <div class="flex">
                            <p class="title">قیمت:</p>
                            <p class="price"><?php echo $product_price; ?></p>
                        </div>

                        <span class="update-time">بروزرسانی قیمت: چهارشنبه ۴ مهر ۱۴۰۳</span>
                        <a href="#" class="buy-button" data-product-id="<?php echo $product->get_id(); ?>">
                            <?php echo get_svg('shopping-cart'); ?>
                            <span>افزودن به سبد خرید</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-features-section">
            <h2 class="regular-title right">مشخصات</h2>

            <div class="features">
                <?php $product_features = get_field('product_features'); ?>
                <?php if ($product_features): ?>
                    <?php foreach ($product_features as $features): ?>
                        <div class="item">
                            <span class="title"><?php echo $features['title']; ?></span>
                            <span class="value"><?php echo $features['value']; ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

        <div class="product-description-section">
            <h2 class="regular-title right">توضیحات</h2>
            <?php echo nl2br(get_field('product_description')); ?>
        </div>

        <!-- محصولات مرتبط -->
        <div class="related-products-section">
            <div class="title-flex">
                <h2 class="regular-title right">محصولات مرتبط</h2>
                <a href="" class="more-link"><span>لیست محصولات</span> <?php echo get_svg('chevron'); ?></a>
            </div>


            <div class="items-slider tree-items-slider swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    $related_ids = wc_get_related_products($product->get_id(), 4);

                    if ($related_ids):
                        $args = array(
                            'post_type' => 'product',
                            'posts_per_page' => 4,
                            'post__in' => $related_ids,
                        );
                        $related_query = new WP_Query($args);

                        if ($related_query->have_posts()):
                            while ($related_query->have_posts()):
                                $related_query->the_post();
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
                            <?php endwhile;
                            ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"><?php echo get_svg('chevron'); ?></div>
                        <div class="swiper-button-prev"><?php echo get_svg('chevron'); ?></div>
                        <?php
                        endif;
                        wp_reset_postdata();
                    endif;
                    ?>
            </div>
        </div>
    </div>

<?php endwhile; ?>
<?php get_footer(); ?>