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
						<video autoplay="" loop="" class="bgvideo">
							<source src="http://serwiswizowy.com/wp-content/themes/serwiswizowy/media/splash.webm" type="video/webm">
							<source src="http://serwiswizowy.com/wp-content/themes/serwiswizowy/media/splash.ogv" type="video/ogv">
							<source src="http://serwiswizowy.com/wp-content/themes/serwiswizowy/media/splash.mp4" type="video/mp4">
							Twoja przeglądarka nie obsługuje formatów VIDEO. Sugerujemy aktualizację przeglądarki www. 
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