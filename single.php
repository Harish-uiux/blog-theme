<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package BloglyPress
 */

get_header();

// Get layout option
$sidebar_position = get_theme_mod('bloglypress_single_sidebar_position', 'right');
$container_class = 'content-area';

if ($sidebar_position !== 'none') {
    $container_class .= ' has-sidebar';
    $container_class .= ' sidebar-' . $sidebar_position;
}
?>

<div id="primary" class="<?php echo esc_attr($container_class); ?>">
    <main id="main" class="site-main">

        <?php
        while (have_posts()) :
            the_post();

            get_template_part('template-parts/content', 'single');

            if (get_theme_mod('bloglypress_show_post_navigation', true)) :
                the_post_navigation(
                    array(
                        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'bloglypress') . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'bloglypress') . '</span> <span class="nav-title">%title</span>',
                    )
                );
            endif;

            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

            // Related posts
            if (get_theme_mod('bloglypress_show_related_posts', true)) :
                get_template_part('template-parts/related-posts');
            endif;

        endwhile; // End of the loop.
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
if ($sidebar_position !== 'none') {
    get_sidebar();
}
get_footer();
