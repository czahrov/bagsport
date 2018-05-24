<?php
class XMLAbstract{
	protected $_connect = null;
	protected $_atts = array(
		// nazwa sklepu
		'shop' => '',
		// ścieżka do XML z produktami
		'products' => '',
		// ścieżka do XML ze znakowaniem
		'marking' => '',
		// ścieżka do XML ze stanem magazynowym
		'stock' => '',
		// po jakim czasie pobrać XML ponownie ( domyślnie 24h )
		'lifetime' => 86400,
		// dodatkowe dane autoryzacyjne
		'context' => array(),
		
	);
	protected $_vat = 0.23;
	protected $_log = array();
	
	// pobiera parametry sklepu, nawiązuje połączenie z bazą
	public function __construct( $atts ){
		// ustawia system kodowania na utf-8
		mb_internal_encoding("UTF-8");
		
		// test połączenia z bazą
		if( $this->_db() ){
			$this->_atts = array_merge(
				$this->_atts,
				$atts
			);
			
			// test aktualności danych XML
			if( $this->_check() ){
				// odświeżanie produktów
				$this->_import( $this->_atts );
				
			}
			
		}
		
		
	}
	
	// kończy połączenie z bazą
	public function __destruct(){
		if( $this->_connect !== null ) mysqli_close( $this->_connect );
		
	}
	
	// funkcja standaryzująca zapis nazw kategorii ( małe litery )
	protected function _stdName( $name ){
		$find = explode( "|", "Ą|Ę|Ż|Ź|Ó|Ł|Ć|Ń|Ś" );
		$replace = explode( "|", "ą|ę|ż|ź|ó|ł|ć|ń|ś" );
		
		return str_replace( $find, $replace, strtolower( strip_tags( trim( (string)$name ) ) ) );
		
	}
	
	// funkcja generująca slug
	protected function _slug( $name ){
		$find = explode( "|", ",| |-|Ą|Ę|Ż|Ź|Ó|Ł|Ć|Ń|Ś|ą|ę|ż|ź|ó|ł|ć|ń|ś" );
		$replace = explode( "|", "|_||a|e|z|z|o|l|c|n|s|a|e|z|z|o|l|c|n|s|" );
		
		return str_replace( $find, $replace, strtolower( strip_tags( trim( (string)$name ) ) ) );
		
	}
	
	// funkcja zwracająca ścieżkę URL do foldera roboczego
	protected function _getURL( $subdir = '' ){
		return __DIR__ . "/" . $this->_atts[ 'shop' ] . "/{$subdir}";
		
	}
	
	// funkcja inicjująca połączenie z bazą danych
	protected function _db(){
		$dbcon = mysqli_connect( DBHOST, DBUSER, DBPASS, DBNAME );
		
		if( mysqli_connect_errno() === 0 ){
			$this->_connect = $dbcon;
			return true;
			
		}
		else{
			$this->_log[] = mysqli_connect_error();
			return false;
		};
		
	}
	
	// funkcja aktualizująca pliki XML, informuje czy należy wykonać importowanie danych XML
	protected function _check(){
		// czy należy wykonać parsowanie produktów z XML
		$doImport = false;
		
		// sprawdzanie dostępności i aktualności plików XML
		foreach( array( $this->_atts[ 'products' ], $this->_atts[ 'marking' ], $this->_atts[ 'stock' ] ) as $source ){
			if( empty( $source ) ) continue;
			$filepath = $this->_getURL( "DND/" . basename( $source ) );
			if( !file_exists( $filepath ) or time() - filemtime( $filepath ) >= $this->_atts[ 'lifetime' ] ){
				// XML nie istnieje, albo jest przestarzały -  pobieranie
				if( copy( $source, $filepath, stream_context_create( $this->_atts[ 'context' ] ) ) ){
					$doImport = true;
					
				}
				
			}
			
		}
		
		// kończy działania jeśli pliki XML nie były aktualne
		if( $doImport ) return true;
		
		// sprawdzanie czy liczba zaimportowanych produktów jest zgodna z ilością w pliku XML
		$XML = simplexml_load_file( $this->_getURL( "DND/" . basename( $this->_atts[ 'products' ] ) ) );
		$sql = "SELECT COUNT( * ) as 'count' from `XML_product` WHERE `shop` = '{$this->_atts[ 'shop' ]}'";
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_assoc( $query );
		if( count( $XML->children() ) > $fetch[ 'count' ] ){
			$doImport = true;
		}
		mysqli_free_result( $query );
		
		return $doImport;
	}
	
	// funkcja czyszcząca wpisy z danego sklepu
	protected function _clear(){
		$sql = "DELETE FROM `XML_product` WHERE `shop` = '{$this->_atts[ 'shop' ]}'";
		if( mysqli_query( $this->_connect, $sql ) === false ){
			$this->_log[] = mysqli_error( $this->_connect );
			
		}
		
	}
	
