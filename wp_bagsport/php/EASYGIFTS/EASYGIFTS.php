<?php
class EASYGIFTS extends XMLAbstract {
	public $_shop = 'EASYGIFTS';
	//protected $_debug = false;
	//protected $_cache_write = false;
	//protected $_cache_read = false;
	
	// konstruktor
	public function __construct(){
		// $this->logger( "" . __CLASS__ . " loaded!", __FUNCTION__, __CLASS__ );
		//$this->_config['refresh'] = 60 * 60;		// 1h
		$this->_config['dnd'] = __DIR__ . "/DND";
		$this->_config['cache'] = __DIR__ . "/CACHE";
		$this->_config['remote'] = array(
			"http://www.easygifts.com.pl/data/webapi/pl/xml/offer.xml",
			"http://www.easygifts.com.pl/data/webapi/pl/xml/stocks.xml"
		);
		/* $this->_config[ 'img' ] = array(
			"http://www.easygifts.com.pl/data/catalogs2017/easygifts-2017-photos.zip",
			"http://www.easygifts.com.pl/data/files/195/zdjecia-victorinox-zdj-.zip?v=716",
			"http://www.easygifts.com.pl/data/files/58/pendrive-y-flashpod-specjalne-pen-.rar?v=694",
			"http://www.easygifts.com.pl/data/files/73/pendrive-y-pqi-seria-inteligent-drive-pqi-.rar?v=131",
			"http://www.easygifts.com.pl/data/files/74/pendrive-y-pqi-seria-cool-drive-pqi-.rar?v=75",
			"http://www.easygifts.com.pl/data/files/75/pendrive-y-pqi-seria-travelling-disc-pqi-.rar?v=1198",
			"http://www.easygifts.com.pl/data/files/76/pendrive-y-pqi-dyski-przenosne-2-5-pqi-.rar?v=1310",
			
		); */
		
	}
	
