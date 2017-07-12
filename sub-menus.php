<?php if ( ! is_home() ) { ?>

	<?php
	if ( is_page() ) {

		$post = get_queried_object();

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

		$taxonomy        = '';
		$related_page_id = false;
		$queried_object  = get_queried_object();
		$post_type       = $queried_object->name;

		if ( is_single() || is_singular() ) {

			$post_type  = $queried_object->post_type;
			$taxonomies = array_values( get_object_taxonomies( $post_type, 'objects' ) );

			// 获取文章的分类方法
			$terms     = wp_get_post_terms( $queried_object->ID, $taxonomies[ 0 ]->name );
			$main_term = $terms[ 0 ];

			// 获取分类法关联的页面
			$related_page_id = get_term_meta( $main_term->term_id, '_related_page', true );
			$related_page    = get_post( $related_page_id );

			if ( $related_page_id ) {

				// 父级页面为分类关联的父级页面的页面 ID
				$children = wp_list_pages( "title_li=&child_of=" . $related_page->post_parent . "&echo=0" );

				if ( $children ) {
					echo $args[ 'before_title' ] . get_the_title( $related_page->post_parent ) . $args[ 'after_title' ]; ?>

                    <ul>
						<?php echo $children; ?>
                    </ul>
				<?php }

			} else {
				$taxonomy = $taxonomies[ 0 ]->name;
			}

		} elseif ( is_post_type_archive() ) {

			$taxonomies = array_values( get_object_taxonomies( $post_type, 'objects' ) );
			$taxonomy   = $taxonomies[ 0 ]->name;

		} else {
			$taxonomy = $queried_object->taxonomy;

			if ( $taxonomy == 'post_tag' ) {
				$taxonomy = null;
			}

			$terms = get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			] );

			// 获取有关键的分类目录并排除
			$exclude = collect( $terms )->filter( function ( $term ) {
				return get_term_meta( $term->term_id, '_related_page', true );
			} )->flatten()->toArray();

			$exclude = $exclude[0]->term_id;

		}

		$args_tax = [
			'taxonomy'     => $taxonomy,
			'exclude'      => $exclude,
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 0,
			'title_li'     => '',
		];

		$post_type       = get_post_type( get_the_ID() );
		$post_type_obj   = get_post_type_object( $post_type );
		$post_type_lable = $post_type_obj->labels->singular_name;

		if ( ! is_tag() && ! $related_page_id ) {
			echo $args[ 'before_title' ] . $post_type_lable . $args[ 'after_title' ];
		}

		if ( ! $related_page_id ) {

			?>

            <ul>
				<?php wp_list_categories( $args_tax ); ?>
            </ul>

		<?php }

	} //end is_page ?>

<?php } //end !is_home ?>