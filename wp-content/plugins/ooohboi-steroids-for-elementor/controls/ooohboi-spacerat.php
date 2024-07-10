<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Spacerat
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.5.4
 */
class OoohBoi_SpaceRat {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.5.4
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/spacer/section_spacer/before_section_end',  [ __CLASS__, 'ooohboi_spacerat_controls' ], 10, 2 );
        add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

        /* should enqueue? */
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/spacerat-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_spacerat_use' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }


    public static function add_attributes( $element ) {

        if ( 'spacer' !== $element->get_name() ) return;
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;

		$settings = $element->get_settings_for_display();
		
		if ( isset( $settings[ '_ob_spacerat_use' ] ) && 'yes' === $settings[ '_ob_spacerat_use' ] ) {

            $element->add_render_attribute( '_wrapper', [
                'class' => 'ob-spacerat'
            ] );

        }

    }
    
	public static function ooohboi_spacerat_controls( $element, $args ) {

        $element->add_control(
            '_ob_spacerat',
            [
                'label' => 'S P A C E R A T',
                'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
            ]
		);

        // ------------------------------------------------------------------------- CONTROL: Yes 4 SpaceRat !
		$element->add_control(
			'_ob_spacerat_use',
			[
                'label' => __( 'Enable SpaceRat?', 'ooohboi-steroids' ), 
                'description' => __( 'Awesome gear for the pretty-much dull Spacer widget.', 'ooohboi-steroids' ), 
                'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
        );
        // --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
		$element->add_control(
			'_ob_spacerat_separator_q',
			[
				'type' => Controls_Manager::DIVIDER, 
				'condition' => [
					'_ob_spacerat_use' => 'yes', 
                ],
			]
		);
        // ------------------------------------------------------------------------- CONTROL: Link or No?
        $element->add_control(
			'_ob_spacerat_link_type',
			[
				'label' => __( 'Link', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'none',
				'options' => [
					'none' => __( 'None', 'ooohboi-steroids' ),
					'custom' => __( 'Custom URL', 'ooohboi-steroids' ),
                ],
                'condition' => [
					'_ob_spacerat_use' => 'yes',
				],
			]
        );
        
        // ------------------------------------------------------------------------- CONTROL: Link to...
		$element->add_control(
			'_ob_spacerat_link',
			[
				'label' => __( 'Link', 'ooohboi-steroids' ),
                'type' => Controls_Manager::URL, 
                'frontend_available' => true, 
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'ooohboi-steroids' ),
				'condition' => [
                    '_ob_spacerat_use' => 'yes',
					'_ob_spacerat_link_type' => 'custom',
				],
				'show_label' => false,
			]
        );
        // ------------------------------------------------------------------------- CONTROL: Cursor pointer
		$element->add_control(
			'_ob_spacerat_pointer',
			[
                'label' => __( 'Cursor Pointer', 'ooohboi-steroids' ), 
                'description' => __( 'Keep the default cursor or show pointer on Hover?', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SWITCHER,
                'default' => false, 
                'separator' => 'before', 
                'selectors' => [
                    '{{WRAPPER}}.ob-spacerat .elementor-widget-container' => 'cursor: pointer;', 
                ],
                'condition' => [
					'_ob_spacerat_use' => 'yes',
				],
			]
		);
        // --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
        $element->add_control(
            '_ob_spacerat_separator_x',
            [
                'type' => Controls_Manager::DIVIDER, 
                'condition' => [
                    '_ob_spacerat_use' => 'yes', 
                ],
            ]
        );
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ ------------------->>
		$element->add_control(
            '_ob_spacerat_popover_masq',
            [
                'label' => __( 'SpaceRat Mask', 'ooohboi-steroids' ), 
                'description' => __( 'NOTE: In order to see the effect you should add the Background to the Spacer widget first!', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'frontend_available' => true, 
                'condition' => [
					'_ob_spacerat_use' => 'yes',
				],
            ]
        );
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ IMAGE
		$element->add_responsive_control(
			'_ob_spacerat_mask_img',
			[
				'label' => __( 'Choose Image Mask', 'ooohboi-steroids' ),
				'description' => __( 'NOTE: Image Mask should be black-and-transparent SVG file! Anything that’s 100% black in the image mask with be completely visible, anything that’s transparent will be completely hidden.', 'ooohboi-steroids' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.ob-spacerat .elementor-widget-container' => '-webkit-mask-image: url("{{URL}}"); mask-image: url("{{URL}}"); -webkit-mask-mode: alpha; mask-mode: alpha;',
				],
				'condition' => [ 
                    '_ob_spacerat_use' => 'yes', 
					'_ob_spacerat_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ POSITION
		$element->add_responsive_control(
			'_ob_spacerat_mask_position',
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
					'{{WRAPPER}}.ob-spacerat .elementor-widget-container' => '-webkit-mask-position: {{VALUE}}; mask-position: {{VALUE}};',
				],
				'condition' => [ 
                    '_ob_spacerat_use' => 'yes', 
					'_ob_spacerat_mask_img[url]!' => '', 
					'_ob_spacerat_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE
		$element->add_responsive_control(
			'_ob_spacerat_mask_size',
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
					'{{WRAPPER}}.ob-spacerat .elementor-widget-container' => '-webkit-mask-size: {{VALUE}}; mask-size: {{VALUE}};',
				],
				'condition' => [ 
                    '_ob_spacerat_use' => 'yes', 
					'_ob_spacerat_mask_img[url]!' => '', 
					'_ob_spacerat_popover_masq' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ SIZE Custom
		$element->add_responsive_control(
			'_ob_spacerat_mask_size_width', 
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
					'{{WRAPPER}}.ob-spacerat .elementor-widget-container' => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
				],
				'condition' => [ 
                    '_ob_spacerat_use' => 'yes', 
					'_ob_spacerat_mask_size' => [ 'initial' ],
					'_ob_spacerat_mask_img[url]!' => '', 
					'_ob_spacerat_popover_masq' => 'yes', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							'{{WRAPPER}}.ob-spacerat .elementor-widget-container' => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
                            '_ob_spacerat_use' => 'yes', 
							'_ob_spacerat_mask_size' => [ 'initial' ], 
							'_ob_spacerat_popover_masq' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							'{{WRAPPER}}.ob-spacerat .elementor-widget-container' => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
                            '_ob_spacerat_use' => 'yes', 
							'_ob_spacerat_mask_size' => [ 'initial' ], 
							'_ob_spacerat_popover_masq' => 'yes', 
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER MASQ REPEAT
		$element->add_responsive_control(
			'_ob_spacerat_mask_repeat',
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
					'{{WRAPPER}}.ob-spacerat .elementor-widget-container' => '-webkit-mask-repeat: {{VALUE}}; mask-repeat: {{VALUE}};',
				],
				'condition' => [ 
                    '_ob_spacerat_use' => 'yes', 
					'_ob_spacerat_mask_img[url]!' => '', 
					'_ob_spacerat_popover_masq' => 'yes', 
				],
			]
		);

        $element->end_popover(); // spacerat MASQ end

        // --------------------------------------------------------------------------------------------- CONTROL Description - Faker !!!!!
		$element->add_control(
			'_ob_spacerat_fake_description',
			[
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => __( 'NOTE: In order to see the effect you should add the Background to the Spacer widget first!', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
				'condition' => [
					'_ob_spacerat_use' => 'yes', 
                ],
			]
        );
        // --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
		$element->add_control(
			'_ob_spacerat_separator_y',
			[
				'type' => Controls_Manager::DIVIDER, 
				'condition' => [
					'_ob_spacerat_use' => 'yes', 
                ],
			]
        );

        // ------------------------------------------------------------------------- CONTROL: Yes 4 Shadow !
		$element->add_control(
			'_ob_spacerat_add_shadow',
			[
                'label' => __( 'Add shadow?', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'_ob_spacerat_use' => 'yes', 
                ],
			]
        );
        // --------------------------------------------------------------------------------------------- CONTROL POPOVER Shadow ------------------->>
        $element->add_control(
            '_ob_spacerat_popover_shadow',
            [
                'label' => __( 'Define Shadow', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'frontend_available' => true, 
                'condition' => [
                    '_ob_spacerat_add_shadow' => 'yes', 
                    '_ob_spacerat_use' => 'yes', 
                ],
            ]
        );

        $element->start_popover();

        // ------------------------------------------------------------------------- CONTROL: Offset X
        $element->add_responsive_control(
            '_ob_spacerat_x',
            [
                'label' => __( 'Offset X', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 50,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => -5,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'size_units' => [ 'px', 'em', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}.ob-spacerat' => 'filter: drop-shadow({{SIZE}}{{UNIT}} {{_ob_spacerat_y.SIZE}}{{_ob_spacerat_y.UNIT}} {{_ob_spacerat_blur.SIZE}}{{_ob_spacerat_blur.UNIT}} {{_ob_spacerat_color.VALUE}});', 
                ],
                'condition' => [
                    '_ob_spacerat_add_shadow' => 'yes', 
                    '_ob_spacerat_use' => 'yes', 
					'_ob_spacerat_popover_shadow' => 'yes', 
                ],
            ]
        );
        // ------------------------------------------------------------------------- CONTROL: Offset Y
        $element->add_responsive_control(
            '_ob_spacerat_y',
            [
                'label' => __( 'Offset Y', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 50,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => -5,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'size_units' => [ 'px', 'em', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}.ob-spacerat' => 'filter: drop-shadow({{_ob_spacerat_x.SIZE}}{{_ob_spacerat_x.UNIT}} {{SIZE}}{{UNIT}} {{_ob_spacerat_blur.SIZE}}{{_ob_spacerat_blur.UNIT}} {{_ob_spacerat_color.VALUE}});', 
                ],
                'condition' => [
                    '_ob_spacerat_add_shadow' => 'yes', 
                    '_ob_spacerat_use' => 'yes', 
					'_ob_spacerat_popover_shadow' => 'yes', 
                ],
            ]
        );
        // ------------------------------------------------------------------------- CONTROL: Blur
        $element->add_responsive_control(
            '_ob_spacerat_blur',
            [
                'label' => __( 'Blur', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                        'step' => 1,
                    ],
                    'em' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'size_units' => [ 'px', 'em', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}.ob-spacerat' => 'filter: drop-shadow({{_ob_spacerat_x.SIZE}}{{_ob_spacerat_x.UNIT}} {{_ob_spacerat_y.SIZE}}{{_ob_spacerat_y.UNIT}} {{SIZE}}{{UNIT}} {{_ob_spacerat_color.VALUE}});', 
                ],
                'condition' => [
                    '_ob_spacerat_add_shadow' => 'yes', 
                    '_ob_spacerat_use' => 'yes', 
					'_ob_spacerat_popover_shadow' => 'yes', 
                ],
            ]
        );

        // ------------------------------------------------------------------------- CONTROL: COLOR
        $element->add_control(
            '_ob_spacerat_color',
            [
                'label' => __( 'Shadow Color', 'ooohboi-steroids' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#0000001C',
                'selectors' => [
                    '{{WRAPPER}}.ob-spacerat' => 'filter: drop-shadow({{_ob_spacerat_x.SIZE}}{{_ob_spacerat_x.UNIT}} {{_ob_spacerat_y.SIZE}}{{_ob_spacerat_y.UNIT}} {{_ob_spacerat_blur.SIZE}}{{_ob_spacerat_blur.UNIT}} {{VALUE}});',
                ],
                'condition' => [
                    '_ob_spacerat_add_shadow' => 'yes', 
                    '_ob_spacerat_use' => 'yes', 
					'_ob_spacerat_popover_shadow' => 'yes', 
                ],
            ]
        );

        $element->end_popover(); // popover shadow end

         // --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
		$element->add_control(
			'_ob_spacerat_separator_z',
			[
				'type' => Controls_Manager::DIVIDER, 
				'condition' => [
					'_ob_spacerat_use' => 'yes', 
                ],
			]
		);
        
        // --------------------------------------------------------------------------------------------- CONTROL CLIP PATH
        $element->add_control(
			'_ob_spacerat_clip_path_normal',
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
					'{{WRAPPER}}.ob-spacerat .elementor-widget-container' => '{{VALUE}}',
				],
				'condition' => [
					'_ob_spacerat_use' => 'yes', 
                ],
			]
		);

	}

}