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
    }
    ?>
    <div class="account-page">
        <h2 class="regular-title right"><?php the_title(); ?></h2>

        <div class="person-info-box">
            <a class="edit-account"
                href="<?php echo home_url('/my-account/edit-account/'); ?>"><?php echo get_svg('edit'); ?></a>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/account/01.png" />
            <div class="texts">
                <p><?php echo $user_name; ?><span> خوش آمدید!</span></p>
                <blockquote><?php echo $user_name; ?></blockquote>
            </div>
        </div>

        <?php the_content(); ?>
    </div>

<?php } ?>
<?php get_footer(); ?>