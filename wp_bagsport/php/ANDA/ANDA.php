<?php
class ANDA extends XMLAbstract{

	// filtrowanie kategorii
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		$subcat_name = $cat_name;
		
		if( in_array( $cat_name, array( 'antystresy', 'notatniki i kartki samoprzylepne', 'notesy', 'plakietki', 'podkładki', 'szklane trofea', 'teczki na dokumenty', 'wieszaki na teczki', 'wizytowniki', 'zaciski na banknoty', 'chusty i krawaty', 'akcesoria podróżne', 'futerały', 'linijki i zakładki', 'przywieszki i paski bagażowe', 'stojaki na długopisy' ) ) ){
			$cat_name = 'Biuro i biznes';
		}
		elseif( in_array( $cat_name, array( 'minutniki', 'smartwatche', 'stacje pogodowe', 'zegarki', 'zegary', 'zegary ścienne' ) ) ){
			$cat_name = 'Czas i pogoda';
		}
		elseif( in_array( $cat_name, array( 'butelki i kubki termiczne', 'kubki', 'kubki termiczne', 'kubki, filiżanki i szklanki', 'plastikowe sportowe butelki', 'aluminiowe sportowe butelki', 'szklanki' ) ) ){
			$cat_name = 'Do picia';
		}
		elseif( in_array( $cat_name, array( 'akcesoria bbq', 'akcesoria coffe  tea', 'akcesoria kuchenne', 'akcesoria łazienkowe', 'dekoracje', 'deski do krojenia', 'fartuchy', 'młynki', 'noże i narzędzia', 'otwieracze do butelek', 'podstawki', 'przybory stołowe', 'pudełka śniadaniowe i pojemniki', 'ramki', 'ręczniki frotté', 'ręczniki z mikrofibry', 'ręczniki, szlafroki', 'rękawice kuchenne', 'rośliny', 'skarbonki', 'szczoteczki do zębów', 'ściereczki', 'świece i kadzidełka', 'zapalniczki', 'zestawy do wina i akcesoria', 'magnesy na lodówkę', 'cukierki', 'akcesoria koktajlowe' ) ) ){
			$cat_name = 'Dom i ogród';
		}
		elseif( in_array( $cat_name, array( 'zabawki', 'zabawki do kolorowania', 'zabawki plażowe', 'rysowania i kolorowanie', 'puzzle', 'pluszaki' ) ) ){
			$cat_name = 'Dzieci i zabawa';
		}
		elseif( in_array( $cat_name, array( 'akcesoria do telefonów i tabletów', 'głośniki', 'kable usb', 'kalkulatory', 'ładowarki usb', 'ładowarki usb samochodowe', 'myszki i podkładki', 'okulary vr i zestawy słuchawkowe', 'power banki', 'power banki i ładowarki usb', 'rękawiczki i rysiki do ekranów dotykowych', 'rozgałęźnik uniwersalny', 'rysiki do ekranów dotykowych', 'selfie sticki i mobilna fotografia', 'słuchawki', 'słuchawki douszne', 'usb hub', 'usb pendrive', 'wiatraki', 'akcesoria komputerowe', 'etui i uchwyty do telefonów', 'foldery na tablety', 'wodoodporne pokrowce na telefony' ) ) ){
			$cat_name = 'Elektronika';
		}
		elseif( in_array( $cat_name, array( 'długopisy drewniane i bambusowe', 'długopisy ekologiczne', 'długopisy i ołówki dla dzieci', 'długopisy metalowe', 'długopisy plastikowe', 'długopisy wielokolorowe', 'gumki i ostrzynki', 'kredki', 'ołówki', 'ołówki automatyczne', 'ołówki grafitowe', 'zestawy piśmiennicze', 'kredki', 'zakreślacze' ) ) ){
			$cat_name = 'Materiały piśmiennicze';
		}
		elseif( in_array( $cat_name, array( 'akcesoria samochodowe', 'breloki', 'drewniane breloki', 'latarki', 'metalowe breloki', 'miary', 'skrobaczki do szyb', 'uchywyty samochodowe', 'monety do wózków zakupowych', 'plastikowe breloki' ) ) ){
			$cat_name = 'Narzędzia, latarki i breloki';
		}
		elseif( in_array( $cat_name, array( 'parasole pełnowymiarowe', 'parasole składane', 'płaszcze przeciwdeszczowe', 'poncza' ) ) ){
			$cat_name = 'Parasole i peleryny';
		}
		elseif( in_array( $cat_name, array( 'papierowe torby na zakupy', 'składane torby na zakupy', 'torby na kółkach', 'torby na laptopa i dokumenty', 'torby na plażę', 'torby na ramię', 'torby na zakupy', 'torby podróżne', 'torby sportowe', 'torby termiczne', 'torby ze sznurkiem', 'plecaki' ) ) ){
			$cat_name = 'Torby i plecaki';
		}
		elseif( in_array( $cat_name, array( 'akcesoria dla biegaczy', 'akcesoria events  sport', 'akcesoria piłkarskie', 'akcesoria plażowe', 'odzież sportowa', 'okulary przeciwsłoneczne', 'outdoor i piesze wycieczki', 'piłki plażowe', 'akcesoria rowerowe', 'kapelusze', 'klapki', 'koce polarowe', 'lornetki i kompasy', 'nadmuchiwane', 'odświeżacze samochodowe', 'ogrzewacze', 'opaski na oczy i zatyczki do uszu', 'osłony przeciwsłoneczne', 'poduszki i materace', 'portfele i portmonetki', 'produkty do dopingu', 'szaliki i rękawiczki', 'uchwyty na karty kredytowe', 'czapki baseballowe', 'czapki zimowe' ) ) ){
			$cat_name = 'Wakacje, sport i rekreacja';
		}
		elseif( in_array( $cat_name, array( 'biżuteria i bransoletki', 'kosmetyczki', 'lusterka i grzebienie', 'paski', 'zestawy do manicure  makeup' ) ) ){
			$cat_name = 'Zdrowie i uroda';
		}
		else{
			$cat_name = 'Inne';
			
		}
		
	}

	// wczytywanie XML, parsowanie danych XML, zapis do bazy danych
	// rehash - określa czy wykonać jedynie przypisanie kategorii dla produktów
	protected function _import( $rehash = false ){
		$url_products = "https://xml.andapresent.com/export/products/pl/KKS4H3KL7CNWXP3B98ZY7SLQ8MCFNIPX37WMPAT9PCYWL458TRUCV6IG6NUDKWLP";
		$url_prices = "https://xml.andapresent.com/export/prices/KKS4H3KL7CNWXP3B98ZY7SLQ8MCFNIPX37WMPAT9PCYWL458TRUCV6IG6NUDKWLP";
		$url_stocks = "https://xml.andapresent.com/export/inventories/KKS4H3KL7CNWXP3B98ZY7SLQ8MCFNIPX37WMPAT9PCYWL458TRUCV6IG6NUDKWLP";
		
		/* tworzenie tablicy z cenami */
		$content = file_exists( __DIR__ . "/DND/newAndaPrices.xml" )?( file_get_contents( __DIR__ . "/DND/newAndaPrices.xml" ) ):( file_get_contents( $url_prices ) );
		$price_a = array();
		if( $content !== false ){
			$XML = simplexml_load_string( $content );
			foreach( $XML->price as $item ){
				$price_a[ (string)$item->itemNumber ] = (string)$item->amount;
				
			}
			
		}
		
		/* tworzenie tablicy ze stanem magazynowym */
		$content = file_exists( __DIR__ . "/DND/newAndaInventory.xml" )?( file_get_contents( __DIR__ . "/DND/newAndaInventory.xml" ) ):( file_get_contents( $url_stocks ) );
		$stock_a = array();
		if( $content !== false ){
			$XML = simplexml_load_string( $content );
			foreach( $XML->record as $item ){
				$stock_a[ (string)$item->itemNumber ] = (float)$item->amount;
				
			}
			
		}
		
		/* wczytywanie produktów z XML */
		$content = file_exists( __DIR__ . "/DND/newAnda.xml" )?( file_get_contents( __DIR__ . "/DND/newAnda.xml" ) ):( file_get_contents( $url_products ) );
		if( $content !== false ){
			$XML = simplexml_load_string( $content );
			$dt = date( 'Y-m-d H:i:s' );

			if( $rehash === true ){
				// parsowanie danych z XML
				foreach( $XML->children() as $item ){
					$code = (string)$item->itemNumber;
					$cat = addslashes( (string)$item->categories->category[0]->name );
					$category = $this->_stdName( $cat );
					$subcat = "";
					$subcategory = $this->_stdName( $subcat );

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
				foreach( $XML->product as $item ){
					/* tablica właściwości produktu */
					$prop_a = array();
					foreach( $item->specification->property as $property ){
						$prop_a[ (string)$property->name ] = (string)$property->values->value[0];
					}
					
					$code = (string)$item->itemNumber;
					$short = $code;
					$price = $price_a[ $code ];
					$netto = (float)str_replace( ",", ".", $price );
					$brutto = $netto * ( 1 + $this->_vat );
					// $catalog = addslashes( (string)$item-> );
					$cat = addslashes( (string)$item->categories->category[0]->name );
					$category = $this->_stdName( $cat );
					$subcat = "";
					$subcategory = $this->_stdName( $subcat );
					$name = addslashes( (string)$item->name );
					$dscr = addslashes( (string)$item->descriptions );
					$material_a = array();
					$dims = "";
					foreach( $prop_a as $prop_name => $prop_value ){
						if( stripos( $prop_name, 'mater' ) !== false ){
							$material_a[] = $prop_value;
						}
						elseif( stripos( $prop_name, 'rozmiar' ) !== false ){
							$dims = addslashes( $prop_value );
						}
					}
					$material = implode( $material_a, " / " );
					$country = addslashes( (string)$item->countryOfOrigin );
					$weight = (int)$item->individualProductWeightGram;
					$color = (string)$item->primaryColor;
					$photo_a = array();
					foreach( $item->images->image as $img ){
						$photo_a[] = (string)$img;
					}
					$photo = json_encode( $photo_a );
					$new = 0;
					$sale = 0;
					$promotion = 0;
					$marking = "";
					
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

					if( mysqli_query( $this->_dbConnect(), $sql ) === false ){
						$this->_log[] = $sql;
						$this->_log[] = mysqli_error( $this->_dbConnect() );

					}
					
				}

			}
			
		}
		
		// czyszczenie nieaktualnych produktów
		// $this->_clear();
		

		if( !empty( $this->_log ) ){
			echo "<!--ANDA ERROR:" . PHP_EOL;
			print_r( $this->_log ) . PHP_EOL;
			echo "-->";

		}

	}
	
}
