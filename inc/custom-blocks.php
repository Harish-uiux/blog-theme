<?php
/**
 * Custom Blocks for Gutenberg
 *
 * @package BloglyPress
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom block category
 */
function bloglypress_block_category($categories) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'bloglypress',
                'title' => __('BloglyPress Blocks', 'bloglypress'),
                'icon'  => 'layout',
            ),
        )
    );
}
add_filter('block_categories_all', 'bloglypress_block_category', 10, 2);

/**
 * Register custom blocks
 */
function bloglypress_register_blocks() {
    // Check if Gutenberg is active
    if (!function_exists('register_block_type')) {
        return;
    }
    
    // Register scripts and styles for the editor
    wp_register_script(
        'bloglypress-editor-script',
        BLOGLYPRESS_URI . '/assets/js/blocks/editor.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
        BLOGLYPRESS_VERSION,
        true
    );
    
    wp_register_style(
        'bloglypress-editor-style',
        BLOGLYPRESS_URI . '/assets/css/blocks/editor.css',
        array('wp-edit-blocks'),
        BLOGLYPRESS_VERSION
    );
    
    // Register script for the frontend
    wp_register_script(
        'bloglypress-frontend-script',
        BLOGLYPRESS_URI . '/assets/js/blocks/frontend.js',
        array(),
        BLOGLYPRESS_VERSION,
        true
    );
    
    wp_register_style(
        'bloglypress-frontend-style',
        BLOGLYPRESS_URI . '/assets/css/blocks/frontend.css',
        array(),
        BLOGLYPRESS_VERSION
    );
    
    // Register featured posts block
    register_block_type('bloglypress/featured-posts', array(
        'editor_script' => 'bloglypress-editor-script',
        'editor_style'  => 'bloglypress-editor-style',
        'script'        => 'bloglypress-frontend-script',
        'style'         => 'bloglypress-frontend-style',
        'render_callback' => 'bloglypress_render_featured_posts_block',
        'attributes'      => array(
            'numberOfPosts' => array(
                'type'    => 'number',
                'default' => 3,
            ),
            'category' => array(
                'type'    => 'string',
                'default' => '',
            ),
            'layout' => array(
                'type'    => 'string',
                'default' => 'grid',
            ),
            'showFeaturedImage' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'showExcerpt' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'showMeta' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
        ),
    ));
    
    // Register author profile block
    register_block_type('bloglypress/author-profile', array(
        'editor_script' => 'bloglypress-editor-script',
        'editor_style'  => 'bloglypress-editor-style',
        'script'        => 'bloglypress-frontend-script',
        'style'         => 'bloglypress-frontend-style',
        'render_callback' => 'bloglypress_render_author_profile_block',
        'attributes'      => array(
            'authorId' => array(
                'type'    => 'number',
                'default' => 1,
            ),
            'showAvatar' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'showBio' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'showSocial' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
        ),
    ));
    
    // Register content table of contents block
    register_block_type('bloglypress/table-of-contents', array(
        'editor_script' => 'bloglypress-editor-script',
        'editor_style'  => 'bloglypress-editor-style',
        'script'        => 'bloglypress-frontend-script',
        'style'         => 'bloglypress-frontend-style',
        'render_callback' => 'bloglypress_render_table_of_contents_block',
        'attributes'      => array(
            'title' => array(
                'type'    => 'string',
                'default' => __('Table of Contents', 'bloglypress'),
            ),
            'showToggle' => array(
                'type'    => 'boolean',
                'default' => true,
            ),
            'headingLevels' => array(
                'type'    => 'array',
                'default' => array(2, 3, 4),
            ),
        ),
    ));
}
add_action('init', 'bloglypress_register_blocks');

/**
 * Render featured posts block
 */
