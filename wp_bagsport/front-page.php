<?php
	get_header();
?>
<!-- Page Content -->
<div id='front'>
	<div class='container'>
		<div class="row">
			<?php get_template_part( "template/segment/side-panel" ); ?>
			<!-- /.col-lg-3 -->
			<div class="col-lg-9">
				<h1 class="my-4">
					<span>
						Bag-sport
						<div class="h1-line"></div>
					</span>
					Agencja reklamowa
				</h1>
				<div class="row">
					<div class="bg-video">
						<div class="video-content">
							<a href="<?php echo home_url( 'zamow-katalog' ); ?>" class="call-ask2">zamów katalog</a>
						</div>
						<video autoplay loop poster="<?php echo get_template_directory_uri(); ?>/media/video.jpg">
							<source src="<?php echo get_template_directory_uri(); ?>/media/video.webm" type="video/webm">
							<source src="<?php echo get_template_directory_uri(); ?>/media/video.mp4" type="video/mp4">
							<source src="<?php echo get_template_directory_uri(); ?>/media/video.ogv" type="video/ogg">
						</video>
					</div>
					<?php get_template_part( "template/segment/polecane" ); ?>
				</div>
				<!-- /.row -->
			</div>
			<!-- /.col-lg-9 -->
		</div>
		<!-- /.row -->
	</div>
	<?php get_template_part( "template/segment/ogladane" ); ?>
	<div class='container seo'>
		<?php echo apply_filters( 'the_content', get_post()->post_content ); ?>
	</div>
</div>
<!-- /.container -->
<?php get_footer(); ?>