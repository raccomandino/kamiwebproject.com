<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi_Imbox class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.5.4
 */
final class OoohBoi_Imbox {

	/**
	 * Initialize 
	 *
	 * @since 1.5.4
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/image-box/section_style_image/before_section_end',  [ __CLASS__, 'ooohboi_imbox_img_controls' ], 10, 2 ); 
        add_action( 'elementor/element/image-box/section_style_content/before_section_end',  [ __CLASS__, 'ooohboi_imbox_cont_controls' ], 10, 2 );

    }
    
	public static function ooohboi_imbox_img_controls( $element, $args ) {

		$element->add_control(
			'_ob_imbox_img',
			[
				'label' => 'I M B O X', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );
        // --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
        $element->add_control(
            '_ob_imbox_separator_x',
            [
                'type' => Controls_Manager::DIVIDER, 
            ]
        );
        // --------------------------------------------------------------------------------------------- CONTROL Box Shadow Regular
		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_ob_imbox_shadow', 
				'label' => __( 'Box Shadow', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => '{{WRAPPER}} .elementor-image-box-img', 
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
			'_ob_imbox_visibility',
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
					'{{WRAPPER}} .elementor-image-box-img' => 'overflow: {{value}};',
				],
			]
		);
		// ------------------------------------------------------------------------- since 1.8.1 - Prevent icon & title collapse on mobiles
		$element->add_control(
			'_ob_imbox_no_collapse',
			[
				'label' => __( 'Prevent Image and Title Collapse', 'ooohboi-steroids' ),
				'description' => __( 'The thumbnail sits atop the title on mobiles. Prevent that, huh?', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ), 
				'return_value' => 'inherit', 
                'selectors' => [
                    '{{WRAPPER}}.elementor-position-left .elementor-image-box-wrapper, {{WRAPPER}}.elementor-position-right .elementor-image-box-wrapper' => 'display: flex !important;',
                ],
			]
		);
		// 1.9.6 patch
		$element->add_responsive_control(
			'_ob_imbox_no_collapse_cont_align',
			[
				'label' => __( 'Content position', 'ooohboi-steroids' ), 
				'description' => __( 'Where to align the content to?', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SELECT,
                'default' => 'center', 
				'options' => [
					'flex-start' => __( 'Start', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'flex-end' => __( 'End', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-position-left .elementor-image-box-wrapper .elementor-image-box-content, {{WRAPPER}}.elementor-position-right .elementor-image-box-wrapper .elementor-image-box-content' => 'align-self: {{VALUE}};', 
				],
				'condition' => [
					'_ob_imbox_no_collapse' => 'inherit',
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Yes 4 more controls
		$element->add_control(
			'_ob_imbox_override_defaults',
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
		// --------------------------------------------------------------------------------------------- CONTROL Image Size
		$element->add_responsive_control(
			'_ob_imbox_image_size',
			[
				'label' => __( 'Image Size', 'ooohboi-steroids' ),
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
					'{{WRAPPER}} .elementor-image-box-wrapper .elementor-image-box-img' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_imbox_override_defaults' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Content Padding
		$element->add_responsive_control(
			'_ob_imbox_padding_icon',
			[
				'label' => __( 'Image Padding', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_imbox_override_defaults' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Content Margin
		$element->add_responsive_control(
			'_ob_imbox_margin_icon',
			[
				'label' => __( 'Image Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ], 
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_imbox_override_defaults' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Yes 4 Full height image !
		$element->add_control(
			'_ob_imbox_full_height',
			[
                'label' => __( 'Enable full-height?', 'ooohboi-steroids' ), 
                'description' => __( 'That will make the photo full-height. Makes sense only if the photo is placed left or the right-hand side!', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'default' => false,
                'separator' => 'before', 
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-box-wrapper' => 'align-items: stretch;', 
                    '{{WRAPPER}}.elementor-position-left .elementor-image-box-img img' => 'object-fit: cover; object-position: 50% 50%; height: 100%;', 
					'{{WRAPPER}}.elementor-position-right .elementor-image-box-img img' => 'object-fit: cover; object-position: 50% 50%; height: 100%;', 
				],
			]
		);
		/* since 1.7.0 */
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER
		$element->add_control(
			'_ob_imbox_popover_border_img',
			[
				'label' => __( 'Border', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
			]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER ALL
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_imbox_borders_img', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'selector' => '{{WRAPPER}} .elementor-image-box-img img', 
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER RADIUS
		$element->add_responsive_control(
			'_ob_imbox_border_rad_img',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS, 
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-img img'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->end_popover(); // popover BORdER end

		// --------------------------------------------------------------------------------------------- CONTROL CLIP PATH
		$element->add_control(
			'_ob_imbox_clip_path',
			[				
				'label' => __( 'Image clip path', 'ooohboi-steroids' ), 
				'description' => sprintf(
					__( 'Enter the full clip-path property! See the copy-paste examples at %sClippy%s', 'ooohboi-steroids' ),
					'<a href="https://bennettfeely.com/clippy/" target="_blank">',
					'</a>'
				),
				'default' => '', 
				'type' => Controls_Manager::TEXTAREA, 
				'rows' => 3, 
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-img' => '{{VALUE}}',
				],
				'condition' => [
					'_ob_imbox_override_defaults' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_imbox_img_background', 
                'selector' => '{{WRAPPER}} .elementor-image-box-img',
            ]
		);

    }
    
    public static function ooohboi_imbox_cont_controls( $element, $args ) {

		$element->add_control(
			'_ob_imbox_cont',
			[
				'label' => 'I M B O X', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );

        // --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
        $element->add_control(
            '_ob_imbox_separator_y',
            [
                'type' => Controls_Manager::DIVIDER, 
            ]
        );

        // --------------------------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_imbox_cont_background', 
                'selector' => '{{WRAPPER}} .elementor-image-box-content',
            ]
		);

        // --------------------------------------------------------------------------------------------- CONTROL Padding
		$element->add_responsive_control(
			'_ob_imbox_padding_cont',
			[
				'label' => __( 'Box Padding', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        // --------------------------------------------------------------------------------------------- CONTROL Margin
		$element->add_responsive_control(
			'_ob_imbox_margin_cont',
			[
				'label' => __( 'Box Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		/* since 1.7.8 */
		// --------------------------------------------------------------------------------------------- Content Box Shadow 
		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_ob_imbox_cont_shadow', 
				'label' => __( 'Box Shadow', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'selector' => '{{WRAPPER}} .elementor-image-box-content', 
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

		/* since 1.7.0 */
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER
		$element->add_control(
			'_ob_imbox_popover_border',
			[
				'label' => __( 'Border', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
			]
		);
		
		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER ALL
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ob_imbox_borders', 
				'label' => __( 'Border', 'ooohboi-steroids' ), 
				'selector' => '{{WRAPPER}} .elementor-image-box-content', 
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL POPOVER BORDER RADIUS
		$element->add_responsive_control(
			'_ob_imbox_border_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS, 
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-content'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->end_popover(); // popover BORdER end

		// --------------------------------------------------------------------------------------------- CONTROL Z-INDeX
		$element->add_control(
			'_ob_imbox_box_z_index',
			[
				'label' => __( 'Box z-index', 'ooohboi-steroids' ),
				'type' => Controls_Manager::NUMBER, 
				'separator' => 'before', 
				'min' => -9999,
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-content' => 'z-index: {{VALUE}}; position: relative;',
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Yes 4 more controls
		$element->add_control(
			'_ob_imbox_overrides',
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
			'_ob_imbox_padding_title',
			[
				'label' => __( 'Title Padding', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'vw', 'vh', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-content .elementor-image-box-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_imbox_overrides' => 'yes', 
                ],
			]
		);
        // --------------------------------------------------------------------------------------------- CONTROL Title Margin
		$element->add_responsive_control(
			'_ob_imbox_margin_title',
			[
				'label' => __( 'Title Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'vw', 'vh', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-content .elementor-image-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_imbox_overrides' => 'yes', 
                ],
			]
		);

        // --------------------------------------------------------------------------------------------- CONTROL Content Padding
		$element->add_responsive_control(
			'_ob_imbox_padding_text',
			[
				'label' => __( 'Description Padding', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'vw', 'vh', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-content .elementor-image-box-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_imbox_overrides' => 'yes', 
                ],
			]
		);
        // --------------------------------------------------------------------------------------------- CONTROL Content Margin
		$element->add_responsive_control(
			'_ob_imbox_margin_text',
			[
				'label' => __( 'Description Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'vw', 'vh', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-content .elementor-image-box-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'_ob_imbox_overrides' => 'yes', 
                ],
			]
		);

	}

}