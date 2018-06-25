<div class='filtr col-12'>
	<div class='text'>
		Sortuj według
	</div>
	<div class='customSelect price'>
		<div class='head'>
			Cena
			<div class='icon fas fa-chevron-down'></div>
		</div>
		<div class='list'>
			<div class='item'>
				Brak
			</div>
			<div class='item'>
				Rosnąco
			</div>
			<div class='item'>
				Malejąco
			</div>
			
		</div>
		
	</div>
	<?php if( !is_search() ): ?>
	<div class='customSelect subcategory'>
		<div class='head'>
			Podkategorie
			<div class='icon fas fa-chevron-down'></div>
		</div>
		<div class='list'>
			<div class="item">
				Brak
			</div>
			<?php
				global $XM;
				$subcats = $XM->subcatsList( $_GET['nazwa'] ); 
				
				if( DMODE ){
					echo "<!--";
					print_r( $subcats );
					echo "-->";
				}
				
				foreach( $subcats as $sub ){
					printf(
						'<div class="item">
							%s
						</div>',
						$sub['name']
					);
				}
			?>
			
		</div>
		
	</div>
	<?php endif; ?>
</div>