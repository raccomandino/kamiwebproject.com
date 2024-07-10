<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$postid = get_the_ID();

if( $selctSource != 'repeater' ){
	$member_url = get_the_permalink();
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="team-list-content">
		<div class="post-content-image">
				<a rel="<?php echo esc_attr($member_urlNofollow); ?>" href="<?php echo esc_url($member_url); ?>" target="<?php echo esc_attr($member_urlBlank);?>" >
					<?php include L_THEPLUS_INCLUDES_URL. 'team-member/format-image.php'; ?>			
				</a>
			<?php if(!empty($team_social_contnet) && !empty($display_social_icon) && $display_social_icon=='yes'){
				echo $team_social_contnet;
			} ?>
		</div>		
		<div class="post-content-bottom">			
			<?php 
				include L_THEPLUS_INCLUDES_URL. 'team-member/post-meta-title.php';

				if(!empty($designation) && !empty($display_designation) && $display_designation == 'yes'){
					echo $designation;
				} 
			?>
		</div>
		
	</div>
</article>
