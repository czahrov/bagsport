<?php
class AXPOL extends XMLAbstract{
	
	// filtrowanie kategorii
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		$subcat_name = "";
		
		if( in_array( $cat_name, array( 'biuro', 'teczki i notatniki', 'mauro conti', 'moleskine' ) ) ){
			$cat_name = 'Biuro i biznes';
			
		}
		elseif( in_array( $cat_name, array( 'do picia' ) ) ){
			$cat_name = 'Do picia';
			
		}
		elseif( in_array( $cat_name, array( 'dom', 'dom i wnętrze' ) ) ){
			$cat_name = 'Dom i ogród';
			
		}
		elseif( in_array( $cat_name, array( 'do pisania', 'przybory piśmienne' ) ) ){
			$cat_name = 'Materiały piśmiennicze';
			
		}
		elseif( in_array( $cat_name, array( 'parasole' ) ) ){
			$cat_name = 'Parasole i peleryny';
			
		}
		elseif( in_array( $cat_name, array( 'rozrywka i szkoła', 'fofcio promo toys' ) ) ){
			$cat_name = 'Dzieci i zabawa';
			
		}
		elseif( in_array( $cat_name, array( 'breloki', 'narzędzia', 'narzędzia i latarki', 'budowlane' ) ) ){
			$cat_name = 'Narzędzia, latarki i breloki';
			
		}
		elseif( stripos( (string)$item->TitlePL, 'zegar' ) !== false or stripos( (string)$item->TitlePL, 'pogod' ) !== false ){
			$cat_name = 'Czas i pogoda';
			
		}
		elseif( in_array( $cat_name, array( 'technologia', 'elektronika', 'akcesoria do telefonów i tabletów', 'air gifts' ) ) ){
			$cat_name = 'Elektronika';
			
		}
		elseif( in_array( $cat_name, array( 'świąteczne', 'wielkanoc' ) ) ){
			$cat_name = 'Świąteczne';
			
		}
		elseif( stripos( (string)$item->TitlePL, 'plecak' ) !== false or in_array( $cat_name, array( 'torby i plecaki', 'torby i podróż', 'podróż' ) ) ){
			$cat_name = 'Torby i plecaki';
			
		}
		elseif( in_array( $cat_name, array( 'wypoczynek i plener', 'wypoczynek', 'sport' ) ) ){
			$cat_name = 'Wakacje sport i rekreacja';
			
		}
		elseif( in_array( $cat_name, array( 'zdrowie i bezpieczeństwo' ) ) ){
			$cat_name = 'Zdrowie i uroda';
			
		}
		
	}
	
	// wczytywanie XML, parsowanie danych XML, zapis do bazy danych
	// rehash - określa czy wykonać jedynie przypisanie kategorii dla produktów
	protected function _import( $atts, $rehash = false ){
		// wczytywanie pliku XML z produktami
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_atts[ 'products' ] ) );
		$dt = date( 'Y-m-d H:i:s' );
		
		if( $rehash === true ){
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->CodeERP;
				$category = $this->_stdName( (string)$item->MainCategoryPL );
				$subcategory = $this->_stdName( (string)$item->SubCategoryPL );
				
				$this->_categoryFilter( $category, $subcategory, $item );
				$this->_addCategory( $category, $subcategory );
				
				if( empty( $subcategory ) ){
					$cat_id = $this->getCategory( 'name', $category, 'ID' );
					
				}
				else{
					$cat_id = $this->getCategory( 'name', $subcategory, 'ID' );
					
				}
				
				$sql = "UPDATE `XML_product` SET cat_id = '{$cat_id}', data = '{$dt}' WHERE code = '{$code}'";
				if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
				
			}
			
		}
		else{
			// czyszczenie tabeli produktów przed importem danych
			$this->_clear();
			
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->CodeERP;
				// $pattern = "~^([^\.\-]+)~";
				$pattern = "~(.+?)(?:[SMLXF]+)?(?:[\.\-\/])?\w$~";
				preg_match_all( $pattern, $code, $match );
				// $short = $match[1];
				$short = $match[1][0];
				$brutto = (float)str_replace( ",", ".", $item->CatalogPricePLN );
				$netto = $brutto / ( 1 + $this->_vat );
				$catalog = addslashes( (string)$item->Catalog );
				$cat = addslashes( (string)$item->MainCategoryPL );
				$subcat = addslashes( (string)$item->SubCategoryPL );
				$name = addslashes( (string)$item->TitlePL );
				$dscr = addslashes( (string)$item->DescriptionPL . "<br><br>" . htmlentities( (string)$item->ExtraTextPL ) );
				$material = addslashes( (string)$item->MaterialPL );
				$dims = addslashes( (string)$item->Dimensions );
				$country = addslashes( (string)$item->CountryOfOrigin );
				$weight = (integer)$item->ItemWeightG;
				$color = addslashes( (string)$item->ColorPL );
				$photo_a = array();
				for( $i=1; $i<=20; $i++ ){
					$t = (string)$item->{sprintf( "Foto%'02u", $i )};
					if( !empty( $t ) ){
						$photo_a[] = sprintf( "https://axpol.com.pl/files/%s/%s", 
							$i == 1?( 'fotob' ):( 'foto_add_big' ),
							$t
						
						);
						
					}
					
				}
				$photo = json_encode( $photo_a );
				$category = $this->_stdName( (string)$item->MainCategoryPL );
				$subcategory = $this->_stdName( (string)$item->SubCategoryPL );
				$new = (int)$item->New;
				$promotion = (int)$item->Promotion;
				$sale = (int)$item->Sale;
				
				$this->_categoryFilter( $category, $subcategory, $item );
				$this->_addCategory( $category, $subcategory );
				
				if( empty( $subcategory ) ){
					$cat_id = $this->getCategory( 'name', $category, 'ID' );
					
				}
				else{
					$cat_id = $this->getCategory( 'name', $subcategory, 'ID' );
					
				}
				
				$sql = "INSERT INTO `XML_product` ( `shop`, `code`, `short`, `cat_id`, `brutto`, `netto`, `catalog`, `title`, `description`, `materials`, `dimension`, `country`, `weight`, `colors`, `photos`, `new`, `promotion`, `sale`, `data` )
				VALUES ( '{$this->_atts[ 'shop' ]}', '{$code}', '{$short}', '{$cat_id}', '{$brutto}', '{$netto}', '{$catalog}', '{$name}', '{$dscr}', '{$material}', '{$dims}', '{$country}', '{$weight}', '{$color}', '{$photo}', '{$new}', '{$promotions}', '{$sale}', '{$dt}' )";
				if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
				
				// echo "\r\n{$category} | {$subcategory}";
				
			}
			
			// wyciąganie stanu magazynowego z XML
			$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_atts[ 'stock' ] ) );
			foreach( $XML->items->children() as $item ){
				$kod = $item->Kod;
				$num = (int)$item->{'na_magazynie_dostepne_teraz'} + (int)$item->{'na_zamowienie_w_ciagu_7-10_dni'};
				
				$sql = "UPDATE `XML_product` SET  `instock` = {$num}, data = '{$dt}' WHERE `code` = '{$kod}'";
				if( mysqli_query( $this->_connect, $sql ) === false ){
					$this->_log[] = mysqli_error( $this->_connect );
					
				}
				
			}
			
			// wyciąganie znakowania z XML
			$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_atts[ 'marking' ] ) );
			foreach( $XML->children() as $item ){
				$kod = $item->CodeERP;
				$marking_a = array();
				
				for( $position = 1; $position <= 6; $position++ ){
					$print_position = (string)$item->{"Position_{$position}_PrintPosition"};
					if( empty( $print_position ) ) continue;
					$print_size = (string)$item->{"Position_{$position}_PrintSize"};
					if( empty( $print_size ) ) continue;
					
					for( $tech = 1; $tech <= 5; $tech++ ){
						$print_tech = (string)$item->{"Position_{$position}_PrintTech_{$tech}"};
						if( empty( $print_tech ) ) continue;
						
						$marking_a = array_merge_recursive(
							$marking_a,
							array(
								"$print_position" => array(
									"$print_size" => $print_tech,
									
								),
							)
							
						);
						
					}
					
					$marking = json_encode( $marking_a );
					
				}
				
				$mark_s = "";
				foreach( $marking_a as $place => $sizes ){
					$mark_s .= "{$place}<br>";
					foreach( $sizes as $size => $technics ){
						$mark_s .= ">{$size} mm<br>>>";
						
						if( is_array( $technics ) ){
							$mark_s .=  implode( ", ", $technics );
							
						}
						else{
							$mark_s .= $technics;
							
						}
						
						$mark_s .= "<br>";
						
					}
					
				}
				
				$sql = "UPDATE `XML_product` SET  `marking` = '{$mark_s}', data = '{$dt}' WHERE `code` = '{$kod}'";
				if( mysqli_query( $this->_connect, $sql ) === false ){
					$this->_log[] = mysqli_error( $this->_connect );
					
				}
				
				/* array(1) {
					["item barrel"]=>
					array(1) {
					["6x25"]=>
						string(2) "T1"
					}
				} */
				
				
			}
			
		}
		
		if( !empty( $this->_log ) ){
			echo "<!--AXPOL ERROR:" . PHP_EOL;
			print_r( $this->_log ) . PHP_EOL;
			echo "-->";
			
		}
		
	}
	
	
}

