<?php
/**
 * Divi 5 initialization.
 *
 * @package ClassifiedListingToolkits
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

// Define Divi 5 specific constants.
if ( ! defined( 'RTCL_TOOLKITS_DIVI5_PATH' ) ) {
	define( 'RTCL_TOOLKITS_DIVI5_PATH', CLASSIFIED_LISTING_TOOLKITS_PATH . '/includes/divi-5/' );
}

if ( ! defined( 'RTCL_TOOLKITS_DIVI5_URL' ) ) {
	define( 'RTCL_TOOLKITS_DIVI5_URL', CLASSIFIED_LISTING_TOOLKITS_URL . '/includes/divi-5/' );
}

// Require module files.
require_once RTCL_TOOLKITS_DIVI5_PATH . 'server/Modules/Modules.php';

/**
 * Enqueue Divi 5 Visual Builder Assets.
 *
 * @since 1.2.5
 */
function rtcl_toolkits_divi5_enqueue_visual_builder_assets() {
	\ET\Builder\VisualBuilder\Assets\PackageBuildManager::register_package_build(
		[
			'name'    => 'rtcl-toolkits-divi5-visual-builder',
			'version' => CLASSIFIED_LISTING_TOOLKITS_VERSION,
			'script'  => [
				'src'                => RTCL_TOOLKITS_DIVI5_URL . 'assets/js/rtcl-toolkits-divi5.js',
				'deps'               => [
					'divi-module-library',
					'divi-vendor-wp-hooks',
					'react',
					'jquery-core',
					'divi-rest',
					'wp-hooks',
				],
				'enqueue_top_window' => false,
				'enqueue_app_window' => true,
			],
		]
	);
}
add_action( 'divi_visual_builder_assets_before_enqueue_scripts', 'rtcl_toolkits_divi5_enqueue_visual_builder_assets' );

/**
 * Enqueue frontend styles for Divi 5 modules.
 *
 * @since 1.2.5
 */
function rtcl_toolkits_divi5_enqueue_frontend_styles() {
	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		$css_file    = RTCL_TOOLKITS_DIVI5_PATH . 'assets/css/rtcl-divi5-frontend.css';
		$css_version = file_exists( $css_file ) ? filemtime( $css_file ) : CLASSIFIED_LISTING_TOOLKITS_VERSION;

		wp_enqueue_style(
			'rtcl-toolkits-divi5-frontend',
			RTCL_TOOLKITS_DIVI5_URL . 'assets/css/rtcl-divi5-frontend.css',
			[],
			$css_version
		);
	}
}
add_action( 'wp_enqueue_scripts', 'rtcl_toolkits_divi5_enqueue_frontend_styles' );

/**
 * Enqueue Swiper slider scripts for Divi 5 listings slider module on frontend.
 *
 * @since 1.2.5
 */
