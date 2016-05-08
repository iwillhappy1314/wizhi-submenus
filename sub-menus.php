<?php
/**
 * Display page`s subpage and taxonomy terms belongs to a post type
 * in single post page,the menu display as well.
 */

?>

<div class="widget">
	<div class="widget_nav_menu">

	<?php if( !is_home() ){ ?>

		<?php
			if( is_page() ){ // if is page

				global $post;
				if($post->post_parent){
					$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&echo=0");
				} else {
					$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
				}
				if ($children) { ?>

					<aside id="widget-cat-links" class="cat-links">
						<h3 class="widget-title">
							<?php echo get_the_title($post->post_parent); ?>
						</h3>
						<ul>
							<?php echo $children; ?>
						</ul>
					</aside>

				<?php }
			} else {
		?>

			<?php

				$taxonomy = '';

				if( is_single() ){ //single post

					global $post;
					$queried_object = get_queried_object();
					$post_type = $post->post_type;
					$taxonomies = array_values( get_object_taxonomies( $post_type, 'objects' ) );

					$taxonomy = $taxonomies[0]->name;

				} elseif ( is_post_type_archive() ){  //post_type archive

					$queried_object = get_queried_object();
					$post_type = $queried_object->name;
					$taxonomies = array_values( get_object_taxonomies( $post_type, 'objects' ) );

					$taxonomy = $taxonomies[0]->name;

				} else { //taxonomy

					$queried_object = get_queried_object();
					$taxonomy = $queried_object->taxonomy;

					if( $taxonomy != 'post_tag' ){
						$taxonomy = $taxonomy; # tags too much, hide theme
					} else {
						$taxonomy = null;
					}
				}

			?>

			<?php
				$args = array(
					'taxonomy'     => $taxonomy,
					'show_count'   => 0,
					'pad_counts'   => 0,
					'hierarchical' => 0,
					'title_li'     => '',
				);

				$post_type = get_post_type( get_the_ID() );
				$post_type_obj = get_post_type_object( $post_type  );
				$post_type_lable = $post_type_obj->labels->singular_name;

			?>

			<aside id="widget-cat-links" class="cat-links">

				<?php if( !is_tag() ){ ?>
					<h3 class="widget-title">
						<?php echo $post_type_lable; ?>
					</h3>
				<?php } ?>

				<ul>
					<?php wp_list_categories( $args ); ?>
				</ul>

			</aside>

		<?php } //end is_page ?>

	<?php } //end !is_home ?>

	</div>
</div>
