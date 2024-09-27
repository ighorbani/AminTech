<?php
/**
 * Template Name: Account
 * Template Post Type: page
 */
get_header(); ?>

<?php while (have_posts()) {
    the_post(); ?>
    <?php
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $user_name = $current_user->display_name;
        echo 'Welcome, ' . esc_html($user_name) . '!';
    }

    ?>
    <div class="account-page">
        <h2 class="regular-title right"><?php the_title(); ?></h2>
        <?php the_content(); ?>
    </div>

<?php } ?>
<?php get_footer(); ?>