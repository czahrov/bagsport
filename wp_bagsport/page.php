<?php get_header(); ?>
<div id='page' class="">
	<?php if( has_post_thumbnail( get_post()->ID ) ): ?>
	<div id='banner' class='' style='background-image:url(<?php echo get_the_post_thumbnail_url( get_post()->ID, 'full' ); ?>);'></div>
	<?php endif; ?>
	<div id='' class='container'>
		<div class="row">
			<div class='title'>
				<?php echo get_post()->post_title; ?>
			</div>
			<div class='content'>
				<?php echo apply_filters( 'the_content', get_post()->post_content ); ?>
			</div>
			
		</div>
		
	</div>
	<!-- /.row -->
</div>
<!-- /.container -->
<?php get_footer(); ?>