	// funkcja inicjująca parsowanie XML i zapis danych do bazy		[>>>]
	protected function _import( $atts, $rehash ){
		
	}
	
	// funkcja filtrująca kategorie produktu									[>>>]
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		
	}
	
	// funkcja dodająca kategorię do tablicy
	protected function _addCategory( $cat_name, $subcat_name ){
		// sprawdza czy nie istnieje już kategoria główna o takiej nazwie, jeśli nie - dodaje ją
		if( $this->getCategory( 'name', $cat_name ) === null ){
			$sql = "INSERT INTO `XML_category` ( `name`, `slug` ) VALUES ( '{$cat_name}', '{$this->_slug( $cat_name )}' )";
			if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
			
		}
		
		// dodawanie podkategorii
		if( !empty( $subcat_name ) ){
			$parent = $this->getCategory( 'name', $cat_name, 'ID' );
			// sprawdza czy istnieje kategoria o takiej nazwie, jeśli nie - dodaje, jeśli tak - aktualizuje ją
			if( $this->getCategory( 'name', $subcat_name ) === null ){
				$sql = "INSERT INTO `XML_category` ( `name`, `parent`, `slug` ) VALUES ( '{$subcat_name}', '{$parent}', '{$this->_slug( $subcat_name )}' )";
				
			}
			// aktualizacja już istniejącej kategorii
			else{
				$cat_id = $this->getCategory( 'name', $subcat_name, 'ID' );
				$sql = "UPDATE `XML_category` SET parent = '{$parent}' WHERE ID = '{$cat_id}'";
				
			}
			
			if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
			
		}
		
		return true;
		
	}
	
	// zwraca kategorię po nazwie
	public function getCategory( $field, $value, $output = null ){
		if( empty ( $field ) ) return false;
		$sql = "SELECT * FROM `XML_category` WHERE `{$field}` = '{$value}' LIMIT 1";
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_assoc( $query );
		mysqli_free_result( $query );
		if( $output !== null ){
			return $fetch[ $output ];
			
		}
		else return $fetch;
		
	}
	
	// funkcja przypisująca produkt do kategorii ( listy ID kategorii )
	protected function _bindCategory( $product_id = null, $cats_id = array() ){
		if( $product_id !== null && !empty( $cats_id ) ){
			foreach( $cats_id as $cid ){
				$sql = "INSERT INTO `XML_category_hash` ( `PID`, `CID`, `shop` ) VALUES ( '{$product_id}', '{$cid}', '{$this->_atts[ 'shop' ]}' )";
				if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
				
				
			}
			return true;
			
		}
		else return false;
		
	}
	
	// funkcja zwracająca produkt po ID
	public function getProductsBy( $stmt = '' ){
		if( empty( $stmt ) ) return false;
		
		$sql = "SELECT * FROM `XML_product` {$stmt}";
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_all( $query, MYSQLI_ASSOC );
		mysqli_free_result( $query );
		return $fetch;
		
	}
	
	// pobiera produkty z kategorii o danym ID
	public function getCategoryProducts( $catID = null, $parentID = null ){
		if( $catID === null ) return false;
		
		/* $sql = "SELECT product.* FROM `XML_product` AS product 
		LEFT JOIN `XML_category_hash` AS hash ON product.code = hash.PID 
		LEFT JOIN `XML_category` AS category ON hash.CID = category.ID 
		WHERE category.ID = '{$catID}'"; */
		
		/* $sql = "SELECT * FROM `products_view`
		WHERE cat_ID = {$catID}";
		if( is_numeric( $parentID ) ) $sql .= " AND cat_parent = {$parentID}";
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_all( $query, MYSQLI_ASSOC );
		mysqli_free_result( $query );
		return $fetch;
		*/
		
		$sql = "SELECT XML_category.name as 'cat_name',
			XML_category.slug as 'cat_slug',
			XML_category.parent as 'cat_parent',
			XML_product.*
			FROM XML_category
			JOIN XML_product ON XML_product.cat_id = XML_category.ID
			WHERE XML_category.ID = '{$catID}'";
		if( $parentID !== null  ) $sql .= " AND XML_category.parent = '{$parentID}'";
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_all( $query, MYSQLI_ASSOC );
		mysqli_free_result( $query );
		return $fetch;
		
	}
	
	// zewnętrzny uchwyt do importowania danych XML
	public function renew(){
		$this->_import( $this->_atts );
		
	}
	
	// zewnętrzny uchwyt do aktualizowania kategorii
	public function rehash(){
		$this->_import( $this->_atts, true );
		
	}
	
	// zewnętrzny uchwyt do odświeżenia plików XML
	public function update(){
		$this->_check();
		
	}
	
	// funkcja usuwająca przypisanie kategorii dla produktów w bazie
	public function clearHash(){
		$sql = "UPDATE `XLM_product` SET cat_id = NULL WHERE shop = '{$this->_atts[ 'shop' ]}'";
		$query = mysqli_query( $this->_connect, $sql );
		if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
		
	}
	
	
}
