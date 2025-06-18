<?php
/**
 * Admin settings registration for Hariharan theme
 *
 * @package Hariharan
 */

/**
 * Register theme settings
 */
function hariharan_register_settings() {
    // General settings
    register_setting('hariharan_options', 'hariharan_logo_light');
    register_setting('hariharan_options', 'hariharan_logo_dark');
    register_setting('hariharan_options', 'hariharan_enable_dark_mode');
    register_setting('hariharan_options', 'hariharan_default_dark_mode');
    
    // Header settings
    register_setting('hariharan_options', 'hariharan_header_layout');
    register_setting('hariharan_options', 'hariharan_sticky_header');
    register_setting('hariharan_options', 'hariharan_enable_search');
    
    // Footer settings
    register_setting('hariharan_options', 'hariharan_footer_layout');
    register_setting('hariharan_options', 'hariharan_footer_widget_count');
    register_setting('hariharan_options', 'hariharan_footer_copyright');
    
    // Color settings
    register_setting('hariharan_options', 'hariharan_primary_color');
    register_setting('hariharan_options', 'hariharan_secondary_color');
    register_setting('hariharan_options', 'hariharan_bg_color_light');
    register_setting('hariharan_options', 'hariharan_text_color_light');
    register_setting('hariharan_options', 'hariharan_bg_color_dark');
    register_setting('hariharan_options', 'hariharan_text_color_dark');
    
    // Typography settings
    register_setting('hariharan_options', 'hariharan_body_font');
    register_setting('hariharan_options', 'hariharan_heading_font');
    register_setting('hariharan_options', 'hariharan_body_font_size');
    register_setting('hariharan_options', 'hariharan_line_height');
    
    // SEO settings
    register_setting('hariharan_options', 'hariharan_enable_schema');
    register_setting('hariharan_options', 'hariharan_enable_open_graph');
    register_setting('hariharan_options', 'hariharan_default_meta_description');
    
    // Advanced settings
    register_setting('hariharan_options', 'hariharan_enable_lazy_load');
    register_setting('hariharan_options', 'hariharan_custom_css');
    register_setting('hariharan_options', 'hariharan_custom_js');
}
add_action('admin_init', 'hariharan_register_settings');

/**
 * Add custom CSS from theme options
 */
/**
 * Add custom CSS from theme options
 */
