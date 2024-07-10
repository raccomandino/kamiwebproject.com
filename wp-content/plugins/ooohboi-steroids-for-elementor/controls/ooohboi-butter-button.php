<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Butter Button
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.3.6
 */
class OoohBoi_Butter_Button {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.3.6
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/button/section_style/after_section_end',  [ __CLASS__, 'ob_butterbutton_panel' ], 10, 2 );
		add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

        /* should enqueue? */
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

	}

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/butterbutton-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_butterbutton_use_it' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/section/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

	public static function add_attributes( $element ) {

        if ( 'button' !== $element->get_name() ) return;

        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;

		$settings = $element->get_settings_for_display();
		
		$butterbutton_in_use = isset( $settings[ '_ob_butterbutton_use_it' ] ) ? $settings[ '_ob_butterbutton_use_it' ] : '';

        if ( 'yes' === $butterbutton_in_use ) $element->add_render_attribute( '_wrapper', 'class', 'ob-is-butterbutton' );

    }
	
	public static function ob_butterbutton_panel( $element, $args ) {

		// selector based on the current element
		$selector = '{{WRAPPER}}.ob-is-butterbutton .elementor-button'; 

		$element->start_controls_section(
            '_ob_butterbutton',
            [
                'label' => 'B U T T E R - B U T T O N',
				'tab' => Controls_Manager::TAB_STYLE, 
            ]
		);

		// --------------------------------------------------------------------------------------------- CONTROL: Use Butter Button
		$element->add_control(
			'_ob_butterbutton_use_it',
			[
                'label' => __( 'Enable Butter Buttons?', 'ooohboi-steroids' ), 
				'separator' => 'after', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Icon Size
        $element->add_responsive_control(
            '_ob_butterbutton_icon_size',
            [
				'label' => __( 'Icon Size', 'ooohboi-steroids' ), 
				'description' => __( 'Be sure the icon is selected in the Content tab!', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'em',
					'size' => 1,
				],
				'selectors' => [
					$selector . ' .elementor-button-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Transition duration
		$element->add_control(
            '_ob_butterbutton_transitions',
            [
				'label' => __( 'Transition Duration', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template', 
				'default' => [
					'size' => 250,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					"$selector, $selector:before, $selector:after, $selector .elementor-button-content-wrapper span" => 'transition-duration: {{SIZE}}ms;', 
				],
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Padding
		$element->add_responsive_control(
			'_ob_butterbutton_padding',
			[
				'label' => __( 'Padding', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					$selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
			]
		);

		// --------------------------------------------------------------------------------------------- START 2 TABS Regular & Hover
		$element->start_controls_tabs( '_ob_butterbutton_tabs' );

		// --------------------------------------------------------------------------------------------- START TAB Regular
        $element->start_controls_tab(
            '_ob_butterbutton_tabs_reg',
            [
                'label' => __( 'Normal', 'ooohboi-steroids' ),
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
            ]
		);

		// --------------------------------------------------------------------------------------------- CONTROL: Text COLOR Regular
		$element->add_control(
			'_ob_butterbutton_color_reg',
			[
				'label' => __( 'Text Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					$selector => 'color: {{VALUE}};',
				],
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL: Text SHADOW Regular
		$element->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => '_ob_butterbutton_text_shadow_reg', 
				'separator' => 'before', 
				'selector' => $selector . ' .elementor-button-content-wrapper span',
				'fields_options' => [
					'text_shadow_type' => [
						'label' => _x( 'Text Shadow', 'Butter Button Text Shadow Control', 'ooohboi-steroids' ),
					],
				],
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
			]
		);

        $element->add_control(
            '_ob_butterbutton_premise_reg',
            [
                'label' => esc_html__( 'The Background Settings', 'ooohboi-animator' ), 
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => esc_html__( 'This won\'t make any effect unless you set the original NORMAL and HOVER background colors to transparent!', 'ooohboi-animator' ), 
                'content_classes' => 'elementor-control-field-description', 
                'separator' => 'before', 
                'condition' => [
                    '_ob_butterbutton_use_it' => 'yes',
                ], 
            ]
        );

		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND Regular
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_butterbutton_tabs_reg_bg', 
				'frontend_available' => true, 
				'selector' => $selector . ':before', 
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
            ]
		);

		// --------------------------------------------------------------------------------------------- CONTROL BORDER Regular
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_butterbutton_border_reg', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => $selector, 
				'condition' => [
                    '_ob_butterbutton_use_it' => 'yes',
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL BORDER RADIUS Regular
		$element->add_responsive_control(
			'_ob_butterbutton_border_rad_reg',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_butterbutton_use_it' => 'yes',
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Box Shadow Regular
		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_ob_butterbutton_shadow_reg', 
				'label' => __( 'Box Shadow', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => $selector, 
				'fields_options' => [
					'box_shadow' => [
						'default' => [
							'horizontal' => 5,
							'vertical' => 5,
							'blur' => 10,
							'spread' => 3,
							'color' => 'rgba(0,0,0,0.2)',
						],
					],
				],
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
                ],
			]
		);

		$element->end_controls_tab(); // Regular tab end

		// --------------------------------------------------------------------------------------------- START TAB Hover
        $element->start_controls_tab(
            '_ob_butterbutton_tabs_hov',
            [
                'label' => __( 'Hover', 'ooohboi-steroids' ),
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
            ]
		);

		// --------------------------------------------------------------------------------------------- CONTROL: Text COLOR Hover
		$element->add_control(
			'_ob_butterbutton_color_hov',
			[
				'label' => __( 'Text Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					$selector . ':hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
			]
		);
		
		// --------------------------------------------------------------------------------------------- CONTROL: Text SHADOW Hover
		$element->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => '_ob_butterbutton_text_shadow_hov', 
				'separator' => 'before', 
				'selector' => $selector . ':hover .elementor-button-content-wrapper span',
				'fields_options' => [
					'text_shadow_type' => [
						'label' => _x( 'Text Shadow', 'Butter Button Text Shadow Control', 'ooohboi-steroids' ),
					],
				],
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
			]
		);

        $element->add_control(
            '_ob_butterbutton_premise_rhov',
            [
                'label' => esc_html__( 'The Background Settings', 'ooohboi-animator' ), 
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => esc_html__( 'This won\'t make any effect unless you set the original NORMAL and HOVER background colors to transparent!', 'ooohboi-animator' ), 
                'content_classes' => 'elementor-control-field-description', 
                'separator' => 'before', 
                'condition' => [
                    '_ob_butterbutton_use_it' => 'yes',
                ], 
            ]
        );

		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND Hover
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_butterbutton_tabs_hov_bg', 
				'description' => __( 'NOTE: this won\'t make any effect unless you set the original HOVER background color to transparent!', 'ooohboi-steroids' ),
				'frontend_available' => true, 
				'selector' => $selector . ':after', 
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
				],
            ]
		);

		// --------------------------------------------------------------------------------------------- CONTROL BORDER Hover
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_butterbutton_border_hov', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => $selector . ':hover', 
				'condition' => [
                    '_ob_butterbutton_use_it' => 'yes',
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL BORDER RADIUS Hover
		$element->add_responsive_control(
			'_ob_butterbutton_border_rad_hov',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					$selector . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_butterbutton_use_it' => 'yes',
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Box Shadow Hover
		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_ob_butterbutton_shadow_hov', 
				'label' => __( 'Box Shadow', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => $selector . ':hover', 
				'fields_options' => [
					'box_shadow' => [
						'default' => [
							'horizontal' => 5,
							'vertical' => 5,
							'blur' => 10,
							'spread' => 3,
							'color' => 'rgba(0,0,0,0.2)',
						],
					],
				],
				'condition' => [
					'_ob_butterbutton_use_it' => 'yes',
                ],
			]
		);

		$element->end_controls_tab(); // Hover tab end

		$element->end_controls_tabs(); // Regular & Hover tabs end

		$element->end_controls_section(); // END SECTION / PANEL

    }

}