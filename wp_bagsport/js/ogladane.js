/* slider najczęściej oglądanych produktów */
(function( main, navs, view, items ){
	var view_w = view.prop('scrollWidth');
	var paused = false;
	var current = 0;
	var last;
	var delay = 2500;
	var duration = 500;
	var itrv;
	
	main
	.on({
		next: function( e ){
			if( paused ) return false;
			paused = true;
			
			last = current;
			current++;
			current %= items.length;
			
			new TimelineLite({
				align: 'sequenced',
				onComplete: function(){
					TweenLite.set(
						items.eq( last ),
						{
							x: '+=' + view_w,
							
						}
					);
					
					paused = false;
					
				},
				
			})
			.add(
				TweenLite.to(
					items,
					duration / 1000,
					{
						x: '-=' + items.first().outerWidth( true ),
						
					}
				)
			);
			
		},
		prev: function( e ){
			if( paused ) return false;
			paused = true;
			
			current--;
			if( current < 0 ) current = items.length - 1;
			current %= items.length;
			last = ( items.length + current ) % items.length;
			
			console.log( [ last, current ] );
			
			new TimelineLite({
				align: 'sequenced',
				onComplete: function(){
					paused = false;
					
				},
				
			})
			.add(
				TweenLite.set(
					items.eq( last ),
					{
						x: '-=' + view_w,
						
					}
				)
			)
			.add(
				TweenLite.to(
					items,
					duration / 1000,
					{
						x: '+=' + items.first().outerWidth( true ),
						
					}
				)
			);
			
		},
		stop: function( e ){
			window.clearInterval( itrv );
			
		},
		start: function(){
			main.triggerHandler( 'stop' );
			itrv = window.setInterval( function(){
				main.triggerHandler( 'next' );
				
			}, delay );
			
		},
		mouseenter: function( e ){
			main.triggerHandler( 'stop' );
			
		},
		mouseleave: function( e ){
			main.triggerHandler( 'start' );
			
		},
		
	})
	.swipe({
		swipeLeft: function(){
			main.triggerHandler( 'next' );
			
		},
		swipeRight: function(){
			main.triggerHandler( 'prev' );
			
		},
		
	});
	
	navs.click( function( e ){
		switch( $(this).index() ){
			case 0:
				main.triggerHandler( 'prev' );
				
			break;
			case 1:
				main.triggerHandler( 'next' );
				
			break;
			
		}
		
	} );
	
	main.triggerHandler( 'start' );
	
})
(
	$( '.most-popular' ),
	$( '.most-popular .arrow-pagination .arrow' ),
	$( '.most-popular .items' ),
	$( '.most-popular .items .single-item' )
);