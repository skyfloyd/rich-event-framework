<?php
namespace easy_db_layer;

class DBconnection {
	private static $connection = null;
	public static $dateFormatForSQL = "Y-m-d H:i:s";
	
	
	private $variablesList = array();
	
	public static $valType_NUMBER = 0;
	
	public function errorInfo(){
		$conn = self::getConnection();
		return $conn->errorInfo();
	}
	
	
	public function addVar( $value, $type = null ){
		if( is_null( $this->variablesList ) ){
			$this->variablesList = array();
		}
		$key = ":myV" . count( $this->variablesList );
		
		$this->variablesList[] = array( "id" => $key, "val" => $value, "type" => $type );
		
		return $key;
	}
	
	private function execWithVars( $query ){
		$conn = self::getConnection();
		$stmt = $conn->prepare( $query );
				
		$execParam = array();
		foreach( $this->variablesList as $par ){
			if( isset( $par[ "type" ] ) && !is_null( $par[ "type" ] ) && $par[ "type" ] == self::$valType_NUMBER ){
				$stmt->bindValue($par[ "id" ], (int) $par[ "val" ], \PDO::PARAM_INT);
			}else{
				$stmt->bindValue($par[ "id" ], $par[ "val" ]);
			}
		}
		
		$stmt->execute(); 
		
		$this->variablesList = array();
		
		return $stmt;
	}
	
	private static function getConnection(){
		if( self::$connection == null ){
			$host = \Config::getDBhost();
			$user = \Config::getDBuser();
			$pass = \Config::getDBpass();
			$db   = \Config::getDBname();
			
			//$opt = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION );
			$opt = array();
			
			self::$connection = new \PDO( ('mysql:host=' . $host . ';dbname=' . $db . ''), $user, $pass, $opt ); //;charset=utf8
		}
		
		return self::$connection;
	}
	
	
	
	public static function closeConnection(){
		self::$connection = null;
	}
	
	
	/**
	 * function for DB insert
	 *
	 * @param String $table
	 * @param Array $fields
	 * @param Array $data
	 * @return Created Row Id
	 */
	public function insert( $table, $fields, $data ){
		$query = "INSERT INTO " . self::getTableName() . " " . $this->getInsertPart( $fields, $data );
		return $this->insertQuery( $query );
	}
	
	
	
	/**
	 * function for DB insert queries
	 *
	 * @param String $query
	 * @return Created Row Id
	 */
	public function insertQuery( $query ){
		$stmt = $this->execWithVars( $query );
		
		$conn = self::getConnection();
		return $conn->lastInsertId();
	}
	
	/**
	 * function to select from DB
	 *
	 * @param String $table
	 * @param Array $fields
	 * @param Array $selectFieldsList
	 * @param Array $whereFields
	 * @return Array
	 */
	public function select( $table, $fields, $selectFieldsList = null, $whereFields = null ){
		$query = "SELECT " . self::getSelectPart( $this->getSelectedFieldsFromFields( $fields, $selectFieldsList ) ) . " FROM " . $table . (is_null( $whereFields ) ? "" : (" WHERE " . $this->getWherePart( $fields, $whereFields )));
		$result = $this->selectQuery( $query, null );
	
		return $result;
	}
	
	private function getSelectedFieldsFromFields( $fields, $selectFields = null ){
		if( is_null( $selectFields ) ){
			return $fields;
		}
		
		$returnFields = array();
		foreach( $selectFields as $sField ){
			foreach( $fields as $uiF => $dbF ){
				if( $uiF == $sField ){
					$returnFields[ $uiF ] = $dbF;
				}
			}
		}
		
		return $returnFields;
	}
	
	
	/**
	 * function for DB select queries
	 *
	 * @param String $query
	 * @param Array $fields
	 * @return Array
	 */
	public function selectQuery( $query, $fields = null ){
		$stmt = $this->execWithVars( $query );
		
		return self::getSqlResultInArray( $stmt, $fields );
	}
	
