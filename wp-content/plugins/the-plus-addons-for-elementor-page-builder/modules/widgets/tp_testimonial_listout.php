<?php 
/*
Widget Name: Testimonial Carousel
Description: Different style of testimonial.
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
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class L_ThePlus_Testimonial_ListOut extends Widget_Base {
	
	public $TpDoc = L_THEPLUS_Tpdoc;

	public function get_name() {
		return 'tp-testimonial-listout';
	}

    public function get_title() {
        return esc_html__('Testimonial', 'tpebl');
    }

    public function get_icon() {
        return 'fa fa-users theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-listing');
    }

	public function get_custom_help_url() {
		$DocUrl = $this->TpDoc . "testimonial";

		return esc_url($DocUrl);
	}

    protected function register_controls() {
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content Layout', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'style',
			[
				'label' => esc_html__( 'Style', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => [
					'style-1'  =>  esc_html__( 'Style 1', 'tpebl' ),
					'style-2' =>  esc_html__( 'Style 2', 'tpebl' ),
					'style-3' =>  esc_html__( 'Style 3 (PRO)', 'tpebl' ),
					'style-4' =>  esc_html__( 'Style 4', 'tpebl' ),
				],
			]
		);
		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'carousel',
				'options' => [
					'grid' => esc_html__( 'Grid', 'tpebl' ),
					'masonry' => esc_html__( 'Masonry', 'tpebl' ),
					'carousel' => esc_html__( 'Carousel', 'tpebl' ),
				],
			]
		);
		$this->add_control('how_it_works_grid',
			[
				'label' => wp_kses_post( "<a class='tp-docs-link' href='" . esc_url($this->TpDoc) . "show-testimonials-in-grid-layout-in-elementor/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> Learn How it works  <i class='eicon-help-o'></i> </a>", 'tpebl' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'layout' => 'grid'
				],
			]
		);
		$this->add_control('how_it_works_Masonry',
			[
				'label' => wp_kses_post( "<a class='tp-docs-link' href='" . esc_url($this->TpDoc) . "show-testimonials-in-masonry-grid-layout-in-elementor/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> Learn How it works  <i class='eicon-help-o'></i> </a>", 'tpebl' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'layout' => 'masonry'
				],
			]
		);
		$this->add_control('how_it_works_carousel',
			[
				'label' => wp_kses_post( "<a class='tp-docs-link' href='" . esc_url($this->TpDoc) . "add-a-testimonial-carousel-slider-in-elementor/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> Learn How it works  <i class='eicon-help-o'></i> </a>", 'tpebl' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'layout' => 'carousel'
				],
			]
		);
		$this->add_control(
			'tlContentFrom',
			[
				'label' => esc_html__( 'Select Source', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tlcontent',
				'options' => [
					'tlcontent' => esc_html__( 'Post Type', 'tpebl' ),
					'tlrepeater' => esc_html__( 'Repeater', 'tpebl' ),
				],
			]
		);
		$this->add_control('how_it_works_Post_Type',
			[
				'label' => wp_kses_post( "<a class='tp-docs-link' href='" . esc_url($this->TpDoc) . "add-testimonials-with-custom-post-type-in-elementor/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> Learn How it works  <i class='eicon-help-o'></i> </a>", 'tpebl' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'tlContentFrom' => 'tlcontent'
				],
			]
		);
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
            'testiAuthor',
            [   
            	'label' => esc_html__( 'Testimonial Content', 'tpebl' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => '',
				'placeholder' => esc_html__( 'Enter Testimonial Content', 'tpebl' ),
				'dynamic' => ['active'   => true,],
            ]
        );
		$repeater->add_control(
			'testiTitle',
			[
				'label' => esc_html__( 'Testimonial Title', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => ['active' => true,],
				'default' => '',
				'placeholder' => esc_html__( 'Enter Testimonial Title', 'tpebl' ),
			]
		);
		$repeater->add_control(
			'testiLabel',
			[
				'label' => esc_html__( 'Author Name', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => ['active' => true,],
				'default' => '',
				'placeholder' => esc_html__( 'Enter Author Name', 'tpebl' ),
			]
		);
		$repeater->add_control(
			'testiDesign',
			[
				'label' => esc_html__( 'Author Designation', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => ['active' => true,],
				'default' => '',
				'placeholder' => esc_html__( 'Enter Designation', 'tpebl' ),
			]
		);
		$repeater->add_control(
			'testiImage',[
				'label' => esc_html__( 'Author Image', 'tpebl' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => ['active'   => true,],
			]
		);
		$repeater->add_control(
			'testiLogo',[
				'label' => esc_html__( 'Company Logo', 'tpebl' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => ['active'   => true,],
			]
		);
		$repeater->add_control(
			'testiLogoNote',
			[				
				'type' => Controls_Manager::RAW_HTML,
				'raw' => 'Note : This is just for style 4.',
				'content_classes' => 'tp-widget-description',
			]
		);
		$this->add_control(
			'testiAllList',
			[
				'label' => esc_html__( 'Manage Testimonials', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),			
				'default' => [
					[	
						'testiAuthor' => 'I have been using the software from XYZ Business for a few weeks now and it has exceeded my expectations. It is user-friendly, efficient, and the customer support team is always available to help. Highly recommend this software!',	
						'testiTitle' => 'Outstanding Support',				
						'testiLabel' => 'Emily Thompson',
						'testiDesign' => 'CEO of CodeCraft Inc.',
					],
					[	
						'testiAuthor' => 'I have been using the software from XYZ Business for a few weeks now and it has exceeded my expectations. It is user-friendly, efficient, and the customer support team is always available to help. Highly recommend this software!',
						'testiTitle' => 'Improved Productivity',					
						'testiLabel' => 'Benjamin Reed',
						'testiDesign' => 'Founder of X Community',
					],
					[	
						'testiAuthor' => 'I have been using the software from XYZ Business for a few weeks now and it has exceeded my expectations. It is user-friendly, efficient, and the customer support team is always available to help. Highly recommend this software!',
						'testiTitle' => 'Highly recommend',					
						'testiLabel' => 'Rachel Johnson',
						'testiDesign' => 'COO of AppFinity Solutions',
					],
				],
				'title_field' => '{{{ testiLabel }}}',
				'condition' => [
					'tlContentFrom' => 'tlrepeater',
				],
			]
		);
		$this->add_control(
			'style_pro_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'style!' => ['style-1','style-2','style-4'],
				],
			]
		);
		$this->end_controls_section();
		/*columns*/
		$this->start_controls_section(
			'columns_section',
			[
				'label' => esc_html__( 'Columns Manage', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'layout!' => ['carousel']
				],
			]
		);
		$this->add_control(
			'desktop_column',
			[
				'label' => esc_html__( 'Desktop Column', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => l_theplus_get_columns_list(),
				'condition' => [
					'layout!' => ['carousel']
				],
			]
		);
		$this->add_control(
			'tablet_column',
			[
				'label' => esc_html__( 'Tablet Column', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => '4',
				'options' => l_theplus_get_columns_list(),
				'condition' => [
					'layout!' => ['carousel']
				],
			]
		);
		$this->add_control(
			'mobile_column',
			[
				'label' => esc_html__( 'Mobile Column', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => '6',
				'options' => l_theplus_get_columns_list(),
				'condition' => [
					'layout!' => ['carousel']
				],
			]
		);
		$this->add_responsive_control(
			'columns_gap',
			[
				'label' => esc_html__( 'Columns Gap/Space Between', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' =>[
					'top' => "15",
					'right' => "15",
					'bottom' => "15",
					'left' => "15",				
				],
				'separator' => 'before',
				'condition' => [
					'layout!' => ['carousel']
				],
				'selectors' => [
					'{{WRAPPER}} .testimonial-list .post-inner-loop .grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		/*columns*/
		$this->start_controls_section(
			'content_source_section',
			[
				'label' => esc_html__( 'Content Source', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'tlContentFrom!' => 'tlrepeater',
				],
			]
		);
		$this->add_control(
			'post_category',
			[
				'type' => Controls_Manager::SELECT2,
				'label'      => esc_html__( 'Select Category', 'tpebl' ),
				'default'    => '',
				'label_block' => true,
				'multiple'   => true,
				'options' => l_theplus_get_testimonial_categories(),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'display_posts',
			[
				'label' => esc_html__( 'Maximum Posts Display', 'tpebl' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 200,
				'step' => 1,
				'default' => 8,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'post_offset',
			[
				'label' => esc_html__( 'Offset Posts', 'tpebl' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'default' => '',
				'description' => esc_html__('Hide posts from the beginning of listing.','tpebl'),
			]
		);
		$this->add_control(
			'post_order_by',
			[
				'label' => esc_html__( 'Order By', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => l_theplus_orderby_arr(),
			]
		);
		$this->add_control(
			'post_order',
			[
				'label' => esc_html__( 'Order', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => l_theplus_order_arr(),
			]
		);
		
		$this->end_controls_section();
		$this->start_controls_section(
			'content_extra_options_section',
			[
				'label' => esc_html__( 'Extra Option', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'post_title_tag',
			[
				'label' => esc_html__( 'Title Tag', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => l_theplus_get_tags_options(),
			]
		);
		$this->add_control(
			'display_thumbnail',
			[
				'label' => esc_html__( 'Display Image Size', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
				'default' => 'no',
			]
		);
		$this->add_control(
			'display_thumbnail_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'display_thumbnail' => [ 'yes' ],
				],
			]
		);
		$this->add_control(
			'caroByheight',
			[
				'label' => esc_html__( 'Content Limit By', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'tpebl' ),
					'height' => esc_html__( 'Height', 'tpebl' ),
					'text-limit' => esc_html__( 'Text Limit', 'tpebl' ),
				],
				'condition' => [
					'tlContentFrom' => ['tlrepeater'],
					'layout' => ['carousel']
				],
			]
		);
		$this->add_responsive_control('contentHei',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => wp_kses_post( "Content Height(px) <a class='tp-docs-link' href='" . esc_url($this->TpDoc) . "set-elementor-testimonial-carousel-height/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> <i class='eicon-help-o'></i> </a>", 'tpebl' ),
				'size_units' => [ 'px'],
				'default' => [
					'unit' => 'px',
					'size' => '',
				],
				'range' => [
					'px' => [
						'min'	=> 1,
						'max'	=> 500,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list .testimonial-list-content .entry-content' => 'height: {{SIZE}}{{UNIT}};overflow-y: auto; padding-right: 5px;',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'grid'],
							]
						],
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'carousel'],
								['name' => 'caroByheight', 'operator' => '===', 'value' => 'height']
							]
						],
					]
				],
            ]
        );
		$this->add_responsive_control(
            'titleHei',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Title Height(px)', 'tpebl'),
				'size_units' => [ 'px'],
				'default' => [
					'unit' => 'px',
					'size' => '',
				],
				'range' => [
					'px' => [
						'min'	=> 1,
						'max'	=> 500,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list .testimonial-list-content .testimonial-author-title' => 'height: {{SIZE}}{{UNIT}};overflow-y: auto; padding-right: 5px;',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'grid'],
							]
						],
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'carousel'],
								['name' => 'caroByheight', 'operator' => '===', 'value' => 'height']
							]
						],
					]
				],
            ]
        );
		$this->add_control(
			'cntscrollOn',
			[
				'label' => esc_html__( 'Content Scroll', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'on-hover',
				'options' => [
					'on-hover' => esc_html__( 'On Hover', 'tpebl' ),
					'visible' => esc_html__( 'Visible', 'tpebl' ),
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'grid'],
							]
						],
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'carousel'],
								['name' => 'caroByheight', 'operator' => '===', 'value' => 'height']
							]
						],
					]
				],
			]
		);
		$this->add_control('descByLimit',
			[
				'label' => wp_kses_post( "Excerpt Limit <a class='tp-docs-link' href='" . esc_url($this->TpDoc) . "limit-elementor-testimonial-carousel-by-text/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> <i class='eicon-help-o'></i> </a>", 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'tpebl' ),
					'letters' => esc_html__( 'By Letters', 'tpebl' ),
					'words' => esc_html__( 'By Words', 'tpebl' ),
				],
				'condition' => [
					'tlContentFrom' => ['tlrepeater'],
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'masonry'],
							]
						],
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'carousel'],
								['name' => 'caroByheight', 'operator' => '===', 'value' => 'text-limit']
							]
						],
					]
				],
			]
		);
		$this->add_control(
			'descLimit',
			[
				'label' => esc_html__( 'Maximum Letters/Words', 'tpebl' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1000,
				'step' => 1,
				'default' => 30,
				'condition' => [
					'tlContentFrom' => ['tlrepeater'],
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'masonry'],
								['name' => 'descByLimit', 'operator' => '!==', 'value' => 'default'],
							]
						],
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'carousel'],
								['name' => 'caroByheight', 'operator' => '===', 'value' => 'text-limit'],
								['name' => 'descByLimit', 'operator' => '!==', 'value' => 'default'],
							]
						],
					]
				],
			]
		);	
		$this->add_control(
			'titleByLimit',
			[
				'label' => esc_html__( 'Title Limit', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'tpebl' ),
					'letters' => esc_html__( 'By Letters', 'tpebl' ),
					'words' => esc_html__( 'By Words', 'tpebl' ),
				],
				'condition' => [
					'layout' => ['carousel'],
					'tlContentFrom' => ['tlrepeater'],
					'caroByheight' => ['text-limit']
				],
			]
		);
		$this->add_control(
			'titleLimit',
			[
				'label' => esc_html__( 'Maximum Letters/Words', 'tpebl' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1000,
				'step' => 1,
				'default' => 30,
				'condition' => [
					'layout' => ['carousel'],
					'tlContentFrom' => ['tlrepeater'],
					'caroByheight' => ['text-limit'],
					'titleByLimit!' => 'default',
				],
			]
		);	
		$this->add_control(
			'redmorTxt',
			[
				'label' => esc_html__( 'Read More', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => ['active' => true,],
				'separator' => 'before',
				'default' => esc_html__( 'Read More', 'tpebl' ),
				'placeholder' => esc_html__( 'Enter Read More', 'tpebl' ),
				'condition' => [
					'tlContentFrom' => ['tlrepeater'],
					'layout' => ['masonry','carousel'],
					'caroByheight' => ['text-limit'],
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
						'terms' => [
								['name' => 'descByLimit', 'operator' => '!=', 'value' => 'default']
							]
						],
						[
						'terms' => [
								['name' => 'titleByLimit', 'operator' => '!=', 'value' => 'default']
							]
						],
					]
				],
			]
		);
		$this->add_control(
			'redlesTxt',
			[
				'label' => esc_html__( 'Read Less', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => ['active' => true,],
				'default' => esc_html__( 'Read Less', 'tpebl' ),
				'placeholder' => esc_html__( 'Enter Read Less', 'tpebl' ),
				'condition' => [
					'tlContentFrom' => ['tlrepeater'],
					'layout' => ['masonry','carousel'],
					'caroByheight' => ['text-limit'],
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
						'terms' => [
								['name' => 'descByLimit', 'operator' => '!=', 'value' => 'default']
							]
						],
						[
						'terms' => [
								['name' => 'titleByLimit', 'operator' => '!=', 'value' => 'default']
							]
						],
					]
				],
			]
		);
		$this->end_controls_section();
		/*Post Title*/
		$this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'tpebl' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY
				],
				'selector' => '{{WRAPPER}} .testimonial-list .post-content-image .post-title,{{WRAPPER}} .testimonial-list.testimonial-style-4 .post-title',
			]
		);
		$this->start_controls_tabs( 'tabs_title_style' );
		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),				
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list .post-content-image .post-title,{{WRAPPER}} .testimonial-list.testimonial-style-4 .post-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_title_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_control(
			'title_hover_color',
			[
				'label' => esc_html__( 'Title Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list .testimonial-list-content:hover .post-title,{{WRAPPER}} .testimonial-list.testimonial-style-4 .testimonial-list-content:hover .post-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Post Title*/
		/*Post Extra options*/
		$this->start_controls_section(
            'section_extra_title_style',
            [
                'label' => esc_html__('Extra Title', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'style'    => ['style-1','style-2','style-4'],
				],
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'extra_title_typography',
				'label' => esc_html__( 'Typography', 'tpebl' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY
				],
				'selector' => '{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-list-content .testimonial-author-title,{{WRAPPER}} .testimonial-list.testimonial-style-2 .testimonial-list-content .testimonial-author-title,{{WRAPPER}} .testimonial-list.testimonial-style-4 .testimonial-author-title',				
			]
		);
		$this->start_controls_tabs( 'tabs_extra_title_style' );
		
		$this->start_controls_tab(
			'tab_extra_title_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
			]
		);
		$this->add_control(
			'extra_title_color',
			[
				'label' => esc_html__( 'Extra Title Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-list-content .testimonial-author-title,{{WRAPPER}} .testimonial-list.testimonial-style-2 .testimonial-list-content .testimonial-author-title,{{WRAPPER}} .testimonial-list.testimonial-style-4 .testimonial-author-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_extra_title_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_control(
			'extra_title_hover_color',
			[
				'label' => esc_html__( 'Extra Title Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-list-content:hover .testimonial-author-title,{{WRAPPER}} .testimonial-list.testimonial-style-2 .testimonial-list-content:hover .testimonial-author-title,{{WRAPPER}} .testimonial-list.testimonial-style-4 .testimonial-list-content:hover .testimonial-author-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
            'section_designation_style',
            [
                'label' => esc_html__('Designation', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'style'    => ['style-1','style-2','style-4'],
				],
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'designation_typography',
				'label' => esc_html__( 'Typography', 'tpebl' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY
				],
				'selector' => '{{WRAPPER}} .testimonial-list.testimonial-style-1 .post-designation,{{WRAPPER}} .testimonial-list.testimonial-style-2 .post-designation,{{WRAPPER}} .testimonial-list.testimonial-style-4 .post-designation',
			]
		);
		$this->start_controls_tabs( 'tabs_designation_style' );
		$this->start_controls_tab(
			'tab_designation_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),				
			]
		);
		$this->add_control(
			'designation_color',
			[
				'label' => esc_html__( 'Designation Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list.testimonial-style-1 .post-designation,{{WRAPPER}} .testimonial-list.testimonial-style-2 .post-designation,{{WRAPPER}} .testimonial-list.testimonial-style-4 .post-designation' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_designation_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_control(
			'designation_hover_color',
			[
				'label' => esc_html__( 'Designation Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-list-content:hover .post-designation,{{WRAPPER}} .testimonial-list.testimonial-style-2 .testimonial-list-content:hover .post-designation,{{WRAPPER}} .testimonial-list.testimonial-style-4 .testimonial-list-content:hover .post-designation' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Post Extra options*/
		/*Post Excerpt*/
		$this->start_controls_section(
            'section_excerpt_style',
            [
                'label' => esc_html__('Excerpt/Content', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'style'    => ['style-1','style-2','style-4'],
				],
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'label' => esc_html__( 'Typography', 'tpebl' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY
				],
				'selector' => '{{WRAPPER}} .testimonial-list .entry-content',
			]
		);
		$this->start_controls_tabs( 'tabs_excerpt_style' );
		$this->start_controls_tab(
			'tab_excerpt_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),				
			]
		);
		$this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__( 'Content Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list .entry-content,{{WRAPPER}} .testimonial-list .entry-content p' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_excerpt_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_control(
			'excerpt_hover_color',
			[
				'label' => esc_html__( 'Content Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list .testimonial-list-content:hover .entry-content,{{WRAPPER}} .testimonial-list .testimonial-list-content:hover .entry-content p' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Post Excerpt*/
		/*Content Background*/
		$this->start_controls_section(
            'section_content_bg_style',
            [
                'label' => esc_html__('Content Background', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'style'    => ['style-1','style-2','style-4'],
				],
            ]
        );
		$this->add_responsive_control(
			'content_inner_padding',
			[
				'label'      => esc_html__( 'Inner Padding', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-content-text,{{WRAPPER}} .testimonial-list.testimonial-style-2 .testimonial-list-content,{{WRAPPER}} .testimonial-list.testimonial-style-4 .testimonial-list-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'content_bg_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-content-text,{{WRAPPER}} .testimonial-list.testimonial-style-2 .testimonial-list-content,{{WRAPPER}} .testimonial-list.testimonial-style-4 .testimonial-list-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .testimonial-list .testimonial-list-content'
			]
		);
		$this->add_responsive_control(
			'box_bg_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-list .testimonial-list-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'tabs_content_bg_style' );
		$this->start_controls_tab(
			'tab_content_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),				
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'contnet_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-content-text,{{WRAPPER}} .testimonial-list.testimonial-style-2 .testimonial-list-content,{{WRAPPER}} .testimonial-list.testimonial-style-4 .testimonial-list-content',
			]
		);
		$this->add_control(
			'down_arrow_color',
			[
				'label' => esc_html__( 'Down Arrow Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-content-text:after' => 'border-top-color: {{VALUE}}',
				],
				'condition'   => [
					'style'    => 'style-1',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_content_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_hover_background',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-list-content:hover .testimonial-content-text,{{WRAPPER}} .testimonial-list.testimonial-style-2 .testimonial-list-content:hover,{{WRAPPER}} .testimonial-list.testimonial-style-4 .testimonial-list-content:hover',
			]
		);
		$this->add_control(
			'down_arrow_hover_color',
			[
				'label' => esc_html__( 'Down Arrow Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-list-content:hover .testimonial-content-text:after' => 'border-top-color: {{VALUE}}',
				],
				'condition'   => [
					'style'    => 'style-1',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();		
		$this->end_controls_section();
		/*Content Background*/
		/*Read More/Less*/
		$this->start_controls_section(
			'section_readML_style',
			[
				'label' => esc_html__('Read More/Less', 'tpebl'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tlContentFrom' => ['tlrepeater'],
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'masonry'],
								['name' => 'descByLimit', 'operator' => '!==', 'value' => 'default']
							]
						],
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'carousel'],
								['name' => 'titleByLimit', 'operator' => '!==', 'value' => 'default']
							]
						],
					]
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readTypo',
				'label' => esc_html__( 'Typography', 'tpebl' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY
				],
				'selector' => '{{WRAPPER}} .testimonial-list .testimonial-content-text .entry-content a.testi-readbtn,{{WRAPPER}} .testimonial-list .testimonial-content-text .entry-content a.testi-readbtn',
			]
		);
		$this->start_controls_tabs( 'tabs_readML_style' );
		$this->start_controls_tab(
			'tab_readML_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),				
			]
		);
		$this->add_control(
			'readColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list .testimonial-list-content .entry-content a.testi-readbtn,{{WRAPPER}} .testimonial-list .testimonial-list-content .entry-content a.testi-readbtn' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_readML_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_control(
			'readmhvrColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list .testimonial-list-content:hover .entry-content a.testi-readbtn,{{WRAPPER}} .testimonial-list .testimonial-list-content:hover .entry-content a.testi-readbtn' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Read More/Less*/
		/*Scroll Bar*/
		$this->start_controls_section(
			'scroll_testi_section',
			[
				'label' => esc_html__( 'Scroll Bar', 'tpebl' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'grid'],
							]
						],
						[
						'terms' => [
								['name' => 'layout', 'operator' => '===', 'value' => 'carousel'],
								['name' => 'caroByheight', 'operator' => '===', 'value' => 'height']
							]
						],
					]
				],
			]
		);
		$this->start_controls_tabs( 'scroll_Tl_style' );
		$this->start_controls_tab(
			'scrollTl_Bar',
			[
				'label' => esc_html__( 'Scrollbar', 'tpebl' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'  => 'tesSclBg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .testimonial-list-content .entry-content::-webkit-scrollbar,{{WRAPPER}} .testimonial-list-content .testimonial-author-title::-webkit-scrollbar',
			]
		);
		$this->add_responsive_control(
			'tesSclWidth',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .testimonial-list-content .entry-content::-webkit-scrollbar,{{WRAPPER}} .testimonial-list-content .testimonial-author-title::-webkit-scrollbar' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'scrollTl_Tmb',
			[
				'label' => esc_html__( 'Thumb', 'tpebl' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tesThumbBg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .testimonial-list-content .entry-content::-webkit-scrollbar-thumb,{{WRAPPER}} .testimonial-list-content .testimonial-author-title::-webkit-scrollbar-thumb',
			]
		);
		$this->add_responsive_control(
			'tesThumbBrs',
			[
				'label' => esc_html__( 'Border Radius', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .testimonial-list-content .entry-content::-webkit-scrollbar-thumb,{{WRAPPER}} .testimonial-list-content .testimonial-author-title::-webkit-scrollbar-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tesThumbBsw',
				'selector' => '{{WRAPPER}} .testimonial-list-content .entry-content::-webkit-scrollbar-thumb,{{WRAPPER}} .testimonial-list-content .testimonial-author-title::-webkit-scrollbar-thumb',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'scrollTl_Trk',
			[
				'label' => esc_html__( 'Track', 'tpebl' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tesTrackBg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .testimonial-list-content .entry-content::-webkit-scrollbar-track,{{WRAPPER}} .testimonial-list-content .testimonial-author-title::-webkit-scrollbar-track',
			]
		);
		$this->add_responsive_control(
			'tesTrackBRs',
			[
				'label' => esc_html__( 'Border Radius', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .testimonial-list-content .entry-content::-webkit-scrollbar-track,{{WRAPPER}} .testimonial-list-content .testimonial-author-title::-webkit-scrollbar-track' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tesTrackBsw',
				'selector' => '{{WRAPPER}} .testimonial-list-content .entry-content::-webkit-scrollbar-track,{{WRAPPER}} .testimonial-list-content .testimonial-author-title::-webkit-scrollbar-track',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Scroll Bar*/
		/*Post Featured Image*/
		$this->start_controls_section(
            'section_post_image_style',
            [
                'label' => esc_html__('Featured Image', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'style'    => ['style-1','style-2','style-4'],
				],
            ]
        );
		
		$this->add_responsive_control(
			'featured_image_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .testimonial-list.testimonial-style-1 .testimonial-featured-image img,{{WRAPPER}} .testimonial-list.testimonial-style-2 .testimonial-featured-image img,{{WRAPPER}} .testimonial-list.testimonial-style-4 .testimonial-featured-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		
		/*carousel option*/
		$this->start_controls_section(
            'section_carousel_options_styling',
            [
                'label' => esc_html__('Carousel Options', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => ['carousel']
				],
            ]
        );
		$this->add_control(
			'slider_direction',
			[
				'label'   => esc_html__( 'Slider Mode', 'tpebl' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal'  => esc_html__( 'Horizontal', 'tpebl' ),
					'vertical' => esc_html__( 'Vertical (PRO)', 'tpebl' ),
				],
			]
		);		
		$this->add_control(
            'slide_speed',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Slide Speed', 'tpebl'),
				'size_units' => '',
				'range' => [
					'' => [
						'min' => 0,
						'max' => 10000,
						'step' => 100,
					],
				],
				'default' => [
					'unit' => '',
					'size' => 1500,
				],
            ]
        );
		
		$this->start_controls_tabs( 'tabs_carousel_style' );
		$this->start_controls_tab(
			'tab_carousel_desktop',
			[
				'label' => esc_html__( 'Desktop', 'tpebl' ),
			]
		);
		$this->add_control(
			'slider_desktop_column',
			[
				'label'   => esc_html__( 'Desktop Columns', 'tpebl' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1'  => esc_html__( 'Column 1', 'tpebl' ),
				],
			]
		);
		$this->add_control(
			'steps_slide',
			[
				'label'   => esc_html__( 'Next Previous', 'tpebl' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'description' => esc_html__( 'Select option of column scroll on previous or next in carousel.','tpebl' ),
				'options' => [
					'1'  => esc_html__( 'One Column', 'tpebl' ),
					'2' => esc_html__( 'All Visible Columns (PRO)', 'tpebl' ),
				],
				'separator' => 'after',
			]
		);
		$this->add_responsive_control(
			'slider_padding',
			[
				'label' => esc_html__( 'Slide Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'px' => [
					'top' => '',
					'right' => '10',
					'bottom' => '',
					'left' => '10',
					'isLinked' => true,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .list-carousel-slick .slick-initialized .slick-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'slider_draggable',
			[
				'label'   => esc_html__( 'Draggable', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'tpebl' ),
				'label_off' => esc_html__( 'Off', 'tpebl' ),				
				'default' => 'yes',
			]
		);
		$this->add_control(
			'multi_drag',
			[
				'label'   => esc_html__( 'Multi Drag', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),				
				'default' => 'no',
				'condition' => [
					'slider_draggable' => 'yes',
				],
			]
		);
		$this->add_control(
			'multi_drag_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'multi_drag' => [ 'yes' ],
				],
			]
		);
		$this->add_control(
			'slider_infinite',
			[
				'label'   => esc_html__( 'Infinite Mode', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'tpebl' ),
				'label_off' => esc_html__( 'Off', 'tpebl' ),				
				'default' => 'yes',
			]
		);
		$this->add_control(
			'slider_pause_hover',
			[
				'label'   => esc_html__( 'Pause On Hover', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'tpebl' ),
				'label_off' => esc_html__( 'Off', 'tpebl' ),				
				'default' => 'no',
			]
		);
		$this->add_control(
			'slider_pause_hover_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'slider_pause_hover' => [ 'yes' ],
				],
			]
		);
		$this->add_control(
			'slider_adaptive_height',
			[
				'label'   => esc_html__( 'Adaptive Height', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'tpebl' ),
				'label_off' => esc_html__( 'Off', 'tpebl' ),				
				'default' => 'no',
			]
		);
		$this->add_control(
			'slider_animation',
			[
				'label'   => esc_html__( 'Animation Type', 'tpebl' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ease',
				'options' => [
					'ease' => esc_html__( 'With Hold (Pro)', 'tpebl' ),
					'linear' => esc_html__( 'Continuous (Pro)', 'tpebl' ),
				],
			]
		);
		$this->add_control(
			'slider_autoplay',
			[
				'label'   => esc_html__( 'Autoplay', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'tpebl' ),
				'label_off' => esc_html__( 'Off', 'tpebl' ),				
				'default' => 'yes',
			]
		);
		$this->add_control(
            'autoplay_speed',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Autoplay Speed', 'tpebl'),
				'size_units' => '',
				'range' => [
					'' => [
						'min' => 500,
						'max' => 10000,
						'step' => 200,
					],
				],
				'default' => [
					'unit' => '',
					'size' => 3000,
				],
				'condition' => [
					'slider_autoplay' => 'yes',
				],
            ]
        );
		
		$this->add_control(
			'slider_dots',
			[
				'label'   => esc_html__( 'Show Dots', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'tpebl' ),
				'label_off' => esc_html__( 'Off', 'tpebl' ),				
				'default' => 'yes',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'slider_dots_style',
			[
				'label'   => esc_html__( 'Dots Style', 'tpebl' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => [
					'style-1' => esc_html__( 'Style 1', 'tpebl' ),
					'style-2' => esc_html__( 'Style 2 (PRO)', 'tpebl' ),
					'style-3' => esc_html__( 'Style 3 (PRO)', 'tpebl' ),
					'style-4' => esc_html__( 'Style 4 (PRO)', 'tpebl' ),
					'style-5' => esc_html__( 'Style 5 (PRO)', 'tpebl' ),
					'style-6' => esc_html__( 'Style 6 (PRO)', 'tpebl' ),
					'style-7' => esc_html__( 'Style 7 (PRO)', 'tpebl' ),
				],
				'condition'    => [
					'slider_dots' => ['yes'],
				],
			]
		);
		$this->add_control(
			'dots_border_color',
			[
				'label' => esc_html__( 'Dots Border Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#252525',
				'selectors' => [
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-1 li button' => '-webkit-box-shadow:inset 0 0 0 8px {{VALUE}};-moz-box-shadow: inset 0 0 0 8px {{VALUE}};box-shadow: inset 0 0 0 8px {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-1 li.slick-active button' => '-webkit-box-shadow:inset 0 0 0 1px {{VALUE}};-moz-box-shadow: inset 0 0 0 1px {{VALUE}};box-shadow: inset 0 0 0 1px {{VALUE}};',
					'{{WRAPPER}} .list-carousel-slick .slick-dots.style-1 li button:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'slider_dots_style' => ['style-1','style-2','style-3','style-5'],
					'slider_dots' => 'yes',
				],
			]
		);
		$this->add_control(
			'slider_arrows',
			[
				'label'   => esc_html__( 'Show Arrows', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'tpebl' ),
				'label_off' => esc_html__( 'Off', 'tpebl' ),				
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'slider_arrows_style',
			[
				'label'   => esc_html__( 'Arrows Style', 'tpebl' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-2',
				'options' => [
					'style-1' => esc_html__( 'Style 1 (PRO)', 'tpebl' ),
					'style-2' => esc_html__( 'Style 2', 'tpebl' ),
					'style-3' => esc_html__( 'Style 3 (PRO)', 'tpebl' ),
					'style-4' => esc_html__( 'Style 4 (PRO)', 'tpebl' ),
					'style-5' => esc_html__( 'Style 5 (PRO)', 'tpebl' ),
					'style-6' => esc_html__( 'Style 6 (PRO)', 'tpebl' ),
				],
				'condition'    => [
					'slider_arrows' => ['yes'],
				],
			]
		);
		$this->add_control(
			'arrow_icon_color',
			[
				'label' => esc_html__( 'Arrow Icon Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [										
					'{{WRAPPER}} .list-carousel-slick .slick-prev.style-2 .icon-wrap:before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-2 .icon-wrap:after,{{WRAPPER}} .list-carousel-slick .slick-next.style-2 .icon-wrap:before,{{WRAPPER}} .list-carousel-slick .slick-next.style-2 .icon-wrap:after' => 'background: {{VALUE}};',
				],
				'condition' => [
					'slider_arrows_style' => ['style-2'],
					'slider_arrows' => 'yes',
				],
			]
		);
		$this->add_control(
			'arrow_hover_bg_color',
			[
				'label' => esc_html__( 'Arrow Hover Background Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .list-carousel-slick .slick-prev.style-2:hover::before,{{WRAPPER}} .list-carousel-slick .slick-next.style-2:hover::before' => 'background: {{VALUE}};',
				],
				'condition' => [
					'slider_arrows_style' => ['style-2'],
					'slider_arrows' => 'yes',
				],
			]
		);
		$this->add_control(
			'arrow_hover_icon_color',
			[
				'label' => esc_html__( 'Arrow Hover Icon Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c44d48',
				'selectors' => [
					'{{WRAPPER}} .list-carousel-slick .slick-prev.style-2:hover .icon-wrap::before,{{WRAPPER}} .list-carousel-slick .slick-prev.style-2:hover .icon-wrap::after,{{WRAPPER}} .list-carousel-slick .slick-next.style-2:hover .icon-wrap::before,{{WRAPPER}} .list-carousel-slick .slick-next.style-2:hover .icon-wrap::after' => 'background: {{VALUE}};',
				],
				'condition' => [
					'slider_arrows_style' => ['style-2'],
					'slider_arrows' => 'yes',
				],
			]
		);
		$this->add_control('arrow_y_space',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Position Y', 'tpebl'),
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 500,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-nav' => 'top: {{SIZE}}{{UNIT}};',					
				],
				'condition' => [
					'slider_arrows' => 'yes',
				],
            ]
        );
		$this->add_control(
			'slider_center_mode',
			[
				'label'   => esc_html__( 'Center Mode', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'tpebl' ),
				'label_off' => esc_html__( 'Off', 'tpebl' ),				
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'slider_center_mode_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'slider_center_mode' => [ 'yes' ],
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_carousel_tablet',
			[
				'label' => esc_html__( 'Tablet', 'tpebl' ),
			]
		);
		$this->add_control(
			'tab_carousel_tablet_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_carousel_mobile',
			[
				'label' => esc_html__( 'Mobile', 'tpebl' ),
			]
		);
		$this->add_control(
			'tab_carousel_mobile_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*carousel option*/
		
		/*Extra options*/
		$this->start_controls_section(
            'section_extra_options_styling',
            [
                'label' => esc_html__('Extra Options', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'messy_column',
			[
				'label' => esc_html__( 'Messy Columns', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'tpebl' ),
				'label_off' => esc_html__( 'Off', 'tpebl' ),				
				'default' => 'no',
			]
		);
		$this->add_control(
			'messy_column_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'messy_column' => [ 'yes' ],
				],
			]
		);
		$this->end_controls_section();
		/*Extra options*/
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
				'condition'    => [
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

		include L_THEPLUS_PATH. 'modules/widgets/theplus-needhelp.php';
	}

	protected function render() {
        $settings = $this->get_settings_for_display();
		$query = $this->get_query_args();
		$post_name=l_theplus_testimonial_post_name();
		$taxonomy_name=l_theplus_testimonial_post_category();
		
		$style=$settings["style"];
		$layout = !empty($settings['layout']) ? $settings['layout'] : 'carousel';
		$post_title_tag=$settings["post_title_tag"];
		$post_category=$settings['post_category'];
		$tlContentFrom = !empty($settings['tlContentFrom']) ? $settings['tlContentFrom'] : 'tlcontent';
		$testiAllList = !empty($settings['testiAllList']) ? $settings['testiAllList'] : [];

		$content_alignment_4='content-left';
		
		$descByLimit = !empty($settings['descByLimit']) ? $settings['descByLimit'] : 'default';
		$descLimit = !empty($settings['descLimit']) ? $settings['descLimit'] : 30 ;
		$cntscrollOn = !empty($settings['cntscrollOn']) ? $settings['cntscrollOn'] : 'on-hover';
		$caroByheight = !empty($settings['caroByheight']) ? $settings['caroByheight'] : '';

		$titleByLimit = !empty($settings['titleByLimit']) ? $settings['titleByLimit'] : 'default';
		$titleLimit = !empty($settings['titleLimit']) ? $settings['titleLimit'] : 30 ;

		$redmorTxt = !empty($settings['redmorTxt']) ? $settings['redmorTxt'] : '';
		$redlesTxt = !empty($settings['redlesTxt']) ? $settings['redlesTxt'] : '';
		
		//animation load
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

		//columns
		$desktop_class=$tablet_class=$mobile_class='';
		if($layout!='carousel'){
			$desktop_class='tp-col-lg-'.esc_attr($settings['desktop_column']);
			$tablet_class='tp-col-md-'.esc_attr($settings['tablet_column']);
			$mobile_class='tp-col-sm-'.esc_attr($settings['mobile_column']);
			$mobile_class .=' tp-col-'.esc_attr($settings['mobile_column']);
		}

		//layout
		$layout_attr=$data_class='';
		if($layout!=''){			
			if($layout!='grid'){
				$data_class .=l_theplus_get_layout_list_class($layout);
				$layout_attr .=l_theplus_get_layout_list_attr($layout);
			}else{
				$data_class .=' list-isotope';
			}
		}else{
				$data_class .=' list-isotope';
		}

		$data_class='';
		if($layout == 'carousel'){
			$data_class .=' list-carousel-slick ';
		}
		$data_class .=' testimonial-'.$style;


		$readAttr = [];
		$attr = '';
		if($layout == 'masonry' || ( $layout == 'carousel' && $caroByheight == 'text-limit' )){
			
			$readAttr['readMore'] = $redmorTxt;
			$readAttr['readLess'] = $redlesTxt;
			
			$readAttr = htmlspecialchars(json_encode($readAttr), ENT_QUOTES, 'UTF-8');

			$attr = 'data-readData = \'' .$readAttr. '\'';
		}

		
		$output=$data_attr='';
				
		$i=1;
		$uid=uniqid("post");
		
		$data_attr .=' data-id="'.esc_attr($uid).'"';
		$data_attr .=' data-style="'.esc_attr($style).'"';
		if($layout=='carousel'){
		   $data_attr .=$this->get_carousel_options();
		}

		if(!empty($tlContentFrom) && $tlContentFrom == 'tlrepeater'){
			if(!empty($testiAllList)) {
				$index=1;
				if($style=='style-1' || $style=='style-2' || $style=='style-4'){
					$output .= '<div id="theplus-testimonial-post-list" class="testimonial-list '.esc_attr($uid).' '.esc_attr($data_class).' '.esc_attr($animated_class).'" '.$layout_attr.' '.$data_attr.' '.$animation_attr.' data-enable-isotope="1">';
						$output .= '<div class="tp-row post-inner-loop '.esc_attr($uid).' '.esc_attr($content_alignment_4).'">';
						foreach($testiAllList as $item) {
							$testiAuthor = !empty($item['testiAuthor']) ? $item['testiAuthor'] : '';
							$testiTitle = !empty($item['testiTitle']) ? $item['testiTitle'] : '';
							$testiLabel = !empty($item['testiLabel']) ? $item['testiLabel'] : '';
							$testiDesign = !empty($item['testiDesign']) ? $item['testiDesign'] : '';
							$testiImage = !empty($item['testiImage']['url']) ? $item['testiImage']['url'] : '';
							$testiImageId = !empty($item['testiImage']['id']) ? $item['testiImage']['id'] : '';
							$testiLogo = !empty($item['testiLogo']['url']) ? $item['testiLogo']['url'] : '';
							//grid item loop
							$output .= '<div class="grid-item '.$desktop_class.' '.$tablet_class.' '.$mobile_class.'">';
								if(!empty($style)){
									ob_start();
									include L_THEPLUS_PATH. 'includes/testimonial/testimonial-'.esc_attr($style).'.php'; 
									$output .= ob_get_contents();
									ob_end_clean();
								}
							$output .='</div>';
							$index++;
						}
						$output .='</div>';
					$output .='</div>';
				}else{
					$output .='<h3 class="theplus-posts-not-found">'.esc_html__( "This Style Premium Version", "tpebl" ).'</h3>';
				}
			}
		}else{
			if ( ! $query->have_posts() ) {
				$output .='<h3 class="theplus-posts-not-found">'.esc_html__( "Posts not found", "tpebl" ).'</h3>';
			}else{
				if($style=='style-1' || $style=='style-2' || $style=='style-4'){
					$output .= '<div id="theplus-testimonial-post-list" class="testimonial-list '.esc_attr($uid).' '.esc_attr($data_class).' '.esc_attr($animated_class).'" '.$layout_attr.' '.$data_attr.' '.$animation_attr.' data-enable-isotope="1">';
					
					
						$output .= '<div class="tp-row post-inner-loop '.esc_attr($uid).' '.esc_attr($content_alignment_4).'">';
						while ( $query->have_posts() ) {
						
							$query->the_post();
							$post = $query->post;
							
							//grid item loop
							$output .= '<div class="grid-item '.$desktop_class.' '.$tablet_class.' '.$mobile_class.'">';				
							if(!empty($style)){
								ob_start();
								include L_THEPLUS_PATH. 'includes/testimonial/testimonial-'.esc_attr($style).'.php'; 
								$output .= ob_get_contents();
								ob_end_clean();
							}
							$output .='</div>';
							
							$i++;
						}
						$output .='</div>';
					
					$output .='</div>';
				}else{
					$output .='<h3 class="theplus-posts-not-found">'.esc_html__( "This Style Premium Version", "tpebl" ).'</h3>';
				}
			}
		}
		
		echo $output;
		
		wp_reset_postdata();
	}
	
    protected function content_template() {
	
    }
	
	protected function get_query_args() {
		$settings = $this->get_settings_for_display();
		$post_name=l_theplus_testimonial_post_name();
		$taxonomy_name=l_theplus_testimonial_post_category();
		
		$terms = get_terms( array('taxonomy' => $taxonomy_name, 'hide_empty' => true) );			
		$post_category=$settings['post_category'];
		$category=array();
		if ( !is_wp_error( $terms ) && !empty($terms) && !empty($post_category)){			
			foreach( $terms as $term ) {					
				if(in_array($term->term_id,$post_category)){
					$category[]=$term->slug;
				}
			}
		}
		$query_args = array(
			'post_type'           => $post_name,
			$taxonomy_name		  => $category,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => intval( $settings['display_posts'] ),
			'orderby'      =>  $settings['post_order_by'],
			'order'      => $settings['post_order'],
		);

		$offset = $settings['post_offset'];
		$offset = ! empty( $offset ) ? absint( $offset ) : 0;

		if ( $offset ) {
			$query_args['offset'] = $offset;
		}
		global $paged;
		if ( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		}
		elseif ( get_query_var('page') ) {
			$paged = get_query_var('page');
		}
		else {
			$paged = 1;
		}
		$query_args['paged'] = $paged;
		
		$query = new \WP_Query( $query_args );
		
		return $query;
	}
	
	protected function get_carousel_options() {
		$settings = $this->get_settings_for_display();
		$data_slider ='';			
			$data_slider .=' data-slide_speed="'.esc_attr($settings["slide_speed"]["size"]).'"';			
			
			$data_slider .=' data-slider_desktop_column="1"';
			$data_slider .=' data-steps_slide="1"';
			
			$slider_draggable= ($settings["slider_draggable"]=='yes') ? 'true' : 'false';
			$data_slider .=' data-slider_draggable="'.esc_attr($slider_draggable).'"';
			$slider_infinite= ($settings["slider_infinite"]=='yes') ? 'true' : 'false';
			$data_slider .=' data-slider_infinite="'.esc_attr($slider_infinite).'"';
			
			$slider_adaptive_height= ($settings["slider_adaptive_height"]=='yes') ? 'true' : 'false';
			$data_slider .=' data-slider_adaptive_height="'.esc_attr($slider_adaptive_height).'"';
			$slider_autoplay= ($settings["slider_autoplay"]=='yes') ? 'true' : 'false';
			$data_slider .=' data-slider_autoplay="'.esc_attr($slider_autoplay).'"';
			$data_slider .=' data-autoplay_speed="'.esc_attr(!empty($settings["autoplay_speed"]["size"]) ? $settings["autoplay_speed"]["size"] : 3000).'"';
			
			$slider_dots= ($settings["slider_dots"]=='yes') ? 'true' : 'false';
			$data_slider .=' data-slider_dots="'.esc_attr($slider_dots).'"';
			$data_slider .=' data-slider_dots_style="slick-dots '.esc_attr($settings["slider_dots_style"]).'"';
			
			
			$slider_arrows= ($settings["slider_arrows"]=='yes') ? 'true' : 'false';
			$data_slider .=' data-slider_arrows="'.esc_attr($slider_arrows).'"';
			$data_slider .=' data-slider_arrows_style="'.esc_attr($settings["slider_arrows_style"]).'" ';
			$data_slider .=' data-arrow_icon_color="'.esc_attr($settings["arrow_icon_color"]).'" ';
			$data_slider .=' data-arrow_hover_bg_color="'.esc_attr($settings["arrow_hover_bg_color"]).'" ';
			$data_slider .=' data-arrow_hover_icon_color="'.esc_attr($settings["arrow_hover_icon_color"]).'" ';
						
		return $data_slider;
	}
}