<?php
	/* Template Name: Hot-produkty */
	get_header();
	
	$items = array_map( function( $arg ){
		$arg['ID'] = $arg['code'];
		$arg['nazwa'] = $arg['title'];
		$arg['galeria'] = explode( ",", str_replace( array( "[", "]", '"' ), "", $arg['photos'] ) );
		return $arg;
		
	}, $XM->getProducts( 'custom', "WHERE prod.promotion = 1 OR prod.sale = 1" ) );
?>
<div id='wlasna' class=''>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
			<h1 class="my-4">
				<span>
					HOT
					<div class="h1-line"></div>
				</span>
				produkty
			</h1>
		</div>
		<?php printProducts( "", array(), $items ); ?>
		</div>
		<!-- /.row -->
	</div>
</div>
<!-- /.container -->
<?php get_footer(); ?>