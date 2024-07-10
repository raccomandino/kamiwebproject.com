'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        PhotoMorph; 

        PhotoMorph = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isPhotoMorph() ) {
                    this.$element.addClass( 'ob-photomorph' );
                } 
            },

            isPhotoMorph: function() {
                return ( this.getElementSettings( '_ob_photomorph_use' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_photomorph_use' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_photomorph_use' ) ) this.$element.addClass( 'ob-photomorph' ); 
                    else this.$element.removeClass( 'ob-photomorph' );
                }
            }, 
            
        } );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/image.default', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( PhotoMorph, { $element: $scope } );
        } );

    } ); 


} ( jQuery, window ) );