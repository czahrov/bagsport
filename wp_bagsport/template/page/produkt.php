<?php
	$id = (int)$_GET['id'];
	$item = getProductData( $id );
	
?>
<div id='produkt' class=''>
	<?php
		echo "<!--";
		print_r( $item );
		echo "-->";
	?>
	<div class='container'>
		<div class='row'>
			<div id='side' class='col-lg-3'>
			<?php get_template_part('template/segment/side-menu'); ?>
			</div>
			<div class="col-lg-9">
				<div class="row">
					<div class="present col-lg-7 col-md-6 mb-4">
						<h1 class="my-4">
							<span>
								<div class="h1-line"></div>
							</span>
							<?php echo $item['kategoria']; ?>
						</h1>
						<div class="single-product">
							<div class="arrow-pagination">
								<i class="arrow ion-ios-arrow-back"></i>
								<i class="arrow ion-ios-arrow-forward"></i>
							</div>
							<div class="product-img" style="background-image: url(<?php echo $item['galeria'][0]; ?>);"></div>
						</div>
						<!-- /.single-product -->
						<div class="product-single-gallery">
							<div class="row">
								<?php if( count( $item['galeria'] ) > 1 ) foreach( $item['galeria'] as $img ): ?>
								<div class="item col-lg-4 col-md-6 mb-4">
									<div class="single-gallery pointer" style="background-image: url(<?php echo $img; ?>);"> </div>
								</div>
								<?php endforeach; ?>
							</div>
							<!-- /.single-product-gallery -->
						</div>
					</div>
					<?php get_template_part('template/segment/produkt-szybki'); ?>
					<!-- /. -->
				</div>
				<!-- /.row -->
				<div class="row">
					<div class="col-lg-7 col-md-6 mb-4">
						<h3 class="product-title">
							<?php echo $item['nazwa']; ?>
						</h3>
					</div>
					<div class="col-lg-4 col-md-6 mb-4 product-price">
						<h1>
							Cena brutto:
							<span>
								<?php echo $item['brutto']; ?> zł
							</span>
						</h1>
					</div>
				</div>
				<div class="col-lg-9 col-md-6 mb-4 product-content">
					<div class="row">
						<h1>O produkcie</h1>
						<p>
							<?php echo $item['opis']; ?>
						</p>
						<div class="d-flex flex-column">
							<h1>Dostępność w magazynie:
								<span>
									<?php echo $item['dostępność'] ?>
								</span>
							</h1>
							<h1>Kolor:
								<span>
									<?php echo $item['kolor']; ?>
								</span>
							</h1>
							<h1>Wymiary:
								<span>
									<?php echo $item['wymiary'] ?>
								</span>
							</h1>
						</div>
					</div>
				</div>
			</div>			
		</div>
		
	</div>
	
</div>