function bloglypress_render_featured_posts_block($attributes) {
    $args = array(
        'posts_per_page' => $attributes['numberOfPosts'],
        'post_status'    => 'publish',
    );
    
    if (!empty($attributes['category'])) {
        $args['category_name'] = $attributes['category'];
    }
    
    $featured_posts = get_posts($args);
    
    if (empty($featured_posts)) {
        return '<p>' . __('No posts found.', 'bloglypress') . '</p>';
    }
    
    $layout = $attributes['layout'];
    $show_featured_image = $attributes['showFeaturedImage'];
    $show_excerpt = $attributes['showExcerpt'];
    $show_meta = $attributes['showMeta'];
    
    ob_start();
    ?>
    <div class="bloglypress-featured-posts layout-<?php echo esc_attr($layout); ?>">
        <?php foreach ($featured_posts as $post) : ?>
            <?php setup_postdata($post); ?>
            <article class="featured-post">
                <?php if ($show_featured_image && has_post_thumbnail($post->ID)) : ?>
                    <div class="featured-post-thumbnail">
                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                            <?php echo get_the_post_thumbnail($post->ID, 'medium_large'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="featured-post-content">
                    <h3 class="featured-post-title">
                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                            <?php echo esc_html(get_the_title($post->ID)); ?>
                        </a>
                    </h3>
                    
                    <?php if ($show_meta) : ?>
                        <div class="featured-post-meta">
                            <span class="featured-post-date">
                                <?php echo esc_html(get_the_date('', $post->ID)); ?>
                            </span>
                            <?php if (get_the_category($post->ID)) : ?>
                                <span class="featured-post-category">
                                    <?php 
                                    $categories = get_the_category($post->ID);
                                    if (!empty($categories)) {
                                        echo esc_html($categories[0]->name);
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($show_excerpt) : ?>
                        <div class="featured-post-excerpt">
                            <?php echo wp_kses_post(get_the_excerpt($post->ID)); ?>
                        </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="read-more">
                        <?php esc_html_e('Read More', 'bloglypress'); ?>
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
        <?php wp_reset_postdata(); ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render author profile block
 */
function bloglypress_render_author_profile_block($attributes) {
    $author_id = $attributes['authorId'];
    $show_avatar = $attributes['showAvatar'];
    $show_bio = $attributes['showBio'];
    $show_social = $attributes['showSocial'];
    
    $author_data = get_userdata($author_id);
    
    if (!$author_data) {
        return '<p>' . __('Author not found.', 'bloglypress') . '</p>';
    }
    
    ob_start();
    ?>
       <div class="bloglypress-author-profile">
        <?php if ($show_avatar) : ?>
            <div class="author-avatar">
                <?php echo get_avatar($author_id, 120); ?>
            </div>
        <?php endif; ?>
        
        <div class="author-content">
            <h3 class="author-name"><?php echo esc_html($author_data->display_name); ?></h3>
            
            <?php if ($show_bio && !empty($author_data->description)) : ?>
                <div class="author-bio">
                    <?php echo wp_kses_post($author_data->description); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($show_social) : ?>
                <div class="author-social">
                    <?php
                    $social_links = array(
                        'website' => array(
                            'url' => get_the_author_meta('url', $author_id),
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
                        ),
                        'twitter' => array(
                            'url' => get_the_author_meta('twitter', $author_id),
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>',
                        ),
                        'facebook' => array(
                            'url' => get_the_author_meta('facebook', $author_id),
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
                        ),
                        'linkedin' => array(
                            'url' => get_the_author_meta('linkedin', $author_id),
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
                        ),
                        'instagram' => array(
                            'url' => get_the_author_meta('instagram', $author_id),
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
                        ),
                    );
                    
                    foreach ($social_links as $network => $data) {
                        if (!empty($data['url'])) {
                            echo '<a href="' . esc_url($data['url']) . '" class="social-icon ' . esc_attr($network) . '" target="_blank" rel="noopener noreferrer">';
                            echo $data['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo '</a>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="author-posts-link">
                <?php esc_html_e('View all posts', 'bloglypress'); ?>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render table of contents block
 */
function bloglypress_render_table_of_contents_block($attributes) {
    global $post;
    
    if (!is_singular()) {
        return '';
    }
    
    $title = $attributes['title'];
    $show_toggle = $attributes['showToggle'];
    $heading_levels = $attributes['headingLevels'];
    
    // Get post content
    $content = $post->post_content;
    
    // Find all headings
    $pattern = '/<h([' . implode('', $heading_levels) . '])(.*?)>(.*?)<\/h\1>/i';
    preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
    
    if (empty($matches)) {
        return '';
    }
    
    // Generate table of contents
    ob_start();
    ?>
    <div class="bloglypress-table-of-contents" x-data="{ open: true }">
        <div class="toc-header">
            <h3 class="toc-title"><?php echo esc_html($title); ?></h3>
            
            <?php if ($show_toggle) : ?>
                <button class="toc-toggle" @click="open = !open">
                    <span x-show="open"><?php esc_html_e('Hide', 'bloglypress'); ?></span>
                    <span x-show="!open"><?php esc_html_e('Show', 'bloglypress'); ?></span>
                </button>
            <?php endif; ?>
        </div>
        
        <div class="toc-content" x-show="open">
            <ul class="toc-list">
                <?php
                $current_level = 0;
                $previous_level = 0;
                
                foreach ($matches as $match) {
                    $level = (int) $match[1];
                    $title = strip_tags($match[3]);
                    $id = sanitize_title($title);
                    
                    // Add id to the heading in the content
                    $content = str_replace($match[0], '<h' . $level . $match[2] . ' id="' . $id . '">' . $match[3] . '</h' . $level . '>', $content);
                    
                    // Adjust list structure based on heading level
                    if ($level > $previous_level) {
                        echo '<ul class="toc-sublist">';
                        $current_level++;
                    } elseif ($level < $previous_level) {
                        echo str_repeat('</ul>', $previous_level - $level);
                        $current_level -= ($previous_level - $level);
                    }
                    
                    echo '<li class="toc-item level-' . esc_attr($level) . '"><a href="#' . esc_attr($id) . '">' . esc_html($title) . '</a></li>';
                    
                    $previous_level = $level;
                }
                
                // Close any remaining lists
                echo str_repeat('</ul>', $current_level);
                ?>
            </ul>
        </div>
    </div>
    <?php
    
    // Update post content with IDs
    $post->post_content = $content;
    
    return ob_get_clean();
}

