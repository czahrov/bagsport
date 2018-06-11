<div class="szybki col-lg-5 col-md-6 mb-4 single-item">
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
			<a href="<?php printf( 'tel:%s', str_replace( " ", "", getInfo( 'infolinia' ) ) ); ?>">
				<?php echo getInfo( 'infolinia' ); ?>
			</a>
		</div>
		<div class="line-section"></div>
		<div class="flex-column single-column">
			<i class="ion-ios-telephone-outline phone-fast"></i>
			<h4> Biuro obsługi klienta</h4>
			<a href="<?php printf( 'mailto:%s', getInfo( 'kontakt_e-mail' ) ); ?>">
				<?php echo getInfo( 'kontakt_e-mail' ); ?>
			</a>
		</div>
		<div class="line-section"></div>
		<div class="justify-content-center d-flex">
			<a href="<?php echo home_url( "zapytaj/?id={$_GET['id']}" ); ?>" class="call-ask">Wyślij zapytanie</a>
		</div>
		<div class="line-section"></div>
		<div class="justify-content-center d-flex  social-ico">
			<div class="flex-wrap">
				<h4>Udostępnij znajomemu</h4>
				<div class="justify-content-between">
					<a href='https://www.facebook.com/sharer/sharer.php?u=<?php echo home_url( "produkt/?id={$_GET['id']}" ); ?>' target='_blank'>
						<i class="ion-social-facebook"></i>            
					</a>
					<!--
					<a href='https://twitter.com/intent/tweet?text=<?php echo home_url( "produkt/?id={$_GET['id']}" ); ?>' target='_blank'>
						<i class="ion-social-twitter"></i>
					</a>
					<a href='https://plus.google.com/share?url=<?php echo home_url( "produkt/?id={$_GET['id']}" ); ?>' target='_blank'>
						<i class="ion-social-googleplus"></i>
					</a>
					-->
				</div>
			</div>
		</div>
	</div>
</div>