<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if($tlContentFrom == 'tlrepeater'){
	$tsize='';
	$feat_id = $testiImageId;
	if(!empty($feat_id)){
		$featured_image = tp_get_image_rander($feat_id, $tsize);
	}else{
		$featured_image = l_theplus_get_thumb_url();
		$featured_image = '<img src="'.esc_url($featured_image).'" alt="'.esc_attr($testiLabel).'">';
	} 
}else{
	global $post;
	$postid = get_the_ID();
	$featured_image_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
	if( !empty($featured_image_url) ){
		$featured_image = get_the_post_thumbnail_url(get_the_ID(),'tp-image-grid');
		$featured_image = '<img src="'.esc_url($featured_image).'" alt="'.esc_attr(get_the_title()).'">';
	}else{
		$featured_image = l_theplus_get_thumb_url();
		$featured_image = '<img src="'.esc_url($featured_image).'" alt="'.esc_attr(get_the_title()).'">';
	}
}
	
?>
	<div class="testimonial-featured-image">
		<span class="thumb-wrap"><?php echo $featured_image; ?></span>
	</div>