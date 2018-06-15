<?php
require_once __DIR__ . "/autoloader.php";
require_once __DIR__ . "/../XML_setup.php";

printf(
	'%1$s--- UPDATE ---%1$s',
	PHP_EOL
);

global $SHOP;
reset( $SHOP );
var_dump( current ($SHOP ) );
current( $SHOP )->clearCats();

foreach( $SHOP as $name => $single ){
	$start = microtime( true );
	echo "{$name}:";
	$single->update();
	$single->renew();
	
	$stop = microtime( true );
	printf(
		'%.2fs%s',
		$stop - $start,
		PHP_EOL
		
	);
	
}

