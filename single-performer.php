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
									<?php

									$field_value = wpautop( get_post_meta( $post->ID, 'pitch', true ) );
									print "<p class='rt-performer-shortbio'><em>" . $field_value . "</em></p>";
									print "<h5 class='rt-performer-title'>Artist Bio</h5>";
									$field_value = the_content();
									print $field_value;

									$reviews = wpautop( get_post_meta( $post->ID, 'reviews', true ) );
									if ( !empty( $reviews ) ) {
										print "<h5 class='rt-performer-reviews'>Reviews</h5>";
										print $reviews;
									}
									?>

								</div>
								<div class="retribal-rcol">
									<?php
									if ( has_post_thumbnail()) {
										$large_image_url = wp_get_attachment_image_url( get_post_thumbnail_id($post->ID), 'medium');
										printf("<img src='%s'>", $large_image_url);
									}
									print "<h5 class='rt-performer-details'>Artist Info</h5>";
									$hometown = get_post_meta( $post->ID, 'hometown', true );
									if ( !empty( $hometown ) ) {
										printf( "<p class='rt-performer-hometown'><b>Hometown:</b> %s</p>", $hometown );
									}
									$official_website = get_post_meta( $post->ID, 'official_website', true );
									if ( !empty( $official_website ) ) {

										$parse = parseUrl($official_website);
										$prettyurl = $parse['domain'];

										printf( "<p class='rt-performer-website'><b>Website:</b> <a href='%s' target='_blank'>%s</a></p>",
											$official_website,
											$prettyurl 
										);
									}

									// Icons
									$iconlist = '';

									$official_youtube = get_post_meta( $post->ID, 'official_youtube', true );
									if ( !empty( $official_youtube ) ) {
										$youtube_icon = '<i class="fa fa-youtube-square fa-2x" aria-hidden="true"></i>';
										$iconlist .= sprintf( "<li><a href='https://www.youtube.com/user/%s' target='_blank'>%s</a></li>",
											$official_youtube,
											$youtube_icon
										);
									}

									// Icons
									$official_facebook = get_post_meta( $post->ID, 'official_facebook', true );
									if ( !empty( $official_facebook ) ) {
										$facebook_icon = '<i class="fa fa-facebook-square fa-2x" aria-hidden="true"></i>';
										$iconlist .= sprintf( "<li><a href='%s' target='_blank'>%s</a></li>",
											$official_facebook,
											$facebook_icon
										);
									}

									$official_twitter = get_post_meta( $post->ID, 'official_twitter', true );
									if ( !empty( $official_twitter ) ) {
										$twitter_icon = '<i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i>';
										$iconlist .= sprintf( "<li><a href='%s' target='_blank'>%s</a></li>",
											$official_twitter,
											$twitter_icon );
									}

									$instagram_gallery = get_post_meta( $post->ID, 'official_instagram', true );
									if ( !empty( $instagram_gallery ) ) {
										$instagram_icon = '<i class="fa fa-instagram fa-2x" aria-hidden="true"></i>';
										$iconlist .= sprintf( "<li><a href='%s' target='_blank'>%s</a></li>",
											$instagram_gallery,
											$instagram_icon );
									}

									$vine = get_post_meta( $post->ID, 'official_vine', true );
									if ( !empty( $vine ) ) {
										$vine_icon = '<i class="fa fa-vine fa-2x" aria-hidden="true"></i>';
										$iconlist .= sprintf( "<li><a href='%s' target='_blank'>%s</a></li>",
											$vine,
											$vine_icon );
									}


									if ( !empty( $iconlist ) ) {
										printf( '<ul class="retribal-soclist">%s</ul>', $iconlist );
									}

									the_terms($post->ID, 'retribal-genre', 'Genres: ');

									$embed = get_post_meta( $post->ID, 'embed_code', true );
									if ( !empty( $embed) ) {
										print $embed;
									}
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