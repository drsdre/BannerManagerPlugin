<?php
/**
 * Created by PhpStorm.
 * User: aschuurman
 * Date: 17/10/2018
 * Time: 10:23
 */
namespace BannerManager;

class BannerAd extends WP_Widget {

	/** constructor */
	function __construct() {
		parent::__construct( false, $name = 'BannerAd' );
	}

	/**
	 * @param $args
	 * @param $instance
	 */
	function widget( $args, $instance ) {
		extract( $args );
		$group        = $instance['group'];
		$banner_title = $instance['banner_title'];
		$title        = apply_filters( 'widget_title', $instance['title'] );

		?>
        <div class="bannerwidget">
			<?php
			if ( $title ) {
				echo "<h4>$title</h4>";
			}
			?>
            <div class="centered-block-inner">
				<?php echo bannerAd( $group, $banner_title, true ); ?>
            </div>
        </div>
		<?php
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance                 = $old_instance;
		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['group']        = strip_tags( $new_instance['group'] );
		$instance['banner_title'] = strip_tags( $new_instance['banner_title'] );

		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		if ( isset( $instance['group'] ) ) {
			$groupName = esc_attr( $instance['group'] );
		} else {
			$groupName = '';
		}
		if ( isset( $instance['banner_title'] ) ) {
			$banner_title = esc_attr( $instance['banner_title'] );
		} else {
			$banner_title = '';
		}
		if ( $groupName == '' ) {
			$groupName = 'template-header';
		}

		$banner_groups = banner_manager_load_banners();

		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title (Optional):</label>
            <input class="widefat"
                   id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>"
                   value="<?php echo isset( $instance['title'] ) ? $instance['title'] : ''; ?>"
                   type="text"
                   style="width:100%;"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'group' ); ?>"><?php _e( 'Group:', 'meesters-theme' ); ?>
                <select class="widefat"
                        id="<?php echo $this->get_field_id( 'group' ); ?>"
                        name="<?php echo $this->get_field_name( 'group' ); ?>">
					<?php foreach ( $banner_groups as $group ) : ?>
                        <option value="<?php echo $group->slug; ?>" <?php echo( $groupName == $group->slug ? 'SELECTED' : '' ); ?>>
							<?php echo $group->title; ?>
                        </option>
					<?php endforeach; ?>
                </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'banner_title' ); ?>"><?php _e( 'Banner Title:',
					'meesters-theme' ); ?><br/>
                <select class="widefat"
                        id="<?php echo $this->get_field_id( 'banner_title' ); ?>"
                        name="<?php echo $this->get_field_name( 'banner_title' ); ?>">
                    <option value=""><?php _e( 'random', 'meesters-theme' ); ?></option>
					<?php foreach ( $banner_groups as $group ) : ?>
						<?php foreach ( $group->banners as $banner ) : ?>
                            <option <?php echo( $group->slug == $groupName && $banner->title == $banner_title ? 'SELECTED' : '' ); ?>
                                    class="<?php echo $group->slug; ?>"
                                    style="<?php echo( $group->slug == $groupName ? "" : "display:none" ); ?>"
                                    value="<?php echo $banner->title; ?>">
								<?php echo $banner->title; ?>
                            </option>
						<?php endforeach; ?>
					<?php endforeach; ?>
                </select>
        </p>
        <script>
            jQuery('#<?php echo $this->get_field_id( 'group' ); ?>').change(function () {
                var group = jQuery(this).children(':selected').val();
                jQuery('#<?php echo $this->get_field_id( 'banner_title' ); ?>').children('option').css('display', 'none');
                jQuery('#<?php echo $this->get_field_id( 'banner_title' ); ?>').children('option.' + group).css('display', 'block');
                jQuery('#<?php echo $this->get_field_id( 'banner_title' ); ?>').children(':first').css('display', 'block').attr('selected', '1');
            });
        </script>
		<?php
	}
}


add_action( 'widgets_init', function () {
	register_widget( 'BannerAd' );
} );