/* slider partnerów */
(function (slider, viewbox, items){
	var num = items.length;
	var current = 0;
	var duration = 3000;
	var delay = 0;
	var vbox_width = viewbox.prop( 'scrollWidth' );
	
	slider
	.on({
		play: function( e ){
			var self = $(this);
			
			TweenLite.to(
				items,
				duration / 1000,
				{
					x: '-=' + items.eq( current ).outerWidth( true ),
					ease: Linear.easeNone,
					onComplete: function( e ){
						TweenLite.set(
							items.eq( current ),
							{
								x: '+=' + vbox_width,
							}
						);
						
						current++;
						current %= num;
						
						window.setTimeout( function(){
							self.triggerHandler( 'play' );
							
						}, delay );
						
					},
					
				}
				
			)
			
		},
		
	});
	
	slider.triggerHandler( 'play' );
	
})
(
	$('#partnerzy'),
	$('#partnerzy .view'),
	$('#partnerzy .view .item ')
	
);
