'use strict';

( function ( $, w ) {
    
    var $window = $( w ); 

	function debounce(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	}

    $window.on( 'elementor/frontend/init', function() {

        var ModuleHandler = elementorModules.frontend.handlers.Base, 
        SearchCop; 

        SearchCop = ModuleHandler.extend( {

            onInit: function() {
                ModuleHandler.prototype.onInit.apply( this, arguments );
                if( this.isSearchCop() ) {
                    this.runSearchCop();
                } 
            },

            isSearchCop: function() {
                return ( this.getElementSettings( '_ob_searchcop_use_it' ) === 'yes' );
            }, 

            onElementChange: debounce( function( changedProp ) {

                if( changedProp === '_ob_searchcop_srch_options' ) this.runSearchCop();

            }, 900 ), 

            runSearchCop: function() {

                var search_cop_val = this.getElementSettings( '_ob_searchcop_srch_options' );

                if( 'post' === search_cop_val || 'page' === search_cop_val || 'product' === search_cop_val ) {
                    var this_input_wrapper = this.$element.find( '.elementor-search-form__container' );
                    if( ! this_input_wrapper.length ) return;
                    var param_input = '<input type="hidden" name="post_type" value="' + search_cop_val + '" />';
                    this_input_wrapper.prepend( param_input );
                }

            }
            
        } );

        elementorFrontend.hooks.addAction( 'frontend/element_ready/search-form.default', function( $scope ) {
            elementorFrontend.elementsHandler.addHandler( SearchCop, { $element: $scope } );
        } );

    } ); 


} ( jQuery, window ) );