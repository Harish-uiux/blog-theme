<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Hariharan
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function hariharan_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class if sidebar is active.
    if (is_active_sidebar('sidebar-1') && !is_page()) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter('body_class', 'hariharan_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function hariharan_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'hariharan_pingback_header');

/**
 * Changes the excerpt length
 */
function hariharan_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'hariharan_excerpt_length');

/**
 * Changes the excerpt more string
 */
function hariharan_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'hariharan_excerpt_more');

/**
 * Add proper image sizes
 */
function hariharan_add_image_sizes() {
    add_image_size('hariharan-featured', 1200, 600, true);
    add_image_size('hariharan-card', 600, 400, true);
}
add_action('after_setup_theme', 'hariharan_add_image_sizes');

/**
 * Fix archive title prefix
 */
function hariharan_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }

    return $title;
}
add_filter('get_the_archive_title', 'hariharan_archive_title');
