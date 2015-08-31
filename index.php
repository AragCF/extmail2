<?
 error_reporting(E_ALL ^ E_NOTICE);
 ini_set('memory_limit', '700M');
 
 include_once("controller/Controller.php");      // this is the initializer (if I could say so) for the controller part of our MVC pattern
 
 $controller = new Controller();   // instantiate a class
 
 $controller->extmail();               // start it. nice, isn't it?
 
 function getrootdirsrvinclude() {
  $s1 = __FILE__;
  $s2 = $_SERVER['SCRIPT_FILENAME'];
//  echo "1: ".$_SERVER['SCRIPT_FILENAME']."<br>".$s."<br>";
  
  $ret1 = (substr($s1, 0, strrpos($s1, "/")))."/";
  $ret2 = (substr($s2, 0, strrpos($s2, "/")));
  
//  echo "ret1: ".$ret1."<br>ret2: ".$ret2."<br>";
  
  $ret = (substr($ret1, strlen($ret2)+1));
//  echo "ret: ".$ret."<br>";
  
  return $ret;
 }
 
?>