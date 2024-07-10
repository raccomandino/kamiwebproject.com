<?php
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Overlaiz
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class OoohBoi_Overlaiz {

	/**
	 * Initialize 
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/section/section_background_overlay/before_section_end',  [ __CLASS__, 'ooohboi_overlaiz_get_controls' ], 10, 2 );
		add_action( 'elementor/element/column/section_background_overlay/before_section_end',  [ __CLASS__, 'ooohboi_overlaiz_get_controls' ], 10, 2 ); 
		add_action( 'elementor/element/container/section_background_overlay/before_section_end',  [ __CLASS__, 'ooohboi_overlaiz_get_controls' ], 10, 2 );

	}
	
	public static function ooohboi_overlaiz_get_controls( $element, $args ) {

		// selector based on the current element
		$selector = '{{WRAPPER}} > .elementor-column-wrap > .elementor-background-overlay, {{WRAPPER}} > .elementor-widget-wrap > .elementor-background-overlay';
		if( 'section' == $element->get_name() ) $selector = '{{WRAPPER}} > .elementor-background-overlay'; 
		elseif( 'container' == $element->get_name() ) $selector = '{{WRAPPER}}.e-con::before'; 


		$element->add_control(
			'_ob_overlaiz_plugin_title',
			[
				'label' => 'O V E R L A I Z', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
				'condition' => [
                    'background_overlay_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Use Overlaiz
		$element->add_control(
			'_ob_overlaiz_use_it',
			[
                'label' => __( 'Enable Overlaiz?', 'ooohboi-steroids' ), 
				'separator' => 'after', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
                    'background_overlay_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: background overlay width
		$element->add_responsive_control(
			'_ob_overlaiz_width',
			[
				'label' => __( 'Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					],
					'%' => [
						'min' => 5,
						'max' => 500,
					],
				],
				'selectors' => [
					$selector => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_width_alt' => '', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							$selector => 'width: {{SIZE}}{{UNIT}};',
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							$selector => 'width: {{SIZE}}{{UNIT}};',
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL: background overlay width - Alternative
        $element->add_responsive_control(
            '_ob_overlaiz_width_alt',
            [
				'label' => __( 'Calc Width', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector => 'width: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: background overlay height
		$element->add_responsive_control(
			'_ob_overlaiz_height',
			[
				'label' => __( 'Height', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					],
					'%' => [
						'min' => 5,
						'max' => 500,
					],
				],
				'selectors' => [
					$selector => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_height_alt' => '', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							$selector => 'height: {{SIZE}}{{UNIT}};',
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							$selector => 'height: {{SIZE}}{{UNIT}};',
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL: background overlay height - Alternative
        $element->add_responsive_control(
            '_ob_overlaiz_height_alt',
            [
				'label' => __( 'Calc Height', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector => 'height: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: move background overlay - X
		$element->add_responsive_control(
			'_ob_overlaiz_move_bg_x',
			[
				'label' => __( 'Position - X', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					$selector => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_move_bg_x_alt' => '', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							$selector => 'left: {{SIZE}}{{UNIT}};',
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							$selector => 'left: {{SIZE}}{{UNIT}};',
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL: move background overlay - X - Alternative
        $element->add_responsive_control(
            '_ob_overlaiz_move_bg_x_alt',
            [
				'label' => __( 'Calc Position - X', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector => 'left: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Magic Overlays - move background overlay - Y
		$element->add_responsive_control(
			'_ob_overlaiz_move_bg_y',
			[
				'label' => __( 'Position - Y', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					$selector => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_move_bg_y_alt' => '', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							$selector => 'top: {{SIZE}}{{UNIT}};',
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							$selector => 'top: {{SIZE}}{{UNIT}};',
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL: move background overlay - Y - Alternative
		$element->add_responsive_control(
			'_ob_overlaiz_move_bg_y_alt',
			[
				'label' => __( 'Calc Position - Y', 'ooohboi-steroids' ),
				'description' => __( 'Enter CSS calc value only! Like: 100% - 50px or 100% + 2em', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					$selector => 'top: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL: ROTATION
		$element->add_responsive_control(
			'_ob_overlaiz_rot',
			[
				'label' => __( 'Rotation', 'ooohboi-steroids' ),
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
					$selector => 'transform: rotate({{SIZE}}deg);',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER
		$element->add_control(
			'_ob_overlaiz_popover_border',
			[
				'label' => __( 'Border', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
                    'background_overlay_background' => [ 'classic', 'gradient' ],
				],
			]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER ALL
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_overlaiz_borders', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'selector' => $selector, 
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
                    'background_overlay_background' => [ 'classic', 'gradient' ], 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER RADIUS
		$element->add_responsive_control(
			'_ob_overlaiz_border_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS, 
				'size_units' => [ 'px', '%', 'custom' ], 
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					$selector  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
                    'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_popover_border' => 'yes', 
				],
			]
		);

		$element->end_popover(); // popover BORdER end

		// --------------------------------------------------------------------------------------------- CONTROL Box Shadow
		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_ob_overlaiz_shadow', 
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
					'_ob_overlaiz_use_it' => 'yes', 
                    'background_overlay_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Clip path

		$element->add_control(
			'_ob_overlaiz_clip_path_popover',
			[
				'label' => __( 'Clip path', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes', 
				'frontend_available' => true, 
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
                    'background_overlay_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);

		$element->start_popover();

		$element->add_control(
			'_ob_overlaiz_clip_path',
			[				
				'description' => sprintf(
                    __( 'Enter the full clip-path property! See the copy-paste examples at %sClippy%s', 'ooohboi-steroids' ),
                    '<a href="https://bennettfeely.com/clippy/" target="_blank">',
                    '</a>'
				),
				'default' => '', 
				'type' => Controls_Manager::TEXTAREA, 
				'rows' => 3, 
				'selectors' => [
					$selector => '{{VALUE}}',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
                    'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_clip_path_popover' => 'yes', 
                ],
			]
		);

		$element->end_popover(); // popover Clip path end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ ------------------->>
		$element->add_control(
            '_ob_overlaiz_popover_masq',
            [
                'label' => __( 'Mask', 'ooohboi-steroids' ), 
                'description' => __( 'NOTE: In order to see the effect you should add the Background to the Spacer widget first!', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'frontend_available' => true, 
                'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
					
                ],
            ]
        );
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ IMAGE
		$element->add_responsive_control(
			'_ob_overlaiz_mask_img',
			[
				'label' => __( 'Choose Image Mask', 'ooohboi-steroids' ),
				'description' => __( 'NOTE: Image Mask should be black-and-transparent SVG file! Anything that’s 100% black in the image mask with be completely visible, anything that’s transparent will be completely hidden.', 'ooohboi-steroids' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'selectors' => [
					$selector => '-webkit-mask-image: url("{{URL}}"); mask-image: url("{{URL}}"); -webkit-mask-mode: alpha; mask-mode: alpha;',
				],
				'condition' => [ 
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ POSITION
		$element->add_responsive_control(
			'_ob_overlaiz_mask_position',
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
					$selector => '-webkit-mask-position: {{VALUE}}; mask-position: {{VALUE}};',
				],
				'condition' => [ 
                    '_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_mask_img[url]!' => '', 
					'_ob_overlaiz_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE
		$element->add_responsive_control(
			'_ob_overlaiz_mask_size',
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
					$selector => '-webkit-mask-size: {{VALUE}}; mask-size: {{VALUE}};',
				],
				'condition' => [ 
                    '_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_mask_img[url]!' => '', 
					'_ob_overlaiz_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE Custom
		$element->add_responsive_control(
			'_ob_overlaiz_mask_size_width', 
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
					$selector => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
				],
				'condition' => [ 
                    '_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_mask_size' => [ 'initial' ],
					'_ob_overlaiz_mask_img[url]!' => '', 
					'_ob_overlaiz_popover_masq' => 'yes', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							$selector => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
							'_ob_overlaiz_use_it' => 'yes', 
							'background_overlay_background' => [ 'classic', 'gradient' ], 
							'_ob_overlaiz_mask_size' => [ 'initial' ], 
							'_ob_overlaiz_popover_masq' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							$selector => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
							'_ob_overlaiz_use_it' => 'yes', 
							'background_overlay_background' => [ 'classic', 'gradient' ], 
							'_ob_overlaiz_mask_size' => [ 'initial' ], 
							'_ob_overlaiz_popover_masq' => 'yes', 
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ REPEAT
		$element->add_responsive_control(
			'_ob_overlaiz_mask_repeat',
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
					$selector => '-webkit-mask-repeat: {{VALUE}}; mask-repeat: {{VALUE}};',
				],
				'condition' => [ 
					'_ob_overlaiz_use_it' => 'yes', 
					'background_overlay_background' => [ 'classic', 'gradient' ], 
					'_ob_overlaiz_mask_img[url]!' => '',
					'_ob_overlaiz_popover_masq' => 'yes', 
				],
			]
		);

        $element->end_popover(); // overlaiz MASQ end

		// --------------------------------------------------------------------------------------------- CONTROL Z-INDeX
		$element->add_control(
			'_ob_overlaiz_z_index',
			[
				'label' => __( 'Z-Index', 'ooohboi-steroids' ),
				'type' => Controls_Manager::NUMBER, 
				'separator' => 'before', 
				'min' => -9999,
				'selectors' => [
					$selector => 'z-index: {{VALUE}};',
				],
				'condition' => [
					'_ob_overlaiz_use_it' => 'yes', 
                    'background_overlay_background' => [ 'classic', 'gradient' ], 
                ],
			]
		);

	}

}