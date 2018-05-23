<div id='quick' class='d-flex justify-content-between align-items-end flex-xl-column align-items-xl-start'>
	<div class='panel social d-flex flex-column flex-xl-row-reverse'>
		<div class='icon pointer d-flex align-items-center justify-content-center'>
			<i class="fas fa-share-alt"></i>
		</div>
		<div class='sub d-flex flex-column flex-xl-row'>
			<a class='item pointer fb' href='<?php echo getInfo( 'facebook' ) ?>' target="_blank">
				<div class='icon d-flex align-items-center justify-content-center'>
					<i class="fab fa-facebook"></i>
					
				</div>
				
			</a>
			
		</div>
		
	</div>
	<div class='panel contact d-flex flex-column flex-xl-row-reverse'>
		<div class='icon pointer d-flex align-items-center justify-content-center'>
			<i class="far fa-address-card"></i>
		</div>
		<div class='sub d-flex flex-column flex-xl-row'>
			<a class='item pointer mail' href='<?php printf( 'mailto:%s', getInfo( 'kontakt_e-mail' ) ); ?>'>
				<div class='icon d-flex align-items-center justify-content-center'>
					<i class="fas fa-envelope"></i>
					
				</div>
				
			</a>
			<a class='item pointer phone' href='<?php printf( 'tel:%s', str_replace( " ", "", getInfo( 'infolinia' ) ) ); ?>'>
				<div class='icon d-flex align-items-center justify-content-center'>
					<i class="fas fa-mobile-alt"></i>
					
				</div>
				
			</a>
			
		</div>
		
	</div>
	
</div>