<?php
/* Template Name: Testy skryptów */

if( !isset( $_COOKIE['sprytne'] ) ){
	header("Location: " . home_url() );
	exit;
}

$stat = stat( __DIR__ . "/php/ANDA/DND/anda_xml_export2.xml" );
print( $stat );
print_r( array(
	'atime' => date( 'd-m-Y H:i:s', $stat['atime'] ),
	'ctime' => date( 'd-m-Y H:i:s', $stat['ctime'] ),
	'mtime' => date( 'd-m-Y H:i:s', $stat['mtime'] ),
) );
