<div class='menu'>
	<h1 class="my-4">
		<span>
			Kategorie
			<div class="h1-line"></div>
		</span>
		produktów
	</h1>
	<div class="list-group">
		<?php
			$kategorie = array(
				'Biuro i biznes',
				'Czas i pogoda',
				'Do picia',
				'Dom i ogród',
				'Dzieci i zabawa',
				'Elektronika',
				'Materiały piśmiennicze',
				'Narzędzia, latarki i breloki',
				'Parasole i peleryny',
				'Torby i plecaki',
				'Wakacje, sport i rekreacja',
				'Zdrowie i uroda',
				'Świąteczne',
				
			);
			sort( $kategorie );
			
			global $XM;
			foreach( $kategorie as $nazwa ){
				
				$menu_class = '';
				if( stripos( $_SERVER['REQUEST_URI'], 'kategoria' ) ){
					$menu_class = mb_strtolower( $nazwa ) === mb_strtolower( $_GET['nazwa'] )?( ' active' ):( '' );
					
				}
				elseif( stripos( $_SERVER['REQUEST_URI'], 'produkt' ) ){
					// $item = $XM->getProducts( 'single', $_GET['id'] )[0];
					$item = $XM->getProducts( 'single', $_GET['id'] );
					if( count( $item ) > 0 ){
						$menu_class = mb_strtolower( $nazwa ) === mb_strtolower( $item['cat_name'] )?( ' active' ):( '' );
						
					}
					else{
						$menu_class = '';
						
					}
					
				}
				
				printf(
					'<a href="%s/?%s" class="list-group-item%s">%s</a>',
					home_url( "kategoria" ),
					http_build_query( array( 'nazwa' => $nazwa ) ),
					$menu_class,
					$nazwa
					
				);
				
			}
		?>
		<?php wp_nav_menu( array( 'theme_location' => 'menu-sklep' ) ); ?>
	</div>
</div>