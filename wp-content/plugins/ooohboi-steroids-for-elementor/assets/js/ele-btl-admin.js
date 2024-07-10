/* Better Templates Library WP Admin JS */

'use strict';

( function ( $, w ) {
    
    var $window = $( w );

    ///////////////////////////////// Handle export click

    $( 'a.btl-template-export' ).on( 'click', function( e ) {

        e.preventDefault();

        // grab data attr
        let templateID = parseInt( $( this ).data( 'template-id' ) );
        if( ! templateID ) return; // bail

        $.ajax({
            url : ajaxurl,
            data : {
                action : 'btl_export_template', 
                template_id: templateID, 
                nonce: EleBTLLocalized.export_template_nonce, 
            },
            method : 'POST',
            success : function( response ) { console.log( response ); },
            error : function( error ) { console.log( error ); }
        } );

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

    /* modify admin table data */
    let arr_include = [ 'column-elementor_library_type', 'column-instances', 'column-author', 'column-date' ];

    $( 'body.wp-admin.post-type-elementor_library tbody > tr > *' ).each( function( i ) {

        let prefix = $( this ).data( 'colname' ); 
        let curr_class_arr = $( this ).attr( 'class' ).split(/\s+/);
        let overlap = intersect( curr_class_arr, arr_include );

        if( '' != prefix && overlap.length ) {

            let this_text = $( this ).html();

            if( ! $( this ).hasClass( 'column-date' ) ) {
                $( this ).html( '<span class="btl-admin-col-prefix">' + prefix + '</span><span class="btl-admin-col-suffix">' + this_text + '</span>' );
            } else {
                let brExp = /<br\s*\/?>/i; 
                var lines = this_text.split( brExp );
                if( lines.length > 1 ) {
                    $( this ).html( '<span class="btl-admin-col-prefix">' + lines[ 0 ] + '</span><span class="btl-admin-col-suffix">' + lines[ 1 ] + '</span>' );
                }
            }

        }
        
    } );

    function intersect( a, b ) {
        var t;
        if( b.length > a.length ) t = b, b = a, a = t; // indexOf to loop over shorter
        return a.filter( function ( e ) {
            return b.indexOf( e ) > -1;
        } );
    }

} ( jQuery, window ) );