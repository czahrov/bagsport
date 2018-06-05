<?php

set_error_handler( function( $level, $message, $file, $line, $context ){
	switch( $level ){
		case 2:
		case 512:
			$level_text = "warning";
		break;
		case 256:
		case 4096:
			$level_text = "error";
		break;
		case 8:
		case 1024:
			$level_text = "notice";
		break;
		default:
			return false;
		
	}
	
	$msg = sprintf(
		'%s
%s
%u
%s
---
',
		date( "H:i:s" ),
		$file,
		$line,
		$message
		
	);
	
	$dst = sprintf(
		'%s/log/%s/%s.log',
		__DIR__,
		date( 'Y-m-d' ),
		$level_text
		
	);
	
	if( !file_exists( dirname( $dst ) ) ) mkdir( dirname( $dst ), 0755, true );
	
	error_log( $msg, 3, $dst );
	
} );

add_theme_support('post-thumbnails');

/* CRON */
// add_action( string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1 )
add_action( 'XMLupdate', function(){
	require_once __DIR__ . "/php/update.php";
	
} );

add_action( 'XMLhash', function( $arg ){
	require_once __DIR__ . "/php/rehash.php";
	
} );

// XML
require_once __DIR__ . '/php/autoloader.php';
require_once __DIR__ . '/XML_setup.php';

/* Generuje breadcrumb */
function printBreadcrumb(){
        /*
                <header class='breadcrumb d-flex align-items-center'>
                        <p class='link-bread'>Strona główna</p>
                        <h3>KRAJE</h3>
                </header>
        */
        $end = false;
        $current = get_post();
       
        echo "<header class='d-flex align-items-center'>";
        echo "<p class='link-bread'>Przeglądasz teraz: </p> " ;
       
		if( strpos( $_SERVER['REQUEST_URI'], 'kategoria' ) !== false ){
			
			printf(
					"<h3>
							<a href='%s'>%s</a>
					</h3>",
					$_SERVER['REQUEST_URI'],
					$_GET['nazwa']
				   
			);
			
		}
		elseif( strpos( $_SERVER['REQUEST_URI'], 'produkt' ) !== false ){
			$id = isset( $_GET['id'] )?( (int)$_GET['id'] ):( null );
			if( get_post( $id ) !== null ){
				$produkt = getProductData( get_post( $id ) );
				
			}
			else{
				global $XM;
				$produkt = getProductData( $XM->getProducts( 'single', $id )[0] );
				
			}
			
			printf(
					"<h3>
							<a href='%s'>%s</a>
					</h3>
					<h3>
							<a href='%s'>%s</a>
					</h3>",
					home_url( "kategoria/?nazwa={$produkt['kategoria']}" ),
					$produkt['kategoria'],
				   home_url( "produkt/?id={$produkt['ID']}" ),
					$produkt['nazwa']
				   
			);
			
		}
		else{
			
			do{
					printf(
							"<h3>
									<a href='%s'>%s</a>
							</h3>",
							get_the_permalink( $current->ID ),
							$current->post_title
						   
					);
				   
					if( $current->post_parent == 0 ){
							$end = true;
						   
					}
					else{
							$current = get_post( $current->post_parent );
						   
					}
				   
			}
			while( $end === false );
			
		}
       
        echo "</header>";
       
}

/* Menu */
function register_my_menus() {
  register_nav_menus(
    array(
      'header-menu' => __( 'Menu Główne' ),
      'zamowienia-menu' => __( 'Zamówienia stopka' ),
      'produkcja-menu' => __( 'Produkcja stopka' ),
      'pomoc-menu' => __( 'Pomoc stopka' ),
      'informacje-menu' => __( 'Informacje stopka' ),
      'menu-sklep' => __( 'Menu sklepowe' )
    )
  );
}
add_action( 'init', 'register_my_menus' );

/* Menu active  */
add_filter('nav_menu_css_class' , 'special_nav_class' , 1 , 2);

/* sidebar */
register_sidebar( array(
	'id' => 'sidebar-faq',
	'name' => 'Boczne FAQ',
	'description' => 'FAQ wyświetlane w panelu bocznym',
	'class' => 'side-faq',
	'before_widget' => '<li>',
	'after_widget' => '</li>',
	'before_title' => '<a>',
	'after_title' => '</a>',
	
) );

function special_nav_class ($classes, $item) {
    if (in_array('current-menu-item', $classes) ){
        $classes[] = 'active ';
    }
    return $classes;
}

require_once __DIR__ . "/php/PHPMailer/PHPMailerAutoload.php";

/* funkcja zwracająca obiekt PHPMailer */
function getMailer(){
	$ret = new PHPMailer();
	$ret->Encoding = 'base64';
	$ret->CharSet = 'utf-8';
	
	return $ret;
}


