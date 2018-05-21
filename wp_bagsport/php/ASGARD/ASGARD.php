<?php
class ASGARD extends XMLAbstract{
	public $_shop = 'ASGARD';
	//protected $_debug = false;
	//protected $_cache_write = false;
	//protected $_cache_read = false;
	
	// konstruktor
	public function __construct(){
		// $this->logger( "" . __CLASS__ . " loaded!", __FUNCTION__, __CLASS__ );
		//$this->_config['refresh'] = 10 * 60;		// 10 minut
		$this->_config['dnd'] = __DIR__ . "/DND";
		$this->_config['cache'] = __DIR__ . "/CACHE";
		$this->_config['remote'] = array( "http://www.asgard.pl/www/xml/oferta.xml" );
		
	}
	
	// funkcja importująca dane o budowie menu w formie tablicy
	protected function getMenu(){
		$ret = array();
		$file = "oferta.xml";		// plik do załadowania
		
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->children() as $sxe ){
				$cat = $this->stdNameCache( (string)$sxe->kategoria );
				$subcat = $this->stdNameCache( (string)$sxe->podkategoria );
				
				// if( !array_key_exists( $subcat, $ret[ $cat ] ) ){
				if( empty( $ret[ $cat ][ $subcat ] ) ){
					$ret[ $cat ][ $subcat ] = array();
					
				}
				
			}
			
		}
		
		//return $ret;
		echo "<!--";
		//print_r( $ret );
		echo "-->";
		return array(
			'ASGARD' => $ret,
			
		);
		
	}
	
	// funkcja importująca dane o produktach w formie tablicy
	protected function getProducts(){
		$ret = array();
		$file = "oferta.xml";		// plik do załadowania
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->produkt as $item ){
				
				$cat_name = strtolower( (string)$item->kategoria );
				$subcat_name = strtolower( (string)$item->podkategoria );
				$item_dscr = (string)$item->opis_produktu;
				$item_title = (string)$item->nazwa;
				
				/* =============== KATEGORIA =============== */
				
				/* =============== //KATEGORIA =============== */
				/* =============== PODKATEGORIA =============== */
				
				if( $cat_name === 'sport i wypoczynek' ){
					
					if( $subcat_name === 'akcesoria' ){
						if( stripos( (string)$item->nazwa, 'obuwia' ) !== false ){
							$cat_name = 'dom';
							$subcat_name = 'pielęgnacja obuwia';
							
						}
						elseif( stripos( (string)$item->nazwa, 'manicure' ) !== false ){
							$cat_name = 'uroda';
							$subcat_name = 'pielęgnacja dłoni';
							
						}
						elseif( stripos( (string)$item->nazwa, 'etui' ) !== false or stripos( (string)$item->nazwa, 'okładka' ) !== false ){
							$cat_name = 'materiały piśmiennicze';
							$subcat_name = 'etui';
							
						}
						elseif( stripos( (string)$item->nazwa, 'kosmetyczka' ) !== false ){
							$cat_name = 'uroda';
							$subcat_name = 'kosmetyczki';
							
						}
						elseif( stripos( (string)$item->nazwa, 'adapter' ) !== false ){
							$cat_name = 'elektronika';
							$subcat_name = 'adaptery';
							
						}
						elseif( stripos( (string)$item->nazwa, 'masaż' ) !== false ){
							$cat_name = 'uroda';
							$subcat_name = 'inne';
							
						}
						else{
							$cat_name = 'podróż';
							$subcat_name = 'akcesoria podróżne';
							
						}
						
					}
					elseif( $subcat_name === 'telefoniczne' ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria do telefonów';
						
					}
					elseif( $subcat_name === 'sportowe' or $subcat_name === 'sprotowe' ){
						if( stripos( (string)$item->nazwa, 'skakanka' ) !== false or 
						stripos( (string)$item->nazwa, 'frisbee' ) !== false or 
						stripos( (string)$item->nazwa, 'gwizdek' ) !== false or 
						stripos( (string)$item->nazwa, 'piłka' ) !== false or 
						stripos( (string)$item->nazwa, 'układan' ) !== false or 
						stripos( (string)$item->nazwa, 'domino' ) !== false or 
						stripos( (string)$item->nazwa, 'jo-jo' ) !== false or 
						stripos( (string)$item->nazwa, 'koło' ) !== false or 
						stripos( (string)$item->nazwa, 'klekotka' ) !== false ){
							$cat_name = 'wypoczynek';
							$subcat_name = 'gry i zabawy';
							
						}
						elseif( stripos( (string)$item->nazwa, 'fitness' ) !== false ){
							$cat_name = 'sport i rekreacja';
							$subcat_name = 'przyrządy do ćwiczeń';
							
						}
						elseif( stripos( (string)$item->nazwa, 'kubek' ) !== false ){
							$cat_name = 'do picia';
							$subcat_name = 'kubki';
							
						}
						else{
							$cat_name = 'sport i rekreacja';
							$subcat_name = 'akcesoria sportowe';
							
						}
						
					}
					elseif( $subcat_name === 'gry' ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'gry i zabawy';
						
					}
					elseif( $subcat_name === 'koce' ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'koce';
						
					}
					
				}
				elseif( $cat_name === 'do pisania' ){
					
					if( $subcat_name === 'akcesoria' ){
						$cat_name = 'biuro';
						
						if( stripos( (string)$item->nazwa, 'piórnik' ) !== false ){
							$subcat_name = 'piórniki';
							
						}
						else{
							$subcat_name = 'akcesoria biurowe';
							
						}
						
					}
					elseif( $subcat_name === 'parker i waterman' ){
						if( stripos( (string)$item->nazwa, 'parker' ) !== false ){
							$cat_name = 'vip piśmiennicze';
							$subcat_name = 'parker';
							
						}
						elseif( stripos( (string)$item->nazwa, 'waterman' ) !== false ){
							$cat_name = 'vip piśmiennicze';
							$subcat_name = 'waterman';
							
						}
						else{
							$cat_name = 'biuro';
							$subcat_name = 'akcesoria biurowe';
							
						}
						
					}
					elseif( $subcat_name === 'touch peny' ){
						if( stripos( (string)$item->nazwa, 'długopis' ) !== false or stripos( (string)$item->nazwa, 'pióro' ) !== false ){
							$cat_name = 'materiały piśmiennicze';
							$subcat_name = 'długopisy z touch penem';
							
						}
						else{
							$cat_name = 'akcesoria do telefonów i tabletów';
							$subcat_name = 'akcesoria';
							
						}
						
					}
					elseif( $subcat_name === 'kredki i zakreślacze' ){
						if( stripos( (string)$item->nazwa, 'kredk' ) !== false ){
							$cat_name = 'materiały piśmiennicze';
							$subcat_name = 'kredki';
							
						}
						elseif( stripos( (string)$item->nazwa, 'zakreślacz' ) !== false ){
							$cat_name = 'materiały piśmiennicze';
							$subcat_name = 'zakreślacze';
							
						}
						else{
							$cat_name = 'rozrywka i szkoła';
							$subcat_name = 'zestawy do malowania';
							
						}
						
					}
					elseif( $subcat_name === 'etui piśmiennicze' ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'etui';
						
					}
					elseif( $subcat_name === 'komplet piśmienniczy' ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'zestawy piśmienne';
						
					}
					elseif( $subcat_name === 'długopisy metalowe' ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'długopisy metalowe';
						
					}
					elseif( $subcat_name === 'długopisy plastikowe' ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'długopisy plastikowe';
						
					}
					elseif( $subcat_name === 'długopisy ekologiczne' ){
						$cat_name = 'eco gadżet';
						$subcat_name = '';
						
					}
					elseif( $subcat_name === 'ołówki' ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'ołówki';
						
					}
					
				}
				elseif( $cat_name === 'elektronika' ){
					
					if( $subcat_name === 'akcesoria' ){
						if( stripos( (string)$item->nazwa, 'stojak' ) !== false ){
							$cat_name = 'akcesoria do telefonów i tabletów';
							$subcat_name = 'stojaki';
							
						}
						elseif( stripos( (string)$item->nazwa, 'rozgałęziacz' ) !== false && stripos( (string)$item->nazwa, 'usb' ) !== false ){
							$subcat_name = 'huby usb';
							
						}
						elseif( stripos( (string)$item->nazwa, 'karton' ) !== false or stripos( (string)$item->nazwa, 'puszka' ) !== false ){
							$subcat_name = 'akcesoria';
							
						}
						elseif( stripos( (string)$item->nazwa, 'rękawiczk' ) !== false && stripos( (string)$item->nazwa, 'dotyk' ) !== false ){
							$cat_name = 'akcesoria do telefonów i tabletów';
							$subcat_name = 'akcesoria';
							
							
						}
						elseif( stripos( (string)$item->nazwa, 'kabel' ) !== false  ){
							$cat_name = 'akcesoria do telefonów i tabletów';
							$subcat_name = 'ładowarki i kable';
							
							
						}
						elseif( stripos( (string)$item->nazwa, 'brelok' ) !== false  ){
							$cat_name = 'pamięci usb';
							$subcat_name = 'pozostałe';
							
							
						}
						else{
							$subcat_name = 'akcesoria';
							
						}
						
					}
					elseif( $subcat_name === 'zegary' or $subcat_name === 'zegarki' ){
						$cat_name = 'zegary i zegarki';
						
						if( stripos( (string)$item->nazwa, 'ścienn' ) !== false ){
							$subcat_name = 'zegary ścienne';
							
						}
						elseif( stripos( (string)$item->nazwa, 'zegarek' ) !== false ){
							$subcat_name = 'zegarki na rękę';
							
						}
						elseif( stripos( (string)$item->nazwa, 'biur' ) !== false ){
							$subcat_name = 'zegary biurkowe';
							
						}
						elseif( stripos( (string)$item->nazwa, 'pogod' ) !== false ){
							$cat_name = 'elektronika';
							$subcat_name = 'stacje pogodowe';
							
						}
						else{
							$subcat_name = 'pozostałe';
							
						}
						
					}
					elseif( $subcat_name === 'stacje pogody'  or $subcat_name === 'stacja pogody' ){
						$subcat_name = 'stacje pogodowe';
						
					}
					elseif( $subcat_name === 'akcesoria biurowe' ){
						if( stripos( (string)$item->nazwa, 'touch' ) !== false ){
							$cat_name = 'materiały piśmiennicze';
							$subcat_name = 'długopisy z touch penem';
							
						}
						elseif( stripos( (string)$item->nazwa, 'adowarka' ) !== false && stripos( (string)$item->opis_produktu, 'usb' ) !== false ){
							$subcat_name = 'ładowarki usb';
							
						}
						elseif( stripos( (string)$item->nazwa, 'kalkulator' ) !== false ){
							$subcat_name = 'kalkulatory';
							
						}
						elseif( stripos( (string)$item->nazwa, 'laser' ) !== false ){
							$subcat_name = 'wskaźniki laserowe';
							
						}
						elseif( stripos( (string)$item->nazwa, 'biurk' ) !== false ){
							$cat_name = 'biuro';
							$subcat_name = 'akcesoria biurowe';
							
						}
						else{
							$subcat_name = 'inne';
							
						}
						
					}
					elseif( $subcat_name === 'telefoniczne' ){
						if( stripos( (string)$item->nazwa, 'samochodow' ) !== false ){
							$subcat_name = 'ładowarki samochodowe';
							
						}
						elseif( stripos( (string)$item->nazwa, 'głośnik' ) !== false ){
							$subcat_name = 'głośniki';
							
						}
						elseif( stripos( (string)$item->nazwa, 'adapter' ) !== false ){
							$subcat_name = 'adaptery';
							
						}
						elseif( stripos( (string)$item->nazwa, 'kieszonka' ) !== false ){
							$cat_name = 'akcesoria do telefonów i tabletów';
							$subcat_name = 'etui i podstawki';
							
						}
						elseif( stripos( (string)$item->nazwa, 'podróżny' ) !== false ){
							$cat_name = 'podróż';
							$subcat_name = 'zestawy podróżne';
							
						}
						elseif( stripos( (string)$item->nazwa, 'obiektyw' ) !== false or stripos( (string)$item->nazwa, 'zdjęć' ) !== false ){
							$cat_name = 'akcesoria do telefonów i tabletów';
							$subcat_name = 'akcesoria';
							
						}
						else{
							$subcat_name = 'inne';
							
						}
						
					}
					elseif( $subcat_name === 'power banki' ){
						// $cat_name = 'xxx';
						// $subcat_name = '';
						
						$val = null;
						$cat_name = 'power banki';
						
						if( stripos( (string)$item->nazwa, ' mAh' ) !== false ){
							preg_match( "@ ((?:\d+ )?\d+) mAh@i", (string)$item->nazwa, $match );
							// $val = (int)$match[1];
							$val = (int)str_replace( " ", "", $match[1] );
							
							
						}
						elseif( stripos( (string)$item->opis_produktu, ' mAh' ) !== false ){
							preg_match( "@ ((?:\d+ )?\d+) mAh@i", (string)$item->opis_produktu, $match );
							// $val = (int)$match[1];
							$val = (int)str_replace( " ", "", $match[1] );
							
						}
						
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
					elseif( $subcat_name === 'rozrywka' ){
						if( stripos( (string)$item->nazwa, 'vr' ) !== false ){
							$cat_name = 'akcesoria do telefonów i tabletów';
							$subcat_name = 'okulary wirtualnej rzeczywistości';
							
						}
						else{
							$subcat_name = 'inne';
							
						}
						
					}
					elseif( $subcat_name === 'notesy' ){
						if( stripos( (string)$item->nazwa, 'organizer' ) !== false ){
							$cat_name = 'biuro';
							$subcat_name = 'notatniki i notesy';
							
						}
						else{
							$cat_name = 'biuro';
							$subcat_name = 'notatniki i notesy';
							
						}
						
					}
					elseif( $subcat_name === 'pamięci usb' ){
						$cat_name = 'pamięci usb';
						
						if( stripos( (string)$item->nazwa, 'GB' ) !== false ){
						
							preg_match( "@(\d+) ?gb@i", (string)$item->nazwa, $match );
							
							if( count( $match ) > 1 ){
								$val = (int)$match[1];
								
								$subcat_name = "{$val}GB";
								
							}
							else{
								$subcat_name = 'pozostałe';
								
							}
							
						}
						else{
							$subcat_name = 'pozostałe';
							
						}
						
					}
					
				}
				elseif( $cat_name === 'biuro i praca' ){
					
					if( $subcat_name === 'akcesoria' ){
						
						if( stripos( (string)$item->nazwa, 'skarbonka' ) !== false ){
							$cat_name = 'dodatki';
							$subcat_name = 'skarbonki';
							
						}
						elseif( stripos( (string)$item->nazwa, 'pudełko' ) !== false ){
							$cat_name = 'biuro';
							$subcat_name = 'akcesoria biurowe';
							
						}
						elseif( stripos( (string)$item->nazwa, 'lupa' ) !== false ){
							$cat_name = 'dodatki';
							$subcat_name = 'lupy';
							
						}
						else{
							$cat_name = 'biuro';
							$subcat_name = 'akcesoria biurowe';
							
						}
						
					}
					elseif( $subcat_name === 'portfele' ){
						$cat_name = 'podróż';
						$subcat_name = 'portfele';
						
					}
					elseif( $subcat_name === 'teczki konferencyjne' ){
						$cat_name = 'biuro';
						$subcat_name = 'teczki';
						
					}
					elseif( $subcat_name === 'etui na tablet' ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria do tabletów';
						
					}
					elseif( $subcat_name === 'wizytowniki i etui na wizytówki' ){
						if( stripos( (string)$item->nazwa, 'etui' ) !== false ){
							if( stripos( (string)$item->nazwa, 'wizytówk' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'etui na wizytówki';
							}
							else{
								$cat_name = 'materiały piśmiennicze';
								$subcat_name = 'etui';
								
							}
							
						}
						elseif( stripos( (string)$item->nazwa, 'wizytownik' ) !== false ){
							$cat_name = 'biuro';
							$subcat_name = 'wizytowniki';
							
						}
						else{
							$cat_name = 'biuro';
							$subcat_name = 'akcesoria biurowe';
							
						}
						
					}
					elseif( $subcat_name === 'komplety upominkowe' ){
						$cat_name = 'biuro';
						$subcat_name = 'zestawy upominkowe';
						
					}
					elseif( $subcat_name === 'notesy' ){
						$cat_name = 'biuro';
						$subcat_name = 'notatniki i notesy';
						
					}
					elseif( $subcat_name === 'artykuły biurowe' ){
						$cat_name = 'biuro';
						
						if( stripos( (string)$item->nazwa, 'samoprzylepn' ) !== false ){
							$subcat_name = 'karteczki samoprzylepne';
							
						}
						elseif( stripos( (string)$item->nazwa, 'linijk' ) !== false ){
							$subcat_name = 'linijki';
							
						}
						elseif( stripos( (string)$item->nazwa, 'klip' ) !== false ){
							$subcat_name = 'klipy do notatek';
							
						}
						else{
							$subcat_name = 'akcesoria biurowe';
							
						}
						
					}
					elseif( $subcat_name === 'telefoniczne' ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						
						if( stripos( (string)$item->nazwa, 'etui' ) !== false ){
							$subcat_name = 'etui i podstawki';
							
						}
						elseif( stripos( (string)$item->nazwa, 'stojak' ) !== false ){
							$subcat_name = 'stojaki';
							
						}
						else{
							$subcat_name = 'akcesoria';
							
						}
						
					}
					elseif( $subcat_name === 'komputerowe' ){
						$cat_name = 'akcesoria komputerowe';
						$subcat_name = 'pozostałe';
						
					}
					elseif( stripos( $subcat_name , 'papierem kamiennym' ) !== false ){
						if( stripos( (string)$item->nazwa, 'notes' ) !== false ){
							$cat_name = 'biuro';
							$subcat_name = 'notatniki i notesy';
							
						}
						elseif( stripos( (string)$item->nazwa, 'prezent' ) !== false ){
							$cat_name = 'gadżety reklamowe';
							$subcat_name = 'opakowania upominkowe';
							
						}
						elseif( stripos( (string)$item->nazwa, 'wino' ) !== false ){
							$cat_name = 'vine club';
							$subcat_name = 'opakowania';
							
						}
						else{
							$cat_name = 'biuro';
							$subcat_name = 'akcesoria biurowe';
							
						}
						
					}
					
				}
				elseif( $cat_name === 'torby i parasole' ){
					
					if( $subcat_name === 'torby na laptop' ){
						$cat_name = 'torby i parasole';
						$subcat_name = 'na laptopa i dokumenty';
						
					}
					elseif( $subcat_name === 'plecaki' ){
						$cat_name = 'torby i plecaki';
						
						if( stripos( (string)$item->nazwa, 'laptop' ) !== false ){
							$subcat_name = 'na laptopa i dokumenty';
						
						}
						else{
							$subcat_name = 'plecaki';
							
						}
						
					}
					elseif( $subcat_name === 'akcesoria' ){
						if( stripos( (string)$item->nazwa, 'ponczo' ) !== false ){
							$cat_name = 'przeciwdeszczowe';
							$subcat_name = 'inne';
						
						}
						elseif( stripos( (string)$item->nazwa, 'term' ) !== false ){
							$cat_name = 'torby i plecaki';
							$subcat_name = 'termoizolacyjne';
						
						}
						elseif( stripos( (string)$item->nazwa, 'kosmetyczka' ) !== false ){
							$cat_name = 'uroda';
							$subcat_name = 'kosmetyczki';
						
						}
						elseif( stripos( (string)$item->nazwa, 'organizer' ) !== false ){
							$cat_name = 'biuro';
							$subcat_name = 'notatniki i notesy';
						
						}
						else{
							$subcat_name = 'inne';
							
						}
						
					}
					elseif( $subcat_name === 'torby na dokumenty' ){
						$cat_name = 'torby i plecaki';
						$subcat_name = 'na dokumenty';
						
						
					}
					elseif( in_array( $subcat_name, array( 'sportowe', 'sprotowe' ) ) ){
						$cat_name = 'torby i plecaki';
						$subcat_name = 'sportowe';
						
					}
					elseif( $subcat_name === 'na zakupy' ){
						$cat_name = 'torby i plecaki';
						$subcat_name = 'na zakupy';
						
					}
					elseif( $subcat_name === 'parasole' ){
						$cat_name = 'przeciwdeszczowe';
						$subcat_name = 'parasole';
						
					}
					
				}
				elseif( $cat_name === 'jedzenie i picie' or $cat_name === 'jedzienie i picie' ){
					
					if( in_array( $subcat_name, array( 'piersiówki', 'kubki termiczne', 'kubki metalowe', 'kubki plastikowe' ) ) ){
						$cat_name = 'do picia';
						$subcat_name = 'kubki';
						
					}
					elseif( in_array( $subcat_name, array( 'bidony', 'termosy' ) ) ){
						$cat_name = 'podróż';
						$subcat_name = 'termosy i bidony';
						
					}
					elseif( $subcat_name === 'akcesoria do alkoholi' ){
						
						if( stripos( (string)$item->nazwa, ' win' ) !== false ){
							$cat_name = 'vine club';
							
							if( stripos( (string)$item->nazwa, 'zestaw' ) !== false ){
								$subcat_name = 'zestawy';
								
							}
							elseif( stripos( (string)$item->nazwa, 'skrzynka' ) !== false or stripos( (string)$item->nazwa, 'torba' ) !== false ){
								$subcat_name = 'opakowania';
								
							}
							else{
								$subcat_name = 'akcesoria';
							}
							
						}
						elseif( stripos( (string)$item->nazwa, 'kieliszk' ) !== false ){
							$cat_name = 'do picia';
							$subcat_name = 'kieliszki';
						
						}
						else{
							$cat_name = 'do picia';
							$subcat_name = 'inne';
							
						}
						
					}
					elseif( $subcat_name === 'kuchenne' ){
						$cat_name = 'dom';
						$subcat_name = 'kuchnia';	
						
					}
					elseif( $subcat_name === 'na grill i piknik' ){
						$cat_name = 'wypoczynek';
						
						if( stripos( (string)$item->nazwa, 'grill' ) !== false ){
							$subcat_name = 'grill';
						
						}
						elseif( stripos( (string)$item->nazwa, 'piknik' ) !== false ){
							$subcat_name = 'piknik';
						
						}
						else{
							$subcat_name = 'inne';
							
						}
						
					}
					
				}
				elseif( $cat_name === 'breloki i smycze' ){
					
					if( $subcat_name === 'breloki' ){
						$cat_name = 'breloki';
						
						if( stripos( $item_dscr, 'metal' ) !== false ){
							$subcat_name = 'metalowe';
							
						}
						elseif( stripos( $item_dscr, 'alumin' ) !== false ){
							$subcat_name = 'aluminiowe';
							
						}
						else{
							$subcat_name = 'wielofunkcyjne';
							
						}
						
					}
					elseif( $subcat_name === 'akcesoria' ){
						$cat_name = 'breloki';
						$subcat_name = 'wielofunkcyjne';
						
					}
					elseif( $subcat_name === 'smycze' ){
						$cat_name = 'podróż';
						
					}
					
				}
				elseif( $cat_name === 'narzędzia i odblaski' ){
					
					if( $subcat_name === 'breloki' ){
						$cat_name = 'breloki';
						$subcat_name = 'wielofunkcyjne';
						
					}
					elseif( $subcat_name === 'latarki' ){
						$cat_name = 'narzędzia';
						
					}
					elseif( $subcat_name === 'akcesoria' ){
						
						if( stripos( $item_title, 'uchwyt' ) !== false ){
							$cat_name = 'akcesoria do telefonów i tabletów';
							$subcat_name = 'akcesoria do telefonów';
							
						}
						elseif( stripos( $item_title, 'rower' ) !== false or stripos( $item_dscr, 'rower' ) !== false ){
							$cat_name = 'wypoczynek';
							$subcat_name = 'akcesoria rowerowe i odblaski';
							
						}
						elseif( stripos( $item_title, 'długopis' ) !== false ){
							$cat_name = 'materiały piśmiennicze';
							$subcat_name = 'długopisy wielofunkcyjne';
							
						}
						else{
							$cat_name = 'narzędzia';
							$subcat_name = 'inne';
							
						}
						
					}
					elseif( $subcat_name === 'odblaskowe' ){
						$cat_name = 'odblaski';
						
						if( stripos( $item_title, 'kamizelka' ) !== false or stripos( $item_title, 'szelki' ) !== false ){
							$subcat_name = 'kamizelki';
							
						}
						elseif( stripos( $item_title, 'opaska' ) !== false ){
							$subcat_name = 'opaski';
							
						}
						elseif( stripos( $item_title, 'zawieszka' ) !== false ){
							$subcat_name = 'breloki';
							
						}
						else $subcat_name = 'inne';
						
					}
					// elseif( $subcat_name === 'zestawy narzędzi' ){
					elseif( $subcat_name === 'zestawy narzędzi' or stripos( $item_title, 'narzędzi' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'zestawy';
						
					}
					elseif( $subcat_name === 'miary' ){
						$cat_name = 'narzędzia';
						$subcat_name = 'miary i miarki';
						
					}
					elseif( $subcat_name === 'skrobaczki' ){
						$cat_name = 'narzędzia';
						$subcat_name = 'samochód';
						
					}
					else{
						$cat_name = 'narzędzia';
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'oferta świąteczna' ){
					
					$cat_name = 'świąteczne';
					$subcat_name = '';
					
				}
				elseif( stripos( $cat_name, 'do pisania' ) !== false ){
					
					// $cat_name = 'xxx';
					$cat_name = 'materiały piśmiennicze';
					
					if( in_array( $subcat_name, array( 'długopisy metalowe', 'długopisy plastikowe' ) ) ){
						
					}
					elseif( $subcat_name === 'etui piśmiennicze' ){
						$subcat_name = 'etui';
						
					}
					else{
						$subcat_name = 'inne';
						
					}
					
				}
				
				/* 
				(string)$item->nazwa
				(string)$item->opis_produktu
				*/
				
				/* =============== //PODKATEGORIA =============== */
				
				$cat_name_slug = $this->stdNameCache( $cat_name );
				$subcat_name_slug = $cat_name_slug . "-" . $this->stdNameCache( $subcat_name );
				
				$cat = array();
				//$cat[ $this->stdNameCache( (string)$item->kategoria ) ][ $this->stdNameCache( (string)$item->podkategoria ) ] = array();
				if( !empty( $subcat_name_slug ) ){
					$cat[ $cat_name_slug ][ $subcat_name_slug ] = array();
					
				}
				else{
					$cat[ $cat_name_slug ] = array();
					
				}
				
				// $this->debugger( $cat_name, $subcat_name );
				
				$img = array();
				$img[] = sprintf( "http://asgard.pl/png/product/%s" ,(string)$item->{"obraz_1"} );
				
				/* $mark = array();
				preg_match_all( "~(\w.*?) \((.*?)\)(?: \((.*?)\))?~", (string)$item->znakowanie_produktu, $match );
				for( $i=0; $i<count( $match[0] ); $i++ ){
					$type = $match[1][ $i ];
					$size = $match[2][ $i ];
					$place = $match[3][ $i ];
					if( !empty( $place ) ){
						$size .= " ($place)";
					}
					$mark[ $size ][] = $type;
					
				}
				
				$mark_size = array();
				$mark_type = array();
				$mark_array = array();
				$pattern = "~(\w+)\s+\((.+?)\)~";
				preg_match_all( $pattern, (string)$item->znakowanie_produktu, $match );
				foreach( $match[0] as $index => $val ){
					$mark_size[] = $match[2][ $index ];
					$mark_type[] = $match[1][ $index ];
					$mark_array[ $match[2][ $index ] ][] = $match[1][ $index ];
					
				} */
				
				$price_netto = (float)str_replace( ",", ".", $item->cena_netto_katalogowa );
				
				$ret[] = array_merge(
					array(
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
					),
					array(
						'ID' => (string)$item->indeks,
						'NAME' => (string)$item->nazwa,
						'DSCR' => (string)$item->opis_produktu,
						'IMG' => $img,
						'CAT' => $cat,
						'DIM' => (string)$item->wymiary_produktu,
						// 'MARK' => $mark_array,
						// 'MARKSIZE' => $mark_size,
						// 'MARKTYPE' => $mark_type,
						'MARK_TEXT' => preg_replace( "~, ~", "<br>", (string)$item->znakowanie_produktu ),
						'INSTOCK' => (int)$item->in_stock,
						'MATTER' => (string)$item->material,
						'COLOR' => (string)$item->kolor,
						// 'COUNTRY' => 'brak danych',
						'MARKCOLORS' => 1,
						'PRICE' => array(
							'NETTO' => $price_netto,
							'BRUTTO' => $this->price2brutto( $price_netto ),
							'CURRENCY' => 'PLN',
						),
						'MODEL' => 'brak danych',
						'WEIGHT' => sprintf( "%.4f kg", (float)str_ireplace( ",", ".", $item->waga_jednostkowa_netto_w_kg ) ),
						// 'BRAND' => 'brak danych',
					)
					
				);
			}
			
			// $this->debugger();
			
		}
		
		return $ret;
	}
	
}
