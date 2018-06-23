<?php
	// $nazwa = $_GET['nazwa'];
	$nazwa = isset( $_GET['podkategoria'] )?( $_GET['podkategoria'] ):( $_GET['nazwa'] );
	
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
				<?php get_template_part( 'template/segment/filtr' );?>
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
						// printProducts( $nazwa, $atts );
						// print_r( $XM->getData( "WHERE cat.name = '{$nazwa}'" ) );
						$sql = sprintf(
							'WHERE cat.name = "%s" OR subcat.name = "%1$s" %s',
							$nazwa,
							( !empty( $atts['orderby'] ) and !empty( $atts['order'] ) )?( "ORDER BY {$atts['orderby']} {$atts['order']}" ):( "" )
							
						);
						
						if( DMODE ){
							echo "<!-- {$sql} -->";
							
						}
						
						printProducts( $nazwa, $atts, $XM->getData( $sql ) );
						
					}
					
				?>
				
			</div>
			
		</div>
		
	</div>
	
</div>