<?php get_header(); ?>
<div id='single' class="">
	<div id='banner' class='' style='background-image:url(<?php echo get_the_post_thumbnail_url( get_post()->ID, 'full' ); ?>);'></div>
	<div class='container'>
		<div class="row">
			<div class='col-12'>
				<div class='title'><?php the_title(); ?></div>
				<div class='content'>
					<?php echo apply_filters( 'the_content', get_post()->post_content ); ?>
				</div>
				
			</div>
			
		</div>
		<div class='row'>
			<?php
				$posts = get_posts( array(
					'category_name' => 'blog',
					'numberposts' => -1,
					
				) );
				$ids = array();
				foreach( $posts as $item ){
					$ids[] = $item->ID;
				}
				
				if( DMODE ){
					echo "<!--";
					// print_r( $posts );
					echo "-->";
					
				}
				
				$next = $posts[ array_search( get_post()->ID, $ids ) + 1 ];
				if( $next instanceof WP_POST ):
			?>
			<div class='next col-12 d-flex justify-content-end'>
				<a href='<?php the_permalink( $next ); ?>'>
					<div class='stdHeader'>
						Kolejny wpis: <?php echo $next->post_title; ?>
					</div>
					<div class=''>
						Zainteresowany? Czytaj dalej!
					</div>
				</a>
				
			</div>
			<?php endif; ?>
		</div>
		
	</div>
	<!-- /.row -->
</div>
<!-- /.container -->
<?php get_footer(); ?>