function hariharan_add_custom_css() {
    // Add dynamic CSS for theme options
    $primary_color = get_option('hariharan_primary_color', '#4a6cf7');
    $secondary_color = get_option('hariharan_secondary_color', '#6d8dff');
    $bg_color_light = get_option('hariharan_bg_color_light', '#ffffff');
    $text_color_light = get_option('hariharan_text_color_light', '#333333');
    $bg_color_dark = get_option('hariharan_bg_color_dark', '#121212');
    $text_color_dark = get_option('hariharan_text_color_dark', '#e0e0e0');
    
    $body_font = get_option('hariharan_body_font', 'system');
    $heading_font = get_option('hariharan_heading_font', 'system');
    $body_font_size = get_option('hariharan_body_font_size', '16');
    $line_height = get_option('hariharan_line_height', '1.6');
    
    $header_layout = get_option('hariharan_header_layout', 'default');
    
    $font_families = array(
        'system' => "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif",
        'poppins' => "'Poppins', sans-serif",
        'roboto' => "'Roboto', sans-serif",
        'open-sans' => "'Open Sans', sans-serif",
        'playfair' => "'Playfair Display', serif",
    );
    
    $body_font_family = isset($font_families[$body_font]) ? $font_families[$body_font] : $font_families['system'];
    $heading_font_family = isset($font_families[$heading_font]) ? $font_families[$heading_font] : $font_families['system'];
    
    // Google Fonts
    if ($body_font !== 'system' || $heading_font !== 'system') {
        $google_fonts = array();
        
        if ($body_font !== 'system') {
            $google_fonts[] = str_replace("'", '', explode(',', $font_families[$body_font])[0]) . ':400,500,700';
        }
        
        if ($heading_font !== 'system' && $heading_font !== $body_font) {
            $google_fonts[] = str_replace("'", '', explode(',', $font_families[$heading_font])[0]) . ':700';
        }
        
        if (!empty($google_fonts)) {
            wp_enqueue_style(
                'hariharan-google-fonts',
                'https://fonts.googleapis.com/css2?family=' . implode('&family=', $google_fonts) . '&display=swap',
                array(),
                null
            );
        }
    }
    
    // Header layout specific CSS
    $header_css = '';
    
    if ($header_layout === 'transparent') {
        $header_css = "
            .site-header {
                background-color: transparent;
                position: absolute;
                width: 100%;
                border-bottom: none;
                box-shadow: none;
            }
            
            [data-theme='dark'] .site-header {
                background-color: transparent;
            }
            
            .site-header .site-title a,
            .site-header .site-description,
            .site-header .main-navigation a,
            .site-header .search-toggle,
            .site-header .dark-mode-toggle,
            .site-header .mobile-menu-toggle,
            .site-header .hamburger-bar {
                color: #ffffff;
                background-color: transparent;
            }
            
            .site-header .hamburger-bar {
                background-color: #ffffff;
            }
            
            .site-header .main-navigation a:hover,
            .site-header .search-toggle:hover,
            .site-header .dark-mode-toggle:hover {
                color: {$primary_color};
            }
            
            @media (max-width: 991px) {
                .mobile-menu-active .navigation-wrapper {
                    background-color: rgba(0,0,0,0.9);
                }
                
                .mobile-menu-active .main-navigation a,
                .mobile-menu-active .search-toggle,
                .mobile-menu-active .dark-mode-toggle {
                    color: #ffffff;
                }
                
                .mobile-menu-active .header-actions {
                    border-top-color: rgba(255,255,255,0.1);
                }
            }
        ";
    } elseif ($header_layout === 'centered') {
        $header_css = "
            @media (min-width: 992px) {
                .site-header-inner {
                    flex-direction: column;
                    padding: 1rem 0;
                }
                
                .site-branding {
                    margin: 0 auto 1rem;
                    max-width: none;
                    text-align: center;
                    justify-content: center;
                    align-items: center;
                }
                
                .navigation-wrapper {
                    width: 100%;
                    justify-content: center;
                }
                
                .main-navigation {
                    justify-content: center;
                    width: 100%;
                }
                
                .main-navigation ul {
                    justify-content: center;
                }
                
                .header-actions {
                    position: absolute;
                    right: 0;
                    top: 1rem;
                }
            }
        ";
    }
    
    $dynamic_css = "
        :root {
            --hariharan-primary-color: {$primary_color};
            --hariharan-secondary-color: {$secondary_color};
            --hariharan-bg-color: {$bg_color_light};
            --hariharan-text-color: {$text_color_light};
            --hariharan-font-family: {$body_font_family};
            --hariharan-heading-font-family: {$heading_font_family};
            --hariharan-font-size: {$body_font_size}px;
            --hariharan-line-height: {$line_height};
        }
        
        [data-theme='dark'] {
            --hariharan-bg-color: {$bg_color_dark};
            --hariharan-text-color: {$text_color_dark};
        }
        
        body {
            font-family: var(--hariharan-font-family);
            font-size: var(--hariharan-font-size);
            line-height: var(--hariharan-line-height);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--hariharan-heading-font-family);
        }
        
        {$header_css}
    ";
    
    // Add custom CSS from theme options
    $custom_css = get_option('hariharan_custom_css', '');
    
    if (!empty($custom_css)) {
        $dynamic_css .= wp_strip_all_tags($custom_css);
    }
    
    echo '<style id="hariharan-dynamic-css">' . wp_strip_all_tags($dynamic_css) . '</style>';
}
add_action('wp_head', 'hariharan_add_custom_css', 20); // Higher priority to ensure it overrides default styles

/**
 * Add custom JavaScript from theme options
 */
function hariharan_add_custom_js() {
    $custom_js = get_option('hariharan_custom_js', '');
    
    if (!empty($custom_js)) {
        echo '<script id="hariharan-custom-js">' . $custom_js . '</script>';
    }
}
add_action('wp_footer', 'hariharan_add_custom_js', 99);
