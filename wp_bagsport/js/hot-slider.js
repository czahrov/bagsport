/* slider hot produkty */
(function( main, view, items, navs ){
	var paused = false;
	var current = 0;
	var view_h = view.prop( 'scrollHeight' );
	var distance = items.first().outerHeight( true );
	var delay = 2500;
	var duration = 500;
	var itrv;
	
	main
	.on({
		stop: function( e ){
			window.clearInterval( itrv );
			
		},
		start: function( e ){
			main.triggerHandler( 'stop' );
			itrv = window.setInterval( function(){
				main.triggerHandler( 'next' );
				// main.triggerHandler( 'prev' );
				
			}, delay );
			
		},
		next: function( e ){
			if( !paused ){
				paused = true;
				
				current %= items.length;
				
				new TimelineLite({
					align: 'sequenced',
					onComplete: function(){
						current++;
						// console.log( current );
						paused = false;
				
					},
					
				})
				.add( TweenLite.to(
					items,
					duration / 1000,
					{
						y: '-=' + distance,
						ease: Linear.easeNone,
						
					}
				) )
				.add( TweenLite.set(
					items.eq( current ),
					{
						// y: view_h - distance * current,
						y: '+=' + view_h,
						
					}
					
				) );
				
			}
			
		},
		prev: function( e ){
			if( !paused ){
				paused = true;
				
				current--;
				if( current < 0 ) current = items.length - 1;
				current %= items.length;
				// console.log( items.eq( items.length - 1 - current  ) );
				
				new TimelineLite({
					align: 'sequenced',
					onComplete: function(){
						// console.log( current );
						paused = false;
				
					},
					
				})
				.add( TweenLite.set(
					items.eq( current  ),
					{
						y: '-=' + view_h,
						
					}
					
				) )
				.add( TweenLite.to(
					items,
					duration / 1000,
					{
						y: '+=' + distance,
						ease: Linear.easeNone,
						
					}
				) );
				
			}
			
		},
		mouseenter: function( e ){
			main.triggerHandler( 'stop' );
			
		},
		mouseleave: function( e ){
			main.triggerHandler( 'start' );
			
		},
		
	})
	.swipe({
		swipeUp: function( e ){
			main.triggerHandler( 'next' );
			
		},
		swipeDown: function( e ){
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
	$( '.hot-products' ),
	$( '.hot-products .view' ),
	$( '.hot-products .view .hot-products-content' ),
	$( '.hot-products .arrow-pagination .arrow' )
);