	/**
	 * function for DB delete queries
	 *
	 * @param String $query
	 * @return rows count which were deleted
	 */
	public function deleteQuery( $query ){
		$stmt = $this->execWithVars( $query );
		
		return $stmt->rowCount();
	}
	
	/**
	 * function for DB update
	 *
	 * @param String $table
	 * @param Array $fields
	 * @param Array $setData
	 * @param Array $whereData
	 * @return Number
	 */
	public function update( $table, $fields, $setData, $whereData ){
		$query = "UPDATE " . $table . " SET " . $this->getUpdatePart($fields, $setData) . " WHERE " . $this->getWherePart( $fields, $whereData );
		return $this->updateQuery( $query );
	}
	
	/**
	 * function for DB update queries
	 *
	 * @param String $query
	 * @return Number
	 */
	public function updateQuery( $query ){
		$stmt = $this->execWithVars( $query );
		
		return $stmt->rowCount();
	}
	
	/**
	 * Is record with given parameters exists
	 *
	 * @param String $table
	 * @param Array or String $field
	 * @param Array or String $value
	 * @return boolean
	 */
	public function isValueExist( $table, $field, $value ){
		if( is_array( $value ) ){
			$query = "SELECT count(*) AS rowsCount FROM $table WHERE " . self::getWherePart( $field, $value );
		}else{
			$query = "SELECT count(*) AS rowsCount FROM $table WHERE `$field` = " . $this->addVar( $value );
		}
			
		
		$count = $this->selectQuery( $query, null );

		if( $count[0]["rowsCount"] > 0 ){
			return true;
		}
			
		return false;
	}
	
///////////////////////////// work without connection

	public function getCommaSepListPart( $list ){
		if( is_string( $list ) ){
			$parts = explode( ",", $list );
			for( $i = 0; $i < count( $parts ); $i++ ){
				$parts[ $i ] = trim( $parts[ $i ] );
				if( substr( $parts[ $i ], 1) == substr( $parts[ $i ], 0, -1) && (substr( $parts[ $i ], 1) == "'" || substr( $parts[ $i ], 1) == '"') ){
					$parts[ $i ] = substr( $parts[ $i ], 1, -1);
				}
			}
			$list = $parts;
		}
		
		$str = "";
		for( $i = 0; $i < count( $list ); $i++ ){
			$str = ", " . $this->addVar( $list[ $i ] );
		}
		$str = substr( $str, 1 );
		return $str;
	}

	/**
	 * get Where part of the sql query
	 *
	 * @param Array $fields
	 * @param Array $values
	 * @param Boolean $isOR
	 * @return String
	 */
	public function getWherePart( $fields, $values, $isOR = false, $isEqual = true, $namespace = "" ){
		$orAnd = ($isOR ? " OR" : "AND"); //" OR"-sra probel@ hastat petqa es toxi hamar $part = substr( $part, 0, -4 );
		$equalNot = ($isEqual ? "=" : "<>");
		$namespace = ($namespace == "" ? $namespace : ($namespace . "."));
		
		$part = "";
		if( !is_null( $values ) && !is_null( $fields ) ){
			if( !is_array( $values ) && !is_array( $fields ) ){
				$part .= $namespace . "`" . $fields . "` " . $equalNot . " " . $this->addVar( $values );
			}else
			if( is_array( $values ) && count( $values ) > 0 ){
				foreach( $values AS $fKey => $fVal ){
					if( isset( $fields[ $fKey ] ) ){
						if( is_array( $fVal ) ){
							foreach ( $fVal AS $v ){
								$part .= $namespace . "`" . $fields[ $fKey ] . "` " . $equalNot . " " . $this->addVar( $v ) . " " . $orAnd . " ";
							}
						}else{
							$part .= $namespace . "`" . $fields[ $fKey ] . "` " . $equalNot . " " . $this->addVar( $fVal ) . " " . $orAnd . " ";
						}
					}
				}
				
				if( $part != "" ){
					$part = substr( $part, 0, -4 );
				}
			}
		}
			
		return $part;
	}

