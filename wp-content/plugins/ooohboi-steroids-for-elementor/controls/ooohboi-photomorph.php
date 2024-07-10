<?php
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi OoohBoi_PhotoMorph
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.5.0
 */
class OoohBoi_PhotoMorph {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.5.0
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/image/section_style_image/before_section_end',  [ __CLASS__, 'ooohboi_photomorph_get_controls' ], 10, 2 ); 
        add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

        /* should enqueue? */
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/photomorph-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_photomorph_use' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

    public static function add_attributes( $element ) {

        if ( 'image' !== $element->get_name() ) return;
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;

		$settings = $element->get_settings_for_display();
		
		if ( isset( $settings[ '_ob_photomorph_use' ] ) && 'yes' === $settings[ '_ob_photomorph_use' ] ) {

            $element->add_render_attribute( '_wrapper', [
                'class' => 'ob-photomorph'
            ] );

        }

    }
    
	public static function ooohboi_photomorph_get_controls( $element, $args ) {

		$element->add_control(
			'_ob_photomorph',
			[
				'label' => 'P H O T O M O R P H', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );

        // ------------------------------------------------------------------------- CONTROL: Yes 4 PhotoMorph !
		$element->add_control(
			'_ob_photomorph_use',
			[
                'label' => __( 'Enable PhotoMorph', 'ooohboi-steroids' ), 
                'description' => __( 'That will allow you to add the custom clip-path for both Normal and Hover Image widget.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
                'default' => 'no', 
                'frontend_available' => true, 
			]
        );
        
        // ------------------------------------------------------------------------- START 2 TABS Normal & Hover
		$element->start_controls_tabs( '_ob_photomorph_tabs' );

		// ------------------------------------------------------------------------- START TAB Normal
        $element->start_controls_tab(
            '_ob_photomorph_tab_normal',
            [
                'label' => __( 'Normal', 'ooohboi-steroids' ),
            ]
        );
        
        $element->add_control(
			'_ob_photomorph_clip_path_normal',
			[				
				'description' => sprintf(
                    __( 'Enter the full clip-path property! See the copy-paste examples at %sClippy%s', 'ooohboi-steroids' ),
                    '<a href="https://bennettfeely.com/clippy/" target="_blank">',
                    '</a>'
				),
				'default' => '', 
				'type' => Controls_Manager::TEXTAREA, 
				'rows' => 3, 
				'selectors' => [
					'{{WRAPPER}}.ob-photomorph img' => '{{VALUE}}',
				],
				'condition' => [
					'_ob_photomorph_use' => 'yes', 
                ],
			]
		);

        $element->end_controls_tab(); // Normal tab end

        // ------------------------------------------------------------------------- START TAB Hover
        $element->start_controls_tab(
            '_ob_photomorph_tab_hover',
            [
                'label' => __( 'Hover', 'ooohboi-steroids' ),
            ]
        );
        
        $element->add_control(
			'_ob_photomorph_clip_path_hover',
			[				
				'description' => sprintf(
                    __( 'Enter the full clip-path property! See the copy-paste examples at %sClippy%s', 'ooohboi-steroids' ),
                    '<a href="https://bennettfeely.com/clippy/" target="_blank">',
                    '</a>'
				),
				'default' => '', 
				'type' => Controls_Manager::TEXTAREA, 
				'rows' => 3, 
				'selectors' => [
					'{{WRAPPER}}.ob-photomorph:hover img' => '{{VALUE}}',
				],
				'condition' => [
					'_ob_photomorph_use' => 'yes', 
                ],
			]
        );
        
        // ------------------------------------------------------------------------- CONTROL: Animate clip-path
		$element->add_control(
			'_ob_photomorph_animate',
			[
                'label' => __( 'Animate Hover?', 'ooohboi-steroids' ), 
                'description' => __( 'To ensure the smooth transition, be sure that the number of nodes of the Normal state clip-path EQUALS the number of nodes of the Hover state!', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
                'default' => 'no', 
                'condition' => [
					'_ob_photomorph_use' => 'yes', 
                ],
			]
        );

        // --------------------------------------------------------------------------------------------- CONTROL Animation Duration
		$element->add_responsive_control(
            '_ob_photomorph_anim_duration',
            [
				'label' => __( 'Animation duration', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template', 
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
					],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ob-photomorph img' => 'transition: clip-path {{SIZE}}s {{_ob_photomorph_anim_easing.VALUE}} {{_ob_photomorph_anim_delay.SIZE}}s;',
				],
                'condition' => [
                    '_ob_photomorph_use' => 'yes', 
                    '_ob_photomorph_animate' => 'yes', 
                ],
			]
        );
        
        // ------------------------------------------------------------------------- CONTROL: Animation easing
        $element->add_control(
			'_ob_photomorph_anim_easing',
			[
				'label' => __( 'Animation effect', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ease',
				'frontend_available' => true, 
                'separator' => 'before', 
				'options' => [
					'ease' => __( 'Default', 'ooohboi-steroids' ), 
					'ease-in' => __( 'Ease-in', 'ooohboi-steroids' ), 
                    'ease-out' => __( 'Ease-out', 'ooohboi-steroids' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'ooohboi-steroids' ), 
                ],
                'selectors' => [
                    '{{WRAPPER}}.ob-photomorph img' => 'transition: clip-path {{_ob_photomorph_anim_duration.SIZE}}s {{VALUE}} {{_ob_photomorph_anim_delay.SIZE}}s;',
				],
                'condition' => [
                    '_ob_photomorph_use' => 'yes', 
                    '_ob_photomorph_animate' => 'yes', 
                ],
			]
        );
        
        // --------------------------------------------------------------------------------------------- CONTROL Animation Delay
		$element->add_responsive_control(
            '_ob_photomorph_anim_delay',
            [
				'label' => __( 'Animation delay', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template', 
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
					],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ob-photomorph img' => 'transition: clip-path {{_ob_photomorph_anim_duration.SIZE}}s {{_ob_photomorph_anim_easing.VALUE}} {{SIZE}}s;',
				],
                'condition' => [
                    '_ob_photomorph_use' => 'yes', 
                    '_ob_photomorph_animate' => 'yes', 
                ],
			]
        );

        $element->end_controls_tab(); // Hover tab end 

		$element->end_controls_tabs(); // Normal & Hover tabs end

	}

}