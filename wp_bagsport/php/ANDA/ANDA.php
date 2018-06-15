<?php
class ANDA extends XMLAbstract{
	
	// filtrowanie kategorii
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		$subcat_name = $cat_name;
		
		if( in_array( $cat_name, array( 'akcesoria antystresowe', 'be creative print', 'krawaty i apaszki', 'linijki i zakładki', 'materiały biurowe', 'notatniki i spinacze', 'office  business', 'produkty szklane pod 3d', 'teczki i podkładki', 'uchwyty na długopisy', 'wizytowniki', 'zakreślacze' ) ) ){
			$cat_name = 'Biuro i biznes';
			
		}
		elseif( in_array( $cat_name, array( 'akcesoria dla psów', 'akcesoria dla rowerzystó', 'akcesoria i gry plażowe', 'akcesoria podróżne', 'akcesoria polarowe', 'akcesoria sportowe i na zabawę', 'czapki oraz czapki z daszkiem', 'japonki', 'kapelusze na lato', 'koce polarowe', 'okulary słoneczne', 'portfele i etui na karty', 'produkty fluoroscencyjne', 'ręczniki, szlafroki', 'smycze', 't-shirt', 'textile  fashion', 'zestawy piknikowe, torby termoizolacyjne' ) ) ){
			$cat_name = 'Wakacje, sport i rekreacja';
			
		}
		elseif( in_array( $cat_name, array( 'akcesoria do koktajli i palenia', 'akcesoria do win i otwieracze do butelek', 'akcesoria kuchenne', 'akcesoria łazienkowe', 'dekoracje domowe', 'fartuchy i akcesoria do pieczenia', 'home & kitchen', 'kuchnia', 'magnesy na lodówkę', 'pudełeczka na tabletki oraz akcessoria medyczne', 'szmatki do czyszczenia okularów', 'świece i kadzidła', 'zestawy do bbq' ) ) ){
			$cat_name = 'Dom i ogród';
			
		}
		elseif( in_array( $cat_name, array( 'akcesoria do telefonów i tabletów', 'akcesoria komputerowe', 'głośniki, słuchawki', 'kalkulatory', 'pendrive-y a hub-y usb i czytniki kart pamięci', 'power banki i ładowarki do telefonów', 'rękawiczki do smartphonów', 'rysiki do ekranów dotykowych', 'technology  mobile' ) ) ){
			$cat_name = 'Elektronika';
			
		}
		elseif( in_array( $cat_name, array( 'akcesoria samochodowe', 'breloki', 'kieszonkowe noże, narzędzia i miarki', 'latarki', 'lornetki i kompasy', 'monety do wózków zakupowych', 'odznaki' ) ) ){
			$cat_name = 'Narzędzia, latarki i breloki';
			
		}
		elseif( in_array( $cat_name, array( 'biżuteria i akcesoria', 'kosmetyczki', 'lusterka oraz zestawy make-up oraz manicure', 'lusterko ze szczotką', 'paski' ) ) ){
			$cat_name = 'Zdrowie i uroda';
			
		}
		elseif( in_array( $cat_name, array( 'butelki sportowe', 'ceramika', 'kubki i szklanki', 'termosy i kubki' ) ) ){
			$cat_name = 'Do picia';
			
		}
		elseif( in_array( $cat_name, array( 'dekoracja', 'office', 'opakowania na prezenty' ) ) ){
			$cat_name = 'Świąteczne';
			
		}
		elseif( in_array( $cat_name, array( 'dla dzieci', 'gumki i ostrzynki', 'jo-jo i magiczne puzzle', 'kids & toys', 'leisure  sport', 'rysowanie i kolorowanie', 'skarbonki', 'zabawki i gry' ) ) ){
			$cat_name = 'Dzieci i zabawa';
			
		}
		elseif( in_array( $cat_name, array( 'długopisy ekologiczne', 'długopisy ekskluzywne', 'długopisy metal-alu i drewniane', 'długopisy plastikowe', 'ołówki', 'pudełka na długopisy', 'zestawy piśmiennicze' ) ) ){
			$cat_name = 'Materiały piśmiennicze';
			
		}
		elseif( in_array( $cat_name, array( 'kurtki, płaszcze przeciwdeszczowe oraz ponczo', 'parasole' ) ) ){
			$cat_name = 'Parasole i peleryny';
			
		}
		elseif( in_array( $cat_name, array( 'plecaki', 'torby na dokumenty, na ramię i na laptopa', 'orby na plażę', 'torby na zakupy', 'torby papierowe', 'torby podróżne, na kółkach i sportowe' ) ) ){
			$cat_name = 'Torby i plecaki';
			
		}
		elseif( in_array( $cat_name, array( 'stacje pogodowe', 'zegarki na rękę', 'zegary ścienne oraz na biurko' ) ) ){
			$cat_name = 'Czas i pogoda';
			
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
				$code = (string)$item->attributes( 'no' );
				$categories = array_slice( $item->folders, 0, -1 );
				$category = (string)$categories[0];
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
			
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				/* tablica właściwości produktu */
				$properties = array();
				foreach( $item->properties->children() as $arg ){
					$properties[ (string)$arg['name'] ] = (string)$arg['value'];
				}
				
				/* tablica stanów magazynowych */
				$stocks = array();
				foreach( $item->stocks->children() as $arg ){
					$stocks[ (string)$arg['name'] ] = (int)$arg['value'];
				}
				
				$code = (string)$item['no'];
				$short = $code;
				$price = (string)$item['price'];
				$brutto = (float)str_replace( ",", ".", $price );
				$netto = $brutto / ( 1 + $this->_vat );
				// $catalog = addslashes( (string)$item-> );
				$cat = addslashes( (string)$item->folders->folder[ $item->folders->folder->count() - 1 ]['subcategory'] );
				$category = $this->_stdName( $cat );
				$subcat = "";
				$subcategory = $this->_stdName( $subcat );
				$name = addslashes( (string)$item['name'] );
				$dscr = addslashes( (string)$item->description );
				$material = "";
				$dims = addslashes( $properties['ROZMIAR PRODUKTU'] );
				// $country = addslashes( (string)$item-> );
				$weight = sprintf( '%.3f kg', $properties['WAGA NETTO/KARTON'] / $properties['SZTUK/KARTON'] );
				$color = null;
				$photo_a = array();
				foreach( $item->images->image as $img ){
					$photo_a[] = (string)$img['src'];
				}
				$photo = json_encode( $photo_a );
				$new = 0;
				$sale = 0;
				$promotion = 0;
				$marking = $properties['METODA DRUKU'];
				
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
					'instock' => $stocks['navi_central'],
				);
				
				// print_r( $insert );
				
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

