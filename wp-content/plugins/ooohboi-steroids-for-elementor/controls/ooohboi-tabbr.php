<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Tabbr
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.9.9
 */
class OoohBoi_Tabbr {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.9.9
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/tabs/section_tabs_style/before_section_end',  [ __CLASS__, 'ooohboi_tabbr_opts' ], 10, 1 ); 
        add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

        /* should enqueue? */
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/tabbr-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_use_tabbr' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/element/tabs/section_tabs_style/before_section_end', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

    public static function add_attributes( $element ) {

        if( ! in_array( $element->get_name(), [ 'tabs' ] ) ) return;
		$settings = $element->get_settings();
        $is_tabbr = isset( $settings[ '_ob_use_tabbr' ] ) ? $settings[ '_ob_use_tabbr' ] : '';
        
        if( 'yes' === $settings[ '_ob_use_tabbr' ] ) 
            $element->add_render_attribute( '_wrapper', 'class', 'ob-use-tabbr' );

    }
    
	public static function ooohboi_tabbr_opts( Element_Base $element ) {

        $element->add_control(
            '_ob_tabbr_title',
            [
                'label' => 'T A B B R',
                'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
            ]
		);

		// --------------------------------------------------------------------------------------------- CONTROL: Use Tabbr
		$element->add_control(
			'_ob_use_tabbr',
			[
                'label' => esc_html__( 'Enable Tabbr?', 'ooohboi-steroids' ), 
				'separator' => 'after', 
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
            '_ob_use_tabbr_fake_descr_0',
            [
                'type' => Controls_Manager::RAW_HTML, 
                'label' => __( 'Tabs general', 'ooohboi-steroids' ),
                'raw' => __( 'General settings for all tabs', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
            ]
        );

        // ------------------------------------------------------------------------- CONTROL: Tabs position
        $element->add_responsive_control(
            '_ob_tabbr_tabs_position',
            [
                'label' => __( 'Tabs position', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'row', 
                'options' => [
					'column' => __( 'Top or Bottom', 'ooohboi-steroids' ), 
                    'row' => __( 'Left or Right', 'ooohboi-steroids' ),
                ],
                'selectors' => [
                    '{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs' => 'flex-direction: {{VALUE}}{{_ob_tabbr_tabs_order.VALUE}};', 
                ],
                'condition' => [
                    '_ob_use_tabbr' => 'yes', 
                ],
				
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [ 
						'selectors' => [
							'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs' => 'flex-direction: {{_ob_tabbr_tabs_position.VALUE}}{{_ob_tabbr_tabs_order.VALUE}};',
						],
						'condition' => [
							'_ob_use_tabbr' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs' => 'flex-direction: {{_ob_tabbr_tabs_position.VALUE}}{{_ob_tabbr_tabs_order.VALUE}};',
						],
						'condition' => [
							'_ob_use_tabbr' => 'yes', 
						],
					],
				],

            ]
        );

        // ------------------------------------------------------------------------- CONTROL: Tabs order
        $element->add_responsive_control(
            '_ob_tabbr_tabs_order',
            [
                'label' => __( 'Tabs order', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'default' => ' ', 
                'options' => [
					' ' => __( 'TABS -- CONTENT', 'ooohboi-steroids' ), 
                    '-reverse' => __( 'CONTENT -- TABS', 'ooohboi-steroids' ),
                ],
                'condition' => [
                    '_ob_use_tabbr' => 'yes', 
                ],
            ]
        );

        // ------------------------------------------------------------------------- CONTROL: Tabs stack
        $element->add_responsive_control(
            '_ob_tabbr_tabs_stack',
            [
                'label' => __( 'Tabs stack', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'column', 
                'options' => [
					'row' => __( 'Horizontal', 'ooohboi-steroids' ), 
                    'column' => __( 'Vertical', 'ooohboi-steroids' ),
                ],
                'selectors' => [
                    '{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper' => 'flex-direction: {{VALUE}};', 
                ],
                'condition' => [
                    '_ob_use_tabbr' => 'yes', 
                ],
            ]
        );

        // ------------------------------------------------------------------------- CONTROL: Tabs alignment
        $element->add_responsive_control(
            '_ob_tabbr_tabs_alignment',
            [
                'label' => __( 'Tabs alignment', 'ooohboi-steroids' ),
                'description' => __( 'BEWARE! This will override the default (non-responsive) tabs alignment!', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex-start', 
                'options' => [
                    'flex-start' => __( 'Start', 'ooohboi-steroids' ),
                    'center' => __( 'Center', 'ooohboi-steroids' ),
                    'flex-end' => __( 'End', 'ooohboi-steroids' ), 
                ],
                'selectors' => [
                    '{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper, {{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper .elementor-tab-title' => 'justify-content: {{VALUE}};', 
					'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper' => 'align-self: {{VALUE}};', 
                ],
                'condition' => [
                    '_ob_use_tabbr' => 'yes', 
                ],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [ 
						'selectors' => [
							'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper, {{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper .elementor-tab-title' => 'justify-content: {{_ob_tabbr_tabs_alignment.VALUE}};', 
							'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper' => 'align-self: {{_ob_tabbr_tabs_alignment.VALUE}};', 
						],
						'condition' => [
							'_ob_use_tabbr' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper, {{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper .elementor-tab-title' => 'justify-content: {{_ob_tabbr_tabs_alignment.VALUE}};', 
							'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper' => 'align-self: {{_ob_tabbr_tabs_alignment.VALUE}};', 
						],
						'condition' => [
							'_ob_use_tabbr' => 'yes', 
						],
					],
				],
            ]
        );

		// --------------------------------------------------------------------------------------------- CONTROL: Tabbr tabs width (vertical only!!!)
        $element->add_responsive_control(
            '_ob_tab_width',
            [
				'label' => __( 'Tabs width', 'ooohboi-steroids' ),
				'description' => __( 'Enter any acceptable CSS value; 125px, 5vw, calc(30% - 10px), clamp(50px, 5vw + 10px, 155px), 12% ...', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT, 
				'default' => '30%', 
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-vertical .elementor-tabs-wrapper, {{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-horizontal .elementor-tabs-wrapper' => 'min-width: {{VALUE}}; width: {{VALUE}};', 
					'{{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-vertical .elementor-tabs-content-wrapper, {{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-horizontal .elementor-tabs-content-wrapper' => 'width: 100%;', 
				],
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [ 
						'selectors' => [
							'{{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-vertical .elementor-tabs-wrapper, {{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-horizontal .elementor-tabs-wrapper' => 'min-width: {{_ob_tab_width.VALUE}}; width: {{_ob_tab_width.VALUE}};', 
							'{{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-vertical .elementor-tabs-content-wrapper, {{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-horizontal .elementor-tabs-content-wrapper' => 'width: 100%;', 
						],
						'condition' => [
							'_ob_use_tabbr' => 'yes', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							'{{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-vertical .elementor-tabs-wrapper, {{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-horizontal .elementor-tabs-wrapper' => 'min-width: {{_ob_tab_width.VALUE}}; width: {{_ob_tab_width.VALUE}};', 
							'{{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-vertical .elementor-tabs-content-wrapper, {{WRAPPER}}.ob-use-tabbr.elementor-tabs-view-horizontal .elementor-tabs-content-wrapper' => 'width: 100%;', 
						],
						'condition' => [
							'_ob_use_tabbr' => 'yes', 
						],
					],
				],
			]
		);

		// --------------------------------------------------------------------------------------------- Tabs BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_tab_bg', 
                'types' => [ 'classic', 'gradient' ], 
                'exclude' => [ 'image' ], 
                'selector' => '{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper',
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
            ]
		);

        // ------------------------------------------------------------------------- CONTROL: Tabs wrappr border
        $element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_tabs_wrappr_border', 
				'label' => __( 'Tabs border', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => '{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper',
				'condition' => [
                    '_ob_use_tabbr' => 'yes',
                ],
			]
		);
        $element->add_responsive_control(
			'_ob_tabs_wrappr_bord_rad',
			[
				'label' => __( 'Tabs border radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tabs-wrapper'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
			]
		);
		
        // --------------------------------------------------------------------------------------------- CONTROL Description - Faker !!!!!
        $element->add_control(
            '_ob_use_tabbr_fake_descr_1',
            [
                'type' => Controls_Manager::RAW_HTML, 
                'label' => __( 'Tab title', 'ooohboi-steroids' ),
                'raw' => __( 'Style up tab title', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
            ]
        );

        // ------------------------------------------------------------------------- CONTROL: Tabbr text orientation
        $element->add_responsive_control(
			'_ob_tabbr_writing_mode',
			[
				'label' => __( 'Writing Mode', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'inherit', 
				'options' => [
					'vertical-lr' => __( 'Vertical LR', 'ooohboi-steroids' ),
					'vertical-rl' => __( 'Vertical RL', 'ooohboi-steroids' ),
					'inherit' => __( 'Normal', 'ooohboi-steroids' ),
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tab-desktop-title' => 'writing-mode: {{VALUE}}; display: flex; align-items: center;', 
                ],
				'condition' => [
                    '_ob_use_tabbr' => 'yes', 
                ],
			]
        );

        $element->add_responsive_control(
			'_ob_tabbr_make_inline',
			[
				'label' => __( 'Flip', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'no', 
				'options' => [
					'yes' => __( 'Yes', 'ooohboi-steroids' ), 
					'no' => __( 'No', 'ooohboi-steroids' ),
				],
				'selectors_dictionary' => [
					'yes' => 'rotate(180deg)', 
					'no' => 'rotate(0deg)',
				],
				'selectors' => [
                    '{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tab-desktop-title' => 'transform: {{VALUE}};', 
				],
				'condition' => [
                    '_ob_use_tabbr' => 'yes', 
                ],
			]
		);

 		// ------------------------------------------------------------------------- CONTROL: Tabbr tab PADDING
         $element->add_responsive_control(
			'_ob_tabbr_padding',
			[
				'label' => __( 'Padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tab-desktop-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
			]
        );

 		// ------------------------------------------------------------------------- CONTROL: Tabbr tab MARGIN
         $element->add_responsive_control(
			'_ob_tabbr_margin',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tab-desktop-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
			]
        );

        // ------------------------------------------------------------------------- CONTROL: border radius
        $element->add_responsive_control(
			'_ob_tabbr_bord_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tab-desktop-title'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
			]
		);

		// --------------------------------------------------------------------------------------------- START 2 TABS: normal and active
		$element->start_controls_tabs( '_ob_tabbr_tabs' );

		// --------------------------------------------------------------------------------------------- START TAB normal
        $element->start_controls_tab(
            '_ob_ob_tabbr_tab_normal',
            [
                'label' => __( 'Normal', 'ooohboi-steroids' ),
            ]
		);

		// --------------------------------------------------------------------------------------------- Tabbr tab BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_tabbr_bg_normal', 
                'types' => [ 'classic', 'gradient' ], 
                'exclude' => [ 'image' ], 
                'selector' => '{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tab-desktop-title',
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
            ]
		);
		// --------------------------------------------------------------------------------------------- Tabbr tab BORDER 
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_tabbr_border_normal', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => '{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tab-desktop-title .ob-tabbr-tab-wrap::before',
				'condition' => [
                    '_ob_use_tabbr' => 'yes',
                ],
			]
		);

		$element->end_controls_tab(); // Normal tab end

		// --------------------------------------------------------------------------------------------- START TAB Active ------------------------------- >>>>>

		$element->start_controls_tab(
            '_ob_ob_tabbr_tab_active',
            [
                'label' => __( 'Active', 'ooohboi-steroids' ),
            ]
		);

		// --------------------------------------------------------------------------------------------- Tabbr tab BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_tabbr_bg_active', 
                'types' => [ 'classic', 'gradient' ], 
                'exclude' => [ 'image' ], 
                'selector' => '{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tab-desktop-title.elementor-active',
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
            ]
		);
		// --------------------------------------------------------------------------------------------- Tabbr tab BORDER 
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_tabbr_border_active', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => '{{WRAPPER}}.ob-use-tabbr.elementor-element .elementor-tab-desktop-title.elementor-active .ob-tabbr-tab-wrap::before', 
				'condition' => [
                    '_ob_use_tabbr' => 'yes',
                ],
			]
		);

        $element->end_controls_tab(); // Active tab end

        $element->end_controls_tabs(); // normal and active tabs end

        // --------------------------------------------------------------------------------------------- CONTROL Description - Faker !!!!!
        $element->add_control(
            '_ob_use_tabbr_fake_descr_2',
            [
                'type' => Controls_Manager::RAW_HTML, 
                'label' => __( 'Tab container', 'ooohboi-steroids' ),
                'raw' => __( 'Style up the tab container', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
            ]
        );

 		// ------------------------------------------------------------------------- CONTROL: Content PADDING
         $element->add_responsive_control(
			'_ob_tabbr_cont_padding',
			[
				'label' => __( 'Padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 20,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr .elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
			]
        );

 		// ------------------------------------------------------------------------- CONTROL: Content MARGIN
         $element->add_responsive_control(
			'_ob_tabbr_cont_margin',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr .elementor-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
			]
        );

		// --------------------------------------------------------------------------------------------- Content BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_tabbr_cont_bg', 
                'types' => [ 'classic', 'gradient', 'video', 'slideshow' ], 
                'selector' => '{{WRAPPER}}.ob-use-tabbr .elementor-tab-content',
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
            ]
		);
		// --------------------------------------------------------------------------------------------- Content BORDER 
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_tabbr_cont_border_fake', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => '{{WRAPPER}}.ob-use-tabbr .elementor-tab-content::before',
				'condition' => [
                    '_ob_use_tabbr' => 'yes', 
                    '_ob_tabbr_cont_max_height' => '', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- Content BORDER real
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_tabbr_cont_border_real', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => '{{WRAPPER}}.ob-use-tabbr .elementor-tab-content',
				'condition' => [
                    '_ob_use_tabbr' => 'yes', 
                    '_ob_tabbr_cont_max_height!' => '', 
                ],
			]
		);
 		// ------------------------------------------------------------------------- CONTROL: fake BORDER inset
         $element->add_responsive_control(
			'_ob_tabbr_cont_inset_border',
			[
				'label' => __( 'Inset border', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr .elementor-tab-content::before' => 'inset: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
                    '_ob_tabbr_cont_max_height' => '', 
				],
			]
        );

		// --------------------------------------------------------------------------------------------- CONTENT max-height
        $element->add_responsive_control(
            '_ob_tabbr_cont_max_height',
            [
				'label' => __( 'Max-height', 'ooohboi-steroids' ),
				'description' => __( 'Enter any acceptable CSS value; 125px, 5vw, calc(30% - 10px), clamp(50px, 5vw + 10px, 155px), 12% ...', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr .elementor-tab-content' => 'max-height: {{VALUE}};',
				],
				'condition' => [
					'_ob_use_tabbr' => 'yes', 
				],
			]
		);

        // ------------------------------------------------------------------------- CONTENT max-height and scroll content
        $element->add_responsive_control(
			'_ob_tabbr_scroll_behaviour',
			[
				'label' => __( 'Scroll behaviour', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'auto', 
				'options' => [
					'hidden' => __( 'Hidden', 'ooohboi-steroids' ), 
                    'auto' => __( 'Auto', 'ooohboi-steroids' ),
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-use-tabbr .elementor-tab-content' => 'overflow-y: {{VALUE}};', 
                ],
				'condition' => [
                    '_ob_use_tabbr' => 'yes', 
                    '_ob_tabbr_cont_max_height!' => '', 
                ],
			]
        );

	}

}