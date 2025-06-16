<?php
/**
 * WooCommerce integration for BloglyPress
 *
 * @package BloglyPress
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Integration Class
 */
class BloglyPress_WooCommerce_Integration {

    /**
     * Constructor
     */
    public function __construct() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        // Add theme support
        add_action('after_setup_theme', array($this, 'setup'));
        
        // Enqueue styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        
        // Disable default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Layout hooks
        add_action('woocommerce_before_main_content', array($this, 'wrapper_start'), 10);
        add_action('woocommerce_after_main_content', array($this, 'wrapper_end'), 10);
        
        // Product columns
        add_filter('loop_shop_columns', array($this, 'loop_columns'));
        
        // Products per page
        add_filter('loop_shop_per_page', array($this, 'products_per_page'));
        
        // Related products
        add_filter('woocommerce_related_products_args', array($this, 'related_products_args'));
        
        // Product gallery
        add_filter('woocommerce_single_product_image_gallery_classes', array($this, 'gallery_classes'));
    }

    /**
     * Setup WooCommerce theme support
     */
    public function setup() {
        // Add WooCommerce support
        add_theme_support('woocommerce');
        
        // Add gallery zoom support
        add_theme_support('wc-product-gallery-zoom');
        
        // Add gallery lightbox support
        add_theme_support('wc-product-gallery-lightbox');
        
        // Add gallery slider support
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Enqueue WooCommerce styles
     */
    public function enqueue_styles() {
        // Enqueue theme's WooCommerce styles
        wp_enqueue_style('bloglypress-woocommerce', BLOGLYPRESS_URI . '/assets/css/woocommerce.css', array(), BLOGLYPRESS_VERSION);
    }

    /**
     * Start main content wrapper
     */
    public function wrapper_start() {
        echo '<div id="primary" class="content-area">';
        echo '<main id="main" class="site-main">';
    }

    /**
     * End main content wrapper
     */
    public function wrapper_end() {
        echo '</main><!-- #main -->';
        echo '</div><!-- #primary -->';
    }

    /**
     * Change number of product columns
     */
    public function loop_columns() {
        // Get setting from theme options
        $columns = get_theme_mod('bloglypress_shop_columns', 3);
        return $columns;
    }

    /**
     * Change number of products per page
     */
    public function products_per_page() {
        // Get setting from theme options
        $products = get_theme_mod('bloglypress_shop_products', 12);
        return $products;
    }

    /**
     * Change related products args
     */
    public function related_products_args($args) {
        // Get settings from theme options
        $columns = get_theme_mod('bloglypress_related_products_columns', 3);
        $count = get_theme_mod('bloglypress_related_products_count', 3);
        
        $args['posts_per_page'] = $count;
        $args['columns'] = $columns;
        
        return $args;
    }

    /**
     * Add classes to product gallery
     */
    public function gallery_classes($classes) {
        $classes[] = 'bloglypress-product-gallery';
        
        return $classes;
    }
}

// Initialize WooCommerce integration
new BloglyPress_WooCommerce_Integration();
