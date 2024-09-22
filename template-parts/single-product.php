<?php get_header(); ?>
<?php while (have_posts()) {
    the_post(); ?>

    <div class="single-page">

        <div class="header-flx">
            <div class="description">
                <h1><?php the_title(); ?></h1>

                <div class="top-features-flx">

                    <?php $workStatus = get_field('work-status'); ?>
                    <?php if (isset($workStatus)): ?>
                        <div class="top-feature work-status <?php echo $workStatus !== 1 ? 'old' : ''; ?>">
                            <div class="icon"><span></span></div>
                            <span>وضعیت: <?php echo get_field('work-status') ? 'نو' : 'کار کرده'; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php $company = get_field('company'); ?>
                    <?php if (isset($company)): ?>
                        <div class="top-feature company">
                            <div class="icon"><span></span></div>
                            <span>شرکت: <?php the_field('company'); ?> | ساخت: <?php the_field('year'); ?></span>
                        </div>
                    <?php endif; ?>

                </div>

                <h2>کاربردها</h2>
                <p><?php the_field('applications'); ?></p>
                <h2>مشخصات</h2>
                <div class="features">
                    <?php if (have_rows('feature')) {
                        while (have_rows('feature')) {
                            the_row();
                            echo '<div class="feature">';
                            echo '<div class="title"><div class="icon"></div>' . get_sub_field('title') . '</div>';
                            echo '<p>' . get_sub_field('value') . '</p>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="image-container">
                <?php echo has_post_thumbnail() ? '<img src="' . get_the_post_thumbnail_url() . '" alt="" class="product-image">' : ''; ?>
            </div>
        </div>

        <div class="description">
            <h2>توضیحات</h2>
            <div class="hr-line"></div>
            <?php the_content(); ?>
            <?php if (get_field('notice-message')) { ?>
                <div class="notice-box">
                    <div class="title">
                        <div class="icon"></div>
                        توجه
                    </div>
                    <p><?php the_field('notice-message'); ?></p>
                </div>
            <?php } ?>
        </div>

        <div class="features-section">
            <div class="feature">
                <?php echo get_svg(''); ?>
                <p>امکان بازگشت کالا</p>
                <span>تا 4 روز</span>
            </div>
        </div>

    <?php } ?>


    <div class="suggestions-section">

        <h2>محصولات مرتبط</h2>
        <div class="suggestions">

            <?php
            $suggestions = new WP_Query(['post_type' => 'product', 'post_status' => 'publish', 'order' => 'DESC', 'posts_per_page' => 3]);
            while ($suggestions->have_posts()) {

                $suggestions->the_post();
                $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                $work_status = get_field('work-status') ? 'نو' : 'کار کرده';

                echo '<a href="' . get_permalink() . '" class="suggest-engine-item">';
                echo '<div class="image-content-flx">';
                echo '<div class="image-container">';
                echo '<img src="' . $img_url . '" class="image" />';
                echo '</div>';
                echo '<div class="content">';
                echo '<h3>' . get_the_title() . '</h3>';
                echo '<p>' . mb_strimwidth(get_the_excerpt(), 0, 67, '...') . '</p>';
                echo '</div>';
                echo '</div>';

                echo '<div class="button-flx">';
                echo '<div class="feature">';
                echo '<div class="icon"><span></span></div><span>' . esc_html($work_status) . '</span>';
                echo '</div>';
                echo '<div class="view-button"><span>بررسی محصول</span>';
                echo '<div class="icon"></div>';
                echo '</div>';
                echo '</div>';
                echo '</a>';
            }
            ?>

        </div>
    </div>

</div>
<?php get_footer(); ?>