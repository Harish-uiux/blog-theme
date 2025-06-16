<?php
/**
 * Block styles for BloglyPress
 *
 * @package BloglyPress
 */

/**
 * Register custom block styles
 */
function bloglypress_register_block_styles() {
    // Register block styles for paragraphs
    register_block_style(
        'core/paragraph',
        array(
            'name'         => 'bloglypress-highlight',
            'label'        => __('Highlight', 'bloglypress'),
            'inline_style' => '
                .is-style-bloglypress-highlight {
                    background-color: var(--bloglypress-secondary-color);
                    color: #ffffff;
                    padding: 1rem;
                    border-radius: 4px;
                }
            ',
        )
    );
    
    // Register block styles for headings
    register_block_style(
        'core/heading',
        array(
            'name'         => 'bloglypress-underline',
            'label'        => __('Underline', 'bloglypress'),
            'inline_style' => '
                .is-style-bloglypress-underline {
                    position: relative;
                    padding-bottom: 0.5rem;
                }
                .is-style-bloglypress-underline::after {
                    content: "";
                    position: absolute;
                    left: 0;
                    bottom: 0;
                    width: 4rem;
                    height: 3px;
                    background-color: var(--bloglypress-primary-color);
                }
            ',
        )
    );
    
    // Register block styles for buttons
    register_block_style(
        'core/button',
        array(
            'name'         => 'bloglypress-outline',
            'label'        => __('Outline', 'bloglypress'),
            'inline_style' => '
                .is-style-bloglypress-outline .wp-block-button__link {
                    background-color: transparent;
                    border: 2px solid var(--bloglypress-primary-color);
                    color: var(--bloglypress-primary-color);
                }
                .is-style-bloglypress-outline .wp-block-button__link:hover {
                    background-color: var(--bloglypress-primary-color);
                    color: #ffffff;
                }
            ',
        )
    );
    
    // Register block styles for images
    register_block_style(
        'core/image',
        array(
            'name'         => 'bloglypress-rounded',
            'label'        => __('Rounded', 'bloglypress'),
            'inline_style' => '
                .is-style-bloglypress-rounded img {
                    border-radius: 12px;
                    overflow: hidden;
                }
            ',
        )
    );
    
    // Register block styles for quotes
    register_block_style(
        'core/quote',
        array(
            'name'         => 'bloglypress-fancy-quote',
            'label'        => __('Fancy Quote', 'bloglypress'),
            'inline_style' => '
                .is-style-bloglypress-fancy-quote {
                    border-left: 4px solid var(--bloglypress-primary-color);
                    padding: 1.5rem;
                    background-color: var(--bloglypress-light-background);
                    font-size: 1.2rem;
                    position: relative;
                }
                .is-style-bloglypress-fancy-quote::before {
                    content: "\201C";
                    position: absolute;
                    top: -20px;
                    left: 10px;
                    font-size: 3rem;
                    color: var(--bloglypress-primary-color);
                    opacity: 0.3;
                }
            ',
        )
    );
}
add_action('init', 'bloglypress_register_block_styles');
