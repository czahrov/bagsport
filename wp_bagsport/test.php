<?php
/* Template Name: Testy skryptów */

if( !isset( $_COOKIE['sprytne'] ) ){
	header("Location: " . home_url() );
	exit;
	
}

parse_str( $_SERVER['QUERY_STRING'], $parsed );
$parsed['msg'] = 'test';
print_r( $parsed );
echo http_build_query( $parsed );

