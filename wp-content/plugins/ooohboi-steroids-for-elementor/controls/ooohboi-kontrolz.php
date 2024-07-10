<?php
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi_Kontrolz class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.5.7
 */
class OoohBoi_Kontrolz {

	/**
	 * Initialize 
	 *
	 * @since 1.5.7
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/image-carousel/section_style_navigation/before_section_end',  [ __CLASS__, 'ooohboi_kontrolz_controls' ], 10, 2 ); 
        add_action( 'elementor/element/media-carousel/section_navigation/before_section_end',  [ __CLASS__, 'ooohboi_kontrolz_controls' ], 10, 2 );

    }
    
	public static function ooohboi_kontrolz_controls( $element, $args ) {

		$element->add_control(
			'_ob_kontrolz',
			[
				'label' => 'K O N T R O L Z', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );

		// ------------------------------------------------------------------------- CONTROL POPOVER Navig
		$element->add_control(
			'_ob_kontrolz_nav_styles',
			[
				'label' => __( 'Navigation styles', 'ooohboi-steroids' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
			]
		);

		$element->start_popover();

		// ------------------------------------------------------------------------- CONTROL: Nav COLOR - Hover
		$element->add_control(
			'_ob_kontrolz_nav_color_hover',
			[
				'label' => __( 'Arrows Color - Hover', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF80',
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover' => 'color: {{VALUE}};',
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Nav BG COLOR
		$element->add_control(
			'_ob_kontrolz_nav_color_bg',
			[
				'label' => __( 'Background Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0000004D',
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'background-color: {{VALUE}};',
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Nav BG COLOR - HOVER
		$element->add_control(
			'_ob_kontrolz_nav_color_bg_hover',
			[
				'label' => __( 'Background Color - Hover', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFCC00E6',
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button-next:hover, {{WRAPPER}} .elementor-swiper-button-prev:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Nav BG border radius
		$element->add_control(
			'_ob_kontrolz_nav_bord_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		// -------------------------------------------------------------------------- CONTROL Padding
		$element->add_control(
            '_ob_kontrolz_nav_padding',
            [
				'label' => __( 'Padding', 'ooohboi-steroids' ),
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
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'padding: {{SIZE}}{{UNIT}}; margin-top: unset;', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: position Y both
        $element->add_control(
            '_ob_kontrolz_nav_pos_y_alt',
            [
				'label' => __( 'Calc - Y', 'ooohboi-steroids' ),
				'description' => __( 'Valid CSS only! Like: 25px or 15em or 100% - 50px or 50% + 3rem', 'ooohboi-steroids' ),
				'default' => '50% - 25px', 
				'type' => Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'top: calc({{VALUE}});',
				],
			]
		);
		// -------------------------------------------------------------------------- CONTROL position X prev
		$element->add_control(
            '_ob_kontrolz_nav_pos_x_prev_alt',
            [
				'label' => __( 'Calc Prev - X', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Valid CSS only! Like: 25px or 15em or 100% - 50px or 50% + 3rem', 'ooohboi-steroids' ),
				'default' => '0%', 
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button-prev' => 'left: calc({{VALUE}}); right: unset;',
				],
			]
		);
		// -------------------------------------------------------------------------- CONTROL position X next
		$element->add_control(
            '_ob_kontrolz_nav_pos_x_next_alt',
            [
				'label' => __( 'Calc Next - X', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Valid CSS only! Like: 25px or 15em or 100% - 50px or 50% + 3rem', 'ooohboi-steroids' ),
				'default' => '0%', 
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button-next' => 'right: calc({{VALUE}}); left: unset;',
				],
			]
		);

		$element->end_popover(); // popover end

		// ------------------------------------------------------------------------- CONTROL POPOVER Pagination
		$element->add_control(
			'_ob_kontrolz_pagination_styles',
			[
				'label' => __( 'Pagination styles', 'ooohboi-steroids' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
			]
		);

		$element->start_popover();

		// ------------------------------------------------------------------------- CONTROL: Pagination COLOR
		$element->add_control(
			'_ob_kontrolz_pagination_color',
			[
                'label' => __( 'Pagination Color', 'ooohboi-steroids' ), 
                'description' => __( 'NOTE: It works only with the pagination style set to Dots!', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::COLOR,
				'default' => '#00000080',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'background-color: {{VALUE}};', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Pagination COLOR Active
		$element->add_control(
			'_ob_kontrolz_pagination_color_active',
			[
				'label' => __( 'Pagination Color - Active', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}} !important;', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Nav BG border radius
		$element->add_control(
			'_ob_kontrolz_pagination_bord_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->end_popover(); // popover end

    }

}