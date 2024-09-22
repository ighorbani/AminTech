<?php
/**
 * Template Name: Products archive template
 * Template Post Type: post, page
 */

// Define filter values
$company_filter = isset($_GET['company']) ? $_GET['company'] : '';
$work_status_filter = isset($_GET['work_status']) ? $_GET['work_status'] : '';

get_header(); ?>
    <div class="archive-page">

        <div class="general-filter-form">
            <div class="filter-title-flx">
                <h3>فیلتر محصولات</h3>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/filter.png"/>
            </div>

            <form method="get">

                <label>
                    <blockquote>دسته‌بندی محصولات</blockquote>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/category.png"/></label>
                <?php
                // Get all product categories
                $categories = get_terms(array(
                    'taxonomy' => 'products-category',
                    'hide_empty' => false,
                ));

                // Get selected product categories from the URL
                $product_categories_filter = isset($_GET['product_categories']) ? $_GET['product_categories'] : array();

                // Check if any categories exist
                if (!empty($categories) && !is_wp_error($categories)) {
                    foreach ($categories as $category) {
                        $checked = in_array($category->slug, (array)$product_categories_filter, true) ? 'checked' : '';
                        echo '<span class="checkbox-span">';
                        echo '<input type="checkbox" name="product_categories[]" value="' . esc_attr($category->slug) . '" ' . $checked . '>';
                        echo esc_html($category->name);
                        echo '</span>';
                    }
                }
                ?>

                <label for="company">
                    <blockquote>انتخاب برند</blockquote>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/brand.png"/></label>
                <?php
                // Get all brand options
                $brands = array('John Deer', 'Iman Black', 'Essi Black');

                // Display checkboxes for brands
                foreach ($brands as $brand) {
                    $checked = $company_filter === $brand ? 'checked' : '';
                    echo '<span class="checkbox-span">';
                    echo '<input type="checkbox" name="company" value="' . esc_attr($brand) . '" ' . $checked . '>';
                    echo esc_html($brand);
                    echo '</span>';
                }
                ?>

                <label for="work_status">
                    <blockquote>وضعیت کارکرد</blockquote>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/power-icon2.png"/></label>
                <?php
                // Get all work status options
                $work_statuses = ['new' => 'نو', 'old' => 'کارکرده'];

                // Display checkboxes for work status
                foreach ($work_statuses as $status => $label) {
                    $checked = $work_status_filter === $status ? 'checked' : '';
                    echo '<span class="checkbox-span">';
                    echo '<input type="checkbox" name="work_status" value="' . esc_attr($status) . '" ' . $checked . '>';
                    echo esc_html($label);
                    echo '</span>';
                }
                ?>

                <input type="submit" value="فیلتر کن!">
            </form>
        </div>

        <div class="content">
            <h2 class="regular-title"><?php the_title(); ?></h2>
            <p class="regular-subtitle"><?php the_field('subtitle'); ?></p>

            <div class="items-flx">
                <?php
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'order' => 'DESC',
                    'posts_per_page' => 6,
                    'paged' => $paged,
                    'meta_query' => array(),
                    'tax_query' => array(),
                );

                // Filter by Product Categories
                if (!empty($product_categories_filter)) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'products-category',
                        'field' => 'slug',
                        'terms' => $product_categories_filter,
                        'operator' => 'IN',
                    );
                }

                // Filter by Company
                if (!empty($company_filter)) {
                    $args['meta_query'][] = array(
                        'key' => 'company',
                        'value' => $company_filter,
                        'compare' => '=',
                    );
                }

                // Filter by Work Status
                if (!empty($work_status_filter)) {
                    $args['meta_query'][] = array(
                        'key' => 'work-status',
                        'value' => $work_status_filter == 'new' ? 1 : 0,
                        'compare' => '=',
                    );
                }

                $products = new WP_Query($args);

                while ($products->have_posts()) {
                    $products->the_post();
                    $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                    $work_status = get_field('work-status') ? 'نو' : 'کار کرده';

                    echo '<a href="' . get_permalink() . '" target="_blank" class="motor-item">';
                    echo '<div class="image" style="background-image: url(' . $img_url . ');"></div>';
                    echo '<h3>' . get_the_title() . '</h3>';
                    echo '<p>' . mb_strimwidth(get_the_excerpt(), 0, 67, '...') . '</p>';
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