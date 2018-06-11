<?php
class MIDOCEANBRANDS extends XMLAbstract{
	
	// filtrowanie kategorii
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		$subcat_name = $cat_name;
		
		if( in_array( $cat_name, array( 'akcesoria', 'myszka', 'podkładka pod myszkę', 'usb i karty pamięci', 'zasilane energią słoneczną', 'ładowarka', 'przedłużacz', 'rozdzielacz', 'wodne' ) ) ){
			$cat_name = 'Elektronika';
			
		}
		elseif( in_array( $cat_name, array( 'akcesoria nadmuchiwane', 'baseball', 'bezpieczeństwo', 'daszek', 'klapki', 'koce', 'lornetki', 'osłony przeciwsłoneczne/ parasole plażow', 'piłka plażowa', 'plaża', 'pojemniki chłodzące i lodówka', 'safari / surfing', 'sporty wodne', 'zasilane przez baterie', 'zasilane przez dynamo' ) ) ){
			$cat_name = 'Wakacje, sport i rekreacja';
			
		}
		elseif( in_array( $cat_name, array( 'bawełna', 'bawełna organiczna', 'juta', 'materiał pet', 'nie tkany', 'papier', 'polyester', 'inny materiał' ) ) ){
			$cat_name = 'Torby i plecaki';
			
		}
		elseif( in_array( $cat_name, array( 'biurko', 'budzik podróżny', 'energia wodna', 'ściana' ) ) ){
			$cat_name = 'Czas i pogoda';
			
		}
		elseif( in_array( $cat_name, array( 'drewniany', 'drewno', 'kolorowe', 'material z recyklingu', 'plastk', 'pudełko', 'zabawne', 'zestaw', 'zestaw biurowy / zestaw do pisania' ) ) ){
			$cat_name = 'Materiały piśmiennicze';
			
		}
		elseif( in_array( $cat_name, array( 'fartuchy i rękawice kuchenne', 'karafka do wina', 'kawiarka', 'koktajl', 'korkociąg / otwieracz', 'krzesła', 'lodówki na wino', 'magnesy na lodówkę', 'minutniki', 'młynki', 'noże', 'otwieracz do butelek', 'ozdoby stołowe', 'podkładki pod szklanki', 'ręczniki', 'rozpylacz', 'sól i pieprz', 'sztućce', 'świece', 'termometr', 'zestaw do wina', 'zestaw pierwszej pomocy' ) ) ){
			$cat_name = 'Dom i ogród';
			
		}
		elseif( in_array( $cat_name, array( 'gry stołowe' ) ) ){
			$cat_name = 'Dzieci i zabawa';
			
		}
		elseif( in_array( $cat_name, array( 'higiena', 'lusterko', 'szczotka do włosów', 'urządzenie do masażu', 'zestaw kosmetyczny' ) ) ){
			$cat_name = 'Zdrowie i uroda';
			
		}
		elseif( in_array( $cat_name, array( 'klips do papieru', 'klips memo', 'linijka', 'materiał z recyklingu', 'notes', 'organizer', 'pojemnik', 'przycisk do papieru', 'pudełko na spinacze', 'stojak na długopisy', 'walizka', 'walizka / pudełko / stojak', 'walizka / zestaw', 'walizka z kółkami', 'zakładka do książek' ) ) ){
			$cat_name = 'Biuro i biznes';
			
		}
		elseif( in_array( $cat_name, array( 'kompas', 'latarka do czytania', 'latarki', 'metal', 'obcinacze', 'taśmy do mierzenia', 'wielofunkcyjne', 'wileofunkcyjne', 'zestaw narzędzi', 'zestaw śrubokrętów', 'zestaw wielofunkcyjny' ) ) ){
			$cat_name = 'Narzędzia, latarki i breloki';
			
		}
		elseif( in_array( $cat_name, array( 'kubek', 'piersiówka' ) ) ){
			$cat_name = 'Do picia';
			
		}
		elseif( in_array( $cat_name, array( 'regularny', 'składany' ) ) ){
			$cat_name = 'Parasole i peleryny';
			
		}
		
	}
	
	// wczytywanie XML, parsowanie danych XML, zapis do bazy danych
	// rehash - określa czy wykonać jedynie przypisanie kategorii dla produktów
	protected function _import( $rehash = false ){
		/* stany magazynowe */
		$stock_a = array();
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'stock' ] ) );
		foreach( $XML->PRODUCTS->PRODUCT as $arg ){
			$stock_a[ (string)$arg->ID ] = (int)$arg->QUANTITY;
		}
		
		// wczytywanie pliku XML z produktami
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'products' ] ) );
		$dt = date( 'Y-m-d H:i:s' );
		
		if( $rehash === true ){
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->attributes( 'no' );
				$category = (string)( array_slice( $item->folders, 0, -1 )[0] );
				$subcategory = "";
				
				$this->_categoryFilter( $category, $subcategory, $item );
				$this->_addCategory( $category, $subcategory );
				
				if( empty( $subcategory ) ){
					$cat_id = $this->getCategory( 'name', $category, 'ID' );
				}
				else{
					$cat_id = $this->getCategory( 'name', $subcategory, 'ID' );
				}
				
				$sql = "UPDATE `XML_product` SET cat_id = '{$cat_id}', data = '{$dt}' WHERE code = '{$code}'";
				if( mysqli_query( $this->_connect, $sql ) === false ){
					$this->_log[] = $sql;
					$this->_log[] = mysqli_error( $this->_connect );
				}
				
			}
			
		}
		else{
			// czyszczenie tabeli produktów przed importem danych
			// $this->_clear();
			
			// var_dump( $XML);
			
			// parsowanie danych z XML
			foreach( $XML->PRODUCTS->PRODUCT as $item ){
				
				$code = (string)$item->PRODUCT_NUMBER;
				$short = (string)$item->PRODUCT_BASE_NUMBER;
				$price = 0;
				$brutto = (float)str_replace( ",", ".", $price );
				$netto = $brutto / ( 1 + $this->_vat );
				// $catalog = addslashes( (string)$item-> );
				$t_cat = array(
					(string)$item->CATEGORY_LEVEL_1,
					(string)$item->CATEGORY_LEVEL_2,
					(string)$item->CATEGORY_LEVEL_3,
					(string)$item->CATEGORY_LEVEL_4,
				);
				$cat = array_slice( array_map( function( $arg ){
					if( !empty( $arg ) ) return $arg;
				}, $t_cat ), -1 )[0];
				$category = $this->_stdName( $cat );
				$subcat = "";
				$subcategory = $this->_stdName( $subcat );
				$name = addslashes( (string)$item->SHORT_DESCRIPTION );
				$dscr = addslashes( (string)$item->LONG_DESCRIPTION );
				$material = addslashes( (string)$item->MATERIAL_TYPE );
				$dims = addslashes( (string)$item->DIMENSIONS );
				$country = addslashes( (string)$item->COUNTRY_OF_ORIGIN );
				$weight = sprintf(
					'%s %s',
					(string)$item->GROSS_WEIGHT,
					(string)$item->GROSS_WEIGHT_UNIT
				);
				$color = addslashes( (string)$item->COLOR_DESCRIPTION );
				$photo = json_encode( array( (string)$item->IMAGE_URL ) );
				$new = 0;
				$sale = 0;
				$promotion = 0;
				
				$this->_categoryFilter( $category, $subcategory, $item );
				$this->_addCategory( $category, $subcategory );
				
				if( empty( $subcategory ) ){
					$cat_id = $this->getCategory( 'name', $category, 'ID' );
				}
				else{
					$cat_id = $this->getCategory( 'name', $subcategory, 'ID' );
				}
				
				/* aktualizacja czy wstawianie? */
				$sql = "SELECT COUNT(*) as num FROM `XML_product` WHERE code = '{$code}'";
				$query = mysqli_query( $this->_connect, $sql );
				$num = mysqli_fetch_assoc( $query )['num'];
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
					'country' => $country,
					'weight' => $weight,
					'colors' => $color,
					'photos' => $photo,
					'new' => $new,
					'promotion' => $promotion,
					'sale' => $sale,
					'data' => $dt,
					// 'marking' => $marking,
					'instock' => $stock_a[ $code ],
				);
				
				// print_r( $item );
				// print_r( $insert );
				// break;
				
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
				
				// echo "\r\n $sql \r\n";
				
				if( mysqli_query( $this->_connect, $sql ) === false ){
					$this->_log[] = $sql;
					$this->_log[] = mysqli_error( $this->_connect );
					
				}
				
				// echo "\r\n{$category} | {$subcategory}\r\n";
				
				// break;
			}
			
		}
		
		if( !empty( $this->_log ) ){
			echo "<!--ANDA ERROR:" . PHP_EOL;
			print_r( $this->_log ) . PHP_EOL;
			echo "-->";
			
		}
		
	}
	
	
}

