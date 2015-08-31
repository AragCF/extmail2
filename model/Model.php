<?
 include_once(getrootdirsrvinclude()."model/Database.php");         // add Database class
 include_once(getrootdirsrvinclude()."model/SimpleImage.php");      // add image manipulation class
 include_once(getrootdirsrvinclude()."model/legacy.php");           // add some legacy code
 include_once(getrootdirsrvinclude()."model/localization.php");     // add some more legacy code
 
 class Model {        // the Model part of the MVC pattern.
  public  $db;        // handle the DB handler. oops.
  private $settings;  // store the settings here
  
  function __construct($settings) {               // the constructor function for our class
   $this->settings   = $settings;
   $this->db=new Database($this->settings);
  }
  
  public function addRequest($rk, $j) {
   $fields = new stdClass;
   
   $fields->tablename      = "Requests";
   $fields->SessionID      = session_id();
   $fields->Domain         = $_SERVER['HTTP_HOST'];
   $fields->UserAgent      = getsecurevariable('UserAgent');
   $fields->RosreestrData  = serialize($j);;
   $fields->RemoteAddr     = getsecurevariable('RemoteAddr');
   $fields->InvoiceID      = $rk->inv_id;
   $fields->RobokassaSend  = serialize($rk);
   //$fields->RobokassaFeedback
   $fields->Referer = getsecurevariable('Referer');
//   $fields->Email
//   ajax_echo_r ($j->features[0]->attributes);
//   ajax_echo_r ($j->features[0]->attributes->CAD_COST);
   
   $fields->CadID   = $j->features[0]->attributes->CAD_NUM;
   $fields->CadCost = $j->features[0]->attributes->CAD_COST;
//   ajax_echo_r ($fields);
   
   $ret = $this->db->addItem($fields);
   return $ret;
  }
  
  public function updateRequestEmail($inv_id, $email) {
   $fields = new stdClass;
   
   $fields->tablename         = "Requests";
   $fields->IDname            = "InvoiceID";
   $fields->ID                = $inv_id;
   $fields->Email             = $email;
   
//   ajax_echo_r ($fields);
   
   $ret = $this->db->saveItemEx($fields);
   return $ret;
  }
  
  public function updateRequest($rk, $j) {
   $fields = new stdClass;
   
   $fields->tablename         = "Requests";
   $fields->IDname            = "InvoiceID";
   $fields->ID                = $rk->inv_id;
   
//   $fields->SessionID         = session_id();
//   $fields->Domain            = $_SERVER['HTTP_HOST'];
//   $fields->UserAgent         = getsecurevariable('UserAgent');
//   $fields->RosreestrData     = serialize($j);;
//   $fields->RemoteAddr        = getsecurevariable('RemoteAddr');
   $fields->RobokassaFeedback = serialize($rk);
//   $fields->Referer = getsecurevariable('Referer');
//   $fields->Email
//   $fields->CadID   = $j->CAD_NUM;
//   $fields->CadCost = $j->CAD_COST;
   
   $ret = $this->db->saveItemEx($fields);
   return $ret;
  }
  
  public function getRequest($inv_id) {
   $sql = "
    SELECT *
    FROM   `Requests`
    WHERE  `InvoiceID` = '".$inv_id."'
    ;
   ";
   $ret=$this->db->query_first($sql);
   return $ret;
   
  }
 }
?>