	// funkcja importująca dane o budowie menu w formie tablicy
	protected function getMenu(){
		$ret = array();
		$file = "offer.xml";		// plik do załadowania
		
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->product as $product ){
				if( $product->categories->count() > 0 ) foreach( $product->categories->category as $category ){
					//$this->genMenuTree( $category, $ret );
					
					//$name = $this->stdNameCache( (string)$category->name );
					//$ret[ $name ] = $this->genMenuTree( $category );
					$ret = array_merge_recursive( $ret, $this->genMenuTree( $category, $product ) );
					
				}
				
			}
			
		}
		
		return array(
			'EASY_GIFTS' => $ret,
			
		);
		
	}
	
	// funkcja rekurencyjna tworząca drzewo podkategorii danej kategorii
	private function genMenuTree( SimpleXMLElement $category, $node ){
		$ret = array();
		// $cat_name = $this->stdNameCache( (string)$category->name );
		//$cat_name = apply_filters( 'stdName', (string)$category->name );
		$cat_name = strtolower( (string)$category->name );
		//$ret[ $cat_name ] = array();
		$item_title = (string)$node->baseinfo->name;
		$item_dscr = (string)$node->baseinfo->intro;
		
		/* ========== KATEGORIA ========== */
			
		if( $cat_name === 'podróże' ){
			$cat_name = 'podróż';
			
		}
		elseif( $cat_name === 'torby' ){
			$cat_name = 'torby i plecaki';
			
		}
		elseif( $cat_name === "pendrive'y" ){
			$cat_name = 'pendrive';
			
		}
		elseif( $cat_name === "elektronika markowa" ){
			$cat_name = 'vip elektronika';
			
		}
		elseif( $cat_name === "biuro i akcesoria biurowe" ){
			$cat_name = 'biuro';
			
		}
		elseif( $cat_name === "odpoczynek" ){
			$cat_name = 'wypoczynek';
			
		}
		elseif( $cat_name === "czas i elektronika" ){
			$cat_name = 'elektronika';
			
		}
		elseif( $cat_name === "czapki cofee" ){
			$cat_name = 'cofee';
			
		}
		elseif( strpos( $cat_name, "świąteczn" ) !== false ){
			$cat_name = 'świąteczne';
			
		}
		
		//$cat_name = $this->stdNameCache( $cat_name );
		
		/* ==================== */
		
		/* ==================== FILTRY KATEGORII ==================== */
		if( stripos( (string)$node->baseinfo->name, 'xlyne' ) !== false ){
			$cat_name = 'vip elektronika';
			$subcat_name = 'XLYNE';
			
		}
		elseif( stripos( (string)$node->baseinfo->name, 'blaupunkt' ) !== false ){
			$cat_name = 'vip elektronika';
			$subcat_name = 'Blaupunkt';
			
		}
		elseif( $cat_name === 'victorinox lifestyle - akcesoria podróżne' ){
			$cat_name = 'victorinox';
			$subcat_name = 'akcesoria podróżne';
			
		}
		elseif( $cat_name === 'wenger - bagaże biznesowe i akcesoria podróżne' ){
			$cat_name = 'wenger';
			$subcat_name = 'bagaże biznesowe i akcesoria podróżne';
			
		}
		elseif( $cat_name === 'wenger - bestsellery' ){
			$cat_name = 'wenger';
			$subcat_name = 'bestsellery';
			
		}
		elseif( stripos( (string)$node->baseinfo->name, 'kielisz' ) ){
			$cat_name = 'do picia';
			$subcat_name = 'kieliszki';
			
		}
		elseif( $cat_name === 'gadżety piłkarskie' ){
			$cat_name = 'Sport i rekreacja';
			$subcat_name = 'Akcesoria sportowe';
			
		}
		elseif( $cat_name === 'wyprzedaŻ' ){
			$cat_name = 'gadżety reklamowe';
			$subcat_name = 'inne';
			
		}
		elseif( $cat_name === 'długopisy z grawerem za 10 groszy!' ){
			$cat_name = 'materiały piśmiennicze';
			$subcat_name = 'inne';
			
		}
		elseif( $cat_name === 'różne' ){
			$cat_name = 'gadżety reklamowe';
			$subcat_name = 'inne';
			
		}
		elseif( $cat_name === 'opakowania' ){
			$cat_name = 'gadżety reklamowe';
			$subcat_name = 'opakowania upominkowe';
			
		}
		elseif( $cat_name === 'elektronika' ){
			
			if( $category->subcategories->count() === 0 ){
				$subcat_name = 'akcesoria';
				
			}
			
		}
		elseif( $cat_name === 'breloki akrylowe' ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'breloki';
				$subcat_name = 'akrylowe';
				
			}
			
		}
		elseif( $cat_name === 'mobile' ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'elektronika';
				$subcat_name = 'akcesoria';
				
			}
			
		}
		elseif( $cat_name === 'pinsy' ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'pinsy';
				$subcat_name = 'metalowe';
				
			}
			
		}
		elseif( $cat_name === 'etui' ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'elektronika';
				$subcat_name = 'akcesoria';
				
			}
			
		}
		elseif( $cat_name === "opakowania dla pendrive'ów" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'elektronika';
				$subcat_name = 'akcesoria';
				
			}
			
		}
		elseif( $cat_name === "cerruti 1881" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'cerruti 1881';
				
				if( stripos( $item_title, 'pióro' ) !== false or 
					stripos( $item_title, 'pen' ) !== false ){
					$subcat_name = 'pióra';
					
				}
				elseif( stripos( $item_title, 'długopis' ) !== false ){
					$subcat_name = 'długopisy';
					
				}
				elseif( stripos( $item_title, 'zestaw' ) !== false ){
					$subcat_name = 'zestawy upominkowe';
					
				}
				elseif( stripos( $item_title, 'portfel' ) !== false or 
					stripos( $item_title, 'wallet' ) !== false ){
					$subcat_name = 'portfele';
					
				}
				elseif( stripos( $item_title, 'teczka' ) !== false ){
					$subcat_name = 'teczki';
					
				}
				elseif( stripos( $item_title, 'torb' ) !== false or 
					stripos( $item_title, 'toreb' ) !== false or 
					stripos( $item_title, 'bag' ) !== false ){
					$subcat_name = 'Torby i torebki';
					
				}
				elseif( stripos( $item_title, 'parasol' ) !== false ){
					$subcat_name = 'parasole';
					
				}
				elseif( stripos( $item_title, 'brelok' ) !== false or 
					stripos( $item_title, 'key ring' ) !== false or 
					stripos( $item_title, 'walizka' ) !== false or 
					stripos( $item_title, 'saszetka' ) !== false or 
					stripos( $item_title, 'case' ) !== false ){
					$subcat_name = 'podróż';
					
				}
				elseif( stripos( $item_title, 'note' ) !== false or 
					stripos( $item_title, 'wizyt' ) !== false or 
					stripos( $item_title, 'card holder' ) !== false or 
					stripos( $item_title, 'folder' ) !== false ){
					$subcat_name = 'notesy i notatniki';
					
				}
				else{
					$subcat_name = 'akcesoria';
					
				}
				
			}
			
		}
		elseif( $cat_name === "power of brands – oferta specjalna 24h" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'power of brands';
				$subcat_name = 'oferta specjalna';
				
			}
			
		}
		elseif( $cat_name === "ungaro" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'ungaro';
				
				if( stripos( $item_title, 'zegarek' ) !== false or 
					stripos( $item_title, 'chronograph' ) !== false or 
					stripos( $item_title, 'watch' ) !== false ){
					$subcat_name = 'zegarki';
					
				}
				elseif( stripos( $item_title, 'opakowan' ) !== false ){
					$subcat_name = 'opakowania';
					
				}
				elseif( stripos( $item_title, 'zestaw' ) !== false ){
					$subcat_name = 'zestawy upominkowe';
					
				}
				elseif( stripos( $item_title, 'piór' ) !== false or 
					stripos( $item_title, 'pen' ) !== false ){
					$subcat_name = 'pióra';
					
				}
				elseif( stripos( $item_title, 'długopis' ) !== false ){
					$subcat_name = 'długopisy';
					
				}
				elseif( stripos( $item_title, 'brelok' ) !== false ){
					$subcat_name = 'breloki';
					
				}
				elseif( stripos( $item_title, 'teczka' ) !== false ){
					$subcat_name = 'teczki';
					
				}
				elseif( stripos( $item_title, 'note' ) !== false or 
					stripos( $item_title, 'wizyt' ) !== false ){
					$subcat_name = 'Notesy i notatniki';
					
				}
				elseif( stripos( $item_title, 'portfel' ) !== false or 
					stripos( $item_title, 'wallet' ) !== false ){
					$subcat_name = 'portfele';
					
				}
				elseif( stripos( $item_title, 'etui' ) !== false ){
					$subcat_name = 'etui';
					
				}
				elseif( stripos( $item_title, 'torb' ) !== false or 
					stripos( $item_title, 'bag' ) !== false ){
					$subcat_name = 'Torby i torebki';
					
				}
				elseif( stripos( $item_title, 'portmon' ) !== false ){
					$subcat_name = 'Portmonetki';
					
				}
				elseif( stripos( $item_title, 'szal' ) !== false or 
					stripos( $item_title, 'apasz' ) !== false or 
					stripos( $item_title, 'scarf' ) !== false ){
					$subcat_name = 'Apaszki i szale';
					
				}
				else{
					$subcat_name = 'akcesoria';
					
				}
				
			}
			
		}
		elseif( $cat_name === "selfie sticki" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'Akcesoria do telefonów i tabletów';
				$subcat_name = 'Akcesoria';
				
			}
			
		}
		elseif( $cat_name === "inne" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'gadżety reklamowe';
				$subcat_name = 'inne';
				
			}
			
		}
		elseif( $cat_name === "torby by jassz" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'Tekstylia';
				$subcat_name = 'Torby jassz';
				
			}
			
		}
		elseif( $cat_name === "nowości 2018" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'gadżety reklamowe';
				$subcat_name = 'inne';
				
			}
			
		}
		elseif( $cat_name === "nina ricci" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'nina ricci';
				
				if( stripos( $item_title, 'pióro' ) !== false or 
					stripos( $item_title, 'pen' ) !== false ){
					$subcat_name = 'pióra';
					
				}
				elseif( stripos( $item_title, 'dług' ) !== false ){
					$subcat_name = 'długopisy';
					
				}
				elseif( stripos( $item_title, 'zestaw' ) !== false ){
					$subcat_name = 'zestawy upominkowe';
					
				}
				elseif( stripos( $item_title, 'note' ) !== false or 
					stripos( $item_title, 'wizyt' ) !== false ){
					$subcat_name = 'notesy i notatniki';
					
				}
				elseif( stripos( $item_title, 'tecz' ) !== false or 
					stripos( $item_title, 'folder' ) !== false ){
					$subcat_name = 'teczki';
					
				}
				elseif( stripos( $item_title, 'portmon' ) !== false or 
					stripos( $item_title, 'portfel' ) !== false ){
					$subcat_name = 'portfele';
					
				}
				elseif( stripos( $item_title, 'torb' ) !== false ){
					$subcat_name = 'Torby i torebki';
					
				}
				elseif( stripos( $item_title, 'etui' ) !== false or 
					stripos( $item_title, 'pouch' ) !== false ){
					$subcat_name = 'etui';
					
				}
				else{
					$subcat_name = 'akcesoria';
					
				}
				
			}
			
		}
		elseif( $cat_name === "jean-louis scherrer" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'Jean-Louis Scherrer';
				
				if( stripos( $item_title, 'zestaw' ) !== false ){
					$subcat_name = 'Zestawy upominkowe';
					
				}
				elseif( stripos( $item_title, 'szal' ) !== false ){
					$subcat_name = 'Apaszki i szale';
					
				}
				elseif( stripos( $item_title, 'bransolet' ) !== false or 
					stripos( $item_title, 'Łańcu' ) !== false or 
					stripos( $item_title, 'necklace' ) !== false ){
					$subcat_name = 'biżuteria';
					
				}
				else{
					$subcat_name = 'Akcesoria';
					
				}
				
			}
			
		}
		elseif( $cat_name === "christian lacroix" ){
			
			if( $category->subcategories->count() === 0 ){
				$cat_name = 'christian lacroix';
				
				if( stripos( $item_title, 'zestaw' ) !== false ){
					$subcat_name = 'zestawy upominkowe';
					
				}
				elseif( stripos( $item_title, 'zegarek' ) !== false or 
					stripos( $item_title, 'chronograph' ) !== false or 
					stripos( $item_title, 'watch' ) !== false ){
					$subcat_name = 'zegarki';
					
				}
				elseif( stripos( $item_title, 'długopis' ) !== false ){
					$subcat_name = 'długopisy';
					
				}
				elseif( stripos( $item_title, 'pióro' ) !== false or 
					stripos( $item_title, 'pen' ) !== false ){
					$subcat_name = 'pióra';
					
				}
				elseif( stripos( $item_title, 'note' ) !== false or 
					stripos( $item_title, 'wizytow' ) !== false ){
					$subcat_name = 'Notesy i notatniki';
					
				}
				elseif( stripos( $item_title, 'torb' ) !== false ){
					$subcat_name = 'Torby i torebki';
					
				}
				elseif( stripos( $item_title, 'portfel' ) !== false or 
					stripos( $item_title, 'portmon' ) !== false or 
					stripos( $item_title, 'wallet' ) !== false ){
					$subcat_name = 'Portfele';
					
				}
				elseif( stripos( $item_title, 'teczka' ) !== false ){
					$subcat_name = 'teczki';
					
				}
				else{
					$subcat_name = 'akcesoria';
					
				}
				
			}
			
		}
		
		
		/* ==================== */
		
		
		if( $category->subcategories->count() > 0 && empty( $subcat_name ) ){
			foreach( $category->subcategories->subcategory as $subcategory ){
				//$subcat_name = $this->stdNameCache( (string)$subcategory->name );
				//$subcat_name = apply_filters( 'stdName', (string)$subcategory->name );
				$subcat_name = strtolower( (string)$subcategory->name );
				
				/* ========== PODKATEGORIA ========== */
				
				if( $subcat_name === 'parasole i parasolki' ){
					$cat_name = "Przeciwdeszczowe";
					
					if( stripos( (string)$node->baseinfo->name, 'parasolka' ) !== false ){
						$subcat_name = "parasolki";
						
					}
					elseif( stripos( (string)$node->baseinfo->name, 'parasol' ) !== false ){
						$subcat_name = "parasole";
						
					}
					else{
						$subcat_name = "inne";
						
					}
					
					
				}
				elseif( $subcat_name === 'poduszki i koce' ){
					$cat_name = "wypoczynek";
					
					if( stripos( (string)$node->baseinfo->name, 'koc' ) !== false ){
						$subcat_name = 'Koce';
						
					}
					else{
						$subcat_name = 'Poduszki';
						
					}
					
				}
				elseif( $subcat_name === 'pielęgnacja obuwia' ){
					$cat_name = "dom";
					
				}
				elseif( $subcat_name === 'płaszcze przeciwdeszczowe' ){
					$cat_name = "Przeciwdeszczowe";
					
					if( stripos( (string)$node->baseinfo->name, 'peleryna' ) !== false ){
						$subcat_name = "Peleryny";
						
					}
					else{
						$subcat_name = "Płaszcze";
						
					}
					
					
				}
				elseif( $subcat_name === 'torby podróżne' ){
					if( stripos( (string)$node->baseinfo->name, 'ramię' ) !== false ){
						$subcat_name = 'na ramię';
						
					}
					else{
						$subcat_name = 'podróżne';
						
					}
					
				}
				elseif( $subcat_name === 'torby i worki sportowe' ){
					if( stripos( (string)$node->baseinfo->name, 'sport' ) !== false ){
						$subcat_name = 'sportowe';
					}
					else{
						$cat_name = 'inne';
						// $subcat_name = '';
						
					}
					
				}
				elseif( $subcat_name === 'torby na zakupy' ){
					$subcat_name = 'na zakupy';
					
				}
				elseif( $subcat_name === 'torby na laptopy' ){
					$subcat_name = 'na laptopa';
					
				}
				elseif( $subcat_name === 'torby jassz' ){
					$cat_name = 'tekstylia';
					
				}
				elseif( $subcat_name === 'narzędzie wielofunkcyjne' ){
					$subcat_name = 'Wielofunkcyjne';
					
				}
				elseif( $subcat_name === 'zestawy narzędzi' ){
					$subcat_name = 'zestawy';
					
				}
				elseif( strpos( $subcat_name, "pendrive'y " ) !== false ){
					$subcat_name = str_replace( "pendrive'y ", "", $subcat_name );
					
				}
				elseif( $subcat_name === 'kalkulatory' ){
					$cat_name = 'elektronika';
					
				}
				elseif( $subcat_name === 'teczki na dokumenty' ){
					$subcat_name = 'teczki';
					
				}
				elseif( $subcat_name === 'stojaki biurkowe' ){
					$subcat_name = 'stojaki';
					
				}
				elseif( $subcat_name === 'akcesoria do telefonów' ){
					$cat_name = 'elektronika';
					
				}
				elseif( $subcat_name === 'zestawy upominkowe (sety)' ){
					$subcat_name = 'zestawy upominkowe';
					
				}
				elseif( $subcat_name === 'zestawy piśmiennicze' ){
					$subcat_name = 'zestawy piśmienne';
					
				}
				elseif( $subcat_name === 'eko długopisy' ){
					$cat_name = 'eco gadżet';
					$subcat_name = '';
					
				}
				elseif( $subcat_name === 'wskaźniki laserowe' ){
					$cat_name = 'elektronika';
					
				}
				elseif( $cat_name === 'wypoczynek' ){
					if( $subcat_name === 'czapki z daszkiem' ){
						$cat_name = 'tekstylia';
						
					}
					elseif( $subcat_name === 'kubki termiczne i termosy' ){
						$cat_name = 'do picia';
						$subcat_name = 'kubki';
						
					}
					elseif( $subcat_name === 'akcesoria do grillowania' ){
						$subcat_name = 'Grill i piknik';
						
					}
					elseif( $subcat_name === 'lornetki i okulary' ){
						if( stripos( (string)$node->baseinfo->name, 'okular' ) !== false ){
							$subcat_name = 'okulary';
						}
						elseif( stripos( (string)$node->baseinfo->name, 'lornet' ) !== false ){
							$subcat_name = 'lornetki';
						}
						else{
							
							$subcat_name = 'inne';
						}
						
					}
					
				}
				elseif( $subcat_name === 'pozostałe power banki' ){
					$subcat_name = 'power banki';
					
				}
				elseif( $cat_name === "dodatki" ){
					if( $subcat_name === 'portfele' ){
						$cat_name = 'Podróż';
						
					}
					elseif( in_array( $subcat_name, array( 'breloki metalowe', 'breloki wielofunkcyjne' ) ) ){
						$cat_name = 'breloki';
						
					}
					elseif( $subcat_name === 'odblaski' ){
						$cat_name = 'odblaski';
						
					}
					elseif( $subcat_name === 'etui' ){
						$cat_name = 'materiały piśmiennicze';
						
					}
					
				}
				elseif( $cat_name === 'elektronika' && in_array( $subcat_name, array( 'zegary biurkowe', 'zegary ścienne', 'zegarki na rękę' ) ) ){
					$cat_name = 'zegary i zegarki';
					
				}
				elseif( $subcat_name === 'kubki i opakowania do kubków' ){
					$cat_name = 'do picia';
					$subcat_name = 'kubki';
					
				}
				elseif( $subcat_name === 'akcesoria kuchenne' ){
					$subcat_name = 'kuchnia';
					
				}
				elseif( $subcat_name === 'akcesoria do wina' ){
					$cat_name = 'vine club';
					$subcat_name = 'akcesoria';
					
				}
				elseif( $cat_name === 'dyski' ){
					$subcat_name = str_replace( "dyski ", "", $subcat_name );
					
				}
				elseif( $cat_name === 'świąteczne' ){
					$subcat_name = '';
					
				}
				elseif( $cat_name === 'mykronoz' ){
					$cat_name = 'smartwatche';
					$subcat_name = 'mykronoz';
					
				}
				elseif( $cat_name === 'mobile' ){
					$cat_name = $this->stdNameCache( 'Akcesoria do telefonów i tabletów' );
					$subcat_name = 'Okulary wirtualnej rzeczywistości';
					
				}
				elseif( $subcat_name === 'zestawy do manicure' ){
					$cat_name = 'uroda';
					$subcat_name = 'Pielęgnacja dłoni';
					
				}
				elseif( $cat_name === 'elektronika' ){
					if( $subcat_name === 'akcesoria komputerowe' ){
						$cat_name = 'Akcesoria komputerowe';
						
						if( stripos( (string)$node->baseinfo->name, 'słuchawk' ) !== false ){
							$subcat_name = 'Słuchawki';
							
						}
						else{
							$subcat_name = 'Pozostałe';
							
						}
						
					}
					
				}
				elseif( $cat_name === 'easy siesta' ){
					
					if( $subcat_name === 'artykuły barmańskie' ){
						$cat_name = 'vine club';
						$subcat_name = 'akcesoria';
						
					}
					elseif( $subcat_name === 'breloki' ){
						$cat_name = 'breloki';
						
						if( stripos( (string)$node->baseinfo->intro, 'drewn' ) !== false ){
							$subcat_name = 'drewniane';
							
						}
						elseif( stripos( (string)$node->baseinfo->intro, 'led' ) !== false ){
							$subcat_name = 'latarki';
							
						}
						elseif( stripos( (string)$node->baseinfo->intro, 'metal' ) !== false ){
							$subcat_name = 'metalowe';
							
						}
						elseif( stripos( (string)$node->baseinfo->intro, 'ładowar' ) !== false ){
							$subcat_name = 'wielofunkcyjne';
							
						}
						elseif( stripos( (string)$node->baseinfo->intro, 'odblask' ) !== false ){
							$cat_name = 'odblaski';
							$subcat_name = 'breloki';
							
						}
						else{
							$subcat_name = 'inne';
							
						}
						
					}
					elseif( $subcat_name === 'czapki i kapelusze' ){
						$cat_name = 'tekstylia';
						
						if( stripos( (string)$node->baseinfo->name, 'kapelusz' ) !== false ){
							$subcat_name =  'kapelusze';
							
						}
						else{
							$subcat_name =  'daszki';
							
						}
						
					}
					elseif( $subcat_name === 'dom' ){
						$subcat_name = 'inne';
						
					}
					elseif( $subcat_name === 'inne' ){
						$cat_name = 'gadżety reklamowe';
						$subcat_name = 'inne';
						
					}
					elseif( $subcat_name === 'materiały piśmiennicze' ){
						$cat_name = 'materiały piśmiennicze';
						
						if( stripos( (string)$node->baseinfo->name, 'długopis' ) !== false ){
							
							if( stripos( (string)$node->baseinfo->name, 'alumin' ) !== false or 
								stripos( (string)$node->baseinfo->intro, 'alumin' ) !== false ){
								$subcat_name = 'długopisy aluminiowe';
								
							}
							elseif( stripos( (string)$node->baseinfo->name, 'metal' ) !== false or 
								stripos( (string)$node->baseinfo->intro, 'metal' ) !== false ){
								$subcat_name = 'długopisy metalowe';
								
							}
							elseif( stripos( (string)$node->baseinfo->name, 'touch' ) !== false or 
								stripos( (string)$node->baseinfo->intro, 'touch' ) !== false ){
								$subcat_name = 'długopisy z touch penem';
								
							}
							elseif( stripos( (string)$node->baseinfo->name, 'ekskluz' ) !== false or 
								stripos( (string)$node->baseinfo->intro, 'ekskluz' ) !== false ){
								$subcat_name = 'długopisy eksluzywne';
								
							}
							else{
								$subcat_name = 'długopisy plastikowe';
								
							}
							
						}
						
					}
					elseif( $subcat_name === 'notesy' ){
						$cat_name = 'biuro';
						$subcat_name = 'notatniki i notesy';
						
					}
					elseif( $subcat_name === 'smycze' ){
						$cat_name = 'podróż';
						$subcat_name = 'smycze';
						
					}
					elseif( $subcat_name === 'torby i plecaki' ){
						$cat_name = 'torby i plecaki';
						
						if( stripos( (string)$node->baseinfo->name, 'plecak' ) !== false ){
							$subcat_name = 'plecaki';
							
						}
						else{
							$subcat_name = 'torby';
							
						}
						
					}
					elseif( $subcat_name === 'odpoczynek' ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'inne';
						
					}
					
				}
				
				/* 
				(string)$node->baseinfo->name
				(string)$node->baseinfo->intro
				 */
				
				/* ================= FILTRY PODKATEGORII =================  */
				if( $cat_name === 'torby i plecaki' ){
					
					if( $subcat_name === 'kosze na zakupy' ){
						$subcat_name = 'na zakupy';
						
					}
					elseif( $subcat_name === 'torby papierowe' ){
						$subcat_name = 'papierowe';
						
					}
					elseif( $subcat_name === 'dom' ){
						$cat_name = 'dom';
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'inne' ){
					
					if( $subcat_name === 'torby i worki sportowe' ){
						$cat_name = 'sport i rekreacja';
						$subcat_name = 'akcesoria sportowe';
						
					}
					
				}
				elseif( $cat_name === 'narzędzia' ){
					
					if( $subcat_name === 'narzędzia wielofunkcyjne' ){
						$subcat_name = 'wielofunkcyjne';
						
					}
					
				}
				elseif( $cat_name === 'materiały piśmiennicze' ){
					
					if( in_array( $subcat_name, array( 'nowości świąteczne 2017', 'materiały piśmiennicze' ) ) ){
						$subcat_name = 'inne';
						
					}
					elseif( $subcat_name === 'zestawy do malowania' ){
						$cat_name = 'rozrywka i szkoła';
						
					}
					
				}
				elseif( $cat_name === 'wypoczynek' ){
					
					if( $subcat_name === 'dom' ){
						$cat_name = 'dom';
						$subcat_name = 'inne';
						
					}
					elseif( $subcat_name === 'Grill i piknik' ){
						
						if( stripos( $item_dscr, 'krzesło' ) !== false or 
							stripos( $item_dscr, 'kemping' ) !== false ){
							$subcat_name = 'piknik';
							
						}
						else{
							$subcat_name = 'grill';
							
						}
						
					}
					elseif( $subcat_name === 'torby termiczne' ){
						$subcat_name = 'Torby termoizolacyjne';
						
					}
					elseif( $subcat_name === 'czapki i kapelusze' ){
						$cat_name = 'tekstylia';
						
						if( stripos( $item_title, 'daszk' ) !== false ){
							$subcat_name = 'Czapki z daszkiem';
							
						}
						elseif( stripos( $item_title, 'czapk' ) !== false ){
							$subcat_name = 'Czapki';
							
						}
						elseif( stripos( $item_title, 'kapelusz' ) !== false ){
							$subcat_name = 'kapelusze';
							
						}
						
					}
					elseif( $subcat_name === 'nowości świąteczne 2017' ){
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'power banki' ){
					
					if( $subcat_name === 'power banki' ){
						$subcat_name = 'pozostałe';
						
					}
					
				}
				elseif( $cat_name === 'akcesoria_do_telefonow_i_tabletow' ){
					
					if( $subcat_name === 'power banki' ){
						$cat_name = 'power banki';
						$subcat_name = 'pozostałe';
						
					}
					elseif( in_array( $subcat_name, array( 'Okulary wirtualnej rzeczywistości', 'różne' ) ) ){
						$cat_name = 'Akcesoria do telefonów i tabletów';
						$subcat_name = 'Akcesoria';
						
					}
					
				}
				elseif( $cat_name === 'Podróż' ){
					
					if( $subcat_name === 'etui' ){
						$cat_name = 'materiały piśmiennicze';
						
					}
					elseif( $subcat_name === 'breloki metalowe' ){
						$cat_name = 'breloki';
						$subcat_name = 'metalowe';
						
					}
					
				}
				elseif( $cat_name === 'breloki' ){
					
					if( $subcat_name === 'breloki wielofunkcyjne' ){
						$subcat_name = 'wielofunkcyjne';
						
					}
					elseif( $subcat_name === 'breloki metalowe' ){
						$subcat_name = 'metalowe';
						
					}
					
				}
				elseif( $cat_name === 'vine club' ){
					
					if( $subcat_name === 'dom' ){
						$cat_name = 'dom';
						$subcat_name = 'kuchnia';
						
					}
					elseif( $subcat_name === 'nowości świąteczne 2017' ){
						$cat_name = 'dom';
						$subcat_name = 'akcesoria';
						
					}
					
					
				}
				elseif( $cat_name === 'odblaski' ){
					
					if( $subcat_name === 'breloki wielofunkcyjne' ){
						$subcat_name = 'breloki';
						
					}
					
					
				}
				elseif( $cat_name === "tekstylia" ){
			
					if( $subcat_name === 'odpoczynek' ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'outdoor';
						
					}
					
				}
				elseif( $cat_name === "gadżety reklamowe" ){
			
					if( $subcat_name === 'nowości świąteczne 2017' ){
						$subcat_name = 'inne';
						
					}
					
				}
				
				
				if( !empty( $subcat_name ) ){
					$cat_name_slug = $this->stdNameCache( $cat_name );
					$subcat_name_slug = $this->stdNameCache( $subcat_name );
					$subcat_name_slug = $cat_name_slug . "-" . $subcat_name_slug;
					$ret[ $cat_name_slug ][ $subcat_name_slug ] = array();
					
				}
				else{
					$cat_name_slug = $this->stdNameCache( $cat_name );
					$ret[ $cat_name_slug ] = array();
					
				}
				
				if( $subcategory->subcategories->count() > 0 ){
					$cat_name_slug = $this->stdNameCache( $cat_name );
					//$ret[ $cat_name ][ $subcat_name ] = $this->genMenuTree( $subcategory );
					$ret[ $cat_name_slug ] = $this->genMenuTree( $subcategory, $node );
					
				}
				
			}
			
		}
		elseif( !empty( $subcat_name ) ){
			$cat_name_slug = $this->stdNameCache( $cat_name );
			$subcat_name_slug = $this->stdNameCache( $subcat_name );
			$subcat_name_slug = $cat_name_slug . "-" . $subcat_name_slug;
			$ret[ $cat_name_slug ][ $subcat_name_slug ] = array();
			
		}
		else{
			$cat_name_slug = $this->stdNameCache( $cat_name );
			$ret[ $cat_name_slug ] = array();
			
		}
		
		$this->debugger( $cat_name, $subcat_name );
		
		/*
		(string)$node->baseinfo->name
		(string)$node->baseinfo->intro
		*/
		
		return $ret;
	}
	
	// funkcja importująca dane o produktach w formie tablicy
	protected function getProducts(){
		//return array();
		$ret = array();
		$file = "offer.xml";		// plik do załadowania
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->product as $item ){
				
				$cat = array();
				if( $item->categories->count() > 0 ) foreach( $item->categories->category as $category ){
					$cat = array_merge_recursive( $cat, $this->genMenuTree( $category, $item ) );
					
				}
				
				/* $mark = array();
				$mark_size = (string)$item->marking_size;
				$mark_types = array();
				if( empty( $mark_size ) ) $mark_size = 'brak rozmiaru';
				foreach( $item->markgroups->markgroup as $group ){
					$mark_type = (string)$group->name;
					if( empty( $mark_type ) ) $mark_type = 'brak znakowania';
					// $mark[ $mark_size ][] = $mark_type;
					// $mark_types[] = $mark_type;
					
					$pattern = "/(\w[\w\s,ł]*\w)/";
					preg_match_all( $pattern, $mark_type, $match );
					switch( count( $match[1] ) ){
						case 1:
							
						case 2:
							$mark_types[] = $match[1][0];
							$mark[ $mark_size ][] = $match[1][0];
							
						break;
						case 3:
							if( $match[1][0] === "ET" ){
								$mark_types[] = $match[1][0] . $match[1][1];
								$mark[ $mark_size ][] = $match[1][0] . $match[1][1];
								
							}
							else{
								$mark_types[] = $match[1][0];
								$mark[ $mark_size ][] = $match[1][0];
								
							}
							
						break;
						case 4:
							if( $match[1][0] === "ET" ){
								$mark_types[] = $match[1][0] . $match[1][1];
								$mark[ $mark_size ][] = $match[1][0] . $match[1][1];
								
							}
							else{
								$mark_types[] = $match[1][0] . $match[1][2];
								$mark[ $mark_size ][] = $match[1][0] . $match[1][2];
								
							}
							
						break;
						
					}
					
					
				} */
				
				$id = (string)$item->baseinfo->code_full;
				if( empty( $id ) ) $id = (string)$item->baseinfo->id;
				
				$short_id = (string)$item->baseinfo->code_short;
				if( empty( $id ) ) $short_id = (string)$item->baseinfo->id;
				
				$name = (string)$item->baseinfo->name;
				if( empty( $name ) ) $name = '- brak danych -';
				
				$materials = array();
				if( count( $item->materials ) > 0 ){
					foreach( $item->materials->material as $matter ){
						$materials[] = (string)$matter->name;
						
					}
					
				}
				
				$price_netto = (float)str_replace( ",", ".", $item->baseinfo->price);
				$price_brutto = $this->price2brutto( $price_netto );
				
				$img = array();
				if( $item->images->count() > 0 ) foreach( $item->images->children() as $image ){
					// $pattern = "~[^/]+$~";
					// preg_match( $pattern, (string)$image, $match );
					// $fname = $match[0];
					/* if( file_exists( __DIR__ . "/../../img/easygifts/{$fname}" ) ){
						// $img[] = "../wp-content/themes/merkuriusz/img/easygifts/{$fname}";
						$img[] = "/wp-content/themes/merkuriusz/img/easygifts/{$fname}";
						
					}
					elseif( file_exists( __DIR__ . "/../../img/easygifts/{$id}.jpg" ) ){
						// $img[] = "../wp-content/themes/merkuriusz/img/easygifts/{$id}.jpg";
						$img[] = "/wp-content/themes/merkuriusz/img/easygifts/{$id}.jpg";
						
					}
					else{
						// $img[] = "../wp-content/themes/merkuriusz/img/noimage.png";
						$img[] = "/wp-content/themes/merkuriusz/img/noimage.png";
						
					} */
					/* if( !file_exists( __DIR__ . "/../../img/easygifts/{$fname}" ) ){
						copy( $image, __DIR__ . "/../../img/easygifts/{$fname}" );
						
					} */
					// $img[] = "/wp-content/themes/merkuriusz/img/easygifts/{$fname}";
					$img[] = (string)$image;
					
				}
				
				$marks_text = array();
				if( count( $item->markgroups->markgroup ) > 0 ) foreach( $item->markgroups->markgroup as $t ){
					$marks_text[] = (string)$t->name;
					
				}
				
				$ret[] = array_merge(
					array(
						'SHOP' => $this->_shop,
						'ID' => 'brak danych',
						'NAME' => 'brak danych',
						'DSCR' => 'brak danych',
						'IMG' => array(),
						'CAT' => array(),
						'DIM' => 'brak danych',
						'MARK' => array(),
						'MARK_TEXT' => '',
						'INSTOCK' => 'brak danych',
						'MATTER' => 'brak danych',
						'COLOR' => 'brak danych',
						'COUNTRY' => 'brak danych',
						'MARKSIZE' => array(),
						'MARKTYPE' => array(),
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
						
					),
					array(
						'ID' => $id,
						'SHORT_ID' => $short_id,
						'NAME' => $name,
						'DSCR' => trim( strip_tags( (string)$item->baseinfo->intro ) ),
						'IMG' => $img,
						'CAT' => $cat,
						'DIM' => (string)$item->attributes->size,
						// 'MARK' => $mark,
						'MARK_TEXT' => implode( "<br>", $marks_text ),
						'INSTOCK' => $this->getStock( (int)$item->baseinfo->id ),
						'MATTER' => !empty( $materials )?( implode( ", ", $materials ) ):( "brak danych" ),
						'COLOR' => (string)$item->color->name,
						'COUNTRY' => (string)$item->origincountry->name,
						'MARKSIZE' => array( $mark_size ),
						'MARKTYPE' => $mark_types,
						'MARKCOLORS' => 1,
						'PRICE' => array(
							'NETTO' => $price_netto,
							'BRUTTO' => $price_brutto,
							'CURRENCY' => 'PLN',
						),
						'WEIGHT' => sprintf( "%.4f kg", (float)str_replace( ",", ".", $item->attributes->weight ) ),
						'BRAND' => (string)$item->brand->name,
						
					)
				);
				
			}
			
			$this->debugger();
			
		}
		
		return $ret;
	}
	
	// funkcja importująca dane o stanie magazynowym
	private function getStock( $id ){
		static $arr = array();
		if( !is_int( $id ) ) return false;
		if( count( $arr ) === 0 ){
			$file = "stocks.xml";		// plik do załadowania
			
			if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
				// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
				
			}
			else{
				// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
				foreach( $this->_XML[ $file ]->product as $product ){
					$arr[ (int)$product->stock->id ] = (int)$product->stock->quantity_24h;
					
				}
				
			}
			
		}
		
		if( array_key_exists( $id, $arr ) ){
			return $arr[ $id ];
			
		}
		else{
			return false;
			
		}
		
	}
	
}
