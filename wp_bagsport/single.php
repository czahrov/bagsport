<?php get_header(); ?>
<div id='single' class="container">
	<div class="row">
		<div class="col-lg-3">
			<h1 class="my-4">
				<span>
					Kategorie
					<div class="h1-line"></div>
				</span>
				produktów
			</h1>
			<div class="list-group">
				<a href="#" class="list-group-item">Biuro i biznes</a>
				<a href="#" class="list-group-item">Czas i pogoda</a>
				<a href="#" class="list-group-item">Do picia</a>
				<a href="#" class="list-group-item">Dom i Ogród</a>
				<a href="#" class="list-group-item">Dzieci i zabawa</a>
				<a href="#" class="list-group-item">Elektronika</a>
				<a href="#" class="list-group-item">Materiały piśmiennicze</a>
				<a href="#" class="list-group-item">Narzędzia, latarki, breloki</a>
				<a href="#" class="list-group-item">Parasole i peleryny</a>
				<a href="#" class="list-group-item">Torby i plecaki</a>
				<a href="#" class="list-group-item">Wakacje, sport i rekreacja</a>
				<a href="#" class="list-group-item">Zdrowie i uroda</a>
				<a href="#" class="list-group-item">Świateczne</a>
				<?php wp_nav_menu( array( 'theme_location' => 'menu-sklep' ) ); ?>
			</div>
		</div>
		<!-- /.col-lg-3 -->
		<div class="col-lg-9">
			<div class="row">
				<div class="col-lg-7 col-md-6 mb-4">
					<h1 title="<?php the_title_attribute(); ?>" class="my-4"><?php the_title(); ?></h1>
					<div class="single-product">
						<div class="arrow-pagination">
							<i class="ion-ios-arrow-back"></i>
							<i class="ion-ios-arrow-forward"></i>
						</div>
						<div class="product-img" style="background-image: url( img/20.jpg );">
						</div>
					</div>
					<!-- /.single-product -->
					<div class="product-single-gallery">
						<div class="row">
							<div class="col-lg-4 col-md-6 mb-4">
								<div class="single-gallery" style="background-image: url( img/20.jpg );"> </div>
							</div>
							<div class="col-lg-4 col-md-6 mb-4">
								<div class="single-gallery" style="background-image: url( img/20.jpg );"> </div>
							</div>
							<div class="col-lg-4 col-md-6 mb-4">
								<div class="single-gallery" style="background-image: url( img/20.jpg );"> </div>
							</div>
						</div>
						<!-- /.single-product-gallery -->
					</div>
				</div>
				<div class="col-lg-5 col-md-6 mb-4 single-item">
					<h1 class="my-4">
						<span>
							Szybki
							<div class="h1-line"></div>
						</span>
						kontakt
					</h1>
					<div class="fast-contact-bar">
						<div class="flex-column single-column">
							<i class="ion-ios-telephone-outline phone-fast"></i>
							<h4> Infolinia</h4>
							<a href=""> +48 540 000 456</a>
						</div>
						<div class="line-section"></div>
						<div class="flex-column single-column">
							<i class="ion-ios-telephone-outline phone-fast"></i>
							<h4> Biuro obsługi klienta</h4>
							<a href=""> biuro@bagsport.pl</a>
						</div>
						<div class="line-section"></div>
						<div class="justify-content-center d-flex">
							<a href="" class="call-ask">Wyślij zapytanie</a>
						</div>
						<div class="line-section"></div>
						<div class="justify-content-center d-flex  social-ico">
							<div class="flex-wrap">
								<h4>Udostępnij znajomemu</h4>
								<div class="justify-content-between">
									<i class="ion-social-facebook"></i>            
									<i class="ion-social-twitter"></i>
									<i class="ion-social-googleplus"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /. -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-7 col-md-6 mb-4">
					<h3 class="product-title"><?php the_title_attribute(); ?></h3>
				</div>
				<div class="col-lg-4 col-md-6 mb-4 product-price">
					<h1>Cena netto: <span>21,50 zł</span></h1>
				</div>
			</div>
			<div class="col-lg-12 col-md-6 mb-4 product-content">
				<div class="row">
					<h1>O produkcie</h1>
					<div class="basic-content">
						<p>
							<?php
								if ( have_posts() ) : while ( have_posts() ) : the_post();
									the_content();
								endwhile; else: ?>
							<p>Sorry, no posts matched your criteria.</p>
							<?php endif; ?>
						</p>
					</div>
					<div class="d-flex flex-column">
						<h1>Dostępność w magazynie: <span>4 szt.</span></h1>
						<h1>Kolor: <span>Biały</span></h1>
						<h1>Wymiary: <span>2,6 x 2,2 x 9,7 cm</span></h1>
					</div>
				</div>
			</div>
		</div>
		<!-- /.col-lg-9 -->
	</div>
	<!-- /.row -->
</div>
</div>
<!-- /.container -->
<?php get_footer(); ?>