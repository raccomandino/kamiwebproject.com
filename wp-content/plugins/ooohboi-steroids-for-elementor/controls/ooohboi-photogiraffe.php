<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi PhotoGiraffe
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class OoohBoi_PhotoGiraffe {

	/**
	 * Initialize 
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/image/section_style_image/before_section_end',  [ __CLASS__, 'ooohboi_photogiraffe_get_controls' ], 10, 2 );

    }
    
	public static function ooohboi_photogiraffe_get_controls( $element, $args ) {

		$element->add_control(
			'_ob_photogiraffe_plugin_title',
			[
				'label' => 'P H O T O G I R A F F E', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );

        // ------------------------------------------------------------------------- CONTROL: Yes 4 PhotoGiraffe !
		$element->add_control(
			'_ob_photogiraffe_use',
			[
                'label' => __( 'Enable PhotoGiraffe', 'ooohboi-steroids' ), 
                'description' => __( 'That will make the photo full-height. Be sure that the parent conteiner (Column) uses the fixed height.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'default' => false,
                'separator' => 'before', 
                'selectors' => [
                    '{{WRAPPER}}, {{WRAPPER}} .elementor-widget-container, {{WRAPPER}} .elementor-widget-container > a, {{WRAPPER}} .elementor-image, {{WRAPPER}} .elementor-image > a' => 'height: 100%;', 
                    '{{WRAPPER}} .elementor-image > img, {{WRAPPER}} .elementor-widget-container > img' => 'object-fit: cover; object-position: 50% 50%; height: 100%;', 
				],
			]
		);

	}

}