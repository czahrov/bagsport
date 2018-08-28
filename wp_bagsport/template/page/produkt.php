<?php
	$id = $_GET['id'];
	
	if( get_post( $id ) !== null and (string)get_post( $id )->ID === $id ){
		$item = getProductData( get_post( $id ) );
		
	}
	else{
		global $XM;
		$XM->addVisit( $id );
		// $item = $XM->getProducts( 'single', $id );
		$item = getProductData( $XM->getProducts( 'single', $id )[0] );
		
	}
	
?>
<div id='produkt' class=''>
	<?php
		if( DMODE ){
			echo "<!--";
			var_dump( (string)get_post( $id )->ID );
			var_dump( $id );
			print_r( $item );
			echo "-->";
		}
			
	?>
	
	<div class='container'>
		<div class='row'>
			<div id='side' class='col-lg-3'>
			<?php get_template_part('template/segment/side-menu'); ?>
			</div>
			<div class="col-lg-9">
				<div class="row">
					<div class="present col-lg-7 col-md-6 mb-4">
						<div class='popup d-flex align-items-center'>
							<div class='container'>
								<div class='row'>
									<div class='img fc-rozowy col-12 d-flex align-items-center justify-content-between'>
										<i class="nav prev pointer fas fa-chevron-circle-left"></i>
										<i class="nav next pointer fas fa-chevron-circle-right"></i>
										
									</div>
									
								</div>
								
							</div>
							
						</div>
						<?php
							$parts = explode( " ", strip_tags( $item['kategoria'] ) );
							printf(
								'<h1 class="my-4">
									<a href="%s">
										<span>
											%s
											<div class="h1-line"></div>
										</span>
										%s
									</a>
								</h1>',
								home_url( "kategoria/?nazwa={$item['kategoria']}" ),
								array_shift( $parts ),
								implode( " ", $parts )
							);
						?>
						<?php if( empty( $item['ID'] ) ): ?>
						Brak produktu
						<?php else: ?>
						<div class="single-product">
							<div class="arrow-pagination">
								<i class="arrow ion-ios-arrow-back"></i>
								<i class="arrow ion-ios-arrow-forward"></i>
								
							</div>
							<div class="product-img pointer" style="background-image: url(<?php echo $item['galeria'][0]; ?>);"></div>
							
						</div>
						<!-- /.single-product -->
						<div class="product-single-gallery">
							<div class="row view flex-nowrap">
								<?php if( count( $item['galeria'] ) > 1 ) foreach( $item['galeria'] as $img ): ?>
								<div class="item col-6 col-md-6 col-lg-4 mb-4">
									<div class="single-gallery pointer" style="background-image: url(<?php echo $img; ?>);"> </div>
								</div>
								
								<?php endforeach; ?>
							</div>
							<!-- /.single-product-gallery -->
							
						</div>
						<?php endif; ?>
					</div>
					<?php get_template_part('template/segment/produkt-szybki'); ?>
					<!-- /. -->
				</div>
				<!-- /.row -->
				<?php if( !empty( $item['ID'] ) ): ?>
				<div class="row">
					<div class="col-lg-7 col-md-6 mb-4">
						<h3 class="product-title">
							<?php
								printf(
									'%s <small class="fc-rozowy">(%s)</small>',
									$item['nazwa'],
									$item['ID']
									
								);
							?>
						</h3>
					</div>
					<div class="col-lg-4 col-md-6 mb-4 product-price">
						<?php if( $item['cena przed'] > 0 ): ?>
						<h1>
							Cena przed obniżką:
							<span>
								<?php echo $item['cena przed']; ?> zł
							</span>
						</h1>
						<?php endif; ?>
						<?php if( $item['brutto'] > 0 ): ?>
						<h1>
							Cena netto:
							<span>
								<?php echo $item['netto']; ?> zł
							</span>
						</h1>
						<?php else: ?>
						<h1>
							Wycena indywidualna.<br>
							<a href="<?php echo home_url( "zapytaj/?id={$_GET['id']}" ); ?>">
								Wyślij zapytanie aby dowiedzieć się więcej.
							</a>
						</h1>
						<?php endif; ?>
						
						
					</div>
				</div>
				<div class="col-lg-9 col-md-6 mb-4 product-content">
					<div class="row">
						<div class="d-flex flex-column">
							<h1>O produkcie</h1>
							<p class='fc-rozowy'>
								<?php echo $item['opis']; ?>
							</p>
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
				<?php endif; ?>
				
			</div>
			
		</div>
		
	</div>
	
</div>