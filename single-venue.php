<?php
get_header();
?>

<?php ?>
	<div class="loop">
		<main id="main" class="loop-content">
			<?php while ( have_posts() ) : // The Loop ?>
				<?php the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content clearfix">
						<div>
							<div class="retribal-title">
								<h1><?php the_title() ?></h1>
							</div>
							<div>
								<div class="retribal-lcol">
									<?php the_content();?>
								</div>
								<div class="retribal-rcol">
									<?php
									if ( has_post_thumbnail()) {
										$large_image_url = wp_get_attachment_image_url( get_post_thumbnail_id($post->ID), 'medium');
										printf("<img class='rt-venue-img' src='%s'>", $large_image_url);
									}
									print "<h5 class='rt-venue-title'>Venue Info</h5>";
									$capacity = get_post_meta( $post->ID, 'capacity', true );
									if ( !empty( $capacity ) ) {
										printf( "<p class='rt-venue-capacity'><b>Capacity:</b> %s</p>", $capacity );
									}
									$official_website = get_post_meta( $post->ID, 'official_website', true );
									if ( !empty( $official_website ) ) {

										$parse = parseUrl($official_website);
										$prettyurl = $parse['domain'];

										printf( "<p class='rt-venue-website'><b>Website:</b> <a href='%s' target='_blank'>%s</a></p>",
											 $official_website,
											 $prettyurl 
											);
									}

									// Icons
									$iconlist = '';

									// Icons
									$official_facebook = get_post_meta( $post->ID, 'official_facebook', true );
									if ( !empty( $official_facebook ) ) {
										$facebook_icon = '<i class="fa fa-facebook-square fa-2x" aria-hidden="true"></i>';
										$iconlist .= sprintf( "<li><a href='%s' target='_blank'>%s</a></li>",
											$official_facebook,
											$facebook_icon
										);
									}

									$official_yelp = get_post_meta( $post->ID, 'official_yelp', true );
									if ( !empty( $official_yelp ) ) {
										$yelp_icon = '<i class="fa fa-yelp fa-2x" aria-hidden="true"></i>';
										$iconlist .= sprintf( "<li><a href='%s' target='_blank'>%s</a></li>",
											$official_yelp,
											$yelp_icon );
									}


									$official_foursquare = get_post_meta( $post->ID, 'official_foursqure', true );
									if ( !empty( $official_foursquare ) ) {
										$foursquare_icon = '<i class="fa fa-foursquare fa-2x" aria-hidden="true"></i>';
										$iconlist .= sprintf( "<li><a href='%s' target='_blank'>%s</a></li>",
											$official_foursquare,
											$foursquare_icon );
									}


									if ( !empty( $iconlist ) ) {
										printf( '<ul class="retribal-soclist">%s</ul>', $iconlist );
									}

									$embed = get_post_meta( $post->ID, 'embed_code', true );
									if ( !empty( $embed) ) {
										print $embed;
									}

									the_terms($post->ID, 'retribal-venue-feature', 'Features: ');

									?>

								</div>  <!-- End right column -->
							</div> <!-- End row -->
						</div> <!-- End container -->


					</div> <!-- End content -->

					<!-- categories and tags -->
					<div class="entry-footer clearfix">
						<?php wp_link_pages( array( 'before' => '<p class="entry-utility"><strong>' . __( 'Pages:' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
						<?php edit_post_link( __( 'Edit this entry.' ), '<p class="entry-utility edit-entry-link">', '</p>' ); ?>
					</div>
				</div>
				<!-- end .post -->

				<?php comments_template(); // include comments template ?>
			<?php endwhile; // end of one post ?>
		</main>
	</div>
<?php
get_footer();
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