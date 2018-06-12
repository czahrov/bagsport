<div class='filtr col-12'>
	<div class='text'>
		Sortuj według
	</div>
	<!--
	<select id='price'>
		<option value='' selected>Sortuj cenę</option>
		<option>Rosnąco</option>
		<option>Malejąco</option>
		
	</select>
	<select id='subcategory'>
		<option value='' selected>Dostępne kategorie</option>
		<?php
			global $XM;
			$subcats = $XM->subcatsList( $_GET['nazwa'] ); 
			
			foreach( $subcats as $sub ){
				printf(
					'<option>%s</option>',
					$sub['name']
				);
			}
		?>
		
	</select>
	-->
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
	
</div>