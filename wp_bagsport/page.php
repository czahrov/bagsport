<?php get_header(); ?>
<div id='page' class="container">
	<div class="row">
		<div id='page' class=''>
			<div id='banner' class='' style='background-image:url(<?php echo get_the_post_thumbnail_url( get_post()->ID, 'full' ); ?>);'></div>
			<div class='content'>
				<?php echo apply_filters( 'the_content', get_post()->post_content ); ?>
			</div>
			
		</div>
		
	</div>
	<!-- /.row -->
</div>
<!-- /.container -->
<?php get_footer(); ?>