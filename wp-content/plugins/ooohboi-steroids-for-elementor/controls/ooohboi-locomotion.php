<?php
use Elementor\Controls_Manager; 
use Elementor\Element_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main OoohBoi Locomotion Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.7.3
 */
class OoohBoi_Locomotion {


    public function __construct() {

        add_action( 'elementor/element/common/_section_background/after_section_end', [ $this, 'ob_attributes_controls_section' ] ); 
        add_action( 'elementor/element/section/section_advanced/after_section_end',  [ $this, 'ob_attributes_controls_section' ] ); 
        add_action( 'elementor/element/column/section_advanced/after_section_end',  [ $this, 'ob_attributes_controls_section' ] );
		add_action( 'elementor/element/container/section_layout/after_section_end',  [ $this, 'ob_attributes_controls_section' ] );
		add_action( 'elementor/frontend/before_render', [ $this, 'ob_render_attributes' ] ); 

	}

    public function ob_attributes_controls_section( Element_Base $element ) {

        $element->start_controls_section(
            '_ob_loco_title_section',
            [
                'label' => 'L O C O M O T I O N',
                'tab' => Controls_Manager::TAB_ADVANCED, 
            ]
        );
        
        $element->add_control(
            '_ob_loco_attributes',
            [
                'label' => __( 'Custom Attributes', 'ooohboi-steroids' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'separator' => 'before', 
                'render_type' => 'none', 
                'classes' => 'elementor-control-direction-ltr', 
                'placeholder' => __( 'key|value', 'ooohboi-steroids' ),
                'description' => sprintf( __( 'Set custom attributes for the current element/widget. Each attribute must be in a separate line! The attribute key and the value MUST be separated by the pipe character, i.e. data-scroll-offset%s100,50em', 'ooohboi-steroids' ), '<code>|</code>' ),
            ]
        );

        $element->end_controls_section();

	}

    public function ob_render_attributes( $element ) {
		
		$settings = $element->get_settings_for_display();
		
		if( isset( $settings[ '_ob_loco_attributes' ] ) && ! empty( $settings[ '_ob_loco_attributes' ] ) ) {

			$attributes = $this->parse_custom_attributes( $settings[ '_ob_loco_attributes' ], "\n" );
			$black_list = $this->get_banned_attributes();
			
			foreach( $attributes as $attribute => $value ) {
				if( ! in_array( $attribute, $black_list, true ) ) {
					$element->add_render_attribute( '_wrapper', $attribute, $value );
				}
			}
            
		}

	}

    private function parse_custom_attributes( $attributes_string, $delimiter = ',' ) {

		$attributes = explode( $delimiter, $attributes_string );
		$result = [];
		
		foreach( $attributes as $attribute ) {

			$attr_key_value = explode( '|', $attribute );
			$attr_key = mb_strtolower( $attr_key_value[ 0 ] );

			preg_match( '/[-_a-z0-9]+/', $attr_key, $attr_key_matches );
			if( empty( $attr_key_matches[ 0 ] ) ) continue;
			
			$attr_key = $attr_key_matches[ 0 ];
			if( 'href' === $attr_key || 'on' === substr( $attr_key, 0, 2 ) ) continue;
			
			if( isset( $attr_key_value[ 1 ] ) ) $attr_value = trim( $attr_key_value[ 1 ] );
			else $attr_value = '';

			$result[ $attr_key ] = $attr_value;
		}

		return $result;

	}

    private function get_banned_attributes() {

		static $banned = NULL;
		if( NULL === $banned ) $banned = [ 'id', 'class', 'data-id', 'data-settings', 'data-element_type', 'data-widget_type', 'data-model-cid' ];

		return $banned;

	}

}