'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        Tabbr; 

        Tabbr = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isTabbr() ) {
                    this.$element.addClass( 'ob-use-tabbr' );
                    this.run();
                } 
            },

            isTabbr: function() {
                return ( this.getElementSettings( '_ob_use_tabbr' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_use_tabbr' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_use_tabbr' ) ) {
                        this.$element.addClass( 'ob-use-tabbr' ); 
                        this.run();
                    }
                    else this.$element.removeClass( 'ob-use-tabbr' );
                }
            }, 

            run: function() {

                if( ! this.isTabbr() ) return; 

                // wrap .elementor-tab-title content with span
                if( this.$element.find( '.elementor-tabs-wrapper > .elementor-tab-title > span.ob-tabbr-tab-wrap' ).length ) return;
                this.$element.find( '.elementor-tabs-wrapper > .elementor-tab-title' ).wrapInner( '<span class="ob-tabbr-tab-wrap"></span>' );

            }, 
            
        } );

        var handlersList = {
            'tabs.default': Tabbr, 
        };

        $.each( handlersList, function( widgetName, handlerClass ) {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widgetName, function( $scope ) {
                elementorFrontend.elementsHandler.addHandler( handlerClass, { $element: $scope } );
            } );
        } );

    } ); 


} ( jQuery, window ) );