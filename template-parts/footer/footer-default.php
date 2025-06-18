<?php
/**
 * Template part for displaying the default footer
 *
 * @package Hariharan
 */

// Get footer widget count
$footer_widget_count = get_option('hariharan_footer_widget_count', '4');
?>

<footer id="colophon" class="site-footer">
    <?php if ($footer_widget_count > 0) : ?>
    <div class="footer-widgets">
        <div class="container">
            <div class="footer-widgets-inner footer-widgets-columns-<?php echo esc_attr($footer_widget_count); ?>">
                <?php
                for ($i = 1; $i <= $footer_widget_count; $i++) {
                    if (is_active_sidebar('footer-' . $i)) {
                        echo '<div class="footer-widget footer-widget-' . esc_attr($i) . '">';
                        dynamic_sidebar('footer-' . $i);
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="site-info">
        <div class="container">
            <div class="copyright">
                <?php
                $copyright = get_option('hariharan_footer_copyright', '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');
                $copyright = str_replace(
                    array('{year}', '{site_name}'),
                    array(date('Y'), get_bloginfo('name')),
                    $copyright
                );
                
                echo wp_kses_post($copyright);
                ?>
            </div>
            
            <?php
            if (has_nav_menu('footer')) {
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'depth'          => 1,
                        'container'      => false,
                        'menu_class'     => 'footer-menu',
                        'fallback_cb'    => '__return_false',
                    )
                );
            }
            ?>
        </div>
    </div>
</footer><!-- #colophon -->
