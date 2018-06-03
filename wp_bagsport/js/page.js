/* skrypt obsługujący galerię w widoku strony pojedynczej */
(function( gallery, popup, small, nav_img ){
	var current = 0;
	var set;
	var TL_popup = new TimelineLite({
		paused: true,
		onStart: function(){
			popup.triggerHandler('loadImg');
			popup.addClass('open');
			
		},
		onReverseComplete: function(){
			popup.removeClass('open');
			
		},
		align: 'sequence',
		
	})
	.add( TweenLite.fromTo(
		popup,
		.2,
		{
			opacity: 0,
		},
		{
			opacity: 1,
		}
	) )
	.add( TweenLite.fromTo(
		popup.find('.img'),
		.3,
		{
			opacity: 0,
			scale: .6,
		},
		{
			opacity: 1,
			scale: 1,
			
		}
	) )
	
	popup
	.on({
		open: function( e ){
			TL_popup.play();
			
		},
		close: function( e ){
			TL_popup.reverse();
			
		},
		loadImg: function( e ){
			popup
			.find('.img')
			.css({
				backgroundImage: function(){
					return set.eq( current ).css('background-image');
					
				},
				
			});
			
		},
		next: function( e ){
			current++;
			current %= set.length;
			
		},
		prev: function( e ){
			current--;
			if( current < 0 ) current = set.length - 1;
			
		},
		click: function( e ){
			popup.triggerHandler('close');
			
		},
		wheel: function( e ){
			e.preventDefault();
			e.stopPropagation();
			
		},
		
	});
	
	small.click( function( e ){
		current = $(this).parent().index();
		set = $(this).closest('.custom_gallery').find('.item');
		popup.triggerHandler( 'open' );
		
	} );
	
	popup.find('.img').click( function( e ){
		e.stopPropagation();
	} );
	
	nav_img.click( function( e ){
		if( $(this).hasClass('prev') ){
			popup.triggerHandler('prev');
			
		}
		else{
			popup.triggerHandler('next');
			
		}
		
		popup.triggerHandler('loadImg');
		
	} );
	
})
(
	$( '#page .custom_gallery' ),
	$( '#page .gallery_popup' ),
	$( '#page .custom_gallery .item' ),
	$( '#page .gallery_popup .img .nav' )
);