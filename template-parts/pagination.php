<?php
/**
 * Template part for displaying pagination
 *
 * @package BloglyPress
 */

$pagination_type = get_theme_mod('bloglypress_pagination_type', 'numeric');

if ($pagination_type === 'numeric') {
    echo '<div class="pagination numeric-pagination">';
    
    the_posts_pagination(
        array(
            'mid_size'           => 2,
            'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
            'next_text'          => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
            'screen_reader_text' => __('Posts navigation', 'bloglypress'),
        )
    );
    
    echo '</div>';
} else {
    echo '<div class="pagination prev-next-pagination">';
    
    the_posts_navigation(
        array(
            'prev_text' => '<span class="nav-previous-label">' . __('Older posts', 'bloglypress') . '</span>',
            'next_text' => '<span class="nav-next-label">' . __('Newer posts', 'bloglypress') . '</span>',
        )
    );
    
    echo '</div>';
}
