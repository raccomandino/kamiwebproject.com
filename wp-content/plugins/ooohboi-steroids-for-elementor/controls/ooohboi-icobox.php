<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi_Icobox class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.6.5
 */
final class OoohBoi_Icobox {

	/**
	 * Initialize 
	 *
	 * @since 1.6.5
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/icon-box/section_style_icon/before_section_end',  [ __CLASS__, 'ooohboi_icobox_img_controls' ], 10, 2 ); 
        add_action( 'elementor/element/icon-box/section_style_content/before_section_end',  [ __CLASS__, 'ooohboi_icobox_cont_controls' ], 10, 2 );

    }
    
	public static function ooohboi_icobox_img_controls( $element, $args ) {

		$element->add_control(
			'_ob_icobox_img',
			[
				'label' => 'I C O B O X', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );
        // --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
        $element->add_control(
            '_ob_icobox_separator_x',
            [
                'type' => Controls_Manager::DIVIDER, 
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Box Shadow Regular
		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_ob_icobox_shadow', 
				'label' => __( 'Box Shadow', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => '{{WRAPPER}} .elementor-icon-box-img', 
				'fields_options' => [
					'box_shadow' => [
						'default' => [
							'horizontal' => 0,
							'vertical' => 0,
							'blur' => 0,
							'spread' => 0,
							'color' => 'rgba(0,0,0,0.5)',
						],
					],
				],
			]
        );
        // ------------------------------------------------------------------------- CONTROL: Visibility
		$element->add_control(
			'_ob_icobox_visibility',
			[
				'label' => __( 'Content Overflow', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hidden',
				'separator' => 'before', 
				'options' => [
					'visible' => __( 'Visible', 'ooohboi-steroids' ), 
					'hidden' => __( 'Hidden', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-img' => 'overflow: {{value}};',
				],
			]
		);
		// ------------------------------------------------------------------------- since 1.8.1 - Prevent icon & title collapse on mobiles
		$element->add_control(
			'_ob_icobox_no_collapse',
			[
				'label' => __( 'Prevent Icon and Title Collapse', 'ooohboi-steroids' ),
				'description' => __( 'The icon sits atop the title on mobiles. Prevent that, huh?', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ), 
				'return_value' => 'inherit', 
                'selectors' => [
                    '{{WRAPPER}}.elementor-position-left .elementor-icon-box-wrapper, {{WRAPPER}}.elementor-position-right .elementor-icon-box-wrapper' => 'display: flex !important;',
                ],
			]
		);
        // ------------------------------------------------------------------------- CONTROL: Yes 4 more controls
		$element->add_control(
			'_ob_icobox_override_defaults',
			[
				'label' => __( 'Overrides', 'ooohboi-steroids' ), 
				'description' => __( 'Allow more basic controls?', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Icon Size
        $element->add_responsive_control(
            '_ob_icobox_icon_size',
            [
                'label' => __( 'Icon Size', 'ooohboi-steroids' ),
                'separator' => 'before', 
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh', 'custom' ],
				'range' => [
					'px' => [
						'max' => 200,
					],
					'%' => [
						'max' => 100,
						'step' => 0.1,
					],
					'vw' => [
						'max' => 100,
						'step' => 0.1,
					],
					'vh' => [
						'max' => 100,
						'step' => 0.1,
					],
					'em' => [
						'max' => 100,
						'step' => 0.1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-wrapper .elementor-icon-box-icon i, {{WRAPPER}} .elementor-icon-box-wrapper .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    '_ob_icobox_override_defaults' => 'yes', 
                ],
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Content Padding
        $element->add_responsive_control(
            '_ob_icobox_padding_icon',
            [
                'label' => __( 'Icon Padding', 'ooohboi-steroids' ),
                'separator' => 'before', 
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-wrapper .elementor-icon-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                    '_ob_icobox_override_defaults' => 'yes', 
                ],
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Content Margin
        $element->add_responsive_control(
            '_ob_icobox_margin_icon',
            [
                'label' => __( 'Icon Margin', 'ooohboi-steroids' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh', 'custom' ], 
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-wrapper .elementor-icon-box-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
                'condition' => [
                    '_ob_icobox_override_defaults' => 'yes', 
                ],
            ]
        );
		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_icobox_img_background', 
                'selector' => '{{WRAPPER}} .elementor-icon-box-wrapper .elementor-icon-box-icon',
            ]
		);

    }
    
    public static function ooohboi_icobox_cont_controls( $element, $args ) {

		$element->add_control(
			'_ob_icobox_cont',
			[
				'label' => 'I C O B O X', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );

        // --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
        $element->add_control(
            '_ob_icobox_separator_y',
            [
                'type' => Controls_Manager::DIVIDER, 
            ]
        );

        // --------------------------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_icobox_cont_background', 
                'selector' => '{{WRAPPER}} .elementor-icon-box-content',
            ]
		);

        // --------------------------------------------------------------------------------------------- CONTROL Padding
		$element->add_responsive_control(
			'_ob_icobox_padding_cont',
			[
				'label' => __( 'Box Padding', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Yes 4 more controls
		$element->add_control(
			'_ob_icobox_overrides',
			[
				'label' => __( 'Overrides', 'ooohboi-steroids' ), 
				'description' => __( 'Allow separate controls for Title and Description?', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

        // --------------------------------------------------------------------------------------------- CONTROL Title Padding
		$element->add_responsive_control(
			'_ob_icobox_padding_title',
			[
				'label' => __( 'Title Padding', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_icobox_overrides' => 'yes', 
                ],
			]
		);
        // --------------------------------------------------------------------------------------------- CONTROL Title Margin
		$element->add_responsive_control(
			'_ob_icobox_margin_title',
			[
				'label' => __( 'Title Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_icobox_overrides' => 'yes', 
                ],
			]
		);
        // --------------------------------------------------------------------------------------------- CONTROL Content Padding
		$element->add_responsive_control(
			'_ob_icobox_padding_text',
			[
				'label' => __( 'Description Padding', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_icobox_overrides' => 'yes', 
                ],
			]
		);
        // --------------------------------------------------------------------------------------------- CONTROL Content Margin
		$element->add_responsive_control(
			'_ob_icobox_margin_text',
			[
				'label' => __( 'Description Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_icobox_overrides' => 'yes', 
                ],
			]
		);

	}

}