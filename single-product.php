<?php get_header(); ?>

<?php while (have_posts()):
    the_post();

    global $product;
    $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'single-post-thumbnail');
    $stock_quantity = $product->get_stock_quantity();
    $product_price = strip_tags(wc_price($product->get_price()));
    ?>

    <div class="single-product">
        <div class="product-header">
            <div class="image-slider">
                <div class="image" style="background-image: url('<?php echo $product_image[0]; ?>');"></div>
                <div class="thumbnails">
                    <div class="item"></div>
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
                        <div class="item">
                            <span class="title">پردازنده</span>
                            <span class="value"><?php the_field('cpu'); ?></span>
                        </div>
                        <div class="item">
                            <span class="title">رم</span>
                            <span class="value"><?php the_field('ram'); ?></span>
                        </div>
                        <div class="item">
                            <span class="title">درایور حافظه</span>
                            <span class="value"><?php the_field('hdd'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="price-container">

                    <?php $product_guarantee_texts = get_field('product_guarantee_texts', 'option'); ?>
                    <?php if ($product_guarantee_texts): ?>
                        <?php foreach ($product_guarantee_texts as $guarantee_text): ?>
                            <div class="purchase-feature">
                                <?php echo get_svg('quality'); ?>
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
                        <a href="" class="buy-button">
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
    </div>

<?php endwhile; ?>
<?php get_footer(); ?>