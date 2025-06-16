<?php
/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BloglyPress
 */

?>
    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="footer-widgets-container">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-1'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-2'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-3'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="site-info">
            <div class="container">
                <div class="site-info-container">
                    <div class="site-info-left">
                        <?php if (has_nav_menu('footer')) : ?>
                            <nav class="footer-navigation">
                                <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'footer',
                                        'menu_class'     => 'footer-menu',
                                        'depth'          => 1,
                                    )
                                );
                                ?>
                            </nav>
                        <?php endif; ?>
                        
                        <div class="copyright">
                            <?php
                            $copyright_text = get_theme_mod('bloglypress_copyright_text', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');
                            echo wp_kses_post(do_shortcode($copyright_text));
                            ?>
                        </div>
                    </div>
                    
                    <div class="site-info-right">
                        <?php if (has_nav_menu('social')) : ?>
                            <nav class="social-navigation">
                                <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'social',
                                        'menu_class'     => 'social-links-menu',
                                        'depth'          => 1,
                                        'link_before'    => '<span class="screen-reader-text">',
                                        'link_after'     => '</span>',
                                    )
                                );
                                ?>
                            </nav>
                        <?php endif; ?>
                        
                        <?php if (get_theme_mod('bloglypress_show_powered_by', true)) : ?>
                            <div class="powered-by">
                                <?php
                                printf(
                                    /* translators: %s: Theme name. */
                                    esc_html__('Powered by %1$s | Theme: %2$s by %3$s', 'bloglypress'),
                                    '<a href="https://wordpress.org/">WordPress</a>',
                                    'BloglyPress',
                                    '<a href="https://www.pixelsmedialab.com/">Pixels Media Lab</a>'
                                );
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (get_theme_mod('bloglypress_back_to_top', true)) : ?>
            <button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'bloglypress'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
            </button>
        <?php endif; ?>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
