<?php
/**
 * Template part for displaying related posts
 *
 * @package BloglyPress
 */

// Get current post categories
$categories = get_the_category();

if ($categories) {
    $category_ids = array();
    
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    $related_count = get_theme_mod('bloglypress_related_posts_count', 3);
    
    $args = array(
        'category__in'        => $category_ids,
        'post__not_in'        => array(get_the_ID()),
        'posts_per_page'      => $related_count,
        'ignore_sticky_posts' => 1,
    );
    
    $related_query = new WP_Query($args);
    
    if ($related_query->have_posts()) :
    ?>
    <div class="related-posts">
        <h3 class="related-posts-title"><?php echo esc_html__('Related Posts', 'bloglypress'); ?></h3>
        
        <div class="related-posts-grid">
            <?php
            while ($related_query->have_posts()) :
                $related_query->the_post();
            ?>
                <article class="related-post">
                    <?php if (has_post_thumbnail()) : ?>
                    <div class="related-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="related-content">
                        <h4 class="related-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        
                        <div class="related-meta">
                            <span class="related-date"><?php echo get_the_date(); ?></span>
                        </div>
                    </div>
                </article>
            <?php
            endwhile;
            ?>
        </div>
    </div>
    <?php
    endif;
    wp_reset_postdata();
}
