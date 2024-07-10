/* Elementor editor JS */

'use strict';

( function ( $, w ) {
    
    var $window = $( w );

    let switch_editor_style = function() {

        var dark_css_el = $( '#ele-btl-styles-editor-dark' );
        dark_css_el.length || ( dark_css_el = $( '<link>', { id: "ele-btl-styles-editor-dark", rel: "stylesheet", href: EleBTLLocalized.dark_stylesheet_url } ) ),
        elementor.settings.editorPreferences.model.on( 'change:ui_theme', function( e, val ) {
            if( 'light' === val ) {
                dark_css_el.remove(); 
                var light_css = $( '<link>', { id: "ele-btl-styles", rel: "stylesheet", href: EleBTLLocalized.light_stylesheet_url } ); 
                light_css.appendTo( elementorCommon.elements.$body );
                return;
            }
            dark_css_el.attr( 'media', 'auto' === val ? '(prefers-color-scheme: dark)' : '' ).appendTo( elementorCommon.elements.$body );
        } );
    };

    elementor.on( 'panel:init', function() { 
        switch_editor_style();
    } );

    ///////////////////////////////// Ajax set thumbnail

    $( document ).on( 'click', 'a[id*="ele-btl-insert-media_"]', function( e ) {

        e.preventDefault();
        let thiz = $( this );
        // Creates a new media frame
        let frame = wp.media( {
            title: "Add the Template preview",
            button: {
                text: 'Insert'
            },
            multiple: false
        } );
        frame.open();

        // Media Library selection/insert
        frame.on( 'select', function() {
            
            let ele_post_id = thiz.data( 'elebtlid' );
            if( typeof ele_post_id === 'undefined') return;
            let attachment = frame.state().get('selection').first().toJSON();

            $( 'a#ele-btl-insert-media_' + ele_post_id + ' > img' ).css( 'opacity', 0 ); 
            $( 'a#ele-btl-insert-media_' + ele_post_id + ' > span' ).addClass( 'btl-spinner' ); 

            $.post(
                ajaxurl, 
                {
                    post_id: ele_post_id, 
                    thumbnail_id: attachment.id, 
                    action: 'ele_btl_set_featured_image', 
                    nonce: EleBTLLocalized.nonce, 
                },     
                function( response ) {
                    if( 'ele-btl-ok' === response ) {
                        // set the thumbnail in place, immediately
                        $( 'a#ele-btl-insert-media_' + ele_post_id ).closest( '.ele-btl-media-wrapper' ).removeClass( 'default' );
                        $( 'a#ele-btl-insert-media_' + ele_post_id + ' > span' ).removeClass( 'btl-spinner' );
                        $( 'a#ele-btl-insert-media_' + ele_post_id + ' > img' ).attr( 'src', attachment.url ).css( 'opacity', 1 ); 
                    } else console.log( response );
                }, 
            );
            
        } );

    } );

    ///////////////////////////////// Ajax delete thumbnail

    $( document ).on( 'click', 'em[id*="ele-btl-delete-media_"]', function( e ) {

        e.preventDefault();
        
        let thiz = $( this );
        let ele_post_id = thiz.data( 'elebtlid' );
        if( typeof ele_post_id === 'undefined') return;

        $( 'a#ele-btl-insert-media_' + ele_post_id + ' > img' ).css( 'opacity', 0 ); 
        $( 'a#ele-btl-insert-media_' + ele_post_id + ' > span' ).addClass( 'btl-spinner' ); 

        $.post(
            ajaxurl, 
            {
                post_id: ele_post_id, 
                action: 'ele_btl_delete_featured_image', 
                nonce: EleBTLLocalized.nonce, 
            },     
            function( response ) {
                if( 'ele-btl-ok' === response ) {
                    // set the thumbnail in place, immediately
                    $( 'a#ele-btl-insert-media_' + ele_post_id + ' > span' ).removeClass( 'btl-spinner' );
                    thiz.remove();
                    $( 'a#ele-btl-insert-media_' + ele_post_id + ' > img' ).attr( 'src', EleBTLLocalized.dummy_url ).css( 'opacity', 1 ); 
                } else console.log( response );
            }, 
        );

    } );

} ( jQuery, window ) );