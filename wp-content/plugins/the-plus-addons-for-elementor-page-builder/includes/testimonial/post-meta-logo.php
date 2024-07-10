<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	if($tlContentFrom == 'tlrepeater'){
		$testimonial_logo = $testiLogo;
	}else if($tlContentFrom == 'tlrepeater'){
		$testimonial_logo = get_post_meta(get_the_id(), 'theplus_testimonial_logo', true); 
	}
	
if( !empty($testimonial_logo) ){ 
?>
	<div class="testimonial-author-logo"><img src="<?php echo esc_url($testimonial_logo); ?>" /></div>
<?php } ?>