function rtcl_toolkits_divi5_enqueue_slider_scripts() {
	// Enqueue swiper + rtcl-public for both frontend and VB iframe.
	// These are registered by the classified-listing core plugin.
	wp_enqueue_style( 'swiper' );
	wp_enqueue_script( 'swiper' );
	wp_enqueue_script( 'rtcl-public' );

	// Enqueue Pro assets when Pro is active.
	if ( defined( 'RTCL_PRO_VERSION' ) ) {
		// Core common script (AJAX nonce, utilities).
		wp_enqueue_script( 'rtcl-common' );

		// Pro public JS + CSS.
		wp_enqueue_style( 'rtcl-pro-public' );
		wp_enqueue_script( 'rtcl-pro-public' );

		// Single listing script — needed for quick view modal gallery/slider init.
		wp_enqueue_script( 'rtcl-single-listing' );

		// PhotoSwipe for gallery lightbox in quick view modal.
		if ( wp_script_is( 'photoswipe', 'registered' ) ) {
			wp_enqueue_script( 'photoswipe' );
			wp_enqueue_script( 'photoswipe-ui-default' );
			wp_enqueue_style( 'photoswipe' );
			wp_enqueue_style( 'photoswipe-default-skin' );
		}

		// Zoom for image hover zoom in quick view.
		if ( wp_script_is( 'zoom', 'registered' ) ) {
			wp_enqueue_script( 'zoom' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'rtcl_toolkits_divi5_enqueue_slider_scripts' );

/**
 * Register REST API endpoint for Visual Builder data.
 *
 * @since 1.2.5
 */
function rtcl_toolkits_divi5_register_rest_routes() {
	register_rest_route( 'rtcl-toolkits/v1', '/divi5-data', [
		'methods'             => 'GET',
		'callback'            => 'rtcl_toolkits_divi5_get_data',
		'permission_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	] );

	register_rest_route( 'rtcl-toolkits/v1', '/listings-grid-preview', [
		'methods'             => 'POST',
		'callback'            => 'rtcl_toolkits_divi5_listings_grid_preview',
		'permission_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	] );

	register_rest_route( 'rtcl-toolkits/v1', '/all-locations-preview', [
		'methods'             => 'POST',
		'callback'            => 'rtcl_toolkits_divi5_all_locations_preview',
		'permission_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	] );

	register_rest_route( 'rtcl-toolkits/v1', '/listings-slider-preview', [
		'methods'             => 'POST',
		'callback'            => 'rtcl_toolkits_divi5_listings_slider_preview',
		'permission_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	] );

	register_rest_route( 'rtcl-toolkits/v1', '/single-location-preview', [
		'methods'             => 'POST',
		'callback'            => 'rtcl_toolkits_divi5_single_location_preview',
		'permission_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	] );

	register_rest_route( 'rtcl-toolkits/v1', '/listings-list-preview', [
		'methods'             => 'POST',
		'callback'            => 'rtcl_toolkits_divi5_listings_list_preview',
		'permission_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	] );

	register_rest_route( 'rtcl-toolkits/v1', '/store-preview', [
		'methods'             => 'POST',
		'callback'            => 'rtcl_toolkits_divi5_store_preview',
		'permission_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	] );
}
add_action( 'rest_api_init', 'rtcl_toolkits_divi5_register_rest_routes' );

/**
 * REST callback: Return server-rendered listings grid HTML for VB preview.
 *
 * @since 1.2.5
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function rtcl_toolkits_divi5_listings_grid_preview( $request ) {
	$params = $request->get_json_params();
	if ( ! is_array( $params ) ) {
		$params = [];
	}

	$settings = [
		'gridStyle'        => rtcl_toolkits_divi5_validate_enum( $params['gridStyle'] ?? 'style-1', [ 'style-1', 'style-2' ], 'style-1' ),
		'gridColumn'       => rtcl_toolkits_divi5_validate_int( $params['gridColumn'] ?? 3, 1, 6, 3 ),
		'gridColumnTablet' => rtcl_toolkits_divi5_validate_int( $params['gridColumnTablet'] ?? 2, 1, 6, 2 ),
		'gridColumnPhone'  => rtcl_toolkits_divi5_validate_int( $params['gridColumnPhone'] ?? 1, 1, 6, 1 ),
		'listingTypes'     => sanitize_text_field( $params['listingTypes'] ?? 'all' ),
		'categories'       => sanitize_text_field( $params['categories'] ?? '' ),
		'locations'        => sanitize_text_field( $params['locations'] ?? '' ),
		'perPage'          => rtcl_toolkits_divi5_validate_int( $params['perPage'] ?? 10, 1, 100, 10 ),
		'pagination'       => 'off',
		'orderby'          => rtcl_toolkits_divi5_validate_enum( $params['orderby'] ?? 'date', [ 'date', 'title', 'ID', 'price', 'views', 'rand' ], 'date' ),
		'order'            => rtcl_toolkits_divi5_validate_enum( $params['order'] ?? 'desc', [ 'asc', 'desc' ], 'desc' ),
		'imageSize'        => sanitize_text_field( $params['imageSize'] ?? 'rtcl-thumbnail' ),
		'noListingText'    => sanitize_text_field( $params['noListingText'] ?? 'No Listing Found' ),
		'showImage'        => rtcl_toolkits_divi5_validate_toggle( $params['showImage'] ?? 'on' ),
		'showDescription'  => rtcl_toolkits_divi5_validate_toggle( $params['showDescription'] ?? 'off' ),
		'contentLimit'     => rtcl_toolkits_divi5_validate_int( $params['contentLimit'] ?? 20, 1, 500, 20 ),
		'showBadge'        => rtcl_toolkits_divi5_validate_toggle( $params['showBadge'] ?? 'on' ),
		'showDate'         => rtcl_toolkits_divi5_validate_toggle( $params['showDate'] ?? 'on' ),
		'showViews'        => rtcl_toolkits_divi5_validate_toggle( $params['showViews'] ?? 'on' ),
		'showAdType'       => rtcl_toolkits_divi5_validate_toggle( $params['showAdType'] ?? 'on' ),
		'showLocation'     => rtcl_toolkits_divi5_validate_toggle( $params['showLocation'] ?? 'on' ),
		'showCategory'     => rtcl_toolkits_divi5_validate_toggle( $params['showCategory'] ?? 'on' ),
		'showPrice'        => rtcl_toolkits_divi5_validate_toggle( $params['showPrice'] ?? 'on' ),
		'showAuthor'       => rtcl_toolkits_divi5_validate_toggle( $params['showAuthor'] ?? 'on' ),
		'showCustomFields' => rtcl_toolkits_divi5_validate_toggle( $params['showCustomFields'] ?? 'off' ),
		'showFavourites'   => rtcl_toolkits_divi5_validate_toggle( $params['showFavourites'] ?? 'off' ),
		'showQuickView'    => rtcl_toolkits_divi5_validate_toggle( $params['showQuickView'] ?? 'off' ),
		'showCompare'      => rtcl_toolkits_divi5_validate_toggle( $params['showCompare'] ?? 'off' ),
	];

	$html = rtcl_toolkits_divi5_render_listings_preview( $settings );

	return rest_ensure_response( [
		'html'       => $html,
		'foundPosts' => ! empty( $html ) && strpos( $html, esc_html( $settings['noListingText'] ) ) === false,
	] );
}

/**
 * Render listings grid HTML for VB preview.
 * Self-contained: does not depend on Divi module classes being loaded.
 *
 * @since 1.2.5
 * @param array $settings Module settings.
 * @return string Rendered HTML.
 */
function rtcl_toolkits_divi5_render_listings_preview( $settings ) {
	if ( ! function_exists( 'rtcl' ) ) {
		return '<p>Classified Listing plugin is not active.</p>';
	}

	// Parse taxonomy IDs (handles pipe-separated, comma-separated, and array).
	$categories_list = rtcl_toolkits_divi5_parse_taxonomy_ids( $settings['categories'] ?? '' );
	$location_list   = rtcl_toolkits_divi5_parse_taxonomy_ids( $settings['locations'] ?? '' );

	// Validate enum inputs (defense-in-depth in case called outside the REST callback).
	$orderby           = rtcl_toolkits_divi5_validate_enum( $settings['orderby'] ?? 'date', [ 'date', 'title', 'ID', 'price', 'views', 'rand' ], 'date' );
	$order             = rtcl_toolkits_divi5_validate_enum( $settings['order'] ?? 'desc', [ 'asc', 'desc' ], 'desc' );
	$listings_per_page = $settings['perPage'] ?? '10';
	$listing_type      = $settings['listingTypes'] ?? 'all';

	// Build WP_Query args.
	$meta_queries = [];
	$the_args     = [
		'post_type'      => rtcl()->post_type,
		'posts_per_page' => intval( $listings_per_page ),
		'post_status'    => 'publish',
		'tax_query'      => [
			'relation' => 'AND',
		],
	];

	// Handle orderby.
	if ( ! empty( $order ) && ! empty( $orderby ) ) {
		switch ( $orderby ) {
			case 'price':
				$the_args['meta_key'] = $orderby;
				$the_args['orderby']  = 'meta_value_num';
				$the_args['order']    = $order;
				break;
			case 'views':
				$the_args['meta_key'] = '_views';
				$the_args['orderby']  = 'meta_value_num';
				$the_args['order']    = $order;
				break;
			case 'rand':
				$the_args['orderby'] = $orderby;
				break;
			default:
				$the_args['orderby'] = $orderby;
				$the_args['order']   = $order;
		}
	}

	// Category filter.
	if ( ! empty( $categories_list ) ) {
		$the_args['tax_query'][] = [
			'taxonomy' => rtcl()->category,
			'terms'    => $categories_list,
			'field'    => 'term_id',
			'operator' => 'IN',
		];
	}

	// Location filter.
	if ( ! empty( $location_list ) ) {
		$the_args['tax_query'][] = [
			'taxonomy' => rtcl()->location,
			'terms'    => $location_list,
			'field'    => 'term_id',
			'operator' => 'IN',
		];
	}

	// Listing type filter.
	if ( $listing_type && 'all' !== $listing_type
		&& class_exists( '\Rtcl\Helpers\Functions' )
		&& in_array( $listing_type, array_keys( \Rtcl\Helpers\Functions::get_listing_types() ), true )
		&& ! \Rtcl\Helpers\Functions::is_ad_type_disabled()
	) {
		$meta_queries[] = [
			'key'     => 'ad_type',
			'value'   => $listing_type,
			'compare' => '=',
		];
	}

	$count_meta_queries = count( $meta_queries );
	if ( $count_meta_queries ) {
		$the_args['meta_query'] = ( $count_meta_queries > 1 ) ? array_merge( [ 'relation' => 'AND' ], $meta_queries ) : $meta_queries;
	}

	// Execute query.
	add_filter( 'excerpt_more', '__return_empty_string' );
	$the_loops = new \WP_Query( $the_args );

	// Map settings to template instance format.
	$instance = [
		'rtcl_grid_style'         => $settings['gridStyle'],
		'rtcl_grid_column'         => $settings['gridColumn'],
		'rtcl_grid_column_tablet'  => $settings['gridColumnTablet'],
		'rtcl_grid_column_phone'   => $settings['gridColumnPhone'],
		'rtcl_listing_types'       => $settings['listingTypes'],
		'rtcl_listing_categories' => $settings['categories'],
		'rtcl_listing_location'   => $settings['locations'],
		'rtcl_listing_per_page'   => $settings['perPage'],
		'rtcl_listing_pagination' => $settings['pagination'],
		'rtcl_orderby'            => $settings['orderby'],
		'rtcl_sortby'             => $settings['order'],
		'rtcl_image_size'         => $settings['imageSize'],
		'rtcl_no_listing_text'    => $settings['noListingText'],
		'rtcl_show_image'         => $settings['showImage'],
		'rtcl_show_description'   => $settings['showDescription'],
		'rtcl_content_limit'      => $settings['contentLimit'],
		'rtcl_show_labels'        => $settings['showBadge'],
		'rtcl_show_date'          => $settings['showDate'],
		'rtcl_show_views'         => $settings['showViews'],
		'rtcl_show_ad_types'      => $settings['showAdType'],
		'rtcl_show_location'      => $settings['showLocation'],
		'rtcl_show_category'      => $settings['showCategory'],
		'rtcl_show_price'         => $settings['showPrice'],
		'rtcl_show_user'          => $settings['showAuthor'],
		'rtcl_show_custom_fields' => $settings['showCustomFields'],
		'rtcl_show_favourites'    => $settings['showFavourites'],
		'rtcl_show_quick_view'    => $settings['showQuickView'],
		'rtcl_show_compare'       => $settings['showCompare'],
	];

	$style          = rtcl_toolkits_divi5_validate_enum( $settings['gridStyle'] ?? 'style-1', [ 'style-1', 'style-2' ], 'style-1' );
	$template_style = 'divi/listing-ads/grid/' . $style;
	$template_path  = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::get_plugin_template_path();

	$data = [
		'template'      => $template_style,
		'instance'      => $instance,
		'the_loops'     => $the_loops,
		'view'          => 'grid',
		'style'         => $style,
		'template_path' => $template_path,
	];

	$data = apply_filters( 'rtcl_divi_filter_listing_data', $data );

	if ( $the_loops->found_posts ) {
		$output = \Rtcl\Helpers\Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
	} elseif ( ! empty( $settings['noListingText'] ) ) {
		$output = '<h3>' . esc_html( $settings['noListingText'] ) . '</h3>';
	} else {
		$output = '';
	}

	wp_reset_postdata();

	return $output;
}

/**
 * Parse taxonomy IDs from pipe-separated, comma-separated, or array values.
 *
 * @since 1.2.5
 * @param mixed $value Taxonomy IDs.
 * @return array Integer IDs.
 */
/**
 * REST callback: Return server-rendered listings list HTML for VB preview.
 */
function rtcl_toolkits_divi5_listings_list_preview( $request ) {
	$params = $request->get_json_params();
	if ( ! is_array( $params ) ) { $params = []; }

	$settings = [
		'listStyle'        => rtcl_toolkits_divi5_validate_enum( $params['listStyle'] ?? 'style-1', [ 'style-1' ], 'style-1' ),
		'listingTypes'     => sanitize_text_field( $params['listingTypes'] ?? 'all' ),
		'categories'       => sanitize_text_field( $params['categories'] ?? '' ),
		'locations'        => sanitize_text_field( $params['locations'] ?? '' ),
		'perPage'          => rtcl_toolkits_divi5_validate_int( $params['perPage'] ?? 10, 1, 100, 10 ),
		'pagination'       => 'off',
		'orderby'          => rtcl_toolkits_divi5_validate_enum( $params['orderby'] ?? 'date', [ 'date', 'title', 'ID', 'price', 'views', 'rand' ], 'date' ),
		'order'            => rtcl_toolkits_divi5_validate_enum( $params['order'] ?? 'desc', [ 'asc', 'desc' ], 'desc' ),
		'imageSize'        => sanitize_text_field( $params['imageSize'] ?? 'rtcl-thumbnail' ),
		'noListingText'    => sanitize_text_field( $params['noListingText'] ?? 'No Listing Found' ),
		'showImage'        => rtcl_toolkits_divi5_validate_toggle( $params['showImage'] ?? 'on' ),
		'showDescription'  => rtcl_toolkits_divi5_validate_toggle( $params['showDescription'] ?? 'off' ),
		'contentLimit'     => rtcl_toolkits_divi5_validate_int( $params['contentLimit'] ?? 20, 1, 500, 20 ),
		'showBadge'        => rtcl_toolkits_divi5_validate_toggle( $params['showBadge'] ?? 'on' ),
		'showDate'         => rtcl_toolkits_divi5_validate_toggle( $params['showDate'] ?? 'on' ),
		'showViews'        => rtcl_toolkits_divi5_validate_toggle( $params['showViews'] ?? 'on' ),
		'showAdType'       => rtcl_toolkits_divi5_validate_toggle( $params['showAdType'] ?? 'on' ),
		'showLocation'     => rtcl_toolkits_divi5_validate_toggle( $params['showLocation'] ?? 'on' ),
		'showCategory'     => rtcl_toolkits_divi5_validate_toggle( $params['showCategory'] ?? 'on' ),
		'showPrice'        => rtcl_toolkits_divi5_validate_toggle( $params['showPrice'] ?? 'on' ),
		'showAuthor'       => rtcl_toolkits_divi5_validate_toggle( $params['showAuthor'] ?? 'on' ),
		'showCustomFields' => rtcl_toolkits_divi5_validate_toggle( $params['showCustomFields'] ?? 'off' ),
		'showFavourites'   => rtcl_toolkits_divi5_validate_toggle( $params['showFavourites'] ?? 'off' ),
		'showQuickView'    => rtcl_toolkits_divi5_validate_toggle( $params['showQuickView'] ?? 'off' ),
		'showCompare'      => rtcl_toolkits_divi5_validate_toggle( $params['showCompare'] ?? 'off' ),
	];

	$instance = [
		'rtcl_list_style'         => $settings['listStyle'],
		'rtcl_listing_types'      => $settings['listingTypes'],
		'rtcl_listing_categories' => $settings['categories'],
		'rtcl_listing_location'   => $settings['locations'],
		'rtcl_listing_per_page'   => $settings['perPage'],
		'rtcl_listing_pagination' => 'off',
		'rtcl_orderby'            => $settings['orderby'],
		'rtcl_sortby'             => $settings['order'],
		'rtcl_image_size'         => $settings['imageSize'],
		'rtcl_no_listing_text'    => $settings['noListingText'],
		'rtcl_show_image'         => $settings['showImage'],
		'rtcl_show_description'   => $settings['showDescription'],
		'rtcl_content_limit'      => $settings['contentLimit'],
		'rtcl_show_labels'        => $settings['showBadge'],
		'rtcl_show_date'          => $settings['showDate'],
		'rtcl_show_views'         => $settings['showViews'],
		'rtcl_show_ad_types'      => $settings['showAdType'],
		'rtcl_show_location'      => $settings['showLocation'],
		'rtcl_show_category'      => $settings['showCategory'],
		'rtcl_show_price'         => $settings['showPrice'],
		'rtcl_show_user'          => $settings['showAuthor'],
		'rtcl_show_custom_fields'  => $settings['showCustomFields'],
		'rtcl_show_favourites'     => $settings['showFavourites'],
		'rtcl_show_quick_view'     => $settings['showQuickView'],
		'rtcl_show_compare'        => $settings['showCompare'],
		'rtcl_show_details_button' => 'off',
		'rtcl_verified_user_base'  => '',
	];

	// Build WP_Query.
	$categories_list = rtcl_toolkits_divi5_parse_taxonomy_ids( $settings['categories'] );
	$location_list   = rtcl_toolkits_divi5_parse_taxonomy_ids( $settings['locations'] );
	$listing_type    = $settings['listingTypes'];

	$the_args = [
		'post_type'      => rtcl()->post_type,
		'posts_per_page' => intval( $settings['perPage'] ),
		'post_status'    => 'publish',
		'tax_query'      => [ 'relation' => 'AND' ],
	];

	$orderby = $settings['orderby'];
	$order   = $settings['order'];
	switch ( $orderby ) {
		case 'price':
			$the_args['meta_key'] = 'price';
			$the_args['orderby']  = 'meta_value_num';
			$the_args['order']    = $order;
			break;
		case 'views':
			$the_args['meta_key'] = '_views';
			$the_args['orderby']  = 'meta_value_num';
			$the_args['order']    = $order;
			break;
		case 'rand':
			$the_args['orderby'] = 'rand';
			break;
		default:
			$the_args['orderby'] = $orderby;
			$the_args['order']   = $order;
	}

	if ( ! empty( $categories_list ) ) {
		$the_args['tax_query'][] = [ 'taxonomy' => rtcl()->category, 'terms' => $categories_list, 'field' => 'term_id', 'operator' => 'IN' ];
	}
	if ( ! empty( $location_list ) ) {
		$the_args['tax_query'][] = [ 'taxonomy' => rtcl()->location, 'terms' => $location_list, 'field' => 'term_id', 'operator' => 'IN' ];
	}
	if ( $listing_type && 'all' !== $listing_type && ! \Rtcl\Helpers\Functions::is_ad_type_disabled() ) {
		$the_args['meta_query'] = [ [ 'key' => 'ad_type', 'value' => $listing_type, 'compare' => '=' ] ];
	}

	add_filter( 'excerpt_more', '__return_empty_string' );
	$the_loops = new \WP_Query( $the_args );

	$style          = $settings['listStyle'];
	$template_style = 'divi/listing-ads/list/' . $style;
	$template_path  = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::get_plugin_template_path();

	if ( $the_loops->found_posts ) {
		$data = [
			'template'      => $template_style,
			'instance'      => $instance,
			'the_loops'     => $the_loops,
			'view'          => 'list',
			'style'         => $style,
			'template_path' => $template_path,
		];
		$html = \Rtcl\Helpers\Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
	} else {
		$html = '<p style="padding:20px;text-align:center;color:#666;">' . esc_html( $settings['noListingText'] ) . '</p>';
	}

	wp_reset_postdata();

	return rest_ensure_response( [ 'html' => $html ] );
}

/**
 * REST callback: Return server-rendered store listing HTML for VB preview.
 *
 * @since 1.2.5
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function rtcl_toolkits_divi5_store_preview( $request ) {
	if ( ! defined( 'RTCL_PRO_VERSION' ) || ! defined( 'RTCL_STORE_VERSION' ) ) {
		return rest_ensure_response( [ 'html' => '<p class="rtcl-store-notice" style="padding:20px;text-align:center;color:#e74c3c;">Classified Listing Pro and Store plugins are required.</p>' ] );
	}

	$params = $request->get_json_params();
	if ( ! is_array( $params ) ) {
		$params = [];
	}

	$settings = [
		'layoutType'        => rtcl_toolkits_divi5_validate_enum( $params['layoutType'] ?? 'grid', [ 'grid', 'list' ], 'grid' ),
		'gridColumn'        => rtcl_toolkits_divi5_validate_int( $params['gridColumn'] ?? 3, 1, 6, 3 ),
		'gridColumnTablet'  => rtcl_toolkits_divi5_validate_int( $params['gridColumnTablet'] ?? 2, 1, 6, 2 ),
		'gridColumnPhone'   => rtcl_toolkits_divi5_validate_int( $params['gridColumnPhone'] ?? 1, 1, 6, 1 ),
		'storeCategories'   => sanitize_text_field( $params['storeCategories'] ?? '' ),
		'perPage'           => rtcl_toolkits_divi5_validate_int( $params['perPage'] ?? 6, 1, 100, 6 ),
		'orderby'           => rtcl_toolkits_divi5_validate_enum( $params['orderby'] ?? 'name', [ 'name', 'ID', 'date', 'rand' ], 'name' ),
		'order'             => rtcl_toolkits_divi5_validate_enum( $params['order'] ?? 'asc', [ 'asc', 'desc' ], 'asc' ),
		'showImage'         => rtcl_toolkits_divi5_validate_toggle( $params['showImage'] ?? 'on' ),
		'showName'          => rtcl_toolkits_divi5_validate_toggle( $params['showName'] ?? 'on' ),
		'showDescription'   => rtcl_toolkits_divi5_validate_toggle( $params['showDescription'] ?? 'on' ),
		'showListingsCount' => rtcl_toolkits_divi5_validate_toggle( $params['showListingsCount'] ?? 'on' ),
		'showContact'       => rtcl_toolkits_divi5_validate_toggle( $params['showContact'] ?? 'on' ),
		'showSocialLinks'   => rtcl_toolkits_divi5_validate_toggle( $params['showSocialLinks'] ?? 'on' ),
		'paginationEnabled' => rtcl_toolkits_divi5_validate_toggle( $params['paginationEnabled'] ?? 'off' ),
		'noStoreText'       => sanitize_text_field( $params['noStoreText'] ?? 'No Store Found' ),
	];

	$html = rtcl_toolkits_divi5_render_store_preview( $settings );

	return rest_ensure_response( [ 'html' => $html ] );
}

/**
 * Render store listing HTML for VB preview.
 *
 * @since 1.2.5
 * @param array $settings Module settings.
 * @return string Rendered HTML.
 */
function rtcl_toolkits_divi5_render_store_preview( $settings ) {
	if ( ! function_exists( 'rtclStore' ) ) {
		return '<p style="padding:20px;text-align:center;color:#666;">Classified Listing Store plugin is not active.</p>';
	}

	$post_type      = rtclStore()->post_type;
	$category_ids   = rtcl_toolkits_divi5_parse_taxonomy_ids( $settings['storeCategories'] );

	$the_args = [
		'post_type'      => $post_type,
		'posts_per_page' => intval( $settings['perPage'] ),
		'post_status'    => 'publish',
	];

	// Handle orderby.
	switch ( $settings['orderby'] ) {
		case 'name':
			$the_args['orderby'] = 'title';
			$the_args['order']   = $settings['order'];
			break;
		case 'ID':
			$the_args['orderby'] = 'ID';
			$the_args['order']   = $settings['order'];
			break;
		case 'date':
			$the_args['orderby'] = 'date';
			$the_args['order']   = $settings['order'];
			break;
		case 'rand':
			$the_args['orderby'] = 'rand';
			break;
		default:
			$the_args['orderby'] = 'title';
			$the_args['order']   = $settings['order'];
	}

	// Add store category filter.
	if ( ! empty( $category_ids ) ) {
		$store_category      = property_exists( rtclStore(), 'category' ) ? rtclStore()->category : 'store_category';
		$the_args['tax_query'] = [
			[
				'taxonomy' => $store_category,
				'terms'    => $category_ids,
				'field'    => 'term_id',
				'operator' => 'IN',
			],
		];
	}

	$the_loops = new \WP_Query( $the_args );

	$instance = [
		'rtcl_layout_type'          => $settings['layoutType'],
		'rtcl_store_column'         => $settings['gridColumn'],
		'rtcl_store_column_tablet'  => $settings['gridColumnTablet'],
		'rtcl_store_column_phone'   => $settings['gridColumnPhone'],
		'rtcl_show_image'           => $settings['showImage'],
		'rtcl_show_name'            => $settings['showName'],
		'rtcl_show_description'     => $settings['showDescription'],
		'rtcl_show_listings_count'  => $settings['showListingsCount'],
		'rtcl_show_contact'         => $settings['showContact'],
		'rtcl_show_social_links'    => $settings['showSocialLinks'],
		'rtcl_store_pagination'     => $settings['paginationEnabled'],
		'rtcl_no_store_text'        => $settings['noStoreText'],
		'stores'                    => $the_loops,
	];

	$template_path = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::get_plugin_template_path();

	if ( $the_loops->found_posts ) {
		$data = [
			'template'      => 'divi/listing-store/grid-store',
			'instance'      => $instance,
			'stores'        => $the_loops,
			'style'         => $settings['layoutType'],
			'view'          => $settings['layoutType'],
			'template_path' => $template_path,
		];
		$output = \Rtcl\Helpers\Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
	} elseif ( ! empty( $settings['noStoreText'] ) ) {
		$output = '<p style="padding:20px;text-align:center;color:#666;">' . esc_html( $settings['noStoreText'] ) . '</p>';
	} else {
		$output = '';
	}

	wp_reset_postdata();

	return $output;
}

/**
 * Validate a string against a whitelist of allowed values.
 *
 * @param mixed  $value   Input value.
 * @param array  $allowed Whitelist of safe values.
 * @param string $default Fallback when not in whitelist.
 * @return string
 */
function rtcl_toolkits_divi5_validate_enum( $value, array $allowed, $default ) {
	$value = sanitize_text_field( (string) $value );
	return in_array( $value, $allowed, true ) ? $value : $default;
}

/**
 * Validate and clamp an integer parameter.
 *
 * @param mixed $value   Input value.
 * @param int   $min     Minimum allowed value (inclusive).
 * @param int   $max     Maximum allowed value (inclusive).
 * @param int   $default Fallback when out of range.
 * @return int
 */
function rtcl_toolkits_divi5_validate_int( $value, $min, $max, $default ) {
	$int = (int) $value;
	if ( $int < $min || $int > $max ) {
		return $default;
	}
	return $int;
}

/**
 * Validate an on/off toggle parameter.
 *
 * @param mixed $value Input value.
 * @return string 'on' or 'off'.
 */
function rtcl_toolkits_divi5_validate_toggle( $value ) {
	return 'on' === (string) $value ? 'on' : 'off';
}

function rtcl_toolkits_divi5_parse_taxonomy_ids( $value ) {
	if ( empty( $value ) ) {
		return [];
	}

	if ( is_array( $value ) ) {
		$ids = array_values( $value );
	} elseif ( is_string( $value ) ) {
		$separator = strpos( $value, '|' ) !== false ? '|' : ',';
		$ids       = array_filter( explode( $separator, $value ) );
	} else {
		return [];
	}

	$valid_ids = array_filter(
		$ids,
		function ( $id ) {
			return is_numeric( $id ) && intval( $id ) > 0;
		}
	);

	return ! empty( $valid_ids ) ? array_map( 'intval', $valid_ids ) : [];
}

/**
 * REST callback: Return server-rendered all-locations HTML for VB preview.
 *
 * @since 1.2.5
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function rtcl_toolkits_divi5_all_locations_preview( $request ) {
	$params = $request->get_json_params();
	if ( ! is_array( $params ) ) {
		$params = [];
	}

	$settings = [
		'style'              => rtcl_toolkits_divi5_validate_enum( $params['style'] ?? 'style-1', [ 'style-1' ], 'style-1' ),
		'gridColumn'         => rtcl_toolkits_divi5_validate_int( $params['gridColumn'] ?? 3, 1, 6, 3 ),
		'locations'          => sanitize_text_field( $params['locations'] ?? '' ),
		'locationLimit'      => rtcl_toolkits_divi5_validate_int( $params['locationLimit'] ?? 12, 1, 200, 12 ),
		'orderby'            => rtcl_toolkits_divi5_validate_enum( $params['orderby'] ?? 'name', [ 'name', 'term_id', 'count', 'custom', 'none' ], 'name' ),
		'order'              => rtcl_toolkits_divi5_validate_enum( $params['order'] ?? 'asc', [ 'asc', 'desc' ], 'asc' ),
		'hideEmpty'          => rtcl_toolkits_divi5_validate_toggle( $params['hideEmpty'] ?? 'off' ),
		'showCount'          => rtcl_toolkits_divi5_validate_toggle( $params['showCount'] ?? 'on' ),
		'showDescription'    => rtcl_toolkits_divi5_validate_toggle( $params['showDescription'] ?? 'off' ),
		'showChildLocations' => rtcl_toolkits_divi5_validate_toggle( $params['showChildLocations'] ?? 'off' ),
		'childLocationLimit' => rtcl_toolkits_divi5_validate_int( $params['childLocationLimit'] ?? 5, 1, 50, 5 ),
		'contentAlignment'   => rtcl_toolkits_divi5_validate_enum( $params['contentAlignment'] ?? 'center', [ 'left', 'center', 'right' ], 'center' ),
	];

	$html = rtcl_toolkits_divi5_render_locations_preview( $settings );

	return rest_ensure_response( [
		'html' => $html,
	] );
}

/**
 * Render all-locations HTML for VB preview.
 *
 * @since 1.2.5
 * @param array $settings Module settings.
 * @return string Rendered HTML.
 */
/**
 * REST callback: Return server-rendered listings slider HTML for VB preview.
 *
 * @since 1.2.5
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function rtcl_toolkits_divi5_listings_slider_preview( $request ) {
	$params = $request->get_json_params();
	if ( ! is_array( $params ) ) {
		$params = [];
	}

	$settings = [
		'sliderStyle'         => rtcl_toolkits_divi5_validate_enum( $params['sliderStyle'] ?? 'style-1', [ 'style-1' ], 'style-1' ),
		'slidesPerView'       => rtcl_toolkits_divi5_validate_int( $params['slidesPerView'] ?? 3, 1, 6, 3 ),
		'slidesPerViewTablet' => rtcl_toolkits_divi5_validate_int( $params['slidesPerViewTablet'] ?? 2, 1, 6, 2 ),
		'slidesPerViewPhone'  => rtcl_toolkits_divi5_validate_int( $params['slidesPerViewPhone'] ?? 1, 1, 6, 1 ),
		'slidesToScroll'      => rtcl_toolkits_divi5_validate_int( $params['slidesToScroll'] ?? 1, 1, 6, 1 ),
		'loop'                => rtcl_toolkits_divi5_validate_toggle( $params['loop'] ?? 'on' ),
		'autoplay'            => rtcl_toolkits_divi5_validate_toggle( $params['autoplay'] ?? 'on' ),
		'autoplaySpeed'       => rtcl_toolkits_divi5_validate_int( $params['autoplaySpeed'] ?? 3000, 100, 30000, 3000 ),
		'showArrows'          => rtcl_toolkits_divi5_validate_toggle( $params['showArrows'] ?? 'on' ),
		'showDots'            => rtcl_toolkits_divi5_validate_toggle( $params['showDots'] ?? 'on' ),
		'listingTypes'        => sanitize_key( $params['listingTypes'] ?? 'all' ),
		'categories'          => sanitize_text_field( $params['categories'] ?? '' ),
		'locations'           => sanitize_text_field( $params['locations'] ?? '' ),
		'perPage'             => rtcl_toolkits_divi5_validate_int( $params['perPage'] ?? 10, 1, 100, 10 ),
		'orderby'             => rtcl_toolkits_divi5_validate_enum( $params['orderby'] ?? 'date', [ 'date', 'title', 'ID', 'price', 'views', 'rand' ], 'date' ),
		'order'               => rtcl_toolkits_divi5_validate_enum( $params['order'] ?? 'desc', [ 'asc', 'desc' ], 'desc' ),
		'imageSize'           => sanitize_text_field( $params['imageSize'] ?? 'rtcl-thumbnail' ),
		'noListingText'       => sanitize_text_field( $params['noListingText'] ?? 'No Listing Found' ),
		'showImage'           => rtcl_toolkits_divi5_validate_toggle( $params['showImage'] ?? 'on' ),
		'showDescription'     => rtcl_toolkits_divi5_validate_toggle( $params['showDescription'] ?? 'off' ),
		'contentLimit'        => rtcl_toolkits_divi5_validate_int( $params['contentLimit'] ?? 20, 1, 200, 20 ),
		'showBadge'           => rtcl_toolkits_divi5_validate_toggle( $params['showBadge'] ?? 'on' ),
		'showDate'            => rtcl_toolkits_divi5_validate_toggle( $params['showDate'] ?? 'on' ),
		'showViews'           => rtcl_toolkits_divi5_validate_toggle( $params['showViews'] ?? 'on' ),
		'showAdType'          => rtcl_toolkits_divi5_validate_toggle( $params['showAdType'] ?? 'on' ),
		'showLocation'        => rtcl_toolkits_divi5_validate_toggle( $params['showLocation'] ?? 'on' ),
		'showCategory'        => rtcl_toolkits_divi5_validate_toggle( $params['showCategory'] ?? 'on' ),
		'showPrice'           => rtcl_toolkits_divi5_validate_toggle( $params['showPrice'] ?? 'on' ),
		'showAuthor'          => rtcl_toolkits_divi5_validate_toggle( $params['showAuthor'] ?? 'on' ),
		'showCustomFields'    => rtcl_toolkits_divi5_validate_toggle( $params['showCustomFields'] ?? 'off' ),
		'showFavourites'      => rtcl_toolkits_divi5_validate_toggle( $params['showFavourites'] ?? 'off' ),
		'showQuickView'       => rtcl_toolkits_divi5_validate_toggle( $params['showQuickView'] ?? 'off' ),
		'showCompare'         => rtcl_toolkits_divi5_validate_toggle( $params['showCompare'] ?? 'off' ),
	];

	// Build instance matching template keys.
	$instance = [
		'rtcl_slider_style'         => $settings['sliderStyle'],
		'rtcl_grid_column'          => $settings['slidesPerView'],
		'rtcl_grid_column_tablet'   => $settings['slidesPerViewTablet'],
		'rtcl_grid_column_phone'    => $settings['slidesPerViewPhone'],
		'rtcl_slider_loop'          => $settings['loop'],
		'rtcl_slider_autoplay'      => $settings['autoplay'],
		'rtcl_autoplay_speed'       => $settings['autoplaySpeed'],
		'rtcl_slider_auto_height'   => 'off',
		'rtcl_slider_stop_on_hover' => 'off',
		'rtcl_slider_dot'           => $settings['showDots'],
		'rtcl_slider_arrow'         => $settings['showArrows'],
		'rtcl_listing_types'        => $settings['listingTypes'],
		'rtcl_image_size'           => $settings['imageSize'],
		'rtcl_no_listing_text'      => $settings['noListingText'],
		'rtcl_show_image'           => $settings['showImage'],
		'rtcl_show_description'     => $settings['showDescription'],
		'rtcl_content_limit'        => $settings['contentLimit'],
		'rtcl_show_labels'          => $settings['showBadge'],
		'rtcl_show_date'            => $settings['showDate'],
		'rtcl_show_views'           => $settings['showViews'],
		'rtcl_show_ad_types'        => $settings['showAdType'],
		'rtcl_show_location'        => $settings['showLocation'],
		'rtcl_show_category'        => $settings['showCategory'],
		'rtcl_show_price'           => $settings['showPrice'],
		'rtcl_show_user'            => $settings['showAuthor'],
		'rtcl_show_favourites'      => $settings['showFavourites'],
		'rtcl_show_quick_view'      => $settings['showQuickView'],
		'rtcl_show_compare'         => $settings['showCompare'],
		'rtcl_show_custom_fields'   => $settings['showCustomFields'],
		'rtcl_verified_user_base'   => '',
	];

	// Build WP_Query.
	$categories_list = rtcl_toolkits_divi5_parse_taxonomy_ids( $settings['categories'] );
	$location_list   = rtcl_toolkits_divi5_parse_taxonomy_ids( $settings['locations'] );
	$listing_type    = $settings['listingTypes'];

	$the_args = [
		'post_type'      => rtcl()->post_type,
		'posts_per_page' => intval( $settings['perPage'] ),
		'post_status'    => 'publish',
		'tax_query'      => [ 'relation' => 'AND' ],
	];

	$orderby = $settings['orderby'];
	$order   = $settings['order'];
	switch ( $orderby ) {
		case 'price':
			$the_args['meta_key'] = 'price';
			$the_args['orderby']  = 'meta_value_num';
			$the_args['order']    = $order;
			break;
		case 'views':
			$the_args['meta_key'] = '_views';
			$the_args['orderby']  = 'meta_value_num';
			$the_args['order']    = $order;
			break;
		case 'rand':
			$the_args['orderby'] = 'rand';
			break;
		default:
			$the_args['orderby'] = $orderby;
			$the_args['order']   = $order;
	}

	if ( ! empty( $categories_list ) ) {
		$the_args['tax_query'][] = [ 'taxonomy' => rtcl()->category, 'terms' => $categories_list, 'field' => 'term_id', 'operator' => 'IN' ];
	}
	if ( ! empty( $location_list ) ) {
		$the_args['tax_query'][] = [ 'taxonomy' => rtcl()->location, 'terms' => $location_list, 'field' => 'term_id', 'operator' => 'IN' ];
	}
	if ( $listing_type && 'all' !== $listing_type && ! \Rtcl\Helpers\Functions::is_ad_type_disabled() ) {
		$the_args['meta_query'] = [ [ 'key' => 'ad_type', 'value' => $listing_type, 'compare' => '=' ] ];
	}

	add_filter( 'excerpt_more', '__return_empty_string' );
	$the_loops = new \WP_Query( $the_args );

	$style          = $settings['sliderStyle'];
	$template_style = 'divi/listing-slider/' . $style;
	$template_path  = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::get_plugin_template_path();

	if ( $the_loops->found_posts ) {
		$data = [
			'template'      => $template_style,
			'instance'      => $instance,
			'the_loops'     => $the_loops,
			'view'          => 'slider',
			'style'         => $style,
			'template_path' => $template_path,
		];
		$html = \Rtcl\Helpers\Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
	} else {
		$html = '<p style="padding:20px;text-align:center;color:#666;">' . esc_html( $settings['noListingText'] ) . '</p>';
	}

	wp_reset_postdata();

	return rest_ensure_response( [ 'html' => $html ] );
}

function rtcl_toolkits_divi5_render_locations_preview( $settings ) {
	if ( ! function_exists( 'rtcl' ) ) {
		return '<p>Classified Listing plugin is not active.</p>';
	}

	// Validate enum inputs (defense-in-depth in case called outside the REST callback).
	$args = [
		'taxonomy'   => rtcl()->location,
		'hide_empty' => 'on' === $settings['hideEmpty'],
		'number'     => intval( $settings['locationLimit'] ),
		'orderby'    => rtcl_toolkits_divi5_validate_enum( $settings['orderby'] ?? 'name', [ 'name', 'term_id', 'count', 'custom', 'none' ], 'name' ),
		'order'      => rtcl_toolkits_divi5_validate_enum( $settings['order'] ?? 'asc', [ 'asc', 'desc' ], 'asc' ),
	];

	if ( 'custom' === $settings['orderby'] ) {
		$args['orderby']  = 'meta_value_num';
		$args['meta_key'] = '_rtcl_order';
	}

	// Parse location IDs (pipe or comma separated).
	$location_ids = rtcl_toolkits_divi5_parse_taxonomy_ids( $settings['locations'] ?? '' );
	if ( ! empty( $location_ids ) ) {
		$args['include'] = $location_ids;
	}

	$terms = get_terms( $args );
	if ( is_wp_error( $terms ) ) {
		$terms = [];
	}

	$template_settings = [
		'rtcl_location_style'    => $settings['style'],
		'rtcl_grid_column'       => $settings['gridColumn'],
		'rtcl_location'          => $settings['locations'],
		'rtcl_location_limit'    => $settings['locationLimit'],
		'rtcl_orderby'           => $settings['orderby'],
		'rtcl_order'             => $settings['order'],
		'rtcl_hide_empty'        => $settings['hideEmpty'],
		'rtcl_show_count'        => $settings['showCount'],
		'rtcl_description'          => $settings['showDescription'],
		'rtcl_show_child_locations' => $settings['showChildLocations'],
		'rtcl_child_location_limit' => $settings['childLocationLimit'],
		'rtcl_content_alignment'    => $settings['contentAlignment'],
		'rtcl_content_limit'     => '20',
	];

	$style          = rtcl_toolkits_divi5_validate_enum( $settings['style'] ?? 'style-1', [ 'style-1' ], 'style-1' );
	$template_style = 'divi/all-location/' . $style;
	$template_path  = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::get_plugin_template_path();

	$data = [
		'template'      => $template_style,
		'settings'      => $template_settings,
		'terms'         => $terms,
		'style'         => $style,
		'template_path' => $template_path,
	];

	$data = apply_filters( 'rtcl_divi_filter_all_locations_data', $data );

	if ( ! empty( $data['terms'] ) ) {
		return \Rtcl\Helpers\Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
	}

	return '<p class="rtcl-no-locations">' . esc_html__( 'No locations found.', 'classified-listing-toolkits' ) . '</p>';
}

/**
 * REST callback: Return server-rendered single location HTML for VB preview.
 *
 * @since 1.2.5
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function rtcl_toolkits_divi5_single_location_preview( $request ) {
	$params = $request->get_json_params();
	if ( ! is_array( $params ) ) {
		$params = [];
	}

	$settings = [
		'style'              => rtcl_toolkits_divi5_validate_enum( $params['style'] ?? 'style-1', [ 'style-1' ], 'style-1' ),
		'locationId'         => rtcl_toolkits_divi5_validate_int( $params['locationId'] ?? 0, 0, PHP_INT_MAX, 0 ),
		'showChildLocations' => rtcl_toolkits_divi5_validate_toggle( $params['showChildLocations'] ?? 'off' ),
		'childLocationLimit' => rtcl_toolkits_divi5_validate_int( $params['childLocationLimit'] ?? 5, 1, 50, 5 ),
		'showCount'          => rtcl_toolkits_divi5_validate_toggle( $params['showCount'] ?? 'on' ),
		'showDescription'    => rtcl_toolkits_divi5_validate_toggle( $params['showDescription'] ?? 'off' ),
		'contentAlignment'   => rtcl_toolkits_divi5_validate_enum( $params['contentAlignment'] ?? 'center', [ 'left', 'center', 'right' ], 'center' ),
	];

	$html = rtcl_toolkits_divi5_render_single_location_preview( $settings );

	return rest_ensure_response( [
		'html' => $html,
	] );
}

/**
 * Render single location HTML for VB preview.
 *
 * @since 1.2.5
 * @param array $settings Module settings.
 * @return string Rendered HTML.
 */
function rtcl_toolkits_divi5_render_single_location_preview( $settings ) {
	if ( ! function_exists( 'rtcl' ) ) {
		return '<p>Classified Listing plugin is not active.</p>';
	}

	$location_id = intval( $settings['locationId'] );
	if ( ! $location_id ) {
		return '<p class="rtcl-no-location">' . esc_html__( 'No location found. Please select a valid location.', 'classified-listing-toolkits' ) . '</p>';
	}

	$location = get_term( $location_id, rtcl()->location );
	if ( is_wp_error( $location ) || empty( $location ) ) {
		return '<p class="rtcl-no-location">' . esc_html__( 'No location found. Please select a valid location.', 'classified-listing-toolkits' ) . '</p>';
	}

	$count = 0;
	if ( 'on' === $settings['showCount'] ) {
		$count = \Rtcl\Helpers\Functions::get_listings_count_by_taxonomy( $location->term_id, rtcl()->location );
	}

	// Get child locations.
	$child_locations = [];
	if ( 'on' === $settings['showChildLocations'] ) {
		$child_locations = get_terms( [
			'taxonomy'   => rtcl()->location,
			'hide_empty' => false,
			'parent'     => $location->term_id,
			'number'     => intval( $settings['childLocationLimit'] ),
			'orderby'    => 'name',
			'order'      => 'ASC',
		] );
		if ( is_wp_error( $child_locations ) ) {
			$child_locations = [];
		}
	}

	$template_settings = [
		'rtcl_location_style'       => $settings['style'],
		'rtcl_location_id'          => $settings['locationId'],
		'rtcl_show_child_locations' => $settings['showChildLocations'],
		'rtcl_child_location_limit' => $settings['childLocationLimit'],
		'rtcl_show_count'           => $settings['showCount'],
		'rtcl_show_description'     => $settings['showDescription'],
		'rtcl_content_alignment'    => $settings['contentAlignment'],
		'rtcl_enable_link'          => 'on',
	];

	$style         = $settings['style'] ?? 'style-1';
	$template_path = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::get_plugin_template_path();

	// Fallback to style-1 if selected style template does not exist.
	if ( ! file_exists( $template_path . 'divi/single-location/' . $style . '.php' ) ) {
		$style = 'style-1';
	}

	$template_style = 'divi/single-location/' . $style;

	$data = [
		'template'        => $template_style,
		'settings'        => $template_settings,
		'location'        => $location,
		'permalink'       => get_term_link( $location ),
		'title'           => $location->name,
		'description'     => $location->description,
		'count'           => $count,
		'child_locations' => $child_locations,
		'style'           => $style,
		'template_path'   => $template_path,
	];

	$data = apply_filters( 'rtcl_divi_filter_single_location_data', $data );

	return \Rtcl\Helpers\Functions::get_template_html( $data['template'], $data, '', $data['template_path'] );
}

/**
 * Get data for Visual Builder REST endpoint.
 *
 * @since 1.2.5
 * @return WP_REST_Response
 */
function rtcl_toolkits_divi5_get_data() {
	// Get parent categories from rtcl_category taxonomy.
	$categories = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::get_listing_taxonomy( 'parent' );
	$category_options = [];
	foreach ( $categories as $id => $name ) {
		$category_options[] = [
			'value' => (string) $id,
			'label' => $name,
		];
	}

	// Get parent locations from rtcl_location taxonomy.
	$locations = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::get_listing_taxonomy( 'parent', rtcl()->location );
	$location_options = [];
	foreach ( $locations as $id => $name ) {
		$location_options[] = [
			'value' => (string) $id,
			'label' => $name,
		];
	}

	return rest_ensure_response( [
		'categories' => $category_options,
		'locations'  => $location_options,
	] );
}

/**
 * Get RTCL Divi 5 data array.
 *
 * @since 1.2.5
 * @return array
 */
function rtcl_toolkits_divi5_get_localized_data() {
	$category_options = [];
	$location_options = [];

	// Get taxonomy names.
	$category_taxonomy = 'rtcl_category';
	$location_taxonomy = 'rtcl_location';

	// Try to get from rtcl() if available.
	if ( function_exists( 'rtcl' ) ) {
		$category_taxonomy = rtcl()->category ?? 'rtcl_category';
		$location_taxonomy = rtcl()->location ?? 'rtcl_location';
	}

	// Get parent categories directly using get_terms.
	if ( taxonomy_exists( $category_taxonomy ) ) {
		$terms = get_terms( [
			'taxonomy'   => $category_taxonomy,
			'hide_empty' => false,
			'parent'     => 0,
		] );

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$children     = [];
				$child_terms  = get_terms( [
					'taxonomy'   => $category_taxonomy,
					'hide_empty' => false,
					'parent'     => $term->term_id,
					'number'     => 10,
					'orderby'    => 'name',
					'order'      => 'ASC',
				] );
				if ( ! is_wp_error( $child_terms ) && ! empty( $child_terms ) ) {
					foreach ( $child_terms as $child ) {
						$children[] = [
							'value' => (string) $child->term_id,
							'label' => html_entity_decode( $child->name ),
							'count' => (int) $child->count,
						];
					}
				}
				$category_options[] = [
					'value'       => (string) $term->term_id,
					'label'       => html_entity_decode( $term->name ),
					'description' => $term->description ?? '',
					'children'    => $children,
				];
			}
		}
	}

	// Get parent locations directly using get_terms.
	if ( taxonomy_exists( $location_taxonomy ) ) {
		$location_terms = get_terms( [
			'taxonomy'   => $location_taxonomy,
			'fields'     => 'id=>name',
			'hide_empty' => false,
			'parent'     => 0,
		] );

		if ( ! is_wp_error( $location_terms ) && ! empty( $location_terms ) ) {
			foreach ( $location_terms as $id => $name ) {
				$location_options[] = [
					'value' => (string) $id,
					'label' => html_entity_decode( $name ),
				];
			}
		}
	}

	// Get listing types from database.
	$listing_type_options = [ [ 'value' => 'all', 'label' => 'All' ] ];
	if ( function_exists( 'rtcl' ) && class_exists( '\Rtcl\Helpers\Functions' ) && ! \Rtcl\Helpers\Functions::is_ad_type_disabled() ) {
		$types = \Rtcl\Helpers\Functions::get_listing_types();
		if ( ! empty( $types ) ) {
			foreach ( $types as $key => $label ) {
				$listing_type_options[] = [
					'value' => $key,
					'label' => html_entity_decode( $label ),
				];
			}
		}
	}

	// Get registered image sizes.
	$image_size_options = [];
	if ( class_exists( '\RadiusTheme\ClassifiedListingToolkits\Hooks\Helper' ) ) {
		$sizes = \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::get_image_sizes_select();
		foreach ( $sizes as $key => $label ) {
			$image_size_options[] = [ 'value' => $key, 'label' => $label ];
		}
	}

	// Get store categories.
	$store_category_options  = [];
	$has_store               = defined( 'RTCL_STORE_VERSION' ) && function_exists( 'rtclStore' );
	$store_category_taxonomy = $has_store && property_exists( rtclStore(), 'category' ) ? rtclStore()->category : 'store_category';
	if ( taxonomy_exists( $store_category_taxonomy ) ) {
		$store_terms = get_terms( [
			'taxonomy'   => $store_category_taxonomy,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		] );
		if ( ! is_wp_error( $store_terms ) ) {
			foreach ( $store_terms as $term ) {
				$store_category_options[] = [
					'value' => (string) $term->term_id,
					'label' => html_entity_decode( $term->name ),
				];
			}
		}
	}

	return [
		'categories'      => $category_options,
		'locations'       => $location_options,
		'listingTypes'    => $listing_type_options,
		'imageSizes'      => $image_size_options,
		'storeCategories' => $store_category_options,
		'hasPro'          => defined( 'RTCL_PRO_VERSION' ),
		'hasStore'        => $has_store,
		'restUrl'         => rest_url( 'rtcl-toolkits/v1/divi5-data' ),
		'nonce'           => wp_create_nonce( 'wp_rest' ),
	];
}

/**
 * Output inline script in wp_head for Visual Builder.
 *
 * @since 1.2.5
 */
function rtcl_toolkits_divi5_output_inline_data() {
	// Only in visual builder context.
	if ( ! function_exists( 'et_core_is_fb_enabled' ) || ! et_core_is_fb_enabled() ) {
		return;
	}

	$data = rtcl_toolkits_divi5_get_localized_data();
	?>
	<script type="text/javascript">
		window.rtclToolkitsDivi5Data = <?php echo wp_json_encode( $data ); ?>;
	</script>
	<?php
}
add_action( 'wp_head', 'rtcl_toolkits_divi5_output_inline_data', 1 );

/**
 * Output inline script in wp_footer for Visual Builder (backup).
 *
 * @since 1.2.5
 */
function rtcl_toolkits_divi5_output_inline_data_footer() {
	// Only in visual builder context.
	if ( ! function_exists( 'et_core_is_fb_enabled' ) || ! et_core_is_fb_enabled() ) {
		return;
	}

	$data = rtcl_toolkits_divi5_get_localized_data();
	?>
	<script type="text/javascript">
		if (typeof window.rtclToolkitsDivi5Data === 'undefined') {
			window.rtclToolkitsDivi5Data = <?php echo wp_json_encode( $data ); ?>;
		}
	</script>
	<?php
}
add_action( 'wp_footer', 'rtcl_toolkits_divi5_output_inline_data_footer', 1 );

/**
 * Pass REST API URL to the Visual Builder script via Divi hook.
 *
 * @since 1.2.5
 */
function rtcl_toolkits_divi5_enqueue_data_script() {
	$data = rtcl_toolkits_divi5_get_localized_data();

	// Register a small inline script that will be loaded in the app window.
	\ET\Builder\VisualBuilder\Assets\PackageBuildManager::register_package_build(
		[
			'name'    => 'rtcl-toolkits-divi5-data',
			'version' => CLASSIFIED_LISTING_TOOLKITS_VERSION,
			'script'  => [
				'src'                => RTCL_TOOLKITS_DIVI5_URL . 'assets/js/rtcl-data.js',
				'deps'               => [],
				'enqueue_top_window' => true,
				'enqueue_app_window' => true,
				'inline'             => 'window.rtclToolkitsDivi5Data = ' . wp_json_encode( $data ) . ';',
			],
		]
	);
}
add_action( 'divi_visual_builder_assets_before_enqueue_scripts', 'rtcl_toolkits_divi5_enqueue_data_script', 1 );
