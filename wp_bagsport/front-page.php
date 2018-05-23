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
							<a href="" class="call-ask2">zamów katalog</a>
						</div>
						<video autoplay="" loop="" class="bgvideo">
							<source src="http://serwiswizowy.com/wp-content/themes/serwiswizowy/media/splash.webm" type="video/webm">
							<source src="http://serwiswizowy.com/wp-content/themes/serwiswizowy/media/splash.ogv" type="video/ogv">
							<source src="http://serwiswizowy.com/wp-content/themes/serwiswizowy/media/splash.mp4" type="video/mp4">
							Twoja przeglądarka nie obsługuje formatów VIDEO. Sugerujemy aktualizację przeglądarki www. 
						</video>
					</div>
					<div class="col-lg-12">
						<h1 class="my-4">
							<span>
								Polecane
								<div class="h1-line"></div>
							</span>
							produkty
						</h1>
					</div>
					<?php printProducts("produkty"); ?>
				</div>
				<!-- /.row -->
				<div class="d-flex justify-content-center pagination-products">
					<a class="active" href="">1</a>
					<a href="">2</a>
					<a href="">3</a>
					<a href="">4</a>
					<a href="">5</a>
					<span>...</span>
					<a href="">245</a>
				</div>
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