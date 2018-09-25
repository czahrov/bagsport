<?php
require_once __DIR__ . "/php/cfg.php";

$SHOP  = array();

$SHOP['asgard'] = new ASGARD( array(
	'products' => 'https://www.asgard.gifts/www/xml/product_details_pl.xml',
	'prices' => 'https://www.asgard.gifts/www/xml/no_discount_prices.xml',
	'stock' => 'https://www.asgard.gifts/www/xml/stock_data.xml',
	'images' => 'https://www.asgard.gifts/www/xml/product_images.xml',
	'colors' => 'https://www.asgard.gifts/www/xml/colour_list.xml',
),
array(
	'shop' => 'ASGARD',
) );

$SHOP['par'] = new PAR( array(
	'products' => 'http://biuro@bagsport.pl:24816vvv@www.par.com.pl/api/products',
	'stock' => 'http://biuro@bagsport.pl:24816vvv@www.par.com.pl/api/stocks',
),
array(
	'shop' => 'PAR',
) );

$SHOP['midoceanbrands'] = new MIDOCEANBRANDS( array(
	'products' => 'ftp://lucyna:80838286@transfer.midoceanbrands.com/prodinfo_PL.xml',
	'stock' => 'ftp://lucyna:80838286@transfer.midoceanbrands.com/stock.xml',
),
array(
	'shop' => 'MIDOCEANBRANDS',
) );

$SHOP['macma'] = new MACMA( array(
	'products' => 'http://www.macma.pl/data/webapi/pl/xml/offer.xml',
	'stock' => 'http://www.macma.pl/data/webapi/pl/xml/stocks.xml',
	'prices' => 'http://www.macma.pl/data/webapi/pl/xml/prices.xml',	
),
array(
	'shop' => 'MACMA',
) );

$SHOP['inspirion'] = new INSPIRION( array(
	'products' => 'https://inspirion.pl/sites/default/files/exports/products.xml',
),
array(
	'shop' => 'INSPIRION',
) );

$SHOP['anda'] = new ANDA( array(),
array(
	'shop' => 'ANDA',
) );

$SHOP['easygifts'] = new EASYGIFTS( array(
	'products' => 'http://www.easygifts.com.pl/data/webapi/pl/xml/offer.xml',
	'stock' => 'http://www.easygifts.com.pl/data/webapi/pl/xml/stocks.xml',
),
array(
	'shop' => 'EASYGIFTS',
) );

$SHOP['axpol'] = new AXPOL( array(
	'products' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_product_data_PL.xml',
	'stock' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_stocklist_pl.xml',
	'marking' => 'ftp://userPL099:QwqChVFh@ftp.axpol.com.pl/axpol_print_data_PL.xml',
),
array(
	'shop' => 'AXPOL',
) );


$XM = new XMLMan();
foreach( $SHOP as $item ){
	$XM->addSupport( $item );
	
}

