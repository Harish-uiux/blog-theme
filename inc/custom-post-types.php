<?php
/**
 * Custom post types for BloglyPress
 *
 * @package BloglyPress
 */

/**
 * Register custom post types
 */
function bloglypress_register_post_types() {
    // Portfolio post type
    $portfolio_labels = array(
        'name'               => _x('Portfolio', 'post type general name', 'bloglypress'),
        'singular_name'      => _x('Portfolio Item', 'post type singular name', 'bloglypress'),
        'menu_name'          => _x('Portfolio', 'admin menu', 'bloglypress'),
        'name_admin_bar'     => _x('Portfolio Item', 'add new on admin bar', 'bloglypress'),
        'add_new'            => _x('Add New', 'portfolio item', 'bloglypress'),
        'add_new_item'       => __('Add New Portfolio Item', 'bloglypress'),
        'new_item'           => __('New Portfolio Item', 'bloglypress'),
        'edit_item'          => __('Edit Portfolio Item', 'bloglypress'),
        'view_item'          => __('View Portfolio Item', 'bloglypress'),
        'all_items'          => __('All Portfolio Items', 'bloglypress'),
        'search_items'       => __('Search Portfolio Items', 'bloglypress'),
        'parent_item_colon'  => __('Parent Portfolio Items:', 'bloglypress'),
        'not_found'          => __('No portfolio items found.', 'bloglypress'),
        'not_found_in_trash' => __('No portfolio items found in Trash.', 'bloglypress')
    );

    $portfolio_args = array(
        'labels'             => $portfolio_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'portfolio'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'menu_icon'          => 'dashicons-portfolio',
        'show_in_rest'       => true
    );

    // Only register if enabled in theme options
    $advanced_options = get_option('bloglypress_advanced_options', array());
    $enable_portfolio = isset($advanced_options['enable_portfolio']) ? $advanced_options['enable_portfolio'] : false;
    
    if ($enable_portfolio) {
        register_post_type('portfolio', $portfolio_args);
        
        // Register Portfolio Category taxonomy
        register_taxonomy(
            'portfolio_category',
            'portfolio',
            array(
                'hierarchical'      => true,
                'labels'            => array(
                    'name'              => _x('Portfolio Categories', 'taxonomy general name', 'bloglypress'),
                    'singular_name'     => _x('Portfolio Category', 'taxonomy singular name', 'bloglypress'),
                    'search_items'      => __('Search Portfolio Categories', 'bloglypress'),
                    'all_items'         => __('All Portfolio Categories', 'bloglypress'),
                    'parent_item'       => __('Parent Portfolio Category', 'bloglypress'),
                    'parent_item_colon' => __('Parent Portfolio Category:', 'bloglypress'),
                    'edit_item'         => __('Edit Portfolio Category', 'bloglypress'),
                    'update_item'       => __('Update Portfolio Category', 'bloglypress'),
                    'add_new_item'      => __('Add New Portfolio Category', 'bloglypress'),
                    'new_item_name'     => __('New Portfolio Category Name', 'bloglypress'),
                    'menu_name'         => __('Categories', 'bloglypress'),
                ),
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array('slug' => 'portfolio-category'),
                'show_in_rest'      => true,
            )
        );
        
        // Register Portfolio Tag taxonomy
        register_taxonomy(
            'portfolio_tag',
            'portfolio',
            array(
                'hierarchical'      => false,
                'labels'            => array(
                    'name'              => _x('Portfolio Tags', 'taxonomy general name', 'bloglypress'),
                    'singular_name'     => _x('Portfolio Tag', 'taxonomy singular name', 'bloglypress'),
                    'search_items'      => __('Search Portfolio Tags', 'bloglypress'),
                    'all_items'         => __('All Portfolio Tags', 'bloglypress'),
                    'parent_item'       => __('Parent Portfolio Tag', 'bloglypress'),
                    'parent_item_colon' => __('Parent Portfolio Tag:', 'bloglypress'),
                    'edit_item'         => __('Edit Portfolio Tag', 'bloglypress'),
                    'update_item'       => __('Update Portfolio Tag', 'bloglypress'),
                    'add_new_item'      => __('Add New Portfolio Tag', 'bloglypress'),
                    'new_item_name'     => __('New Portfolio Tag Name', 'bloglypress'),
                    'menu_name'         => __('Tags', 'bloglypress'),
                ),
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array('slug' => 'portfolio-tag'),
                'show_in_rest'      => true,
            )
        );
    }
}
add_action('init', 'bloglypress_register_post_types');
