<?php
namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) { exit; }

class Emage_Post_Grid extends Widget_Base {

    protected $config = null;

    public function get_name() {
		return 'emage_post_grid';
	}

	public function get_title() {
		return esc_html__('Emage Post Grid', 'ehe-lang');
    }
    
    public function get_icon() {
		return 'eicon-posts-grid';
	}
	
	public function get_help_url() {
		return 'https://docs.blocksera.com/emage-hover-effects-for-elementor?utm_source=wp&utm_medium=elementor&utm_term=emage';
	}

	public function get_custom_help_url() {
		return 'https://docs.blocksera.com/emage-hover-effects-for-elementor?utm_source=wp&utm_medium=elementor&utm_term=emage';
	}

    public function get_post_types() {
        $cpts = get_post_types(array('public' => true, 'show_in_nav_menus' => true), 'object');
        $exclude_cpts = array('elementor_library', 'attachment');

        foreach ($exclude_cpts as $exclude_cpt) {
            unset($cpts[$exclude_cpt]);
        }
        $post_types = array_merge($cpts);
        foreach ($post_types as $type) {
            $types[$type->name] = $type->label;
        }
        return $types;
    }
    
    public function get_categories() {
		$cat_arr = get_categories();
		$categoryNameArray = [];

		foreach ($cat_arr as $category) {
		  $categoryNameArray[$category->slug] = $category->name;
		}
		return $categoryNameArray;
    }
    
    public function get_tags() {
        $posttags = get_tags(array('hide_empty' => false));
        
        $categoryTagArray= [];
        
		foreach($posttags as $tag){
			$categoryTagArray[$tag->slug] = $tag->name;
        }
        
		return $categoryTagArray;
	}

	protected function _register_controls() {
        $this->addcontent();
        $this->addlicense();
		$this->addstyle();
    }

