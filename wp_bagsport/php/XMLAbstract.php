<?php
class XMLAbstract{
	protected $_connect = null;
	protected $_sources = array(
		// ścieżka do XML z produktami
		// 'products' => '',
		// ścieżka do XML ze znakowaniem
		// 'marking' => '',
		// ścieżka do XML ze stanem magazynowym
		// 'stock' => '',
		
	);
	protected $_atts = array(
		// nazwa sklepu
		'shop' => '',
		// po jakim czasie pobrać XML ponownie ( domyślnie 24h )
		'lifetime' => 86400,
		// dodatkowe dane autoryzacyjne
		'context' => array(
			'http' => array(
				'timeout' => 600,
				
			),
			
		),
		
	);
	protected $_vat = 0.23;
	protected $_log = array();
	
	// pobiera parametry sklepu, nawiązuje połączenie z bazą
	public function __construct( $sources, $atts ){
		// ustawia system kodowania na utf-8
		mb_internal_encoding("UTF-8");
		
		// test połączenia z bazą
		if( $this->_db() ){
			$this->_atts = array_merge(
				$this->_atts,
				$atts
			);
			
			$this->_sources = $sources;
			
			/* // test aktualności danych XML
			if( $this->_check() ){
				// odświeżanie produktów
				$this->_import( $this->_atts );
				
			} */
			
		}
		
	}
	
	// kończy połączenie z bazą
	public function __destruct(){
		if( $this->_connect !== null ) mysqli_close( $this->_connect );
		
	}
	
	// funkcja standaryzująca zapis nazw kategorii ( małe litery )
	protected function _stdName( $name ){
		return addslashes( mb_strtolower( strip_tags( trim( str_replace( array( '&', '?' ), '', (string)$name ) ) ) ) );
		
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
		foreach( $this->_sources as $source ){
			if( empty( $source ) ) continue;
			$filepath = $this->_getURL( "DND/" . basename( $source ) );
			
			if( !file_exists( $filepath ) or time() - filemtime( $filepath ) >= $this->_atts[ 'lifetime' ] ){
				// XML nie istnieje, albo jest przestarzały -  pobieranie
				mkdir( dirname( $filepath ), 0755, true );
				if( copy( $source, $filepath, stream_context_create( $this->_atts[ 'context' ] ) ) ){
					$doImport = true;
					
				}
				
			}
			
		}
		
		// kończy działania jeśli pliki XML nie były aktualne
		if( $doImport ) return true;
		
		// sprawdzanie czy liczba zaimportowanych produktów jest zgodna z ilością w pliku XML
		/* $XML = simplexml_load_file( $this->_getURL( "DND/" . basename( $this->_atts[ 'products' ] ) ) );
		$sql = "SELECT COUNT( * ) as 'count' from `XML_product` WHERE `shop` = '{$this->_atts[ 'shop' ]}'";
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_assoc( $query );
		if( count( $XML->children() ) > $fetch[ 'count' ] ){
			$doImport = true;
		}
		mysqli_free_result( $query ); */
		
		return $doImport;
	}
	
	// funkcja czyszcząca wpisy z danego sklepu
	protected function _clear(){
		$sql = "DELETE FROM `XML_product` WHERE `shop` = '{$this->_atts[ 'shop' ]}'";
		echo "\r\n $sql \r\n";
		if( mysqli_query( $this->_connect, $sql ) === false ){
			$this->_log[] = mysqli_error( $this->_connect );
			
		}
		
	}
	
	// funkcja inicjująca parsowanie XML i zapis danych do bazy		[>>>]
	protected function _import( $rehash ){
		
	}
	
	// funkcja filtrująca kategorie produktu									[>>>]
	protected function _categoryFilter( &$cat_name, &$subcat_name, $item ){
		
	}
	
