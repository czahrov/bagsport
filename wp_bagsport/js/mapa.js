(function( mapa ){
	if( mapa.length == 0 ) return false;
	$.gmap3({
		key: 'AIzaSyAyaC8rMFmCUI_Wa0rCdPfn8CCYearGTlY'
		
	});
	
	$(function(){
		mapa
		.gmap3({
			address: window.home_address,
			zoom: 16,
			mapTypeControl: false,
			
		})
		.marker({
			address: window.home_address,
			icon: "",
			
		});
		
	});
	
})
(
	$( '#mapa' )
);