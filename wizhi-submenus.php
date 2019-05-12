<?php
/*
Plugin Name:        Wizhi Submenus
Plugin URI:         http://www.wpzhiku.com/
Description:        Display page`s subpage list and taxonomy terms list belongs to a post type
Version:            3.3.3
Author:             WordPress 智库
Author URI:         http://www.wpzhiku.com/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define( 'WIZHI_SUBMENU', plugin_dir_path( __FILE__ ) );

// Add a widget
class wizhi_submenus extends WP_Widget {

	public function __construct() {
		$widget_options = [
			'classname'   => 'widget_nav_menu widget_sub_menu',
			'description' => 'Display page`s subpage list and taxonomy terms list belongs to a post type',
		];
		parent::__construct( false, 'Wizhi Sidemenu', $widget_options );
	}

	/**
	 * @param $args
	 * @param $instance
	 */
	public function widget( $args, $instance ) {
		include( WIZHI_SUBMENU . 'sub-menus.php' );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$instance      = wp_parse_args( (array) $instance, [ 'exclude_terms' => '', 'exclude_posts' => '' ] );
		$exclude_terms = esc_attr( $instance[ 'exclude_terms' ] );
		$exclude_posts = esc_attr( $instance[ 'exclude_posts' ] );
		?>
        <p>
            <label for="<?= $this->get_field_id( 'exclude_terms' ); ?>">
				<?= __( 'Excluded Term IDs', 'enter' ); ?>:
            </label>
            <input type="text" name="<?= $this->get_field_name( 'exclude_terms' ); ?>" value="<?= $exclude_terms; ?>" class="widefat"
                   id="<?= $this->get_field_id( 'exclude_terms' ); ?>" />
        </p>
        <p>
            <label for="<?= $this->get_field_id( 'exclude_posts' ); ?>">
				<?= __( 'Excluded Post/Page IDs', 'enter' ); ?>:
            </label>
            <input type="text" name="<?= $this->get_field_name( 'exclude_posts' ); ?>" value="<?= $exclude_posts; ?>" class="widefat"
                   id="<?= $this->get_field_id( 'exclude_posts' ); ?>" />
        </p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
     *
     * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

}

add_action( 'widgets_init', function () {
	register_widget( "wizhi_submenus" );
} );