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
				<?php
					global $XM;
					// var_dump( $XM );
					
					echo "<!--";
					print_r( getCategory( $nazwa ) );
					// print_r( $XM->getProducts( 'url', $nazwa ) );
					echo "-->";
					
					if( !empty( $nazwa ) ){
						// $XM->getProducts( 'url', $nazwa );
						printProducts( $nazwa );
						
					}
					
				?>
				
			</div>
			
		</div>
		
	</div>
	
</div>