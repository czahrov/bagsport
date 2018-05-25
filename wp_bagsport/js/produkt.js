/* skrypt obsługujący galerię w widoku produktu */
(function( main, popup, viewbox, small, nav, nav_img ){
	var current = 0;
	var vbox_scroll = viewbox.prop('scrollWidth');
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
	
	/* chowa/pokazuje przyciski nawigacyjne */
	if( viewbox.width() < viewbox.prop('scrollWidth') - 1 ){
		nav.parent().show();
	}
	else{
		nav.parent().hide();
	}
	
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
				/* wstecz */
				TweenLite.to(
					viewbox,
					0.3,
					{
						scrollLeft: function(){
							return '-=' + small.first().outerWidth(true) + 'px';
						},
					}
				);
				
			break;
			case 1:
				/* dalej */
				TweenLite.to(
					viewbox,
					0.3,
					{
						scrollLeft: function(){
							return '+=' + small.first().outerWidth(true) + 'px';
						},
					}
				);
			break;
			
		}
		
	} );
	
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
					return main.css('background-image');
					
				},
				
			});
			
		},
		next: function( e ){
			current++;
			current %= small.length;
			main
			.css({
				backgroundImage: function( e ){
					return small.eq(current).find('.single-gallery').css('background-image');
					
				},
				
			});
			
		},
		prev: function( e ){
			current--;
			if( current < 0 ) current = small.length - 1;
			main
			.css({
				backgroundImage: function( e ){
					return small.eq(current).find('.single-gallery').css('background-image');
					
				},
				
			});
			
		},
		click: function( e ){
			popup.triggerHandler('close');
			
		},
		wheel: function( e ){
			e.preventDefault();
			e.stopPropagation();
			
		},
		
	});
	
	popup.find('.img').click( function( e ){
		e.stopPropagation();
	} );
	
	main.click( function( e ){
		popup.triggerHandler('open');
		
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
	$( '#produkt .present .product-img' ),
	$( '#produkt .present .popup' ),
	$( '#produkt .present .product-single-gallery .view' ),
	$( '#produkt .present .product-single-gallery .view .item' ),
	$( '#produkt .present .arrow-pagination .arrow' ),
	$( '#produkt .present .popup .img .nav' )
);