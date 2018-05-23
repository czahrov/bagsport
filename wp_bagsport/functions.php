<?

add_theme_support('post-thumbnails');

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
function printProducts( $categoryName = "produkty" ){
        $items = get_posts( array(
                'category_name' => $categoryName,
                'numberposts' => -1,
               
        ) );
       
        foreach( $items as $item ){
				$data = getProductData( $item->ID );
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
						home_url( sprintf( 'produkt?id=%s', $item->ID ) ),
                        $data['galeria'][0],                // adres obrazka produktu
                        home_url( "zapytaj/?id={$item->ID}" ),             // link do "wyślij zapytanie"
                        $item->post_title,              // nazwa produktu
                        (float)get_post_meta( $item->ID, 'brutto', true )              // cena produktu
                       
                );
               
        }
       
}

/* funkcja generująca tablicę z danymi produktu */
function getProductData( $id = null ){
	$data = array(
		'galeria' => array(),
		'kategoria' => 'brak danych',
		'nazwa' => 'brak danych',
		'opis' => 'brak danych',
		'brutto' => 'brak danych',
		'dostępność' => 'brak danych',
		'kolor' => 'brak danych',
		'wymiary' => 'brak danych',
		
	);
	
	/* czy ID wskazuje na wpis WP */
	$post = get_post( $id );
	if( $post !== null ){
		$data['nazwa'] = $post->post_title;
		$data['kategoria'] = 'Produkcja własna';
		$data['opis'] = apply_filters( 'the_content', $post->post_content );
		$data['brutto'] = (float)get_post_meta( $post->ID, 'brutto', true );
		$data['dostępność'] = get_post_meta( $post->ID, 'dostępność', true );
		$data['kolor'] = get_post_meta( $post->ID, 'kolor', true );
		$data['wymiary'] = get_post_meta( $post->ID, 'rozmiar', true );
		preg_match( "~ids=\"([^\"]+)\"~", get_post_meta( $post->ID, 'galeria', true ), $match );
		$data['galeria'] = array_map( function( $img ){
			return wp_get_attachment_url( $img );
			
		}, explode( ",", $match[1] ) );
		
	}
	else{
		/* produkt należy pobrać z bazy danych XML */
		
	}
	
	
	return $data;
}

/* funkcja generująca og tagi do social mediów */
function OGTags( $id ){
	$post = get_post( $id );
	$data = getProductData( $post->ID );
	
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

// XML
require_once __DIR__ . '/php/autoloader.php';



