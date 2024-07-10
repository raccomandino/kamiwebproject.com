'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        Postman; 

        Postman = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isPostman() ) {
                    this.$element.addClass( 'ob-postman' );
                } 
            },

            isPostman: function() {
                return ( this.getElementSettings( '_ob_postman_use' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_postman_use' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_postman_use' ) ) this.$element.addClass( 'ob-postman' ); 
                    else this.$element.removeClass( 'ob-postman' );
                }
            }, 
            
        } );

        var handlersList = {
            'theme-post-content.default': Postman, 
            'text-editor.default': Postman, 
        };

        $.each( handlersList, function( widgetName, handlerClass ) {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widgetName, function( $scope ) {
                elementorFrontend.elementsHandler.addHandler( handlerClass, { $element: $scope } );
            } );
        } );

    } ); 


} ( jQuery, window ) );