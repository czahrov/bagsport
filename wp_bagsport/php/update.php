<?php
require_once __DIR__ . "/autoloader.php";
require_once __DIR__ . "/XML_setup.php";

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

