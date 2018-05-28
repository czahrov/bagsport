<?

add_theme_support('post-thumbnails');

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

/* Funkcja generująca widok produktów */
function printProducts( $categoryName = "Produkcja własna", $arg = array(), $input = null ){
	$arg = array_merge(
		array(
			'per_page' => get_option('posts_per_page'),
			'page' => max( 1, (int)$_GET['strona'] ),
			
		),
		$arg
	);
	
	$produkty = is_array( $input )?( $input ):( getCategory( $categoryName, $arg ) );
	
	if( count( $produkty) > 0 ){
		foreach( array_slice( $produkty, ($arg['page'] - 1) * $arg['per_page'], $arg['per_page'] ) as $item ){
			printf(
				'<div class="col-lg-4 col-md-6 mb-4 single-item">
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
			'page' => max( 1, (int)$_GET['strona'] ),
			
		),
		$arg
	);
	
	if( count( $items ) > $arg['per_page'] ){
		echo "<div class='pagination-products col-12 d-flex flex-wrap justify-content-center'>";
		
		foreach( array_chunk( $items, $arg['per_page'] ) as $num => $page ){
			parse_str( $_SERVER['QUERY_STRING'], $parsed );
			$parsed['strona'] = $num + 1;
			$httpQuery = http_build_query( $parsed );
			
			printf(
				'<a%s href="%s">%s</a>',
				($num + 1) === $arg['page']?( ' class="active"' ):( '' ),
				"?{$httpQuery}",
				$num + 1
				
			);
			
		}
		
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
	home_url( "produkt/?id={$post->ID}" ),
	get_bloginfo( 'name' ),
	$data['galeria'][0],
	implode( " ", array_slice( explode( " ", strip_tags( $data['opis'] ) ) , 0, 50 ) )
	
	);
	
}

/* pobieranie informacji o stronie ( ze specjalnej strony ) */
function getInfo( $name = null ){
	static $meta = null;
	
	if( $meta === null ){
		$meta = get_post_meta( get_page_by_title( 'Ustawienia' )->ID );
		
	}
	
	return $meta[ $name ][0];
	
}

/* funkcja wyszukująca galerię wordpressa w treści i zwracająca adresy url grafik w formie tablicy */
function extractGallery( $content = "" ){
	preg_match( "~ids=\"([^\"]+)\"~", $content, $match );
	return array_map( function( $img ){
		return wp_get_attachment_url( $img );
		
	}, explode( ",", $match[1] ) );
	
}

