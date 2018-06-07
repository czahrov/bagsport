<?php
/* Template Name: Testy skryptów */

if( !isset( $_COOKIE['sprytne'] ) ){
	header("Location: " . home_url() );
	exit;
	
}

// print_r( $_SERVER );
$url = "http://www.macma.pl/data/shopproducts/12046/083342000.jpg";
$url = "https://inspirion.pl/sites/default/files/exports/products.xml";
$file = fopen( $url );
var_dump( $file );
if( $file !== false ) fclose( $file );
