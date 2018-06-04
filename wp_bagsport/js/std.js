/* efekty paska wyszukiwania */
(function( main, btn, input, menubar ){
	var opened = false;
	
	main
	.on({
		show: function( e ){
			opened = true;
			
			new TimelineLite({
				align: 'sequence',
			})
			.add(
				TweenLite.to(
					[ input, menubar ],
					.3,
					{
						yPercent: -100,
					}
				)
			)
			
		},
		hide: function( e ){
			opened = false;
			
			new TimelineLite({
				align: 'sequence',
			})
			.add(
				TweenLite.to(
					[ input, menubar ],
					.5,
					{
						yPercent: 0,
					}
				)
			)
			
		},
		reset: function( e ){
			opened = false;
			
			TweenLite.set(
				[ input, menubar ],
				{
					yPercent: 0,
				}
			);
			
		},
		
	})
	
	btn
	.on({
		click: function( e ){
			if( !opened && window.innerWidth >= 992 ){
				e.preventDefault();
				main.triggerHandler( 'show' );
				
			}
			else{
				main.triggerHandler( 'reset' );
				
			}
			
		},
		
	});
	
	input
	.on({
		blur: function( e ){
			main.triggerHandler( 'hide' );
			
		},
		
	});
	
	$( window ).resize( function( e ){
		if( window.innerWidth < 992 && opened ) main.triggerHandler( 'reset' );
		
	} );
	
})
(
	$('#navbarResponsive #search'),
	$('#navbarResponsive #search button'),
	$('#navbarResponsive #search input'),
	$('#navbarResponsive .navbar-nav')
)