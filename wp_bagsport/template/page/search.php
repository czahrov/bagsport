<?php
	$search = $_GET['s'];
	if( empty( $search ) ) header( "Location: " . home_url() );
	
?>
<div id='kategoria' class=''>
	<div class='container'>
		<div class='row align-items-start'>
			<?php get_template_part('template/segment/side', 'panel'); ?>
			<div class='col-lg-9 d-flex flex-wrap'>
				<div class="col-lg-12">
					<?php
						$parts = explode( " ", strip_tags( $search ) );
						printf(
							'<h1 class="my-4">
								<span>
									%s
									<div class="h1-line"></div>
								</span>
								%s
							</h1>',
							array_shift( $parts ),
							implode( " ", $parts )
						);
					?>
				</div>
				<?php
					global $XM;
					/*
						SELECT cat.name as 'cat_name', prod.*
						FROM XML_product as prod
						LEFT JOIN XML_category as cat
						ON prod.cat_id = cat.ID
					*/
					/*
						wyszukiwanie produktu po kilku słowach rozdzielonych spacją
						słowo:
						- może być kodem produktu, lub jego fragmentem		[code]
						- może występować w tytule produktu		[title]
						- może występować w opisie produktu		[description]
						- może występować w rodzaju materiału ( np: plastik )		[materials]
						- może występować w kolorze ( np: czerwony )		[colors]
					*/
					// var_dump( $XM );
					// $found = $XM->getProducts( 'custom', "WHERE prod.code LIKE '%{$search}%' OR prod.title LIKE '%{$search}%' OR prod.description LIKE '%{$search}%'" );
					$sql = "WHERE ";
					$words = explode( " ", $search );
					$fields = array( 'code', 'title', 'description', 'materials', 'colors' );
					$temp_w = array();
					foreach( $words as $word ){
						
						$temp_f = array();
						foreach( $fields as $field ){
							$temp_f[] = sprintf(
								'prod.%s LIKE "%%%s%%"',
								$field,
								$word
								
							);
							
						}
						
						$temp_w[] = " ( " . implode( " OR ", $temp_f ) . " ) ";
					}
					$sql .= implode( " AND ", $temp_w );
					
					$found = $XM->getProducts( 'custom', $sql );
					
					$found = array_map( function( $arg ){
						$arg['ID'] = $arg['code'];
						$arg['nazwa'] = $arg['title'];
						$arg['galeria'] = explode( ",", str_replace( array( "[", "]", '"' ), "", $arg['photos'] ) );
						return $arg;
						
					}, $found );
					
					echo "<!--";
					echo PHP_EOL . $sql . PHP_EOL;
					print_r( array_slice( $found, 0, 3 ) );
					echo "-->";
					
					printProducts( "", array(), $found );
					
				?>
				
			</div>
			
		</div>
		
	</div>
	
</div>