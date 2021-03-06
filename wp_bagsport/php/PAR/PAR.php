<?php
class PAR extends XMLAbstract{

	// filtrowanie kategorii
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		$subcat_name = $cat_name;

		if( in_array( $cat_name, array( 'akcesoria komputerowe i smartfonowe', 'etui na laptopa i smartfon', 'zegary i kalkulatory' ) ) ){
			$cat_name = 'Elektronika';

		}
		elseif( in_array( $cat_name, array( 'akcesoria samochodowe', 'breloki antystresowe', 'latarki', 'metalowe, aluminiowe', 'miarki, ołówki stolarskie', 'z miarką, latarką, diodą', 'z żetonem, z otwieraczem', 'zestawy narzędzi, scyzoryki' ) ) ){
			$cat_name = 'Narzędzia, latarki i breloki';

		}
		elseif( in_array( $cat_name, array( 'bidony', 'izotermiczne', 'kubki', 'leak proof', 'termosy' ) ) ){
			$cat_name = 'Do picia';

		}
		elseif( in_array( $cat_name, array( 'czapki', 'niezbędne w podróży', 'odblaski', 'rowerowe', 'kosze' ) ) ){
			$cat_name = 'Wakacje, sport i rekreacja';

		}
		elseif( in_array( $cat_name, array( 'do kuchni', 'do łazienki', 'ozdoby domowe', 'zestawy do wina' ) ) ){
			$cat_name = 'Dom i ogród';

		}
		elseif( in_array( $cat_name, array( 'etui', 'na biurko', 'notesy, notatniki', 'piłki antystresowe' , 'teczki i torby na dokumenty', 'teczki konferencyjne', 'wizytowniki' ) ) ){
			$cat_name = 'Biuro i biznes';

		}
		elseif( in_array( $cat_name, array( 'gry', 'pluszaki i maskotki', 'szkoła i dom' ) ) ){
			$cat_name = 'Dzieci i zabawa';

		}
		elseif( in_array( $cat_name, array( 'parasole' ) ) ){
			$cat_name = 'Parasole i peleryny';

		}
		elseif( in_array( $cat_name, array( 'plecaki', 'torby i plecaki na laptopa', 'torby na prezenty', 'torby na zakupy', 'torby podróżne', 'worki na prezenty' ) ) ){
			$cat_name = 'Torby i plecaki';

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
		/* generowanie tablicy ze stanem magazynowym */
		$stock_a = array();
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources['stock'] ) );
		foreach( $XML->produkt as $item ){
			$stock_a[ (string)$item->kod ] = (int)$item->stan_magazynowy;

		}

		// wczytywanie pliku XML z produktami
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'products' ] ) );
		$dt = date( 'Y-m-d H:i:s' );

		if( $rehash === true ){
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->kod;
				$category = $this->_stdName( (string)$item->kategorie->kategoria[0] );
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
				if( mysqli_query( $this->_dbConnect(), $sql ) === false ){
					$this->_log[] = $sql;
					$this->_log[] = mysqli_error( $this->_dbConnect() );
				}

			}

		}
		else{
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->kod;
				$short = (string)$item->kod;
				$price = (string)$item->cena_pln;
				$netto = (float)str_replace( ",", ".", $price );
				$brutto = $netto * ( 1 + $this->_vat );
				// $catalog = addslashes( (string)$item-> );
				$cat = addslashes( (string)$item->kategorie->kategoria[0] );
				$category = $this->_stdName( $cat );
				$subcat = "";
				$subcategory = $this->_stdName( $subcat );
				$name = addslashes( (string)$item->nazwa );
				$dscr = addslashes( (string)$item->opis );
				$material = addslashes( (string)$item->material_wykonania );
				$dims = addslashes( (string)$item->wymiary );
				// $country = addslashes( (string)$item-> );
				$weight = (float)$item->opakowania->opakowanie_jednostkowe->waga_brutto;
				$color = addslashes( (string)$item->kolor_podstawowy );
				$photo_a = array();
				/* https://www.par.com.pl/shared/zdjecia_katalog/full/R91744_02_c.jpg */
				foreach( $item->zdjecia->children() as $img ){
					$photo_a[] = "https://www.par.com.pl/shared/zdjecia_katalog/full/" . basename( (string)$img );
				}
				$photo = json_encode( $photo_a );
				$new = (string)$item->towar_nowosc === "false"?( 0 ):( 1 );
				$sale = (string)$item->wyprzedaz === "false"?( 0 ):( 1 );
				$promotion = (string)$item->promocja === "0"?( 0 ):( 1 );
				$marking_a = array();
				foreach( $item->techniki_zdobienia->technika as $arg ){
					$marking_a[] = sprintf(
						'%s<br>
>%s
>>%s',
						(string)$arg->miejsce_zdobienia,
						(string)$arg->technika_zdobienia,
						(string)$arg->maksymalny_rozmiar_logo
					);

				}
				$marking = implode( "<br>", $marking_a );

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
					// 'country' => $country,
					'weight' => $weight,
					'colors' => $color,
					'photos' => $photo,
					'new' => $new,
					'promotion' => $promotion,
					'sale' => $sale,
					'data' => $dt,
					'marking' => $marking,
					'instock' => $stock_a[ $code ],
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

				// echo "\r\n $sql \r\n";

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
			echo "<!--PAR ERROR:" . PHP_EOL;
			print_r( $this->_log ) . PHP_EOL;
			echo "-->";

		}

	}


}