/* Funkcja generująca widok produktów */
function printProducts( $categoryName = "Produkcja własna", $arg = array(), $input = null ){
	$strona = isset( $_GET['strona'] )?( (int)$_GET['strona'] ):( 1 );
	$arg = array_merge(
		array(
			'per_page' => get_option('posts_per_page'),
			'page' => $strona,
			
		),
		$arg
	);
	
	$produkty = is_array( $input )?( $input ):( getCategory( $categoryName, $arg ) );
	
	if( count( $produkty) > 0 ){
		foreach( array_slice( $produkty, ($arg['page'] - 1) * $arg['per_page'], $arg['per_page'] ) as $item ){
			printf(
				'<div class="col-12 col-md-6 col-lg-4 mb-4 single-item">
				   <div class="card h-100 d-flex">
						  <a href="%s">
								 <div class="card-img" style="background-image: url(%s);"></div>
						  </a>
						  <div class="card-body d-flex flex-column">
								 <a href="%1$s"></a>
								 <div class="hover-element-shop">
										<a href="%1$s"></a>
										<a href="%s">wyślij zapytanie</a>
								</div>
								 <h4 class="card-title grow ">
										<a href="%1$s">%s</a>
								 </h4>
								 <div class="price">
										<h5>%.2f zł</h5>
								 </div>
								 <a href="%1$s" class="button-show-item">Zobacz</a>
						  </div>
				   </div>
				</div>',
				home_url( "produkt?id={$item['ID']}" ),
				$item['galeria'][0],                // adres obrazka produktu
				home_url( "zapytaj/?id={$item['ID']}" ),             // link do "wyślij zapytanie"
				$item['nazwa'],              // nazwa produktu
				(float)$item['brutto']              // cena produktu
				
			);
			
		}
		
		printPagin( $produkty );
		
	}
	else{
		echo "<div class=''>Ta kategoria nie posiada produktów</div>";
		
	}
	
}

/* funkcja generująca paginację */
function printPagin( $items, $arg = array() ){
	$arg = array_merge(
		array(
			'per_page' => get_option('posts_per_page'),
			'page' => isset( $_GET['strona'] )?( (int)$_GET['strona'] ):( 1 ),
			
		),
		$arg
	);
	
	if( count( $items ) > $arg['per_page'] ){
		$current = (int)$arg['page'] - 1;
		echo "<div class='pagination-products col-12 d-flex flex-wrap justify-content-center'>";
		$ret = array();
		$pages = array_chunk( $items, $arg['per_page'] );
		
		foreach( $pages as $num => $page ){
			parse_str( $_SERVER['QUERY_STRING'], $parsed );
			$parsed['strona'] = $num + 1;
			$httpQuery = http_build_query( $parsed );
			
			$ret[] = sprintf(
				'<a%s href="%s">%s</a>',
				$num === $current?( ' class="active"' ):( '' ),
				"?{$httpQuery}",
				$num + 1
				
			);
			
		}
		
		/* [0][1][2]...[x-2][x-1][x][x+1][x+2]...[z-2][z-1][z] */
		$end = 3;
		$mid = 2;
		
		if( $current + $mid + 1 < count( $ret ) - $end ){
			array_splice( $ret, $current + $mid + 1, -$end, array( "<a>...</a>" ) );
			
		}
		
		if( $current - $mid > $end ){
			array_splice( $ret, $end, $current - $mid - $end, array( "<a>...</a>" ) );
			
		}
		
		echo implode( "", $ret );
		
		echo"</div>";
	}
	
}

/* funkcja zwraca tablicę wszystkich produktów z danej kategorii */
function getCategory( $name = null, $arg = array() ){
	/* parametry zestawu pobieranych wpisów */
	$arg = array_merge(
		array(
			// 'num' => get_option('posts_per_page'),
			'order' => 'DESC',
			'orderby' => 'date',
		),
		$arg
	);
	/* tablica z wynikiem */
	$ret = array();
	
	/* pobieranie produktów z WP */
	if( $name == 'Produkcja własna' ){
		$items = get_posts( array(
			'numberposts' => -1,
			'category_name' => 'produkty',
			'order' => $arg['order'],
			'orderby' => $arg['orderby'],
			
		) );
		
		$ret =  array_map( function( $item ){
			return getProductData( $item );
			
		}, $items );
		
	}
	/* pobieranie produktów z bazy danych */
	else{
		global $XM;
		$ret = array_map( function( $item ){
			return getProductData( $item );
			
		}, $XM->getProducts( 'url', $name ) );
		
	}
	
	return $ret;
}

