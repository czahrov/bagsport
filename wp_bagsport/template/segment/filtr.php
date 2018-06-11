<div class='filtr col-12'>
	<div class='text'>
		Sortuj według
	</div>
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
	
</div>