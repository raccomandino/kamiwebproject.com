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
        HoverAnimator; 

        HoverAnimator = ModuleHandler.extend( {

            can_hover: window.matchMedia( '(hover: hover)' ).matches, 

            onInit: function() {

                ModuleHandler.prototype.onInit.apply( this, arguments );

                if( this.isHoverAnimatorContainer() ) {
                    this.$element.addClass( 'ob-is-hoveranimator' );
                } 
                if( this.isHoverAnimatorWidget() ) {

                    if( ! this.isEdit && ! this.can_hover && 'yes' === this.getElementSettings( '_ob_allow_hoveranimator_touch' ) ) {
                        this.$element.removeClass( 'ob-is-hoveranimal' ); 
                        return;
                    }

                    this.$element.addClass( 'ob-is-hoveranimal' );
                    this.run();
                }

            },

            isHoverAnimatorContainer: function() {
                return ( this.getElementSettings( '_ob_column_hoveranimator' ) === 'yes' );
            }, 

            isHoverAnimatorWidget: function() {
                return ( this.getElementSettings( '_ob_allow_hoveranimator' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                /* Column / Container */
                if( changedProp === '_ob_column_hoveranimator' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_column_hoveranimator' ) ) this.$element.addClass( 'ob-is-hoveranimator' ); 
                    else this.$element.removeClass( 'ob-is-hoveranimator' );
                } 
                /* Widgets */
                if( changedProp === '_ob_allow_hoveranimator' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_allow_hoveranimator' ) ) {
                        this.$element.addClass( 'ob-is-hoveranimal' );
                        this.run();
                    } else this.$element.removeClass( 'ob-is-hoveranimal' );
                } 
                /* Widget props */
                if( [ '_ob_hoveranimator_opacity_hover', '_ob_hoveranimator_y_hover', '_ob_hoveranimator_y_hover_alt', '_ob_hoveranimator_x_hover', '_ob_hoveranimator_x_hover_alt', '_ob_hoveranimator_rot_hover', '_ob_hoveranimator_scalex_hover', '_ob_hoveranimator_scaley_hover', '_ob_hoveranimator_blur_hover' ].indexOf( changedProp ) !== -1 ) {
                    debounce( function() { 
                        this.run();
                    }, 500 );
                }
            }, 

			runHoverAnimator: function() {

                var myself = this.$element.find( '.elementor-widget-container' );

                var parent_column = myself.parent().closest( '.ob-is-hoveranimator' );
                if( parent_column.length ) {

                    var top_alt, left_alt, text_node;
                    var col_id  = parent_column.data( 'id' );

                    var opacity = this.getElementSettings( '_ob_hoveranimator_opacity_hover' ) || 1; 
                    var pos_y   = this.getElementSettings( '_ob_hoveranimator_y_hover' ) || 0; 
                    var pos_y_alt = this.getElementSettings( '_ob_hoveranimator_y_hover_alt' ) || '';
                    var pos_x   = this.getElementSettings( '_ob_hoveranimator_x_hover' ) || 0;
                    var pos_x_alt = this.getElementSettings( '_ob_hoveranimator_x_hover_alt' ) || '';
                    var rot     = this.getElementSettings( '_ob_hoveranimator_rot_hover' ) || 0;
                    var scale_x = this.getElementSettings( '_ob_hoveranimator_scalex_hover' ) || 1;
                    var scale_y = this.getElementSettings( '_ob_hoveranimator_scaley_hover' ) || 1;
                    var blur    = this.getElementSettings( '_ob_hoveranimator_blur_hover' ) || 0; 

                    // alt values chckpoint
                    if( '' != $.trim( pos_x_alt ) && undefined !== pos_x_alt ) left_alt = 'calc(' + pos_x_alt + ')';
                    else left_alt = pos_x.size + pos_x.unit;
                    if( '' != $.trim( pos_y_alt ) && undefined !== pos_y_alt ) top_alt = 'calc(' + pos_y_alt + ')';
                    else top_alt  = pos_y.size + pos_y.unit;

                    var hover_css = {
                        'opacity': opacity.size, 
                        'top': top_alt, 
                        'left': left_alt, 
                        'transform': 'rotate(' + rot.size + 'deg) scaleX(' + scale_x.size + ') scaleY(' + scale_y.size + ')', 
                        'filter': 'blur(' + blur.size + blur.unit + ')'
                    };

                    parent_column.on( 'mouseenter.' + col_id, function() {
                        myself.css( hover_css );
                    } );
                    parent_column.on( 'mouseleave.' + col_id, function() {
                        myself.removeAttr( 'style' );
                    } );

                }

			},

            run: function() {
                if( this.isHoverAnimatorWidget() ) this.runHoverAnimator(); 
            }, 

        } );

        var handlersList = {
            'container': HoverAnimator, 
            'column': HoverAnimator, 
            'widget': HoverAnimator, 
        };

        $.each( handlersList, function( widgetName, handlerClass ) {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widgetName, function( $scope ) {
                elementorFrontend.elementsHandler.addHandler( handlerClass, { $element: $scope } );
            } );
        } );

    } ); 


} ( jQuery, window ) );