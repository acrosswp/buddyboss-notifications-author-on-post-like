<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Sorting_Option_In_Network_Search_For_BuddyBoss
 * @subpackage Sorting_Option_In_Network_Search_For_BuddyBoss/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sorting_Option_In_Network_Search_For_BuddyBoss
 * @subpackage Sorting_Option_In_Network_Search_For_BuddyBoss/includes
 * @author     AcrossWP <contact@acrosswp.com>
 */
class Sorting_Option_In_Network_Search_For_BuddyBoss {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sorting_Option_In_Network_Search_For_BuddyBoss_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->define_constants();

		if ( defined( 'SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_VERSION' ) ) {
			$this->version = SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'sorting-option-in-network-search-for-buddyboss';

		$this->load_dependencies();

		$this->set_locale();

		$this->load_hooks();
	}

	/**
	 * Define WCE Constants
	 */
	private function define_constants() {

		$this->define( 'SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_FILE', SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_FILES );
		$this->define( 'SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_BASENAME', plugin_basename( SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_FILES ) );
		$this->define( 'SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_PATH', plugin_dir_path( SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_FILES ) );
		$this->define( 'SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_URL', plugin_dir_url( SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_FILES ) );
		
		$plugin_data = get_plugin_data( SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_FILE );
		$version = $plugin_data['Version'];
		$this->define( 'SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_VERSION', $version );
	}

	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Register all the hook once all the active plugins are loaded
	 *
	 * Uses the plugins_loaded to load all the hooks and filters
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function load_hooks() {

		/**
		 * Check if plugin can be loaded safely or not
		 * 
		 * @since    1.0.0
		 */
		if( apply_filters( 'sorting-option-in-network-search-for-buddyboss-load', true ) ) {
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sorting_Option_In_Network_Search_For_BuddyBoss_Loader. Orchestrates the hooks of the plugin.
	 * - Sorting_Option_In_Network_Search_For_BuddyBoss_i18n. Defines internationalization functionality.
	 * - Sorting_Option_In_Network_Search_For_BuddyBoss_Admin. Defines all hooks for the admin area.
	 * - Sorting_Option_In_Network_Search_For_BuddyBoss_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for loading the dependency main class
		 * core plugin.
		 */
		require_once SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/dependency/class-dependency.php';

		/**
		 * The class responsible for loading the dependency main class
		 * core plugin.
		 */
		require_once SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/dependency/buddyboss.php';

		/**
		 * The file is responce for loading all the necessay functions
		 * core plugin.
		 */
		require_once SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/functions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/class-sorting-option-in-network-search-for-buddyboss-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/class-sorting-option-in-network-search-for-buddyboss-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_PATH . 'admin/class-sorting-option-in-network-search-for-buddyboss-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once SORTING_OPTION_IN_NETWORK_SEARCH_FOR_BUDDYBOSS_PLUGIN_PATH . 'public/class-sorting-option-in-network-search-for-buddyboss-public.php';

		$this->loader = new Sorting_Option_In_Network_Search_For_BuddyBoss_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sorting_Option_In_Network_Search_For_BuddyBoss_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sorting_Option_In_Network_Search_For_BuddyBoss_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Sorting_Option_In_Network_Search_For_BuddyBoss_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'plugin_action_links', $plugin_admin, 'modify_plugin_action_links', 10, 2 );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		
		$this->loader->add_action( 'bp_admin_setting_search_register_fields', $plugin_admin, 'admin_setting_general_register_fields' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Sorting_Option_In_Network_Search_For_BuddyBoss_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'sorting_option_in_network_search_for_buddyboss_hook', 81 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sorting_Option_In_Network_Search_For_BuddyBoss_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
