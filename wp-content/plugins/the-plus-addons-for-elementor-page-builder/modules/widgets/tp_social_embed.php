<?php 
/*
Widget Name: Social Embed
Description: Social Embed
Author: Theplus
Author URI: https://posimyth.com
*/

namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

use TheplusAddons\Theplus_Element_Load;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class L_ThePlus_Social_Embed extends Widget_Base {
		
	public function get_name() {
		return 'tp-social-embed';
	}

    public function get_title() {
        return esc_html__('Social Embed', 'tpebl');
    }

    public function get_icon() {
        return 'fa fa-code theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-essential');
    }

    protected function register_controls() {
		/*Embed Option Start*/
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Embed Options', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);	
		$this->add_control('EmbedType',
			[
				'label' => esc_html__( 'Type', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'facebook',
				'options' => [
					'facebook'  => esc_html__( 'Facebook', 'tpebl' ),
					'twitter' => esc_html__( 'Twitter', 'tpebl' ),
					'vimeo'  => esc_html__( 'Vimeo', 'tpebl' ),
					'instagram' => esc_html__( 'Instagram', 'tpebl' ),
					'youtube'  => esc_html__( 'YouTube', 'tpebl' ),
					'googlemap'  => esc_html__( 'Google Map', 'tpebl' ),
				],
			]
		);
		$this->end_controls_section();
		/*Embed Option End*/
		/*Facebook Start*/
		$this->start_controls_section(
			'semd_Fb',
			[
				'label' => esc_html__( 'Facebook', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition'		=> [
					'EmbedType' => 'facebook',
				],
			]
		);
		$this->add_control(
			'Type',
			[
				'label' => esc_html__( 'Type', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'videos',
				'options' => [
					'comments'  => esc_html__( 'Comments', 'tpebl' ),
					'posts' => esc_html__( 'Posts', 'tpebl' ),
					'videos'  => esc_html__( 'Videos', 'tpebl' ),
					'page' => esc_html__( 'Page', 'tpebl' ),
					'likebutton'  => esc_html__( 'Like Button', 'tpebl' ),
					'save'  => esc_html__( 'Save Button', 'tpebl' ),
					'share'  => esc_html__( 'Share Button', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
				],
			]
		);
		$this->add_control(
			'CommentType',
			[
				'label' => esc_html__( 'Options', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'viewcomment',
				'options' => [
					'viewcomment'  => esc_html__( 'View Comments', 'tpebl' ),
					'onlypost' => esc_html__( 'Add Comments', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'CommentTypeViewCommentDep',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : The Embedded Comments has been deprecated.<a rel="noopener noreferrer" target="_blank" href="https://developers.facebook.com/docs/plugins/embedded-comments/" target="_blank">More Info</a>',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'viewcomment',
				],
			]
		);
		$this->add_control(
			'CommentURL',
			[
				'label' => __( 'Comment URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => 'https://www.facebook.com/posimyth/posts/2107330979289233?comment_id=2107337105955287&reply_comment_id=2108417645847233',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'viewcomment',
				],
			]
		);
		$this->add_control(
			'AppID',
			[
				'label' 		=> esc_html__( 'App ID', 'tpebl' ),
				'type' 			=> Controls_Manager::TEXT,
				'dynamic' 		=> [ 'active' => true ],
				'default' 		=> '',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'onlypost',
				],
			]
		);	
		$this->add_control(
			'AppIDFbPost',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : How to <a href="https://developers.facebook.com/apps"  target="_blank" rel="noopener noreferrer">Create App ID ?</a>',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'onlypost',
				],
			]
		);
		$this->add_control(
			'TargetC',
			[
				'label' => esc_html__( 'Target URL', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'currentpage'  => esc_html__( 'Current Page', 'tpebl' ),
					'custom' => esc_html__( 'Custom', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'onlypost',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'URLFC',
			[
				'label' => esc_html__( 'URL Format', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'plain',
				'options' => [
					'plain'  => esc_html__( 'Plain Permalink', 'tpebl' ),
					'pretty' => esc_html__( 'Pretty Permalink', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'onlypost',
					'TargetC' => 'currentpage',
				],
			]
		);
		$this->add_control(
			'CommentAddURL',
			[
				'label' => __( 'Custom URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => 'https://www.facebook.com/',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'onlypost',
					'TargetC' => 'custom',
				],
			]
		);
		$this->add_control(
			'PostURL',
			[
				'label' => __( 'Post URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => 'https://www.facebook.com/posimyth/posts/3054603914561930',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'posts',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'VideosURL',
			[
				'label' => __( 'Videos URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => 'https://www.facebook.com/posimyth/videos/444986032863860/',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'videos',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'URLP',
			[
				'label' => __( 'Page URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => 'https://www.facebook.com/posimyth',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'page',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'TargetLike',
			[
				'label' => esc_html__( 'Target URL', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'currentpage'  => esc_html__( 'Current Page', 'tpebl' ),
					'custom' => esc_html__( 'Custom', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'likebutton',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'FmtURLlb',
			[
				'label' => esc_html__( 'URL Format', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'plain',
				'options' => [
					'plain'  => esc_html__( 'Plain Permalink', 'tpebl' ),
					'pretty' => esc_html__( 'Pretty Permalink', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'likebutton',
					'TargetLike' => 'currentpage',
				],
			]
		);
		$this->add_control(
			'likeBtnUrl',
			[
				'label' => __( 'Like Button URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => 'https://www.facebook.com/posimyth',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'likebutton',
					'TargetLike' => 'custom',
				],
			]
		);
		$this->add_control(
			'SaveURL',
			[
				'label' => __( 'Save URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => 'https://www.facebook.com/',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'save',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'ShareURL',
			[
				'label' => __( 'Share URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => 'https://www.facebook.com/',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'share',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'ReMrFbPost',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : <a href="https://developers.facebook.com/docs/plugins"  target="_blank" rel="noopener noreferrer">Read More About All Options</a>',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'facebook',
				],
			]
		);
		$this->end_controls_section();
		/*Facebook End*/
		/*Facebook Options Start*/
		$this->start_controls_section(
			'semd_Fb_opts',
			[
				'label' => esc_html__( 'Facebook Options', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition'		=> [
					'EmbedType' => 'facebook',
				],
			]
		);
		$this->add_control(
            'PcomentcT',
            [
				'label'   => esc_html__( 'Parent Comment', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'viewcomment',
				],
			]
		);
		$this->add_responsive_control(
            'wdCmt',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'viewcomment',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'width: {{SIZE}}{{UNIT}}',	
				],
				'separator' => 'before',
            ]
        );
		$this->add_responsive_control(
            'HgCmt',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Height', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'viewcomment',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'height: {{SIZE}}{{UNIT}}',	
				],
            ]
        );
        $this->add_control(
			'CountC',
			[
				'label' => esc_html__( 'Comment Count', 'tpebl' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'step' => 100,
				'default' => '',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'onlypost',
				],
			]
		);
		$this->add_control(
			'OrderByC',
			[
				'label' => esc_html__( 'Order By', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'social',
				'options' => [
					'social'  => esc_html__( 'Social', 'tpebl' ),
					'reversetime' => esc_html__( 'Reverse Time', 'tpebl' ),
					'time' => esc_html__( 'Time', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'comments',
					'CommentType' => 'onlypost',
				],
			]
		);	
		$this->add_control(
            'FullPT',
            [
				'label'   => esc_html__( 'Show Text', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'posts',
				],
			]
		);
		$this->add_responsive_control(
            'wdPost',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'posts',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'width: {{SIZE}}{{UNIT}}',	
				],
				'separator' => 'before',
            ]
        );
		$this->add_responsive_control(
            'HgPost',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Height', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'posts',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'height: {{SIZE}}{{UNIT}}',	
				],
            ]
        );
        $this->add_control(
            'FullVT',
            [
				'label'   => esc_html__( 'Allow Full Screen', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'videos',
				],
			]
		);
		$this->add_control(
            'AutoplayVT',
            [
				'label'   => esc_html__( 'Autoplay', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'videos',
				],
			]
		);
		$this->add_control(
            'CaptionVT',
            [
				'label'   => esc_html__( 'Captions', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'videos',
				],
			]
		);
		$this->add_responsive_control(
            'wdVideo',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'videos',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'width: {{SIZE}}{{UNIT}}',	
				],
				'separator' => 'before',
            ]
        );
		$this->add_responsive_control(
            'HgVideo',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Height', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'videos',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'height: {{SIZE}}{{UNIT}}',
				],
            ]
        );
        $this->add_control(
			'LayoutP',
			[
				'label' => esc_html__( 'Layout', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'timeline',
				'options' => [
					'timeline'  => esc_html__( 'Timeline', 'tpebl' ),
					'events' => esc_html__( 'Events', 'tpebl' ),
					'messages' => esc_html__( 'Messages', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'page',
				],
			]
		);
		$this->add_control(
            'smallHP',
            [
				'label'   => esc_html__( 'Small Header', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'page',
				],
			]
		);
		$this->add_control(
            'CoverP',
            [
				'label'   => esc_html__( 'Cover Photo', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'page',
				],
			]
		);
		$this->add_control(
            'ProfileP',
            [
				'label'   => esc_html__( 'Show Friend\'s Faces', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'page',
				],
			]
		);
		$this->add_control(
            'CTABTN',
            [
				'label'   => esc_html__( 'Custom CTA Button', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'page',
				],
			]
		);
		$this->add_responsive_control(
            'wdPage',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'page',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'width: {{SIZE}}{{UNIT}}',	
				],
				'separator' => 'before',
            ]
        );
		$this->add_responsive_control(
            'HgPage',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Height', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'page',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'height: {{SIZE}}{{UNIT}}',
				],
            ]
        );
        $this->add_control(
			'SizeLB',
			[
				'label' => esc_html__( 'Size', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'small',
				'options' => [
					'small'  => esc_html__( 'Small', 'tpebl' ),
					'large'  => esc_html__( 'Large', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => ['likebutton','save','share'],
				],
			]
		);
		$this->add_control(
			'TypeLB',
			[
				'label' => esc_html__( 'Type', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'like',
				'options' => [
					'like'  => esc_html__( 'Like', 'tpebl' ),
					'recommend'  => esc_html__( 'Recommend', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'likebutton',
				],
			]
		);
		$this->add_control(
			'BtnStyleLB',
			[
				'label' => esc_html__( 'Layout', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'button',
				'options' => [
					'standard'  => esc_html__( 'Standard', 'tpebl' ),
					'button'  => esc_html__( 'Button', 'tpebl' ),
					'button_count'  => esc_html__( 'Button Count', 'tpebl' ),
					'box_count'  => esc_html__( 'Box Count', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'likebutton',
				],
			]
		);
		$this->add_control(
			'ColorSLB',
			[
				'label' => esc_html__( 'Color Scheme', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light'  => esc_html__( 'Light', 'tpebl' ),
					'dark'  => esc_html__( 'Dark', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'likebutton',
					'BtnStyleLB' => 'standard',
				],
			]
		);
		$this->add_control(
            'SBtnLB',
            [
				'label'   => esc_html__( 'Share Button', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'likebutton',
				],
			]
		);
		$this->add_control(
            'FacesLBT',
            [
				'label'   => esc_html__( 'Faces', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'likebutton',
				],
			]
		);
		$this->add_responsive_control(
            'wdLikeBtn',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'likebutton',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'width: {{SIZE}}{{UNIT}}',	
				],
				'separator' => 'before',
            ]
        );
		$this->add_responsive_control(
            'HgLikeBtn',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Height', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'likebutton',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'height: {{SIZE}}{{UNIT}}',
				],
            ]
        );
        $this->add_control(
			'ShareBTN',
			[
				'label' => esc_html__( 'Layout', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'button',
				'options' => [
					'button'  => esc_html__( 'Button', 'tpebl' ),
					'button_count'  => esc_html__( 'Button Count', 'tpebl' ),
					'box_count'  => esc_html__( 'Box Count', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'share',
				],
			]
		);
		$this->add_responsive_control(
            'wdShareBtn',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'share',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'width: {{SIZE}}{{UNIT}}',	
				],
				'separator' => 'before',
            ]
        );
		$this->add_responsive_control(
            'HgShareBtn',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Height', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'facebook',
					'Type' => 'share',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-fb-iframe' => 'height: {{SIZE}}{{UNIT}}',
				],
            ]
        );
		$this->end_controls_section();
		/*Facebook Options End*/
		/*Twitter Start*/
		$this->start_controls_section(
			'semd_Tw',
			[
				'label' => esc_html__( 'Twitter', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition'		=> [
					'EmbedType' => 'twitter',
				],
			]
		);
		$this->add_control(
			'TweetType',
			[
				'label' => esc_html__( 'Embed Type', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'timelines',
				'options' => [
					'Tweets'  => esc_html__( 'Tweets Embed', 'tpebl' ),
					'timelines'  => esc_html__( 'Timelines Embed', 'tpebl' ),
					'buttons'  => esc_html__( 'Buttons Embed', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
				],
			]
		);
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'TweetURl',
			[
				'label' => esc_html__( 'Tweet URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'default' => [
					'url' => 'https://twitter.com/Interior/status/463440424141459456',
				],
				'dynamic' => [
					'active'   => true,
				],
			]
		);
		$repeater->add_control(
			'TwMassage',
			[
				'label' => esc_html__( 'Loading Message', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Loading', 'tpebl' ),
				'dynamic' => ['active'   => true,],
			]
		);
		$this->add_control(
            'TwRepeater',
            [
				'label' => esc_html__( 'Tweets', 'tpebl' ),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'TweetURl' => 'https://twitter.com/Interior/status/463440424141459456', 
                        'TwMassage' => '&mdash; Loading',                      
                    ],				
                ],
                'separator' => 'before',
				'fields' => $repeater->get_controls(),
                'title_field' => '{{{ TwMassage }}}',
                'condition'  => [
                    'EmbedType' => 'twitter',					
					'TweetType' => 'Tweets',
				],				
            ]
        );
        $this->add_control(
			'TwGuides',
			[
				'label' => esc_html__( 'Guides Contents', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'Profile',
				'options' => [
					'Profile'  => esc_html__( 'Profile Timeline', 'tpebl' ),
					'Likes'  => esc_html__( 'Likes Timeline', 'tpebl' ),
					'List'  => esc_html__( 'List Timeline', 'tpebl' ),
					'Collection'  => esc_html__( 'Collection', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'timelines',
				],
			]
		);
		$this->add_control(
			'Twstyle',
			[
				'label' => esc_html__( 'Layout', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'linear',
				'options' => [
					'grid'  => esc_html__( 'Grid style', 'tpebl' ),
					'linear'  => esc_html__( 'Linear style', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'timelines',
				],
			]
		);
		$this->add_control(
			'Twlisturl',
			[
				'label' => __( 'List URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => 'https://twitter.com/TwitterDev/lists/national-parks',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'timelines',
					'TwGuides' => 'List',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'TwlisturlNote',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : How to <a href="https://tweetdeck.twitter.com/"  target="_blank" rel="noopener noreferrer">Create List ?</a>',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'timelines',
					'TwGuides' => 'List',
				],
			]
		);
		$this->add_control(
			'TwCollection',
			[
				'label' => __( 'Collection URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => 'https://twitter.com/TwitterDev/timelines/539487832448843776',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'timelines',
					'TwGuides' => 'Collection',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'TwCollectionNote',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : How to <a href="https://tweetdeck.twitter.com/"  target="_blank" rel="noopener noreferrer">Create Collections ?</a>',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'timelines',
					'TwGuides' => 'Collection',
				],
			]
		);
		$this->add_control(
			'Twbutton',
			[
				'label' => esc_html__( 'Button Type', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'follow',
				'options' => [
					'Tweets'  => esc_html__( 'Tweets', 'tpebl' ),
					'follow'  => esc_html__( 'Follow', 'tpebl' ),
					'Message'  => esc_html__( 'Direct Message', 'tpebl' ),
					'like'  => esc_html__( 'Like', 'tpebl' ),
					'Reply'  => esc_html__( 'Reply', 'tpebl' ),
					'Retweet'  => esc_html__( 'Retweet', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
				],
			]
		);
		$this->add_control(
			'Twname',
			[
				'label' => esc_html__( 'Username', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'TwitterDev', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
				'condition'		=> [
					'EmbedType' => 'twitter',
				],
				'conditions' => [
				    'relation' => 'or',
				    'terms' => [
				        [
				        'terms' => [
				        	    ['name' => 'TweetType', 'operator' => '===', 'value' => 'timelines'],
				                ['name' => 'TwGuides', 'operator' => 'in', 'value' => ['Profile','Likes']], 
				            ]
				        ],
				        [
				        'terms' => [
				                ['name' => 'TweetType', 'operator' => '===', 'value' => 'buttons'],
				                ['name' => 'Twbutton', 'operator' => 'in', 'value' => ['follow','Message']],
				            ]
				        ],
				    ]
				],		
			]
		);
		$this->add_control(
			'TwRId',
			[
				'label' => esc_html__( 'Message ID', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '3805104374', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => 'Message',
				],				
			]
		);
		$this->add_control(
			'TwTweetId',
			[
				'label' => esc_html__( 'Tweet ID', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '463440424141459456', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['like','Reply','Retweet'],
				],				
			]
		);
		$this->add_control(
			'ReMrTwPost',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : <a href="https://developer.twitter.com/en/docs/twitter-for-websites/embedded-tweets/overview"  target="_blank" rel="noopener noreferrer">Read More About All Options</a>',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'twitter',
				],
			]
		);
		$this->end_controls_section();
		/*Twitter End*/
		/*Twitter Options Start*/
		$this->start_controls_section(
			'semd_Tw_opts',
			[
				'label' => esc_html__( 'Twitter Options', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition'		=> [
					'EmbedType' => 'twitter',
				],
			]
		);
		$this->add_control(
            'TwColor',
            [
				'label'   => esc_html__( 'Dark Mode', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => ['Tweets','timelines'],
				],
			]
		);
		$this->add_control(
            'TwCards',
            [
				'label'   => esc_html__( 'Disable Media', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'Tweets',
				],
			]
		);
		$this->add_control(
            'Twconver',
            [
				'label'   => esc_html__( 'Disable Conversation', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'Tweets',
				],
			]
		);
		$this->add_control(
			'Twalign',
			[
				'label' => esc_html__( 'Alignment', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [					
					'left'  => esc_html__( 'Left', 'tpebl' ),
					'center'  => esc_html__( 'Center', 'tpebl' ),
					'right'  => esc_html__( 'Right', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'Tweets',
				],
			]
		);
		$this->add_control(
			'TwBrCr',
			[
				'label' => esc_html__( 'Separator Color', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'separator' => 'before',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'timelines',
				],
			]
		);
		$this->add_control(
			'TwDesign',
			[
				'label' => esc_html__( 'Button', 'tpebl' ),
				'type' => Controls_Manager::SELECT2,				
				'multiple' => true,	
				'default' => '',			
				'options' => [
					'noheader' => esc_html__( 'No Header','tpebl' ),
					'nofooter' => esc_html__( 'No Footer','tpebl' ),	
					'noborders' => esc_html__( 'No Borders','tpebl' ),
					'noscrollbar' => esc_html__( 'No Scrollbar','tpebl' ),
					'transparent' => esc_html__( 'Transparent','tpebl' ),				
				],
				'label_block' 	=> true,
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'timelines',
				],
			]
		);
		$this->add_responsive_control(
            'Twlimit',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Tweet Limit', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => '',
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'timelines',
				],
				'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'Twwidth',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width ( px )', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => ['Tweets','timelines'],
				],
				'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'Twheight',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Height ( px )', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 10,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'timelines',
					'Twstyle' => 'linear',
				],
				'separator' => 'before',
            ]
        );
        $this->add_control(
			'TwBtnSize',
			[
				'label' => esc_html__( 'Button Size', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__( 'Normal', 'tpebl' ),
					'large' => esc_html__( 'Large', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['Tweets','follow','Message'],
				],
			]
		);
		$this->add_control(
			'TwTextBtn',
			[
				'label' => esc_html__( 'Tweet Text', 'tpebl' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 3,
				'default' => esc_html__( 'Hello', 'tpebl' ),
				'dynamic' => [
					'active'   => true,
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['Tweets'],
				],
			]
		);	
		$this->add_control(
			'TwTweetUrl',
			[
				'label' => __( 'Page URL', 'tpebl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL Here', 'tpebl' ),
				'show_external' => true,
				'dynamic' 		=> [ 'active' => true ],
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['Tweets'],
				],
			]
		);
		$this->add_control(
			'TwHashtags',
			[
				'label' => esc_html__( 'Hashtags ( Tag1 #Tag2 )', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Twitter', 'tpebl' ),
				'placeholder' => esc_html__( '#Tag1 #Tag2 #tag3', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' 	=> true,
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['Tweets'],
				],				
			]
		);
		$this->add_control(
			'TwVia',
			[
				'label' => esc_html__( 'Via ( Tag1 @Tag2 )', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Twitter', 'tpebl' ),
				'placeholder' => esc_html__( 'Tag1 @Tag2 @tag3', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' 	=> true,
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['Tweets'],
				],				
			]
		);
		$this->add_control(
            'TwCount',
            [
				'label'   => esc_html__( 'Followers Count', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['follow'],
				],
			]
		);
		$this->add_control(
            'TwHideUname',
            [
				'label'   => esc_html__( 'Disable Username', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['follow','Message'],
				],
			]
		);
		$this->add_control(
			'TwMessage',
			[
				'label' => esc_html__( 'Message Text', 'tpebl' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 3,
				'default' => esc_html__( 'Hello', 'tpebl' ),
				'dynamic' => [
					'active'   => true,
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['Message'],
				],
			]
		);
		$this->add_control(
            'TwIcon',
            [
				'label'   => esc_html__( 'Disable Icon', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['like','Reply','Retweet'],
				],
			]
		);
		$this->add_control(
			'likeBtn',
			[
				'label' => esc_html__( 'Button Text', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Like', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['like'],
				],					
			]
		);
		$this->add_control(
			'ReplyBtn',
			[
				'label' => esc_html__( 'Button Text', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Reply', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['Reply'],
				],					
			]
		);
		$this->add_control(
			'RetweetBtn',
			[
				'label' => esc_html__( 'Button Text', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Retweet', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['Retweet'],
				],					
			]
		);
		$this->add_control(
			'TwMsg',
			[
				'label' => esc_html__( 'Loading Message', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Loading', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' 	=> true,
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
				],
				'separator' => 'before',					
			]
		);
		$this->end_controls_section();
		/*Twitter Options End*/
		/*Vimeo Start*/
		$this->start_controls_section(
			'semd_Vm',
			[
				'label' => esc_html__( 'Vimeo', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition'		=> [
					'EmbedType' => 'vimeo',
				],
			]
		);
		$this->add_control(
			'ViId',
			[
				'label' => esc_html__( 'Vimeo ID', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '288344114', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'vimeo',
				],					
			]
		);
		$this->add_control(
			'ViOption',
			[
				'label' => esc_html__( 'Vimeo Option', 'tpebl' ),
				'type' => Controls_Manager::SELECT2,				
				'multiple' => true,	
				'default' => '',			
				'options' => [
					'loop' => esc_html__( 'Loop','tpebl' ),
					'muted' => esc_html__( 'Muted','tpebl' ),	
					'speed' => esc_html__( 'Speed (Pro Account)','tpebl' ),
					'title' => esc_html__( 'Title','tpebl' ),
					'autoplay' => esc_html__( 'Autoplay','tpebl' ),
					'autopause' => esc_html__( 'AutoPause','tpebl' ),				
					'portrait' => esc_html__( 'Portrait','tpebl' ),
					'fullscreen' => esc_html__( 'FullScreen','tpebl' ),
					'background' => esc_html__( 'Background (Plus Account)','tpebl' ),
					'playsinline' => esc_html__( 'PlaysInline','tpebl' ),
					'byline' => esc_html__( 'Byline (Username)','tpebl' ),
					'transparent' => esc_html__( 'Transparent','tpebl' ),
					'dnt' => esc_html__( 'Do Not Track (DNT)','tpebl' ),
					'pip' => esc_html__( 'Picture In Picture (PIP)','tpebl' ),
				],
				'label_block' 	=> true,
				'condition'		=> [
					'EmbedType' => 'vimeo',
				],
			]
		);
		$this->add_control(
			'VmAutoplayNote',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : The <b>mute</b> option should be required when you select the <b>autoplay</b> option.',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'vimeo',
				],
			]
		);
		$this->add_control(
			'ReMrVmPost',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : <a href="https://vimeo.zendesk.com/hc/en-us/articles/360001494447-Using-Player-Parameters"  target="_blank" rel="noopener noreferrer">Read More About All Options</a>',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'vimeo',
				],
			]
		);
		$this->add_control(
			'VmStime',
			[
				'label' => esc_html__( 'Video Start Time', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'E.g : 5m0s', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'vimeo',
				],					
			]
		);
		$this->add_control(
			'VmColor',
			[
				'label' => esc_html__( 'Text Color', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition'		=> [
					'EmbedType' => 'vimeo',
				],
			]
		);
		$this->end_controls_section();
		/*Vimeo End*/
		/*Instagram Start*/
		$this->start_controls_section(
			'semd_Ig',
			[
				'label' => esc_html__( 'Instagram', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition'		=> [
					'EmbedType' => 'instagram',
				],
			]
		);
		$this->add_control(
			'IGType',
			[
				'label' => esc_html__( 'Instagram Type', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'posts',
				'options' => [
					'posts'  => esc_html__( 'Posts', 'tpebl' ),
					'reels' => esc_html__( 'Reels', 'tpebl' ),
					'igtv'  => esc_html__( 'IGTV', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'instagram',
				],
			]
		);	
		$this->add_control(
			'IGId',
			[
				'label' => esc_html__( 'Instagram ID', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'CGAvnLcA3zb', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'instagram',
				],					
			]
		);
		$this->add_control(
            'IGCaptione',
            [
				'label'   => esc_html__( 'Disable Captioned', 'tpebl' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'condition'		=> [
					'EmbedType' => 'instagram',
				],
			]
		);
		$this->add_control(
			'ReMrIgPost',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : <a href="https://developers.facebook.com/docs/instagram"  target="_blank" rel="noopener noreferrer">Read More About All Options</a>',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'instagram',
				],
			]
		);
		$this->end_controls_section();
		/*Instagram End*/
		/*YouTube Start*/
		$this->start_controls_section(
			'semd_Yt',
			[
				'label' => esc_html__( 'YouTube', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition'		=> [
					'EmbedType' => 'youtube',
				],
			]
		);
		$this->add_control(
			'YtType',
			[
				'label' => esc_html__( 'YouTube Type', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'YtSV',
				'options' => [
					'YtSV'  => esc_html__( 'Single Video', 'tpebl' ),
					'YtPlayV' => esc_html__( 'Playlist Video', 'tpebl' ),
					'YtuserV'  => esc_html__( 'Users Video', 'tpebl' ),
				],
				'condition'		=> [
					'EmbedType' => 'youtube',
				],
			]
		);	
		$this->add_control(
			'YtVideoId',
			[
				'label' => esc_html__( 'Video ID', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'XmtXC_n6X6Q', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' 	=> true,
				'condition'		=> [
					'EmbedType' => 'youtube',
					'YtType' => 'YtSV',
				],					
			]
		);
		$this->add_control(
			'YtPlaylistId',
			[
				'label' => esc_html__( 'Playlist ID', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'PLivjPDlt6ApQgylktXlL2AhuPvRtDiN1S', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' 	=> true,
				'condition'		=> [
					'EmbedType' => 'youtube',
					'YtType' => 'YtPlayV',
				],					
			]
		);
		$this->add_control(
			'YtUsername',
			[
				'label' => esc_html__( 'Username', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'NationalGeographic', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' 	=> true,
				'condition'		=> [
					'EmbedType' => 'youtube',
					'YtType' => 'YtuserV',
				],					
			]
		);
		$this->add_control(
			'YtOption',
			[
				'label' => esc_html__( 'YouTube Option', 'tpebl' ),
				'type' => Controls_Manager::SELECT2,				
				'multiple' => true,	
				'default' => '',			
				'options' => [
					'loop' => esc_html__( 'Loop','tpebl' ),
					'fs' => esc_html__( 'FullScreen','tpebl' ),	
					'autoplay' => esc_html__( 'Autoplay','tpebl' ),
					'mute' => esc_html__( 'Muted','tpebl' ),
					'controls' => esc_html__( 'Controls Enable','tpebl' ),
					'disablekb' => esc_html__( 'Disable Keyboard','tpebl' ),
					'modestbranding' => esc_html__( 'Disable Youtube Icon','tpebl' ),				
					'playsinline' => esc_html__( 'PlaysInline','tpebl' ),
					'rel' => esc_html__( 'Related Video','tpebl' ),
				],
				'label_block' 	=> true,
				'condition'		=> [
					'EmbedType' => 'youtube',
				],
			]
		);
		$this->add_control(
			'YtAutoplayNote',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : The <b>mute</b> option should be required when you select the <b>autoplay</b> option.',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'youtube',
				],
			]
		);
		$this->add_control(
			'ReMrYtPost',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : <a href="https://developers.google.com/youtube/player_parameters"  target="_blank" rel="noopener noreferrer">Read More About All Options</a>',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'youtube',
				],
			]
		);
		$this->add_control(
			'YtSTime',
			[
				'label' => esc_html__( 'Start Time', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'E.g : 60', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'youtube',
				],					
			]
		);
		$this->add_control(
			'YtETime',
			[
				'label' => esc_html__( 'End Time', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'E.g : 60', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'youtube',
				],					
			]
		);
		$this->add_control(
			'Ytlanguage',
			[
				'label' => esc_html__( 'Language', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'E.g : en', 'tpebl' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'		=> [
					'EmbedType' => 'youtube',
				],					
			]
		);
		$this->add_control(
			'YtLangNote',
			[				
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => 'Note : <a href="http://www.loc.gov/standards/iso639-2/php/code_list.php" target="_blank" rel="noopener noreferrer">Language ISO 639-1 Code</a>',
				'content_classes' => 'tp-widget-description',
				'condition'		=> [
					'EmbedType' => 'youtube',
				],
			]
		);
		$this->end_controls_section();
		/*YouTube End*/

		/*Google Map Start*/
		$this->start_controls_section('Map_section',
			[
				'label'=>esc_html__('Google Map','tpebl'),
				'tab'=>Controls_Manager::TAB_CONTENT,
				'condition'=>[
					'EmbedType'=>'googlemap',
				],
			]
		);
		$this->add_control('Mapaccesstoken',
			[
				'label'=>esc_html__('Map Type','tpebl'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'default',
				'options'=>[
					'default'=>esc_html__('Default','tpebl'),
					'accesstoken'=>esc_html__('Access Token','tpebl'),
				],
			]
		);
		$this->add_control('GAccesstoken',
			[
				'label'=>__('AccessToken','tpebl'),
				'type'=>Controls_Manager::TEXTAREA,
				'rows'=>2,
				'default'=>'',
				'placeholder'=>__('Enter AccessToken','tpebl'),
				'condition'=>[
					'Mapaccesstoken'=>'accesstoken',
				],
			]
		);
		$this->add_control('GMapModes',
			[
				'label'=>esc_html__('Map Modes','tpebl'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'search',
				'options'=>[
					'place'=>esc_html__('Place Mode','tpebl'),
					'directions'=>esc_html__('Directions Mode','tpebl'),
					'streetview'=>esc_html__('Streetview Mode','tpebl'),
					'search'=>esc_html__('Search Mode','tpebl'),
				],
				'condition'=>[
					'Mapaccesstoken'=>'accesstoken',
				],
			]
		);
		$this->add_control('GSearchText',
			[
				'label'=>__('Search Text','tpebl'),
				'type'=>Controls_Manager::TEXT,
				'default'=>__('New York, NY, USA','tpebl'),
				'placeholder'=>__('Enter Location Text','tpebl'),
				'condition'=>[
					'GMapModes'=>['place','search'],
				],
			]
		);
		$this->add_control('GOrigin',
			[
				'label'=>__('Starting point','tpebl'),
				'type'=>Controls_Manager::TEXT,
				'default'=>__('LosAngeles+California+USA','tpebl'),
				'placeholder'=>__('Enter Starting Point','tpebl'),
				'separator'=>'before',
				'condition'=>[
					'Mapaccesstoken'=>'accesstoken',
					'GMapModes'=>'directions',
				],
			]
		);
		$this->add_control('GDestination',
			[
				'label'=>__('End Point','tpebl'),
				'type'=>Controls_Manager::TEXT,
				'default'=>__('Corona+California+USA','tpebl'),
				'placeholder'=>__('Enter Starting Point','tpebl'),
				'condition'=>[
					'Mapaccesstoken'=>'accesstoken',
					'GMapModes'=>'directions',
				],
			]
		);
		$this->add_control('GWaypoints',
			[
				'label'=>__('Way Points','tpebl'),
				'type'=>Controls_Manager::TEXTAREA,
				'rows'=>3,
				'default'=>__('Huntington+Beach+California+US | Santa Ana+California+USA','tpebl'),
				'placeholder'=>__( 'Type your description here','tpebl'),
				'condition'=>[
					'Mapaccesstoken'=>'accesstoken',
					'GMapModes'=>'directions',
				],
			]
		);
		$this->add_control('GTravelMode',
			[
				'label'=>esc_html__('Travel Mode','tpebl'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'driving',
				'options'=>[
					'driving'=>esc_html__('Driving Mode','tpebl'),
					'bicycling'=>esc_html__('Bicycling','tpebl'),
					'flying'=>esc_html__('Flying','tpebl'),
					'transit'=>esc_html__('Transit','tpebl'),
					'walking'=>esc_html__('Walking Mode','tpebl'),					
				],
				'condition'=>[
					'Mapaccesstoken'=>'accesstoken',
					'GMapModes'=>'directions',
				],
			]
		);
		$this->add_control('Gavoid',
			[
				'label'=>esc_html__('Avoid Elements','tpebl'),
				'type'=>Controls_Manager::SELECT2,				
				'multiple'=>true,	
				'default'=>'',			
				'options'=>[
					'tolls'=>__('Tolls','tpebl'),
					'highways'=>__('Highways','tpebl'),
				],
				'label_block'=>true,
				'condition'=>[
					'Mapaccesstoken'=>'accesstoken',
					'GMapModes'=>'directions',
				],
			]
		);
		$this->add_control('GstreetviewText',
			[
				'label'=>__('latitude and longitude','tpebl'),
				'type'=>Controls_Manager::TEXT,
				'default'=>__('23.0489,72.5160','tpebl'),
				'placeholder'=>__('let,long','tpebl'),
				'condition'=>[
					'Mapaccesstoken'=>'accesstoken',
					'GMapModes'=>'streetview',
				],
			]
		);
		$this->add_control('Pluscodelink',
			[				
				'type'=>Controls_Manager::RAW_HTML,
				'raw'=>'Note : <a href="https://plus.codes/7JMJ2GP6+9F" target="_blank" rel="noopener noreferrer">Get latitude and longitude</a>',
				'content_classes'=>'tp-widget-description',
				'condition'=>[
					'EmbedType'=>'googlemap',
				],
			]
		);
		$this->end_controls_section();
		/*Google Map End*/

		/*Map option start*/
		$this->start_controls_section('MapOption_section',
			[
				'label'=>esc_html__('Map Option','tpebl'),
				'tab'=>Controls_Manager::TAB_CONTENT,
				'condition'=>[
					'EmbedType'=>'googlemap',
				],
			]
		);
		$this->add_control('MapViews',
			[
				'label'=>esc_html__('Map View','tpebl'),
				'type'=>Controls_Manager::SELECT,
				'default'=>'roadmap',
				'options'=>[
					'roadmap'=>esc_html__('Roadmap','tpebl'),
					'satellite'=>esc_html__('Satellite','tpebl'),
				],
				'condition'=>[
					'Mapaccesstoken'=>'accesstoken',
					'EmbedType'=>'googlemap',
					'GMapModes!'=>'streetview',
				],
			]
		);
		$this->add_control('MapZoom',
			[
				'label'=>__('Zoom','tpebl'),
				'type'=>Controls_Manager::SLIDER,
				'size_units'=>['px'],
				'range' => [
					'px'=>[
						'min' => 0,
						'max' => 21,
						'step' => 1,
					],
				],
				'default' => [
					'unit'=>'px',
					'size'=>3,
				],
				'condition'=>[
					'GMapModes!'=>'streetview',
				],
			]
		);
		$this->add_control('GMwidth',
			[
				'label'=>__('Width','tpebl'),
				'type'=>Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit'=>'px',
					'size'=>600,
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe' => 'width:{{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control('GMHeight',
			[
				'label'=>__('Height','tpebl'),
				'type'=>Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit'=>'px',
					'size'=>450,
				],
				
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe' => 'height:{{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->end_controls_section();
		/*Map option End*/

		/*Extra Options Start*/
		$this->start_controls_section(
			'semd_Extra_opts',
			[
				'label' => esc_html__( 'Extra Options', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition'		=> [
					'EmbedType' => ['vimeo','youtube'],
				],
			]
		);
		$this->add_responsive_control(
            'ExWidth',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
						'step' => 640,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => ['vimeo','youtube'],
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-frame-set' => 'width: {{SIZE}}{{UNIT}}',			
				],
            ]
        );
		$this->add_responsive_control(
            'ExHeight',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Height', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
						'step' => 360,
					],
				],
				'render_type' => 'ui',
				'condition'		=> [
					'EmbedType' => ['vimeo','youtube'],
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe.tp-frame-set' => 'height: {{SIZE}}{{UNIT}}',			
				],
            ]
        );
		$this->end_controls_section();
		/*Extra Options End*/

		/*Embed Options Style Start*/
		$this->start_controls_section(
            'section_EmdOpt_styling',
            [
                'label' => esc_html__('Embed Options', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,				
            ]
        );
        $this->add_responsive_control(
			'AlignmentBG',
			[
				'label' => esc_html__( 'Content Alignment', 'tpebl' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
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
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->start_controls_tabs( 'tabs_EmdOpt_stl' );
		$this->start_controls_tab(
			'tab_EmdOpt_Nml',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
				'conditions' => [
				    'relation' => 'or',
				    'terms' => [
				        [
				        'terms' => [
				        	    ['name' => 'EmbedType', 'operator' => '===', 'value' => 'facebook'],
				                ['name' => 'Type', 'operator' => 'in', 'value' => ['comments','posts','videos','page']], 
				            ]
				        ],
				        [
				        'terms' => [
				                ['name' => 'EmbedType', 'operator' => '===', 'value' => 'twitter'],
				                ['name' => 'TweetType', 'operator' => '===', 'value' => 'buttons'],
				                ['name' => 'Twbutton', 'operator' => 'in', 'value' => ['like','Reply','Retweet']],
				            ]
				        ],
				    ]
				],
			]
		);
		$this->add_control(
            'TwBtnCr',
            [
                'label' => esc_html__('Color', 'tpebl'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tw-button' => 'color:{{VALUE}};',
                ],
                'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['like','Reply','Retweet'],
				],
            ]
        );
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'TwBtnBg',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .tw-button',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['like','Reply','Retweet'],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'BorderPost',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-fb-iframe,{{WRAPPER}} .tw-button',
				'conditions' => [
				    'relation' => 'or',
				    'terms' => [
				        [
				        'terms' => [
				        	    ['name' => 'EmbedType', 'operator' => '===', 'value' => 'facebook'],
				                ['name' => 'Type', 'operator' => 'in', 'value' => ['comments','posts','videos','page']], 
				            ]
				        ],
				        [
				        'terms' => [
				                ['name' => 'EmbedType', 'operator' => '===', 'value' => 'twitter'],
				                ['name' => 'TweetType', 'operator' => '===', 'value' => 'buttons'],
				                ['name' => 'Twbutton', 'operator' => 'in', 'value' => ['like','Reply','Retweet']],
				            ]
				        ],
				    ]
				],
			]
		);
		$this->add_responsive_control(
			'BorderRs',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tp-fb-iframe,{{WRAPPER}} .tw-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
				],
				'conditions' => [
				    'relation' => 'or',
				    'terms' => [
				        [
				        'terms' => [
				        	    ['name' => 'EmbedType', 'operator' => '===', 'value' => 'facebook'],
				                ['name' => 'Type', 'operator' => 'in', 'value' => ['comments','posts','videos','page']], 
				            ]
				        ],
				        [
				        'terms' => [
				                ['name' => 'EmbedType', 'operator' => '===', 'value' => 'twitter'],
				                ['name' => 'TweetType', 'operator' => '===', 'value' => 'buttons'],
				                ['name' => 'Twbutton', 'operator' => 'in', 'value' => ['like','Reply','Retweet']],
				            ]
				        ],
				    ]
				],	
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'BoxS',
				'selector' => '{{WRAPPER}} .tp-fb-iframe,{{WRAPPER}} .tw-button',
				'conditions' => [
				    'relation' => 'or',
				    'terms' => [
				        [
				        'terms' => [
				        	    ['name' => 'EmbedType', 'operator' => '===', 'value' => 'facebook'],
				                ['name' => 'Type', 'operator' => 'in', 'value' => ['comments','posts','videos','page']], 
				            ]
				        ],
				        [
				        'terms' => [
				                ['name' => 'EmbedType', 'operator' => '===', 'value' => 'twitter'],
				                ['name' => 'TweetType', 'operator' => '===', 'value' => 'buttons'],
				                ['name' => 'Twbutton', 'operator' => 'in', 'value' => ['like','Reply','Retweet']],
				            ]
				        ],
				    ]
				],				
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_EmdOpt_Hvr',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
				'conditions' => [
				    'relation' => 'or',
				    'terms' => [
				        [
				        'terms' => [
				        	    ['name' => 'EmbedType', 'operator' => '===', 'value' => 'facebook'],
				                ['name' => 'Type', 'operator' => 'in', 'value' => ['comments','posts','videos','page']], 
				            ]
				        ],
				        [
				        'terms' => [
				                ['name' => 'EmbedType', 'operator' => '===', 'value' => 'twitter'],
				                ['name' => 'TweetType', 'operator' => '===', 'value' => 'buttons'],
				                ['name' => 'Twbutton', 'operator' => 'in', 'value' => ['like','Reply','Retweet']],
				            ]
				        ],
				    ]
				],
			]
		);
		$this->add_control(
            'TwBtnCrH',
            [
                'label' => esc_html__('Color', 'tpebl'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tw-button:hover' => 'color:{{VALUE}};',
                ],
                'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['like','Reply','Retweet'],
				],
            ]
        );
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'TwBtnBgH',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .tw-button:hover',
				'condition'		=> [
					'EmbedType' => 'twitter',
					'TweetType' => 'buttons',
					'Twbutton' => ['like','Reply','Retweet'],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'BorderPostHr',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-fb-iframe:hover,{{WRAPPER}} .tw-button:hover',
				'conditions' => [
				    'relation' => 'or',
				    'terms' => [
				        [
				        'terms' => [
				        	    ['name' => 'EmbedType', 'operator' => '===', 'value' => 'facebook'],
				                ['name' => 'Type', 'operator' => 'in', 'value' => ['comments','posts','videos','page']], 
				            ]
				        ],
				        [
				        'terms' => [
				                ['name' => 'EmbedType', 'operator' => '===', 'value' => 'twitter'],
				                ['name' => 'TweetType', 'operator' => '===', 'value' => 'buttons'],
				                ['name' => 'Twbutton', 'operator' => 'in', 'value' => ['like','Reply','Retweet']],
				            ]
				        ],
				    ]
				],
			]
		);
		$this->add_responsive_control(
			'BorderHRs',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tp-fb-iframe:hover,{{WRAPPER}} .tw-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',				
				],
				'conditions' => [
				    'relation' => 'or',
				    'terms' => [
				        [
				        'terms' => [
				        	    ['name' => 'EmbedType', 'operator' => '===', 'value' => 'facebook'],
				                ['name' => 'Type', 'operator' => 'in', 'value' => ['comments','posts','videos','page']], 
				            ]
				        ],
				        [
				        'terms' => [
				                ['name' => 'EmbedType', 'operator' => '===', 'value' => 'twitter'],
				                ['name' => 'TweetType', 'operator' => '===', 'value' => 'buttons'],
				                ['name' => 'Twbutton', 'operator' => 'in', 'value' => ['like','Reply','Retweet']],
				            ]
				        ],
				    ]
				],	
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'BoxSHr',
				'selector' => '{{WRAPPER}} .tp-fb-iframe:hover,{{WRAPPER}} .tw-button:hover',
				'conditions' => [
				    'relation' => 'or',
				    'terms' => [
				        [
				        'terms' => [
				        	    ['name' => 'EmbedType', 'operator' => '===', 'value' => 'facebook'],
				                ['name' => 'Type', 'operator' => 'in', 'value' => ['comments','posts','videos','page']], 
				            ]
				        ],
				        [
				        'terms' => [
				                ['name' => 'EmbedType', 'operator' => '===', 'value' => 'twitter'],
				                ['name' => 'TweetType', 'operator' => '===', 'value' => 'buttons'],
				                ['name' => 'Twbutton', 'operator' => 'in', 'value' => ['like','Reply','Retweet']],
				            ]
				        ],
				    ]
				],				
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'EmbedBrstyle',
			[
				'label' => esc_html__( 'Border Style', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'tpebl' ),
					'solid' => esc_html__( 'Solid', 'tpebl' ),
					'dashed' => esc_html__( 'Dashed', 'tpebl' ),
					'dotted' => esc_html__( 'Dotted', 'tpebl' ),
					'groove' => esc_html__( 'Groove',  'tpebl' ),
					'inset' => esc_html__( 'Inset','tpebl' ),
					'outset' => esc_html__( 'Outset','tpebl' ),
					'ridge' => esc_html__( 'Ridge', 'tpebl' ),
				],
				'condition' => [
					'EmbedType' => ['vimeo','instagram','youtube'],
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe' => 'border-style: {{VALUE}} !important',
				],
			]
		);
		$this->add_responsive_control(
			'EmbedBrwidth',
			[
				'label' => esc_html__( 'Border Width', 'tpebl' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition' => [
					'EmbedType' => ['vimeo','instagram','youtube'],
					'EmbedBrstyle!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_control(
			'EmbedBrcolor',
			[
				'label' => esc_html__( 'Border Color', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::COLOR,					
				'condition' => [
					'EmbedType' => ['vimeo','instagram','youtube'],
					'EmbedBrstyle!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe' => 'border-color: {{VALUE}} !important',
				],
			]
		);
		$this->add_control(
			'EmbedBsd',
			[
				'label' => esc_html__( 'Box Shadow', 'tpebl' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'Default', 'tpebl' ),
				'label_on' => __( 'Custom', 'tpebl' ),
				'return_value' => 'yes',
				'condition'		=> [
					'EmbedType' => ['vimeo','instagram','youtube'],
				],
			]
		);
		$this->start_popover();
		$this->add_control(
			'EmbedBsd_color',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'condition' => [
					'EmbedType' => ['vimeo','instagram','youtube'],
					'EmbedBsd' => 'yes',		
				],
			]
		);
		$this->add_control(
			'EmbedBsd_horizontal',
			[
				'label' => esc_html__( 'Horizontal', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 100,
						'min' => -100,
						'step' => 2,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition'    => [
					'EmbedType' => ['vimeo','instagram','youtube'],
					'EmbedBsd' => 'yes',
				],
			]
		);
		$this->add_control(
			'EmbedBsd_vertical',
			[
				'label' => esc_html__( 'Vertical', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 100,
						'min' => -100,
						'step' => 2,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition'    => [
					'EmbedType' => ['vimeo','instagram','youtube'],
					'EmbedBsd' => 'yes',
				],
			]
		);
		$this->add_control(
			'EmbedBsd_blur',
			[
				'label' => esc_html__( 'Blur', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 100,
						'min' => 0,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'condition'    => [
					'EmbedType' => ['vimeo','instagram','youtube'],
					'EmbedBsd' => 'yes',
				],
			]
		);
		$this->add_control(
			'EmbedBsd_spread',
			[
				'label' => esc_html__( 'Spread', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 100,
						'min' => -100,
						'step' => 2,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tp-social-embed iframe' => 'box-shadow: {{EmbedBsd_horizontal.SIZE}}{{EmbedBsd_horizontal.UNIT}} {{EmbedBsd_vertical.SIZE}}{{EmbedBsd_vertical.UNIT}} {{EmbedBsd_blur.SIZE}}{{EmbedBsd_blur.UNIT}} {{EmbedBsd_spread.SIZE}}{{EmbedBsd_spread.UNIT}} {{EmbedBsd_color.VALUE}} !important',
				 ],
				'condition'    => [
					'EmbedType' => ['vimeo','instagram','youtube'],
					'EmbedBsd' => 'yes',
				],
			]
		);
		$this->end_popover();
		$this->add_control(
			'SemdBgOpt',
			[
				'label' => esc_html__( 'Outer Background Option', 'tpebl' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',	
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'SocialBg',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .tp-social-embed',
			]
		);
		$this->end_controls_section();
		/*Embed Options Style End*/
	}
	
	protected function render() {

        $settings = $this->get_settings_for_display();
		$uid_sembed = uniqid("tp-sembed");
		$EmbedType = !empty($settings['EmbedType']) ? $settings['EmbedType'] : 'facebook';

		$output = '';
		$lz2 = function_exists('tp_has_lazyload') ? tp_bg_lazyLoad($settings['SocialBg_image']) : '';
		$output .= '<div class="tp-widget-'.esc_attr($uid_sembed).' tp-social-embed '.$lz2.'">';
		if($EmbedType == 'vimeo' || $EmbedType == 'youtube'){
			$ExWidth = !empty($settings['ExWidth']['size']) ? $settings['ExWidth']['size'] : 640;
			$ExHeight = !empty($settings['ExHeight']['size']) ? $settings['ExHeight']['size'] : 360;
		}
		if( $EmbedType == 'facebook' ){
			$Type = !empty($settings['Type']) ? $settings['Type'] : '';
			$SizeBtn = !empty($settings['SizeLB']) ? $settings['SizeLB'] : '';
			if( $Type == 'comments' ){
				$CommentType = !empty($settings['CommentType']) ? $settings['CommentType'] : 'viewcomment';
				if( $CommentType == 'viewcomment' ){
					$CommentURL = !empty($settings['CommentURL']) && !empty($settings['CommentURL']['url']) ? urlencode($settings['CommentURL']['url']) : '';
					$FBwdCmt = !empty($settings['wdCmt']['size']) ? $settings['wdCmt']['size'] : 560;
					$FBHgCmt = !empty($settings['HgCmt']['size']) ? $settings['HgCmt']['size'] : 300;
					$PcomentcT = !empty($settings['PcomentcT'] == 'yes') ? true : false;
					if( $CommentURL ){
						$output .= '<iframe class="tp-fb-iframe" src="https://www.facebook.com/plugins/comment_embed.php?href='.esc_attr($CommentURL).'&include_parent='.esc_attr($PcomentcT).'&width='.esc_attr($FBwdCmt).'&height='.esc_attr($FBHgCmt).'&appId=" width="'.esc_attr($FBwdCmt).'" height="'.esc_attr($FBHgCmt).'" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" ></iframe>';
					}else{
						$output .= 'URL Empty';
					}
				}else if( $CommentType == 'onlypost' ){
					$FBCommentAdd = !empty($settings['CommentAddURL']) && !empty($settings['CommentAddURL']['url']) ? $settings['CommentAddURL']['url'] : '';	
					$TargetC = !empty($settings['TargetC']) ? $settings['TargetC'] : 'custom';
					if( $TargetC == 'currentpage' ){
 						$URLFC = !empty($settings['URLFC']) ? $settings['URLFC'] : 'plain';
						$post_id = get_the_ID();
					    if( $URLFC == 'plain' ){
					    	$PlainURL = get_permalink( $post_id );
							$output .= '<div class="fb-comments tp-fb-iframe" data-href="'.esc_url($PlainURL).'" data-width="" data-numposts="'.esc_attr($settings['CountC']).'" data-order-by="'.esc_attr($settings['OrderByC']).'" ></div>';
					    }else if( $URLFC == 'pretty' ){
					    	$PrettyURL = add_query_arg('p', $post_id, home_url());	
							$output .= '<div class="fb-comments tp-fb-iframe" data-href="'.esc_url($PrettyURL).'" data-width="" data-numposts="'.esc_attr($settings['CountC']).'" data-order-by="'.esc_attr($settings['OrderByC']).'" ></div>';
					    }
					}else{
						$output .= '<div class="fb-comments tp-fb-iframe" data-href="'.esc_url($FBCommentAdd).'" data-width="" data-numposts="'.esc_attr($settings['CountC']).'" data-order-by="'.esc_attr($settings['OrderByC']).'" ></div>';
					}
					$output .= '<script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>';
				}
			}
			if( $Type == 'posts' ){
				$PostURL = !empty($settings['PostURL']) && !empty($settings['PostURL']['url']) ? $settings['PostURL']['url'] : '';			
				$wdPost = !empty($settings['wdPost']['size']) ? $settings['wdPost']['size'] : 500;
				$HgPost = !empty($settings['HgPost']['size']) ? $settings['HgPost']['size'] : 560;
				$FullPT = !empty($settings['FullPT'] == 'yes') ? true : false;
				$output .= '<iframe class="tp-fb-iframe" src="https://www.facebook.com/plugins/post.php?href='.esc_url($PostURL).'&show_text='.esc_attr($FullPT).'&width='.esc_attr($wdPost).'&height='.esc_attr($HgPost).'&appId=" width="'.esc_attr($wdPost).'" height="'.esc_attr($HgPost).'" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" ></iframe>';
			}
			if( $Type == 'videos' ){
				$VideosURL = !empty($settings['VideosURL']) && !empty($settings['VideosURL']['url']) ? $settings['VideosURL']['url'] : '';
				$wdVideo = !empty($settings['wdVideo']['size']) ? $settings['wdVideo']['size'] : 500;
				$HgVideo = !empty($settings['HgVideo']['size']) ? $settings['HgVideo']['size'] : 560;
				$CaptionVT = !empty($settings['CaptionVT'] == 'yes') ? true : false;
				$AutoplayVT = !empty($settings['AutoplayVT'] == 'yes') ? true : false;
				$FullVideo = '';
				if(isset($settings['FullVT']) && $settings['FullVT'] =='yes'){
					$FullVideo = 'allowFullScreen';
				}				
				$output .= '<iframe class="tp-fb-iframe" src="https://www.facebook.com/plugins/video.php?href='.esc_url($VideosURL).'&show_text='.esc_attr($CaptionVT).'&width='.esc_attr($wdVideo).'&height='.esc_attr($HgVideo).'&autoplay='.esc_attr($AutoplayVT).'&appId=" width="'.esc_attr($wdVideo).'" height="'.esc_attr($HgVideo).'" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" '.$FullVideo.' ></iframe>';
			}
			if( $Type == 'likebutton' ){
				$FBLikeBtn = !empty($settings['likeBtnUrl']) && !empty($settings['likeBtnUrl']['url']) ? $settings['likeBtnUrl']['url'] : '';
				$FacesLBT = !empty($settings['FacesLBT'] == 'yes') ? true : false;
				$FBHgLike = !empty($settings['HgLikeBtn']['size']) ? $settings['HgLikeBtn']['size'] : 70;
				$FBwdLike = !empty($settings['wdLikeBtn']['size']) ? $settings['wdLikeBtn']['size'] : 350;
				$SBtnLB = !empty($settings['SBtnLB'] == 'yes') ? true : false;
				if( $settings['TargetLike'] == 'currentpage' ){
					$FmtURLlb = !empty($settings['FmtURLlb']) ? $settings['FmtURLlb'] : 'plain';
					$post_id = get_the_ID();
					if( $FmtURLlb == 'plain' ){
						$PlainLURL = get_permalink( $post_id );
						$output .= '<iframe class="tp-fb-iframe" src="https://www.facebook.com/plugins/like.php?href='.esc_url($PlainLURL).'&layout='.esc_attr($settings['BtnStyleLB']).'&action='.esc_attr($settings['TypeLB']).'&size='.esc_attr($SizeBtn).'&share='.esc_attr($SBtnLB).'&height='.esc_attr($FBHgLike).'&show_faces='.esc_attr($FacesLBT).'&colorscheme='.esc_attr($settings['ColorSLB']).'&width='.esc_attr($FBwdLike).'&appId=" width="'.esc_attr($FBwdLike).'" height="'.esc_attr($FBHgLike).'" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>';
					}else if( $FmtURLlb == 'pretty' ){
						$PrettyLURL = add_query_arg('p', $post_id, home_url());						
						$output .= '<iframe class="tp-fb-iframe" src="https://www.facebook.com/plugins/like.php?href='.esc_url($PrettyLURL).'&layout='.esc_attr($settings['BtnStyleLB']).'&action='.esc_attr($settings['TypeLB']).'&size='.esc_attr($SizeBtn).'&share='.esc_attr($SBtnLB).'&height='.esc_attr($FBHgLike).'&show_faces='.esc_attr($FacesLBT).'&colorscheme='.esc_attr($settings['ColorSLB']).'&width='.esc_attr($FBwdLike).'&appId=" width="'.esc_attr($FBwdLike).'" height="'.esc_attr($FBHgLike).'" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>';
					}
				}else{
					$output .= '<iframe class="tp-fb-iframe" src="https://www.facebook.com/plugins/like.php?href='.esc_url($FBLikeBtn).'&layout='.esc_attr($settings['BtnStyleLB']).'&action='.esc_attr($settings['TypeLB']).'&size='.esc_attr($SizeBtn).'&share='.esc_attr($SBtnLB).'&height='.esc_attr($FBHgLike).'&show_faces='.esc_attr($FacesLBT).'&colorscheme='.esc_attr($settings['ColorSLB']).'&width='.esc_attr($FBwdLike).'&appId=" width="'.esc_attr($FBwdLike).'" height="'.esc_attr($FBHgLike).'" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>';
				}
			}
			if( $Type == 'page' ){
				$URLP = !empty($settings['URLP']) && !empty($settings['URLP']['url']) ? $settings['URLP']['url'] : '';			
				$wdPage = !empty($settings['wdPage']['size']) ? $settings['wdPage']['size'] : 340;
				$HgPage = !empty($settings['HgPage']['size']) ? $settings['HgPage']['size'] : 500;
                $smallHP = !empty($settings['smallHP'] == 'yes') ? true : false;
				
				$CoverP = true;
				if(!empty($settings['CoverP']) && $settings['CoverP']==='yes'){
					$CoverP = false;
				}
                
                $ProfileP = !empty($settings['ProfileP'] == 'yes') ? true : false;
				
                $CTABTN = !empty($settings['CTABTN'] == 'yes') ? true : false;				
				$output  .= '<iframe class="tp-fb-iframe" src="https://www.facebook.com/plugins/page.php?href='.esc_url($URLP).'&tabs='.esc_attr($settings['LayoutP']).'&width='.esc_attr($wdPage).'&height='.esc_attr($HgPage).'&small_header='.esc_attr($smallHP).'&hide_cover='.esc_attr($CoverP).'&show-facepile='.esc_attr($ProfileP).'&hide_cta='.esc_attr($CTABTN).'&lazy=true&adapt_container_width=true&appId=" width="'.esc_attr($wdPage).'" height="'.esc_attr($HgPage).'" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" ></iframe>';
			}
			if( $Type == 'save' ){
				$SaveURL = !empty($settings['SaveURL']) && !empty($settings['SaveURL']['url']) ? $settings['SaveURL']['url'] : '';
							
				$output .= '<div class="fb-save" data-uri="'.esc_url($SaveURL).'" data-size="'.esc_attr($SizeBtn).'"></div>';
				$output .= '<script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>';
			}
			if( $Type == 'share' ){
				$ShareURL = !empty($settings['ShareURL']) && !empty($settings['ShareURL']['url']) ? $settings['ShareURL']['url'] : '';
				$ShareHbt = !empty($settings['HgShareBtn']['size']) ? $settings['HgShareBtn']['size'] : 50;
				$ShareWbt = !empty($settings['wdShareBtn']['size']) ? $settings['wdShareBtn']['size'] : 96;				
				$output .= '<iframe class="tp-fb-iframe" src="https://www.facebook.com/plugins/share_button.php?href='.esc_url($ShareURL).'&layout='.esc_attr($settings['ShareBTN']).'&size='.esc_attr($SizeBtn).'&width='.esc_attr($ShareWbt).'&height='.esc_attr($ShareHbt).'&appId=" width="'.esc_attr($ShareWbt).'" height="'.esc_attr($ShareHbt).'" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>';
			}
		}else if( $EmbedType == 'twitter' ){
			$TweetType = !empty($settings['TweetType']) ? $settings['TweetType'] : 'timelines';
			$Twname = !empty($settings['Twname']) ? $settings['Twname'] : '';
			$TwColor = !empty($settings['TwColor'] == 'yes') ? 'dark' : 'light';			
			$Twwidth = !empty($settings['Twwidth']['size']) ? $settings['Twwidth']['size'] : '';
			$Twconver = !empty($settings['Twconver'] == 'yes') ? 'none' : '';
			$TwMsg = !empty($settings['TwMsg']) ? $settings['TwMsg'] : '';
			if( $TweetType == 'Tweets' ){
				$TwRepeater = !empty($settings['TwRepeater']) ? $settings['TwRepeater'] : [];
				$TwCards = !empty($settings['TwCards'] == 'yes') ? 'hidden' : '';
				$TwAlign = !empty($settings['Twalign']) ? $settings['Twalign'] : 'center';
				foreach ( $TwRepeater as $index => $Tweet ) {
					$TwURl = !empty($Tweet['TweetURl']) && !empty($Tweet['TweetURl']['url']) ? $Tweet['TweetURl']['url'] : '';
					$TwMassage = !empty($Tweet['TwMassage']) ? $Tweet['TwMassage'] : '';
					$output .= '<blockquote class="twitter-tweet" data-theme="'.esc_attr($TwColor).'" data-width="'.esc_attr($Twwidth).'" data-cards="'.esc_attr($TwCards).'" data-align="'.esc_attr($TwAlign).'" data-conversation="'.esc_attr($Twconver).'" >';
						$output .= '<p lang="en" dir="ltr">'.wp_kses_post($TwMassage).'</p>';
						$output .= '<a href="'.esc_attr($TwURl).'"></a>';
					$output .= '</blockquote>';
				}
			}
			if( $TweetType == 'timelines' ){
				$TwURl = '';
				$Twclass = 'twitter-timeline';
				$TwGuides = !empty($settings['TwGuides']) ? $settings['TwGuides'] : 'Profile';
				$TwBrCr = !empty($settings['TwBrCr']) ? $settings['TwBrCr'] : '';
				$Twlimit = !empty($settings['Twlimit']['size']) ? $settings['Twlimit']['size'] : '';
				$Twstyle = !empty($settings['Twstyle']) ? $settings['Twstyle'] : 'linear';
				$TwDesign = !empty($settings['TwDesign']) ? $settings['TwDesign'] : [];
				$Twheight = ( $Twstyle == 'linear' ) ? $settings['Twheight']['size'] : '';
				$DesignBTN = array();
				if (is_array($TwDesign)) {
					foreach ($TwDesign as $value) {
						$DesignBTN[] = $value;
					}
				}
				$TwDesign = json_encode($DesignBTN);				    
				if($TwGuides == 'Profile'){
					$TwURl = 'https://twitter.com/'.esc_attr($Twname);
				}else if($TwGuides == 'List'){
					$TwURl = !empty($settings['Twlisturl']) && !empty($settings['Twlisturl']['url']) ? $settings['Twlisturl']['url'] : '';
				}else if($TwGuides == 'Likes'){
					$TwURl = 'https://twitter.com/'.esc_attr($Twname).'/likes';
				}else if($TwGuides == 'Collection'){
					$Twclass = 'twitter-grid';
					$TwURl = !empty($settings['TwCollection']) && !empty($settings['TwCollection']['url']) ? $settings['TwCollection']['url'] : '';
				}
				$output .= '<a class="'.esc_attr($Twclass).'" href="'.esc_url($TwURl).'" data-width="'.esc_attr($Twwidth).'" data-height="'.esc_attr($Twheight).'" data-theme="'.esc_attr($TwColor).'" data-chrome="'.esc_attr($TwDesign).'" data-border-color="'.esc_attr($TwBrCr).'" data-tweet-limit="'.esc_attr($Twlimit).'" data-aria-polite="" >'.wp_kses_post($TwMsg).'</a>';
			}
			if( $TweetType == 'buttons' ){
				$Twbutton = !empty($settings['Twbutton']) ? $settings['Twbutton'] : 'follow';
				$TwBtnSize = !empty($settings['TwBtnSize']) ? $settings['TwBtnSize'] : '';
				$TwTweetId = !empty($settings['TwTweetId']) ? $settings['TwTweetId'] : '';
				$Twicon = !empty($settings['TwIcon'] == 'yes') ? '' : '<i class="fab fa-twitter"></i>';
				
				$lz1 = function_exists('tp_has_lazyload') ? tp_bg_lazyLoad($settings['TwBtnBg_image'],$settings['TwBtnBgH_image']) : '';
				if( $Twbutton == 'Tweets' ){
					$TwVia = !empty($settings['TwVia']) ? $settings['TwVia'] : '';
					$TwTextBtn = !empty($settings['TwTextBtn']) ? $settings['TwTextBtn'] : '';
					$TwHashtags = !empty($settings['TwHashtags']) ? $settings['TwHashtags'] : '';
					$TwTweetUrl = !empty($settings['TwTweetUrl']) && !empty($settings['TwTweetUrl']['url']) ? $settings['TwTweetUrl']['url'] : '';
					$output .= '<a class="twitter-share-button" href="https://twitter.com/intent/tweet" data-size="'.esc_attr($TwBtnSize).'" data-text="'.esc_attr($TwTextBtn).'" data-url="'.esc_url($TwTweetUrl).'" data-via="'.esc_attr($TwVia).'" data-hashtags="'.esc_attr($TwHashtags).'" >'.wp_kses_post($TwMsg).'</a></br>';
				}else if( $Twbutton == 'follow' ){
					$TwCount = !empty($settings['TwCount']) ? $settings['TwCount'] : 'false';
					$TwHideUname = !empty($settings['TwHideUname'] == 'yes') ? 'false' : $settings['TwHideUname'];
					$output .= '<a class="twitter-follow-button" href="https://twitter.com/'.esc_attr($Twname).'" data-size="'.esc_attr($TwBtnSize).'" data-show-screen-name="'.esc_attr($TwHideUname).'" data-show-count="'.esc_attr($TwCount).'" >'.wp_kses_post($TwMsg).'</a></br>';
				}else if( $Twbutton == 'Message' ){
					$TwRId = !empty($settings['TwRId']) ? $settings['TwRId'] : '';
					$TwMessage = !empty($settings['TwMessage']) ? $settings['TwMessage'] : '';
					$TwHideUname = !empty($settings['TwHideUname']) ? '@' : '';
					$output .= '<a class="twitter-dm-button" href="https://twitter.com/messages/compose?recipient_id='.esc_attr($TwRId).'" data-text="'.esc_attr($TwMessage).'" data-size="'.esc_attr($TwBtnSize).'" data-screen-name="'.esc_attr($TwHideUname.$Twname).'">'.wp_kses_post($TwMsg).'</a>';
				}else if( $Twbutton == 'like' ){
					$output .= '<a class="tw-button '.esc_attr($lz1).'" href="https://twitter.com/intent/like?tweet_id='.esc_attr($TwTweetId).'" >'.wp_kses_post($Twicon.' '.$settings['likeBtn']).'</a>';
				}else if( $Twbutton == 'Reply' ){
					$output .= '<a class="tw-button '.esc_attr($lz1).'" href="https://twitter.com/intent/tweet?in_reply_to='.esc_attr($TwTweetId).'">'.wp_kses_post($Twicon.' '.$settings['ReplyBtn']).'</a>';
				}else if( $Twbutton == 'Retweet' ){
					$output .= '<a class="tw-button '.esc_attr($lz1).'" href="https://twitter.com/intent/retweet?tweet_id='.esc_attr($TwTweetId).'">'.wp_kses_post($Twicon.' '.$settings['RetweetBtn']).'</a>';
				}
			}
			$output .= '<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
		}else if( $EmbedType == 'vimeo' ){
			$VmId = !empty($settings['ViId']) ? $settings['ViId'] : '';
			$VmStime = !empty($settings['VmStime']) ? $settings['VmStime'] : '';
			$VmColor = !empty($settings['VmColor']) ? ltrim($settings['VmColor'], '#') : 'ffffff';
			$VmSelect = !empty($settings['ViOption']) ? $settings['ViOption'] : [];                
			$VmALL = [];
			if (is_array($VmSelect)) {
				foreach ($VmSelect as $value) {
					$VmALL[] = $value;
				}
		    }
			$Vm_FullScreen = (in_array('fullscreen', $VmALL)) ? 'webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true"' : '';
			$Vm_AutoPlay = (in_array('autoplay', $VmALL)) ? 1 : 0;
			$Vm_loop = (in_array('loop', $VmALL)) ? 1 : 0;
			$Vm_Muted = (in_array('muted', $VmALL)) ? 1 : 0;
			$Vm_AutoPause = (in_array('autopause', $VmALL)) ? 1 : 0;
			$Vm_BackGround = (in_array('background', $VmALL)) ? 1 : 0;
			$Vm_Byline = (in_array('byline', $VmALL)) ? 1 : 0;
			$Vm_Speed = (in_array('speed', $VmALL)) ? 1 : 0;
			$Vm_Title = (in_array('title', $VmALL)) ? 1 : 0;
			$Vm_Portrait = (in_array('portrait', $VmALL)) ? 1 : 0;
			$Vm_PlaySinline = (in_array('playsinline', $VmALL)) ? 1 : 0;
			$Vm_Dnt = (in_array('dnt', $VmALL)) ? 1 : 0;
			$Vm_PiP = (in_array('pip', $VmALL)) ? 1 : 0;
			$Vm_transparent = (in_array('transparent', $VmALL)) ? 1 : 0;
			$output .= '<iframe class="tp-frame-set" src="https://player.vimeo.com/video/'.esc_attr($VmId).'?autoplay='.esc_attr($Vm_AutoPlay).'&loop='.esc_attr($Vm_loop).'&muted='.esc_attr($Vm_Muted).'&autopause='.esc_attr($Vm_AutoPause).'&background='.esc_attr($Vm_BackGround).'&byline='.esc_attr($Vm_Byline).'&playsinline='.esc_attr($Vm_PlaySinline).'&speed='.esc_attr($Vm_Speed).'&title='.esc_attr($Vm_Title).'&portrait='.esc_attr($Vm_Portrait).'&dnt='.esc_attr($Vm_Dnt).'&pip='.esc_attr($Vm_PiP).'&transparent='.esc_attr($Vm_transparent).'&color='.esc_attr($VmColor).'&#t='.esc_attr($VmStime).'" width="'.esc_attr($ExWidth).'" height="'.esc_attr($ExHeight).'" frameborder="0" '.esc_attr($Vm_FullScreen).' ></iframe>';
		}else if( $EmbedType == 'instagram' ){
			$IGType = !empty($settings['IGType']) ? $settings['IGType'] : 'posts';
			$IGId = !empty($settings['IGId']) ? $settings['IGId'] : 'CGAvnLcA3zb';
			$IGCap = '';
			if(isset($settings['IGCaptione']) && $settings['IGCaptione']!='yes'){
				$IGCap = 'data-instgrm-captioned';
			}
			if($IGType == "posts"){
				$IG_id = 'p/'.$IGId;
			}else if($IGType == "reels"){
				$IG_id = 'reel/'.$IGId;
			}else if($IGType == "igtv"){
				$IG_id = 'tv/'.$IGId;
			}
			$output .= '<blockquote class="instagram-media" '.esc_attr($IGCap).' data-instgrm-version="13" data-instgrm-permalink="https://www.instagram.com/'.esc_attr($IG_id).'/?utm_source=ig_embed"></blockquote><script async src="//www.instagram.com/embed.js"></script>';
		}else if( $EmbedType == 'youtube' ){
            $YtType = !empty($settings['YtType']) ? $settings['YtType'] : 'YtSV';
			$YtOption = !empty($settings['YtOption']) ? $settings['YtOption'] : [];
			$YtSTime = !empty($settings['YtSTime']) ? $settings['YtSTime'] : '';
			$YtETime = !empty($settings['YtETime']) ? $settings['YtETime'] : '';	
			$Ytlanguage = !empty($settings['Ytlanguage']) ? $settings['Ytlanguage'] : '';
			$YtSelect = [];
			if (is_array($YtOption)) {
				foreach ($YtOption as $value) {
					$YtSelect[] = $value;
				}
		    }	
			$Yt_loop = (in_array('loop', $YtSelect)) ? 1 : 0;
			$Yt_fs = (in_array('fs', $YtSelect)) ? 1 : 0;
			$Yt_autoplay = (in_array('autoplay', $YtSelect)) ? 1 : 0;
			$Yt_muted = (in_array('mute', $YtSelect)) ? 1 : 0;
			$Yt_controls = (in_array('controls', $YtSelect)) ? 1 : 0;
			$Yt_disablekb = (in_array('disablekb', $YtSelect)) ? 1 : 0;
			$Yt_modestbranding = (in_array('modestbranding', $YtSelect)) ? 1 : 0;
			$Yt_playsinline = (in_array('playsinline', $YtSelect)) ? 1 : 0;
			$Yt_rel = (in_array('rel', $YtSelect)) ? 1 : 0;
			$YT_Parameters = 'autoplay='.esc_attr($Yt_autoplay).'&mute='.esc_attr($Yt_muted).'&controls='.esc_attr($Yt_controls).'&disablekb='.esc_attr($Yt_disablekb).'&fs='.esc_attr($Yt_fs).'&modestbranding='.esc_attr($Yt_modestbranding).'&loop='.esc_attr($Yt_loop).'&rel='.esc_attr($Yt_rel).'&playsinline='.esc_attr($Yt_playsinline).'&start='.esc_attr($YtSTime).'&end='.esc_attr($YtETime).'&hl='.esc_attr($Ytlanguage);
			if($YtType == "YtSV"){
				$YtVideoId = !empty($settings['YtVideoId']) ? $settings['YtVideoId'] : '';
				$YtSrc = 'https://www.youtube.com/embed/'.esc_attr($YtVideoId).'?playlist='.esc_attr($YtVideoId).'&'.esc_attr($YT_Parameters);
			}else if($YtType == "YtPlayV"){
				$YtPlaylistId = !empty($settings['YtPlaylistId']) ? $settings['YtPlaylistId'] : '';
				$YtSrc = 'https://www.youtube.com/embed?listType=playlist&list='.esc_attr($YtPlaylistId).'&'.esc_attr($YT_Parameters);
			}else if($YtType == "YtuserV"){
				$YtUsername = !empty($settings['YtUsername']) ? $settings['YtUsername'] : '';
				$YtSrc = 'https://www.youtube.com/embed?listType=user_uploads&list='.esc_attr($YtUsername).'&'.esc_attr($YT_Parameters);
			}
			$output .= '<iframe class="tp-frame-set" width="'.esc_attr($ExWidth).'" height="'.esc_attr($ExHeight).'" src="'.esc_attr($YtSrc).'" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
		}else if( $EmbedType == 'googlemap' ){
			$Mapaccesstoken = !empty($settings['Mapaccesstoken']) ? $settings['Mapaccesstoken'] : 'default';	
			$GSearchText = !empty($settings['GSearchText']) ? $settings['GSearchText'] : 'Goa+India';
			$MapZoom = (!empty($settings['MapZoom']) && !empty($settings['MapZoom']['size']) ) ? (int)$settings['MapZoom']['size'] : 1;

			if($Mapaccesstoken == 'default'){
				$output .= '<iframe src="https://maps.google.com/maps?q='.esc_attr($GSearchText).'&z='.esc_attr($MapZoom).'&output=embed"  loading="lazy" allowfullscreen frameborder="0" scrolling="no"></iframe>';
			}else if($Mapaccesstoken == 'accesstoken'){
				$GAccesstoken = !empty($settings['GAccesstoken']) ? $settings['GAccesstoken'] : '';
				$GMapModes = !empty($settings['GMapModes']) ? $settings['GMapModes'] : 'search';
				$MapViews = !empty($settings['MapViews']) ? $settings['MapViews'] : 'roadmap';

				if($GMapModes == "place"){
					$output .= '<iframe src="https://www.google.com/maps/embed/v1/place?key='.esc_attr($GAccesstoken).'&q='.esc_attr($GSearchText).'&zoom='.esc_attr($MapZoom).'&maptype='.esc_attr($MapViews).'&language=En"   loading="lazy" allowfullscreen></iframe>';
				}else if($GMapModes == "directions"){
					$GOrigin = !empty($settings['GOrigin']) ? '&origin='.$settings['GOrigin'] : '&origin=""';
					$GDestination = !empty($settings['GDestination']) ? '&destination='.$settings['GDestination'] : '&destination=""';
					$GWaypoints = !empty($settings['GWaypoints']) ? '&waypoints='.$settings['GWaypoints'] : '';
					$GTravelMode = !empty($settings['GTravelMode']) ? $settings['GTravelMode'] : 'GTravelMode';
					$Gavoid = !empty($settings['Gavoid']) ? '&avoid='.implode("|", $settings['Gavoid']) : '';

					$output .= '<iframe src="https://www.google.com/maps/embed/v1/directions?key='.esc_attr($GAccesstoken).esc_attr($GOrigin).esc_attr($GDestination).esc_attr($GWaypoints).esc_attr($Gavoid).'&mode='.esc_attr($GTravelMode).'&zoom='.esc_attr($MapZoom).'&maptype='.esc_attr($MapViews).'&language=En"  loading="lazy" allowfullscreen ></iframe>';
				}else if($GMapModes == "streetview"){
					$GstreetviewText = !empty($settings['GstreetviewText']) ? $settings['GstreetviewText'] : '';

					$output .= '<iframe src="https://www.google.com/maps/embed/v1/streetview?key='.esc_attr($GAccesstoken).'&location='.esc_attr($GstreetviewText).'&heading=210&pitch=10&fov=90"  loading="lazy" allowfullscreen></iframe>';
				}else if($GMapModes == "search"){
					$output .= '<iframe src="https://www.google.com/maps/embed/v1/search?key='.esc_attr($GAccesstoken).'&q='.esc_attr($GSearchText).'&zoom='.esc_attr($MapZoom).'&maptype='.esc_attr($MapViews).'&language=En"  loading="lazy" allowfullscreen ></iframe>';
				}
			}
		}
		$output .= '</div>';					
		echo $output;
	}
	
    protected function content_template() {
    }
}