<?php

use RadiusTheme\ClassifiedListingToolkits\Hooks\Helper;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;

// D5 uses 'rtcl_layout_type', D4 uses 'rtcl_store_view'.
$layout = ! empty( $instance['rtcl_layout_type'] )
	? $instance['rtcl_layout_type']
	: ( ! empty( $instance['rtcl_store_view'] ) ? $instance['rtcl_store_view'] : 'grid' );

$column = intval( ! empty( $instance['rtcl_store_column'] ) ? $instance['rtcl_store_column'] : 3 );
$col_sm = intval( ! empty( $instance['rtcl_store_column_tablet'] ) ? $instance['rtcl_store_column_tablet'] : max( 1, min( 2, $column ) ) );
// D5 uses 'rtcl_store_column_phone', D4 uses 'rtcl_store_column_mobile'.
$col_xs = intval(
	! empty( $instance['rtcl_store_column_phone'] )
		? $instance['rtcl_store_column_phone']
		: ( ! empty( $instance['rtcl_store_column_mobile'] ) ? $instance['rtcl_store_column_mobile'] : 1 )
);

if ( 'list' === $layout ) {
	$stores_class = 'rtcl-stores rtcl-list-view';
} else {
	$stores_class = 'rtcl-stores rtcl-grid-view columns-' . $column . ' columns-sm-' . $col_sm . ' columns-xs-' . $col_xs;
}

// Pagination value: D5 sends 'on'/'off', D4 sends boolean true/false.
$pagination_on = isset( $instance['rtcl_store_pagination'] )
	&& ( 'on' === $instance['rtcl_store_pagination'] || true === $instance['rtcl_store_pagination'] );

?>
<div class="rtcl-stores-wrapper">
	<div class="<?php echo esc_attr( $stores_class ); ?>">
		<?php
		$the_loops = $instance['stores'];
		while ( $the_loops->have_posts() ) :
			$the_loops->the_post();
			$data = [
				'id'       => get_the_ID(),
				'instance' => $instance,
			];
			Functions::get_template( 'divi/listing-store/store-grid-item', $data, '', Helper::get_plugin_template_path() );
		endwhile;
		?>
	</div>

	<?php
	wp_reset_postdata();

	if ( $pagination_on && $the_loops->max_num_pages > 1 ) :
		$paged       = Pagination::get_page_number();
		$total_pages = (int) $the_loops->max_num_pages;
		$range       = 2;

		// Build the list of page numbers to display (always include first, last, and neighbours of current).
		$pages_to_show = [];
		for ( $i = 1; $i <= $total_pages; $i++ ) {
			if ( 1 === $i || $total_pages === $i || abs( $i - $paged ) <= $range ) {
				$pages_to_show[] = $i;
			}
		}
		$pages_to_show = array_unique( $pages_to_show );
		sort( $pages_to_show );
		?>
		<nav class="rtcl-global-pagination-wrap" aria-label="<?php esc_attr_e( 'Pagination', 'classified-listing-toolkits' ); ?>">
			<ul class="rtcl-pagination">
				<?php if ( $paged > 1 ) : ?>
					<li class="page-item">
						<a class="page-link" href="<?php echo esc_url( get_pagenum_link( $paged - 1 ) ); ?>" aria-label="<?php esc_attr_e( 'Previous', 'classified-listing-toolkits' ); ?>">&lt;</a>
					</li>
				<?php endif; ?>

				<?php
				$prev = null;
				foreach ( $pages_to_show as $page_num ) :
					if ( null !== $prev && $page_num - $prev > 1 ) :
						?>
						<li class="page-item disabled"><span class="page-link">&hellip;</span></li>
						<?php
					endif;

					if ( $paged === $page_num ) :
						?>
						<li class="page-item active"><span class="page-link"><?php echo absint( $page_num ); ?></span></li>
						<?php
					else :
						?>
						<li class="page-item">
							<a class="page-link" href="<?php echo esc_url( get_pagenum_link( $page_num ) ); ?>"><?php echo absint( $page_num ); ?></a>
						</li>
						<?php
					endif;

					$prev = $page_num;
				endforeach;
				?>

				<?php if ( $paged < $total_pages ) : ?>
					<li class="page-item">
						<a class="page-link" href="<?php echo esc_url( get_pagenum_link( $paged + 1 ) ); ?>" aria-label="<?php esc_attr_e( 'Next', 'classified-listing-toolkits' ); ?>">&gt;</a>
					</li>
				<?php endif; ?>
			</ul>
		</nav>
	<?php endif; ?>
</div>
