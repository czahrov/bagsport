<?php
	
?>
<div id='kategoria' class=''>
	<div class='container'>
		<div class='row'>
			<?php get_template_part('template/segment/side', 'panel'); ?>
			<div class='col-lg-9 d-flex flex-wrap'>
				<?php
					$nazwa = $_GET['nazwa'];
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