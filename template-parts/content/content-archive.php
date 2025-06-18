<?php
/**
 * Template part for displaying posts in an archive
 *
 * @package Hariharan
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
    <div class="post-card-image">
        <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php 
            the_post_thumbnail(
                'medium_large', 
                array(
                    'alt' => the_title_attribute(array('echo' => false)),
                    'loading' => get_option('hariharan_enable_lazy_load', '1') ? 'lazy' : 'eager',
                )
            ); 
            ?>
        </a>
    </div>
    <?php endif; ?>

    <div class="post-card-content">
        <header class="entry-header">
            <?php the_title('<h2 class="post-card-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>

            <?php if ('post' === get_post_type()) : ?>
            <div class="post-card-meta">
                <?php
                $posted_on = '';
                if (function_exists('hariharan_posted_on')) {
                    ob_start();
                    hariharan_posted_on();
                    $posted_on = ob_get_clean();
                }
                echo $posted_on;
                ?>
            </div>
            <?php endif; ?>
        </header>

        <div class="post-card-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="post-card-readmore"><?php esc_html_e('Read More', 'hariharan'); ?></a>
    </div>
</article>
