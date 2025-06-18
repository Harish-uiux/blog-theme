<?php
/**
 * Template part for displaying single posts
 *
 * @package Hariharan
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

        <div class="entry-meta">
            <?php
            if (function_exists('hariharan_posted_on')) {
                hariharan_posted_on();
            }
            
            if (function_exists('hariharan_posted_by')) {
                hariharan_posted_by();
            }
            ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail()) : ?>
    <div class="post-thumbnail">
        <?php 
        the_post_thumbnail(
            'large', 
            array(
                'alt' => the_title_attribute(array('echo' => false)),
                'loading' => get_option('hariharan_enable_lazy_load', '1') ? 'lazy' : 'eager',
            )
        ); 
        ?>
    </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        the_content();

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'hariharan'),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php
        if (function_exists('hariharan_entry_footer')) {
            hariharan_entry_footer();
        }
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
