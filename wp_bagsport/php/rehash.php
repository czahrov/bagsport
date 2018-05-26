<?php
require_once __DIR__ . "/autoloader.php";
require_once __DIR__ . "/../XML_setup.php";

printf(
	'%1$s--- REHASH ---%1$s',
	PHP_EOL
);

foreach( $SHOP as $name => $single ){
	echo "{$name}:";
	$start = microtime( true );
	$single->rehash();
	
	$stop = microtime( true );
	printf(
		'%.2fs%s',
		$stop - $start,
		PHP_EOL
		
	);
	
}

echo "-- Czyszczenie pustych kategorii: ";
$start = microtime( true );
current( $SHOP )->clearEmptyCats();
$stop = microtime( true );
printf(
	'%.2fs%s',
	$stop - $start,
	PHP_EOL
	
);

