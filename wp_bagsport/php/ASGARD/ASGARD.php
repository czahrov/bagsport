<?php
class ASGARD extends XMLAbstract{

	// filtrowanie kategorii
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		$subcat_name = $cat_name;
		// $subcat_name = '';
		
		if( in_array( $cat_name, array( 'sport i wypoczynek' ) ) ){
			$cat_name = 'Wakacje, sport i rekreacja';
		}
		elseif( in_array( $cat_name, array( 'biuro i praca' ) ) ){
			$cat_name = 'Biuro i biznes';
		}
		elseif( in_array( $cat_name, array( 'torby i parasole' ) ) ){
			$cat_name = 'Parasole i peleryny';
		}
		elseif( in_array( $cat_name, array( 'jedzenie i picie' ) ) ){
			$cat_name = 'Do picia';
		}
		elseif( in_array( $cat_name, array( 'breloki i smycze', 'narzędzia i odblaski' ) ) ){
			$cat_name = 'Narzędzia, latarki i breloki';
		}
		elseif( in_array( $cat_name, array( 'oferta świąteczna' ) ) ){
			$cat_name = 'Świąteczne';
		}
		elseif( 
				stripos( (string)$item->nazwa, 'długo' ) !== false or
				stripos( (string)$item->nazwa, 'ołów' ) !== false or
				stripos( (string)$item->nazwa, 'piór' ) !== false or
				stripos( (string)$item->nazwa, 'flamas' ) !== false
			){
				$cat_name = 'Materiały piśmiennicze';
		}
		else{
			$cat_name = 'Inne';
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
				$code = (string)$item->indeks;
				$category = $this->_stdName( (string)$item->kategoria );
				$subcategory = $this->_stdName( (string)$item->Akcesoria );

				$this->_categoryFilter( $category, $subcategory, $item );
				$this->_addCategory( $category, $subcategory );

				if( empty( $subcategory ) ){
					$cat_id = $this->getCategory( 'name', $category, 'ID' );

				}
				else{
					$cat_id = $this->getCategory( 'name', $subcategory, 'ID' );

				}
				
				$sql = "UPDATE `XML_product` SET cat_id = '{$cat_id}', data = '{$dt}' WHERE code = '{$code}'";
				if( mysqli_query( $this->_dbConnect(), $sql ) === false )
				{
					$this->_log[] = $sql;
					$this->_log[] = mysqli_error( $this->_dbConnect() );
				}

			}

		}
		else{
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->indeks;
				$short = $code;
				$price = (string)$item->cena_netto_katalogowa;
				$netto = (float)str_replace( ",", ".", $price );
				$brutto = $netto * ( 1 + $this->_vat );
				// $catalog = addslashes( (string)$item-> );
				$cat = addslashes( (string)$item->kategoria );
				$category = $this->_stdName( $cat );
				$subcat = addslashes( (string)$item->podkategoria );
				$subcategory = $this->_stdName( $subcat );
				$name = addslashes( (string)$item->nazwa );
				$dscr = addslashes( (string)$item->opis_produktu );
				$material = addslashes( (string)$item->material );
				$dims = addslashes( (string)$item->wymiary_produktu );
				// $country = addslashes( (string)$item-> );
				$weight = (float)str_replace( ",", ".", $item->waga_jednostkowa_netto_w_kg ) * 1000;
				$color = addslashes( (string)$item->kolor );
				$photo_base = "https://asgard.pl/png/product/";
				$photo = json_encode( array(
					$photo_base . (string)$item->obraz,
					$photo_base . (string)$item->obraz_1,
					
				) );
				$instock = (int)$item->in_stock;
				$new = (string)$item->status == 'Nowość'?( 1 ):( 0 );
				$promotion = 0;
				$sale = 0;
				$marking = (string)$item->znakowanie_produktu;
				
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
				$query = mysqli_query( $this->_dbConnect(), $sql );
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
					'instock' => $instock,
					
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

				if( mysqli_query( $this->_dbConnect(), $sql ) === false ){
					$this->_log[] = $sql;
					$this->_log[] = mysqli_error( $this->_dbConnect() );

				}

				// echo "\r\n{$category} | {$subcategory}";

			}

		}
		
		// czyszczenie nieaktualnych produktów
		// $this->_clear();
		
		if( !empty( $this->_log ) ){
			echo "<!--ASGARD ERROR:" . PHP_EOL;
			print_r( $this->_log ) . PHP_EOL;
			echo "-->";

		}

	}


}
