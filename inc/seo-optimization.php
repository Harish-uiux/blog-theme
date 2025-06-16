<?php
/**
 * SEO optimizations for the theme
 *
 * @package BloglyPress
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class for handling SEO optimizations
 */
class BloglyPress_SEO {

    /**
     * Constructor
     */
    public function __construct() {
        // Check if SEO optimizations are enabled
        $advanced_options = get_option('bloglypress_advanced_options', array());
        $enable_seo = isset($advanced_options['enable_seo']) ? $advanced_options['enable_seo'] : true;
        
        if (!$enable_seo) {
            return;
        }
        
        // Initialize optimizations
        $this->init();
    }

    /**
     * Initialize SEO optimizations
     */
    public function init() {
        // Add meta description
        add_action('wp_head', array($this, 'meta_description'), 1);
        
        // Add meta robots
        add_action('wp_head', array($this, 'meta_robots'), 2);
        
        // Add Open Graph tags
        add_action('wp_head', array($this, 'open_graph_tags'), 3);
        
        // Add Twitter Card tags
        add_action('wp_head', array($this, 'twitter_card_tags'), 4);
        
        // Add JSON-LD structured data
        add_action('wp_head', array($this, 'json_ld_schema'), 5);
        
        // Modify title tag
        add_filter('document_title_parts', array($this, 'modify_title_tag'));
        
        // Add image alt text
        add_filter('the_content', array($this, 'add_image_alt_text'));
        
        // Add rel attributes to external links
        add_filter('the_content', array($this, 'external_links_rel_attributes'));
    }

    /**
     * Add meta description
     */
    public function meta_description() {
        // Skip if SEO plugin is active
        if ($this->is_seo_plugin_active()) {
            return;
        }
        
        $description = '';
        
        if (is_singular()) {
            // Get post excerpt or generate from content
            $post_excerpt = get_the_excerpt();
            
            if (!empty($post_excerpt)) {
                $description = $post_excerpt;
            } else {
                $post_content = get_the_content();
                $post_content = strip_shortcodes($post_content);
                $post_content = wp_strip_all_tags($post_content);
                $description = wp_trim_words($post_content, 30, '');
            }
        } elseif (is_home() || is_front_page()) {
            // Use site description
            $description = get_bloginfo('description');
        } elseif (is_category() || is_tag() || is_tax()) {
            // Use term description
            $description = term_description();
            $description = wp_strip_all_tags($description);
        } elseif (is_author()) {
            // Use author bio
            $description = get_the_author_meta('description');
        }
        
        if (!empty($description)) {
            echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
        }
    }

    /**
     * Add meta robots
     */
    public function meta_robots() {
        // Skip if SEO plugin is active
        if ($this->is_seo_plugin_active()) {
            return;
        }
        
        $robots = array();
        
        // Default index and follow
        $robots[] = 'index, follow';
        
        // No index for specific pages
        if (is_search() || is_404() || is_attachment()) {
            $robots = array('noindex, follow');
        }
        
        // Apply filters for custom control
        $robots = apply_filters('bloglypress_robots', $robots);
        
        if (!empty($robots)) {
            echo '<meta name="robots" content="' . esc_attr(implode(', ', $robots)) . '" />' . "\n";
        }
    }

    /**
     * Add Open Graph tags
     */
    public function open_graph_tags() {
        // Skip if SEO plugin is active
        if ($this->is_seo_plugin_active()) {
            return;
        }
        
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        
        if (is_singular()) {
            echo '<meta property="og:type" content="article" />' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
            
            // Get post excerpt or generate from content
            $post_excerpt = get_the_excerpt();
            
            if (!empty($post_excerpt)) {
                echo '<meta property="og:description" content="' . esc_attr($post_excerpt) . '" />' . "\n";
            }
            
            // Get post thumbnail
            if (has_post_thumbnail()) {
                $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($post_thumbnail) {
                    echo '<meta property="og:image" content="' . esc_url($post_thumbnail[0]) . '" />' . "\n";
                    echo '<meta property="og:image:width" content="' . esc_attr($post_thumbnail[1]) . '" />' . "\n";
                    echo '<meta property="og:image:height" content="' . esc_attr($post_thumbnail[2]) . '" />' . "\n";
                }
            }
            
            // Get post publish time
            echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '" />' . "\n";
            
            // Get post modified time
            echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '" />' . "\n";
            
            // Get author name
            echo '<meta property="article:author" content="' . esc_attr(get_the_author()) . '" />' . "\n";
        } else {
            echo '<meta property="og:type" content="website" />' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(wp_get_document_title()) . '" />' . "\n";
            echo '<meta property="og:url" content="' . esc_url(home_url($_SERVER['REQUEST_URI'])) . '" />' . "\n";
            
            // Get site description
            $description = get_bloginfo('description');
            if (!empty($description)) {
                echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
            }
            
            // Get site logo
            $custom_logo_id = get_theme_mod('custom_logo');
            if ($custom_logo_id) {
                $logo_info = wp_get_attachment_image_src($custom_logo_id, 'full');
                if ($logo_info) {
                    echo '<meta property="og:image" content="' . esc_url($logo_info[0]) . '" />' . "\n";
                    echo '<meta property="og:image:width" content="' . esc_attr($logo_info[1]) . '" />' . "\n";
                    echo '<meta property="og:image:height" content="' . esc_attr($logo_info[2]) . '" />' . "\n";
                }
            }
        }
    }

