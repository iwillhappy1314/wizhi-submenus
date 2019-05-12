<?php
/*
Plugin Name:        Wizhi Submenus
Plugin URI:         http://www.wpzhiku.com/
Description:        Display page`s subpage list and taxonomy terms list belongs to a post type
Version:            3.3
Author:             WordPress 智库
Author URI:         http://www.wpzhiku.com/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define('WIZHI_SUBMENU', plugin_dir_path(__FILE__));

// Add a widget
class wizhi_submenus extends WP_Widget
{

    public function __construct()
    {
        $widget_options = [
            'classname'   => 'widget_sub_menu',
            'description' => 'Display page`s subpage list and taxonomy terms list belongs to a post type',
        ];
        parent::__construct(false, 'Wizhi Sidemenu', $widget_options);
    }

    /**
     * @param $args
     * @param $instance
     */
    public function widget($args, $instance)
    {
        echo $args[ 'before_widget' ];
        include(WIZHI_SUBMENU . 'sub-menus.php');
        echo $args[ 'after_widget' ];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form($instance)
    {
        echo '<p>该小工具不需要任何设置.</p>';
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update($new_instance, $old_instance)
    {
        // processes widget options to be saved
    }

}

add_action('widgets_init', function ()
{
    register_widget("wizhi_submenus");
});