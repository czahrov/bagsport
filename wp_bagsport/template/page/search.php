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
					// var_dump( $XM );
					$found = $XM->getProducts( 'custom', "WHERE prod.code LIKE '%{$search}%' OR prod.title LIKE '%{$search}%' OR prod.description LIKE '%{$search}%'" );
					
					$found = array_map( function( $arg ){
						$arg['ID'] = $arg['code'];
						$arg['galeria'] = explode( ",", str_replace( array( "[", "]", '"' ), "", $arg['photos'] ) );
						return $arg;
						
					}, $found );
					
					echo "<!--";
					print_r( $found );
					echo "-->";
					
					printProducts( "", array(), $found );
					
				?>
				
			</div>
			
		</div>
		
	</div>
	
</div>