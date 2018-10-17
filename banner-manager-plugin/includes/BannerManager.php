<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BannerManager
 * @subpackage BannerManager/includes
 */

namespace BannerManager;

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
 * @package    BannerManager
 * @subpackage BannerManager/includes
 * @author     Your Name <email@example.com>
 */
class BannerManager {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      BannerManagerLoader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $banner_manager The string used to uniquely identify this plugin.
	 */
	protected $banner_manager;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	protected $banner_manager_header;

	protected $banner_manager_groups;

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
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->banner_manager = 'plugin-name';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - BannerManagerLoader. Orchestrates the hooks of the plugin.
	 * - BannerManageri18n. Defines internationalization functionality.
	 * - BannerManagerAdmin. Defines all hooks for the admin area.
	 * - BannerManagerPublic. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/BannerManagerLoader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/BannerManageri18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/BannerManagerAdmin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/BannerManagerPublic.php';

		$this->loader = new BannerManagerLoader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the BannerManageri18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new BannerManageri18n();

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

		$plugin_admin = new BannerManagerAdmin( $this->get_banner_manager(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_banner_manager() {
		return $this->banner_manager;
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

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new BannerManagerPublic( $this->get_banner_manager(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    BannerManagerLoader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * @param string $group
	 * @param string $bannerTitle
	 * @param bool $return
	 *
	 * @return mixed
	 */
	public function bannerAd( $group = 'template-header', $bannerTitle = '', $return = false ) {
		$banner_groups = banner_manager_load_banners();
		$group         = $banner_groups[ $group ];
		$banners       = $group->banners;
		$realbanners   = [];
		$usablebanner  = false;

		foreach ( $banners as $banner ) {
			if ( $banner->active == true ) {
				$realbanners[] = $banner;
				if ( $bannerTitle != '' && $bannerTitle == $banner->title ) {
					$usablebanner = $banner;
				}
			}
		}

		if ( $bannerTitle == '' ) {
			$usablebanner = $realbanners[ array_rand( $realbanners ) ];
		}

		if ( $usablebanner ) {
			if ( $return ) {
				return $usablebanner->getHTML();
			}
			echo $usablebanner->getHTML();
		}
	}

	/**
	 * Save banners to the options
	 *
	 * @param array $banner_groups
	 */
	function save_banners( $banner_groups ) {
		$header = $banner_groups['template-header'];

		// Update cached value
		$this->banner_manager_groups = $banner_groups;
		$this->banner_manager_header = $header;

		// Save header
		update_option( 'banner-manager-header', $header );

		// Save groups
		unset( $banner_groups['template-header'] );
		update_option( 'banner-manager-groups', $banner_groups );

	}

	/**
	 * Load banners from the options
	 * @return array BannerGroup
	 */
	function load_banners() {
		if ( ! isset( $this->banner_manager_header ) ) {
			$this->banner_manager_header = get_option( 'banner-manager-header' );
			if ( ! is_object( $this->banner_manager_header ) ) {
				// TODO remove after migration
				if ( is_string( $this->banner_manager_header ) ) {
					$this->banner_manager_header = @unserialize( $this->banner_manager_header );
				} else {
					$this->banner_manager_header        = new BannerGroup();
					$this->banner_manager_header->title = 'Template Header';
					$this->banner_manager_header->slug  = 'template-header';
				}
			}
		}

		if ( ! isset( $this->banner_manager_groups ) ) {
			$this->banner_manager_groups = get_option( 'banner-manager-groups' );
			if ( ! is_array( $this->banner_manager_groups ) ) {
				// TODO remove after migration
				if ( is_string( $this->banner_manager_groups ) ) {
					$this->banner_manager_groups = @unserialize( $this->banner_manager_groups );
				} else {
					$this->banner_manager_groups = [];
				}
			}
			// Header to group
			$this->banner_manager_groups = array_merge( [ 'template-header' => $this->banner_manager_header ],
				$this->banner_manager_groups );
		}

		return $this->banner_manager_groups;
	}
}