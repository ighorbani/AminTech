<Footer class="theme-footer <?php echo is_front_page() ? 'home-footer' : ''; ?>">
    <div class="breadcrumb-call-flex">
        <div class="background-color"></div>
        <div class="breadcrumb">
            <span class="title">شما در اینجا هستید:</span>
            <a class="item" href="<?php echo esc_url(home_url()); ?>">خانه</a>
            <?php

            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                echo '<a class="item" href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
            }
            ?>
            <a class="item" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a>
        </div>

        <div class="call">
            <div class="icon-title-flex">
                <?php echo get_svg('head-set'); ?>
                <span>با کارشناسان ما در ارتباط باشید:</span>
                <span class="short-text">ارتباط با ما:</span>
            </div>

            <a href="<?php the_field('contact_phone_link', 'option'); ?>"
                class="phone-number"><?php the_field('contact_phone', 'option'); ?>
            </a>
        </div>
    </div>

    <div class="features">
        <?php $footer_top_items = get_field('footer_top_items', 'option'); ?>
        <?php if ($footer_top_items): ?>
            <?php foreach ($footer_top_items as $item): ?>
                <div class="feature">
                    <div class="icon">
                        <img src="<?php echo $item['icon']; ?>">
                    </div>
                    <div class="content">
                        <strong><?php echo esc_html($item['title']); ?></strong>
                        <p><?php echo esc_html($item['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="main-section">
        <section class="links-trust-flx">
            <div class="trust-info">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hr-logo-white.png"
                    class="trust-hr-logo" />
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hr-logo-white.png"
                    class="trust-square-logo" />
                <div class="description"><?php echo nl2br(get_field('footer_description', 'option')); ?></div>
                <div class="contact-items">

                    <?php $footer_contacts = get_field('footer_contacts', 'option'); ?>
                    <?php foreach ($footer_contacts as $contact): ?>
                        <a href="<?php echo $contact['link']; ?>" class="contact-item">
                            <span class="icon"><?php echo get_svg($contact['icon']); ?></span>
                            <div class="content">
                                <strong><?php echo $contact['title']; ?></strong>
                                <p><?php echo $contact['value']; ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="links-column">
                <?php $footer_links = get_field('footer_links', 'option'); ?>
                <?php if ($footer_links): ?>
                    <?php foreach ($footer_links as $link_group): ?>

                        <div class="link-column">
                            <div class="column-header">
                                <h2><?php echo esc_html($link_group['group_title']); ?></h2>
                                <?php echo get_svg('chevron'); ?>
                            </div>
                            <nav class="links">
                                <?php foreach ($link_group['links'] as $link): ?>
                                    <a href="<?php echo esc_url($link['link']); ?>">
                                        <span class="text"><?php echo esc_html($link['title']); ?></span>
                                        <!-- <?php echo get_svg('chevron'); ?> -->
                                    </a>
                                <?php endforeach; ?>
                            </nav>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="footer-tags-section">
            <div class="tags">
                <?php $footer_tags = get_field('footer_tags', 'option'); ?>
                <?php if ($footer_tags): ?>
                    <?php foreach ($footer_tags as $tag): ?>
                        <a href="<?php echo esc_url($tag['tag_link']); ?>"><?php echo esc_html($tag['tag_title']); ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="show-more">
                <span class="text">نمایش همه</span>
                <span class="icon"><?php echo get_svg('chevron'); ?></span>
            </div>
        </section>

        <div class="hr-line"></div>

        <section class="copyright-social-flex">
            <p><?php echo nl2br(get_field('copyright_text', 'option')); ?></p>
            <div class="footer-socials">
                <a href="https://t.me/TrustImmigration"><?php echo get_svg('telegram'); ?></a>
                <a href="https://t.me/TrustImmigration"><?php echo get_svg('instagram'); ?></a>
            </div>
        </section>
    </div>
</Footer>
</body>
<?php wp_footer(); ?>

</html>