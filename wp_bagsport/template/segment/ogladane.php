<?php
	global $XM;
	$items = array_map( function( $arg ){
		$arg['ID'] = $arg['code'];
		$arg['nazwa'] = $arg['title'];
		$arg['galeria'] = explode( ",", str_replace( array( "[", "]", '"' ), "", $arg['photos'] ) );
		return $arg;
		
	}, $XM->getProducts( 'mostVisited', array() ) );
	
?>
<div class="most-popular">
	<?php if( DMODE ): ?>
	<!--
	<?php print_r( $items[0] ); ?>
	-->
	<?php endif; ?>
	<div class="container">
		<h1 class="my-4">
			<span>
				Najczęściej
				<div class="h1-line"></div>
			</span>
			oglądane
			<div class="arrow-pagination">
				<i class="arrow ion-ios-arrow-back"></i>
				<i class="arrow ion-ios-arrow-forward"></i>
				
			</div>
		</h1>
		<div class="col-lg-12">
			<div class="items row minus-margin flex-nowrap">
				<?php printProducts( "", array(), $items ); ?>
			</div>
			<!-- /.row -->
		</div>
	</div>
	<!-- /.container -->
</div>
