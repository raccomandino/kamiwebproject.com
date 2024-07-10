<?php
/**
 * Plugin Name: OoohBoi Steroids for Elementor
 * Description: An awesome set of tools/options/settings that extend Elementor default/existing widgets and elements. It keeps the editor tidy, saves valuable resources and improves the workflow.
 * Version:     2.1.6
 * Author:      OoohBoi
 * Author URI:  https://www.youtube.com/c/OoohBoi
 * Text Domain: ooohboi-steroids
 * Domain Path: /lang
 * License: GPLv3
 * Elementor tested up to: 3.17
 * Elementor Pro tested up to: 3.17
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */

use Elementor\Core\Settings\Manager as SettingsManager;

defined( 'ABSPATH' ) || die(); // Exit if accessed directly.

/**
 * Main OoohBoi Steroids Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class OoohBoi_Steroids { 

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '2.1.6';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.12';

	/**
	 * Elementor Version for Containers
	 *
	 * @since 1.9.1
	 *
	 * @var string Elementor version required for particular extensions to work
	 */
	const ELEMENTOR_VERSION_CONTAINER = '3.12';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	*/
	const MINIMUM_PHP_VERSION = '7.2';

	/**
	 * Plugin URL and PATH
	 *
	 * @since 2.0.6
	 *
	 * @var string $OBS_DIR Full URL to the plugin
	 * @var string $OBS_URI Full PATH to the plugin
	*/
	public static $OBS_DIR = '';
	public static $OBS_URI = '';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var OoohBoi_Steroids The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Library globals
	 * 
	 * @since 1.7.3
	 *
	 * @access private
	 * @static
	 *
	 * @var OoohBoi_Steroids The single instance of the class.
	 */
	private static $sfe_lib_locomotive = 0;
	public static $sfe_lib_locomotive_multiplier = 1;
	public static $sfe_lib_locomotive_tablet = 0;
	public static $sfe_lib_locomotive_mobile = 0; 
	public static $sfe_lib_allow_refresh = 0;
	private static $sfe_lib_scroll_trigger = 0;
	private static $sfe_lib_scroll_to = 0;
	private static $sfe_lib_motion_path = 0;
	private static $sfe_lib_gsap = 0;
	private static $sfe_remove_locomotive_section_attribute = 0;
	private static $sfe_lib_barba = 0;
	private static $sfe_lib_anime = 0; 
	private static $sfe_lib_three = 0;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return OoohBoi_Steroids An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	
	public function __construct() {	
		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'ooohboi-steroids', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by 'plugins_loaded' action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		self::$OBS_DIR = plugin_dir_url( __FILE__ );
		self::$OBS_URI = plugin_dir_path( __FILE__ );

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version			
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// load common stuff functions
		require plugin_dir_path( __FILE__ ) . 'inc/exopite-simple-options/exopite-simple-options-framework-class.php';
		require plugin_dir_path( __FILE__ ) . 'inc/common-functions.php';

		// init EXOPIT ---------------------------------------------------------->
		$ob_settings_options = get_exopite_sof_option( 'steroids_for_elementor' );

		if( $ob_settings_options ) {
			// ... Locomotive Scroll
			if( isset( $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_scroll' ] ) && $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_scroll' ] && 'yes' === $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_scroll' ] ) self::$sfe_lib_locomotive = 1;
			// multiplier
			if( isset( $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_multiplier' ] ) && $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_multiplier' ] ) {
				$the_multiplier = $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_multiplier' ];
				if( ( $the_multiplier >= -10 ) && ( $the_multiplier <= 10 ) ) self::$sfe_lib_locomotive_multiplier = $the_multiplier;
			}
			// devices
			if( isset( $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_devices' ] ) && $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_devices' ] && in_array( 'allow-tablet', $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_devices' ] ) ) self::$sfe_lib_locomotive_tablet = 1;
			if( isset( $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_devices' ] ) && $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_devices' ] && in_array( 'allow-mobile', $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_devices' ] ) ) self::$sfe_lib_locomotive_mobile = 1;
			// allow refresh on resize
			if( isset( $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_allow_refresh' ] ) && $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_allow_refresh' ] && 'yes' === $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_allow_refresh' ] ) self::$sfe_lib_allow_refresh = 1;
			// remove section attributes
			if( isset( $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_remove_section_attribute' ] ) && $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_remove_section_attribute' ] && 'yes' === $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_remove_section_attribute' ] ) self::$sfe_remove_locomotive_section_attribute = 1;
			// ... GSAP 
			if( isset( $ob_settings_options[ 'ob_use_gsap' ] ) && $ob_settings_options[ 'ob_use_gsap' ] && 'yes' === $ob_settings_options[ 'ob_use_gsap' ] ) self::$sfe_lib_gsap = 1;
			// ... Scroll Trigger 
			if( isset( $ob_settings_options[ 'ob_use_scroll_trigger' ] ) && $ob_settings_options[ 'ob_use_scroll_trigger' ] && 'yes' === $ob_settings_options[ 'ob_use_scroll_trigger' ] ) self::$sfe_lib_scroll_trigger = 1;
			// ... Scroll To 
			if( isset( $ob_settings_options[ 'ob_use_scroll_to' ] ) && $ob_settings_options[ 'ob_use_scroll_to' ] && 'yes' === $ob_settings_options[ 'ob_use_scroll_to' ] ) self::$sfe_lib_scroll_to = 1;
			// ... Motion Path
			if( isset( $ob_settings_options[ 'ob_use_motion_path' ] ) && $ob_settings_options[ 'ob_use_motion_path' ] && 'yes' === $ob_settings_options[ 'ob_use_motion_path' ] ) self::$sfe_lib_motion_path = 1;
			// ... Barba
			if( isset( $ob_settings_options[ 'ob_use_barba' ] ) && $ob_settings_options[ 'ob_use_barba' ] && 'yes' === $ob_settings_options[ 'ob_use_barba' ] ) self::$sfe_lib_barba = 1; 
			// ... Anime
			if( isset( $ob_settings_options[ 'ob_use_anime' ] ) && $ob_settings_options[ 'ob_use_anime' ] && 'yes' === $ob_settings_options[ 'ob_use_anime' ] ) self::$sfe_lib_anime = 1; 
			// ... Three
			if( isset( $ob_settings_options[ 'ob_use_three' ] ) && $ob_settings_options[ 'ob_use_three' ] && 'yes' === $ob_settings_options[ 'ob_use_three' ] ) self::$sfe_lib_three = 1; 
		}

		// admin styles
		add_action( 'admin_enqueue_scripts', function() {

			/* TODO: better way to handle installed components */
			$tmp_ob_settings_options = get_exopite_sof_option( 'steroids_for_elementor' );

			wp_enqueue_style(
				'ooohboi-steroids-admin', 
				plugins_url( 'assets/css/admin.css', __FILE__ ),
				[],
				self::VERSION . '17012022c'
			);
			/* better templates library */
			if( isset( $tmp_ob_settings_options[ 'ob_use_btl' ] ) && $tmp_ob_settings_options[ 'ob_use_btl' ] && 'yes' === $tmp_ob_settings_options[ 'ob_use_btl' ] ) {

				wp_enqueue_style(
					'ooohboi-steroids-admin-btl', 
					plugins_url( 'assets/css/btl-admin.css', __FILE__ ),
					[],
					self::VERSION . '18012023e'
				);

			}

			wp_enqueue_media();
		} );

		// de-activation hook 
		register_deactivation_hook( __FILE__, [ $this, 'obs_on_deactivate' ] );

		// Editor Styles
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'ooohboi_register_styles_editor' ] );

		// Register/Enqueue Scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'ooohboi_register_scripts_front' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'ooohboi_register_styles' ] );
		
		// Editor Styles & Scripts
		add_action( 'elementor/editor/after_enqueue_scripts', function() {

			wp_enqueue_script(
				'ooohboi-steroids-editor',
				plugins_url( 'assets/js/ob-steroids-editor.js', __FILE__ ), 
				[ 'elementor-editor', 'jquery' ],
				self::VERSION . '15072022',
				true
			);
			// data to JS via wp_localize_script
			$local_data = [
				'dark_stylesheet_url' => self::ooohboi_dark_stylesheet_url(), 
				'light_stylesheet_url' => self::ooohboi_light_stylesheet_url(), 
			];
			wp_localize_script(
				'ooohboi-steroids-editor',
				'SteroidsEditorLocalized',
				$local_data
			);

		} );
		
		add_action( 'elementor/frontend/after_enqueue_styles', function() { 

			// locomotive scroll
			if( 1 === self::$sfe_lib_locomotive ) {
				wp_enqueue_style( 'locomotive-scroll-css' ); 
				wp_enqueue_script( 'locomotive-scroll-js' ); 
				wp_enqueue_script( 'locomotive-scroll-ctrl' );
				// things to pass to the js
				$device_settings = array( 
					'scroll_multiplier' => self::$sfe_lib_locomotive_multiplier, 
					'allow_tablet' => self::$sfe_lib_locomotive_tablet, 
					'allow_mobile' => self::$sfe_lib_locomotive_mobile, 
					'allow_refresh' => self::$sfe_lib_allow_refresh, 
					'remove_section_attribute' => self::$sfe_remove_locomotive_section_attribute, 
				); 
				wp_localize_script( 'locomotive-scroll-ctrl', 'device_settings', $device_settings );
			}
			// ssfe_lib_gsap 
			if( 1 === self::$sfe_lib_gsap ) {
				wp_enqueue_script( 'gsap-js' );  
			}
			// scroll trigger 
			if( 1 === self::$sfe_lib_scroll_trigger ) {
				wp_enqueue_script( 'scroll-trigger-js' ); 
			}
			// scroll to 
			if( 1 === self::$sfe_lib_scroll_to ) {
				wp_enqueue_script( 'scroll-to-js' ); 
			}
			// motion path
			if( 1 === self::$sfe_lib_motion_path ) {
				wp_enqueue_script( 'motion-path-js' ); 
			}
			// barba
			if( 1 === self::$sfe_lib_barba ) {
				wp_enqueue_script( 'barba-js' ); 
			}
			// anime
			if( 1 === self::$sfe_lib_anime ) {
				wp_enqueue_script( 'anime-js' ); 
			}
			// three
			if( 1 === self::$sfe_lib_three ) {
				wp_enqueue_script( 'three-js' ); 
			}
			// plugin stuff
			wp_enqueue_style( 'ooohboi-steroids-styles' ); 
			wp_enqueue_script( 'ooohboi-steroids' ); 
		} );

		// editor preview styles
		add_action( 'elementor/preview/enqueue_styles', function() {
			wp_enqueue_style(
				'ooohboi-steroids-preview',
				plugins_url( 'assets/css/preview.css', __FILE__ ),
				[ 'editor-preview' ],
				self::VERSION . '28042021'
			);
		} ); 

		// init extensions
		self::ooohboi_init_extensions( $ob_settings_options );

	}

	/*
		* On plugin deactivation
		*
		* @since 2.1.3
		*
		* @access public
	*/
    public function obs_on_deactivate() {

        delete_option( 'steroids_for_elementor' );

    }

	/*
		* Init Extensions
		*
		* @since 1.9.0
		*
		* @access public
	*/
    public function admin_notice_missing_main_plugin() {

        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'ooohboi-steroids' ),
            '<strong>' . esc_html__( 'Steroids for Elementor', 'ooohboi-steroids' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'ooohboi-steroids' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ooohboi-steroids' ),
			'<strong>' . esc_html__( 'Steroids for Elementor', 'ooohboi-steroids' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ooohboi-steroids' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ooohboi-steroids' ),
			'<strong>' . esc_html__( 'Steroids for Elementor', 'ooohboi-steroids' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ooohboi-steroids' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/*
		* Init Extensions
		*
		* @since 1.4.8		
		* @modified	1.8.2
		*
		* @access public
	*/
	public function ooohboi_init_extensions( $ob_settings_options ) {

		// Include extension classes
		self::ooohboi_take_steroids();

		// is container experiment active?
		$container_active = ( 'active' === get_option( 'elementor_experiment-container' ) ) ? true : false;

		$extensions_array = [ 
			'OoohBoi_Harakiri' => 'ob_use_harakiri', 
			'OoohBoi_Overlay_Underlay' => 'ob_use_poopart', 
			'OoohBoi_Overlaiz' => 'ob_use_overlaiz', 
			'OoohBoi_Paginini' => 'ob_use_paginini', 
			'OoohBoi_Breaking_Bad' => 'ob_use_breakingbad', 
			'OoohBoi_Glider' => 'ob_use_glider', 
			'OoohBoi_PhotoGiraffe' => 'ob_use_photogiraffe', 
			'OoohBoi_Teleporter' => 'ob_use_teleporter', 
			'OoohBoi_SearchCop' => 'ob_use_searchcop', 
			'OoohBoi_Videomasq' => 'ob_use_videomasq', 
			'OoohBoi_Butter_Button' => 'ob_use_butterbutton', 
			'OoohBoi_Perspektive' => 'ob_use_perspektive', 
			'OoohBoi_Shadough' => 'ob_use_shadough', 
			'OoohBoi_PhotoMorph' => 'ob_use_photomorph', 
			'OoohBoi_Commentz' => 'ob_use_commentz', 
			'OoohBoi_SpaceRat' => 'ob_use_spacerat', 
			'OoohBoi_Imbox' => 'ob_use_imbox', 
			'OoohBoi_Icobox' => 'ob_use_icobox', 
			'OoohBoi_Hover_Animator' => 'ob_use_hoveranimator', 
			'OoohBoi_Kontrolz' => 'ob_use_kontrolz', 
			'OoohBoi_Widget_Stalker' => 'ob_use_widgetstalker', 
			'OoohBoi_Pseudo' => 'ob_use_pseudo', 
			'OoohBoi_Bullet' => 'ob_use_bullet', 
			'OoohBoi_Container_Extras' => 'ob_use_container_extras', 
			'OoohBoi_Counterz' => 'ob_use_counterz', 
			'OoohBoi_Tabbr' => 'ob_use_tabbr', 
			'OoohBoi_Postman' => 'ob_use_postman', 
			/*'OoohBoi_Interactor' => 'ob_use_interactor', */ 
			'OoohBoi_Typo' => 'ob_use_typo', 
			'OoohBoi_Better_Templates_Library' => 'ob_use_btl' 
		];

		/* since 1.9.1 & Elementor 3.6+ */
		$exclude_with_containers = [];
		if( $container_active ) $exclude_with_containers = [ 'OoohBoi_Breaking_Bad', 'OoohBoi_PhotoGiraffe', 'OoohBoi_Teleporter', 'OoohBoi_Perspektive' ]; 

		/* since 2.1.3 */
		$disabled_by_default = [ 'OoohBoi_Better_Templates_Library' ];

		if( ! $ob_settings_options ) {

			foreach( $extensions_array as $extension_class => $extension_token ) {
				if( ! in_array( $extension_class, $exclude_with_containers ) && ! in_array( $extension_class, $disabled_by_default ) ) $extension_class::init();
			}

		} else {
			foreach( $extensions_array as $extension_class => $extension_token ) {

				if( ! isset( $ob_settings_options[ $extension_token ] ) && ! in_array( $extension_class, $exclude_with_containers ) ) {

					$ob_settings_options[ $extension_token ] = ( in_array( $extension_class, $disabled_by_default ) ) ? 'no' : 'yes';
					update_option( 'steroids_for_elementor', $ob_settings_options );

				} else {
					if( $ob_settings_options[ $extension_token ] && 'yes' === $ob_settings_options[ $extension_token ] && ! in_array( $extension_class, $exclude_with_containers ) ) { 

						$extension_class::init();

					}
				}
			}

			// include libraries that involve editor controls; Locomotive Scroll, GSAP/ScrollTrigger...
			if( $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_scroll' ] && 'yes' === $ob_settings_options[ 'fieldset_locomotive' ][ 'ob_use_locomotive_scroll' ] ) new OoohBoi_Locomotion();
			/*if( $container_active && $ob_settings_options[ 'ob_use_gsap' ] && 'yes' === $ob_settings_options[ 'ob_use_gsap' ] && $ob_settings_options[ 'ob_use_scroll_trigger' ] && 'yes' === $ob_settings_options[ 'ob_use_scroll_trigger' ] ) OoohBoi_Oh_Animator::init();*/
		}
		
	}
	/*
		* Init styles for Elementor Editor
		*
		* Include css files and register them
		*
		* NEW @since 1.9.0				
		*
		* @access public
	*/
	public function ooohboi_register_styles_editor() {

		$theme = SettingsManager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );

		/* TODO: better way to handle installed components */
		$tmp_ob_settings_options = get_exopite_sof_option( 'steroids_for_elementor' );

		if( 'light' !== $theme ) {

			$mq = 'all';
			if( 'auto' === $theme ) $mq = '(prefers-color-scheme: dark)';

			wp_enqueue_style( 'ooohboi-steroids-styles-editor-dark', self::ooohboi_dark_stylesheet_url(), [ 'elementor-editor' ], self::VERSION . '04072022a', $mq );

			/* better templates library */
			if( isset( $tmp_ob_settings_options[ 'ob_use_btl' ] ) && $tmp_ob_settings_options[ 'ob_use_btl' ] && 'yes' === $tmp_ob_settings_options[ 'ob_use_btl' ] ) {

				wp_enqueue_style(
					'btl-editor-dark', 
					plugins_url( 'assets/css/editor-dark-btl.css', __FILE__ ),
					[],
					self::VERSION . '18012023'
				);

			}

		} else { 

			wp_enqueue_style( 'ooohboi-steroids-styles-dark', self::ooohboi_light_stylesheet_url(), [ 'elementor-editor' ], self::VERSION . '04072022a' );

			/* better templates library */
			if( isset( $tmp_ob_settings_options[ 'ob_use_btl' ] ) && $tmp_ob_settings_options[ 'ob_use_btl' ] && 'yes' === $tmp_ob_settings_options[ 'ob_use_btl' ] ) {

				wp_enqueue_style(
					'btl-editor-dark', 
					plugins_url( 'assets/css/editor-btl.css', __FILE__ ),
					[],
					self::VERSION . '18012023'
				);

			}

		}

	}
	public static function ooohboi_dark_stylesheet_url() {
		return plugins_url( 'assets/css/editor-dark.css', __FILE__ );
	}
	public static function ooohboi_light_stylesheet_url() {
		return plugins_url( 'assets/css/editor.css', __FILE__ );
	}

	/*
		* Init styles
		*
		* Include css files and register them
		*
		* @since 1.0.0				
		*
		* @access public
	*/
	public function ooohboi_register_styles() {

		// locomotive scroll
		if( 1 === self::$sfe_lib_locomotive ) { 
			wp_register_style( 'locomotive-scroll-css', plugins_url( 'lib/locomotive_scroll/locomotive-scroll.min.css', __FILE__ ), [ 'ooohboi-steroids-styles' ], self::VERSION );
		}
		// -----------------------------

		wp_register_style( 'ooohboi-steroids-styles', plugins_url( 'assets/css/main.css', __FILE__ ), NULL, self::VERSION . '26082023' );

	}

	/*
		* Init Scripts
		*
		* Include js files and register them
		*
		* @since 1.0.0				
		*
		* @access public
	*/
	public function ooohboi_check_file_avail( $the_file ) {
		return (bool)@fopen( $the_file, 'r' );
	}
	public function ooohboi_register_scripts_front() {

		$ele_is_preview = \Elementor\Plugin::$instance->preview->is_preview_mode(); 
		
		wp_register_script( 'ooohboi-steroids', plugins_url( 'assets/js/ooohboi-steroids.js', __FILE__ ), [ 'jquery' ], self::VERSION . '07072022', true );

		// locomotive scroll
		if( 1 === self::$sfe_lib_locomotive ) {
			wp_register_script( 'locomotive-scroll-js', plugins_url( 'lib/locomotive_scroll/locomotive-scroll.min.js', __FILE__ ), [], self::VERSION . '02052022', true ); 
			wp_register_script( 'locomotive-scroll-ctrl', plugins_url( 'assets/js/ooohboi-libs-locomotion.js', __FILE__ ), [ 'locomotive-scroll-js' ], self::VERSION . '02052022', true ); 
		}
		// gsap
		if( 1 === self::$sfe_lib_gsap ) {
			wp_register_script( 'gsap-js', '//cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js', [], self::VERSION, true ); 
		}
		// scroll trigger
		if( 1 === self::$sfe_lib_scroll_trigger ) {
			wp_register_script( 'scroll-trigger-js', '//cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/ScrollTrigger.min.js', [], self::VERSION, true ); 
		}
		// scroll to
		if( 1 === self::$sfe_lib_scroll_to ) {
			wp_register_script( 'scroll-to-js', '//cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/ScrollToPlugin.min.js', [], self::VERSION, true );
		}
		// scroll motion path
		if( 1 === self::$sfe_lib_motion_path ) {
			wp_register_script( 'motion-path-js', '//cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/MotionPathPlugin.min.js', [], self::VERSION, true ); 
		}
		// barba
		if( 1 === self::$sfe_lib_barba && ! $ele_is_preview ) {
			wp_register_script( 'barba-js', plugins_url( 'lib/barba/barba.min.js', __FILE__ ), [], self::VERSION, true ); 
		}
		// anime
		if( 1 === self::$sfe_lib_anime && ! $ele_is_preview ) {
			wp_register_script( 'anime-js', plugins_url( 'lib/anime/anime.min.js', __FILE__ ), [], self::VERSION, true ); 
		}
		// three
		if( 1 === self::$sfe_lib_three && ! $ele_is_preview ) {
			wp_register_script( 'three-js', '//cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js', [], self::VERSION, true ); 
		}
		// -----------------------------

	}

	/**
	 *
	 * Include extensions
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function ooohboi_take_steroids() {

		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-overlay-underlay.php'; // OoohBoi Overlay Underlay
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-harakiri.php'; // OoohBoi Harakiri
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-paginini.php'; // OoohBoi Paginini
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-glider.php'; // OoohBoi Glider Slider
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-overlaiz.php'; // OoohBoi Overlaiz
		if( 'active' !== get_option( 'elementor_experiment-container' ) ) {
			include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-breaking-bad.php'; // OoohBoi Breaking Bad
			include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-photogiraffe.php'; // OoohBoi PhotoGiraffe
			include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-teleporter.php'; // OoohBoi Teleporter
			include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-perspektive.php'; // OoohBoi Perspektive 
		}
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-searchcop.php'; // OoohBoi Search Cop
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-videomasq.php'; // OoohBoi Video Masq
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-butter-button.php'; // OoohBoi Butter Button 
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-shadough.php'; // OoohBoi Shadough
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-photomorph.php'; // OoohBoi PhotoMorph
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-commentz.php'; // OoohBoi Commentz
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-spacerat.php'; // OoohBoi SpaceRat
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-imbox.php'; // OoohBoi Imbox 
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-icobox.php'; // OoohBoi Icobox
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-hover-animator.php'; // OoohBoi Hover Animator
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-kontrolz.php'; // OoohBoi Kontrolz
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-widget-stalker.php'; // OoohBoi Widget Stalker 
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-pseudo.php'; // OoohBoi Pseudo
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-bullet.php'; // OoohBoi Bullet 
		// OoohBoi Container Extras ONLY IF ELEMENTOR >= ELEMENTOR_VERSION_CONTAINER
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-container-extras.php'; // OoohBoi Container Extras 
		/*include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-oh-animator.php'; // OoohBoi Animator*/
		/*include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-interactor.php'; // OoohBoi Interactor*/
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-counterz.php'; // OoohBoi Counterz
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-tabbr.php'; // OoohBoi Tabbr 
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-postman.php'; // OoohBoi Postman
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-better-templates-library.php'; // OoohBoi Better Templates Library
		// ----------
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-locomotion.php'; // OoohBoi Locomotion 
		// kit add-ons
		include_once plugin_dir_path( __FILE__ ) . 'controls/ooohboi-typo.php'; // OoohBoi Typo

	}

}

OoohBoi_Steroids::instance();