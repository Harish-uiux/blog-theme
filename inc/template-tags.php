<?php
/**
 * Custom template tags for this theme
 *
 * @package BloglyPress
 */

if (!function_exists('bloglypress_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function bloglypress_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x('Posted on %s', 'post date', 'bloglypress'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('bloglypress_posted_by')) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function bloglypress_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'bloglypress'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('bloglypress_entry_footer')) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function bloglypress_entry_footer() {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'bloglypress'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'bloglypress') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'bloglypress'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'bloglypress') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'bloglypress'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'bloglypress'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

if (!function_exists('bloglypress_post_thumbnail')) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function bloglypress_post_thumbnail() {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            ?>

            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                    )
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if (!function_exists('bloglypress_comment')) :
    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @param object $comment Comment to display.
     * @param array  $args    Arguments passed to wp_list_comments().
     * @param int    $depth   Depth of the current comment.
     */
    function bloglypress_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;

        switch ($comment->comment_type) :
            case 'pingback':
            case 'trackback':
                ?>
                <li class="post pingback">
                    <p><?php esc_html_e('Pingback:', 'bloglypress'); ?> <?php comment_author_link(); ?><?php edit_comment_link(esc_html__('Edit', 'bloglypress'), ' <span class="edit-link">', '</span>'); ?></p>
                </li>
                <?php
                break;
            default:
                ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment-body">
                        <footer class="comment-meta">
                            <div class="comment-author vcard">
                                <?php
                                $avatar_size = 60;
                                if ($comment->comment_parent != '0') {
                                    $avatar_size = 40;
                                }
                                echo get_avatar($comment, $avatar_size);
                                ?>
                                <b class="fn"><?php comment_author_link(); ?></b>
                            </div><!-- .comment-author -->

                            <div class="comment-metadata">
                                <a href="<?php echo esc_url(get_comment_link()); ?>">
                                    <time datetime="<?php comment_time('c'); ?>">
                                        <?php
                                        /* translators: 1: comment date, 2: comment time */
                                        printf(esc_html__('%1$s at %2$s', 'bloglypress'), get_comment_date(), get_comment_time());
                                        ?>
                                    </time>
                                </a>
                                <?php edit_comment_link(esc_html__('Edit', 'bloglypress'), ' <span class="edit-link">', '</span>'); ?>
                            </div><!-- .comment-metadata -->

                            <?php if ('0' == $comment->comment_approved) : ?>
                            <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'bloglypress'); ?></p>
                            <?php endif; ?>
                        </footer><!-- .comment-meta -->

                        <div class="comment-content">
                            <?php comment_text(); ?>
                        </div><!-- .comment-content -->

                        <div class="reply">
                            <?php
                            comment_reply_link(
                                array_merge(
                                    $args,
                                    array(
                                        'add_below' => 'comment',
                                        'depth'     => $depth,
                                        'max_depth' => $args['max_depth'],
                                    )
                                )
                            );
                            ?>
                        </div><!-- .reply -->
                    </article><!-- #comment-## -->
                </li>
                <?php
                break;
        endswitch;
    }
endif;

if (!function_exists('bloglypress_breadcrumb')) :
    /**
     * Display breadcrumbs for posts, pages, archive and search pages.
     */
    function bloglypress_breadcrumb() {
        // Check if breadcrumbs are enabled
        $general_options = get_option('bloglypress_general_options', array());
        $enable_breadcrumbs = isset($general_options['enable_breadcrumbs']) ? $general_options['enable_breadcrumbs'] : true;
        
        if (!$enable_breadcrumbs) {
            return;
        }
        
        // Skip on front page
        if (is_front_page()) {
            return;
        }
        
        echo '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">';
        
        echo '<span class="breadcrumb-item home"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'bloglypress') . '</a></span>';
        
        if (is_category() || is_single()) {
            echo '<span class="breadcrumb-separator">/</span>';
            
            if (is_single()) {
                $categories = get_the_category();
                if (!empty($categories)) {
                    echo '<span class="breadcrumb-item category"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></span>';
                    echo '<span class="breadcrumb-separator">/</span>';
                }
                
                echo '<span class="breadcrumb-item current">' . get_the_title() . '</span>';
            } else {
                echo '<span class="breadcrumb-item current">' . single_cat_title('', false) . '</span>';
            }
        } elseif (is_page()) {
            echo '<span class="breadcrumb-separator">/</span>';
            
            // Check if the page has ancestors
            $ancestors = get_post_ancestors(get_the_ID());
            if (!empty($ancestors)) {
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor) {
                    echo '<span class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . get_the_title($ancestor) . '</a></span>';
                    echo '<span class="breadcrumb-separator">/</span>';
                }
            }
            
            echo '<span class="breadcrumb-item current">' . get_the_title() . '</span>';
        } elseif (is_tag()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . single_tag_title('', false) . '</span>';
        } elseif (is_author()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . get_the_author() . '</span>';
        } elseif (is_search()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . esc_html__('Search results for: ', 'bloglypress') . get_search_query() . '</span>';
        } elseif (is_404()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . esc_html__('Page not found', 'bloglypress') . '</span>';
        } elseif (is_archive()) {
            echo '<span class="breadcrumb-separator">/</span>';
            if (is_day()) {
                echo '<span class="breadcrumb-item current">' . esc_html__('Archives for: ', 'bloglypress') . get_the_date() . '</span>';
            } elseif (is_month()) {
                echo '<span class="breadcrumb-item current">' . esc_html__('Archives for: ', 'bloglypress') . get_the_date('F Y') . '</span>';
            } elseif (is_year()) {
                echo '<span class="breadcrumb-item current">' . esc_html__('Archives for: ', 'bloglypress') . get_the_date('Y') . '</span>';
            } else {
                echo '<span class="breadcrumb-item current">' . esc_html__('Archives', 'bloglypress') . '</span>';
            }
        }
        
        echo '</div>';
    }
endif;
