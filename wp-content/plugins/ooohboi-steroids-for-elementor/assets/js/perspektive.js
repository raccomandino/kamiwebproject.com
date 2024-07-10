'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        Perspektive; 

        Perspektive = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isPerspektive() ) {
                    this.$element.addClass( 'ob-use-perspektive' );
                } 
            },

            isPerspektive: function() {
                return ( this.getElementSettings( '_ob_perspektive_use' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_perspektive_use' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_perspektive_use' ) ) this.$element.addClass( 'ob-use-perspektive' ); 
                    else this.$element.removeClass( 'ob-use-perspektive' );
                }
            }, 
            
        } );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( Perspektive, { $element: $scope } );
        } );

    } ); 


} ( jQuery, window ) );