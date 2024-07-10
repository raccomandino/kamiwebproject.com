<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Interactor Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.9.6
 */
class OoohBoi_Interactor {

    static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.9.6
	 *
	 * @access public
	 */
	public static function init() {

        /* containers */
		add_action( 'elementor/element/container/section_layout/after_section_end',  [ __CLASS__, 'add_section' ] ); 
        add_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'add_attributes' ] );
        /* widgets */
		add_action( 'elementor/element/common/_section_background/after_section_end',  [ __CLASS__, 'add_section' ] );
		add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

        /* should enqueue? */
		add_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

	public static function add_attributes( $element ) {

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;
		$settings = $element->get_settings_for_display();

		if ( isset( $settings[ '_ob_do_interactor' ] ) && 'yes' === $settings[ '_ob_do_interactor' ] ) {

			$element->add_render_attribute( '_wrapper', [
				'class' => 'ob-is-interactor'
			] );

		}

	}
    
    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/interactor.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_do_interactor' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'should_script_enqueue' ] );
            remove_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

    public static function add_section( $element ) {

		$element->start_controls_section(
            '_ob_steroids_interactor',
            [
                'label' => 'I N T E R A C T O R',
				'tab' => Controls_Manager::TAB_ADVANCED, 
            ]
		);

		// --------------------------------------------------------------------------------------------- CONTROL: Use Interactor
		$element->add_control(
			'_ob_do_interactor',
			[
                'label' => esc_html__( 'Enable Interactor?', 'ooohboi-steroids' ), 
				'separator' => 'after', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
				'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL: Interaction Type
        $element->add_control(
            '_ob_i_type',
            [
                'label' => esc_html__( 'Interaction Type', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'It is either the onClick or onHover the selected element/widget', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT, 
                'default' => 'mouseenter', 
                'options' => [
                    'click' => esc_html__( 'Mouse Click', 'ooohboi-steroids' ),
                    'mouseenter' => esc_html__( 'Mouse Over', 'ooohboi-steroids' ),
                ],
				'condition' => [
                    '_ob_do_interactor' => 'yes', 
                ],
                'frontend_available' => true,
            ]
        );
		// ------------------------------------------------------------------------- CONTROL: Force pointer
        $element->add_control(
			'_ob_i_target_pointer',
			[
				'label' => esc_html__( 'Pointer Type', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'Force pointer type on the trigger if needed', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'separator' => 'before', 
				'options' => [
					'default' => esc_html__( 'Default', 'ooohboi-steroids' ),
					'pointer' => esc_html__( 'Pointer', 'ooohboi-steroids' ),
                ],
				'selectors' => [
                    '{{WRAPPER}}' => 'cursor: {{VALUE}};', 
                ],
				'condition' => [
                    '_ob_do_interactor' => 'yes', 
                ], 
			]
		);

        // --------------------------------------------------------------------------------------------- CONTROL: Interactions
        $element->add_control(
            '_ob_i_property_descr',
            [
                'label' => esc_html__( 'Interactive Properties', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => esc_html__( 'Add as many as you like but avoid redundancy!', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
                'separator' => 'before', 
                'condition' => [
                    '_ob_do_interactor' => 'yes',
                ], 
            ]
        );
        
        $repeater = new Repeater();

		// -------------------------------------------------------------------------- CONTROL: Interaction Target
        $repeater->add_control(
            '_ob_i_target',
            [
                'label' => esc_html__( 'Interaction Target', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'Self or any other element on page', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT, 
                'default' => 'self', 
                'options' => [
                    'self' => esc_html__( 'Self', 'ooohboi-steroids' ),
                    'other' => esc_html__( 'Other element', 'ooohboi-steroids' ),
                ],
                'frontend_available' => true,
            ]
        );
		// --------------------------------------------------------------------------------------------- CONTROL: target element class or ID
        $repeater->add_control(
            '_ob_i_target_id_or_class',
            [
				'label' => esc_html__( 'Element class or ID', 'ooohboi-steroids' ),
				'description' => esc_html__( 'Enter the target element class or ID. IT MUST INCLUDE THE PREFIX! For instance; ".some-class-name" (for the custom class name) or "#some-id" (for the element ID)', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT, 
                'condition' => [ 
                    '_ob_i_target' => 'other' 
                ], 
                'frontend_available' => true, 
			]
		);
        // ------------------------------------------------------------------------- CONTROL Select animatable property
        $repeater->add_control(
            '_ob_i_property',
            [
                'label' => esc_html__( 'Animate', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ),
                    'width' => esc_html__( 'Width', 'ooohboi-steroids' ), 
                    'height' => esc_html__( 'Height', 'ooohboi-steroids' ), 
                    'translateX' => esc_html__( 'Translate X', 'ooohboi-steroids' ), 
                    'translateY' => esc_html__( 'Translate Y', 'ooohboi-steroids' ), 
                    'opacity' => esc_html__( 'Opacity', 'ooohboi-steroids' ), 
                    'color' => esc_html__( 'Color', 'ooohboi-steroids' ), 
                    'background-color' => esc_html__( 'Background color', 'ooohboi-steroids' ), 
                    'scale' => esc_html__( 'Scale', 'ooohboi-steroids' ), 
                    'rotate' => esc_html__( 'Rotate', 'ooohboi-steroids' ), 
                    'skewX' => esc_html__( 'SkewX', 'ooohboi-steroids' ), 
                    'skewY' => esc_html__( 'SkewY', 'ooohboi-steroids' ), 
                ], 
            ]
        ); 
		// --------------------------------------------------------------------------------------------- CONTROL duration
		$repeater->add_control(
			'_ob_i_duration',
			[
				'label' => esc_html__( 'Duration', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'Animation duration in seconds. 0.4 is deafult.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
				'min' => 0, 
				'default' => 0.4, 
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL delay
		$repeater->add_control(
			'_ob_i_delay',
			[
				'label' => esc_html__( 'Delay', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'Seconds to pause before the animation starts.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
				'min' => 0, 
				'default' => 0, 
			]
		);
        // ------------------------------------------------------------------------- CONTROL: Transforms easing
        $repeater->add_control(
            '_ob_i_easing',
            [
                'label' => esc_html__( 'Easing', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'frontend_available' => true, 
                'default' => 'ease-in-out',
                'separator' => 'before', 
                'options' => [
                    'ease' => esc_html__( 'Default', 'ooohboi-steroids' ), 
                    'ease-in' => esc_html__( 'Ease-in', 'ooohboi-steroids' ), 
                    'ease-out' => esc_html__( 'Ease-out', 'ooohboi-steroids' ), 
                    'ease-in-out' => esc_html__( 'Ease-in-out', 'ooohboi-steroids' ), 
                ],
            ]
        );
        // FROM ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
		$repeater->add_control(
			'_ob_i_prop_width_from',
			[
				'label' => esc_html__( 'From', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,  
				'range' => [
					'px' => [
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					], 
					'vw' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', '%', 'vw', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [ 
                    '_ob_i_property' => 'width' 
                ], 
			]
		);
		$repeater->add_control(
			'_ob_i_prop_height_from',
			[
				'label' => esc_html__( 'From', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,  
				'range' => [
					'px' => [
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					], 
					'vh' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', '%', 'vh', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [ 
                    '_ob_i_property' => 'height' 
                ], 
			]
		);
		$repeater->add_control(
			'_ob_i_prop_translateX_from',
			[
				'label' => esc_html__( 'From', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,  
				'range' => [
					'px' => [
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [ 
                    '_ob_i_property' => 'translateX' 
                ], 
			]
		);
		$repeater->add_control(
			'_ob_i_prop_translateY_from',
			[
				'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,  
				'range' => [
					'px' => [
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [ 
                    '_ob_i_property' => 'translateY' 
                ], 
			]
		);
        $repeater->add_control(
            '_ob_i_prop_opacity_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => 0, 
                'max' => 1, 
                'default' => 1, 
				'condition' => [
                    '_ob_i_property' => 'opacity', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_i_prop_color_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,  
                'condition' => [ 
                    '_ob_i_property' => 'color'
                ], 
            ]
        );
        $repeater->add_control(
            '_ob_i_prop_background-color_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,  
                'condition' => [ 
                    '_ob_i_property' => 'background-color'
                ], 
            ]
        );
        $repeater->add_control(
            '_ob_i_prop_scale_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => 0, 
                'default' => 1, 
				'condition' => [
                    '_ob_i_property' => 'scale', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_i_prop_rotate_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_i_property' => 'rotate', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_i_prop_skewX_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_i_property' => 'skewX', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_i_prop_skewY_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_i_property' => 'skewY', 
                ],
            ]
		);
        // TO ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
		$repeater->add_control(
			'_ob_i_prop_width_to',
			[
				'label' => esc_html__( 'To', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,  
				'range' => [
					'px' => [
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					], 
					'vw' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', '%', 'vw', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [ 
                    '_ob_i_property' => 'width' 
                ], 
			]
		);
		$repeater->add_control(
			'_ob_i_prop_height_to',
			[
				'label' => esc_html__( 'To', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,  
				'range' => [
					'px' => [
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					], 
					'vh' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', '%', 'vh', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [ 
                    '_ob_i_property' => 'height' 
                ], 
			]
		);
		$repeater->add_control(
			'_ob_i_prop_translateX_to',
			[
				'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,  
				'range' => [
					'px' => [
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [ 
                    '_ob_i_property' => 'translateX'
                ], 
			]
		);
		$repeater->add_control(
			'_ob_i_prop_translateY_to',
			[
				'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,  
				'range' => [
					'px' => [
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [ 
                    '_ob_i_property' => 'translateY' 
                ], 
			]
		);
        $repeater->add_control(
            '_ob_i_prop_opacity_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => 0, 
                'max' => 1, 
                'default' => 1, 
				'condition' => [
                    '_ob_i_property' => 'opacity', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_i_prop_color_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,  
                'condition' => [ 
                    '_ob_i_property' => 'color'
                ], 
            ]
        );
        $repeater->add_control(
            '_ob_i_prop_background-color_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,  
                'condition' => [ 
                    '_ob_i_property' => 'background-color'
                ], 
            ]
        );
        $repeater->add_control(
            '_ob_i_prop_scale_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => 0, 
                'default' => 1, 
				'condition' => [
                    '_ob_i_property' => 'scale', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_i_prop_tr_origin',
            [
                'label' => esc_html__( 'Transform Origin', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT, 
                'frontend_available' => true, 
                'default' => 'none', 
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'left top' => esc_html__( 'Left Top', 'ooohboi-steroids' ), 
                    'left center' => esc_html__( 'Left Center', 'ooohboi-steroids' ), 
                    'left bottom' => esc_html__( 'Left Bottom', 'ooohboi-steroids' ), 
                    'center top' => esc_html__( 'Center Top', 'ooohboi-steroids' ), 
                    'center center' => esc_html__( 'Center Center', 'ooohboi-steroids' ), 
                    'center bottom' => esc_html__( 'Center Bottom', 'ooohboi-steroids' ), 
                    'right top' => esc_html__( 'Right Top', 'ooohboi-steroids' ), 
                    'right center' => esc_html__( 'Right Center', 'ooohboi-steroids' ), 
                    'right bottom' => esc_html__( 'Right Bottom', 'ooohboi-steroids' ), 
                ], 
				'condition' => [
                    '_ob_i_property' => 'scale', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_i_prop_rotate_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_i_property' => 'rotate', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_i_prop_skewX_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_i_property' => 'skewX', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_i_prop_skewY_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_i_property' => 'skewY', 
                ],
            ]
		);
        // render REPEATER
		$element->add_control(
			'_ob_i_props_repeater',
			[
				'type' => Controls_Manager::REPEATER,
                'frontend_available' => true,  
				'fields' => $repeater->get_controls(),
				'title_field' => '<# print(_ob_i_property.slice(0,1).toUpperCase() + _ob_i_property.slice(1) + ` | ` + _ob_i_target + ` | ` + _ob_i_target_id_or_class) #>', 
				'default' => [
					[
						'_ob_i_property' => 'translateX', 
                        '_ob_i_prop_translateX_from' => 0, 
                        '_ob_i_prop_translateX_to' => 0, 
					],
				],
                'condition' => [
                    '_ob_do_interactor' => 'yes', 
                ], 
			]
		);

        $element->end_controls_section(); // END SECTION / PANEL

    }


}