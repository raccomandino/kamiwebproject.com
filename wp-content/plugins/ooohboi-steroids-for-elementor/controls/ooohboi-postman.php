<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
/*
use Elementor\Core\Schemes\Typography;
*/
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi OoohBoi_Postman
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 2.0.0
 */
class OoohBoi_Postman {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/theme-post-content/section_style/after_section_end',  [ __CLASS__, 'add_section' ] ); 
		add_action( 'elementor/element/text-editor/section_style/after_section_end',  [ __CLASS__, 'add_section' ] ); 
        add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

        /* should enqueue? */
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/postman-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_postman_use' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

    public static function add_attributes( $element ) {

		if( ! in_array( $element->get_name(), [ 'theme-post-content', 'text-editor' ] ) ) return;
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;

		$settings = $element->get_settings_for_display();
		
		if ( isset( $settings[ '_ob_postman_use' ] ) && 'yes' === $settings[ '_ob_postman_use' ] ) {

            $element->add_render_attribute( '_wrapper', [
                'class' => 'ob-postman'
            ] );

        }

    }
    
	public static function add_section( Element_Base $element ) {

		$element->start_controls_section(
			'_ob_postman',
			[
				'label' => 'P O S T M A N', 
				'type' => Controls_Manager::TAB_CONTENT,
			]
        );

        // ------------------------------------------------------------------------- CONTROL: Yes 4 Comments !
		$element->add_control(
			'_ob_postman_use',
			[
                'label' => __( 'Enable Postman', 'ooohboi-steroids' ), 
                'description' => __( 'That will allow you to style up the elements of Post content', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
                'default' => 'no', 
                'separator' => 'after', 
                'frontend_available' => true, 
			]
        );

		// --------------------------------------------------------------------------------------------- CONTROL Paragraph styles
		$element->add_control(
			'_ob_postman_paragraph_popover',
			[
				'label' => __( 'Paragraph styles', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'frontend_available' => true, 
				'return_value' => 'yes', 
				'condition' => [
					'_ob_postman_use' => 'yes', 
                ],
			]
		);

        $element->start_popover();

        $element->add_responsive_control(
			'_ob_postman_paragraph_padding',
			[
				'label' => __( 'Paragraph padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_paragraph_popover' => 'yes', 
				],
			]
        );
        $element->add_responsive_control(
			'_ob_postman_paragraph_margin',
			[
				'label' => __( 'Paragraph margins', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_paragraph_popover' => 'yes', 
				],
			]
        );

        $element->end_popover(); // popover paragraphs margin


		// --------------------------------------------------------------------------------------------- CONTROL Heading styles
		$element->add_control(
			'_ob_postman_headings_popover',
			[
				'label' => __( 'Heading styles', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'frontend_available' => true, 
				'return_value' => 'yes', 
				'condition' => [
					'_ob_postman_use' => 'yes', 
                ],
			]
		);

        $element->start_popover();

        // --------------------------------------------------------------------------------------------- CONTROL: Headings margin
        $element->add_responsive_control(
			'_ob_postman_headings_margin_h1',
			[
				'label' => __( 'H1 Margins', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container h1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_headings_popover' => 'yes', 
				],
			]
        );
        $element->add_responsive_control(
			'_ob_postman_headings_margin_h2',
			[
				'label' => __( 'H2 Margins', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_headings_popover' => 'yes', 
				],
			]
        );
        $element->add_responsive_control(
			'_ob_postman_headings_margin_h3',
			[
				'label' => __( 'H3 Margins', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_headings_popover' => 'yes', 
				],
			]
        );
        $element->add_responsive_control(
			'_ob_postman_headings_margin_h4',
			[
				'label' => __( 'H4 Margins', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_headings_popover' => 'yes', 
				],
			]
        );
        $element->add_responsive_control(
			'_ob_postman_headings_margin_h5',
			[
				'label' => __( 'H5 Margins', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container h5' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_headings_popover' => 'yes', 
				],
			]
        );
        $element->add_responsive_control(
			'_ob_postman_headings_margin_h6',
			[
				'label' => __( 'H6 Margins', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_headings_popover' => 'yes', 
				],
			]
        );
        $element->add_responsive_control(
			'_ob_postman_headings_padding_all',
			[
				'label' => __( 'Padding - all', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container h1, 
                    {{WRAPPER}}.ob-postman .elementor-widget-container h2, 
                    {{WRAPPER}}.ob-postman .elementor-widget-container h3, 
                    {{WRAPPER}}.ob-postman .elementor-widget-container h4, 
                    {{WRAPPER}}.ob-postman .elementor-widget-container h5, 
                    {{WRAPPER}}.ob-postman .elementor-widget-container h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_headings_popover' => 'yes', 
				],
			]
        );

		$element->end_popover(); // popover headings margin

		// --------------------------------------------------------------------------------------------- CONTROL lists styles
		$element->add_control(
			'_ob_postman_lists_popover',
			[
				'label' => __( 'Lists styles: UL and OL', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'frontend_available' => true, 
				'return_value' => 'yes', 
				'condition' => [
					'_ob_postman_use' => 'yes', 
                ],
			]
		);

        $element->start_popover();

        $element->add_responsive_control(
			'_ob_postman_lists_padding',
			[
				'label' => __( 'Padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container > ul, 
					{{WRAPPER}}.ob-postman .elementor-widget-container > ol,
					{{WRAPPER}}.ob-postman .elementor-text-editor > ul, 
					{{WRAPPER}}.ob-postman .elementor-text-editor > ol' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_lists_popover' => 'yes', 
				],
			]
        );

        $element->add_responsive_control(
			'_ob_postman_lists_margin',
			[
				'label' => __( 'Margins', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container ul, {{WRAPPER}}.ob-postman .elementor-widget-container ol' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_lists_popover' => 'yes', 
				],
			]
        );
        $element->add_responsive_control(
			'_ob_postman_lists_margin_nested',
			[
				'label' => __( 'Margins - nested lists', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container ul li ul, 
                    {{WRAPPER}}.ob-postman .elementor-widget-container ol li ol, 
                    {{WRAPPER}}.ob-postman .elementor-widget-container ul li ol, 
                    {{WRAPPER}}.ob-postman .elementor-widget-container ol li ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_lists_popover' => 'yes', 
				],
			]
        );
        $element->add_responsive_control(
			'_ob_postman_lists_spacing',
			[
				'label' => __( 'Items spacing', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_lists_popover' => 'yes', 
				],
			]
        );
		$element->add_responsive_control(
			'_ob_postman_lists_type_ul',
			[
				'label' => __( 'List type UL', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'inherit', 
				'options' => [
                    'inherit' => __( 'Default', 'ooohboi-steroids' ),
					'circle' => __( 'Circle', 'ooohboi-steroids' ),
					'square' => __( 'Square', 'ooohboi-steroids' ), 
					'disc' => __( 'Disc', 'ooohboi-steroids' ), 
                    'none' => __( 'Custom', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container ul' => 'list-style-type: {{VALUE}};',
				],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_lists_popover' => 'yes', 
				],
			]
		);
		$element->add_responsive_control(
			'_ob_postman_lists_type_ul_alt',
			[
				'label' => __( 'List type UL - custom', 'ooohboi-steroids' ),
                'description' => __( 'It has to be Unicode HEX escape, like \2192, or \00A9', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::TEXT, 
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container ul > li:before' => 'content: "{{VALUE}}\00a0 ";', 
                    '{{WRAPPER}}.ob-postman .elementor-widget-container ul > li' => 'display: block;', 
                    '{{WRAPPER}}.ob-postman .elementor-widget-container ul' => 'padding: 0;', 
				],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_lists_popover' => 'yes', 
                    '_ob_postman_lists_type_ul' => 'none', 
				],
			]
		);
		$element->add_responsive_control(
			'_ob_postman_lists_type_ol',
			[
				'label' => __( 'List type OL', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'inherit', 
				'options' => [
                    'inherit' => __( 'Default', 'ooohboi-steroids' ),
					'decimal' => __( 'Decimal', 'ooohboi-steroids' ),
					'georgian' => __( 'Georgian', 'ooohboi-steroids' ), 
                    'upper-alpha' => __( 'Upper alpha', 'ooohboi-steroids' ), 
                    'none' => __( 'Custom', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container ol' => 'list-style-type: {{VALUE}};',
				],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_lists_popover' => 'yes', 
				],
			]
		);
		$element->add_responsive_control(
			'_ob_postman_lists_type_ol_alt',
			[
				'label' => __( 'List type OL - custom', 'ooohboi-steroids' ),
                'description' => __( 'It has to be Unicode HEX escape, like \2192, or \00A9', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT, 
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container ol > li:before' => 'content: "{{VALUE}}\00a0 ";', 
                    '{{WRAPPER}}.ob-postman .elementor-widget-container ol > li' => 'display: block;', 
                    '{{WRAPPER}}.ob-postman .elementor-widget-container ol' => 'padding: 0;', 
				],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                    '_ob_postman_lists_popover' => 'yes', 
                    '_ob_postman_lists_type_ol' => 'none', 
				],
			]
		);

        $element->end_popover(); // popover lists style

		// --------------------------------------------------------------------------------------------- CONTROL quotes styles
        $element->add_control(
            '_ob_postman_quotes_fake_descr',
            [
                'type' => Controls_Manager::RAW_HTML, 
                'label' => __( 'Quotations', 'ooohboi-steroids' ),
                'raw' => __( 'Style up the Post Content Quotations', 'ooohboi-steroids' ), 
                'content_classes' => 'elementor-control-field-description', 
				'condition' => [
					'_ob_postman_use' => 'yes', 
				],
            ]
        );

        $element->add_control(
			'_ob_postman_quotes_color',
			[
				'label' => __( 'Quotation Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container blockquote' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                ],
			]
		);
        $element->add_responsive_control(
			'_ob_postman_quotes_margin',
			[
				'label' => __( 'Quotation margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container blockquote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                    '{{WRAPPER}}.ob-postman .elementor-widget-container blockquote p' => 'margin: 0;', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
				],
			]
        );
        $element->add_responsive_control(
			'_ob_postman_quotes_padding',
			[
				'label' => __( 'Quotation padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                    '{{WRAPPER}}.ob-postman .elementor-widget-container blockquote p' => 'padding: 0;', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
				],
			]
        );
        $element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_ob_postman_quotes_typography', 
				'label' => __( 'Quotation Typography', 'ooohboi-steroids' ), 
				'selector' => '{{WRAPPER}}.ob-postman .elementor-widget-container blockquote', 
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				], 
				'condition' => [
					'_ob_postman_use' => 'yes', 
				],
			]
		);

        $element->add_control(
			'_ob_postman_quotes_cite_color',
			[
				'label' => __( 'Cite color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container blockquote cite' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_ob_postman_use' => 'yes', 
                ],
			]
		);
        $element->add_responsive_control(
			'_ob_postman_quotes_cite_margin',
			[
				'label' => __( 'Cite margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-postman .elementor-widget-container blockquote cite' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; display: block;', 
                ],
				'condition' => [
					'_ob_postman_use' => 'yes', 
				],
			]
        );
        $element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_ob_postman_quotes_cite_typography', 
				'label' => __( 'Cite typography', 'ooohboi-steroids' ), 
				'selector' => '{{WRAPPER}}.ob-postman .elementor-widget-container blockquote cite', 
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				], 
				'condition' => [
					'_ob_postman_use' => 'yes', 
				],
			]
		);
		$element->add_group_control(
            Group_Control_Background::get_type(),
            [
				'name' => '_ob_postman_quotes_bg', 
                'types' => [ 'classic' ], 
                'selector' => '{{WRAPPER}}.ob-postman .elementor-widget-container blockquote::before',
				'condition' => [
					'_ob_postman_use' => 'yes', 
				],
            ]
		);
        $element->add_responsive_control(
            '_ob_postman_quotes_bg_opacity',
            [
                'label' => __( 'Background opacity', 'ooohboi-steroids' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ob-postman .elementor-widget-container blockquote::before' => 'opacity: {{SIZE}};',
				],
				'condition' => [
                    '_ob_postman_use' => 'yes', 
                    '_ob_postman_quotes_bg_background' => [ 'classic' ], 
                ],
            ]
		);
		$element->end_controls_section();

	}

}