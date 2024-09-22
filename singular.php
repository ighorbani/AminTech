<?php get_header(); ?>
<?php while (have_posts()) {
    the_post(); ?>

    <div class="page">
        <h2 class="regular-title"><?php the_title(); ?></h2>
        <p class="regular-subtitle"><?php the_field('subtitle'); ?></p>
        <div class="content"><?php the_content(); ?></div>
    </div>

<?php } ?>
<?php get_footer(); ?>