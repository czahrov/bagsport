<div id='page' class="">
	<?php if( has_post_thumbnail( get_post()->ID ) ): ?>
	<div id='banner' class='' style='background-image:url(<?php echo get_the_post_thumbnail_url( get_post()->ID, 'full' ); ?>);'></div>
	<?php endif; ?>
	<div id='kontakt' class='container'>
		<div class="row">
			<div class='col-12 col-md-6'>
				<div class='cell kontakt col-12'>
					<h2>
						Dane kontaktowe
					</h2>
					<div class='row'>
						<div class='col-3'>Adres firmy: </div>
						<a class='' href="<?php
							printf(
								'https://maps.google.com/?q=%s',
								getInfo('adres_firmy')
								
							);
						?>" target='_blank'>
							<?php echo getInfo('adres_firmy'); ?>
						</a>
					</div>
					<div class='row'>
						<div class='col-3'>Telefon: </div>
						<a class='' href="<?php
							printf(
								'tel:%s',
								preg_replace( '~\D+~', '', getInfo('infolinia') )
								
							);
						?>" >
							<?php echo getInfo('infolinia'); ?>
						</a>
					</div>
					<div class='row'>
						<div class='col-3'>Stacjonarny: </div>
						<a class='' href="<?php
							printf(
								'tel:%s',
								preg_replace( '~\D+~', '', getInfo('stacjonarny') )
								
							);
						?>" >
							<?php echo getInfo('stacjonarny'); ?>
						</a>
					</div>
					<div class='row'>
						<div class='col-3'>E-mail:</div>
						<a class='' href="<?php
							printf(
								'mailto:%s',
								getInfo('kontakt_e-mail')
								
							);
						?>" >
							<?php echo getInfo('kontakt_e-mail'); ?>
						</a>
					</div>
					
				</div>
				<div class='cell odwiedz d-flex flex-column col-12'>
					<h2>
						Odwiedź nas
					</h2>
					<script>
						home_address = "<?php echo getInfo('adres_firmy') ?>";
					</script>
					<div id='mapa' class=''></div>
					
				</div>
				
			</div>
			<div class='col-12 col-md-6'>
				<div class='cell napisz col-12'>
					<h2>
						Napisz do nas
					</h2>
					<?php echo do_shortcode( "[ninja_form id=2]" ); ?>
				</div>
				
			</div>
			
		</div>
		<!--
		<?php
			print_r( getInfo() );
		?>
		-->
		
	</div>
	<!-- /.row -->
</div>