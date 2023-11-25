<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Icon_Element_Icons_Integration' ) ) {

	class Icon_Element_Icons_Integration {

		private static $instance = null;

		public function __construct() { 
			add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_material_icons_tabs' ) );
		}

		public function add_material_icons_tabs( $tabs = array() ) {

			if ( get_option('icon-elementie-captain') ){
				$tabs['captain'] = array(
					'name'          => 'captain',
					'label'         => esc_html__( 'Captain', 'icon-element' ),
					'labelIcon'     => 'xlcaptain-100',
					'prefix'        => 'xlcaptain-',
					'displayPrefix' => 'xlcpt',
					'url'           => ICON_ELEM_URL . 'assets/captain/captain.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/captain/fonts/captain.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-elementor') ){
				$tabs['elementor'] = array(
					'name'          => 'elementor',
					'label'         => esc_html__( 'Elementor', 'icon-element' ),
					'labelIcon'     => 'eicon-elementor-circle',
					'prefix'        => 'eicon-',
					'displayPrefix' => 'eicon',
					'url'           => ICON_ELEM_URL . 'assets/elementor/elementor.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/elementor/fonts/elementor.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-feather') ){
				$tabs['feather'] = array(
					'name'          => 'feather',
					'label'         => esc_html__( 'Feather', 'icon-element' ),
					'labelIcon'     => 'feather feather-feather',
					'prefix'        => 'feather-',
					'displayPrefix' => 'feather',
					'url'           => ICON_ELEM_URL . 'assets/feather/feather.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/feather/fonts/feather.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-elusive') ){
				$tabs['elusive'] = array(
					'name'          => 'elusive',
					'label'         => esc_html__( 'Elusive', 'icon-element' ),
					'labelIcon'     => 'el-icon-wrench',
					'prefix'        => 'el-icon-',
					'displayPrefix' => 'elusive',
					'url'           => ICON_ELEM_URL . 'assets/elusive/elusive.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/elusive/fonts/elusive.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-obicon') ){
				$tabs['obicon'] = array(
					'name'          => 'obicon',
					'label'         => esc_html__( 'Obicon', 'icon-element' ),
					'labelIcon'     => 'obicon-socket-square',
					'prefix'        => 'obicon-',
					'displayPrefix' => 'obicon',
					'url'           => ICON_ELEM_URL . 'assets/obicon/obicon.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/obicon/fonts/obicon.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-webicon') ){
				$tabs['webicon'] = array(
					'name'          => 'webicon',
					'label'         => esc_html__( 'Web icon', 'icon-element' ),
					'labelIcon'     => 'wb-book',
					'prefix'        => 'wb-',
					'displayPrefix' => 'wb',
					'url'           => ICON_ELEM_URL . 'assets/webicons/webicons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/webicons/fonts/webicons.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-vscode') ){
				$tabs['vscode'] = array(
					'name'          => 'vscode',
					'label'         => esc_html__( 'Vscode', 'icon-element' ),
					'labelIcon'     => 'vscode-debug-rerun',
					'prefix'        => 'vscode-',
					'displayPrefix' => 'vscode',
					'url'           => ICON_ELEM_URL . 'assets/vscode/vscode.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/vscode/fonts/vscode.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-ionicons') ){

				$tabs['ionicons'] = array(
					'name'          => 'ionicons',
					'label'         => esc_html__( 'Ionicons', 'icon-element' ),
					'labelIcon'     => 'ion-ios-appstore',
					'prefix'        => 'ion-',
					'displayPrefix' => 'xlio',
					'url'           => ICON_ELEM_URL . 'assets/ionicons/css/ionicons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/ionicons/fonts/ionicons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-material-design') ){

				$tabs['material-design'] = array(
					'name'          => 'material-design',
					'label'         => esc_html__( 'Material Design Icons', 'icon-element' ),
					'labelIcon'     => 'fab fa-google',
					'prefix'        => 'md-',
					'displayPrefix' => 'material-icons',
					'url'           => ICON_ELEM_URL . 'assets/material-icons/css/material-icons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/material-icons/fonts/material-icons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-metrize') ){

				$tabs['metrize'] = array(
					'name'          => 'metrize',
					'label'         => esc_html__( 'Metrize', 'icon-element' ),
					'labelIcon'     => 'metriz-yen',
					'prefix'        => 'metriz-',
					'displayPrefix' => 'xlmetriz',
					'url'           => ICON_ELEM_URL . 'assets/metrize/metrize.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/metrize/fonts/metrize.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-simpline') ){

				$tabs['simpline'] = array(
					'name'          => 'simpline',
					'label'         => esc_html__( 'Simple Line', 'icon-element' ),
					'labelIcon'     => 'simpline-user',
					'prefix'        => 'simpline-',
					'displayPrefix' => 'xlsmpli',
					'url'           => ICON_ELEM_URL . 'assets/simple-line-icons/css/simple-line-icons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/simple-line-icons/fonts/simple-line-icons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-wppagebuilder') ){

				$tabs['wppagebuilder'] = array(
					'name'          => 'wppagebuilder',
					'label'         => esc_html__( 'Wp pagebuilder', 'icon-element' ),
					'labelIcon'     => 'wppb-font-balance',
					'prefix'        => 'wppb-font-',
					'displayPrefix' => 'xlwpf',
					'url'           => ICON_ELEM_URL . 'assets/wppagebuilder/wppagebuilder.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/wppagebuilder/fonts/wppagebuilder.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-wppagebuilder') ){

				$tabs['wppagebuilder'] = array(
					'name'          => 'wppagebuilder',
					'label'         => esc_html__( 'Wp pagebuilder', 'icon-element' ),
					'labelIcon'     => 'wppb-font-balance',
					'prefix'        => 'wppb-font-',
					'displayPrefix' => 'xlwpf',
					'url'           => ICON_ELEM_URL . 'assets/wppagebuilder/wppagebuilder.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/wppagebuilder/fonts/wppagebuilder.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-iconsaxbold') ){

				$tabs['iconsaxbold'] = array(
					'name'          => 'iconsaxbold',
					'label'         => esc_html__( 'Iconsax Bold', 'icon-element' ),
					'labelIcon'     => 'isaxbold-wind',
					'prefix'        => 'isaxbold-',
					'displayPrefix' => 'xlwpf',
					'url'           => ICON_ELEM_URL . 'assets/iconsax-bold/iconsax-bold.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/iconsax-bold/fonts/iconsax-bold.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-tutor') ){

				$tabs['tutor'] = array(
					'name'          => 'tutor',
					'label'         => esc_html__( 'Tutor', 'icon-element' ),
					'labelIcon'     => 'tutor-icon-ban',
					'prefix'        => 'tutor-icon-',
					'displayPrefix' => 'xlwpf',
					'url'           => ICON_ELEM_URL . 'assets/tutor/tutor.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/tutor/fonts/tutor.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-uniconsolid') ){

				$tabs['uniconsolid'] = array(
					'name'          => 'uniconsolid',
					'label'         => esc_html__( 'Unicon solid', 'icon-element' ),
					'labelIcon'     => 'unisolid-airplay',
					'prefix'        => 'unisolid-',
					'url'           => ICON_ELEM_URL . 'assets/uniconsolid/uniconsolid.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/uniconsolid/fonts/uniconsolid.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-happyicon') ){

				$tabs['happyicon'] = array(
					'name'          => 'happyicon',
					'label'         => esc_html__( 'Happy icon', 'icon-element' ),
					'labelIcon'     => 'hm-3d-rotate',
					'prefix'        => 'hm-',
					'url'           => ICON_ELEM_URL . 'assets/happyicon/happyicon.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/happyicon/fonts/happyicon.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-woocommerce') ){

				$tabs['woocommerce'] = array(
					'name'          => 'woocommerce',
					'label'         => esc_html__( 'Woocommerce', 'icon-element' ),
					'labelIcon'     => 'wcicon-woo',
					'prefix'        => 'wcicon-',
					'url'           => ICON_ELEM_URL . 'assets/woocommerce/woocommerce.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/woocommerce/fonts/woocommerce.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-detheme') ){

				$tabs['detheme'] = array(
					'name'          => 'detheme',
					'label'         => esc_html__( 'DeTheme', 'icon-element' ),
					'labelIcon'     => 'dticon-add-circle-outline',
					'prefix'        => 'dticon-',
					'url'           => ICON_ELEM_URL . 'assets/detheme/detheme.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/detheme/fonts/detheme.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-prestashop') ){

				$tabs['prestashop'] = array(
					'name'          => 'prestashop',
					'label'         => esc_html__( 'Prestashop', 'icon-element' ),
					'labelIcon'     => 'ps-icon-lego',
					'prefix'        => 'ps-icon-',
					'url'           => ICON_ELEM_URL . 'assets/prestashop/prestashop.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/prestashop/fonts/prestashop.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-uicons') ){

				$tabs['uicons'] = array(
					'name'          => 'uicons',
					'label'         => esc_html__( 'Uicons', 'icon-element' ),
		            'labelIcon' => 'fi-rr-0',
		            'prefix' => 'fi-rr-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/uicons/uicons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/uicons/fonts/uicons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-jquery-uicons') ){

				$tabs['jquery-ui-icon'] = array(
					'name'          => 'jquery-ui-icon',
					'label'         => esc_html__( 'Jquery UI Icons', 'icon-element' ),
		            'labelIcon' => 'jquery-ui-icon-addon',
		            'prefix' => 'jquery-ui-icon-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/ui-icon/ui-icon.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/ui-icon/fonts/ui-icon.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-fabric') ){
				
				$tabs['fabric'] = array(
					'name'          => 'fabric',
					'label'         => esc_html__( 'Fabric icon', 'icon-element' ),
		            'labelIcon' => 'ms-Icon--OfficeLogo',
		            'prefix' => 'ms-Icon--',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/ms-fabric/ms-fabric.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/ms-fabric/fonts/ms-fabric.json',
					'ver'           => '3.0.1',
				);
				
			}

			if ( get_option('icon-elementie-ixsiemens') ){
				
				$tabs['ixsiemens'] = array(
					'name'          => 'ixsiemens',
					'label'         => esc_html__( 'Siemens icon', 'icon-element' ),
		            'labelIcon' => 'ixsiemens-zoom-out',
		            'prefix' => 'ixsiemens-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/siemens/siemens.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/siemens/fonts/siemens.json',
					'ver'           => '3.0.1',
				);
				
			}

			if ( get_option('icon-elementie-xlslim') ){
				
				$tabs['xlslim'] = array(
					'name'          => 'xlslim',
					'label'         => esc_html__( 'Xl Slim', 'icon-element' ),
		            'labelIcon' => 'xlslim-action-redo',
		            'prefix' => 'xlslim-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/xlslim/xlslim.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/xlslim/fonts/xlslim.json',
					'ver'           => '3.0.1',
				);
				
			}

			return $tabs;
		}

		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}
