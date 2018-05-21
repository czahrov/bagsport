<?php
class JAGUARGIFT extends XMLAbstract{
	public $_shop = 'JAGUARGIFT';
	//protected $_debug = false;
	//protected $_cache_write = false;
	//protected $_cache_read = false;
	
	// konstruktor
	public function __construct( $aContext ){
		// $this->logger( "" . __CLASS__ . " loaded!", __FUNCTION__, __CLASS__ );
		//$this->_config['refresh'] = 60 * 30;		// 30m
		$this->_config['dnd'] = __DIR__ . "/DND";
		$this->_config['cache'] = __DIR__ . "/CACHE";
		$this->_config['remote'] = array(
			"http://www.jaguargift.com/pl/jaguargift.xml",
			
		);
		$this->_context = $aContext;
		
	}
	
	// funkcja importująca dane o budowie menu w formie tablicy
	protected function getMenu(){
		//return array();
		$ret = array();
		$file = "jaguargift.xml";		// plik do załadowania
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->children() as $item ){
				
				foreach( $item->category->{'list-item'} as $cat ){
					$cat_name = 'VIP Skóra';
					$subcat_name = (string)$cat->name;
					$ret[ $cat_name ][ $subcat_name ] = array();
					
				}
				
			}
			
		}
		
		//return $ret;
		return array(
			'JAGUARGIFT' => $ret,
			
		);
	}
	
	// funkcja importująca dane o produktach w formie tablicy
	protected function getProducts(){
		//return array();
		$ret = array();
		$file = "jaguargift.xml";		// plik do załadowania
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			
			foreach( $this->_XML[ $file ]->children() as $item ){
				
				$price_netto = (float)str_replace( ",", ".", $item->price );
				$weight = sprintf( "%.3f kg", $item->weight );
				$dim = (string)$item->size;
				
				$matters = array();
				foreach( $item->materials->{'list-item'} as $t ){
					$matters[] = (string)$t->value;
					
				}
				$matter = implode( ", ", $matters );
				
				$dscrs = array();
				foreach( $item->features->{'list-item'} as $t ){
					$dscrs[] = (string)$t->value;
					
				}
				$dscr = implode( ",<br>", $dscrs );
				
				$mark = array();
				foreach( $item->marking->{'list-item'} as $t ){
					$mark_size = "";
					$mark_type = (string)$t->vendo_code;
					
					switch( $mark_type ){
						case 'TLOCZENIEPERSONALIZACJA':
						case 'TLOCZENIE':
							$mark_type = 'HS';
							
						break;
						case 'PERSONALIZACJALASEREM':
						case 'GRAWERLASEREM':
						case 'BLASZKAZGRAWEREMMALA':
						case 'BLASZKAZGRAWEREMSREDNIA':
						case 'BLASZKAZGRAWEREMDUZA':
							$mark_type = 'L2';
							
						break;
						case 'HOTSTAMPING1KOLOR':
						case 'HOTSTAMPINGMINI1KOLOR':
						case 'HOTSTAMPING2KOLORY':
							$mark_type = 'TT1';
							
						break;
						case 'NADRUK':
							$mark_type = 'T1';
							
						break;
						default:
							continue;
						
						// APLIKACJAPVC
					}
					
					if( !is_array( $mark[ $mark_size ] ) or !in_array( $mark_type, $mark[ $mark_size ] ) ){
						$mark[ $mark_size ][] = $mark_type;
						
					}
					
				}
				
				/* ================ KATEGORIA ================ */
				$cat_name = "VIP Skóra";
				$categories = array();
				foreach( $item->category->{'list-item'} as $t ){
					
					/* ================ PODKATEGORIA ================ */
					$subcat_name = (string)$t->name;
					
					/* if( !in_array( $subcat_name, array( 
						'Nowości', 
						'Produkty z filmami Video', 
						'Made in Europe' 
					) ) ){
						$cat_name_slug = $this->stdNameCache( $cat_name );
						$subcat_name_slug = $cat_name_slug . "-" . $this->stdNameCache( $subcat_name );
						$categories[ $cat_name_slug ][ $subcat_name_slug ] = array();
					
					} */
					
					$cat_name_slug = $this->stdNameCache( $cat_name );
					$subcat_name_slug = $cat_name_slug . "-" . $this->stdNameCache( $subcat_name );
					$categories[ $cat_name_slug ][ $subcat_name_slug ] = array();
					
				}
				
				foreach( $item->available_colors->children() as $subitem ){
					$id = (string)$subitem->vendo_code;
					$color = (string)$subitem->color->name;
					$name = sprintf( "%s (%s)", $subitem->name, $color );
					
					$img = array();
					$t_img = (string)$subitem->main_image->image;
					if( !empty( $t_img ) ) $img[] = $t_img;
					
					if( count( $item->images->children() ) > 0 ) foreach( $item->images->children() as $t ){
						$img[] = (string)$t->image;
						
					}
					
					if( empty( $img ) ) $img[] = "../wp-content/themes/merkuriusz/img/noimage.png";
					
					$instock = (int)$subitem->availability_count;
					
					$marks_text = array();
					foreach( $item->marking->{'list-item'} as $t ){
						$marks_text[] = (string)$t->name;
						
					}
					
					$ret[] = array_merge(
						array(
							'SHOP' => $this->_shop,
							'ID' => 'brak danych',
							/* 'SHORT_ID' => '', */
							'NAME' => 'brak danych',
							'DSCR' => 'brak danych',
							'IMG' => array(),
							'CAT' => array(),
							'DIM' => 'brak danych',
							// 'MARK' => array(),
							// 'MARKSIZE' => array(),
							// 'MARKTYPE' => array(),
							'MARK_TEXT' => '',
							'INSTOCK' => 'brak danych',
							'MATTER' => 'brak danych',
							'COLOR' => 'brak danych',
							'COUNTRY' => 'brak danych',
							'MARKCOLORS' => 1,
							'PRICE' => array(
								'BRUTTO' => 0,
								'NETTO' => null,
								'CURRENCY' => 'PLN',
							),
							'PRICE_ALT' => 'Wycena indywidualna<br>( telefon/mail )',
							'MODEL' => 'brak danych',
							'WEIGHT' => 'brak danych',
							'BRAND' => 'brak danych',
							
						),
						array(
							'ID' => $id,
							'SHORT_ID' => substr( $id, 0, -2 ),
							'NAME' => $name,
							'DSCR' => $dscr,
							'IMG' => $img,
							'CAT' => $categories,
							'DIM' => $dim,
							// 'MARK' => $mark,
							// 'MARKSIZE' => array( 'brak danych' ),
							// 'MARKTYPE' => array( 'brak danych' ),
							'MARK_TEXT' => implode( "<br>", $marks_text ),
							'INSTOCK' => $instock,
							'MATTER' => $matter,
							'COLOR' => $color,
							'COUNTRY' => 'brak danych',
							'MARKCOLORS' => 1,
							'PRICE' => array(
								'NETTO' => $price_netto,
								'BRUTTO' => $this->price2brutto( $price_netto ),
								'CURRENCY' => 'PLN',
							),
							'PRICE_ALT' => 'Wycena indywidualna<br>( telefon/mail )',
							'MODEL' => 'brak danych',
							'WEIGHT' => $weight,
							'BRAND' => 'brak danych',
						)
					);
					
				}
				
			}
			
		}
		
		return $ret;
	}
	
}
