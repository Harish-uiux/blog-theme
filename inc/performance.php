<?php
/**
 * Performance optimizations for the theme
 *
 * @package BloglyPress
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class for handling performance optimizations
 */
class BloglyPress_Performance {

    /**
     * Constructor
     */
    public function __construct() {
        // Check if performance optimizations are enabled
        $advanced_options = get_option('bloglypress_advanced_options', array());
        $enable_performance = isset($advanced_options['enable_performance']) ? $advanced_options['enable_performance'] : true;
        
        if (!$enable_performance) {
            return;
        }
        
        // Initialize optimizations
        $this->init();
    }

    /**
     * Initialize performance optimizations
     */
    public function init() {
        // Remove query strings from static resources
        add_filter('script_loader_src', array($this, 'remove_query_strings'), 15);
        add_filter('style_loader_src', array($this, 'remove_query_strings'), 15);
        
        // Disable emojis
        $this->disable_emojis();
        
        // Defer JavaScript loading
        add_filter('script_loader_tag', array($this, 'defer_js_files'), 10, 3);
        
        // Add preconnect for Google Fonts
        add_filter('wp_resource_hints', array($this, 'resource_hints'), 10, 2);
        
        // Optimize images
        $this->optimize_images();
        
        // Add browser caching headers
        add_action('send_headers', array($this, 'add_browser_caching'));
    }

    /**
     * Remove query strings from static resources
     */
    public function remove_query_strings($src) {
        if (strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }

    /**
     * Disable emojis
     */
    public function disable_emojis() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        
        // Remove TinyMCE emojis
        add_filter('tiny_mce_plugins', function($plugins) {
            if (is_array($plugins)) {
                return array_diff($plugins, array('wpemoji'));
            } else {
                return array();
            }
        });
    }

    /**
     * Defer JavaScript files
     */
    public function defer_js_files($tag, $handle, $src) {
        // Skip deferring specific scripts
        $skip_defer = array(
            'jquery',
            'jquery-core',
            'jquery-migrate',
            'wp-polyfill',
        );
        
        if (in_array($handle, $skip_defer)) {
            return $tag;
        }
        
        // Don't defer admin scripts
        if (is_admin()) {
            return $tag;
        }
        
        // Add defer attribute
        return str_replace(' src', ' defer src', $tag);
    }

    /**
     * Add preconnect for Google Fonts
     */
    public function resource_hints($urls, $relation_type) {
        if ('preconnect' === $relation_type) {
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin',
            );
        }
        
        return $urls;
    }

    /**
     * Optimize images
     */
    public function optimize_images() {
        // Enable WebP image format if supported
        $enable_webp = get_theme_mod('bloglypress_enable_webp', true);
        
        if ($enable_webp) {
            add_filter('wp_get_attachment_image_src', array($this, 'use_webp_image'), 10, 4);
        }
    }

    /**
     * Use WebP images if available
     */
    public function use_webp_image($image, $attachment_id, $size, $icon) {
        if (!$image) {
            return $image;
        }
        
        // Check if browser supports WebP
        if (!isset($_SERVER['HTTP_ACCEPT']) || strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') === false) {
            return $image;
        }
        
        $image_url = $image[0];
        $webp_url = $image_url . '.webp';
        
        // Check if WebP version exists
        $webp_path = str_replace(site_url('/'), ABSPATH, $webp_url);
        
        if (file_exists($webp_path)) {
            $image[0] = $webp_url;
        }
        
        return $image;
    }

    /**
     * Add browser caching headers
     */
    public function add_browser_caching() {
        // Only apply on front-end
        if (is_admin()) {
            return;
        }
        
        $file_type = '';
        
        // Get file type
        if (isset($_SERVER['REQUEST_URI'])) {
            $file_parts = explode('.', $_SERVER['REQUEST_URI']);
            $file_type = strtolower(end($file_parts));
        }
        
        // Set cache expiration time based on file type
        $expires = array(
            'css'  => 31536000, // 1 year
            'js'   => 31536000, // 1 year
            'jpg'  => 31536000, // 1 year
            'jpeg' => 31536000, // 1 year
            'png'  => 31536000, // 1 year
            'gif'  => 31536000, // 1 year
            'webp' => 31536000, // 1 year
            'svg'  => 31536000, // 1 year
            'ico'  => 31536000, // 1 year
            'woff' => 31536000, // 1 year
            'woff2' => 31536000, // 1 year
            'ttf'  => 31536000, // 1 year
            'eot'  => 31536000, // 1 year
        );
        
        if (array_key_exists($file_type, $expires)) {
            $expire_time = $expires[$file_type];
            
            header('Cache-Control: public, max-age=' . $expire_time);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expire_time) . ' GMT');
        }
    }
}

// Initialize performance optimizations
new BloglyPress_Performance();
