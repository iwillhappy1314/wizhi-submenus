<?php

if ( ! is_home() ) {
	if ( is_page() ) {

		$post = get_queried_object();

		$args_posts = [
			'title_li' => false,
			'child_of' => $post->post_parent,
			'exclude'  => $instance[ 'exclude_posts' ],
			'echo'     => false,
		];

		if ( $post->post_parent ) {
			$args_posts[ 'child_of' ] = $post->post_parent;
		} else {
			$args_posts[ 'child_of' ] = $post->ID;
		}
		
		$children = wp_list_pages( $args_posts );

		if ( $children ) {
			echo $args[ 'before_widget' ];

			echo $args[ 'before_title' ] . get_the_title( $post->post_parent ) . $args[ 'after_title' ];

			echo '<ul>';
			echo $children;
			echo '</ul>';

			echo $args[ 'after_widget' ];

		}

	} else {

		$taxonomy = '';

		if ( is_single() ) {

			global $post;
			$queried_object = get_queried_object();
			$post_type      = $post->post_type;
			$taxonomies     = array_values( get_object_taxonomies( $post_type, 'objects' ) );

			$taxonomy = $taxonomies[ 0 ]->name;

		} elseif ( is_post_type_archive() ) {

			$queried_object = get_queried_object();
			$post_type      = $queried_object->name;
			$taxonomies     = array_values( get_object_taxonomies( $post_type, 'objects' ) );

			$taxonomy = $taxonomies[ 0 ]->name;

		} else {

			$queried_object = get_queried_object();
			$taxonomy       = $queried_object->taxonomy;

			if ( $taxonomy == 'post_tag' ) {
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
		if ( $taxonomy ) {
			$terms = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			] );
		}

		$post_type       = get_post_type( get_the_ID() );
		$post_type_obj   = get_post_type_object( $post_type );
		$post_type_lable = $post_type_obj->labels->singular_name;

		if ( $taxonomy && $terms && ! is_wp_error( $terms ) ) {
			echo $args[ 'before_widget' ];

			if ( ! is_tag() ) {
				echo $args[ 'before_title' ] . $post_type_lable . $args[ 'after_title' ];
			}

			echo '<ul>';
			echo wp_list_categories( $args_tax );
			echo '</ul>';

			echo $args[ 'after_widget' ];

		}

	}
}