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
);

/* zsuwanie i rozsuwanie segmentów a panelu bocznym na mobile */
(function( polecane, menu, hot, faq ){
	
	polecane
	.on({
		show: function( e ){
			
		},
		hide: function( e ){
			console.log( [ this, $(this) ] );
			
		},
		reset: function( e ){
			
		},
		
	});
	
})
(
	$('#polecane'),
	$('#side > .menu'),
	$('#side > .hot-products'),
	$('#side > .faq')
);

/* funkcja modyfikująca zapytanie URL */
function queryMod( args ){
	var query = window.location.search;
	if( typeof args === 'undefined' ) return query;
	
	$.each( args, function( name, value ){
		/* usuwanie zmiennej */
		if( value === null ){
			var search = new RegExp( name + "=[^&]+&?" );
			query = query.replace( search, "" );
			
		}
		/* zmienna już istnieje */
		else if( new RegExp( name + "=" ).test( query ) ){
			var search = new RegExp( name + "=[^&]+" );
			var replace = name + "=" + value;
			query = query.replace( search, replace );
			
		}
		/* dodawanie zmiennej */
		else{
			query += "&" + name;
			
			if( typeof value !== 'undefined' ) query += "=" + value;
		}
		
	} );
	
	return query;
}

/* sortowanie produktów */
(function( cena, podkategoria ){
	cena
	.on( 'click', '.list .item', function( e ){
		var val = $(this).text().trim();
		if( val !== 'Brak' ){
			window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + queryMod( {by: 'cena', order: val.toLowerCase()} );
		}
		else{
			window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + queryMod( {by: null, order: null} );
		}
		
	} );
	
	podkategoria
	.on( 'click', '.list .item', function( e ){
		var val = $(this).text().trim();
		if( val !== 'Brak' ){
			window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + queryMod( {podkategoria: val.toLowerCase()} );
		}
		else{
			window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + queryMod( {podkategoria: null} );
		}
		
	} );
	
})
(
	$( '.customSelect.price' ),
	$( '.customSelect.subcategory' )
)
