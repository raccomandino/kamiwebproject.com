<?php
/* Common task helpers */

if( ! defined( 'ABSPATH' ) ) exit;

define( 'SFE_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'SFE_DIR_URL', plugin_dir_url( __FILE__ ) );

ob_start(); 
// news content
include( SFE_DIR_PATH . 'info/ob-landing.php' );
$news_content = ob_get_clean();

// cdnjs acknowledgements
$cdnjs_gsap = '<p class="ob-alert">NOTE! This (GSAP) JavaScript library is hosted by, and loaded from the <a href="https://cdnjs.com/libraries/gsap">cdnjs.com</a>. "OoohBoi - Steroids for Elementor" plugin does not include that file! 
By enabling this option you acknowledge its <a href="https://greensock.com/standard-license/" target="_blank">terms of use</a>, and accept every responsibility - including the risk of script unavailability, vulnerability and the data loss.</p>'; 
$cdnjs_scrolltrigger = '<p class="ob-alert">NOTE! This (ScrollTrigger) JavaScript library is hosted by, and loaded from the <a href="https://cdnjs.com/libraries/ScrollTrigger">cdnjs.com</a>. "OoohBoi - Steroids for Elementor" plugin does not include that file! 
By enabling this option you acknowledge its <a href="https://greensock.com/standard-license/" target="_blank">terms of use</a>, and accept every responsibility - including the risk of script unavailability, vulnerability and the data loss.</p>';
$cdnjs_scrollto = '<p class="ob-alert">NOTE! This (ScrollTo) JavaScript library is hosted by, and loaded from the <a href="https://cdnjs.com/libraries/gsap">cdnjs.com</a>. "OoohBoi - Steroids for Elementor" plugin does not include that file! 
By enabling this option you acknowledge its <a href="https://greensock.com/standard-license/" target="_blank">terms of use</a>, and accept every responsibility - including the risk of script unavailability, vulnerability and the data loss.</p>';
$cdnjs_motionpath = '<p class="ob-alert">NOTE! This (MotionPath) JavaScript library is hosted by, and loaded from the <a href="https://cdnjs.com/libraries/gsap">cdnjs.com</a>. "OoohBoi - Steroids for Elementor" plugin does not include that file! 
By enabling this option you acknowledge its <a href="https://greensock.com/standard-license/" target="_blank">terms of use</a>, and accept every responsibility - including the risk of script unavailability, vulnerability and the data loss.</p>';
// --------------------------- E X O P I T ----- >
$config_submenu = array(
    
    'type'              => 'menu', // Required, menu or metabox
    'title'             => 'Steroids for Elementor v' . OoohBoi_Steroids::VERSION, // options panel title
    'menu_title'        => 'Steroids for Elementor', // admin menu title
    'icon'              => SFE_DIR_URL . 'img/sfe-icon-WP-admin.png',
    'id'                => 'steroids_for_elementor',  // Required, meta box id, unique per page, to save: get_option( id )
    'parent'            => 'edit.php?post_type=steroids_for_elementor', // Parent page of plugin menu (default Settings [options-general.php])
    'submenu'           => false, 
    'search_box'        => false, 
    'capability'        => 'manage_options', 
    'plugin_basename'   =>  plugin_basename( plugin_dir_path( __DIR__ ) . 'ooohboi-steroids-for-elementor.php' ),
    'tabbed'            => true,
    'multilang'         => false, 

);

$fields[] = array(
    'name'   => 'sfe_home',
    'title'  => esc_attr__( 'What\'s New?', 'ooohboi-steroids' ), 
    'icon'   => 'dashicons-format-status',
    'fields' => array(
        array(
            'id'      => 'sfe_home_news',
            'type'    => 'content',
            'content' => $news_content, 
        ),
    ),
);

$fields[] = array(
    'name'   => 'sfe_extensions',
    'title'  => esc_attr__( 'Manage Extensions', 'ooohboi-steroids' ), 
    'icon'   => 'dashicons-admin-plugins',
    'fields' => array(
        array(
            'id'      => 'sfe_extensions_intro',
            'type'    => 'content',
            'content' => sprintf( __( '%sThe following extensions are currently available with Steroids for Elementor add-on. Enable or disable particular extension by switching it ON or OFF.%s', 'ooohboi-steroids' ), '<p>', '</p>' ), 
            'class'   => 'sfe-intro', 
        ),
        array(
            'id'            => 'ob_use_btl', 
            'title'			=> 'BETTER TEMPLATES LIBRARY', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAdd preview to your templates, Export/Import/Share your templates with preview image.%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ),
            'default'       => 'no',
        ),
        array(
            'id'            => 'ob_use_harakiri', 
            'title'			=> 'HARAKIRI', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAllows you to change the writing mode of the Heading and Text Editor widgets%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ),
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_poopart', 
            'title'			=> 'POOPART', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAdd an overlay or underlay ghost-element to any Elementor Widget%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_overlaiz', 
            'title'			=> 'OVERLAIZ', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAn awesome set of options for the Background Overlay element manipulation (up to Elementor 3.5.9)%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_paginini', 
            'title'			=> 'PAGININI', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sIt allows you to style up the posts pagination in Elementor%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_breakingbad', 
            'title'			=> 'BREAKING BAD', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sA must to have extension for the Section and Columns (up to Elementor 3.5.9)%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_glider', 
            'title'			=> 'GLIDER', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sThe content slider made out of Section and Columns (Swiper)%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_photogiraffe', 
            'title'			=> 'PHOTOGIRAFFE', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sMake the Image widget full-height of the container (up to Elementor 3.5.9)%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_teleporter', 
            'title'			=> 'TELEPORTER', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sThe Column hover controls for an exceptional effects (up to Elementor 3.5.9)%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_searchcop', 
            'title'			=> 'SEARCH COP', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sDecide what to search for; posts only, pages only or everything%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_videomasq', 
            'title'			=> 'VIDEOMASQ', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAdd the SVG mask to the Section video background and let the video play inside any shape%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ),
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_butterbutton', 
            'title'			=> 'BUTTER BUTTON', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sDesign awesome buttons in Elementor%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_perspektive', 
            'title'			=> 'PERSPEKTIVE', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sA small set of options that allow you to move widgets in 3D space%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_shadough', 
            'title'			=> 'SHADOUGH', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sCreate the shadow that conforms the shape%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_photomorph', 
            'title'			=> 'PHOTOMORPH', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAllows you to add the clip-path to the Image widget for Normal and Hover state%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_commentz', 
            'title'			=> 'COMMENTZ', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAllows you to style up the post comments.', 'ooohboi-steroids%s' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_spacerat', 
            'title'			=> 'SPACERAT', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAdds new shine to the Spacer widget.', 'ooohboi-steroids%s' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_imbox', 
            'title'			=> 'IMBOX', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sImage Box widget extra controls', 'ooohboi-steroids%s' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_icobox', 
            'title'			=> 'ICOBOX', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sIcon Box widget extra controls', 'ooohboi-steroids%s' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_hoveranimator', 
            'title'			=> 'HOVERANIMATOR', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAnimate widgets on columns mouse-over event', 'ooohboi-steroids%s' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_kontrolz', 
            'title'			=> 'KONTROLZ', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAllows you to additionaly style Image Carousel and Media Carousel controls%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_widgetstalker', 
            'title'			=> 'WIDGET STALKER', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sStack widgets like flex elements%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_pseudo', 
            'title'			=> 'PSEUDO', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sTake control over the Column\'s pseudo elements%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),
        array(
            'id'            => 'ob_use_bullet', 
            'title'			=> 'BULLET', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sAllows you to move the Icon List widget bullet to top%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ),  
        array(
            'id'            => 'ob_use_container_extras', 
            'title'			=> 'CONTAINER EXTRAS', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sJust a few extra Container settings which can make your life easier (for Elementor 3.6 and above)%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ), 
        /*
        array(
            'id'            => 'ob_use_interactor', 
            'title'			=> 'INTERACTOR', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sCreate interactions between the elements on page (for Elementor 3.6 and above)%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ), 
        */
        array(
            'id'            => 'ob_use_counterz', 
            'title'			=> 'COUNTERZ', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sMore styling options to the Counter widget%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ), 
        array(
            'id'            => 'ob_use_tabbr', 
            'title'			=> 'TABBR', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sStyle up your tabs like a rock star%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ), 
        array(
            'id'            => 'ob_use_postman', 
            'title'			=> 'POSTMAN', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sStyle up the Post Content widget elements%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ), 
        array(
            'id'            => 'ob_use_typo', 
            'title'			=> 'TYPO', 
            'type'			=> 'switcher',
            'description'	=> sprintf( __( '%sMore controls to your Elementor Kit%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
            'default'       => 'yes',
        ), 
    ),
);

$fields[] = array(
    'name'   => 'sfe_libraries',
    'title'  => esc_attr__( 'Manage JS Libraries', 'ooohboi-steroids' ), 
    'icon'   => 'dashicons-edit-page',
    'fields' => array(
        array(
            'id'      => 'sfe_libraries_intro',
            'type'    => 'content',
            'content' => sprintf( __( '%sThe following libraries are currently available with Steroids for Elementor add-on. Enable or disable particular library by switching it ON or OFF.%s', 'ooohboi-steroids' ), '<p>', '</p>' ), 
            'class'   => 'sfe-intro', 
        ),
        array(
            'type'    => 'fieldset',
            'id'      => 'fieldset_locomotive',
            'title'   => esc_html__( 'LOCOMOTIVE SCROLL', 'ooohboi-steroids' ),
            'description' => sprintf( __( '%1$sDetection of elements in viewport and smooth scrolling with parallax.%3$s%4$sSOURCE%5$s%2$s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>', '<br />', '<a href="https://github.com/locomotivemtl/locomotive-scroll" title="Locomotive Scroll Github" target="_blank">', '</a>' ), 
            'fields'  => array(
                array(
                    'id'      => 'ob_use_locomotive_scroll',
                    'type'    => 'switcher',
                    'default' => 'no',
                ),
                array(
                    'id'        => 'ob_use_locomotive_devices',
                    'type'      => 'tap_list',
                    'options'   => array(
                        'allow-tablet' => esc_html__( 'Enable for Tablets', 'ooohboi-steroids' ), 
                        'allow-mobile' => esc_html__( 'Enable for Mobiles', 'ooohboi-steroids' ), 
                    ),
                    'default'   => array(
                        'allow-tablet',
                        'allow-mobile'
                    ),
                ),
                array(
                    'id'        => 'ob_use_locomotive_multiplier', 
                    'title'	    => esc_html__( 'Multiplier', 'ooohboi-steroids' ), 
                    'description' => sprintf( __( '%sBoost/reduce scrolling speed. 1 is the default.%s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>' ), 
                    'type'      => 'range',
                    'default'   => '0.5', 
                    'min'     => '0.1',
                    'max'     => '3',
                    'step'    => '0.1', 
                ),
                array(
                    'id'      => 'ob_allow_refresh', 
                    'title'	  => esc_html__( 'Refresh on resize?', 'ooohboi-steroids' ), 
                    'description' => esc_html__( 'Things get messy on resize. That will do the silent page refresh upon the window resize/orientationchange.', 'ooohboi-steroids' ), 
                    'type'    => 'checkbox',
                    'default' => 'no',
                    'style'   => 'fancy',
                ),
                array(
                    'id'      => 'ob_remove_section_attribute', 
                    'title'	  => esc_html__( 'Enable freehand mode?', 'ooohboi-steroids' ), 
                    'description' => sprintf( __( 'That removes all the default %sdata-scroll-section%s attributes so you can set them at will.', 'ooohboi-steroids' ), '<em>', '</em>' ), 
                    'type'    => 'checkbox',
                    'default' => 'no',
                    'style'   => 'fancy',
                ),
            ),
        ),
        array(
            'id'            => 'ob_use_three', 
            'title'			=> 'Three.JS', 
            'type'			=> 'switcher',
            'description'   => sprintf( __( '%1$s Cross-browser JavaScript library/API which is used to create and animate 3D computer graphics to display in a web browser.%3$s%4$sSOURCE%5$s%2$s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>', '<br />', '<a href="https://threejs.org/" title="Three.js Home Page" target="_blank">', '</a>' ), 
            'default'       => 'no',
        ),
        array(
            'id'            => 'ob_use_anime', 
            'title'			=> 'Anime', 
            'type'			=> 'switcher',
            'description'   => sprintf( __( '%1$s A lightweight JavaScript animation library with a simple, yet powerful API.%3$s%4$sSOURCE%5$s%2$s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>', '<br />', '<a href="https://animejs.com/" title="Anime Home Page" target="_blank">', '</a>' ), 
            'default'       => 'no',
        ),
        array(
            'id'            => 'ob_use_barba', 
            'title'			=> 'Barba', 
            'type'			=> 'switcher',
            'description'   => sprintf( __( '%1$s Create badass fluid and smooth transitions between your website\'s pages.%3$s%4$sSOURCE%5$s%2$s', 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>', '<br />', '<a href="https://barba.js.org/" title="Barba Home Page" target="_blank">', '</a>' ), 
            'default'       => 'no',
        ),
        array(
            'id'            => 'ob_use_gsap', 
            'title'			=> 'GSAP', 
            'type'			=> 'switcher',
            'description'   => sprintf( __( '%1$sGreenSock\'s GSAP JavaScript animation library (including Draggable).%3$s%4$sSOURCE%5$s%2$s' . $cdnjs_gsap, 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>', '<br />', '<a href="https://github.com/greensock/GSAP" title="GSAP Github" target="_blank">', '</a>' ), 
            'default'       => 'no',
        ),
        array(
            'id'            => 'ob_use_scroll_trigger', 
            'title'			=> 'GSAP - SCROLL TRIGGER', 
            'type'			=> 'switcher',
            'description'   => sprintf( __( '%1$sLet your page react to scroll changes.%3$s%4$sSOURCE%5$s%2$s' . $cdnjs_scrolltrigger, 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>', '<br />', '<a href="https://github.com/terwanerik/ScrollTrigger" title="ScrollTrigger Github" target="_blank">', '</a>' ), 
            'default'       => 'no',
        ),
        array(
            'id'            => 'ob_use_scroll_to', 
            'title'			=> 'GSAP - SCROLL TO', 
            'type'			=> 'switcher',
            'description'   => sprintf( __( '%1$sAnimates the scroll position of the window or a DOM element.%3$s%4$sSOURCE%5$s%2$s' . $cdnjs_scrollto, 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>', '<br />', '<a href="https://greensock.com/docs/v3/Plugins/ScrollToPlugin" title="ScrollTo" target="_blank">', '</a>' ), 
            'default'       => 'no',
        ),
        array(
            'id'            => 'ob_use_motion_path', 
            'title'			=> 'GSAP - MOTION PATH', 
            'type'			=> 'switcher',
            'description'   => sprintf( __( '%1$sAnimate anything (SVG, DOM, canvas, generic objects, whatever) along a motion path in any browser.%3$s%4$sSOURCE%5$s%2$s' . $cdnjs_motionpath, 'ooohboi-steroids' ), '<span class="ob-option-desc">', '</span>', '<br />', '<a href="https://greensock.com/motionpath/" title="MotionPath" target="_blank">', '</a>' ), 
            'default'       => 'no',
        ),

    ),
);

$options_panel = new Exopite_Simple_Options_Framework( $config_submenu, $fields ); 