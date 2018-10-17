<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BannerManager
 * @subpackage BannerManager/admin
 */

namespace BannerManager;

use Banner;
use BannerGroup;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    BannerManager
 * @subpackage BannerManager/admin
 * @author     Your Name <email@example.com>
 */
class BannerManagerAdmin {

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
	 *
	 * @param      string    $banner_manager       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $banner_manager, $version ) {

		$this->banner_manager = $banner_manager;
		$this->version = $version;

	}

	public static function add_menu()
	{
		add_menu_page(
			'Banner Manager',
			'Banner Manager',
			'update_themes',
			'banner-manager',
			'BannerManager::show_ui'
		);
	}

	/**
	 * Register the stylesheets for the admin area.
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

		//wp_enqueue_style( $this->banner_manager, plugin_dir_url( __FILE__ ) . 'css/banner-manager-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style('thickbox');
	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script( $this->banner_manager, plugin_dir_url( __FILE__ ) . 'js/banner-manager-admin.js', ['jquery','media-upload','thickbox'], $this->version, false );
		wp_enqueue_script($this->banner_manager );

		//wp_register_script('design-upload', get_bloginfo('template_url').'/admin/js/design-options.js', ['jquery','media-upload','thickbox']);
	}

	/**
	* @param string $group
	* @param array $banner_groups
	 */
	function show_new_banner($group, array $banner_groups)
	{
		$group = $banner_groups[$group];

		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'Create Banner':
					$title = $_POST['banner-title'];
					$type = $_POST['banner-type'];

					$banner = new Banner();
					$banner->title = $title;
					if ($type=='simple')
					{
						$image = $_POST['banner-image'];
						$link = $_POST['banner-link'];

						$banner->makeImageBanner($title, $image, $link, true);
						$banner->alt = $_POST['banner-alt'];
					} else {
						$html = $_POST['banner-html'].' ';

						$banner->makeHTMLBanner($title, $html, true);
					}

					$group->addBanner($banner);

					$banner_groups[$group->slug] = $group;
					$this->save_banners($banner_groups);

					unset($_GET['section']);
					unset($_POST['section']);
					$this->show_ui();
					return;
				case 'Cancel':
					unset($_GET['section']);
					unset($_POST['section']);
					$this->show_ui();
					return;
			}
		}
		?>
	    <style>
			input.check { width: auto !important; }
			.upload_image_button, .clear_field_button { width: auto !important; }
		</style>
		<div class="wrap meta-box-sortables">
	        <div class="icon32" id="icon-themes"><br></div>
	        <h2>Create Banner in <?php echo $group->title; ?></h2>
	        <form method="post" action="?page=banner-manager&section=new-banner&group=<?php echo $group->slug ?>" style="width: 400px;">
	            <div class="form-field">
		            <label>Title</label>
	                <input type="text" name="banner-title" />
	                <p>A name for the banner so you can quickly identify banners later</p>
	            </div>
	            <div class="form-field">
	                <input class="check" name="banner-type" type="radio" value="simple" CHECKED /> Simple Banner<br />
				</div>
	                <div id="simple-banner" style="padding-left: 50px; padding-bottom: 5px; margin-bottom: 5px; border-bottom: 1px solid #CCC">
	                    <div class="form-field">
	                        <label>Image</label>
	                        <input type="text" id="banner-image" name="banner-image" />
							<input class="upload_image_button button-primary" id="upload_image_button" type="button" value="Choose Image" />
						    <input class="clear_field_button button-secondary" type="button" value="Clear Field" />
	                        <p>Upload an image to use as your banner</p>
						</div>
	                    <div class="form-field">
	                        <label>Link</label>
	                        <input type="text" name="banner-link" />
	                        <p>Enter the URL the banner should link to</p>
	                    </div>
						<div class="form-field">
	                        <label>Alt Text</label>
	                        <input type="text" name="banner-alt" />
	                        <p>Alt text to be used on the link and image</p>
	                    </div>
	                </div>
	           <div class="form-field">
	                <input class="check" name="banner-type" type="radio" value="html" /> HTML Banner
	           </div>
					<div id="html-banner" style="padding-left: 50px;">
	                    <div class="form-field">
	                        <label>Banner HTML</label>
	                        <textarea rows=10 name="banner-html"></textarea>
	                        <p>Paste HTML code from a banner exchange or affiliate program</p>
						</div>
	                </div>

				<input type="submit" name="action" class="button-primary" id="publish" value="Create Banner">
				<input type="submit" name="action" class="button-secondary" id="publish" value="Cancel">

	        </form>
	    <?php
	}

	function show_ui()
	{
		// Load banners
		$banner_groups = $this->load_banners();

		if (isset($_GET['section'])) {
			switch ($_GET['section']) {
				case 'new-banner':
					$this->show_new_banner($_GET['group'], $banner_groups);
					return '';

				case 'delete-banner':
					$group = $_GET['group'];
					$bannerIndex = $_GET['banner-index'];
					$group = $banner_groups[$group];
					unset( $group->banners[$bannerIndex] );
					$banner_groups[$group->slug] = $group;

					$this->save_banners($banner_groups);
					$banner_groups = $this->load_banners();
					break;

				case 'edit-banner':
					$this->show_edit_banner($_GET['group'], $_GET['banner-index'], $banner_groups);
					return '';
			}
		}
		if (isset($_GET['action'])) {
			switch ($_GET['action']) {
				case 'reset-hits':
					$group = $banner_groups[$_GET['group']];
					$banner = $group->banners[$_GET['banner-index']];

					$banner->hits=0;
					$group->banners[$_GET['banner-index']] = $banner;
					$banner_groups[$group->slug] = $group;

					$this->save_banners($banner_groups);
					$banner_groups = $this->load_banners();
					break;
				case 'delete-group':
					$group = $_GET['group'];
					unset($banner_groups[$group]);
					$this->save_banners($banner_groups);
					$banner_groups = $this->load_banners();
					break;
				case 'activate':
					$group = $banner_groups[$_GET['group']];
					$banner = $group->banners[$_GET['banner-index']];

					$banner->active=true;
					$group->banners[$_GET['banner-index']] = $banner;
					$banner_groups[$group->slug] = $group;

					$this->save_banners($banner_groups);
					$banner_groups = $this->load_banners();
					break;

				case 'deactivate':
					$group = $banner_groups[$_GET['group']];
					$banner = $group->banners[$_GET['banner-index']];

					$banner->active=false;
					$group->banners[$_GET['banner-index']] = $banner;
					$banner_groups[$group->slug] = $group;

					$this->save_banners($banner_groups);
					$banner_groups = $this->load_banners();
					break;
			}
		}

		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'Create Group':
					$title = $_POST['group_title'];
					$group = new BannerGroup();
					$group->title = $title;
					$group->slug = sanitize_title_with_dashes($title);

					$banner_groups[$group->slug] = $group;
					$this->save_banners($banner_groups);
					$banner_groups = $this->load_banners();
					break;
			}
		}

		// Fix the blank entry problem
		$changed=false;

		foreach ($banner_groups as $group)
		{
			foreach (array_keys($group->banners) as $banner_key) {
				$banner = $group->banners[$banner_key];

				if (!property_exists($banner, 'title')) {
					unset($group->banners[$banner_key]);
					$changed=true;
				}
				//$group = array_values($group);
			}
		}

		if ($changed) {
			$this->save_banners($banner_groups);
		}
	?>
		<style>
		#poststuff h2 { margin-bottom: 0; }
		</style>
		<div class="wrap meta-box-sortables">
	        <div class="icon32" id="icon-themes"><br></div>
	        <h2>Banner Manager</h2>

	        <div id="poststuff">
	        <h3>Create a group</h3>
	        <p>An ad group contains one or more advertisements to be displayed on your site in a particular location.
	        To create a new group begin by typing a name below and pressing "create"
	        <form method="post" action="?page=banner-manager">
	            <label>Group Title:</label>
	            <input type="text" name="group_title" />
	            <input type="submit" value="Create Group" id="publish" class="button-primary" name="action" />
	        </form>
	        <hr />
	        <p>Each group can contain one or more advertisements. Advertisements will be selected randomly from the
	        group, and you may selectively disable individual advertisements to stop them from appearing temporarily.</p>
	        <?php foreach ($banner_groups as $group) : ?>
	            <h2><?php echo $group->title;?></h2>
	            <p>
	            <?php if ($group->slug!="template-header"): ?>
	            <a onclick="return confirm('Deleting groups cannot be undone. Are you sure?');" style="color:red"
	            href="?page=banner-manager&action=delete-group&group=<?php echo $group->slug;?>">Delete</a>
	            <?php endif; ?>
	            <br />
	            Shortcode: [banner-group name="<?php echo $group->slug;?>"]<br />
	               Template Tag: &lt;?php if (function_exists('bannerAd')) bannerAd('<?php echo $group->slug;?>'); ?&gt;</p>

	            <table cellspacing="0" class="widefat fixed" style="width: auto">
	                <thead>
	                    <tr>
	                    <th class="manage-column">Active</th>
	                    <th class="manage-column" style="width: 150px">Title</th>
	                    <th class="manage-column" style="width: 75px">Type</th>
	                    <th class="manage-column" style="width: 50px">Hits</th>
	                    <th class="manage-column" style="width: 150px">Key</th>
	                    <th class="manage-column" style="width: 200px">Actions</th>
	                    </tr>
	                </thead>

	                <tfoot>
	                    <tr>
	                    <th class="manage-column">Active</th>
	                    <th class="manage-column">Title</th>
	                    <th class="manage-column">Type</th>
	                    <th class="manage-column">Hits</th>
	                    <th class="manage-column">Key</th>
	                    <th class="manage-column">Actions</th>
	                    </tr>
	                </tfoot>

	                <tbody>
	                <?php
						$y=0;
						foreach (array_keys($group->banners) as $banner_key) :
							$banner = $group->banners[$banner_key]; ?>
	                    <tr>
		                    <td><?php echo ($banner->active?"Yes":"No");?></td>
	                        <td><?php echo $banner->title; ?></td>
	                        <td><?php echo $banner->getType(); ?></td>
	                        <td><?php echo $banner->getHits();?></td>
	                        <td><?php echo $banner->getKey()?></td>
	                        <td>
	                            <a href="?page=banner-manager&section=edit-banner&group=<?php echo $group->slug;?>&banner-index=<?php echo $banner_key;?>">Edit</a>
	                            <a href="?page=banner-manager&section=delete-banner&group=<?php echo $group->slug;?>&banner-index=<?php echo $banner_key;?>">Delete</a>
	                            <?php if ($banner->active) : ?>
	                            <a href="?page=banner-manager&group=<?php echo $group->slug;?>&banner-index=<?php echo $banner_key;?>&action=deactivate">Deactivate</a>
	                            <?php else: ?>
	                            <a href="?page=banner-manager&group=<?php echo $group->slug;?>&banner-index=<?php echo $banner_key;?>&action=activate">Activate</a>
	                            <?php endif; ?>
	                            <a href="?page=banner-manager&group=<?php echo $group->slug;?>&banner-index=<?php echo $banner_key;?>&action=reset-hits">Reset&nbsp;Hits</a>

	                        </td>
	                    </tr>
	                <?php $y++; endforeach; ?>
						<tr>
	                        <td colspan=4><a href="?page=banner-manager&section=new-banner&group=<?php echo $group->slug; ?>">Create new banner</a></td>
						</tr>
	                </tbody>
	            </table>
	        <?php endforeach; ?>

			</div><!--poststuff-->
	    </div><!--wrap-->
		<?php
	}


	/**
	 * @param $group
	 * @param $bannerIndex
	 * @param array $banner_groups
	 *
	 * @throws Exception
     */
	function show_edit_banner($group, $bannerIndex, array $banner_groups)
	{
		$group = $banner_groups[$group];

		if (!isset($group->banners[$bannerIndex])) {
			throw new Exception('Banner index '.$bannerIndex.' does not exist');
		}
		$banner = $group->banners[$bannerIndex];

		if (isset($_POST['action'])) {
			switch($_POST['action']) {
				case 'Save Banner':
					$title = $_POST['banner-title'];
					$type = $_POST['banner-type'];

					$banner = new Banner();
					$banner->title = $title;
					if ($type=='simple')
					{
						$image = $_POST['banner-image'];
						$link = $_POST['banner-link'];
						$alt = $_POST['banner-alt'];

						$banner->alt = $alt;
						$banner->image = $image;
						$banner->href = $link;
						$banner->html = '';
					} else {
						$html = $_POST['banner-html'];
						if ($html=='') {
							$html=' ';
						}
						$banner->html=$html;
						$banner->image='';
						$banner->href='';
						$banner->alt='';
					}

					$group->banners[$bannerIndex] = $banner;
					$banner_groups[$group->slug] = $group;

					$this->save_banners($banner_groups);

					unset($_POST['section']);
					unset($_GET['section']);
					$this->show_ui();
					return;
				case 'Cancel':
					unset($_POST['section']);
					unset($_GET['section']);
					$this->show_ui();
					return;
			}
		}
		?>
	    <style>
			input.check { width: auto !important; }
			.upload_image_button, .clear_field_button { width: auto !important; }
		</style>
		<div class="wrap meta-box-sortables">
	        <div class="icon32" id="icon-themes"><br></div>
	        <h2>Edit Banner</h2>
	        <form method="post" action="?page=banner-manager&section=edit-banner&group=<?php echo $group->slug ?>&banner-index=<?php echo $bannerIndex;?>"
	            style="width: 400px;">
	            <div class="form-field">
		            <label>Title</label>
	                <input type="text" name="banner-title" value="<?php echo $banner->title;?>" />
	                <p>A name for the banner so you can quickly identify banners later</p>
	            </div>
	            <div class="form-field">
	                <input class="check" name="banner-type" type="radio" value="simple" <?php echo ($banner->html==''?'CHECKED':'');?> />Simple Banner<br />
				</div>
	                <div id="simple-banner" style="padding-left: 50px; padding-bottom: 5px; margin-bottom: 5px; border-bottom: 1px solid #CCC">
	                    <div class="form-field">
	                        <label>Image</label>
	                        <input type="text" id="banner-image" name="banner-image" value="<?php echo $banner->image;?>"/>
							<input class="upload_image_button button-primary" id="upload_image_button" type="button" value="Choose Image" />
						    <input class="clear_field_button button-secondary" type="button" value="Clear Field" />
	                        <p>Upload an image to use as your banner</p>
						</div>
	                    <div class="form-field">
	                        <label>Link</label>
	                        <input type="text" name="banner-link" value="<?php echo $banner->href;?>" />
	                        <p>Enter the URL the banner should link to</p>
	                    </div>
						<div class="form-field">
	                        <label>Alt Text</label>
	                        <input type="text" name="banner-alt" value="<?php echo $banner->alt;?>" />
	                        <p>Alt text to be used on the link and image</p>
	                    </div>
	                </div>
	           <div class="form-field">
	                <input class="check" name="banner-type" type="radio" value="html" <?php echo ($banner->html!=''?'CHECKED':'');?> /> HTML Banner
	           </div>
					<div id="html-banner" style="padding-left: 50px;">
	                    <div class="form-field">
	                        <label>Banner HTML</label>
	                        <textarea rows=10 name="banner-html"><?php echo stripslashes( $banner->html );?></textarea>
	                        <p>Paste HTML code from a banner exchange or affiliate program</p>
						</div>
	                </div>

				<input type="submit" name="action" class="button-primary" id="publish" value="Save Banner">
				<input type="submit" name="action" class="button-secondary" id="publish" value="Cancel">

	        </form>
	    <?php
	}
}

add_action('admin_menu', '\BannerManager\BannerManagerAdmin::add_menu', 100);
