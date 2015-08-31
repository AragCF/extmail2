var           ajaxRet = new Array();
var   select_defaults = new Array();

var               buf = '';
var         buf_theme = '';
var          filename = '';
var           srvroot = '';

//var      toolbar_left = '';
//var     toolbar_right = '';

var          isLoaded = 0;
var      lastMsgCount = 0;
var     prevscrolltop = 0;
var         splitmode = -1;

var            lastid = 0;
var         lastelsid = 0;
var       scrollThres = 0.9;

var   SmoothAnimation = 1;       // enabled by default

var          docWidth = 0;
var      prevdocWidth = 0;

function el(elid){
 if (document.getElementById(elid)) {
  return document.getElementById(elid);
 }
}

function ch_vis(mid){
 el(mid).style.display = (el(mid).style.display == 'block')?'none':'block';
}
function ch_vis2(mid){
 el(mid).style.visibility = (el(mid).style.visibility == 'visible')?'hidden':'visible';
 el(mid).style.height = (el(mid).style.visibility == 'visible')?0:440;
}
function ch_vis_inl(mid){
 el(mid).style.display = (el(mid).style.display == 'inline')?'none':'inline';
}

function hide(mid){
 var ele=el(mid);
 if (ele) {
  if (ele.style.display != 'none') ele.style.display = 'none';
 }
 if (mid=='popupeditor') {
  if (userAgent.indexOf('trident')>0) {      // if ie
   hide('popupeditorbkg');
  } else {
   hideEx('popupeditorbkg');
//   hide('popupeditorbkg');
  }
//  el('popupeditor').innerHTML='';
 }
}
function show(mid){
 if (mid=='popupeditor') {
  if (userAgent.indexOf('trident')>0) {      // if ie
   show('popupeditorbkg');
  } else {
   showEx('popupeditorbkg');
  }
 }
 var ele=el(mid);
 if (ele) {
  if (ele.style.display != 'block') ele.style.display = 'block';
 }
}
function showi(mid){
 var ele=el(mid);
 if (ele) {
  if (ele.style.display != 'inline') ele.style.display = 'inline';
 }
}


function hideo(o){
 if (o.style.display != 'none') o.style.display = 'none';
}
function showo(o){
 if (o.style.display != 'block') o.style.display = 'block';
}
function showoi(o){
 if (o.style.display != 'inline') o.style.display = 'inline';
}


function showEx(id) {
 if (SmoothAnimation) {
  jQuery('#'+id).fadeIn('fast');
 } else {
  jQuery('#'+id).show();
 }
}

function hideEx(id) {
 if (SmoothAnimation) {
  jQuery('#'+id).fadeOut('fast');
 } else {
  jQuery('#'+id).hide();
 }
}

function supertoggle(id) {
 if (jQuery('#'+id).css('display')!='block') {
  showEx(id);
 } else {
  hideEx(id);
 }
}


function safefill(elid,text){
 if (el(elid)) {
  el(elid).innerHTML=text;
 }
}

function safesetvalue(elid,text){
 if (el(elid)) {
  el(elid).value=filter(text);
 }
}

function trim(str) {
 return trimBoth(str);
}
function trimLeft(str) {
 return str.replace(/^\s+/, '');
}
function trimRight(str) {
 return str.replace(/\s+$/, '');
}
function trimBoth(str) {
 return trimRight(trimLeft(str));
}
function trimSpaces(str) {
 return str.replace(/\s{2,}/g, ' ');
}

var keyStr = "ABCDEFGHIJKLMNOP" +
             "QRSTUVWXYZabcdef" +
             "ghijklmnopqrstuv" +
             "wxyz0123456789+/" +
             "=";

function encode64(input) {
 input = escape(input);
 var output = "";
 var chr1, chr2, chr3 = "";
 var enc1, enc2, enc3, enc4 = "";
 var i = 0;

 do {
  chr1 = input.charCodeAt(i++);
  chr2 = input.charCodeAt(i++);
  chr3 = input.charCodeAt(i++);

  enc1 = chr1 >> 2;
  enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
  enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
  enc4 = chr3 & 63;

  if (isNaN(chr2)) {
   enc3 = enc4 = 64;
  } else if (isNaN(chr3)) {
   enc4 = 64;
  }
  
  output = output +
           keyStr.charAt(enc1) +
           keyStr.charAt(enc2) +
           keyStr.charAt(enc3) +
           keyStr.charAt(enc4);
  chr1 = chr2 = chr3 = "";
  enc1 = enc2 = enc3 = enc4 = "";
 } while (i < input.length);

 return output;
}

function strip_tags (input, allowed) {
 allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
 var tags               = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
     commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
 var ret = input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
  return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
 });
