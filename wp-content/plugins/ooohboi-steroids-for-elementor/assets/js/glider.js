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
        Glider; 

        Glider = ModuleHandler.extend( {

            me_the_swiper: 'undefined', 
            glider_external_controls: [], 

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isGlider() && this.isGliderCandidate() ) {

                    this.$element.addClass( 'ob-is-glider' ); 
                    this.generateSwiperStructure(); 

                } else if( this.isGlider() && this.isOldGliderCandidate() ) { // if not a container!!!

                    this.$element.addClass( 'ob-is-glider' ); 
                    this.generateSwiperOld(); 

                }
            },

            isGlider: function() {
                return ( this.getElementSettings( '_ob_glider_is_slider' ) === 'yes' );
            }, 

            isGliderCandidate: function() {
                return ( 
                    ! this.$element.closest( '.swiper' ).length && 
                    ! this.$element.find( '.swiper' ).length && 
                    ( this.$element.find( '.e-con-inner' ).children( '[data-element_type="container"]' ).length > 1 || this.$element.children( '[data-element_type="container"]' ).length ) );
            }, 

            isOldGliderCandidate: function() { // it's not a container 
                return ( 'section' === this.$element.attr( 'data-element_type' ) );
            }, 

            onElementChange: function( changedProp ) {

                if( changedProp === '_ob_glider_is_slider' ) { 

                    console.log( 'Prop changed' );
                    console.log( 'This is Glider candidate: ' + this.isGliderCandidate() );

                    if( this.isGliderCandidate() ) {

                        if( 'yes' === this.getElementSettings( '_ob_glider_is_slider' ) ) {
                            this.$element.attr( 'id', 'glider-' + this.getID() ); 
                            this.$element.addClass( 'ob-is-glider' ); 
                            this.generateSwiperStructure();
                        } else if( '' === this.getElementSettings( '_ob_glider_is_slider' ) ) {
                            this.$element.removeClass( 'ob-is-glider' ); 
                            elementor.reloadPreview();
                        } 

                    } else if( this.isOldGliderCandidate() ) { // if not a container  !!!

                        if( 'yes' === this.getElementSettings( '_ob_glider_is_slider' ) ) {
                            this.$element.attr( 'id', 'glider-' + this.getID() ); 
                            this.$element.addClass( 'ob-is-glider' ); 
                            this.generateSwiperOld();
                        } else if( '' === this.getElementSettings( '_ob_glider_is_slider' ) ) {
                            this.$element.removeClass( 'ob-is-glider' ); 
                            elementor.reloadPreview();
                        } 

                    }

                }
            }, 

            generateSwiperStructure: function() {

                if( this.$element.find( '.ob-swiper-bundle' ).length ) return; // bail if the wraping element exists

                var is_boxed = this.$element.hasClass( 'e-con-boxed' ) ? true : false;

                if( ! is_boxed ) this.$element.children( '[data-element_type="container"]' ).wrapAll( '<div class="ob-swiper-bundle swiper"></div>' ); 
                else this.$element.find( '.e-con-inner' ).children( '[data-element_type="container"]' ).wrapAll( '<div class="ob-swiper-bundle swiper"></div>' ); 

                var wrapr = this.$element.find( '.ob-swiper-bundle' );

                wrapr.children( '[data-element_type="container"]' ).addClass( 'swiper-slide' ).wrapAll( '<div class="swiper-wrapper"></div>' );

                // grab the settings...
                var settingz = {};

                settingz.icon_obj = this.getElementSettings( '_ob_glider_nav_icon' );
                var default_next = $( '<div class="swiper-button-next"></div>' );
                var deafult_prev = $( '<div class="swiper-button-prev"></div>' );
                var default_pagi = $( '<div class="swiper-pagination"></div>' );

                // append controls: next prev pagination
                wrapr
                .append( default_next )
                .append( deafult_prev )
                .append( default_pagi );

                if( ! $.isEmptyObject( settingz.icon_obj ) ) {

                    if( 'string' === typeof settingz.icon_obj.value ) {

                        default_next.html( '<i aria-hidden="true" class="' + settingz.icon_obj.value + '"></i>' );
                        deafult_prev.html( '<i aria-hidden="true" class="' + settingz.icon_obj.value + ' fa-flip-horizontal"></i>' );

                    } else {

                        $.get( settingz.icon_obj.value, null, function( data ) {
                            default_next.html( document.adoptNode( $( 'svg', data )[ 0 ] ) );
                        }, 'xml' );
                        $.get( settingz.icon_obj.value, null, function( data ) {
                            deafult_prev.html( document.adoptNode( $( 'svg', data )[ 0 ] ) );
                        }, 'xml' );

                    }

                }

                settingz.pagination_type = this.getElementSettings( '_ob_glider_pagination_type' ) || 'bullets';
                settingz.allowTouchMove = this.getElementSettings( '_ob_glider_allow_touch_move' );
                settingz.autoheight = this.getElementSettings( '_ob_glider_auto_h' ) || 'no';
                settingz.effect = this.getElementSettings( '_ob_glider_effect' ) || 'slide';
                settingz.loop = this.getElementSettings( '_ob_glider_loop' ) || false;
                settingz.direction = this.getElementSettings( '_ob_glider_direction' ) || 'horizontal';
                settingz.parallax = this.getElementSettings( '_ob_glider_parallax' );
                settingz.speed = this.getElementSettings( '_ob_glider_speed' ) || 450;
                var autoplayed = this.getElementSettings( '_ob_glider_autoplay' ) || false;
                if( autoplayed ) {
                    settingz.autoplay = {
                        'delay': this.getElementSettings( '_ob_glider_autoplay_delay' ), 
                    }
                } else settingz.autoplay = false;
                settingz.mousewheel = this.getElementSettings( '_ob_glider_allow_mousewheel' );

                settingz.allowMultiSlides = this.getElementSettings( '_ob_glider_allow_multi_slides' );
                var breakpointsSettings = {},
                breakpoints = elementorFrontend.config.breakpoints;

                // widescreen
                if( undefined !== this.getElementSettings( '_ob_glider_slides_per_view_widescreen' ) ) {
                    breakpointsSettings[breakpoints.xxl] = {
                        slidesPerView: parseInt( this.getElementSettings( '_ob_glider_slides_per_view_widescreen' ) ) || 1,
                        slidesPerGroup: parseInt( this.getElementSettings( '_ob_glider_slides_to_scroll_widescreen' ) ) || 1,
                        spaceBetween: parseInt( this.getElementSettings( '_ob_glider_space_between_widescreen' ) ) || 0,
                    }; 
                }
                // desktop - default
                breakpointsSettings[breakpoints.xl] = {
                    slidesPerView: parseInt( this.getElementSettings( '_ob_glider_slides_per_view' ) ) || 1, 
                    slidesPerGroup: parseInt( this.getElementSettings( '_ob_glider_slides_to_scroll' ) ) || 1, 
                    spaceBetween: parseInt( this.getElementSettings( '_ob_glider_space_between' ) ) || 0, 
                };
                // tablet
                breakpointsSettings[breakpoints.md] = {
                    slidesPerView: parseInt( this.getElementSettings( '_ob_glider_slides_per_view_tablet' ) ) || 1,
                    slidesPerGroup: parseInt( this.getElementSettings( '_ob_glider_slides_to_scroll_tablet' ) ) || 1,
                    spaceBetween: parseInt( this.getElementSettings( '_ob_glider_space_between_tablet' ) ) || 0,
                }; 
                // mobile
                breakpointsSettings[breakpoints.sm] = {
                    slidesPerView: parseInt( this.getElementSettings( '_ob_glider_slides_per_view_mobile' ) ) || 1,
                    slidesPerGroup: parseInt( this.getElementSettings( '_ob_glider_slides_to_scroll_mobile' ) ) || 1,
                    spaceBetween: parseInt( this.getElementSettings( '_ob_glider_space_between_mobile' ) ) || 0,
                }; 

                if( 'fade' === settingz.effect || 'yes' !== settingz.allowMultiSlides ) settingz.breakpoints = {}; // no breakpoints needed in this case
                else settingz.breakpoints = breakpointsSettings; 

                // centered slides - v1.7.9
                settingz.slides_centered = this.getElementSettings( '_ob_glider_centered_slides' ); 
                settingz.slides_centered_bounds = this.getElementSettings( '_ob_glider_centered_bounds_slides' ); 
                settingz.slides_round_lenghts = this.getElementSettings( '_ob_glider_roundlengths_slides' ); 

                // create swiper ----------------------------------------------------------------------------------
                var swiper_config = {
                    allowTouchMove: ( 'yes' === settingz.allowTouchMove ? true : false ), 
                    autoHeight: ( 'yes' === settingz.autoheight ? true : false ), 
                    effect: settingz.effect, 
                    loop: settingz.loop, 
                    direction: ( 'fade' === settingz.effect ? 'horizontal' : settingz.direction ), 
                    parallax: ( 'yes' === settingz.parallax ? true : false ),
                    speed: settingz.speed, 
                    slidesPerView: 1, 
                    slidesPerGroup: 1, 
                    spaceBetween: 0, 
                    breakpoints: settingz.breakpoints, 
                    centeredSlides: ( 'yes' === settingz.slides_centered ? true : false ), 
                    centeredSlidesBounds: ( 'yes' === settingz.slides_centered_bounds ? true : false ), 
                    roundLengths: ( 'yes' === settingz.slides_round_lenghts ? true : false ), 
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination', 
                        type: settingz.pagination_type, 
                        clickable: true, 
                    },
                    autoplay: settingz.autoplay, 
                    mousewheel: ( 'yes' === settingz.mousewheel ? true : false ), 
                    watchOverflow : true, /* gotta force it down */ 
                    on: {
                        init: function () {
                            wrapr.css( 'visibility', 'visible' );
                        },
                    },
                };
                // improved asset loading
                if ( 'undefined' === typeof Swiper ) { // swiper not loaded
                    var tmp_this = this;
                    let chck_if_elementor_utils_loaded = setInterval( function() {

                        if( elementorFrontend.utils ) {
                            clearInterval( chck_if_elementor_utils_loaded ); 
                            const asyncSwiper = elementorFrontend.utils.swiper;
                            new asyncSwiper( wrapr, swiper_config ).then( ( newSwiperInstance ) => {
                                tmp_this.me_the_swiper = newSwiperInstance; 
                                tmp_this.runSyncStuff( tmp_this.me_the_swiper );
                            } );
                        }

                    }, 500 ); // wait for Elementor utils to load entirely

                } else { // otherwise swiper exists
                    this.me_the_swiper = new Swiper( wrapr, swiper_config );
                    this.runSyncStuff( this.me_the_swiper );
                }
                
                if( this.isEdit ) {
                    var TMP_this = this;
                    // the DOM hack to prevent all kinds of shit ... 
                    elementor.channels.editor.on( 'change:container', function( el ) {
                        if( el._parent.model.id === TMP_this.getID() ) {
                            w.dispatchEvent( new Event( 'resize' ) ); // trigger only if dealing with the Glider
                        }
                    } );
                }

            }, 

            generateSwiperOld: function() {

                var wrapr = this.$element.children( '.elementor-container' ).first();
                var wrapr_has_row = $( wrapr ).children( '.elementor-row' ).first(); // chck for elementor-row

                if( wrapr_has_row.length ) wrapr = wrapr_has_row;

                wrapr.children( '[data-element_type="column"]' ).addClass( 'swiper-slide' ).wrapAll( '<div class="swiper-wrapper"></div>' );

                // grab the settings...
                var settingz = {};

                settingz.icon_obj = this.getElementSettings( '_ob_glider_nav_icon' );
                var default_next = $( '<div class="swiper-button-next"></div>' );
                var deafult_prev = $( '<div class="swiper-button-prev"></div>' );
                var default_pagi = $( '<div class="swiper-pagination"></div>' );

                // append controls: next prev pagination
                wrapr
                .append( default_next )
                .append( deafult_prev )
                .append( default_pagi );

                if( ! $.isEmptyObject( settingz.icon_obj ) ) {

                    if( 'string' === typeof settingz.icon_obj.value ) {

                        default_next.html( '<i aria-hidden="true" class="' + settingz.icon_obj.value + '"></i>' );
                        deafult_prev.html( '<i aria-hidden="true" class="' + settingz.icon_obj.value + ' fa-flip-horizontal"></i>' );

                    } else {

                        $.get( settingz.icon_obj.value, null, function( data ) {
                            default_next.html( document.adoptNode( $( 'svg', data )[ 0 ] ) );
                        }, 'xml' );
                        $.get( settingz.icon_obj.value, null, function( data ) {
                            deafult_prev.html( document.adoptNode( $( 'svg', data )[ 0 ] ) );
                        }, 'xml' );

                    }

                }
                
                settingz.pagination_type = this.getElementSettings( '_ob_glider_pagination_type' ) || 'bullets';
                settingz.allowTouchMove = this.getElementSettings( '_ob_glider_allow_touch_move' );
                settingz.autoheight = this.getElementSettings( '_ob_glider_auto_h' ) || 'no';
                settingz.effect = this.getElementSettings( '_ob_glider_effect' ) || 'slide';
                settingz.loop = this.getElementSettings( '_ob_glider_loop' ) || false;
                settingz.direction = this.getElementSettings( '_ob_glider_direction' ) || 'horizontal';
                settingz.parallax = this.getElementSettings( '_ob_glider_parallax' );
                settingz.speed = this.getElementSettings( '_ob_glider_speed' ) || 450;
                var autoplayed = this.getElementSettings( '_ob_glider_autoplay' ) || false;
                if( autoplayed ) {
                    settingz.autoplay = {
                        'delay': this.getElementSettings( '_ob_glider_autoplay_delay' ), 
                    }
                } else settingz.autoplay = false;
                settingz.mousewheel = this.getElementSettings( '_ob_glider_allow_mousewheel' );

                settingz.allowMultiSlides = this.getElementSettings( '_ob_glider_allow_multi_slides' );
                var breakpointsSettings = {},
                breakpoints = elementorFrontend.config.breakpoints;

                // widescreen
                if( undefined !== this.getElementSettings( '_ob_glider_slides_per_view_widescreen' ) ) {
                    breakpointsSettings[breakpoints.xxl] = {
                        slidesPerView: parseInt( this.getElementSettings( '_ob_glider_slides_per_view_widescreen' ) ) || 1,
                        slidesPerGroup: parseInt( this.getElementSettings( '_ob_glider_slides_to_scroll_widescreen' ) ) || 1,
                        spaceBetween: parseInt( this.getElementSettings( '_ob_glider_space_between_widescreen' ) ) || 0,
                    }; 
                }
                // desktop - default
                breakpointsSettings[breakpoints.xl] = {
                    slidesPerView: parseInt( this.getElementSettings( '_ob_glider_slides_per_view' ) ) || 1, 
                    slidesPerGroup: parseInt( this.getElementSettings( '_ob_glider_slides_to_scroll' ) ) || 1, 
                    spaceBetween: parseInt( this.getElementSettings( '_ob_glider_space_between' ) ) || 0, 
                };
                // tablet
                breakpointsSettings[breakpoints.md] = {
                    slidesPerView: parseInt( this.getElementSettings( '_ob_glider_slides_per_view_tablet' ) ) || 1,
                    slidesPerGroup: parseInt( this.getElementSettings( '_ob_glider_slides_to_scroll_tablet' ) ) || 1,
                    spaceBetween: parseInt( this.getElementSettings( '_ob_glider_space_between_tablet' ) ) || 0,
                }; 
                // mobile
                breakpointsSettings[breakpoints.sm] = {
                    slidesPerView: parseInt( this.getElementSettings( '_ob_glider_slides_per_view_mobile' ) ) || 1,
                    slidesPerGroup: parseInt( this.getElementSettings( '_ob_glider_slides_to_scroll_mobile' ) ) || 1,
                    spaceBetween: parseInt( this.getElementSettings( '_ob_glider_space_between_mobile' ) ) || 0,
                }; 

                if( 'fade' === settingz.effect || 'yes' !== settingz.allowMultiSlides ) settingz.breakpoints = {}; // no breakpoints needed in this case
                else settingz.breakpoints = breakpointsSettings; 

                // centered slides - v1.7.9
                settingz.slides_centered = this.getElementSettings( '_ob_glider_centered_slides' ); 
                settingz.slides_centered_bounds = this.getElementSettings( '_ob_glider_centered_bounds_slides' ); 
                settingz.slides_round_lenghts = this.getElementSettings( '_ob_glider_roundlengths_slides' ); 

                // create swiper ----------------------------------------------------------------------------------
                var swiper_config = {
                    allowTouchMove: ( 'yes' === settingz.allowTouchMove ? true : false ), 
                    autoHeight: ( 'yes' === settingz.autoheight ? true : false ), 
                    effect: settingz.effect, 
                    loop: settingz.loop, 
                    direction: ( 'fade' === settingz.effect ? 'horizontal' : settingz.direction ), 
                    parallax: ( 'yes' === settingz.parallax ? true : false ),
                    speed: settingz.speed, 
                    slidesPerView: 1, 
                    slidesPerGroup: 1, 
                    spaceBetween: 0, 
                    breakpoints: settingz.breakpoints, 
                    centeredSlides: ( 'yes' === settingz.slides_centered ? true : false ), 
                    centeredSlidesBounds: ( 'yes' === settingz.slides_centered_bounds ? true : false ), 
                    roundLengths: ( 'yes' === settingz.slides_round_lenghts ? true : false ), 
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination', 
                        type: settingz.pagination_type, 
                        clickable: true, 
                    },
                    autoplay: settingz.autoplay, 
                    mousewheel: ( 'yes' === settingz.mousewheel ? true : false ), 
                    watchOverflow : true, /* gotta force it down */ 
                    on: {
                        init: function () {
                            wrapr.css( 'visibility', 'visible' );
                        },
                    },
                };
                // improved asset loading
                if ( 'undefined' === typeof Swiper ) { // swiper not loaded
                    var tmp_this = this;
                    let chck_if_elementor_utils_loaded = setInterval( function() {

                        if( elementorFrontend.utils ) {
                            clearInterval( chck_if_elementor_utils_loaded ); 
                            const asyncSwiper = elementorFrontend.utils.swiper;
                            new asyncSwiper( wrapr, swiper_config ).then( ( newSwiperInstance ) => {
                                tmp_this.me_the_swiper = newSwiperInstance; 
                                tmp_this.runSyncStuff( tmp_this.me_the_swiper );
                            } );
                        }

                    }, 500 ); // wait for Elementor utils to load entirely

                } else { // otherwise swiper exists
                    this.me_the_swiper = new Swiper( wrapr, swiper_config );
                    this.runSyncStuff( this.me_the_swiper );
                }

                if( this.isEdit ) {
                    var TMP_this = this;
                    // the DOM hack to prevent all kinds of shit ... 
                    elementor.channels.editor.on( 'change:section', function( el ) {
                        if( el._parent.model.id === TMP_this.getID() ) {
                            w.dispatchEvent( new Event( 'resize' ) ); // trigger only if dealing with the Glider
                        }
                    } );
                }

            }, 

            runSyncStuff: function( swiper_ob ) {

                // external control via the CSS class
                this.glider_external_controls = $( 'body' ).find( '[class*="glider-' + this.$element[ 0 ].dataset[ 'id' ] + '-gotoslide-"]' ) || [];

                if( this.glider_external_controls.length ) {

                    this.glider_external_controls.each( function() {
                        this.target_swiper = swiper_ob;
                    } );

                    this.glider_external_controls.on( 'click', function( e ) {

                        var slide_num = parseInt( $( this ).attr( 'class' ).match(/-gotoslide-(\d+)/)[ 1 ] );
                        if( slide_num >= 0 ) this.target_swiper.slideTo( slide_num );

                        e.preventDefault(); // bail
            
                    } );
                }

            }, 
            
        } );

        var handlersList = {

            'section': Glider, 
            'container': Glider, 

        };
        $.each( handlersList, function( widgetName, handlerClass ) {

            elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widgetName, function( $scope ) {
                
                elementorFrontend.elementsHandler.addHandler( handlerClass, { $element: $scope } );

            } );

        } );

    } ); 


} ( jQuery, window ) );