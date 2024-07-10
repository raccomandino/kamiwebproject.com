'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        Commentz; 

        Commentz = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isCommentz() ) {
                    this.$element.addClass( 'ob-commentz' );
                } 
            },

            isCommentz: function() {
                return ( this.getElementSettings( '_ob_commentz_use' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_commentz_use' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_commentz_use' ) ) this.$element.addClass( 'ob-commentz' ); 
                    else this.$element.removeClass( 'ob-commentz' );
                }
            }, 
            
        } );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/post-comments.theme_comments', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( Commentz, { $element: $scope } );
        } );

    } ); 


} ( jQuery, window ) );