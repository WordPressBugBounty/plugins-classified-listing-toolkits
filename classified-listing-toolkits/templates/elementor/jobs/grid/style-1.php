<?php
/**
 * Job Listings widget — grid style 1 (same job card as the job archive grid view).
 *
 * This template can be overridden by copying it to yourtheme/classified-listing/elementor/jobs/grid/style-1.php
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

$columns = sprintf(
	'columns-%d tab-columns-%d mobile-columns-%d',
	! empty( $instance['rtcl_job_columns'] ) ? absint( $instance['rtcl_job_columns'] ) : 3,
	! empty( $instance['rtcl_job_columns_tablet'] ) ? absint( $instance['rtcl_job_columns_tablet'] ) : 2,
	! empty( $instance['rtcl_job_columns_mobile'] ) ? absint( $instance['rtcl_job_columns_mobile'] ) : 1
);
?>
<div class="rtcl rtcl-elementor-widget rtcl-job-listings-widget">
	<div class="rtcl-listings rtcl-grid-view rtcl-style-1-view <?php echo esc_attr( $columns ); ?>">
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
