'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

	function debounce(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	}

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        Animator; 

        Animator = ModuleHandler.extend( {

            gsap_instance: null, 
            st_instance: null, 
            st_instance_id: '', 

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );

                if( this.isAnimatorObject() ) {
                    this.$element.addClass( 'ob-is-animated' );
                    this.run();
                } 

            },
           
			bindEvents: debounce( function() { 
                w.ScrollTrigger.refresh();
			}, 500 ),
            
			getDefaultElements: function() {
				return {
					$container: ( 'yes' === this.getElementSettings( '_ob_animator_el_is_sibling' ) ) ? this.$element.find( '.elementor-widget-container > *:first-child' ) : this.$element.find( '.elementor-widget-container' ),
				};
			},

            isAnimatorObject: function() {
                return ( this.getElementSettings( '_ob_animator' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_animator' ) {
                    if( 'yes' === this.getElementSettings( '_ob_animator' ) ) {
                        this.$element.addClass( 'ob-is-animated' );
                    } else if( '' === this.getElementSettings( '_ob_animator' ) ) { 
                        this.$element.removeClass( 'ob-is-animated' ); 
                        this.removeSTInstance();
                        elementor.reloadPreview();
                    }
                } 
            }, 

            removeSTInstance: function() {
                let this_ID = this.$element[ 0 ].dataset[ 'id' ];
                $( "div[class*='gsap-marker-']:contains('ST_" + this_ID + "')" ).remove(); 
                if( 'object' === typeof this.st_instance && null !== this.st_instance ) this.st_instance.kill();
            },

            run: function() {
                if( this.isAnimatorObject() ) this.getAnimatorSettings();
            }, 

			getAnimatorSettings: function() { 

                var el_type = this.$element[ 0 ].dataset.element_type; 
                var myself = '';
                var children = '';
                var stagger_obj = {};
                var el_settings = {}; 

                el_settings._ob_animator_el_is_sibling = this.getElementSettings( '_ob_animator_el_is_sibling' ); 
                el_settings._ob_animator_st_markers = this.getElementSettings( '_ob_animator_st_markers' ); 
                el_settings._ob_animator_st_scrub = this.getElementSettings( '_ob_animator_st_scrub' ); 
                el_settings._ob_animator_st_scrub_smooth = this.getElementSettings( '_ob_animator_st_scrub_smooth' ); 
                el_settings._ob_animator_st_invalidate_on_refresh = this.getElementSettings( '_ob_animator_st_invalidate_on_refresh' ); 
                el_settings._ob_animator_st_immediate_render = this.getElementSettings( '_ob_animator_st_immediate_render' ); 
                el_settings._ob_animator_st_pin = this.getElementSettings( '_ob_animator_st_pin' ); 
                el_settings._ob_animator_st_pin_spacing = this.getElementSettings( '_ob_animator_st_pin_spacing' ); 
                el_settings._ob_animator_st_anticipate_pin = this.getElementSettings( '_ob_animator_st_anticipate_pin' ); 
                el_settings._ob_animator_st_pin_prevent_overlaps = this.getElementSettings( '_ob_animator_st_pin_prevent_overlaps' ); 
                el_settings._ob_animator_st_toggle_class = this.getElementSettings( '_ob_animator_st_toggle_class' ); 
                el_settings._ob_animator_st_end_trigger_class = this.getElementSettings( '_ob_animator_st_end_trigger_class' ); 
                el_settings._ob_animator_st_start_trigger_el = this.getElementSettings( '_ob_animator_st_start_trigger_el' ) || 'none'; 
                el_settings._ob_animator_st_start_trigger_el_off = this.getElementSettings( '_ob_animator_st_start_trigger_el_off.top' ) || 0; 
                el_settings._ob_animator_st_start_viewport = this.getElementSettings( '_ob_animator_st_start_viewport' ) || 'none'; 
                el_settings._ob_animator_st_start_viewport_off = this.getElementSettings( '_ob_animator_st_start_viewport_off.top' ) || 0;
                el_settings._ob_animator_st_end_trigger_el = this.getElementSettings( '_ob_animator_st_end_trigger_el' ) || 'none'; 
                el_settings._ob_animator_st_end_trigger_el_off = this.getElementSettings( '_ob_animator_st_end_trigger_el_off.bottom' ) || 0; 
                el_settings._ob_animator_st_end_viewport = this.getElementSettings( '_ob_animator_st_end_viewport' ) || 'none'; 
                el_settings._ob_animator_st_end_viewport_off = this.getElementSettings( '_ob_animator_st_end_viewport_off.bottom' ) || 0;
                el_settings._ob_animator_st_ta_onenter = this.getElementSettings( '_ob_animator_st_ta_onenter' ) || 'restart'; 
                el_settings._ob_animator_st_ta_onleave = this.getElementSettings( '_ob_animator_st_ta_onleave' ) || 'non'; 
                el_settings._ob_animator_st_ta_onenterback = this.getElementSettings( '_ob_animator_st_ta_onenterback' ) || 'none'; 
                el_settings._ob_animator_st_ta_onleaveback = this.getElementSettings( '_ob_animator_st_ta_onleaveback' ) || 'reverse'; 
                el_settings._ob_animator_easing = this.getElementSettings( '_ob_animator_easing' ); 
                el_settings._ob_animator_duration = this.getElementSettings( '_ob_animator_duration' ); 
                el_settings._ob_animator_delay = this.getElementSettings( '_ob_animator_delay' ); 
                el_settings.rptr = this.getElementSettings( '_ob_animator_props_repeater' ); 

                if( 'container' === el_type  ) myself = this.$element; 
                else myself = this.elements.$container; 

                // markers 
                var show_markers = ( 'yes' === el_settings._ob_animator_st_markers ) ? true : false;
                // anticipate pin
                var anticipate_pin = ( 'yes' === el_settings._ob_animator_st_anticipate_pin ) ? true : false;
                // prevent overlaps 
                var prevent_overlaps = ( 'yes' === el_settings._ob_animator_st_pin_prevent_overlaps ) ? true : false;
                // invalidate on refresh
                var invalidate_on_refresh = ( 'yes' === el_settings._ob_animator_st_invalidate_on_refresh ) ? true : false;
                // immediate render
                var immediate_render = ( 'yes' === el_settings._ob_animator_st_immediate_render ) ? true : false;
                // scrub
                var scrub_value = false; 
                if( 'yes' ===  el_settings._ob_animator_st_scrub ) {
                    scrub_value = true; 
                    if( el_settings._ob_animator_st_scrub_smooth > 0 && '' !== el_settings._ob_animator_st_scrub_smooth ) scrub_value = el_settings._ob_animator_st_scrub_smooth; 
                }
                // pin
                var pin_el = false; 
                var pin_spacing = true; 
                if( 'yes' ===  el_settings._ob_animator_st_pin ) {
                    pin_el = true; 
                    if( 'no' === el_settings._ob_animator_st_pin_spacing ) pin_spacing = false; 
                    else if( 'margin' === el_settings._ob_animator_st_pin_spacing ) pin_spacing = 'margin'; 
                }
                // toggle class
                var toggle_class = ''; 
                if( 'undefined' !== el_settings._ob_animator_st_toggle_class ) toggle_class = $.escapeSelector( el_settings._ob_animator_st_toggle_class );
                // endTrigger
                var end_trigger = ''; 
                if( 'undefined' !== el_settings._ob_animator_st_end_trigger_class ) end_trigger = $.escapeSelector( el_settings._ob_animator_st_end_trigger_class );

                // ---- START & END ------
                var start_trigger_el_off_unit = this.getElementSettings( '_ob_animator_st_start_trigger_el_off.unit' );
                var start_element = ( 'none' !== el_settings._ob_animator_st_start_trigger_el ) ? el_settings._ob_animator_st_start_trigger_el : ''; 
                if( 'top_and_height' === start_element ) start_element = 'top' + `+=${myself.outerHeight()}`;
                var start_element_offset = el_settings._ob_animator_st_start_trigger_el_off;
                if( 'top_and_height' !== start_element ) {
                    var start_val = ( 'none' === start_element ) ? '' : start_element;
                    if( start_element_offset < 0 ) start_element = start_val + '-=' + Math.abs( start_element_offset ) + start_trigger_el_off_unit; 
                    else if( start_element_offset > 0 ) start_element = start_val + '+=' + Math.abs( start_element_offset ) + start_trigger_el_off_unit; 
                }
                var start_viewport_off_unit = this.getElementSettings( '_ob_animator_st_start_viewport_off.unit' );
                var start_viewprt = ( 'none' !== el_settings._ob_animator_st_start_viewport ) ? ' ' + el_settings._ob_animator_st_start_viewport : ''; 
                var start_viewprt_offset = el_settings._ob_animator_st_start_viewport_off; 
                if( start_viewprt_offset < 0 ) start_viewprt = start_viewprt + '-=' + Math.abs( start_viewprt_offset ) + start_viewport_off_unit;
                else if( start_viewprt_offset > 0 ) start_viewprt = start_viewprt + '+=' + Math.abs( start_viewprt_offset ) + start_viewport_off_unit; 

                var end_trigger_el_off_unit = this.getElementSettings( '_ob_animator_st_end_trigger_el_off.unit' );
                var end_element = ( 'none' !== el_settings._ob_animator_st_end_trigger_el ) ? el_settings._ob_animator_st_end_trigger_el : ''; 
                var end_element_offset = el_settings._ob_animator_st_end_trigger_el_off;
                if( end_element_offset < 0 ) end_element = end_element + '-=' + Math.abs( end_element_offset ) + end_trigger_el_off_unit;
                else if( end_element_offset > 0 ) end_element = end_element + '+=' + Math.abs( end_element_offset ) + end_trigger_el_off_unit;

                var end_viewport_off_unit = this.getElementSettings( '_ob_animator_st_end_viewport_off.unit' );
                var end_viewprt = ( 'none' !== el_settings._ob_animator_st_end_viewport ) ? ' ' + el_settings._ob_animator_st_end_viewport : ''; 
                var end_viewprt_offset = el_settings._ob_animator_st_end_viewport_off; 
                if( end_viewprt_offset < 0 ) end_viewprt = end_viewprt + '-=' + Math.abs( end_viewprt_offset ) + end_viewport_off_unit;
                else if( end_viewprt_offset > 0 ) end_viewprt = end_viewprt + '+=' + Math.abs( end_viewprt_offset ) + end_viewport_off_unit;
                // ------------------------------------------------
                // ---- TOGGLE ACTIONS ----------------------------
                var toggle_actions = 
                el_settings._ob_animator_st_ta_onenter + ' ' 
                + el_settings._ob_animator_st_ta_onleave + ' ' 
                + el_settings._ob_animator_st_ta_onenterback + ' ' 
                + el_settings._ob_animator_st_ta_onleaveback; 
                // ------------------------------------------------

                // easing
                var animation_easing = el_settings._ob_animator_easing; 
                if( 'yes' ===  el_settings._ob_animator_st_scrub ) animation_easing = 'none'; // none if use scrubbing
                // duration
                var animation_duration = parseFloat( el_settings._ob_animator_duration ); 
                //delay
                var animation_delay = parseFloat( el_settings._ob_animator_delay ); 
                
                // let's animate 
                /*******/
                var rptr_from = []; 
                var rptr_to   = [];
                var rptr_set  = [];
                $.each( el_settings.rptr, function( i, val ) { 
                    if( 'none' === val._ob_animator_property ) return;
                    var prop = val._ob_animator_property;
                    var from_obj = val[ '_ob_prop_' + prop + '_from' ];
                    var to_obj   = val[ '_ob_prop_' + prop + '_to' ];
                    var from_size = ( 'object' === typeof from_obj ) ? from_obj.size : from_obj; 
                    var from_unit = ( 'object' === typeof from_obj ) ? from_obj.unit : ''; 
                    var to_size = ( 'object' === typeof to_obj ) ? to_obj.size : to_obj; 
                    var to_unit = ( 'object' === typeof to_obj ) ? to_obj.unit : ''; 

                    rptr_from.push( { '_prop': prop, '_val': from_size + from_unit } );
                    rptr_to.push( { '_prop': prop, '_val': to_size + to_unit } );
                    
                    // special cases
                    // ---------------------------------------------------------------------------------
                    var transform_origin = val[ '_ob_prop_tr_origin' ]; 
                    if( 'none' != transform_origin && 'scale' == prop ) rptr_set.push( { '_prop': 'transformOrigin', '_val': transform_origin } ); // NOTE: must include 'scale' === prop
                } );
                
                // format for GSAP
                var gsap_from = {};
                var gsap_to   = { 'duration': animation_duration, 'ease': animation_easing, 'delay': animation_delay, 'stagger': stagger_obj };
                var gsap_set  = {};

                $.each( rptr_from, function( i, val ) { gsap_from[ val._prop ] = val._val; } );
                $.each( rptr_to, function( i, val ) { gsap_to[ val._prop ] = val._val; } );
                $.each( rptr_set, function( i, val ) { gsap_to[ val._prop ] = val._val; } );
                /***************************************************/
                
                // run animation
                if( w.gsap && w.ScrollTrigger ) {

                    // this element id
                    let this_ID = this.$element[ 0 ].dataset[ 'id' ];

                    if( this.isEdit ) $( "div[class*='gsap-marker-']:contains('ST_" + this_ID + "')" ).remove(); 

                    // if animate all
                    if( 'yes' === el_settings._ob_animator_el_animate_children ) myself = children;
                    // set
                    if( Object.keys( gsap_set ).length ) w.gsap.set( myself, gsap_set ); // SETS STATIC PROPERTIES
                    // from-to
                    this.gsap_instance = w.gsap.fromTo( myself, gsap_from, gsap_to );

                    this.st_instance_id = 'ST_' + this_ID;
                    
                    this.st_instance = w.ScrollTrigger.create( {
                        id: this.st_instance_id, 
                        trigger: myself, 
                        toggleActions: toggle_actions, 
                        start: () => start_element + start_viewprt, 
                        end: end_element + end_viewprt, 
                        scrub: scrub_value, 
                        pin: pin_el, 
                        pinSpacing: pin_spacing, 
                        anticipatePin: anticipate_pin, 
                        preventOverlaps: prevent_overlaps, 
                        invalidateOnRefresh: invalidate_on_refresh, 
                        immediateRender: immediate_render, 
                        markers: show_markers, 
                        animation: this.gsap_instance, 
                    } );

                    // append conditioned vars
                    if( '' !== toggle_class && 'undefined' !== toggle_class ) this.st_instance.vars.toggleClass = toggle_class;
                    if( '' !== end_trigger && 'undefined' !== end_trigger ) this.st_instance.vars.endTrigger = end_trigger;

                }

                if( this.isEdit ) {
                    var TMP_this = this;
                    // the DOM hack to prevent all kinds of shit ... 
                    elementor.channels.editor.on( 'change:container', function( el ) {
                        if( el._parent.model.id === TMP_this.getID() ) {
                            w.dispatchEvent( new Event( 'resize' ) ); 
                        }
                    } );
                }

			}, 

        } );

        var handlersList = {
            'container': Animator, 
            'widget': Animator, 
        };

        $.each( handlersList, function( widgetName, handlerClass ) {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widgetName, function( $scope ) {
                elementorFrontend.elementsHandler.addHandler( handlerClass, { $element: $scope } );
            } );
        } );

    } ); 


} ( jQuery, window ) );