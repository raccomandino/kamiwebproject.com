'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        ButterButton; 

        ButterButton = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isButterButton() ) {
                    this.$element.addClass( 'ob-is-butterbutton' );
                } 
            },

            isButterButton: function() {
                return ( this.getElementSettings( '_ob_butterbutton_use_it' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_butterbutton_use_it' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_butterbutton_use_it' ) ) this.$element.addClass( 'ob-is-butterbutton' ); 
                    else this.$element.removeClass( 'ob-is-butterbutton' );
                }
            }, 
            
        } );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/button.default', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( ButterButton, { $element: $scope } );
        } );

    } ); 


} ( jQuery, window ) );