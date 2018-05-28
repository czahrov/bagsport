<?php
/* Template Name: Testy skryptów */

if( !isset( $_COOKIE['sprytne'] ) ){
	header("Location: " . home_url() );
	exit;
	
}

print_r( $_SERVER );

