<?php
class INSPIRION extends XMLAbstract {
	public $_shop = 'INSPIRION';
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
			"http://inspirion.pl/sites/default/files/exports/products.xml"
		);
		
	}
	
	// funkcja importująca dane o budowie menu w formie tablicy
	protected function getMenu(){
		//return array();
		$ret = array();
		$file = "products.xml";		// plik do załadowania
		
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->children() as $item ){
				$cat_name = $this->stdNameCache( (string)$item->catalog );
				if( !empty( $cat_name ) ) $ret[ $cat_name ] = array();
				$cat2_name = $this->stdNameCache( (string)$item->catalog_special );
				if( !empty( $cat2_name ) ) $ret[ $cat2_name ] = array();
				
				
			}
			
		}
		
		//return $ret;
		return array(
			'INSPIRION' => $ret,
			
		);
		/*
		*/
		
	}
	
	// funkcja importująca dane o produktach w formie tablicy
	protected function getProducts(){
		//return array();
		$ret = array();
		$file = "products.xml";		// plik do załadowania
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->node as $item ){
				
				$item_title = (string)$item->product_name;
				$item_dscr = (string)$item->body;
				
				$img = array();
				// $pattern = $pattern = "~http://.+?\.\w{3}~";
				$pattern = $pattern = "~[^/\.]+?\.\w{3}~";
				preg_match_all( $pattern, (string)$item->product_images, $img_found );
				foreach( $img_found[0] as $t ){
					$img[] = "http://inspirion.pl/sites/default/files/imagecache/product/{$t}";
					
				}
				
				$cat = array();
				// $cat_name = $this->stdNameCache( (string)$item->catalog );
				$cat_name = strtolower( (string)$item->catalog );
				$subcat_name = '';
				// $cat2_name = $this->stdNameCache( (string)$item->catalog_special );
				
				if( $cat_name === 'parasole' ){
					$cat_name = 'przeciwdeszczowe';
					$subcat_name = 'parasole';
					
				}
				elseif( $cat_name === 'rekreacja i piknik' ){
					
					
					if( stripos( $item_title, 'plaż' ) !== false  ){
						$cat_name = 'wypoczynek';
						
						if( stripos( $item_title, 'piłka' ) !== false or
						stripos( $item_title, 'parasol' ) !== false or
						stripos( $item_title, 'osłona' ) !== false ){
							$subcat_name = 'akcesoria plażowe';
							
						}
						elseif( stripos( $item_title, 'mata' ) !== false ){
							$subcat_name = 'maty';
							
						}
						elseif( stripos( $item_title, 'zestaw' ) !== false ){
							$subcat_name = 'gry i zabawy';
							
						}
						else{
							$subcat_name = 'inne';
							
						}
						
					}
					elseif( stripos( $item_title, 'dmuch' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'akcesoria plażowe';
						
					}
					elseif( stripos( $item_title, 'rower' ) !== false ){
						
						if( stripos( $item_title, 'lamp' ) !== false or 
							stripos( $item_title, 'bidon' ) !== false or 
							stripos( $item_title, 'dzwonek' ) !== false or 
							stripos( $item_title, 'zapięci' ) !== false ){
							$cat_name = 'wypoczynek';
							$subcat_name = 'akcesoria rowerowe i odblaski';
							
						}
						elseif( stripos( $item_title, 'pompk' ) !== false or
							stripos( $item_title, 'torb' ) !== false ){
							$cat_name = 'sport i rekreacja';
							$subcat_name = 'akcesoria dla rowerzystów';
							
						}
						elseif( stripos( $item_title, 'narzędzi' ) !== false ){
							$cat_name = 'narzędzia';
							$subcat_name = 'rower';
							
						}
						
					}
					elseif( stripos( $item_title, 'bidon' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'termosy i bidony';
						
					}
					elseif( stripos( $item_title, 'okulary' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'okulary przeciwsłoneczne';
						
					}
					elseif( stripos( $item_title, 'butelka' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'materac' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'materace';
						
					}
					elseif( stripos( $item_title, 'piknik' ) !== false or
							stripos( $item_title, 'izolacyjny pojemnik' ) !== false or
							stripos( $item_title, 'izotermiczna' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'piknik';
						
					}
					elseif( stripos( $item_title, 'gril' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'grill';
						
					}
					elseif( stripos( $item_title, 'latark' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'latarki';
						
					}
					elseif( stripos( $item_title, 'piwór' ) !== false or
							stripos( $item_title, 'namiot' ) !== false or
							stripos( $item_title, 'osłona' ) !== false or
							stripos( $item_title, 'wentylator' ) !== false or
							stripos( $item_title, 'solarny' ) !== false or
							stripos( $item_title, 'nordic' ) !== false or
							stripos( $item_title, 'poduszka' ) !== false or
							stripos( $item_title, 'krokomierz' ) !== false or
							stripos( $item_title, 'czołowa' ) !== false or
							stripos( $item_title, 'camping' ) !== false or
							stripos( $item_title, 'podbierak' ) !== false or
							stripos( $item_title, 'ogrzewacz' ) !== false or
							stripos( $item_title, 'hamak' ) !== false or
							stripos( $item_title, 'wiszące' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'piłka' ) !== false or
							stripos( $item_title, 'latawiec' ) !== false or
							stripos( $item_title, 'gry' ) !== false or
							stripos( $item_title, 'gra' ) !== false or
							stripos( $item_title, 'boks' ) !== false or
							stripos( $item_title, 'kaczka' ) !== false or
							stripos( $item_title, 'makaron' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'gry i zabawy';
						
					}
					elseif( stripos( $item_title, 'koc' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'koce';
						
					}
					elseif( stripos( $item_title, 'termos' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'termosy';
						
					}
					elseif( stripos( $item_title, 'gimnastycz' ) !== false or 
							stripos( $item_title, 'ćwiczeń' ) !== false or 
							stripos( $item_title, 'skakanka' ) !== false or 
							stripos( $item_title, 'hantla' ) !== false ){
						$cat_name = 'sport i rekreacja';
						$subcat_name = 'przyrządy do ćwiczeń';
						
					}
					elseif( stripos( $item_title, 'waga' ) !== false or
							stripos( $item_title, 'nierdzewnej' ) !== false or
							stripos( $item_title, 'mikrofibry' ) !== false or
							stripos( $item_title, 'szczypce' ) !== false or
							stripos( $item_title, 'fartuch' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'kuchnia';
						
					}
					elseif( stripos( $item_title, 'ładowark' ) !== false && stripos( 'samochod' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'ładowarki samochodowe';
						
					}
					elseif( stripos( $item_title, 'długopis' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'długopisy plastikowe';
						
					}
					elseif( stripos( $item_title, 'słuchawki' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'słuchawki';
						
					}
					elseif( stripos( $item_title, 'usb' ) !== false ){
						$cat_name = 'akcesoria komputerowe';
						$subcat_name = 'akcesoria usb';
						
					}
					
				}
				elseif( $cat_name === 'galanteria podróżna' ){
					
					if( stripos( $item_title, 'waliz' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'walizki';
						
					}
					elseif( stripos( $item_title, 'portfel' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'portfele';
						
					}
					elseif( stripos( $item_title, 'saszetk' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'saszetki';
						
					}
					elseif( stripos( $item_title, 'torb' ) !== false or 
							stripos( $item_title, 'dyplomatka' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'torby podróżne i sportowe';
						
					}
					elseif( stripos( $item_title, 'etui' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'etui';
						
					}
					elseif( stripos( $item_title, 'portmonetk' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'portmonetki';
						
					}
					elseif( stripos( $item_title, 'sakiewka' ) !== false or 
							stripos( $item_title, 'zagłówek' ) !== false or 
							stripos( $item_title, 'poduszka' ) !== false or 
							stripos( $item_title, 'lusterko' ) !== false or 
							stripos( $item_title, 'zawieszka' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					elseif( stripos( $item_title, 'zestaw' ) !== false or 
							stripos( $item_title, 'przybornik' ) !== false or 
							stripos( $item_title, 'kosmetyczka' ) !== false or 
							stripos( $item_title, 'teczka' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'zestawy podróżne';
						
					}
					elseif( stripos( $item_title, 'plecak' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'plecaki na kółkach';
						
					}
					else{
						$cat_name = 'podróż';
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'torby i plecaki' ){
					
					$cat_name = 'torby i plecaki';
					
					if( stripos( $item_title, 'torba podróżna' ) !== false or 
							stripos( $item_title, 'torba sportowa' ) !== false or 
							stripos( $item_title, 'torba na kółkach' ) !== false ){
						$subcat_name = 'torby podróżne i sportowe';
						
					}
					elseif( stripos( $item_title, 'walizka' ) !== false or  
							stripos( $item_title, 'plecak na kółkach' ) !== false  ){
						$subcat_name = 'walizki';
						
					}
					elseif( stripos( $item_title, 'plecak' ) !== false ){
						$subcat_name = 'plecaki';
						
					}
					elseif( stripos( $item_title, 'na ramię' ) !== false ){
						$subcat_name = 'torby na ramię';
						
					}
					elseif( stripos( $item_title, 'izotermiczn' ) !== false or 
							stripos( $item_title, 'termiczn' ) !== false ){
						$subcat_name = 'termoizolacyjne';
						
					}
					elseif( stripos( $item_title, 'na dokumenty' ) !== false ){
						$subcat_name = 'na dokumenty';
						
					}
					elseif( stripos( $item_title, 'zakupy' ) !== false ){
						$subcat_name = 'na zakupy';
						
					}
					elseif( stripos( $item_title, 'torba bawełniana' ) !== false ){
						$subcat_name = 'torby bawełniane';
						
					}
					elseif( stripos( $item_title, 'sportow' ) !== false ){
						$subcat_name = 'sportowe';
						
					}
					elseif( stripos( $item_title, 'laptop' ) !== false ){
						$subcat_name = 'na laptopa';
						
					}
					elseif( stripos( $item_title, 'kosmetyczk' ) !== false or 
							stripos( $item_title, 'kosmeczka' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'kosmetyczki';
						
					}
					elseif( stripos( $item_title, 'torba plażowa' ) !== false ){
						$subcat_name = 'torby plażowe';
						
					}
					elseif( stripos( $item_title, 'marynar' ) !== false ){
						$subcat_name = 'worki ze sznurkiem';
						
					}
					elseif( stripos( $item_title, 'saszetka' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'saszetki';
						
					}
					else{
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'narzędzia i hobby' ){
					
					if( stripos( $item_title, 'scyzoryk' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'scyzoryki';
						
					}
					elseif( stripos( $item_title, 'wielofunkcyjn' ) !== false or 
							stripos( $item_dscr, 'wielofunkcyjn' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'wielofunkcyjne';
						
					}
					elseif( stripos( $item_title, 'zestaw' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'zestawy';
						
					}
					elseif( stripos( $item_title, 'nóż' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'noże i nożyki';
						
					}
					elseif( stripos( $item_title, 'miark' ) !== false or 
							stripos( $item_title, 'taśm' ) !== false or 
							stripos( $item_title, 'tasma' ) !== false or 
							stripos( $item_title, 'miara' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'miary i miarki';
						
					}
					elseif( stripos( $item_title, 'latarka' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'latarki';
						
					}
					elseif( stripos( $item_title, 'lamp' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'lampki';
						
					}
					elseif( stripos( $item_title, 'apteczk' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'apteczki';
						
					}
					elseif( stripos( $item_title, 'skrob' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'skrobaczki';
						
					}
					elseif( stripos( $item_title, 'długopis' ) !== false ){
						
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'długopisy plastikowe';
						
					}
					elseif( stripos( $item_title, 'odblask' ) !== false or 
							stripos( $item_dscr, 'odblask' ) !== false ){
						$cat_name = 'odblask';
						
						if( stripos( $item_title, 'opaska' ) !== false or 
								stripos( $item_title, 'pasek' ) !== false ){
							$subcat_name = 'opaski';
							
						}
						
					}
					elseif( stripos( $item_title, 'poziomica' ) !== false or 
							stripos( $item_title, 'młotek' ) !== false or 
							stripos( $item_title, 'bity' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'warsztat';
						
					}
					elseif( stripos( $item_title, 'torba z narzędziami' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'zestawy';
						
					}
					elseif( stripos( $item_title, 'samochod' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'samochód';
						
					}
					elseif( stripos( $item_title, 'piknik' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'piknik';
						
					}
					elseif( stripos( $item_title, 'termos' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'termosy';
						
					}
					elseif( stripos( $item_title, 'kubek' ) !== false && 
							stripos( $item_title, 'termiczn' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'kubk';
						
					}
					elseif( stripos( $item_title, 'opaska' ) !== false && 
							stripos( $item_title, 'ucisk' ) !== false ){
						$cat_name = 'medyczne';
						$subcat_name = '';
						
					}
					elseif( stripos( $item_title, 'bateria' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'stojak na wino' ) !== false ){
						$cat_name = 'vine club';
						$subcat_name = 'akcesoria';
						
					}
					elseif( stripos( $item_title, 'yo-yo' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'gry i zabawy';
						
					}
					elseif( stripos( $item_title, 'opaska' ) !== false && 
							stripos( $item_title, 'runner' ) !== false ){
						$cat_name = 'sport i rekreacja';
						$subcat_name = 'akcesoria sportowe';
						
					}
					elseif( stripos( $item_title, 'wieszak' ) !== false or 
							stripos( $item_title, 'podgrzewacz' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					
				}
				elseif( $cat_name === 'do domu i kuchni' ){
					
					if( stripos( $item_title, ' win' ) !== false ){
						$cat_name = 'vine club';
						
						if( stripos( $item_title, 'zestaw' ) !== false ){
							$subcat_name = 'zestawy';
							
						}
						else{
							$subcat_name = 'akcesoria';
							
						}
						
					}
					elseif( stripos( $item_title, 'dziadek' ) !== false or 
							stripos( $item_title, 'nutcracker' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'dziadki do orzechów';
						
					}
					elseif( stripos( $item_title, 'kubek' ) !== false or 
							stripos( $item_title, 'kubk' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'kubki';
						
					}
					elseif( stripos( $item_title, 'termos' ) !== false or 
							stripos( $item_title, 'bidon' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'termosy i bidony';
						
					}
					elseif( stripos( $item_title, 'deska' ) !== false or 
							stripos( $item_title, 'desek' ) !== false or 
							stripos( $item_title, 'ser' ) !== false or 
							stripos( $item_title, 'kuchenn' ) !== false or 
							stripos( $item_title, 'spaghetti' ) !== false or 
							stripos( $item_title, 'sztućc' ) !== false or 
							stripos( $item_title, 'ubijaczk' ) !== false or 
							stripos( $item_title, 'zaparzarka' ) !== false or 
							stripos( $item_title, 'przyprawy' ) !== false or 
							stripos( $item_title, 'patelnia' ) !== false or 
							stripos( $item_title, 'nóż' ) !== false or 
							stripos( $item_dscr, 'nóż' ) !== false or 
							stripos( $item_dscr, 'noż' ) !== false or 
							stripos( $item_title, 'obieracz' ) !== false or 
							stripos( $item_title, 'soli' ) !== false or 
							stripos( $item_title, 'sól' ) !== false or 
							stripos( $item_title, 'pieprz' ) !== false or 
							stripos( $item_title, ' dip' ) !== false or 
							stripos( $item_title, ' fondue' ) !== false or 
							stripos( $item_title, 'zapal' ) !== false or 
							stripos( $item_title, ' gotow' ) !== false or 
							stripos( $item_title, 'podkładk' ) !== false or 
							stripos( $item_title, 'fartuch' ) !== false or 
							stripos( $item_title, 'fartusz' ) !== false or 
							stripos( $item_title, 'minutnik' ) !== false or 
							stripos( $item_title, 'shaker' ) !== false or 
							stripos( $item_title, 'rękawiczki' ) !== false or 
							stripos( $item_title, 'korkociąg' ) !== false or 
							stripos( $item_title, 'herbat' ) !== false or
							stripos( $item_title, 'kawy' ) !== false or 
							stripos( $item_title, 'stojak' ) !== false or 
							stripos( $item_title, 'espresso' ) !== false or 
							stripos( $item_title, 'zapaska' ) !== false or 
							stripos( $item_title, 'mop' ) !== false or 
							stripos( $item_title, 'zestaw stołowy' ) !== false or 
							stripos( $item_title, 'schładzacz' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'akcesoria kuchenne';
						
					}
					elseif( stripos( $item_title, 'butelka' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'butelki';
						
					}
					elseif( stripos( $item_title, 'piersiówk' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'piersiówki';
						
					}
					elseif( stripos( $item_title, 'forem' ) !== false or 
							stripos( $item_title, 'trzepa' ) !== false or 
							stripos( $item_title, 'ciast' ) !== false or 
							stripos( $item_title, ' piecz' ) !== false or 
							stripos( $item_title, ' piekar' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'akcesoria do pieczenia';
						
					}
					elseif( stripos( $item_title, 'świec' ) !== false or 
							stripos( $item_title, 'Świec' ) !== false or 
							stripos( $item_title, 'zapach' ) !== false or 
							stripos( $item_dscr, 'zapach' ) !== false or 
							stripos( $item_title, 'aroma' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'świece i aromaterapia';
						
					}
					elseif( stripos( $item_title, 'młynek' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'pojemniki i młynki do przypraw';
						
					}
					elseif( stripos( $item_title, 'pojemnik' ) !== false or 
							stripos( $item_title, 'na oliwki' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'pojemniki na żywność';
						
					}
					elseif( stripos( $item_title, 'brelok' ) !== false ){
						$cat_name = 'breloki';
						$subcat_name = 'breloki';
						
					}
					elseif( stripos( $item_title, 'lamp' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'lampki';
						
					}
					elseif( stripos( $item_title, 'grill' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'grill';
						
					}
					elseif( stripos( $item_title, 'otwieracz' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'otwieracze do butelek';
						
					}
					elseif( stripos( $item_title, 'lodówka' ) !== false or 
							stripos( $item_title, 'chłodziark' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					elseif( stripos( $item_title, 'korek' ) !== false or 
							stripos( $item_title, 'zamknięcie' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'wellness' ) !== false or 
							stripos( $item_title, 'pedicure' ) !== false or 
							stripos( $item_title, 'paznokci' ) !== false or 
							stripos( $item_title, 'masaż' ) !== false ){
						$cat_name = 'uroda';
						$subcat_name = 'uroda i pielęgnacja';
						
					}
					elseif( stripos( $item_title, 'wiąteczn' ) !== false or 
							stripos( $item_dscr, 'wiąteczn' ) !== false or 
							stripos( $item_title, 'choink' ) !== false or 
							stripos( $item_title, 'ginger man' ) !== false or 
							stripos( $item_title, 'white shine' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'świąteczne';
						
					}
					elseif( stripos( $item_title, 'usb' ) !== false or
							stripos( $item_title, 'pc' ) !== false or
							stripos( $item_title, 'adapter' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'oponach' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'samochód';
						
					}
					elseif( stripos( $item_title, 'dryfeet' ) !== false or 
							stripos( $item_title, 'podgrzewacz' ) !== false or 
							stripos( $item_title, 'czyszczenia' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					elseif( stripos( $item_title, 'kieliszki' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'kieliszki';
						
					}
					else{
						$cat_name = 'dom';
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'kosmetyka' ){
					
					if( stripos( $item_title, 'manicure' ) !== false or 
							stripos( $item_title, 'pilniczek' ) !== false ){
						$cat_name = 'uroda';
						$subcat_name = 'pielęgnacja dłoni';
						
					}
					elseif( stripos( $item_title, 'maska' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					elseif( stripos( $item_title, 'lusterk' ) !== false ){
						$cat_name = 'uroda';
						$subcat_name = 'lusterka';
						
					}
					elseif( stripos( $item_title, 'chłodząc' ) !== false or 
							stripos( $item_title, 'izoterm' ) !== false or 
							stripos( $item_title, 'krokomierz' ) !== false ){
						$cat_name = 'sport i rekreacja';
						$subcat_name = 'akcesoria sportowe';
						
					}
					elseif( stripos( $item_title, 'podgrzewacz' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					elseif( stripos( $item_title, 'masaż' ) !== false or 
							stripos( $item_title, 'pielęgnac' ) !== false ){
						$cat_name = 'uroda';
						$subcat_name = 'inne';
						
					}
					else{
						$cat_name = 'dom';
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'dla dzieci' ){
					
					if( stripos( $item_title, 'antystres' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'antystresy';
						
					}
					elseif( stripos( $item_title, 'pluszow' ) !== false or 
							stripos( $item_dscr, 'pluszow' ) !== false or 
							stripos( $item_title, 'przytul' ) !== false or 
							stripos( $item_dscr, 'przytul' ) !== false ){
						
						$cat_name = 'pluszaki i maskotki';
						if( stripos( $item_title, 'miś' ) !== false ){
							$subcat_name = 'misie';
							
						}
						elseif( stripos( $item_title, 'osioł' ) !== false or 
								stripos( $item_title, 'konik' ) !== false or 
								stripos( $item_title, 'krówka' ) !== false or 
								stripos( $item_title, 'pies' ) !== false or 
								stripos( $item_title, 'mysz' ) !== false ){
							$subcat_name = 'domowe';
							
						}
						else{
							$subcat_name = 'dzikie';
							
						}
						
					}
					elseif( stripos( $item_title, 'gra' ) !== false or 
							stripos( $item_title, 'jojo' ) !== false or 
							stripos( $item_title, 'układanka' ) !== false or 
							stripos( $item_title, 'samolot' ) !== false or 
							stripos( $item_title, 'koszykówka' ) !== false or 
							stripos( $item_title, 'zabawk' ) !== false or 
							stripos( $item_title, 'helikopter' ) !== false or 
							stripos( $item_title, 'kaczuszka' ) !== false ){
						$cat_name = 'rozrywka i szkoła';
						$subcat_name = 'gry';
						
					}
					elseif( stripos( $item_title, 'kred' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'kredki';
						
					}
					elseif( stripos( $item_title, 'piórnik' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'piórniki';
						
					}
					elseif( stripos( $item_title, 'szkolny' ) !== false or 
							stripos( $item_title, 'zakładki' ) !== false or 
							stripos( $item_title, 'klips' ) !== false or 
							stripos( $item_title, 'ołów' ) !== false or 
							stripos( $item_title, 'piśmienny' ) !== false ){
						$cat_name = 'rozrywka i szkoła';
						$subcat_name = 'szkoła';
						
					}
					elseif( stripos( $item_title, 'malowania' ) !== false or 
							stripos( $item_title, 'malarsk' ) !== false or 
							stripos( $item_title, 'kolorowania' ) !== false or 
							stripos( $item_dscr, 'kolorowania' ) !== false or 
							stripos( $item_title, 'stempl' ) !== false ){
						$cat_name = 'rozrywka i szkoła';
						$subcat_name = 'zestawy do malowania';
						
					}
					elseif( stripos( $item_title, 'plastelin' ) !== false or 
							stripos( $item_title, 'skakanka' ) !== false or 
							stripos( $item_title, 'pajacyk' ) !== false or 
							stripos( $item_title, 'kwiatek' ) !== false or 
							stripos( $item_title, 'mydlane' ) !== false or 
							stripos( $item_title, 'puzzle' ) !== false or 
							stripos( $item_title, 'dziurkacz' ) !== false or 
							stripos( $item_title, 'zabaw' ) !== false or 
							stripos( $item_title, 'termometr' ) !== false or 
							stripos( $item_title, 'kaczka' ) !== false or 
							stripos( $item_title, 'kocyk' ) !== false ){
						$cat_name = 'rozrywka i szkoła';
						$subcat_name = 'zabawa';
						
					}
					elseif( stripos( $item_title, 'skarbonk' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'skarbonki';
						
					}
					elseif( stripos( $item_title, 'termofor' ) !== false ){
						$cat_name = 'uroda';
						$subcat_name = 'termofory';
						
					}
					elseif( stripos( $item_title, 'plaż' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'akcesoria plażowe';
						
					}
					elseif( stripos( $item_title, 'kamizelka odblask' ) !== false ){
						$cat_name = 'odblaski';
						$subcat_name = 'kamizelki';
						
					}
					else{
						$cat_name = 'dodatki';
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'ubrania' ){
					
					$cat_name = 'tekstylia';
					
					if( stripos( $item_title, 'poncho' ) !== false ){
						$subcat_name = 'ponczo';
						
					}
					elseif( stripos( $item_title, 'kapelusz' ) !== false ){
						$subcat_name = 'kapelusze';
						
					}
					elseif( stripos( $item_title, 'szalik' ) !== false ){
						$subcat_name = 'szaliki';
						
					}
					elseif( stripos( $item_title, 'trendy' ) !== false or 
							stripos( $item_title, 'opaska' ) !== false ){
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'rękawiczki' ) !== false ){
						$subcat_name = 'rękawiczki';
						
					}
					elseif( stripos( $item_title, 'czapka' ) !== false ){
						$subcat_name = 'czapki zimowe';
						
					}
					elseif( stripos( $item_title, 'zestaw' ) !== false ){
						$subcat_name = 'zestawy zimowe';
						
					}
					elseif( stripos( $item_title, 'hero' ) !== false ){
						$cat_name = 'odblaski';
						$subcat_name = 'kamizelki';
						
					}
					else{
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'czas i pogoda' ){
					
					$cat_name = 'elektronika';
					if( stripos( $item_title, 'pogod' ) !== false or 
							stripos( $item_title, 'meteo' ) !== false or 
							stripos( $item_dscr, 'termometr' ) !== false ){
						$subcat_name = 'stacje pogodowe';
						
					}
					elseif( stripos( $item_title, 'zegar' ) !== false or 
							stripos( $item_title, 'budzik' ) !== false ){
						$cat_name = 'zegary i zegarki';
						
						if( stripos( $item_title, 'biur' ) !== false ){
							$subcat_name = 'zegary biurkowe';
							
						}
						elseif( stripos( $item_title, 'cienn' ) !== false or
								stripos( $item_dscr, 'cienn' ) !== false ){
							$subcat_name = 'zegary ścienne';
							
						}
						elseif( stripos( $item_title, 'rękę' ) !== false ){
							$subcat_name = 'zegarki na rękę';
							
						}
						else{
							// $cat_name = 'xxx';
							$subcat_name = 'pozostałe';
							
						}
						
					}
					elseif( stripos( $item_title, 'brelok' ) !== false or 
							stripos( $item_title, 'zawieszka' ) !== false ){
						$cat_name = 'breloki';
						$subcat_name = 'breloki';
						
					}
					elseif( stripos( $item_title, 'wizytow' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'wizytowniki';
						
					}
					elseif( stripos( $item_title, 'ramka' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'ramki na zdjęcia';
						
					}
					elseif( stripos( $item_title, 'palnik' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'elektronika' ){
					
					$cat_name = 'elektronika';
					
					if( stripos( $item_title, 'słuchawk' ) !== false ){
						
						if( stripos( $item_title, 'douszne' ) !== false ){
							$cat_name = 'Akcesoria do telefonów i tabletów';
							$subcat_name = 'słuchawki';
							
						}
						else{
							$subcat_name = 'słuchawki';
							
						}
						
					}
					elseif( stripos( $item_title, 'głośnik' ) !== false ){
						$subcat_name = 'głośniki';
						
					}
					elseif( stripos( $item_title, 'monopod' ) !== false or 
							stripos( $item_title, 'HANGING TOUGH' ) !== false or 
							stripos( $item_title, 'teleskopowy' ) !== false or 
							stripos( $item_title, 'obiektywy' ) !== false or 
							stripos( $item_title, 'uchwyt' ) !== false or 
							stripos( $item_dscr, 'selfie' ) !== false ){
						$cat_name = 'Akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria do telefonów';
						
					}
					elseif( stripos( $item_title, 'adowarka' ) !== false ){
						
						if( stripos( $item_title, 'samochod' ) !== false ){
							$subcat_name = 'ładowarki samochodowe';
							
						}
						else{
							$cat_name = 'Akcesoria do telefonów i tabletów';
							$subcat_name = 'ładowark i kable';
							
						}
						
					}
					elseif( stripos( $item_title, 'rozdzielacz' ) !== false or 
							stripos( $item_title, 'zapalniczka' ) !== false ){
						$subcat_name = 'akcesoria';
						
					}
					elseif( stripos( $item_title, 'okulary' ) !== false ){
						$cat_name = 'Akcesoria do telefonów i tabletów';
						$subcat_name = 'Okulary wirtualnej rzeczywistości';
						
					}
					elseif( stripos( $item_title, 'lampka' ) !== false ){
						$cat_name = 'akcesoria komputerowe';
						$subcat_name = 'akcesoria usb';
						
					}
					elseif( stripos( $item_title, 'powerbank' ) !== false ){
						$cat_name = 'power banki';
						
						$val = null;
						
						preg_match( "~(\d*\.?\d+)\s*mAh~i", $item_dscr, $match );
						// $val = (int)$match[1];
						$val = (int)str_replace( ".", "", $match[1] );
						
						if( is_int( $val ) ){
							$cap = array( 0, 500, 1000, 2000, 4000, 6000, 8000, 10000 );
							reset( $cap );
							
							$f = current( $cap );
							foreach( $cap as $t ){
								if( $val >= $t ) $f = $t;
								
							}
							if( $f > 0 ){
								$subcat_name = "Pojemność od " . $f . " mAh";
								
							}
							else{
								$subcat_name = 'Pozostałe';
								
							}
							
						}
						else{
							$subcat_name = 'Pozostałe';
							
						}
						
						
					}
					elseif( stripos( $item_title, 'podstawka' ) !== false ){
						$cat_name = 'Akcesoria do telefonów i tabletów';
						$subcat_name = 'etui i podstawki';
						
					}
					elseif( stripos( $item_title, 'mysz' ) !== false ){
						$cat_name = 'akcesoria komputerowe';
						$subcat_name = 'mysz';
						
					}
					elseif( stripos( $item_title, 'stojak' ) !== false && 
							stripos( $item_title, 'tablet' ) !== false ){
						$cat_name = 'Akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria do tabletów';
						
					}
					elseif( stripos( $item_title, 'adapter' ) !== false ){
						$subcat_name = 'adaptery';
						
					}
					elseif( stripos( $item_title, 'wieszak' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					elseif( stripos( $item_title, 'długopis' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'długopisy plastikowe';
						
					}
					else{
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'drobiazgi' ){
					
					if( stripos( $item_title, 'bransolet' ) !== false or 
							stripos( $item_title, 'yżka do butów' ) !== false or 
							stripos( $item_title, 'na tabletki' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'pokrowiec na siodełko' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'Akcesoria rowerowe i odblaski';
						
					}
					elseif( stripos( $item_title, 'wykałaczk' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'kuchnia';
						
					}
					elseif( stripos( $item_title, 'opask' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'opaski';
						
					}
					elseif( stripos( $item_title, 'latark' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'latarki';
						
					}
					elseif( stripos( $item_title, 'miernicz' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'miary i miarki';
						
					}
					elseif( stripos( $item_title, 'biegowy' ) !== false ){
						$cat_name = 'sport i rekreacja';
						$subcat_name = 'akcesoria sportowe';
						
					}
					elseif( stripos( $item_title, 'portfel' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'portfele';
						
					}
					elseif( stripos( $item_title, 'telefon' ) !== false ){
						$cat_name = 'Akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria do telefonów';
						
					}
					elseif( stripos( $item_title, 'lamp' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'lampki';
						
					}
					elseif( stripos( $item_title, 'cool hiking' ) !== false or 
							stripos( $item_title, 'stay chilled' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'brelok' ) !== false ){
						$cat_name = 'breloki';
						
						if( stripos( $item_title, 'led' ) !== false ){
							$subcat_name = 'latarki';
							
						}
						else{
							$subcat_name = 'breloki';
							
						}
						
					}
					elseif( stripos( $item_title, 'solar' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'wiatło' ) !== false or 
							stripos( $item_title, 'radio' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'skrobaczka' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'skrobaczki';
						
					}
					elseif( stripos( $item_title, 'nóż' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'noże i nożyki';
						
					}
					elseif( stripos( $item_title, 'stacja' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'stacje pogodowe';
						
					}
					elseif( stripos( $item_title, 'wieszak' ) !== false or 
							stripos( $item_title, 'zawieszka' ) !== false or 
							stripos( $item_title, 'klip' ) !== false or 
							stripos( $item_title, 'stojak' ) !== false or 
							stripos( $item_title, 'bagaż' ) !== false or 
							stripos( $item_title, 'okular' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					elseif( stripos( $item_title, 'zegar ścienny' ) !== false ){
						$cat_name = 'zegary i zegarki';
						$subcat_name = 'zegary ścienne';
						
					}
					elseif( stripos( $item_title, 'lupa' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'lupy';
						
					}
					else{
						$cat_name = 'dodatki';
						$subcat_name = 'dodatki';
						
					}
					
				}
				elseif( $cat_name === 'wszystko do biura' ){
					
					if( stripos( $item_title, 'etui' ) !== false &&
							stripos( $item_title, 'kart' ) !== false ){
						$cat_name = 'podróz';
						$subcat_name = 'etui na karty';
						
					}
					elseif( stripos( $item_title, 'klip' ) !== false or 
							stripos( $item_title, 'memo' ) !== false or 
							stripos( $item_dscr, 'memo' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'klipy do notatek';
						
					}
					elseif( stripos( $item_title, 'lupa' ) !== false or 
							stripos( $item_title, 'powiększające' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'lupa';
						
					}
					elseif( stripos( $item_title, 'adapter' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'adaptery';
						
					}
					elseif( stripos( $item_title, 'teczka' ) !== false or 
							stripos( $item_dscr, 'teczka' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'teczki';
						
					}
					elseif( stripos( $item_title, 'torba na dokumenty' ) !== false ){
						$cat_name = 'torby i plecaki';
						$subcat_name = 'na dokumenty';
						
					}
					elseif( stripos( $item_title, 'długopis' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						
						if( stripos( $item_dscr, 'ekolog' ) !== false ){
							$cat_name = 'gadżety reklamowe';
							$subcat_name = 'eco gadżet';
							
						}
						elseif( stripos( $item_dscr, 'metal' ) !== false ){
							$subcat_name = 'długopisy metalowe';
							
						}
						elseif( stripos( $item_dscr, 'alumin' ) !== false ){
							$subcat_name = 'długopisy aluminiowe';
							
						}
						elseif( stripos( $item_dscr, 'dotyk' ) !== false or 
								stripos( $item_dscr, 'touch' ) !== false ){
							$subcat_name = 'długopisy z touch penem';
							
						}
						else{
							$subcat_name = 'długopisy plastikowe';
							
						}
						
					}
					elseif( stripos( $item_title, 'ołów' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'ołówki';
						
					}
					elseif( stripos( $item_title, 'zestaw' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'zestawy piśmienne';
						
					}
					elseif( stripos( $item_title, 'wentylator' ) !== false or 
							stripos( $item_title, 'czytnik' ) !== false ){
						$cat_name = 'akcesoria komputerowe';
						$subcat_name = 'akcesoria usb';
						
					}
					elseif( stripos( $item_title, 'piórnik' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'piórniki';
						
					}
					elseif( stripos( $item_title, 'wizytownik' ) !== false or 
							stripos( $item_title, 'wizytwonik' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'wizytowniki';
						
					}
					elseif( stripos( $item_title, 'desk talent' ) !== false or 
							stripos( $item_title, 'dziurkacz' ) !== false or 
							stripos( $item_title, 'dusty' ) !== false or 
							stripos( $item_title, 'zakładk' ) !== false or 
							stripos( $item_title, 'speech bubble' ) !== false or 
							stripos( $item_title, 'nóż do kopert' ) !== false or 
							stripos( $item_title, 'otwieracz do listów' ) !== false or 
							stripos( $item_title, 'pudełko na notatki' ) !== false or 
							stripos( $item_title, 'organiz' ) !== false or 
							stripos( $item_title, 'przycisk' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'akcesoria biurowe';
						
					}
					elseif( stripos( $item_title, 'laserowy' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'wskaźniki laserowe';
						
					}
					elseif( stripos( $item_title, 'zakreśl' ) !== false ){
						$cat_name = 'materiał piśmienne';
						$subcat_name = 'zakreślacze';
						
					}
					elseif( stripos( $item_title, 'notatnik' ) !== false or 
							stripos( $item_title, 'notes' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'notatniki i notesy';
						
					}
					elseif( stripos( $item_title, 'portfolio' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'powerbank' ) !== false ){
						$cat_name = 'power banki';
						
						$val = null;
						
						preg_match( "~(?:pojemność[,\s]*)?(\d*[ ,]?\d+)\s*mAh~i", $item_dscr, $match );
						// $val = (int)$match[1];
						$val = (int)preg_replace( "~[ ,\.]+~", "", $match[1] );
						
						if( is_int( $val ) ){
							$cap = array( 0, 500, 1000, 2000, 4000, 6000, 8000, 10000 );
							reset( $cap );
							
							$f = current( $cap );
							foreach( $cap as $t ){
								if( $val >= $t ) $f = $t;
								
							}
							if( $f > 0 ){
								$subcat_name = "Pojemność od " . $f . " mAh";
								
							}
							else{
								$subcat_name = 'Pozostałe';
								
							}
							
						}
						else{
							$subcat_name = 'Pozostałe';
							
						}
						
						
					}
					elseif( stripos( $item_title, 'kalkulator' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'kalkulatory';
						
					}
					elseif( stripos( $item_title, 'podkład' ) !== false && 
							stripos( $item_title, 'mysz' ) !== false ){
						$cat_name = 'akcesoria komputerowe';
						$subcat_name = 'mysz';
						
					}
					elseif( stripos( $item_title, 'hub' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'huby usb';
						
					}
					elseif( stripos( $item_title, 'usb' ) !== false or 
							stripos( $item_title, 'podgrzewacz' ) !== false ){
						$cat_name = 'akcesoria komputerowe';
						$subcat_name = 'akcesoria usb';
						
					}
					elseif( stripos( $item_title, 'pióro' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'pióra wieczne i kulkowe';
						
					}
					elseif( ( stripos( $item_title, 'stojak' ) !== false && 
							stripos( $item_title, 'telefon' ) !== false ) or 
							stripos( $item_title, 'słuchawk' ) !== false ){
						$cat_name = 'Akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria do telefonów';
						
					}
					elseif( ( stripos( $item_title, 'plecak' ) !== false or 
								stripos( $item_title, 'torba' ) !== false ) && 
							stripos( $item_dscr, 'laptop' ) !== false ){
						$cat_name = 'torby i plecaki';
						$subcat_name = 'na laptopa';
						
					}
					elseif( stripos( $item_title, 'głośnik' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'głośniki';
						
					}
					elseif( stripos( $item_title, 'etui' ) !== false && 
							stripos( $item_title, 'paszport' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'etui';
						
					}
					elseif( stripos( $item_title, 'roll up' ) !== false ){
						$cat_name = 'rozrywka i szkoła';
						$subcat_name = 'zabawa';
						
					}
					elseif( stripos( $item_title, 'linijka' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'linijki';
						
					}
					elseif( stripos( $item_title, 'bagaż' ) !== false or 
							stripos( $item_title, 'czytania' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					else{
						$cat_name = 'dodatki';
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'breloki' ){
					
					if( stripos( $item_title, 'brelok' ) !== false or 
							stripos( $item_title, 'zawieszk' ) !== false or 
							stripos( $item_dscr, 'zawieszk' ) !== false ){
						
						if( stripos( $item_title, 'odblask' ) !== false or 
								stripos( $item_dscr, 'odblask' ) !== false ){
							$cat_name = 'odblaski';
							$subcat_name = 'breloki';
							
						}
						elseif( stripos( $item_title, 'metal' ) !== false or 
								stripos( $item_dscr, 'metal' ) !== false ){
							$cat_name = 'breloki';
							$subcat_name = 'metalowe';
							
						}
						elseif( stripos( $item_title, 'akryl' ) !== false or 
								stripos( $item_dscr, 'aktyl' ) !== false ){
							$cat_name = 'breloki';
							$subcat_name = 'akrylowe';
							
						}
						elseif( stripos( $item_title, 'alumin' ) !== false or 
								stripos( $item_dscr, 'alumin' ) !== false ){
							$cat_name = 'breloki';
							$subcat_name = 'aluminiowe';
							
						}
						elseif( stripos( $item_title, 'plastik' ) !== false or 
								stripos( $item_dscr, 'plastik' ) !== false ){
							$cat_name = 'breloki';
							$subcat_name = 'plastikowe';
							
						}
						elseif( stripos( $item_title, 'drewn' ) !== false or 
								stripos( $item_dscr, 'drewn' ) !== false ){
							$cat_name = 'breloki';
							$subcat_name = 'drewniane';
							
						}
						elseif( stripos( $item_title, 'stres' ) !== false or 
								stripos( $item_dscr, 'stres' ) !== false ){
							$cat_name = 'breloki';
							$subcat_name = 'antystresy';
							
						}
						elseif( stripos( $item_title, 'latark' ) !== false or 
								stripos( $item_dscr, 'latark' ) !== false or 
								stripos( $item_title, 'led' ) !== false or 
								stripos( $item_dscr, 'led' ) !== false ){
							$cat_name = 'breloki';
							$subcat_name = 'latarki';
							
						}
						elseif( stripos( $item_title, 'miark' ) !== false or 
								stripos( $item_dscr, 'miark' ) !== false ){
							$cat_name = 'breloki';
							$subcat_name = 'miarki';
							
						}
						else{
							$cat_name = 'breloki';
							$subcat_name = 'breloki';
							
						}
						
					}
					elseif( stripos( $item_title, 'smycz' ) !== false ){
						$cat_name = 'sport i rekreacja';
						$subcat_name = 'akcesoria sportowe';
						
					}
					elseif( stripos( $item_title, 'krokomierz' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'smycze';
						
					}
					elseif( stripos( $item_title, 'wieszak' ) !== false or 
							stripos( $item_title, 'view' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					elseif( stripos( $item_title, 'otwieracz' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'otwieracze do butelek';
						
					}
					elseif( stripos( $item_title, 'in car' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'samochód';
						
					}
					elseif( stripos( $item_title, 'upomin' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'zestawy upominkowe';
						
					}
					else{
						$cat_name = 'xxx';
						
					}
					
				}
				elseif( $cat_name === 'gry' ){
					
					$cat_name = 'rozrywka i szkoła';
					$subcat_name = 'gry';
					
				}
				elseif( $cat_name === 'czapki' ){
					
					$cat_name = 'tekstylia';
					
					if( stripos( $item_title, 'baseball' ) !== false or 
							stripos( $item_title, 'segmentowa' ) !== false or 
							stripos( $item_dscr, 'segmentowa' ) !== false or 
							stripos( $item_title, 'soldier' ) !== false or 
							stripos( $item_title, 'wickie' ) !== false ){
						$subcat_name = 'czapki z daszkiem';
						
					}
					else{
						$cat_name = 'inne';
						
					}
					
				}
				elseif( stripos( $cat_name, 'pico' ) !== false ){
					$cat_name = 'xxx';
					
					if( stripos( $item_title, 'torba' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'torby termoizolacyjne';
						
					}
					elseif( stripos( $item_title, 'brelok' ) !== false ){
						$cat_name = 'breloki';
						$subcat_name = 'breloki';
						
					}
					elseif( stripos( $item_title, 'pendrive' ) !== false ){
						$cat_name = 'pendrive';
						$subcat_name = 'pozostałe';
						
					}
					elseif( stripos( $item_title, 'długopis' ) !== false ){
						
						if( stripos( $item_title, 'stojak' ) !== false ){
							$cat_name = 'biuro';
							$subcat_name = 'akcesoria biurowe';
							
						}
						elseif( stripos( $item_title, 'stojak' ) !== false ){
							$cat_name = 'biuro';
							$subcat_name = 'akcesoria biurowe';
							
						}
						elseif( stripos( $item_dscr, 'metal' ) !== false ){
							$cat_name = 'Materiały piśmiennicze';
							$subcat_name = 'Długopisy metalowe';
							
						}
						elseif( stripos( $item_dscr, 'drewn' ) !== false ){
							$cat_name = 'eco gadżet';
							
						}
						elseif( stripos( $item_dscr, 'sztucz' ) !== false ){
							$cat_name = 'Materiały piśmiennicze';
							$subcat_name = 'Długopisy plastikowe';
							
						}
						
					}
					elseif( stripos( $item_title, 'kubek' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'kubki';
						
					}
					elseif( stripos( $item_title, 'bidon' ) !== false ){
						$cat_name = 'Podróż';
						$subcat_name = 'Termosy i bidony';
						
					}
					elseif( stripos( $item_title, 'pieprz' ) !== false or
					stripos( $item_title, 'minutnik' ) !== false or
					stripos( $item_title, 'śniada' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'akcesoria kuchenne';
						
					}
					elseif( stripos( $item_title, 'wizytow' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'Wizytowniki';
						
					}
					elseif( stripos( $item_title, 'organ' ) !== false or 
						stripos( $item_title, 'memo' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'Akcesoria biurowe';
						
					}
					elseif( stripos( $item_title, 'mysz' ) !== false ){
						$cat_name = 'Akcesoria komputerowe';
						$subcat_name = 'Mysz';
						
					}
					elseif( stripos( $item_title, 'słuch' ) !== false ){
						
						$cat_name = 'Akcesoria komputerowe';
						$subcat_name = 'słuchawki';
						
					}
					elseif( stripos( $item_title, 'zegar' ) !== false ){
						$cat_name = 'Zegary i zegarki';
						
						if( stripos( $item_title, 'biurk' ) !== false ){
							$subcat_name = 'Zegary biurkowe';
							
						}
						else{
							$subcat_name = 'Pozostałe';
							
						}
						
					}
					elseif( stripos( $item_title, 'pogod' ) !== false ){
						$cat_name = 'Elektronika';
						$subcat_name = 'Stacje pogodowe';
						
					}
					elseif( stripos( $item_title, 'grill' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'grill';
						
					}
					elseif( stripos( $item_title, 'manicure' ) !== false ){
						$cat_name = 'uroda';
						$subcat_name = 'Pielęgnacja dłoni';
						
					}
					elseif( stripos( $item_title, 'luster' ) !== false ){
						$cat_name = 'uroda';
						$subcat_name = 'Lusterka';
						
					}
					elseif( stripos( $item_title, 'kemping' ) !== false or
						stripos( $item_title, 'latarka' ) !== false ){
						$cat_name = 'Wypoczynek';
						$subcat_name = 'Outdoor';
						
					}
					elseif( stripos( $item_title, 'krokomierz' ) !== false ){
						$cat_name = 'Wypoczynek';
						$subcat_name = 'Sport';
						
					}
					elseif( stripos( $item_title, 'miar' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'miarki';
						
					}
					elseif( stripos( $item_title, 'radio' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'akcesoria';
						
					}
					elseif( stripos( $item_title, 'stojak' ) !== false ){
						$cat_name = 'Akcesoria do telefonów i tabletów';
						$subcat_name = 'Akcesoria do telefonów';
						
					}
					elseif( stripos( $item_title, 'gra' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'gry';
						
					}
					elseif( stripos( $item_title, 'kalku' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'kalkulatory';
						
					}
					elseif( stripos( $item_title, 'zawiesz' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'akcesoria podróżne';
						
					}
					elseif( stripos( $item_title, 'laser' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'Wskaźniki laserowe';
						
					}
					elseif( stripos( $item_title, 'zapal' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'zapalniczki';
						
					}
					
				}
				
				
				$cat_name_slug = $this->stdNameCache( $cat_name );
				$subcat_name_slug = $cat_name_slug . "-" . $this->stdNameCache( $subcat_name );
				
				if( !empty( $cat_name ) ) $cat[ $cat_name_slug ][ $subcat_name_slug ] = array();
				// if( !empty( $cat2_name ) ) $cat[ $cat2_name ] = array();
				
				// $this->debugger( $cat_name, $subcat_name );
				
				/* $mark = array();
				$mark_size = array();
				$mark_type = array();
				$t = (string)$item->{'Imprint-size'};
				if( strlen( $t ) > 0 ){
					// $pattern = "/(\S+):(\d+x\d+)/";
					$pattern = "/(\S+):\s*(.+?mm)/";
					preg_match_all( $pattern, $t, $match );
					foreach( $match[1] as $key => $val ){
						$size = $match[2][ $key ];
						$type = $match[1][ $key ];
						$mark[ $size ][] = $type;
						if( !in_array( $type, $mark_size ) ) $mark_size[] = $size;
						if( !in_array( $type, $mark_type ) ) $mark_type[] = $type;
						
					}
					
				}
				else{
					$mark = array(
						'' => 'brak danych',
					);
					
					$mark_size[] = 'brak danych';
					$mark_type[] = 'brak danych';
					
				} */
				
				sscanf( (string)str_replace( ",", ".", $item->catalog_price ), "%u.%u zł", $zl, $gr );
				$price_netto = (float)"{$zl}.{$gr}";
				
				$id = (string)$item->sku;
				
				$pattern = "~>([^<>,]+?)[,<]~";
				preg_match_all( $pattern, html_entity_decode( (string)$item->kolor ), $colors );
				
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
							'NETTO' => null,
							'BRUTTO' => 0,
							'CURRENCY' => 'PLN',
						),
						'PRICE_ALT' => 'Wycena indywidualna<br>( telefon/mail )',
						'MODEL' => 'brak danych',
						'WEIGHT' => 'brak danych',
						'BRAND' => 'brak danych',
					),
					array(
						'ID' => $id,
						'SHORT_ID' => substr( str_replace( '-', '', $id ), 0 ),
						'NAME' => str_replace( "/", " / ", $item_title ),
						'DSCR' => $item_dscr,
						'IMG' => $img,
						'CAT' => $cat,
						'DIM' => (string)$item->wymiary,
						// 'MARK' => $mark,
						// 'MARKSIZE' => $mark_size,
						// 'MARKTYPE' => $mark_type,
						'MARK_TEXT' => preg_replace( "~, ~", "<br>", (string)$item->{'Imprint-size'} ),
						// 'INSTOCK' => 'brak danych',
						'MATTER' => (string)$item->material,
						'COLOR' => implode( ", ", $colors[1] ),
						// 'COUNTRY' => 'brak danych',
						'MARKCOLORS' => 1,
						'PRICE' => array(
							'NETTO' => $price_netto,
							'BRUTTO' => $this->price2brutto( $price_netto ),
							'CURRENCY' => 'PLN',
						),
						'PRICE_ALT' => 'Wycena indywidualna<br>( telefon/mail )',
						// 'MODEL' => 'brak danych',
						'WEIGHT' => (string)$item->weight_gross,
						'BRAND' => (string)$item->manufacturer,
						
					)
					
				);
				
			}
			
			// $this->debugger();
			
		}
		
		echo "<!--";
		//print_r( array_slice( $ret, 0, 10 ) );
		echo "-->";
		
		return $ret;
	}
	
}
