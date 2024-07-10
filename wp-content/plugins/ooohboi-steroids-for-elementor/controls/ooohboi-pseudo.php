<?php
use Elementor\Controls_Manager; 
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter; 
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Pseudo Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.7.1
 */
class OoohBoi_Pseudo {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.7.1
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/column/section_advanced/after_section_end',  [ __CLASS__, 'ooohboi_handle_pseudo' ] );
		add_action( 'elementor/element/container/section_layout/after_section_end',  [ __CLASS__, 'ooohboi_handle_pseudo' ] );

		add_action( 'elementor/frontend/column/before_render', [ __CLASS__, 'ooohboi_before_render_hook' ] );
		add_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'ooohboi_before_render_hook' ] );

        /* should enqueue? */
        add_action( 'elementor/frontend/column/before_render', [ __CLASS__, 'should_script_enqueue' ] ); 
		add_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

	}

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/pseudo-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_column_has_pseudo' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/column/before_render', [ __CLASS__, 'should_script_enqueue' ] );
			remove_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

	public static function ooohboi_before_render_hook( Element_Base $element ) {

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;
		$settings = $element->get_settings_for_display();

		if ( isset( $settings[ '_ob_column_has_pseudo' ] ) && 'yes' === $settings[ '_ob_column_has_pseudo' ] ) {

			$element->add_render_attribute( '_wrapper', [
				'class' => 'ob-is-pseudo'
			] );

		}

	}


    public static function ooohboi_handle_pseudo( Element_Base $element ) {

		$selector_before = '{{WRAPPER}}.ob-is-pseudo > .elementor-element-populated:before'; 
		$selector_after  = '{{WRAPPER}}.ob-is-pseudo > .elementor-element-populated:after'; 

		if( 'container' === $element->get_type() ) {
			$selector_before = '{{WRAPPER}}.ob-is-pseudo > .ob-pseudo-before'; 
			$selector_after  = '{{WRAPPER}}.ob-is-pseudo > .ob-pseudo-after'; 
		}

        //  create panel
        $element->start_controls_section(
            '_ob_pseudo_section_title',
            [
                'label' => 'P S E U D O',
				'tab' => Controls_Manager::TAB_ADVANCED, 
            ]
		);
        // ------------------------------------------------------------------------- CONTROL: Use Pseudo for Section and Columns
		$element->add_control(
			'_ob_column_has_pseudo',
			[
                'label' => __( 'Enable Pseudo?', 'ooohboi-steroids' ), 
				'description' => __( 'This is how you can create and manage :before and :after pseudo elements for this column', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
		);

        // --------------------------------------------------------------------------------------------- START 2 TABS Before & After
		$element->start_controls_tabs( '_ob_pseudo_tabs' );

		// --------------------------------------------------------------------------------------------- START TAB Before
        $element->start_controls_tab(
            '_ob_pseudo_tab_before',
            [
                'label' => __( 'Before', 'ooohboi-steroids' ),
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_pseudo_before_background', 
                'selector' => $selector_before, 
                'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND OPACITY
        $element->add_control(
            '_ob_pseudo_before_bg_opacity',
            [
                'label' => __( 'Opacity', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    $selector_before => 'opacity: {{SIZE}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL FILTERS
		$element->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => '_ob_pseudo_before_bg_filters',
				'selector' => $selector_before, 
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BLEND MODE
        $element->add_control(
            '_ob_pseudo_before_bg_blend_mode',
            [
                'label' => __( 'Blend Mode', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'ooohboi-steroids' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    $selector_before => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
                ],
            ]
        );

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER W H Y X Rot
		$element->add_control(
            '_ob_pseudo_before_popover_whyxrot',
            [
                'label' => __( 'Position and Size', 'ooohboi-steroids' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
                ],
            ]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER WIDTH
        $element->add_responsive_control(
            '_ob_pseudo_before_w',
            [
				'label' => __( 'Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_before_w_alt' => '', 
							'_ob_pseudo_before_popover_whyxrot' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_before_w_alt' => '', 
							'_ob_pseudo_before_popover_whyxrot' => 'yes', 
						],
					],
				],
				'selectors' => [
					$selector_before => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_w_alt' => '', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER WIDTH - Alternative
        $element->add_responsive_control(
            '_ob_pseudo_before_w_alt',
            [
				'label' => __( 'Calc Width', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector_before => 'width: calc({{VALUE}});',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER HEIGHT
        $element->add_responsive_control(
            '_ob_pseudo_before_h',
            [
				'label' => __( 'Height', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_before_h_alt' => '', 
							'_ob_pseudo_before_popover_whyxrot' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_before_h_alt' => '', 
							'_ob_pseudo_before_popover_whyxrot' => 'yes', 
						],
					],
				],
				'selectors' => [
					$selector_before => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_h_alt' => '', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER HEIGHT - Alternative
        $element->add_responsive_control(
            '_ob_pseudo_before_h_alt',
            [
				'label' => __( 'Calc Height', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 45% + 85px or 100% - 3em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector_before => 'height: calc({{VALUE}});',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET TOP
		$element->add_responsive_control(
			'_ob_pseudo_before_y',
			[
				'label' => __( 'Offset Top', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					],
					'%' => [
						'min' => -500,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_before_y_alt' => '', 
							'_ob_pseudo_before_popover_whyxrot' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_before_y_alt' => '', 
							'_ob_pseudo_before_popover_whyxrot' => 'yes', 
						],
					],
				],
				'selectors' => [
					$selector_before => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_y_alt' => '', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET TOP - Alternative
        $element->add_responsive_control(
            '_ob_pseudo_before_y_alt',
            [
				'label' => __( 'Calc Offset Top', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector_before => 'top: calc({{VALUE}});',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET LEFT
		$element->add_responsive_control(
			'_ob_pseudo_before_x',
			[
				'label' => __( 'Offset Left', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					],
					'%' => [
						'min' => -500,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_before_x_alt' => '', 
							'_ob_pseudo_before_popover_whyxrot' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_before_x_alt' => '', 
							'_ob_pseudo_before_popover_whyxrot' => 'yes', 
						],
					],
				],
				'selectors' => [
					$selector_before => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_x_alt' => '', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET LEFT - Alternative
        $element->add_responsive_control(
            '_ob_pseudo_before_x_alt',
            [
				'label' => __( 'Calc Offset Left', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 45% + 85px or 100% - 3em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector_before => 'left: calc({{VALUE}});',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER ROTATION
		# NOTE : this is the hack. Elementor does not do well with 'deg' when speaking of responsiveness!
		$element->add_responsive_control(
			'_ob_pseudo_before_rot',
			[
				'label' => __( 'Rotate', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 5,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					$selector_before => 'transform: rotate({{SIZE}}deg);',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_whyxrot' => 'yes', 
                ],
			]
		);

		$element->end_popover(); // popover end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER
		$element->add_control(
            '_ob_pseudo_before_popover_border',
            [
                'label' => __( 'Border', 'ooohboi-steroids' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
				'frontend_available' => true, 
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
                ],
            ]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER ALL
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_pseudo_before_borders', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'selector' => $selector_before, 
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER RADIUS
		$element->add_responsive_control(
			'_ob_pseudo_before_border_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					$selector_before => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_border' => 'yes', 
                ],
			]
		);

		$element->end_popover(); // popover BORdER end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ
		$element->add_control(
            '_ob_pseudo_before_popover_masq',
            [
                'label' => __( 'Before Mask', 'ooohboi-steroids' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
				],
            ]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ IMAGE
		$element->add_responsive_control(
			'_ob_pseudo_before_mask_img',
			[
				'label' => __( 'Choose Image Mask', 'ooohboi-steroids' ),
				'description' => __( 'NOTE: Image Mask should be black-and-transparent SVG file! Anything that’s 100% black in the image mask with be completely visible, anything that’s transparent will be completely hidden.', 'ooohboi-steroids' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'selectors' => [
					$selector_before => '-webkit-mask-image: url("{{URL}}"); mask-image: url("{{URL}}"); -webkit-mask-mode: alpha; mask-mode: alpha;',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ POSITION
		$element->add_responsive_control(
			'_ob_pseudo_before_mask_position',
			[
				'label' => __( 'Mask position', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center center',
				'options' => [
					'' => __( 'Default', 'ooohboi-steroids' ),
					'center center' => __( 'Center Center', 'ooohboi-steroids' ),
					'center left' => __( 'Center Left', 'ooohboi-steroids' ),
					'center right' => __( 'Center Right', 'ooohboi-steroids' ),
					'top center' => __( 'Top Center', 'ooohboi-steroids' ),
					'top left' => __( 'Top Left', 'ooohboi-steroids' ),
					'top right' => __( 'Top Right', 'ooohboi-steroids' ),
					'bottom center' => __( 'Bottom Center', 'ooohboi-steroids' ),
					'bottom left' => __( 'Bottom Left', 'ooohboi-steroids' ),
					'bottom right' => __( 'Bottom Right', 'ooohboi-steroids' ),
				],
				'selectors' => [
					$selector_before => '-webkit-mask-position: {{VALUE}}; mask-position: {{VALUE}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_mask_img[url]!' => '',
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE
		$element->add_responsive_control(
			'_ob_pseudo_before_mask_size',
			[
				'label' => __( 'Mask size', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'contain', 
				'options' => [
					'' => __( 'Default', 'ooohboi-steroids' ),
					'auto' => __( 'Auto', 'ooohboi-steroids' ),
					'cover' => __( 'Cover', 'ooohboi-steroids' ),
					'contain' => __( 'Contain', 'ooohboi-steroids' ),
					'initial' => __( 'Custom', 'ooohboi-steroids' ),
				],
				'selectors' => [
					$selector_before => '-webkit-mask-size: {{VALUE}}; mask-size: {{VALUE}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_mask_img[url]!' => '',
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE Custom
		$element->add_responsive_control(
			'_ob_pseudo_before_mask_size_width', 
			[
				'label' => __( 'Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					$selector_before => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_mask_size' => [ 'initial' ],
					'_ob_pseudo_before_mask_img[url]!' => '',
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_masq' => 'yes', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							$selector_before => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_before_mask_size' => [ 'initial' ],
							'_ob_pseudo_before_popover_masq' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							$selector_before => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_before_mask_size' => [ 'initial' ], 
							'_ob_pseudo_before_popover_masq' => 'yes', 
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ REPEAT
		$element->add_responsive_control(
			'_ob_pseudo_before_mask_repeat',
			[
				'label' => __( 'Mask repeat', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no-repeat',
				'options' => [
					'no-repeat' => __( 'No-repeat', 'ooohboi-steroids' ),
					'repeat' => __( 'Repeat', 'ooohboi-steroids' ),
					'repeat-x' => __( 'Repeat-x', 'ooohboi-steroids' ),
					'repeat-y' => __( 'Repeat-y', 'ooohboi-steroids' ),
				],
				'selectors' => [
					$selector_before => '-webkit-mask-repeat: {{VALUE}}; mask-repeat: {{VALUE}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_mask_img[url]!' => '',
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_before_popover_masq' => 'yes', 
				],
			]
		);

		$element->end_popover(); // popover MASQ end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER CLIP PATH since 1.6.4
        $element->add_control(
			'_ob_pseudo_before_clip_path',
			[				
                'label' => __( 'Clip path', 'ooohboi-steroids' ), 
                'description' => sprintf(
                    __( 'Enter the full clip-path property! See the copy-paste examples at %sClippy%s', 'ooohboi-steroids' ),
                    '<a href="https://bennettfeely.com/clippy/" target="_blank">',
                    '</a>'
				),
				'default' => '', 
				'type' => Controls_Manager::TEXTAREA, 
				'rows' => 3, 
				'selectors' => [
					$selector_before => '{{VALUE}}',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Z-INDeX
		$element->add_control(
			'_ob_pseudo_before_z_index',
			[
				'label' => __( 'Z-Index', 'ooohboi-steroids' ),
				'type' => Controls_Manager::NUMBER,
				'min' => -9999,
				'default' => 0, 
				'selectors' => [
					$selector_before => 'z-index: {{VALUE}};',
				],
				'label_block' => false, 
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_before_background_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);

		$element->end_controls_tab(); // Before tab end

		// --------------------------------------------------------------------------------------------- START TAB After ------------------------------- >>>>>

		$element->start_controls_tab(
            '_ob_pseudo_tab_after',
            [
                'label' => __( 'After', 'ooohboi-steroids' ),
            ]
		);

		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_pseudo_after_background', 
                'selector' => $selector_after, 
                'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND OPACITY
        $element->add_control(
            '_ob_pseudo_after_bg_opacity',
            [
                'label' => __( 'Opacity', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    $selector_after => 'opacity: {{SIZE}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL FILTERS
		$element->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => '_ob_pseudo_after_bg_filters',
				'selector' => $selector_after, 
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BLEND MODE
        $element->add_control(
            '_ob_pseudo_after_bg_blend_mode',
            [
                'label' => __( 'Blend Mode', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'ooohboi-steroids' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    $selector_after => 'mix-blend-mode: {{VALUE}}',
				], 
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER W H Y X Rot
		$element->add_control(
			'_ob_pseudo_after_popover_whyxrot',
			[
				'label' => __( 'Position and Size', 'ooohboi-steroids' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'frontend_available' => true, 
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);

		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER WIDTH
		$element->add_responsive_control(
			'_ob_pseudo_after_w',
			[
				'label' => __( 'Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_after_w_alt' => '', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_after_w_alt' => '', 
						],
					],
				],
				'selectors' => [
					$selector_after => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_w_alt' => '', 
					'_ob_pseudo_after_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER WIDTH - Alternative
        $element->add_responsive_control(
            '_ob_pseudo_after_w_alt',
            [
				'label' => __( 'Calc Width', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector_after => 'width: calc({{VALUE}});',
				],
                'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER HEIGHT
		$element->add_responsive_control(
			'_ob_pseudo_after_h',
			[
				'label' => __( 'Height', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_after_h_alt' => '', 
							'_ob_pseudo_after_popover_whyxrot' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_after_h_alt' => '', 
							'_ob_pseudo_after_popover_whyxrot' => 'yes', 
						],
					],
				],
				'selectors' => [
					$selector_after => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_h_alt' => '', 
					'_ob_pseudo_after_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER HEIGHT - Alternative
        $element->add_responsive_control(
            '_ob_pseudo_after_h_alt',
            [
				'label' => __( 'Calc Height', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 45% + 85px or 100% - 3em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector_after => 'height: calc({{VALUE}});',
				],
                'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET TOP
		$element->add_responsive_control(
			'_ob_pseudo_after_y',
			[
				'label' => __( 'Offset Top', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					],
					'%' => [
						'min' => -500,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_after_y_alt' => '', 
							'_ob_pseudo_after_popover_whyxrot' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_after_y_alt' => '', 
							'_ob_pseudo_after_popover_whyxrot' => 'yes', 
						],
					],
				],
				'selectors' => [
					$selector_after => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_y_alt' => '', 
					'_ob_pseudo_after_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET TOP - Alternative
        $element->add_responsive_control(
            '_ob_pseudo_after_y_alt',
            [
				'label' => __( 'Calc Offset Top', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector_after => 'top: calc({{VALUE}});',
				],
                'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET LEFT
		$element->add_responsive_control(
			'_ob_pseudo_after_x',
			[
				'label' => __( 'Offset Left', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					],
					'%' => [
						'min' => -500,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_after_x_alt' => '', 
							'_ob_pseudo_after_popover_whyxrot' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_after_x_alt' => '', 
							'_ob_pseudo_after_popover_whyxrot' => 'yes', 
						],
					],
				],
				'selectors' => [
					$selector_after => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_x_alt' => '', 
					'_ob_pseudo_after_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET LEFT - Alternative
        $element->add_responsive_control(
            '_ob_pseudo_after_x_alt',
            [
				'label' => __( 'Calc Offset Left', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector_after => 'left: calc({{VALUE}});',
				],
                'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_popover_whyxrot' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER ROTATION
		# NOTE : this is the hack. Elementor does not do well with 'deg' when speaking of responsiveness!
		$element->add_responsive_control(
			'_ob_pseudo_after_rot',
			[
				'label' => __( 'Rotate', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 5,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					$selector_after => 'transform: rotate({{SIZE}}deg);',
				],
                'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_popover_whyxrot' => 'yes', 
                ],
			]
		);

		$element->end_popover(); // popover end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER
		$element->add_control(
			'_ob_pseudo_after_popover_border',
			[
				'label' => __( 'Border', 'ooohboi-steroids' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'frontend_available' => true, 
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);

		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER ALL
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_pseudo_after_borders', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'selector' => $selector_after, 
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER RADIUS
		$element->add_responsive_control(
			'_ob_pseudo_after_border_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					$selector_after => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_after_popover_border' => 'yes', 
                ],
			]
		);

		$element->end_popover(); // popover BORdER end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ - After ------------------->>
		$element->add_control(
            '_ob_pseudo_after_popover_masq',
            [
                'label' => __( 'After Mask', 'ooohboi-steroids' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
				],
            ]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ IMAGE
		$element->add_responsive_control(
			'_ob_pseudo_after_mask_img',
			[
				'label' => __( 'Choose Image Mask', 'ooohboi-steroids' ),
				'description' => __( 'NOTE: Image Mask should be black-and-transparent SVG file! Anything that’s 100% black in the image mask with be completely visible, anything that’s transparent will be completely hidden.', 'ooohboi-steroids' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'selectors' => [
					$selector_after => '-webkit-mask-image: url("{{URL}}"); mask-image: url("{{URL}}"); -webkit-mask-mode: alpha; mask-mode: alpha;',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
					'_ob_pseudo_after_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ POSITION
		$element->add_responsive_control(
			'_ob_pseudo_after_mask_position',
			[
				'label' => __( 'Mask position', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center center',
				'options' => [
					'' => __( 'Default', 'ooohboi-steroids' ),
					'center center' => __( 'Center Center', 'ooohboi-steroids' ),
					'center left' => __( 'Center Left', 'ooohboi-steroids' ),
					'center right' => __( 'Center Right', 'ooohboi-steroids' ),
					'top center' => __( 'Top Center', 'ooohboi-steroids' ),
					'top left' => __( 'Top Left', 'ooohboi-steroids' ),
					'top right' => __( 'Top Right', 'ooohboi-steroids' ),
					'bottom center' => __( 'Bottom Center', 'ooohboi-steroids' ),
					'bottom left' => __( 'Bottom Left', 'ooohboi-steroids' ),
					'bottom right' => __( 'Bottom Right', 'ooohboi-steroids' ),
				],
				'selectors' => [
					$selector_after => '-webkit-mask-position: {{VALUE}}; mask-position: {{VALUE}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_mask_img[url]!' => '',
					'_ob_pseudo_after_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE
		$element->add_responsive_control(
			'_ob_pseudo_after_mask_size',
			[
				'label' => __( 'Mask size', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'contain', 
				'options' => [
					'' => __( 'Default', 'ooohboi-steroids' ),
					'auto' => __( 'Auto', 'ooohboi-steroids' ),
					'cover' => __( 'Cover', 'ooohboi-steroids' ),
					'contain' => __( 'Contain', 'ooohboi-steroids' ),
					'initial' => __( 'Custom', 'ooohboi-steroids' ),
				],
				'selectors' => [
					$selector_after => '-webkit-mask-size: {{VALUE}}; mask-size: {{VALUE}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_mask_img[url]!' => '',
					'_ob_pseudo_after_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE Custom
		$element->add_responsive_control(
			'_ob_pseudo_after_mask_size_width', 
			[
				'label' => __( 'Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					$selector_after => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_mask_size' => [ 'initial' ],
					'_ob_pseudo_after_mask_img[url]!' => '',
					'_ob_pseudo_after_popover_masq' => 'yes', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							$selector_after => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_after_mask_size' => [ 'initial' ],
							'_ob_pseudo_after_popover_masq' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							$selector_after => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
                            '_ob_column_has_pseudo' => 'yes', 
							'_ob_pseudo_after_mask_size' => [ 'initial' ], 
							'_ob_pseudo_after_popover_masq' => 'yes', 
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ REPEAT
		$element->add_responsive_control(
			'_ob_pseudo_after_mask_repeat',
			[
				'label' => __( 'Mask repeat', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no-repeat',
				'options' => [
					'no-repeat' => __( 'No-repeat', 'ooohboi-steroids' ),
					'repeat' => __( 'Repeat', 'ooohboi-steroids' ),
					'repeat-x' => __( 'Repeat-x', 'ooohboi-steroids' ),
					'repeat-y' => __( 'Repeat-y', 'ooohboi-steroids' ),
				],
				'selectors' => [
					$selector_after => '-webkit-mask-repeat: {{VALUE}}; mask-repeat: {{VALUE}};',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_mask_img[url]!' => '',
					'_ob_pseudo_after_popover_masq' => 'yes', 
				],
			]
		);

		$element->end_popover(); // popover MASQ end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER CLIP PATH since 1.6.4
        $element->add_control(
			'_ob_pseudo_after_clip_path',
			[				
                'label' => __( 'Clip path', 'ooohboi-steroids' ), 
                'description' => sprintf(
                    __( 'Enter the full clip-path property! See the copy-paste examples at %sClippy%s', 'ooohboi-steroids' ),
                    '<a href="https://bennettfeely.com/clippy/" target="_blank">',
                    '</a>'
				),
				'default' => '', 
				'type' => Controls_Manager::TEXTAREA, 
				'rows' => 3, 
				'selectors' => [
					$selector_after => '{{VALUE}}',
				],
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
					'_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Z-INDeX
		$element->add_control(
			'_ob_pseudo_after_z_index',
			[
				'label' => __( 'Z-Index', 'ooohboi-steroids' ),
				'type' => Controls_Manager::NUMBER,
				'min' => -9999,
				'default' => 0, 
				'selectors' => [
					$selector_after => 'z-index: {{VALUE}};',
				],
				'label_block' => false, 
				'condition' => [
                    '_ob_column_has_pseudo' => 'yes', 
                    '_ob_pseudo_after_background_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);

		$element->end_controls_tab(); // After tab end

		$element->end_controls_tabs(); // After and Before tabs end

		$element->end_controls_section(); // END SECTION / PANEL

    }

}