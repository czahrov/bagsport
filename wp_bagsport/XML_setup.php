<?php
require_once __DIR__ . "/php/cfg.php";

$SHOP  = array();
$SHOP['axpol'] = new AXPOL( array(
	'shop' => 'AXPOL',
	'products' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_product_data_PL.xml',
	'stock' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_stocklist_pl.xml',
	'marking' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_print_data_PL.xml',
	
) );

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
