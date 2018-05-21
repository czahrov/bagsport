<?php

require_once "php/cfg.php";

$jaguar_auth = array(
	'http' => array(
		'method' => "GET",
		'header' => implode( "\r\n", array(
			'Accept-language: pl',
			'Authorization: Token b877f60a12c850f74a169fa036265f852b38be79'
		) ),
	)
);

$AXPOL = new AXPOL( array(
	'shop' => 'AXPOL',
	'products' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_product_data_PL.xml',
	'stock' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_stocklist_pl.xml',
	'marking' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_print_data_PL.xml',
	
) );
// $EASYGIFTS = new EASYGIFTS();
// $MACMA = new MACMA();
// $ANDA = new ANDA();
// $FALKROSS = new FALKROSS();
// $JAGUARGIFT = new JAGUARGIFT( $jaguar_auth );
// $ASGARD = new ASGARD();
// $INSPIRION = new INSPIRION();
// $PAR = new PAR();

// $AXPOL->renew();

$XM = new XMLMan;
if( isset( $AXPOL ) ) $XM->addSupport( $AXPOL );

if( isset( $_GET[ 'renew' ] ) ){
	$start = microtime( true );
	
	if( isset( $_GET[ 'axpol' ] ) ) $AXPOL->renew();
	
	$stop = microtime( true );
	printf( "%u[s]\r\n", $stop - $start ) . PHP_EOL;
	echo date( "H:i:s d-m-Y" ) . PHP_EOL;
}
elseif( isset( $_GET[ 'rehash' ] ) ){
	$start = microtime( true );
	
	$XM->rehash();
	
	$stop = microtime( true );
	printf( "%u[s]\r\n", $stop - $start ) . PHP_EOL;
	echo date( "H:i:s d-m-Y" ) . PHP_EOL;
}
