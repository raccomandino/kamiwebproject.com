'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var WidgetStalker; 

        /* Widget Stalker */
        WidgetStalker = elementorModules.frontend.handlers.Base.extend( {

            onInit: function() {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply( this, arguments );
                if( this.isWidgetStalker() ) {
                    this.$element.addClass( 'ob-got-stalker' );
                } 
            },

            isWidgetStalker: function() {
                return ( this.getElementSettings( '_ob_widget_stalker_use' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_widget_stalker_use' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_widget_stalker_use' ) ) this.$element.addClass( 'ob-got-stalker' ); 
                    else this.$element.removeClass( 'ob-got-stalker' );
                }
            }, 
            
        } );

        /* Widget Stalker */
        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( WidgetStalker, { $element: $scope } );
        } );

    } ); 


} ( jQuery, window ) );