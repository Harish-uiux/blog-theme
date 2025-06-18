<?php
/**
 * Admin page setup for Hariharan theme
 *
 * @package Hariharan
 */

/**
 * Add the theme admin menu item
 */
function hariharan_add_admin_menu() {
    add_theme_page(
        esc_html__('Theme Options', 'hariharan'),
        esc_html__('Theme Options', 'hariharan'),
        'manage_options',
        'hariharan-options',
        'hariharan_options_page'
    );
}
add_action('admin_menu', 'hariharan_add_admin_menu');

/**
 * Enqueue admin scripts and styles
 */
function hariharan_admin_enqueue_scripts($hook) {
    // Only load on theme options page
    if ($hook != 'appearance_page_hariharan-options') {
        return;
    }

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_media();
    
    wp_enqueue_style(
        'hariharan-admin-styles',
        get_template_directory_uri() . '/admin/css/admin-style.css',
        array(),
        HARIHARAN_VERSION
    );
    
    wp_enqueue_script(
        'hariharan-admin-script',
        get_template_directory_uri() . '/admin/js/admin-script.js',
        array('jquery', 'wp-color-picker'),
        HARIHARAN_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'hariharan_admin_enqueue_scripts');

/**
 * Theme options page callback
 */
function hariharan_options_page() {
    ?>
    <div class="wrap hariharan-options-page">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <?php settings_errors(); ?>
        
        <div class="hariharan-options-container">
            <div class="hariharan-options-sidebar">
                <div class="hariharan-options-sidebar-header">
                    <h2><?php esc_html_e('Hariharan Theme', 'hariharan'); ?></h2>
                    <p><?php esc_html_e('Version:', 'hariharan'); ?> <?php echo HARIHARAN_VERSION; ?></p>
                </div>
                
                <nav class="hariharan-options-nav">
                    <ul>
                        <li><a href="#general" class="active"><?php esc_html_e('General', 'hariharan'); ?></a></li>
                        <li><a href="#header"><?php esc_html_e('Header', 'hariharan'); ?></a></li>
                        <li><a href="#footer"><?php esc_html_e('Footer', 'hariharan'); ?></a></li>
                        <li><a href="#typography"><?php esc_html_e('Typography', 'hariharan'); ?></a></li>
                        <li><a href="#colors"><?php esc_html_e('Colors', 'hariharan'); ?></a></li>
                        <li><a href="#seo"><?php esc_html_e('SEO', 'hariharan'); ?></a></li>
                        <li><a href="#advanced"><?php esc_html_e('Advanced', 'hariharan'); ?></a></li>
                    </ul>
                </nav>
            </div>
            
            <div class="hariharan-options-content">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('hariharan_options');
                    
                    // General Tab
                    ?>
                    <div id="general" class="hariharan-options-tab active">
                        <div class="hariharan-options-section">
                            <h2><?php esc_html_e('General Settings', 'hariharan'); ?></h2>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_logo_light">
                                    <?php esc_html_e('Light Logo', 'hariharan'); ?>
                                </label>
                                <div class="hariharan-image-upload">
                                    <?php $light_logo = get_option('hariharan_logo_light', ''); ?>
                                    <div class="hariharan-image-preview">
                                        <?php if (!empty($light_logo)) : ?>
                                            <img src="<?php echo esc_url($light_logo); ?>" alt="Light Logo">
                                        <?php endif; ?>
                                    </div>
                                    <input type="hidden" id="hariharan_logo_light" name="hariharan_logo_light" value="<?php echo esc_attr($light_logo); ?>">
                                    <button type="button" class="button hariharan-upload-image">
                                        <?php esc_html_e('Upload Logo', 'hariharan'); ?>
                                    </button>
                                    <button type="button" class="button hariharan-remove-image">
                                        <?php esc_html_e('Remove', 'hariharan'); ?>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_logo_dark">
                                    <?php esc_html_e('Dark Logo', 'hariharan'); ?>
                                </label>
                                <div class="hariharan-image-upload">
                                    <?php $dark_logo = get_option('hariharan_logo_dark', ''); ?>
                                    <div class="hariharan-image-preview">
                                        <?php if (!empty($dark_logo)) : ?>
                                            <img src="<?php echo esc_url($dark_logo); ?>" alt="Dark Logo">
                                        <?php endif; ?>
                                    </div>
                                    <input type="hidden" id="hariharan_logo_dark" name="hariharan_logo_dark" value="<?php echo esc_attr($dark_logo); ?>">
                                    <button type="button" class="button hariharan-upload-image">
                                        <?php esc_html_e('Upload Logo', 'hariharan'); ?>
                                    </button>
                                    <button type="button" class="button hariharan-remove-image">
                                        <?php esc_html_e('Remove', 'hariharan'); ?>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_enable_dark_mode">
                                    <?php esc_html_e('Enable Dark Mode Toggle', 'hariharan'); ?>
                                </label>
                                <input type="checkbox" id="hariharan_enable_dark_mode" name="hariharan_enable_dark_mode" value="1" <?php checked(get_option('hariharan_enable_dark_mode', '1'), '1'); ?>>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_default_dark_mode">
                                    <?php esc_html_e('Default to Dark Mode', 'hariharan'); ?>
                                </label>
                                <input type="checkbox" id="hariharan_default_dark_mode" name="hariharan_default_dark_mode" value="1" <?php checked(get_option('hariharan_default_dark_mode', ''), '1'); ?>>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Header Tab -->
                    <div id="header" class="hariharan-options-tab">
                        <div class="hariharan-options-section">
                            <h2><?php esc_html_e('Header Settings', 'hariharan'); ?></h2>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_header_layout">
                                    <?php esc_html_e('Header Layout', 'hariharan'); ?>
                                </label>
                                <select id="hariharan_header_layout" name="hariharan_header_layout">
                                    <option value="default" <?php selected(get_option('hariharan_header_layout', 'default'), 'default'); ?>>
                                        <?php esc_html_e('Default', 'hariharan'); ?>
                                    </option>
                                    <option value="transparent" <?php selected(get_option('hariharan_header_layout', 'default'), 'transparent'); ?>>
                                        <?php esc_html_e('Transparent', 'hariharan'); ?>
                                    </option>
                                    <option value="centered" <?php selected(get_option('hariharan_header_layout', 'default'), 'centered'); ?>>
                                        <?php esc_html_e('Centered', 'hariharan'); ?>
                                    </option>
                                </select>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_sticky_header">
                                    <?php esc_html_e('Enable Sticky Header', 'hariharan'); ?>
                                </label>
                                <input type="checkbox" id="hariharan_sticky_header" name="hariharan_sticky_header" value="1" <?php checked(get_option('hariharan_sticky_header', '1'), '1'); ?>>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_enable_search">
                                    <?php esc_html_e('Show Search in Header', 'hariharan'); ?>
                                </label>
                                <input type="checkbox" id="hariharan_enable_search" name="hariharan_enable_search" value="1" <?php checked(get_option('hariharan_enable_search', '1'), '1'); ?>>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Tab -->
                    <div id="footer" class="hariharan-options-tab">
                        <div class="hariharan-options-section">
                            <h2><?php esc_html_e('Footer Settings', 'hariharan'); ?></h2>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_footer_layout">
                                    <?php esc_html_e('Footer Layout', 'hariharan'); ?>
                                </label>
                                <select id="hariharan_footer_layout" name="hariharan_footer_layout">
                                    <option value="default" <?php selected(get_option('hariharan_footer_layout', 'default'), 'default'); ?>>
                                        <?php esc_html_e('Default', 'hariharan'); ?>
                                    </option>
                                    <option value="minimal" <?php selected(get_option('hariharan_footer_layout', 'default'), 'minimal'); ?>>
                                        <?php esc_html_e('Minimal', 'hariharan'); ?>
                                    </option>
                                </select>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_footer_widget_count">
                                    <?php esc_html_e('Footer Widget Areas', 'hariharan'); ?>
                                </label>
                                <select id="hariharan_footer_widget_count" name="hariharan_footer_widget_count">
                                    <option value="0" <?php selected(get_option('hariharan_footer_widget_count', '4'), '0'); ?>>
                                        <?php esc_html_e('Disabled', 'hariharan'); ?>
                                    </option>
                                    <option value="1" <?php selected(get_option('hariharan_footer_widget_count', '4'), '1'); ?>>
                                        <?php esc_html_e('1 Column', 'hariharan'); ?>
                                    </option>
                                    <option value="2" <?php selected(get_option('hariharan_footer_widget_count', '4'), '2'); ?>>
                                        <?php esc_html_e('2 Columns', 'hariharan'); ?>
                                    </option>
                                    <option value="3" <?php selected(get_option('hariharan_footer_widget_count', '4'), '3'); ?>>
                                        <?php esc_html_e('3 Columns', 'hariharan'); ?>
                                    </option>
                                    <option value="4" <?php selected(get_option('hariharan_footer_widget_count', '4'), '4'); ?>>
                                        <?php esc_html_e('4 Columns', 'hariharan'); ?>
                                    </option>
                                </select>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_footer_copyright">
                                    <?php esc_html_e('Copyright Text', 'hariharan'); ?>
                                </label>
                                <textarea id="hariharan_footer_copyright" name="hariharan_footer_copyright" rows="3"><?php echo esc_textarea(get_option('hariharan_footer_copyright', '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.')); ?></textarea>
                                <p class="description">
                                    <?php esc_html_e('Use {year} for the current year and {site_name} for the site name.', 'hariharan'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colors Tab -->
                    <div id="colors" class="hariharan-options-tab">
                        <div class="hariharan-options-section">
                            <h2><?php esc_html_e('Colors', 'hariharan'); ?></h2>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_primary_color">
                                    <?php esc_html_e('Primary Color', 'hariharan'); ?>
                                </label>
                                <input type="text" id="hariharan_primary_color" name="hariharan_primary_color" class="hariharan-color-picker" value="<?php echo esc_attr(get_option('hariharan_primary_color', '#4a6cf7')); ?>">
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_secondary_color">
                                    <?php esc_html_e('Secondary Color', 'hariharan'); ?>
                                </label>
                                <input type="text" id="hariharan_secondary_color" name="hariharan_secondary_color" class="hariharan-color-picker" value="<?php echo esc_attr(get_option('hariharan_secondary_color', '#6d8dff')); ?>">
                            </div>
                            
                            <h3><?php esc_html_e('Light Mode Colors', 'hariharan'); ?></h3>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_bg_color_light">
                                    <?php esc_html_e('Background Color', 'hariharan'); ?>
                                </label>
                                <input type="text" id="hariharan_bg_color_light" name="hariharan_bg_color_light" class="hariharan-color-picker" value="<?php echo esc_attr(get_option('hariharan_bg_color_light', '#ffffff')); ?>">
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_text_color_light">
                                    <?php esc_html_e('Text Color', 'hariharan'); ?>
                                </label>
                                <input type="text" id="hariharan_text_color_light" name="hariharan_text_color_light" class="hariharan-color-picker" value="<?php echo esc_attr(get_option('hariharan_text_color_light', '#333333')); ?>">
                            </div>
                            
                            <h3><?php esc_html_e('Dark Mode Colors', 'hariharan'); ?></h3>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_bg_color_dark">
                                    <?php esc_html_e('Background Color', 'hariharan'); ?>
                                </label>
                                <input type="text" id="hariharan_bg_color_dark" name="hariharan_bg_color_dark" class="hariharan-color-picker" value="<?php echo esc_attr(get_option('hariharan_bg_color_dark', '#121212')); ?>">
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_text_color_dark">
                                    <?php esc_html_e('Text Color', 'hariharan'); ?>
                                </label>
                                <input type="text" id="hariharan_text_color_dark" name="hariharan_text_color_dark" class="hariharan-color-picker" value="<?php echo esc_attr(get_option('hariharan_text_color_dark', '#e0e0e0')); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Typography Tab -->
                    <div id="typography" class="hariharan-options-tab">
                        <div class="hariharan-options-section">
                            <h2><?php esc_html_e('Typography', 'hariharan'); ?></h2>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_body_font">
                                    <?php esc_html_e('Body Font', 'hariharan'); ?>
                                </label>
                                <select id="hariharan_body_font" name="hariharan_body_font">
                                    <option value="system" <?php selected(get_option('hariharan_body_font', 'system'), 'system'); ?>>
                                        <?php esc_html_e('System Font', 'hariharan'); ?>
                                    </option>
                                    <option value="poppins" <?php selected(get_option('hariharan_body_font', 'system'), 'poppins'); ?>>
                                        <?php esc_html_e('Poppins', 'hariharan'); ?>
                                    </option>
                                    <option value="roboto" <?php selected(get_option('hariharan_body_font', 'system'), 'roboto'); ?>>
                                        <?php esc_html_e('Roboto', 'hariharan'); ?>
                                    </option>
                                    <option value="open-sans" <?php selected(get_option('hariharan_body_font', 'system'), 'open-sans'); ?>>
                                        <?php esc_html_e('Open Sans', 'hariharan'); ?>
                                    </option>
                                </select>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_heading_font">
                                    <?php esc_html_e('Heading Font', 'hariharan'); ?>
                                </label>
                                <select id="hariharan_heading_font" name="hariharan_heading_font">
                                    <option value="system" <?php selected(get_option('hariharan_heading_font', 'system'), 'system'); ?>>
                                        <?php esc_html_e('System Font', 'hariharan'); ?>
                                    </option>
                                    <option value="poppins" <?php selected(get_option('hariharan_heading_font', 'system'), 'poppins'); ?>>
                                        <?php esc_html_e('Poppins', 'hariharan'); ?>
                                    </option>
                                    <option value="roboto" <?php selected(get_option('hariharan_heading_font', 'system'), 'roboto'); ?>>
                                        <?php esc_html_e('Roboto', 'hariharan'); ?>
                                    </option>
                                    <option value="playfair" <?php selected(get_option('hariharan_heading_font', 'system'), 'playfair'); ?>>
                                        <?php esc_html_e('Playfair Display', 'hariharan'); ?>
                                    </option>
                                </select>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_body_font_size">
                                    <?php esc_html_e('Base Font Size (px)', 'hariharan'); ?>
                                </label>
                                <input type="number" id="hariharan_body_font_size" name="hariharan_body_font_size" min="12" max="24" value="<?php echo esc_attr(get_option('hariharan_body_font_size', '16')); ?>">
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_line_height">
                                    <?php esc_html_e('Line Height', 'hariharan'); ?>
                                </label>
                                <input type="number" id="hariharan_line_height" name="hariharan_line_height" min="1" max="2" step="0.1" value="<?php echo esc_attr(get_option('hariharan_line_height', '1.6')); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO Tab -->
                    <div id="seo" class="hariharan-options-tab">
                        <div class="hariharan-options-section">
                            <h2><?php esc_html_e('SEO Settings', 'hariharan'); ?></h2>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_enable_schema">
                                    <?php esc_html_e('Enable Schema Markup', 'hariharan'); ?>
                                </label>
                                <input type="checkbox" id="hariharan_enable_schema" name="hariharan_enable_schema" value="1" <?php checked(get_option('hariharan_enable_schema', '1'), '1'); ?>>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_enable_open_graph">
                                    <?php esc_html_e('Enable Open Graph Tags', 'hariharan'); ?>
                                </label>
                                <input type="checkbox" id="hariharan_enable_open_graph" name="hariharan_enable_open_graph" value="1" <?php checked(get_option('hariharan_enable_open_graph', '1'), '1'); ?>>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_default_meta_description">
                                    <?php esc_html_e('Default Meta Description', 'hariharan'); ?>
                                </label>
                                <textarea id="hariharan_default_meta_description" name="hariharan_default_meta_description" rows="3"><?php echo esc_textarea(get_option('hariharan_default_meta_description', '')); ?></textarea>
                                <p class="description">
                                    <?php esc_html_e('Used when no specific meta description is provided.', 'hariharan'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Tab -->
                    <div id="advanced" class="hariharan-options-tab">
                        <div class="hariharan-options-section">
                            <h2><?php esc_html_e('Advanced Settings', 'hariharan'); ?></h2>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_enable_lazy_load">
                                    <?php esc_html_e('Enable Image Lazy Loading', 'hariharan'); ?>
                                </label>
                                <input type="checkbox" id="hariharan_enable_lazy_load" name="hariharan_enable_lazy_load" value="1" <?php checked(get_option('hariharan_enable_lazy_load', '1'), '1'); ?>>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_custom_css">
                                    <?php esc_html_e('Custom CSS', 'hariharan'); ?>
                                </label>
                                <textarea id="hariharan_custom_css" name="hariharan_custom_css" rows="8" class="code"><?php echo esc_textarea(get_option('hariharan_custom_css', '')); ?></textarea>
                            </div>
                            
                            <div class="hariharan-options-field">
                                <label for="hariharan_custom_js">
                                    <?php esc_html_e('Custom JavaScript', 'hariharan'); ?>
                                </label>
                                <textarea id="hariharan_custom_js" name="hariharan_custom_js" rows="8" class="code"><?php echo esc_textarea(get_option('hariharan_custom_js', '')); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <?php submit_button(); ?>
                </form>
            </div>
        </div>
    </div>
    <?php
}
