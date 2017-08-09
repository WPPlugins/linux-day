<?php
/**
 * Plugin Name: Linux Day for WordPress
 * Plugin URI: https://github.com/deshack/linux-day
 * Description: This plugin adds a widget for Linux Day banners.
 * Author: Mattia Migliorini
 * Version: 1.2.4
 * Author URI: http://www.deshack.net
 * License: GPLv2 or later
 * Text Domain: linux-day
 */

// Load Text Domain
function ld_text_start() {
	load_plugin_textdomain( 'linux-day', false, plugin_basename( dirname( __FILE__ ) . '/languages' ) );
}
add_action( 'plugins_loaded', 'ld_text_start' );

/**
 * Linux Day Italy Widget Class
 */
class ld_widget_banner extends WP_Widget {

	/**
	 * Plugin Version.
	 *
	 * Used to purge browser cache after banner image updates.
	 *
	 * @since 1.2.2
	 * @access public
	 * @var string
	 */
	const VERSION = '1.2.4';

	// Constructor
	function __construct() {
		parent::__construct(
			'ld_widget_banner', // Base ID
			__( 'Linux Day Banner', 'linux-day' ), // Name
			array( 'description' => __( 'Add the Linux Day banners to your sidebar', 'linux-day' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args. Widget arguments.
	 * @param array $instance. Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$format = isset( $instance['format'] ) ? $instance['format'] : '';

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		if ( ! empty( $format ) ) :
		?>
		<a href="http://www.linuxday.it">
			<?php $banner = 'images/banner_' . $format . '.png'; ?>
			<img src="<?php echo plugins_url( $banner, __FILE__ ); ?>?<?php echo self::VERSION; ?>" border="0" alt="<?php _e( 'Linux Day 2016', 'linux-day' ); ?>">
		</a>
		<?php
		endif;
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance. Previously saved values from database.
	 */
	public function form( $instance ) {
		$sizes = array( '180x150', '300x250', '468x60', '120x600' );
		$selected = 'selected="selected"';
		$title = isset($instance['title']) ? $instance['title'] : '';
		$format = isset($instance['format']) ? $instance['format'] : '';
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'linux-day' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('format'); ?>"><?php _e( 'Banner format:', 'linux-day' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>">
				<option <?php selected( $format, '' ); ?> disabled><?php _e( 'Select format', 'linux-day' ); ?></option>
				<?php foreach( $sizes as $size ) : ?>
					<option <?php selected( $size, $format ); ?> value="<?php echo esc_attr($size); ?>"><?php echo $size; ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance. Values just sent to be saved.
	 * @param array $old_instance. Previously saved values from database.
	 *
	 * @return array. Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['format'] = $new_instance['format'];

		return $instance;
	}
}

function ld_register_widget() {
	register_widget( 'ld_widget_banner' );
}
add_action( 'widgets_init', 'ld_register_widget' );
