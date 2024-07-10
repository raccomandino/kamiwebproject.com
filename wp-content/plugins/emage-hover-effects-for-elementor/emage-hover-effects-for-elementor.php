<?php
/**
 * Plugin Name: 		            Emage Hover Effects for Elementor
 * Plugin URI:  		            https://imagehoverpro.blocksera.com
 * Author: 				            Blocksera
 * Author URI:			            https://blocksera.com
 * Description: 		            Unlimited image hover effects to your web pages using this Elementor addon.
 * Version:     		            4.3.4
 * Requires at least:               4.6
 * Requires PHP:                    5.6
 * Tested up to:                    5.8
 * License: 			            GPL v3
 * Text Domain: 		            ehe-lang
 * Domain Path: 		            /languages
 * Elementor tested up to:          3.3.1
 * Elementor Pro tested up to:      3.3.1
**/

if (!defined('ABSPATH')) {
    exit;
}

define('EHE_VERSION', '4.3.4');
define('EHE_MINIMUM_ELEMENTOR_VERSION', '1.1.2');
define('EHE_PATH', plugin_dir_path(__FILE__));
define('EHE_URL', plugin_dir_url(__FILE__));

require(EHE_PATH . 'includes/updater.php');
require(EHE_PATH . 'includes/upgrades.php');

class Emage_Hover_Effects {