	public function getUpdatePart( $fields, $values ){
		$part = "";
		foreach( $fields AS $fKey => $f ){
			if( isset( $values[ $fKey ] ) && $fKey != "id" ){
				$part .= "`" . $f . "`=" . $this->addVar( $values[ $fKey ] ) . ",";
			}
		}
		
		$part = substr( $part, 0, -1 );
		return $part;
	}
	
	public function getInsertPart( $fields, $values, $isIdAutoIncrement = false ){
		$fPart = "(  ";
		$vPart = "(  ";
		if( isset( $values[ 0 ] ) ){
			for( $i = 0; $i < count($values); $i++ ){
				foreach( $fields AS $fKey => $f ){
					if( isset( $values[ $i ][ $fKey ] ) ){
						if( $isIdAutoIncrement && $fKey == "id" ){
							if( $i == 0 ){
								$fPart .= "`" . $f . "`, ";
							}
							$vPart .= "'', ";
						}else{
							if( $i == 0 ){
								$fPart .= "`" . $f . "`, ";
							}
							$vPart .= "" . $this->addVar( $values[ $i ][ $fKey ] ) . ", ";
						}
					}
				}
				$vPart = substr( $vPart, 0, -2 );
				$vPart .= "  ),(";
			}
			
			$fPart = substr( $fPart, 0, -2 );
			$vPart = substr( $vPart, 0, -2 );
			$fPart .= "  )";
		}else{
			foreach( $fields AS $fKey => $f ){
				if( isset( $values[ $fKey ] ) ){
					if( $isIdAutoIncrement && $fKey == "id" ){
						$fPart .= "`" . $f . "`, ";
						$vPart .= "'', ";
					}else{
						$fPart .= "`" . $f . "`, ";
						$vPart .= "" . $this->addVar( $values[ $fKey ] ) . ", ";
					}
				}
			}
			$fPart = substr( $fPart, 0, -2 );
			$vPart = substr( $vPart, 0, -2 );
			$fPart .= "  )";
			$vPart .= "  )";
		}		

		$fullPart = " $fPart VALUES $vPart "; // error
		
		return $fullPart;
	}
	
	
	public static function getSelectPart( $fields, $tableNamespace = null, $asFields = null ){
		if( is_null( $asFields ) ){
			$asFields = $fields;
		}else 
		if( is_string( $asFields ) ){
			$prefix = $asFields;
			$asFields = $fields;
			foreach ( $asFields AS $key => $val ){
				$asFields[ $key ] = $prefix . $asFields[ $key ];
			}
		}
		
		if( is_null( $tableNamespace ) ){
			$tableNamespace = "";
		}else{
			$tableNamespace = $tableNamespace . ".";
		}
		
		$select = "";
		foreach( $fields as $clientF => $dbF ){
			if( isset( $asFields[ $clientF ] ) ){
				$select .= ", " . $tableNamespace . "`" . $fields[ $clientF ] . "` AS `" . $asFields[ $clientF ] . "`";
			}
		}
		$select = trim( substr( $select, 1 ) );
		
		return $select;
	}
	
	
	private static function getSqlResultInArray( $result, $fields = null ){
		if( $result === null || $result === false ){
			return array();
		}
		
		$resultArray = array();
		$index = 0;
		if( $fields === null ){
			while( $row = $result->fetch(\PDO::FETCH_ASSOC) ){
				$resultArray[ $index ] = $row;
				$index++;
			}
		}else{
			while( $row = $result->fetch(\PDO::FETCH_ASSOC) ){
				foreach ( $fields AS $appF => $dbF )
					$resultArray[ $index ][ $appF ] = $row[ $dbF ];
					
				$index++;
			}
		}
		
		return $resultArray;
	}
	
	public static function filterStringForSQL( $str ){
		$str = str_replace( "\'", "'", $str );
		$str = str_replace( "'", "\'", $str );
		return $str;
	}
}