<?php

require_once "autoloader.php";
require_once "cfg.php";

$SHOP  = array();

$jaguar_auth = array(
	'http' => array(
		'method' => "GET",
		'header' => implode( "\r\n", array(
			'Accept-language: pl',
			'Authorization: Token b877f60a12c850f74a169fa036265f852b38be79'
		) ),
	)
);

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
// $JAGUARGIFT = new JAGUARGIFT( $jaguar_auth );
// $ASGARD = new ASGARD();
// $INSPIRION = new INSPIRION();
// $PAR = new PAR();

foreach( $SHOP as $name => $single ){
	$start = microtime( true );
	$single->update();
	$single->renew();
	
	$stop = microtime( true );
	echo PHP_EOL;
	printf(
		'%s %.2fs%s',
		$name,
		$stop - $start,
		PHP_EOL
		
	);
	
}

