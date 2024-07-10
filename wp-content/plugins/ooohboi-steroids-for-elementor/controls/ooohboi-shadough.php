<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Shadough
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.4.6
 */
class OoohBoi_Shadough {

	/**
	 * Initialize 
	 *
	 * @since 1.4.6
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/common/_section_background/after_section_end',  [ __CLASS__, 'ooohboi_shadough_controls' ], 10 );

    }
    
	public static function ooohboi_shadough_controls( Element_Base $element ) {

        $element->start_controls_section(
            '_ob_shadough',
            [
                'label' => 'S H A D O U G H',
				'tab' => Controls_Manager::TAB_ADVANCED, 
            ]
		);

        // ------------------------------------------------------------------------- CONTROL: Yes 4 Shadough !
		$element->add_control(
			'_ob_shadough_use',
			[
                'label' => __( 'Enable Shadough?', 'ooohboi-steroids' ), 
                'description' => __( 'Creates a shadow that conforms to the shape.', 'ooohboi-steroids' ), 
                'separator' => 'after', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
        );
        // ------------------------------------------------------------------------- CONTROL: Offset X
        $element->add_responsive_control(
            '_ob_shadough_x',
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
					'{{WRAPPER}} .elementor-widget-container' => 'filter: drop-shadow({{SIZE}}{{UNIT}} {{_ob_shadough_y.SIZE}}{{_ob_shadough_y.UNIT}} {{_ob_shadough_blur.SIZE}}{{_ob_shadough_blur.UNIT}} {{_ob_shadough_color.VALUE}});', 
                ],
                'condition' => [
					'_ob_shadough_use' => 'yes', 
				],
			]
        );
        // ------------------------------------------------------------------------- CONTROL: Offset Y
        $element->add_responsive_control(
            '_ob_shadough_y',
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
					'{{WRAPPER}} .elementor-widget-container' => 'filter: drop-shadow({{_ob_shadough_x.SIZE}}{{_ob_shadough_x.UNIT}} {{SIZE}}{{UNIT}} {{_ob_shadough_blur.SIZE}}{{_ob_shadough_blur.UNIT}} {{_ob_shadough_color.VALUE}});', 
                ],
                'condition' => [
					'_ob_shadough_use' => 'yes', 
				],
			]
        );
        // ------------------------------------------------------------------------- CONTROL: Blur
        $element->add_responsive_control(
            '_ob_shadough_blur',
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
					'{{WRAPPER}} .elementor-widget-container' => 'filter: drop-shadow({{_ob_shadough_x.SIZE}}{{_ob_shadough_x.UNIT}} {{_ob_shadough_y.SIZE}}{{_ob_shadough_y.UNIT}} {{SIZE}}{{UNIT}} {{_ob_shadough_color.VALUE}});', 
                ],
                'condition' => [
					'_ob_shadough_use' => 'yes', 
				],
			]
        );
        
        // ------------------------------------------------------------------------- CONTROL: COLOR
		$element->add_control(
			'_ob_shadough_color',
			[
				'label' => __( 'Shadow Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0000001C',
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: drop-shadow({{_ob_shadough_x.SIZE}}{{_ob_shadough_x.UNIT}} {{_ob_shadough_y.SIZE}}{{_ob_shadough_y.UNIT}} {{_ob_shadough_blur.SIZE}}{{_ob_shadough_blur.UNIT}} {{VALUE}});',
                ],
                'condition' => [
					'_ob_shadough_use' => 'yes', 
				],
			]
		);
        
        $element->end_controls_section(); // END SECTION / PANEL

	}

}