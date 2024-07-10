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
 * Main OoohBoi Underlay Overlay Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class OoohBoi_Overlay_Underlay {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/common/_section_background/after_section_end',  [ __CLASS__, 'add_section' ] );
		add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );
		// Document Settings :: 
		add_action( 'elementor/element/wp-post/document_settings/before_section_end', [ __CLASS__, 'poopart_remove_horizontal_scroller' ] ); 
		add_action( 'elementor/element/wp-page/document_settings/before_section_end', [ __CLASS__, 'poopart_remove_horizontal_scroller' ] ); 

        /* should enqueue? */
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

	}

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/poopart-min.js'; 

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

            remove_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

	public static function add_attributes( Element_Base $element ) {

        if ( in_array( $element->get_name(), [ 'section', 'column', 'container' ] ) ) return;
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;

		$settings = $element->get_settings_for_display();

        if ( isset( $settings[ '_ob_poopart_use' ] ) && 'yes' === $settings[ '_ob_poopart_use' ] ) {

            $element->add_render_attribute( '_wrapper', [
                'class' => 'ob-has-background-overlay'
            ] );

        }

    }


    public static function add_section( Element_Base $element ) {

		$element->start_controls_section(
            '_ob_steroids_background_overlay',
            [
                'label' => 'P O O P A R T',
				'tab' => Controls_Manager::TAB_ADVANCED, 
            ]
		);

		// --------------------------------------------------------------------------------------------- CONTROL: Use Butter Button
		$element->add_control(
			'_ob_poopart_use',
			[
                'label' => __( 'Enable PoopArt?', 'ooohboi-steroids' ), 
				'separator' => 'after', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		// --------------------------------------------------------------------------------------------- START 2 TABS Overlay & Underlay
		$element->start_controls_tabs( '_ob_steroids_tabs' );

		// --------------------------------------------------------------------------------------------- START TAB Overlay
        $element->start_controls_tab(
            '_ob_steroids_tab_overlay',
            [
                'label' => __( 'Overlay', 'ooohboi-steroids' ),
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_steroids_overlay_background', 
                'selector' => '{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before',
				'condition' => [
                    '_ob_poopart_use' => 'yes', 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND OPACITY
        $element->add_control(
            '_ob_steroids_overlay_bg_opacity',
            [
                'label' => __( 'Opacity', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.7,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'opacity: {{SIZE}};',
				],
				'condition' => [
                    '_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL FILTERS
		$element->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => '_ob_steroids_overlay_bg_filters',
				'selector' => '{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before', 
				'condition' => [
                    '_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BLEND MODE
        $element->add_control(
            '_ob_steroids_overlay_bg_blend_mode',
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
                    '{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
                    '_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
            ]
        );
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER W H Y X Rot
		$element->add_control(
            '_ob_steroids_overlay_popover_whyxrot',
            [
                'label' => __( 'Position and Size', 'ooohboi-steroids' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
                    '_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
            ]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER WIDTH
        $element->add_responsive_control(
            '_ob_steroids_overlay_w',
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
					'size' => 100,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
							'_ob_steroids_overlay_w_alt' => '', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
							'_ob_steroids_overlay_w_alt' => '', 
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_ob_steroids_overlay_w_alt' => '', 
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER WIDTH - Alternative
        $element->add_responsive_control(
            '_ob_steroids_overlay_w_alt',
            [
				'label' => __( 'Calc Width', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'width: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER HEIGHT
        $element->add_responsive_control(
            '_ob_steroids_overlay_h',
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
					'size' => 100,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
							'_ob_steroids_overlay_h_alt' => '', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
							'_ob_steroids_overlay_h_alt' => '', 
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_ob_steroids_overlay_h_alt' => '', 
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER HEIGHT - Alternative
        $element->add_responsive_control(
            '_ob_steroids_overlay_h_alt',
            [
				'label' => __( 'Calc Height', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 45% + 85px or 100% - 3em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'height: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET TOP
		$element->add_responsive_control(
			'_ob_steroids_overlay_y',
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
							'_ob_steroids_overlay_y_alt' => '', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
							'_ob_steroids_overlay_y_alt' => '', 
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_ob_steroids_overlay_y_alt' => '', 
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET TOP - Alternative
        $element->add_responsive_control(
            '_ob_steroids_overlay_y_alt',
            [
				'label' => __( 'Calc Offset Top', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'top: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET LEFT
		$element->add_responsive_control(
			'_ob_steroids_overlay_x',
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
							'_ob_steroids_overlay_x_alt' => '', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
							'_ob_steroids_overlay_x_alt' => '', 
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_ob_steroids_overlay_x_alt' => '', 
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET LEFT - Alternative
        $element->add_responsive_control(
            '_ob_steroids_overlay_x_alt',
            [
				'label' => __( 'Calc Offset Left', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 45% + 85px or 100% - 3em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'left: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER ROTATION
		# NOTE : this is the hack. Elementor does not do well with 'deg' when speaking of responsiveness!
		$element->add_responsive_control(
			'_ob_steroids_overlay_rot',
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'transform: rotate({{SIZE}}deg);',
				],
				'condition' => [
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);

		$element->end_popover(); // popover end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER
		$element->add_control(
            '_ob_steroids_overlay_popover_border',
            [
                'label' => __( 'Border', 'ooohboi-steroids' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
				'frontend_available' => true, 
				'condition' => [
                    '_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
            ]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER ALL
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_steroids_overlay_borders', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'selector' => '{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before', 
				'condition' => [
                    '_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER RADIUS
		$element->add_responsive_control(
			'_ob_steroids_overlay_border_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_border' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);

		$element->end_popover(); // popover BORdER end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ
		$element->add_control(
            '_ob_steroids_overlay_popover_masq',
            [
                'label' => __( 'Overlay Mask', 'ooohboi-steroids' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
				],
            ]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ IMAGE
		$element->add_responsive_control(
			'_ob_steroids_overlay_mask_img',
			[
				'label' => __( 'Choose Image Mask', 'ooohboi-steroids' ),
				'description' => __( 'NOTE: Image Mask should be black-and-transparent SVG file! Anything that’s 100% black in the image mask with be completely visible, anything that’s transparent will be completely hidden.', 'ooohboi-steroids' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => '-webkit-mask-image: url("{{URL}}"); mask-image: url("{{URL}}"); -webkit-mask-mode: alpha; mask-mode: alpha;',
				],
				'condition' => [
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_masq' => 'yes', 
					'_ob_poopart_use' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ POSITION
		$element->add_responsive_control(
			'_ob_steroids_overlay_mask_position',
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => '-webkit-mask-position: {{VALUE}}; mask-position: {{VALUE}};',
				],
				'condition' => [
					'_ob_steroids_overlay_mask_img[url]!' => '',
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_masq' => 'yes', 
					'_ob_poopart_use' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE
		$element->add_responsive_control(
			'_ob_steroids_overlay_mask_size',
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => '-webkit-mask-size: {{VALUE}}; mask-size: {{VALUE}};',
				],
				'condition' => [
					'_ob_steroids_overlay_mask_img[url]!' => '',
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_masq' => 'yes', 
					'_ob_poopart_use' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE Custom
		$element->add_responsive_control(
			'_ob_steroids_overlay_mask_size_width', 
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
				],
				'condition' => [
					'_ob_steroids_overlay_mask_size' => [ 'initial' ],
					'_ob_steroids_overlay_mask_img[url]!' => '',
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_masq' => 'yes', 
					'_ob_poopart_use' => 'yes', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
							'_ob_steroids_overlay_mask_size' => [ 'initial' ], 
							'_ob_steroids_overlay_popover_masq' => 'yes', 
							'_ob_poopart_use' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
							'_ob_steroids_overlay_mask_size' => [ 'initial' ], 
							'_ob_steroids_overlay_popover_masq' => 'yes', 
							'_ob_poopart_use' => 'yes', 
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ REPEAT
		$element->add_responsive_control(
			'_ob_steroids_overlay_mask_repeat',
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => '-webkit-mask-repeat: {{VALUE}}; mask-repeat: {{VALUE}};',
				],
				'condition' => [
					'_ob_steroids_overlay_mask_img[url]!' => '',
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_overlay_popover_masq' => 'yes', 
					'_ob_poopart_use' => 'yes', 
				],
			]
		);

		$element->end_popover(); // popover MASQ end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER CLIP PATH since 1.6.4
        $element->add_control(
			'_ob_steroids_overlay_clip_path',
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => '{{VALUE}}',
				],
				'condition' => [
					'_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Z-INDeX
		$element->add_control(
			'_ob_steroids_overlay_z_index',
			[
				'label' => __( 'Z-Index', 'ooohboi-steroids' ),
				'type' => Controls_Manager::NUMBER,
				'min' => -9999,
				'default' => -1, 
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:before' => 'z-index: {{VALUE}};',
				],
				'label_block' => false, 
				'condition' => [
                    '_ob_steroids_overlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);

		$element->end_controls_tab(); // Overlay tab end

		// --------------------------------------------------------------------------------------------- START TAB Underlay ------------------------------- >>>>>

		$element->start_controls_tab(
            '_ob_steroids_tab_underlay',
            [
                'label' => __( 'Underlay', 'ooohboi-steroids' ),
            ]
		);

		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_steroids_underlay_background', 
                'selector' => '{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after', 
				'condition' => [
					'_ob_poopart_use' => 'yes', 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND OPACITY
        $element->add_control(
            '_ob_steroids_underlay_bg_opacity',
            [
                'label' => __( 'Opacity', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.7,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'opacity: {{SIZE}};',
				],
				'condition' => [
                    '_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL FILTERS
		$element->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => '_ob_steroids_underlay_bg_filters',
				'selector' => '{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after', 
				'condition' => [
                    '_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BLEND MODE
        $element->add_control(
            '_ob_steroids_underlay_bg_blend_mode',
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
                    '{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'mix-blend-mode: {{VALUE}}',
				], 
				'condition' => [
                    '_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER W H Y X Rot
		$element->add_control(
			'_ob_steroids_underlay_popover_whyxrot',
			[
				'label' => __( 'Position and Size', 'ooohboi-steroids' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'frontend_available' => true, 
				'condition' => [
                    '_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);

		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER WIDTH
		$element->add_responsive_control(
			'_ob_steroids_underlay_w',
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
					'size' => 100,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
							'_ob_steroids_underlay_w_alt' => '', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
							'_ob_steroids_underlay_w_alt' => '', 
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_steroids_underlay_w_alt' => '', 
					'_ob_steroids_underlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER WIDTH - Alternative
        $element->add_responsive_control(
            '_ob_steroids_underlay_w_alt',
            [
				'label' => __( 'Calc Width', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'width: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_steroids_underlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER HEIGHT
		$element->add_responsive_control(
			'_ob_steroids_underlay_h',
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
					'size' => 100,
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'condition' => [
							'_ob_steroids_underlay_h_alt' => '', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
							'_ob_steroids_underlay_h_alt' => '', 
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_steroids_underlay_h_alt' => '', 
					'_ob_steroids_underlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER HEIGHT - Alternative
        $element->add_responsive_control(
            '_ob_steroids_underlay_h_alt',
            [
				'label' => __( 'Calc Height', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 45% + 85px or 100% - 3em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'height: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_steroids_underlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET TOP
		$element->add_responsive_control(
			'_ob_steroids_underlay_y',
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
							'_ob_steroids_underlay_y_alt' => '', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
							'_ob_steroids_underlay_y_alt' => '', 
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_steroids_underlay_y_alt' => '', 
					'_ob_steroids_underlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET TOP - Alternative
        $element->add_responsive_control(
            '_ob_steroids_underlay_y_alt',
            [
				'label' => __( 'Calc Offset Top', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'top: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_steroids_underlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET LEFT
		$element->add_responsive_control(
			'_ob_steroids_underlay_x',
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
							'_ob_steroids_underlay_x_alt' => '', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'condition' => [
							'_ob_steroids_underlay_x_alt' => '', 
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_steroids_underlay_x_alt' => '', 
					'_ob_steroids_underlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER OFFSET LEFT - Alternative
        $element->add_responsive_control(
            '_ob_steroids_underlay_x_alt',
            [
				'label' => __( 'Calc Offset Left', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'left: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_steroids_underlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER ROTATION
		# NOTE : this is the hack. Elementor does not do well with 'deg' when speaking of responsiveness!
		$element->add_responsive_control(
			'_ob_steroids_underlay_rot',
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'transform: rotate({{SIZE}}deg);',
				],
				'condition' => [
					'_ob_steroids_underlay_popover_whyxrot' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);

		$element->end_popover(); // popover end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER
		$element->add_control(
			'_ob_steroids_underlay_popover_border',
			[
				'label' => __( 'Border', 'ooohboi-steroids' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'frontend_available' => true, 
				'condition' => [
                    '_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);

		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER ALL
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_steroids_underlay_borders', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'selector' => '{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after', 
				'condition' => [
                    '_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER RADIUS
		$element->add_responsive_control(
			'_ob_steroids_underlay_border_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    '_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_underlay_popover_border' => 'yes', 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);

		$element->end_popover(); // popover BORdER end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ - UNDERLAY ------------------->>
		$element->add_control(
            '_ob_steroids_underlay_popover_masq',
            [
                'label' => __( 'Underlay Mask', 'ooohboi-steroids' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
				],
            ]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ IMAGE
		$element->add_responsive_control(
			'_ob_steroids_underlay_mask_img',
			[
				'label' => __( 'Choose Image Mask', 'ooohboi-steroids' ),
				'description' => __( 'NOTE: Image Mask should be black-and-transparent SVG file! Anything that’s 100% black in the image mask with be completely visible, anything that’s transparent will be completely hidden.', 'ooohboi-steroids' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => '-webkit-mask-image: url("{{URL}}"); mask-image: url("{{URL}}"); -webkit-mask-mode: alpha; mask-mode: alpha;',
				],
				'condition' => [
					'_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_steroids_underlay_popover_masq' => 'yes', 
					'_ob_poopart_use' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ POSITION
		$element->add_responsive_control(
			'_ob_steroids_underlay_mask_position',
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => '-webkit-mask-position: {{VALUE}}; mask-position: {{VALUE}};',
				],
				'condition' => [
					'_ob_steroids_underlay_mask_img[url]!' => '',
					'_ob_steroids_underlay_popover_masq' => 'yes', 
					'_ob_poopart_use' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE
		$element->add_responsive_control(
			'_ob_steroids_underlay_mask_size',
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => '-webkit-mask-size: {{VALUE}}; mask-size: {{VALUE}};',
				],
				'condition' => [
					'_ob_steroids_underlay_mask_img[url]!' => '',
					'_ob_steroids_underlay_popover_masq' => 'yes', 
					'_ob_poopart_use' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE Custom
		$element->add_responsive_control(
			'_ob_steroids_underlay_mask_size_width', 
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
				],
				'condition' => [
					'_ob_steroids_underlay_mask_size' => [ 'initial' ],
					'_ob_steroids_underlay_mask_img[url]!' => '', 
					'_ob_steroids_underlay_popover_masq' => 'yes', 
					'_ob_poopart_use' => 'yes', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
							'_ob_steroids_underlay_mask_size' => [ 'initial' ], 
							'_ob_steroids_underlay_popover_masq' => 'yes', 
							'_ob_poopart_use' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
							'_ob_steroids_underlay_mask_size' => [ 'initial' ], 
							'_ob_steroids_underlay_popover_masq' => 'yes', 
							'_ob_poopart_use' => 'yes', 
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ REPEAT
		$element->add_responsive_control(
			'_ob_steroids_underlay_mask_repeat',
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => '-webkit-mask-repeat: {{VALUE}}; mask-repeat: {{VALUE}};',
				],
				'condition' => [
					'_ob_steroids_underlay_mask_img[url]!' => '', 
					'_ob_steroids_underlay_popover_masq' => 'yes', 
					'_ob_poopart_use' => 'yes', 
				],
			]
		);

		$element->end_popover(); // popover MASQ end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER CLIP PATH since 1.6.4
        $element->add_control(
			'_ob_steroids_underlay_clip_path',
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
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => '{{VALUE}}',
				],
				'condition' => [
					'_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Z-INDeX
		$element->add_control(
			'_ob_steroids_underlay_z_index',
			[
				'label' => __( 'Z-Index', 'ooohboi-steroids' ),
				'type' => Controls_Manager::NUMBER,
				'min' => -9999,
				'default' => -1, 
				'selectors' => [
					'{{WRAPPER}}.ob-has-background-overlay > .elementor-widget-container:after' => 'z-index: {{VALUE}};',
				],
				'label_block' => false, 
				'condition' => [
                    '_ob_steroids_underlay_background_background' => [ 'classic', 'gradient' ], 
					'_ob_poopart_use' => 'yes', 
                ],
			]
		);

		$element->end_controls_tab(); // Underlay tab end

		$element->end_controls_tabs(); // Underlay and Overlay tabs end

		$element->end_controls_section(); // END SECTION / PANEL

	}


    public static function poopart_remove_horizontal_scroller( \Elementor\Core\DocumentTypes\PageBase $page ) {

		// ------------------------------------------------------------------------- CONTROL: get rid of horizontal scroller
		$page->add_control(
			'_ob_steroids_no_horizontal_scroller',
			[
				'label' => __( 'Get rid of the Horizontal scroller?', 'ooohboi-steroids' ),
				'description' => __( 'OoohBoi POOOPART may cause Horizontal Scroller to show up. This is how you can remove it.', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'hidden',
				'default' => 'auto',
				'selectors' => [
					'html, body' => 'overflow-x: {{VALUE}};',
				],
				'label_block' => false,
				'separator' => 'before', 
			]
		);	

		// ------------------------------------------------------------------------- CONTROL: design your baseline grid
		$page->add_control(
			'_ob_steroids_use_baseline_grid',
			[
				'label' => __( 'Baseline Grid', 'ooohboi-steroids' ),
				'description' => __( 'Baseline grid helps you maintain accuracy and consistency', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'separator' => 'before', 
			]
		);
        $page->add_control(
            '_ob_steroids_baseline_grid_style',
            [
                'label' => __( 'Grid style', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'both', 
                'options' => [
                    'both' => __( 'Squares', 'ooohboi-steroids' ),
                    'vertical' => __( 'Vertical lines', 'ooohboi-steroids' ),
                    'horizontal' => __( 'Horizontal lines', 'ooohboi-steroids' ),
				],
				'condition' => [
                    '_ob_steroids_use_baseline_grid' => 'yes', 
                ],
            ]
        );
		$page->add_control(
			'_ob_steroids_baseline_grid_color',
			[
				'label' => __( 'Grid color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F9F9F9', 
				'condition' => [
                    '_ob_steroids_use_baseline_grid' => 'yes', 
                ],
			]
		);
		$page->add_control(
			'_ob_steroids_baseline_grid_size',
			[
				'label' => __( 'Grid size', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER, 
				'range' => [
					'px' => [
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 4,
				],
				'selectors' => [
					'html.elementor-html::before' => 'background-image: repeating-linear-gradient(to right, {{_ob_steroids_baseline_grid_color.VALUE}}, {{_ob_steroids_baseline_grid_color.VALUE}} 1px, transparent 1px, transparent), repeating-linear-gradient(to bottom, {{_ob_steroids_baseline_grid_color.VALUE}}, {{_ob_steroids_baseline_grid_color.VALUE}} 1px, transparent 1px, transparent);background-size: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_steroids_use_baseline_grid' => 'yes', 
					'_ob_steroids_baseline_grid_style' => [ 'both' ], 
                ],
			]
		);
		$page->add_control(
			'_ob_steroids_baseline_grid_size_vert',
			[
				'label' => __( 'Vertical Spacing', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER, 
				'range' => [
					'px' => [
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'html.elementor-html::before' => 'background-image: repeating-linear-gradient(90deg, {{_ob_steroids_baseline_grid_color.VALUE}}, {{_ob_steroids_baseline_grid_color.VALUE}} 1px, transparent 1px, transparent);background-size: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_steroids_use_baseline_grid' => 'yes', 
					'_ob_steroids_baseline_grid_style' => [ 'vertical' ], 
                ],
			]
		);
		$page->add_control(
			'_ob_steroids_baseline_grid_size_horiz',
			[
				'label' => __( 'Horizontal Spacing', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER, 
				'range' => [
					'px' => [
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'html.elementor-html::before' => 'background-image: repeating-linear-gradient(0deg, {{_ob_steroids_baseline_grid_color.VALUE}}, {{_ob_steroids_baseline_grid_color.VALUE}} 1px, transparent 1px, transparent);background-size: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_steroids_use_baseline_grid' => 'yes', 
					'_ob_steroids_baseline_grid_style' => [ 'horizontal' ], 
                ],
			]
		);

	}

}