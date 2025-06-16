<?php
/**
 * BloglyPress Theme Customizer
 *
 * @package BloglyPress
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function bloglypress_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    // Add panel for theme options
    $wp_customize->add_panel('bloglypress_theme_options', array(
        'title'    => __('BloglyPress Theme Options', 'bloglypress'),
        'priority' => 130,
    ));

    // Header Section
    $wp_customize->add_section('bloglypress_header_options', array(
        'title'    => __('Header Options', 'bloglypress'),
        'panel'    => 'bloglypress_theme_options',
        'priority' => 10,
    ));

    // Header Layout
    $wp_customize->add_setting('bloglypress_header_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'bloglypress_sanitize_select',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_header_layout', array(
        'label'    => __('Header Layout', 'bloglypress'),
        'section'  => 'bloglypress_header_options',
        'type'     => 'select',
        'choices'  => array(
            'default'      => __('Default', 'bloglypress'),
            'centered'     => __('Centered', 'bloglypress'),
            'minimal'      => __('Minimal', 'bloglypress'),
            'transparent'  => __('Transparent', 'bloglypress'),
        ),
    ));

    // Sticky Header
    $wp_customize->add_setting('bloglypress_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_sticky_header', array(
        'label'    => __('Enable Sticky Header', 'bloglypress'),
        'section'  => 'bloglypress_header_options',
        'type'     => 'checkbox',
    ));

    // Enable Search
    $wp_customize->add_setting('bloglypress_enable_search', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_enable_search', array(
        'label'    => __('Show Search in Header', 'bloglypress'),
        'section'  => 'bloglypress_header_options',
        'type'     => 'checkbox',
    ));

    // Enable Dark Mode Toggle
    $wp_customize->add_setting('bloglypress_enable_dark_mode', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_enable_dark_mode', array(
        'label'    => __('Show Dark Mode Toggle', 'bloglypress'),
        'section'  => 'bloglypress_header_options',
        'type'     => 'checkbox',
    ));

    // Blog Layout Section
    $wp_customize->add_section('bloglypress_blog_options', array(
        'title'    => __('Blog Options', 'bloglypress'),
        'panel'    => 'bloglypress_theme_options',
        'priority' => 20,
    ));

    // Blog Layout
    $wp_customize->add_setting('bloglypress_blog_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'bloglypress_sanitize_select',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_blog_layout', array(
        'label'    => __('Blog Layout', 'bloglypress'),
        'section'  => 'bloglypress_blog_options',
        'type'     => 'select',
        'choices'  => array(
            'grid'    => __('Grid', 'bloglypress'),
            'list'    => __('List', 'bloglypress'),
            'masonry' => __('Masonry', 'bloglypress'),
        ),
    ));

    // Blog Sidebar Position
    $wp_customize->add_setting('bloglypress_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'bloglypress_sanitize_select',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_sidebar_position', array(
        'label'    => __('Sidebar Position', 'bloglypress'),
        'section'  => 'bloglypress_blog_options',
        'type'     => 'select',
        'choices'  => array(
            'right' => __('Right', 'bloglypress'),
            'left'  => __('Left', 'bloglypress'),
            'none'  => __('None', 'bloglypress'),
        ),
    ));

    // Featured Post
    $wp_customize->add_setting('bloglypress_featured_post', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_featured_post', array(
        'label'    => __('Show Featured Post', 'bloglypress'),
        'section'  => 'bloglypress_blog_options',
        'type'     => 'checkbox',
    ));

    // Show Excerpt
    $wp_customize->add_setting('bloglypress_show_excerpt', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_show_excerpt', array(
        'label'    => __('Show Post Excerpt', 'bloglypress'),
        'section'  => 'bloglypress_blog_options',
        'type'     => 'checkbox',
    ));

    // Excerpt Length
    $wp_customize->add_setting('bloglypress_excerpt_length', array(
        'default'           => 25,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_excerpt_length', array(
        'label'    => __('Excerpt Length', 'bloglypress'),
        'section'  => 'bloglypress_blog_options',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 100,
            'step' => 5,
        ),
    ));

    // Show Meta
    $wp_customize->add_setting('bloglypress_show_meta', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_show_meta', array(
        'label'    => __('Show Post Meta', 'bloglypress'),
        'section'  => 'bloglypress_blog_options',
        'type'     => 'checkbox',
    ));

    // Show Categories
    $wp_customize->add_setting('bloglypress_show_categories', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_show_categories', array(
        'label'    => __('Show Categories', 'bloglypress'),
        'section'  => 'bloglypress_blog_options',
        'type'     => 'checkbox',
    ));

    // Show Author
    $wp_customize->add_setting('bloglypress_show_author', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_show_author', array(
        'label'    => __('Show Author', 'bloglypress'),
        'section'  => 'bloglypress_blog_options',
        'type'     => 'checkbox',
    ));

    // Show Date
    $wp_customize->add_setting('bloglypress_show_date', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_show_date', array(
        'label'    => __('Show Date', 'bloglypress'),
        'section'  => 'bloglypress_blog_options',
        'type'     => 'checkbox',
    ));

    // Pagination Type
    $wp_customize->add_setting('bloglypress_pagination_type', array(
        'default'           => 'numeric',
        'sanitize_callback' => 'bloglypress_sanitize_select',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_pagination_type', array(
        'label'    => __('Pagination Type', 'bloglypress'),
        'section'  => 'bloglypress_blog_options',
        'type'     => 'select',
        'choices'  => array(
            'numeric'  => __('Numeric', 'bloglypress'),
            'prev_next' => __('Previous / Next', 'bloglypress'),
        ),
    ));

    // Single Post Section
    $wp_customize->add_section('bloglypress_single_post_options', array(
        'title'    => __('Single Post Options', 'bloglypress'),
        'panel'    => 'bloglypress_theme_options',
        'priority' => 30,
    ));

    // Single Post Sidebar
    $wp_customize->add_setting('bloglypress_single_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'bloglypress_sanitize_select',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_single_sidebar_position', array(
        'label'    => __('Sidebar Position', 'bloglypress'),
        'section'  => 'bloglypress_single_post_options',
        'type'     => 'select',
        'choices'  => array(
            'right' => __('Right', 'bloglypress'),
            'left'  => __('Left', 'bloglypress'),
            'none'  => __('None', 'bloglypress'),
        ),
    ));

    // Featured Image
    $wp_customize->add_setting('bloglypress_single_show_featured_image', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_single_show_featured_image', array(
        'label'    => __('Show Featured Image', 'bloglypress'),
        'section'  => 'bloglypress_single_post_options',
        'type'     => 'checkbox',
    ));

    // Show Post Navigation
    $wp_customize->add_setting('bloglypress_show_post_navigation', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_show_post_navigation', array(
        'label'    => __('Show Post Navigation', 'bloglypress'),
        'section'  => 'bloglypress_single_post_options',
        'type'     => 'checkbox',
    ));

    // Show Author Box
    $wp_customize->add_setting('bloglypress_show_author_box', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_show_author_box', array(
        'label'    => __('Show Author Box', 'bloglypress'),
        'section'  => 'bloglypress_single_post_options',
        'type'     => 'checkbox',
    ));

    // Show Related Posts
    $wp_customize->add_setting('bloglypress_show_related_posts', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_show_related_posts', array(
        'label'    => __('Show Related Posts', 'bloglypress'),
        'section'  => 'bloglypress_single_post_options',
        'type'     => 'checkbox',
    ));

    // Related Posts Count
    $wp_customize->add_setting('bloglypress_related_posts_count', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_related_posts_count', array(
        'label'    => __('Related Posts Count', 'bloglypress'),
        'section'  => 'bloglypress_single_post_options',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 2,
            'max'  => 6,
            'step' => 1,
        ),
    ));

    // Show Social Sharing
    $wp_customize->add_setting('bloglypress_show_social_sharing', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_show_social_sharing', array(
        'label'    => __('Show Social Sharing', 'bloglypress'),
        'section'  => 'bloglypress_single_post_options',
        'type'     => 'checkbox',
    ));

    // Footer Section
    $wp_customize->add_section('bloglypress_footer_options', array(
        'title'    => __('Footer Options', 'bloglypress'),
        'panel'    => 'bloglypress_theme_options',
        'priority' => 40,
    ));

    // Copyright Text
    $wp_customize->add_setting('bloglypress_copyright_text', array(
        'default'           => '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('bloglypress_copyright_text', array(
        'label'    => __('Copyright Text', 'bloglypress'),
        'section'  => 'bloglypress_footer_options',
        'type'     => 'textarea',
    ));

    // Show Powered By
    $wp_customize->add_setting('bloglypress_show_powered_by', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_show_powered_by', array(
        'label'    => __('Show "Powered by" Text', 'bloglypress'),
        'section'  => 'bloglypress_footer_options',
        'type'     => 'checkbox',
    ));

    // Back to Top Button
    $wp_customize->add_setting('bloglypress_back_to_top', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_back_to_top', array(
        'label'    => __('Show Back to Top Button', 'bloglypress'),
        'section'  => 'bloglypress_footer_options',
        'type'     => 'checkbox',
    ));

    // Colors Section
    $wp_customize->add_section('bloglypress_colors_options', array(
        'title'    => __('Color Options', 'bloglypress'),
        'panel'    => 'bloglypress_theme_options',
        'priority' => 50,
    ));

    // Primary Color
    $wp_customize->add_setting('bloglypress_primary_color', array(
        'default'           => '#3857ff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bloglypress_primary_color', array(
        'label'    => __('Primary Color', 'bloglypress'),
        'section'  => 'bloglypress_colors_options',
    )));

    // Secondary Color
    $wp_customize->add_setting('bloglypress_secondary_color', array(
        'default'           => '#15c9c9',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bloglypress_secondary_color', array(
        'label'    => __('Secondary Color', 'bloglypress'),
        'section'  => 'bloglypress_colors_options',
    )));

    // Text Color
    $wp_customize->add_setting('bloglypress_text_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bloglypress_text_color', array(
        'label'    => __('Text Color', 'bloglypress'),
        'section'  => 'bloglypress_colors_options',
    )));

    // Link Color
    $wp_customize->add_setting('bloglypress_link_color', array(
        'default'           => '#3857ff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bloglypress_link_color', array(
        'label'    => __('Link Color', 'bloglypress'),
        'section'  => 'bloglypress_colors_options',
    )));

    // Performance Section
    $wp_customize->add_section('bloglypress_performance_options', array(
        'title'    => __('Performance Options', 'bloglypress'),
        'panel'    => 'bloglypress_theme_options',
        'priority' => 60,
    ));

    // Lazy Loading
    $wp_customize->add_setting('bloglypress_enable_lazy_loading', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_enable_lazy_loading', array(
        'label'    => __('Enable Lazy Loading for Images', 'bloglypress'),
        'section'  => 'bloglypress_performance_options',
        'type'     => 'checkbox',
    ));

    // Minify CSS/JS
    $wp_customize->add_setting('bloglypress_minify_assets', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_minify_assets', array(
        'label'    => __('Minify CSS and JavaScript', 'bloglypress'),
        'section'  => 'bloglypress_performance_options',
        'type'     => 'checkbox',
    ));

    // Enable WebP
    $wp_customize->add_setting('bloglypress_enable_webp', array(
        'default'           => true,
        'sanitize_callback' => 'bloglypress_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('bloglypress_enable_webp', array(
        'label'    => __('Enable WebP Image Format', 'bloglypress'),
        'section'  => 'bloglypress_performance_options',
        'type'     => 'checkbox',
    ));

    // Advanced Section
    $wp_customize->add_section('bloglypress_advanced_options', array(
        'title'    => __('Advanced Options', 'bloglypress'),
        'panel'    => 'bloglypress_theme_options',
        'priority' => 70,
    ));

    // Custom CSS
    $wp_customize->add_setting('bloglypress_custom_css', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('bloglypress_custom_css', array(
        'label'    => __('Custom CSS', 'bloglypress'),
        'section'  => 'bloglypress_advanced_options',
        'type'     => 'textarea',
    ));

    // Custom JavaScript
    $wp_customize->add_setting('bloglypress_custom_js', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('bloglypress_custom_js', array(
        'label'    => __('Custom JavaScript', 'bloglypress'),
        'section'  => 'bloglypress_advanced_options',
        'type'     => 'textarea',
    ));
}
add_action('customize_register', 'bloglypress_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function bloglypress_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function bloglypress_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function bloglypress_customize_preview_js() {
    wp_enqueue_script('bloglypress-customizer', BLOGLYPRESS_URI . '/assets/js/customizer.js', array('customize-preview'), BLOGLYPRESS_VERSION, true);
}
add_action('customize_preview_init', 'bloglypress_customize_preview_js');

/**
 * Sanitize select option.
 *
 * @param string $input
 * @param object $setting
 * @return string
 */
function bloglypress_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize checkbox option.
 *
 * @param bool $checked
 * @return bool
 */
function bloglypress_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}
