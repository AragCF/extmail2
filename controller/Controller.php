<?
// echo getrootdirsrvinclude();
// include_once(getrootdirsrvinclude()."model/Model.php");            // connect to the Model part of our MVC pattern
 include_once(getrootdirsrvinclude()."model/Settings.php");           // connect to the Settings 
 include_once(getrootdirsrvinclude()."model/legacy.php");             // connect to the Settings 
 include_once(getrootdirsrvinclude()."model/localization.php");       // connect to the Settings 
// include_once(getrootdirsrvinclude()."controller/lessc.inc.php");            // the lesscss server-side compiler
// include_once(getrootdirsrvinclude()."controller/StringForge.php");          // the string processing class
// include_once(getrootdirsrvinclude()."controller/Template.1.87.php");        // add Template class
 include_once(getrootdirsrvinclude()."controller/Session.1.2.php");
 
 class Controller {                        // the Controller class
  public $model;                           // used to handle the link to the Model
  public $settings;                        // used to handle the link to the Settings (settings.ini in an object representation)
  public $viewroot;
  public $themeinfo;
  public $srvroot;
  
  public function __construct() {          // the constructor function
   $this->settings = Settings::init();     // load settings file
   $ssp = getrootdirsrv().$this->settings->sessionsavepath;
   
   new SessionSaveHandler($ssp);
   
//   setsecurevariable('settings',$this->settings);
   
   date_default_timezone_set($this->settings->timezone);       // set the default timezone. may be we need to customize it for particular user
//   $this->model       = new Model($this->settings);            // instantiate the Model class
//   $this->less        = new lessc;
//   $this->StringForge = new StringForge($this->settings);
  }
  
  function extmail() {
   $req = new stdClass;
   $req->Get  = $_GET;
   $req->Post = $_POST;
   
//   ajax_echo_r ($req);
   
   $subject       = getvariablereq('subject');
   $mail_template = getvariablereq('mail_template');
   $email         = getvariablereq('email');
   $from_name     = getvariablereq('from_name');
   $from_mail     = getvariablereq('from_mail');
   
   $ret = sendmail ($subject, $mail_template, $email, $from_name, $from_mail);
   $req->Result = $ret;
   setCache('request-'.date('Y-m-d-H-i-s'), $req);
   echo json_encode($req);
  }
 }
?>
