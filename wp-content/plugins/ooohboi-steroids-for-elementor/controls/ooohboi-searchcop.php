<?php
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi SearchCop
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.3.3
 */
class OoohBoi_SearchCop {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/search-form/search_content/before_section_end',  [ __CLASS__, 'ooohboi_searchcop_get_controls' ], 10, 2 );

        /* should enqueue? */
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/searchcop-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_searchcop_use_it' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }
    
	public static function ooohboi_searchcop_get_controls( $element, $args ) {

		$element->add_control(
			'_ob_searchcop_plugin_title',
			[
				'label' => 'S E A R C H - C O P', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );

		// --------------------------------------------------------------------------------------------- CONTROL: Use Butter Button
		$element->add_control(
			'_ob_searchcop_use_it',
			[
                'label' => __( 'Enable Search Cop?', 'ooohboi-steroids' ), 
				'separator' => 'after', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

        // ------------------------------------------------------------------------- CONTROL: Yes 4 Search Cop !
		$element->add_control(
			'_ob_searchcop_srch_options',
			[
				'label' => __( 'Search Target', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'all', 
                'frontend_available' => true,
				'options' => [
					'post' => __( 'Search Posts', 'ooohboi-steroids' ),
					'page' => __( 'Search Pages', 'ooohboi-steroids' ), 
					'product' => __( 'Search Products', 'ooohboi-steroids' ), 
					'all' => __( 'Search All', 'ooohboi-steroids' ), 
				],
			]
		);

	}

}