<?php
/**
 * Job Listings widget — list style 1 (same job card as the job archive list view).
 *
 * This template can be overridden by copying it to yourtheme/classified-listing/elementor/jobs/list/style-1.php
 *
 * @package classified-listing-toolkits/templates
 * @version 1.3.1.1
 *
 * @var WP_Query $the_loops
 * @var array    $instance
 * @var array    $card
 */

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;

defined( 'ABSPATH' ) || exit;
?>
<div class="rtcl rtcl-elementor-widget rtcl-job-listings-widget">
	<div class="rtcl-listings rtcl-list-view rtcl-style-1-view">
		<?php
		while ( $the_loops->have_posts() ) :
			$the_loops->the_post();
			$listing = rtcl()->factory->get_listing( get_the_ID() );
			if ( ! $listing ) {
				continue;
			}
			?>
			<div <?php Functions::listing_class( 'rtcl-job-listing', $listing ); ?>>
				<?php Functions::get_template( 'job/listing/card', [ 'listing' => $listing, 'card' => $card ], '', rtcl_job_manager()->get_plugin_template_path() ); ?>
			</div>
		<?php endwhile; ?>
	</div>
	<?php
	if ( ! empty( $instance['rtcl_job_pagination'] ) ) {
		Pagination::pagination( $the_loops, true );
	}
	?>
</div>
