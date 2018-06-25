<?php
class MACMA extends XMLAbstract{

	// filtrowanie kategorii
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		$subcat_name = $cat_name;

		if( in_array( $cat_name, array( 'artykuły biurowe', 'crisma', 'smile hand' ) ) ){
			$cat_name = 'Biuro i biznes';

		}
		elseif( in_array( $cat_name, array( 'breloki i odznaki', 'latarki i narzędzia' ) ) ){
			$cat_name = 'Narzędzia, latarki i breloki';

		}
		elseif( in_array( $cat_name, array( 'długopisy i zestawy piśmienne', 'mark twain' ) ) ){
			$cat_name = 'Materiały piśmiennicze';

		}
		elseif( in_array( $cat_name, array( 'dom i wyposażenie wnętrz' ) ) ){
			$cat_name = 'Dom i ogród';

		}
		elseif( in_array( $cat_name, array( 'cofee', 'sport i rekreacja' ) ) ){
			$cat_name = 'Wakacje, sport i rekreacja';

		}
		elseif( in_array( $cat_name, array( 'elektronika i zegary', 'pamięci usb', 'power banki' ) ) ){
			$cat_name = 'Elektronika';

		}
		elseif( in_array( $cat_name, array( 'kosmetyki i pielęgnacja' ) ) ){
			$cat_name = 'Zdrowie i uroda';

		}
		elseif( in_array( $cat_name, array( 'kubki reklamowe' ) ) ){
			$cat_name = 'Do picia';

		}
		elseif( in_array( $cat_name, array( 'parasole i płaszcze' ) ) ){
			$cat_name = 'Parasole i peleryny';

		}
		elseif( in_array( $cat_name, array( 'torby, worki, plecaki' ) ) ){
			$cat_name = 'Torby i plecaki';

		}

	}

	// wczytywanie XML, parsowanie danych XML, zapis do bazy danych
	// rehash - określa czy wykonać jedynie przypisanie kategorii dla produktów
	protected function _import( $rehash = false ){
		/* generowanie tablicy z cenami */
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources['prices'] ) );
		$price_a = array();
		foreach( $XML->children() as $product ){
			$price_a[ (string)$product->code_full ] = (float)str_replace( ",", ".", (string)$product->price );

		}

		/* generowanie tablicy ze stanem magazynowym */
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources['stock'] ) );
		$stock_a = array();
		foreach( $XML->children() as $product ){
			$stock_a[ (string)$product->code_full ] = (int)$product->quantity_24h;

		}

		// wczytywanie pliku XML z produktami
		$XML = simplexml_load_file( __DIR__ . "/DND/" . basename( $this->_sources[ 'products' ] ) );
		$dt = date( 'Y-m-d H:i:s' );

		if( $rehash === true ){
			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$code = (string)$item->baseinfo->code_full;
				$category = addslashes( (string)$item->categories->category[0]->name );
				$subcategory = addslashes( (string)$item->categories->category[0]->subcategory[0]->name );

				$this->_categoryFilter( $category, $subcategory, $item );
				$this->_addCategory( $category, $subcategory );

				if( empty( $subcategory ) ){
					$cat_id = $this->getCategory( 'name', $category, 'ID' );

				}
				else{
					$cat_id = $this->getCategory( 'name', $subcategory, 'ID' );

				}

				$sql = "UPDATE `XML_product` SET cat_id = '{$cat_id}', data = '{$dt}' WHERE code = '{$code}'";
				if( mysqli_query( $this->_dbConnect(), $sql ) === false ) $this->_log[] = mysqli_error( $this->_dbConnect() );

			}

		}
		else{
			// czyszczenie tabeli produktów przed importem danych
			// $this->_clear();

			// parsowanie danych z XML
			foreach( $XML->children() as $item ){
				$id = (int)$item->baseinfo->id;
				$code = (string)$item->baseinfo->code_full;
				$short = (string)$item->baseinfo->code_short;

				$brutto = $price_a[ $code ];
				$netto = $brutto / ( 1 + $this->_vat );

				// $catalog = addslashes( (string)$item->Catalog );
				$cat = addslashes( (string)$item->categories->category[0]->name );
				$subcat = addslashes( (string)$item->categories->category[0]->subcategory[0]->name );
				$category = $this->_stdName( $cat );
				$subcategory = $this->_stdName( $subcat );
				$name = addslashes( (string)$item->baseinfo->name );
				$dscr = addslashes( (string)$item->baseinfo->intro );
				$material = addslashes( (string)$item->materials->material[0]->name );
				$dims = addslashes( (string)$item->attributes->size );
				$country = addslashes( (string)$item->origincountry->name );
				$weight = (integer)$item->attributes->weight;
				$color = addslashes( (string)$item->color->name );
				$photo_a = array();
				foreach( $item->images->children() as $img ){
					$remote = (string)$img;
					$local = __DIR__ . "/IMG/{$id}/" . basename( $remote );

					if( !file_exists( dirname( $local ) ) ) mkdir( dirname( $local ), 0755, true );
					if( !file_exists( $local ) and !copy( $remote, $local ) ){
						$photo_a[] = $remote;

					}
					else{
						$photo_a[] = "/wp-content/themes/wp_bagsport/php/MACMA/IMG/{$id}/" . basename( $remote );

					}


				}
				$photo = json_encode( $photo_a );
				$new = (int)$item->attributes->new;
				$promotion = 0;
				$sale = 0;
				$marking_a = array();
				foreach( $item->markgroups->children() as $mark ){
					$marking_a[] = (string)$mark->name;

				}
				$marking = (string)$item->marking_size . "<br>>" . implode( ", ", $marking_a );

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

				if( mysqli_query( $this->_dbConnect(), $sql ) === false ) $this->_log[] = mysqli_error( $this->_dbConnect() );

				// echo "\r\n{$category} | {$subcategory}";

			}

		}

		if( !empty( $this->_log ) ){
			echo "<!--MACMA ERROR:" . PHP_EOL;
			print_r( $this->_log ) . PHP_EOL;
			echo "-->";

		}

	}


}