// ret = ret.replace(/<\s*(\w+).*?>/, '<$1>');
 return ret;
}



function filter(v) {
 return (strip_tags(v, "<b></b><i></i><u></u><sup></sup><br>"));
}
function linkfilter(v) {
// var s=new String;
// s=v;
// s=s.replace("/[&;']+/i",   '');
 return (strip_tags(v, ""));
}
function msgfilter(v) {
 var s=new String;
 s=v;
 s=s.replace(/`/g,     '&#96;' );
 s=s.replace(/'/g,     '&#39;' );
 s=s.replace(/"/g,     '&#34;' );
 s=s.split("\n").join("<br>");
 s=s.split("\r").join("");
 s=s.split("\f").join("");
 s=filter(s);
 return (s);
}

function addpopupeditortable() {
 if (popupdefault.length ==0) {
  popupdefault = el('popupdefault').innerHTML;
  el('popupdefault').innerHTML = "";
 }
 el('popupeditor').innerHTML=popupdefault;
}






function f_scrollTop() {
 return f_filterResults (
  window.pageYOffset ? window.pageYOffset : 0,
  document.documentElement ? document.documentElement.scrollTop : 0,
  document.body ? document.body.scrollTop : 0
 );
}
function f_filterResults(n_win, n_docel, n_body) {
 var n_result = n_win ? n_win : 0;
 if (n_docel && (!n_result || (n_result > n_docel)))
  n_result = n_docel;
 return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
}

function doscroll() {
 if (el('popupeditor')) {
  if (prevscrolltop!=f_scrollTop()) {
   prevscrolltop=f_scrollTop();
   el('popupeditor').style.top    = prevscrolltop+'px';
   el('popupeditorbkg').style.top = prevscrolltop+'px';
  }
 }
}






function gethtml (id) {
 if (el(id)) return el(id).innerHTML;
}

function sethtml (id, text) {
 if (el(id)) el(id).innerHTML=text;
}

function addhtml (id, text) {
 if (el(id)) el(id).innerHTML+=", "+text;
}

function setvalue (id, text) {
 if (el(id)) el(id).value=text;
}





function substr_count(string, subString, allowOverlapping) {
 string+=""; subString+="";
 if(subString.length<=0) return string.length+1;
 
 var n=0, pos=0;
 var step=(allowOverlapping)?(1):(subString.length);
 
 while(true){
  pos=string.indexOf(subString,pos);
  if(pos>=0){ n++; pos+=step; } else break;
 }
 return(n);
}


function substr(str, start, length) {
 if (length>0) {
  return str.substr(start, length);
 } else {
  return str.substr(start);
 }
}

function strpos(str, needle) {
 return str.indexOf(needle);
}

function strlen(str) {
 return str.length;
}












function getIframeDocument(iframeNode) {
 if (iframeNode.contentDocument) return iframeNode.contentDocument;
 if (iframeNode.contentWindow) return iframeNode.contentWindow.document;
 return iframeNode.document;
}

function gethtml (id) {
 if (el(id)) return el(id).innerHTML;
}

function sethtml (id, text) {
 if (el(id)) el(id).innerHTML=text;
}

function copyhtml (id, id2) {
 if (el(id) && el(id2)) el(id).innerHTML=el(id2).innerHTML;
}

function addhtml (id, text) {
 if (el(id)) el(id).innerHTML+=", "+text;
}

function setvalue (id, text) {
 if (el(id)) el(id).value=text;
}

function copyvalue (id, id2) {
 if (el(id) && el(id2)) el(id).value=el(id2).innerHTML;
}






function elv(id) {             // returns element value
 var e = el(id);
 if (e) {
  return e.value;
 } else {
  return '';
 }
}

function elh(id) {             // returns element value
 var e = el(id);
 if (e) {
  return e.innerHTML;
 } else {
  return '';
 }
}

function elvf(id) {            // returns element value filtered
 return msgfilter (elv(id));
}

function elvfc(id) {            // returns element value filtered
 return msgfilter (cinput_value(id));
}

function elsid(id) {           // returns element selected index (helper)
 return getSelectedId(id);
}

function elsv(id) {   // returns element selected index
 ele = el(id);
 if (ele) {
//  alert (ele.selectedIndex);
  if (ele.selectedIndex) {
   return ele.options[ele.selectedIndex].value;
  } else {
   if (ele.options.length>0) {
    return ele.options[0].value;
   }
  }
 }
}


function elvrid(id) {           // returns element selected index (helper)
 var els = el(id).getElementsByTagName('input');
 
 for (var i=0; i<els.length; i++) {
  if (els[i].checked) {
   return els[i].id;
  }
 }
 
// return getSelectedId(id);
}

function elc(id) {
 var e = el(id);
 if (e) {
  return e.checked;
 } else {
  return false;
 }
}

function elci(id) {
 var e = el(id);
 if (e) {
  return (e.checked)?1:0;
 } else {
  return 0;
 }
}

function fcbv(id) {
 return fcb_getvalue(id);
}

function getSelectedId(id) {   // returns element selected index
 ele = el(id);
 if (ele) {
//  alert (ele.selectedIndex);
  if (ele.selectedIndex) {
   return ele.options[ele.selectedIndex].id;
  } else {
   if (ele.options.length>0) {
    return ele.options[0].id;
   }
  }
 }
}


function setSelectedIndex(id, v) {
 ele = el(id);
 if (ele) {
  ele.selectedIndex=v;
 }
}

function setSelectedId(id, v) {
 ele = el(id);
 if (ele) {
  for (var i=0; i<ele.options.length; i++) {
   if (ele.options[i].id==v) {
    ele.selectedIndex=i;
    return true;
   }
  }
 }
}

function setSelectedText(id, v) {
 ele = el(id);
 if (ele) {
  for (var i=0; i<ele.options.length; i++) {
   if (ele.options[i].innerHTML==v) {
    ele.selectedIndex=i;
    return true;
   }
  }
 }
}

function setSelectedIdByTitle(id, title) {
 ele = el(id);
 if (ele) {
  for (var i=0; i<ele.options.length; i++) {
   if (ele.options[i].innerHTML == title) {
    ele.selectedIndex = i;
    return i;
   }
  }
 }
}















function showpopup(buttons) {
 addpopupeditortable();
 showbuttons(buttons);
 show('popupeditor');
}

function hidepopup() {
 hide('popupeditor');
 forcerefresh = 1;
 showtable(0);
}

function lastnumber(s) {
 return(s.substr(s.length-1));
}


function load(root) {
 srvroot = root;
 
 cinput_init();
 
 for (var n=0; n<6; n++) {
  select_defaults['addr'+n] = elh('addr'+n);
 }
 
 
 resize();
}

function resize() {
 var w = window.innerWidth  || document.documentElement.clientWidth  || document.body.clientWidth ;
 var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
 
// el('debug').innerHTML = w+" "+h;
}

function dosubmit(mode) {
 JSON.clear();                                          // here we use JSON to send data to server
 switch (mode) {
  case (1):    // user login
   el('loginresult').innerHTML=el('authorizing').innerHTML;
   JSON.addItem('username',  msgfilter(el('username').value)             );
   JSON.addItem('password',  hex_md5(el('password').value).toLowerCase() );
   var jsonstr = JSON.make();
   sjax_post('dologin','loginres',srvroot+'index.php',jsonstr);
   if (ajaxRet['loginres']=='##refresh##') {
    document.location.href = document.location.href;
   } else {
    el('loginresult').innerHTML=ajaxRet['loginres'];
   }
  break;
  case (2):    // partnership request
   JSON.addItem('name',      msgfilter(el('name'    ).value)             );
   JSON.addItem('email',     msgfilter(el('email'   ).value)             );
   JSON.addItem('comments',  msgfilter(el('comments').value)             );
   var jsonstr = JSON.make();
   sjax_post('newpartner','submitresult',srvroot+'index.php',jsonstr);
  break;
 }
}

function dologout() {
// JSON.clear();                                          // here we use JSON to send data to server
// JSON.addItem('a_ction',    msgfilter(el('dologout'    ).value)             );
 
// var jsonstr = JSON.make();
 
// ajaxRet['logoutres'] = "a";
 sjax_post('dologout','logoutres',srvroot+'index.php');
 if (ajaxRet['logoutres']=='##refresh##') {
  document.location.href = document.location.href;
 } else {
  alert ('dologout() result: '+ajaxRet['logoutres']);
 }
}






var cinput_defaults = new Array();
var    cinput_first = new Array();

function cinput_init() {
 var els = document.getElementsByClassName("input");
 for (var i=0; i<els.length; i++) {
  cinput_defaults[els[i].id] = els[i].value;
//  alert (els[i].id+": "+cinput_defaults[els[i].id]);
 }
}

function cinput_click(id) {
 
}

function cinput_focus(id) {
 if (el(id).value == cinput_defaults[id]) {
  el(id).value = "";
//  el(id).className = "";
 }
}

function cinput_blur(id) {
 var v = el(id).value;
 if (v == "") {
  el(id).value = cinput_defaults[id];
//  alert (cinput_defaults[id]);
  
//  el(id).className = "input_grayed";
 }
}

function cinput_keyup(id, e) {
 switch (e.keyCode) {
  case (10):                             // ENTER key
  case (13):                             // ENTER key
//   if (e.ctrlKey) {
    switch (id) {
     case ("cadnum0"):
      getData(0);
     break;
     case ("street"):
     case ("house"):
     case ("building"):
     case ("structure"):
     case ("apartment"):
      getData(1);
     break;
    }
//   }

/*   
   switch (id) {
    default:
     alert ("cinput_keyup: id="+id+", value="+el(id).value);
    break;
   }
*/
  break;
  default:
//   alert (e.ctrlKey);
//   alert (e.keyCode);
  break;
 }
}

function cinput_value(id) {
 if (el(id).value == cinput_defaults[id]) {
  return ("");
 } else {
  return (el(id).value);
 }
}








function setUserParameter(name, value) {
 if (name=='SmoothAnimation') SmoothAnimation = parseInt(value);
 
 JSON.clear();                                          // here we use JSON to send data to server
 JSON.addItem(  'name', name                );
 JSON.addItem( 'value', value               );
 var jsonstr = JSON.make();
 
 ajax_post('setUserParameter','res',srvroot+'index.php',jsonstr,c_setUserParameter);
}

function c_setUserParameter() {
 sethtml('sethue_status',ajaxRet['res']);
 if (ajaxRet['res']=='##refresh##') {
  sethtml('sethue_status',el('needrefresh').innerHTML);
  //document.location.href = document.location.href;
 }
}

function getData(id) {
 copyhtml("result"+id, "loading");
 
 JSON.clear();                                          // here we use JSON to send data to server
 JSON.addItem(  'id', id                );
 
 switch (id) {
  case (0):
   JSON.addItem( 'cadnum0', elvf('cadnum0')               );
  break;
  case (1):
  
   JSON.addItem(     'srcObject', elsid('srcObject')            );
   JSON.addItem( 'macroRegionId', elsid('addr0' )               );
   JSON.addItem(      'regionId', elsid('addr1' )               );
   JSON.addItem(  'settlementId', elsid('addr2' )               );
   JSON.addItem(    'streetType', elsv ('addr4' )               );
   JSON.addItem(        'street', elvfc('street')               );
   JSON.addItem(         'house', elvfc('house')                );
   JSON.addItem(      'building', elvfc('building')             );
   JSON.addItem(     'structure', elvfc('structure')            );
   JSON.addItem(     'apartment', elvfc('apartment')            );
  break;
 }
 
 var jsonstr = JSON.make();
 
 ajax_post('getData','result'+id,srvroot+'index.php',jsonstr,c_getData);
}

function c_getData() {
 
}

function fillSelect(id) {
 var dest = "addr"+(id+1);
 if (lastelsid != elsid("addr"+id)) {
  lastelsid = elsid("addr"+id);
  if (lastelsid=='-1') {
//   if (!select_defaults[dest]) {
//    select_defaults[dest] = elh(dest);
//    alert (select_defaults[dest]);
//   } else {
//    alert (id);
    sethtml(dest,select_defaults[dest]);
//   }
  } else {
   lastid = id;
   copyhtml("loader_addr"+id, "loadingsmall");
   
   JSON.clear();                                          // here we use JSON to send data to server
   JSON.addItem(    'id', id                );
   JSON.addItem( 'elsid', lastelsid         );
   var jsonstr = JSON.make();
   
   ajax_post('fillSelect',dest,srvroot+'index.php',jsonstr,c_fillSelect);
//  ajax_post('fillSelect','debug',srvroot+'index.php',jsonstr,c_fillSelect);
  }
 }
}

function c_fillSelect() {
 sethtml("loader_addr"+lastid, "");
 
// copyhtml ("debug", "addr"+(lastid+1));
 
 var els = el("addr"+(lastid+1)).getElementsByTagName('option');
 
// alert (els.length);
 
 if (els.length==2) {
  setSelectedIndex("addr"+(lastid+1),1);
  if (lastid<2) fillSelect(lastid+1);
 }
 
}

function showDetails(cadnum) {
 setvalue('cadnum0', cadnum);
 
 if (window.pageYOffset) window.pageYOffset=0;
 if (document.documentElement) document.documentElement.scrollTop=0;
 if (document.body) document.body.scrollTop=0;
 
 getData(0);
}

function saveEmail() {
 if (el('Email')) {
//  alert (elvf('Email'));
  JSON.clear();                                          // here we use JSON to send data to server
  JSON.addItem(    'Email', elvf('Email') );
  JSON.addItem(    'invid', elv ('invid') );
  var jsonstr = JSON.make();
  
  ajax_post('saveEmail','debug',srvroot+'index.php',jsonstr);
  
 }
}

