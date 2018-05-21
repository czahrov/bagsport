<?php
class FALKROSS extends XMLAbstract{
	public $_shop = 'FALKROSS';
	//protected $_debug = false;
	//protected $_cache_write = false;
	//protected $_cache_read = false;
	
	// konstruktor
	public function __construct(){
		// $this->logger( "" . __CLASS__ . " loaded!", __FUNCTION__, __CLASS__ );
		//$this->_config['refresh'] = 60 * 30;		// 30m
		$this->_config['dnd'] = __DIR__ . "/DND";
		$this->_config['cache'] = __DIR__ . "/CACHE";
		$this->_config['remote'] = array(
			"http://download.falk-ross.eu/download/article/falkross-articles.xml",
			
		);
		
	}
	
	// funkcja importująca dane o budowie menu w formie tablicy
	protected function getMenu(){
		//return array();
		$ret = array();
		$file = "falkross-articles.xml";		// plik do załadowania
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->style as $item ){
				
				if( $item->style_category_list->style_category_main->style_category_sub->count() > 0 ) foreach( $item->style_category_list->style_category_main->style_category_sub as $cat ){
					$ret[ (string)$cat->language->pl ] = array();
					
				}
				
			}
			
		}
		
		//return $ret;
		return array(
			'FALKROSS' => $ret,
			
		);
	}
	
	// funkcja importująca dane o produktach w formie tablicy
	protected function getProducts(){
		//return array();
		$ret = array();
		$file = "falkross-articles.xml";		// plik do załadowania
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			
			foreach( $this->_XML[ $file ]->style as $item ){
				
				$img = array();
				if( $item->style_picture_list->count() > 0 ) foreach( $item->style_picture_list->style_picture as $picture ){
					$img[] = (string)$picture->url;
					
				}
				
				$colors = array();
				if( $item->style_filter_list->style_color_group_list->count() > 0 ) foreach( $item->style_filter_list->style_color_group_list->children() as $color ){
					$colors[] = (string)$color;
					
				}
				
				$weights = array();
				if( $item->style_filter_list->style_weight_group_list->count() > 0 ) foreach( $item->style_filter_list->style_weight_group_list->children() as $weight ){
					$weights[] = (string)$weight;
				}
				
				$cats = array();
				foreach( $item->style_category_list->style_category_main->style_category_sub as $cat ){
					$name = (string)$item->style_name->language->pl;
					$cat_slug = $this->stdNameCache( 'odzież reklamowa' );
					$subcat_name = (string)$cat->language->pl;
					
					if( $subcat_name === 'Bluzy' && (
							stripos( $name, 'spodnie') !== false 
							or stripos( $name, 'spodenki') !== false 
							or stripos( $name, 'legginsy') !== false 
							or stripos( $name, 'szorty') !== false 
						) ){
						continue;
						
					}
					elseif( $subcat_name === 'Kurtki' && (
							stripos( $name, 'spodnie') !== false 
							or stripos( $name, 'softshell') !== false 
							or stripos( $name, 'polar') !== false 
						) ){
						continue;
						
					}
					elseif( $subcat_name === 'Polary' && (
							stripos( $name, 'polar') === false
						) ){
						continue;
						
					}
					elseif( $subcat_name === 'Ręczniki i szlafroki' && (
							stripos( $name, 'ręcznik') === false
							and stripos( $name, 'szlafrok') === false
						) ){
						continue;
						
					}
					elseif( $subcat_name === 'Softshelle' && (
							stripos( $name, 'softshell') === false
						) ){
						continue;
						
					}
					elseif( $subcat_name === 'T-shirty' && (
							stripos( $name, 't-shirt') === false
						) ){
						continue;
						
					}
					
					$cat_name = sprintf( "%s-%s", 
						$cat_slug,
						$this->stdNameCache( $subcat_name )
						
					);
					$cats[ $cat_name ] = array();
					
				}
				
				$base = array(
					'SHOP' => $this->_shop,
					'ID' => 'brak danych',
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
						'CURRENCY' => '',
					),
					'PRICE_ALT' => 'Wycena indywidualna<br>( telefon/mail )',
					'MODEL' => 'brak danych',
					'WEIGHT' => 'brak danych',
					'BRAND' => 'brak danych',
					
				);
				
				$product = array(
					'ID' => (string)$item->style_nr,
					'NAME' => (string)$item->style_name->language->pl,
					'DSCR' => nl2br( (string)$item->style_description->language->pl ),
					'IMG' => $img,
					'CAT' => $cats,
					// 'DIM' => 'brak danych',
					// 'MARK' => array( 'brak danych' ),
					// 'MARKSIZE' => array( 'brak danych' ),
					// 'MARKTYPE' => array( 'brak danych' ),
					// 'INSTOCK' => 'brak danych',
					// 'MATTER' => 'brak danych',
					'COLOR' => implode( ", ", $colors ),
					// 'COUNTRY' => 'brak danych',
					// 'MARKCOLORS' => 1,
					// 'PRICE' => array(
						// 'BRUTTO' => 0,
						// 'NETTO' => null,
					// ),
					// 'MODEL' => 'brak danych',
					'WEIGHT' => implode( ", ", $weights ),
					'BRAND' => (string)$item->brand_name,
					'MARKCOLORS' => $out[ 'MARKCOLORS' ],
					'PRICE' => $out[ 'PRICE' ],
					'PRICE_ALT' => $out[ 'PRICE_ALT' ],
					
				);
				
				$out = array_merge(
					$base,
					$product
				);
				
				$ret[] = $out;
				
				/* Dodatkowe produkty z SKU */
				if( count( $item->sku_list->sku ) > 0 ){
					foreach( $item->sku_list->sku as $sku ){
						$temp = array(
							'SHOP' => $out[ 'SHOP' ],
							'ID' => (string)$sku->sku_artnum,
							'NAME' => sprintf( "%s<br>%s<br>%s", 
								$out[ 'NAME' ], 
								$sku->sku_color_name,
								$sku->sku_size_name
							),
							'DSCR' => $out[ 'DSCR' ],
							'IMG' => array( (string)$sku->sku_color_picture_url ),
							'CAT' => $out[ 'CAT' ],
							'DIM' => (string)$sku->sku_size_name,
							'COUNTRY' => (string)$sku->sku_coo,
							'WEIGHT' => sprintf( '%.3f kg', 
								$sku->sku_weight
								
							),
							'MARK' => $out[ 'MARK' ],
							'MARKSIZE' => $out[ 'MARKSIZE' ],
							'MARKTYPE' => $out[ 'MARKTYPE' ],
							'INSTOCK' => $out[ 'INSTOCK' ],
							'SHORT_ID' => $out[ 'ID' ],
							
						);
						
						$ret[] = $temp;
						
					}
					
				}
				
			}
			
		}
		
		return $ret;
	}
	
}
