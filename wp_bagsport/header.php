<?php
	session_start();
	if( $_SESSION['sprytne'] !== 'bardzo' and !isset( $_GET['sprytne'] ) ){
	 include( 'wbudowie.php' );
	 exit;
	
	}
	else{
	 $_SESSION['sprytne'] = 'bardzo';
	
	}
	
	define( 'DMODE', true );
	$infix = DMODE?(''):('.min');
	$buster = DMODE?( time() ):( false );
	
	wp_enqueue_style( "bootstrap", get_stylesheet_directory_uri() . "/css/bootstrap.css" );
	wp_enqueue_style( "style", get_stylesheet_directory_uri() . "/style{$infix}.css", array(), $buster );
	wp_enqueue_style( "override", get_stylesheet_directory_uri() . "/scss/override{$infix}.css", array(), $buster );
	
	wp_enqueue_script( "js-slim", get_stylesheet_directory_uri() . "/js/jquery.slim.js", array(), false, true );	
	wp_enqueue_script( "bootstrap-bundle", get_stylesheet_directory_uri() . "/js/bootstrap.bundle.js", array(), false, true );
	wp_enqueue_script( "bootstrap-js", get_stylesheet_directory_uri() . "/js/bootstrap.js", array(), false, true );
	wp_enqueue_script( "jQ", get_stylesheet_directory_uri() . "/js/jquery.js", array(), false, true );
	wp_enqueue_script( "akordeon-js", get_stylesheet_directory_uri() . "/js/akordeon{$infix}.js", array(), $buster, true );
	
	?>
<!DOCTYPE html>
<html lang="pl">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
		<meta name="description" content="Torby reklamowe, gadżety reklamowe, sklep z gadżetami">
		<meta name="author" content="Scepter Agencja interaktywna">
		<title><?php wp_title(''); ?><?php if (!(is_404()) && (is_single()) || (is_page()) || (is_archive())) { ?> &raquo; <?php } ?><?php bloginfo('name'); ?></title>
		<!-- Custom styles for this template -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700" rel="stylesheet">
		<link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
	</head>
	<?php wp_head(); ?>
	<body>
		<!-- Sub navigation -->
		<div class="container d-flex justify-content-between d-flex flex-wrap ">
			<a class="navbar-brand" href="http://poligon.scepter.pl/SzymonJ/wp_bagsport/"></a>
			<div class="contact-top d-flex flex-wrap">
				<div class="icon-phone d-flex flex-wrap">
					<i class="top-icons ion-ios-telephone-outline"></i>
					<div class="d-flex flex-column">
						<p>
							<span>
								<a title="Kliknij, aby zadzwonić." style="text-decoration: none;"" href="tel: 540 000 456">
									Infolinia 540 000 456
								</a>
							<span>
						</p>
						<p class="font-grey">Pn-Pt 8:00 - 16:00</p>
					</div>
				</div>
				<div class="icon-phone d-flex flex-wrap">
					<i class="top-icons ion-ios-email-outline"></i>
					<div class="d-flex flex-column">
						<p>
							<span>
								<a title="Kliknij, aby napisać e-mail." style="text-decoration: none;"" href="mailto:biuro@bagsport.pl">
									biuro@bagsport.pl
								</a>
							</span>
						</p>
						<p class="font-grey">
							Napisz do nas
						</p>
					</div>
				</div>
				<div class="icon-phone d-flex flex-wrap">
					<i class="ion-ios-location-outline top-icons"></i>
					<div class="d-flex flex-column">
						<p>
							<span>
								<a title="Kliknij, aby wyświetlić na mapie." style="text-decoration: none;" target="_blank" href="https://www.google.com/maps/place/Bank+BG%C5%BB,+Nawojowska+4,+33-300+Nowy+S%C4%85cz/@49.6136999,20.7003392,17z/data=!3m1!4b1!4m5!3m4!1s0x473de5399421778d:0x8d5bba09187d65a1!8m2!3d49.6137143!4d20.7026215">
									Nawojowska 4
								</a>
							</span>
						</p>
						<p class="font-grey">Spotkajmy się</p>
					</div>
				</div>
			</div>
		</div>
		<!-- Navigation -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
			<div class="container">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarResponsive">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item active">
						<li<?php if(is_home()) {?> class="active"<?php } ?>>
							<?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
						</li>
					</ul>
					<a class="ml-auto search- d-flex">
					<i class=" ion-ios-search-strong"></i>
					</a>
				</div>
			</div>
		</nav>
		<div class="breadcrumb">
			<div class="container">
				<?php printBreadcrumb(); ?>
			</div>
		</div>
		<!-- <div class="ciotka"></div> -->