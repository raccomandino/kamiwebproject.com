<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Main OoohBoi Container Extras class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.9.1
 */
class OoohBoi_Container_Extras {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function init() {

		/* CONTAINER */
        add_action( 'elementor/element/container/section_layout_container/before_section_end',  [ __CLASS__, 'add_section' ], 10, 2 );
		add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_container_attributes' ] );
		
		/* should enqueue? */
        add_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/container-extras-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_use_container_extras' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }
	
    public static function add_container_attributes( Element_Base $element ) {
        // bail if any other element but container
        if ( $element->get_name() !== 'container' ) return;
        // bail if editor
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;
		// grab the settings
		$settings = $element->get_settings_for_display();

        if( isset( $settings[ '_ob_use_container_extras' ] ) && 'yes' === $settings[ '_ob_use_container_extras' ] ) { 
			$element->add_render_attribute( '_wrapper', [
                'class' => 'ob-is-container-extras',
            ] );
        }

    }
    
	public static function add_section( Element_Base $element ) {

        $element->add_control(
            '_ob_container_extras',
            [
                'label' => 'C O N T A I N E R &nbsp; E X R A S',
                'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
            ]
		);

        // ------------------------------------------------------------------------- CONTROL: Yes 4 extras
		$element->add_control(
			'_ob_use_container_extras',
			[
                'label' => __( 'Enable Container Extras?', 'ooohboi-steroids' ), 
                'description' => __( 'Get some more flexibility on Containers width', 'ooohboi-steroids' ), 
                'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
        );
        
		// --------------------------------------------------------------------------------------------- CONTROL width
		$element->add_responsive_control(
			'_ob_ce_width',
			[
				'label' => __( 'Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'label_block' => true,
				'default' => '', 
				'description' => __( 'You can enter any acceptable CSS value ( 50em, 25vw, 42.1% ) or the expression ( 100% - 300px, 55vw - 150px, 15rem - 20px ). NO calc() needed, it will be added automatically!', 'ooohboi-steroids' ), 
				'selectors' => [
					'{{WRAPPER}}.ob-is-container-extras' => '--width: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_use_container_extras' => 'yes', 
					'content_width' => 'full', 
				],
			]
		); 
		$element->add_responsive_control(
			'_ob_ce_boxed_width',
			[
				'label' => __( 'Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'label_block' => true,
				'default' => '', 
				'description' => __( 'You can enter any acceptable CSS value ( 50em, 25vw, 42.1% ) or the expression ( 100% - 300px, 55vw - 150px, 15rem - 20px ). NO calc() needed, it will be added automatically!', 'ooohboi-steroids' ), 
				'selectors' => [
					'{{WRAPPER}}.ob-is-container-extras' => '--content-width: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_use_container_extras' => 'yes', 
					'content_width' => 'boxed', 
				],
			]
		);
		$element->add_responsive_control(
			'_ob_ce_max_width',
			[
				'label' => __( 'Max-width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'label_block' => true,
				'default' => '', 
				'description' => __( 'You can enter any acceptable CSS value ( 50em, 25vw, 42.1% ) or the expression ( 100% - 300px, 55vw - 150px, 15rem - 20px ). NO calc() needed, it will be added automatically!', 'ooohboi-steroids' ), 
				'selectors' => [
					'{{WRAPPER}}.ob-is-container-extras' => 'max-width: Min(100%,calc({{VALUE}}));',
				],
				'condition' => [
					'_ob_use_container_extras' => 'yes', 
					'content_width' => 'full', 
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Container calc min height
		$element->add_responsive_control(
            '_ob_ce_calc_min_height',
			[
				'label' => __( 'Calc Min Height', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'label_block' => true,
				'default' => '', 
				'description' => __( 'You can enter any acceptable CSS value ( 50em, 25vw, 42.1% ) or the expression ( 100% - 300px, 55vw - 150px, 15rem - 20px ). NO calc() needed, it will be added automatically!', 'ooohboi-steroids' ), 
				'selectors' => [
					'{{WRAPPER}}.ob-is-container-extras' => '--min-height: calc({{VALUE}});', 
				],
				'condition' => [
					'_ob_use_container_extras' => 'yes', 
				],
			]
		);

	}

}