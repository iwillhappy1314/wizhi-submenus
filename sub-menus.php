<?php if ( ! is_home() ) { ?>

	<?php
	if ( is_page() ) {

		global $post;

		if ( $post->post_parent ) {
			$children = wp_list_pages( "title_li=&child_of=" . $post->post_parent . "&echo=0" );
		} else {
			$children = wp_list_pages( "title_li=&child_of=" . $post->ID . "&echo=0" );
		}

		if ( $children ) {

			echo $args[ 'before_title' ] . get_the_title( $post->post_parent ) . $args[ 'after_title' ]; ?>

			<ul>
				<?php echo $children; ?>
			</ul>

		<?php }

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
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 0,
			'title_li'     => '',
		];

		$post_type       = get_post_type( get_the_ID() );
		$post_type_obj   = get_post_type_object( $post_type );
		$post_type_lable = $post_type_obj->labels->singular_name;

		if ( ! is_tag() ) {
			echo $args[ 'before_title' ] . $post_type_lable . $args[ 'after_title' ];
		}

		?>

		<ul>
			<?php wp_list_categories( $args_tax ); ?>
		</ul>

	<?php } //end is_page ?>

<?php } //end !is_home ?>