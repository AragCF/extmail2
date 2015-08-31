<?
 // some legacy but very useful helper functions. thanks to my dad for this code. =)
 include "cache.1.3.php";
 
 function filestr($fname) {           // replacement for file_get_contents
  $ret="";
  
  if (file_exists($fname)) {
   if (is_readable($fname)) {
//    $ret = file_get_contents($fname);
    $fh = fopen($fname, "r") or die("Can't open file!");   // Открыть файл и установить указатель текущей позиции в конец файла
    while (! feof($fh)) {
     $ret.= fgets($fh, 4096);
     $n++;
    }
    fclose($fh);
   } else {
    $ret.="$fname is not readable!";
   }
  } else {
   $ret.="File ".$fname." not found!" ;
  }
  
  return ($ret);
  
 }
 
 function ajax_echo($txt) {            // used to solve some ajax output problems on Unix systems if any (usually if the UTF-8 and Windows-1251 meets together)
  echo localize(cfstrconv($txt));
 }
 
 function cfstrconv($str) {            // used to friendly convert the text from one codepage to another
  if (getsecurevariable('settings')->enableajaxrecode) {
   return iconv("windows-1251","UTF-8",$str);
  } else {
   return $str;
  }
  //return iconv("UTF-8","windows-1251",$str);
  //return ($str);
 }
 
 function cfstrinvconv($str) {         // used to friendly convert the text back. seems to be obsolete.
  return iconv("UTF-8","windows-1251",$str);
  //return ($str);
 }
 
 function cf_strtolower($s) {          // used to convert the uppercase characters to lowercase ones
  return (strtr( $s, strrevprepare('ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮЁQWERTYUIOPASDFGHJKLZXCVBNM'), strrevprepare('йцукенгшщзхъфывапролджэячсмитьбюёqwertyuiopasdfghjklzxcvbnm') ));
 }
 
 function cf_mb_strtolower($s) {          // used to convert the uppercase characters to lowercase ones
//  return strprepare(strtolower( strrevprepare($s)));
  return strprepare(cf_strtolower( strrevprepare($s)));
 }
 
 function mimetype($fileext) {         // used to get a nice mime type from file extension. used in file downloader.
  $known_mime_types=array(
   "css"  => "text/css",
   "pdf"  => "application/pdf",
   "txt"  => "text/plain",
   "js"   => "text/javascript",
   "html" => "text/html",
   "htm"  => "text/html",
   "exe"  => "application/octet-stream",
   "zip"  => "application/zip",
   "doc"  => "application/msword",
   "xls"  => "application/vnd.ms-excel",
   "ppt"  => "application/vnd.ms-powerpoint",
   "gif"  => "image/gif",
   "png"  => "image/png",
   "jpeg" => "image/jpg",
   "jpg"  => "image/jpg",
   "php"  => "text/plain",
   "mp3"  => "audio/mpeg",
   "mp4"  => "audio/mpeg",
   "ogg"  => "audio/vorbis",
   "rar"  => "application/octet-stream"  // "application/x-rar-compressed"
  );
  return($known_mime_types[$fileext]);
 }
 
 function filter($t) {                                       // used to protect us from various hacks
  return preg_replace ("/^[^a-zA-ZА-Яа-я0-9\s]*$/","",$t);
 }
 
 function replace_unicode_escape_sequence($match) {
//  $match = "\u00ed";
//  $match = str_replace("%u","U+",$match);
//  return mb_convert_encoding($match, 'UTF-8', 'HTML-ENTITIES');
//  return ($match);;
//  return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
 }
