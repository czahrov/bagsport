		<!-- /.most-popular -->
		<div class="container">
			<div class="d-flex justify-content-center flex-column text-center seo">
				<?php get_template_part( "template/segment/slider", "partners" ); ?>
			</div>
		</div>
		<!-- /.container -->
		<!-- Footer -->
		<div class="social-media d-flex justify-content-center">
			<div class="social-icons">Sprawdź Ciotkę Gadżet</div>
		</div>
		<footer class="py-5 bg-footer">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-lg-3 col-md-6 mb-3 footer-content">
						<ul>
							<h1 class="foot-title orange">
								Zamówienia
								<div class="h1-line orange"></div>
							</h1>
							<?php wp_nav_menu( array( 'theme_location' => 'zamowienia-menu', 'container_class' => 'menu-foot' ) ); ?>
						</ul>
					</div>
					<div class="col-lg-3 col-md-6 mb-3 footer-content">
						<ul>
							<h1 class="foot-title yellow">
								Poznaj nas
								<div class="h1-line yellow"></div>
							</h1>
							<?wp_nav_menu( array( 'theme_location' => 'produkcja-menu', 'container_class' => 'menu-foot' ) ); ?>
						</ul>
					</div>
					<div class="col-lg-3 col-md-6 mb-3 footer-content">
						<ul>
							<h1 class="foot-title blue">
								pomoc
								<div class="h1-line blue"></div>
							</h1>
							<?wp_nav_menu( array( 'theme_location' => 'pomoc-menu', 'container_class' => 'menu-foot' ) ); ?>
						</ul>
					</div>
					<div class="col-lg-3 col-md-6 mb-3 footer-content">
						<ul>
							<h1 class="foot-title red">
								Informacje
								<div class="h1-line red"></div>
							</h1>
							<?wp_nav_menu( array( 'theme_location' => 'informacje-menu', 'container_class' => 'menu-foot' ) ); ?>
							<?php
								$stacjonarny = getInfo('stacjonarny');
							?>
							<a class='' href='<?php echo str_replace( array( ' ', '-' ), '', $stacjonarny ) ?>'>
								<?php echo $stacjonarny; ?>
							</a>
						</ul>
					</div>
				</div>
				<div class="sep"></div>
				<div class='row'>
					<div class="col-12 justify-content-between">
						<div class="copyright">
							Copyright Bagsport 2018
						</div>
						<div class="author">
							Projekt i wykonanie: <a href="http://www.scepter.pl">Scepter Agencja interaktywna</a>
						</div>
					</div>
				
				</div>
			</div>
			<!-- /.container -->
		</footer>
		<?php wp_footer(); ?>
	</body>
</html>