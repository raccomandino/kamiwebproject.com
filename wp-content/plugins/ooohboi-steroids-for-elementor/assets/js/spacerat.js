'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        SpaceRat; 

        SpaceRat = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isSpaceRat() ) {
                    this.$element.addClass( 'ob-spacerat' ); 
                    this.runRoutine();
                } 
            },

            isSpaceRat: function() {
                return ( this.getElementSettings( '_ob_spacerat_use' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_spacerat_use' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_spacerat_use' ) ) this.$element.addClass( 'ob-spacerat' ); 
                    else this.$element.removeClass( 'ob-spacerat' );
                }
            }, 

            runRoutine: function() {
                if( ! this.isEdit ) {

                    var spacerat_settings = {};

                    try {
                        spacerat_settings = JSON.parse( this.$element.attr( 'data-settings' ) );
                    } catch ( error ) {
                        console.log( error );
                        return;
                    }

                    if( undefined !== spacerat_settings._ob_spacerat_link ) {
                    
                        var spacerat_link = spacerat_settings._ob_spacerat_link;
                        if( '' === spacerat_link.url ) return;

                        this.$element.off( 'click.obSpacerat' );
                        this.$element.on( 'click.obSpacerat', function() {
                            if( spacerat_link.is_external ) window.open( spacerat_link.url ); 
                            else location.href = spacerat_link.url;
                        } );

                    }

                }
            }
            
        } );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/spacer.default', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( SpaceRat, { $element: $scope } );
        } );

    } ); 


} ( jQuery, window ) );