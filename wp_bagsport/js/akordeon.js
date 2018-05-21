/* (function ($) {
	// $('.accordion > li:eq(0) a').next().slideDown();

	$('.accordion a').click(function (j) {
		var dropDown = $(this).closest('li').find('p');

		$(this).closest('.accordion').find('p').not(dropDown).slideUp();

		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
		} else {
			$(this).closest('.accordion').find('a.active').removeClass('active');
			$(this).addClass('active');
		}

		dropDown.stop(false, true).slideToggle();

		j.preventDefault();
	});
	
	
	
})(jQuery);
 */
 
$(function(){
	$( '.accordion > li' )
	.click( function( e ){
		$( this )
		.toggleClass( 'active' )
		.children( 'p' )
		.slideToggle();
		
		$( this )
		.siblings()
		.removeClass( 'active' )
		.find( 'p' )
		.slideUp();
		
	} );
	
	$( '.accordion > li > p' )
	.click( function( e ){
		e.stopPropagation();
		
	} );
	
})