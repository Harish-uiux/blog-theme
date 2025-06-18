<?php
/**
 * Custom search form for Hariharan theme
 *
 * @package Hariharan
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="search-field" class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'hariharan' ); ?></label>
    <input type="search" id="search-field" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'hariharan' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x( 'Submit search', 'submit button', 'hariharan' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
    </button>
</form>
