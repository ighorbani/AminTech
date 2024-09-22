<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />
    <?php wp_head(); ?>
</head>

<body>
    <div class="wide-header">
        <div class="logo-and-search">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hr-logo.png">
            </a>

            <div class="search">
                <input type="text" id="search-input" placeholder="جستجوی محصولات" />
                <div class="search-suggestions" id="search-suggestions"></div>
                <?php echo get_svg('search'); ?>
            </div>
        </div>

        <div class="menu-and-call">
            <a class="contact-button" href="tel:<?php the_field('contact_phone_link', 'option'); ?>">
                <?php echo get_svg('phone'); ?>
                <span><?php the_field('contact_phone', 'option'); ?></span>
            </a>

            <div class="menu">
                <a href="<?php echo ACCOUNT_LINK; ?>" class="login">ورود / ثبت نام</a>
                <a href="<?php echo CART_LINK; ?>" class="shopping-cart">
                    <span class="count">
                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                    </span>
                    <?php echo get_svg('shopping-cart'); ?>
                </a>
            </div>
        </div>
    </div>

    <div class="hr-line"></div>

    <div class="wide-head-menu">
        <?php $main_menu = get_field('main_menu', 'option'); ?>
        <?php foreach ($main_menu as $parent): ?>
            <div class="menu-item">
                <?php echo $parent['title']; ?>

                <div class="submenu">
                    <?php foreach ($parent['submenu'] as $item): ?>
                        <a href="<?php echo $item['link']; ?>" class="submenu-item"><?php echo $item['item']; ?></a>
                    <?php endforeach; ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <div id="backdrop"></div>