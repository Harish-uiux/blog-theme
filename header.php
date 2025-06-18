<?php
/**
 * The header for our theme
 *
 * @package Hariharan
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'hariharan'); ?></a>

    <?php
    // Get header settings from options
    $header_layout = get_option('hariharan_header_layout', 'default');
    $sticky_header = get_option('hariharan_sticky_header', '1') ? 'sticky-header' : '';
    ?>

    <header id="masthead" class="site-header <?php echo esc_attr($sticky_header); ?> header-layout-<?php echo esc_attr($header_layout); ?>">
        <div class="container">
            <div class="site-header-inner">
                <!-- Site branding -->
                <div class="site-branding">
                    <?php
                    // Get logo options
                    $light_logo = get_option('hariharan_logo_light', '');
                    $dark_logo = get_option('hariharan_logo_dark', '');
                    
                    if ($light_logo) : ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link">
                            <img src="<?php echo esc_url($light_logo); ?>" alt="<?php bloginfo('name'); ?>">
                        </a>
                    <?php endif;
                    
                    if ($dark_logo) : ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-dark-link">
                            <img src="<?php echo esc_url($dark_logo); ?>" alt="<?php bloginfo('name'); ?>">
                        </a>
                    <?php endif;
                    
                    if (!$light_logo && !$dark_logo) : ?>
                        <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></p>
                        <?php 
                        $description = get_bloginfo('description');
                        if ($description) : ?>
                            <p class="site-description"><?php echo $description; ?></p>
                        <?php endif;
                    endif; ?>
                </div>
                
                <!-- Navigation wrapper -->
                <div id="navigation-wrapper" class="navigation-wrapper">
                    <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'hariharan'); ?>">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id' => 'primary-menu',
                            'container' => false,
                            'fallback_cb' => '__return_false',
                        ));
                        ?>
                    </nav>
                    
                    <!-- Header actions for desktop -->
                    <div class="header-actions desktop-actions">
                        <?php if (get_option('hariharan_enable_search', '1')) : ?>
                        <button id="search-toggle" class="search-toggle" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <span class="screen-reader-text"><?php esc_html_e('Search', 'hariharan'); ?></span>
                        </button>
                        <?php endif; ?>
                        
                        <?php if (get_option('hariharan_enable_dark_mode', '1')) : ?>
                        <button id="dark-mode-toggle" class="dark-mode-toggle">
                            <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                            <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                            <span class="screen-reader-text"><?php esc_html_e('Toggle Dark Mode', 'hariharan'); ?></span>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Mobile header actions -->
                <div class="mobile-actions">
                    <?php if (get_option('hariharan_enable_search', '1')) : ?>
                    <button id="mobile-search-toggle" class="search-toggle mobile-search-toggle" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <span class="screen-reader-text"><?php esc_html_e('Search', 'hariharan'); ?></span>
                    </button>
                    <?php endif; ?>
                    
                    <?php if (get_option('hariharan_enable_dark_mode', '1')) : ?>
                    <button id="mobile-dark-mode-toggle" class="dark-mode-toggle mobile-dark-mode-toggle">
                        <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                        <span class="screen-reader-text"><?php esc_html_e('Toggle Dark Mode', 'hariharan'); ?></span>
                    </button>
                    <?php endif; ?>
                    
                    <!-- Mobile menu toggle -->
                    <button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-controls="navigation-wrapper" aria-expanded="false">
                        <span class="hamburger-icon">
                            <span class="hamburger-bar"></span>
                            <span class="hamburger-bar"></span>
                            <span class="hamburger-bar"></span>
                        </span>
                        <span class="screen-reader-text"><?php esc_html_e('Menu', 'hariharan'); ?></span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Search container -->
        <?php if (get_option('hariharan_enable_search', '1')) : ?>
        <div id="search-container" class="search-container">
            <div class="container">
                <?php get_search_form(); ?>
            </div>
        </div>
        <?php endif; ?>
    </header>

    <div id="content" class="site-content">
        <div class="container">
