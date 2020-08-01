<?php
/*
Plugin Name:        Wizhi Submenus
Plugin URI:         http://www.wpzhiku.com/
Description:        Display page`s subpage list and taxonomy terms list belongs to a post type
Version:            3.3.5
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
            'classname'   => 'widget_nav_menu widget_sub_menu',
            'description' => 'Display page`s subpage list and taxonomy terms list belongs to a post type',
        ];
        parent::__construct(false, 'Wizhi Submenu', $widget_options);
    }

    /**
     * @param $args
     * @param $instance
     */
    public function widget($args, $instance)
    {
        $this->render_menu($args, $instance);
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form($instance)
    {
        $instance      = wp_parse_args((array)$instance, ['exclude_terms' => '', 'exclude_posts' => '']);
        $exclude_terms = esc_attr($instance[ 'exclude_terms' ]);
        $exclude_posts = esc_attr($instance[ 'exclude_posts' ]);
        ?>
        <p>
            <label for="<?= $this->get_field_id('exclude_terms'); ?>">
                <?= __('Excluded Term IDs', 'enter'); ?>:
            </label>
            <input type="text" name="<?= $this->get_field_name('exclude_terms'); ?>" value="<?= $exclude_terms; ?>" class="widefat"
                   id="<?= $this->get_field_id('exclude_terms'); ?>" />
        </p>
        <p>
            <label for="<?= $this->get_field_id('exclude_posts'); ?>">
                <?= __('Excluded Post/Page IDs', 'enter'); ?>:
            </label>
            <input type="text" name="<?= $this->get_field_name('exclude_posts'); ?>" value="<?= $exclude_posts; ?>" class="widefat"
                   id="<?= $this->get_field_id('exclude_posts'); ?>" />
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
    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }


    public function render_menu($args, $instance)
    {
        if ( ! is_home()) {
            if (is_page()) {

                $post = get_queried_object();

                $args_posts = [
                    'title_li' => false,
                    'child_of' => $post->post_parent,
                    'exclude'  => $instance[ 'exclude_posts' ],
                    'echo'     => false,
                ];

                if ($post->post_parent) {
                    $args_posts[ 'child_of' ] = $post->post_parent;
                } else {
                    $args_posts[ 'child_of' ] = $post->ID;
                }

                $children = wp_list_pages($args_posts);

                if ($children) {
                    echo $args[ 'before_widget' ];

                    echo $args[ 'before_title' ] . get_the_title($post->post_parent) . $args[ 'after_title' ];

                    echo '<ul class="rs-submenu__list">';
                    echo $children;
                    echo '</ul>';

                    echo $args[ 'after_widget' ];
                }

            } else {

                $post_type_label = '';

                if (is_single()) {

                    global $post;
                    $post_type      = $post->post_type;
                    $taxonomies     = array_values(get_object_taxonomies($post_type, 'objects'));

                    $taxonomy = $taxonomies[ 0 ]->name;

                } elseif (is_post_type_archive()) {

                    $queried_object = get_queried_object();
                    $post_type      = $queried_object->name;
                    $taxonomies     = array_values(get_object_taxonomies($post_type, 'objects'));

                    $taxonomy = $taxonomies[ 0 ]->name;

                } else {

                    $queried_object = get_queried_object();
                    $taxonomy       = $queried_object->taxonomy;

                    if ($taxonomy === 'post_tag') {
                        $taxonomy = null;
                    }

                }

                $args_tax = [
                    'taxonomy'     => $taxonomy,
                    'exclude'      => $instance[ 'exclude_terms' ],
                    'show_count'   => 0,
                    'pad_counts'   => 0,
                    'hierarchical' => 0,
                    'title_li'     => '',
                ];

                // 判断是否有自定义分类方法
                $terms = false;
                if ($taxonomy) {
                    $terms = get_terms([
                        'taxonomy'   => $taxonomy,
                        'hide_empty' => false,
                    ]);
                }

                $post_type     = get_post_type(get_the_ID());
                $post_type_obj = get_post_type_object($post_type);

                if ($post_type_obj) {
                    $post_type_label = $post_type_obj->labels->singular_name;
                }

                if ($taxonomy && $terms && ! is_wp_error($terms)) {
                    echo $args[ 'before_widget' ];

                    if ( ! is_tag()) {
                        echo $args[ 'before_title' ] . $post_type_label . $args[ 'after_title' ];
                    }

                    echo '<ul class="rs-submenu__list">';
                    echo wp_list_categories($args_tax);
                    echo '</ul>';

                    echo $args[ 'after_widget' ];

                }

            }
        }
    }

}

add_action('widgets_init', function ()
{
    register_widget("wizhi_submenus");
});