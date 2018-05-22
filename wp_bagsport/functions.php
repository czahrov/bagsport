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
function printBlog( $categoryName = "produkty" ){
        $items = get_posts( array(
                'category_name' => $categoryName,
                'numberposts' => -1,
               
        ) );
       
        foreach( $items as $item ){
                printf(
                        '<div class="col-lg-4 col-md-6 mb-4 single-item">
                           <div class="card h-100 d-flex">
                                  <a href="#">
                                         <div class="card-img" style="background-image: url(%s);">
                                                <div class="icon">
                                                   <span class="fa fa-search-plus"></span>
                                                </div>
                                         </div>
                                  </a>
                                  <div class="card-body d-flex flex-column">
                                         <a href="#"></a>
                                         <div class="hover-element-shop">
                                                <a href="#"></a>
                                                <a href="%s">wyślij zapytanie</a>
                                        </div>
                                         <h4 class="card-title grow ">
                                                <a href="#">%s</a>
                                         </h4>
                                         <div class="price">
                                                <h5>%s</h5>
                                         </div>
                                         <a href="%s" class="button-show-item">Zobacz</a>
                                  </div>
                           </div>
                        </div>',
                        get_the_post_thumbnail_url( $item->ID, 'full' ),                // adres obrazka produktu
                        "/ask?{$item->ID}",             // link do "wyślij zapytanie"
                        $item->post_title,              // nazwa produktu
                        get_post_meta( $item->ID, 'price', true ),              // cena produktu
                        get_the_permalink( $item->ID )          // link do "zobacz"
                       
                );
               
        }
       
}

/* Funkcja generująca widok Bloga */
function printProducts( $categoryName = "blog" ){
        $items = get_posts( array(
                'category_name' => $categoryName,
                'numberposts' => -1,
               
        ) );
       
        foreach( $items as $item ){
                printf(
                        '<div class="col-lg-4 col-md-6 mb-4 single-item">
                           <div class="card h-100 d-flex">
                                  <a href="#">
                                         <div class="card-img" style="background-image: url(%s);">
                                                <div class="icon">
                                                   <span class="fa fa-search-plus"></span>
                                                </div>
                                         </div>
                                  </a>
                                  <div class="card-body d-flex flex-column">
                                         <a href="#"></a>
                                         <div class="hover-element-shop">
                                                <a href="#"></a>
                                                <a href="%s">wyślij zapytanie</a>
                                        </div>
                                         <h4 class="card-title grow ">
                                                <a href="#">%s</a>
                                         </h4>
                                         <div class="price">
                                                <h5>%s</h5>
                                         </div>
                                         <a href="%s" class="button-show-item">Zobacz</a>
                                  </div>
                           </div>
                        </div>',
                        get_the_post_thumbnail_url( $item->ID, 'full' ),                // adres obrazka produktu
                        "/ask?{$item->ID}",             // link do "wyślij zapytanie"
                        $item->post_title,              // nazwa produktu
                        get_post_meta( $item->ID, 'price', true ),              // cena produktu
                        get_the_permalink( $item->ID )          // link do "zobacz"
                       
                );
               
        }
       
}

// XML
require_once __DIR__ . '/php/autoloader.php';



