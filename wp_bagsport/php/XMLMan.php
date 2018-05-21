<?php

class XMLMan{
	private $_proxy = array();
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
	
	// dodawanie obsługi modułu dla sklepu
	public function addSupport( &$handler ){
		$this->_proxy[] = $handler;
		
	}
	
	/* Funkcja aktualizująca pliki XML */
	public function update(){
		foreach( $this->_proxy as $handler ){
			$handler->check();
			
		}
		
	}
	
	/* Funkcja importująca dane z XML do bazy danych */
	public function renew(){
		foreach( $this->_proxy as $handler ){
			$handler->renew();
			
		}
		
	}
	
	// funkcja pobierająca produkty z bazy
	public function getProducts( $mode, $atts ){
		$ret = array();
		if( !empty( $this->_proxy ) ){
			$handle = $this->_proxy[0];
			
			switch( $mode ){
				case 'url':
					$cats = array_slice( explode( ",", $atts ), -2 );
					$parent_id = $handle->getCategory( 'slug', $cats[0], 'ID' );
					$cat_id = $handle->getCategory( 'slug', $cats[1], 'ID' );
					
					if( is_numeric( $parent_id ) && is_numeric( $cat_id ) ){
						$ret = $handle->getCategoryProducts( $cat_id, $parent_id );
						
					}
					
				break;
				case 'single':
					$ret = $handle->getProductsBy( "WHERE `code` = '{$atts}'" );
					
				break;
				case 'custom':
					$ret = $handle->getProductsBy( $atts );
					
				break;
				case 'similar':
					$ret = $handle->getSimilarProducts( $atts );
					
				break;
				
			}
			
		}
		
		return $ret;
		
	}
	
	// funkcja regenerująca przypisania produktów do kategorii
	public function rehash(){
		$this->_proxy[0]->clearHash();
		
		foreach( $this->_proxy as $item ){
			$item->rehash();
			
		}
		
	}
	
}
