<div class="partners-slider">
	<h1>
		<span>
			Zaufali
		</span>
		nam
	</h1>
	<div id='partnerzy' class="slider-content">
		<script>
			<?php printf(
					'partners_slider = {
						duration: %u,
						delay: %u,
					};',
					getInfo( 'przejście' ),
					getInfo( 'opoźnienie' )
					
				); ?>
		</script>
		<div class="view view-steps d-flex flex-nowrap">
			<?php foreach( extractGallery( getInfo( 'slajdy' ) ) as $url ): ?>
			<div class="item" style="background-image:url(<?php echo $url; ?>);"></div>
			<?php endforeach; ?>
			
		</div>
		
	</div>
	
</div>