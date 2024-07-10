<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Counterz
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.9.8
 */
class OoohBoi_Counterz {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.9.8
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/counter/section_title/after_section_end',  [ __CLASS__, 'ooohboi_counterz_opts' ], 10, 1 ); 
        add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

        /* should enqueue? */
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/counterz-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_use_counterz' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/element/counter/section_title/after_section_end', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

    public static function add_attributes( $element ) {

        if( ! in_array( $element->get_name(), [ 'counter' ] ) ) return;
		$settings = $element->get_settings();
        $is_counterz = isset( $settings[ '_ob_use_counterz' ] ) ? $settings[ '_ob_use_counterz' ] : '';
        
        if( 'yes' === $settings[ '_ob_use_counterz' ] ) 
            $element->add_render_attribute( '_wrapper', 'class', 'ob-use-counterz' );

    }
    
	public static function ooohboi_counterz_opts( Element_Base $element ) {

		$element->start_controls_section(
            '_ob_counterz_title',
            [
                'label' => 'C O U N T E R Z',
				'tab' => Controls_Manager::TAB_STYLE, 
				'hide_in_inner' => true, 
            ]
        );

		// --------------------------------------------------------------------------------------------- CONTROL: Use Counterz
		$element->add_control(
			'_ob_use_counterz',
			[
                'label' => esc_html__( 'Enable Counterz?', 'ooohboi-steroids' ), 
				'separator' => 'after', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
				'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

        // --------------------------------------------------------------------------------------------- CONTROL Description - Faker !!!!!
        $element->add_control(
            '_ob_use_counterz_fake_descr_1',
            [
                'type' => Controls_Manager::RAW_HTML, 
                'label' => __( 'Numbers', 'ooohboi-steroids' ),
                'raw' => __( 'Additional options to the Numbers element', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
				'condition' => [
					'_ob_use_counterz' => 'yes', 
				],
            ]
        );
        // ------------------------------------------------------------------------- CONTROL: Numbers alignment
		$element->add_responsive_control(
			'_ob_counterz_numbers_align',
			[
				'label' => __( 'Align numbers', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'center', 
				'options' => [
					'flex-start' => __( 'Start', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'flex-end' => __( 'End', 'ooohboi-steroids' ), 
					'space-between' => __( 'Space Between', 'ooohboi-steroids' ), 
					'space-around' => __( 'Space Around', 'ooohboi-steroids' ), 
					'space-evenly' => __( 'Space Evenly', 'ooohboi-steroids' ),
				],
				'selectors' => [
					'{{WRAPPER}}.ob-use-counterz span[class*="elementor-counter-number"]' => 'flex-grow: unset;', 
                    '{{WRAPPER}}.ob-use-counterz .elementor-counter-number-wrapper' => 'justify-content: {{VALUE}};', 
				],
				'condition' => [
					'_ob_use_counterz' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Counterz title MARGIN
		$element->add_responsive_control(
			'_ob_counterz_numbers_marginz',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-counterz .elementor-counter-number-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'condition' => [
					'_ob_use_counterz' => 'yes', 
				],
			]
        );

 		// ------------------------------------------------------------------------- CONTROL: Counterz title PADDING
         $element->add_responsive_control(
			'_ob_counterz_numbers_padding',
			[
				'label' => __( 'Padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-counterz .elementor-counter-number-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'condition' => [
					'_ob_use_counterz' => 'yes', 
				],
			]
        );
		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_counterz_numbers_bg', 
                'selector' => '{{WRAPPER}}.ob-use-counterz .elementor-counter-number-wrapper',
				'condition' => [
					'_ob_use_counterz' => 'yes', 
				],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BORDER Regular
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_counterz_numbers_border', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => '{{WRAPPER}}.ob-use-counterz .elementor-counter-number-wrapper',
				'condition' => [
                    '_ob_use_counterz' => 'yes',
                ],
			]
		);


        // --------------------------------------------------------------------------------------------- CONTROL Description - Faker !!!!!
        $element->add_control(
            '_ob_use_counterz_fake_descr_2',
            [
                'type' => Controls_Manager::RAW_HTML, 
                'label' => __( 'Title', 'ooohboi-steroids' ),
                'raw' => __( 'Additional options to the Title element', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
				'condition' => [
					'_ob_use_counterz' => 'yes', 
				],
            ]
        );

        // ------------------------------------------------------------------------- CONTROL: Counterz title alignment
		$element->add_responsive_control(
			'_ob_counterz_title_align',
			[
				'label' => __( 'Align title', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'center', 
				'options' => [
					'left' => __( 'Left', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'right' => __( 'Right', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-use-counterz .elementor-counter-title' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'_ob_use_counterz' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Counterz title MARGIN
		$element->add_responsive_control(
			'_ob_counterz_title_marginz',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-counterz .elementor-counter-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'condition' => [
					'_ob_use_counterz' => 'yes', 
				],
			]
        );

 		// ------------------------------------------------------------------------- CONTROL: Counterz title PADDING
         $element->add_responsive_control(
			'_ob_counterz_title_padding',
			[
				'label' => __( 'Padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-counterz .elementor-counter-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'condition' => [
					'_ob_use_counterz' => 'yes', 
				],
			]
        );
		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_counterz_title_bg', 
                'selector' => '{{WRAPPER}}.ob-use-counterz .elementor-counter-title',
				'condition' => [
					'_ob_use_counterz' => 'yes', 
				],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BORDER Regular
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_counterz_title_border', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => '{{WRAPPER}}.ob-use-counterz .elementor-counter-title',
				'condition' => [
                    '_ob_use_counterz' => 'yes',
                ],
			]
		);


        $element->end_controls_section();

	}

}