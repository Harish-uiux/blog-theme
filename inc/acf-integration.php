<?php
/**
 * ACF integration for BloglyPress
 *
 * @package BloglyPress
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ACF Integration Class
 */
class BloglyPress_ACF_Integration {

    /**
     * Constructor
     */
    public function __construct() {
        // Check if ACF is active
        if (!class_exists('ACF')) {
            return;
        }
        
        // Add ACF options page
        add_action('acf/init', array($this, 'add_options_page'));
        
        // Register custom fields
        add_action('acf/init', array($this, 'register_fields'));
        
        // Save ACF JSON
        add_filter('acf/settings/save_json', array($this, 'save_acf_json'));
        
        // Load ACF JSON
        add_filter('acf/settings/load_json', array($this, 'load_acf_json'));
    }

    /**
     * Add ACF options page
     */
    public function add_options_page() {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page(array(
                'page_title'  => __('BloglyPress Theme Options', 'bloglypress'),
                'menu_title'  => __('Theme Options', 'bloglypress'),
                'menu_slug'   => 'bloglypress-theme-options',
                'capability'  => 'manage_options',
                'redirect'    => false,
                'position'    => '59.1',
                'icon_url'    => 'dashicons-admin-customizer',
            ));
            
            acf_add_options_sub_page(array(
                'page_title'  => __('Header Options', 'bloglypress'),
                'menu_title'  => __('Header', 'bloglypress'),
                'parent_slug' => 'bloglypress-theme-options',
            ));
            
            acf_add_options_sub_page(array(
                'page_title'  => __('Footer Options', 'bloglypress'),
                'menu_title'  => __('Footer', 'bloglypress'),
                'parent_slug' => 'bloglypress-theme-options',
            ));
            
            acf_add_options_sub_page(array(
                'page_title'  => __('Blog Options', 'bloglypress'),
                'menu_title'  => __('Blog', 'bloglypress'),
                'parent_slug' => 'bloglypress-theme-options',
            ));
            
            acf_add_options_sub_page(array(
                'page_title'  => __('Social Media', 'bloglypress'),
                'menu_title'  => __('Social Media', 'bloglypress'),
                'parent_slug' => 'bloglypress-theme-options',
            ));
        }
    }

    /**
     * Register custom fields
     */
    public function register_fields() {
        if (function_exists('acf_add_local_field_group')) {
            // Header Options
            acf_add_local_field_group(array(
                'key' => 'group_bloglypress_header',
                'title' => 'Header Options',
                'fields' => array(
                    array(
                        'key' => 'field_header_logo',
                        'label' => 'Header Logo',
                        'name' => 'header_logo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'instructions' => 'Upload your logo for the header.',
                    ),
                    array(
                        'key' => 'field_header_transparent',
                        'label' => 'Transparent Header',
                        'name' => 'header_transparent',
                        'type' => 'true_false',
                        'ui' => 1,
                        'instructions' => 'Enable transparent header on homepage.',
                    ),
                    array(
                        'key' => 'field_header_sticky',
                        'label' => 'Sticky Header',
                        'name' => 'header_sticky',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 1,
                        'instructions' => 'Enable sticky header that stays at the top when scrolling.',
                    ),
                    array(
                        'key' => 'field_header_search',
                        'label' => 'Search Button',
                        'name' => 'header_search',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 1,
                        'instructions' => 'Show search button in header.',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'acf-options-header',
                        ),
                    ),
                ),
            ));
            
            // Footer Options
            acf_add_local_field_group(array(
                'key' => 'group_bloglypress_footer',
                'title' => 'Footer Options',
                'fields' => array(
                    array(
                        'key' => 'field_footer_logo',
                        'label' => 'Footer Logo',
                        'name' => 'footer_logo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'instructions' => 'Upload your logo for the footer.',
                    ),
                    array(
                        'key' => 'field_footer_columns',
                        'label' => 'Footer Columns',
                        'name' => 'footer_columns',
                        'type' => 'select',
                        'choices' => array(
                            '1' => '1 Column',
                            '2' => '2 Columns',
                            '3' => '3 Columns',
                            '4' => '4 Columns',
                        ),
                        'default_value' => '3',
                        'instructions' => 'Select the number of footer widget columns.',
                    ),
                    array(
                        'key' => 'field_footer_copyright',
                        'label' => 'Copyright Text',
                        'name' => 'footer_copyright',
                        'type' => 'textarea',
                        'rows' => 2,
                        'default_value' => '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
                        'instructions' => 'Enter your copyright text. Use {year} to dynamically display the current year.',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'acf-options-footer',
                        ),
                    ),
                ),
            ));
            
            // Blog Options
            acf_add_local_field_group(array(
                'key' => 'group_bloglypress_blog',
                'title' => 'Blog Options',
                'fields' => array(
                    array(
                        'key' => 'field_blog_layout',
                        'label' => 'Blog Layout',
                        'name' => 'blog_layout',
                        'type' => 'select',
                        'choices' => array(
                            'grid' => 'Grid',
                            'list' => 'List',
                            'masonry' => 'Masonry',
                        ),
                        'default_value' => 'grid',
                        'instructions' => 'Select the layout for the blog archive pages.',
                    ),
                    array(
                        'key' => 'field_blog_sidebar',
                        'label' => 'Sidebar Position',
                        'name' => 'blog_sidebar',
                        'type' => 'select',
                        'choices' => array(
                            'right' => 'Right',
                            'left' => 'Left',
                            'none' => 'No Sidebar',
                        ),
                        'default_value' => 'right',
                        'instructions' => 'Select the sidebar position for blog pages.',
                    ),
                    array(
                        'key' => 'field_featured_posts',
                        'label' => 'Featured Posts',
                        'name' => 'featured_posts',
                        'type' => 'relationship',
                        'return_format' => 'id',
                        'post_type' => 'post',
                        'min' => 0,
                        'max' => 5,
                        'instructions' => 'Select posts to feature at the top of the blog page.',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'acf-options-blog',
                        ),
                    ),
                ),
            ));
            
            // Social Media
            acf_add_local_field_group(array(
                'key' => 'group_bloglypress_social',
                'title' => 'Social Media',
                'fields' => array(
                    array(
                        'key' => 'field_social_facebook',
                        'label' => 'Facebook',
                        'name' => 'social_facebook',
                        'type' => 'url',
                        'instructions' => 'Enter your Facebook page URL.',
                    ),
                    array(
                        'key' => 'field_social_twitter',
                        'label' => 'Twitter',
                        'name' => 'social_twitter',
                        'type' => 'url',
                        'instructions' => 'Enter your Twitter profile URL.',
                    ),
                    array(
                        'key' => 'field_social_instagram',
                        'label' => 'Instagram',
                        'name' => 'social_instagram',
                        'type' => 'url',
                        'instructions' => 'Enter your Instagram profile URL.',
                    ),
                    array(
                        'key' => 'field_social_linkedin',
                        'label' => 'LinkedIn',
                        'name' => 'social_linkedin',
                        'type' => 'url',
                        'instructions' => 'Enter your LinkedIn profile URL.',
                    ),
                    array(
                        'key' => 'field_social_youtube',
                        'label' => 'YouTube',
                        'name' => 'social_youtube',
                        'type' => 'url',
                        'instructions' => 'Enter your YouTube channel URL.',
                    ),
                    array(
                        'key' => 'field_social_pinterest',
                        'label' => 'Pinterest',
                        'name' => 'social_pinterest',
                        'type' => 'url',
                        'instructions' => 'Enter your Pinterest profile URL.',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'acf-options-social-media',
                        ),
                    ),
                ),
            ));
            
            // Post Options
            acf_add_local_field_group(array(
                'key' => 'group_bloglypress_post',
                'title' => 'Post Options',
                'fields' => array(
                    array(
                        'key' => 'field_post_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'post_subtitle',
                        'type' => 'text',
                        'instructions' => 'Enter a subtitle for this post (optional).',
                    ),
                    array(
                        'key' => 'field_post_featured',
                        'label' => 'Featured Post',
                        'name' => 'post_featured',
                        'type' => 'true_false',
                        'ui' => 1,
                        'instructions' => 'Mark this post as featured to highlight it on the homepage.',
                    ),
                    array(
                        'key' => 'field_post_layout',
                        'label' => 'Post Layout',
                        'name' => 'post_layout',
                        'type' => 'select',
                        'choices' => array(
                            'default' => 'Default',
                            'wide' => 'Wide',
                            'full' => 'Full Width',
                            'sidebar' => 'With Sidebar',
                        ),
                        'default_value' => 'default',
                        'instructions' => 'Select a layout for this post.',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'post',
                        ),
                    ),
                ),
            ));
        }
    }

    /**
     * Save ACF JSON
     */
    public function save_acf_json($path) {
        // Save ACF JSON in theme directory
        $path = get_template_directory() . '/acf-json';
        
        // Create directory if it doesn't exist
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        
        return $path;
    }

    /**
     * Load ACF JSON
     */
    public function load_acf_json($paths) {
        // Load ACF JSON from theme directory
        $paths[] = get_template_directory() . '/acf-json';
        
        return $paths;
    }
}

// Initialize ACF integration
new BloglyPress_ACF_Integration();
