<?php

namespace RadiusTheme\ClassifiedListingToolkits\Setup;

use RadiusTheme\ClassifiedListingToolkits\Common\Keys;


class Installer {

	/**
	 * Run the installer.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		// Update the installed version.
		$this->add_version();

		// Register and create tables.
		//$this->register_table_names();
		//$this->create_tables();
	}


	/**
	 * Add time and version on DB.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function add_version(): void {
		$installed = get_option( Keys::CLASSIFIED_LISTING_TOOLKITS_INSTALLED );

		if ( $installed ) {
			return;
		}

		$rtcl_version = get_option( 'rtcl_installed_from' );

		$version_to_set = $rtcl_version && version_compare( $rtcl_version, '5.0.0', '<=' ) ? '1.1.4' : CLASSIFIED_LISTING_TOOLKITS_VERSION;

		add_option( Keys::CLASSIFIED_LISTING_TOOLKITS_INSTALLED, $version_to_set );
	}


	/**
	 * Register table names.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	private function register_table_names(): void {
		global $wpdb;

		// Register the tables to wpdb global.
		$wpdb->plugin_name = $wpdb->prefix . 'plugin_name';
	}


	/**
	 * Create necessary database tables.
	 *
	 * @return void
	 * @since JOB_PLACE_
	 *
	 */
	public function create_tables() {
		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}
	}
}
