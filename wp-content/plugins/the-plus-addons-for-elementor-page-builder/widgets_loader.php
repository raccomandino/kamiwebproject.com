<?php
namespace TheplusAddons;
use Elementor\Utils;
use Elementor\Core\Settings\Manager as SettingsManager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class L_Theplus_Element_Load {
	/**
		* Core singleton class
		* @var self - pattern realization
	*/
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $_modules_manager;
	public $TPAG_Slug = "the-plus-addons-for-block-editor/the-plus-addons-for-block-editor.php";
	public $TPAG_DocUrl = "https://theplusblocks.com/?utm_source=wpbackend&utm_medium=adminpanel&utm_campaign=notice";

	/**
	 * @deprecated
	 * @return string
	 */
	public function get_version() {
		return L_THEPLUS_VERSION;
	}
	
	/**
	* Cloning disabled
	*/
	public function __clone() {
	}
	
	/**
	* Serialization disabled
	*/
	public function __sleep() {
	}
	
	/**
	* De-serialization disabled
	*/
	public function __wakeup() {
	}
	
	/**
	* @return \Elementor\Theplus_Element_Loader
	*/
	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}
	
	/**
	* @return Theplus_Element_Loader
	*/
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * we loaded module manager + admin php from here
	 * @return [type] [description]
	 */
	private function includes() {
		
		require_once L_THEPLUS_INCLUDES_URL .'tp-lazy-function.php';
		
		if ( ! class_exists( 'CMB2' ) ){
			require_once L_THEPLUS_INCLUDES_URL.'plus-options/metabox/init.php';
		}
		$option_name='default_plus_options';
		$value='1';
		if ( is_admin() && get_option( $option_name ) !== false ) {
		} else if( is_admin() ){
			$default_load=get_option( 'theplus_options' );
			if ( $default_load !== false && $default_load!='') {
				$deprecated = null;
				$autoload = 'no';
				add_option( $option_name,$value, $deprecated, $autoload );
			}else{
				$theplus_options=get_option( 'theplus_options' );
				$theplus_options['check_elements']= array('tp_accordion','tp_adv_text_block','tp_blockquote','tp_blog_listout','tp_button','tp_contact_form_7','tp_countdown','tp_clients_listout','tp_gallery_listout','tp_flip_box','tp_heading_animation','tp_header_extras','tp_heading_title','tp_info_box','tp_navigation_menu_lite','tp_page_scroll','tp_progress_bar','tp_number_counter','tp_pricing_table','tp_scroll_navigation','tp_social_icon','tp_tabs_tours','tp_team_member_listout','tp_testimonial_listout','tp_video_player');
				
				$deprecated = null;
				$autoload = 'no';
				add_option( 'theplus_options',$theplus_options, $deprecated, $autoload );
				add_option( $option_name,$value, $deprecated, $autoload );
			}
		}
		
		require_once L_THEPLUS_INCLUDES_URL .'plus_addon.php';
		
		
		if ( file_exists(L_THEPLUS_INCLUDES_URL . 'plus-options/metabox/init.php' ) ) {
			require_once L_THEPLUS_INCLUDES_URL.'plus-options/includes.php';
		}
		
		//@since 5.0.6
		require L_THEPLUS_PATH.'modules/theplus-core-cp.php';
		
		require L_THEPLUS_INCLUDES_URL.'theplus_options.php';		
		
		if(!defined('THEPLUS_VERSION')){
			require L_THEPLUS_PATH.'modules/theplus-integration.php';
		}
		
		require L_THEPLUS_PATH.'modules/query-control/module.php';
		
		require_once L_THEPLUS_PATH .'modules/helper-function.php';
		
		
	}
	
	/**
	* Widget Include required files
	*
	*/
	public function include_widgets(){			
		require_once L_THEPLUS_PATH.'modules/theplus-include-widgets.php';		
	}
	
	public function theplus_editor_styles() {
		wp_enqueue_style( 'theplus-ele-admin', L_THEPLUS_ASSETS_URL .'css/admin/theplus-ele-admin.css', array(),L_THEPLUS_VERSION,false );
		$ui_theme = SettingsManager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );
		if(!empty($ui_theme) && $ui_theme=='dark'){
			wp_enqueue_style( 'theplus-ele-admin-dark', L_THEPLUS_ASSETS_URL .'css/admin/theplus-ele-admin-dark.css', array(),L_THEPLUS_VERSION,false );
		}
	}
	public function theplus_elementor_admin_css() {  
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_style( 'theplus-ele-admin', L_THEPLUS_ASSETS_URL .'css/admin/theplus-ele-admin.css', array(),L_THEPLUS_VERSION,false );
		wp_enqueue_script( 'theplus-admin-js', L_THEPLUS_ASSETS_URL .'js/admin/theplus-admin.js', array(),L_THEPLUS_VERSION,false );
		
		$js_inline = 'var theplus_ajax_url = "'.admin_url("admin-ajax.php").'";
		var theplus_ajax_post_url = "'.admin_url("admin-post.php").'";
        var theplus_nonce = "'.wp_create_nonce("theplus-addons").'";';
		echo wp_print_inline_script_tag($js_inline);
	}
	function theplus_mime_types($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
		return $mimes;
	}
	
	/*
	 * Get all pages
	 * @since 5.0.0
	 */
	public function tp_get_elementor_pages(){
		
		if ( ! wp_verify_nonce( $_REQUEST['security'], 'theplus-addons' ) ) {
			wp_send_json_error( esc_html__('Invalid security', 'tpebl') );
		}
		
		if ( ! current_user_can('install_plugins') ) {
			wp_send_json_error( esc_html__('Invalid User', 'tpebl') );
		}
		
		global $wpdb;

		$post_ids = $wpdb->get_col(
			'SELECT `post_id` FROM `' . $wpdb->postmeta . '`
					WHERE `meta_key` = \'_elementor_version\';'
		);
		$tp_widgets_list ='';
		$page = !empty($_GET['page']) ? wp_unslash($_GET['page']) : '';
		if($page == "tpewidpage"){
			$theplus_options=get_option( 'theplus_options' );
			if(!empty($theplus_options) && isset($theplus_options['check_elements'])){
				$tp_widgets_list = $theplus_options['check_elements'];
			}
		}else if($page == "elementorwidgetpage"){
			// $theplus_options=get_option( 'theplus_elementor_widget' );
			// if(!empty($theplus_options) && isset($theplus_options['elementor_check_elements'])){
			// 	$tp_widgets_list = $theplus_options['elementor_check_elements'];
			// }
		}
		
		if ( empty( $post_ids ) ) {
			wp_send_json_error(esc_html('Empty post list.'));
		}

		$scan_post_ids = [];
		$countWidgets = [];
		foreach ( $post_ids as $post_id ) {
			if( 'revision' === get_post_type($post_id) ){
				continue;
			}
			$get_widgets = $this->tp_check_elements_status_scan( $post_id, $tp_widgets_list );			
			$scan_post_ids[$post_id] = $get_widgets;
			if( !empty( $get_widgets ) ){				
				foreach($get_widgets as $value ){					
					if(!empty($value) && in_array( $value, $tp_widgets_list ) ){						
						$countWidgets[$value] = (isset($countWidgets[$value]) ? absint($countWidgets[$value]) : 0) + 1;
					}
				}
			}
		}
		$output =[];
		$val1 = count($tp_widgets_list);
		$val2 = count($countWidgets);
		$val3 = $val1 - $val2;
		$output['message'] = "* ".$val3." Unused Widgets Found!";
		$output['widgets'] = $countWidgets;
		wp_send_json_success( $output );
	}
	
	public function tp_check_elements_status_scan( $post_id ='', $tp_widgets_list='' ){
		if ( ! wp_verify_nonce( $_REQUEST['security'], 'theplus-addons' ) ) {
			wp_send_json_error( esc_html__('Invalid security', 'tpebl') );
		}
		
		if ( ! current_user_can('install_plugins') ) {
			wp_send_json_error( esc_html__('Invalid User', 'tpebl') );
		}
		if( !empty($post_id) ){
			$meta_data = \Elementor\Plugin::$instance->documents->get( $post_id );
			if (is_object($meta_data)) {
				$meta_data = $meta_data->get_elements_data();
			}
			
			if ( empty( $meta_data ) ) {
				return '';
			}
			
			$to_return = [];

			\Elementor\Plugin::$instance->db->iterate_data( $meta_data, function( $element ) use ($tp_widgets_list, &$to_return) {
				$page = !empty($_GET['page']) ? wp_unslash($_GET['page']) : '';
				if($page == "tpewidpage"){
					if ( !empty( $element['widgetType'] ) && array_key_exists( str_replace('-', '_', $element['widgetType']), array_flip($tp_widgets_list) ) ) {				
						$to_return[] = str_replace('-','_',$element['widgetType']);
					}
				}else if($page == "elementorwidgetpage"){
					// if ( !empty( $element['widgetType'] ) && array_key_exists($element['widgetType'], array_flip($tp_widgets_list) ) ) {				
					// 	$to_return[] = $element['widgetType'];
					// }
				}
				
			} );
		}		
		return array_values($to_return);
	}
	
	function tp_disable_elements_status_scan(){
		
		if ( ! wp_verify_nonce( $_REQUEST['security'], 'theplus-addons' ) ) {
			wp_send_json_error( esc_html__('Invalid security', 'tpebl') );
		}
		if ( ! current_user_can('install_plugins') ) {
			wp_send_json_error( esc_html__('Invalid User', 'tpebl') );
		}
		
		$message = '';
		if(isset($_GET['SacanedDataPass']) && !empty($_GET['SacanedDataPass'])){
			$tp_widgets_list ='';
			$page = !empty($_GET['page']) ? wp_unslash($_GET['page']) : '';
			if($page == "tpewidpage"){
				$theplus_options=get_option( 'theplus_options' );			
				if(!empty($theplus_options) && isset($theplus_options['check_elements'])){
					$tp_widgets_list = $theplus_options['check_elements'];			
					$val1 = count($tp_widgets_list);
					$val2 = count($_GET['SacanedDataPass']);
					$val3 = $val1 - $val2;
					
					$theplus_options['check_elements'] = array_keys($_GET['SacanedDataPass']);
					update_option( 'theplus_options',$theplus_options, null, 'no' );
					l_theplus_library()->remove_backend_dir_files();
					$message = "We have scanned your site and disabled ".$val3." unused The Plus Addons widgets.";
				}
			}else if($page == "elementorwidgetpage"){
				// $theplus_options=get_option( 'theplus_elementor_widget' );			
				// if(!empty($theplus_options) && isset($theplus_options['elementor_check_elements'])){
				// 	$tp_widgets_list = $theplus_options['elementor_check_elements'];
				// 	$val1 = count($tp_widgets_list);
				// 	$val2 = count($_GET['SacanedDataPass']);
				// 	$val3 = $val1 - $val2;
					
				// 	$theplus_options['elementor_check_elements'] = array_keys($_GET['SacanedDataPass']);
				// 	update_option( 'theplus_elementor_widget',$theplus_options, null, 'no' );
				// 	$message = "We have scanned your site and disabled ".$val3." unused The Plus Addons widgets.";
				// }
			}			
		}
		wp_send_json_success( $message );
		exit;
	}
	
	/**
	 * Print style.
	 *
	 * Adds custom CSS to the HEAD html tag. The CSS that emphasise the maintenance
	 * mode with red colors.
	 *
	 * Fired by `admin_head` and `wp_head` filters.
	 *
	 * @since 2.1.0
	 */
	public function print_style() {
		?>
			<style>*:not(.elementor-editor-active) .plus-conditions--hidden {display: none;}</style>
		<?php
	}

	public function add_elementor_category() {
			
		$elementor = \Elementor\Plugin::$instance;
		
		//Add elementor category
		$elementor->elements_manager->add_category('plus-essential', 
			[
				'title' => esc_html__( 'PlusEssential', 'tpebl' ),
				'icon' => 'fa fa-plug',
			],
			1
		);
		$elementor->elements_manager->add_category('plus-listing', 
			[
				'title' => esc_html__( 'PlusListing', 'tpebl' ),
				'icon' => 'fa fa-plug',
			],
			1
		);
		$elementor->elements_manager->add_category('plus-creatives', 
			[
				'title' => esc_html__( 'PlusCreatives', 'tpebl' ),
				'icon' => 'fa fa-plug',
			],
			1
		);
		$elementor->elements_manager->add_category('plus-tabbed', 
			[
				'title' => esc_html__( 'PlusTabbed', 'tpebl' ),
				'icon' => 'fa fa-plug',
			],
			1
		);
		$elementor->elements_manager->add_category('plus-adapted', 
			[
				'title' => esc_html__( 'PlusAdapted', 'tpebl' ),
				'icon' => 'fa fa-plug',
			],
			1
		);
		$elementor->elements_manager->add_category('plus-header', 
			[
				'title' => esc_html__( 'PlusHeader', 'tpebl' ),
				'icon' => 'fa fa-plug',
			],
			1
		);
		$elementor->elements_manager->add_category('plus-builder', 
			[
				'title' => esc_html__( 'PlusBuilder', 'tpebl' ),
				'icon' => 'fa fa-plug',
			],
			1
		);
		$elementor->elements_manager->add_category('plus-woo-builder', 
			[
				'title' => esc_html__( 'PlusWooBuilder', 'tpebl' ),
				'icon' => 'fa fa-plug',
			],
			1
		);
		$elementor->elements_manager->add_category('plus-search-filter', 
			[
				'title' => esc_html__( 'PlusSearchFilters', 'tpebl' ),
				'icon' => 'fa fa-plug',
			],
			1
		);
		$elementor->elements_manager->add_category('plus-depreciated', 
			[
				'title' => esc_html__( 'PlusDepreciated', 'tpebl' ),
				'icon' => 'fa fa-plug',
			],
			1
		);
	}

	// public function tp_advanced_shadow_style() {
	// 	wp_enqueue_script( 'tp-advanced-shadows', L_THEPLUS_ASSETS_URL .'js/admin/tp-advanced-shadow-layout.js', array('jquery'),L_THEPLUS_VERSION, true );
	// }

	/**
	 * ThePlus_Load constructor.
	 */
	private function __construct() {
		
		// Register class automatically
		$this->includes();

		// Finally hooked up all things
		$this->hooks();
		
		if( !defined('THEPLUS_VERSION') ){
			L_Theplus_Elements_Integration()->init();
		}
		
		// $plus_extras=l_theplus_get_option('general','extras_elements');
		
		// if((isset($plus_extras) && empty($plus_extras) && empty($theplus_options)) || (!empty($plus_extras) && in_array('plus_adv_shadow',$plus_extras))){
		// 	//add_action( 'wp_enqueue_scripts', [ $this, 'tp_advanced_shadow_style' ] );
		// }

		//@since 5.0.6
		theplus_core_cp_lite()->init();

		$this->include_widgets();

		l_theplus_widgets_include();
		
	}

	/**
	*
	* @since 5.1.18
	*/
	private function hooks() {
		$theplus_options = get_option('theplus_options');
		$plus_extras = l_theplus_get_option('general','extras_elements');
		
		if((isset($plus_extras) && empty($plus_extras) && empty($theplus_options)) || (!empty($plus_extras) && in_array('plus_display_rules',$plus_extras))){
			add_action( 'wp_head', [ $this, 'print_style' ] );
		}

		add_action( 'elementor/init', [ $this, 'add_elementor_category' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'theplus_editor_styles' ] );
		
		add_filter( 'upload_mimes', array( $this,'theplus_mime_types') );

		// Include some backend files
		add_action( 'admin_enqueue_scripts', [ $this,'theplus_elementor_admin_css'] );

		/**Add Notice Black Friday*/
		
		add_action( 'admin_notices', array( $this, 'tp_bf_sale_notice' ));
		add_action( 'wp_ajax_bf_dismiss_notice',  array( $this, 'tp_bf_dismiss_notice' ) );

		if ( current_user_can( 'install_plugins' ) ) {
			/**Install ThePlus Blocks Notice*/
			add_action( 'admin_notices', array( $this, 'theplus_blocks_notice_install_plugin' ) );
			/**TPAG Close Popup and Notice*/
			add_action( 'wp_ajax_theplus_blocks_dismiss_notice', array( $this, 'theplus_blocks_dismiss_notice' ) );
		}

		if( is_admin() && current_user_can("manage_options") ){
			add_action( 'wp_ajax_tp_get_elementor_pages', [$this, 'tp_get_elementor_pages'] );
			add_action( 'wp_ajax_tp_check_elements_status_scan', [$this, 'tp_check_elements_status_scan'] );
			add_action( 'wp_ajax_tp_disable_elements_status_scan', [$this, 'tp_disable_elements_status_scan'] );

			/**Plugin active option*/
			add_filter( 'plugin_action_links_' . L_THEPLUS_PBNAME, array( $this, 'tp_settings_pro_link' ) );

			/**Plugin by links*/
			add_filter( 'plugin_row_meta', array( $this, 'tp_extra_links_plugin_row_meta' ), 10, 2 );
		}
	}

	/**
	 * Plugin Active Theplus Addons for Block Editor Notice Installing Notice show
	 *
	 * @since 5.3.0
	 */
	function tp_bf_sale_notice() {
		
		if ( get_user_meta( get_current_user_id(), 'tp_dismissed_notice_blackfy', true ) ) {
		    return;
		}

		$ouput = '';
		$ouput .= '<div class="notice notice-info is-dismissible tpae-bf-sale" style="display:grid;grid-template-columns: 100px auto;padding-top: 25px; padding-bottom: 22px;border-left-color: #8072fc;margin-left: 0;">';
			$ouput .= '<div style="display: flex;justify-content: center;flex-direction: row;margin: 0;padding: 0;">';
				$ouput .= '<img alt="tp-notice-blackfdy-image" src="'.esc_url( L_THEPLUS_ASSETS_URL.'/images/theplus-gif.gif' ).'" width="74" height="74" style="grid-row: 1 / 4;align-self: center;justify-self: center;height: 100%;width: 100%;">';
			$ouput .= '</div>';
			
			$ouput .= '<div style="display: flex;flex-direction: column;margin-left: 15px;justify-content: space-between;">';
				$ouput .= '<h2 style="margin:0;">'.esc_html__('Best Time to Upgrade The Plus Addons for Elementor PRO!🎁','tpebl').'</h2>';
					$message = sprintf(
						__( 'Black Friday 2023 Sale: %1$s Get upto 40%% OFF %2$s on The Plus Addons for Elementor. Offer Valid Upto Nov 30 - %1$s 60 Days Money Back Guarantee. %2$s', 'tpebl' ),
						'<strong>',
						'</strong>'
					);

					$ouput .= sprintf( '<p style="margin:0 0 2px;">%1$s</p>', $message );

					$ouput .= '<p style="margin:0;">';
						$ouput .= '<a class="button button-primary" href="'.esc_url('"https://theplusaddons.com/pricing/?utm_source=wpbackend&utm_medium=AdminPage&utm_campaign=BlackFriday').'" target="_blank" rel="noopener noreferrer" style="margin-right:10px;">'.esc_html__('Buy Now','tpebl').'</a>';
						$ouput .= '<a class="button button-dismiss" href="'.esc_url('https://theplusaddons.com/free-vs-pro/?utm_source=wpbackend&utm_medium=AdminPage&utm_campaign=BlackFriday').'" target="_blank" rel="noopener noreferrer">'.esc_html__('Whats there in PRO?','tpebl').'</a>';
					$ouput .= '</p>';
				$ouput .= '</div>';
			$ouput .= '</div>';


		$ouput .= '<script>;
			jQuery(document).ready(function ($) {
				$(".tpae-bf-sale.is-dismissible").on("click", ".notice-dismiss", function () {
					$.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							action: "bf_dismiss_notice",
						},
					});
				});
			});
		</script>';

		echo $ouput;
	}

	/**
	 * Balck Friday Notice Close button ajax call.
	 *
	 * @since 5.3.0
	 * 
	 */
	function tp_bf_dismiss_notice() {

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'content' => __( 'Insufficient permissions.', 'tpebl' ) ) );
		}

		update_user_meta( get_current_user_id(), 'tp_dismissed_notice_blackfy', 1 );

		wp_die();
	}
	
	 /**
	 * Plugin Active Theplus Addons for Block Editor Notice Installing Notice show
	 *
	 * @since 5.2.3
	 */
	public function theplus_blocks_notice_install_plugin() {
		$file_path = $this->TPAG_Slug;
		$installed_plugins = get_plugins();
		$screen = get_current_screen();
		$nonce = wp_create_nonce( 'theplus-addons-tpag-blocks' );
		$PT_exclude = !empty( $screen->post_type ) && in_array( $screen->post_type, [ 'elementor_library', 'product' ] );
		$ParentBase = !empty( $screen->parent_base ) && in_array( $screen->parent_base, [ 'edit', 'plugins' ] );

		if ( !$ParentBase || $PT_exclude ) {
			return;
		}

		$notice_dismissed = get_user_meta( get_current_user_id(), 'theplus_tpag_blocks_dismissed_notice', true );
		if ( !empty($notice_dismissed) ) {
			return; 
		}

		if ( is_plugin_active( $file_path ) || isset( $installed_plugins[ $file_path ] ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=the-plus-addons-for-block-editor' ), 'install-plugin_the-plus-addons-for-block-editor' );

		$admin_notice = '<h3>' . esc_html__( "It's Live 🎉 The Plus Blocks for Gutenberg is Ready to Use!", 'tpebl' ) . '</h3>';
		$admin_notice .= '<p>' . esc_html__( 'Do you use Gutenberg Block Editor to create websites or post blogs?', 'tpebl' ) . '</p>';
		$admin_notice .= '<p>' . esc_html__( 'Then check our Gutenberg Block version, where we provide you over 80+ WordPress Blocks (40 Free Blocks) to help you create fast websites without compromising on design.', 'tpebl' ) . '</p>';
		$admin_notice .= '<p>' . sprintf( '<a href="%s" class="tp-block-notice-checkdemos" target="_blank" rel="noopener noreferrer">%s</a>', $this->TPAG_DocUrl, esc_html__( 'Check Live demos', 'tpebl' ) ) . '</p>';
		$admin_notice .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install The Plus Blocks', 'tpebl' ) ) . '</p>';
		$admin_notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__( 'Dismiss this notice', 'tpebl' ) . '</span></button>';

		echo '<div class="notice notice-error is-dismissible theplus-tpag-blocks-notice">'.wp_kses_post( $admin_notice ).'</div>';

		?>
		<script>
			jQuery('.theplus-tpag-blocks-notice .notice-dismiss').on('click', function() {
				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'theplus_blocks_dismiss_notice',
						security: "<?php echo esc_html( $nonce ); ?>",
						type: 'tpag_notice',
					},
					success: function(response) {
						jQuery('.theplus-tpag-blocks-notice').hide();
					}
				});
			});
		</script>
		<?php
	
	}

	/**
	 * It's is use for Save key in database
	 * TAPG Notice and TAG Popup Dismisse 
	 * 
	 * @since 5.2.3
	 */
	public function theplus_blocks_dismiss_notice() {
		$GetSecurity = !empty($_POST['security']) ? $_POST['security'] : '';

		if( !isset($GetSecurity) || empty($GetSecurity) || ! wp_verify_nonce( $GetSecurity, 'theplus-addons-tpag-blocks' ) ) {	
			die ('Security checked!');
		}

		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
		}

		$GetType = !empty($_POST['type']) ? $_POST['type'] : '';

		if( $GetType == "tpag_notice" ){
			update_user_meta( get_current_user_id(), 'theplus_tpag_blocks_dismissed_notice', true );
		}else if( $GetType == "tpag_popup" ){
			// add_option('theplus_tpag_blocks_dismissed_popup', true);
		}

		wp_send_json_success();
	}   	

	/**
	 * Plugin Active Settings, Need Help link Show 
	 *
	 * @since 5.1.18
	 */
	public function tp_settings_pro_link( $links ){

		/**Settings link.*/
		$setting_link = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=theplus_options') ), __( 'Settings', 'tpebl' ) );
		$links[] = $setting_link;

		/**Need Help.*/
		$need_help = sprintf( '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', esc_url("https://theplusaddons.com/free-vs-pro/?utm_source=wpbackend&utm_medium=pluginpage&utm_campaign=links"), __( 'Need Help?', 'tpebl' ) );
		$links = (array) $links;
		$links[] = $need_help;

		/**Upgrade PRO link.*/
		if ( !defined('THEPLUS_VERSION') ) {
			$pro_link = sprintf( '<a href="%s" target="_blank" style="color: #cc0000;font-weight: 700;" rel="noopener noreferrer">%s</a>', esc_url('https://theplusaddons.com/pricing/'), __( 'Upgrade PRO', 'tpebl' ) );
			$links = (array) $links;
			$links[] = $pro_link;
		}
		
		return $links;
	}
	
	/**
	 * Plugin Active show Document links 
	 *
	 * @since 5.1.18
	 */
	public function tp_extra_links_plugin_row_meta( $plugin_meta = [], $plugin_file =''){

		if ( strpos( $plugin_file, L_THEPLUS_PBNAME ) !== false ) {
			$new_links = array(
				'official-site' => '<a href="'.esc_url('https://theplusaddons.com/?utm_source=wpbackend&utm_medium=pluginpage&utm_campaign=links').'" target="_blank" rel="noopener noreferrer">'.esc_html__( 'Visit Plugin site', 'tpebl' ).'</a>',
				'docs' => '<a href="'.esc_url('https://theplusaddons.com/docs?utm_source=wpbackend&utm_medium=pluginpage&utm_campaign=links').'" target="_blank" rel="noopener noreferrer" style="color:green;">'.esc_html__( 'Docs', 'tpebl' ).'</a>',
				'video-tutorials' => '<a href="'.esc_url('https://www.youtube.com/c/POSIMYTHInnovations/?sub_confirmation=1').'" target="_blank" rel="noopener noreferrer">'.esc_html__( 'Video Tutorials', 'tpebl' ).'</a>',
				'join-community' => '<a href="'.esc_url('https://www.facebook.com/groups/1331664136965680').'" target="_blank" rel="noopener noreferrer">'.esc_html__( 'Join Community', 'tpebl' ).'</a>',
				'whats-new' => '<a href="'.esc_url('https://roadmap.theplusaddons.com/updates?filter=Free').'" target="_blank" rel="noopener noreferrer" style="color: orange;">'.esc_html__( 'What\'s New?', 'tpebl' ).'</a>',
				'req-feature' => '<a href="'.esc_url('https://roadmap.theplusaddons.com/boards/feature-request').'" target="_blank" rel="noopener noreferrer">'.esc_html__( 'Request Feature', 'tpebl' ).'</a>',
				'rate-plugin-star' => '<a href="'.esc_url('https://wordpress.org/support/plugin/the-plus-addons-for-elementor-page-builder/reviews/?filter=5').'" target="_blank" rel="noopener noreferrer">'.esc_html__( 'Share Review', 'tpebl' ).'</a>'
			);

			$plugin_meta = array_merge( $plugin_meta, $new_links );
		}
			
		return $plugin_meta;
	}

}

function l_theplus_addon_load(){
	return L_Theplus_Element_Load::instance();
}

// Get l_theplus_addon_load Running
l_theplus_addon_load();