	// funkcja dodająca kategorię do tablicy
	protected function _addCategory( $cat_name, $subcat_name ){
		$dt = date( 'Y-m-d H:i:s' );
		
		// sprawdza czy nie istnieje już kategoria główna o takiej nazwie, jeśli nie - dodaje ją
		if( $this->getCategory( 'name', $cat_name ) === null ){
			$sql = "INSERT INTO `XML_category` ( `name`, `slug`, `data` ) VALUES ( '{$cat_name}', '{$this->_slug( $cat_name )}', '{$dt}' )";
			if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
			
		}
		
		// dodawanie podkategorii
		if( !empty( $subcat_name ) ){
			$parent = $this->getCategory( 'name', $cat_name, 'ID' );
			// sprawdza czy istnieje kategoria o takiej nazwie, jeśli nie - dodaje, jeśli tak - aktualizuje ją
			if( $this->getCategory( 'name', $subcat_name ) === null ){
				$sql = "INSERT INTO `XML_category` ( `name`, `parent`, `slug`, `data` ) VALUES ( '{$subcat_name}', '{$parent}', '{$this->_slug( $subcat_name )}', '{$dt}' )";
				
			}
			// aktualizacja już istniejącej kategorii
			else{
				$cat_id = $this->getCategory( 'name', $subcat_name, 'ID' );
				$sql = "UPDATE `XML_category` SET parent = '{$parent}', data = '{$dt}' WHERE ID = '{$cat_id}'";
				
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
	/* protected function _bindCategory( $product_id = null, $cats_id = array() ){
		if( $product_id !== null && !empty( $cats_id ) ){
			foreach( $cats_id as $cid ){
				$sql = "INSERT INTO `XML_category_hash` ( `PID`, `CID`, `shop` ) VALUES ( '{$product_id}', '{$cid}', '{$this->_atts[ 'shop' ]}' )";
				if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
				
				
			}
			return true;
			
		}
		else return false;
		
	} */
	
	// funkcja zwracająca produkt po ID
	public function getProductsBy( $stmt = '' ){
		if( empty( $stmt ) ) return false;
		
		$sql = "SELECT cat.name as 'cat_name', prod.*
		FROM XML_product as prod
		LEFT JOIN XML_category as cat
		ON prod.cat_id = cat.ID
		{$stmt}";
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_all( $query, MYSQLI_ASSOC );
		mysqli_free_result( $query );
		return $fetch;
		
	}
	
	// pobiera produkty z kategorii o danym ID
	public function getCategoryProducts( $catID = null, $parentID = null, $atts = array() ){
		if( $catID === null ) return false;
		
		$sql = "SELECT XML_category.name as 'cat_name',
			XML_category.slug as 'cat_slug',
			XML_category.parent as 'cat_parent',
			XML_product.*
			FROM XML_category
			JOIN XML_product ON XML_product.cat_id = XML_category.ID
			WHERE XML_category.ID = '{$catID}'";
		if( $parentID !== null  ) $sql .= " AND XML_category.parent = '{$parentID}'";
		if( !empty( $atts['order'] ) and !empty( $atts['orderby'] ) ){
			$sql .= " ORDER BY {$atts['orderby']} {$atts['order']}";
		}
		
		// echo $sql;
		
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_all( $query, MYSQLI_ASSOC );
		mysqli_free_result( $query );
		return $fetch;
		
	}
	
	/* funkcja zwracająca tablicę najczęściej oglądanych produktów */
	public function getMostVisited( $atts = array() ){
		$atts = array_merge(
			array(
				'num' => 8,
			),
			$atts
		);
		
		$sql = "SELECT *
FROM XML_product as prod
JOIN XML_show as visit
ON prod.code = visit.PID
ORDER BY visit.visit DESC
LIMIT {$atts['num']}";
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_all( $query, MYSQLI_ASSOC );
		mysqli_free_result( $query );
		return $fetch;
		
	}
	
	/* funkcja naliczająca kolejne wizyty */
	public function addVisit( $id ){
		$sql = "SELECT *, COUNT( * ) as 'num' FROM XML_show WHERE PID = '{$id}'";
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_assoc( $query );
		mysqli_free_result( $query );
		
		/* aktualizacja */
		if( $fetch['num'] >= 1 ){
			$sql =  sprintf(
				'UPDATE XML_show SET visit = "%u" WHERE PID = "%s"',
				$fetch['visit'] + 1,
				$fetch['PID']
				
			);
			$query = mysqli_query( $this->_connect, $sql );
			
		}
		/* nowy rekord */
		else{
			$sql = "INSERT INTO XML_show ( `PID`, `visit` ) VALUES ( '{$id}', 1 )";
			$query = mysqli_query( $this->_connect, $sql );
			
		}
		
	}
	
	// zewnętrzny uchwyt do importowania danych XML
	public function renew(){
		$this->_import();
		
	}
	
	// zewnętrzny uchwyt do aktualizowania kategorii
	public function rehash(){
		$this->_import( true );
		
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
	
	// funkcja czyszcząca tablicę kategorii
	public function clearCats(){
		$sql = "TRUNCATE `XML_category`";
		$query = mysqli_query( $this->_connect, $sql );
		if( mysqli_query( $this->_connect, $sql ) === false ) $this->_log[] = mysqli_error( $this->_connect );
		
	}
	
	// funkcja czyszcząca puste kategorie
	public function clearEmptyCats(){
		$sql = "SELECT cat.ID
FROM XML_category as cat
LEFT JOIN XML_product as prod
ON cat.ID = prod.cat_id
WHERE prod.ID IS NULL
GROUP BY cat.ID";
		$query = mysqli_query( $this->_connect, $sql );
		$fetch = mysqli_fetch_all( $query );
		mysqli_free_result( $query );
		
		$list = implode( ", ", array_map( function( $arg ){
			return $arg[0];
			
		}, $fetch ) );
		$sql = "DELETE FROM XML_category WHERE ID IN ( {$list} )";
		$query = mysqli_query( $this->_connect, $sql );
		
	}
	
	
}
