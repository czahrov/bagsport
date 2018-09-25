<?php
class ASGARD extends XMLAbstract{
	private function _priceMod( $price ){
		return $price;
	}
	
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
		// wczytywanie stanów magazynowych
		$stock_a = array();
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'stock' ] ) );
		foreach( $XML->product as $item ){
			$stock_a[ (string)$item->index ] = (int)$item->stock;
		}
		
		// wczytywanie cen produktów bez obniżki agencyjnej
		$price_a  = array();
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'prices' ] ) );
		foreach( $XML->product as $item ){
			$price_a[ (string)$item->index ] = (float)$item->price->pln;
		}
		
		// wczytywanie zdjęć produktów
		$photo_a  = array();
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'images' ] ) );
		foreach( $XML->product as $item ){
			$t = array();
			foreach( $item->files->file_name as $img ){
				$t[] = "http://www.bluecollection.eu/assets/img/" . (string)$img;
			}
			$photo_a[ (string)$item->index ] = $t;
		}
		
		// wczytywanie listy kolorów
		$colors_a  = array();
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'colors' ] ) );
		foreach( $XML->colour as $item ){
			$colors_a[ (string)$item->id ] = (string)$item->name;
		}
		
		// wczytywanie pliku XML z produktami
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'products' ] ) );
		$dt = date( 'Y-m-d H:i:s' );

		if( $rehash === true ){
			

		}
		else{
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->index;
				$short = $code;
				$price = ( $t = (float)str_replace( ',', '.', (string)$item->price->pln ) ) > 0?( $t ):( $price_a[$code] );
				$netto = $this->_priceMod( $price );
				$brutto = $netto * ( 1 + $this->_vat );
				$catalog = "";
				$cat = addslashes( (string)$item->category->pl );
				$category = $this->_stdName( $cat );
				$subcat = addslashes( (string)$item->subcategory->pl );
				$subcategory = $this->_stdName( $subcat );
				$name = addslashes( (string)$item->name->pl );
				$dscr = addslashes( (string)$item->description->pl );
				$material = addslashes( (string)$item->made_of->pl );
				$dims = addslashes( (string)$item->measurements->pl );
				$country = addslashes(  );
				$weight = (float)$item->weight * 1000;
				$color = addslashes( $colors_a[ (string)$item->product_colour->pl ] );
				$new = 0;
				$promotion = 0;
				$sale = 0;
				$marking = (string)$item->product_marking->pl;
				$photo = array_key_exists( $code, $photo_a )?( $photo_a[ $code ] ):( array( "https://asgard.pl/png/product/" . $code . ".jpg" ) );
				$instock = $stock_a[ $code ];
				
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
					'catalog' => $catalog,
					'title' => $name,
					'description' => $dscr,
					'materials' => $material,
					'dimension' => $dims,
					'country' => $country,
					'weight' => $weight,
					'colors' => $color,
					'photos' => json_encode( $photo ),
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
