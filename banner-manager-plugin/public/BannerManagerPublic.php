<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BannerManager
 * @subpackage BannerManager/public
 */
namespace BannerManager;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    BannerManager
 * @subpackage BannerManager/public
 * @author     Your Name <email@example.com>
 */
class BannerManagerPublic {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $banner_manager    The ID of this plugin.
	 */
	private $banner_manager;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $banner_manager       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $banner_manager, $version ) {

		$this->banner_manager = $banner_manager;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in BannerManagerLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The BannerManagerLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->banner_manager, plugin_dir_url( __FILE__ ) . 'css/banner-manager-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in BannerManagerLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The BannerManagerLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->banner_manager, plugin_dir_url( __FILE__ ) . 'js/banner-manager-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 *
	 */
	public static function redirect_banners () {
		$request = $_SERVER['REQUEST_URI'];
		if (!isset($_SERVER['REQUEST_URI'])) {
			$request = substr($_SERVER['PHP_SELF'], 1);
			if (isset($_SERVER['QUERY_STRING']) AND $_SERVER['QUERY_STRING'] != '') {
				$request.='?'.$_SERVER['QUERY_STRING'];
			}
		}
		if (isset($_GET['banerkey'])) {
			$request = '/banner/'.$_GET['banerkey'].'/';
		}

		if ( strpos('/'.$request, '/banner/') ) {
			$bannerkey = explode('banner/', $request);
			$bannerkey = $bannerkey[1];
			$bannerkey = str_replace('/', '', $bannerkey);

			$banner_groups = banner_manager_load_banners();
			$url=false;
			foreach ($banner_groups as $group)
			{
				foreach (array_keys($group->banners) as $banner_key) {
					$banner = $group->banners[$banner_key];

					if ($banner->getKey()==$bannerkey) {
						$url = $banner->href;
						$banner->hits = $banner->hits+1;

						$group->banners[$banner_key]=$banner;
						$this->save_banners($banner_groups);
					}
				}
			}

			if ($url != '') {
				header('X-Robots-Tag: noindex, nofollow', true);
				header('Location: ' . $url);
				exit;
			}
		}
	}
}


add_action('init', 'BannerManagerPublic::redirect_banners');
