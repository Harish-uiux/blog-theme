<?php
/**
 * BloglyPress Theme Options
 *
 * @package BloglyPress
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Options Class
 */
class BloglyPress_Theme_Options {

    /**
     * Instance
     */
    private static $instance;

    /**
     * Options pages
     */
    private $options_pages = array();

    /**
     * Options
     */
    private $options = array();

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        // Set default options
        $this->set_default_options();
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Add admin assets
        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
        
        // AJAX handlers
        add_action('wp_ajax_bloglypress_save_options', array($this, 'ajax_save_options'));
        add_action('wp_ajax_bloglypress_reset_options', array($this, 'ajax_reset_options'));
        add_action('wp_ajax_bloglypress_export_options', array($this, 'ajax_export_options'));
        add_action('wp_ajax_bloglypress_import_options', array($this, 'ajax_import_options'));
    }

    /**
     * Set default options
     */
    private function set_default_options() {
        $this->options = array(
            'general' => array(
                'layout_mode' => 'wide',
                'container_width' => '1200',
                'enable_preloader' => true,
                'enable_back_to_top' => true,
                'enable_breadcrumbs' => true,
            ),
            'typography' => array(
                'body_font' => 'Inter',
                'heading_font' => 'Playfair Display',
                'base_font_size' => '16',
                'line_height' => '1.7',
            ),
            'colors' => array(
                'primary_color' => '#3857ff',
                'secondary_color' => '#15c9c9',
                'body_background' => '#ffffff',
                'text_color' => '#333333',
                'heading_color' => '#222222',
                'link_color' => '#3857ff',
                'link_hover_color' => '#2a3eb1',
            ),
            'header' => array(
                'layout' => 'default',
                'sticky' => true,
                'transparent' => false,
                'search' => true,
                'dark_mode_toggle' => true,
            ),
            'blog' => array(
                'layout' => 'grid',
                'sidebar_position' => 'right',
                'show_featured_post' => true,
                'posts_per_page' => '9',
                'excerpt_length' => '25',
                'show_author' => true,
                'show_date' => true,
                'show_categories' => true,
                'show_tags' => true,
                'show_comments_count' => true,
                'pagination_type' => 'numeric',
            ),
            'single_post' => array(
                'sidebar_position' => 'right',
                'show_featured_image' => true,
                'show_author_box' => true,
                'show_post_navigation' => true,
                'show_related_posts' => true,
                'related_posts_count' => '3',
                'show_social_sharing' => true,
            ),
            'footer' => array(
                'widgets_columns' => '3',
                'copyright_text' => '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
                'show_powered_by' => true,
                'back_to_top' => true,
            ),
            'performance' => array(
                'enable_lazy_loading' => true,
                'minify_assets' => true,
                'enable_webp' => true,
            ),
            'advanced' => array(
                'custom_css' => '',
                'custom_js' => '',
                'enable_schema' => true,
                'enable_portfolio' => false,
            ),
        );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        $this->options_pages['theme_options'] = add_menu_page(
            __('BloglyPress', 'bloglypress'),
            __('BloglyPress', 'bloglypress'),
            'manage_options',
            'bloglypress-options',
            array($this, 'render_dashboard_page'),
            'dashicons-admin-customizer',
            58
        );
        
        $this->options_pages['dashboard'] = add_submenu_page(
            'bloglypress-options',
            __('Dashboard', 'bloglypress'),
            __('Dashboard', 'bloglypress'),
            'manage_options',
            'bloglypress-options',
            array($this, 'render_dashboard_page')
        );
        
        $this->options_pages['general'] = add_submenu_page(
            'bloglypress-options',
            __('General Settings', 'bloglypress'),
            __('General Settings', 'bloglypress'),
            'manage_options',
            'bloglypress-general',
            array($this, 'render_general_page')
        );
        
        $this->options_pages['typography'] = add_submenu_page(
            'bloglypress-options',
            __('Typography', 'bloglypress'),
            __('Typography', 'bloglypress'),
            'manage_options',
            'bloglypress-typography',
            array($this, 'render_typography_page')
        );
        
        $this->options_pages['colors'] = add_submenu_page(
            'bloglypress-options',
            __('Colors', 'bloglypress'),
            __('Colors', 'bloglypress'),
            'manage_options',
            'bloglypress-colors',
            array($this, 'render_colors_page')
        );
        
        $this->options_pages['header'] = add_submenu_page(
            'bloglypress-options',
            __('Header', 'bloglypress'),
            __('Header', 'bloglypress'),
            'manage_options',
            'bloglypress-header',
            array($this, 'render_header_page')
        );
        
        $this->options_pages['blog'] = add_submenu_page(
            'bloglypress-options',
            __('Blog', 'bloglypress'),
            __('Blog', 'bloglypress'),
            'manage_options',
            'bloglypress-blog',
            array($this, 'render_blog_page')
        );
        
        $this->options_pages['single_post'] = add_submenu_page(
            'bloglypress-options',
            __('Single Post', 'bloglypress'),
            __('Single Post', 'bloglypress'),
            'manage_options',
            'bloglypress-single-post',
            array($this, 'render_single_post_page')
        );
        
        $this->options_pages['footer'] = add_submenu_page(
            'bloglypress-options',
            __('Footer', 'bloglypress'),
            __('Footer', 'bloglypress'),
            'manage_options',
            'bloglypress-footer',
            array($this, 'render_footer_page')
        );
        
        $this->options_pages['performance'] = add_submenu_page(
            'bloglypress-options',
            __('Performance', 'bloglypress'),
            __('Performance', 'bloglypress'),
            'manage_options',
            'bloglypress-performance',
            array($this, 'render_performance_page')
        );
        
        $this->options_pages['advanced'] = add_submenu_page(
            'bloglypress-options',
            __('Advanced', 'bloglypress'),
            __('Advanced', 'bloglypress'),
            'manage_options',
            'bloglypress-advanced',
            array($this, 'render_advanced_page')
        );
        
        $this->options_pages['import_export'] = add_submenu_page(
            'bloglypress-options',
            __('Import/Export', 'bloglypress'),
            __('Import/Export', 'bloglypress'),
            'manage_options',
            'bloglypress-import-export',
            array($this, 'render_import_export_page')
        );
    }

    /**
     * Admin assets
     */
    public function admin_assets($hook) {
        // Only load on theme options pages
        if (!in_array($hook, $this->options_pages)) {
            return;
        }
        
        // Styles
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('bloglypress-admin-icons', 'https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css', array(), '6.5.95');
        wp_enqueue_style('bloglypress-admin', get_template_directory_uri() . '/assets/css/admin.css', array('wp-color-picker'), '1.0.0');
        
        // Scripts
        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('bloglypress-admin', get_template_directory_uri() . '/assets/js/admin.js', array('jquery', 'wp-color-picker', 'jquery-ui-sortable', 'jquery-ui-tabs'), '1.0.0', true);
        
        // Pass data to script
        wp_localize_script('bloglypress-admin', 'bloglyPressAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bloglypress_options_nonce'),
            'resetConfirm' => __('Are you sure you want to reset all theme options to default settings? This action cannot be undone.', 'bloglypress'),
            'saveSuccess' => __('Settings saved successfully!', 'bloglypress'),
            'saveFail' => __('Error saving settings. Please try again.', 'bloglypress'),
            'resetSuccess' => __('Settings reset to defaults successfully!', 'bloglypress'),
            'resetFail' => __('Error resetting settings. Please try again.', 'bloglypress'),
            'importSuccess' => __('Settings imported successfully!', 'bloglypress'),
            'importFail' => __('Error importing settings. Please try again.', 'bloglypress'),
            'fonts' => $this->get_google_fonts(),
        ));
    }

    /**
     * Get Google Fonts
     */
    private function get_google_fonts() {
        return array(
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Montserrat' => 'Montserrat',
            'Lato' => 'Lato',
            'Poppins' => 'Poppins',
            'Playfair Display' => 'Playfair Display',
            'Merriweather' => 'Merriweather',
            'Source Sans Pro' => 'Source Sans Pro',
            'Nunito' => 'Nunito',
            'Raleway' => 'Raleway',
            'Ubuntu' => 'Ubuntu',
            'Rubik' => 'Rubik',
            'Work Sans' => 'Work Sans',
            'Quicksand' => 'Quicksand',
        );
    }

    /**
     * Get saved options
     */
    private function get_options($section) {
        $saved_options = get_option('bloglypress_' . $section . '_options', array());
        return wp_parse_args($saved_options, $this->options[$section]);
    }

    /**
     * Render dashboard page
     */
    public function render_dashboard_page() {
        ?>
        <div class="bloglypress-admin-wrapper">
            <div class="bloglypress-admin-header">
                <div class="bloglypress-admin-logo">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/bloglypress-logo.png" alt="BloglyPress">
                </div>
                <div class="bloglypress-admin-version">
                    <span><?php echo esc_html__('Version', 'bloglypress'); ?> 1.0.0</span>
                </div>
            </div>
            
            <div class="bloglypress-admin-notices"></div>
            
            <div class="bloglypress-admin-content">
                <div class="bloglypress-admin-title">
                    <h1><?php echo esc_html__('Welcome to BloglyPress', 'bloglypress'); ?></h1>
                    <p class="bloglypress-admin-subtitle"><?php echo esc_html__('A modern WordPress theme for bloggers and content creators', 'bloglypress'); ?></p>
                </div>
                
                <div class="bloglypress-admin-cards">
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-icon">
                            <span class="mdi mdi-palette-swatch"></span>
                        </div>
                        <div class="bloglypress-admin-card-content">
                            <h2><?php echo esc_html__('Customize Theme', 'bloglypress'); ?></h2>
                            <p><?php echo esc_html__('Personalize your theme with colors, fonts, layouts and more.', 'bloglypress'); ?></p>
                            <a href="<?php echo admin_url('admin.php?page=bloglypress-general'); ?>" class="bloglypress-admin-button"><?php echo esc_html__('Theme Settings', 'bloglypress'); ?></a>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-icon">
                            <span class="mdi mdi-rocket-launch"></span>
                        </div>
                        <div class="bloglypress-admin-card-content">
                            <h2><?php echo esc_html__('Performance', 'bloglypress'); ?></h2>
                            <p><?php echo esc_html__('Optimize your site speed with built-in performance features.', 'bloglypress'); ?></p>
                            <a href="<?php echo admin_url('admin.php?page=bloglypress-performance'); ?>" class="bloglypress-admin-button"><?php echo esc_html__('Performance Settings', 'bloglypress'); ?></a>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-icon">
                            <span class="mdi mdi-tune"></span>
                        </div>
                        <div class="bloglypress-admin-card-content">
                            <h2><?php echo esc_html__('Advanced Options', 'bloglypress'); ?></h2>
                            <p><?php echo esc_html__('Configure advanced features and add custom code.', 'bloglypress'); ?></p>
                            <a href="<?php echo admin_url('admin.php?page=bloglypress-advanced'); ?>" class="bloglypress-admin-button"><?php echo esc_html__('Advanced Settings', 'bloglypress'); ?></a>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-icon">
                            <span class="mdi mdi-help-circle"></span>
                        </div>
                        <div class="bloglypress-admin-card-content">
                            <h2><?php echo esc_html__('Documentation', 'bloglypress'); ?></h2>
                            <p><?php echo esc_html__('Learn how to use all the features of BloglyPress.', 'bloglypress'); ?></p>
                            <a href="https://www.pixelsmedialab.com/docs/bloglypress/" target="_blank" class="bloglypress-admin-button"><?php echo esc_html__('View Documentation', 'bloglypress'); ?></a>
                        </div>
                    </div>
                </div>
                
                <div class="bloglypress-admin-boxes">
                    <div class="bloglypress-admin-box">
                        <h2><?php echo esc_html__('Getting Started', 'bloglypress'); ?></h2>
                        <ul class="bloglypress-admin-list">
                            <li>
                                <span class="mdi mdi-check-circle"></span>
                                <a href="<?php echo admin_url('customize.php'); ?>"><?php echo esc_html__('Customize Theme Appearance', 'bloglypress'); ?></a>
                            </li>
                            <li>
                                <span class="mdi mdi-check-circle"></span>
                                <a href="<?php echo admin_url('nav-menus.php'); ?>"><?php echo esc_html__('Set Up Navigation Menus', 'bloglypress'); ?></a>
                            </li>
                            <li>
                                <span class="mdi mdi-check-circle"></span>
                                <a href="<?php echo admin_url('widgets.php'); ?>"><?php echo esc_html__('Configure Widgets', 'bloglypress'); ?></a>
                            </li>
                            <li>
                                <span class="mdi mdi-check-circle"></span>
                                <a href="<?php echo admin_url('admin.php?page=bloglypress-blog'); ?>"><?php echo esc_html__('Set Up Blog Layout', 'bloglypress'); ?></a>
                            </li>
                            <li>
                                <span class="mdi mdi-check-circle"></span>
                                <a href="<?php echo admin_url('admin.php?page=bloglypress-footer'); ?>"><?php echo esc_html__('Customize Footer', 'bloglypress'); ?></a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="bloglypress-admin-box">
                        <h2><?php echo esc_html__('Theme Features', 'bloglypress'); ?></h2>
                        <ul class="bloglypress-admin-features">
                            <li><span class="mdi mdi-check"></span> <?php echo esc_html__('Modern & Clean Design', 'bloglypress'); ?></li>
                            <li><span class="mdi mdi-check"></span> <?php echo esc_html__('Fully Responsive Layout', 'bloglypress'); ?></li>
                            <li><span class="mdi mdi-check"></span> <?php echo esc_html__('Advanced Theme Options', 'bloglypress'); ?></li>
                            <li><span class="mdi mdi-check"></span> <?php echo esc_html__('Multiple Blog Layouts', 'bloglypress'); ?></li>
                            <li><span class="mdi mdi-check"></span> <?php echo esc_html__('Dark Mode Support', 'bloglypress'); ?></li>
                            <li><span class="mdi mdi-check"></span> <?php echo esc_html__('Performance Optimization', 'bloglypress'); ?></li>
                            <li><span class="mdi mdi-check"></span> <?php echo esc_html__('SEO Friendly Code', 'bloglypress'); ?></li>
                            <li><span class="mdi mdi-check"></span> <?php echo esc_html__('Custom Gutenberg Blocks', 'bloglypress'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render general page
     */
    public function render_general_page() {
        $options = $this->get_options('general');
        ?>
        <div class="bloglypress-admin-wrapper">
            <?php $this->render_header(__('General Settings', 'bloglypress')); ?>
            
            <div class="bloglypress-admin-content">
                <form id="bloglypress-options-form" data-section="general">
                    <?php wp_nonce_field('bloglypress_options_action', 'bloglypress_options_nonce'); ?>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Layout Settings', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="layout_mode"><?php echo esc_html__('Layout Mode', 'bloglypress'); ?></label>
                                <select id="layout_mode" name="layout_mode" class="bloglypress-admin-select">
                                    <option value="wide" <?php selected($options['layout_mode'], 'wide'); ?>><?php echo esc_html__('Wide', 'bloglypress'); ?></option>
                                    <option value="boxed" <?php selected($options['layout_mode'], 'boxed'); ?>><?php echo esc_html__('Boxed', 'bloglypress'); ?></option>
                                    <option value="framed" <?php selected($options['layout_mode'], 'framed'); ?>><?php echo esc_html__('Framed', 'bloglypress'); ?></option>
                                </select>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Select the layout mode for your website.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="container_width"><?php echo esc_html__('Container Width (px)', 'bloglypress'); ?></label>
                                <input type="number" id="container_width" name="container_width" value="<?php echo esc_attr($options['container_width']); ?>" min="800" max="1920" step="10" class="bloglypress-admin-input">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Set the maximum width of the content container in pixels.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Features', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Enable Preloader', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="enable_preloader" name="enable_preloader" value="1" <?php checked($options['enable_preloader'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display a loading animation while the page is loading.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Enable Back to Top Button', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="enable_back_to_top" name="enable_back_to_top" value="1" <?php checked($options['enable_back_to_top'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display a button that allows users to scroll back to the top of the page.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Enable Breadcrumbs', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="enable_breadcrumbs" name="enable_breadcrumbs" value="1" <?php checked($options['enable_breadcrumbs'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display breadcrumbs navigation to show the current page location.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php $this->render_form_footer(); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render typography page
     */
    public function render_typography_page() {
        $options = $this->get_options('typography');
        $fonts = $this->get_google_fonts();
        ?>
        <div class="bloglypress-admin-wrapper">
            <?php $this->render_header(__('Typography', 'bloglypress')); ?>
            
            <div class="bloglypress-admin-content">
                <form id="bloglypress-options-form" data-section="typography">
                    <?php wp_nonce_field('bloglypress_options_action', 'bloglypress_options_nonce'); ?>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Fonts', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="body_font"><?php echo esc_html__('Body Font', 'bloglypress'); ?></label>
                                <select id="body_font" name="body_font" class="bloglypress-admin-select">
                                    <?php foreach ($fonts as $font_value => $font_name) : ?>
                                        <option value="<?php echo esc_attr($font_value); ?>" <?php selected($options['body_font'], $font_value); ?>><?php echo esc_html($font_name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Select the font family for body text.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="heading_font"><?php echo esc_html__('Heading Font', 'bloglypress'); ?></label>
                                <select id="heading_font" name="heading_font" class="bloglypress-admin-select">
                                    <?php foreach ($fonts as $font_value => $font_name) : ?>
                                        <option value="<?php echo esc_attr($font_value); ?>" <?php selected($options['heading_font'], $font_value); ?>><?php echo esc_html($font_name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Select the font family for headings.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="base_font_size"><?php echo esc_html__('Base Font Size (px)', 'bloglypress'); ?></label>
                                <input type="number" id="base_font_size" name="base_font_size" value="<?php echo esc_attr($options['base_font_size']); ?>" min="12" max="24" step="1" class="bloglypress-admin-input">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Set the base font size in pixels.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="line_height"><?php echo esc_html__('Line Height', 'bloglypress'); ?></label>
                                <input type="number" id="line_height" name="line_height" value="<?php echo esc_attr($options['line_height']); ?>" min="1" max="3" step="0.1" class="bloglypress-admin-input">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Set the default line height.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Typography Preview', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-typography-preview">
                                <h1 class="preview-heading">Heading 1 - The quick brown fox jumps over the lazy dog</h1>
                                <h2 class="preview-heading">Heading 2 - The quick brown fox jumps over the lazy dog</h2>
                                <h3 class="preview-heading">Heading 3 - The quick brown fox jumps over the lazy dog</h3>
                                <p class="preview-body">Body Text - Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Sed non mauris vitae erat consequat auctor eu in elit.</p>
                                <p class="preview-body"><a href="#">This is a link</a> - Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            </div>
                        </div>
                    </div>
                    
                    <?php $this->render_form_footer(); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render colors page
     */
    public function render_colors_page() {
        $options = $this->get_options('colors');
        ?>
        <div class="bloglypress-admin-wrapper">
            <?php $this->render_header(__('Colors', 'bloglypress')); ?>
            
            <div class="bloglypress-admin-content">
                <form id="bloglypress-options-form" data-section="colors">
                    <?php wp_nonce_field('bloglypress_options_action', 'bloglypress_options_nonce'); ?>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Color Scheme', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-color-schemes">
                                <div class="bloglypress-admin-color-scheme" data-scheme="default" data-primary="#3857ff" data-secondary="#15c9c9">
                                    <div class="bloglypress-admin-color-scheme-preview">
                                        <span style="background-color: #3857ff;"></span>
                                        <span style="background-color: #15c9c9;"></span>
                                    </div>
                                    <div class="bloglypress-admin-color-scheme-label"><?php echo esc_html__('Default', 'bloglypress'); ?></div>
                                </div>
                                
                                <div class="bloglypress-admin-color-scheme" data-scheme="purple" data-primary="#7e57ff" data-secondary="#ff57c3">
                                    <div class="bloglypress-admin-color-scheme-preview">
                                        <span style="background-color: #7e57ff;"></span>
                                        <span style="background-color: #ff57c3;"></span>
                                    </div>
                                    <div class="bloglypress-admin-color-scheme-label"><?php echo esc_html__('Purple', 'bloglypress'); ?></div>
                                </div>
                                
                                <div class="bloglypress-admin-color-scheme" data-scheme="green" data-primary="#2e8b57" data-secondary="#7cb342">
                                    <div class="bloglypress-admin-color-scheme-preview">
                                        <span style="background-color: #2e8b57;"></span>
                                        <span style="background-color: #7cb342;"></span>
                                    </div>
                                    <div class="bloglypress-admin-color-scheme-label"><?php echo esc_html__('Green', 'bloglypress'); ?></div>
                                </div>
                                
                                <div class="bloglypress-admin-color-scheme" data-scheme="red" data-primary="#e53935" data-secondary="#ff9800">
                                    <div class="bloglypress-admin-color-scheme-preview">
                                        <span style="background-color: #e53935;"></span>
                                        <span style="background-color: #ff9800;"></span>
                                    </div>
                                    <div class="bloglypress-admin-color-scheme-label"><?php echo esc_html__('Red', 'bloglypress'); ?></div>
                                </div>
                                
                                <div class="bloglypress-admin-color-scheme" data-scheme="dark" data-primary="#455a64" data-secondary="#607d8b">
                                    <div class="bloglypress-admin-color-scheme-preview">
                                        <span style="background-color: #455a64;"></span>
                                        <span style="background-color: #607d8b;"></span>
                                    </div>
                                    <div class="bloglypress-admin-color-scheme-label"><?php echo esc_html__('Dark', 'bloglypress'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Custom Colors', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="primary_color"><?php echo esc_html__('Primary Color', 'bloglypress'); ?></label>
                                <input type="text" id="primary_color" name="primary_color" value="<?php echo esc_attr($options['primary_color']); ?>" class="bloglypress-admin-color-picker">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Choose the primary color for buttons, links, and accents.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="secondary_color"><?php echo esc_html__('Secondary Color', 'bloglypress'); ?></label>
                                <input type="text" id="secondary_color" name="secondary_color" value="<?php echo esc_attr($options['secondary_color']); ?>" class="bloglypress-admin-color-picker">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Choose the secondary color for highlights and secondary elements.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="body_background"><?php echo esc_html__('Body Background', 'bloglypress'); ?></label>
                                <input type="text" id="body_background" name="body_background" value="<?php echo esc_attr($options['body_background']); ?>" class="bloglypress-admin-color-picker">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Choose the background color for the site.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="text_color"><?php echo esc_html__('Text Color', 'bloglypress'); ?></label>
                                <input type="text" id="text_color" name="text_color" value="<?php echo esc_attr($options['text_color']); ?>" class="bloglypress-admin-color-picker">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Choose the color for body text.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="heading_color"><?php echo esc_html__('Heading Color', 'bloglypress'); ?></label>
                                <input type="text" id="heading_color" name="heading_color" value="<?php echo esc_attr($options['heading_color']); ?>" class="bloglypress-admin-color-picker">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Choose the color for headings.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="link_color"><?php echo esc_html__('Link Color', 'bloglypress'); ?></label>
                                <input type="text" id="link_color" name="link_color" value="<?php echo esc_attr($options['link_color']); ?>" class="bloglypress-admin-color-picker">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Choose the color for links.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="link_hover_color"><?php echo esc_html__('Link Hover Color', 'bloglypress'); ?></label>
                                <input type="text" id="link_hover_color" name="link_hover_color" value="<?php echo esc_attr($options['link_hover_color']); ?>" class="bloglypress-admin-color-picker">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Choose the color for links on hover.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php $this->render_form_footer(); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render header page
     */
    public function render_header_page() {
        $options = $this->get_options('header');
        ?>
        <div class="bloglypress-admin-wrapper">
            <?php $this->render_header(__('Header', 'bloglypress')); ?>
            
            <div class="bloglypress-admin-content">
                <form id="bloglypress-options-form" data-section="header">
                    <?php wp_nonce_field('bloglypress_options_action', 'bloglypress_options_nonce'); ?>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Header Layout', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="layout"><?php echo esc_html__('Header Layout', 'bloglypress'); ?></label>
                                <div class="bloglypress-admin-image-choices">
                                    <label class="bloglypress-admin-image-choice <?php echo $options['layout'] === 'default' ? 'active' : ''; ?>">
                                        <input type="radio" name="layout" value="default" <?php checked($options['layout'], 'default'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/header-default.png" alt="Default Header">
                                        <span><?php echo esc_html__('Default', 'bloglypress'); ?></span>
                                    </label>
                                    
                                    <label class="bloglypress-admin-image-choice <?php echo $options['layout'] === 'centered' ? 'active' : ''; ?>">
                                        <input type="radio" name="layout" value="centered" <?php checked($options['layout'], 'centered'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/header-centered.png" alt="Centered Header">
                                        <span><?php echo esc_html__('Centered', 'bloglypress'); ?></span>
                                    </label>
                                    
                                    <label class="bloglypress-admin-image-choice <?php echo $options['layout'] === 'minimal' ? 'active' : ''; ?>">
                                        <input type="radio" name="layout" value="minimal" <?php checked($options['layout'], 'minimal'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/header-minimal.png" alt="Minimal Header">
                                        <span><?php echo esc_html__('Minimal', 'bloglypress'); ?></span>
                                    </label>
                                    
                                    <label class="bloglypress-admin-image-choice <?php echo $options['layout'] === 'transparent' ? 'active' : ''; ?>">
                                        <input type="radio" name="layout" value="transparent" <?php checked($options['layout'], 'transparent'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/header-transparent.png" alt="Transparent Header">
                                        <span><?php echo esc_html__('Transparent', 'bloglypress'); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Header Features', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Sticky Header', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="sticky" name="sticky" value="1" <?php checked($options['sticky'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Enable sticky header that stays at the top when scrolling.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Transparent Header on Homepage', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="transparent" name="transparent" value="1" <?php checked($options['transparent'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Enable transparent header on the homepage.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Search Button', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="search" name="search" value="1" <?php checked($options['search'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Show search button in header.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Dark Mode Toggle', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="dark_mode_toggle" name="dark_mode_toggle" value="1" <?php checked($options['dark_mode_toggle'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Show dark mode toggle button in header.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php $this->render_form_footer(); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render blog page
     */
    public function render_blog_page() {
        $options = $this->get_options('blog');
        ?>
        <div class="bloglypress-admin-wrapper">
            <?php $this->render_header(__('Blog', 'bloglypress')); ?>
            
            <div class="bloglypress-admin-content">
                <form id="bloglypress-options-form" data-section="blog">
                    <?php wp_nonce_field('bloglypress_options_action', 'bloglypress_options_nonce'); ?>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Blog Layout', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="layout"><?php echo esc_html__('Blog Layout', 'bloglypress'); ?></label>
                                <div class="bloglypress-admin-image-choices">
                                    <label class="bloglypress-admin-image-choice <?php echo $options['layout'] === 'grid' ? 'active' : ''; ?>">
                                        <input type="radio" name="layout" value="grid" <?php checked($options['layout'], 'grid'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/blog-grid.png" alt="Grid Layout">
                                        <span><?php echo esc_html__('Grid', 'bloglypress'); ?></span>
                                    </label>
                                    
                                    <label class="bloglypress-admin-image-choice <?php echo $options['layout'] === 'list' ? 'active' : ''; ?>">
                                        <input type="radio" name="layout" value="list" <?php checked($options['layout'], 'list'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/blog-list.png" alt="List Layout">
                                        <span><?php echo esc_html__('List', 'bloglypress'); ?></span>
                                    </label>
                                    
                                    <label class="bloglypress-admin-image-choice <?php echo $options['layout'] === 'masonry' ? 'active' : ''; ?>">
                                        <input type="radio" name="layout" value="masonry" <?php checked($options['layout'], 'masonry'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/blog-masonry.png" alt="Masonry Layout">
                                        <span><?php echo esc_html__('Masonry', 'bloglypress'); ?></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="sidebar_position"><?php echo esc_html__('Sidebar Position', 'bloglypress'); ?></label>
                                <div class="bloglypress-admin-image-choices">
                                    <label class="bloglypress-admin-image-choice <?php echo $options['sidebar_position'] === 'right' ? 'active' : ''; ?>">
                                        <input type="radio" name="sidebar_position" value="right" <?php checked($options['sidebar_position'], 'right'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/sidebar-right.png" alt="Right Sidebar">
                                        <span><?php echo esc_html__('Right', 'bloglypress'); ?></span>
                                    </label>
                                    
                                    <label class="bloglypress-admin-image-choice <?php echo $options['sidebar_position'] === 'left' ? 'active' : ''; ?>">
                                        <input type="radio" name="sidebar_position" value="left" <?php checked($options['sidebar_position'], 'left'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/sidebar-left.png" alt="Left Sidebar">
                                        <span><?php echo esc_html__('Left', 'bloglypress'); ?></span>
                                    </label>
                                    
                                    <label class="bloglypress-admin-image-choice <?php echo $options['sidebar_position'] === 'none' ? 'active' : ''; ?>">
                                        <input type="radio" name="sidebar_position" value="none" <?php checked($options['sidebar_position'], 'none'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/sidebar-none.png" alt="No Sidebar">
                                        <span><?php echo esc_html__('None', 'bloglypress'); ?></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="posts_per_page"><?php echo esc_html__('Posts Per Page', 'bloglypress'); ?></label>
                                <input type="number" id="posts_per_page" name="posts_per_page" value="<?php echo esc_attr($options['posts_per_page']); ?>" min="1" max="50" class="bloglypress-admin-input">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Number of posts to display per page.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Featured Post', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Featured Post', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_featured_post" name="show_featured_post" value="1" <?php checked($options['show_featured_post'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display a featured post at the top of the blog page.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Content Display', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="excerpt_length"><?php echo esc_html__('Excerpt Length', 'bloglypress'); ?></label>
                                <input type="number" id="excerpt_length" name="excerpt_length" value="<?php echo esc_attr($options['excerpt_length']); ?>" min="10" max="100" class="bloglypress-admin-input">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Number of words in post excerpts.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Author', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_author" name="show_author" value="1" <?php checked($options['show_author'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Date', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_date" name="show_date" value="1" <?php checked($options['show_date'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Categories', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_categories" name="show_categories" value="1" <?php checked($options['show_categories'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Tags', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_tags" name="show_tags" value="1" <?php checked($options['show_tags'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Comments Count', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_comments_count" name="show_comments_count" value="1" <?php checked($options['show_comments_count'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Pagination', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="pagination_type"><?php echo esc_html__('Pagination Type', 'bloglypress'); ?></label>
                                <select id="pagination_type" name="pagination_type" class="bloglypress-admin-select">
                                    <option value="numeric" <?php selected($options['pagination_type'], 'numeric'); ?>><?php echo esc_html__('Numeric', 'bloglypress'); ?></option>
                                    <option value="prev_next" <?php selected($options['pagination_type'], 'prev_next'); ?>><?php echo esc_html__('Previous / Next', 'bloglypress'); ?></option>
                                </select>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Select the pagination style for blog pages.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php $this->render_form_footer(); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render single post page
     */
    public function render_single_post_page() {
        $options = $this->get_options('single_post');
        ?>
        <div class="bloglypress-admin-wrapper">
            <?php $this->render_header(__('Single Post', 'bloglypress')); ?>
            
            <div class="bloglypress-admin-content">
                <form id="bloglypress-options-form" data-section="single_post">
                    <?php wp_nonce_field('bloglypress_options_action', 'bloglypress_options_nonce'); ?>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Layout', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="sidebar_position"><?php echo esc_html__('Sidebar Position', 'bloglypress'); ?></label>
                                <div class="bloglypress-admin-image-choices">
                                    <label class="bloglypress-admin-image-choice <?php echo $options['sidebar_position'] === 'right' ? 'active' : ''; ?>">
                                        <input type="radio" name="sidebar_position" value="right" <?php checked($options['sidebar_position'], 'right'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/sidebar-right.png" alt="Right Sidebar">
                                        <span><?php echo esc_html__('Right', 'bloglypress'); ?></span>
                                    </label>
                                    
                                    <label class="bloglypress-admin-image-choice <?php echo $options['sidebar_position'] === 'left' ? 'active' : ''; ?>">
                                        <input type="radio" name="sidebar_position" value="left" <?php checked($options['sidebar_position'], 'left'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/sidebar-left.png" alt="Left Sidebar">
                                        <span><?php echo esc_html__('Left', 'bloglypress'); ?></span>
                                    </label>
                                    
                                    <label class="bloglypress-admin-image-choice <?php echo $options['sidebar_position'] === 'none' ? 'active' : ''; ?>">
                                        <input type="radio" name="sidebar_position" value="none" <?php checked($options['sidebar_position'], 'none'); ?>>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/admin/sidebar-none.png" alt="No Sidebar">
                                        <span><?php echo esc_html__('None', 'bloglypress'); ?></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Featured Image', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_featured_image" name="show_featured_image" value="1" <?php checked($options['show_featured_image'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display featured image on single post.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Post Elements', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Author Box', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_author_box" name="show_author_box" value="1" <?php checked($options['show_author_box'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display author information box after post content.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Post Navigation', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_post_navigation" name="show_post_navigation" value="1" <?php checked($options['show_post_navigation'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display links to previous and next posts.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Social Sharing', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_social_sharing" name="show_social_sharing" value="1" <?php checked($options['show_social_sharing'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display social sharing buttons.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Related Posts', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show Related Posts', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_related_posts" name="show_related_posts" value="1" <?php checked($options['show_related_posts'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display related posts after post content.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="related_posts_count"><?php echo esc_html__('Related Posts Count', 'bloglypress'); ?></label>
                                <input type="number" id="related_posts_count" name="related_posts_count" value="<?php echo esc_attr($options['related_posts_count']); ?>" min="2" max="12" class="bloglypress-admin-input">
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Number of related posts to display.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php $this->render_form_footer(); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render footer page
     */
    public function render_footer_page() {
        $options = $this->get_options('footer');
        ?>
        <div class="bloglypress-admin-wrapper">
            <?php $this->render_header(__('Footer', 'bloglypress')); ?>
            
            <div class="bloglypress-admin-content">
                <form id="bloglypress-options-form" data-section="footer">
                    <?php wp_nonce_field('bloglypress_options_action', 'bloglypress_options_nonce'); ?>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Footer Layout', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="widgets_columns"><?php echo esc_html__('Footer Widgets Columns', 'bloglypress'); ?></label>
                                <select id="widgets_columns" name="widgets_columns" class="bloglypress-admin-select">
                                    <option value="1" <?php selected($options['widgets_columns'], '1'); ?>>1</option>
                                    <option value="2" <?php selected($options['widgets_columns'], '2'); ?>>2</option>
                                    <option value="3" <?php selected($options['widgets_columns'], '3'); ?>>3</option>
                                    <option value="4" <?php selected($options['widgets_columns'], '4'); ?>>4</option>
                                </select>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Select number of widget columns in the footer.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Footer Content', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="copyright_text"><?php echo esc_html__('Copyright Text', 'bloglypress'); ?></label>
                                <textarea id="copyright_text" name="copyright_text" rows="3" class="bloglypress-admin-textarea"><?php echo esc_textarea($options['copyright_text']); ?></textarea>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Enter your copyright text. Use {year} to dynamically display the current year.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Show "Powered by" Text', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="show_powered_by" name="show_powered_by" value="1" <?php checked($options['show_powered_by'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display "Powered by WordPress | Theme: BloglyPress" text in footer.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Back to Top Button', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="back_to_top" name="back_to_top" value="1" <?php checked($options['back_to_top'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Display back to top button.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php $this->render_form_footer(); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render performance page
     */
    public function render_performance_page() {
        $options = $this->get_options('performance');
        ?>
        <div class="bloglypress-admin-wrapper">
            <?php $this->render_header(__('Performance', 'bloglypress')); ?>
            
            <div class="bloglypress-admin-content">
                <form id="bloglypress-options-form" data-section="performance">
                    <?php wp_nonce_field('bloglypress_options_action', 'bloglypress_options_nonce'); ?>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Media Optimization', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Lazy Loading', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="enable_lazy_loading" name="enable_lazy_loading" value="1" <?php checked($options['enable_lazy_loading'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Enable lazy loading for images.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('WebP Images', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="enable_webp" name="enable_webp" value="1" <?php checked($options['enable_webp'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Use WebP image format when available.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Asset Optimization', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Minify Assets', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="minify_assets" name="minify_assets" value="1" <?php checked($options['minify_assets'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Minify CSS and JavaScript files.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Performance Status', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-performance-report">
                                <div class="bloglypress-admin-performance-score">
                                    <div class="bloglypress-admin-performance-circle">
                                        <svg viewBox="0 0 36 36" class="circular-chart">
                                            <path class="circle-bg" d="M18 2.0845
                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <path class="circle" stroke-dasharray="85, 100" d="M18 2.0845
                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <text x="18" y="20.35" class="percentage">85%</text>
                                        </svg>
                                    </div>
                                    <p><?php echo esc_html__('Performance Score', 'bloglypress'); ?></p>
                                </div>
                                
                                <div class="bloglypress-admin-performance-metrics">
                                    <div class="bloglypress-admin-metric">
                                        <span class="bloglypress-admin-metric-label"><?php echo esc_html__('CSS Files', 'bloglypress'); ?></span>
                                        <span class="bloglypress-admin-metric-value">5</span>
                                    </div>
                                    
                                    <div class="bloglypress-admin-metric">
                                        <span class="bloglypress-admin-metric-label"><?php echo esc_html__('JS Files', 'bloglypress'); ?></span>
                                        <span class="bloglypress-admin-metric-value">7</span>
                                    </div>
                                    
                                    <div class="bloglypress-admin-metric">
                                        <span class="bloglypress-admin-metric-label"><?php echo esc_html__('Page Size', 'bloglypress'); ?></span>
                                        <span class="bloglypress-admin-metric-value">~450KB</span>
                                    </div>
                                    
                                    <div class="bloglypress-admin-metric">
                                        <span class="bloglypress-admin-metric-label"><?php echo esc_html__('HTTP Requests', 'bloglypress'); ?></span>
                                        <span class="bloglypress-admin-metric-value">18</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bloglypress-admin-performance-tips">
                                <h3><?php echo esc_html__('Performance Tips', 'bloglypress'); ?></h3>
                                <ul>
                                    <li><span class="mdi mdi-check-circle"></span> <?php echo esc_html__('Optimize images before uploading', 'bloglypress'); ?></li>
                                    <li><span class="mdi mdi-check-circle"></span> <?php echo esc_html__('Use a caching plugin for better performance', 'bloglypress'); ?></li>
                                    <li><span class="mdi mdi-check-circle"></span> <?php echo esc_html__('Minimize the use of external scripts', 'bloglypress'); ?></li>
                                    <li><span class="mdi mdi-check-circle"></span> <?php echo esc_html__('Consider using a CDN for static assets', 'bloglypress'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <?php $this->render_form_footer(); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render advanced page
     */
    public function render_advanced_page() {
        $options = $this->get_options('advanced');
        ?>
        <div class="bloglypress-admin-wrapper">
            <?php $this->render_header(__('Advanced', 'bloglypress')); ?>
            
            <div class="bloglypress-admin-content">
                <form id="bloglypress-options-form" data-section="advanced">
                    <?php wp_nonce_field('bloglypress_options_action', 'bloglypress_options_nonce'); ?>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Custom Code', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field">
                                <label for="custom_css"><?php echo esc_html__('Custom CSS', 'bloglypress'); ?></label>
                                <textarea id="custom_css" name="custom_css" rows="8" class="bloglypress-admin-code-editor"><?php echo esc_textarea($options['custom_css']); ?></textarea>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Add custom CSS code here. It will be added to both the head of your site.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field">
                                <label for="custom_js"><?php echo esc_html__('Custom JavaScript', 'bloglypress'); ?></label>
                                <textarea id="custom_js" name="custom_js" rows="8" class="bloglypress-admin-code-editor"><?php echo esc_textarea($options['custom_js']); ?></textarea>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Add custom JavaScript code here. It will be added to the footer of your site.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bloglypress-admin-card">
                        <div class="bloglypress-admin-card-header">
                            <h2><?php echo esc_html__('Advanced Features', 'bloglypress'); ?></h2>
                        </div>
                        <div class="bloglypress-admin-card-body">
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Enable Schema Markup', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="enable_schema" name="enable_schema" value="1" <?php checked($options['enable_schema'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Add schema.org structured data for better SEO.', 'bloglypress'); ?></p>
                            </div>
                            
                            <div class="bloglypress-admin-field-toggle">
                                <span class="bloglypress-admin-field-label"><?php echo esc_html__('Enable Portfolio', 'bloglypress'); ?></span>
                                <label class="bloglypress-admin-toggle">
                                    <input type="checkbox" id="enable_portfolio" name="enable_portfolio" value="1" <?php checked($options['enable_portfolio'], true); ?>>
                                    <span class="bloglypress-admin-toggle-slider"></span>
                                </label>
                                <p class="bloglypress-admin-field-description"><?php echo esc_html__('Enable portfolio custom post type and functionality.', 'bloglypress'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php $this->render_form_footer(); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render import/export page
     */
    public function render_import_export_page() {
        ?>
        <div class="bloglypress-admin-wrapper">
            <?php $this->render_header(__('Import / Export', 'bloglypress')); ?>
            
            <div class="bloglypress-admin-content">
                <div class="bloglypress-admin-card">
                    <div class="bloglypress-admin-card-header">
                        <h2><?php echo esc_html__('Export Settings', 'bloglypress'); ?></h2>
                    </div>
                    <div class="bloglypress-admin-card-body">
                        <p><?php echo esc_html__('Export your theme settings for backup or migration to another site.', 'bloglypress'); ?></p>
                        <button type="button" id="bloglypress-export-options" class="bloglypress-admin-button">
                            <span class="mdi mdi-download"></span> <?php echo esc_html__('Export Theme Settings', 'bloglypress'); ?>
                        </button>
                    </div>
                </div>
                
                <div class="bloglypress-admin-card">
                    <div class="bloglypress-admin-card-header">
                        <h2><?php echo esc_html__('Import Settings', 'bloglypress'); ?></h2>
                    </div>
                    <div class="bloglypress-admin-card-body">
                        <p><?php echo esc_html__('Import theme settings from a previously exported file.', 'bloglypress'); ?></p>
                        <form id="bloglypress-import-options-form" method="post" enctype="multipart/form-data">
                            <?php wp_nonce_field('bloglypress_options_action', 'bloglypress_options_nonce'); ?>
                            <div class="bloglypress-admin-file-upload">
                                <input type="file" id="bloglypress-import-file" name="import_file" accept=".json">
                                <label for="bloglypress-import-file">
                                    <span class="mdi mdi-upload"></span>
                                    <span class="upload-text"><?php echo esc_html__('Choose a file or drag it here', 'bloglypress'); ?></span>
                                    <span id="bloglypress-import-file-name"></span>
                                </label>
                            </div>
                            <button type="submit" class="bloglypress-admin-button">
                                <span class="mdi mdi-import"></span> <?php echo esc_html__('Import Theme Settings', 'bloglypress'); ?>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="bloglypress-admin-card">
                    <div class="bloglypress-admin-card-header">
                        <h2><?php echo esc_html__('Reset Settings', 'bloglypress'); ?></h2>
                    </div>
                    <div class="bloglypress-admin-card-body">
                        <p><?php echo esc_html__('Reset all theme settings to default values. This action cannot be undone.', 'bloglypress'); ?></p>
                        <button type="button" id="bloglypress-reset-options" class="bloglypress-admin-button bloglypress-admin-button-danger">
                            <span class="mdi mdi-refresh"></span> <?php echo esc_html__('Reset All Settings', 'bloglypress'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render header
     */
    private function render_header($title) {
        ?>
        <div class="bloglypress-admin-header">
            <div class="bloglypress-admin-logo">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/bloglypress-logo.png" alt="BloglyPress">
            </div>
            <div class="bloglypress-admin-title">
                <h1><?php echo esc_html($title); ?></h1>
            </div>
            <div class="bloglypress-admin-version">
                <span><?php echo esc_html__('Version', 'bloglypress'); ?> 1.0.0</span>
            </div>
        </div>
        
        <div class="bloglypress-admin-notices"></div>
        <?php
    }

    /**
     * Render form footer
     */
    private function render_form_footer() {
        ?>
        <div class="bloglypress-admin-form-footer">
            <button type="button" class="bloglypress-admin-button bloglypress-admin-button-primary bloglypress-save-options">
                <span class="mdi mdi-content-save"></span> <?php echo esc_html__('Save Changes', 'bloglypress'); ?>
            </button>
            
            <button type="button" class="bloglypress-admin-button bloglypress-reset-section">
                <span class="mdi mdi-refresh"></span> <?php echo esc_html__('Reset Section', 'bloglypress'); ?>
            </button>
        </div>
        <?php
    }

    /**
     * AJAX save options
     */
    public function ajax_save_options() {
        // Check nonce
        if (!check_ajax_referer('bloglypress_options_action', 'nonce', false)) {
            wp_send_json_error(array('message' => __('Security check failed.', 'bloglypress')));
        }
        
        // Check capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this.', 'bloglypress')));
        }
        
        // Get form data
        parse_str($_POST['formData'], $form_data);
        
        // Get section
        $section = sanitize_text_field($_POST['section']);
        
        // Validate section
        if (!array_key_exists($section, $this->options)) {
            wp_send_json_error(array('message' => __('Invalid section.', 'bloglypress')));
        }
        
        // Prepare options
        $options = array();
        
        // Process form data
        foreach ($form_data as $key => $value) {
            if ($key === 'bloglypress_options_nonce') {
                continue;
            }
            
            // Handle checkboxes
            if (is_string($value) && $value === '1') {
                $options[$key] = true;
            } else {
                $options[$key] = $value;
            }
        }
        
        // Handle unchecked checkboxes (they are not included in form data)
        foreach ($this->options[$section] as $option_key => $option_value) {
            if (is_bool($option_value) && !isset($options[$option_key])) {
                $options[$option_key] = false;
            }
        }
        
        // Save options
        update_option('bloglypress_' . $section . '_options', $options);
        
        // Return success
        wp_send_json_success(array('message' => __('Settings saved successfully.', 'bloglypress')));
    }

    /**
     * AJAX reset options
     */
    public function ajax_reset_options() {
        // Check nonce
        if (!check_ajax_referer('bloglypress_options_action', 'nonce', false)) {
            wp_send_json_error(array('message' => __('Security check failed.', 'bloglypress')));
        }
        
        // Check capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this.', 'bloglypress')));
        }
        
        // Get section
        $section = isset($_POST['section']) ? sanitize_text_field($_POST['section']) : '';
        
        if ($section && array_key_exists($section, $this->options)) {
            // Reset specific section
            delete_option('bloglypress_' . $section . '_options');
        } else {
            // Reset all sections
            foreach (array_keys($this->options) as $section_key) {
                delete_option('bloglypress_' . $section_key . '_options');
            }
        }
        
        // Return success
        wp_send_json_success(array('message' => __('Settings reset successfully.', 'bloglypress')));
    }

    /**
     * AJAX export options
     */
    public function ajax_export_options() {
        // Check nonce
        if (!check_ajax_referer('bloglypress_options_action', 'nonce', false)) {
            wp_send_json_error(array('message' => __('Security check failed.', 'bloglypress')));
        }
        
        // Check capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this.', 'bloglypress')));
        }
        
        // Get all options
        $options = array();
        
        foreach (array_keys($this->options) as $section) {
            $options[$section] = get_option('bloglypress_' . $section . '_options', array());
        }
        
        // Return options
        wp_send_json_success($options);
    }

    /**
     * AJAX import options
     */
    public function ajax_import_options() {
        // Check nonce
        if (!check_ajax_referer('bloglypress_options_action', 'bloglypress_options_nonce', false)) {
            wp_send_json_error(array('message' => __('Security check failed.', 'bloglypress')));
        }
        
        // Check capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this.', 'bloglypress')));
        }
        
        // Check file
        if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error(array('message' => __('Please select a valid file to import.', 'bloglypress')));
        }
        
        // Get file contents
        $file_contents = file_get_contents($_FILES['import_file']['tmp_name']);
        
        // Decode JSON
        $options = json_decode($file_contents, true);
        
        // Check if valid JSON
        if ($options === null) {
            wp_send_json_error(array('message' => __('The uploaded file is not a valid JSON file.', 'bloglypress')));
        }
        
        // Import options
        foreach ($options as $section => $section_options) {
            if (array_key_exists($section, $this->options)) {
                update_option('bloglypress_' . $section . '_options', $section_options);
            }
        }
        
        // Return success
        wp_send_json_success(array('message' => __('Settings imported successfully.', 'bloglypress')));
    }
}

// Initialize Theme Options
BloglyPress_Theme_Options::get_instance();
