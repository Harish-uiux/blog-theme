<?php
/**
 * BloglyPress functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package BloglyPress
 */

// Define constants
define('BLOGLYPRESS_VERSION', '1.0.0');
define('BLOGLYPRESS_DIR', get_template_directory());
define('BLOGLYPRESS_URI', get_template_directory_uri());

/**
 * Setup theme
 */
if (!function_exists('bloglypress_setup')) :
    function bloglypress_setup() {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title.
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(1200, 9999);

        // Register navigation menus
        register_nav_menus(
            array(
                'primary' => esc_html__('Primary Menu', 'bloglypress'),
                'footer' => esc_html__('Footer Menu', 'bloglypress'),
                'social' => esc_html__('Social Links Menu', 'bloglypress'),
            )
        );

        // Switch default core markup to output valid HTML5.
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

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background');

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for Block Styles.
        add_theme_support('wp-block-styles');

        // Add support for full and wide align images.
        add_theme_support('align-wide');

        // Add support for responsive embeds.
        add_theme_support('responsive-embeds');

        // Add support for custom line height controls.
        add_theme_support('custom-line-height');

        // Add support for custom spacing.
        add_theme_support('custom-spacing');

        // Add support for custom units.
        add_theme_support('custom-units');

        // Add support for editor styles.
        add_theme_support('editor-styles');

        // Enqueue editor styles.
        add_editor_style('assets/css/editor-style.css');

        // Add support for custom logo.
        add_theme_support(
            'custom-logo',
            array(
                'height' => 250,
                'width' => 250,
                'flex-width' => true,
                'flex-height' => true,
            )
        );
    }
endif;
add_action('after_setup_theme', 'bloglypress_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function bloglypress_content_width() {
    $GLOBALS['content_width'] = apply_filters('bloglypress_content_width', 1200);
}
add_action('after_setup_theme', 'bloglypress_content_width', 0);

/**
 * Register widget areas.
 */
function bloglypress_widgets_init() {
    register_sidebar(
        array(
            'name' => esc_html__('Sidebar', 'bloglypress'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'bloglypress'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name' => esc_html__('Footer 1', 'bloglypress'),
            'id' => 'footer-1',
            'description' => esc_html__('Add footer widgets here.', 'bloglypress'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name' => esc_html__('Footer 2', 'bloglypress'),
            'id' => 'footer-2',
            'description' => esc_html__('Add footer widgets here.', 'bloglypress'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name' => esc_html__('Footer 3', 'bloglypress'),
            'id' => 'footer-3',
            'description' => esc_html__('Add footer widgets here.', 'bloglypress'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
}
add_action('widgets_init', 'bloglypress_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function bloglypress_scripts() {
    // Main stylesheet
    wp_enqueue_style('bloglypress-style', get_stylesheet_uri(), array(), BLOGLYPRESS_VERSION);
    
    // Theme stylesheet
    wp_enqueue_style('bloglypress-main', BLOGLYPRESS_URI . '/assets/css/main.css', array(), BLOGLYPRESS_VERSION);
    
    // Add Google Fonts
    $typography_options = get_option('bloglypress_typography_options', array());
    $body_font = !empty($typography_options['body_font']) ? $typography_options['body_font'] : 'Inter';
    $heading_font = !empty($typography_options['heading_font']) ? $typography_options['heading_font'] : 'Playfair Display';
    
    wp_enqueue_style('bloglypress-fonts', "https://fonts.googleapis.com/css2?family={$body_font}:wght@400;500;700&family={$heading_font}:wght@400;700;900&display=swap", array(), null);
    
    // AlpineJS for interactivity
    wp_enqueue_script('alpine-js', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', array(), '3.10.5', true);
    
    // Theme JS
    wp_enqueue_script('bloglypress-main', BLOGLYPRESS_URI . '/assets/js/main.js', array('jquery', 'alpine-js'), BLOGLYPRESS_VERSION, true);

    // Add comment reply JS
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Add custom CSS variables based on theme options
    $color_options = get_option('bloglypress_color_options', array());
    $primary_color = !empty($color_options['primary_color']) ? $color_options['primary_color'] : '#3857ff';
    $secondary_color = !empty($color_options['secondary_color']) ? $color_options['secondary_color'] : '#15c9c9';
    
    $custom_css = "
        :root {
            --bloglypress-primary-color: {$primary_color};
            --bloglypress-secondary-color: {$secondary_color};
            --bloglypress-body-font: '{$body_font}', sans-serif;
            --bloglypress-heading-font: '{$heading_font}', serif;
        }
    ";
    wp_add_inline_style('bloglypress-main', $custom_css);
    
    // Localize script with theme data
    wp_localize_script('bloglypress-main', 'bloglyPressData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'themeUri' => BLOGLYPRESS_URI,
        'nonce' => wp_create_nonce('bloglypress_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'bloglypress_scripts');

/**
 * Admin scripts and styles
 */
function bloglypress_admin_scripts() {
    wp_enqueue_style('bloglypress-admin', BLOGLYPRESS_URI . '/assets/css/admin.css', array(), BLOGLYPRESS_VERSION);
    wp_enqueue_script('bloglypress-admin', BLOGLYPRESS_URI . '/assets/js/admin.js', array('jquery'), BLOGLYPRESS_VERSION, true);
    
    wp_localize_script('bloglypress-admin', 'bloglyPressAdmin', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('bloglypress_admin_nonce'),
    ));
}
add_action('admin_enqueue_scripts', 'bloglypress_admin_scripts');

/**
 * Include required files
 */
require BLOGLYPRESS_DIR . '/inc/template-functions.php';
require BLOGLYPRESS_DIR . '/inc/template-tags.php';
require BLOGLYPRESS_DIR . '/inc/customizer.php';
require BLOGLYPRESS_DIR . '/inc/theme-options.php';
require BLOGLYPRESS_DIR . '/inc/custom-header.php';
require BLOGLYPRESS_DIR . '/inc/custom-post-types.php';
require BLOGLYPRESS_DIR . '/inc/block-patterns.php';
require BLOGLYPRESS_DIR . '/inc/block-styles.php';
require BLOGLYPRESS_DIR . '/inc/custom-blocks.php';
require BLOGLYPRESS_DIR . '/inc/seo-optimization.php';
require BLOGLYPRESS_DIR . '/inc/performance.php';

// Include ACF integration if ACF Pro is active
if (class_exists('ACF')) {
    require BLOGLYPRESS_DIR . '/inc/acf-integration.php';
}

// Include WooCommerce support if active
if (class_exists('WooCommerce')) {
    require BLOGLYPRESS_DIR . '/inc/woocommerce.php';
}
