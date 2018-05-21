<?php
class AXPOL extends XMLAbstract{
	
	// filtrowanie kategorii
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		
	}
	
	// wczytywanie XML, parsowanie danych XML, zapis do bazy danych
	// rehash - określa czy wykonać jedynie przypisanie kategorii dla produktów
	protected function _import( $atts, $rehash = false ){
		// wczytywanie pliku XML z produktami
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_atts[ 'products' ] ) );
		
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
				
				$sql = "UPDATE `XML_product` SET cat_id = '{$cat_id}' WHERE code = ''{$code}";
				if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
				
			}
			
			
		}
		else{
			// czyszczenie tabeli przed importem danych
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
				$dscr = addslashes( (string)$item->DescriptionPL);
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
				
				$sql = "INSERT INTO `XML_product` ( `shop`, `code`, `short`, `cat_id`, `brutto`, `netto`, `catalog`, `title`, `description`, `materials`, `dimension`, `country`, `weight`, `colors`, `photos`, `new`, `promotion`, `sale` )
				VALUES ( '{$this->_atts[ 'shop' ]}', '{$code}', '{$short}', '{$cat_id}', '{$brutto}', '{$netto}', '{$catalog}', '{$name}', '{$dscr}', '{$material}', '{$dims}', '{$country}', '{$weight}', '{$color}', '{$photo}', '{$new}', '{$promotions}', '{$sale}' )";
				if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
				
				// echo "\r\n{$category} | {$subcategory}";
				
			}
			
			// wyciąganie stanu magazynowego z XML
			$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_atts[ 'stock' ] ) );
			foreach( $XML->items->children() as $item ){
				$kod = $item->Kod;
				$num = (int)$item->{'na_magazynie_dostepne_teraz'} + (int)$item->{'na_zamowienie_w_ciagu_7-10_dni'};
				
				$sql = "UPDATE `XML_product` SET  `instock` = {$num} WHERE `code` = '{$kod}'";
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
				
				$sql = "UPDATE `XML_product` SET  `marking` = '{$mark_s}' WHERE `code` = '{$kod}'";
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
