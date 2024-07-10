'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

    $window.on( 'elementor/frontend/init', function() {

        var Pseudo, PseudoContainer; 

        /* pseudo for columns */
        Pseudo = elementorModules.frontend.handlers.Base.extend( {

            onInit: function() {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply( this, arguments );
                if( this.isPseudo() ) {
                    this.$element.addClass( 'ob-is-pseudo' );
                } 
            },

            isPseudo: function() {
                return ( this.getElementSettings( '_ob_column_has_pseudo' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_column_has_pseudo' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_column_has_pseudo' ) ) this.$element.addClass( 'ob-is-pseudo' ); 
                    else this.$element.removeClass( 'ob-is-pseudo' );
                }
            }, 
            
        } );

        /* pseudo for container E3.6+ */
        PseudoContainer = elementorModules.frontend.handlers.Base.extend( {

            onInit: function() {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply( this, arguments );
                if( this.isPseudoContainer() ) {
                    this.$element.addClass( 'ob-is-pseudo' ); 
                    this.removePseudos(); 
                    this.runPseudos();
                } 
            },

            isPseudoContainer: function() {
                return ( this.getElementSettings( '_ob_column_has_pseudo' ) === 'yes' );
            }, 

            onElementChange: function( changedProp ) {
                if( changedProp === '_ob_column_has_pseudo' ) { 
                    if( 'yes' === this.getElementSettings( '_ob_column_has_pseudo' ) ) {
                        this.$element.addClass( 'ob-is-pseudo' ); 
                        this.runPseudos();
                    } else {
                        this.removePseudos();
                        this.$element.removeClass( 'ob-is-pseudo' );
                    }
                }
            }, 

            runPseudos: function() {

                // create 2 dummy containers: BEFORE and AFTER
                if( ! this.$element.children( '.ob-pseudo-before' ).first().length ) $( '<div>', { class: 'ob-pseudo-before' } ).appendTo( this.$element );
                if( ! this.$element.children( '.ob-pseudo-after' ).first().length ) $( '<div>', { class: 'ob-pseudo-after' } ).appendTo( this.$element );

            }, 

            removePseudos: function() {

                // remove 2 dummy containers: BEFORE and AFTER
                this.$element.children( '.ob-pseudo-before' ).first().remove();
                this.$element.children( '.ob-pseudo-after' ).first().remove();

            }, 
            
        } );

        /* pseudo for columns */
        elementorFrontend.hooks.addAction( 'frontend/element_ready/column', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( Pseudo, { $element: $scope } );
        } );

        /* pseudo for container E3.6+ */
        elementorFrontend.hooks.addAction( 'frontend/element_ready/container', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( PseudoContainer, { $element: $scope } );
        } );

    } ); 


} ( jQuery, window ) );