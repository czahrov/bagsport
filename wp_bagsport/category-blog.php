<?php
	get_header();
?>
<!-- Page Content -->
<div id='blog' class="container">
	<?php
		$posts = get_posts( array(
			'category_name' => 'blog',
			'posts_per_page' => get_option('posts_per_page'),
			'paged' => max( 1, get_query_var('page') ),
			
		) );
		
		foreach( $posts as $post ):
	?>
	<div class='item row'>
		<div class='img col-12 col-md-6 col-lg-3' style="background-image:url(<?php echo get_the_post_thumbnail_url( $post->ID, 'medium' ); ?>);"></div>
		<div class='text col-12 col-md-6 col-lg-9 d-flex flex-column '>
			<div class='title'>
				<?php echo get_the_title( $post->ID ); ?>
			</div>
			<div class='date'>
				<?php echo get_the_date( 'd.m.Y', $post->ID ); ?>
			</div>
			<div class='excerpt'>
				<?php
					if( has_excerpt( $post ) ){
						echo $post->post_excerpt;
					}
					else{
						$parts = explode( " ", $post->post_content );
						if( count( $parts ) > 50 ){
							echo implode( " ", array_slice( $parts, 0, 50 ) ) . " (...)";
							
						}
						else{
							echo implode( " ", $parts );
							
						}
						
					}
				?>
				<a class='more' href='<?php the_permalink( $post->ID ); ?>'>czytaj dalej</a>
			</div>
			
		</div>
		
	</div>
	<?php 
		endforeach;
		
		the_posts_pagination( array(
			'screen_reader_text' => ' ',
			'base' => sprintf( '%s/%%_%%', home_url( 'category/blog' ) ),
			'format' => '?page=%#%',
			'current' => max( 1, get_query_var('page') ),
			'end_size' => 3,
			'mid_size' => 3,
			'prev_next' => false,
			
		) );
	?>
	<!-- /.row -->
</div>
<!-- /.container -->
<?php get_footer(); ?>