'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        ContainerExtras; 

        ContainerExtras = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isContainerExtras() ) {
                    this.$element.addClass( 'ob-is-container-extras' ); 
                    this.initContainerExtends();
                } 
            },

            isContainerExtras: function() {
                return ( this.getElementSettings( '_ob_use_container_extras' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_use_container_extras' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_use_container_extras' ) ) this.$element.addClass( 'ob-is-container-extras' ); 
                    else this.$element.removeClass( 'ob-is-container-extras' );
                }
            }, 

            initContainerExtends: function() {
                if( ! this.isEdit ) {
                    var container_settings = {};
                    try {
                        container_settings = JSON.parse( this.$element.attr( 'data-settings' ) );
                    } catch ( error ) {
                        console.log( error );
                        return;
                    }
                    // handle links
                    if( undefined !== container_settings._ob_ce_link ) { 

                        var continer_link = container_settings._ob_ce_link;
                        if( '' === continer_link.url ) { 
                            this.$element.removeClass( 'ob-container-link' );
                            return;
                        } else {
                            this.$element.addClass( 'ob-container-link' );
                        }

                        this.$element.off( 'click' );
                        this.$element.on( 'click', function() {
                            if( continer_link.is_external ) window.open( continer_link.url ); 
                            else location.href = continer_link.url;
                        } );

                    }
                }
            }, 
            
        } );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/container', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( ContainerExtras, { $element: $scope } );
        } );

    } ); 


} ( jQuery, window ) );