    protected function get_license($key) {
        if ($this->config == null) {
            $defaults = array(
                'license' => 'false',
                'license_key' => ''
            );
			$this->config = array_merge($defaults, get_option('ehe_config', array()));
			$this->config['license_key'] = (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $this->config['license_key']) !== 1) ? '' : substr_replace($this->config['license_key'], '-xxxx-xxxx-xxxx-xxxxxxxxxxxx', 8);
        }
        return $this->config[$key];
    }

    protected function addlicense() {
        $this->start_controls_section(
			'license_section',
			[
				'label' => __( 'License', 'plugin-name' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
        );

        $this->add_control(
			'license',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => is_admin() ? $this->get_license('license') : 'regular',
			]
		);

        $this->add_control(
			'license_intro',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __('<p style="line-height:1.3;">Enter your purchase code below to activate your addon.</p><br><p style="line-height:1.3;">Activating the plugin unlocks additional settings, automatic future updates, and support from developers.</p>', 'plugin-name')
			]
        );

        $this->add_control(
			'license_link',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __('<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">Where is my purchase code?</a><br><br><a href="https://codecanyon.net/item/emage-image-hover-effects-for-elementor-page-builder/22563091" target="_blank">Buy a new license</a>', 'plugin-name')
			]
        );

        $this->add_control(
			'license_purchase_code',
			[
                'label' => __( 'Purchase Code', 'plugin-domain' ),
                'label_block' => true,
				'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', 'plugin-domain' ),
                'default' => $this->get_license('license_key')
			]
        );

        $this->add_control(
			'license_notice',
			[
				'show_label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __('', 'plugin-domain'),
			]
        );

        $this->add_control(
            'license_activate_btn',
            [
                'label' => __('Status: <b>Inactive</b>', 'plugin-name'),
                'type' => Controls_Manager::BUTTON,
                'separator' => 'before',
                'button_type' => 'success',
                'text' => '&nbsp;&nbsp;<span class="elementor-state-icon">&nbsp;<i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i></span><span class="publish-label">Activate</span>&nbsp;&nbsp;',
                'event' => 'emage:editor:activate',
                'condition' => [
					'license' => 'false'
				]
            ]
        );

        $this->add_control(
            'license_deactivate_btn',
            [
                'label' => __('Status: <b>Active</b>', 'plugin-name'),
                'type' => Controls_Manager::BUTTON,
                'separator' => 'before',
                'button_type' => 'default',
                'text' => __('&nbsp;&nbsp;<span class="elementor-state-icon">&nbsp;<i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i></span><span class="publish-label">Deactivate</span>&nbsp;&nbsp;', 'plugin-domain'),
                'event' => 'emage:editor:deactivate',
                'condition' => [
					'license!' => 'false'
				]
            ]
        );
        
        $this->end_controls_section();
    }
    
    public function addcontent() {

        $this->start_controls_section(
			'posts_section',
			[
				'label' => __( 'Posts', 'plugin-name' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
					'license!' => 'false'
				]
			]
        );

        $this->add_control(
			'posts',
			[
                'label' => __('No. of Posts', 'plugin-domain' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 30,
				'step' => 1,
				'default' => 3,
			]
        );

        $this->add_control(
			'post_offset',
			[
                'label' => __('Offset', 'plugin-domain' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 30,
				'step' => 1,
				'default' => 0,
			]
        );

        $this->add_responsive_control(
			'columns',
			[
                'label' => __('Columns', 'plugin-domain' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 6,
				'step' => 1,
				'default' => 3,
			]
        );

        $this->add_control(
			'post_type',
			[
                'label' => __( 'Post Type', 'plugin-domain' ),
                'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->get_post_types(),
			]
        );

        $this->add_control(
			'orderby',
			[
                'label' => __('Order By', 'plugin-domain'),
                'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'none' => __('None', 'plugin-domain'),
					'ID' => __('Post ID', 'plugin-domain'),
					'author' => __('Post Author', 'plugin-domain'),
					'title' => __('Title', 'plugin-domain'),
					'type' => __('Type', 'plugin-domain'),
					'date' => __('Date', 'plugin-domain'),
					'modified' => __('Last Modified', 'plugin-domain'),
					'rand' => __('Random', 'plugin-domain'),
					'comment_count' => __('Comment Count', 'plugin-domain'),
					'menu_order' => __('Menu Order', 'plugin-domain'),
				],
			]
        );
        
        $this->add_control(
			'order',
			[
                'label' => __('Order', 'plugin-domain'),
                'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' => __('Descending', 'plugin-domain'),
					'ASC' => __('Ascending', 'plugin-domain'),
				],
			]
		);
        
        $this->add_control(
			'categories',
			[
				'label' => __('Categories', 'plugin-domain'),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_categories(),
				'label_block' => true,
			]
        );
        
        $this->add_control(
			'tags',
			[
				'label' => __( 'Tags', 'plugin-domain' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_tags(),
				'label_block' => true,
			]
        );

        $this->add_control(
			'target',
			[
                'label' => __('Link To', 'plugin-domain'),
                'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
                    'none' => __('None', 'plugin-domain'),
                    'title' => __('Title', 'plugin-domain'),
					'image' => __('Image', 'plugin-domain'),
				]
			]
		);
        
        $this->end_controls_section();

        $this->start_controls_section(
			'image_section',
			[
				'label' => __( 'Image', 'plugin-name' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
					'license!' => 'false'
				]
			]
        );

        $this->add_control(
			'image',
			[
				'label' => __('Choose Fallback Image', 'plugin-domain'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src()
                ]
			]
		);

        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
                'name' => 'image',
				'exclude' => [],
				'include' => [],
                'default' => 'large'
			]
        );

        $this->add_control(
			'image_effect',
			[
				'label' => __( 'Hover Effect', 'plugin-domain' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => 'imghvr-anim-zoom-in',
				'options' => [
					'imghvr-anim-none'  => __( 'None', 'plugin-domain' ),
					'imghvr-anim-grayscale'  => __( 'Grayscale', 'plugin-domain' ),
					'imghvr-anim-color'  => __( 'Color', 'plugin-domain' ),
                    'imghvr-anim-dive'  => __( 'Dive', 'plugin-domain' ),
                    'imghvr-anim-scroll|imghvr-padding'  => __( 'Scroll', 'plugin-domain' ),
					'imghvr-anim-zoom-in' => __('Zoom In', 'plugin-domain'),
					'imghvr-anim-zoom-out' => __('Zoom Out', 'plugin-domain'),
					'imghvr-anim-zoom-in-out' => __('Zoom In Out', 'plugin-domain'),
					'imghvr-anim-zoom-out-in' => __('Zoom Out In', 'plugin-domain'),
					'imghvr-anim-zoom-in imghvr-anim-blur'  => __( 'Zoom In Blur', 'plugin-domain' ),
					'imghvr-anim-zoom-out imghvr-anim-blur'  => __( 'Zoom Out Blur', 'plugin-domain' ),
					'imghvr-anim-rotate'  => __( 'Rotate', 'plugin-domain' ),
					'imghvr-anim-blur'  => __( 'Blur', 'plugin-domain' ),
					'imghvr-anim-scale-rotate-left'  => __( 'Scale Rotate Left', 'plugin-domain' ),
					'imghvr-anim-scale-rotate-right'  => __( 'Scale Rotate Right', 'plugin-domain' ),
					'imghvr-anim-move imghvr-anim-move-up'  => __( 'Move Up', 'plugin-domain' ),
					'imghvr-anim-move imghvr-anim-move-down'  => __( 'Move Down', 'plugin-domain' ),
					'imghvr-anim-move imghvr-anim-move-left'  => __( 'Move Left', 'plugin-domain' ),
					'imghvr-anim-move imghvr-anim-move-right'  => __( 'Move Right', 'plugin-domain' ),
					'imghvr-anim-slide-out imghvr-anim-slide-out-up'  => __( 'Slide Up', 'plugin-domain' ),
					'imghvr-anim-slide-out imghvr-anim-slide-out-down'  => __( 'Slide Down', 'plugin-domain' ),
					'imghvr-anim-slide-out imghvr-anim-slide-out-left'  => __( 'Slide Left', 'plugin-domain' ),
					'imghvr-anim-slide-out imghvr-anim-slide-out-right'  => __( 'Slide Right', 'plugin-domain' ),
					'imghvr-anim-hinge imghvr-anim-hinge-up|imghvr-perspective'  => __( 'Hinge Up', 'plugin-domain' ),
					'imghvr-anim-hinge imghvr-anim-hinge-down|imghvr-perspective'  => __( 'Hinge Down', 'plugin-domain' ),
					'imghvr-anim-hinge imghvr-anim-hinge-left|imghvr-perspective'  => __( 'Hinge Left', 'plugin-domain' ),
					'imghvr-anim-hinge imghvr-anim-hinge-right|imghvr-perspective'  => __( 'Hinge Right', 'plugin-domain' ),
					'imghvr-anim-flip imghvr-anim-flip-hor'  => __( 'Flip Horizontal', 'plugin-domain' ),
					'imghvr-anim-flip imghvr-anim-flip-vert'  => __( 'Flip Vertical', 'plugin-domain' ),
					'imghvr-anim-flip imghvr-anim-flip-diag-left'  => __( 'Flip Diagonal Left', 'plugin-domain' ),
					'imghvr-anim-flip imghvr-anim-flip-diag-right'  => __( 'Flip Diagonal Right', 'plugin-domain' ),
					'imghvr-anim-fold imghvr-anim-fold-up'  => __( 'Fold Up', 'plugin-domain' ),
					'imghvr-anim-fold imghvr-anim-fold-down'  => __( 'Fold Down', 'plugin-domain' ),
					'imghvr-anim-fold imghvr-anim-fold-left'  => __( 'Fold Left', 'plugin-domain' ),
					'imghvr-anim-fold imghvr-anim-fold-right'  => __( 'Fold Right', 'plugin-domain' ),
					'imghvr-anim-zoom-out-slide imghvr-anim-zoom-out-slide-up'  => __( 'Zoom Out Up', 'plugin-domain' ),
					'imghvr-anim-zoom-out-slide imghvr-anim-zoom-out-slide-down'  => __( 'Zoom Out Down', 'plugin-domain' ),
					'imghvr-anim-zoom-out-slide imghvr-anim-zoom-out-slide-left'  => __( 'Zoom Out Left', 'plugin-domain' ),
					'imghvr-anim-zoom-out-slide imghvr-anim-zoom-out-slide-right'  => __( 'Zoom Out Right', 'plugin-domain' ),
					'imghvr-anim-zoom-out-flip imghvr-anim-zoom-out-flip-hor'  => __( 'Zoom Out Flip Horizontal', 'plugin-domain' ),
					'imghvr-anim-zoom-out-flip imghvr-anim-zoom-out-flip-vert'  => __( 'Zoom Out Flip Vetical', 'plugin-domain' ),
					'imghvr-anim-pivot-out imghvr-anim-pivot-out-top-left'  => __( 'Pivot Top Left', 'plugin-domain' ),
					'imghvr-anim-pivot-out imghvr-anim-pivot-out-top-right'  => __( 'Pivot Top Right', 'plugin-domain' ),
					'imghvr-anim-pivot-out imghvr-anim-pivot-out-bottom-left'  => __( 'Pivot Bottom Left', 'plugin-domain' ),
					'imghvr-anim-pivot-out imghvr-anim-pivot-out-bottom-right'  => __( 'Pivot Bottom Right', 'plugin-domain' ),
					'imghvr-anim-rotate-around'  => __( 'Rotate Around', 'plugin-domain' ),
					'imghvr-anim-lightspeed imghvr-anim-lightspeed-out-left'  => __( 'Light Speed Out Left', 'plugin-domain' ),		
					'imghvr-anim-lightspeed imghvr-anim-lightspeed-out-right'  => __( 'Light Speed Out Right', 'plugin-domain' ),
					'imghvr-anim-fall imghvr-anim-fall-away-horizontal'  => __( 'Fall Away Horizontal', 'plugin-domain' ),	
					'imghvr-anim-fall imghvr-anim-fall-away-vertical'  => __( 'Fall Away Vertical', 'plugin-domain' ),	
					'imghvr-anim-fall imghvr-anim-fall-away-rotate'  => __( 'Fall Away Rotate', 'plugin-domain' ),
					'imghvr-anim-fall imghvr-anim-fall-away-rotate-invert'  => __( 'Fall Away Rotate Invert', 'plugin-domain' ),
					'imghvr-anim-throw-out imghvr-anim-throw-out-up' => __('Throw Up', 'plugin-domain'),
					'imghvr-anim-throw-out imghvr-anim-throw-out-down' => __('Throw Down', 'plugin-domain'),
					'imghvr-anim-throw-out imghvr-anim-throw-out-left' => __('Throw Left', 'plugin-domain'),
					'imghvr-anim-throw-out imghvr-anim-throw-out-right' => __('Throw Right', 'plugin-domain'),
					'imghvr-anim-cube-out imghvr-anim-cube-out-up|imghvr-perspective imghvr-overflow' => __('Cube Up', 'plugin-domain'),
					'imghvr-anim-cube-out imghvr-anim-cube-out-down|imghvr-perspective imghvr-overflow' => __('Cube Down', 'plugin-domain'),
					'imghvr-anim-cube-out imghvr-anim-cube-out-right|imghvr-perspective imghvr-overflow' => __('Cube Left', 'plugin-domain'),
					'imghvr-anim-cube-out imghvr-anim-cube-out-left|imghvr-perspective imghvr-overflow' => __('Cube Right', 'plugin-domain'),
					'imghvr-anim-stack' => __('Stack', 'plugin-domain'),
					'imghvr-anim-bounce-out' => __('Bounce Out', 'plugin-domain'),
					'imghvr-anim-bounce-out-up' => __('Bounce Out Up', 'plugin-domain'),
					'imghvr-anim-bounce-out-down' => __('Bounce Out Down', 'plugin-domain'),
					'imghvr-anim-bounce-out-left' => __('Bounce Out Left', 'plugin-domain'),
					'imghvr-anim-bounce-out-right' => __('Bounce Out Right', 'plugin-domain'),
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => __( 'Opacity', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .imghvr-anim-color' => 'opacity: {{SIZE}};',
					'{{WRAPPER}} .imghvr:hover .imghvr-anim-color' => 'opacity: 1;',
				],
				'condition' => [
					'image_effect' => 'imghvr-anim-color'
				]
			]
		);

		$this->add_control(
			'image_duration',
			[
				'label' => __( 'Transition Duration', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.05,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.35,
				],
				'selectors' => [
					'{{WRAPPER}} .imghvr, {{WRAPPER}} .imghvr img' => 'transition: {{SIZE}}s; animation-duration: {{SIZE}}s;'
				]
			]
        );
        
        $this->add_control(
			'image_hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
        
        $this->end_controls_section();

        $this->start_controls_section(
			'overlay_section',
			[
				'label' => __( 'Overlay', 'plugin-name' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
					'license!' => 'false'
				]
			]
		);

		$this->add_control(
			'overlay_show',
			[
				'label' => __( 'Show Overlay', 'plugin-domain' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => "1",
				'default' => "1"
			]
		);

		$this->add_control(
			'overlay_effect',
			[
				'label' => __( 'Hover Effect', 'plugin-domain' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => 'imghvr-anim-fade-in imghvr-anim-single',
				'options' => [
					'imghvr-anim-none imghvr-anim-single'  => __( '-- Show Always --', 'plugin-domain' ),
					'imghvr-anim-fade-in imghvr-anim-single'  => __( 'Fade', 'plugin-domain' ),
					'imghvr-anim-zoom-in-alt imghvr-anim-single'  => __( 'Zoom In', 'plugin-domain' ),
					'imghvr-anim-crop imghvr-anim-single'  => __( 'Crop', 'plugin-domain' ),
					'imghvr-anim-none imghvr-anim-bg-none imghvr-anim-single'  => __( 'No Background', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-single imghvr-anim-slide-in-up'  => __( 'Slide Up', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-single imghvr-anim-slide-in-down'  => __( 'Slide Down', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-single imghvr-anim-slide-in-left'  => __( 'Slide Left', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-single imghvr-anim-slide-in-right'  => __( 'Slide Right', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-single imghvr-anim-slide-in-top-left'  => __( 'Slide Top Left', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-single imghvr-anim-slide-in-top-right'  => __( 'Slide Top Right', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-single imghvr-anim-slide-in-bottom-left'  => __( 'Slide Bottom Left', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-single imghvr-anim-slide-in-bottom-right'  => __( 'Slide Bottom Right', 'plugin-domain' ),
					'imghvr-anim-shutter-out imghvr-anim-single imghvr-anim-shutter-out-hor'  => __( 'Shutter Out Horizontal', 'plugin-domain' ),
					'imghvr-anim-shutter-out imghvr-anim-single imghvr-anim-shutter-out-vert'  => __( 'Shutter Out Vertical', 'plugin-domain' ),
					'imghvr-anim-shutter-out imghvr-anim-single imghvr-anim-shutter-out-diag-left'  => __( 'Shutter Out Diagonal Left', 'plugin-domain' ),
					'imghvr-anim-shutter-out imghvr-anim-single imghvr-anim-shutter-out-diag-right'  => __( 'Shutter Out Diagonal Right', 'plugin-domain' ),
					'imghvr-anim-shutter-in imghvr-anim-pseudo imghvr-anim-shutter-in-hor'  => __( 'Shutter In Horizontal', 'plugin-domain' ),
					'imghvr-anim-shutter-in imghvr-anim-pseudo imghvr-anim-shutter-in-vert'  => __( 'Shutter In Vertical', 'plugin-domain' ),
					'imghvr-anim-shutter-in-out imghvr-anim-pseudo imghvr-anim-shutter-in-out-hor'  => __( 'Shutter In Out Horizontal', 'plugin-domain' ),
					'imghvr-anim-shutter-in-out imghvr-anim-pseudo imghvr-anim-shutter-in-out-vert'  => __( 'Shutter In Out Vertical', 'plugin-domain' ),
					'imghvr-anim-shutter-in-out imghvr-anim-pseudo imghvr-anim-shutter-in-out-diag-left'  => __( 'Shutter In Out Diagonal Left', 'plugin-domain' ),
					'imghvr-anim-shutter-in-out imghvr-anim-pseudo imghvr-anim-shutter-in-out-diag-right'  => __( 'Shutter In Out Diagonal Right', 'plugin-domain' ),
					'imghvr-anim-strip-shutter imghvr-anim-pseudo imghvr-anim-strip-shutter-up'  => __( 'Strip Shutter Up', 'plugin-domain' ),
					'imghvr-anim-strip-shutter imghvr-anim-pseudo imghvr-anim-strip-shutter-down'  => __( 'Strip Shutter Down', 'plugin-domain' ),
					'imghvr-anim-strip-shutter imghvr-anim-pseudo imghvr-anim-strip-shutter-left'  => __( 'Strip Shutter Left', 'plugin-domain' ),
					'imghvr-anim-strip-shutter imghvr-anim-pseudo imghvr-anim-strip-shutter-right'  => __( 'Strip Shutter Right', 'plugin-domain' ),
					'imghvr-anim-strip-hor imghvr-anim-pseudo imghvr-anim-strip-hor-up'  => __( 'Strip Horizontal Up', 'plugin-domain' ),
					'imghvr-anim-strip-hor imghvr-anim-pseudo imghvr-anim-strip-hor-down'  => __( 'Strip Horizontal Down', 'plugin-domain' ),
					'imghvr-anim-strip-hor imghvr-anim-pseudo imghvr-anim-strip-hor-top-left'  => __( 'Strip Horizontal Top Left', 'plugin-domain' ),
					'imghvr-anim-strip-hor imghvr-anim-pseudo imghvr-anim-strip-hor-top-right'  => __( 'Strip Horizontal Top Right', 'plugin-domain' ),
					'imghvr-anim-strip-hor imghvr-anim-pseudo imghvr-anim-strip-hor-bottom-left'  => __( 'Strip Horizontal Bottom Left', 'plugin-domain' ),
					'imghvr-anim-strip-hor imghvr-anim-pseudo imghvr-anim-strip-hor-bottom-right'  => __( 'Strip Horizontal Bottom Right', 'plugin-domain' ),
					'imghvr-anim-strip-vert imghvr-anim-pseudo imghvr-anim-strip-vert-left'  => __( 'Strip Vertical Left', 'plugin-domain' ),
					'imghvr-anim-strip-vert imghvr-anim-pseudo imghvr-anim-strip-vert-right'  => __( 'Strip Vertical Right', 'plugin-domain' ),
					'imghvr-anim-strip-vert imghvr-anim-pseudo imghvr-anim-strip-vert-top-left'  => __( 'Strip Vertical Top Left', 'plugin-domain' ),
					'imghvr-anim-strip-vert imghvr-anim-pseudo imghvr-anim-strip-vert-top-right'  => __( 'Strip Vertical Top Right', 'plugin-domain' ),
					'imghvr-anim-strip-vert imghvr-anim-pseudo imghvr-anim-strip-vert-bottom-left'  => __( 'Strip Vertical Bottom Left', 'plugin-domain' ),
					'imghvr-anim-strip-vert imghvr-anim-pseudo imghvr-anim-strip-vert-bottom-right'  => __( 'Strip Vertical Bottom Right', 'plugin-domain' ),
					'imghvr-anim-pixel imghvr-anim-pseudo imghvr-anim-pixel-up'  => __( 'Pixel Up', 'plugin-domain' ),
					'imghvr-anim-pixel imghvr-anim-pseudo imghvr-anim-pixel-down'  => __( 'Pixel Down', 'plugin-domain' ),
					'imghvr-anim-pixel imghvr-anim-pseudo imghvr-anim-pixel-left'  => __( 'Pixel Left', 'plugin-domain' ),
					'imghvr-anim-pixel imghvr-anim-pseudo imghvr-anim-pixel-right'  => __( 'Pixel Right', 'plugin-domain' ),
					'imghvr-anim-pixel imghvr-anim-pseudo imghvr-anim-pixel-top-left'  => __( 'Pixel Top Left', 'plugin-domain' ),
					'imghvr-anim-pixel imghvr-anim-pseudo imghvr-anim-pixel-top-right'  => __( 'Pixel Top Right', 'plugin-domain' ),
					'imghvr-anim-pixel imghvr-anim-pseudo imghvr-anim-pixel-bottom-left'  => __( 'Pixel Bottom Left', 'plugin-domain' ),
					'imghvr-anim-pixel imghvr-anim-pseudo imghvr-anim-pixel-bottom-right'  => __( 'Pixel Bottom Right', 'plugin-domain' ),
					'imghvr-anim-pivot-in imghvr-anim-single imghvr-anim-pivot-in-top-left'  => __( 'Pivot Top Left', 'plugin-domain' ),
					'imghvr-anim-pivot-in imghvr-anim-single imghvr-anim-pivot-in-top-right'  => __( 'Pivot Top Right', 'plugin-domain' ),
					'imghvr-anim-pivot-in imghvr-anim-single imghvr-anim-pivot-in-bottom-left'  => __( 'Pivot Bottom Left', 'plugin-domain' ),
					'imghvr-anim-pivot-in imghvr-anim-single imghvr-anim-pivot-in-bottom-right'  => __( 'Pivot Bottom Right', 'plugin-domain' ),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-rotate-left' => __('Blocks Rotate Left', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-rotate-right' => __('Blocks Rotate Right', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-rotate-in-left' => __('Blocks Rotate In Left', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-rotate-in-right' => __('Blocks Rotate In Right', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-in' => __('Blocks In', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-out' => __('Blocks Out', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-float-up' => __('Blocks Float Up', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-float-down' => __('Blocks Float Down', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-float-left' => __('Blocks Float Left', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-float-right' => __('Blocks Float Right', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-zoom-top-left' => __('Blocks Zoom Top Left', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-zoom-top-right' => __('Blocks Zoom Top Right', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-zoom-bottom-left' => __('Blocks Zoom Bottom Left', 'plugin-domain'),
					'imghvr-anim-blocks imghvr-anim-pseudo imghvr-anim-blocks-zoom-bottom-right' => __('Blocks Zoom Bottom Right', 'plugin-domain'),
					'imghvr-anim-throw-in imghvr-anim-single imghvr-anim-throw-in-up' => __('Throw Up', 'plugin-domain'),
					'imghvr-anim-throw-in imghvr-anim-single imghvr-anim-throw-in-down' => __('Throw Down', 'plugin-domain'),
					'imghvr-anim-throw-in imghvr-anim-single imghvr-anim-throw-in-left' => __('Throw Left', 'plugin-domain'),
					'imghvr-anim-throw-in imghvr-anim-single imghvr-anim-throw-in-right' => __('Throw Right', 'plugin-domain'),
					'imghvr-anim-flash imghvr-anim-pseudo imghvr-anim-flash-top-left' => __('Flash Top Left', 'plugin-domain'),
					'imghvr-anim-flash imghvr-anim-pseudo imghvr-anim-flash-top-right' => __('Flash Top Right', 'plugin-domain'),
					'imghvr-anim-flash imghvr-anim-pseudo imghvr-anim-flash-bottom-left' => __('Flash Bottom Left', 'plugin-domain'),
					'imghvr-anim-flash imghvr-anim-pseudo imghvr-anim-flash-bottom-right' => __('Flash Bottom Right', 'plugin-domain'),
					'imghvr-anim-splash imghvr-anim-pseudo imghvr-anim-splash-up' => __('Splash Up', 'plugin-domain'),
					'imghvr-anim-splash imghvr-anim-pseudo imghvr-anim-splash-down' => __('Splash Down', 'plugin-domain'),
					'imghvr-anim-splash imghvr-anim-pseudo imghvr-anim-splash-left' => __('Splash Left', 'plugin-domain'),
					'imghvr-anim-splash imghvr-anim-pseudo imghvr-anim-splash-right' => __('Splash Right', 'plugin-domain'),
					'imghvr-anim-stack imghvr-anim-single imghvr-anim-stack-up' => __('Stack Up', 'plugin-domain'),
					'imghvr-anim-stack imghvr-anim-single imghvr-anim-stack-down' => __('Stack Down', 'plugin-domain'),
					'imghvr-anim-stack imghvr-anim-single imghvr-anim-stack-left' => __('Stack Left', 'plugin-domain'),
					'imghvr-anim-stack imghvr-anim-single imghvr-anim-stack-right' => __('Stack Right', 'plugin-domain'),
					'imghvr-anim-circle imghvr-anim-pseudo imghvr-anim-circle-up' => __('Circle Up', 'plugin-domain'),
					'imghvr-anim-circle imghvr-anim-pseudo imghvr-anim-circle-down' => __('Circle Down', 'plugin-domain'),
					'imghvr-anim-circle imghvr-anim-pseudo imghvr-anim-circle-left' => __('Circle Left', 'plugin-domain'),
					'imghvr-anim-circle imghvr-anim-pseudo imghvr-anim-circle-right' => __('Circle Right', 'plugin-domain'),
					'imghvr-anim-circle imghvr-anim-pseudo imghvr-anim-circle-top-left' => __('Circle Top Left', 'plugin-domain'),
					'imghvr-anim-circle imghvr-anim-pseudo imghvr-anim-circle-top-right' => __('Circle Top Right', 'plugin-domain'),
					'imghvr-anim-circle imghvr-anim-pseudo imghvr-anim-circle-bottom-left' => __('Circle Bottom Left', 'plugin-domain'),
					'imghvr-anim-circle imghvr-anim-pseudo imghvr-anim-circle-bottom-right' => __('Circle Bottom Right', 'plugin-domain'),
					'imghvr-anim-book imghvr-anim-pseudo imghvr-anim-book-open-horiz|imghvr-perspective imghvr-overflow' => __('Book Open Horizontal', 'plugin-domain'),
					'imghvr-anim-book imghvr-anim-pseudo imghvr-anim-book-open-vert|imghvr-perspective imghvr-overflow' => __('Book Open Vertical', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo' => __('Border Reveal', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-horiz' => __('Border Reveal Horizontal', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-vert' => __('Border Reveal Vertical', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-corners-2' => __('Border Reveal Diagonal Left', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-corners-1' => __('Border Reveal Diagonal Right', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-top-left' => __('Border Reveal Top Left', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-top-right' => __('Border Reveal Top Right', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-bottom-left' => __('Border Reveal Bottom Left', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-bottom-right' => __('Border Reveal Bottom Right', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-cc-1' => __('Border Reveal Clockwise', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-ccc-1' => __('Border Reveal Anti Clockwise', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-cc-2' => __('Border Reveal Split Clockwise', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-ccc-2' => __('Border Reveal Split Anti Clockwise', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-cc-3' => __('Border Reveal Attach Clockwise', 'plugin-domain'),
					'imghvr-anim-border-reveal imghvr-anim-pseudo imghvr-anim-border-reveal-ccc-3' => __('Border Reveal Attach Anti Clockwise', 'plugin-domain'),
					'imghvr-anim-blinds imghvr-anim-pseudo imghvr-anim-blinds-horiz' => __('Blinds Horizontal', 'plugin-domain'),
					'imghvr-anim-blinds imghvr-anim-pseudo imghvr-anim-blinds-vert' => __('Blinds Vertical', 'plugin-domain'),
					'imghvr-anim-blinds imghvr-anim-pseudo imghvr-anim-blinds-up' => __('Blinds Up', 'plugin-domain'),
					'imghvr-anim-blinds imghvr-anim-pseudo imghvr-anim-blinds-down' => __('Blinds Down', 'plugin-domain'),
					'imghvr-anim-blinds imghvr-anim-pseudo imghvr-anim-blinds-left' => __('Blinds Left', 'plugin-domain'),
					'imghvr-anim-blinds imghvr-anim-pseudo imghvr-anim-blinds-right' => __('Blinds Right', 'plugin-domain'),
				],
				'condition' => [
					'overlay_show' => "1"
				]
			]
		);

		$this->add_control(
			'overlay_duration',
			[
				'label' => __( 'Transition Duration', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.05,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.35,
				],
				'selectors' => [
					'{{WRAPPER}} .imghvr-overlay, {{WRAPPER}} .imghvr-anim-pseudo:before, {{WRAPPER}} .imghvr-anim-pseudo:after, {{WRAPPER}} .imghvr-anim-pseudo div:before, {{WRAPPER}} .imghvr-anim-pseudo div:after' => 'transition-duration: {{SIZE}}s; animation-duration: {{SIZE}}s;',
					'{{WRAPPER}} .imghvr:hover .imghvr-overlay, {{WRAPPER}} .imghvr:hover .imghvr-anim-pseudo:before, {{WRAPPER}} .imghvr:hover .imghvr-anim-pseudo:after, {{WRAPPER}} .imghvr:hover .imghvr-anim-pseudo div:before, {{WRAPPER}} .imghvr:hover .imghvr-anim-pseudo div:after' => 'transition-duration: {{SIZE}}s; animation-duration: {{SIZE}}s;'
				],
				'condition' => [
					'overlay_show' => "1"
				]
			]
		);

        $this->end_controls_section();
        
        $this->start_controls_section(
			'content_section',
			[
				'label' => __('Content', 'plugin-name'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
					'license!' => 'false'
				]
			]
        );
        
        $this->add_control(
			'title',
			[
				'label' => __('Show Title', 'plugin-domain'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => "1",
				'default' => "1",
			]
        );
        
        $this->add_control(
			'meta',
			[
				'label' => __('Show Meta', 'plugin-domain'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => "1",
				'default' => "1",
			]
        );
        
        $this->add_control(
			'author',
			[
				'label' => __('Show Author', 'plugin-domain'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => "1",
                'default' => "1",
                'condition' => [
					'meta' => "1"
				]
			]
        );
        
        $this->add_control(
			'date',
			[
				'label' => __('Show Date', 'plugin-domain'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => "1",
                'default' => "1",
                'condition' => [
					'meta' => "1"
				]
			]
        );

        $this->add_control(
			'comments',
			[
				'label' => __('Show Comments', 'plugin-domain'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => "1",
                'default' => "1",
                'condition' => [
					'meta' => "1"
				]
			]
        );

        $this->add_control(
			'icons',
			[
				'label' => __('Show Icons', 'plugin-domain'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => "1",
                'default' => "1"
			]
        );

        $this->add_control(
			'show_categories',
			[
				'label' => __('Show Categories', 'plugin-domain'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => "1",
                'default' => "1"
			]
        );

        $this->add_control(
			'excerpt',
			[
				'label' => __('Show Excerpt', 'plugin-domain'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => "1",
				'default' => "1",
			]
        );

        $this->add_control(
			'excerpt_length',
			[
                'label' => __('Excerpt Length', 'plugin-domain' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 200,
				'step' => 1,
                'default' => 20,
                'condition' => [
					'excerpt' => "1"
				]
			]
        );

        $this->add_control(
			'content_hr2',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'align',
			[
				'label' => __( 'Alignment', 'plugin-domain' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'plugin-domain' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'plugin-domain' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'plugin-domain' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'center',
				'toggle' => false
			]
		);

		$this->add_control(
			'valign',
			[
				'label' => __( 'Vertical Alignment', 'plugin-domain' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Top', 'plugin-domain' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Middle', 'plugin-domain' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => __( 'Bottom', 'plugin-domain' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .imghvr .imghvr-content-wrapper' => 'justify-content: {{VALUE}};'
				]
			]
        );
        
        $this->add_control(
			'sort_element',
			[
				'label' => 'Reorder Elements',
				'type' => 'html5sortable',
				'options' => [
					'title' => __( 'Post Title', 'plugin-domain' ),
					'meta' => __( 'Post Meta', 'plugin-domain' ),
					'excerpt' => __( 'Post Excerpt', 'plugin-domain' ),
					'categories' => __( 'Post Categories', 'plugin-domain' ),
				],
				'default' => ['categories', 'title', 'meta', 'excerpt']
			]
		);

		$this->add_control(
			'content_hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'content_effect',
			[
				'label' => __( 'Hover Effect', 'plugin-domain' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => 'imghvr-anim-fade-in',
				'options' => [
					'imghvr-anim-none'  => __( '-- Show Always --', 'plugin-domain' ),
					'imghvr-anim-fade-in'  => __( 'Fade', 'plugin-domain' ),
					'imghvr-anim-zoom-in-alt'  => __( 'Zoom In', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-slide-in-up'  => __( 'Slide Up', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-slide-in-down'  => __( 'Slide Down', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-slide-in-left'  => __( 'Slide Left', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-slide-in-right'  => __( 'Slide Right', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-slide-in-top-left'  => __( 'Slide Top Left', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-slide-in-top-right'  => __( 'Slide Top Right', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-slide-in-bottom-left'  => __( 'Slide Bottom Left', 'plugin-domain' ),
					'imghvr-anim-slide-in imghvr-anim-slide-in-bottom-right'  => __( 'Slide Bottom Right', 'plugin-domain' ),
					'imghvr-anim-fade imghvr-anim-fade-up'  => __( 'Fade Up', 'plugin-domain' ),
					'imghvr-anim-fade imghvr-anim-fade-down'  => __( 'Fade Down', 'plugin-domain' ),
					'imghvr-anim-fade imghvr-anim-fade-left'  => __( 'Fade Left', 'plugin-domain' ),
					'imghvr-anim-fade imghvr-anim-fade-right'  => __( 'Fade Right', 'plugin-domain' ),
					'imghvr-anim-hinge imghvr-anim-hinge-up|imghvr-perspective'  => __( 'Hinge Up', 'plugin-domain' ),
					'imghvr-anim-hinge imghvr-anim-hinge-down|imghvr-perspective'  => __( 'Hinge Down', 'plugin-domain' ),
					'imghvr-anim-hinge imghvr-anim-hinge-left|imghvr-perspective'  => __( 'Hinge Left', 'plugin-domain' ),
					'imghvr-anim-hinge imghvr-anim-hinge-right|imghvr-perspective'  => __( 'Hinge Right', 'plugin-domain' ),
					'imghvr-anim-flip imghvr-anim-flip-hor'  => __( 'Flip Horizontal', 'plugin-domain' ),
					'imghvr-anim-flip imghvr-anim-flip-vert'  => __( 'Flip Vertical', 'plugin-domain' ),
					'imghvr-anim-flip imghvr-anim-flip-diag-left'  => __( 'Flip Diagonal Left', 'plugin-domain' ),
					'imghvr-anim-flip imghvr-anim-flip-diag-right'  => __( 'Flip Diagonal Right', 'plugin-domain' ),
					'imghvr-anim-fold imghvr-anim-fold-down'  => __( 'Fold Up', 'plugin-domain' ),
					'imghvr-anim-fold imghvr-anim-fold-up'  => __( 'Fold Down', 'plugin-domain' ),
					'imghvr-anim-fold imghvr-anim-fold-left'  => __( 'Fold Left', 'plugin-domain' ),
					'imghvr-anim-fold imghvr-anim-fold-right'  => __( 'Fold Right', 'plugin-domain' ),
					'imghvr-anim-zoom-in-flip imghvr-anim-zoom-in-flip-hor'  => __( 'Zoom In Flip Horizontal', 'plugin-domain' ),
					'imghvr-anim-zoom-in-flip imghvr-anim-zoom-in-flip-vert'  => __( 'Zoom In Flip Vetical', 'plugin-domain' ),
					'imghvr-anim-pivot-in imghvr-anim-pivot-in-top-left'  => __( 'Pivot Top Left', 'plugin-domain' ),
					'imghvr-anim-pivot-in imghvr-anim-pivot-in-top-right'  => __( 'Pivot Top Right', 'plugin-domain' ),
					'imghvr-anim-pivot-in imghvr-anim-pivot-in-bottom-left'  => __( 'Pivot Bottom Left', 'plugin-domain' ),
					'imghvr-anim-pivot-in imghvr-anim-pivot-in-bottom-right'  => __( 'Pivot Bottom Right', 'plugin-domain' ),
					'imghvr-anim-throw-in imghvr-anim-throw-in-up' => __('Throw Up', 'plugin-domain'),
					'imghvr-anim-throw-in imghvr-anim-throw-in-down' => __('Throw Down', 'plugin-domain'),
					'imghvr-anim-throw-in imghvr-anim-throw-in-left' => __('Throw Left', 'plugin-domain'),
					'imghvr-anim-throw-in imghvr-anim-throw-in-right' => __('Throw Right', 'plugin-domain'),
					'imghvr-anim-cube-in imghvr-anim-cube-in-up|imghvr-perspective imghvr-overflow' => __('Cube Up', 'plugin-domain'),
					'imghvr-anim-cube-in imghvr-anim-cube-in-down|imghvr-perspective imghvr-overflow' => __('Cube Down', 'plugin-domain'),
					'imghvr-anim-cube-in imghvr-anim-cube-in-right|imghvr-perspective imghvr-overflow' => __('Cube Left', 'plugin-domain'),
					'imghvr-anim-cube-in imghvr-anim-cube-in-left|imghvr-perspective imghvr-overflow' => __('Cube Right', 'plugin-domain'),
					'imghvr-anim-lightspeed-in imghvr-anim-lightspeed-in-left' => __('Lightspeed In Left', 'plugin-domain'),
					'imghvr-anim-lightspeed-in imghvr-anim-lightspeed-in-right' => __('Lightspeed In Right', 'plugin-domain'),
					'imghvr-anim-bounce-in' => __('Bounce In', 'plugin-domain'),
					'imghvr-anim-bounce-in-up' => __('Bounce In Up', 'plugin-domain'),
					'imghvr-anim-bounce-in-down' => __('Bounce In Down', 'plugin-domain'),
					'imghvr-anim-bounce-in-left' => __('Bounce In Left', 'plugin-domain'),
					'imghvr-anim-bounce-in-right' => __('Bounce In Right', 'plugin-domain'),
					'imghvr-anim-shift imghvr-anim-shift-top-left|imghvr-overflow' => __('Shift Top Left', 'plugin-domain'),
					'imghvr-anim-shift imghvr-anim-shift-top-right|imghvr-overflow' => __('Shift Top Right', 'plugin-domain'),
					'imghvr-anim-shift imghvr-anim-shift-bottom-left|imghvr-overflow' => __('Shift Bottom Left', 'plugin-domain'),
					'imghvr-anim-shift imghvr-anim-shift-bottom-right|imghvr-overflow' => __('Shift Bottom Right', 'plugin-domain'),
				],
			]
		);

		$this->add_control(
			'content_duration',
			[
				'label' => __( 'Transition Duration', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.05,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.35,
				],
				'selectors' => [
					'{{WRAPPER}} .imghvr .imghvr-content-wrapper' => 'transition-duration: {{SIZE}}s;',
					'{{WRAPPER}} .imghvr:hover .imghvr-content-wrapper' => 'animation-duration: {{SIZE}}s'
				]
			]
		);

		$this->add_control(
			'content_delay',
			[
				'label' => __( 'Transition Delay', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.05,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .imghvr .imghvr-content-wrapper' => 'transition-delay: {{SIZE}}s; animation-duration: {{SIZE}}s;'
				]
			]
        );
        
        $this->add_control(
			'content_appearance',
			[
				'label' => 'Content Appearance',
				'type' => 'repeatselect',
				'options' => [
					'imghvr-anim-fade-content imghvr-anim-fade-content-up' => __( 'Fade Up', 'plugin-domain' ),
					'imghvr-anim-fade-content imghvr-anim-fade-content-down' => __( 'Fade Down', 'plugin-domain' ),
					'imghvr-anim-fade-content imghvr-anim-fade-content-left' => __( 'Fade Left', 'plugin-domain' ),
					'imghvr-anim-fade-content imghvr-anim-fade-content-right' => __( 'Fade Right', 'plugin-domain' ),
					'imghvr-anim-zoom-content imghvr-anim-zoom-content-in' => __( 'Zoom In', 'plugin-domain' ),
					'imghvr-anim-zoom-content imghvr-anim-zoom-content-out' => __( 'Zoom Out', 'plugin-domain' ),
					'imghvr-anim-flip-content imghvr-anim-flip-content-x' => __( 'Flip X', 'plugin-domain' ),
					'imghvr-anim-flip-content imghvr-anim-flip-content-y' => __( 'Flip Y', 'plugin-domain' ),
				],
				'default' => [
					'imghvr-anim-fade-content imghvr-anim-fade-content-up',
					'imghvr-anim-fade-content imghvr-anim-fade-content-up',
					'imghvr-anim-fade-content imghvr-anim-fade-content-up',
					'imghvr-anim-fade-content imghvr-anim-fade-content-up'
				]
			]
		);

		$this->end_controls_section();

    }

    protected function addstyle() {

		$this->start_controls_section(
			'image_style',
			[
				'label' => __( 'Image', 'plugin-name' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_background',
			[
				'label' => __( 'Background', 'plugin-domain' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .imghvr' => 'background: {{VALUE}}',
				]
			]
        );
        
        $this->add_control(
			'match_height',
			[
                'label' => __( 'Match Height', 'plugin-domain' ),
                'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => '100%',
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}}, {{WRAPPER}} .elementor-widget-container, {{WRAPPER}} .col-row, {{WRAPPER}} .imghvr-wrapper' => 'height: {{VALUE}};'
                ]
			]
        );
        
        $this->add_control(
			'height',
			[
				'label' => __( 'Height', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 400,
                ],
                'condition' => [
					'match_height' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .imghvr' => 'max-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .imghvr-padding:hover' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'plugin-domain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .imghvr' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'overlay_style',
			[
				'label' => __( 'Overlay', 'plugin-name' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'overlay_border',
				'label' => __( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .imghvr-overlay',
			]
		);

		$this->add_control(
			'overlay_margin',
			[
				'label' => __( 'Margin', 'plugin-domain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .imghvr .imghvr-overlay' => 'margin-top: {{TOP}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'label' => __( 'Background', 'plugin-domain' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .imghvr-anim-single, {{WRAPPER}} .imghvr-anim-pseudo::before, {{WRAPPER}} .imghvr-anim-pseudo::after, {{WRAPPER}} .imghvr-anim-pseudo div::before, {{WRAPPER}} .imghvr-anim-pseudo div::after',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_style',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => __( 'Title', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'title' => "1"
                ]
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'plugin-domain' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
                'default' => '',
                'condition' => [
                    'title' => "1"
                ],
				'selectors' => [
					'{{WRAPPER}} .imghvr-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'plugin-domain' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
                'selector' => '{{WRAPPER}} .imghvr-title',
                'condition' => [
                    'title' => "1"
                ]
			]
		);		

		$this->add_control(
			'content_heading',
			[
				'label' => __('Excerpt', 'plugin-name'),
				'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'excerpt' => "1"
                ]
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Color', 'plugin-domain' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
                'default' => '',
                'condition' => [
                    'excerpt' => "1"
                ],
				'selectors' => [
					'{{WRAPPER}} .imghvr-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Typography', 'plugin-domain' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'selector' => '{{WRAPPER}} .imghvr-content',
                'condition' => [
                    'excerpt' => "1"
                ]
			]
		);

		$this->add_control(
			'meta_heading',
			[
				'label' => __( 'Meta', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'meta' => "1"
                ]
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Color', 'plugin-domain' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
                'default' => '',
                'condition' => [
                    'meta' => "1"
                ],
				'selectors' => [
					'{{WRAPPER}} .imghvr-subtitle, {{WRAPPER}} .imghvr-subtitle a' => 'color: {{VALUE}}',
				],
			]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'label' => __( 'Typography', 'plugin-domain' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
                'selector' => '{{WRAPPER}} .imghvr-subtitle',
                'condition' => [
                    'meta' => "1"
                ]
			]
        );
        
        $this->add_control(
			'meta_icon_color',
			[
				'label' => __( 'Icon Color', 'plugin-domain' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'condition' => [
                    'icons' => "1",
                    'meta' => "1",
                ],
				'selectors' => [
					'{{WRAPPER}} .imghvr-subtitle svg' => 'stroke: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
                'condition' => [
                    'show_categories' => "1"
                ],
			]
        );

        $this->add_control(
			'categories_heading',
			[
				'label' => __( 'Categories', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_categories' => "1"
                ],
			]
        );
        
        $this->add_control(
			'category_color',
			[
				'label' => __( 'Color', 'plugin-domain' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'condition' => [
                    'show_categories' => "1"
                ],
				'selectors' => [
					'{{WRAPPER}} .imghvr-cat' => 'color: {{VALUE}}',
				],
			]
        );
        
        $this->add_control(
			'category_background_color',
			[
				'label' => __( 'Background', 'plugin-domain' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#F0423C',
                'condition' => [
                    'show_categories' => "1"
                ],
				'selectors' => [
					'{{WRAPPER}} .imghvr-cat' => 'background: {{VALUE}}',
				],
			]
        );
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'category_typography',
				'label' => __( 'Typography', 'plugin-domain' ),
                'selector' => '{{WRAPPER}} .imghvr-cat',
                'condition' => [
                    'show_categories' => "1"
                ],
			]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'category_border',
				'label' => __( 'Border', 'plugin-domain' ),
                'selector' => '{{WRAPPER}} .imghvr-cat',
                'condition' => [
                    'show_categories' => "1"
                ],
			]
		);
        
        $this->add_control(
			'hr2',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
        );
        
        $this->add_control(
			'content_spacing',
			[
				'label' => __( 'Spacing', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .imghvr-content-block' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .imghvr-content-block:last-child' => 'margin-bottom: 0;',
				],
			]
		);

		$this->add_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'plugin-domain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .imghvr-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_margin',
			[
				'label' => __( 'Margin', 'plugin-domain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .imghvr-content-wrapper' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => __( 'Background', 'plugin-domain' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .imghvr-content-wrapper',
			]
		);
		$this->end_controls_section();
	}

    protected function get_posts($settings) {
        $args = array(
            'posts_per_page'=> $settings['posts'],
            'post_type'=> $settings['post_type'],
            'offset' => $settings['post_offset'],
			'orderby'=> $settings['orderby'],
			'order'=> $settings['order'],
			'category_name'=> ($settings['categories'] != '') ? implode(',', $settings['categories']) : '',
			'tag'=> ($settings['tags'] != '') ? implode(',', $settings['tags']) : '',
        );

        $posts = new \WP_Query($args);
        return $posts;
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        if (is_admin() && $this->get_license('license') == 'false') {
            ?>

            <div class="imghvr-wrapper">
                <div class="imghvr">
                <img src="<?php echo EHE_URL . 'assets/placeholder.png'; ?>" alt="" class="imghvr-anim-none">
                </div>
            </div>

            <?php
        } else {
        
        $posts = $this->get_posts($settings);

        $delays = array('ih-delay-xs', 'ih-delay-sm', 'ih-delay-md', 'ih-delay-lg', 'ih-delay-xl', 'ih-delay-xxl');
        $contents = array('title', 'meta', 'content');
        $types = array('image', 'overlay', 'content');
        $classes = array(
            'imghvr' => array('imghvr'), 
            'image' => array('imghvr-anim-none'), 
            'overlay' => array('imghvr-anim-none', 'imghvr-anim-single'), 
            'content' => array('imghvr-anim-none')
        );

        foreach ($types as $type) {
            $tmpclass = explode('|', $settings[$type . '_effect']);
            $classes[$type] = $tmpclass[0];
            if (isset($tmpclass[1])) {
                $classes['imghvr'][] = $tmpclass[1];
            }
        }

        array_unique($classes['imghvr']);

        ?>

        <div class="col-row">

        <?php while ($posts->have_posts()) {

            $index = 0;
            $posts->the_post();

            $responsive = 'col-desk-' . $settings['columns'];
            $responsive .= ($settings['columns_tablet'] !== '') ? ' col-tab-' . $settings['columns_tablet'] : '';
            $responsive .= ($settings['columns_mobile'] !== '') ? ' col-mob-' . $settings['columns_mobile'] : '';

            ?>

            <div class="<?php echo $responsive; ?>">

            <?php if ($settings['target'] === 'image') { ?>
            <a href="<?php echo get_permalink(); ?>" class="imghvr-wrapper">
            <?php } else { ?>
            <div class="imghvr-wrapper">
            <?php } ?>

                <div class="<?php echo implode(' ', $classes['imghvr']); ?>">

                    <?php if ($settings['overlay_show'] === "1") { ?>
                        <div class="imghvr-overlay <?php echo $classes['overlay']; ?>"><div></div></div>
                    <?php } ?>

                    <div class="imghvr-content-wrapper imghvr-content-<?php echo $settings['align']; ?> <?php echo $classes['content']; ?>">

                    <?php

                        foreach ($settings['sort_element'] as $content) {

                            $anim = (isset($settings['content_appearance'][$index]) && $settings['content_effect'] !== 'imghvr-anim-none') ? $settings['content_appearance'][$index] : 'imghvr-anim-none';

                            switch ($content) {
                                case 'title':
                                    if ($settings['title'] === "1") {
                                        if ($settings['target'] === 'title') {
                                            echo '<a href="' . get_permalink() . '" class="imghvr-content-block imghvr-title ' . $anim . ' ' . $delays[$index] . '">' . get_the_title() . '</a>';
                                        } else {
                                            echo '<div class="imghvr-content-block imghvr-title ' . $anim . ' ' . $delays[$index] . '">' . get_the_title() . '</div>';
                                        }
                                    }
                                    break;
                                case 'meta':
                                    if ($settings['meta'] === "1") {
                                        echo '<div class="imghvr-content-block imghvr-subtitle ' . $anim . ' ' . $delays[$index] . '">';
                                        if ($settings['date']) {
                                            echo '<span class="imghvr-meta">' . (($settings['icons'] === "1") ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> ' : '') . get_the_time('F j, Y') . '</span>';
                                        }
                                        if ($settings['author']) {
                                            echo '<span class="imghvr-meta">' . (($settings['icons'] === "1") ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> ' : '') . get_the_author() . '</span>';
                                        }
                                        if ($settings['comments']) {
                                            echo '<span class="imghvr-meta">' . (($settings['icons'] === "1") ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> ' : '') . get_comments_number() . ' comments</span>';
                                        }
                                        echo '</div>';
                                    }
                                    break;
                                case 'excerpt':
                                    if ($settings['excerpt'] === "1") {
                                        $excerpt = wp_strip_all_tags(get_the_excerpt(), true);
                                        $excerpt = wp_trim_words($excerpt, $settings['excerpt_length'], '');
                                        echo '<div class="imghvr-content-block imghvr-content ' . $anim . ' ' . $delays[$index] . '">' . $excerpt . '</div>';
                                    }
                                    break;
                                case 'categories':
                                    if ($settings['show_categories'] === "1") {
                                        $categories = get_the_category();
                                        echo '<div class="imghvr-content-block imghvr-cats ' . $anim . ' ' . $delays[$index] . '">';
                                        foreach($categories as $cat) {
                                            if ($settings['target'] === 'image') {
                                                echo '<span class="imghvr-cat">' . $cat->name . '</span>';
                                            } else {
                                                echo '<a href="'. get_category_link($cat->term_id) .'" class="imghvr-cat"><span>' . $cat->name . '</span></a>';
                                            } 
                                        }
                                        echo '</div>';
                                    }
                                    break;
                            }

                            $index++;

                        }

                    ?>

                    </div>
            
                    <?php if (has_post_thumbnail()) { ?>
					<?php echo the_post_thumbnail($settings['image_size'], array('class' => $classes['image'])); ?>
                    <?php } else if (!empty($settings['image']['url'])) { ?>
                    <img src="<?php echo $settings['image']['url']; ?>" alt="" class="<?php echo $classes['image']; ?>" />
                    <?php } ?>

                </div>

            <?php if ($settings['target'] === 'image') { ?></a><?php } else { ?></div><?php } ?>

            </div>

        <?php } ?>

        </div>

        <?php

        }

        wp_reset_postdata();

    }

}

Plugin::instance()->widgets_manager->register_widget_type(new Emage_Post_Grid()); 