// $str = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
 
 function strprepare($s) {
  return iconv("windows-1251","UTF-8",$s);
 }
 function strrevprepare($s) {
  return iconv("UTF-8","windows-1251",$s);
 }
 
 function safetext ($s) {                                    // used to protect us from god-level hacks and special characters
//  return strprepare(filter(rawurldecode(base64_decode($s))));
  return (filter($s));
 }
 
 function softtrunc($str, $len) {
  $str = strrevprepare($str);
  if (strlen($str)>=$len) {
   $pos  = strpos($str, " ", $len);
   $str = substr($str, 0, $pos)."...";
  }
  $str = strprepare($str);
  return $str;
 }
 
 function echo_r($txt, $return=false) {
  if ($return) {
   return (nl2br(str_replace(" ","&nbsp",(print_r($txt,true)))))."<br>";
  } else {
   echo (nl2br(str_replace(" ","&nbsp",(print_r($txt,true)))))."<br>";
  }
 }
 function ajax_echo_r($txt) {
  ajax_echo (nl2br(str_replace(" ","&nbsp",(print_r($txt,true)))))."<br>";
 }
 
 function getvariable($vn) {
  // Short introduction to variable variables ($$) 
  // $v = 'hue';
  // $hue = 'test';
  // $$v now represents the value of $hue which is 'test'
  if(!isset($_SESSION)) sessionstart();
  
  if (isset($_GET[$vn])) {
   $$vn  = $_GET[$vn];                        // get hue from the request
   $_SESSION[$vn]=$$vn;
  } else if (isset($_POST[$vn])) {
   $$vn = $_POST[$vn];                        // if we used POST
   $_SESSION[$vn]=$$vn;
  } else if (isset($_SESSION[$vn])) {
   $$vn=$_SESSION[$vn];
  } else {
   $$vn  = -1;
  }
  return $$vn;
  
 }
 
 function getsecurevariable($vn) {
  // Short introduction to variable variables ($$) 
  // $v = 'hue';
  // $hue = 'test';
  // $$v now represents the value of $hue which is 'test'
  if(!isset($_SESSION)) sessionstart();
  
  if (isset($_SESSION[$vn])) {
   $$vn=$_SESSION[$vn];
  } else {
   $$vn  = 0;
  }
  return $$vn;
 }
 
 function setsecurevariable($vn,$vv) {
  if(!isset($_SESSION)) sessionstart();
  $_SESSION[$vn]=$vv;
  return $vv;
 }
 
 function getvariablereq($vn) {
  $$vn    = $_GET[$vn];                  // get action from the request
  if (!$$vn) $$vn = $_POST[$vn];         // oops... may be here?
  return $$vn;
 }
 
 function sendmail($subject, $mail_template, $email = "microbook@mail.ru", $from_name = "cfteamru", $from_mail = "microbook@mail.ru") {
  if (!$from_name) $from_name = "cfteamru";
  if (!$from_mail) $from_mail = "microbook@mail.ru"; //"main@cfteam.ru";
  
  $charset = 'UTF-8';
  $headers = "From: " .$from_name." <".$from_mail.">\n"."Content-Type: text/html; charset=$charset; format=flowed\n"."MIME-Version: 1.0\n"."Content-Transfer-Encoding: 16bit\n"."X-Mailer: PHP/".phpversion()."\n";
  
  $ret    = 0;
//  $ret   += mail("microbook@mail.ru", $subject, $mail_template, $headers);
  $ret   += mail($email             , $subject, $mail_template, $headers);
  
  return $ret;
 }
 
 
 function send_mime_mail(
  $name_from, // имя отправителя
  $email_from, // email отправителя
  $name_to, // имя получателя
  $email_to, // email получателя
  $data_charset, // кодировка переданных данных
  $send_charset, // кодировка письма
  $subject, // тема письма
  $body, // текст письма
  $html = FALSE, // письмо в виде html или обычного текста
  $reply_to = FALSE
 ) {
  $to = mime_header_encode($name_to, $data_charset, $send_charset).' <' . $email_to.'>';
  $subject = mime_header_encode($subject, $data_charset, $send_charset);
  $from =  mime_header_encode($name_from, $data_charset, $send_charset).' <'.$email_from.'>';
  if($data_charset != $send_charset) {
   $body = iconv($data_charset, $send_charset, $body);
  }
  $headers = "From: ".$from."\r\n";
  $type = ($html) ? 'html' : 'plain';
  $headers .= "Content-type: text/".$type."; charset=".$send_charset."\r\n";
  $headers .= "Mime-Version: 1.0\r\n";
  if ($reply_to) {
   $headers .= "Reply-To: ".$reply_to;
  }
  return mail($to, $subject, $body, $headers);
 }
 
 function mime_header_encode($str, $data_charset, $send_charset) {
  if($data_charset != $send_charset) {
   $str = iconv($data_charset, $send_charset, $str);
  }
  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
 }
 
 
 function mkdirr($path) {
  $PHP_EOL = "\r\n";
  $dirs = explode("/",$path);
  $prevdirs = "";
  foreach ($dirs as $dir) {
   $prevdirs.=$dir."/";
   @mkdir($prevdirs);
//   echo $prevdirs.$PHP_EOL;
  }
//  print_r ($dirs);
 }
 
 function getrootdir() {
  return "http://".$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],'/'))."/";
 }
 
 function getrootdirsrv() {
  $s = $_SERVER['SCRIPT_FILENAME'];
  $ret = (substr($s, 0, strrpos($s, "/")))."/";
  return $ret;
 }
 
 function sort_by_order($f1,$f2) {
//  $_f1 = substr($f1, 0, strrpos($f1, '.')).'-';
//  $_f2 = substr($f2, 0, strrpos($f2, '.')).'-';
//  echo $f1."  ".$_f1." ".$_f2."<br>";
  if     ($f1->Order < $f2->Order)  return -1;
  elseif ($f1->Order > $f2->Order)  return  1;
  else                              return  0;
 }
 
 function addtolog($txt) {
  @mkdirr('data/logs');
  file_put_contents('data/logs/events.txt', (date("Y-m-d H:i:s"))."\t".$txt, FILE_APPEND);  // write to log in case of errors
//  echo $txt."<br>";
 }
 
 function sup($buf) {
//  $ret=str_replace(strprepare("м2"),strprepare("м<sup>2</sup>"),$buf);
  $ret=str_replace(("м2"),("м<sup>2</sup>"),$buf);
  return $ret;
 }
 
 function sdir($path='.', $mask='*', $nocache=0) {
  if (is_dir($path)) {
   static $dir = array();                                            // cache result in memory
   if ( !isset($dir[$path]) || $nocache) {
    $dir[$path] = scandir($path);
   }
   foreach ($dir[$path] as $i=>$entry) {
    if ($entry!='.' && $entry!='..' && fnmatch($mask, $entry) ) {
     $sdir[] = $entry;
    }
   }
  }
  return ($sdir); 
 }
 
 function format($v, $f) {
  $c = substr_count($f,"#");
  return sprintf("%0".$c."s",   $v);
 }
 
 function sessionstart() {
  $lifetime = 99999999;
  ini_set('session.gc_maxlifetime', $lifetime);
  ini_set('session.cookie_lifetime', $lifetime);
  ini_set('session.gc_probability', 1);
  
  session_set_cookie_params($lifetime,"/");
  session_cache_expire($lifetime);
  
  session_start();
  setcookie(session_name(),session_id(),time()+$lifetime);
 }
 
 function nbsp($s) {
  return str_replace(" ", "&nbsp;", $s);
 }
 
 function formatmicrotime($s) {
  return sprintf("%01.4f", $s);
 }
 
 function escape($txt) {
  $txt = str_replace(';','',$txt);
  $txt = str_replace('"','',$txt);
  $txt = str_replace("'",'',$txt);
  return $txt;
 }
 
 function getfilemtime($fname) {
  if (file_exists($fname)) {
   return filemtime($fname);
  } else {
   return 0;
  }
 }
 
 function backtrace() {
  $bt = debug_backtrace();
  $ret = "";
  for ($i=1; $i<sizeof($bt); $i++) {
   $bti = $bt[$i];
   $args = "";
   foreach ($bti['args'] as $arg) {
    if ($args) $args.=", ";
    $args.=$arg;
   }
   
   $ret.=$bti['file'].":".$bti['line']." ".$bti['class']."->".$bti['function']."(".$args.")<br>";
   
  }
  return $ret;
 }
 
 function convertBytes($value) {
  if ( is_numeric( $value ) ) {
   return $value;
  } else {
   $value_length = strlen( $value );
   $qty = substr( $value, 0, $value_length - 1 );
   $unit = strtolower( substr( $value, $value_length - 1 ) );
   switch ( $unit ) {
    case 'k':
     $qty *= 1024;
    break;
    case 'm':
     $qty *= 1048576;
    break;
    case 'g':
     $qty *= 1073741824;
    break;
   }
   return $qty;
  }
 }
 
 function renameEx($oldname, $newname) {
  if (file_exists($newname)) unlink($newname);
  if (file_exists($oldname)) {
   return rename ($oldname, $newname);
  } else {
   return 0;
  }
 }
 
 
 function cmp_sbn($a, $b) {
//  return strcmp($a->name, $b->name);
//  echo $a->id."-".$b->id."-".strcmp($a->id, $b->id)."<br>";
  return strcmp($a->name, $b->name);
 }
 
 function sortbyname (&$your_data) {
  usort($your_data, "cmp_sbn");
//  ajax_echo_r ($your_data);
 }
 
 function getOKS_TYPEs() {
  $fields = array();
  $fields['flat'] = "Квартира";
  return $fields;
 }
 
 function getFieldNames_Cad() {
  $fields = array();
  
  $fields['CAD_COST'] = "Кадастровая стоимость";
  $fields['OBJECT_ADDRESS'] = "Адрес";
  $fields['CAD_NUM'] = "Кадастровый номер";
  $fields['OKS_TYPE'] = "Тип объекта кап. строительства";
  $fields['AREA_VALUE'] = "Площадь";
  $fields['AREA_UNIT_STR'] = "Ед. изм. площади";
  $fields['AREA_UNIT'] = "Ед. изм. площади";
  $fields['RIGHT_REG_STR'] = "Наличие зарегистрированных прав";
  $fields['AREA_TYPE'] = "Тип площади";
  
//  $fields['RIGHT_REG'] = "Права на регистрацию";
  $fields['CAD_UNIT'] = "Ед. кад. стоимости (исх.)";
//  $fields['CAD_UNIT_STR'] = "Ед. кад. стоимости";
  $fields['DATE_COST_STR'] = "Дата оценки";
  $fields['ONLINE_ACTUAL_DATE_STR'] = "Дата актуализации";
  
//  $fields['DATE_COST'] = "Дата оценки";
//  $fields['ONLINE_ACTUAL_DATE'] = "Дата актуализации";
  
  /*
  $fields['OBJECT_ID'] = "Номер объекта"; //"2:56:30302:639";
  $fields['REGION_KEY'] = "Код региона"; // "102";
  $fields['OKS_EXECUTOR'] = "Наименование органа"; //"Стерлитамакский городской филиал  государственного унитарного предприятия Бюро технической инвентаризации Республики Башкортостан";
  
  $fields['PARCEL_CN'] = "02:56:030302:639";
  $fields['PARCEL_STATUS'] = "01";
  $fields['DATE_CREATE'] = "1309478400000";
  $fields['DATE_REMOVE'] = "";
  $fields['CATEGORY_TYPE'] = "";
  $fields['AREA_VALUE'] = "33";
  $fields['AREA_TYPE'] = "008";
  $fields['AREA_UNIT'] = "055";
  $fields['RIGHT_REG'] = "0";
  $fields['CAD_COST'] = "680478.81";
  $fields['CAD_UNIT'] = "383";
  $fields['DATE_COST'] = "1366329600000";
  $fields['ONLINE_ACTUAL_DATE'] = "1400630400000";
  $fields['DATE_LOAD'] = "1400630400000";
  $fields['CI_SURNAME'] = "";
  $fields['CI_FIRST'] = "";
  $fields['CI_PATRONYMIC'] = "";
  $fields['RC_DATE'] = "";
  $fields['RC_TYPE'] = "";
  $fields['CO_NAME'] = "";
  $fields['CO_INN'] = "";
  $fields['OBJECT_DISTRICT'] = "";
  $fields['DISTRICT_TYPE'] = "неопр";
  $fields['OBJECT_PLACE'] = "Стерлитамак";
  $fields['PLACE_TYPE'] = "г";
  $fields['OBJECT_LOCALITY'] = "";
  $fields['LOCALITY_TYPE'] = "неопр";
  $fields['OBJECT_STREET'] = "Лесная";
  $fields['STREET_TYPE'] = "ул";
  $fields['OBJECT_HOUSE'] = "61";
  $fields['OBJECT_BUILDING'] = "";
  $fields['OBJECT_STRUCTURE'] = "";
  $fields['OBJECT_APARTMENT'] = "24";
  $fields['UTIL_BY_DOC'] = "";
  $fields['UTIL_CODE'] = "";
  $fields['OKS_FLAG'] = "1";
  $fields['OKS_FLOORS'] = "";
  $fields['OKS_U_FLOORS'] = "";
  $fields['OKS_ELEMENTS_CONSTRUCT'] = "";
  $fields['OKS_YEAR_USED'] = "";
  $fields['OKS_INVENTORY_COST'] = "74989";
  $fields['OKS_INN'] = "";
  $fields['YEAR_BUILT'] = "";
  $fields['OKS_COST_DATE'] = "1230768000000";
  $fields['FORM_RIGHTS'] = "";
  $fields['OBJECTID'] = "";
  $fields['SHAPE'] = "";
  $fields['PARCEL_ID'] = "";
  $fields['TEMP_ID'] = "";
  $fields['PKK_ID'] = "";
  $fields['PARENT_ID'] = "";
  $fields['STATE_CODE'] = "";
  $fields['ANNO_TEXT'] = "";
  $fields['CP_VALUE'] = "";
  $fields['CATEGORY_CODE'] = "";
  $fields['ACTUAL_DATE'] = "";
  $fields['ERROR_CODE'] = "";
  $fields['XC'] = "6229578.1876";
  $fields['YC'] = "7101808.1702";
  $fields['XMIN'] = "6229150.0124";
  $fields['XMAX'] = "6230862.7133";
  $fields['YMIN'] = "7101118.8985";
  $fields['YMAX'] = "7102286.2976";
  $fields['DEL_FEATURE'] = "";
  $fields['G_AREA'] = "";
  $fields['SHAPE_Length'] = "";
  $fields['SHAPE_Area'] = "";
  $fields['OKS_ID'] = "";
  $fields['CAD_NUM_OLD'] = "";
  $fields['NAME'] = "";
  $fields['PARCEL_CADNUM'] = "";
  $fields['ERRORCODE'] = "1";
  */
  
  return $fields;
 }
 
 function getAreaUnits() {
  $ret = array (
   '003'=>"Миллиметр",
   '004'=>"Сантиметр",
   '005'=>"Дециметр",
   '006'=>"Метр",
   '008'=>"Километр",
   '009'=>"Мегаметр",
   '047'=>"Морская миля",
   '050'=>"Квадратный миллиметр",
   '051'=>"Квадратный сантиметр",
   '053'=>"Квадратный дециметр",
   '055'=>"Квадратный метр",
   '058'=>"Тысяча квадратных метров",
   '059'=>"Гектар",
   '061'=>"Квадратный километр"
  );
  return $ret;
 }
 
 function getRights() {
  $ret = array (
   '0'=>"Не зарегистрированы",
   '1'=>"Зарегистрированы"
  );
  return $ret;
 }
 
 
 function getFieldNames_Fir() {
  $fields = array();
  $fields['objectId']     = "Номер объекта";
//  $fields['srcObject']    = "srcObject";
  $fields['srcObjectStr'] = "Источник";
  $fields['regionKey']    = "Код региона";
  $fields['objectType']   = "Тип объекта";
  $fields['objectCn']     = "Кадастровый номер";
  $fields['objectCon']    = "Условный номер";
//  $fields['subjectId']    = "Субъект";
//  $fields['regionId']     = "Регион";
//  $fields['settlementId'] = "Поселение";
  $fields['street']       = "Улица";
  $fields['house']        = "Дом";
  $fields['apartment']    = "Квартира";
  $fields['addressNotes'] = "Адрес";
  $fields['okato']        = "Код ОКАТО";
//  $fields['nobjectCn']    = "nobjectCn";
//  $fields['nobjectCon']   = "nobjectCon";
  return $fields;
 }
 
 function getSrcObjects() {
  $fields = array();
  $fields['1']     = "ГКН";
  $fields['2']     = "ЕГРП";
  return $fields;
 }
 
?>
