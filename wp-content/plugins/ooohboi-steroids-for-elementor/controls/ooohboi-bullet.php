<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Bullet
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.8.2
 */
class OoohBoi_Bullet {

	/**
	 * Initialize 
	 *
	 * @since 1.4.6
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/icon-list/section_icon_style/before_section_end',  [ __CLASS__, 'ooohboi_handle_bullet' ], 10, 2 );

    }
    
	public static function ooohboi_handle_bullet( $element, $args ) {

		$element->add_control(
			'_ob_bullet',
			[
				'label' => 'B U L L E T', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );

        // ------------------------------------------------------------------------- CONTROL: Moves bullet to top!
		$element->add_control(
			'_ob_bullet_move_top',
			[
				'label' => __( 'Move bullet to top', 'ooohboi-steroids' ), 
				'description' => __( 'NOTE: Takes effect with more than just one line of text!', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ), 
				'return_value' => 'inherit', 
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item, {{WRAPPER}} .elementor-icon-list-item a' => 'align-items: start;', 
                ],
			]
        );
		// ------------------------------------------------------------------------- CONTROL: Bullet top margin
		$element->add_responsive_control(
			'_ob_bullet_top_margin',
            [
				'label' => __( 'Margin Top', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'max' => 10,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-item .elementor-icon-list-icon, {{WRAPPER}} .elementor-icon-list-item a .elementor-icon-list-icon' => 'margin-top: {{SIZE}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_bullet_move_top' => 'inherit',
				],
			]
		);

	}

}