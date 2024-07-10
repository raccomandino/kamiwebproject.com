/* Elementor editor JS */
'use strict';

( function ( $, w ) {

    var $window = $( w );
    var switch_editor_style = function() {

        var dark_css_el = $( '#ooohboi-steroids-styles-editor-dark-css' );
        dark_css_el.length || ( dark_css_el = $( '<link>', { id: "ooohboi-steroids-styles-editor-dark-css", rel: "stylesheet", href: SteroidsEditorLocalized.dark_stylesheet_url } ) ),
        elementor.settings.editorPreferences.model.on( 'change:ui_theme', function( e, val ) {
            if( 'light' === val ) {
                dark_css_el.remove(); 
                var light_css = $( '<link>', { id: "ooohboi-steroids-styles-editor-css", rel: "stylesheet", href: SteroidsEditorLocalized.light_stylesheet_url } ); 
                light_css.appendTo( elementorCommon.elements.$body );
                return;
            }
            dark_css_el.attr( 'media', 'auto' === val ? '(prefers-color-scheme: dark)' : '' ).appendTo( elementorCommon.elements.$body );
        } );
    };

    elementor.on( 'panel:init', function() { 
        switch_editor_style();
    } );

} ( jQuery, window ) );