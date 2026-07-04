<?php
/**
 * Setup Class
 *
 * @package T_Platform_WooCommerce_Theme
 * @since 0.1.0
 */

// Preprecim neposredni dostop
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Setup razred
 */
class T_Platform_Setup {
    
    /**
     * Instance razreda
     */
    private static $instance = null;
    
    /**
     * Pridobi instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Konstruktor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Inicializiraj hooks
     */
    private function init_hooks() {
        // Theme support
        add_action('after_setup_theme', array($this, 'theme_support'));
        
        // Enqueue scripts in styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
        // WooCommerce support
        add_action('after_setup_theme', array($this, 'woocommerce_support'));
        
        // Custom product fields
        add_action('init', array($this, 'register_custom_product_fields'));
        
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    /**
     * Theme support features
     */
    public function theme_support() {
        // Add theme support
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('responsive-embeds');
        add_theme_support('align-wide');
        
        // Logo support
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 300,
            'flex-height' => true,
            'flex-width'  => true,
        ));
        
        // Custom background
        add_theme_support('custom-background', array(
            'default-color' => 'ffffff',
        ));
    }
    
    /**
     * WooCommerce support
     */
    public function woocommerce_support() {
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Enqueue scripts in styles
     */
    public function enqueue_scripts() {
        // Main CSS
        wp_enqueue_style(
            't-platform-main',
            T_PLATFORM_PLUGIN_URL . 'assets/css/t-platform-main.css',
            array(),
            T_PLATFORM_VERSION
        );
        
        // Product cards CSS
        wp_enqueue_style(
            't-platform-product-cards',
            T_PLATFORM_PLUGIN_URL . 'assets/css/t-platform-product-cards.css',
            array('t-platform-main'),
            T_PLATFORM_VERSION
        );
        
        // Responsive CSS
        wp_enqueue_style(
            't-platform-responsive',
            T_PLATFORM_PLUGIN_URL . 'assets/css/t-platform-responsive.css',
            array('t-platform-main'),
            T_PLATFORM_VERSION
        );
        
        // Main JS
        wp_enqueue_script(
            't-platform-main',
            T_PLATFORM_PLUGIN_URL . 'assets/js/t-platform-main.js',
            array('jquery'),
            T_PLATFORM_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('t-platform-main', 'tPlatform', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('t_platform_nonce'),
        ));
    }
    
    /**
     * Admin enqueue scripts
     */
    public function admin_enqueue_scripts($hook) {
        // Samo na nasih admin straneh
        if (strpos($hook, 't-platform') === false) {
            return;
        }
        
        wp_enqueue_style(
            't-platform-admin',
            T_PLATFORM_PLUGIN_URL . 'assets/css/t-platform-admin.css',
            array(),
            T_PLATFORM_VERSION
        );
        
        wp_enqueue_script(
            't-platform-admin',
            T_PLATFORM_PLUGIN_URL . 'assets/js/t-platform-admin.js',
            array('jquery'),
            T_PLATFORM_VERSION,
            true
        );
    }
    
    /**
     * Registriraj custom product fields
     */
    public function register_custom_product_fields() {
        // CPU
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_cpu_field'));
        add_action('woocommerce_process_product_meta', array($this, 'save_cpu_field'));
        
        // RAM
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_ram_field'));
        add_action('woocommerce_process_product_meta', array($this, 'save_ram_field'));
        
        // Storage
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_storage_field'));
        add_action('woocommerce_process_product_meta', array($this, 'save_storage_field'));
        
        // Screen
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_screen_field'));
        add_action('woocommerce_process_product_meta', array($this, 'save_screen_field'));
        
        // Warranty
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_warranty_field'));
        add_action('woocommerce_process_product_meta', array($this, 'save_warranty_field'));
        
        // Quality Grade
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_quality_grade_field'));
        add_action('woocommerce_process_product_meta', array($this, 'save_quality_grade_field'));
    }
    
    /**
     * CPU field
     */
    public function add_cpu_field() {
        global $post;
        echo '<div class="options_group">';
        woocommerce_wp_text_input(array(
            'id'          => '_t_platform_cpu',
            'label'       => __('CPU', 't-platform-woo-theme'),
            'placeholder' => 'npr. Intel Core i7-10700',
            'desc_tip'    => true,
            'description' => __('Procesor', 't-platform-woo-theme'),
        ));
        echo '</div>';
    }
    
    public function save_cpu_field($post_id) {
        if (isset($_POST['_t_platform_cpu'])) {
            update_post_meta($post_id, '_t_platform_cpu', sanitize_text_field($_POST['_t_platform_cpu']));
        }
    }
    
    /**
     * RAM field
     */
    public function add_ram_field() {
        woocommerce_wp_text_input(array(
            'id'          => '_t_platform_ram',
            'label'       => __('RAM', 't-platform-woo-theme'),
            'placeholder' => 'npr. 16GB DDR4',
            'desc_tip'    => true,
            'description' => __('Pomnilnik', 't-platform-woo-theme'),
        ));
    }
    
    public function save_ram_field($post_id) {
        if (isset($_POST['_t_platform_ram'])) {
            update_post_meta($post_id, '_t_platform_ram', sanitize_text_field($_POST['_t_platform_ram']));
        }
    }
    
    /**
     * Storage field
     */
    public function add_storage_field() {
        woocommerce_wp_text_input(array(
            'id'          => '_t_platform_storage',
            'label'       => __('Storage', 't-platform-woo-theme'),
            'placeholder' => 'npr. 512GB SSD',
            'desc_tip'    => true,
            'description' => __('Shramba', 't-platform-woo-theme'),
        ));
    }
    
    public function save_storage_field($post_id) {
        if (isset($_POST['_t_platform_storage'])) {
            update_post_meta($post_id, '_t_platform_storage', sanitize_text_field($_POST['_t_platform_storage']));
        }
    }
    
    /**
     * Screen field
     */
    public function add_screen_field() {
        woocommerce_wp_text_input(array(
            'id'          => '_t_platform_screen',
            'label'       => __('Screen', 't-platform-woo-theme'),
            'placeholder' => 'npr. 15.6" Full HD',
            'desc_tip'    => true,
            'description' => __('Zaslon', 't-platform-woo-theme'),
        ));
    }
    
    public function save_screen_field($post_id) {
        if (isset($_POST['_t_platform_screen'])) {
            update_post_meta($post_id, '_t_platform_screen', sanitize_text_field($_POST['_t_platform_screen']));
        }
    }
    
    /**
     * Warranty field
     */
    public function add_warranty_field() {
        woocommerce_wp_text_input(array(
            'id'          => '_t_platform_warranty',
            'label'       => __('Warranty', 't-platform-woo-theme'),
            'placeholder' => 'npr. 24 months',
            'desc_tip'    => true,
            'description' => __('Garancija', 't-platform-woo-theme'),
        ));
    }
    
    public function save_warranty_field($post_id) {
        if (isset($_POST['_t_platform_warranty'])) {
            update_post_meta($post_id, '_t_platform_warranty', sanitize_text_field($_POST['_t_platform_warranty']));
        }
    }
    
    /**
     * Quality Grade field
     */
    public function add_quality_grade_field() {
        woocommerce_wp_select(array(
            'id'          => '_t_platform_quality_grade',
            'label'       => __('Quality Grade', 't-platform-woo-theme'),
            'options'     => array(
                ''              => __('Select grade', 't-platform-woo-theme'),
                'teqcycle_pro'  => __('Teqcycle Pro', 't-platform-woo-theme'),
                'hp_certified'  => __('HP Certified', 't-platform-woo-theme'),
                'new_retail'    => __('New Retail', 't-platform-woo-theme'),
                'refurbished_a' => __('Refurbished Grade A', 't-platform-woo-theme'),
                'refurbished_b' => __('Refurbished Grade B', 't-platform-woo-theme'),
            ),
            'desc_tip'    => true,
            'description' => __('Kakovostni razred', 't-platform-woo-theme'),
        ));
    }
    
    public function save_quality_grade_field($post_id) {
        if (isset($_POST['_t_platform_quality_grade'])) {
            update_post_meta($post_id, '_t_platform_quality_grade', sanitize_text_field($_POST['_t_platform_quality_grade']));
        }
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('T-Platform Settings', 't-platform-woo-theme'),
            __('T-Platform', 't-platform-woo-theme'),
            'manage_options',
            't-platform-settings',
            array($this, 'render_settings_page'),
            'dashicons-admin-generic',
            58
        );
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p><?php _e('Nastavitve T-Platform WooCommerce Theme.', 't-platform-woo-theme'); ?></p>
            <p><?php _e('Verzija:', 't-platform-woo-theme'); ?> <strong><?php echo T_PLATFORM_VERSION; ?></strong></p>
            
            <div class="notice notice-info">
                <p>
                    <?php _e('Plugin je aktiven in deluje. V prihodnjih verzijah bodo dodane nastavitve za:', 't-platform-woo-theme'); ?>
                </p>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li><?php _e('Barve in stil', 't-platform-woo-theme'); ?></li>
                    <li><?php _e('Logotip', 't-platform-woo-theme'); ?></li>
                    <li><?php _e('Layout', 't-platform-woo-theme'); ?></li>
                    <li><?php _e('Header/Footer', 't-platform-woo-theme'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }
}
