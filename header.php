<?php
/**
 * The header for our theme
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BloglyPress
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

<div id="page" class="site" x-data="{ mobileMenuOpen: false, searchOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }" 
     :class="{ 'dark-mode': darkMode }">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'bloglypress'); ?></a>

    <?php
    // Get header layout option
    $header_layout = get_theme_mod('bloglypress_header_layout', 'default');
    $sticky_header = get_theme_mod('bloglypress_sticky_header', true);
    $header_class = 'site-header layout-' . $header_layout;
    
    if ($sticky_header) {
        $header_class .= ' sticky-header';
    }
    ?>

    <header id="masthead" class="<?php echo esc_attr($header_class); ?>">
        <div class="container">
            <div class="header-container">
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()) :
                        the_custom_logo();
                    else :
                    ?>
                        <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                        <?php
                        $bloglypress_description = get_bloginfo('description', 'display');
                        if ($bloglypress_description || is_customize_preview()) :
                        ?>
                            <p class="site-description"><?php echo $bloglypress_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div><!-- .site-branding -->

                <nav id="site-navigation" class="main-navigation">
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" @click="mobileMenuOpen = !mobileMenuOpen">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    
                    <div class="primary-menu-container" :class="{ 'menu-open': mobileMenuOpen }">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'container'      => false,
                            )
                        );
                        ?>
                    </div>
                </nav><!-- #site-navigation -->

                <div class="header-actions">
                    <?php if (get_theme_mod('bloglypress_enable_search', true)) : ?>
                    <button class="search-toggle" @click="searchOpen = !searchOpen">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('bloglypress_enable_dark_mode', true)) : ?>
                    <button class="dark-mode-toggle" @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)">
                        <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (get_theme_mod('bloglypress_enable_search', true)) : ?>
        <div class="search-modal" x-show="searchOpen" @click.away="searchOpen = false" x-transition>
            <div class="search-container">
                <?php get_search_form(); ?>
            </div>
        </div>
        <?php endif; ?>
    </header><!-- #masthead -->
    
    <div id="content" class="site-content">
