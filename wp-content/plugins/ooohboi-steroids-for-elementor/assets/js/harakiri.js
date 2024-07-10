'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        Harakiri; 

        Harakiri = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isHarakiri() ) {
                    this.$element.addClass( 'ob-harakiri' );
                    if( 'clip' === this.getElementSettings( '_ob_harakiri_text_clip' ) ) this.updateCSS( 'add' );
                } 
            },

            isHarakiri: function() {
                return ( this.getElementSettings( '_ob_use_harakiri' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_harakiri_writing_mode' ) { 
                    if( 'inherit' !== this.getElementSettings( '_ob_harakiri_writing_mode' ) ) this.$element.addClass( 'ob-harakiri' ); 
                    else this.$element.removeClass( 'ob-harakiri' );
                }
                if( changedProp === '_ob_harakiri_text_clip' ) { 
                    if( 'clip' === this.getElementSettings( '_ob_harakiri_text_clip' ) ) {
                        this.updateCSS( 'add' );
                    } else if( '' === this.getElementSettings( '_ob_harakiri_text_clip' ) ) {
                        this.updateCSS( 'remove' );
                    }
                }
            }, 

            updateCSS: function( action ) {

                if( 'add' === action ) {

                    this.$element.find( '.elementor-heading-title' )
                    .css( 'background-clip', 'text' ) 
                    .css( '-webkit-text-fill-color', 'transparent' );

                } 
                if( 'remove' === action ) {

                    this.$element.find( '.elementor-heading-title' )
                    .css( 'background-clip', 'unset' ) 
                    .css( '-webkit-text-fill-color', 'unset' );

                }

            }, 
            
        } );

        var handlersList = {
            'heading.default': Harakiri, 
            'text-editor.default': Harakiri 
        };

        $.each( handlersList, function( widgetName, handlerClass ) {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widgetName, function( $scope ) {
                elementorFrontend.elementsHandler.addHandler( handlerClass, { $element: $scope } );
            } );
        } );

    } ); 


} ( jQuery, window ) );