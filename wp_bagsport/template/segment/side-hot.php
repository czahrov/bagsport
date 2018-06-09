<?php
	global $XM;
	$items = $XM->getProducts( 'custom', "WHERE prod.promotion = 1 OR prod.sale = 1" );
	shuffle( $items );
	$items = array_slice( $items, 0, 20 );
	
?>
<div class="hot-products ">
	<?php 
		if( DMODE ){
			echo "<!--";
			print_r( array_slice( $items, 0, 1 ) );
			echo "-->";
		}
		
	?>
	<h1 class="my-4">
		<span>
			Hot
			<div class="h1-line"></div>
		</span>
		produkty
		<div class="arrow-pagination">
			<i class="arrow ion-ios-arrow-back"></i>
			<i class="arrow ion-ios-arrow-forward"></i>
			
		</div>
	</h1>
	<div class='view d-flex flex-column'>

		<?php foreach( $items as $item ):
			preg_match( "(http[^\"]+)", $item['photos'], $match );
			$img = $match[0];
			$title = $item['title'];
			$price = $item['brutto'] . "zł";
			$code = $item['code'];
		?>
		<div class="hot-products-content d-flex align-items-center">
			<a class='hitbox' href='<?php echo home_url( "produkt/?id={$code}" ); ?>'></a>
			<div class="hot-products-img">
				<div class="hot-img" style="background-image: url( <?php echo $img; ?> );">
				</div>
			</div>
			<div class="hot-content">
				<h4><?php echo $title; ?></h4>
				<h5><?php echo $price; ?></h5>
			</div>
		</div>
		<!-- /.hot single -->  
		<?php endforeach; ?>
	</div>
	
</div>