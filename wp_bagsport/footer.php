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
		<!-- BEGIN callpage.io widget -->
		<!-- IMPORTANT: Remove script below if you don't need support for older browsers. -->
		<script>(function () {var script = document.createElement('script');script.src = 'https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/6.26.0/polyfill.min.js';script.async = false;document.head.appendChild(script);}())</script><script>var __cp = {"id":"UgHd9Lb2VZ4yqnTSH37-YqWUtFEmJthP4DqrnvDbB4A","version":"1.1"};(function (window, document) {var cp = document.createElement('script');cp.type = 'text/javascript';cp.async = false;cp.src = "++cdn-widget.callpage.io+build+js+callpage.js".replace(/[+]/g, '/').replace(/[=]/g, '.');var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(cp, s);if (window.callpage) {alert('You could have only 1 CallPage code on your website!');} else {/*process the queue of calls*/window.callpage = function (method) {if (method == '__getQueue') {return this.methods;}else if (method) {if (typeof window.callpage.execute === 'function') {return window.callpage.execute.apply(this, arguments);}else {(this.methods = this.methods || []).push({arguments: arguments});}}};window.callpage.__cp = __cp;/*here comes execution*/window.callpage('api.button.autoshow');}})(window, document);</script>
		<!-- END callpage.io widget -->
		<?php wp_footer(); ?>
	</body>
</html>