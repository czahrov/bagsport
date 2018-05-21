<?php
	class Loader{
		private $_CName;
		private $_URI;
		
		public function __construct( $className ){
			$this->_CName = $className;
			
			switch( $className ){
				case "XMLMan":
					$this->_URI = __DIR__ . "/{$className}.php";
					
				break;
				case "XMLAbstract":
					$this->_URI = __DIR__ . "/{$className}.php";
					
				break;
				default:
				$this->_URI = __DIR__ . "/{$className}/{$className}.php";
				
			}
			
			$this->check();
			
		}
		
		private function check(){
			if( !file_exists( $this->_URI ) ){
				return false;
				
			}
			
			$this->register();
			
		}
		
		private function register(){
			require_once $this->_URI;
			
			return true;
		}
		
	}
	
	spl_autoload_register( function( $className ){
		new Loader( $className);
		
	} );
	
?>