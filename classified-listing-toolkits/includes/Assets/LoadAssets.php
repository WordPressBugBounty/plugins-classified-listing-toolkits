<?php

namespace RadiusTheme\ClassifiedListingToolkits\Assets;

/**
 * Load assets class
 *
 * Responsible for managing all of the assets (CSS, JS, Images, Locales).
 */
class LoadAssets {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_all_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'register_script' ] );
	}

	/**
	 * Register all scripts and styles.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function register_all_scripts() {
		$this->register_styles( $this->get_styles() );
		$this->register_scripts( $this->get_scripts() );
	}

	/**
	 * Get all styles.
	 *
	 * @return array
	 * @since 1.0.0
	 *
	 */
	public function get_styles(): array {
		return [
			'classified-listing-toolkits-css'           => [
				'src'     => CLASSIFIED_LISTING_TOOLKITS_BUILD . '/index.css',
				'version' => CLASSIFIED_LISTING_TOOLKITS_VERSION,
				'deps'    => [],
			],
			'classified-listing-toolkits-elementor-css' => [
				'src'     => CLASSIFIED_LISTING_TOOLKITS_BUILD . '/elementor-widget.css',
				'version' => CLASSIFIED_LISTING_TOOLKITS_VERSION,
				'deps'    => [ 'rtcl-public' ],
			],
		];
	}

	/**
	 * Get all scripts.
	 *
	 * @return array
	 * @since 1.0.0
	 *
	 */
	public function get_scripts(): array {
		$dependency = [];
		if ( file_exists( CLASSIFIED_LISTING_TOOLKITS_DIR . '/build/index.asset.php' ) ) {
			$dependency = require_once CLASSIFIED_LISTING_TOOLKITS_DIR . '/build/index.asset.php';
		}

		return [
			'classified-listing-toolkits-app' => [
				'src'       => CLASSIFIED_LISTING_TOOLKITS_BUILD . '/index.js',
				'version'   => $dependency['version'] ?? '',
				'deps'      => $dependency['dependencies'] ?? '',
				'in_footer' => true,
			],
		];
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function register_styles( array $styles ) {
		foreach ( $styles as $handle => $style ) {
			wp_register_style( $handle, $style['src'], $style['deps'], $style['version'] );
		}
	}

	/**
	 * Register scripts.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function register_scripts( array $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			wp_register_script( $handle, $script['src'], $script['deps'], $script['version'], $script['in_footer'] );
		}
	}

	/**
	 * Enqueue admin styles and scripts.
	 *
	 * @return void
	 * @since 0.3.0 Loads the JS and CSS only on the Dynamic Discount admin page.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_admin_assets() {
		if ( ! is_admin() || ! isset( $_GET['page'] )
		     || sanitize_text_field( wp_unslash( $_GET['page'] ) ) !== 'classified-listing-toolkits'
		) { //phpcs:ignore WordPress.Security.NonceVerification
			return;
		}

		wp_enqueue_style( 'classified-listing-toolkits-css' );
		wp_enqueue_script( 'classified-listing-toolkits-app' );
	}

	public function register_script() {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			wp_enqueue_style( 'classified-listing-toolkits-elementor-css' );
		}
	}


}
