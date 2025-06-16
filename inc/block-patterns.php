<?php
/**
 * Block patterns for BloglyPress
 *
 * @package BloglyPress
 */

/**
 * Register block patterns and categories.
 */
function bloglypress_register_block_patterns() {
    // Register block pattern category
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category(
            'bloglypress',
            array('label' => __('BloglyPress', 'bloglypress'))
        );
    }

    // Register block patterns
    if (function_exists('register_block_pattern')) {
        // Hero section pattern
        register_block_pattern(
            'bloglypress/hero-section',
            array(
                'title'       => __('Hero Section', 'bloglypress'),
                'description' => __('A hero section with heading, text, and button.', 'bloglypress'),
                'content'     => '<!-- wp:group {"align":"full","style":{"color":{"background":"#f8f9fa"},"spacing":{"padding":{"top":"4rem","right":"2rem","bottom":"4rem","left":"2rem"}}},"layout":{"inherit":true}} -->
<div class="wp-block-group alignfull has-background" style="background-color:#f8f9fa;padding-top:4rem;padding-right:2rem;padding-bottom:4rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","level":1,"fontSize":"huge"} -->
<h1 class="has-text-align-center has-huge-font-size">Welcome to BloglyPress</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">A modern WordPress theme for bloggers and content creators.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-background">Read More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
                'categories'  => array('bloglypress'),
            )
        );

        // Featured posts pattern
        register_block_pattern(
            'bloglypress/featured-posts',
            array(
                'title'       => __('Featured Posts Grid', 'bloglypress'),
                'description' => __('A grid of featured posts with featured image, title, and excerpt.', 'bloglypress'),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem","right":"2rem","left":"2rem"}}},"layout":{"inherit":true}} -->
<div class="wp-block-group alignfull" style="padding-top:4rem;padding-right:2rem;padding-bottom:4rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Featured Posts</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Check out our most popular articles</p>
<!-- /wp:paragraph -->

<!-- wp:query {"queryId":1,"query":{"perPage":"3","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"displayLayout":{"type":"flex","columns":3}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:post-featured-image {"isLink":true,"height":"250px"} /-->

<!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->

<!-- wp:post-excerpt {"moreText":"Read More"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->',
                'categories'  => array('bloglypress'),
            )
        );

        // Call to action pattern
        register_block_pattern(
            'bloglypress/call-to-action',
            array(
                'title'       => __('Call to Action', 'bloglypress'),
                'description' => __('A call to action section with heading, text, and button.', 'bloglypress'),
                'content'     => '<!-- wp:group {"align":"full","style":{"color":{"background":"#3857ff","text":"#ffffff"},"spacing":{"padding":{"top":"4rem","right":"2rem","bottom":"4rem","left":"2rem"}}},"layout":{"inherit":true}} -->
<div class="wp-block-group alignfull has-text-color has-background" style="background-color:#3857ff;color:#ffffff;padding-top:4rem;padding-right:2rem;padding-bottom:4rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","textColor":"white"} -->
<h2 class="has-text-align-center has-white-color has-text-color">Ready to Get Started?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color">Join thousands of satisfied users who are already using BloglyPress.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background">Subscribe Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
                'categories'  => array('bloglypress'),
            )
        );
    }
}
add_action('init', 'bloglypress_register_block_patterns');
