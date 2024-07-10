<?php
use Elementor\Controls_Manager; 
use Elementor\Element_Base;
use Elementor\Plugin;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi Breaking Bad Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class OoohBoi_Breaking_Bad {

	/**
	 * Initialize 
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function init() {

		add_action( 'elementor/element/section/section_layout/before_section_end',  [ __CLASS__, 'ooohboi_handle_section' ], 10, 2 );
		add_action( 'elementor/element/column/layout/before_section_end',  [ __CLASS__, 'ooohboi_handle_columns' ], 10, 2 );

        add_action( 'elementor/frontend/section/before_render', function( Element_Base $element ) {

			$amInner = ( $element->get_data( 'isInner' ) ) ? ' ob-bb-inner' : '';

			if ( Plugin::instance()->editor->is_edit_mode() ) return;
			$settings = $element->get_settings_for_display();

			if ( isset( $settings[ '_ob_bbad_use_it' ] ) && 'yes' === $settings[ '_ob_bbad_use_it' ] ) {

				$element->add_render_attribute( '_wrapper', [
					'class' => 'ob-is-breaking-bad' . $amInner
				] );

			}
			if ( isset( $settings[ '_ob_bbad_sssic_use' ] ) && 'yes' === $settings[ '_ob_bbad_sssic_use' ] ) {

				$element->add_render_attribute( '_wrapper', [
					'class' => 'ob-is-sticky-inner-section'
				] );
	
			}

		} );

		add_action( 'elementor/frontend/column/before_render', function( Element_Base $element ) {

			if ( Plugin::instance()->editor->is_edit_mode() ) return;
			$settings = $element->get_settings_for_display();

			if ( isset( $settings[ '_ob_bbad_is_stalker' ] ) && 'yes' === $settings[ '_ob_bbad_is_stalker' ] ) {

				$element->add_render_attribute( '_wrapper', [
					'class' => 'ob-is-stalker'
				] );
	
			}

		} );


	}

    public static function ooohboi_handle_section( $element, $args ) {

        //  create panel section
		$element->add_control(
			'_ob_bbad_section_title',
			[
				'label' => 'B R E A K I N G - B A D', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );
        // ------------------------------------------------------------------------- CONTROL: Use Breaking Bad for Section and Columns
		$element->add_control(
			'_ob_bbad_use_it',
			[
                'label' => __( 'Enable Breaking Bad?', 'ooohboi-steroids' ), 
				'description' => __( 'By enabling Breaking Bad for this SECTION, all the Columns will break in order to fit the available width.', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Align columns
		$element->add_responsive_control(
			'_ob_bbad_arrange_cols',
			[
				'label' => __( 'Align Columns', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'flex-start', 
				'options' => [
					'flex-start' => __( 'Start', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'flex-end' => __( 'End', 'ooohboi-steroids' ), 
					'space-between' => __( 'Space Between', 'ooohboi-steroids' ), 
					'space-around' => __( 'Space Around', 'ooohboi-steroids' ), 
					'space-evenly' => __( 'Space Evenly', 'ooohboi-steroids' ),
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-breaking-bad > .elementor-container > .elementor-row, {{WRAPPER}}.ob-is-breaking-bad > .elementor-container' => 'justify-content: {{VALUE}} !important;', 
					'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner > .elementor-container > .elementor-row, {{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner > .elementor-container' => 'justify-content: {{VALUE}} !important;', 
				],
				'condition' => [
					'_ob_bbad_use_it' => 'yes',
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Columns direction
		$element->add_responsive_control(
			'_ob_bbad_cols_direction',
			[
				'label' => __( 'Columns Direction', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'row', 
				'options' => [
					'row' => __( 'Row', 'ooohboi-steroids' ),
					'column' => __( 'Column', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-breaking-bad > .elementor-container > .elementor-row, {{WRAPPER}}.ob-is-breaking-bad > .elementor-container' => 'flex-direction: {{VALUE}};', 
					'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner > .elementor-container > .elementor-row, {{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner > .elementor-container' => 'flex-direction: {{VALUE}};', 
					'{{WRAPPER}}.ob-is-breaking-bad.ob-is-glider > .elementor-container.swiper-container-vertical > .elementor-row, {{WRAPPER}}.ob-is-breaking-bad.ob-is-glider > .elementor-container.swiper-container-vertical' => 'flex-direction: column;', 
					'{{WRAPPER}}.ob-is-breaking-bad.ob-is-glider.ob-bb-inner > .elementor-container.swiper-container-vertical > .elementor-row, {{WRAPPER}}.ob-is-breaking-bad.ob-is-glider.ob-bb-inner > .elementor-container.swiper-container-vertical' => 'flex-direction: column;', 
				],
				'condition' => [
					'_ob_bbad_use_it' => 'yes',
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Columns direction - items alignment
		$element->add_responsive_control(
			'_ob_bbad_cols_direction_align',
			[
				'label' => __( 'Align Items', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'flex-start', 
				'options' => [
					'flex-start' => __( 'Start', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'flex-end' => __( 'End', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-breaking-bad > .elementor-container > .elementor-row, {{WRAPPER}}.ob-is-breaking-bad > .elementor-container' => 'align-items: {{VALUE}};', 
					'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner > .elementor-container > .elementor-row, {{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner > .elementor-container' => 'align-items: {{VALUE}};', 
				],
				'condition' => [
					'_ob_bbad_use_it' => 'yes', 
					'_ob_bbad_cols_direction' => [ 'column' ],
				],
			]
		);
		// ------------------------------------------------------------------------- SINCE 1.5.7 - CONTROL: Enable Sticky Section Stay in Column
		$element->add_control(
			'_ob_bbad_sssic_use',
			[
				'label' => __( 'Sticky Section', 'ooohboi-steroids' ), 
				'description' => __( 'It works for the Inner Section only! It keeps it sticky inside the column to avoid content overlaps.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no', 
				'frontend_available' => true, 
				'hide_in_top' => true, 
				'condition' => [
					'_ob_bbad_use_it' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- SINCE 1.6.3 - CONTROL: InnerSection width

		// ------------------------------------------------------------------------- CONTROL: Size Method
		$element->add_responsive_control(
			'_ob_bbad_inner_width_method',
			[
				'label' => __( 'Size Method', 'ooohboi-steroids' ),
				'description' => __( 'Use Flex or Units?', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT, 
				'hide_in_top' => true, 
				'default' => 'units',
				'options' => [
					'flex' => __( 'Flex', 'ooohboi-steroids' ), 
					'units' => __( 'Units', 'ooohboi-steroids' ), 
				],
				'condition' => [
					'_ob_bbad_use_it' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Flex size
		$element->add_responsive_control(
			'_ob_bbad_inner_flex',
			[
				'label' => __( 'Flex', 'ooohboi-steroids' ),
				'type' => Controls_Manager::NUMBER, 
				'hide_in_top' => true, 
				'separator' => 'before', 
				'default' => 1, 
				'min' => 1,
				'selectors' => [
					'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner' => 'flex: {{VALUE}}; width: unset; min-width: 1px;', 
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner' => 'flex: {{VALUE}}; width: unset; min-width: 1px;', 
						],
						'condition' => [
							'_ob_bbad_use_it' => 'yes', 
							'_ob_bbad_inner_width_method' => 'flex', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner' => 'flex: {{VALUE}}; width: unset; min-width: 1px;', 
						],
						'condition' => [
							'_ob_bbad_use_it' => 'yes', 
							'_ob_bbad_inner_width_method' => 'flex', 
						],
					],
				],
				'condition' => [
					'_ob_bbad_use_it' => 'yes', 
					'_ob_bbad_inner_width_method' => 'flex', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL width
		$element->add_responsive_control(
			'_ob_bbad_inner_width',
			[
				'label' => __( 'Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT,
				'hide_in_top' => true, 
				'separator' => 'before',
				'label_block' => true,
				'default' => '100%', 
				'description' => __( 'You can enter any acceptable CSS value, for example: 50em, 300px, 100%, calc(100% - 300px).', 'ooohboi-steroids' ), 
				'selectors' => [
					'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner' => 'width: {{VALUE}}; flex: unset;',
				],
				'device_args' => [
					Breakpoints_Manager::BREAKPOINT_KEY_TABLET => [
						'selectors' => [
							'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner' => 'width: {{VALUE}}; flex: unset;',
						],
						'condition' => [
							'_ob_bbad_use_it' => 'yes', 
							'_ob_bbad_inner_width_method' => 'units', 
						],
					],
					Breakpoints_Manager::BREAKPOINT_KEY_MOBILE => [
						'selectors' => [
							'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner' => 'width: {{VALUE}}; flex: unset;',
						],
						'condition' => [
							'_ob_bbad_use_it' => 'yes', 
							'_ob_bbad_inner_width_method' => 'units', 
						],
					],
				],
				'condition' => [
					'_ob_bbad_use_it' => 'yes', 
					'_ob_bbad_inner_width_method' => 'units', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL max width
		$element->add_responsive_control(
			'_ob_bbad_inner_max_width',
			[
				'label' => __( 'Max Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::TEXT, 
				'hide_in_top' => true, 
				'separator' => 'before',
				'label_block' => true,
				'description' => __( 'You can enter any acceptable CSS value, for example: 50em, 300px, 100%, calc(100% - 300px).', 'ooohboi-steroids' ), 
				'selectors' => [
					'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner' => 'max-width: {{VALUE}};',
				],
				'condition' => [
					'_ob_bbad_use_it' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: align self vertically
		$element->add_responsive_control(
			'_ob_bbad_inner_align_self',
			[
				'label' => __( 'Align self Vertically', 'ooohboi-steroids' ),
				'description' => __( 'Align this Inner Section vertically (when the wrapper element widgets direction is Row or Inherit).', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT, 
				'hide_in_top' => true, 
				'default' => 'auto',
				'options' => [
					'auto' => __( 'Auto', 'ooohboi-steroids' ), 
					'baseline' => __( 'Baseline', 'ooohboi-steroids' ), 
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'end' => __( 'End', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner' => 'align-self: {{VALUE}};',
				],
				'condition' => [
					'_ob_bbad_use_it' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: align self horizontally
		$element->add_responsive_control(
			'_ob_bbad_inner_align_self_horiz',
			[
				'label' => __( 'Align self Horizontally', 'ooohboi-steroids' ),
				'description' => __( 'Align this Inner Section horizontally (when the wrapper element widgets direction is Column).', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT, 
				'hide_in_top' => true, 
				'default' => 'inherit',
				'options' => [
					'inherit' => __( 'Inherit', 'ooohboi-steroids' ), 
					'flex-start' => __( 'Start', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'flex-end' => __( 'End', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner' => 'align-self: {{VALUE}};',
				],
				'condition' => [
					'_ob_bbad_use_it' => 'yes', 
				],
			]
		);
		// --------------------------------------------------------------------------------------------- inner order
		$element->add_responsive_control(
            '_ob_bbad_inner_widget_order',
            [
				'label' => __( 'Stacking Order', 'ooohboi-steroids' ), 
				'description' => sprintf(
                    __( 'More info at %sMozilla%s.', 'ooohboi-steroids' ),
                    '<a 
href="https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout/Ordering_Flex_Items#The_order_property" target="_blank">',
                    '</a>'
                ),
				'type' => Controls_Manager::NUMBER, 
				'style_transfer' => true, 
				'hide_in_top' => true, 
				'selectors' => [
					'{{WRAPPER}}.ob-is-breaking-bad.ob-bb-inner' => '-webkit-box-ordinal-group: calc({{VALUE}} + 1 ); -ms-flex-order:{{VALUE}}; order: {{VALUE}};', 
                ],
				'condition' => [
					'_ob_bbad_use_it' => 'yes', 
				],
			]
		);

    }

    public static function ooohboi_handle_columns( $element, $args ) {

		$element->add_control(
			'_ob_bbad_column_title',
			[
				'label' => 'B R E A K I N G - B A D', 
				'type' => Controls_Manager::HEADING,
				'separator' => 'before', 
			]
        );
		// --------------------------------------------------------------------------------------------- CONTROL Column width
		$element->add_responsive_control(
            '_ob_bbad_column_width',
            [
                'label' => __( 'Custom Width', 'ooohboi-steroids' ),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'label_block' => true,
                'description' => __( 'You can enter any acceptable CSS value, for example: 50em, 300px, 100%, calc(100% - 300px). NOTE: If you want to make the columns wrap, Enable Breaking Bad for this Column parent SECTION!', 'ooohboi-steroids' ),
                'selectors' => [
                    '{{WRAPPER}}.elementor-column' => 'width: {{VALUE}};',
				],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Column max width, since v 1.5.8
		$element->add_responsive_control(
            '_ob_bbad_column_max_width',
            [
                'label' => __( 'Max Width', 'ooohboi-steroids' ),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'label_block' => true,
				'description' => __( 'You can enter any acceptable CSS value, for example: 50em, 300px, 100%, calc(100% - 300px).', 'ooohboi-steroids' ), 
                'selectors' => [
                    '{{WRAPPER}}.elementor-column' => 'max-width: {{VALUE}};',
				],
            ]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Column height
		$element->add_responsive_control(
            '_ob_bbad_column_height',
            [
				'label' => __( 'Custom Height', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1000,
						'step' => 1,
					],
					'em' => [
						'max' => 100,
						'step' => 1,
					],
					'vh' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', 'em', 'vh', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.elementor-column, {{WRAPPER}}.elementor-column > .elementor-widget-wrap' => 'height: {{SIZE}}{{UNIT}};', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Column height
		$element->add_responsive_control(
            '_ob_bbad_column_min_height',
            [
				'label' => __( 'Min Height', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1000,
						'step' => 1,
					],
					'em' => [
						'max' => 100,
						'step' => 1,
					],
					'vh' => [
						'max' => 100,
						'step' => 1,
					],
                ],
				'size_units' => [ 'px', 'em', 'vh', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.elementor-column, {{WRAPPER}}.elementor-column > .elementor-widget-wrap' => 'min-height: {{SIZE}}{{UNIT}};', 
                ],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Column order
		$element->add_responsive_control(
			'_ob_bbad_column_order',
			[
				'label' => __( 'Column Order', 'ooohboi-steroids' ), 
				'description' => sprintf(
					__( 'More info at %sMozilla%s.', 'ooohboi-steroids' ),
					'<a 
href="https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout/Ordering_Flex_Items#The_order_property" target="_blank">',
					'</a>'
				),
				'type' => Controls_Manager::NUMBER, 
				'style_transfer' => true, 
				'selectors' => [
					'{{WRAPPER}}.elementor-column' => '-webkit-box-ordinal-group: calc({{VALUE}} + 1 ); -ms-flex-order:{{VALUE}}; order: {{VALUE}};', 
				],
			]
		);
		// ------------------------------------------------------------------------- since 1.6.3 - CONTROL: column - vertical alignment
		$element->add_responsive_control(
			'_ob_bbad_column_vert_align',
			[
				'label' => __( 'Align vertically', 'ooohboi-steroids' ), 
				'description' => __( 'Defines the vertical alignmnet of this column only!', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SELECT,
                'default' => 'inherit', 
				'options' => [
					'inherit' => __( 'Inherit', 'ooohboi-steroids' ), 
					'flex-start' => __( 'Start', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'flex-end' => __( 'End', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-column' => 'align-self: {{VALUE}};',
				],
			]
		);
		// --------------------------------------------------------------------------------------------- CONTROL Scrollable
		$element->add_control(
            '_ob_bbad_column_scrollable',
            [
                'label' => __( 'Scrollable Column?', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'scroll',
				'default' => 'visible',
				'selectors' => [
					'{{WRAPPER}}.elementor-column > .elementor-column-wrap, {{WRAPPER}}.elementor-column > .elementor-widget-wrap' => 'overflow-y: {{VALUE}};', 
                ],
				'condition' => [
					'_ob_bbad_column_height!' => '',
				],
			]
		);
		/* since 1.7.2 */
		// ------------------------------------------------------------------------- CONTROL: Link or No?
        $element->add_control(
			'_ob_bbad_link_type',
			[
				'label' => __( 'Column Link', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'separator' => 'before', 
				'options' => [
					'default' => __( 'None', 'ooohboi-steroids' ),
					'pointer' => __( 'Custom URL', 'ooohboi-steroids' ),
                ],
				'selectors' => [
                    '{{WRAPPER}}.elementor-column' => 'cursor: {{VALUE}};', 
                ],
			]
		);
        // ------------------------------------------------------------------------- CONTROL: Link to...
		$element->add_control(
			'_ob_bbad_link',
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
					'_ob_bbad_link_type' => 'pointer',
				],
				'show_label' => false,
			]
        );

		// --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
		$element->add_control(
			'_ob_bbad_separator_0',
			[
				'type' => Controls_Manager::DIVIDER, 
			]
		);
		// --------------------------------------------------------------------------------------------- Widgets Stalker, since v 1.6.0

		$element->add_control(
			'_ob_bbad_section_title_0',
			[
				'label' => sprintf( __( 'Widget Stalker', 'ooohboi-steroids' ) ), 
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-control-field-description',
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Enable Widget Stalker
		$element->add_control(
			'_ob_bbad_is_stalker',
			[
				'label' => __( 'Enable Widget Stalker?', 'ooohboi-steroids' ), 
				'description' => __( 'NOTE: It changes the default alignment of widgets inside this column - including the Inner Section widget.', 'ooohboi-steroids' ), 
				'separator' => 'before', 
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
		);
		// ------------------------------------------------------------------------- CONTROL: widgets direction
		$element->add_responsive_control(
			'_ob_bbad_ws_widgets_direction',
			[
				'label' => __( 'Widgets Direction', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'inherit', 
				'options' => [
					'inherit' => __( 'Row', 'ooohboi-steroids' ),
					'column' => __( 'Column', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-column.ob-is-stalker .elementor-widget-wrap' => 'flex-direction: {{VALUE}};', 
				],
				'condition' => [
					'_ob_bbad_is_stalker' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Align widgets
		$element->add_responsive_control(
			'_ob_bbad_ws_widgets_horiz_align',
			[
				'label' => __( 'Align Widgets', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'flex-start', 
				'options' => [
					'flex-start' => __( 'Start', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'flex-end' => __( 'End', 'ooohboi-steroids' ), 
					'space-between' => __( 'Space Between', 'ooohboi-steroids' ), 
					'space-around' => __( 'Space Around', 'ooohboi-steroids' ), 
					'space-evenly' => __( 'Space Evenly', 'ooohboi-steroids' ),
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-column.ob-is-stalker .elementor-widget-wrap' => 'justify-content: {{VALUE}} !important;', 
				],
				'condition' => [
					'_ob_bbad_is_stalker' => 'yes', 
					'_ob_bbad_ws_widgets_direction' => [ 'inherit' ],
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: widgets - vertical alignment
		$element->add_responsive_control(
			'_ob_bbad_ws_items_align',
			[
				'label' => __( 'Align vertically', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'flex-start', 
				'options' => [
					'flex-start' => __( 'Start', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'flex-end' => __( 'End', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-column.ob-is-stalker .elementor-widget-wrap' => 'align-items: {{VALUE}}; align-content: unset;',
				],
				'condition' => [
					'_ob_bbad_is_stalker' => 'yes', 
					'_ob_bbad_ws_widgets_direction' => [ 'column' ],
				],
			]
		);
    }

}