<?php
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
/*
use Elementor\Core\Schemes\Typography;
*/
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main OoohBoi OoohBoi_Commentz
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.5.1
 */
class OoohBoi_Commentz {

	static $should_script_enqueue = false;

	/**
	 * Initialize 
	 *
	 * @since 1.5.1
	 *
	 * @access public
	 */
	public static function init() {

        add_action( 'elementor/element/post-comments/section_content/after_section_end',  [ __CLASS__, 'add_section' ] ); 
        add_action( 'elementor/element/after_add_attributes',  [ __CLASS__, 'add_attributes' ] );

        /* should enqueue? */
        add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        /* add script */
        add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

    }

    /* enqueue script JS */
    public static function enqueue_scripts() {

        $extension_js = plugin_dir_path( __DIR__ ) . 'assets/js/commentz-min.js'; 

        if( file_exists( $extension_js ) ) {
            wp_add_inline_script( 'elementor-frontend', file_get_contents( $extension_js ) );
        }

    }
    /* should enqueue? */
    public static function should_script_enqueue( $element ) {

        if( self::$should_script_enqueue ) return;

        if( 'yes' == $element->get_settings_for_display( '_ob_commentz_use' ) ) {

            self::$should_script_enqueue = true;
            self::enqueue_scripts();

            remove_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'should_script_enqueue' ] );
        }
    }

    public static function add_attributes( $element ) {

        if ( 'post-comments' !== $element->get_name() ) return;
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) return;

		$settings = $element->get_settings_for_display();
		
		if ( isset( $settings[ '_ob_commentz_use' ] ) && 'yes' === $settings[ '_ob_commentz_use' ] ) {

            $element->add_render_attribute( '_wrapper', [
                'class' => 'ob-commentz'
            ] );

        }

    }
    
	public static function add_section( Element_Base $element ) {

		$element->start_controls_section(
			'_ob_commentz',
			[
				'label' => 'C O M M E N T Z', 
				'type' => Controls_Manager::TAB_CONTENT,
			]
        );

        // ------------------------------------------------------------------------- CONTROL: Yes 4 Comments !
		$element->add_control(
			'_ob_commentz_use',
			[
                'label' => __( 'Enable Commentz', 'ooohboi-steroids' ), 
                'description' => __( 'That will allow you to style up the post comments.', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'yes',
                'default' => 'no', 
                'separator' => 'after', 
                'frontend_available' => true, 
			]
        );

        // --------------------------------------------------------------------------------------------- CONTROL Comments title
        $element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_ob_commentz_title_typo', 
				'label' => __( 'Heading', 'ooohboi-steroids' ), 
				/*'scheme' => Typography::TYPOGRAPHY_3, */
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				], 
				'selector' => '{{WRAPPER}}.ob-commentz .title-comments', 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Comments title styles
		$element->add_control(
			'_ob_commentz_title',
			[
				'label' => __( 'Heading style', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'frontend_available' => true, 
				'return_value' => 'yes', 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

        $element->start_popover();

        // --------------------------------------------------------------------------------------------- CONTROL: Text COLOR
		$element->add_control(
			'_ob_commentz_title_color',
			[
				'label' => __( 'Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}}.ob-commentz .title-comments' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_commentz_title' => 'yes',
				],
			]
		);

        // ------------------------------------------------------------------------- CONTROL: Comments title alignment
		$element->add_responsive_control(
			'_ob_commentz_title_align',
			[
				'label' => __( 'Text alignment', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'left', 
				'options' => [
					'left' => __( 'Left', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'right' => __( 'Right', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz .title-comments' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_commentz_title' => 'yes',
				],
			]
		);

         // ------------------------------------------------------------------------- CONTROL: Comments title PADDING
		$element->add_responsive_control(
			'_ob_commentz_title_paddingz',
			[
				'label' => __( 'Padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz .title-comments' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_commentz_title' => 'yes',
                ],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Comments title MARGIN
		$element->add_responsive_control(
			'_ob_commentz_title_marginz',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz .title-comments' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_commentz_title' => 'yes',
                ],
			]
        );

		$element->end_popover(); // popover Title styles end

		// --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
		$element->add_control(
			'_ob_dummy_separator_0',
			[
				'type' => Controls_Manager::DIVIDER, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Comments General styles
		$element->add_control(
			'_ob_comment_list_styles',
			[
				'label' => __( 'Comment list style', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes', 
				'frontend_available' => true, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		$element->start_popover();

		// ------------------------------------------------------------------------- CONTROL: Comment body PADDINGs
		$element->add_responsive_control(
			'_ob_comment_body_padding',
			[
				'label' => __( 'Comment body padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .comment-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',  
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_list_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Comment body MARGINs
		$element->add_responsive_control(
			'_ob_comment_body_margin',
			[
				'label' => __( 'Comment body margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .comment-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',  
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_list_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Comment body border
		$element->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => '_ob_comment_body_border',
				'label' => __( 'Border', 'ooohboi-steroids' ),
				'default' => 0,
				'selector' => '{{WRAPPER}}.ob-commentz #comments .comment-body',
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);
		$element->add_responsive_control(
			'_ob_comment_body_bord_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .comment-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_list_styles' => 'yes', 
                ],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Comment body BG Color
		$element->add_control(
			'_ob_comment_body_bg_color',
			[
				'label' => __( 'Background color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR, 
				'default' => '#00000000', 
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments ol:not(.children) .comment-body' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_list_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Comment body BG Color Children
		$element->add_control(
			'_ob_comment_body_bg_color_child',
			[
				'label' => __( 'Background color replies', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR, 
				'default' => '#00000000', 
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments ol.children .comment-body' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_list_styles' => 'yes', 
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Gravatar size
		$element->add_responsive_control(
			'_ob_child_comments_indent',
			[
				'label' => __( 'Child comments indent', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
						'step' => 1,
					],
					'em' => [
						'max' => 100,
						'step' => 0.1,
					],
                ],
				'size_units' => [ 'px', 'em', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments ol.children' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_list_styles' => 'yes', 
                ],
			]
		);
		
		$element->end_popover(); // popover General styles end

		// ------------------------------------------------------------------------- CONTROL: Show Gravatar
		$element->add_control(
			'_ob_commentz_show_gravatar',
			[
				'label' => __( 'Gravatar', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'none',
				'default' => 'block',
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'label_on' => __( 'Show', 'elementor-pro' ),
				'separator' => 'before', 
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments img.avatar' => 'display: {{VALUE}};', 
                ],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Gravatar styles

		$element->add_control(
			'_ob_gravatar_style',
			[
				'label' => __( 'Gravatar style', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes', 
				'frontend_available' => true, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_commentz_show_gravatar!' => 'none', 
                ],
			]
		);

		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL Gravatar size
		$element->add_responsive_control(
			'_ob_gravatar_size',
			[
				'label' => __( 'Size', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
						'step' => 1,
					],
					'em' => [
						'max' => 100,
						'step' => 0.1,
					],
                ],
				'size_units' => [ 'px', 'em', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments img.avatar' => 'width: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_commentz_show_gravatar!' => 'none', 
					'_ob_gravatar_style' => 'yes', 
                ],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Gravatar position
		$element->add_responsive_control(
			'_ob_gravatar_position',
			[
				'label' => __( 'Position', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments img.avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_commentz_show_gravatar!' => 'none', 
					'_ob_gravatar_style' => 'yes', 
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Gravatar border props
		$element->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => '_ob_gravatar_border',
				'label' => __( 'Border', 'ooohboi-steroids' ),
				'default' => 0,
				'selector' => '{{WRAPPER}}.ob-commentz #comments img.avatar', 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_commentz_show_gravatar!' => 'none', 
                ],
			]
		);
		$element->add_responsive_control(
			'_ob_gravatar_bord_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments img.avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_commentz_show_gravatar!' => 'none', 
					'_ob_gravatar_style' => 'yes', 
                ],
			]
		);
		
		$element->end_popover(); // popover Gravatar styles end

		// --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
		$element->add_control(
			'_ob_dummy_separator',
			[
				'type' => Controls_Manager::DIVIDER, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Meta font-family
        $element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_ob_meta_typo_user', 
				'label' => __( 'Meta-data User', 'ooohboi-steroids' ), 
				/*'scheme' => Typography::TYPOGRAPHY_3, */
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				], 
				'selector' => '{{WRAPPER}}.ob-commentz #comments .comment-author', 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
				],
			]
		);
		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_ob_meta_typo_time', 
				'label' => __( 'Meta-data Time', 'ooohboi-steroids' ), 
				/*'scheme' => Typography::TYPOGRAPHY_3, */
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				], 
				'selector' => '{{WRAPPER}}.ob-commentz #comments .comment-metadata', 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Meta styles
		$element->add_control(
			'_ob_meta_style',
			[
				'label' => __( 'Meta-data style', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes', 
				'frontend_available' => true, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		$element->start_popover();

		$element->add_control(
			'_ob_meta_color',
			[
				'label' => __( 'Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .comment-meta' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_meta_style' => 'yes', 
                ],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Meta MARGINs
		$element->add_responsive_control(
			'_ob_meta_marginz',
			[
				'label' => __( 'Outer Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .comment-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_meta_style' => 'yes', 
				],
			]
		);

		$element->end_popover(); // popover Meta styles end

		// --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
		$element->add_control(
			'_ob_dummy_separator_2',
			[
				'type' => Controls_Manager::DIVIDER, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Comment text
        $element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_ob_comment_text_typo', 
				'label' => __( 'Comment text', 'ooohboi-steroids' ), 
				/*'scheme' => Typography::TYPOGRAPHY_3, */
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				], 
				'selector' => '{{WRAPPER}}.ob-commentz #comments .comment-content', 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Comment text styles
		$element->add_control(
			'_ob_comment_text_style',
			[
				'label' => __( 'Comment text style', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes', 
				'frontend_available' => true, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		$element->start_popover();

		// ------------------------------------------------------------------------- CONTROL: Text Color
		$element->add_control(
			'_ob_comment_text_color',
			[
				'label' => __( 'Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .comment-content' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_text_style' => 'yes', 
                ],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: MARGINs
		$element->add_responsive_control(
			'_ob_comment_text_marginz',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .comment-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',  
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_text_style' => 'yes', 
				],
			]
		);

		$element->end_popover(); // popover Comment text styles end

		// --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
		$element->add_control(
			'_ob_dummy_separator_3',
			[
				'type' => Controls_Manager::DIVIDER, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL REPLY 
		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_ob_reply_typo', 
				'label' => __( 'Reply button', 'ooohboi-steroids' ), 
				/*'scheme' => Typography::TYPOGRAPHY_3, */
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				], 
				'selector' => '{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link', 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL REPLY styles
		$element->add_control(
			'_ob_reply_style',
			[
				'label' => __( 'Reply button style', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes', 
				'frontend_available' => true, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
				],
			]
		);

		$element->start_popover();

		 // ------------------------------------------------------------------------- CONTROL: Button alignment
		 $element->add_responsive_control(
			'_ob_reply_align',
			[
				'label' => __( 'Align button', 'ooohboi-steroids' ),
				'type' => Controls_Manager::SELECT,
                'default' => 'left', 
				'options' => [
					'left' => __( 'Left', 'ooohboi-steroids' ),
					'center' => __( 'Center', 'ooohboi-steroids' ), 
					'right' => __( 'Right', 'ooohboi-steroids' ), 
				],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Text Color
		$element->add_control(
			'_ob_reply_text_color',
			[
				'label' => __( 'Text color normal', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR, 
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: Text Color Hover
		$element->add_control(
			'_ob_reply_text_color_hover',
			[
				'label' => __( 'Text color hover', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: BG Color

		$element->add_control(
			'_ob_reply_bg_color',
			[
				'label' => __( 'Background color normal', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR, 
				'default' => '#00000000', 
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: BG Color Hover

		$element->add_control(
			'_ob_reply_bg_hover',
			[
				'label' => __( 'Background color hover', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#00000000', 
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: PADDINGs
		$element->add_responsive_control(
			'_ob_reply_padding',
			[
				'label' => __( 'Padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link' => 'display: inline-block; padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',  
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
				],
			]
		);

		// ------------------------------------------------------------------------- CONTROL: MARGINz
		$element->add_responsive_control(
			'_ob_reply_margin',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',  
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL REPLY border props
		$element->add_control(
			'_ob_reply_bord_color',
			[
				'label' => __( 'Border color normal', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
                ],
			]
		);
		$element->add_control(
			'_ob_reply_bord_color_hover',
			[
				'label' => __( 'Border color hover', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
                ],
			]
		);
		$element->add_responsive_control(
			'_ob_reply_bord_width',
			[
				'label' => __( 'Border Width', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
                ],
			]
		);
		$element->add_responsive_control(
			'_ob_reply_bord_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .reply a.comment-reply-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_reply_style' => 'yes', 
                ],
			]
		);

		$element->end_popover(); // popover Comment text styles end

		// --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!

		$element->add_control(
			'_ob_dummy_separator_4',
			[
				'type' => Controls_Manager::DIVIDER, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Comment form heading

        $element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_ob_comment_form_title_typo', 
				'label' => __( 'Comment form heading', 'ooohboi-steroids' ), 
				/*'scheme' => Typography::TYPOGRAPHY_3, */
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				], 
				'selector' => '{{WRAPPER}}.ob-commentz #comments #reply-title', 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Comment form heading styles

		$element->add_control(
			'_ob_comment_form_title',
			[
				'label' => __( 'Comment form heading style', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes', 
				'frontend_available' => true, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		$element->start_popover();

		// --------------------------------------------------------------------------------------------- CONTROL: Comment form heading COLOR
		$element->add_control(
			'_ob_comment_form_title_color',
			[
				'label' => __( 'Color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments #reply-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_form_title' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Comments title MARGIN
		$element->add_responsive_control(
			'_ob_comment_form_title_marginz',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments #reply-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_form_title' => 'yes', 
				],
			]
		);

		$element->end_popover(); // popover Comment form styles end

		// --------------------------------------------------------------------------------------------- CONTROL Comment form heading styles

		$element->add_control(
			'_ob_comment_form_styles',
			[
				'label' => __( 'Comment form style', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes', 
				'frontend_available' => true, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		$element->start_popover();

		// ------------------------------------------------------------------------- CONTROL: Comment form border
		$element->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => '_ob_comment_form_border',
				'label' => __( 'Border', 'ooohboi-steroids' ),
				'default' => 0,
				'selector' => '{{WRAPPER}}.ob-commentz #comments #respond',
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);
		$element->add_responsive_control(
			'_ob_comment_form_bord_rad',
			[
				'label' => __( 'Border Radius', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments #respond' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_form_styles' => 'yes', 
                ],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Comment form BG Color
		$element->add_control(
			'_ob_comment_form_bg_color',
			[
				'label' => __( 'Background color', 'ooohboi-steroids' ),
				'type' => Controls_Manager::COLOR, 
				'default' => '#00000000', 
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments #respond' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_form_styles' => 'yes', 
				],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Comment form PADDING
		$element->add_responsive_control(
			'_ob_comment_form_paddingz',
			[
				'label' => __( 'Padding', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments #respond' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_form_styles' => 'yes', 
                ],
			]
		);
		// ------------------------------------------------------------------------- CONTROL: Comment form MARGIN
		$element->add_responsive_control(
			'_ob_comment_form_marginz',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'size' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments #respond' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_comment_form_styles' => 'yes', 
                ],
			]
        );

		$element->end_popover(); // popover Comment form styles end

		// --------------------------------------------------------------------------------------------- CONTROL DIVIDER !!!!!
		$element->add_control(
			'_ob_dummy_separator_x',
			[
				'type' => Controls_Manager::DIVIDER, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
                ],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Navig 
		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => '_ob_navig_typo', 
				'label' => __( 'Comments Nav', 'ooohboi-steroids' ), 
				/*'scheme' => Typography::TYPOGRAPHY_3, */
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				], 
				'selector' => '{{WRAPPER}}.ob-commentz #comments .nav-links', 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
				],
			]
		);

		// --------------------------------------------------------------------------------------------- CONTROL Navig styles
		$element->add_control(
			'_ob_navig_style',
			[
				'label' => __( 'Comments Nav style', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes', 
				'frontend_available' => true, 
				'condition' => [
					'_ob_commentz_use' => 'yes', 
				],
			]
		);

		$element->start_popover();

		// ------------------------------------------------------------------------- CONTROL: MARGINz
		$element->add_responsive_control(
			'_ob_navig_margin',
			[
				'label' => __( 'Margin', 'ooohboi-steroids' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #comments .nav-links' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',  
				],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
					'_ob_navig_style' => 'yes', 
				],
			]
		);

		$element->end_popover(); // popover Comment form styles end

		// ------------------------------------------------------------------------- CONTROL: Hide URL field, since 1.7.3
		$element->add_control(
			'_ob_commentz_hide_URL',
			[
                'label' => __( 'Hide the Website input field?', 'ooohboi-steroids' ), 
                'description' => __( 'If you make it hidden, be sure it\'s not mandatory otherwise the comment can not be posted!', 'ooohboi-steroids' ), 
				'type' => Controls_Manager::SWITCHER, 
				'separator' => 'before', 
				'label_on' => __( 'Yes', 'ooohboi-steroids' ),
				'label_off' => __( 'No', 'ooohboi-steroids' ),
				'return_value' => 'none',
                'default' => 'inherit',
				'selectors' => [
					'{{WRAPPER}}.ob-commentz #commentform .comment-form-url' => 'display: {{VALUE}};', 
                ],
				'condition' => [
					'_ob_commentz_use' => 'yes', 
				],
			]
        );
        
		$element->end_controls_section();

	}

}