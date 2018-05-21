<?php

require_once "autoloader.php";

$input = <<<EOD
<xml>
  <node>
		<nid>21859</nid>
		<product_name>Lusterko kosmetyczne z lampką Led, DIVA, srebrny</product_name>
		<sku>56-0402336</sku>
		<product_path>http://inspirion.pl/product/56-0402336</product_path>
		<body>Lusterko kosmetyczne DIVA , podręczne składane lusterko z lampką LED.</body>
		<catalog>Kosmetyka</catalog>
		<catalog_special/>
		<catalog_price>6,99 zł</catalog_price>
		<product_images>http://inspirion.pl/sites/default/files/56-0402336_0.jpg</product_images>
		<manufacturer>TOPS</manufacturer>
		<kolor>srebrny</kolor>
		<weight>0.05kg</weight>
		<weight_gross>0.05kg</weight_gross>
		<material>Plastik / Szkło</material>
		<overprints>Druk bezpośredni, Grawer laserowy, Grawer laserowy, Tampodruk</overprints>
		<package>200</package>
		<subpackage>50</subpackage>
		<wymiary>fi=7 x 1,6 cm</wymiary>
		<catalog_page>251</catalog_page>
		<package_length>36.50cm</package_length>
		<package_width>21.50cm</package_width>
		<pacakge_height>36.00cm</pacakge_height>
		<Customs-codes>70099200000</Customs-codes>
		<Imprint-size>Druk bezpośredni:fi=25mm, Grawer laserowy:18x27mm, Tampodruk:fi=23mm</Imprint-size>
		<Batteries-qty/>
		<Battery-contains/>
		<Battery-type/>
		<Special-package/>
	</node>
</xml>
EOD;

$xml = simplexml_load_string( $input );

//echo getcwd();
/*
/home/users/scepterssd/public_html/poligon/DawidW/Merkuriusz
/home/users/scepterssd/public_html/poligon/DawidW/Merkuriusz
*/

//print_r( json_decode( file_get_contents( __DIR__ . "/PAR/CACHE/indexer.php" ), true ) );
//print_r( json_decode( file_get_contents( __DIR__ . "/PAR/CACHE/cat_torby_na_zakupy.php" ), true ) );

//preg_match_all( "~http://inspirion.pl/.*?\.\w{3}~", (string)$xml->node->product_images, $img );
//print_r( $img );

//phpinfo();

//print_r( glob( __DIR__ . "/ASGARD/DND/*.*" ) );
array_map( function( $file ){
	$data = date( "d/m/Y H:i:s", filemtime( $file ) );
	echo "\r\n[$data][$file]\r\n";
	
}, glob( __DIR__ . "/*/DND/*.*" ) );