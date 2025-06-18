<?php
/**
 * Template part for displaying posts
 *
 * @package Hariharan
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;

        if ('post' === get_post_type()) :
            ?>
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
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail() && !is_singular()) : ?>
    <div class="post-thumbnail">
        <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php 
            the_post_thumbnail(
                'post-thumbnail', 
                array(
                    'alt' => the_title_attribute(array('echo' => false)),
                    'loading' => get_option('hariharan_enable_lazy_load', '1') ? 'lazy' : 'eager',
                )
            ); 
            ?>
        </a>
    </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        if (is_singular()) :
            if (has_post_thumbnail()) :
                the_post_thumbnail(
                    'large', 
                    array(
                        'alt' => the_title_attribute(array('echo' => false)),
                        'loading' => get_option('hariharan_enable_lazy_load', '1') ? 'lazy' : 'eager',
                    )
                );
            endif;
            
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'hariharan'),
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
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'hariharan'),
                    'after'  => '</div>',
                )
            );
        else :
            the_excerpt();
            ?>
            <a href="<?php the_permalink(); ?>" class="post-card-readmore"><?php esc_html_e('Read More', 'hariharan'); ?></a>
        <?php endif; ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php
        if (function_exists('hariharan_entry_footer')) {
            hariharan_entry_footer();
        }
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
