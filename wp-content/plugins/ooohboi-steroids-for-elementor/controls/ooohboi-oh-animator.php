<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main OoohBoi Oh Animator
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.9.5
 */
class OoohBoi_Oh_Animator {

    static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.9.5
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/container/section_layout/after_section_end',  [ __CLASS__, 'manage_interface' ] );
        add_action( 'elementor/element/common/_section_style/after_section_end',  [ __CLASS__, 'manage_interface' ] );
        add_action( 'elementor/element/after_add_attributes', [ __CLASS__, 'add_attributes' ] ); 

		/* should enqueue? */
        add_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/ohanimator-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_animator' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'should_script_enqueue' ] ); 
            remove_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

	public static function add_attributes( Element_Base $element ) {

        if ( \Elementor\Plugin::$instance->experiments->get_active_features() ) return;

        $settings = $element->get_settings_for_display();
		$allow_animator = isset( $settings[ '_ob_animator' ] ) ? $settings[ '_ob_animator' ] : '';

        if ( 'yes' === $allow_animator ) $element->add_render_attribute( '_wrapper', 'class', 'ob-is-animated' ); 

    }

    public static function manage_interface( Element_Base $element ) {

        //  create panel
		$element->start_controls_section(
			'_ob_animator_section_title',
			[
				'label' => 'O H ! &nbsp; A N I M A T O R', 
				'tab' => Controls_Manager::TAB_ADVANCED, 
			]
        );

        // --------------------------------------------------------------------------------------------- CONTROL enable HOVERANIMATOR
        $element->add_control(
            '_ob_animator',
            [
                'label' => esc_html__( 'Enable ANIMATOR?', 'ooohboi-steroids' ), 
                'separator' => 'before', 
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
                'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true, 
            ]
        );

        // --------------------------------------------------------------------------------------------- CONTROL Description - Faker !!!!!
        $element->add_control(
            '_ob_animator_fake_description',
            [
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => sprintf(
                    __( 'If you are not familiar with the %s, %s and scroll triggered animations, it\'s good idea to learn some basics.', 'ooohboi-steroids' ), 
                    '<a href="https://greensock.com/docs/v3/GSAP" target="_blank">GSAP</a>',
                    '<a href="https://greensock.com/docs/v3/Plugins/ScrollTrigger" target="_blank">ScrollTrigger</a>'
				),
                'content_classes' => 'elementor-control-field-description', 
                'separator' => 'after', 
            ]
        );

        // --------------------------------------------------------------------------------------------- CONTROL select sibling instead
        if( 'column' !== $element->get_name() ) { 
            $element->add_control(
                '_ob_animator_el_is_sibling',
                [
                    'label' => esc_html__( 'Animate first child?', 'ooohboi-steroids' ), 
                    'description' => esc_html__( 'By default ".elementor-widget-container" will be animated. You can animate its first child element instead.', 'ooohboi-steroids' ), 
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
                    'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'frontend_available' => true,  
                    'condition' => [
                        '_ob_animator' => 'yes',
                    ], 
                ]
            );
        } 
        
        // --------------------------------------------------------------------------------------------- ScrollTrigger Description 
        $element->add_control(
            '_ob_animator_st_block_desc',
            [
                'label' => esc_html__( 'ScrollTrigger Settings', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => esc_html__( 'This is where you set you scroll triggered animation.', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
                'condition' => [
                    '_ob_animator' => 'yes',
                ], 
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Show markers
        $element->add_control(
            '_ob_animator_st_markers',
            [
                'label' => esc_html__( 'Show Markers?', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'See where the target element/widget start/end/trigger points are', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
                'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,  
                'condition' => [
                    '_ob_animator' => 'yes',
                ], 
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Scrub
        $element->add_control(
            '_ob_animator_st_scrub',
            [
                'label' => esc_html__( 'Scrub?', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'Controll the animation progress with the scrollbar so it acts like a scrubber', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
                'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,  
                'condition' => [
                    '_ob_animator' => 'yes',
                ], 
            ]
        );
		// --------------------------------------------------------------------------------------------- CONTROL Scrub Value
		$element->add_control(
			'_ob_animator_st_scrub_smooth',
			[
				'label' => esc_html__( 'Scrub smooth', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'Adds smoothing to scrub. Best between 1 and 5 (extreme already!)', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
				'min' => 0, 
                'max' => 10,
				'default' => 1, 
				'label_block' => false, 
				'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_scrub' => 'yes', 
                ],
			]
		);
        // --------------------------------------------------------------------------------------------- CONTROL Invalidate on refresh 
        $element->add_control(
            '_ob_animator_st_invalidate_on_refresh',
            [
                'label' => esc_html__( 'Invalidate on refresh?', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'This flushes out any internally-recorded starting values.', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
                'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,  
                'condition' => [
                    '_ob_animator' => 'yes',
                ], 
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Immediate Render
        $element->add_control(
            '_ob_animator_st_immediate_render',
            [
                'label' => esc_html__( 'Immediate render', 'ooohboi-steroids' ), 
                'description' => sprintf(
                    __( 'Please take a quick guide %s', 'ooohboi-steroids' ), 
                    '<a href="https://greensock.com/immediaterender/" target="_blank">here</a>'
                ), 
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
                'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,  
                'condition' => [
                    '_ob_animator' => 'yes',
                ], 
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Pin 
        $element->add_control(
            '_ob_animator_st_pin',
            [
                'label' => esc_html__( 'Pin?', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'Warning: don\'t animate the pinned element itself due to the unpredicted results! Rather animate elements inside the pinned element.', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
                'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,  
                'condition' => [
                    '_ob_animator' => 'yes',
                ], 
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Anticipate Pin 
        $element->add_control(
            '_ob_animator_st_anticipate_pin',
            [
                'label' => esc_html__( 'Anticipate Pin', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
                'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,  
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_pin' => 'yes', 
                ], 
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Pin Prevent Overlaps
        $element->add_control(
            '_ob_animator_st_pin_prevent_overlaps',
            [
                'label' => esc_html__( 'Prevent overlaps', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ooohboi-steroids' ),
                'label_off' => esc_html__( 'No', 'ooohboi-steroids' ),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,  
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_pin' => 'yes', 
                ], 
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Pin Spacing
        $element->add_control(
            '_ob_animator_st_pin_spacing',
            [
                'label' => esc_html__( 'Pin spacing', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SELECT,
                'default' => 'yes',
                'frontend_available' => true,  
                'options' => [
                    'yes' => esc_html__( 'Yes (true)', 'ooohboi-steroids' ), 
                    'no' => esc_html__( 'No (false)', 'ooohboi-steroids' ), 
                    'margin' => esc_html__( 'Margin', 'ooohboi-steroids' ), 
                ], 
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_pin' => 'yes', 
                ], 
            ]
        ); 

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER ScrollTrigger
		$element->add_control(
			'_ob_animator_st_controls',
			[
				'label' => esc_html__( 'ScrollTrigger Start and End', 'ooohboi-steroids' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'frontend_available' => true,  
				'condition' => [
                    '_ob_animator' => 'yes', 
                ],
			]
		);

		$element->start_popover();

        // --------------------------------------------------------------------------------------------- CONTROL Description 
        $element->add_control(
            '_ob_animator_st_start_trigger_description',
            [
                'label' => esc_html__( 'ScrollTrigger Start', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => esc_html__( 'Describes a place on the trigger and a place on the scroller that must meet in order to start the ScrollTrigger', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
                'separator' => 'after', 
				'condition' => [
                    '_ob_animator' => 'yes', 
                ],
            ]
        );

        // ------------------------------------------------------------------------- ST: Start Trigger
        $element->add_control(
            '_ob_animator_st_start_trigger_el',
            [
                'label' => esc_html__( 'Start - Trigger Element', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'top' => esc_html__( 'Top', 'ooohboi-steroids' ), 
                    'center' => esc_html__( 'Center', 'ooohboi-steroids' ), 
                    'bottom' => esc_html__( 'Bottom', 'ooohboi-steroids' ), 
                    'top_and_height' => esc_html__( 'Top + Self height', 'ooohboi-steroids' ), 
                ], 
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_controls' => 'yes', 
                ], 
            ]
        );
        // ------------------------------------------------------------------------- ST: Offset Trigger Element
		$element->add_control(
			'_ob_animator_st_start_trigger_el_off',
			[
				'label' => esc_html__( 'Start - Trigger Element Offset', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS, 
                'frontend_available' => true,  
                //'separator' => 'after', 
				'size_units' => [ 'px', '%', 'custom' ],
				'allowed_dimensions' => [ 'top' ],
				'placeholder' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
				],
				'default' 	 => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
				],
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_controls' => 'yes', 
                ], 
			]
		);
        // ------------------------------------------------------------------------- ST: Start Viewport
        $element->add_control(
            '_ob_animator_st_start_viewport',
            [
                'label' => esc_html__( 'Start - Viewport', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'top' => esc_html__( 'Top', 'ooohboi-steroids' ), 
                    'center' => esc_html__( 'Center', 'ooohboi-steroids' ), 
                    'bottom' => esc_html__( 'Bottom', 'ooohboi-steroids' ), 
                ], 
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_controls' => 'yes', 
                ], 
            ]
        );
        // ------------------------------------------------------------------------- ST: Offset
		$element->add_control(
			'_ob_animator_st_start_viewport_off',
			[
				'label' => esc_html__( 'Start - Viewport Offset', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS, 
                'frontend_available' => true,  
                'separator' => 'after', 
				'size_units' => [ 'px', '%', 'custom' ],
				'allowed_dimensions' => [ 'top' ],
				'placeholder' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
				],
				'default' 	 => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
				],
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_controls' => 'yes', 
                ], 
			]
		);
        // --------------------------------------------------------------------------------------------- CONTROL Description END
        $element->add_control(
            '_ob_animator_st_end_trigger_description',
            [
                'label' => esc_html__( 'ScrollTrigger End', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => esc_html__( 'Describes a place on the endTrigger (or trigger if one isn\'t defined) and a place on the scroller that must meet in order to end the scroll triggered animation', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
				'condition' => [
                    '_ob_animator' => 'yes', 
                ],
            ]
        );
        // ------------------------------------------------------------------------- ST: End Trigger
        $element->add_control(
            '_ob_animator_st_end_trigger_el',
            [
                'label' => esc_html__( 'End - Trigger Element', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'top' => esc_html__( 'Top', 'ooohboi-steroids' ), 
                    'center' => esc_html__( 'Center', 'ooohboi-steroids' ), 
                    'bottom' => esc_html__( 'Bottom', 'ooohboi-steroids' ), 
                ], 
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_controls' => 'yes', 
                ], 
            ]
        );
        // ------------------------------------------------------------------------- ST: Offset Trigger Element
		$element->add_control(
			'_ob_animator_st_end_trigger_el_off',
			[
				'label' => esc_html__( 'End - Trigger Element Offset', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS, 
                'frontend_available' => true,  
                'separator' => 'after', 
				'size_units' => [ 'px', '%', 'custom' ],
				'allowed_dimensions' => [ 'bottom' ],
				'placeholder' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
				],
				'default' 	 => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
				],
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_controls' => 'yes', 
                ], 
			]
		);
        // ------------------------------------------------------------------------- ST: Start Viewport
        $element->add_control(
            '_ob_animator_st_end_viewport',
            [
                'label' => esc_html__( 'End - Viewport', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'top' => esc_html__( 'Top', 'ooohboi-steroids' ), 
                    'center' => esc_html__( 'Center', 'ooohboi-steroids' ), 
                    'bottom' => esc_html__( 'Bottom', 'ooohboi-steroids' ), 
                ], 
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_controls' => 'yes', 
                ], 
            ]
        );
        // ------------------------------------------------------------------------- ST: Offset
		$element->add_control(
			'_ob_animator_st_end_viewport_off',
			[
				'label' => esc_html__( 'End - Viewport Offset', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS, 
                'frontend_available' => true,  
                'separator' => 'after', 
				'size_units' => [ 'px', '%', 'custom' ],
				'allowed_dimensions' => [ 'bottom' ],
				'placeholder' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
				],
				'default' 	 => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
				],
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_controls' => 'yes', 
                ], 
			]
		);
        // ------------------------------------------------------------------------- ST: End trigger - selector
        $element->add_control(
            '_ob_animator_st_end_trigger_class',
            [
                'label' => esc_html__( 'End trigger selector', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'Selector (element) whose position in the normal document flow is used for calculating where the ScrollTrigger ends. For instance: ".element-class-name" or "#element-unique-id"', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::TEXT, 
                'default' => '',
                'frontend_available' => true,  
                'condition' => [
                    '_ob_animator' => 'yes', 
                ], 
            ]
        );

        $element->end_popover(); // POPOVER ScrollTrigger end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER ToggleActions
		$element->add_control(
			'_ob_animator_st_toggle_actions',
			[
				'label' => esc_html__( 'Toggle Actions', 'ooohboi-steroids' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes', 
				'frontend_available' => true,  
				'condition' => [
                    '_ob_animator' => 'yes', 
                ],
			]
		);

		$element->start_popover();

        // --------------------------------------------------------------------------------------------- CONTROL Description - Faker !!!!!
        $element->add_control(
            '_ob_animator_st_ts_description',
            [
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => __( 'Determines how the linked animation is controlled at the 4 distinct toggle places - onEnter, onLeave, onEnterBack, and onLeaveBack, in that order. <br/>
                For example:<br/>
                <pre>play pause resume reset</pre><br/>
                will play the animation when entering, pause it when leaving, resume it when entering again backwards, and reset (rewind back to the beginning) when scrolling all the way back past the beginning.', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
            ]
        );

        // ------------------------------------------------------------------------- CONTROL onEnter
        $element->add_control(
            '_ob_animator_st_ta_onenter',
            [
                'label' => esc_html__( 'Action onEnter', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'default' => 'restart',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'play' => esc_html__( 'Play', 'ooohboi-steroids' ), 
                    'pause' => esc_html__( 'Pause', 'ooohboi-steroids' ), 
                    'resume' => esc_html__( 'Resume', 'ooohboi-steroids' ), 
                    'reset' => esc_html__( 'Reset', 'ooohboi-steroids' ), 
                    'restart' => esc_html__( 'Restart', 'ooohboi-steroids' ), 
                    'complete' => esc_html__( 'Complete', 'ooohboi-steroids' ), 
                    'reverse' => esc_html__( 'Reverse', 'ooohboi-steroids' ), 
                ], 
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_toggle_actions' => 'yes', 
                ], 
            ]
        );
        // ------------------------------------------------------------------------- CONTROL onLeave
        $element->add_control(
            '_ob_animator_st_ta_onleave',
            [
                'label' => esc_html__( 'Action onLeave', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'default' => 'none',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'play' => esc_html__( 'Play', 'ooohboi-steroids' ), 
                    'pause' => esc_html__( 'Pause', 'ooohboi-steroids' ), 
                    'resume' => esc_html__( 'Resume', 'ooohboi-steroids' ), 
                    'reset' => esc_html__( 'Reset', 'ooohboi-steroids' ), 
                    'restart' => esc_html__( 'Restart', 'ooohboi-steroids' ), 
                    'complete' => esc_html__( 'Complete', 'ooohboi-steroids' ), 
                    'reverse' => esc_html__( 'Reverse', 'ooohboi-steroids' ), 
                ], 
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_toggle_actions' => 'yes', 
                ], 
            ]
        );
        // ------------------------------------------------------------------------- CONTROL onEnterBack
        $element->add_control(
            '_ob_animator_st_ta_onenterback',
            [
                'label' => esc_html__( 'Action onEnterBack', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'default' => 'none',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'play' => esc_html__( 'Play', 'ooohboi-steroids' ), 
                    'pause' => esc_html__( 'Pause', 'ooohboi-steroids' ), 
                    'resume' => esc_html__( 'Resume', 'ooohboi-steroids' ), 
                    'reset' => esc_html__( 'Reset', 'ooohboi-steroids' ), 
                    'restart' => esc_html__( 'Restart', 'ooohboi-steroids' ), 
                    'complete' => esc_html__( 'Complete', 'ooohboi-steroids' ), 
                    'reverse' => esc_html__( 'Reverse', 'ooohboi-steroids' ), 
                ], 
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_toggle_actions' => 'yes', 
                ], 
            ]
        );
        // ------------------------------------------------------------------------- CONTROL onLeaveBack
        $element->add_control(
            '_ob_animator_st_ta_onleaveback',
            [
                'label' => esc_html__( 'Action onLeaveBack', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'default' => 'reverse',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'play' => esc_html__( 'Play', 'ooohboi-steroids' ), 
                    'pause' => esc_html__( 'Pause', 'ooohboi-steroids' ), 
                    'resume' => esc_html__( 'Resume', 'ooohboi-steroids' ), 
                    'reset' => esc_html__( 'Reset', 'ooohboi-steroids' ), 
                    'restart' => esc_html__( 'Restart', 'ooohboi-steroids' ), 
                    'complete' => esc_html__( 'Complete', 'ooohboi-steroids' ), 
                    'reverse' => esc_html__( 'Reverse', 'ooohboi-steroids' ), 
                ], 
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_toggle_actions' => 'yes', 
                ], 
            ]
        );

        $element->end_popover(); // POPOVER ToggleActions end

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER ToggleActions
		$element->add_control(
			'_ob_animator_st_toggle_class_popover',
			[
				'label' => esc_html__( 'Toggle Class', 'ooohboi-steroids' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'frontend_available' => true,  
				'condition' => [
                    '_ob_animator' => 'yes', 
                ],
			]
		);

		$element->start_popover(); // Toggle class

        // --------------------------------------------------------------------------------------------- CONTROL Toggle class
        $element->add_control(
            '_ob_animator_st_toggle_class_desc',
            [
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => esc_html__( 'Adds/removes a class to an element (or multiple elements) when the ScrollTrigger toggles active/inactive. <br/>
                NOTE: It can only be the name of the class to add to the trigger element - the String! The Object is currently not supported!', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
            ]
        );

        $element->add_control(
            '_ob_animator_st_toggle_class',
            [
                'label' => esc_html__( 'Toggle class name', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::TEXT, 
                'default' => '',
                'frontend_available' => true,  
                'condition' => [
                    '_ob_animator' => 'yes', 
                    '_ob_animator_st_toggle_class_popover' => 'yes', 
                ], 
            ]
        );

        $element->end_popover(); // POPOVER Toggle class

        // --------------------------------------------------------------------------------------------- Animatable properties Description 
        $element->add_control(
            '_ob_animator_property_descr',
            [
                'label' => esc_html__( 'Animatable Properties', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => esc_html__( 'Add as many as you like but avoid redundancy!', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
                'separator' => 'before', 
                'condition' => [
                    '_ob_animator' => 'yes',
                ], 
            ]
        );

        $repeater = new Repeater();

        // ------------------------------------------------------------------------- CONTROL Select animatable property
        $repeater->add_control(
            '_ob_animator_property',
            [
                'label' => esc_html__( 'Animate', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'x' => esc_html__( 'X', 'ooohboi-steroids' ), 
                    'y' => esc_html__( 'Y', 'ooohboi-steroids' ), 
                    'opacity' => esc_html__( 'Opacity', 'ooohboi-steroids' ), 
                    'color' => esc_html__( 'Color', 'ooohboi-steroids' ), 
                    'backgroundColor' => esc_html__( 'Background color', 'ooohboi-steroids' ), 
                    'scale' => esc_html__( 'Scale', 'ooohboi-steroids' ), 
                    'rotate' => esc_html__( 'Rotate', 'ooohboi-steroids' ), 
                    'skewX' => esc_html__( 'SkewX', 'ooohboi-steroids' ), 
                    'skewY' => esc_html__( 'SkewY', 'ooohboi-steroids' ), 
                    'clipPath' => esc_html__( 'Clip path', 'ooohboi-steroids' ), 
                ], 
            ]
        ); 
        // FROM ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
		$repeater->add_control(
			'_ob_prop_x_from',
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
                    '_ob_animator_property' => 'x' 
                ], 
			]
		);
		$repeater->add_control(
			'_ob_prop_y_from',
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
                    '_ob_animator_property' => 'y' 
                ], 
			]
		);
        $repeater->add_control(
            '_ob_prop_opacity_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => 0, 
                'max' => 1, 
                'default' => 1, 
				'condition' => [
                    '_ob_animator_property' => 'opacity', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_color_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,  
                'condition' => [ 
                    '_ob_animator_property' => 'color'
                ], 
            ]
        );
        $repeater->add_control(
            '_ob_prop_backgroundColor_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,  
                'condition' => [ 
                    '_ob_animator_property' => 'backgroundColor'
                ], 
            ]
        );
        $repeater->add_control(
            '_ob_prop_scale_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => 0, 
                'default' => 1, 
				'condition' => [
                    '_ob_animator_property' => 'scale', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_rotate_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_animator_property' => 'rotate', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_skewX_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_animator_property' => 'skewX', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_skewY_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_animator_property' => 'skewY', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_clipPath_from',
            [
                'label' => esc_html__( 'From', 'ooohboi-steroids' ), 
				'description' => sprintf(
                    __( 'Enter the full clip-path property! See the copy-paste examples at %sClippy%s', 'ooohboi-steroids' ),
                    '<a href="https://bennettfeely.com/clippy/" target="_blank">',
                    '</a>'
				),
                'type' => Controls_Manager::TEXTAREA, 
                'frontend_available' => true,  
                'default' => '',
                'rows' => 3,  
				'condition' => [
                    '_ob_animator_property' => 'clipPath', 
                ],
            ]
		);
        // TO ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
		$repeater->add_control(
			'_ob_prop_x_to',
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
                    '_ob_animator_property' => 'x'
                ], 
			]
		);
		$repeater->add_control(
			'_ob_prop_y_to',
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
                    '_ob_animator_property' => 'y' 
                ], 
			]
		);
        $repeater->add_control(
            '_ob_prop_opacity_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => 0, 
                'max' => 1, 
                'default' => 1, 
				'condition' => [
                    '_ob_animator_property' => 'opacity', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_color_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,  
                'condition' => [ 
                    '_ob_animator_property' => 'color'
                ], 
            ]
        );
        $repeater->add_control(
            '_ob_prop_backgroundColor_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,  
                'condition' => [ 
                    '_ob_animator_property' => 'backgroundColor'
                ], 
            ]
        );
        $repeater->add_control(
            '_ob_prop_scale_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => 0, 
                'default' => 1, 
				'condition' => [
                    '_ob_animator_property' => 'scale', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_tr_origin',
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
                    '_ob_animator_property' => 'scale', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_rotate_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_animator_property' => 'rotate', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_skewX_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_animator_property' => 'skewX', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_skewY_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
                'min' => -360, 
                'max' => 360,
                'default' => 0, 
				'condition' => [
                    '_ob_animator_property' => 'skewY', 
                ],
            ]
		);
        $repeater->add_control(
            '_ob_prop_clipPath_to',
            [
                'label' => esc_html__( 'To', 'ooohboi-steroids' ),
                'type' => Controls_Manager::TEXTAREA, 
                'frontend_available' => true,  
                'default' => '', 
                'rows' => 3, 
				'condition' => [
                    '_ob_animator_property' => 'clipPath', 
                ],
            ]
		);
        // render REPEATER
		$element->add_control(
			'_ob_animator_props_repeater',
			[
				//'label' => esc_html__( 'Animatable Properties', 'ooohboi-steroids' ),
				'type' => Controls_Manager::REPEATER,
                'frontend_available' => true,  
				'fields' => $repeater->get_controls(),
				'title_field' => '<# print(_ob_animator_property.slice(0,1).toUpperCase() + _ob_animator_property.slice(1)) #>', 
				'default' => [
					[
						'_ob_animator_property' => 'x', 
                        '_ob_prop_x_from' => 0, 
                        '_ob_prop_x_to' => 0, 
					],
				],
                'condition' => [
                    '_ob_animator' => 'yes', 
                ], 
			]
		);

        // --------------------------------------------------------------------------------------------- Animatable properties Description 
        $element->add_control(
            '_ob_animator_easing_timing',
            [
                'label' => esc_html__( 'Easing and Timing', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::RAW_HTML, 
                'raw' => esc_html__( 'These will make any animation look more natural', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
                'separator' => 'before', 
                'condition' => [
                    '_ob_animator' => 'yes',
                ], 
            ]
        );
        // ------------------------------------------------------------------------- CONTROL Easing
        $element->add_control(
            '_ob_animator_easing',
            [
                'label' => esc_html__( 'Ease', 'ooohboi-steroids' ),
                'description' => esc_html__( 'It will automatically become NONE with the Scrub option enabled!', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'frontend_available' => true,  
                'options' => [
                    'none' => esc_html__( 'None', 'ooohboi-steroids' ), 
                    'power1.in' => esc_html__( 'Power1.in', 'ooohboi-steroids' ), 
                    'power1.out' => esc_html__( 'Power1.out', 'ooohboi-steroids' ), 
                    'power1.inOut' => esc_html__( 'Power1.inOut', 'ooohboi-steroids' ), 
                    'power2.in' => esc_html__( 'Power2.in', 'ooohboi-steroids' ), 
                    'power2.out' => esc_html__( 'Power2.out', 'ooohboi-steroids' ), 
                    'power2.inOut' => esc_html__( 'Power2.inOut', 'ooohboi-steroids' ), 
                    'power3.in' => esc_html__( 'Power3.in', 'ooohboi-steroids' ), 
                    'power3.out' => esc_html__( 'Power3.out', 'ooohboi-steroids' ), 
                    'power3.inOut' => esc_html__( 'Power3.inOut', 'ooohboi-steroids' ), 
                    'power4.in' => esc_html__( 'Power4.in', 'ooohboi-steroids' ), 
                    'power4.out' => esc_html__( 'Power4.out', 'ooohboi-steroids' ), 
                    'power4.inOut' => esc_html__( 'Power4.inOut', 'ooohboi-steroids' ), 
                    'circ.in' => esc_html__( 'Circ.in', 'ooohboi-steroids' ), 
                    'circ.out' => esc_html__( 'Circ.out', 'ooohboi-steroids' ), 
                    'circ.inOut' => esc_html__( 'Circ.inOut', 'ooohboi-steroids' ), 
                    'expo.in' => esc_html__( 'Expo.in', 'ooohboi-steroids' ), 
                    'expo.out' => esc_html__( 'Expo.out', 'ooohboi-steroids' ), 
                    'expo.inOut' => esc_html__( 'Expo.inOut', 'ooohboi-steroids' ), 
                    'sine.in' => esc_html__( 'Sine.in', 'ooohboi-steroids' ), 
                    'sine.out' => esc_html__( 'Sine.out', 'ooohboi-steroids' ), 
                    'sine.inOut' => esc_html__( 'Sine.inOut', 'ooohboi-steroids' ), 
                    'elastic' => esc_html__( 'Elastic', 'ooohboi-steroids' ), 
                    'back' => esc_html__( 'Back', 'ooohboi-steroids' ), 
                    'bounce' => esc_html__( 'Bounce', 'ooohboi-steroids' ), 
                ], 
                'condition' => [
                    '_ob_animator' => 'yes',
                ], 
            ]
        );
		// --------------------------------------------------------------------------------------------- CONTROL duration
		$element->add_control(
			'_ob_animator_duration',
			[
				'label' => esc_html__( 'Duration', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'Animation duration in seconds. 0.4 is deafult.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
				'min' => 0, 
				'default' => 0.4, 
				'label_block' => false, 
				'condition' => [
                    '_ob_animator' => 'yes', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL delay
		$element->add_control(
			'_ob_animator_delay',
			[
				'label' => esc_html__( 'Delay', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'Seconds to pause before the animation starts.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::NUMBER, 
                'frontend_available' => true,  
				'min' => 0, 
				'default' => 0, 
				'label_block' => false, 
				'condition' => [
                    '_ob_animator' => 'yes', 
                ],
			]
		);

        $element->end_controls_section(); // END SECTION / PANEL

    }

}