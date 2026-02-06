<?php

/**
 * Plugin Name:         Classified Listing Toolkits
 * Plugin URI:          https://wordpress.org/plugins/classified-listing-toolkits/
 * Description:         Classified Listing Toolkits which features several Elementor widgets and Divi modules to help you elegantly display listings in diverse layouts.
 * Version:             1.2.3
 * Requires at least:   6
 * Requires PHP:        7.4
 * Author:              RadiusTheme
 * Author URI:          https://radiustheme.com
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         classified-listing-toolkits
 * Domain Path:         /languages
 * Namespace:           ClassifiedListingToolkits
 * Requires Plugins:    classified-listing
 *
 */

use RadiusTheme\ClassifiedListingToolkits\Admin\DiviController;
use RadiusTheme\ClassifiedListingToolkits\Admin\ElementorController;
use RadiusTheme\ClassifiedListingToolkits\Admin\ELStoreController;

defined( 'ABSPATH' ) || exit;
const CLASSIFIED_LISTING_TOOLKITS_VERSION = '1.2.3';
const CLASSIFIED_LISTING_MIN_VERSION      = '5.3.0';

final class ClassifiedListingToolkits {
	/**
	 * Plugin slug.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	const SLUG = 'classified-listing-toolkits';

	/**
	 * Holds various class instances.
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	private $container = [];

	/**
	 * Constructor for the PluginName class.
	 *
	 * Sets up all the appropriate hooks and actions within our plugin.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		require_once __DIR__ . '/vendor/autoload.php';

		$this->define_constants();

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

		add_action( 'wp_loaded', [ $this, 'flush_rewrite_rules' ] );
		$this->init_plugin();
	}

	/**
	 * Initializes the PluginBoilerplate() class.
	 *
	 * Checks for an existing PluginBoilerplate() instance
	 * and if it doesn't find one, creates it.
	 *
	 * @return ClassifiedListingToolkits|bool
	 * @since 1.0.0
	 *
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new ClassifiedListingToolkits();
		}

		return $instance;
	}

	/**
	 * Magic getter to bypass referencing plugin.
	 *
	 * @param $prop
	 *
	 * @return mixed
	 * @since 1.0.0
	 *
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}

		return $this->{$prop};
	}

	/**
	 * Magic isset to bypass referencing plugin.
	 *
	 * @param $prop
	 *
	 * @return mixed
	 * @since 1.0.0
	 *
	 */
	public function __isset( $prop ) {
		return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
	}

	/**
	 * Define the constants.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function define_constants() {
		define( 'CLASSIFIED_LISTING_TOOLKITS_SLUG', self::SLUG );
		define( 'CLASSIFIED_LISTING_TOOLKITS_FILE', __FILE__ );
		define( 'CLASSIFIED_LISTING_TOOLKITS_DIR', __DIR__ );
		define( 'CLASSIFIED_LISTING_TOOLKITS_PATH', dirname( CLASSIFIED_LISTING_TOOLKITS_FILE ) );
		define( 'CLASSIFIED_LISTING_TOOLKITS_INCLUDES', CLASSIFIED_LISTING_TOOLKITS_PATH . '/includes' );
		define( 'CLASSIFIED_LISTING_TOOLKITS_TEMPLATE_PATH', CLASSIFIED_LISTING_TOOLKITS_PATH . '/views' );
		define( 'CLASSIFIED_LISTING_TOOLKITS_URL', plugins_url( '', CLASSIFIED_LISTING_TOOLKITS_FILE ) );
		define( 'CLASSIFIED_LISTING_TOOLKITS_BUILD', CLASSIFIED_LISTING_TOOLKITS_URL . '/build' );
		define( 'CLASSIFIED_LISTING_TOOLKITS_ASSETS', CLASSIFIED_LISTING_TOOLKITS_URL . '/assets' );
		define( 'CLASSIFIED_LISTING_TOOLKITS_PRODUCTION', 'yes' );
	}

	/**
	 * Load the plugin after all plugins are loaded.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function init_plugin() {
		$this->includes();
		$this->init_hooks();
		$this->add_version();

		/**
		 * Fires after the plugin is loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'classified_listing_toolkits_loaded' );
	}

	/**
	 * Activating the plugin.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function activate() {
		// Run the installer to create necessary migrations.
		$this->install();
	}

	/**
	 * Placeholder for deactivation function.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function deactivate() {
		//
	}

	/**
	 * Flush rewrite rules after plugin is activated.
	 *
	 * Nothing being added here yet.
	 *
	 * @since 1.0.0
	 */
	public function flush_rewrite_rules() {
		// fix rewrite rules
	}

