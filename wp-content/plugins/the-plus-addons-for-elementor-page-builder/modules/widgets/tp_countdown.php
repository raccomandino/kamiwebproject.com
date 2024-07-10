<?php 
/*
Widget Name: Countdown 
Description: Display countdown.
Author: Theplus
Author URI: https://posimyth.com
*/

namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class L_ThePlus_Countdown extends Widget_Base {
		
	public function get_name() {
		return 'tp-countdown';
	}

    public function get_title() {
        return esc_html__('Countdown', 'tpebl');
    }

    public function get_icon() {
        return 'fa fa-clock-o theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-essential');
    }

	public function get_keywords() {
		return ['Countdown', 'Count down', 'time', 'fake number', 'tp', 'the plus'];
	}

    protected function register_controls() {
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Countdown Date', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control('CDType',
			[
				'label' => esc_html__( 'Countdown Setup', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal'  => esc_html__( 'Normal Countdown', 'tpebl' ),
					'scarcity' => esc_html__( 'Scarcity Countdown (Evergreen) (Pro)', 'tpebl' ),
					'numbers' => esc_html__( 'Fake Numbers Counter (Pro)', 'tpebl' ),
				],
			]
		);
		$this->add_control('tab_content_options1',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition' => [
					'CDType' => ['scarcity','numbers'],
				]
			]
		);
		$this->add_control('CDstyle',
			[
				'label' => esc_html__( 'Countdown Style', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => [
					'style-1'  => esc_html__( 'Style 1', 'tpebl' ),
					'style-2' => esc_html__( 'Style 2', 'tpebl' ),
					'style-3' => esc_html__( 'Style 3 ', 'tpebl' ),
				],
				'condition' => [
					'CDType' => 'normal',
				],
			]
		);

		$this->add_control(
			'counting_timer',
			[
				'label' => esc_html__( 'Launch Date', 'tpebl' ),
				'type' => Controls_Manager::DATE_TIME,
				'default'     => date( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				'description' => sprintf( esc_html__( 'Date set according to your timezone: %s.', 'tpebl' ), Utils::get_timezone_string() ),
				'condition' => [
					'CDType' => 'normal',
				],
			]
		);
		$this->add_control(
			'inline_style',
			[
				'label' => esc_html__( 'Inline Style', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'tpebl' ),
				'label_off' => esc_html__( 'Off', 'tpebl' ),
				'default' => 'no',
				'separator' => 'before',
				'condition' => [
					'CDType' => 'normal',
					'CDstyle' => 'style-1',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_downcount',
            [
                'label' => esc_html__('Content Source', 'tpebl'),
            ]
        );
		$this->add_control('days_labels',
			[
				'label' => esc_html__( 'Days', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
				'default' => 'yes',
			]
		);
		$this->add_control('hours_labels',
			[
				'label' => esc_html__( 'Hours', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
				'default' => 'yes',
			]
		);

		$this->add_control('minutes_labels',
			[
				'label' => esc_html__( 'Minutes', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
				'default' => 'yes',
			]
		);

		$this->add_control('seconds_labels',
			[
				'label'   => esc_html__( 'Seconds', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
				'separator' => 'after',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'show_labels',
			[
				'label' => esc_html__( 'Show Labels', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
            'text_days',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Days Section Text', 'tpebl'),
                'separator' => 'before',
                'default' => esc_html__('Days', 'tpebl'),
				'condition'    => [
					'show_labels!' => '',
				],
            ]
        );
		$this->add_control(
            'text_hours',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Hours Section Text', 'tpebl'),
                'default' => esc_html__('Hours', 'tpebl'),
				'condition' => [
					'show_labels!' => '',
				],
            ]
        );
		$this->add_control(
            'text_minutes',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Minutes Section Text', 'tpebl'),
                'default' => esc_html__('Minutes', 'tpebl'),
                'condition' => [
					'show_labels!' => '',
				],
            ]
        );
		$this->add_control(
            'text_seconds',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Seconds Section Text', 'tpebl'),
                'default' => esc_html__('Seconds', 'tpebl'),
				'condition' => [
					'show_labels!' => '',
				],
            ]
        );
		$this->end_controls_section();
		$this->start_controls_section('extraoption_downcount',
            [
                'label' => esc_html__('Extra Option', 'tpebl'),
				'condition' => [
					'CDType' => ['normal','scarcity'],
					'CDstyle' => 'style-2',
				],	
            ]
        );
		$this->add_control('fliptheme',
			[
				'label' => esc_html__( 'Theme Color', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dark',
				'options' => [
					'dark'  => esc_html__( 'Dark', 'tpebl' ),
					'light' => esc_html__( 'Light', 'tpebl' ),
					'mix' => esc_html__( 'Mix', 'tpebl' ),
				],
				'condition' => [
					'CDType' => ['normal','scarcity'],
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						['name' => 'CDstyle', 'operator' => '===', 'value' => 'style-2'],
					],
				],
			]
		);
		$this->add_control('style_extra',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition' => [
					'fliptheme!' => 'dark',
				]
			]
		);
		$this->add_control('expirytype',
			[
				'label' => esc_html__( 'After Expiry Action', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'return_value' => 'yes',
				'default' => '',
				'conditions'=>[
					'relation'=>'or',
					'terms' => [
						[
							'terms' => [
								[ 'name' => 'CDType', 'operator'=>'===', 'value' => 'normal' ],
							]
						],
					]
				],
			]
		);
		$this->add_control('expirytype_pro',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition' => [
					'expirytype' => 'yes',
				]
			]
		);
		$this->add_control('countdownExpiry',
			[
				'label' => esc_html__( 'Select Action', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'tpebl' ),
					'showmsg' => esc_html__( 'Message', 'tpebl' ),
					'showtemp' => esc_html__( 'Template', 'tpebl' ),
					'redirect' => esc_html__( 'Page Redirect', 'tpebl' ),
				],
				'conditions'=>[
					'relation'=>'or',
					'terms' => [
						[
							'terms' => [
								[ 'name' => 'CDType', 'operator'=>'===', 'value' => 'normal' ],
							]
						],
					]
				],
				
			]
		);
		$this->add_control('countdownExpiry_pro',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition' => [
					'countdownExpiry!' => 'none',
				]
			]
		);
		$this->add_control('cd_classbased',[
				'label'   => esc_html__( 'Class Based Section Visibility', 'tpebl' ),
				'type'    =>  Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'separator' => 'before',
				'condition' => [
					'CDType' => 'normal',
					'CDstyle!' => 'style-3',
				],
			]
		);
		$this->add_control('cd_classbasedPro',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition' => [
					'cd_classbased' => 'yes',
				]
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
            'section_styling',
            [
                'label' => esc_html__('Counter Styling', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,	
				'condition' => [
					'show_labels!' => '',
					'CDstyle' => 'style-1',
				],			
            ]
        );
		$this->add_control(
            'number_text_color',
            [
                'label' => esc_html__('Counter Font Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li > span' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(Group_Control_Typography::get_type(),
			array(
				'name' => 'numbers_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT
				],
				'selector' => '{{WRAPPER}}  .pt_plus_countdown li > span',
				'separator' => 'after',
			)
		);
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => esc_html__('Label Typography', 'tpebl'),
                'selector' => '{{WRAPPER}} .pt_plus_countdown li > h6',
				'separator' => 'after',
				'condition' => [
					'show_labels!' => '',
				],
            ]
        );
		$this->start_controls_tabs( 'tabs_days_style' );

		$this->start_controls_tab(
			'tab_day_style',
			[
				'label' => esc_html__( 'Days', 'tpebl' ),
			]
		);
		$this->add_control(
            'days_text_color',
            [
                'label' => esc_html__('Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_1 h6' => 'color:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'days_border_color',
            [
                'label' => esc_html__('Border Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_1' => 'border-color:{{VALUE}};',
                ],
                'condition'    => [
                    'inline_style!' => 'yes',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'days_background',
				'label' => esc_html__("Days Background",'tpebl'),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .pt_plus_countdown li.count_1',
				
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hour_style',
			[
				'label' => esc_html__( 'Hours', 'tpebl' ),
			]
		);
		$this->add_control(
            'hours_text_color',
            [
                'label' => esc_html__('Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_2 h6' => 'color:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'hours_border_color',
            [
                'label' => esc_html__('Border Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_2' => 'border-color:{{VALUE}};',
                ],
                'condition' => [
                    'inline_style!' => 'yes',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'hours_background',
				'label' => esc_html__("Background",'tpebl'),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .pt_plus_countdown li.count_2',				
			]
		);
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_minute_style',
			[
				'label' => esc_html__( 'Minutes', 'tpebl' ),
			]
		);
		$this->add_control(
            'minutes_text_color',
            [
                'label' => esc_html__('Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_3 h6' => 'color:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'minutes_border_color',
            [
                'label' => esc_html__('Border Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_3' => 'border-color:{{VALUE}};',
                ],
                'condition' => [
                    'inline_style!' => 'yes',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'minutes_background',
				'label' => esc_html__("Background",'tpebl'),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .pt_plus_countdown li.count_3',				
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_second_style',
			[
				'label' => esc_html__( 'Seconds', 'tpebl' ),
			]
		);
		$this->add_control(
            'seconds_text_color',
            [
                'label' => esc_html__('Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_4 h6' => 'color:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'seconds_border_color',
            [
                'label' => esc_html__('Border Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_4' => 'border-color:{{VALUE}};',
                ],
                'condition' => [
                    'inline_style!' => 'yes',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'seconds_background',
				'label' => esc_html__("Background",'tpebl'),
				'types' => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .pt_plus_countdown li.count_4',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'counter_padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .pt_plus_countdown li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_responsive_control(
			'counter_margin',
			[
				'label' => esc_html__( 'Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .pt_plus_countdown li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_control(
			'count_border_style',
			[
				'label' => esc_html__( 'Border Style', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'none' => esc_html__( 'None', 'tpebl' ),
					'solid'  => esc_html__( 'Solid', 'tpebl' ),
					'dotted' => esc_html__( 'Dotted', 'tpebl' ),
					'dashed' => esc_html__( 'Dashed', 'tpebl' ),
					'groove' => esc_html__( 'Groove', 'tpebl' ),
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .pt_plus_countdown li' => 'border-style: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'count_border_width',
			[
				'label' => esc_html__( 'Border Width', 'tpebl' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .pt_plus_countdown li' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'count_border_style!' => 'none',
				]
			]
		);
		$this->add_control(
			'count_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pt_plus_countdown li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'count_hover_shadow',
				'selector' => '{{WRAPPER}} .pt_plus_countdown li',
				'separator' => 'before',
			]			
		);
		
        $this->end_controls_section();
		$this->start_controls_section('style3_styling',
			[
				'label' => esc_html__('Style 3', 'tpebl'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'CDType' => ['normal','scarcity'],
					'CDstyle' => 'style-3',
				],
			]
		);
		$this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'s3numbertypo',
                'label'=>esc_html__('Typography','tpebl'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY
				],
                'selector'=>'{{WRAPPER}} .tp-countdown .tp-countdown-counter .progressbar-text .number',
            ]
        );
		$this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'s3labeltypo',
                'label'=>esc_html__('Typography','tpebl'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY
				],
                'selector'=>'{{WRAPPER}} .tp-countdown .tp-countdown-counter .progressbar-text .label',
            ]
        );
		$this->add_control('strokewd1',
			[
				'label' => esc_html__( 'Stroke Width', 'tpebl' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 5,
				'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter svg > path:nth-of-type(2)' => 'stroke-width:{{VALUE}};',
				],
			]
		);
		$this->add_control('trailwd',
			[
				'label' => esc_html__( 'Trail Width', 'tpebl' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 3,
				'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter svg > path:nth-of-type(1)' => 'stroke-width:{{VALUE}};',
				],
			]
		);
		$this->start_controls_tabs('s3_tabs');
        $this->start_controls_tab('s3_num_days',
            [
                'label'=>esc_html__('Days','tpebl')
            ]
        ); 	
		$this->add_control('s3daynumberncr',
            [
                'label' => esc_html__('Counter Number Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(1) .progressbar-text .number' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3daytextncr',
            [
                'label' => esc_html__('Counter Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(1) .progressbar-text .label' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3daystrokencr',
            [
                'label' => esc_html__('Counter Stroke Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(1) svg > path:nth-of-type(1)' => 'stroke: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3daystrailnncr',
            [
                'label' => esc_html__('Counter Trail Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(1) svg > path:nth-of-type(2)' => 'stroke: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
        $this->start_controls_tab('s3_text_hours',
            [
                'label'=>esc_html__('Hours','tpebl')
            ]
        );
		$this->add_control('s3hoursnumberncr',
            [
                'label' => esc_html__('Counter Number Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(2) .progressbar-text .number' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3hourstextncr',
            [
                'label' => esc_html__('Counter Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(2) .progressbar-text .label' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3hourstrokencr',
            [
                'label' => esc_html__('Counter Stroke Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(2) svg > path:nth-of-type(1)' => 'stroke: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3hourstrailncr',
            [
                'label' => esc_html__('Counter Trail Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(2) svg > path:nth-of-type(2)' => 'stroke: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		$this->start_controls_tab('s3_text_minutes',
            [
                'label'=>esc_html__('Minutes','tpebl')
            ]
        );
		$this->add_control('s3minutnumberncr',
            [
                'label' => esc_html__('Counter Number Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(3) .progressbar-text .number' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3minuttextncr',
            [
                'label' => esc_html__('Counter Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(3) .progressbar-text .label' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3miutstrokencr',
            [
                'label' => esc_html__('Counter Stroke Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(3) svg > path:nth-of-type(1)' => 'stroke: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3miutstrailncr',
            [
                'label' => esc_html__('Counter Trail Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(3) svg > path:nth-of-type(2)' => 'stroke: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		$this->start_controls_tab('s3_text_seconds',
            [
                'label'=>esc_html__('Second','tpebl')
            ]
        );
		$this->add_control('s3secondnumberncr',
			[
				'label' => esc_html__('Counter Number Color', 'tpebl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(4) .progressbar-text .number' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control('s3secondtextncr',
            [
                'label' => esc_html__('Counter Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(4) .progressbar-text .label' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3secondtrokencr',
            [
                'label' => esc_html__('Counter Stroke Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(4) svg > path:nth-of-type(1)' => 'stroke: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3secondstrailncr',
            [
                'label' => esc_html__('Counter Trail Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:nth-of-type(4) svg > path:nth-of-type(2)' => 'stroke: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control('s3hoverstyle',
			[
				'label'=>__('Hover style','tpebl'),
				'type'=>Controls_Manager::HEADING,
				'separator'=>'before',
			]
		);
		$this->add_control('s3numberhcr',
			[
				'label' => esc_html__('Number Color', 'tpebl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:hover .progressbar-text .number' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control('s3texthcr',
            [
                'label' => esc_html__('Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:hover .progressbar-text .label' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3trokehcr',
            [
                'label' => esc_html__('Stroke Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:hover svg > path:nth-of-type(1)' => 'stroke: {{VALUE}};',
                ],
            ]
        );
		$this->add_control('s3strailhcr',
            [
                'label' => esc_html__('Trail Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
					'{{WRAPPER}} .tp-countdown .tp-countdown-counter .counter-part:hover svg > path:nth-of-type(2)' => 'stroke: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_section();
		$this->start_controls_section('style2text_styling',
			[
				'label' => esc_html__('Label', 'tpebl'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'CDType' => ['normal','scarcity'],
					'CDstyle' => 'style-2',					
					'show_labels' => 'yes',					
				],
			]
		);
		$this->add_group_control(Group_Control_Typography::get_type(),
			[
				'name'=>'s2texttypo',
				'label'=>esc_html__('Typography','tpebl'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY
				],
				'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group .rotor-group-heading',
			]
		);
		$this->start_controls_tabs('s32_tabs');
		$this->start_controls_tab('s2_text_days',
			[
				'label'=>esc_html__('Days','tpebl')
			]
		); 	
		$this->add_control('s2daytextdcr',
			[
				'label' => esc_html__('Text Color', 'tpebl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(1) .rotor-group-heading:before' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'s2daytextdbg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(1) .rotor-group-heading:before',
            ]
        );
		$this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'s2daytextdb',
                'label'=>esc_html__('Border','tpebl'),
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(1) .rotor-group-heading:before',
            ]
        );
        $this->add_responsive_control('s2daytextdbrs',
            [
                'label'=>__('Border Radius','tpebl'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(1) .rotor-group-heading:before'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'s2daytextdsd',
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(1) .rotor-group-heading:before',
            ]
        );
		$this->end_controls_tab();
        $this->start_controls_tab('s2_text_hours',
            [
                'label'=>esc_html__('Hours','tpebl')
            ]
        ); 	
		$this->add_control('s2hoursnumberncr',
            [
                'label' => esc_html__('Text Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(2) .rotor-group-heading:before' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'s2daytexttbg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(2) .rotor-group-heading:before',
            ]
        );
		$this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'s2daytexttdb',
                'label'=>esc_html__('Border','tpebl'),
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(2) .rotor-group-heading:before',
            ]
        );
        $this->add_responsive_control('s2daytexttbrs',
            [
                'label'=>__('Border Radius','tpebl'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(2) .rotor-group-heading:before'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'s2daytexttsd',
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(2) .rotor-group-heading:before',
            ]
        );
		$this->end_controls_tab();
		$this->start_controls_tab('s2_text_minutes',
            [
                'label'=>esc_html__('Minutes','tpebl')
            ]
        ); 	
		$this->add_control('s2minutesnumberncr',
			[
				'label' => esc_html__('Text Color', 'tpebl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(3) .rotor-group-heading:before' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'s2daytextmtbg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(3) .rotor-group-heading:before',
            ]
        );
		$this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'s2daytextmdb',
                'label'=>esc_html__('Border','tpebl'),
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(3) .rotor-group-heading:before',
            ]
        );
        $this->add_responsive_control('s2daytextmbrs',
            [
                'label'=>__('Border Radius','tpebl'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(3) .rotor-group-heading:before'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'s2daytextmsd',
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(3) .rotor-group-heading:before',
            ]
        );
		$this->end_controls_tab();
		$this->start_controls_tab('s2_text_seconds',
            [
                'label'=>esc_html__('Second','tpebl')
            ]
        );
		$this->add_control('s2secondnumberncr',
			[
				'label' => esc_html__('Text Color', 'tpebl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(4) .rotor-group-heading:before' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'s2daytextmsbg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(4) .rotor-group-heading:before',
            ]
        );
		$this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'s2daytextsdb',
                'label'=>esc_html__('Border','tpebl'),
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(4) .rotor-group-heading:before',
            ]
        );
        $this->add_responsive_control('s2daytextsbrs',
            [
                'label'=>__('Border Radius','tpebl'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(4) .rotor-group-heading:before'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'s2daytextssd',
                'selector'=>'{{WRAPPER}} .tp-countdown .rotor-group:nth-of-type(4) .rotor-group-heading:before',
            ]
        );
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		/*counter style*/
		$this->start_controls_section('style2counter_styling',
			[
				'label' => esc_html__('Counter', 'tpebl'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'CDType' => ['normal','scarcity'],
					'CDstyle' => 'style-2',			
				],
			]
		);
		$this->add_group_control(Group_Control_Typography::get_type(),
            [
                'name'=>'style2countertypo',
                'label'=>esc_html__('Typography','tpebl'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY
				],
                'selector'=>'{{WRAPPER}} .tp-countdown .flipdown .rotor',
            ]
        );
		$this->end_controls_section();
		/*counter style*/

		$this->start_controls_section('style2dark_styling',
			[
				'label' => esc_html__('Dark Theme', 'tpebl'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'CDType' => ['normal','scarcity'],
					'CDstyle' => 'style-2',
				],
			]
		);
		$this->start_controls_tabs('s2dark_tabs');
		$this->start_controls_tab('s2dark_normal',
			[
				'label'=>esc_html__('Normal','tpebl')
			]
		); 	
		$this->add_control('s2haddingntop',
			[
				'label' => esc_html__( 'Top Options', 'tpebl' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control('s2darktopncr',
			[
				'label' => esc_html__('Top Text Color', 'tpebl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark .rotor,{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark .rotor-top,{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark .rotor-leaf-front' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'s2darktopnbg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark .rotor,{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark .rotor-top,{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark .rotor-leaf-front',
            ]
        );
		$this->add_group_control(Group_Control_Border::get_type(),
			[
				'name'=>'s2bordernb',
				'label'=>esc_html__('Border Top','tpebl'),
				'selector'=>'{{WRAPPER}} .flipdown.flipdown__theme-dark .rotor,{{WRAPPER}} .flipdown.flipdown__theme-dark .rotor-top,{{WRAPPER}} .flipdown.flipdown__theme-dark .rotor-leaf-front',
			]
		);

		$this->add_control('s2haddingnbootom',
			[
				'label' => esc_html__( 'Bottom Options', 'tpebl' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control('s2darkbottomncr',
			[
				'label' => esc_html__('Bottom Text Color', 'tpebl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipdown.flipdown__theme-dark .rotor-bottom, {{WRAPPER}} .flipdown.flipdown__theme-dark .rotor-leaf-rear' => 'color:{{VALUE}};',
				],
			]
		);
		$this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'s2darkbottomnbg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .flipdown.flipdown__theme-dark .rotor-bottom, {{WRAPPER}} .flipdown.flipdown__theme-dark .rotor-leaf-rear',
            ]
        );
		$this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'s2borderbottomnb',
                'label'=>esc_html__('Border Top','tpebl'),
                'selector'=>'{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark .rotor:after',
            ]
        );
		$this->end_controls_tab();
		$this->start_controls_tab('s2dark_hover',
            [
                'label'=>esc_html__('Hover','tpebl')
            ]
        );
		$this->add_control('s2haddihghtop',
			[
				'label' => esc_html__( 'Top Options', 'tpebl' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control('s2darktophcr',
			[
				'label' => esc_html__('Top Text Color', 'tpebl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark:hover .rotor,{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark:hover .rotor-top,{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark:hover .rotor-leaf-front' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(Group_Control_Background::get_type(),
			[
				'name'=>'s2darktophbg',
				'types'=>['classic','gradient'],
				'selector'=>'{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark:hover .rotor,{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark:hover .rotor-top,{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark:hover .rotor-leaf-front',
			]
		);
		$this->add_group_control(Group_Control_Border::get_type(),
			[
				'name'=>'s2darkborderhb',
				'label'=>esc_html__('Border Top','tpebl'),
				'selector'=>'{{WRAPPER}} .flipdown.flipdown__theme-dark:hover .rotor,{{WRAPPER}} .flipdown.flipdown__theme-dark:hover .rotor-top,{{WRAPPER}} .flipdown.flipdown__theme-dark:hover .rotor-leaf-front',
			]
		);

		$this->add_control('s2haddinghbootom',
			[
				'label' => esc_html__( 'Bottom Options', 'tpebl' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control('s2darkbottomhcr',
			[
				'label' => esc_html__('Bottom Text Color', 'tpebl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipdown.flipdown__theme-dark:hover .rotor-bottom, {{WRAPPER}} .flipdown.flipdown__theme-dark:hover .rotor-leaf-rear' => 'color:{{VALUE}};',
				],
			]
		);
		$this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'s2darkbottomhbg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .flipdown.flipdown__theme-dark:hover .rotor-bottom, {{WRAPPER}} .flipdown.flipdown__theme-dark:hover .rotor-leaf-rear',
            ]
        );
		$this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'middlelinehb',
                'label'=>esc_html__('Border','tpebl'),
                'selector'=>'{{WRAPPER}} .tp-countdown .flipdown.flipdown__theme-dark:hover .rotor:after',
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section('style2dot_styling',
			[
				'label' => esc_html__('Dot', 'tpebl'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'CDType' => ['normal','scarcity'],
					'CDstyle' => 'style-2',
				],
			]
		);
		$this->add_group_control(Group_Control_Background::get_type(),
			[
				'name'=>'s2ndotbg',
				'types'=>['classic','gradient'],
				'selector'=>'{{WRAPPER}} .tp-countdown .flipdown .rotor-group:nth-child(n+2):nth-child(-n+3):before,{{WRAPPER}} .tp-countdown .flipdown .rotor-group:nth-child(n+2):nth-child(-n+3):after,{{WRAPPER}}  .tp-countdown.countdown-style-2 .rotor-group:first-child::after,{{WRAPPER}}  .tp-countdown.countdown-style-2 .rotor-group:first-child::before',
			]
		);
		$this->end_controls_section();	
			
		/*Adv tab*/
		$this->start_controls_section(
            'section_plus_extra_adv',
            [
                'label' => esc_html__('Plus Extras', 'tpebl'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );
		$this->end_controls_section();
		$this->start_controls_section('background_styling',
            [
                'label' => esc_html__('Background', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control('bgpad',
			[
				'label'=>__('Padding','tpebl'),
				'type'=>Controls_Manager::DIMENSIONS,
				'size_units'=>['px','%'],
				'selectors'=>[
					'{{WRAPPER}} .tp-countdown'=>'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control('bgmar',
			[
				'label'=>__('Margin','tpebl'),
				'type'=>Controls_Manager::DIMENSIONS,
				'size_units'=>['px','%'],
				'selectors'=>[
					'{{WRAPPER}} .tp-countdown'=>'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('bg_tabs');
        $this->start_controls_tab('bg_normal',
            [
                'label'=>esc_html__('Normal','tpebl')
            ]
        ); 
		$this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'bgnbg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-countdown',
            ]
        );
		$this->add_group_control(Group_Control_Border::get_type(),
            [
                'name'=>'bgnb',
                'label'=>esc_html__('Border','tpebl'),
                'selector'=>'{{WRAPPER}} .tp-countdown',
            ]
        );
        $this->add_responsive_control('bgnbr',
            [
                'label'=>__('Border Radius','tpebl'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-countdown'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'bgnsd',
                'selector'=>'{{WRAPPER}} .tp-countdown',
            ]
        );
		$this->end_controls_tab();
		$this->start_controls_tab('bg_hover',
			[
				'label'=>esc_html__('hover','tpebl')
			]
		); 
		$this->add_group_control(Group_Control_Background::get_type(),
            [
                'name'=>'bghbg',
                'types'=>['classic','gradient'],
                'selector'=>'{{WRAPPER}} .tp-countdown:hover',
            ]
        );
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'=>'bghb',
                'label'=>esc_html__('Border','tpebl'),
                'selector'=>'{{WRAPPER}} .tp-countdown:hover',
				
			]
		);
        $this->add_responsive_control('bghbr',
            [
                'label'=>__('Border Radius','tpebl'),
                'type'=>Controls_Manager::DIMENSIONS,
                'size_units'=>['px','%'],
                'selectors'=>[
                    '{{WRAPPER}} .tp-countdown:hover'=>'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(Group_Control_Box_Shadow::get_type(),
            [
                'name'=>'bghsd',
                'selector'=>'{{WRAPPER}} .tp-countdown:hover',
            ]
        );
		$this->end_controls_tab();
		$this->end_controls_tabs();
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
				'label' => esc_html__( 'Choose Animation Effect', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
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
						'min' => 0,
						'max' => 4000,
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
				'label' => esc_html__( 'Animation Duration', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
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
						'min' => 100,
						'max' => 10000,
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
				'label' => esc_html__( 'Out Animation Effect', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
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
						'min' => 0,
						'max' => 4000,
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
				'label' => esc_html__( 'Out Animation Duration', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
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
						'min' => 100,
						'max' => 10000,
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
		$DaysLabels = !empty($settings['days_labels']) ? true : false;
		$HoursLabels = !empty($settings['hours_labels']) ? true : false;
		$MinutesLabels = !empty($settings['minutes_labels']) ? true : false;
		$SecondsLabels = !empty($settings['seconds_labels']) ? true : false;
		$CDType = !empty($settings['CDType']) ? $settings['CDType'] : 'normal';
		$CDstyle = !empty($settings['CDstyle']) ? $settings['CDstyle'] : 'style-1';

		$data_attr='';
		
		$uid=uniqid('count_down');
		$WidgetId = $this->get_id();

		if (empty($settings['show_labels'])){
			$show_labels=$settings['show_labels'];
		}else{
			$show_labels='yes';
		}

		if (empty($settings['text_days'])){
			$text_days='Days';
		}else{
			$text_days=$settings['text_days'];
		}
		
		if (empty($settings['text_hours'])){
			$text_hours='Hours';
		}else{
			$text_hours=$settings['text_hours'];
		}
		
		if (empty($settings['text_minutes'])){
			$text_minutes='Minutes';
		}else{
			$text_minutes=$settings['text_minutes'];
		}
		
		if (empty($settings['text_seconds'])){
			$text_seconds='Seconds';
		}else{
			$text_seconds=$settings['text_seconds'];
		}

		if(!empty($settings['counting_timer'])){
			$counting_timer=$settings['counting_timer'];
			$counting_timer= date('m/d/Y H:i:s',strtotime($counting_timer) );
		}else{
			$counting_timer='08/31/2019 12:00:00';
		}

		$offset_time=get_option('gmt_offset');
		$offsetTime = wp_timezone_string();
		$now = new \DateTime('NOW', new \DateTimeZone($offsetTime));

		$Styleclass="";
		$CDData=[];
		if($CDType == 'normal') {
			$Styleclass = "countdown-".$CDstyle;

			$CDData = array(
				'widgetid' => $WidgetId,
				'type' => $CDType,
				'style' => $CDstyle,
				'days' => $text_days,
				'hours' => $text_hours,
				'minutes' => $text_minutes,
				'seconds' => $text_seconds,

				'daysenable' => $DaysLabels,
				'hoursenable' => $HoursLabels,
				'minutesenable' => $MinutesLabels,
				'secondsenable' => $SecondsLabels,
			);
		}

		if($CDType == 'normal') {
			$OtherDataa = array(
				'offset' => $offset_time,
				'timer' => $counting_timer,
			);

			$CDData = array_merge($CDData, $OtherDataa);
		}

		$cd_classbased =  isset($settings['cd_classbased']) ? 'yes' : 'no';
		$CDData = htmlspecialchars(json_encode($CDData), ENT_QUOTES, 'UTF-8');
		$output = '';
		$output .= '<div class="tp-countdown tp-widget-'.esc_attr($WidgetId).' '.esc_attr($Styleclass).'" data-basic="'.esc_attr($CDData).'" >';

			$data_attr .=' data-days="'.esc_attr($text_days).'"';
			$data_attr .=' data-hours="'.esc_attr($text_hours).'"';
			$data_attr .=' data-minutes="'.esc_attr($text_minutes).'"';
			$data_attr .=' data-seconds="'.esc_attr($text_seconds).'"';
			
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
			
			$inline_style= (!empty($settings["inline_style"]) && $settings["inline_style"]=='yes') ? 'count-inline-style' : '';

			if( $CDType == 'normal' && $CDstyle == 'style-1' ){	
				$output .= '<ul class="pt_plus_countdown '.esc_attr($uid).' '.esc_attr($inline_style).' '.esc_attr($animated_class).'" '.esc_attr($data_attr).' data-timer="'.esc_attr($counting_timer).'" data-offset="'.esc_attr($offset_time).'" '.esc_attr($animation_attr).'>';
					if(!empty($DaysLabels)){
						$output .= '<li class="count_1">';
							$output .= '<span class="days">00</span>';
							if(!empty($show_labels) && $show_labels == 'yes'){
								$output .= '<h6 class="days_ref">'.esc_attr($text_days).'</h6>';
							} 
						$output .= '</li>';
					} 

					if( !empty($HoursLabels)){ 
						$output .= '<li class="count_2">';
							$output .= '<span class="hours">00</span>';
							if(!empty($show_labels) && $show_labels == 'yes'){ 
								$output .= '<h6 class="hours_ref">'.esc_attr($text_hours).'</h6>';
							}
						$output .= '</li>';
					} 

					if( !empty($MinutesLabels) ){
						$output .= '<li class="count_3">';
							$output .= '<span class="minutes">00</span>';
							if(!empty($show_labels) && $show_labels == 'yes'){
								$output .= '<h6 class="minutes_ref">'.esc_attr($text_minutes).'</h6>';
							} 
						$output .= '</li>';
					}
					
					if( !empty($SecondsLabels) ){ 
						$output .= '<li class="count_4">';
							$output .= '<span class="seconds last">00</span>';
							if(!empty($show_labels) && $show_labels == 'yes'){
								$output .= '<h6 class="seconds_ref">'.esc_attr($text_seconds).'</h6>';
							} 
						$output .= '</li>';
					}
				$output .= '</ul>';
			}
		
		$output .= '</div>';

		echo $output;
	}

    protected function content_template() {
	
    }

}