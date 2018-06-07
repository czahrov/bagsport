<?php
require_once __DIR__ . "/php/cfg.php";

$SHOP  = array();

$SHOP['axpol'] = new AXPOL( array(
	'products' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_product_data_PL.xml',
	'stock' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_stocklist_pl.xml',
	'marking' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_print_data_PL.xml',
	
),
array(
	'shop' => 'AXPOL',
	
) );
/* $SHOP['easygifts'] = new EASYGIFTS( array(
	'products' => 'http://www.easygifts.com.pl/data/webapi/pl/xml/offer.xml',
	'stock' => 'http://www.easygifts.com.pl/data/webapi/pl/xml/stocks.xml',
	
),
array(
	'shop' => 'EASYGIFTS',
) ); */
/* $SHOP['macma'] = new MACMA( array(
	'products' => 'http://www.macma.pl/data/webapi/pl/xml/offer.xml',
	'stock' => 'http://www.macma.pl/data/webapi/pl/xml/stocks.xml',
	'prices' => 'http://www.macma.pl/data/webapi/pl/xml/prices.xml',
	
),
array(
	'shop' => 'MACMA',
	
) ); */
/* $SHOP['inspirion'] = new INSPIRION( array(
	'products' => 'https://inspirion.pl/sites/default/files/exports/products.xml',
	
),
array(
	'shop' => 'INSPIRION',
	
) ); */

// $EASYGIFTS = new EASYGIFTS();
// $MACMA = new MACMA();
// $ANDA = new ANDA();
// $FALKROSS = new FALKROSS();
/* $jaguar_auth = array(
	'http' => array(
		'method' => "GET",
		'header' => implode( "\r\n", array(
			'Accept-language: pl',
			'Authorization: Token b877f60a12c850f74a169fa036265f852b38be79'
		) ),
	)
); */
// $JAGUARGIFT = new JAGUARGIFT( $jaguar_auth );
// $ASGARD = new ASGARD();
// $INSPIRION = new INSPIRION();
// $PAR = new PAR();

$XM = new XMLMan();
foreach( $SHOP as $item ){
	$XM->addSupport( $item );
	
}

/* if( isset( $_GET[ 'renew' ] ) ){
	if( isset( $_GET[ 'axpol' ] ) ) $AXPOL->renew();
	
}
elseif( isset( $_GET[ 'rehash' ] ) ){
	$XM->rehash();
	
} */
