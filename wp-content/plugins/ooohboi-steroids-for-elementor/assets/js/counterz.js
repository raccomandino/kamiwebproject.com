'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        Counterz; 

        Counterz = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isCounterz() ) {
                    this.$element.addClass( 'ob-use-counterz' );
                } 
            },

            isCounterz: function() {
                return ( this.getElementSettings( '_ob_use_counterz' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_use_counterz' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_use_counterz' ) ) this.$element.addClass( 'ob-use-counterz' ); 
                    else this.$element.removeClass( 'ob-use-counterz' );
                }
            }, 
            
        } );

        var handlersList = {
            'counter.default': Counterz, 
        };

        $.each( handlersList, function( widgetName, handlerClass ) {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widgetName, function( $scope ) {
                elementorFrontend.elementsHandler.addHandler( handlerClass, { $element: $scope } );
            } );
        } );

    } ); 


} ( jQuery, window ) );