<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package BloglyPress
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function bloglypress_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class for the sidebar position.
    if (is_single()) {
        $sidebar_position = get_theme_mod('bloglypress_single_sidebar_position', 'right');
    } else {
        $sidebar_position = get_theme_mod('bloglypress_sidebar_position', 'right');
    }
    
    if ($sidebar_position !== 'none') {
        $classes[] = 'has-sidebar';
        $classes[] = 'sidebar-' . $sidebar_position;
    } else {
        $classes[] = 'no-sidebar';
    }

    // Adds a class for the layout mode.
    $layout_mode = get_option('bloglypress_general_options', array());
    $layout_mode = isset($layout_mode['layout_mode']) ? $layout_mode['layout_mode'] : 'wide';
    $classes[] = 'layout-' . $layout_mode;

    // Blog layout class
    if (is_home() || is_archive() || is_search()) {
        $blog_layout = get_theme_mod('bloglypress_blog_layout', 'grid');
        $classes[] = 'blog-layout-' . $blog_layout;
    }

    return $classes;
}
add_filter('body_class', 'bloglypress_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function bloglypress_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'bloglypress_pingback_header');

/**
 * Get reading time for a post.
 */
function bloglypress_get_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Assuming 200 words per minute reading speed
    
    if ($reading_time < 1) {
        $reading_time = 1;
    }
    
    /* translators: %d: Reading time in minutes */
    return sprintf(_n('%d min read', '%d min read', $reading_time, 'bloglypress'), $reading_time);
}

/**
 * Display post categories.
 */
function bloglypress_post_categories() {
    $categories = get_the_category();
    if ($categories) {
        echo '<ul class="post-categories">';
        foreach ($categories as $category) {
            echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
        }
        echo '</ul>';
    }
}

/**
 * Display post tags.
 */
function bloglypress_post_tags() {
    $tags = get_the_tags();
    if ($tags) {
        echo '<ul class="post-tags">';
        foreach ($tags as $tag) {
            echo '<li><a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a></li>';
        }
        echo '</ul>';
    }
}

/**
 * Display social sharing buttons.
 */
function bloglypress_social_sharing_buttons() {
    $post_title = get_the_title();
    $post_url = get_permalink();
    $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
    
    $share_links = array(
        'facebook' => array(
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($post_url),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
            'label' => __('Share on Facebook', 'bloglypress'),
        ),
        'twitter' => array(
            'url' => 'https://twitter.com/intent/tweet?text=' . urlencode($post_title) . '&url=' . urlencode($post_url),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>',
            'label' => __('Share on Twitter', 'bloglypress'),
        ),
        'linkedin' => array(
            'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($post_url) . '&title=' . urlencode($post_title),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
            'label' => __('Share on LinkedIn', 'bloglypress'),
        ),
        'pinterest' => array(
            'url' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($post_url) . '&media=' . (!empty($post_thumbnail) ? urlencode($post_thumbnail[0]) : '') . '&description=' . urlencode($post_title),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 12h8"></path><path d="M12 8v8"></path><circle cx="12" cy="12" r="10"></circle></svg>',
            'label' => __('Share on Pinterest', 'bloglypress'),
        ),
        'email' => array(
            'url' => 'mailto:?subject=' . urlencode($post_title) . '&body=' . urlencode($post_url),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
            'label' => __('Share via Email', 'bloglypress'),
        ),
    );
    
    echo '<div class="social-sharing-buttons">';
    
    foreach ($share_links as $network => $data) {
        echo '<a href="' . esc_url($data['url']) . '" class="share-button share-' . esc_attr($network) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($data['label']) . '">';
        echo $data['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</a>';
    }
    
    echo '</div>';
}

/**
 * Add custom image sizes.
 */
function bloglypress_add_image_sizes() {
    add_image_size('bloglypress-featured', 1200, 600, true);
    add_image_size('bloglypress-grid', 600, 400, true);
    add_image_size('bloglypress-square', 400, 400, true);
}
add_action('after_setup_theme', 'bloglypress_add_image_sizes');

/**
 * Add schema markup to the head.
 */
function bloglypress_schema_markup() {
    // Check if schema is enabled
    $advanced_options = get_option('bloglypress_advanced_options', array());
    $enable_schema = isset($advanced_options['enable_schema']) ? $advanced_options['enable_schema'] : true;
    
    if (!$enable_schema) {
        return;
    }
    
    if (is_single()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink(),
            ),
            'headline' => get_the_title(),
            'description' => get_the_excerpt(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author(),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_custom_logo_url(),
                ),
            ),
        );
        
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                $schema['image'] = array(
                    '@type' => 'ImageObject',
                    'url' => $image[0],
                    'width' => $image[1],
                    'height' => $image[2],
                );
            }
        }
    } elseif (is_home() || is_front_page()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'description' => get_bloginfo('description'),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ),
        );
    }
    
    if (isset($schema)) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'bloglypress_schema_markup');

/**
 * Get custom logo URL.
 */
function get_custom_logo_url() {
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_info = wp_get_attachment_image_src($custom_logo_id, 'full');
        if ($logo_info) {
            return $logo_info[0];
        }
    }
    
    return '';
}

/**
 * Add lazyload attributes to images if enabled.
 */
function bloglypress_add_lazyload_to_images($content) {
    // Check if lazyload is enabled
    $enable_lazy_loading = get_theme_mod('bloglypress_enable_lazy_loading', true);
    
    if (!$enable_lazy_loading || is_admin() || is_feed()) {
        return $content;
    }
    
    // Don't lazy load images in AMP context
    if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        return $content;
    }
    
    // Replace image tags with lazyload attributes
    $content = preg_replace_callback('/<img([^>]+)src=([\'"])([^\'"]*)([\'"])([^>]*)>/i', 'bloglypress_add_lazyload_attributes', $content);
    
    return $content;
}
add_filter('the_content', 'bloglypress_add_lazyload_to_images', 99);
add_filter('widget_text_content', 'bloglypress_add_lazyload_to_images', 99);

/**
 * Add lazyload attributes to image tags.
 */
function bloglypress_add_lazyload_attributes($matches) {
    // Skip if image already has loading attribute or is a placeholder
    if (strpos($matches[0], 'loading=') !== false || strpos($matches[3], 'data:image') !== false) {
        return $matches[0];
    }
    
    // Add loading attribute
    $lazyload_image = sprintf(
        '<img%1$ssrc=%2$s%3$s%4$s%5$s loading="lazy">',
        $matches[1],
        $matches[2],
        $matches[3],
        $matches[4],
        $matches[5]
    );
    
    return $lazyload_image;
}
