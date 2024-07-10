<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Teleporter
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.3.0
 */
class OoohBoi_Teleporter {

	/**
	 * Initialize 
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/column/layout/before_section_end',  [ __CLASS__, 'ooohboi_column_teleport' ], 10, 2 );
        add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

    }

    public static function add_attributes( Element_Base $element ) {

        if ( 'column' !== $element->get_name() ) return;
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;

		$settings = $element->get_settings_for_display();
        if ( isset( $settings[ '_ob_teleporter_use' ] ) && $settings[ '_ob_teleporter_use' ]  ) {
            $element->add_render_attribute( '_wrapper', 'class', 'ob-is-teleporter' );
        }

    }
    
	public static function ooohboi_column_teleport( $element, $args ) {

		$element->add_control(
			'_ob_teleporter_plugin_title',
			[
				'label' => 'T E L E P O R T E R', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );

        // ------------------------------------------------------------------------- CONTROL: Yes 4 Teleporter !
		$element->add_control(
			'_ob_teleporter_use',
			[
                'label' => __( 'Enable Teleporter', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SWITCHER, 
                'frontend_available' => true, 
                'return_value' => 'use-teleporter',
				'default' => false,
                'separator' => 'before', 
			]
        );
        // ------------------------------------------------------------------------- CONTROL: Link or No?
        $element->add_control(
			'_ob_teleporter_link_type',
			[
				'label' => __( 'Link', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'separator' => 'before', 
				'options' => [
					'none' => __( 'None', 'ooohboi-steroids' ),
					'custom' => __( 'Custom URL', 'ooohboi-steroids' ),
                ],
                'condition' => [
					'_ob_teleporter_use' => 'use-teleporter',
				],
			]
		);
        // ------------------------------------------------------------------------- CONTROL: Link to...
		$element->add_control(
			'_ob_teleporter_link',
			[
				'label' => __( 'Link', 'ooohboi-steroids' ),
                'type' => Controls_Manager::URL, 
                'separator' => 'before', 
                'frontend_available' => true, 
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'ooohboi-steroids' ),
				'condition' => [
                    '_ob_teleporter_use' => 'use-teleporter',
					'_ob_teleporter_link_type' => 'custom',
				],
				'show_label' => false,
			]
        );
        // ------------------------------------------------------------------------- CONTROL: Cursor pointer
		$element->add_control(
			'_ob_teleporter_pointer',
			[
                'label' => __( 'Cursor Pointer', 'ooohboi-steroids' ), 
                'description' => __( 'Keep the default cursor or show pointer on Hover?', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SWITCHER,
                'default' => false, 
                'separator' => 'before', 
                'selectors' => [
                    '{{WRAPPER}}.ob-is-teleporter' => 'cursor: pointer;', 
                ],
                'condition' => [
					'_ob_teleporter_use' => 'use-teleporter',
				],
			]
		);
        // ------------------------------------------------------------------------- START 2 TABS Normal & Hover
		$element->start_controls_tabs( '_ob_teleporter_tabs' );

		// ------------------------------------------------------------------------- START TAB Normal
        $element->start_controls_tab(
            '_ob_teleporter_tab_normal',
            [
                'label' => __( 'Normal', 'ooohboi-steroids' ),
            ]
		);
		// ------------------------------------------------------------------------- CONTROL: content - X
		$element->add_responsive_control(
            '_ob_teleporter_move_content_xp',
            [
				'label' => __( 'Content Position - X', 'ooohboi-steroids' ), 
				'description' => __( 'You can enter any acceptable CSS value, for example: 50em, 300px, 100%, calc(100% - 300px).', 'ooohboi-steroids' ),
                'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'separator' => 'before', 
				'default' => 0, 
				'selectors' => [
					'{{WRAPPER}}.ob-is-teleporter .ob-tele-midget > .widget-wrap-children' => 'left: {{value}};',
				],
				'condition' => [
					'_ob_teleporter_use' => 'use-teleporter', 
				],
            ]
		);
		// ------------------------------------------------------------------------- CONTROL: content - Y
		$element->add_responsive_control(
            '_ob_teleporter_move_content_yp',
            [
				'label' => __( 'Content Position - Y', 'ooohboi-steroids' ), 
				'description' => __( 'You can enter any acceptable CSS value, for example: 50em, 300px, 100%, calc(100% - 300px).', 'ooohboi-steroids' ),
                'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 0, 
				'selectors' => [
					'{{WRAPPER}}.ob-is-teleporter .ob-tele-midget > .widget-wrap-children' => 'top: {{value}};',
				],
				'condition' => [
					'_ob_teleporter_use' => 'use-teleporter', 
				],
            ]
		);
        
        $element->end_controls_tab(); // Normal tab end

        // ------------------------------------------------------------------------- START TAB Hover
        $element->start_controls_tab(
            '_ob_teleporter_tab_hover',
            [
                'label' => __( 'Hover', 'ooohboi-steroids' ),
            ]
		);
		// ------------------------------------------------------------------------- CONTROL: content - X hover
		$element->add_responsive_control(
            '_ob_teleporter_move_content_xp_hover',
            [
                'label' => __( 'Content Position - X', 'ooohboi-steroids' ),
                'type' => Controls_Manager::TEXT,
				'label_block' => true, 
				'separator' => 'before', 
				'default' => 0, 
                'description' => __( 'You can enter any acceptable CSS value, for example: 50em, 300px, 100%, calc(100% - 300px).', 'ooohboi-steroids' ),
				'selectors' => [
					'{{WRAPPER}}.ob-is-teleporter:hover .ob-tele-midget > .widget-wrap-children' => 'left: {{value}};',
				],
				'condition' => [
					'_ob_teleporter_use' => 'use-teleporter', 
				],
            ]
		);
		// ------------------------------------------------------------------------- CONTROL: content - Y hover
		$element->add_responsive_control(
            '_ob_teleporter_move_content_yp_hover',
            [
                'label' => __( 'Content Position - Y', 'ooohboi-steroids' ),
                'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 0, 
                'description' => __( 'You can enter any acceptable CSS value, for example: 50em, 300px, 100%, calc(100% - 300px).', 'ooohboi-steroids' ),
				'selectors' => [
					'{{WRAPPER}}.ob-is-teleporter:hover .ob-tele-midget > .widget-wrap-children' => 'top: {{value}};',
				],
				'condition' => [
					'_ob_teleporter_use' => 'use-teleporter', 
				],
            ]
		);
		// ------------------------------------------------------------------------- CONTROL: Hover IMAGE pass to parent
		$element->add_control(
			'_ob_teleporter_pass',
			[
                'label' => __( 'Teleport to parent', 'ooohboi-steroids' ), 
                'description' => __( 'Column Hover event is teleported to the parent element instead!', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SWITCHER, 
                'return_value' => 'do-pass', 
                'default' => false, 
                'separator' => 'before', 
                'frontend_available' => true, 
                'condition' => [
					'_ob_teleporter_use' => 'use-teleporter',  
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL parent
        $element->add_control(
            '_ob_teleporter_pass_element',
            [
                'label' => __( 'Which parent?', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,  
				'default' => 'section', 
				'frontend_available' => true, 
				'separator' => 'after', 
                'options' => [
                    'section' => __( 'Section', 'ooohboi-steroids' ), 
                    'container' => __( 'Container', 'ooohboi-steroids' ), 
                ],
                'condition' => [
                    '_ob_teleporter_use' => 'use-teleporter', 
                    '_ob_teleporter_pass' => 'do-pass', 
				],
            ]
		);
		// ------------------------------------------------------------------------- CONTROL: Disable Tablet
		$element->add_control(
			'_ob_teleporter_no_pass_tablet',
			[
                'label' => __( 'Disable on Tablet', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SWITCHER, 
                'return_value' => 'no-tablet', 
                'default' => false, 
				'frontend_available' => true, 
                'condition' => [
                    '_ob_teleporter_use' => 'use-teleporter', 
                    '_ob_teleporter_pass' => 'do-pass', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Disable Mobile
		$element->add_control(
			'_ob_teleporter_no_pass_mobile',
			[
                'label' => __( 'Disable on Mobile', 'ooohboi-steroids' ), 
                'type' => Controls_Manager::SWITCHER, 
                'return_value' => 'no-mobile', 
                'default' => false, 
                'separator' => 'after', 
				'frontend_available' => true, 
                'condition' => [
                    '_ob_teleporter_use' => 'use-teleporter', 
                    '_ob_teleporter_pass' => 'do-pass', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL BACKGROUND
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => '_ob_teleporter_bg_img',
				'selector' => '{{WRAPPER}}.ob-is-teleporter div[class*="ob-teleporter-"]', 
				'condition' => [
                    '_ob_teleporter_use' => 'use-teleporter', 
                    '_ob_teleporter_pass' => 'do-pass', 
				],
            ]
		);
		// ------------------------------------------------------------------------- CONTROL: Overlay COLOR
		$element->add_control(
			'_ob_teleporter_overlay_color',
			[
				'label' => __( 'Overlay Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR, 
				'frontend_available' => true, 
				'default' => '#0000004D',
				'selectors' => [
					'{{WRAPPER}}.ob-is-teleporter div[class*="ob-teleporter-"] > .ob-tele-overlay' => 'background-color: {{VALUE}};', 
				],
				'condition' => [
                    '_ob_teleporter_use' => 'use-teleporter', 
                    '_ob_teleporter_pass' => 'do-pass', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: effect
        $element->add_control(
			'_ob_teleporter_pass_effect',
			[
				'label' => __( 'Hover effect', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'frontend_available' => true, 
                'separator' => 'before', 
				'options' => [
					'fade' => __( 'Fade', 'ooohboi-steroids' ), 
					'zoom' => __( 'Zoom', 'ooohboi-steroids' ), 
					'stutter' => __( 'Stutter', 'ooohboi-steroids' ), 
                ],
                'condition' => [
                    '_ob_teleporter_use' => 'use-teleporter', 
                    '_ob_teleporter_pass' => 'do-pass', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL Transition duration
		$element->add_control(
            '_ob_butterbutton_transitions',
            [
				'label' => __( 'Effect Duration', 'ooohboi-steroids' ),
				'separator' => 'before', 
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template', 
				'default' => [
					'size' => 250,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-teleporter .widget-wrap-children, .ob-tele-eff-fade, .ob-tele-eff-zoom, .ob-tele-eff-stutter' => 'transition-duration: {{SIZE}}ms;', 
				],
				'condition' => [
                    '_ob_teleporter_use' => 'use-teleporter', 
				],
			]
		);
        
        $element->end_controls_tab(); // Hover tab end 

		$element->end_controls_tabs(); // Normal & Hover tabs end
		
		// ------------------------------------------------------------------------- CONTROL: Visibility
		$element->add_control(
			'_ob_teleporter_visibility',
			[
				'label' => __( 'Content Overflow', 'ooohboi-steroids' ),
				'description' => __( 'Strongly advised: HIDDEN.', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hidden',
				'separator' => 'before', 
				'options' => [
					'visible' => __( 'Visible', 'ooohboi-steroids' ), 
					'hidden' => __( 'Hidden', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-teleporter' => 'overflow: {{value}};',
				],
				'condition' => [
					'_ob_teleporter_use' => 'use-teleporter', 
				],
			]
		);

    }

}