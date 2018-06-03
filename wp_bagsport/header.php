<?php
	session_start();
	if( $_SESSION['sprytne'] !== 'bardzo' and !isset( $_GET['sprytne'] ) and !isset( $_COOKIE['sprytne'] ) ){
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
	wp_enqueue_style( "FA", get_stylesheet_directory_uri() . "/css/fontawesome-all{$infix}.css", array(), $buster );
	wp_enqueue_style( "style", get_stylesheet_directory_uri() . "/style{$infix}.css", array(), $buster );
	wp_enqueue_style( "override", get_stylesheet_directory_uri() . "/scss/override{$infix}.css", array(), $buster );
	
	wp_enqueue_script( "js-slim", get_stylesheet_directory_uri() . "/js/jquery.slim.js", array(), false, true );	
	wp_enqueue_script( "bootstrap-bundle", get_stylesheet_directory_uri() . "/js/bootstrap.bundle.js", array(), false, true );
	wp_enqueue_script( "bootstrap-js", get_stylesheet_directory_uri() . "/js/bootstrap.js", array(), false, true );
	wp_enqueue_script( "jQ", get_stylesheet_directory_uri() . "/js/jquery.js", array(), false, true );
	wp_enqueue_script( "GSAP-CSS", get_stylesheet_directory_uri() . "/js/CSSPlugin.min.js", array(), false, true );
	wp_enqueue_script( "GSAP-TweenLite", get_stylesheet_directory_uri() . "/js/TweenLite.min.js", array(), false, true );
	wp_enqueue_script( "GSAP-TimelineLite", get_stylesheet_directory_uri() . "/js/TimelineLite.min.js", array(), false, true );
	wp_enqueue_script( "jQ-touchswipe", get_stylesheet_directory_uri() . "/js/jquery.touchSwipe.min.js", array(), $buster, true );
	wp_enqueue_script( "GMAP", get_stylesheet_directory_uri() . "/js/gmap3.js", array(), $buster, true );
	
	wp_enqueue_script( "akordeon-js", get_stylesheet_directory_uri() . "/js/akordeon{$infix}.js", array(), $buster, true );
	wp_enqueue_script( "slider-partnerzy", get_stylesheet_directory_uri() . "/js/partnerzy{$infix}.js", array(), $buster, true );
	wp_enqueue_script( "hot-slider", get_stylesheet_directory_uri() . "/js/hot-slider{$infix}.js", array(), $buster, true );
	wp_enqueue_script( "popular-slider", get_stylesheet_directory_uri() . "/js/ogladane{$infix}.js", array(), $buster, true );
	wp_enqueue_script( "produkt", get_stylesheet_directory_uri() . "/js/produkt{$infix}.js", array(), $buster, true );
	wp_enqueue_script( "mapa", get_stylesheet_directory_uri() . "/js/mapa{$infix}.js", array(), $buster, true );
	wp_enqueue_script( "page", get_stylesheet_directory_uri() . "/js/page{$infix}.js", array(), $buster, true );
	
	?>
<!DOCTYPE html>
<html lang="pl">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
		<meta name="description" content="Torby reklamowe, gadżety reklamowe, sklep z gadżetami">
		<meta name="author" content="Scepter Agencja interaktywna">
		<?php
			if( get_post()->post_title == 'Produkt' ){
				$id = $_GET['id'];
				$t = get_post( $id );
				if( $t instanceof WP_POST ){
					OGTags( $t );
					
				}
				else{
					global $XM;
					OGTags( $XM->getProducts( 'single', $id )[0] );
					
				}
				
			}
			
		?>
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
			<div class="contact-top d-flex flex-wrap flex-column flex-md-row">
				<div class="icon-phone d-flex flex-wrap">
					<i class="top-icons ion-ios-telephone-outline"></i>
					<div class="d-flex flex-column">
						<p>
							<span>
								<a title="Kliknij, aby zadzwonić." style="text-decoration: none;" href="tel:<?php echo str_replace( " ", "", getInfo( 'infolinia' ) ); ?>">
									<?php printf( 'Infolinia: %s', getInfo( 'infolinia' ) ); ?>
								</a>
							</span>
						</p>
						<p class="font-grey">
							<?php echo getInfo( 'godziny_otwarcia' ); ?>
						</p>
					</div>
				</div>
				<div class="icon-phone d-flex flex-wrap">
					<i class="top-icons ion-ios-email-outline"></i>
					<div class="d-flex flex-column">
						<p>
							<span>
								<a title="Kliknij, aby napisać e-mail." style="text-decoration: none;" href="<?php printf( 'mailto:%s', getInfo( 'kontakt_e-mail' ) ); ?>">
									<?php echo getInfo( 'kontakt_e-mail' ); ?>
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
								<a title="Kliknij, aby wyświetlić na mapie." style="text-decoration: none;" target="_blank" href="<?php printf( 'https://maps.google.com/?q=%s', getInfo( 'adres_firmy' ) ); ?>">
									<?php echo getInfo( 'adres_firmy' ); ?>
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
					<ul class="navbar-nav">
						<li<?php if(is_home()) {?> class="active"<?php } ?>>
							<?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
						</li>
					</ul>
					<form method='GET' action='<?php echo home_url(); ?>' id='search' class='d-flex align-self-stretch'>
						<input type='text' name='s' class='' placeholder="kod, nazwa produktu albo słowo kluczowe">
						<button class='search_btn d-flex align-items-center justify-content-center'>
							<i class="fas fa-search"></i>
						</button>
					</form>
				</div>
			</div>
		</nav>
		<div class="breadcrumb">
			<div class="container">
				<?php printBreadcrumb(); ?>
			</div>
		</div>
		<!-- <div class="ciotka"></div> -->
		<?php get_template_part( 'template/segment/quickpanel' ); ?>