<?php
/**
 * Template Name: Contact Page(DEPRECATED)
 *
 * Description: This templates uses Google maps to show yor location
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */

get_header(); ?>

<?php if ( sq_option( 'gps_lat' ) && sq_option( 'gps_lon' ) ): ?>

	<?php if ( sq_option( 'gps_key' ) ) {
		$key = '?key=' . sq_option( 'gps_key' );
	} else {
		$key = '';
	}
	?>

	<script src="https://maps.googleapis.com/maps/api/js<?php echo esc_attr( $key ); ?>"></script>
	<script>
		function initialize() {
			var myLatlng = new google.maps.LatLng(<?php echo esc_attr( sq_option( 'gps_lat' ) );?>,<?php echo esc_attr( sq_option( 'gps_lon' ) );?>);
			var mapOptions = {
				zoom: 12,
				center: myLatlng,
				scrollwheel: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};

			var map = new google.maps.Map(document.getElementById('gmap'), mapOptions);

			var contentString = '<div id="mapcontent">' +
				'<img id="logo_img" src="<?php echo esc_url( sq_option( 'logo', get_template_directory_uri() . '/assets/images/logo.png' ) ); ?>" width="200" alt="<?php bloginfo( 'name' ); ?>">' +
				'</div>';

			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});

			var marker = new google.maps.Marker({
				position: myLatlng,
				map: map,
				title: '<?php bloginfo( 'name' ); ?>',
				icon: '<?php echo esc_url( sq_option( 'apple57', get_template_directory_uri() . '/assets/images/icons/apple-touch-icon-57x57.png' ) );?>'
			});

			google.maps.event.addListener(marker, 'click', function () {
				//infowindow.open(map,marker);
			});
		}

		google.maps.event.addDomListener(window, 'load', initialize);

	</script>
	<section>
		<div id="gmap" class="map"></div>
	</section>

<?php endif; ?>


	<!-- MAIN SECTION
	================================================ -->
	<section>
		<div id="main">

			<?php
			/**
			 * Before main part - action
			 */
			do_action( 'kleo_before_main' );
			?>

			<div class="row">
				<div id="main-content" class="twelve columns">


					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', 'page' ); ?>

						<!-- Begin Comments -->
						<?php comments_template( '', true ); ?>
						<!-- End Comments -->

					<?php endwhile; ?>

				</div><!--end twelve-->

			</div><!--end row-->
		</div><!--end main-->

		<?php
		/**
		 * After main part - action
		 */
		do_action( 'kleo_after_main' );
		?>

	</section>
	<!--END MAIN SECTION-->

<?php get_footer(); ?>