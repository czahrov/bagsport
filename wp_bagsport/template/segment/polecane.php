<?php
	global $XM;
	$items = array_map( function( $item ){
		$item['ID'] = $item['code'];
		$item['nazwa'] = $item['title'];
		$item['galeria'] = explode( ",", str_replace( array( "[", "]", '"' ), "", $item['photos'] ) );
		return $item;
		
	}, $XM->getProducts( 'custom', "WHERE prod.new = 1" ) );
	
?>
<div id='polecane' class='d-none d-lg-block'>
	<div class="col-lg-12">
		<h1 class="my-4">
			<span>
				Polecane
				<div class="h1-line"></div>
			</span>
			produkty
		</h1>
	</div>
	<div class='items d-flex flex-wrap'>
	<?php printProducts( "", array(), $items ); ?>
	</div>
</div>