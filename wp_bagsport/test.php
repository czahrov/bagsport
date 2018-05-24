<?php
/* Template Name: Testy skryptów */

if( !isset( $_COOKIE['sprytne'] ) ){
	header("Location: " . home_url() );
	exit;
	
}

var_dump( get_post( 'V1298-03' ) );
