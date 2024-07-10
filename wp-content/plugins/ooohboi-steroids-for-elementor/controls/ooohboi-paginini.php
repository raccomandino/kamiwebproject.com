<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Paginini
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class OoohBoi_Paginini {

	/**
	 * Initialize 
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/posts/section_pagination_style/before_section_end',  [ __CLASS__, 'ooohboi_paginini_get_controls' ], 10, 2 );
		add_action( 'elementor/element/archive-posts/section_pagination_style/before_section_end',  [ __CLASS__, 'ooohboi_paginini_get_controls' ], 10, 2 );

    }
    
	public static function ooohboi_paginini_get_controls( $element, $args ) {

		$element->add_control(
			'_ob_paginini_plugin_title',
			[
				'label' => 'P A G I N I N I', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );
        
		// ------------------------------------------------------------------------- CONTROL: Pagination BG COLOR
		$element->add_control(
			'ooohboi_paginini_bg_color',
			[
				'label' => __( 'Background Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#00000000',
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination' => 'background-color: {{VALUE}};',
				],
			]
        );
        // ------------------------------------------------------------------------- CONTROL: Pagination PADDING
		$element->add_responsive_control(
			'ooohboi_paginini_paddingz',
			[
				'label' => __( 'Padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => '%',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Pagination MARGIN
		$element->add_responsive_control(
			'ooohboi_paginini_marginz',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => '%',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
        );
        // --------------------------------------------------------------------------------------------- CONTROL POPOVER: Pagination page numbers padding
        $element->add_responsive_control(
            '_ob_paginini_page_number_padding',
            [
                'label' => __( 'Page Number Padding', 'ooohboi-steroids' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // --------------------------------------------------------------------------------------------- CONTROL POPOVER: Pagination page numbers
		$element->add_control(
            '_ob_paginini_popover_page_numbers',
            [
                'label' => __( 'Page numbers', 'ooohboi-steroids' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'frontend_available' => true,
            ]
		);
		
        $element->start_popover();
        
        // ------------------------------------------------------------------------- CONTROL POPOVER: Pagination page numbers bg color
		$element->add_control(
			'_ob_paginini_popover_pn_bg_color',
			[
				'label' => __( 'Background Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#00000000',
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination .page-numbers:not(.current)' => 'background-color: {{VALUE}};',
				],
			]
        );
        // ------------------------------------------------------------------------- CONTROL POPOVER: Pagination page numbers bg color - hover
        $element->add_control(
            '_ob_paginini_popover_pn_bg_color_hover',
            [
                'label' => __( 'Background Color - HOVER', 'ooohboi-steroids' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#00000000',
                'selectors' => [
                    '{{WRAPPER}} .elementor-pagination .page-numbers:not(.current):hover' => 'background-color: {{VALUE}};',
				],
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL POPOVER: Pagination page numbers border
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_paginini_popover_pn_bord', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'selector' => '{{WRAPPER}} .elementor-pagination .page-numbers:not(.current)', 
			]
		);
        // ------------------------------------------------------------------------- CONTROL POPOVER: Pagination page numbers border radius
        $element->add_responsive_control(
			'_ob_paginini_popover_pn_bord_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination .page-numbers:not(.current)'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $element->end_popover(); // popover end

        // --------------------------------------------------------------------------------------------- CONTROL POPOVER: Pagination Current Item
		$element->add_control(
            '_ob_paginini_popover_current_page',
            [
                'label' => __( 'Current Page', 'ooohboi-steroids' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'frontend_available' => true,
            ]
		);
		
        $element->start_popover();

        // ------------------------------------------------------------------------- CONTROL POPOVER: Pagination Current Item bg color
		$element->add_control(
			'_ob_paginini_popover_current_bg_color',
			[
				'label' => __( 'Background Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#00000000',
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'background-color: {{VALUE}}; opacity: unset;',
				],
			]
        );
        // --------------------------------------------------------------------------------------------- CONTROL POPOVER: Pagination Current Item border
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_paginini_popover_current_bord', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'selector' => '{{WRAPPER}} .elementor-pagination .page-numbers.current', 
			]
		);
        // ------------------------------------------------------------------------- CONTROL POPOVER: Pagination Current Item border radius
        $element->add_responsive_control(
			'_ob_paginini_popover_current_bord_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination .page-numbers.current'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $element->end_popover(); // popover end

        // ------------------------------------------------------------------------- CONTROL: Exclude Prev and Next
		$element->add_control(
			'_ob_paginini_exclude_prev_next',
			[
				'label' => __( 'Unstyle Prev and Next?', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .elementor-pagination .page-numbers.prev' => 'background-color: transparent; border: none; border-radius: 0;', 
                    '{{WRAPPER}} .elementor-pagination .page-numbers.next' => 'background-color: transparent; border: none; border-radius: 0;', 
                    '{{WRAPPER}} .elementor-pagination .page-numbers.prev:hover' => 'background-color: transparent;', 
                    '{{WRAPPER}} .elementor-pagination .page-numbers.next:hover' => 'background-color: transparent;',
				],
			]
		);

	}

}