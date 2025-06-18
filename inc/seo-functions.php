<?php
/**
 * SEO functions for the Hariharan theme
 *
 * @package Hariharan
 */

/**
 * Add Open Graph meta tags to the head.
 */
function hariharan_add_opengraph_tags() {
    // Check if Open Graph tags are enabled
    if (get_option('hariharan_enable_open_graph', '1') !== '1') {
        return;
    }
    
    global $post;
    
    if (is_singular() && $post) {
        // Get post excerpt
        $excerpt = has_excerpt($post->ID) ? get_the_excerpt() : wp_trim_words(strip_shortcodes($post->post_content), 55);
        $excerpt = strip_tags($excerpt);
        
        // Output Open Graph tags
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($excerpt) . '" />' . "\n";
        
        // Add featured image if available
        if (has_post_thumbnail($post->ID)) {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
            if ($thumbnail) {
                echo '<meta property="og:image" content="' . esc_url($thumbnail[0]) . '" />' . "\n";
                echo '<meta property="og:image:width" content="' . esc_attr($thumbnail[1]) . '" />' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr($thumbnail[2]) . '" />' . "\n";
            }
        }
    } else {
        // For non-singular pages (archive, home, etc.)
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        
        $description = get_bloginfo('description');
        if (empty($description)) {
            $description = get_option('hariharan_default_meta_description', '');
        }
        
        if (!empty($description)) {
            echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
            echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'hariharan_add_opengraph_tags', 5);

/**
 * Add schema markup.
 */
function hariharan_add_schema_markup() {
    // Check if schema markup is enabled
    if (get_option('hariharan_enable_schema', '1') !== '1') {
        return;
    }
    
    global $post;
    
    // Website Schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
    );
    
    // Add search action
    if (get_option('hariharan_enable_search', '1') === '1') {
        $schema['potentialAction'] = array(
            '@type' => 'SearchAction',
            'target' => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string',
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    
    // Organization Schema
    $organization = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
    );
    
    // Add logo if available
    $light_logo = get_option('hariharan_logo_light', '');
    if (!empty($light_logo)) {
        $organization['logo'] = $light_logo;
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($organization) . '</script>' . "\n";
    
    // Add specific schema for posts/pages
    if (is_singular() && $post) {
        $article_schema = array(
            '@context' => 'https://schema.org',
            '@type' => is_page() ? 'WebPage' : 'Article',
            'headline' => get_the_title(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author(),
            ),
            'publisher' => $organization,
        );
        
        // Add featured image
        if (has_post_thumbnail()) {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($thumbnail) {
                $article_schema['image'] = array(
                    '@type' => 'ImageObject',
                    'url' => $thumbnail[0],
                    'width' => $thumbnail[1],
                    'height' => $thumbnail[2],
                );
            }
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($article_schema) . '</script>' . "\n";
        
        // Add breadcrumb schema
        hariharan_add_breadcrumb_schema();
    }
}
add_action('wp_head', 'hariharan_add_schema_markup');

/**
 * Add breadcrumb schema.
 */
function hariharan_add_breadcrumb_schema() {
    $breadcrumbs = array();
    $position = 1;
    
    // Home
    $breadcrumbs[] = array(
        '@type' => 'ListItem',
        'position' => $position,
        'name' => __('Home', 'hariharan'),
        'item' => home_url(),
    );
    
    // Build breadcrumb path based on content type
    if (is_category() || is_single()) {
        // Add category
        if (is_category() || is_single()) {
            $cats = get_the_category();
            if ($cats) {
                $position++;
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $cats[0]->name,
                    'item' => get_category_link($cats[0]->term_id),
                );
            }
        }
        
        // Add post title for single posts
        if (is_single()) {
            $position++;
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => get_the_title(),
                'item' => get_permalink(),
            );
        }
    } elseif (is_page()) {
        // Add page title
        $position++;
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink(),
        );
    }
    
    // Create the breadcrumb schema
    $breadcrumb_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbs,
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($breadcrumb_schema) . '</script>' . "\n";
}