    private static $_instance = null;

    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }

    public function __construct() {
        $this->config = array(
            'license' => 'true',
            'license_key' => 'nullmasterinbabiato'
        );
        $this->options = array();

        $this->options['config'] = array_merge($this->config, get_option('ehe_config', array()));

        add_action('plugins_loaded', [$this, 'init']);
        $this->updater = new Blocksera_Updater(__FILE__, 'emage-hover-effects-for-elementor', '4NymbyYaJ2VaExZbeLbN', $this->options['config']['license_key']);
        $this->updater->checker->addResultFilter(array($this, 'refreshLicenseFromPluginInfo'));
    }
    
    public function init() {

        // Check if Elementor installed and activated
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
			return;
        }
        
        // Check for required Elementor version
		if (!version_compare(ELEMENTOR_VERSION, EHE_MINIMUM_ELEMENTOR_VERSION, '>=')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
			return;
        }
        
        add_action('elementor/dynamic_tags/register_tags', function($dynamic_tags) {
            include_once(EHE_PATH . 'includes/product-gallery-image-tag.php');
            $dynamic_tags->register_tag( 'Product_Gallery_Image' );
        });
		
		add_action( 'elementor/editor/after_enqueue_scripts', function() {
            wp_register_script('eihe-admin-editor', EHE_URL . 'assets/js/editor.js', array(), EHE_VERSION, true);
            wp_localize_script('eihe-admin-editor', 'emage', array('ajax_url' => admin_url('admin-ajax.php')));
            wp_enqueue_script('eihe-admin-editor');
        });
        
        add_action('wp_ajax_emage_license', array($this, 'check_license'));
        add_action('elementor/frontend/after_enqueue_styles', array($this, 'includes'));
        add_action('elementor/frontend/after_register_scripts', array($this, 'scripts'));
		add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
        add_action('elementor/controls/controls_registered', array($this, 'register_controls'));
        add_filter('wpml_elementor_widgets_to_translate', array($this, 'wpml_widgets_to_translate_filter'));
        add_filter('plugin_row_meta', array($this, 'insert_plugin_row_meta'), 10, 2);
    }
    
    public function insert_plugin_row_meta($links, $file) {
        if (plugin_basename(__FILE__) == $file) {
            // docs
            $links[] = sprintf('<a href="https://docs.blocksera.com/emage-hover-effects-for-elementor?utm_source=wp&utm_medium=admin" target="_blank">' . __('Docs', 'ehe-lang') . '</a>');
        }

        return $links;
    }

	public function  register_controls() {
        if (!class_exists('Elementor_RepeatSelect_Control')) {
            require_once EHE_PATH . 'includes/repeatselect-control.php';
            $controls_manager = \Elementor\Plugin::$instance->controls_manager;
            $controls_manager->register_control('repeatselect', new \Elementor\Elementor_RepeatSelect_Control());
        }
        if (!class_exists('Elementor_Html5Sortable_Control')) {
            require_once EHE_PATH . 'includes/html5sortable-control.php';
            $controls_manager = \Elementor\Plugin::$instance->controls_manager;
            $controls_manager->register_control('html5sortable', new \Elementor\Elementor_Html5Sortable_Control());
        }
    }

    public function register_widgets() {
        require_once(EHE_PATH . 'widgets/emage-hover.php');
        require_once(EHE_PATH . 'widgets/emage-post-grid.php');
    }

    public function includes() {
		wp_enqueue_style('eihe-front-style', EHE_URL . 'assets/css/style.min.css', array(), EHE_VERSION);
    }

    public function scripts() {
        wp_register_script('emage', EHE_URL . 'assets/js/script.js', array('jquery'), EHE_VERSION, true);
    }
    
    public function admin_notice_minimum_elementor_version() {

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			esc_html__('%1$s requires %2$s version %3$s or greater.', 'emage-hover-effects-for-elementor'),
			'<strong>' . esc_html__('Emage Hover Effects for Elementor', 'emage-hover-effects-for-elementor') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'emage-hover-effects-for-elementor') . '</strong>',
			EHE_MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }
    
    public function admin_notice_minimum_php_version() {

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			esc_html__('%1$s requires %2$s version %3$s or greater.', 'emage-hover-effects-for-elementor'),
			'<strong>' . esc_html__('Emage Hover Effects for Elementor', 'emage-hover-effects-for-elementor') . '</strong>',
			'<strong>' . esc_html__('PHP', 'emage-hover-effects-for-elementor') . '</strong>',
			EHE_MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    public function wpml_widgets_to_translate_filter($widgets) {

		$widgets[ 'emage_hover_effects' ] = [
		    'conditions' => [ 'widgetType' => 'emage_hover_effects' ],
            'fields'     => [
                [
                    'field'       => 'title',
                    'type'        => __( 'Emage: Title', 'emage-hover' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'subtitle',
                    'type'        => __( 'Emage: Subtitle', 'emage-hover' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'content',
                    'type'        => __( 'Emage: Content', 'emage-hover' ),
                    'editor_type' => 'AREA'
                ],
            ],
            'integration-class' => 'Emage_WPML'
        ];
        
        require_once(EHE_PATH . 'includes/wpml.php');

        return $widgets;
        
    }
    
    public function check_license() {

        $license_action = sanitize_key($_POST['license_action']);
        $this->options['config']['license_key'] = 'nullmasterinbabiato';

        $response = array(
            'message' => __('Your purchase code is not valid. Please try again.', 'ehe-lang')
        );
		
        switch ($license_action) {

            case 'activate':

                $queryargs = array(
                    'code' => 'nullmasterinbabiato'
                );

                $update = $this->updater->request_info($queryargs);

               
                $response['message'] = __('Emage Hover Effects has been activated.<br><br>Please update and refresh this page.', 'ehe-lang');
                

                $this->options['config']['license'] = $update->license;

                break;

            case 'deactivate':

                $queryargs = array(
                    'code' => $this->options['config']['license_key'],
                    'remove' => 'true'
                );

                $update = $this->updater->request_info($queryargs);

                break;
        }

        $response['license'] = $this->options['config']['license'];
        update_option('ehe_config', $this->options['config']);
        wp_send_json($response);
    }

    public function refreshLicenseFromPluginInfo($pluginInfo, $result) {
       
            $apiResponse = json_decode($result['body']);
            
            $update = array('license' => 'true', 'license_key' => 'nullmasterinbabiato');
            $this->options['config'] = array_merge($this->options['config'], $update);
            update_option('ehe_config', $this->options['config']);
           
       
        return $pluginInfo;
    }
    
}

Emage_Hover_Effects::get_instance();

?>