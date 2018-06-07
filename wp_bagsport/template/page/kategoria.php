<?php
	$nazwa = $_GET['nazwa'];
	
?>
<div id='kategoria' class=''>
	<div class='container'>
		<div class='row align-items-start'>
			<?php get_template_part('template/segment/side', 'panel'); ?>
			<div class='col-lg-9 d-flex flex-wrap'>
				<div class="col-lg-12">
					<?php
						$parts = explode( " ", strip_tags( $nazwa ) );
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
				<div class='filtr col-12'>
					<div class='text'>
						Sortuj według
					</div>
					<select id='price'>
						<option value='' selected>Sortuj cenę</option>
						<option value='ASC'>Rosnąco</option>
						<option value='DESC'>Malejąco</option>
						
					</select>
					<select id='subcategory'>
						<option value='' selected>Dostępne kategorie</option>
						<option>Kategoria1</option>
						<option>Kategoria2</option>
						<option>Kategoria3</option>
						
					</select>
					
				</div>
				<?php
					global $XM;
					// var_dump( $XM );
					$atts = array();
					$order = $_GET['order'];
					$orderby = $_GET['by'];
					if( !empty( $orderby ) ){
						switch( $orderby ){
							case 'cena':
								$atts['orderby'] = 'brutto';
							break;
							
						}
						
						if( !empty( $order ) ){
							switch( $order ){
								case 'rosnąco':
									$atts['order'] = 'ASC';
								break;
								case 'malejąco':
									$atts['order'] = 'DESC';
								break;
								
							}
							
						}
						
					}
					
					if( DMODE ){
						echo "<div hidden><!--";
						print_r( $atts );
						print_r( getCategory( $nazwa, $atts ) );
						// print_r( $XM->getProducts( 'url', $nazwa ) );
						echo "--></div>";
						
					}
					
					if( !empty( $nazwa ) ){
						// $XM->getProducts( 'url', $nazwa );
						printProducts( $nazwa, $atts );
						
					}
					
				?>
				
			</div>
			
		</div>
		
	</div>
	
</div>