"use strict";!function(e,t){e(t);e("a.btl-template-export").on("click",(function(t){t.preventDefault();let l=parseInt(e(this).data("template-id"));l&&e.ajax({url:ajaxurl,data:{action:"btl_export_template",template_id:l,nonce:EleBTLLocalized.export_template_nonce},method:"POST",success:function(e){},error:function(e){}})})),e(document).on("click",'a[id*="ele-btl-insert-media_"]',(function(t){t.preventDefault();let l=e(this),a=wp.media({title:"Add the Template preview",button:{text:"Insert"},multiple:!1});a.open(),a.on("select",(function(){let t=l.data("elebtlid");if(void 0===t)return;let n=a.state().get("selection").first().toJSON();e("a#ele-btl-insert-media_"+t+" > img").css("opacity",0),e("a#ele-btl-insert-media_"+t+" > span").addClass("btl-spinner"),e.post(ajaxurl,{post_id:t,thumbnail_id:n.id,action:"ele_btl_set_featured_image",nonce:EleBTLLocalized.nonce},(function(l){"ele-btl-ok"===l&&(e("a#ele-btl-insert-media_"+t+" > span").removeClass("btl-spinner"),e("a#ele-btl-insert-media_"+t+" > img").attr("src",n.url).css("opacity",1))}))}))})),e(document).on("click",'em[id*="ele-btl-delete-media_"]',(function(t){t.preventDefault();let l=e(this),a=l.data("elebtlid");void 0!==a&&(e("a#ele-btl-insert-media_"+a+" > img").css("opacity",0),e("a#ele-btl-insert-media_"+a+" > span").addClass("btl-spinner"),e.post(ajaxurl,{post_id:a,action:"ele_btl_delete_featured_image",nonce:EleBTLLocalized.nonce},(function(t){"ele-btl-ok"===t&&(e("a#ele-btl-insert-media_"+a+" > span").removeClass("btl-spinner"),l.remove(),e("a#ele-btl-insert-media_"+a+" > img").attr("src",EleBTLLocalized.dummy_url).css("opacity",1))})))}));let l=["column-elementor_library_type","column-instances","column-author","column-date"];e("body.wp-admin.post-type-elementor_library tbody > tr > *").each((function(t){let a=e(this).data("colname"),n=function(e,t){var l;t.length>e.length&&(l=t,t=e,e=l);return e.filter((function(e){return t.indexOf(e)>-1}))}(e(this).attr("class").split(/\s+/),l);if(""!=a&&n.length){let t=e(this).html();if(e(this).hasClass("column-date")){let l=/<br\s*\/?>/i;var i=t.split(l);i.length>1&&e(this).html('<span class="btl-admin-col-prefix">'+i[0]+'</span><span class="btl-admin-col-suffix">'+i[1]+"</span>")}else e(this).html('<span class="btl-admin-col-prefix">'+a+'</span><span class="btl-admin-col-suffix">'+t+"</span>")}}))}(jQuery,window);