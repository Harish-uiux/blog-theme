<?php
/**
 * Template part for displaying the default header
 *
 * @package Hariharan
 */

// Check if sticky header is enabled
$sticky_class = get_option('hariharan_sticky_header', '1') ? 'sticky-header' : '';
?>

<header id="masthead" class="site-header <?php echo esc_attr($sticky_class); ?>">
    <div class="container">
        <div class="site-header-inner">
            <div class="site-branding">
                <?php
                // Get custom logos from theme options
                $light_logo = get_option('hariharan_logo_light', '');
                $dark_logo = get_option('hariharan_logo_dark', '');
                
                if ($light_logo) :
                ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                        <img src="<?php echo esc_url($light_logo); ?>" alt="<?php bloginfo('name'); ?>" class="custom-logo">
                    </a>
                <?php
                endif;
                
                if ($dark_logo) :
                ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-dark-link" rel="home">
                        <img src="<?php echo esc_url($dark_logo); ?>" alt="<?php bloginfo('name'); ?>" class="custom-logo-dark">
                    </a>
                <?php
                endif;
                
                // If no custom logo, show site title and description
                if (!$light_logo && !$dark_logo) :
                    if (is_front_page() && is_home()) :
                ?>
                    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                <?php
                    else :
                ?>
                    <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                <?php
                    endif;
                    
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) :
                ?>
                    <p class="site-description"><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                <?php
                    endif;
                endif;
                ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'hariharan'); ?>">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="screen-reader-text"><?php esc_html_e('Menu', 'hariharan'); ?></span>
                    <span class="menu-toggle-icon"></span>
                </button>
                
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => '__return_false',
                    )
                );
                ?>
            </nav><!-- #site-navigation -->

            <div class="header-actions">
                <?php if (get_option('hariharan_enable_search', '1')) : ?>
                <button id="search-toggle" class="search-toggle" aria-expanded="false" aria-controls="search-form-container">
                    <span class="screen-reader-text"><?php esc_html_e('Search', 'hariharan'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
                <?php endif; ?>

                <?php if (get_option('hariharan_enable_dark_mode', '1')) : ?>
                <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'hariharan'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sun-icon"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="moon-icon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if (get_option('hariharan_enable_search', '1')) : ?>
    <div id="search-form-container" class="search-form-container">
        <div class="container">
            <?php get_search_form(); ?>
        </div>
    </div>
    <?php endif; ?>
</header><!-- #masthead -->