    /**
     * Add Twitter Card tags
     */
    public function twitter_card_tags() {
        // Skip if SEO plugin is active
        if ($this->is_seo_plugin_active()) {
            return;
        }
        
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        
        // Get Twitter username
        $social_options = get_option('bloglypress_social_options', array());
        $twitter_username = isset($social_options['twitter_username']) ? $social_options['twitter_username'] : '';
        
        if (!empty($twitter_username)) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_username) . '" />' . "\n";
        }
        
        if (is_singular()) {
            echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
            
            // Get post excerpt or generate from content
            $post_excerpt = get_the_excerpt();
            
            if (!empty($post_excerpt)) {
                echo '<meta name="twitter:description" content="' . esc_attr($post_excerpt) . '" />' . "\n";
            }
            
            // Get post thumbnail
            if (has_post_thumbnail()) {
                $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($post_thumbnail) {
                    echo '<meta name="twitter:image" content="' . esc_url($post_thumbnail[0]) . '" />' . "\n";
                }
            }
        } else {
            echo '<meta name="twitter:title" content="' . esc_attr(wp_get_document_title()) . '" />' . "\n";
            
            // Get site description
            $description = get_bloginfo('description');
            if (!empty($description)) {
                echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
            }
            
            // Get site logo
            $custom_logo_id = get_theme_mod('custom_logo');
            if ($custom_logo_id) {
                $logo_info = wp_get_attachment_image_src($custom_logo_id, 'full');
                if ($logo_info) {
                    echo '<meta name="twitter:image" content="' . esc_url($logo_info[0]) . '" />' . "\n";
                }
            }
        }
    }

    /**
     * Add JSON-LD structured data
     */
    public function json_ld_schema() {
        // Skip if SEO plugin is active
        if ($this->is_seo_plugin_active()) {
            return;
        }
        
        $schema = array();
        
        if (is_singular('post')) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'BlogPosting',
                'headline' => get_the_title(),
                'description' => get_the_excerpt(),
                'datePublished' => get_the_date('c'),
                'dateModified' => get_the_modified_date('c'),
                'author' => array(
                    '@type' => 'Person',
                    'name' => get_the_author(),
                    'url' => get_author_posts_url(get_the_author_meta('ID')),
                ),
                'publisher' => array(
                    '@type' => 'Organization',
                    'name' => get_bloginfo('name'),
                    'logo' => array(
                        '@type' => 'ImageObject',
                        'url' => get_custom_logo_url(),
                    ),
                ),
                'mainEntityOfPage' => array(
                    '@type' => 'WebPage',
                    '@id' => get_permalink(),
                ),
            );
            
            // Add featured image
            if (has_post_thumbnail()) {
                $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                if ($post_thumbnail) {
                    $schema['image'] = array(
                        '@type' => 'ImageObject',
                        'url' => $post_thumbnail[0],
                        'width' => $post_thumbnail[1],
                        'height' => $post_thumbnail[2],
                    );
                }
            }
        } elseif (is_page()) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => get_the_title(),
                'description' => get_the_excerpt(),
                'datePublished' => get_the_date('c'),
                'dateModified' => get_the_modified_date('c'),
                'author' => array(
                    '@type' => 'Person',
                    'name' => get_the_author(),
                ),
            );
            
            // Add featured image
            if (has_post_thumbnail()) {
                $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                if ($post_thumbnail) {
                    $schema['image'] = array(
                        '@type' => 'ImageObject',
                        'url' => $post_thumbnail[0],
                        'width' => $post_thumbnail[1],
                        'height' => $post_thumbnail[2],
                    );
                }
            }
        } elseif (is_home() || is_front_page()) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => get_bloginfo('name'),
                'description' => get_bloginfo('description'),
                'url' => home_url('/'),
                'potentialAction' => array(
                    '@type' => 'SearchAction',
                    'target' => home_url('/?s={search_term_string}'),
                    'query-input' => 'required name=search_term_string',
                ),
            );
        }
        
        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
        }
    }

    /**
     * Modify title tag
     */
    public function modify_title_tag($title) {
        // Skip if SEO plugin is active
        if ($this->is_seo_plugin_active()) {
            return $title;
        }
        
        // Add site name to title on single posts and pages
        if (is_singular() && !is_front_page()) {
            $title['title'] = get_the_title();
            $title['site'] = get_bloginfo('name');
        }
        
        return $title;
    }

    /**
     * Add image alt text if missing
     */
    public function add_image_alt_text($content) {
        // Use DOM to modify images
        if (!class_exists('DOMDocument')) {
            return $content;
        }
        
        // Create DOM document
        $dom = new DOMDocument();
        
        // Suppress errors due to malformed HTML
        libxml_use_internal_errors(true);
        
        // Load content
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        
        // Clear errors
        libxml_clear_errors();
        
        // Get all images
        $images = $dom->getElementsByTagName('img');
        
        foreach ($images as $image) {
            // Check if alt attribute exists
            if (!$image->hasAttribute('alt')) {
                // Get image title or file name
                if ($image->hasAttribute('title')) {
                    $alt_text = $image->getAttribute('title');
                } elseif ($image->hasAttribute('src')) {
                    // Get file name from src
                    $src = $image->getAttribute('src');
                    $file_name = basename($src);
                    $file_name = pathinfo($file_name, PATHINFO_FILENAME);
                    $file_name = str_replace(array('-', '_'), ' ', $file_name);
                    $alt_text = ucwords($file_name);
                } else {
                    $alt_text = get_the_title();
                }
                
                // Set alt attribute
                $image->setAttribute('alt', $alt_text);
            }
        }
        
        // Get content
        $content = $dom->saveHTML();
        
        // Extract body content
        $content = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $content));
        
        return $content;
    }

    /**
     * Add rel attributes to external links
     */
    public function external_links_rel_attributes($content) {
        // Skip if no content
        if (empty($content)) {
            return $content;
        }
        
        // Use DOM to modify links
        if (!class_exists('DOMDocument')) {
            return $content;
        }
        
        // Create DOM document
        $dom = new DOMDocument();
        
        // Suppress errors due to malformed HTML
        libxml_use_internal_errors(true);
        
        // Load content
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        
        // Clear errors
        libxml_clear_errors();
        
        // Get all links
        $links = $dom->getElementsByTagName('a');
        
        // Get home URL
        $home_url = home_url();
        $home_domain = parse_url($home_url, PHP_URL_HOST);
        
        foreach ($links as $link) {
            // Get href attribute
            $href = $link->getAttribute('href');
            
            // Skip if empty
            if (empty($href)) {
                continue;
            }
            
            // Parse URL
            $url_parts = parse_url($href);
            
            // Skip if no host
            if (empty($url_parts['host'])) {
                continue;
            }
            
            // Check if external link
            if ($url_parts['host'] !== $home_domain) {
                // Add rel attribute
                $link->setAttribute('rel', 'noopener noreferrer');
                
                // Add target attribute if not exists
                if (!$link->hasAttribute('target')) {
                    $link->setAttribute('target', '_blank');
                }
            }
        }
        
        // Get content
        $content = $dom->saveHTML();
        
        // Extract body content
        $content = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $content));
        
        return $content;
    }

    /**
     * Check if a SEO plugin is active
     */
    private function is_seo_plugin_active() {
        $seo_plugins = array(
            'wordpress-seo/wp-seo.php', // Yoast SEO
            'seo-by-rank-math/rank-math.php', // Rank Math
            'all-in-one-seo-pack/all_in_one_seo_pack.php', // All in One SEO Pack
            'wp-seopress/seopress.php', // SEOPress
        );
        
        foreach ($seo_plugins as $plugin) {
            if (is_plugin_active($plugin)) {
                return true;
            }
        }
        
        return false;
    }
}

// Initialize SEO optimizations
new BloglyPress_SEO();
