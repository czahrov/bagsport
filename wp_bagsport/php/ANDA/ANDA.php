<?php
class ANDA extends XMLAbstract{
	public $_shop = 'ANDA';
	//protected $_debug = false;
	//protected $_cache_write = false;
	//protected $_cache_read = false;
	
	// konstruktor
	public function __construct(){
		// $this->logger( "" . __CLASS__ . " loaded!", __FUNCTION__, __CLASS__ );
		//$this->_config['refresh'] = 60 * 60 * 4.5;		// 4.5 godziny
		$this->_config['dnd'] = __DIR__ . "/DND";
		$this->_config['cache'] = __DIR__ . "/CACHE";
		$this->_config['remote'] = array( "http://andapresent.hu/admin/system/anda_xml_export2.php?&orszag_id=6&nyelv_id=7&password=92ba3632c8c22ebd65fbce872b317875" );
		
	}
	
	// funkcja importująca dane o budowie menu w formie tablicy
	protected function getMenu(){
		//return array();
		
		$ret = array();
		$file = "anda_xml_export2.xml";		// plik do załadowania
		
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->product as $product ){
				foreach( $product->folders as $folders ){
					$this->genMenuTree( $folders, $ret, $product );
					
				}
				
			}
			
		}
		
		return array(
			'ANDA' => $ret,
			
		);
		
	}
	
	// funckja generująca drzewo podkategorii
	private function genMenuTree( SimpleXMLElement $node, Array &$arr){
		// tablica wskaźników
		$proxy = array();
		
		foreach( $node->folder as $folder ){
			$cat = $this->stdName( (string)$folder->attributes()->category );
			$subcat = $this->stdName( (string)$folder->attributes()->subcategory );
			
			/*
				Sprawdza czy w tablicy wskaźników istnieje wpis dla danej kategorii i podkategorii.
				Jeśli nie, to go dodaje.
			*/
			if( !array_key_exists( $cat, $proxy ) ){
				if( !array_key_exists( $cat, $arr ) ){
					$arr[ $cat ] = array();
					
				}
				
				$proxy[ $cat ] =& $arr[ $cat ];
				
			}
			
			if( !array_key_exists( $subcat, $proxy ) ){
				//if( !array_key_exists( $subcat, $arr[ $cat ] ) ){
				if( empty( $arr[ $cat ][ $subcat ] ) ){
					$arr[ $cat ][ $subcat ] = array();
					
				}
				
				$proxy[ $subcat ] =& $arr[ $cat ][ $subcat ];
				
			}
			
		}
		
	}
	
	// funkcja importująca dane o produktach w formie tablicy
	protected function getProducts(){
		//return array();
		
		$ret = array();
		$file = "anda_xml_export2.xml";		// plik do załadowania
		if( !array_key_exists( $file, $this->_XML ) or $this->_XML[ $file ] === false ){
			// $this->logger( "plik $file nie został prawidłowo wczytany!", __FUNCTION__, __CLASS__ );
			
		}
		else{
			// $this->logger( "odczyt XML z pliku $file", __FUNCTION__, __CLASS__ );
			foreach( $this->_XML[ $file ]->children() as $item ){
				// tablica z obrazkami
				$img = array();
				foreach( $item->images->children() as $image ){
					$img[] = (string)$image->attributes()->src;
					
				}
				
				// tablica z kategoriami / podkategoriami
				$cat = array();
				foreach( $item->folders->children() as $folder ){
					//$cat_name = $this->stdNameCache( (string)$folder->attributes()->category );
					//$subcat_name = $this->stdNameCache( (string)$folder->attributes()->subcategory );
					$cat_name = strtolower( (string)$folder->attributes()->category );
					$subcat_name = strtolower( (string)$folder->attributes()->subcategory );
					
					/* ========== KATEGORIE ==========  */
					if( $cat_name === 'textile & fashion' ){
						$cat_name = 'tekstylia';
						
					}
					elseif( $cat_name === 'vitality & wellness' ){
						$cat_name = 'uroda';
						
					}
					elseif( $cat_name === 'technology & mobile' ){
						$cat_name = 'elektronika';
						
					}
					elseif( $cat_name === 'writing' ){
						$cat_name = 'materiały piśmiennicze';
						
					}
					elseif( $cat_name === 'home & kitchen' ){
						$cat_name = 'dom';
						
					}
					elseif( $cat_name === 'leisure & sport' ){
						$cat_name = 'wypoczynek';
						
					}
					elseif( $cat_name === 'bags & travel' ){
						$cat_name = 'torby i plecaki';
						
					}
					elseif( $cat_name === 'keys & tools' ){
						$cat_name = 'narzędzia';
						
					}
					
					/* ========== //KATEGORIE ==========  */
					/* ========== PODKATEGORIE ==========  */
					
					if( $cat_name === 'tekstylia' ){
						if( $subcat_name === 'kurtki, płaszcze przeciwdeszczowe oraz ponczo' ){
							if( stripos( (string)$item->attributes()->name, 'kurtka' ) !== false ){
								$subcat_name = 'kurtki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'bluza' ) !== false ){
								$subcat_name = 'Bluzy, polary, softshelle';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'kamizelka' ) !== false ){
								$subcat_name = 'Kamizelki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'płaszcz' ) !== false ){
								$subcat_name = 'Płaszcze';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'ponczo' ) !== false or stripos( (string)$item->attributes()->name, 'poncho' ) !== false ){
								$subcat_name = 'Ponczo';
								
							}
							else{
								$subcat_name = 'Inne';
								
							}
							
						}
						elseif( $subcat_name === 'akcesoria polarowe' ){
							if( stripos( (string)$item->attributes()->name, 'kurtka' ) !== false ){
								$subcat_name = 'bluzy, polary, softshelle';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'szalik' ) !== false ){
								$subcat_name = 'szaliki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'rękawiczki' ) !== false ){
								$subcat_name = 'rękawiczki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'nausznik' ) !== false ){
								$subcat_name = 'nauszniki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'ogrzewacz' ) !== false ){
								$subcat_name = 'ogrzewacz na szyję';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'kurtka' ) !== false ){
								$subcat_name = 'kurtki';
								
							}
							else{
								$subcat_name = 'Inne';
								
							}
							
						}
						elseif( $subcat_name === 'odznaki' ){
							$cat_name = 'pinsy';
							
							if( stripos( (string)$item->attributes()->name, 'metal' ) !== false ){
								$subcat_name = 'metalowe';
								
							}
							else{
								$subcat_name = 'pozostałe';
								
							}
							
						}
						elseif( $subcat_name === 'czapki oraz czapki z daszkiem' ){
							if( stripos( (string)$item->description, ' zimowa' ) !== false ){
								$subcat_name = 'czapki zimowe';
								
							}
							elseif( stripos( (string)$item->description, ' przeciw' ) !== false or stripos( (string)$item->attributes()->name, ' daszek' ) !== false ){
								$subcat_name = 'daszki przeciwsłoneczne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' baseball' ) !== false or stripos( (string)$item->description, ' baseball' ) !== false or stripos( (string)$item->attributes()->name, ' dasz' ) !== false or stripos( (string)$item->description, ' dasz' ) !== false ){
								$subcat_name = 'czapki z daszkiem';
								
							}
							else{
								$subcat_name = 'inne';
								
							}
							
						}
						
					}
					elseif( $cat_name === 'uroda' ){
						if( stripos( (string)$item->description, 'ręcznik' ) !== false ){
							$subcat_name = 'Ręczniki';
							
						}
						elseif( stripos( (string)$item->description, 'szlafrok' ) !== false ){
							$subcat_name = 'Szlafroki';
							
						}
						elseif( $subcat_name === 'akcesoria łazienkowe' ){
							$cat_name = 'dom';
							$subcat_name = 'Łazienka';
							
						}
						elseif( $subcat_name === 'lusterko ze szczotką' ){
							$subcat_name = 'Lusterka';
							
						}
						elseif( $subcat_name === 'pudełeczka na tabletki oraz akcessoria medyczne' ){
							$cat_name = 'medyczne';
							$subcat_name = '';
							
						}
						elseif( $subcat_name === 'lusterka oraz zestawy make-up oraz manicure' ){
							if( stripos( (string)$item->attributes()->name, ' lusterk' ) !== false or stripos( (string)$item->description, ' lusterk' ) !== false ){
								$subcat_name = 'lusterka';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' manicure' ) !== false or stripos( (string)$item->attributes()->name, ' manikiur' ) !== false ){
								$subcat_name = 'Pielęgnacja dłoni';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' makijaż' ) !== false ){
								$subcat_name = 'Uroda i pielęgnacja';
								
							}
							else{
								$subcat_name = 'Inne';
								
							}
							
						}
						elseif( $subcat_name === 'akcesoria antystresowe' ){
							$cat_name = 'biuro';
							$subcat_name = 'antystresy';
							
						}
						
					}
					elseif( $cat_name === 'elektronika' ){
						if( $subcat_name === 'akcesoria do telefonów i tabletów' ){
							$cat_name = 'akcesoria do telefonów i tabletów';
							
							if( stripos( (string)$item->attributes()->name, ' etui' ) !== false or stripos( (string)$item->attributes()->name, ' uchwyt' ) !== false ){
								$subcat_name = 'etui i podstawki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' vr' ) !== false ){
								$subcat_name = 'Okulary wirtualnej rzeczywistości';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' ładowarka' ) !== false ){
								$subcat_name = 'Ładowarki i kable';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' stick' ) !== false or stripos( (string)$item->attributes()->name, ' ramię' ) !== false ){
								$subcat_name = 'akcesoria do telefonów';
								
							}
							else{
								$subcat_name = 'Akcesoria';
							}
							
						}
						elseif( $subcat_name === 'zegary ścienne oraz na biurko' ){
							$cat_name = 'zegary i zegarki';
							if( stripos( (string)$item->attributes()->name, ' ścienny' ) !== false or stripos( (string)$item->description, ' ścienny' ) !== false ){
								$subcat_name = 'zegary ścienne';
								
							}
							elseif( stripos( (string)$item->description, ' stołow' ) !== false or stripos( (string)$item->description, ' stół' ) !== false or stripos( (string)$item->description, ' biurko' ) !== false ){
								$subcat_name = 'zegary biurkowe';
								
							}
							else{
								$subcat_name = 'pozostałe';
								
							}
							
						}
						elseif( $subcat_name === 'akcesoria komputerowe' ){
							$cat_name = 'akcesoria komputerowe';
							
							if( stripos( (string)$item->attributes()->name, 'mysz' ) !== false ){
								$subcat_name = 'Mysz';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'klawiatura' ) !== false ){
								$subcat_name = 'Klawiatura';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' usb' ) !== false ){
								$subcat_name = 'Akcesoria USB';
								
							}
							else{
								$subcat_name = 'pozostałe';
								
							}
							
						}
						elseif( $subcat_name === 'pendrive-y a hub-y usb i czytniki kart pamięci' ){
							if( strpos( (string)$item->attributes()->name, 'drive' ) !== false or stripos( (string)$item->attributes()->name, 'pamięć' ) !== false or stripos( (string)$item->attributes()->name, 'GB' ) !== false ){
								$cat_name = 'pendrive';
								$subcat_name = 'pozostałe';
							}
							if( stripos( (string)$item->attributes()->name, 'hub' ) !== false or stripos( (string)$item->attributes()->name, 'rozdzielacz' ) !== false ){
								$subcat_name = 'adaptery i huby usb';
							}
							else{
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'głośniki, słuchawki' ){
							if( stripos( (string)$item->attributes()->name, 'słuchawk' ) !== false ){
								$subcat_name = 'słuchawki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'głoś' ) !== false ){
								$subcat_name = 'głośniki';
								
							}
							else{
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'zegarki na rękę' ){
							 $cat_name = 'zegary i zegarki';
							 $subcat_name = 'zegarki na rękę';
							
						}
						elseif( $subcat_name === 'power banki i ładowarki do telefonów' ){
							if( stripos( (string)$item->attributes()->name, 'samochod' ) !== false ){
								$subcat_name = 'Ładowarki samochodowe';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'bank' ) !== false or stripos( (string)$item->description, 'bank' ) !== false ){
								$cat_name = 'Power banki';
								$val = null;
						
								if( stripos( (string)$item->attributes()->name, ' mAh' ) !== false ){
									preg_match( "@ (\d+) mAh@i", (string)$item->attributes()->name, $match );
									$val = (int)$match[1];
									
								}
								elseif( stripos( (string)$item->description, ' mAh' ) !== false ){
									preg_match( "@ (\d+) mAh@i", (string)$item->description, $match );
									$val = (int)$match[1];
									
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
							elseif( stripos( (string)$item->attributes()->name, 'ładowar' ) !== false or stripos( (string)$item->description, 'ładowar' ) !== false ){
								$subcat_name = 'Ładowarki USB';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'brelok' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'inne';
								
							}
							else{
								$subcat_name = 'inne';
								
							}

						}
						elseif( $subcat_name === 'rękawiczki do smartphonów' ){
							$cat_name = 'akcesoria do telefonów i tabletów';
							$subcat_name = 'akcesoria do telefonów';
							
						}
						elseif( $subcat_name === 'stacje pogodowe' ){
							$cat_name = 'elektronika';
							
						}
						
					}
					elseif( $cat_name === 'materiały piśmiennicze' ){
						if( $subcat_name === 'długopisy metal-alu i drewniane' ){
							if( stripos( (string)$item->description, 'ekolog' ) !== false or stripos( (string)$item->description, 'bambus' ) !== false ){
								$cat_name = 'gadżety reklamowe';
								$subcat_name = 'eco gadżet';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'zestaw' ) !== false ){
								$subcat_name = 'zestawy piśmienne';
								
							}
							elseif( stripos( (string)$item->description, 'metal' ) !== false ){
								$subcat_name = 'długopisy metalowe';
								
							}
							elseif( stripos( (string)$item->description, 'alumin' ) !== false ){
								$subcat_name = 'długopisy aluminiowe';
								
							}
							else{
								$subcat_name = 'długopisy plastikowe';
								
							}
							
						}
						elseif( $subcat_name === 'zestawy piśmiennicze' ){
							$subcat_name = 'zestawy piśmienne';
							
						}
						elseif( $subcat_name === 'pudełka na długopisy' ){
							$subcat_name = 'etui';
							
						}
						elseif( $subcat_name === 'gumki i ostrzynki' ){
							if( stripos( (string)$item->attributes()->name, 'gumk' ) !== false ){
								$subcat_name = 'Gumki do mazania';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'temper' ) !== false or stripos( (string)$item->description, 'temper' ) !== false or stripos( (string)$item->attributes()->name, 'ostrzynk' ) !== false ){
								$subcat_name = 'Temperówki';
								
							}
							
						}
						elseif( $subcat_name === 'rysiki do ekranów dotykowych' ){
							$subcat_name = 'Touch peny';
							
						}
						
					}
					elseif( $cat_name === 'dom' ){
						if( $subcat_name === 'kubki i szklanki' ){
							$cat_name = 'do picia';
							
							if( stripos( (string)$item->attributes()->name, 'szklank' ) !== false ){
								$subcat_name = 'szklanki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'taca' ) !== false ){
								$cat_name = 'dom';
								$subcat_name = 'kuchnia';
								
							}
							else{
								$subcat_name = 'kubki';
								
							}
							
						}
						elseif( $subcat_name === 'akcesoria do win i otwieracze do butelek' ){
							if( stripos( (string)$item->attributes()->name, 'otwier' ) !== false or stripos( (string)$item->description, 'otwier' ) !== false ){
								$subcat_name = 'otwieracze do butelek';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' win' ) !== false ){
								$cat_name = 'vine club';
								
								if( stripos( (string)$item->attributes()->name, 'torba' ) !== false ){
									$subcat_name = 'opakowania';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'zestaw' ) !== false ){
									$subcat_name = 'zestawy';
									
								}
								else{
									$subcat_name = 'akcesoria';
									
								}
								
							}
							else{
								$cat_name = 'vine club';
								$subcat_name = 'akcesoria';
								
							}
							
						}
						//elseif( $subcat_name === 'świece i kadzidła' ){		nie chciało działać, możliwe że to jakiś problem z kodowaniem polskich znaków w plikach XML
						elseif( stripos( $subcat_name, 'kadzid' ) !== false ){
							$cat_name = 'uroda';
							$subcat_name = 'świece i zestawy do aromaterapii';
							
						}
						//elseif( $subcat_name === 'akcesoria do koktaili i palenia' ){
						elseif( stripos( $subcat_name, 'palenia' ) !== false ){
							if( stripos( (string)$item->attributes()->name, 'zapal' ) !== false ){
								$cat_name = 'dodatki';
								$subcat_name = 'zapalniczki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'schowek' ) !== false ){
								$cat_name = 'dodatki';
								$subcat_name = 'inne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'brel' ) !== false or stripos( (string)$item->description, 'brelok' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'breloki';
								
							}
							else{
								$cat_name = 'do picia';
								$subcat_name = 'inne';
							}
							
						}
						elseif( $subcat_name === 'dekoracje domowe' ){
							if( stripos( (string)$item->attributes()->name, 'doniczka' ) !== false ){
								$subcat_name = 'ogród';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'ramka' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'ramki na zdjęcia';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'wiec' ) !== false or stripos( (string)$item->attributes()->name, 'zapach' ) !== false ){
								$subcat_name = 'świece i aromaterapia';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'lamp' ) !== false ){
								$cat_name = 'narzędzia';
								$subcat_name = 'lampki';
								
							}
							else{
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'fartuchy i akcesoria do pieczenia' ){
							$subcat_name = 'akcesoria do pieczenia';
							
						}
						
					}
					elseif( $cat_name === 'wypoczynek' ){
						if( $subcat_name === 'akcesoria i gry plażowe' ){
							if( stripos( (string)$item->attributes()->name, 'piłka' ) !== false or stripos( (string)$item->attributes()->name, 'rakiet' ) !== false or stripos( (string)$item->attributes()->name, 'tenis' ) !== false or stripos( (string)$item->attributes()->name, 'badmin' ) !== false or stripos( (string)$item->attributes()->name, 'frisbee' ) !== false ){
								$subcat_name = 'gry i zabawy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'okulary' ) !== false ){
								$subcat_name = 'okulary przeciwsłoneczne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'termoizolacyjn' ) !== false ){
								$subcat_name = 'torby termoizolacyjne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'kubek' ) !== false ){
								$cat_name = 'do picia';
								
								if(  stripos( (string)$item->attributes()->name, 'ceram' ) !== false ){
									$subcat_name = 'kubki ceramiczne';
									
								}
								elseif(  stripos( (string)$item->attributes()->name, 'porcelan' ) !== false ){
									$subcat_name = 'kubki porcelanowe';
									
								}
								elseif(  stripos( (string)$item->attributes()->name, 'plastik' ) !== false ){
									$subcat_name = 'kubki plastikowe';
									
								}
								elseif(  stripos( (string)$item->attributes()->name, 'termicz' ) !== false ){
									$subcat_name = 'kubki termiczne';
									
								}
								elseif(  stripos( (string)$item->attributes()->name, 'szkl' ) !== false ){
									$subcat_name = 'kubki szklane';
									
								}
								else{
									$subcat_name = 'kubki';
								}
								
							}
							else{
								$subcat_name = 'akcesoria plażowe';
								
							}
							
						}
						elseif( $subcat_name === 'zestawy piknikowe, torby termoizolacyjne' ){
							if( stripos( (string)$item->attributes()->name, 'term' ) !== false or stripos( (string)$item->attributes()->name, 'chłodz' ) !== false ){
								$subcat_name = 'torby termoizolacyjne';
								
							}
							else{
								$subcat_name = 'akcesoria plażowe';
								
							}
							
						}
						elseif( $subcat_name === 'termosy i kubki' ){
							$cat_name = 'do picia';
							
							if( stripos( (string)$item->attributes()->name, 'kubek' ) !== false ){
								$cat_name = 'do picia';
								
								if( stripos( (string)$item->attributes()->name, 'ceram' ) !== false ){
									$subcat_name = 'kubki ceramiczne';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'porcelan' ) !== false ){
									$subcat_name = 'kubki porcelanowe';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'plastik' ) !== false ){
									$subcat_name = 'kubki plastikowe';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'termicz' ) !== false ){
									$subcat_name = 'kubki termiczne';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'szklan' ) !== false ){
									$subcat_name = 'kubki szklane';
									
								}
								else{
									$subcat_name = 'kubki';
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'termos' ) !== false ){
								$subcat_name = 'termosy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'piersi' ) !== false ){
								$subcat_name = 'Piersiówki';
								
							}
							elseif(  stripos( (string)$item->attributes()->name, 'butelk' ) !== false ){
								$subcat_name = 'butelki';
								
							}
							else{
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'japonki' ){
							$subcat_name = 'akcesoria plażowe';
							
						}
						elseif( $subcat_name === 'okulary słoneczne' ){
							$subcat_name = 'okulary przeciwsłoneczne';
							
						}
						elseif( $subcat_name === 'butelki sportowe' ){
							$cat_name = 'do picia';
							$subcat_name = 'butelki';
							
						}
						elseif( $subcat_name === 'akcesoria sportowe i na zabawę' ){
							if( stripos( (string)$item->attributes()->name, 'odblask' ) !== false ){
								$cat_name = 'odblaski';
								
								if( stripos( (string)$item->attributes()->name, 'opaska' ) !== false ){
									$subcat_name = 'opaski';
									
								}
								else{
									$subcat_name = 'inne';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'piłka' ) !== false or stripos( (string)$item->attributes()->name, 'tee' ) !== false ){
								$cat_name = 'wypoczynek';
								$subcat_name = 'gry i zabawy';
								
							}
							
						}
						elseif( stripos( $subcat_name, 'rowerzyst') !== false ){
							$cat_name = 'sport i rekreacja';
							$subcat_name = 'akcesoria dla rowerzystów';
							
						}
						elseif( stripos( $subcat_name, 'koce polarowe') !== false ){
							$subcat_name = 'koce';
							
						}
						elseif( $subcat_name === 'lornetki i kompasy' ){
							if( stripos( (string)$item->attributes()->name, 'kompas' ) !== false or stripos( (string)$item->description, 'kompas' ) !== false ){
								$subcat_name = 'kompasy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'bino' ) !== false or stripos( (string)$item->attributes()->name, 'lornet' ) !== false ){
								$subcat_name = 'lornetki';
								
							}
							else{
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'zestawy do bbq' ){
							$subcat_name = 'grill';
							
						}
						
					}
					elseif( $cat_name === 'be creative' ){
						if( $subcat_name === 'be creative custom' ){
							if( stripos( (string)$item->attributes()->name, 'odznaka' ) !== false ){
								$cat_name = 'pinsy';
								$subcat_name = 'pozostałe';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'wentylator' ) !== false ){
								$cat_name = 'wypoczynek';
								$subcat_name = 'akcesoria plażowe';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'balon' ) !== false or stripos( (string)$item->description, 'balon' ) !== false ){
								$cat_name = 'wypoczynek';
								$subcat_name = 'gry i zabawy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'magnes' ) !== false ){
								$cat_name = 'dom';
								$subcat_name = 'magnesy na lodówkę';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'zakup' ) !== false ){
								$cat_name = 'torby i plecaki';
								$subcat_name = 'na zakupy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'brelok' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'breloki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'portfel' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'portfele';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'okular' ) !== false ){
								$cat_name = 'wypoczynek';
								$subcat_name = 'okulary';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'japon' ) !== false ){
								$cat_name = 'wypoczynek';
								$subcat_name = 'akcesoria plażowe';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'kubek' ) !== false ){
								$cat_name = 'do picia';
								
								if( stripos( (string)$item->attributes()->name, 'termicz' ) !== false ){
									$subcat_name = 'kubki termiczne';
									
								}
								else{
									$subcat_name = 'inne';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'wina' ) !== false ){
								$cat_name = 'vine club';
								$subcat_name = 'opakowania';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'linij' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'linijki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'zawieszka' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'inne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'creaclip' ) !== false ){
								$cat_name = 'materiały piśmiennicze';
								$subcat_name = 'długopisy plastikowe';
								
							}
							
						}
						elseif( $subcat_name === 'be creative print' ){
							if( stripos( (string)$item->attributes()->name, 'ręcznik' ) !== false ){
								$cat_name = 'uroda';
								$subcat_name = 'ręczniki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'smycz' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'smycze';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'zegar' ) !== false ){
								$cat_name = 'zegary i zegarki';
								
								if( stripos( (string)$item->description, 'stół' ) !== false ){
									$subcat_name = 'zegary biurkowe';
									
								}
								elseif( stripos( (string)$item->description, 'ścienn' ) !== false ){
									$subcat_name = 'zegary ścienne';
									
								}
								else{
									$subcat_name = 'pozostałe';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'brelok' ) !== false ){
								$cat_name = 'breloki';
								
								if( stripos( (string)$item->description, 'plastik' ) !== false ){
									$subcat_name = 'plastikowe';
									
								}
								else{
									$subcat_name = 'breloki';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'lusterko' ) !== false ){
								$cat_name = 'uroda';
								$subcat_name = 'lusterka';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'portfel' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'portfele';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'bidon' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'termosy i bidony';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'magnes' ) !== false ){
								$cat_name = 'dom';
								$subcat_name = 'magnesy na lodówkę';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'tabletk' ) !== false ){
								$cat_name = 'medyczne';
								$subcat_name = '';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'ramka' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'ramki na zdjęcia';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'przeciwsłoneczne' ) !== false ){
								$cat_name = 'wypoczynek';
								$subcat_name = 'okulary przeciwsłoneczne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'okulary' ) !== false ){
								$cat_name = 'wypoczynek';
								$subcat_name = 'okulary';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'daszkiem' ) !== false ){
								$cat_name = 'tekstylia';
								$subcat_name = 'czapki z daszkiem';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'butelka' ) !== false ){
								$cat_name = 'do picia';
								$subcat_name = 'butelki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'słuchawki' ) !== false ){
								$cat_name = 'elektronika';
								$subcat_name = 'słuchawki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'kubek' ) !== false ){
								$cat_name = 'do picia';
								
								if( stripos( (string)$item->description, 'ceram' ) !== false ){
									$subcat_name = 'kubki ceramiczne';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'term' ) !== false ){
									$subcat_name = 'kubki termiczne';
									
								}
								elseif( stripos( (string)$item->description, 'szkl' ) !== false ){
									$subcat_name = 'kubki szklane';
									
								}
								else{
									$subcat_name = 'kubki inne';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'piekar' ) !== false ){
								$cat_name = 'dom';
								$subcat_name  = 'akcesoria do pieczenia';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'rysik' ) !== false or stripos( (string)$item->attributes()->name, 'smart' ) !== false ){
								$cat_name = 'akcesoria do telefonów i tabletów';
								$subcat_name  = 'akcesoria';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'opaska' ) !== false ){
								$cat_name = 'dodatki';
								$subcat_name  = 'opaski';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'samochod' ) !== false ){
								$cat_name = 'narzędzia';
								$subcat_name  = 'samochód';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'osłona' ) !== false ){
								$cat_name = 'dodatki';
								$subcat_name  = 'inne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'czyścik' ) !== false ){
								$cat_name = 'akcesoria do telefonów i tabletów';
								$subcat_name  = 'akcesoria';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'frisbee' ) !== false ){
								$cat_name = 'wypoczynek';
								$subcat_name  = 'gry i zabawy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'skarbon' ) !== false ){
								$cat_name = 'dodatki';
								$subcat_name  = 'skarbonki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'skrob' ) !== false ){
								$cat_name = 'dodatki';
								$subcat_name  = 'skrobaczki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'pins' ) !== false or stripos( (string)$item->attributes()->name, 'metal' ) !== false ){
								$cat_name = 'pinsy';
								$subcat_name  = 'metalowe';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'pins' ) !== false ){
								$cat_name = 'pinsy';
								$subcat_name  = 'metalowe';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'pass' ) !== false or stripos( (string)$item->attributes()->name, 'torebki' ) !== false ){
								$cat_name = 'dodatki';
								$subcat_name  = 'inne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'bank' ) !== false or stripos( (string)$item->description, 'bank' ) !== false ){
								$val = null;
						
								if( stripos( (string)$item->attributes()->name, ' mAh' ) !== false ){
									preg_match( "@ (\d+) mAh@i", (string)$item->attributes()->name, $match );
									$val = (int)$match[1];
									
								}
								elseif( stripos( (string)$item->description, ' mAh' ) !== false ){
									preg_match( "@ (\d+) mAh@i", (string)$item->description, $match );
									$val = (int)$match[1];
									
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
							elseif( stripos( (string)$item->attributes()->name, 'etui' ) !== false or stripos( (string)$item->attributes()->name, 'pokrowiec' ) !== false ){
								$cat_name = 'Akcesoria do telefonów i tabletów';
								$subcat_name = 'etui i podstawki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'długopis' ) !== false ){
								$cat_name = 'materiały piśmiennicze';
								
								if( stripos(  (string)$item->description, 'stojak' ) !== false ){
									$subcat_name = 'inne';
									
								}
								else{
									$subcat_name = 'długopisy plastikowe';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'termos' ) !== false ){
								$cat_name = 'do picia';
								$subcat_name = 'termosy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'telefon' ) !== false ){
								if( stripos( (string)$item->attributes()->name, 'uchwyt' ) !== false ){
									$cat_name = 'Akcesoria do telefonów i tabletów';
									$subcat_name = 'Akcesoria do telefonów';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'podkład' ) !== false ){
								if( stripos( (string)$item->attributes()->name, 'mysz' ) !== false ){
									$cat_name = 'akcesoria komputerowe';
									$subcat_name = 'mysz';
									
								}
								else{
									$cat_name = 'biuro';
									$subcat_name = 'podkładki';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'wieszak' ) !== false ){
								$cat_name = 'torby i plecaki';
								$subcat_name = 'inne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'miar' ) !== false ){
								$cat_name = 'narzędzia';
								$subcat_name = 'miarki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'ścier' ) !== false or stripos( (string)$item->attributes()->name, 'zakładka' ) !== false or stripos( (string)$item->attributes()->name, 'cukierki' ) !== false ){
								$cat_name = 'dodatki';
								$subcat_name = 'inne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'czasomierz' ) !== false or stripos( (string)$item->attributes()->name, 'podstaw' ) !== false or stripos( (string)$item->attributes()->name, 'otwieracz' ) !== false or stripos( (string)$item->attributes()->name, 'czyszcz' ) !== false ){
								$cat_name = 'dom';
								$subcat_name = 'akcesoria kuchenne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'torba' ) !== false ){
								$cat_name = 'torby i plecaki';
								
								if( stripos( (string)$item->attributes()->name, 'zakupy' ) !== false ){
									$subcat_name = 'na zakupy';
									
								}
								else{
									$subcat_name = 'inne';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'klip' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'klipy do notatek';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'usb' ) !== false or stripos( (string)$item->attributes()->name, 'pendrive' ) !== false ){
								if( stripos( (string)$item->attributes()->name, 'ładowark' ) !== false ){
									$cat_name = 'elektronika';
									$subcat_name = 'ładowarki usb';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'gb' ) !== false or stripos( (string)$item->attributes()->name, 'drive' ) !== false ){
									$cat_name = 'pamięci usb';
									$subcat_name = 'pozostałe';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'puzzle' ) !== false or stripos( (string)$item->attributes()->name, 'koszyków' ) !== false or stripos( (string)$item->attributes()->name, 'gra' ) !== false ){
								$cat_name = 'wypoczynek';
								$subcat_name = 'gry i zabawy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'bransol' ) !== false or stripos( (string)$item->attributes()->name, 'kolczyk' ) !== false or stripos( (string)$item->attributes()->name, 'pierście' ) !== false ){
								$cat_name = 'biżuteria i akcesoria';
								$subcat_name = '';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'krawat' ) !== false or stripos( (string)$item->attributes()->name, 'kapelusz' ) !== false or stripos( (string)$item->attributes()->name, 'pasek' ) !== false or stripos( (string)$item->attributes()->name, 'mankiet' ) !== false ){
								$cat_name = 'tekstylia';
								
								if( stripos( (string)$item->attributes()->name, 'kapelusz' ) !== false ){
									$subcat_name = 'kapelusze';
								}
								elseif( stripos( (string)$item->attributes()->name, 'krawat' ) !== false ){
									$subcat_name = 'krawaty i apaszki';
								}
								else{
									$subcat_name = 'inne';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'wizytownik' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'wizytowniki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'grzebień' ) !== false ){
								$cat_name = 'uroda';
								$subcat_name = 'akcesoria kosmetyczne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'zakreślacz' ) !== false ){
								$cat_name = 'materiały piśmiennicze';
								$subcat_name = 'zakreślacze';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'na butelkę' ) !== false ){
								$cat_name = 'do picia';
								$subcat_name = 'inne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'czapka' ) !== false ){
								$cat_name = 'tekstylia';
								
								if( stripos( (string)$item->attributes()->name, 'zimow' ) !== false ){
									$subcat_name = 'czapki zimowe';
									
								}
								else{
									$subcat_name = 'inne';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'bagaż' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'akcesoria podróżne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'zakład' ) !== false or stripos( (string)$item->description, 'zakład' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'akcesoria biurowe';
								
							}
							else{
								$cat_name = 'dodatki';
								$subcat_name = 'inne';
								
							}
							
						}
						
					}
					elseif( $cat_name === 'office & business' ){
						if( $subcat_name === 'smycze' ){
							$cat_name = 'podróż';
							
						}
						elseif( $subcat_name === 'teczki i podkładki' ){
							if( stripos( (string)$item->attributes()->name, 'pod' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'podkładki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'teczk' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'teczki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'folder' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'foldery';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'notatnik' ) !== false or stripos( (string)$item->attributes()->name, 'organizer' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'notatniki i notesy';
								
							}
							else{
								$cat_name = 'biuro';
								$subcat_name = 'akcesoria biurowe';
								
							}
							
						}
						elseif( $subcat_name === 'linijki i zakładki' ){
							if( stripos( (string)$item->attributes()->name, 'linijka' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'linijki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'zakład' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'akcesoria biurowe';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'karte' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'karteczki samoprzylepne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'podstaw' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'podkładki';
								
							}
							else{
								$cat_name = 'biuro';
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'szmatki do czyszczenia okularów' ){
							$cat_name = 'dodatki';
							$subcat_name = 'inne';
							
						}
						elseif( $subcat_name === 'notatniki i spinacze' ){
							if( stripos( (string)$item->attributes()->name, 'notatnik' ) !== false or stripos( (string)$item->description, 'notatnik' ) !== false or stripos( (string)$item->attributes()->name, 'notes' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'notatniki i notesy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'karte' ) !== false or stripos( (string)$item->description, 'karte' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'karteczki samoprzylepne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'klip' ) !== false or stripos( (string)$item->description, 'klip' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'klipy do notatek';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'notebook' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'notatniki i notesy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'zakład' ) !== false or stripos( (string)$item->attributes()->name, 'taśm' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'akcesoria biurowe';
								
							}
							elseif( stripos( (string)$item->description, 'długopis' ) !== false ){
								$cat_name = 'materiały piśmiennicze';
								$subcat_name = 'długopisy plastikowe';
								
							}
							else{
								$cat_name = 'dodatki';
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'uchwyty na długopisy' ){
							$cat_name = 'biuro';
							$subcat_name = 'pojemniki na długopisy';
							
						}
						elseif( $subcat_name === 'materiały biurowe' ){
							$cat_name = 'biuro';
							$subcat_name = 'akcesoria biurowe';
							
						}
						elseif( $subcat_name === 'kalkulatory' ){
							$cat_name = 'elektronika';
							$subcat_name = 'kalkulatory';
							
						}
						elseif( $subcat_name === 'produkty szklane pod 3d' ){
							$cat_name = 'dodatki';
							$subcat_name = 'inne';
							
						}
						elseif( $subcat_name === 'wizytowniki' ){
							$cat_name = 'biuro';
							$subcat_name = 'wizytowniki';
							
						}
						
						
					}
					elseif( $cat_name === 'torby i plecaki' ){
						if( $subcat_name === 'torby na zakupy' ){
							$subcat_name = 'na zakupy';
							
						}
						elseif( $subcat_name === 'torby papierowe' ){
							$subcat_name = 'papierowe';
							
						}
						elseif( $subcat_name === 'portfele i etui na karty' ){
							if( stripos( (string)$item->attributes()->name, 'portfel' ) !== false or stripos( (string)$item->attributes()->name, 'portofel' ) !== false  or stripos( (string)$item->attributes()->name, 'potfel' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'portfele';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' kart' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'etui na karty';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' portmon' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'portmonetki';
								
							}
							elseif( stripos( (string)$item->description, ' telefon' ) !== false ){
								$cat_name = 'akcesoria do telefonów i tabletów';
								$subcat_name = 'akcesoria do telefonów';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' toreb' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'torebki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, ' wizytow' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'wizytowniki';
								
							}
							else{
								$cat_name = 'biuro';
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'akcesoria podróżne' ){
							if( stripos( (string)$item->attributes()->name, 'torebka' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'torebki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'bagaż' ) !== false ){
								$cat_name = 'podróż';
								$subcat_name = 'akcesoria podróżne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'adapter' ) !== false ){
								$cat_name = 'elektronika';
								$subcat_name = 'adaptery';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'wieszak' ) !== false ){
								$cat_name = 'dodatki';
								$subcat_name = 'inne';
								
							}
							else{
								$cat_name = 'podróż';
								$subcat_name = 'akcesoria podróżne';
								
							}
							
						}
						elseif( $subcat_name === 'torby podróżne, na kółkach i sportowe' ){
							if( stripos( (string)$item->attributes()->name, 'torebka' ) !== false ){
								$cat_name = 'podróz';
								$subcat_name = 'torebki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'torba' ) !== false ){
								if( stripos( (string)$item->attributes()->name, 'plaż' ) !== false ){
									$cat_name = 'torby i plecaki';
									$subcat_name = 'torby plażowe';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'sznur' ) !== false ){
									$cat_name = 'torby i plecaki';
									$subcat_name = 'worki ze sznurkiem';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'ramię' ) !== false ){
									$cat_name = 'torby i plecaki';
									$subcat_name = 'torby na ramię';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'sport' ) !== false or stripos( (string)$item->description, 'sport' ) !== false or stripos( (string)$item->attributes()->name, 'kółk' ) !== false ){
									$cat_name = 'podróż';
									$subcat_name = 'torby podróżne i sportowe';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'zakup' ) !== false ){
									$cat_name = 'torby i torebki';
									$subcat_name = 'na zakupy';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'ręcznik' ) !== false or stripos( (string)$item->attributes()->name, 'wodoodpor' ) !== false ){
									$cat_name = 'wypoczynek';
									$subcat_name = 'akcesoria plażowe';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'odblask' ) !== false ){
									$cat_name = 'odblaski';
									$subcat_name = 'inne';
									
								}
								else{
									$cat_name = 'torby i plecaki';
									$subcat_name = 'inne';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'worek' ) !== false or stripos( (string)$item->description, 'żeglar' ) !== false ){
								$cat_name = 'torby i plecaki';
								$subcat_name = 'worki ze sznurkiem';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'plecak' ) !== false or stripos( (string)$item->attributes()->name, 'pleacak' ) !== false ){
								$cat_name = 'torby i plecaki';
								$subcat_name = 'plecaki';
								
							}
							else{
								$cat_name = 'podróż';
								$subcat_name = 'akcesoria podróżne';
							}
							
						}
						elseif( $subcat_name === 'plecaki' ){
							if( stripos( (string)$item->attributes()->name, 'plecak' ) !== false ){
								$cat_name = 'torby i plecaki';
								$subcat_name = 'plecaki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'worek' ) !== false ){
								if( stripos( (string)$item->attributes()->name, 'sznur' ) !== false ){
									$cat_name = 'torby i plecaki';
									$subcat_name = 'worki ze sznurkiem';
									
								}
								else{
									$cat_name = 'torby i plecaki';
									$subcat_name = 'inne';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'termoizolacyjn' ) !== false ){
								$cat_name = 'torby i plecaki';
								$subcat_name = 'termoizolacyjne';
								
							}
							else{
								$cat_name = 'torby i plecaki';
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'torby na dokumenty, na ramię i na laptopa' ){
							if( stripos( (string)$item->attributes()->name, 'torba' ) !== false ){
								if( stripos( (string)$item->attributes()->name, 'laptop' ) !== false ){
									$cat_name = 'torby i plecaki';
									$subcat_name = 'na laptopa';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'dokument' ) !== false ){
									$cat_name = 'torby i plecaki';
									$subcat_name = 'na dokumenty';
									
								}
								elseif( stripos( (string)$item->attributes()->name, 'ramię' ) !== false or stripos( (string)$item->description, 'ramię' ) !== false ){
									$cat_name = 'torby i plecaki';
									$subcat_name = 'torby na ramię';
									
								}
								else{
									$cat_name = 'torby i plecaki';
									$subcat_name = 'inne';
									
								}
							
							}
							elseif( stripos( (string)$item->attributes()->name, 'folder' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'foldery';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'teczka' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'teczki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'aktówk' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'aktówki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'plecak' ) !== false ){
								$cat_name = 'torby i plecaki';
								$subcat_name = 'plecak';
								
							}
							else{
								$cat_name = 'torby i plecaki';
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'torby na plażę' ){
							if( stripos( (string)$item->attributes()->name, 'plaż' ) !== false ){
								$cat_name = 'torby i plecaki';
								$subcat_name = 'torby plażowe';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'zakup' ) !== false ){
								$cat_name = 'torby i plecaki';
								$subcat_name = 'na zakupy';
								
							}
							else{
								$cat_name = 'torby i plecaki';
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'parasole' ){
							$cat_name = 'przeciwdeszczowe';
							
						}
						elseif( $subcat_name === 'wieszaki na teczki' ){
							$cat_name = 'dodatki';
							$subcat_name = 'inne';
							
						}
						
					}
					elseif( $cat_name === 'narzędzia' ){
						if( $subcat_name === 'breloki' ){
							if( stripos( (string)$item->description, 'miś' ) !== false ){
								$cat_name = 'pluszaki i maskotki';
								$subcat_name = 'breloki';
								
							}
							elseif( stripos( (string)$item->description, 'alumin' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'aluminiowe';
								
							}
							elseif( stripos( (string)$item->description, 'plastik' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'plastikowe';
								
							}
							elseif( stripos( (string)$item->description, 'drewn' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'drewniane';
								
							}
							elseif( stripos( (string)$item->description, 'metal' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'metalowe';
								
							}
							elseif( stripos( (string)$item->description, 'akryl' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'akrylowe';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'antystres' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'antystresy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'sklep' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'breloki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'pojemnik' ) !== false or stripos( (string)$item->attributes()->name, 'usb' ) !== false or stripos( (string)$item->attributes()->name, 'taśm' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'wielofunkcyjne';
								
							}
							elseif( stripos( (string)$item->description, 'metal' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'metalowe';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'odblask' ) !== false ){
								$cat_name = 'odblaski';
								$subcat_name = 'breloki';
								
							}
							else{
								$cat_name = 'breloki';
								$subcat_name = 'breloki';
								
							}
							
						}
						elseif( $subcat_name === 'akcesoria samochodowe' ){
							$subcat_name = 'samochód';
							
						}
						elseif( $subcat_name === 'monety do wózków zakupowych' ){
							if( stripos( (string)$item->description, 'alumin' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'aluminiowe';
								
							}
							elseif( stripos( (string)$item->description, 'plastik' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'plastikowe';
								
							}
							elseif( stripos( (string)$item->description, 'drewn' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'drewniane';
								
							}
							elseif( stripos( (string)$item->description, 'metal' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'metalowe';
								
							}
							elseif( stripos( (string)$item->description, 'akryl' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'akrylowe';
								
							}
							else{
								$cat_name = 'breloki';
								$subcat_name = 'breloki';
								
							}
							
						}
						elseif( $subcat_name === 'latarki' ){
							if( stripos( (string)$item->attributes()->name, 'latar' ) !== false or stripos( (string)$item->description, 'latar' ) !== false ){
								$subcat_name = 'latarki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'lamp' ) !== false or stripos( (string)$item->description, 'lamp' ) !== false ){
								$subcat_name = 'lampki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'śrubo' ) !== false ){
								$subcat_name = 'śrubokręty';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'brelok' ) !== false or stripos( (string)$item->description, 'brelok' ) !== false ){
								if( stripos( (string)$item->description, 'alumin' ) !== false ){
									$cat_name = 'breloki';
									$subcat_name = 'aluminiowe';
									
								}
								elseif( stripos( (string)$item->description, 'plastik' ) !== false ){
									$cat_name = 'breloki';
									$subcat_name = 'plastikowe';
									
								}
								elseif( stripos( (string)$item->description, 'drewn' ) !== false ){
									$cat_name = 'breloki';
									$subcat_name = 'drewniane';
									
								}
								elseif( stripos( (string)$item->description, 'metal' ) !== false ){
									$cat_name = 'breloki';
									$subcat_name = 'metalowe';
									
								}
								elseif( stripos( (string)$item->description, 'akryl' ) !== false ){
									$cat_name = 'breloki';
									$subcat_name = 'akrylowe';
									
								}
								else{
									$cat_name = 'breloki';
									$subcat_name = 'breloki';
									
								}
								
							}
							elseif( stripos( (string)$item->description, 'otwieracz' ) !== false && stripos( (string)$item->description, 'kółk' ) !== false ){
								$cat_name = 'breloki';
								$subcat_name = 'otwieracze do butelek';
								
							}
							else{
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'produkty fluoroscencyjne' ){
							$cat_name = 'odblaski';
							
							if( stripos( (string)$item->attributes()->name, 'kamizelka' ) !== false ){
								$subcat_name = 'kamizelki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'brelok' ) !== false ){
								$subcat_name = 'breloki';
								
							}
							else{
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'kieszonkowe noże, narzędzia i miarki' ){
							if( stripos( (string)$item->attributes()->name, 'wielofunk' ) !== false or stripos( (string)$item->description, 'wielofunk' ) !== false ){
								$subcat_name =  'wielofunkcyjne';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'scyzor' ) !== false ){
								$subcat_name =  'scyzoryki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'miar' ) !== false or stripos( (string)$item->attributes()->name, 'mier' ) !== false ){
								$subcat_name =  'miary i miarki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'nóż' ) !== false ){
								$subcat_name =  'noże i nożyki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'śrubo' ) !== false ){
								$subcat_name =  'śrubokręty';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'zestaw' ) !== false ){
								$subcat_name =  'zestawy';
								
							}
							else{
								$subcat_name = 'inne';
								
							}
							
						}
						
					}
					elseif( $cat_name === 'kids & toys' ){
						if( in_array( $subcat_name, array( 'zabawki i gry', 'jo-jo i magiczne puzzle' ) ) ){
							if( stripos( (string)$item->attributes()->name, 'koszykówk' ) !== false or 
							stripos( (string)$item->attributes()->name, 'balony' ) !== false or 
							stripos( (string)$item->attributes()->name, 'jo-jo' ) !== false or 
							stripos( (string)$item->attributes()->name, 'yo-yo' ) !== false or 
							stripos( (string)$item->attributes()->name, 'puzzle' ) !== false or 
							stripos( (string)$item->attributes()->name, 'puzle' ) !== false or 
							stripos( (string)$item->attributes()->name, 'baniek' ) !== false or 
							stripos( (string)$item->attributes()->name, 'zabaw' ) !== false or 
							stripos( (string)$item->attributes()->name, 'samolot' ) !== false or 
							stripos( (string)$item->attributes()->name, 'gra' ) !== false or 
							stripos( (string)$item->attributes()->name, 'grzech' ) !== false or 
							stripos( (string)$item->attributes()->name, 'poker' ) !== false or 
							stripos( (string)$item->attributes()->name, 'zmywal' ) !== false ){
								$cat_name = 'wypoczynek';
								$subcat_name = 'gry i zabawy';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'skarbon' ) !== false ){
								$cat_name = 'dodatki';
								$subcat_name = 'skarbonki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'magnes' ) !== false ){
								$cat_name = 'dom';
								$subcat_name = 'magnesy na lodówkę';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'brelok' ) !== false ){
								if( stripos( (string)$item->description, 'miś' ) !== false ){
									$cat_name = 'pluszaki i maskotki';
									$subcat_name = 'breloki';
									
								}
								elseif( stripos( (string)$item->description, 'drewn' ) !== false ){
									$cat_name = 'breloki';
									$subcat_name = 'drewniane';
									
								}
								else{
									$cat_name = 'breloki';
									$subcat_name = 'breloki';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'misiu' ) !== false or stripos( (string)$item->attributes()->name, 'miś' ) !== false ){
								$cat_name = 'pluszaki i maskotki';
								$subcat_name = 'misie';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'plusz' ) !== false ){
								if( stripos( (string)$item->attributes()->name, 'krowa' ) !== false or stripos( (string)$item->attributes()->name, 'kaczka' ) !== false ){
									$cat_name = 'pluszaki i maskotki';
									$subcat_name = 'domowe';
									
								}
								else{
									$cat_name = 'pluszaki i maskotki';
									$subcat_name = 'dzikie';
									
								}
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'wirtualn' ) !== false ){
								$cat_name    = 'akcesoria do telefonów i tabletów';
								$subcat_name = 'okulary wirtualnej rzeczywistości';
								
							}
							else{
								$cat_name = 'dodatki';
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'linijki i ostrzynki' ){
							if( stripos( (string)$item->attributes()->name, 'temper' ) !== false or 
							stripos( (string)$item->description, 'temper' ) !== false or 
							stripos( (string)$item->attributes()->name, 'ostrzyn' ) !== false ){
								$cat_name = 'materiały piśmiennicze';
								$subcat_name = 'temperówki';
								
							}
							elseif( stripos( (string)$item->attributes()->name, 'linij' ) !== false ){
								$cat_name = 'biuro';
								$subcat_name = 'linijki';
								
							}
							else{
								$cat_name = 'dodatki';
								$subcat_name = 'inne';
								
							}
							
						}
						elseif( $subcat_name === 'skarbonki' ){
							$cat_name = 'dodatki';
							$subcat_name = 'skarbonki';
							
						}
						
					}
					elseif( $cat_name === 'xmas' ){
						$cat_name = 'świąteczne';
						$subcat_name =  '';
						
					}
					elseif( $cat_name === 'indywidualny produkt' ){
						if( stripos( (string)$item->attributes()->name, 'brelok' ) !== false ){
							$cat_name = 'breloki';
							$subcat_name = 'breloki';
							
						}
						elseif( stripos( (string)$item->attributes()->name, 'organizer' ) !== false ){
							$cat_name = 'biuro';
							$subcat_name = 'notatniki i notesy';
							
						}
						else{
							$cat_name = 'dodatki';
							$subcat_name =  'inne';
							
						}
						
					}
					
					// (string)$item->attributes()->name
					// (string)$item->description
					
					/* ========== //PODKATEGORIE ==========  */
					/* ========== FILTROWANIE ==========  */
					if( stripos( (string)$item->description, 'Antonio Miro' ) !== false ){
						$cat_name = 'Cool Brands';
						$subcat_name = 'Antonio miro';
						
					}
					elseif( stripos( (string)$item->description, 'André Philippe' ) !== false ){
						$cat_name = 'Cool Brands';
						$subcat_name = 'André Philippe';
						
					}
					elseif( stripos( (string)$item->description, 'Alexluca' ) !== false ){
						$cat_name = 'Cool Brands';
						$subcat_name = 'Alexluca';
						
					}
					elseif( stripos( (string)$item->attributes()->name, 'kielisz' ) !== false ){
						$cat_name = 'do picia';
						$subcat_name = 'kieliszki';
						
					}
					
					
					/* ========== /FILTROWANIE ==========  */
					
					$cat_name_slug = $this->stdNameCache( $cat_name );
					$subcat_name_slug = $cat_name_slug . "-" . $this->stdNameCache( $subcat_name );
					
					//$cat[ $cat_name ][ $subcat_name ] = array();
					$cat[ $cat_name_slug ][ $subcat_name_slug ] = array();
					
					// $this->debugger( $cat_name, $subcat_name );
					
				}
				
				// tablica z wymiarami
				$dim = array();
				foreach( $item->properties->children() as $property ){
					if( strpos( (string)$property->attributes()->name, 'WYM' ) === 0 ){
						$dim[] = (string)$property->attributes()->value;
						
					}
					
				}
				
				// znakowanie i waga
				// $mark_array = array();
				// $mark_size = array();
				// $mark_type = array();
				// $weight = null;
				$mark_text = '';
				foreach( $item->properties->children() as $property ){
					if( strpos( (string)$property->attributes()->name, 'METODA' ) === 0 ){
						$mark_text = (string)$property->attributes()->value;
						// $pattern = "~([\w\d]+) \((?:.*?, )?(.*?MM)\)~";
						/* $pattern = "~([\w\-]+)\s+\((?:.*?([\w×ø]+ MM)?)?\)~";
						preg_match_all( $pattern, (string)$property->attributes()->value, $match );
						for( $i=0; $i<count( $match[0] ); $i++ ){
							$type = $match[1][ $i ];
							$size = $match[2][ $i ];
							if( !empty( $type ) ){
								// $mark[ $size ][] = $type;
								$mark_array[ $size ][] = $type;
								$mark_size[] = $size;
								$mark_type[] = $type;
								
							}
							
						} */
						
					}
					if( strpos( (string)$property->attributes()->name, 'WAGA' ) === 0 ){
						$weight = (float)$property->attributes()->value;
						
					}
					
				}
				
				$price_netto = (float)str_replace( ",", ".", $item->attributes()->price );
				
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
						'ID' => (string)$item->attributes()->no,
						'NAME' => (string)$item->attributes()->name,
						'DSCR' => (string)$item->description,
						'IMG' => $img,
						'CAT' => $cat,
						'DIM' => implode( " x ", $dim ),
						// 'MARK' => $mark_array,
						// 'MARKSIZE' => $mark_size,
						// 'MARKTYPE' => $mark_type,
						'MARK_TEXT' => $mark_text,
						'INSTOCK' => (int)$item->stocks[0]->attributes()->value,
						// 'MATTER' => 'brak danych',
						// 'COLOR' => 'brak danych',
						// 'COUNTRY' => 'brak danych',
						'MARKCOLORS' => 1,
						'PRICE' => array(
							'NETTO' => $price_netto,
							'BRUTTO' => $this->price2brutto( $price_netto ),
							'CURRENCY' => (string)$item->attributes()->currency,
						),
						'MODEL' => 'brak danych',
						'WEIGHT' => sprintf( "%s g", $weight ),
						'BRAND' => 'brak danych',
						
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
