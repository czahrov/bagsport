/* skrypt obsługujący galerię w widoku produktu */
(function( main, small, nav ){
	var current = 0;
	
	small
	.click( function( e ){
		current = $(this).index();
		
		main
		.css( 'background-image', $(this).children( '.single-gallery' ).css( 'background-image' ) );
		
	} );
	
	nav
	.click( function( e ){
		switch( $(this).index() ){
			case 0:
				current--;
			break;
			case 1:
				current++;
			break;
			
		}
		
		if( current < 0 ) current = small.length - 1;
		current %= small.length;
		
		main
		.css( 'background-image', small.eq( current ).children( '.single-gallery' ).css( 'background-image' ) );
		
	} );
	
})
(
	$( '#produkt .present .product-img' ),
	$( '#produkt .present .product-single-gallery .item' ),
	$( '#produkt .present .arrow-pagination .arrow' )
);