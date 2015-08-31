<?
 class Database {
  private $DBH, $STH;                         // handle the database and query handlers
  private $settings;
  
  function __construct($settings) {
//   echo_r ($settings);
   $this->settings = $settings;
   try {
    $this->DBH = new PDO("mysql:host=".$settings->dbhost.";dbname=".$settings->dbname, $settings->dbuser, $settings->dbpassword);          // Соединяемся с базой данных
   } catch(PDOException $e) {                 // если ошибка
    if ($settings->displaydberrors!="0") echo $e->getMessage();
   }
   
   if ($this->DBH) {
    $this->DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->STH = $this->DBH->query("SET NAMES 'utf8' ; ");
    $this->STH = $this->DBH->query("SET SQL_BIG_SELECTS=1 ; ");
   }
  }
  
  public function query($sql, $ret = array()) {                            // execute query
   if ($this->DBH) {                                       // if we connected
//    $sql = substr_replace($sql,"",(strpos($sql," ;")+2));  // filter SQL code
    try {
//     echo ("SQL:--".$sql."--");
     $this->STH = $this->DBH->query($sql);  
    } catch(PDOException $e) {
     if ($settings->displaydberrors!="0") {
      echo "Error in SQL: <br>\n".nl2br($sql)."<br>\n".$e->getMessage()."<br>";
     }
     $this->addToLogPDOErrors($sql, $e->getMessage());
    }
//    echo_r($this->STH);
    if ($this->STH) {                                // if there's a query results
     $this->STH->setFetchMode(PDO::FETCH_OBJ);       // выбираем режим выборки
//     $ret = array();                                 // initialize the results array
     
     $i = 0;
     while($row = $this->STH->fetch()) {             // выводим результат
      $ret[] = $row;                                 // add the record to array
      $i++;
//      if ($i>$this->settings->sqllimit) {
//       echo "SQL Limit reached (".$i." rows)";
//       break;
//       continue;
//      }
     }
     
     return $ret;         // send array to Model
    }
   }
  }
  
  public function query_first($sql, $ret = array()) {                      // execute query
   $ret = $this->query($sql, $ret);
   return $ret[0];                                    // send array to Model
  }
  
  public function query_assoc($sql,$keyfieldname) {                            // execute query
   if ($this->DBH) {                                       // if we connected
//    $sql = substr_replace($sql,"",(strpos($sql," ;")+2));  // filter SQL code
    try {
//     echo ("SQL:--".$sql."--");
     $this->STH = $this->DBH->query($sql);  
    } catch(PDOException $e) {
     if ($settings->displaydberrors!="0") {
      echo "Error in SQL: <br>\n".nl2br($sql)."<br>\n".$e->getMessage()."<br>";
     }
     $this->addToLogPDOErrors($sql, $e->getMessage());
    }
//    echo_r($this->STH);
    if ($this->STH) {                                // if there's a query results
     $this->STH->setFetchMode(PDO::FETCH_OBJ);       // выбираем режим выборки
     $ret = array();                                 // initialize the results array
     
     while($row = $this->STH->fetch()) {             // выводим результат
      $ret[$row->$keyfieldname] = $row;                                 // add the record to array
//      echo $row->ID;
     }
     return $ret;         // send array to Model
    }
   }
  }
  
  public function query_flat($sql) {                       // execute query
   if ($this->DBH) {                                       // if we connected
//    $sql = substr_replace($sql,"",(strpos($sql," ;")+2));  // filter SQL code
    try {
//     echo ("SQL:--".$sql."--");
     $this->STH = $this->DBH->query($sql);  
    } catch(PDOException $e) {
     if ($settings->displaydberrors!="0") {
      echo "Error in SQL: <br>\n".nl2br($sql)."<br>\n".$e->getMessage()."<br>";
     }
     $this->addToLogPDOErrors($sql, $e->getMessage());
    }
//    echo_r($this->STH);
    if ($this->STH) {                                // if there's a query results
     $this->STH->setFetchMode(PDO::FETCH_NUM);       // выбираем режим выборки
     $ret = array();                                 // initialize the results array
     
     while($row = $this->STH->fetch()) {             // выводим результат
      $ret[] = $row[0];                              // add the record to array
     }
     
     return $ret;         // send array to Model
    }
   }
  }
  
  public function exec($sql) {                            // execute query
   if ($this->DBH) {                                       // if we connected
//    $sql = substr_replace($sql,"",(strpos($sql," ;")+2));  // filter SQL code
    $ret = new stdClass();
    try {
//     echo ("SQL:--".$sql."--");
     $ret->rowsAffected = $this->DBH->exec($sql);  
    } catch(PDOException $e) {
     if ($settings->displaydberrors!="0") {
      echo "Error in SQL: <br>\n".nl2br($sql)."<br>\n".$e->getMessage()."<br>";
     }
     $this->addToLogPDOErrors($sql, $e->getMessage());
    }
    $ret->lastInsertID = $this->DBH->lastInsertId();
    return $ret;
   }
  }
  
  
  
  
  
  
  
  
  public function query_only($sql) {                            // execute query
   if ($this->DBH) {                                       // if we connected
//    $sql = substr_replace($sql,"",(strpos($sql," ;")+2));  // filter SQL code
    
    try {
//     echo ("SQL:--".$sql."--\n<br>");
//     addtologEx("Database-query_only",$sql);
     $this->STH = $this->DBH->query($sql);  
    } catch(PDOException $e) {
     if ($this->settings->displaydberrors!="0") {
      echo "query_only - Error in SQL: <br>\n".nl2br($sql)."<br>\n".$e->getMessage()."<br>";
     }
     $this->addToLogPDOErrors($sql, $e->getMessage());
    }
//    echo_r($this->STH);
    if ($this->STH) {                                // if there's a query results
     $this->STH->setFetchMode(PDO::FETCH_OBJ);       // выбираем режим выборки
     $ret = 1;
    } else {
     $ret = 0;
    }
    return $ret;         // send array to Model
   }
  }
  
  public function fetch() {                            // execute query
   return $this->STH->fetch();
  }
  
  public function getRecord($tablename, $id) {
   $sql="
    SELECT  *
    FROM    `".escape($tablename)."`
    WHERE   `ID`='".escape($id)."'
    LIMIT   1
    ;
   ";
   $ret = $this->query($sql);
   return $ret[0];
//   return $this->query($sql);
  }  
  
  public function getRecord2($table, $field, $value) {
   $sql="
    SELECT  *
    FROM    `".escape($table)."`
    WHERE   `".escape($field)."`='".escape($value)."'
    LIMIT   1
    ;
   ";
   $ret = $this->query($sql);
   return $ret[0];
//   return $this->query($sql);
  }
  
  public function getRecordEx($table, $data) {
   foreach ($data as $k=>$v) {
    if ($k!="ID") {
     $sql_set .= "`".escape($k)."`='".escape($v)."' AND ";
    }
   }
   $sql_where = substr($sql_set,0,strlen($sql_set)-5);
   
   $sql="
    SELECT  *
    FROM    `".escape($table)."`
    WHERE   ".$sql_where."
    LIMIT   1
    ;
   ";
   $ret = $this->query($sql);
   return $ret[0];
//   return $this->query($sql);
  }
  
  
  
  
  
  
  public function addToLogPDOErrors($sql, $message) {
   @mkdirr('data/logs');
   file_put_contents('data/logs/PDOErrors.txt', date("Y-m-d H:i:s")."\t".$sql."\t".$message."\n", FILE_APPEND);  // write to log in case of errors
  }
  
  public function addItem($json) {
   foreach ($json as $k=>$v) {
    if ($k!="tablename") {
     $sql_insert .= "`".escape($k)."`, ";
     $sql_values .= "'".escape($v)."', ";
    }
   }
   $sql_insert = substr($sql_insert,0,strlen($sql_insert)-2);
   $sql_values = substr($sql_values,0,strlen($sql_values)-2);
   
   $sql="
    INSERT INTO `".escape($json->tablename)."`
     (".$sql_insert.")
    VALUES
     (".$sql_values.") ; 
   ";
   
   $ret=$this->exec($sql);                                   // save the new GalleryID to the parent object's table
   
   return $ret;
  }
  
  public function saveField($params) {
   $sql = "
    UPDATE `".$params->tablename."`
    SET    `".$params->columnname."`='".$params->data."'
    WHERE  `ID` = '".$params->id."'
    ;
   ";
   $ret=$this->db->exec($sql);
//   ajax_echo_r($ret);
   
//   if ($ret->rowsAffected<1) {
  }
  
  public function saveItem($json) {
//   ajax_echo_r ($json);
   
   foreach ($json as $k=>$v) {
    if (($k!="ID") && ($k!="tablename")) {
     $sql_set .= "`".escape($k)."`='".escape($v)."', ";
    }
   }
   $sql_set = substr($sql_set,0,strlen($sql_set)-2);
   
   $sql = "
    UPDATE `".escape($json->tablename)."`
    SET    ".$sql_set."
    WHERE  `ID` = '".escape($json->ID)."'
    ; 
   ";
   
   $ret=$this->exec($sql);                                   // save the new GalleryID to the parent object's table
   
   return $ret;
  }
  
  public function saveItemEx($json) {
//   ajax_echo_r ($json);
   
   foreach ($json as $k=>$v) {
    if (($k!="ID") && ($k!="IDname") && ($k!="tablename")) {
     $sql_set .= "`".escape($k)."`='".escape($v)."', ";
    }
   }
   $sql_set = substr($sql_set,0,strlen($sql_set)-2);
   
   $sql = "
    UPDATE `".escape($json->tablename)."`
    SET    ".$sql_set."
    WHERE  `".escape($json->IDname)."` = '".escape($json->ID)."'
    ; 
   ";
   
   $ret=$this->exec($sql);                                   // save the new GalleryID to the parent object's table
   
   return $ret;
  }
  
  public function deleteItem($json) {
   $sql="
    DELETE FROM `".escape($json->tablename)."`
    WHERE `ID` = '".escape($json->ID)."'
    ;
   ";
   $ret=$this->exec($sql);
   return $ret;
  }
  
  
  
  
  
  public function getList($tablename) {
   $sql="
    SELECT   *
    FROM     `".$tablename."`
    ;
   ";
   $listitems=$this->query($sql);
   return $listitems;
  }
  
  public function getListEx($tablename,$auxfilter) {
   $sql="
    SELECT   *
    FROM     `".$tablename."`
    ".(($auxfilter)?"WHERE ".$auxfilter:"")."
    ;
   ";
   $listitems=$this->query($sql);
   return $listitems;
  }
  
  public function getListByParentID($tablename, $parentname, $parentid) {
   $sql="
    SELECT   `".$tablename."`.*
    FROM     `".$tablename."`
    WHERE    `".$parentname."` = '".$parentid."'
    ;
   ";
   $listitems=$this->query($sql);
   return $listitems;
  }  
  
  
  public function getField($params) {
   $sql = "
    SELECT `".$params->columnname."` as `Result`
    FROM   `".$params->tablename."`
    WHERE  `ID` = '".$params->id."'
    ;
   ";
   $ret=$this->query($sql);
   return $ret->Result;
  }
  
 }
?>
