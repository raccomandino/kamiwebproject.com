<?php
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Main OoohBoi Typo
 *
 *
 * @since 2.0.4
 */
class OoohBoi_Typo {

	/**
	 * Initialize 
	 *
	 * @since 2.0.4
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/kit/section_typography/before_section_end',  [ __CLASS__, 'ooohboi_typo_opts' ], 10, 1 ); 

    }
    
	public static function ooohboi_typo_opts( $element ) {

        /* BODY */

        $element->start_injection( [
			'of' => 'paragraph_spacing',
            'at' => 'after',
        ] );
		
		// --------------------------------------------------------------------------------------------- CONTROL: Remove horizontal scroller
		$element->add_control(
			'_ob_remove_horiz_scroll',
			[
                'label' => esc_html__( 'Remove horizontal scroller?', 'ooohboi-steroids' ), 
				'separator' => 'before', 
                'type' => Controls_Manager::SELECT,
                'default' => 'inherit', 
                'options' => [
					'hidden' => esc_html__( 'Remove', 'ooohboi-steroids' ), 
                    'inherit' => esc_html__( 'Leave', 'ooohboi-steroids' ),
                ],
                'selectors' => [
                    'body' => 'overflow-x: {{VALUE}} !important;', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL: Remove link underlines
		$element->add_control(
			'_ob_remove_link_underlines',
			[
                'label' => esc_html__( 'Remove link underlines?', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SELECT,
                'default' => 'inherit', 
                'options' => [
					'none' => esc_html__( 'Remove', 'ooohboi-steroids' ), 
                    'inherit' => esc_html__( 'Leave', 'ooohboi-steroids' ),
                ],
                'selectors' => [
                    '.elementor a, .elementor a:link, .elementor a:focus, .elementor a:active, .elementor a:hover' => 'text-decoration: {{VALUE}} !important;', 
                ],
			]
		);

        $element->end_injection();

        /* Links */

        $element->start_injection( [
			'of' => 'link_normal_typography_typography',
            'at' => 'after',
        ] );

		// --------------------------------------------------------------------------------------------- CONTROL: Smooth link hovers
		$element->add_control(
			'_ob_smooth_link_hovers',
			[
                'label' => esc_html__( 'Smooth link hovers?', 'ooohboi-steroids' ), 
                'description' => esc_html__( 'TIP: You can add ".smooth-hover" custom class name to any widget in order to add smooth-hover behaviour to its hyperlinks', 'ooohboi-steroids' ),
				'separator' => 'before', 
                'type' => Controls_Manager::SELECT,
                'default' => 'inherit', 
                'options' => [
					'all 0.25s ease-in-out' => esc_html__( 'Yes', 'ooohboi-steroids' ), 
                    'inherit' => esc_html__( 'No', 'ooohboi-steroids' ),
                ],
                'selectors' => [
                    '.elementor a:link, .elementor .smooth-hover a:link, ' => 'transition: {{VALUE}};', 
                ],
			]
		);

        $element->end_injection();

	}

}