<?php

	namespace SFF;

	use SFF\Performer;
	use SFF\Sponsor;
	use SFF\Schedule;
	use SFF\Venue;

	wp_enqueue_script("jquery");
    wp_enqueue_script( 'prettyPhoto', get_stylesheet_directory_uri() . '/js/prettyPhoto_compressed_3.1.5/js/jquery.prettyPhoto.js' );

    wp_enqueue_style( 'prettyPhoto', get_stylesheet_directory_uri() . '/js/prettyPhoto_compressed_3.1.5/css/prettyPhoto.css' );

?>


	<?php if ( have_posts() ) : ?>
		<div class="loop">
			<div class="loop-content">
				<?php while ( have_posts() ) : // The Loop ?>
					<?php the_post(); ?>
					
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


					<?php
					
					// For later
					$venue = new Venue( get_the_ID() );
					// dump( $venue );


					?>

						<!-- Photo rev3 -->
<?php 

	if ( has_post_thumbnail()) {

		$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');

		$wood = get_stylesheet_directory_uri() . "/images/wood-bg.jpg";
		print '<div id="photobg" style="background-image: url(' . $wood 
				. '); 
					margin-left: -26px; 
					margin-top: 20px; 
					background-repeat: no-repeat; 
					background-size: cover; 
					width: 115%; 
					height: 405px;" 
					title="' . the_title_attribute('echo=0') . '">
				</div>';

		print '<div id="photoarea" style="
					background-image: url(' . $large_image_url[0] . '); 
					margin-left: 0; 
					margin-top: -450px; 
					background-repeat: no-repeat; 
					background-size: cover; 
					width: 100%; 
					height: 400px;" 
					title="' . the_title_attribute('echo=0') . '">
				</div>';

	}
	
?>

						<!-- title, meta, and date info -->
						<div class="entry-header clearfix">
							<h1 class="entry-title" style="text-align:left;margin-top:-50px;margin-left:0;padding-left: 9px;margin-bottom:50px;background-color:rgba(0, 0, 0, 0.5);"><?php the_title(); ?></h1>
						</div>
						
						<!-- post content -->
						<div class="entry-content clearfix">

							<div class="tcontainer">

								<div class="trow">
							
	
									<div class="tcontent tcol">

			
							<?php 

								$field_value = get_post_meta( $post->ID, 'short_description', true );
								print "<p><em>" . $field_value . "</em></p>";

								$field_value = get_the_content( );
								// print "<h5>Venue Details</h5>";									
								print do_shortcode($field_value);
								
								print "\n<div class='clearfix'></div>";
								
