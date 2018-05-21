<?php
class PAR extends XMLAbstract {
	public $_shop = 'PAR';
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
			"http://biuro@merkuriusz.pl:merkuriusz345@www.par.com.pl/api/categories",
			"http://biuro@merkuriusz.pl:merkuriusz345@www.par.com.pl/api/products",
			"http://biuro@merkuriusz.pl:merkuriusz345@www.par.com.pl/api/stocks"
		);
		
	}
	
	// funkcja importująca dane o budowie menu w formie tablicy
	protected function getMenu(){
		$ret = array();
		$file = "categories.xml";		// plik do załadowania
		
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->category as $category ){
				$cat_name = $this->stdName( (string)$category->attributes()->name );
				$ret[ $cat_name ] = array();
				
				foreach( $category->node as $subcategory ){
					$subcat_name = $this->stdName( (string)$subcategory->attributes()->name );
					$ret[ $cat_name ][ $subcat_name ] = array();					
					
				}
				
			}
			
		}
		
		//return $ret;
		return array(
			'PAR' => $ret,
			
		);
		/*
		*/
		
	}
	
	// funkcja importująca dane o produktach w formie tablicy
	protected function getProducts(){
		$ret = array();
		$file = "products.xml";		// plik do załadowania
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->product as $item ){
				
				$item_title = (string)$item->nazwa;
				$item_dscr = (string)$item->opis;
				
				$id = (string)$item->kod;
				$name = (string)$item->nazwa;
				
				$dscrs = array( (string)$item->opis );
				if( strlen( (string)$item->opis_dodatkowy ) > 0 ) $dscrs[] = (string)$item->opis_dodatkowy;
				$dscr = implode( "<br>", $dscrs );
				
				$matters = array( (string)$item->material_wykonania );
				if( strlen( (string)$item->material_dodatkowy ) > 0 ) $matters[] = (string)$item->material_dodatkowy;
				$matter = implode( ", ", $matters );
				
				$dim = (string)$item->wymiary;
				$instock = $this->getStock( (string)$item->kod );
				
				$colors = array( (string)$item->kolor_podstawowy );
				if( strlen( (string)$item->kolor_dodatkowy ) > 0 ) $colors[] = (string)$item->kolor_dodatkowy;
				$color = implode( " / ", $colors );
				
				$price_netto = (float)str_replace( ",", ".", $item->cena_pln );
				$price_brutto = $this->price2brutto( $price_netto );
				$img = array();
				foreach( $item->zdjecia->children() as $image ){
					$img[] = "http://www.par.com.pl" . (string)$image;
					
				}
				
				/* $mark = array();
				$mark_size = array();
				$mark_type = array();
				foreach( $item->techniki_zdobienia->technika as $technika ){
					$size = (string)$technika->maksymalny_rozmiar_logo;
					$type = sprintf( "%s (%s)", (string)$technika->technika_zdobienia, (string)$technika->miejsce_zdobienia );
					$mark[ $size ][] = $type;
					
					$mark_size[] = $size;
					$mark_type[] = $type;
					
				} */
				
				$marks_text = array();
				foreach( $item->techniki_zdobienia->technika as $t ){
					$marks_text[] = sprintf( "%s %s %s",
						$t->technika_zdobienia,
						$t->miejsce_zdobienia,
						$t->maksymalny_rozmiar_logo
						
					);
					
				}
				
				$cat = array();
				$cat_name = strtolower( $item->kategorie->kategoria[0] );
				$subcat_name = '';
				
				if( $cat_name === 'akcesoria samochodowe' ){
					
					if( stripos( $item_title, 'skrob' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'skrobaczki';
						
					}
					elseif( stripos( $item_title, 'kamizel' ) !== false ){
						$cat_name = 'odblaski';
						$subcat_name = 'kamizelki';
						
					}
					elseif( stripos( $item_title, 'kubek' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'akcesoria';
						
					}
					elseif( stripos( $item_title, 'uchwyt' ) !== false ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria do telefonów';
						
					}
					
				}
				elseif( $cat_name === 'akcesoria komputerowe i smartfonowe' ){
					
					if( stripos( $item_title, 'rysik' ) !== false or 
							stripos( $item_title, 'touch tip' ) !== false ){
						
						$cat_name = 'materiały piśmiennicze';
						if( stripos( $item_dscr, 'długopis' ) !== false ){
							$subcat_name = 'długopisy z touch penem';
							
						}
						else{
							$subcat_name = 'Touch peny';
							
						}
						
					}
					elseif( stripos( $item_title, 'self' ) !== false or 
							stripos( $item_title, 'enlarge' ) !== false or 
							stripos( $item_title, 'tidy' ) !== false ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria do telefonów';
						
					}
					elseif( stripos( $item_title, 'rozdzielacz' ) !== false or 
							stripos( $item_title, 'touch control' ) !== false ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria';
						
					}
					elseif( stripos( $item_title, 'głośni' ) !== false ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						$subcat_name = 'głośniki';
						
					}
					elseif( stripos( $item_title, 'adowa' ) !== false or 
							stripos( $item_dscr, 'adowa' ) !== false or 
							stripos( $item_dscr, 'kabel' ) !== false ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						$subcat_name = 'ładowarki i kable';
						
					}
					elseif( stripos( $item_title, 'wirtual' ) !== false ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						$subcat_name = 'okulary wirtualnej rzeczywistości';
						
					}
					elseif( stripos( $item_title, 'intense' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'słuchawki';
						
					}
					elseif( stripos( $item_title, 'słuchaw' ) !== false ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						$subcat_name = 'słuchawki';
						
					}
					elseif( stripos( $item_title, 'trim' ) !== false ){
						$cat_name = 'akcesoria komputerowe';
						$subcat_name = 'pozostałe';
						
					}
					elseif( stripos( $item_title, 'podstawk' ) !== false ){
						$cat_name = 'Akcesoria do telefonów i tabletów';
						$subcat_name = 'Etui i podstawki';
						
					}
					
				}
				elseif( $cat_name === 'artykuły świąteczne' ){
					
					$cat_name = 'świąteczne';
					
				}
				elseif( $cat_name === 'bidony' ){
					
					$cat_name = 'podróź';
					$subcat_name = 'termosy i bidony';
					
				}
				elseif( $cat_name === 'breloki antystresowe' ){
					
					$cat_name = 'breloki';
					$subcat_name = 'antystresy';
					
				}
				elseif( $cat_name === 'czapki' ){
					
					$cat_name = 'tekstylia';
					if( stripos( $item_title, 'daszkiem' ) !== false or 
							stripos( $item_dscr, 'daszkiem' ) !== false or 
							stripos( $item_title, 'panel' ) !== false ){
						$subcat_name = 'czapki z daszkiem';
						
					}
					if( stripos( $item_title, 'przeciw' ) !== false ){
						$subcat_name = 'daszki przeciwsłoneczne';
						
					}
					if( stripos( $item_title, 'odblask' ) !== false ){
						$cat_name = 'odblaski';
						$subcat_name = 'czapki';
						
					}
					if( stripos( $item_title, 'świąt' ) !== false ){
						$cat_name = 'świąteczne';
						
					}
					else{
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'dla fanów' ){
					
					if( stripos( $item_title, 'długopis' ) !== false ){
						
						$cat_name = 'materiały piśmiennicze';
						if( stripos( $item_dscr, 'plastik' ) !== false ){
							$subcat_name = 'długopisy plastikowe';
							
						}
						else{
							$subcat_name = 'długopisy';
							
						}
						
					}
					elseif( stripos( $item_title, 'brelok' ) !== false ){
						
						$cat_name = 'breloki';
						if( stripos( $item_title, 'antystres' ) !== false ){
							$subcat_name = 'antystresowe';
							
						}
						elseif( stripos( $item_title, 'latark' ) !== false ){
							$subcat_name = 'latarki';
							
						}
						else{
							$subcat_name = 'breloki';
							
						}
						
					}
					else{
						$cat_name = 'wypoczynek';
						$subcat_name = 'sport';
						
					}
					
				}
				elseif( $cat_name === 'długopisy aluminiowe' ){
					
					$cat_name = 'materiały piśmiennicze';
					$subcat_name = 'długopisy aluminiowe';
					
				}
				elseif( $cat_name === 'długopisy eco' ){
					
					$cat_name = 'eco gadżet';
					$subcat_name = '';
					
				}
				elseif( $cat_name === 'długopisy metalowe' ){
					
					$cat_name = 'materiały piśmiennicze';
					$subcat_name = 'długopisy metalowe';
					
				}
				elseif( $cat_name === 'długopisy plastikowe' ){
					
					$cat_name = 'materiały piśmiennicze';
					$subcat_name = 'długopisy plastikowe';
					
				}
				elseif( $cat_name === 'etui' ){
					
					$cat_name = 'materiały piśmiennicze';
					$subcat_name = 'etui';
					
				}
				elseif( $cat_name === 'etui na laptopa i smartfon' ){
					
					$cat_name = 'Akcesoria do telefonów i tabletów';
					$subcat_name = 'Etui i podstawki';
					
				}
				elseif( $cat_name === 'gry' ){
					
					$cat_name = 'wypoczynek';
					
					if( stripos( $item_title, 'fidget' ) !== false ){
						$subcat_name = 'fidget spinner';
						
					}
					else{
						$subcat_name = 'gry i zabawy';
						
					}
					
				}
				elseif( $cat_name === 'izotermiczne' ){
					
					$cat_name = 'do picia';
					$subcat_name = 'kubk';
					
				}
				elseif( $cat_name === 'kosze' ){
					
					$cat_name = 'torby i plecaki';
					$subcat_name = 'na zakupy';
					
				}
				elseif( $cat_name === 'kubki' ){
					$cat_name = 'do picia';
					$subcat_name = 'kubki';
					
				}
				elseif( $cat_name === 'latarki' ){
					
					if( stripos( $item_title, 'brelok' ) !== false or 
							stripos( $item_dscr, 'brelok' ) !== false ){
						$cat_name = 'breloki';
						$subcat_name = 'latarki';
						
					}
					elseif( stripos( $item_title, 'rower' ) !== false ){
						$cat_name = 'Wypoczynek';
						$subcat_name = 'Akcesoria rowerowe i odblaski';
						
					}
					else{
						$cat_name = 'narzędzia';
						$subcat_name = 'latarki';
						
					}
					
				}
				elseif( $cat_name === 'leak proof' ){
					
					$cat_name = 'do picia';
					$subcat_name = 'kubk';
					
				}
				elseif( $cat_name === 'metalowe, aluminiowe' ){
					
					if( stripos( $item_title, 'brelok' ) !== false ){
						
						$cat_name = 'breloki';
						if( stripos( $item_title, 'metal' ) !== false or 
								stripos( $item_dscr, 'metal' ) !== false ){
							$subcat_name = 'metalowe';
							
						}
						else{
							$cat_name = 'breloki';
							
						}
						
					}
					
				}
				elseif( $cat_name === 'miarki, ołówki stolarskie' ){
					
					if( stripos( $item_title, 'brelok' ) !== false ){
						
						$cat_name = 'breloki';
						if( stripos( $item_title, 'miark' ) !== false ){
							$subcat_name = 'miarki';
							
						}
						else{
							$cat_name = 'breloki';
							
						}
						
					}
					elseif( stripos( $item_title, 'miar' ) !== false ){
						$cat_name = 'narzędzia';
						$subcat_name = 'miarki';
						
					}
					elseif( stripos( $item_title, 'ołów' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'ołówki';
						
					}
					
				}
				elseif( $cat_name === 'na biurko' ){
					
					$cat_name = 'biuro';
					
					if( stripos( $item_title, 'trofeum' ) !== false or 
							stripos( $item_title, 'przycisk' ) !== false or 
							stripos( $item_title, 'globe' ) !== false or 
							stripos( $item_title, 'kartecz' ) !== false or 
							stripos( $item_title, 'spinacz' ) !== false ){
						$subcat_name = 'inne';
						
					}
					elseif( stripos( $item_title, 'wizyt' ) !== false ){
						$subcat_name = 'wizytowniki';
						
					}
					elseif( stripos( $item_title, 'przybornik' ) !== false ){
						$subcat_name = 'pojemniki na długopisy';
						
					}
					elseif( stripos( $item_title, 'memo' ) !== false ){
						$subcat_name = 'karteczki samoprzylepne';
						
					}
					elseif( stripos( $item_title, 'lupa' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'lupy';
						
					}
					else{
						$cat_name = 'xxx';
						
					}
					
				}
				elseif( $cat_name === 'niezbędne w podróży' ){
					
					if( stripos( $item_title, 'brelok' ) !== false ){
						
						$cat_name = 'breloki';
						if( stripos( $item_title, 'metal' ) !== false or 
								stripos( $item_dscr, 'metal' ) !== false ){
							$subcat_name = 'metalowe';
							
						}
						else{
							$subcat_name = 'breloki';
							
						}
						
					}
					elseif( stripos( $item_title, 'wizytow' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'wizytowniki';
						
					}
					elseif( stripos( $item_title, 'portfel' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'portfele';
						
					}
					elseif( stripos( $item_title, 'etui' ) !== false ){
						
						$cat_name = 'podróż';
						if( stripos( $item_title, 'kart' ) !== false ){
							$subcat_name = 'etui na karty';
							
						}
						else{
							$subcat_name = 'akcesoria podróżne';
							
						}
						
					}
					elseif( stripos( $item_title, 'ski-pass' ) !== false or 
							stripos( $item_title, 'waga' ) !== false or 
							stripos( $item_title, 'kłódka' ) !== false or 
							stripos( $item_title, 'wieszak' ) !== false or 
							stripos( $item_title, 'przywieszka' ) !== false or 
							stripos( $item_title, 'neat dog' ) !== false or 
							stripos( $item_title, 'walk dog' ) !== false or 
							stripos( $item_title, 'smycz dla psa' ) !== false or 
							stripos( $item_title, 'feelfresh' ) !== false ){
						$cat_name = 'podróź';
						$subcat_name = 'akcesoria podróżne';
						
					}
					elseif( stripos( $item_title, 'kosmety' ) !== false ){
						$cat_name = 'podróź';
						$subcat_name = 'kosmetyczki';
						
					}
					elseif( stripos( $item_title, 'saszet' ) !== false ){
						$cat_name = 'podróź';
						$subcat_name = 'saszetki';
						
					}
					elseif( stripos( $item_title, 'peleryn' ) !== false ){
						$cat_name = 'przeciwdeszczowe';
						$subcat_name = 'peleryny';
						
					}
					elseif( stripos( $item_title, 'piersi' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'piersiówki';
						
					}
					elseif( stripos( $item_title, 'koc' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'koce';
						
					}
					elseif( stripos( $item_title, 'sparky' ) !== false ){
						$cat_name = 'sport i rekreacja';
						$subcat_name = 'akcesoria sportowe';
						
					}
					elseif( stripos( $item_title, 'przeciwsłon' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'okulary przeciwsłoneczne';
						
					}
					elseif( stripos( $item_title, 'torba' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'torby podróżne i sportowe';
						
					}
					elseif( stripos( $item_title, 'lokalizator' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'akcesoria';
						
					}
					elseif( stripos( $item_title, 'crystal' ) !== false ){
						$cat_name = 'Akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria do telefonów';
						
					}
					
				}
				elseif( $cat_name === 'notesy, notatniki' ){
					
					$cat_name = 'biuro';
					$subcat_name = 'notatniki i notesy';
					
				}
				elseif( $cat_name === 'odblaski' ){
					
					$cat_name = 'odblaski';
					if( stripos( $item_title, 'brelok' ) !== false ){
						$subcat_name = 'breloki';
						
					}
					elseif( stripos( $item_title, 'opaska' ) !== false ){
						$subcat_name = 'opaski';
						
					}
					elseif( stripos( $item_title, 'kamizel' ) !== false ){
						$subcat_name = 'kamizelki';
						
					}
					else{
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'ołówki' ){
					
					$cat_name = 'materiały piśmiennicze';
					$subcat_name = 'ołówki';
					
				}
				elseif( $cat_name === 'opaski do r08394' ){
					
					$cat_name = 'do picia';
					$subcat_name = 'inne';
					
				}
				elseif( $cat_name === 'ozdoby domowe' ){
					
					$cat_name = 'dom';
					if( stripos( $item_title, 'zapach' ) !== false ){
						$subcat_name = 'świece i aromaterapia';
						
					}
					else{
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'parasole' ){
					
					$cat_name = 'przeciwdeszczowe';
					$subcat_name = 'parasole';
					
				}
				elseif( $cat_name === 'piłki antystresowe' ){
					
					$cat_name = 'biuro';
					$subcat_name = 'antystresy';
					
				}
				elseif( $cat_name === 'pióra wieczne i kulkowe' ){
					
					$cat_name = 'materiały piśmiennicze';
					$subcat_name = 'pióra wieczne i kulkowe';
					
				}
				elseif( $cat_name === 'plecaki' ){
					
					$cat_name = 'torby i plecaki';
					$subcat_name = 'plecaki';
					
				}
				elseif( $cat_name === 'pluszaki i maskotki' ){
					
					$cat_name = 'pluszaki i maskotki';
					if( stripos( $item_title, 'brelok' ) !== false or 
							stripos( $item_title, 'zawieszka' ) !== false ){
						$subcat_name = 'breloki';
						
					}
					elseif( stripos( $item_title, 'urso' ) !== false or 
							 stripos( $item_title, 'bear' ) !== false or 
							stripos( $item_dscr, 'miś' ) !== false or 
							stripos( $item_title, 'teddy' ) !== false ){
						$subcat_name = 'misie';
						
					}
					elseif( stripos( $item_title, 'cow' ) !== false or 
							stripos( $item_title, 'sheep' ) !== false or 
							stripos( $item_title, 'horse' ) !== false ){
						$subcat_name = 'domowe';
						
					}
					elseif( stripos( $item_title, 'reindeer' ) !== false ){
						$subcat_name = 'boże narodzenie';
						
					}
					elseif( stripos( $item_title, 'plane' ) !== false or 
							stripos( $item_title, 'ball' ) !== false or 
							stripos( $item_title, 'car' ) !== false ){
						$subcat_name = 'inne zwierzątka';
						
					}
					else{
						$subcat_name = 'dzikie';
						
					}
					
				}
				elseif( $cat_name === 'pozostałe' ){
					
					$cat_name = 'biuro';
					$subcat_name = 'antystresy';
					
				}
				elseif( $cat_name === 'szkoła i dom' ){
					
					if( stripos( $item_title, 'skarbonk' ) !== false ){
						$cat_name = 'dodatki';
						$subcat_name = 'skarbonki';
						
					}
					elseif( stripos( $item_title, 'linijk' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'linijki';
						
					}
					elseif( stripos( $item_title, 'temper' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'temperówki';
						
					}
					elseif( stripos( $item_title, 'zakład' ) !== false or 
							stripos( $item_title, 'reclaim' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'akcesoria biurowe';
						
					}
					elseif( stripos( $item_title, 'szczoteczk' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'łazienka';
						
					}
					elseif( stripos( $item_title, 'kred' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'kredki';
						
					}
					elseif( stripos( $item_title, 'skakanka' ) !== false or 
							stripos( $item_title, 'lornetka' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'gry i zabawy';
						
					}
					elseif( stripos( $item_title, 'notes' ) !== false or 
							stripos( $item_title, 'memo' ) !== false or 
							stripos( $item_title, 'kartecz' ) !== false or 
							stripos( $item_dscr, 'kartecz' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'Notatniki i notesy';
						
					}
					elseif( stripos( $item_title, 'zestaw' ) !== false && 
							( stripos( $item_title, 'malowani' ) !== false or 
								stripos( $item_title, 'rysowani' ) !== false or 
								stripos( $item_title, 'kolorow' ) !== false ) ){
						$cat_name = 'rozrywka i szkoła';
						$subcat_name = 'zestawy do malowania';
						
					}
					elseif( stripos( $item_title, 'ołów' ) !== false ){
						$cat_name = 'Materiały piśmiennicze';
						$subcat_name = 'ołówki';
						
					}
					elseif( stripos( $item_title, 'piórnik' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'piórniki';
						
					}
					elseif( stripos( $item_title, 'zakreśl' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'zakreślacze';
						
					}
					elseif( stripos( $item_title, 'gra' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'gry i zabawy';
						
					}
					elseif( stripos( $item_title, 'plecak' ) !== false ){
						$cat_name = 'torby i plecaki';
						$subcat_name = 'plecaki';
						
					}
					elseif( stripos( $item_title, 'worek' ) !== false ){
						$cat_name = 'torby i plecaki';
						$subcat_name = 'worki ze sznurkiem';
						
					}
					elseif( stripos( $item_title, 'prezent' ) !== false ){
						$cat_name = 'opakowania upominkowe';
						$subcat_name = '';
						
					}
					elseif( stripos( $item_title, 'koszulka' ) !== false ){
						$cat_name = 'tekstylia';
						$subcat_name = 'odzież';
						
					}
					elseif( stripos( $item_title, 'odblask' ) !== false ){
						$cat_name = 'odblaski';
						$subcat_name = 'inne';
						
					}
					
				}
				elseif( $cat_name === 'teczki i torby na dokumenty' ){
					
					$cat_name = 'torby i plecaki';
					$subcat_name = 'na laptopa i dokumenty';
					
				}
				elseif( $cat_name === 'teczki konferencyjne' ){
					
					$cat_name = 'biuro';
					$subcat_name = 'teczki';
					
				}
				elseif( $cat_name === 'termosy' ){
					
					if( stripos( $item_title, 'termos' ) !== false or 
							stripos( $item_dscr, 'termos' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'termosy';
						
					}
					elseif( stripos( $item_title, 'kubek' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'kubki';
						
					}
					else{
						$cat_name = 'xxx';
						$subcat_name = '';
						
					}
					
				}
				elseif( $cat_name === 'torby i plecaki na laptopa' ){
					
					if( stripos( $item_title, 'laptop' ) !== false ){
						$cat_name = 'torby i plecaki';
						$subcat_name = 'na laptopa';
						
					}
					elseif( stripos( $item_title, 'tablet' ) !== false ){
						$cat_name = 'akcesoria do telefonów i tabletów';
						$subcat_name = 'akcesoria do tabletów';
						
					}
					
				}
				elseif( $cat_name === 'torby na prezenty' ){
					
					$cat_name = 'opakowania upominkowe';
					
				}
				elseif( $cat_name === 'torby na wino' ){
					
					$cat_name = 'vine club';
					$subcat_name = 'opakowania';
					
				}
				elseif( $cat_name === 'torby na zakupy' ){
					
					$cat_name = 'torby i plecaki';
					$subcat_name = 'na zakupy';
					
				}
				elseif( $cat_name === 'torby podróżne' ){
					
					$cat_name = 'torby i plecaki';
					$subcat_name = 'podróżne';
					
				}
				elseif( $cat_name === 'upominiki ze skóry' ){
					
					if( stripos( $item_title, 'karty' ) !== false ){
						$cat_name = 'podróz';
						$subcat_name = 'etui na karty';
						
					}
					elseif( stripos( $item_title, 'etui' ) !== false ){
						$cat_name = 'materiały piśmiennicze';
						$subcat_name = 'etui na karty';
						
					}
					elseif( stripos( $item_title, 'kosmetyczk' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'kosmetyczki';
						
					}
					elseif( stripos( $item_title, 'wizyt' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'wizytowniki';
						
					}
					
				}
				elseif( $cat_name === 'wizytowniki' ){
					
					if( stripos( $item_title, 'wizyt' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'wizytowniki';
						
					}
					elseif( stripos( $item_title, 'etui' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'etui na karty';
						
					}
					elseif( stripos( $item_title, 'upomin' ) !== false ){
						$cat_name = 'biuro';
						$subcat_name = 'zestawy upominkowe';
						
					}
					else{
						$cat_name = 'xxx';
						
					}
					
				}
				elseif( $cat_name === 'worki na prezenty' ){
					
					$cat_name = 'torby i plecaki';
					$subcat_name = 'świąteczne';
					
				}
				elseif( $cat_name === 'z miarką, latarką, diodą' ){
					
					if( stripos( $item_title, 'raincoat' ) !== false ){
						$cat_name = 'przeciwdeszczowe';
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
					
				}
				elseif( $cat_name === 'z żetonem, z otwieraczem' ){
					
					if( stripos( $item_title, 'brelok' ) !== false ){
						
						$cat_name = 'breloki';
						if( stripos( $item_title, 'metal' ) !== false or 
								stripos( $item_dscr, 'metal' ) !== false ){
							$subcat_name = 'metalowe';
							
						}
						if( stripos( $item_title, 'alumin' ) !== false ){
							$subcat_name = 'aluminiowe';
							
						}
						else{
							$cat_name = 'breloki';
							
						}
						
					}
					
				}
				elseif( $cat_name === 'zegary i kalkulatory' ){
					
					if( stripos( $item_title, 'kalkulat' ) !== false ){
						$cat_name = 'elektronika';
						$subcat_name = 'kalkulatory';
						
					}
					elseif( stripos( $item_title, 'zegar' ) !== false ){
						$cat_name = 'zegary i zegarki';
						
						if( stripos( $item_title, 'ścien' ) !== false ){
							$subcat_name = 'ścienne';
							
						}
						else{
							$subcat_name = 'pozostałe';
							
						}
						
					}
					else{
						$cat_name = 'xxx';
						
					}
					
				}
				elseif( $cat_name === 'zestawy do wina' ){
					
					$cat_name = 'vine club';
					if( stripos( $item_title, 'zestaw' ) !== false ){
						$cat_name = 'zestawy';
						
					}
					else{
						$cat_name = 'akcesoria';
						
					}
					
				}
				elseif( $cat_name === 'zestawy kuchenne i łazienkowe' ){
					
					if( stripos( $item_title, 'grill' ) !== false ){
						$cat_name = 'wypoczynek';
						$subcat_name = 'grill';
						
					}
					elseif( stripos( $item_title, 'kosmetyczka' ) !== false ){
						$cat_name = 'podróż';
						$subcat_name = 'kosmetyczki';
						
					}
					elseif( stripos( $item_title, 'pojemnik' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'pojemnik na żywność';
						
					}
					elseif( stripos( $item_title, 'młyn' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'Pojemniki i młynki do przypraw';
						
					}
					elseif( stripos( $item_title, 'dziad' ) !== false ){
						$cat_name = 'dom';
						$subcat_name = 'Dziadki do orzechów';
						
					}
					elseif( stripos( $item_title, 'ręcznik' ) !== false ){
						$cat_name = 'sport i rekreacja';
						$subcat_name = 'akcesoria sportowe';
						
					}
					else{
						$cat_name = 'dom';
						$cat_name = 'akcesoria kuchenne';
						
					}
					
				}
				elseif( $cat_name === 'zestawy narzędzi, scyzoryki' ){
					
					$cat_name = 'narzędzia';
					if( stripos( $item_title, 'brelok' ) !== false ){
						$cat_name = 'breloki';
						if( stripos( $item_title, 'wielofun' ) !== false ){
							$subcat_name = 'breloki';
							
						}
						else{
							$subcat_name = 'breloki';
							
						}
						
						
					}
					elseif( stripos( $item_title, 'scyzor' ) !== false ){
						$subcat_name = 'scyzoryki';
						
					}
					elseif( stripos( $item_title, 'wielofun' ) !== false or 
							stripos( $item_title, 'saperka' ) !== false ){
						$subcat_name = 'wielofunkcyjne';
						
					}
					elseif( stripos( $item_title, 'zestaw' ) !== false ){
						$subcat_name = 'zestawy';
						
					}
					elseif( stripos( $item_title, 'nóż' ) !== false or 
							stripos( $item_title, 'noż' ) !== false ){
						$subcat_name = 'noże i nożyki';
						
					}
					else{
						$cat_name = 'xxx';
						
					}
					
				}
				elseif( $cat_name === 'zestawy noży' ){
					
					$cat_name = 'dom';
					$subcat_name = 'kuchnia';
					
				}
				elseif( $cat_name === 'zestawy piśmiennicze' ){
					
					$cat_name = 'materiały piśmiennicze';
					if( stripos( $item_title, 'piśmienn' ) !== false ){
						$subcat_name = 'zesawy piśmienne';
						
					}
					else{
						$subcat_name = 'inne';
						
					}
					
				}
				
				$cat_name_slug = $this->stdNameCache( $cat_name );
				$subcat_name_slug = $cat_name_slug . "-" . $this->stdNameCache( $subcat_name );
				$cat[ $cat_name_slug ][ $subcat_name_slug ] = array();
				
				// $this->debugger( $cat_name, $subcat_name );
				
				$ret[] = array_merge(
					array(
						'SHOP' => $this->_shop,
						'ID' => 'brak danych',
						// 'SHORT_ID' => '',
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
						// 'SHORT_ID' => '',
						'NAME' => $name,
						'DSCR' => $dscr,
						'IMG' => $img,
						'CAT' => $cat,
						'DIM' => $dim,
						// 'MARK' => $mark,
						// 'MARKSIZE' => $mark_size,
						// 'MARKTYPE' => $mark_type,
						'MARK_TEXT' => implode( "<br>", $marks_text ),
						'INSTOCK' => $instock,
						'MATTER' => $matter,
						'MATTER' => $matter,
						'COLOR' => $color,
						// 'COUNTRY' => 'brak danych',
						'MARKCOLORS' => 1,
						'PRICE' => array(
							'NETTO' => $price_netto,
							'BRUTTO' => $price_brutto,
							'CURRENCY' => 'PLN',
						),
						'PRICE_ALT' => 'Wycena indywidualna<br>( telefon/mail )',
						// 'MODEL' => 'brak danych',
						// 'WEIGHT' => 'brak danych',
						// 'BRAND' => 'brak danych',
					)
				);
				
			}
			
			// $this->debugger( );
			
		}
		
		return $ret;
	}
	
	// funkcja zwracająca stan magazynowy danego produktu
	private function getStock( $id ){
		static $arr = array();
		
		if( count( $arr ) === 0 ){
			$file = "stocks.xml";		// plik do załadowania
			if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
				// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
				
				return false;
			}
			else{
				// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
				foreach( $this->_XML[ $file ]->produkt as $item ){
					$arr[ (string)$item->kod ] = (int)$item->stan_magazynowy;
					
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
