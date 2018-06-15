<?php
class INSPIRION extends XMLAbstract{
	
	// filtrowanie kategorii
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		$subcat_name = $cat_name;
		
		if( in_array( $cat_name, array( 'breloki', 'narzędzia i hobby' ) ) ){
			$cat_name = 'Narzędzia, latarki i breloki';
		}
		elseif( in_array( $cat_name, array( 'czapki', 'picolux', 'picotravel', 'rekreacja i piknik', 'ubrania' ) ) ){
			$cat_name = 'Wakacje, sport i rekreacja';
		}
		elseif( in_array( $cat_name, array( 'dla dzieci', 'gry', 'picogame' ) ) ){
			$cat_name = 'Dzieci i zabawa';
		}
		elseif( in_array( $cat_name, array( 'czas i pogoda', 'picotime' ) ) ){
			$cat_name = 'Czas i pogoda';
		}
		elseif( in_array( $cat_name, array( 'do domu i kuchni', 'picohome' ) ) ){
			$cat_name = 'Dom i ogród';
		}
		elseif( in_array( $cat_name, array( 'elektronika', 'picosound' ) ) ){
			$cat_name = 'Elektronika';
		}
		elseif( in_array( $cat_name, array( 'kosmetyka' ) ) ){
			$cat_name = 'Zdrowie i uroda';
		}
		elseif( in_array( $cat_name, array( 'parasole' ) ) ){
			$cat_name = 'Parasole i peleryny';
		}
		elseif( in_array( $cat_name, array( 'picoactive' ) ) ){
			$cat_name = 'Do picia';
		}
		elseif( in_array( $cat_name, array( 'picooffice', 'wszystko do biura' ) ) ){
			$cat_name = 'Biuro i biznes';
		}
		elseif( in_array( $cat_name, array( 'picopen' ) ) ){
			$cat_name = 'Materiały piśmiennicze';
		}
		elseif( in_array( $cat_name, array( 'torby i plecaki' ) ) ){
			$cat_name = 'Torby i plecaki';
		}
		
	}
	
	// wczytywanie XML, parsowanie danych XML, zapis do bazy danych
	// rehash - określa czy wykonać jedynie przypisanie kategorii dla produktów
	protected function _import( $rehash = false ){
		// wczytywanie pliku XML z produktami
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'products' ] ) );
		$dt = date( 'Y-m-d H:i:s' );
		
		if( $rehash === true ){
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->sku;
				$category = $this->_stdName( (string)$item->catalog );
				$subcategory = $this->_stdName( (string)$item->catalog_special );
				
				$this->_categoryFilter( $category, $subcategory, $item );
				$this->_addCategory( $category, $subcategory );
				
				if( empty( $subcategory ) ){
					$cat_id = $this->getCategory( 'name', $category, 'ID' );
					
				}
				else{
					$cat_id = $this->getCategory( 'name', $subcategory, 'ID' );
					
				}
				
				mysqli_ping( $this->_connect );
				
				$sql = "UPDATE `XML_product` SET cat_id = '{$cat_id}', data = '{$dt}' WHERE code = '{$code}'";
				if( mysqli_query( $this->_connect, $sql ) === false )
				{
					$this->_log[] = $sql;
					$this->_log[] = mysqli_error( $this->_connect );
				}
				
			}
			
		}
		else{
			// czyszczenie tabeli produktów przed importem danych
			// $this->_clear();
			
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->sku;
				$short = (string)$item->sku;
				$price = (string)$item->catalog_price;
				$brutto = (float)str_replace( ",", ".", $price );
				$netto = $brutto / ( 1 + $this->_vat );
				// $catalog = addslashes( (string)$item-> );
				$cat = addslashes( (string)$item->catalog );
				$subcat = addslashes( (string)$item->catalog_special );
				$name = addslashes( (string)$item->product_name );
				$dscr = addslashes( (string)$item->body );
				$material = addslashes( (string)$item->material );
				$dims = addslashes( (string)$item->wymiary );
				// $country = addslashes( (string)$item-> );
				$weight = (float)$item->weight_gross;
				$color = addslashes( (string)$item->kolor );
				/* https://inspirion.pl/sites/default/files/imagecache/product_full/56-1103282.jpg */
				$photo = json_encode( array_map( function( $arg ){
					return "https://inspirion.pl/sites/default/files/imagecache/product_full/" . basename( $arg );
					
				}, explode( ", ", (string)$item->product_images ) ) );
				$category = $this->_stdName( $cat );
				$subcategory = $this->_stdName( $subcat );
				$new = 0;
				$promotion = 0;
				$sale = 0;
				$marking = (string)$item->Imprint-size;
				
				$this->_categoryFilter( $category, $subcategory, $item );
				$this->_addCategory( $category, $subcategory );
				
				if( empty( $subcategory ) ){
					$cat_id = $this->getCategory( 'name', $category, 'ID' );
					
				}
				else{
					$cat_id = $this->getCategory( 'name', $subcategory, 'ID' );
					
				}
				
				mysqli_ping( $this->_connect );
				
				/* aktualizacja czy wstawianie? */
				$sql = "SELECT COUNT(*) as num FROM `XML_product` WHERE code = '{$code}'";
				$query = mysqli_query( $this->_connect, $sql );
				$fetch = mysqli_fetch_assoc( $query );
				$num = $fetch['num'];
				mysqli_free_result( $query );
				
				$insert = array(
					'shop' => $this->_atts['shop'],
					'code' => $code,
					'short' => $short,
					'cat_id' => $cat_id,
					'brutto' => $brutto,
					'netto' => $netto,
					// 'catalog' => $catalog,
					'title' => $name,
					'description' => $dscr,
					'materials' => $material,
					'dimension' => $dims,
					// 'country' => $country,
					'weight' => $weight,
					'colors' => $color,
					'photos' => $photo,
					'new' => $new,
					'promotion' => $promotion,
					'sale' => $sale,
					'data' => $dt,
					'marking' => $marking,
				);
				
				$t_fields = array();
				$t_values = array();
				
				/* aktualizacja */
				if( $num > 0 ){
					$t_sql = array();
					
					unset( $insert['code'] );
					$sql = "UPDATE XML_product SET ";
					
					foreach( $insert as $field => $value ){
						$t_sql[] = "`{$field}` = '{$value}'";
					}
					
					$sql .= implode( ", ", $t_sql );
					
					$sql .= " WHERE `code` = '{$code}'";
					
				}
				/* wstawianie */
				else{
					
					foreach( $insert as $field => $value ){
						$t_fields[] = "`{$field}`";
						$t_values[] = "'{$value}'";
						
					}
					
					$sql = sprintf(
						'INSERT INTO XML_product ( %s ) VALUES ( %s )',
						implode( ", ", $t_fields ),
						implode( ", ", $t_values )
						
					);
					
					
				}
				
				if( mysqli_query( $this->_connect, $sql ) === false ){
					$this->_log[] = $sql;
					$this->_log[] = mysqli_error( $this->_connect );
					
				}
				
				// echo "\r\n{$category} | {$subcategory}";
				
			}
			
		}
		
		if( !empty( $this->_log ) ){
			echo "<!--INSPIRION ERROR:" . PHP_EOL;
			print_r( $this->_log ) . PHP_EOL;
			echo "-->";
			
		}
		
	}
	
	
}

