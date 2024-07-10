<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Perspektive Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class OoohBoi_Perspektive {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.4.4
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/common/_section_style/before_section_end',  [ __CLASS__, 'ob_perspektive_add_section' ] );
        add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'ob_perspektive_add_attributes' ] ); 

        /* should enqueue? */
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/perspektive-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_perspektive_use' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

	public static function ob_perspektive_add_attributes( Element_Base $element ) {

        if ( in_array( $element->get_name(), [ 'section', 'column' ] ) ) {
            return;
        }

        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
            return;
        }

		$settings = $element->get_settings_for_display();

		$use_perspektive  = isset( $settings[ '_ob_perspektive_use' ] ) ? $settings[ '_ob_perspektive_use' ] : '';

        if ( 'yes' === $use_perspektive ) {
            $element->add_render_attribute( '_wrapper', 'class', 'ob-use-perspektive' );
        }

    }

    public static function ob_perspektive_add_section( Element_Base $element ) {

        $selector = '{{WRAPPER}}.ob-use-perspektive .elementor-widget-container';

        //  create panel section
		$element->add_control(
			'_ob_perspektive',
			[
				'label' => 'P E R S P E K T I V E', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );
		// ------------------------------------------------------------------------- CONTROL: Use Perspektive
		$element->add_control(
			'_ob_perspektive_use',
			[
                'label' => __( 'Enable Perspektive?', 'ooohboi-steroids' ), 
				'description' => __( 'NOTE: Perspektive interferes with z-index! The background of the element with the Perspektive will not be affected by the Perspektive.', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
        );

		// --------------------------------------------------------------------------------------------- CONTROL Perspective depth
		$element->add_responsive_control(
            '_ob_perspektive_val',
            [
				'label' => __( 'Perspective', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1500,
					],
					'em' => [
						'min' => 10,
						'max' => 150,
					],
					'rem' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 800,
				],
				'selectors' => [
					$selector => 'transform: perspective({{SIZE}}{{UNIT}}); transform-style: preserve-3d;', 
                ],
                'condition' => [
					'_ob_perspektive_use' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Perspective origin
		$element->add_responsive_control(
            '_ob_perspektive_origin',
            [
				'label' => __( 'Perspective Origin', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'custom' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'selectors' => [
					$selector => 'perspective-origin: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};', 
                ],
                'condition' => [
					'_ob_perspektive_use' => 'yes',  
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Perspective Z
		$element->add_responsive_control(
            '_ob_perspektive_z',
            [
				'label' => __( 'Translate Z', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
					'em' => [
						'min' => -10,
						'max' => 10,
					],
					'rem' => [
						'min' => -10,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					$selector . ' > *' => 'transform: translateZ({{SIZE}}{{UNIT}});', 
                ],
                'condition' => [
					'_ob_perspektive_use' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Rotate X
		$element->add_responsive_control(
            '_ob_perspektive_xrot',
            [
				'label' => __( 'Rotate X', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -90,
						'max' => 90,
						'step' => 5,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					$selector . ' > *' => 'transform: rotateX({{SIZE}}deg) rotateY({{_ob_perspektive_yrot.SIZE}}deg) translateZ({{_ob_perspektive_z.SIZE}}{{_ob_perspektive_z.UNIT}});', 
                ],
                'condition' => [
					'_ob_perspektive_use' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Rotate Y
		$element->add_responsive_control(
            '_ob_perspektive_yrot',
            [
				'label' => __( 'Rotate Y', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -90,
						'max' => 90,
						'step' => 5,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					$selector . ' > *' => 'transform: rotateX({{_ob_perspektive_xrot.SIZE}}deg) rotateY({{SIZE}}deg) translateZ({{_ob_perspektive_z.SIZE}}{{_ob_perspektive_z.UNIT}});', 
                ],
                'condition' => [
					'_ob_perspektive_use' => 'yes', 
				],
			]
        );
        // ------------------------------------------------------------------------- CONTROL: Visibility
		$element->add_control(
			'_ob_parspektive_visibility',
			[
				'label' => __( 'Content Overflow', 'ooohboi-steroids' ),
				'description' => __( 'Just in case the wrapper is "overflow: hidden"', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'visible',
				'separator' => 'before', 
				'options' => [
					'visible' => __( 'Visible', 'ooohboi-steroids' ), 
					'hidden' => __( 'Hidden', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					$selector => 'overflow: {{value}};',
				],
				'condition' => [
					'_ob_perspektive_use' => 'yes', 
				],
			]
		);

	}

}