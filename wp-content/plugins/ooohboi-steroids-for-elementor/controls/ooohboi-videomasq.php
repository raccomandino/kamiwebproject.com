<?php
use Elementor\Controls_Manager;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Videomasq
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class OoohBoi_Videomasq {

	/**
	 * Initialize 
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/section/section_background/before_section_end',  [ __CLASS__, 'ooohboi_videomasq_get_controls' ], 10, 2 );
		add_action( 'elementor/element/container/section_background/before_section_end',  [ __CLASS__, 'ooohboi_videomasq_get_controls' ], 10, 2 );

	}
	
	public static function ooohboi_videomasq_get_controls( $element, $args ) {

		// selector based on the current element
		$selector = '{{WRAPPER}} .elementor-background-video-container'; 


		$element->add_control(
			'_ob_videomasq_plugin_title',
			[
				'label' => 'V I D E O M A S Q', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
				'condition' => [
                    'background_background' => [ 'video' ],
				],
			]
        );

		// --------------------------------------------------------------------------------------------- CONTROL VIDEOMASQ IMAGE
		$element->add_responsive_control(
			'_ob_videomasq_mask_img',
			[
				'label' => __( 'Choose Video Mask', 'ooohboi-steroids' ),
				'description' => __( 'NOTE: The video mask should be black-and-transparent SVG file! Anything that’s 100% black in the mask image with be completely visible, anything that’s transparent will be completely hidden.', 'ooohboi-steroids' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'selectors' => [
					$selector => '-webkit-mask-image: url("{{URL}}"); mask-image: url("{{URL}}"); -webkit-mask-mode: alpha; mask-mode: alpha;',
				],
				'condition' => [
					'background_background' => [ 'video' ], 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL VIDEOMASQ POSITION
		$element->add_responsive_control(
			'_ob_videomasq_mask_position',
			[
				'label' => __( 'Mask position', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center center',
				'options' => [
					'' => __( 'Default', 'ooohboi-steroids' ),
					'center center' => __( 'Center Center', 'ooohboi-steroids' ),
					'center left' => __( 'Center Left', 'ooohboi-steroids' ),
					'center right' => __( 'Center Right', 'ooohboi-steroids' ),
					'top center' => __( 'Top Center', 'ooohboi-steroids' ),
					'top left' => __( 'Top Left', 'ooohboi-steroids' ),
					'top right' => __( 'Top Right', 'ooohboi-steroids' ),
					'bottom center' => __( 'Bottom Center', 'ooohboi-steroids' ),
					'bottom left' => __( 'Bottom Left', 'ooohboi-steroids' ),
					'bottom right' => __( 'Bottom Right', 'ooohboi-steroids' ),
				],
				'selectors' => [
					$selector => '-webkit-mask-position: {{VALUE}}; mask-position: {{VALUE}};',
				],
				'condition' => [
					'_ob_videomasq_mask_img[url]!' => '',
					'background_background' => [ 'video' ], 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL VIDEOMASQ SIZE
		$element->add_responsive_control(
			'_ob_videomasq_mask_size',
			[
				'label' => __( 'Mask size', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'contain', 
				'options' => [
					'' => __( 'Default', 'ooohboi-steroids' ),
					'auto' => __( 'Auto', 'ooohboi-steroids' ),
					'cover' => __( 'Cover', 'ooohboi-steroids' ),
					'contain' => __( 'Contain', 'ooohboi-steroids' ),
					'initial' => __( 'Custom', 'ooohboi-steroids' ),
				],
				'selectors' => [
					$selector => '-webkit-mask-size: {{VALUE}}; mask-size: {{VALUE}};',
				],
				'condition' => [
					'_ob_videomasq_mask_img[url]!' => '',
					'background_background' => [ 'video' ], 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL VIDEOMASQ SIZE Custom
		$element->add_responsive_control(
			'_ob_videomasq_mask_size_width', 
			[
				'label' => __( 'Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					$selector => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
				],
				'condition' => [
					'_ob_videomasq_mask_size' => [ 'initial' ],
					'_ob_videomasq_mask_img[url]!' => '',
					'background_background' => [ 'video' ], 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							$selector => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
							'_ob_videomasq_mask_size' => [ 'initial' ],
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							$selector => '-webkit-mask-size: {{SIZE}}{{UNIT}} auto; mask-size: {{SIZE}}{{UNIT}} auto;',
						],
						'condition' => [
							'_ob_videomasq_mask_size' => [ 'initial' ], 
						],
					],
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL VIDEOMASQ REPEAT
		$element->add_responsive_control(
			'_ob_videomasq_mask_repeat',
			[
				'label' => __( 'Mask repeat', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no-repeat',
				'options' => [
					'no-repeat' => __( 'No-repeat', 'ooohboi-steroids' ),
					'repeat' => __( 'Repeat', 'ooohboi-steroids' ),
					'repeat-x' => __( 'Repeat-x', 'ooohboi-steroids' ),
					'repeat-y' => __( 'Repeat-y', 'ooohboi-steroids' ),
				],
				'selectors' => [
					$selector => '-webkit-mask-repeat: {{VALUE}}; mask-repeat: {{VALUE}};',
				],
				'condition' => [
					'_ob_videomasq_mask_img[url]!' => '',
					'background_background' => [ 'video' ], 
				],
			]
		);

    }

}