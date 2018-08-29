<?php
class EASYGIFTS extends XMLAbstract{

	// filtrowanie kategorii
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		$subcat_name = $cat_name;

		if( in_array( $cat_name, array( 'biuro i akcesoria biurowe', 'etui', 'cerruti 1881', 'christian lacroix', 'cacharel', 'nina ricci', 'ungaro', 'victorinox lifestyle - akcesoria podróżne' ) ) ){
			$cat_name = 'Biuro i biznes';

		}
		elseif( in_array( $cat_name, array( 'icewatch', 'smartwatche' ) ) ){
			$cat_name = 'Czas i pogoda';

		}
		elseif( in_array( $cat_name, array( 'aladdin & stanley' ) ) ){
			$cat_name = 'Do picia';

		}
		elseif( in_array( $cat_name, array( 'dom' ) ) ){
			$cat_name = 'Dom i ogród';

		}
		elseif( in_array( $cat_name, array( 'czas i elektronika', 'dyski', 'elektronika markowa', 'pendrive\'y', 'power banki', 'mobile', 'selfie sticki', 'silicon power' ) ) ){
			$cat_name = 'Elektronika';

		}
		elseif( in_array( $cat_name, array( 'breloki akrylowe', 'narzędzia', 'pinsy', 'victorinox delemont collection' ) ) ){
			$cat_name = 'Narzędzia, latarki i breloki';

		}
		elseif( in_array( $cat_name, array( 'torby', 'torby by jassz', 'tucano', 'victorinox altmont - plecaki i torby', 'wenger - bagaże biznesowe i akcesoria podróżne' ) ) ){
			$cat_name = 'Torby i plecaki';

		}
		elseif( in_array( $cat_name, array( 'odpoczynek', 'czapki cofee', 'easy siesta', 'gadżety piłkarskie' ) ) ){
			$cat_name = 'Wakacje, sport i rekreacja';

		}
		elseif( in_array( $cat_name, array( 'uroda' ) ) ){
			$cat_name = 'Zdrowie i uroda';

		}
		elseif( in_array( $cat_name, array( 'katalog świąteczny', 'katalog świąteczny 2015' ) ) ){
			$cat_name = 'Świąteczne';

		}
		elseif( 
				stripos( (string)$item->baseinfo->name, 'długo' ) !== false or
				stripos( (string)$item->baseinfo->name, 'ołów' ) !== false or
				stripos( (string)$item->baseinfo->name, 'piór' ) !== false or
				stripos( (string)$item->baseinfo->name, 'flamas' ) !== false
			){
				$cat_name = 'Materiały piśmiennicze';
		}
		else{
			$sucat_name = $cat_name;
			$cat_name = 'Inne';
		}

	}

	// wczytywanie XML, parsowanie danych XML, zapis do bazy danych
	// rehash - określa czy wykonać jedynie przypisanie kategorii dla produktów
	protected function _import( $rehash = false ){
		// wczytywanie pliku XML z produktami
		$dt = date( 'Y-m-d H:i:s' );

		if( $rehash === true ){
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->baseinfo->code_full;
				$category = $this->_stdName( (string)$item->categories->category[0]->name );
				$subcategory = '';

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
			// wyciąganie stanu magazynowego z XML
			$stock_a = array();
			$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'stock' ] ) );
			foreach( $XML->children() as $item ){
				$kod = (string)$item->code_full;
				$num = (int)$item->quantity_24h;

				$stock_a[ $kod ] = $num;
			}
			
			// parsowanie danych z XML
			$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'products' ] ) );
			foreach( $XML->children() as $item ){
				$code = (string)$item->baseinfo->code_full;
				$short = (string)$item->baseinfo->code_short;
				$price = (float)str_replace( ',', '.', (string)$item->baseinfo->price );
				$price_promo = (float)str_replace( ',', '.', (string)$item->baseinfo->price_promotion );
				$price_sellout = (float)str_replace( ',', '.', (string)$item->baseinfo->price_sellout );
				$netto = ($price_promo > 0 and $price_promo < $price)?( $price_promo ):( ( $price_sellout > 0 and $price_sellout < $price )?( $price_sellout ):( $price ) );
				$brutto = $netto * ( 1 + $this->_vat );
				// $catalog = addslashes( (string)$item-> );
				$cat = addslashes( (string)$item->categories->category[0]->name );
				// $subcat = addslashes( (string)$item->SubCategoryPL );
				$name = addslashes( (string)$item->baseinfo->name );
				$dscr = addslashes( (string)$item->baseinfo->intro );
				$material = addslashes( (string)$item->materials->material[0]->name );
				$dims = addslashes( (string)$item->attributes->size );
				$country = addslashes( (string)$item->origincountry->name );
				$weight = (float)$item->attributes->weight;
				$color = addslashes( (string)$item->color->name );
				$photo_a = array();
				foreach( $item->images->children() as $img ){
					$photo_a[] = (string)$img;

				}
				$photo = json_encode( $photo_a );
				$category = $this->_stdName( (string)$item->categories->category[0]->name );
				// $subcategory = $this->_stdName( (string)$item-> );
				$new = (int)$item->attributes->new;
				$sale = ( (float)$item->baseinfo->price_sellout > 0 and $price_sellout > 0 )?( 1 ):( 0 );
				$promotion = ( (float)$item->baseinfo->price_promotion > 0 and $price_promo > 0 )?( 1 ):( 0 );
				$marking_size = (string)$item->marking_size;
				$marking = "{$marking_size}<br>>";
				$marking_a = array();
				foreach( $item->markgroups->children() as $child ){
					$marking_a[] = (string)$child->name;

				}
				$marking .= implode( ", ", $marking_a );

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
					'price_before' => ($price_promo > 0 or $price_sellout > 0)?( $price ):( 0 ),
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
			echo "<!--EASYGIFTS ERROR:" . PHP_EOL;
			print_r( $this->_log ) . PHP_EOL;
			echo "-->";

		}

	}


}
