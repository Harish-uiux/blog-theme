<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BloglyPress
 */

$show_featured_image = get_theme_mod('bloglypress_single_show_featured_image', true);
$show_meta = get_theme_mod('bloglypress_single_show_meta', true);
$show_categories = get_theme_mod('bloglypress_single_show_categories', true);
$show_tags = get_theme_mod('bloglypress_single_show_tags', true);
$show_author = get_theme_mod('bloglypress_single_show_author', true);
$show_date = get_theme_mod('bloglypress_single_show_date', true);
$show_author_box = get_theme_mod('bloglypress_show_author_box', true);
$show_sharing = get_theme_mod('bloglypress_show_social_sharing', true);
$estimated_reading_time = bloglypress_get_reading_time();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php if ($show_categories) : ?>
        <div class="entry-categories">
            <?php bloglypress_post_categories(); ?>
        </div>
        <?php endif; ?>
        
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        
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
            
            <span class="reading-time">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <?php echo esc_html($estimated_reading_time); ?>
            </span>
        </div>
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if ($show_featured_image && has_post_thumbnail()) : ?>
    <div class="post-thumbnail">
        <?php the_post_thumbnail('large'); ?>
    </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        the_content(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'bloglypress'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            )
        );

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'bloglypress'),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php if ($show_tags && has_tag()) : ?>
        <div class="entry-tags">
            <?php bloglypress_post_tags(); ?>
        </div>
        <?php endif; ?>
        
        <?php if ($show_sharing) : ?>
        <div class="social-sharing">
            <h3><?php echo esc_html__('Share This Post', 'bloglypress'); ?></h3>
            <?php bloglypress_social_sharing_buttons(); ?>
        </div>
        <?php endif; ?>
    </footer><!-- .entry-footer -->
    
    <?php if ($show_author_box) : ?>
    <div class="author-box">
        <div class="author-avatar">
            <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
        </div>
        <div class="author-description">
            <h2 class="author-title">
                <?php
                /* translators: %s: Author name */
                printf(esc_html__('About %s', 'bloglypress'), esc_html(get_the_author()));
                ?>
            </h2>
            <p class="author-bio">
                <?php the_author_meta('description'); ?>
            </p>
            <a class="author-link" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                <?php
                /* translators: %s: Author name */
                printf(esc_html__('View all posts by %s', 'bloglypress'), esc_html(get_the_author()));
                ?>
            </a>
        </div>
    </div>
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
