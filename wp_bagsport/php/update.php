<?php
$time = 60 * 30;
set_time_limit( $time );
require_once __DIR__ . "/autoloader.php";
require_once __DIR__ . "/../XML_setup.php";

printf(
	'%1$s--- UPDATE [%2$s] ---%1$s',
	PHP_EOL,
	date("Y-m-d H:i:s")
);

global $SHOP;
reset( $SHOP );
// var_dump( current ($SHOP ) );
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

printf(
	'%1$s--- [%2$s] ---%1$s',
	PHP_EOL,
	date("Y-m-d H:i:s")
);

