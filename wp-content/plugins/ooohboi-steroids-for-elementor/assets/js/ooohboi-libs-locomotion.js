'use strict';

( function ( $, w, sfe_loco_scroll ) { 
    
    var $window = $( w );
    var loco_multiplier = device_settings.scroll_multiplier; // validated via PHP
    var loco_scroll_tablet = parseInt( device_settings.allow_tablet );
    var loco_scroll_mobile = parseInt( device_settings.allow_mobile );
    var loco_allow_refresh = parseInt( device_settings.allow_refresh );
    var loco_remove_section_attribute = parseInt( device_settings.remove_section_attribute );
	var resize_timer;

    // fix the Firefox scroll lag
    if( navigator.userAgent.indexOf( "Firefox" ) !== -1 ) loco_multiplier *= 3;

    $window.on( 'elementor/frontend/init', function() { 
        // reference to THIS / Elementor
        var myself = this;
        // default Elementor - there's no any kind of wrapper at all
        var target_node = this.elementorFrontend.elements.$body[ 0 ];
        // locomotive scroll wrapper
        var loco_wrapper = target_node.firstElementChild;
        // qualify BODY children to become scroll sections
        var body_children = target_node.childNodes; 
        // take care about header, footer and blocks plugin
        var is_header_footer_and_blocks = 0;
        // traverse to figure out the wrapper
        for( var i = 0; i < body_children.length; i ++ ) {
            if( $( body_children[ i ] ).is( 'div#page' ) ) {
                $( body_children[ i ] ).addClass( 'sfe-locomotive-scroll-wrapper' ).attr( 'data-scroll-container', '' ); 
                is_header_footer_and_blocks = 1; 
                target_node = body_children[ i ]; // it's not default Elementor anymore
                loco_wrapper = target_node.firstElementChild;
                break;
            } 
        }
        // add required attributes
        if( loco_remove_section_attribute < 1 ) {
            for( var i = 0; i < target_node.childNodes.length; i ++ ) {
                if( ( $( target_node.childNodes[ i ] ).is( 'div' ) && $( target_node.childNodes[ i ] ).attr( 'data-elementor-type' ) ) 
                || $( target_node.childNodes[ i ] ).is( 'header' ) 
                || $( target_node.childNodes[ i ] ).is( 'main' ) 
                || $( target_node.childNodes[ i ] ).is( 'footer' ) ) {
                    $( target_node.childNodes[ i ] ).attr( 'data-scroll-section', '' );
                }
            }
        }
        // wrap everything inside BODY if default Elementor
        if( ! is_header_footer_and_blocks ) {
            $( this.elementorFrontend.elements.$body[ 0 ] ).wrapInner( '<div class="sfe-locomotive-scroll-wrapper" data-scroll-container=""></div>' ); 
        }
        // init locomotive scroll
        elementorFrontend.on( 'components:init', function() {
            var class_list = myself.elementorFrontend.elements.$body[ 0 ].classList;
            if( $.inArray( 'elementor-editor-active', class_list ) === -1 && $( loco_wrapper ).length ) {
                w.sfe_loco_scroll = new LocomotiveScroll( {
                    el: document.querySelector( '[data-scroll-container]' ), 
                    smooth: true, 
                    multiplier: loco_multiplier, 
                    tablet: { smooth: loco_scroll_tablet }, 
                    smartphone: { smooth: loco_scroll_mobile }, 
                } );
            };
        } );
    } ); 
    // handle scrollTo new way plus ... Locomotive Scroll tends to cutoff a chunk of footer, this is some sort of an ugly workaround
    $window.load( function( $ ) {
        var loco_scroll_loaded = setInterval( function() {
            if( w.sfe_loco_scroll && Object.keys( w.sfe_loco_scroll ).length !== 0 ) {
				// fix the miscalculated page height
				if( ! loco_allow_refresh ) w.dispatchEvent( new Event( 'resize' ) );
                // anchor/hash
                var all_data_scroll_to = document.querySelectorAll( '[data-jump-to]' ), i;
                for( i = 0; i < all_data_scroll_to.length; ++ i ) {
                    all_data_scroll_to[ i ].addEventListener( 'click', event => {
                        event.preventDefault(); // esc defos
                        var ankor = event.currentTarget.getAttribute( 'data-jump-to' );
                        var regexp = /^[\w#-_]+$/; 
                        if( ankor.search( regexp ) !== -1 ) {
                            w.sfe_loco_scroll.scrollTo( ankor, { 
                                'offset': 0,
                                'duration': 5,
                                'disableLerp': false
                            } );
                        }
                    } );
                }
                // get off the duty
                refresh_loc();
                clearInterval( loco_scroll_loaded ); 
            }
        }, 100 );
    } );
    // handle resize --- we have to refresh the page upon resize!
    window.onresize = () => {
		clearTimeout( resize_timer );
		resize_timer = setTimeout( refresh_loc, 500 );
	};
	function refresh_loc() {
		if( w.sfe_loco_scroll && Object.keys( w.sfe_loco_scroll ).length !== 0 && loco_allow_refresh ) {
            w.sfe_loco_scroll.update();
			w.sfe_loco_scroll.scroll.reinitScrollBar();
        }
	}
}( jQuery, window, window.sfe_loco_scroll = window.sfe_loco_scroll || {} ) );