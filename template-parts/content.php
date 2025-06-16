<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BloglyPress
 */

$blog_layout = get_theme_mod('bloglypress_blog_layout', 'grid');
$show_thumbnail = get_theme_mod('bloglypress_show_thumbnail', true);
$show_excerpt = get_theme_mod('bloglypress_show_excerpt', true);
$show_meta = get_theme_mod('bloglypress_show_meta', true);
$show_categories = get_theme_mod('bloglypress_show_categories', true);
$show_author = get_theme_mod('bloglypress_show_author', true);
$show_date = get_theme_mod('bloglypress_show_date', true);
$show_comments = get_theme_mod('bloglypress_show_comments_count', true);
$excerpt_length = get_theme_mod('bloglypress_excerpt_length', 25);

$post_classes = array('post-item');
$post_classes[] = 'layout-' . $blog_layout;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_classes); ?>>
    <?php if ($show_thumbnail && has_post_thumbnail()) : ?>
    <div class="post-thumbnail">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium_large'); ?>
        </a>
        
        <?php if ($show_categories) : ?>
        <div class="post-categories">
            <?php
            $categories = get_the_category();
            if ($categories) {
                $output = '<ul>';
                foreach ($categories as $category) {
                    $output .= '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                }
                $output .= '</ul>';
                echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="post-content">
        <header class="entry-header">
            <?php
            if (is_singular()) :
                the_title('<h1 class="entry-title">', '</h1>');
            else :
                the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
            endif;
            ?>
            
            <?php if ($show_meta) : ?>
            <div class="entry-meta">
                <?php if ($show_date) : ?>
                <span class="posted-on">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                </span>
                <?php endif; ?>
                
                <?php if ($show_author) : ?>
                <span class="byline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span class="author vcard">
                        <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                            <?php echo esc_html(get_the_author()); ?>
                        </a>
                    </span>
                </span>
                <?php endif; ?>
                
                <?php if ($show_comments && comments_open()) : ?>
                <span class="comments-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <?php comments_popup_link('0', '1', '%'); ?>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </header><!-- .entry-header -->

        <?php if ($show_excerpt) : ?>
        <div class="entry-summary">
            <?php
            // Generate custom excerpt
            $excerpt = get_the_excerpt();
            $excerpt = wp_trim_words($excerpt, $excerpt_length, '...');
            echo '<p>' . wp_kses_post($excerpt) . '</p>';
            ?>
            
            <a href="<?php the_permalink(); ?>" class="read-more-link">
                <?php echo esc_html__('Read More', 'bloglypress'); ?> 
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div><!-- .entry-summary -->
        <?php endif; ?>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