/* funkcja generująca tablicę z danymi produktu */
function getProductData( $obj = null ){
	$data = array(
		'ID' => 'brak danych',
		'galeria' => array(),
		'kategoria' => 'brak danych',
		'nazwa' => 'brak danych',
		'opis' => 'brak danych',
		'brutto' => 'brak danych',
		'dostępność' => 'brak danych',
		'kolor' => 'brak danych',
		'wymiary' => 'brak danych',
		
	);
	
	/* czy obiekt jest wpisem wordpress */
	if( $obj instanceof WP_POST ){
		$data['ID'] = $obj->ID;
		$data['nazwa'] = $obj->post_title;
		$data['kategoria'] = 'Produkcja własna';
		$data['opis'] = apply_filters( 'the_content', $obj->post_content );
		$data['brutto'] = (float)get_post_meta( $obj->ID, 'brutto', true );
		$data['dostępność'] = get_post_meta( $obj->ID, 'dostępność', true );
		$data['kolor'] = get_post_meta( $obj->ID, 'kolor', true );
		$data['wymiary'] = get_post_meta( $obj->ID, 'rozmiar', true );
		$data['galeria'] = extractGallery( get_post_meta( $obj->ID, 'galeria', true ) );
		
	}
	/* obiekt pochodzi z bazy danych XML */
	else{
		$data['ID'] = $obj['code'];
		$data['nazwa'] = $obj['title'];
		$data['kategoria'] = $obj['cat_name'];
		$data['opis'] = $obj['description'];
		$data['brutto'] = $obj['brutto'];
		$data['dostępność'] = $obj['instock'];
		$data['kolor'] = $obj['colors'];
		$data['wymiary'] = $obj['dimension'];
		$data['galeria'] = explode( ",", preg_replace( "~(\[|\]|\")~", "", $obj['photos'] ) );
		
	}
	
	return $data;
}

/* funkcja generująca og tagi do social mediów */
function OGTags( $obj ){
	$data = getProductData( $obj );
	
	printf(
'<meta property="og:title" content="%s" />
<meta property="og:type" content="%s" />
<meta property="og:url" content="%s" />
<meta property="og:site_name" content="%s" />
<meta property="og:image" content="%s" />
<meta property="og:description" content="%s" />',
	$data['nazwa'],
	'product',
	home_url( "produkt/?id={$data['ID']}" ),
	get_bloginfo( 'name' ),
	$data['galeria'][0],
	implode( " ", array_slice( explode( " ", strip_tags( $data['opis'] ) ) , 0, 50 ) )
	
	);
	
}

/* pobieranie informacji o stronie ( ze specjalnej strony ) */
function getInfo( $name = null ){
	/*
		facebook
		kontakt_e-mail
		infolinia
		stacjonarny
		adres_firmy
		godziny_otwarcia
	*/
	static $meta = null;
	
	if( $meta === null ){
		$meta = get_post_meta( get_page_by_title( 'Ustawienia' )->ID );
		
	}
	
	return $meta[ $name ][0];
	
}

/* funkcja wyszukująca galerię wordpressa w treści i zwracająca adresy url grafik w formie tablicy */
function extractGallery( $content = "" ){
	preg_match( "~ids=\"([^\"]+)\"~", $content, $match );
	if( !empty( $match[1] ) ){
		return array_map( function( $img ){
			return wp_get_attachment_url( $img );
			
		}, explode( ",", $match[1] ) );
		
	}
	
}

/* funkcja (filtr) generująca galerię zamiast galerii WP */
add_filter( 'custom_gallery', function( $content ){
	preg_match_all( '/\[gallery.+?ids=\"([\d,]+)\"\]/', $content, $galleries );
	
/*  $galleries
Array
(
    [0] => Array
        (
            [0] => [gallery columns="9" link="file" ids="201,200,198,197,79,85"]
            [1] => [gallery columns="9" link="file" ids="201,200,197,85"]
        )

    [1] => Array
        (
            [0] => 201,200,198,197,79,85
            [1] => 201,200,197,85
        )

)
*/
	
	if( empty( $galleries[0] ) ) return $content;
	
	$replace = array();
	
	foreach( $galleries[1] as $num => $set ){
		$ids = explode( ",", $set );
		$items = array_map( function( $id ){
			return sprintf(
				'<div class="wrapper col-12 col-md-6 col-lg-4 col-xl-3"><div class="item" style="background-image:url(%s);"></div></div>',
				wp_get_attachment_image_url( $id, 'full' )
				
			);
			
		}, $ids );
		
		$replace[] = sprintf(
			'<div class="custom_gallery container"><div class="row">%s</div></div>',
			implode( $items )
			
		);
		
	}
	
	$content = "<div class='gallery_popup d-flex align-items-center'>
	<div class='container'>
		<div class='row'>
			<div class='img fc-rozowy col-12 d-flex align-items-center justify-content-between'>
				<i class='nav prev pointer fas fa-chevron-circle-left'></i>
				<i class='nav next pointer fas fa-chevron-circle-right'></i>
			</div>
		</div>
	</div>
</div>" . str_replace( $galleries[0], $replace, $content );
	
	return $content;
} );


