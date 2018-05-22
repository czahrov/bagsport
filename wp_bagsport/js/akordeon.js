$(function(){
	
	$( '.accordion > li' )
	.click( function( e ){
		$( this )
		.toggleClass( 'active' )
		.children( '.textwidget' )
		.slideToggle();
		
		$( this )
		.siblings()
		.removeClass( 'active' )
		.find( '.textwidget' )
		.slideUp();
		
	} );
	
	$( '.accordion > li > .textwidget' )
	.click( function( e ){
		e.stopPropagation();
		
	} );
	
})