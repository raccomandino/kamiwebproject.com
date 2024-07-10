<?php 
/*
Widget Name: Blockquote
Description: Author Quote Style.
Author: Theplus
Author URI: https://posimyth.com
*/

namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class L_ThePlus_Block_Quote extends Widget_Base {
		
	public function get_name() {
		return 'tp-blockquote';
	}

    public function get_title() {
        return esc_html__('Blockquote', 'tpebl');
    }

    public function get_icon() {
        return 'fa fa-quote-left theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-essential');
    }

    protected function register_controls() {
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Blockquote', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'style',
			[
				'label' => esc_html__( 'Style', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => l_theplus_get_style_list(2),
			]
		);
		$this->add_control(
			'content_description',
			[
				'label' => esc_html__( 'Quote Description', 'tpebl' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( '"I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo."', 'tpebl' ),
				'placeholder' => esc_html__( 'Type your block quote here', 'tpebl' ),
				'dynamic' => [
					'active'   => true,
				],
			]
		);
		$this->add_control(
			'quote_author',
			[
				'label' => esc_html__( 'Author', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'John Doe', 'tpebl' ),
				'dynamic' => [
					'active'   => true,
				],
				'condition' => [
					'style' => 'style-2',
				],
			]
		);
		$this->add_control(
			'quote_author_desc',
			[
				'label' => esc_html__( 'Author Description', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '- Developer', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'style!' => 'style-1',
				],
			]
		);
		$this->add_control('quote_icon',
			[
				'label' => esc_html__( 'Icon', 'tpebl' ),
				'type' =>  Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'separator' => 'before',
				'condition' => [
					'style!' => 'style-1',
				],
			]
		);
		$this->add_control(
			'quote_icon_select',
			[
				'label' => esc_html__( 'Icon Library', 'tpebl' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-quote-left',
					'library' => 'solid',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
				],	
			]
		);
		$this->add_control(
			'quote_icon_pos',
			[
				'label' => esc_html__( 'Position', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'qip_top',
				'options' => [
					'qip_top'  => esc_html__( 'Top', 'tpebl' ),
					'qip_bottm'  => esc_html__( 'Bottom', 'tpebl' ),
					'qip_both' => esc_html__( 'Both', 'tpebl' ),
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
				],
			]
		);
		$this->add_control(
			'quote_icon_pos_align',
			[
				'label' => esc_html__( 'Alignment', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'qipa_left',
				'options' => [
					'qipa_left'  => esc_html__( 'Left', 'tpebl' ),
					'qipa_right'  => esc_html__( 'Right', 'tpebl' ),
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => ['qip_top','qip_bottm'],
				],
			]
		);
		$this->add_control(
			'quote_icon_pos_align_both',
			[
				'label' => esc_html__( 'Alignment', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'qipa_opposite',
				'options' => [
					'qipa_opposite'  => esc_html__( 'Opposite', 'tpebl' ),
					'qipa_left'  => esc_html__( 'Left', 'tpebl' ),
					'qipa_right'  => esc_html__( 'Right', 'tpebl' ),
					'qipa_center'  => esc_html__( 'Center', 'tpebl' ),
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_both',
				],
			]
		);
		$this->add_control(
			'quote_tweet',[
				'label'   => esc_html__( 'Tweet', 'tpebl' ),
				'type'    =>  Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'separator' => 'before',				
			]
		);
		$this->add_control(
			'quote_tweet_icon_select',
			[
				'label' => esc_html__( 'Tweet Icon', 'tpebl' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fab fa-twitter',
					'library' => 'solid',
				],
				'condition' => [
					'quote_tweet' => 'yes',
				],	
			]
		);
		$this->add_control(
			'quote_tweet_text',
			[
				'label' => esc_html__( 'Text', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Tweet', 'tpebl' ),
				'dynamic' => ['active'   => true,],
				'condition' => [
					'quote_tweet' => 'yes',
				],
			]
		);
		$this->add_control(
			'quote_tweet_link',[
				'label'   => esc_html__( 'Tweet Current Page', 'tpebl' ),
				'type'    =>  Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'description' => 'Note : If disable, It will tweet content of blockquote instead of current page URL.',
				'condition' => [
					'quote_tweet' => 'yes',
				],
			]
		);
		$this->add_control(
			'quote_iamge_switch',[
				'label'   => esc_html__( 'Image', 'tpebl' ),
				'type'    =>  Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'separator' => 'before',
				'condition' => [
					'style!' => 'style-1',
				],
			]
		);
		$this->add_control(
			'quote_image',
			[
				'label' => esc_html__( 'Select', 'tpebl' ),
				'type' => Controls_Manager::MEDIA,
				'media_type' => 'image',
				'separator' => 'before',
				'condition' => [
					'style!' => 'style-1',
					'quote_iamge_switch' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'content_align',
			[
				'label' => esc_html__( 'Alignment', 'tpebl' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'tpebl' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'prefix_class' => 'text-%s',
			]
		);
		$this->end_controls_section();
		
		/*extras start*/
		$this->start_controls_section(
			'content_extras_section',
			[
				'label' => esc_html__( 'Extra Options', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'quote_dropcap',[
				'label'   => esc_html__( 'Drop Cap', 'tpebl' ),
				'type'    =>  Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'condition' => [
					'style!' => 'style-2',
				],
			]
		);
		$this->add_control(
			'border_layout',
			[
				'label' => esc_html__( 'Border Layout', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'  => esc_html__( 'None', 'tpebl' ),
					'bl_1'  => esc_html__( 'Layout 1', 'tpebl' ),
					'bl_2'  => esc_html__( 'Layout 2', 'tpebl' ),
					'bl_3'  => esc_html__( 'Layout 3', 'tpebl' ),
					'bl_3'  => esc_html__( 'Layout 3', 'tpebl' ),
				],
			]
		);
		$this->end_controls_section();
		/*extras end*/
		
		$this->start_controls_section(
            'section_styling',
            [
                'label' => esc_html__('Typography', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => esc_html__('Typography', 'tpebl'),
                'selector' => '{{WRAPPER}} .plus_blockquote blockquote.quote-text > span,{{WRAPPER}} .plus_blockquote blockquote.quote-text',
            ]
        );
		$this->start_controls_tabs( 'tabs_quote_style' );
		$this->start_controls_tab(
			'tab_quote_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
			]
		);
		$this->add_control(
            'content_color',
            [
                'label' => esc_html__('Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#888',
                'selectors' => [
                    '{{WRAPPER}} .plus_blockquote blockquote.quote-text > span,{{WRAPPER}} .plus_blockquote blockquote.quote-text p,{{WRAPPER}} .plus_blockquote blockquote.quote-text' => 'color:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'author_color',
            [
                'label' => esc_html__('Author Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#888',
                'selectors' => [
                    '{{WRAPPER}} .plus_blockquote .quote-text .quote_author' => 'color:{{VALUE}};',
                ],
				'condition' => [
					'style' => 'style-2',
				],
            ]
        );
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_quote_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_control(
            'content_hover_color',
            [
                'label' => esc_html__('Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plus_blockquote:hover blockquote.quote-text > span,{{WRAPPER}} .plus_blockquote:hover blockquote.quote-text p,{{WRAPPER}} .plus_blockquote:hover blockquote.quote-text' => 'color:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'author_hover_color',
            [
                'label' => esc_html__('Author Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plus_blockquote:hover .quote-text .quote_author' => 'color:{{VALUE}};',
                ],
				'condition' => [
					'style' => 'style-2',
				],
            ]
        );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'quote_color',
			[
				'label' => esc_html__( 'Quote Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#888',
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'style' => 'style-2',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'label' => esc_html__( 'Quote Shadow', 'tpebl' ),
				'selector' => '{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left',
				'separator' => 'before',
				'condition' => [
					'style' => 'style-2',
				],
			]
		);
		$this->add_responsive_control(
			'quote_padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'quote_margin',
			[
				'label' => esc_html__( 'Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		
		/*dropcap start*/
		$this->start_controls_section(
            'section_dropcap_styling',
            [
                'label' => esc_html__('Drop Cap', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style!' => 'style-2',
					'quote_dropcap' => 'yes',
				],
            ]
        );
		$this->add_responsive_control(
			'dropcap_padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','%','em'],
				'selectors' => [
					'{{WRAPPER}} .pt-plus-text-block-wrapper .tp-blockquote-dropcap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'dropcap_typography',
				'selector' => '{{WRAPPER}} .pt-plus-text-block-wrapper .tp-blockquote-dropcap',				
			]
		);
		$this->start_controls_tabs( 'tabs_dropcap' );
		$this->start_controls_tab(
			'tab_dropcap_n',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
			]
		);
		$this->add_control(
			'dropcap_color_n',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pt-plus-text-block-wrapper .tp-blockquote-dropcap' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_dropcap_h',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_control(
			'dropcap_color_h',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pt-plus-text-block-wrapper .plus_blockquote:hover .tp-blockquote-dropcap' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*dropcap start*/
		
		/*desc start*/
		$this->start_controls_section(
            'section_desc_styling',
            [
                'label' => esc_html__('Description', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'desc_padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-desc,{{WRAPPER}} .plus_blockquote .tp-bq-desc p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],				
			]
		);
		$this->add_responsive_control(
			'desc_margin',
			[
				'label' => esc_html__( 'Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-desc,{{WRAPPER}} .plus_blockquote .tp-bq-desc p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		/*desc end*/
		
		/*author start*/
		$this->start_controls_section(
            'section_author_styling',
            [
                'label' => esc_html__('Author', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style!' => 'style-1',
				],
            ]
        );
		$this->add_responsive_control(
			'author_align',
			[
				'label' => esc_html__( 'Alignment', 'tpebl' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-text .quote_author' => 'justify-content: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'author_main_heading',
			[
				'label' => 'Author Name',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',	
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_main_typography',
				'selector' => '{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-text .quote_author',
			]
		);
		$this->add_responsive_control(
			'author_main_margin',
			[
				'label' => esc_html__( 'Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-text .quote_author' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'author_main_padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-text .quote_author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'author_main_color',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-text .quote_author' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'author_desc_heading',
			[
				'label' => 'Author Description',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',	
			]
		);
		$this->add_responsive_control(
			'author_desc_margin',
			[
				'label' => esc_html__( 'Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-text .quote_author .quote_author-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'author_desc_padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-text .quote_author .quote_author-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_desc_typography',
				'selector' => '{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-text .quote_author .quote_author-desc',				
			]
		);
		$this->add_control(
			'author_desc_color',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-text .quote_author .quote_author-desc' => 'color: {{VALUE}};',
				],
			]
		);		
		$this->add_control(
			'author_extras_heading',
			[
				'label' => esc_html__( 'Author Extras', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'author_extras_position',
			[
				'label' => esc_html__( 'Position', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Absolute', 'tpebl' ),
				'label_off' => esc_html__( 'Relative', 'tpebl' ),
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote_author' => 'position: absolute;',
				],
				'condition' => [
					'author_extras_heading' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
            'ae_left',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Left', 'tpebl'),
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => -700,
						'max' => 700,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote_author' => 'left:{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'author_extras_heading' => 'yes',
					'author_extras_position' => 'yes',
				],
            ]
        );
		$this->add_responsive_control(
            'ae_right',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Top', 'tpebl'),
				'size_units' => [ 'px' , '%'],
				'range' => [
					'px' => [
						'min' => -700,
						'max' => 700,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote_author' => 'top:{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'author_extras_heading' => 'yes',
					'author_extras_position' => 'yes',
				],
            ]
        );
		$this->add_responsive_control(
			'ae_padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote_author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'author_extras_heading' => 'yes',
					'author_extras_position' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'ae_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .plus_blockquote .quote_author',
				'condition' => [
					'author_extras_heading' => 'yes',
					'author_extras_position' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ae_border',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .plus_blockquote .quote_author',
				'separator' => 'before',
				'condition' => [
					'author_extras_heading' => 'yes',
					'author_extras_position' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'ae_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote .quote_author' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
				],
				'condition' => [
					'author_extras_heading' => 'yes',
					'author_extras_position' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'ae_shadow',
				'selector' => '{{WRAPPER}} .plus_blockquote .quote_author',
				'condition' => [
					'author_extras_heading' => 'yes',
					'author_extras_position' => 'yes',
				],
			]
		);
		$this->end_controls_section();
		/*author start*/
		
		/*image start*/
		$this->start_controls_section(
            'section_image_styling',
            [
                'label' => esc_html__('Image', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style!' => 'style-1',
					'quote_iamge_switch' => 'yes',
				],
            ]
        );
		$this->add_responsive_control(
			'image_padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .tp-bq-imr-wrap img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'image_margin',
			[
				'label' => esc_html__( 'Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .tp-bq-imr-wrap img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'image_align',
			[
				'label' => esc_html__( 'Alignment', 'tpebl' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors'  => [
					'{{WRAPPER}} .tp-bq-imr-wrap' => 'justify-content: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
            'image_size',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Size', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
						'step' => 1,
					],
				],
				'default'=>[
					'unit'=>'px',
					'size'=>100,
				],
				'render_type' => 'ui',
				'selectors' => [					
					'{{WRAPPER}} .tp-bq-imr-wrap img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'image_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .tp-bq-imr-wrap img',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-bq-imr-wrap img',
			]
		);
		$this->add_responsive_control(
			'image_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tp-bq-imr-wrap img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],	
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_shadow',
				'selector' => '{{WRAPPER}} .tp-bq-imr-wrap img',				
			]
		);
		$this->end_controls_section();
		/*image start*/
		
		/*icon start*/
		$this->start_controls_section(
            'section_icon_styling',
            [
                'label' => esc_html__('Icon', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
				],
            ]
        );
		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote-left i,{{WRAPPER}}  .plus_blockquote .quote-left-both i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
            'icon_size',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Size', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote-left i,{{WRAPPER}}  .plus_blockquote .quote-left-both i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
            ]
        );
		$this->add_responsive_control(
            'icon_pos_top1',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Top Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote-left.qip_top' => 'top: {{SIZE}}{{UNIT}} !important',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_top',
				],
            ]
        );
		$this->add_responsive_control(
            'icon_pos_top_left1',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Left Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote-left.qipa_left' => 'left: {{SIZE}}{{UNIT}} !important',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_top',
					'quote_icon_pos_align' => 'qipa_left',
				],
            ]
        );
		$this->add_responsive_control(
            'icon_pos_top_right1',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Right Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote-left.qipa_right' => 'right: {{SIZE}}{{UNIT}} !important',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_top',
					'quote_icon_pos_align' => 'qipa_right',
				],
            ]
        );
		$this->add_responsive_control(
            'icon_pos_bottom_bottom1',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Bottom Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote-left.qip_bottm' => 'bottom: {{SIZE}}{{UNIT}} !important',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_bottm',
				],
            ]
        );
		$this->add_responsive_control(
            'icon_pos_bottom_left1',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Left Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote-left.qip_bottm.qipa_left' => 'left: {{SIZE}}{{UNIT}} !important',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_bottm',
					'quote_icon_pos_align' => 'qipa_left',
				],
            ]
        );
		$this->add_responsive_control(
            'icon_pos_bottom_right1',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Right Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote-left.qip_bottm.qipa_right' => 'right: {{SIZE}}{{UNIT}} !important',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_bottm',
					'quote_icon_pos_align' => 'qipa_right',
				],
            ]
        );
		/*both*/
		$this->add_responsive_control(
            'icon_pos_both_top_bottom1',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Top/Bottom Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left.qip_both' => 'top: {{SIZE}}{{UNIT}} !important',
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-text>span.quote-left-both' => 'bottom: {{SIZE}}{{UNIT}} !important',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_both',
				],
            ]
        );
		$this->add_responsive_control(
            'iplt_icon',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Left Top Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left.qipa_opposite' => 'left: {{SIZE}}{{UNIT}} !important;right:auto',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_both',
					'quote_icon_pos_align_both' => 'qipa_opposite',
				],
            ]
        );
		$this->add_responsive_control(
            'ipbr_icon',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Right Bottom Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left-both.qipa_opposite' => 'right: {{SIZE}}{{UNIT}} !important;left:auto',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_both',
					'quote_icon_pos_align_both' => 'qipa_opposite',
				],
            ]
        );
		$this->add_responsive_control(
            'ipld_icon',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Left Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left.qip_both.qipa_left,{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left-both.qip_both.qipa_left ' => 'left: {{SIZE}}{{UNIT}} !important;right:auto',
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left-both.qip_both.qipa_left' => 'transform: rotate(180deg)',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_both',
					'quote_icon_pos_align_both' => 'qipa_left',
				],
            ]
        );
		$this->add_responsive_control(
            'ipright_icon',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Right Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left.qip_both.qipa_right,{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left-both.qip_both.qipa_right ' => 'right: {{SIZE}}{{UNIT}} !important;left:auto',
					'{{WRAPPER}} .plus_blockquote.quote-style-2 .quote-left-both.qip_both.qipa_right' => 'transform: rotate(180deg)',
				],
				'condition' => [
					'style!' => 'style-1',
					'quote_icon' => 'yes',
					'quote_icon_pos' => 'qip_both',
					'quote_icon_pos_align_both' => 'qipa_right',
				],
            ]
        );		
		/*both*/
		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .quote-left i,{{WRAPPER}}  .plus_blockquote .quote-left-both i' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'icon_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .plus_blockquote .quote-left i,{{WRAPPER}}  .plus_blockquote .quote-left-both i',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .plus_blockquote .quote-left i,{{WRAPPER}}  .plus_blockquote .quote-left-both i',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'icon_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote .quote-left i,{{WRAPPER}}  .plus_blockquote .quote-left-both i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
				],	
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_shadow',
				'selector' => '{{WRAPPER}} .plus_blockquote .quote-left i,{{WRAPPER}}  .plus_blockquote .quote-left-both i',				
			]
		);
		$this->end_controls_section();
		/*icon start*/
		
		/*Tweet start*/
		$this->start_controls_section(
            'section_tweet_styling',
            [
                'label' => esc_html__('Tweet Button', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'quote_tweet' => 'yes',
				],
            ]
        );
		$this->add_responsive_control(
			'tweet_align',
			[
				'label' => esc_html__( 'Alignment', 'tpebl' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-start',
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper' => 'justify-content: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'tweet_padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','%','em'],
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'tweet_margin',
			[
				'label' => esc_html__( 'Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','%','em'],
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tweet_typography',
				'selector' => '{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet',				
			]
		);	
		$this->add_responsive_control(
            'tweet_svg_size',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Icon Size', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet svg' => 'width: {{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
            ]
        );
		$this->add_responsive_control(
            'tweet_svg_offset',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Icon Offset', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet i,{{WRAPPER}}  .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet svg' => 'margin-right:{{SIZE}}{{UNIT}}',
				],
            ]
        );
		$this->start_controls_tabs( 'tabs_tweet' );
		$this->start_controls_tab(
			'tab_tweet_n',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
			]
		);
		$this->add_control(
			'tweet_color_n',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet' => 'color: {{VALUE}};',
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tweet_background_n',
				'label' => esc_html__( 'Background', 'tpebl' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tweet_border_n',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet',
			]
		);
		$this->add_responsive_control(
			'tweet_br_n',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tweet_shadow_n',
				'selector' => '{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_tweet_h',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_control(
			'tweet_color_h',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tweet_background_h',
				'label' => esc_html__( 'Background', 'tpebl' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tweet_border_h',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet:hover',
			]
		);
		$this->add_responsive_control(
			'tweet_br_h',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tweet_shadow_h',
				'selector' => '{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'tweet_extras_heading',
			[
				'label' => esc_html__( 'Tweet Extras', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'tweet_extras_position',
			[
				'label' => esc_html__( 'Position', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Absolute', 'tpebl' ),
				'label_off' => esc_html__( 'Relative', 'tpebl' ),
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet' => 'position: absolute;',
				],
				'condition' => [
					'tweet_extras_heading' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
            'tw_left',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Left', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -700,
						'max' => 700,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet' => 'left:{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'tweet_extras_heading' => 'yes',
					'tweet_extras_position' => 'yes',
				],
            ]
        );
		$this->add_responsive_control(
            'tw_right',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Top', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -700,
						'max' => 700,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote .tp-bq-tweet-wrapper .tp-bq-tweet' => 'top:{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'tweet_extras_heading' => 'yes',
					'tweet_extras_position' => 'yes',
				],
            ]
        );
		$this->end_controls_section();
		/*Tweet end*/
		
		/*Border Layout 1 start*/
		$this->start_controls_section(
            'section_border_l1_styling',
            [
                'label' => esc_html__('Border Layout', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'border_layout' => 'bl_1',
				],
            ]
        );
		$this->add_control(
			'border_l1_inner_heading',
			[
				'label' => 'Inner Part',
				'type' => \Elementor\Controls_Manager::HEADING,				
			]
		);
		$this->add_responsive_control(
            'bl1o_i_height',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Height', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_1' => 'height: {{SIZE}}{{UNIT}};',
				],
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'bl1o_i_background_h',
				'label' => esc_html__( 'Background', 'tpebl' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .plus_blockquote.bl_1 blockquote.quote-text',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'bl1o_i_border_h',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .plus_blockquote.bl_1 blockquote.quote-text',
			]
		);
		$this->add_responsive_control(
			'bl1o_i__br_h',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote.bl_1 blockquote.quote-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'bl1o_i_shadow_h',
				'selector' => '{{WRAPPER}} .plus_blockquote.bl_1 blockquote.quote-text',
			]
		);
		$this->add_control(
			'border_l1_outer_heading',
			[
				'label' => 'Outer Part',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		$this->add_responsive_control(
            'bl1o_size',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Border Size', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_1:before,{{WRAPPER}} .plus_blockquote.bl_1:after' => 'border-width: {{SIZE}}{{UNIT}};',
				],
            ]
        );
		$this->add_control(
			'bl1o_color',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_1:before,{{WRAPPER}} .plus_blockquote.bl_1:after' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'bl1o_br',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote.bl_1:before,{{WRAPPER}} .plus_blockquote.bl_1:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->end_controls_section();
		/*Border Layout 1 end*/
		
		/*Border Layout 2 start*/
		$this->start_controls_section(
            'section_border_l2_styling',
            [
                'label' => esc_html__('Border Layout', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'border_layout' => 'bl_2',
				],
            ]
        );
		$this->add_responsive_control(
            'bl2o_width_height',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Background Size', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 700,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_2' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
            ]
        );
		$this->add_control(
			'bl2o_bg_heading',
			[
				'label' => 'Normal',
				'type' => \Elementor\Controls_Manager::HEADING,							
			]
		);
		$this->add_control(
			'bl2o_bgcolor1',
			[
				'label' => esc_html__( 'Background Color1', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
			]
		);
		$this->add_control(
			'bl2o_bgcolor2',
			[
				'label' => esc_html__( 'Background Color2', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_2' => 'background: radial-gradient( ellipse at center, {{bl2o_bgcolor1.VALUE}}, {{value}} 70%, rgb(176 187 191 / 0%) 70.3% );',
				],
			]
		);
		$this->add_control(
			'bl2o_bg_hover_heading',
			[
				'label' => 'Hover',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		$this->add_control(
			'bl2o_bgcolor1_h',
			[
				'label' => esc_html__( 'Background Color1', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
			]
		);
		$this->add_control(
			'bl2o_bgcolor2_h',
			[
				'label' => esc_html__( 'Background Color2', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_2:hover' => 'background: radial-gradient( ellipse at center, {{bl2o_bgcolor1_h.VALUE}}, {{value}} 70%, rgb(176 187 191 / 0%) 70.3% );',
				],
			]
		);
		$this->end_controls_section();
		/*Border Layout 2 end*/
		
		/*Border Layout 3 start*/
		$this->start_controls_section(
            'section_border_l3_styling',
            [
                'label' => esc_html__('Border Layout', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'border_layout' => 'bl_3',
				],
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'bl3_border',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .plus_blockquote.bl_3',
			]
		);
		$this->add_control(
			'bl3_corner_dot_bg_color',
			[
				'label' => esc_html__( 'Corner Background Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'bl3_corner_dot_color',
			[
				'label' => esc_html__( 'Corner Border Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box.tp-corner-box-left' => 'border-color: transparent transparent {{VALUE}} transparent;',
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box.tp-corner-box-right' => 'border-color:{{VALUE}} transparent transparent transparent;',
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box.tp-corner-box-top' => 'border-color:transparent transparent transparent {{VALUE}};',
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box.tp-corner-box-bottom' => 'border-color:transparent {{VALUE}} transparent transparent;',
				],
			]
		);
		$this->add_responsive_control(
            'bl3_corner_dot_bs',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Corner Border Size', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box' => 'border-width: {{SIZE}}{{UNIT}};',
				],
            ]
        );
		$this->add_responsive_control(
            'bl3_corner_dot_bsize',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Corner Position', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 150,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box.tp-corner-box-left' => 'top: -{{SIZE}}{{UNIT}};left:-{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box.tp-corner-box-right' => 'bottom: -{{SIZE}}{{UNIT}};right:-{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box.tp-corner-box-top' => 'top: -{{SIZE}}{{UNIT}};right:-{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box.tp-corner-box-bottom' => 'bottom: -{{SIZE}}{{UNIT}};left:-{{SIZE}}{{UNIT}};',
				],
            ]
        );
		$this->add_responsive_control(
            'bl3_corner_dot_bsize_n',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Corner Size', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 150,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote.bl_3 .tp-corner-box' => 'height: {{SIZE}}{{UNIT}};width:{{SIZE}}{{UNIT}};',
				],
            ]
        );
		$this->end_controls_section();
		/*Border Layout 3 end*/
		
		/*background option*/
		$this->start_controls_section(
            'section_bg_option_styling',
            [
                'label' => esc_html__('Background Options', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'box_padding_n',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'box_margin_n',
			[
				'label' => esc_html__( 'Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em'],				
				'selectors' => [
					'{{WRAPPER}} .plus_blockquote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'box_border',
			[
				'label' => esc_html__( 'Box Border', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
				'default' => 'no',
			]
		);
		$this->add_control(
			'border_style',
			[
				'label' => esc_html__( 'Border Style', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => l_theplus_get_border_style(),
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'box_border' => 'yes',
				],
			]
		);
		$this->start_controls_tabs( 'tabs_border_style' );
		$this->start_controls_tab(
			'tab_border_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
			]
		);
		$this->add_control(
			'box_border_color',
			[
				'label' => esc_html__( 'Border Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#252525',
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'box_border_width',
			[
				'label' => esc_html__( 'Border Width', 'tpebl' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'    => 1,
					'right'  => 1,
					'bottom' => 1,
					'left'   => 1,
				],
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'box_border' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_border_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_control(
			'box_border_hover_color',
			[
				'label' => esc_html__( 'Border Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#252525',
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'border_hover_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plus_blockquote:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'background_options',
			[
				'label' => esc_html__( 'Background Options', 'tpebl' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->start_controls_tabs( 'tabs_background_style' );
		$this->start_controls_tab(
			'tab_background_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'box_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .plus_blockquote',
				
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_background_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'box_hover_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .plus_blockquote:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'shadow_options',
			[
				'label' => esc_html__( 'Box Shadow Options', 'tpebl' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->start_controls_tabs( 'tabs_shadow_style' );
		$this->start_controls_tab(
			'tab_shadow_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .plus_blockquote',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_shadow_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_hover_shadow',
				'selector' => '{{WRAPPER}} .plus_blockquote:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*background option*/
		/*Adv tab*/
		$this->start_controls_section(
            'section_plus_extra_adv',
            [
                'label' => esc_html__('Plus Extras', 'tpebl'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );
		$this->end_controls_section();
		/*Adv tab*/
		$this->start_controls_section(
            'section_animation_styling',
            [
                'label' => esc_html__('On Scroll View Animation', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'animation_effects',
			[
				'label'   => esc_html__( 'Choose Animation Effect', 'tpebl' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no-animation',
				'options' => l_theplus_get_animation_options(),
			]
		);
		$this->add_control(
            'animation_delay',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Animation Delay', 'tpebl'),
				'default' => [
					'unit' => '',
					'size' => 50,
				],
				'range' => [
					'' => [
						'min'	=> 0,
						'max'	=> 4000,
						'step' => 15,
					],
				],
				'condition' => [
					'animation_effects!' => 'no-animation',
				],
            ]
        );
		$this->add_control(
            'animation_duration_default',
            [
				'label'   => esc_html__( 'Animation Duration', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition' => [
					'animation_effects!' => 'no-animation',
				],
			]
		);
		$this->add_control(
            'animate_duration',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Duration Speed', 'tpebl'),
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'range' => [
					'px' => [
						'min'	=> 100,
						'max'	=> 10000,
						'step' => 100,
					],
				],
				'condition' => [
					'animation_effects!' => 'no-animation',
					'animation_duration_default' => 'yes',
				],
            ]
        );
		$this->add_control(
			'animation_out_effects',
			[
				'label'   => esc_html__( 'Out Animation Effect', 'tpebl' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no-animation',
				'options' => l_theplus_get_out_animation_options(),
				'separator' => 'before',
				'condition' => [
					'animation_effects!' => 'no-animation',
				],
			]
		);
		$this->add_control(
            'animation_out_delay',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Out Animation Delay', 'tpebl'),
				'default' => [
					'unit' => '',
					'size' => 50,
				],
				'range' => [
					'' => [
						'min'	=> 0,
						'max'	=> 4000,
						'step' => 15,
					],
				],
				'condition' => [
					'animation_effects!' => 'no-animation',
					'animation_out_effects!' => 'no-animation',
				],
            ]
        );
		$this->add_control(
            'animation_out_duration_default',
            [
				'label'   => esc_html__( 'Out Animation Duration', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition' => [
					'animation_effects!' => 'no-animation',
					'animation_out_effects!' => 'no-animation',
				],
			]
		);
		$this->add_control(
            'animation_out_duration',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Duration Speed', 'tpebl'),
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'range' => [
					'px' => [
						'min'	=> 100,
						'max'	=> 10000,
						'step' => 100,
					],
				],
				'condition' => [
					'animation_effects!' => 'no-animation',
					'animation_out_effects!' => 'no-animation',
					'animation_out_duration_default' => 'yes',
				],
            ]
        );
		$this->end_controls_section();
	}
	
	 protected function render() {

        $settings = $this->get_settings_for_display();
		$content_description = $settings['content_description'];
		$quote_style = $settings['style'];
		$quote_author = $settings['quote_author'];
		$quote_author_desc = !empty($settings['quote_author_desc']) ? $settings['quote_author_desc'] : '';
		
		/*border layout*/
		$border_layout = !empty($settings['border_layout']) ? $settings['border_layout'] : 'none';
		$blclass='';
		if($border_layout !='none'){
			$blclass=' tpblcls';
		}
		
		/*icon*/
		$quote_icon = isset($settings['quote_icon']) ? $settings['quote_icon'] : '';
		$quote_icon_pos = !empty($settings['quote_icon_pos']) ? $settings['quote_icon_pos'] : 'qip_top';
		$quote_icon_pos_align = !empty($settings['quote_icon_pos_align']) ? $settings['quote_icon_pos_align'] : 'qipa_left';
		$quote_icon_pos_align_both = !empty($settings['quote_icon_pos_align_both']) ? $settings['quote_icon_pos_align_both'] : 'qipa_opposite';
		
		$quote_icon_s=$quote_icon_pos_s=$quote_icon_pos_align_s=$quote_tweet_icons=$quote_tweet_text=$quote_img=$tweetlink=$twt_btn=$udata=$quote_author_desccon='';
		
		/*tweet button*/
		$quote_tweet = isset($settings['quote_tweet']) ? $settings['quote_tweet'] : 'no';
		if($quote_tweet==='yes'){
			if(!empty($settings['quote_tweet_icon_select'])){
				ob_start();
				\Elementor\Icons_Manager::render_icon( $settings['quote_tweet_icon_select'], [ 'aria-hidden' => 'true' ]);
				$quote_tweet_icons = ob_get_contents();
				ob_end_clean();
			}
			
			$quote_tweet_text = !empty($settings['quote_tweet_text']) ? $settings['quote_tweet_text'] : '';
			
			if(!empty($settings['content_description'])){
				$udata .= strip_tags($settings['content_description']);
			}
			if(!empty($settings['quote_author'])){
				$udata .= ' — ' .strip_tags($settings['quote_author']);
			}			
			if(!empty($settings['quote_author_desc'])){
				$udata .= ' ( '.strip_tags($settings['quote_author_desc']).' ) ';
			}
			
			$quote_tweet_link = isset($settings['quote_tweet_link']) ? $settings['quote_tweet_link'] : 'no';
			if($quote_tweet_link==='yes'){
				$urldata = "http://";   
				if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
					$urldata = "https://";   
				}
				$urldata .= $_SERVER['HTTP_HOST'];
				$urldata .= $_SERVER['REQUEST_URI'];
				$udata .= " ".urlencode($urldata);				
			}			
			
			$twt_btn = '<div class="tp-bq-tweet-wrapper"><a href="https://twitter.com/intent/tweet?text='.esc_attr($udata).'" class="tp-bq-tweet" target="_blank">'.$quote_tweet_icons.esc_html($quote_tweet_text).'</a></div>';
			
		}
		
		/*image*/
		if(isset($settings['quote_iamge_switch']) && $settings['quote_iamge_switch']=='yes' && !empty($settings['quote_image']['url'])){
			$quote_imageid = $settings['quote_image']['id'];
			$quote_image_src = tp_get_image_rander( $quote_imageid,'full', [ 'class' => 'tp-bq-img' ] );
			$quote_img ='<div class="tp-bq-imr-wrap" >'.$quote_image_src.'</div>';			
		}	
		
			$animation_effects=$settings["animation_effects"];
			$animation_delay= (!empty($settings["animation_delay"]["size"])) ? $settings["animation_delay"]["size"] : 50;
			if($animation_effects=='no-animation'){
				$animated_class = '';
				$animation_attr = '';
			}else{
				$animate_offset = l_theplus_scroll_animation();
				$animated_class = 'animate-general';
				$animation_attr = ' data-animate-type="'.esc_attr($animation_effects).'" data-animate-delay="'.esc_attr($animation_delay).'"';
				$animation_attr .= ' data-animate-offset="'.esc_attr($animate_offset).'"';
				if($settings["animation_duration_default"]=='yes'){
					$animate_duration=$settings["animate_duration"]["size"];
					$animation_attr .= ' data-animate-duration="'.esc_attr($animate_duration).'"';
				}
				if(!empty($settings["animation_out_effects"]) && $settings["animation_out_effects"]!='no-animation'){
					$animation_attr .= ' data-animate-out-type="'.esc_attr($settings["animation_out_effects"]).'" data-animate-out-delay="'.esc_attr($settings["animation_out_delay"]["size"]).'"';					
					if($settings["animation_out_duration_default"]=='yes'){						
						$animation_attr .= ' data-animate-out-duration="'.esc_attr($settings["animation_out_duration"]["size"]).'"';
					}
				}
			}	
			
			$text_block ='<div class="pt-plus-text-block-wrapper" >';
				$text_block .='<div class="text_block_parallax">';
					$lz1 = function_exists('tp_has_lazyload') ? tp_bg_lazyLoad($settings['box_background_image'],$settings['box_hover_background_image']) : '';
					
					$text_block .='<div class="plus_blockquote quote-'.esc_attr($quote_style).' '.esc_attr($animated_class).' '.esc_attr($lz1).' '.esc_attr($border_layout).' '.esc_attr($blclass).'" '.$animation_attr.'>';
						if($border_layout==='bl_3'){
							$text_block .='<div class="tp-corner-box tp-corner-box-left"></div>
							<div class="tp-corner-box tp-corner-box-right"></div>
							<div class="tp-corner-box tp-corner-box-top"></div>
							<div class="tp-corner-box tp-corner-box-bottom"></div>';
						}
						$text_block .= '<blockquote class="quote-text">';
							if($quote_style=='style-2'){
								if($quote_icon=='yes' && !empty($settings['quote_icon_select'])){
									ob_start();
									\Elementor\Icons_Manager::render_icon( $settings['quote_icon_select'], [ 'aria-hidden' => 'true' ]);
									$quote_icon_s = ob_get_contents();
									ob_end_clean();
									
									if(!empty($quote_icon_pos) && ($quote_icon_pos=='qip_top' || $quote_icon_pos=='qip_bottm')){
										$quote_icon_pos_s = $quote_icon_pos;
										if(!empty($quote_icon_pos_align)){
											$quote_icon_pos_align_s = $quote_icon_pos_align;
										}
									}
									
									if(!empty($quote_icon_pos) && $quote_icon_pos=='qip_both'){
										$quote_icon_pos_s = $quote_icon_pos;
										if(!empty($settings['quote_icon_pos_align_both'])){
											$quote_icon_pos_align_s = $quote_icon_pos_align_both;
										}
									}
									$text_block .= '<span class="quote-left '.esc_attr($quote_icon_pos).' '.esc_attr($quote_icon_pos_align_s).'">'.$quote_icon_s.'</span>';
								}else{
									$text_block .= '<i class="fa fa-quote-left quote-left" aria-hidden="true"></i>';
								}
								if(isset($settings['quote_iamge_switch']) && $settings['quote_iamge_switch']=='yes' && !empty($settings['quote_image']['url'])){
									$text_block .= $quote_img;
								}
							}
							
							if(isset($settings['quote_dropcap']) && $settings['quote_dropcap']=='yes' && $quote_style !='style-2'){
								$content = wp_kses_post($content_description);
								if( $content[0] == '<' ) {
                                    $position = strpos($content, '>');
                                    $pos = $position + 2;
                                    $FirstPos = $position + 1;
                                    $result = substr($content, 0, $pos);

                                    $text_block .= '<span><span class="tp-blockquote-dropcap">'.$content[$FirstPos].'</span> '.trim($content, $result) .' </span>';
                                }else{
                                    $text_block .= '<span class="tp-bq-desc">'.($content_description).'</span>';
                                }
							}else{
								$text_block .= '<span class="tp-bq-desc">'.wp_kses_post($content_description).'</span>';
							}							
							
							if($quote_style=='style-2' && !empty($quote_author)){
								if(!empty($quote_author_desc)){
									$quote_author_desccon = '<span class="quote_author-desc">'.esc_html($quote_author_desc).'</span>';
								}
								$text_block .= '<p class="quote_author">'.esc_html($quote_author).$quote_author_desccon.'</p>';
							}
							/*tweet button*/
							$text_block .= $twt_btn;
							
							/*second quote*/
							if($quote_style=='style-2' && $quote_icon=='yes' && !empty($settings['quote_icon_select']) && !empty($quote_icon_pos) && $quote_icon_pos=='qip_both' && !empty($settings['quote_icon_pos_align_both'])){
								$quote_icon_pos_align_s = $quote_icon_pos_align_both;
								
								$text_block .= '<span class="quote-left-both '.esc_attr($quote_icon_pos).' '.esc_attr($quote_icon_pos_align_s).'">'.$quote_icon_s.'</span>';
							}
							
						$text_block .= '</blockquote>';
					$text_block .='</div>';
				$text_block .='</div>';
			$text_block .='</div>';			
			
		echo $text_block;
	}
	
    protected function content_template() {
	
    }

}