'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        Interactor; 

        Interactor = ModuleHandler.extend( {

            click_count: 0, 

            onInit: function() {

                ModuleHandler.prototype.onInit.apply( this, arguments );

                if( this.isInteractorObject() ) { 
                    this.$element.addClass( 'ob-is-interactor' );
                    this.run();
                } 

            },

            isInteractorObject: function() {
                return ( this.getElementSettings( '_ob_do_interactor' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {

                if( changedProp === '_ob_do_interactor' ) {
                    if( 'yes' === this.getElementSettings( '_ob_do_interactor' ) ) {
                        this.$element.addClass( 'ob-is-interactor' );
                    } else if( '' === this.getElementSettings( '_ob_do_interactor' ) ) { 
                        this.$element.removeClass( 'ob-is-interactor' ); 
                        this.deployInteractor();
                    }
                } 
            }, 

            run: function() {
                if( this.isInteractorObject() ) this.deployInteractor();
            }, 

            deployInteractor: function() {

                var el_settings = {}; 

                // reset actions
                this.$element.off( 'click' );
                this.$element.off( 'mouseenter' );
                this.$element.off( 'mouseleave' );

                el_settings._ob_i_type = this.getElementSettings( '_ob_i_type' ) || 'mouseenter'; 
                el_settings.rptr = this.getElementSettings( '_ob_i_props_repeater' ); 

                // repeater
                var rptr_target = ''; 
                var all_elements = [];
                var me = this.$element;
                var _this = this;

                $.each( el_settings.rptr, function( i, val ) {

                    // target element
                    if( 'other' === val._ob_i_target ) { 
                        // chck for the class name or ID 
                        var target_id = val._ob_i_target_id_or_class.trim();
                        if( ( target_id.startsWith( '.' ) || target_id.startsWith( '#' ) ) && target_id.length > 2 && $( target_id ).length ) {
                            rptr_target = target_id; 
                        } 
                    } else if( 'self' === val._ob_i_target ) {
                        rptr_target = '[data-id="' + me[ 0 ].dataset[ 'id' ] + '"]'; 
                    } 

                    if( 'none' === val._ob_i_property || '' === rptr_target ) return; // bail if none property 

                    var transforms = [ 'translateX', 'translateY', 'scale', 'rotate', 'skewX', 'skewY' ]; // handled differently as a CSS property
                    
                    var prop = val._ob_i_property;

                    var from_obj = val[ '_ob_i_prop_' + prop + '_from' ];
                    var to_obj   = val[ '_ob_i_prop_' + prop + '_to' ];
                    var from_size = ( 'object' === typeof from_obj ) ? from_obj.size : from_obj; 
                    var from_unit = ( 'object' === typeof from_obj ) ? from_obj.unit : ''; 
                    var to_size = ( 'object' === typeof to_obj ) ? to_obj.size : to_obj; 
                    var to_unit = ( 'object' === typeof to_obj ) ? to_obj.unit : ''; 
                    var the_css_from = {}; 
                    var the_css_to = {}; 

                    if( $.inArray( prop, transforms ) === -1 ) {
                        // populate values
                        the_css_from[ prop ] = from_size + from_unit; 
                        the_css_to[ prop ] = to_size + to_unit;
                    } else {
                        // populate values
                        if( 'rotate' === prop || 'skewX' === prop || 'skewY' === prop ) {
                            from_unit = 'deg';
                            to_unit = 'deg';
                        }

                        the_css_from[ 'transform' ] = prop + '(' + from_size + from_unit + ')'; 
                        the_css_to[ 'transform' ] = prop + '(' + to_size + to_unit + ')'; 
                    }

                    // transform origin
                    var transform_origin = val[ '_ob_i_prop_tr_origin' ]; 
                    if( 'none' !== transform_origin ) {
                        the_css_from[ 'transform-origin' ] = transform_origin; 
                    }
                    // transition
                    the_css_from[ 'transition' ] = 'all ' + val._ob_i_duration + 's ' + val._ob_i_easing + ' ' + val._ob_i_delay + 's'; 

                    all_elements.push( { 'target' : rptr_target, 'from' : the_css_from, 'to' : the_css_to } );

                } );

                // merge duplicated properties
                var dupes = []; // duplicates chck
                var new_elements = []; // new build

                $.each( all_elements, function( i, val ) {

                    if( dupes.indexOf( val.target ) !== -1 ) {

                        var index = dupes.indexOf( val.target );
                        var existing = all_elements[ index ];

                        var new_from = merger_objects( existing.from, val.from ); 
                        var new_to   = merger_objects( existing.to, val.to );

                        new_elements[ index ].from = new_from;
                        new_elements[ index ].to = new_to;

                    } else {

                        dupes.push( val.target );
                        new_elements.push( val );

                    }

                } );

                // set initial states
                $.each( new_elements, function( i, val ) { $( val.target ).css( val.from ) } );

                // events
                if( 'click' === el_settings._ob_i_type ) {
                    me.on( 'click', function( e ) {
                        e.preventDefault(); // prevent default
                        if( ! _this.click_count ) {
                            $.each( new_elements, function( i, val ) {
                                $( val.target ).css( val.to ); 
                                _this.click_count = 1;
                            } );
                        } else {
                            $.each( new_elements, function( i, val ) {
                                $( val.target ).css( val.from ); 
                                _this.click_count = 0;
                            } );
                        }
                    } );
                }
                // action mouseenter
                if( 'mouseenter' === el_settings._ob_i_type ) {
                    me.on( 'mouseenter', function( e ) {
                        $.each( new_elements, function( i, val ) {
                            $( val.target ).css( val.to ); 
                        } );
                    } );
                    me.on( 'mouseleave', function( e ) {
                        $.each( new_elements, function( i, val ) {
                            $( val.target ).css( val.from ); 
                        } );
                    } );
                }
            }, 

        } );

        // merge all properties into 1
        function merger_objects( obj1, obj2 ) {
            $.each( obj2, function( key, value ) {
               obj1[ key ] = value;    
            } );

           return obj1;
        }

        var handlersList = {
            'container': Interactor, 
            'widget': Interactor, 
        };

        $.each( handlersList, function( widgetName, handlerClass ) {

            elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widgetName, function( $scope ) {
                elementorFrontend.elementsHandler.addHandler( handlerClass, { $element: $scope } );
            } );

        } );

    } ); 

} ( jQuery, window ) ); 