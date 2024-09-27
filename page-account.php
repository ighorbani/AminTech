<?php
/**
 * Template Name: Account
 * Template Post Type: page
 */
get_header(); ?>

<?php while (have_posts()) {
    the_post(); ?>

    <div class="account-page">
        <h2 class="regular-title right"><?php the_title(); ?></h2>
        <?php the_content(); ?>
    </div>

<?php } ?>
<?php get_footer(); ?>