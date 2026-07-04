<?php
/**
 * Plugin Name: T-Platform WooCommerce Theme
 * Plugin URI: https://github.com/mohyat/t-platform-woo-theme
 * Description: Profesionalna WooCommerce tema po vzoru Foxway.dk - B2B dizajn za tehnicne izdelke
 * Version: 0.2.1
 * Author: T-Platform
 * Author URI: https://github.com/mohyat
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: t-platform-woo-theme
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.4
 */

// Preprecim neposredni dostop
if (!defined('ABSPATH')) {
    exit;
}

// Definiraj konstante plugin-a
define('T_PLATFORM_VERSION', '0.2.1');
define('T_PLATFORM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('T_PLATFORM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('T_PLATFORM_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Glavni razred plugin-a
 */
class T_Platform_WooCommerce_Theme {
    
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
        $this->load_dependencies();
        $this->init_hooks();
    }
    
    /**
     * Nalozi odvisnosti
     */
    private function load_dependencies() {
        // Nalozi setup class
        require_once T_PLATFORM_PLUGIN_DIR . 'includes/class-t-platform-setup.php';
    }
    
    /**
     * Inicializiraj hooks
     */
    private function init_hooks() {
        // Aktivacija plugin-a
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Deaktivacija plugin-a
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Inicializiraj setup
        add_action('plugins_loaded', array($this, 'init_setup'));
        
        // Preveri ce je WooCommerce aktiven
        add_action('admin_init', array($this, 'check_woocommerce'));
    }
    
    /**
     * Aktivacija plugin-a
     */
    public function activate() {
        // Preveri ce je WooCommerce aktiven
        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(
                __('T-Platform WooCommerce Theme zahteva WooCommerce plugin. Prosimo namestite in aktivirajte WooCommerce.', 't-platform-woo-theme'),
                __('Napaka aktivacije', 't-platform-woo-theme'),
                array('back_link' => true)
            );
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Log aktivacije
        update_option('t_platform_activated', true);
        update_option('t_platform_version', T_PLATFORM_VERSION);
    }
    
    /**
     * Deaktivacija plugin-a
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Log deaktivacije
        delete_option('t_platform_activated');
    }
    
    /**
     * Inicializiraj setup
     */
    public function init_setup() {
        // Preveri ce je WooCommerce aktiven
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }
        
        // Inicializiraj setup class
        T_Platform_Setup::get_instance();
    }
    
    /**
     * Preveri WooCommerce
     */
    public function check_woocommerce() {
        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
        }
    }
    
    /**
     * WooCommerce manjka opozorilo
     */
    public function woocommerce_missing_notice() {
        ?>
        <div class="error">
            <p>
                <?php
                printf(
                    __('%1$s T-Platform WooCommerce Theme %2$s zahteva %3$s WooCommerce %4$s plugin. Prosimo namestite in aktivirajte WooCommerce.', 't-platform-woo-theme'),
                    '<strong>',
                    '</strong>',
                    '<a href="https://woocommerce.com/" target="_blank">',
                    '</a>'
                );
                ?>
            </p>
        </div>
        <?php
    }
}

// Inicializiraj plugin
function t_platform_woo_theme() {
    return T_Platform_WooCommerce_Theme::get_instance();
}

// Zazeni plugin
t_platform_woo_theme();
