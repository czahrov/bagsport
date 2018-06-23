<?php
	/* Template Name: Hot-produkty */
	get_header();
	$atts = array();
	$order = $_GET['order'];
	$orderby = $_GET['by'];
	if( !empty( $orderby ) ){
		switch( $orderby ){
			case 'cena':
				$atts['orderby'] = 'brutto';
			break;
			
		}
		
		if( !empty( $order ) ){
			switch( $order ){
				case 'rosnąco':
					$atts['order'] = 'ASC';
				break;
				case 'malejąco':
					$atts['order'] = 'DESC';
				break;
				
			}
			
		}
		
	}
	
	$order_infix = "";
	if( !empty( $atts['order'] ) and !empty( $atts['orderby'] ) ) $order_infix = "ORDER BY {$atts['orderby']} {$atts['order']}";
	
	$items = array_map( function( $arg ){
		$arg['ID'] = $arg['code'];
		$arg['nazwa'] = $arg['title'];
		$arg['galeria'] = explode( ",", str_replace( array( "[", "]", '"' ), "", $arg['photos'] ) );
		return $arg;
		
	}, $XM->getProducts( 'custom', "WHERE prod.promotion = 1 AND shop = 'EASYGIFTS' {$order_infix}" ) );
?>
<div id='wlasna' class=''>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
			<h1 class="my-4">
				<span>
					HOT
					<div class="h1-line"></div>
				</span>
				produkty
			</h1>
		</div>
		<?php get_template_part( 'template/segment/filtr' );?>
		<?php printProducts( "", array(), $items ); ?>
		</div>
		<!-- /.row -->
	</div>
</div>
<!-- /.container -->
<?php get_footer(); ?>