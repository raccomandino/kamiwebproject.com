<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Glider class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class OoohBoi_Glider {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/section/section_layout/after_section_end',  [ __CLASS__, 'add_section' ], 10, 2 );
		add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

		/* CONTAINER */
        add_action( 'elementor/element/container/section_layout/after_section_end',  [ __CLASS__, 'add_section' ], 10, 2 );
		add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_container_attributes' ] );
		
		/* should enqueue? */
		add_action( 'elementor/frontend/section/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        add_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/glider-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_glider_is_slider' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/section/before_render', [ __CLASS__, 'should_script_enqueue' ] );
			remove_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }
	/* add_container_attributes separate from an old ELEmentor grid */
    public static function add_container_attributes( Element_Base $element ) {
        // bail if any other element but container
        if ( $element->get_name() !== 'container' ) return;
        // bail if editor
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;
		// grab the settings
		$settings = $element->get_settings_for_display();

        if( isset( $settings[ '_ob_glider_is_slider' ] ) && 'yes' === $settings[ '_ob_glider_is_slider' ] ) { 
			$element->add_render_attribute( '_wrapper', [
                'class' => 'ob-is-glider',
            ] );
        }

    }

    public static function add_attributes( Element_Base $element ) {
        // bail if any other element but section
        if ( $element->get_name() !== 'section' ) return;
        // bail if editor
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;
		// grab the settings
		$settings = $element->get_settings_for_display();

        if( isset( $settings[ '_ob_glider_is_slider' ] ) && 'yes' === $settings[ '_ob_glider_is_slider' ] ) { 
			$element->add_render_attribute( '_wrapper', [
                'class' => 'ob-is-glider',
            ] );
        }

    }
    
	public static function add_section( Element_Base $element ) {

		$element->start_controls_section(
            '_ob_steroids_background_overlay',
            [
                'label' => 'G L I D E R',
				'tab' => Controls_Manager::TAB_LAYOUT, 
				'hide_in_inner' => true, 
            ]
        );
        
        // ------------------------------------------------------------------------- CONTROL: Turn section to Slider
		$element->add_control(
			'_ob_glider_is_slider',
			[
                'label' => __( 'Create Slider?', 'ooohboi-steroids' ), 
				'description' => __( 'This container containers will become slidable. TIP: Use this Switch to refresh your Glider.', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Slider AutoHeight
		$element->add_control(
			'_ob_glider_auto_h',
			[
                'label' => __( 'Adaptable height?', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true, 
				'hide_in_inner' => true, 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
				],
			]
        );
        // ------------------------------------------------------------------------- CONTROL SLIDER HEIGHT
        $element->add_responsive_control(
            '_ob_glider_h',
            [
				'label' => __( 'Slider height', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER, 
				'size_units' => [ 'px', 'vh', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider' => 'height: {{SIZE}}{{UNIT}} !important;', 
					'{{WRAPPER}}.ob-is-glider .swiper-wrapper' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.ob-is-glider .swiper-wrapper .swiper-slide' => 'height: {{SIZE}}{{UNIT}} !important;', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_auto_h!' => 'yes', 
					'_ob_glider_direction!' => 'vertical', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
        // ------------------------------------------------------------------------- CONTROL SLIDER HEIGHT - for vertical
        $element->add_responsive_control(
            '_ob_glider_h_vert',
            [
				'label' => esc_html__( 'Slider vertical height', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SLIDER, 
				'description' => esc_html__( 'Some content might end up being cut off -- check all out carefully', 'ooohboi-steroids' ), 
				'size_units' => [ 'px', 'vh', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider' => 'height: {{SIZE}}{{UNIT}} !important;', 
					'{{WRAPPER}}.ob-is-glider .swiper-wrapper' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.ob-is-glider .swiper-wrapper .swiper-slide' => 'height: {{SIZE}}{{UNIT}} !important;', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_direction' => 'vertical', 
				],

			]
		);

		// ------------------------------------------------------------------------- CONTROL Loop
		$element->add_control(
			'_ob_glider_loop',
			[
                'label' => __( 'Infinite Loop', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'yes', 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Direction
		$element->add_control(
			'_ob_glider_direction',
			[
				'label' => __( 'Direction', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT, 
                'default' => 'horizontal', 
				'options' => [
					'horizontal' => __( 'Horizontal', 'ooohboi-steroids' ),
					'vertical' => __( 'Vertical', 'ooohboi-steroids' ), 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_effect' => 'slide', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Effect
		$element->add_control(
			'_ob_glider_effect',
			[
				'label' => __( 'Transition type', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT, 
                'default' => 'slide', 
				'options' => [
					'slide' => __( 'Slide', 'ooohboi-steroids' ),
					'fade' => __( 'Fade', 'ooohboi-steroids' ), 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Easing
		$element->add_control(
			'_ob_glider_effect_easing',
			[
				'label' => sprintf( __( 'Easing%sNEW!%s', 'ooohboi-steroids' ), '<sup class="ob-new-feature">', '</sup>' ), 
				'description' => esc_html__( 'Easing effect applied to Glider', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SELECT, 
                'default' => 'ease-in-out', 
				'options' => [
					'linear' => esc_html__( 'Linear', 'ooohboi-steroids' ),
					'ease' => esc_html__( 'Ease', 'ooohboi-steroids' ), 
					'ease-in' => esc_html__( 'Ease-in', 'ooohboi-steroids' ), 
					'ease-out' => esc_html__( 'Ease-out', 'ooohboi-steroids' ), 
					'ease-in-out' => esc_html__( 'Ease-in-out', 'ooohboi-steroids' ), 
					'cubic-bezier(1,.02,.54,.86)' => esc_html__( 'Stagger', 'ooohboi-steroids' ), 
					'cubic-bezier(.6,-0.28,.74,.05)' => esc_html__( 'Backward', 'ooohboi-steroids' ), 
					'cubic-bezier(.86,0,.07,1)' => esc_html__( 'Quintic', 'ooohboi-steroids' ), 
					'cubic-bezier(.79,.14,.15,.86)' => esc_html__( 'Circular', 'ooohboi-steroids' ), 
					'cubic-bezier(.19,1,.22,1)' => esc_html__( 'Exponential', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-container-initialized:not(.swiper-container-fade) .swiper-wrapper' => 'transition-property: all; transition-timing-function: {{VALUE}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_effect!' => 'fade', 
				], 
				'frontend_available' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL Speed
		$element->add_control(
			'_ob_glider_speed',
			[
				'label' => __( 'Transition speed', 'ooohboi-steroids' ),
				'type' => Controls_Manager::NUMBER, 
				'min' => 1,
				'default' => 450, 
				'condition' => [
                    '_ob_glider_is_slider' => 'yes', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);

		// ------------------------------------------------------------------------- CONTROL Navig - prev and next
		$element->add_control(
			'_ob_glider_add_navig',
			[
                'label' => __( 'Hide Navigation', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'separator' => 'before', 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'none',
				'default' => 'block', 
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-prev, {{WRAPPER}}.ob-is-glider .swiper-button-next' => 'display: {{VALUE}} !important;', 
				],
				'condition' => [
                    '_ob_glider_is_slider' => 'yes', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL POPOVER Navig
		$element->add_control(
			'_ob_glider_nav_styles',
			[
				'label' => sprintf( __( 'Navigation styles%sNEW!%s', 'ooohboi-steroids' ), '<sup class="ob-new-feature">', '</sup>' ), 
				'description' => esc_html__( 'Edit to trigger new styles!', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'hide_in_inner' => true, 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
				],
			]
		);

		$element->start_popover();

		// ------------------------------------------------------------------------- CONTROL: Nav ICON
		$element->add_control(
			'_ob_glider_nav_icon',
			[
				'label' => esc_html__( 'Icon', 'ooohboi-steroids' ), 
				'description' => esc_html__( 'Select FontAwesome icon or upload your own SVG image', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
				'skin' => 'inline', 
				'frontend_available' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Nav COLOR
		$element->add_control(
			'_ob_glider_nav_color',
			[
				'label' => esc_html__( 'Nav Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-prev, {{WRAPPER}}.ob-is-glider .swiper-button-next' => 'fill: {{VALUE}};', 
					'{{WRAPPER}}.ob-is-glider .swiper-button-prev i::before, {{WRAPPER}}.ob-is-glider .swiper-button-next i::before' => 'color: {{VALUE}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Nav COLOR - Hover
		$element->add_control(
			'_ob_glider_nav_color_hover',
			[
				'label' => esc_html__( 'Nav Color - Hover', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF80',
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-prev:hover, {{WRAPPER}}.ob-is-glider .swiper-button-next:hover' => 'fill: {{VALUE}};', 
					'{{WRAPPER}}.ob-is-glider .swiper-button-prev:hover i::before, {{WRAPPER}}.ob-is-glider .swiper-button-next:hover i::before' => 'color: {{VALUE}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Nav BG COLOR
		$element->add_control(
			'_ob_glider_nav_color_bg',
			[
				'label' => esc_html__( 'Background Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0000004D',
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-next, {{WRAPPER}}.ob-is-glider .swiper-button-prev' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Nav BG COLOR - HOVER
		$element->add_control(
			'_ob_glider_nav_color_bg_hover',
			[
				'label' => esc_html__( 'Background Color - Hover', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFCC00E6',
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-next:hover, {{WRAPPER}}.ob-is-glider .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- Nav border
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_glider_nav_bord', 
				'label' => esc_html__( 'Border', 'ooohboi-steroids' ), 
				'selector' => '{{WRAPPER}}.ob-is-glider .swiper-button-next, {{WRAPPER}}.ob-is-glider .swiper-button-prev', 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Nav BG border radius
		$element->add_responsive_control(
			'_ob_glider_nav_bord_rad',
			[
				'label' => esc_html__( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-next, {{WRAPPER}}.ob-is-glider .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// -------------------------------------------------------------------------- CONTROL Icon Size
		$element->add_responsive_control(
			'_ob_glider_nav_icon_size',
			[
				'label' => esc_html__( 'Icon size', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER, 
				'range' => [
					'px' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-next, {{WRAPPER}}.ob-is-glider .swiper-button-prev' => 'width: unset; height: unset;', 
					'{{WRAPPER}}.ob-is-glider .swiper-button-next svg, {{WRAPPER}}.ob-is-glider .swiper-button-prev svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; display: block;', 
					'{{WRAPPER}}.ob-is-glider .swiper-button-prev i::before, {{WRAPPER}}.ob-is-glider .swiper-button-next i::before' => 'font-size: {{SIZE}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// -------------------------------------------------------------------------- CONTROL Padding
		$element->add_responsive_control(
			'_ob_glider_nav_padding',
			[
				'label' => esc_html__( 'Padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER, 
				'range' => [
					'px' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-next, {{WRAPPER}}.ob-is-glider .swiper-button-prev' => 'padding: {{SIZE}}{{UNIT}}; margin-top: unset;', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: position Y prev
		$element->add_responsive_control(
			'_ob_glider_nav_pos_y_prev_alt',
			[
				'label' => esc_html__( 'Calc Prev - Y', 'ooohboi-steroids' ),
				'description' => esc_html__( 'Valid CSS only! Like: 25px or 15em or 100% - 50px or 50% + 3rem', 'ooohboi-steroids' ), 
				'default' => '50% - 25px', 
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-prev' => 'top: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: position Y next
		$element->add_responsive_control(
			'_ob_glider_nav_pos_y_next_alt',
			[
				'label' => esc_html__( 'Calc Next - Y', 'ooohboi-steroids' ),
				'description' => esc_html__( 'Valid CSS only! Like: 25px or 15em or 100% - 50px or 50% + 3rem', 'ooohboi-steroids' ), 
				'default' => '50% - 25px', 
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-next' => 'top: calc({{VALUE}});',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// -------------------------------------------------------------------------- CONTROL position X prev
		$element->add_responsive_control(
			'_ob_glider_nav_pos_x_prev_alt',
			[
				'label' => esc_html__( 'Calc Prev - X', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Valid CSS only! Like: 25px or 15em or 100% - 50px or 50% + 3rem', 'ooohboi-steroids' ),
				'default' => '0%', 
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-prev' => 'left: calc({{VALUE}}); right: unset;',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);
		// -------------------------------------------------------------------------- CONTROL position X next
		$element->add_responsive_control(
			'_ob_glider_nav_pos_x_next_alt',
			[
				'label' => esc_html__( 'Calc Next - X', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Valid CSS only! Like: 25px or 15em or 100% - 50px or 50% + 3rem', 'ooohboi-steroids' ),
				'default' => '0%', 
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-button-next' => 'right: calc({{VALUE}}); left: unset;',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_navig!' => 'none', 
					'_ob_glider_nav_styles' => 'yes', 
				],
			]
		);

		$element->end_popover(); // popover end

		// ------------------------------------------------------------------------- CONTROL Pagination
		$element->add_control(
			'_ob_glider_add_pagination',
			[
                'label' => __( 'Hide Pagination', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'separator' => 'before', 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'none',
				'default' => 'block', 
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination' => 'display: {{VALUE}} !important;', 
				],
				'condition' => [
                    '_ob_glider_is_slider' => 'yes', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);

		// ------------------------------------------------------------------------- CONTROL POPOVER Pagination
		$element->add_control(
			'_ob_glider_pagination_styles',
			[
				'label' => sprintf( __( 'Pagination styles%sNEW!%s', 'ooohboi-steroids' ), '<sup class="ob-new-feature">', '</sup>' ), 
				'description' => esc_html__( 'Edit to trigger new styles!', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'hide_in_inner' => true, 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_pagination!' => 'none', 
				],
			]
		);

		$element->start_popover();

		// ------------------------------------------------------------------------- CONTROL: Pagination Type
		$element->add_control(
			'_ob_glider_pagination_type',
			[
				'label' => esc_html__( 'Pagination type', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT, 
				'default' => 'bullets', 
				'options' => [
					'bullets' => esc_html__( 'Bullets', 'ooohboi-steroids' ),
					'fraction' => esc_html__( 'Fraction', 'ooohboi-steroids' ), 
					'progressbar' => esc_html__( 'Progress Bar', 'ooohboi-steroids' ), 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_add_pagination!' => 'none', 
					'_ob_glider_pagination_styles' => 'yes', 
				],
				'frontend_available' => true, 
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Pagination COLOR
		$element->add_control(
			'_ob_glider_pagination_color',
			[
				'label' => esc_html__( 'Pagination Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#00000080',
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination-bullet' => 'background-color: {{VALUE}}; opacity: 1;', 
					'{{WRAPPER}}.ob-is-glider .swiper-pagination-progressbar' => 'background: {{VALUE}};', 
					'{{WRAPPER}}.ob-is-glider .swiper-pagination-fraction' => 'color: {{VALUE}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Pagination COLOR Active
		$element->add_control(
			'_ob_glider_pagination_color_active',
			[
				'label' => esc_html__( 'Pagination Color - Active', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}; opacity: 1;', 
					'{{WRAPPER}}.ob-is-glider .swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background: {{VALUE}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
					'_ob_glider_pagination_type!' => [ 'fraction' ], 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Pagination Size
		$element->add_responsive_control(
			'_ob_glider_pagination_size',
			[
				'label' => esc_html__( 'Size', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 11,
				],
				'range' => [
					'px' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', 
					'{{WRAPPER}}.ob-is-glider .swiper-container-horizontal > .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}};', 
					'{{WRAPPER}}.ob-is-glider .swiper-container-vertical > .swiper-pagination-progressbar' => 'width: {{SIZE}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
					'_ob_glider_pagination_type!' => [ 'bullets', 'fraction' ], 
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Pagination width for Bullets only! WIDTH
		$element->add_responsive_control(
			'_ob_glider_pagination_size_bullets_w',
			[
				'label' => esc_html__( 'Bullets width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 7,
				],
				'range' => [
					'px' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
					'_ob_glider_pagination_type' => [ 'bullets' ], 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Pagination height for Bullets only!
		$element->add_responsive_control(
			'_ob_glider_pagination_size_bullets_h',
			[
				'label' => esc_html__( 'Bullets height', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 7,
				],
				'range' => [
					'px' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
					'_ob_glider_pagination_type' => [ 'bullets' ], 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- Bullets border
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_glider_pagination_bord', 
				'label' => esc_html__( 'Border', 'ooohboi-steroids' ), 
				'selector' => '{{WRAPPER}}.ob-is-glider .swiper-pagination-bullet', 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_type' => [ 'bullets' ], 
					'_ob_glider_pagination_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: bullets border radius
		$element->add_responsive_control(
			'_ob_glider_pagination_bord_rad',
			[
				'label' => esc_html__( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ], 
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_type' => [ 'bullets' ], 
					'_ob_glider_pagination_styles' => 'yes', 
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: bullets spacing
		$element->add_responsive_control(
			'_ob_glider_pagination_size_bullets_space',
			[
				'label' => esc_html__( 'Bullets spacing', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'range' => [
					'px' => [
						'max' => 85,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
					'_ob_glider_pagination_type' => [ 'bullets' ], 
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: bullets wrapper alignment
		$element->add_responsive_control(
			'_ob_glider_pagination_bg_align', 
			[
				'label' => esc_html__( 'Pagination alignment', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT, 
				'default' => 'left:0;right:unset;transform:unset;', 
				'options' => [
					'left:0;right:unset;transform:unset;' => esc_html__( 'Left', 'ooohboi-steroids' ),
					'left:50%;right:unset;transform:translate(-50%,0);' => esc_html__( 'Center', 'ooohboi-steroids' ), 
					'left:unset;right:0;transform:unset;' => esc_html__( 'Right', 'ooohboi-steroids' ), 
				], 
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination:not(.swiper-pagination-progressbar)' => '{{VALUE}}', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
					'_ob_glider_pagination_type!' => [ 'progressbar' ], 
				],
				'frontend_available' => true, 
			]
		);
		// -------------------------------------------------------------------------- bullets Padding
		$element->add_responsive_control(
			'_ob_glider_pagination_bg_padding',
			[
				'label' => esc_html__( 'Pagination padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'top' => '11',
					'right' => '17',
					'bottom' => '11',
					'left' => '17',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination:not(.swiper-pagination-progressbar)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
					'_ob_glider_pagination_type!' => [ 'progressbar' ], 
				],
			]
		);
		// -------------------------------------------------------------------------- bullets Margin
		$element->add_responsive_control(
			'_ob_glider_pagination_bg_margin',
			[
				'label' => esc_html__( 'Pagination margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '11',
					'left' => '0',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination:not(.swiper-pagination-progressbar)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
					'_ob_glider_pagination_type!' => [ 'progressbar' ], 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Nav BG COLOR
		$element->add_control(
			'_ob_glider_pagination_bg_color',
			[
				'label' => esc_html__( 'Pagination background color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0000002E',
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination:not(.swiper-pagination-progressbar)' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
					'_ob_glider_pagination_type!' => [ 'progressbar' ], 
				],
			]
		);
		$element->add_responsive_control(
			'_ob_glider_pagination_bg_bord_rad',
			[
				'label' => esc_html__( 'Pagination background border radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'top' => '17',
					'right' => '17',
					'bottom' => '17',
					'left' => '17',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-glider .swiper-pagination:not(.swiper-pagination-progressbar)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_pagination_styles' => 'yes', 
					'_ob_glider_pagination_type!' => [ 'progressbar' ], 
				],
			]
		);

		$element->end_popover(); // popover end

		/* by Xmastermind */
		// ------------------------------------------------------------------------- CONTROL: Show Multiple Slides
		$element->add_control(
			'_ob_glider_allow_multi_slides',
			[
				'label' => __( 'Show Multiple Slides', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'separator' => 'before', 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no', 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_effect' => 'slide', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Slides Per View
		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );
		$element->add_responsive_control(
			'_ob_glider_slides_per_view',
			[
				'label' => __( 'Slides Per View', 'ooohboi-steroids' ),				
				'type' => Controls_Manager::SELECT, 
				'desktop_default' => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'options' => $slides_per_view,
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_allow_multi_slides' => 'yes', 
					
				],
				'frontend_available' => true,
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Slides to Scroll
		$slides_to_scroll = range( 1, 10 );
		$slides_to_scroll = array_combine( $slides_to_scroll, $slides_to_scroll );
		$element->add_responsive_control(
			'_ob_glider_slides_to_scroll',
			[
				'label' => __( 'Slides to Scroll', 'ooohboi-steroids' ),
				'description' => __( 'Scrolling multiple slides can break infinite loop.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SELECT, 
				'desktop_default' => 1,
				'tablet_default' => 1,
				'mobile_default' => 1,
				'options' => $slides_to_scroll,
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_allow_multi_slides' => 'yes', 
					
				],
				'frontend_available' => true,
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Space Between Slides
		$element->add_responsive_control(
			'_ob_glider_space_between',
			[
				'label' => __( 'Space Between Slides (px)', 'ooohboi-steroids' ),
				'type' => Controls_Manager::NUMBER, 
				'desktop_default' => 10,
				'tablet_default' => 10,
				'mobile_default' => 10,
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_allow_multi_slides' => 'yes', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		// v1.7.9
		// ------------------------------------------------------------------------- CONTROL: Centered Slides
		$element->add_control(
			'_ob_glider_centered_slides',
			[
				'label' => __( 'Centered Slides', 'ooohboi-steroids' ),
				'description' => __( 'Active slide will be centered.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no', 
				'condition' => [
					'_ob_glider_allow_multi_slides' => 'yes', 
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_effect' => 'slide', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Centered Slides Bounds
		$element->add_control(
			'_ob_glider_centered_bounds_slides',
			[
				'label' => __( 'Centered Slide Bounds', 'ooohboi-steroids' ),
				'description' => __( 'Active slide will be centered without adding gaps at the beginning and end of slider.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no', 
				'condition' => [
					'_ob_glider_allow_multi_slides' => 'yes', 
					'_ob_glider_centered_slides' => 'yes', 
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_effect' => 'slide', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		/* -------------- */
		// ------------------------------------------------------------------------- CONTROL Parallax
		$element->add_control(
			'_ob_glider_parallax',
			[
				'label' => __( 'Parallax', 'ooohboi-steroids' ), 
				'description' => __( 'It will work with Elementor PRO Attributes only.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'separator' => 'before', 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'yes', 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					/*'_ob_glider_effect' => 'slide', */
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL Disable TouchMove
		$element->add_control(
			'_ob_glider_allow_touch_move',
			[
                'label' => __( 'Enable Touch Move', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'yes', 
				'condition' => [
                    '_ob_glider_is_slider' => 'yes', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL Enable Mousewheel
		$element->add_control(
			'_ob_glider_allow_mousewheel',
			[
                'label' => __( 'Enable Mousewheel', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no', 
				'condition' => [
                    '_ob_glider_is_slider' => 'yes', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL: De-blur slides content
		$element->add_control(
			'_ob_glider_roundlengths_slides',
			[
				'label' => __( 'Deblur Slides', 'ooohboi-steroids' ),
				'description' => __( 'Round values of slides width and height to prevent blurry texts?', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no', 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_effect' => 'slide', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL Autoplay
		$element->add_control(
			'_ob_glider_autoplay',
			[
                'label' => __( 'Autoplay', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => true,
				'default' => '', 
				'condition' => [
                    '_ob_glider_is_slider' => 'yes', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);
		// ------------------------------------------------------------------------- CONTROL Autoplay delay
		$element->add_control(
			'_ob_glider_autoplay_delay',
			[
				'label' => __( 'Autoplay delay', 'ooohboi-steroids' ), 
				'description' => __( 'In miliseconds! 1000 is one second.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::NUMBER,
				'min' => 1000,
				'default' => 3000, 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
					'_ob_glider_autoplay!' => '', 
				],
				'frontend_available' => true, 
				'hide_in_inner' => true, 
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Glider ID
		$element->add_control(
			'_ob_glider_control_id_description',
			[
				'label' => esc_html__( 'Control Glider externally!', 'ooohboi-animator' ), 
				'type' => Controls_Manager::RAW_HTML, 
				'raw' => __( 'You can control this Glider instance from any link on this page by adding the custom class name to the link. 
				Copy the text entirely and append the slide number to go to at the end. For instance: glider-NNNNNNN-gotoslide-3', 'ooohboi-animator' ),
				'content_classes' => 'elementor-control-field-description', 
				'separator' => 'before', 
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
				],
			]
		);
		$element->add_control(
			'_ob_glider_control_id',
			[
				'label' => __( 'This Glider control class', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'render_type' => 'ui', 
				'description' => '<script>
				var $obWidgetID = jQuery(\'.elementor-control-_ob_glider_control_id input\');
				$obWidgetID.val("glider-" + elementor.getCurrentElement().model.id + "-gotoslide-");
				$obWidgetID.attr(\'readonly\', true);
				$obWidgetID.on(\'focus click\', function() { this.select();document.execCommand(\'copy\'); });
				</script>',
				'condition' => [
					'_ob_glider_is_slider' => 'yes', 
				],
			]
		);
        
        $element->end_controls_section();

	}

}