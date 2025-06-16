<?php
/**
 * The main template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BloglyPress
 */

get_header();

// Get layout option
$blog_layout = get_theme_mod('bloglypress_blog_layout', 'grid');
$sidebar_position = get_theme_mod('bloglypress_sidebar_position', 'right');
$container_class = 'content-area';

if ($sidebar_position !== 'none') {
    $container_class .= ' has-sidebar';
    $container_class .= ' sidebar-' . $sidebar_position;
}
?>

<div id="primary" class="<?php echo esc_attr($container_class); ?>">
    <main id="main" class="site-main">
        <?php if (is_home() && !is_front_page() && get_theme_mod('bloglypress_show_blog_header', true)) : ?>
            <header class="page-header">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
                <?php
                $blog_description = get_theme_mod('bloglypress_blog_description', '');
                if (!empty($blog_description)) :
                ?>
                    <div class="archive-description">
                        <?php echo wp_kses_post($blog_description); ?>
                    </div>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if (have_posts()) : ?>
            
            <?php if (get_theme_mod('bloglypress_featured_post', true) && is_home() && !is_paged()) :
                // Display featured post
                get_template_part('template-parts/featured-post');
            endif; ?>

            <div class="posts-container layout-<?php echo esc_attr($blog_layout); ?>">
                <?php
                // Start the Loop
                while (have_posts()) :
                    the_post();
                    
                    get_template_part('template-parts/content', get_post_type());
                    
                endwhile;
                ?>
            </div>

            <?php
            // Pagination
            get_template_part('template-parts/pagination');
            
        else :
            
            get_template_part('template-parts/content', 'none');
            
        endif;
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
if ($sidebar_position !== 'none') {
    get_sidebar();
}
get_footer();