//								print "<H5>Location</H5>";
//								print do_shortcode('[codespacing_light_map post_ids="' . $post->ID 
//										. '" height="320px" width="100%"  zoom="18" show_overlay ="no"]');
								
							?>
							
									</div>
									<div class="tsidebar tcol">
							
							<?php


								print "<h5>Venue Info</h5>";

								$hometown = get_post_meta( $post->ID, 'capacity', true );
								if ( !empty( $hometown ) ) {
									printf( "<p><b>Capacity:</b> %s</p>", $hometown );		
								}

								$street_address = get_post_meta( $post->ID, 'street_address', true );
								if ( !empty( $street_address ) ) {
									printf( "<p><b>Address:</b> %s</p>", $street_address );
								}
								
								$official_website = get_post_meta( $post->ID, 'official_website', true );
								if ( !empty( $official_website ) ) {

									$parse = parseUrl($official_website);
									$prettyurl = $parse['domain'];

									printf( "<p><b>Website:</b> <a href='%s' target='_blank'>%s</a></p>", $official_website,
												 $prettyurl );		
								}
								
								// Icons
								$iconlist = '';
								

								// Icons
								$official_facebook = get_post_meta( $post->ID, 'official_facebook', true );
								if ( !empty( $official_facebook ) ) {
									$facebook_icon = '<img src="' . get_stylesheet_directory_uri() . '/images/vintage_stamp_icon_pack/facebook64.png">';
									$iconlist .= sprintf( "<li><a href='%s' target='_blank'>%s</a></li>", 
												$official_facebook,
												$facebook_icon 
											);		
								}

								$official_yelp = get_post_meta( $post->ID, 'official_yelp', true );
								if ( !empty( $official_yelp ) ) {
									$yelp_icon = '<img src="' . get_stylesheet_directory_uri() . '/images/vintage_stamp_icon_pack/yelp64.png">';
									$iconlist .= sprintf( "<li><a href='%s' target='_blank'>%s</a></li>", 
												$official_yelp,
												$yelp_icon );		
								}

								$foursquare = get_post_meta( $post->ID, 'official_foursquare', true );
								if ( !empty( $foursquare ) ) {
									$foursquare_icon = '<img src="' . get_stylesheet_directory_uri() . '/images/vintage_stamp_icon_pack/foursquare64.png">';
									$iconlist .= sprintf( "<li><a href='%s' target='_blank'>%s</a></li>", 
												$foursquare,
												$foursquare_icon );		
								}

								if ( !empty( $iconlist ) ) {
									// print "<h5>Social Media</h5>";
									printf( '<ul id="soclist">%s</ul>', $iconlist ); 
								}
		
								if(taxonomy_exists('venue_feature')) {
									print "<h5>Features</h5>";
									the_terms( $post->ID, 'venue_feature', '', ', ', ' ' );			
								}
								
								$sff_settings = maybe_unserialize( get_option( 'folkfest-settings' ) );
								$show_schedule = $sff_settings['sff_show_schedules'];

								// Schedule
								//if ( $show_schedule != false ) {
								if ( $show_schedule != false || ( is_user_logged_in() && current_user_can('edit_posts') ) ) {


								// Schedule
									$schedule  = $venue->schedule;
									if ( !empty( $schedule ) ) {
									
										print "<br><h5>On Stage</h5>";
									
										// sort the items
										foreach ( $schedule as $sidx => $item ) {
											$didx[$item->start_time] = $sidx;
										}
										ksort($didx);
										
										$last_weekday = '';
										
										foreach( $didx as $sidx ) {
										
											$show = $schedule[$sidx];

											// if (is_user_logged_in() && current_user_can('edit_posts')) {
											// 	dump( $show );
											// }
											
											$stime = strtotime( $show->start_time  );
											$showtime = getdate( $stime );
											$sweekday = date( "l, M jS", $stime );
											if ( $sweekday != $last_weekday ) {
												printf( "\n<h6>%s</h6>", $sweekday );
												$last_weekday = $sweekday;
											}
	
											// Look up the venue for this event
											if ( is_array( $show->__relate_performer ) ) {
												// print "<BR>Array";
												$pout = array();
												foreach( $show->__relate_performer as $pidx => $performer_id ) {
	
													$performer = new Performer( $performer_id );
													
													$pname = stripslashes( $performer->post_title );
													$plink = get_permalink( $performer->id );
													$pout[] = sprintf( "<a href='%s'>%s</a>", $plink, $pname );
												}
												
												printf( "\n<div>" );
												printf( "\n%s ", date( "g:i a", $stime ) );
												
												if (!empty( $show->preferredTitle )) {
													printf( "%s: ", $show->preferredTitle );
												}
												
												print implode( ', ', $pout );
												printf( "\n</div>" );
												
											} else {
												$performer = new Performer( $show->__relate_performer );
												
												// print "<BR>Performer ID: " . $performer->id;
												$pname = stripslashes( $performer->post_title );
												if (!empty( $show->preferredTitle )) {
													$pname = $show->preferredTitle;
												}

												$plink = get_permalink( $performer->id );
												printf( "\n<div>%s <a href='%s'>%s</a></div>", date( "g:i a", $stime ), $plink, $pname );
											}
											// dump( $venue );
										}
	
									}

								} else {
									print "\n<!-- Schedule is not available yet. -->\n";
								}									
																
								// Sponsor
								if ( is_array( $venue->__relate_sponsor ) ) {
								
									print "<br><h5>Stage Sponsors</h5>";
									// print "<BR>Array";
									$pout = array();
									foreach( $venue->__relate_sponsor as $pidx => $sponsor_id ) {

										$sponsor = new Sponsor( $sponsor_id );
										
										$pname = stripslashes( $sponsor->post_title );
										$plink = $sponsor->website ;
										if ( $sponsor->_thumbnail_id ) {
											$width = 350;
											$height = 350;
											$spermalink = $sponsor->website;
											$simg_url = wp_get_attachment_image_url( $sponsor->_thumbnail_id, $width . 'x' . $height );
											$pname = sprintf( "<img src='%s' alt='%s'>", $simg_url, $pname );
										}
										$pout[] = sprintf( "<a href='%s'>%s</a>", $plink, $pname );
									}
									
									printf( "\n<div>" );
									// printf( "\n%s ", date( "g:i a", $stime ) );
									print implode( '<br> ', $pout );
									printf( "\n</div>" );
									
								} else if ( !empty( $venue->__relate_sponsor ) ) {
									print "<br><h5>Stage Sponsor</h5>";
									$sponsor_id = $venue->__relate_sponsor;
									$sponsor = new Sponsor( $sponsor_id );
									// print "<BR>Performer ID: " . $performer->id;
									$pname = stripslashes( $sponsor->post_title );
									$plink = $sponsor->website ;
									if ( $sponsor->_thumbnail_id ) {
										$width = 350;
										$height = 350;
										$spermalink = $sponsor->website;
										$simg_url = wp_get_attachment_image_url( $sponsor->_thumbnail_id, $width . 'x' . $height );
										$pname = sprintf( "<img src='%s' alt='%s'>", $simg_url, $pname );
									}
									printf( "\n<div><a href='%s'>%s</a></div>", $plink, $pname );
								}


		
							?>
													
							
									</div>  <!-- End right column -->
								</div> <!-- End row -->
							</div> <!-- End container -->
														
							
						</div> <!-- End content -->
						
						<!-- categories and tags -->
						<div class="entry-footer clearfix">
							<?php wp_link_pages( array( 'before' => '<p class="entry-utility"><strong>' . __( 'Pages:', 'it-l10n-Builder-Essence-White' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
							<?php edit_post_link( __( 'Edit this entry.', 'it-l10n-Builder-Essence-White' ), '<p class="entry-utility edit-entry-link">', '</p>' ); ?>
						</div>
					</div>
					<!-- end .post -->
					
					<?php comments_template(); // include comments template ?>
				<?php endwhile; // end of one post ?>
			</div>
		</div>

		
<script type="text/javascript" charset="utf-8">
   jQuery(document).ready(function($){
      $("a[rel^='prettyPhoto']").prettyPhoto(
      	{
	      	animation_speed: 'fast', /* fast/slow/normal */
	      	default_width: 800,
			default_height: 600,
			theme: 'dark_rounded',
      	}
      );
   });
</script>
		
	<?php else : // do not delete ?>
		<?php do_action( 'builder_template_show_not_found' ); ?>
	<?php endif; // do not delete ?>
<?php

function parseUrl($url) {
    $r  = "^(?:(?P<scheme>\w+)://)?";
    $r .= "(?:(?P<login>\w+):(?P<pass>\w+)@)?";
    $r .= "(?P<host>(?:(?P<subdomain>[\w\.]+)\.)?" . "(?P<domain>\w+\.(?P<extension>\w+)))";
    $r .= "(?::(?P<port>\d+))?";
    $r .= "(?P<path>[\w/]*/(?P<file>\w+(?:\.\w+)?)?)?";
    $r .= "(?:\?(?P<arg>[\w=&]+))?";
    $r .= "(?:#(?P<anchor>\w+))?";
    $r = "!$r!";                                                // Delimiters
    
    preg_match ( $r, $url, $out );
    
    return $out;
}	








?>