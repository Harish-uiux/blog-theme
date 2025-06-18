<?php
/**
 * Hariharan functions and definitions
 *
 * @package Hariharan
 */

// Define theme version
define('HARIHARAN_VERSION', '1.0.0');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function hariharan_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Register navigation menus
    register_nav_menus(
        array(
            'primary' => esc_html__('Primary Menu', 'hariharan'),
            'footer'  => esc_html__('Footer Menu', 'hariharan'),
        )
    );

    // Switch default core markup to output valid HTML5
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    // Add support for custom logo
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    // Add support for Block Styles
    add_theme_support('wp-block-styles');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'hariharan_setup');

/**
 * Set the content width in pixels.
 */
function hariharan_content_width() {
    $GLOBALS['content_width'] = apply_filters('hariharan_content_width', 1200);
}
add_action('after_setup_theme', 'hariharan_content_width', 0);

/**
 * Register widget area.
 */
function hariharan_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__('Sidebar', 'hariharan'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'hariharan'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    // Footer widget areas
    $footer_widget_areas = get_option('hariharan_footer_widget_count', 4);

    for ($i = 1; $i <= $footer_widget_areas; $i++) {
        register_sidebar(
            array(
                'name'          => sprintf(esc_html__('Footer %d', 'hariharan'), $i),
                'id'            => 'footer-' . $i,
                'description'   => esc_html__('Add widgets here.', 'hariharan'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );
    }
}
add_action('widgets_init', 'hariharan_widgets_init');

/**
 * Enqueue scripts and styles.
 */
/**
 * Enqueue scripts and styles.
 */
/**
 * Enqueue scripts and styles.
 */
function hariharan_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('hariharan-style', get_stylesheet_uri(), array(), HARIHARAN_VERSION);
    
    // Enqueue mobile menu script
    wp_enqueue_script('hariharan-mobile-menu', get_template_directory_uri() . '/assets/js/mobile-menu.js', array(), HARIHARAN_VERSION, true);
    
    // Enqueue dark mode script
    wp_enqueue_script('hariharan-dark-mode', get_template_directory_uri() . '/assets/js/dark-mode.js', array(), HARIHARAN_VERSION, true);
    
    // Pass theme settings to JavaScript
    wp_localize_script('hariharan-mobile-menu', 'hariharanSettings', array(
        'defaultDarkMode' => get_option('hariharan_default_dark_mode', 'false'),
        'headerLayout' => get_option('hariharan_header_layout', 'default'),
        'stickyHeader' => get_option('hariharan_sticky_header', '1'),
    ));

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'hariharan_scripts');



/**
 * Include admin functions.
 */
require get_template_directory() . '/admin/admin-page.php';
require get_template_directory() . '/admin/admin-settings.php';

/**
 * Include template functions and tags.
 */
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/seo-functions.php';

/**
 * Add support for wp_body_open()
 */
if (!function_exists('wp_body_open')) {
    function wp_body_open() {
        do_action('wp_body_open');
    }
}