	/**
	 * Run the installer to create necessary migrations and seeders.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	private function install() {
		$installer = new RadiusTheme\ClassifiedListingToolkits\Setup\Installer();
		$installer->run();
	}

	private function add_version() {
		$installer = new RadiusTheme\ClassifiedListingToolkits\Setup\Installer();
		$installer->add_version();
	}

	/**
	 * Include the required files.
	 *
	 * @return void
	 * @since 0.2.0
	 *
	 */
	public function includes() {
		if ( $this->is_request( 'admin' ) ) {
			$this->container['admin_menu'] = new RadiusTheme\ClassifiedListingToolkits\Admin\Menu();
		}
		$this->container['assets']   = new RadiusTheme\ClassifiedListingToolkits\Assets\LoadAssets();
		$this->container['rest_api'] = new RadiusTheme\ClassifiedListingToolkits\Rest\Api();

	}

	/**
	 * Initialize the hooks.
	 *
	 * @return void
	 * @since 0.2.0
	 *
	 */
	public function init_hooks() {
		// Init classes
		add_action( 'init', [ $this, 'init_classes' ] );

		// Localize our plugin

		// Add the plugin page links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );
	}


	/**
	 * Instantiate the required classes.
	 *
	 * @return void
	 * @since 0.2.0
	 *
	 */
	public function init_classes() {

		if ( class_exists( \ET_Builder_Element::class ) ) {
			new DiviController;
		}

		// Only init Elementor if it's loaded
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			if ( defined( "RTCL_PRO_VERSION" ) && ! version_compare( RTCL_PRO_VERSION, '3.2.0', '>=' ) ) {
				return;
			}
			ElementorController::elementor_init();
		}
		if ( defined( 'RTCL_STORE_VERSION' ) ) {
			if ( ! version_compare( RTCL_STORE_VERSION, '2.1.0', '>=' ) ) {
				return;
			}
			ELStoreController::init();
		}

	}

	/**
	 * What type of request is this.
	 *
	 * @param string $type admin, ajax, cron or frontend
	 *
	 * @return bool
	 * @since 0.2.0
	 *
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();

			case 'ajax':
				return defined( 'DOING_AJAX' );

			case 'rest':
				return defined( 'REST_REQUEST' );

			case 'cron':
				return defined( 'DOING_CRON' );

			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Plugin action links
	 *
	 * @param array $links
	 *
	 * @return array
	 * @since 0.2.0
	 *
	 */
	public function plugin_action_links( $links ) {
//        $links[] = '<a href="' . admin_url( 'admin.php?page=plugin_name#/settings' ) . '">' . __( 'Settings', 'classified-listing-toolkits' ) . '</a>';
//        $links[] = '<a href="#" target="_blank">' . __( 'Documentation', 'classified-listing-toolkits' ) . '</a>';

		return $links;
	}
}


/**
 * Initialize the main plugin.
 *
 * @return \ClassifiedListingToolkits|bool
 * @since 1.0.0
 *
 */
function the_classified_listing_toolkits_main_function() {
	return ClassifiedListingToolkits::init();
}

/**
 * Initializes the Rex Dynamic Discount plugin.
 *
 * Checks if WooCommerce is installed and, if not, displays an admin notice.
 * If WooCommerce is installed, initializes the Rex Dynamic Discount plugin.
 *
 * @return void
 * @since 1.0.0
 *
 */
function classified_listing_toolkits_init() {

	if ( ! class_exists( 'Rtcl' ) ) {
		add_action( 'admin_notices', 'classified_listing_toolkits_wc_missing_notice' );
		add_action( 'admin_init', function () {
			if ( ! class_exists( 'Rtcl' ) ) {
				classified_listing_toolkits_self_deactivation();
			}
		} );

		return;
	} else {
		if ( version_compare( RTCL_VERSION, CLASSIFIED_LISTING_MIN_VERSION, '<' ) ) {
			add_action( 'admin_notices', 'classified_listing_toolkits_missing_notice_version' );
			add_action( 'admin_init', function () {
				if ( ! class_exists( 'Rtcl' ) || ! defined( 'RTCL_VERSION' ) || version_compare( RTCL_VERSION, '5.0.0', '<=' ) ) {
					classified_listing_toolkits_self_deactivation();
				}
			} );

			return;
		}
	}

	/*
	 * Kick-off the plugin.
	 *
	 * @since 1.0.0
	 */
	the_classified_listing_toolkits_main_function();
}

add_action( 'plugins_loaded', 'classified_listing_toolkits_init' );

/**
 * Deactivates the Product Recommendations for WooCommerce plugin.
 *
 * @since 1.0.0
 */
function classified_listing_toolkits_self_deactivation() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * WooCommerce not installed and activated fallback notice.
 *
 * @since 1.0.0
 */
function classified_listing_toolkits_wc_missing_notice() {
	echo '<div class="error"><p><strong>' . sprintf(
			esc_html__( 'Classified Listing Toolkit requires Classified Listing to be installed and active.', 'classified-listing-toolkits' )
		),
	'</strong></p></div>';
}

/**
 * Classified Listing is not installed and activated fallback notice.
 *
 * @since 1.0.0
 */
function classified_listing_toolkits_missing_notice_version() {
	echo '<div class="error"><p><strong>' . sprintf(
			esc_html__( 'Classified Listing Toolkit requires Classified Listing to be minimum version 5.0.1', 'classified-listing-toolkits' )
		),
	'</strong></p></div>';
}
