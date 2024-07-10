'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        PoopArt; 

        PoopArt = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isPoopArt() ) {
                    this.$element.addClass( 'ob-has-background-overlay' );
                } 
            },

            isPoopArt: function() {
                return ( this.getElementSettings( '_ob_poopart_use' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_poopart_use' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_poopart_use' ) ) this.$element.addClass( 'ob-has-background-overlay' ); 
                    else this.$element.removeClass( 'ob-has-background-overlay' );
                }
            }, 
            
        } );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( PoopArt, { $element: $scope } );
        } );

    } ); 


} ( jQuery, window ) );