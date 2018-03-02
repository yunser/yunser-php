<?php
if(function_exists('date_default_timezone_set')){
	date_default_timezone_set('Asia/Shanghai'); //设定时区
}
define("APP_ROOT",dirname(dirname(__FILE__))); //网站根目录

function visitorIP(){ //访问者IP
	if($_SERVER['HTTP_X_FORWARDED_FOR']){
			 $ipa = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}elseif($_SERVER['HTTP_CLIENT_IP']){
			 $ipa = $_SERVER['HTTP_CLIENT_IP'];
		}else{
			 $ipa = $_SERVER['REMOTE_ADDR'];
	}
	return $ipa;
}

function cleanDomain($q,$w=0){ //整理域名 $w=1过滤www.前缀 $w=0不过滤
	$q = htmlspecialchars(strtolower(trim($q)));
	if(substr($q,0,7) == "http://" || substr($q,0,8) == "https://" || substr($q,0,6) == "ftp://"){
		$q = str_replace("http:/","",$q);
		$q = str_replace("https:/","",$q);
		$q = str_replace("ftp:/","",$q);
	}
	if(substr($q,0,4) == "www." && $w==1) {
		$q = str_replace("www.","",$q);
	}
	$q = trim($q,"/");
	return $q;
}

//获取网页
class HTTPRequest
{
/*
获取网页
*/
   var $_fp;        // HTTP socket
   var $_url;        // full URL
   var $_host;        // HTTP host
   var $_protocol;    // protocol (HTTP/HTTPS)
   var $_uri;        // request URI
   var $_port;        // port
   
   // scan url
   function _scan_url()
   {
       $req = $this->_url;
       
       $pos = strpos($req, '://');
       $this->_protocol = strtolower(substr($req, 0, $pos));
       
       $req = substr($req, $pos+3);
       $pos = strpos($req, '/');
       if($pos === false)
           $pos = strlen($req);
       $host = substr($req, 0, $pos);
       
       if(strpos($host, ':') !== false)
       {
           list($this->_host, $this->_port) = explode(':', $host);
       }
       else 
       {
           $this->_host = $host;
           $this->_port = ($this->_protocol == 'https') ? 443 : 80;
       }
       
       $this->_uri = substr($req, $pos);
       if($this->_uri == '')
           $this->_uri = '/';
   }
   
   // constructor
   function HTTPRequest($url)
   {
       $this->_url = $url;
       $this->_scan_url();
   }
   
   // download URL to string
   function DownloadToString()
   {
       $crlf = "\r\n";
       $response="";
       // generate request
       $req = 'GET ' . $this->_uri . ' HTTP/1.0' . $crlf
           .    'Host: ' . $this->_host . $crlf
           .    $crlf;
       
       // fetch
       $this->_fp = @fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port);
       @fwrite($this->_fp, $req);
       while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp))
           $response .= fread($this->_fp, 1024);
       @fclose($this->_fp);
       
       // split header and body
       $pos = strpos($response, $crlf . $crlf);
       if($pos === false)
           return($response);
       $header = substr($response, 0, $pos);
       $body = substr($response, $pos + 2 * strlen($crlf));
       
       // parse headers
       $headers = array();
       $lines = explode($crlf, $header);
       foreach($lines as $line)
           if(($pos = strpos($line, ':')) !== false)
               $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));
       
       // redirection?
       if(isset($headers['location']))
       {
           $http = new HTTPRequest($headers['location']);
           return($http->DownloadToString($http));
       }
       else 
       {
           return($body);
       }
   }
}

function get_html($siteurl) {
	//将网页代码存入字符串
	$r=new HTTPRequest($siteurl);
	$htm=$r->DownloadToString();
	return $htm;
}

$visitorip = visitorIP();

$q = cleanDomain($_POST['q']);
$q_encode = urlencode($q);

$title = "同IP站点查询";

$chaxun_status = 0; //查询状态 -1是没有查询参数，0是查询出错，1是查域名，2是查IP

if(isset($_GET['action']) && trim($_GET['action']) == "do"){ //AJAX调出数据
	$ipArr = ReverseIP($q);
	if(count($ipArr)>0){
		echo '<p class="f14">在此IP找到了'.count($ipArr).'个域名，见下：</p>';
		echo '<ul class="lst">';
		for($i=0;$i<count($ipArr);$i++){
			echo '<li><a href="http://'.$ipArr[$i].'/" title="访问 '.$ipArr[$i].'" target="_blank" class="f14 l200">'.$ipArr[$i].'</a></li>';
		}
		echo '</ul><div class="cboth"></div>';
	}else{
		echo '<p class="f14">没有找到IP '.$ip.' 对应的域名记录！</p>';
	}
	die();
}

function IpToInt($Ip){ //IP转为数字
    $array=explode('.',$Ip);
    $Int=($array[0] * 256*256*256) + ($array[1]*256*256) + ($array[2]*256) + $array[3];
    return $Int;
}

function ReverseIP($q){
	$htm = get_html('http://www.ip-adress.com/reverse_ip/'.$q);
	preg_match_all('/<a href=\"\/whois\/(.*)\">Whois<\/a>/', $htm, $tt);
	$res = $tt[1];
	return $res;
}

if(preg_match("/[a-zA-Z\-_]+/si",$q)){ //如果查询的是域名
	$ip = gethostbyname($q);
	if($ip == $q){
		$ip = $visitorip;
		$chaxun_status = -1;
	}else{
		$chaxun_status = 1;
	}
}elseif(ereg("^[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$",$q)){ //如果查询的是IP
	$ip = $q;
	$chaxun_status = 2;
}else{
	$ip = $visitorip;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>同IP站点查询</title>
<style>
*{margin:0;padding:0;}
body{font-size:12px;font-family: Geneva, Arial, Helvetica, sans-serif;}
a img {border:0;}

.red{color:#f00;}
.center{text-align:center;}
p{padding:5px 0 6px 0;word-break:break-all;word-wrap:break-word;}
.f14{font-size:14px;}
a,a:visited{color:#0353ce;}
table {font-size:12px;}
	table th {font-size:12px;font-weight:bold;background-color:#f7f7f7;line-height:200%;padding: 0 5px;}
	table th {font-size:12px;font-weight:bold;background:#EDF7FF;padding: 0 5px;color:#014198;line-height:200%;}
.red{color:red}
.blue{color:blue}
#footer{line-height:150%;text-align:center;color:#9c9c9c;padding: 8px 0;}
	#footer a,#footer a:visited{color:#9c9c9c;}
ul.lst{padding:0;margin:0;width:100%;}
	ul.lst li{list-style-type:none;float:left;padding:0 0 0 12px;line-height:24px;height:24px;overflow:hidden;}
	ul.lst li{width:258px!important;width:270px;}
</style>
<? if($chaxun_status>0){ ?>
<SCRIPT type="text/javascript">
<!--
	var xmlHttp;
	function creatXMLHttpRequest() {
		if(window.ActiveXObject) {
			xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
		} else if(window.XMLHttpRequest) {
			xmlHttp = new XMLHttpRequest();
		}
	}

	function startRequest() {
		var queryString;
		var domain = "<?=$ip?>";
		queryString = "q=" + domain;
		creatXMLHttpRequest();
		xmlHttp.open("POST","./?action=do","true");
		xmlHttp.onreadystatechange = handleStateChange;
		xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
		xmlHttp.send(queryString);
	}

	function handleStateChange() {
		if(xmlHttp.readyState == 1) {
			document.getElementById('ipresult').style.cssText = "";
			document.getElementById('ipresult').innerHTML = '<span class="green">结果加载中，请稍等...</span>';
		}
		if(xmlHttp.readyState == 4) {
			if(xmlHttp.status == 200) {
				document.getElementById('ipresult').style.cssText = "";
				var allcon =  xmlHttp.responseText;
				document.getElementById('ipresult').innerHTML = allcon;
			}
		}
	}
 
//-->
</SCRIPT>
<? } ?>
</head>

<body>
<div align="center">
<table cellspacing="4" cellpadding="0" style="background-color:#f7f7f7;border-bottom:1px solid #dfdfdf;" width="778">
<tr><td align="left"><a href="http://www.911cha.com/" target="_blank">实用查询大全</a> &gt; <a href="http://reverseip.911cha.com/" target="_blank">同IP站点查询</a></td><td align="right"><a href="javascript:;" onClick="window.external.AddFavorite(document.location.href,document.title);">收藏本页</a></td></tr></table>
<div id="result"><br />
<table width="700" cellpadding="2" cellspacing="0" style="border:1px solid #B2D0EA;">
<tr>
<th align="left"><a href="http://reverseip.911cha.com/" target="_blank">同IP站点查询</a></th>
</tr>
<tr><td align="center">
<table border="0" cellPadding="0" cellSpacing="1">
<tr><td style="font-size:14px">
<br />
<form action="" method="post" name="f1">IP地址或域名 <input name="q" id="q" type="text" size="18" delay="0" value="<? if($chaxun_status>0) echo $q; ?>" style="width:200px;height:22px;font-size:16px;font-family: Geneva, Arial, Helvetica, sans-serif;" /> <input type="submit" value=" 查询 " /></form>
</td></tr></table><br />
</td></tr>
<tr><td align="center" valign="middle" height="40" style="font-size:12px">输入域名或者IP地址，查询同一IP地址的服务器上有哪些网站。</td></tr>
</table>
<br />
<table width="700" cellpadding=2 cellspacing=0 style="border:1px solid #B2D0EA;">
<tr>
<th align="left"><?
	if($chaxun_status==1){
		echo '<a href="./">'.$title.'</a> &gt; 域名: '.$q;
	}elseif($chaxun_status==2){
		echo '<a href="./">'.$title.'</a> &gt; IP: '.$ip;
	}else{
		echo $title;
	}
?></th>
</tr>
<tr><td align="left">
<div style="padding:20px">
<p class="f14">
<?
	if(!$q){
		$ipq = '您的IP地址是：<span class="blue f14">'.$ip.'</span>';
	}elseif($chaxun_status == 0){
		$ipq = '<b class="red f14">出错啦！</b>没有找到与 <b class="blue f14">'.$q.'</b> 匹配的结果，请确定IP/域名的格式是否写对！</p><p class="f14 blue">你的IP地址是：'.$ip;
	}elseif($chaxun_status==1){
		$ipq = '你查询的域名 <span class="blue f14">'.$q.'</span></p><p class="f14">域名的IP: <span class="blue f14">'.$ip.'</span>';
	}else{
		$ipq = "你查询的IP：".$ip;
	}
	echo $ipq;
	?></p><? if($chaxun_status>0){ ?>
	<div id="ipresult"></div><script>startRequest();</script>
<? } ?></p><hr style="border-style: dotted;" color="#cccccc" size="1" /><p align="center">相关查询：<a href="http://ip.911cha.com/" target="_blank">IP地址查询</a> <a href="http://whois.911cha.com/" target="_blank">WHOIS查询</a> <a href="http://ipwhois.911cha.com/" target="_blank">IPWHOIS地址查询</a> <a href="http://pr.911cha.com/" target="_blank">PR值查询</a> <a href="http://reverseip.911cha.com/" target="_blank">同IP站点查询</a> <a href="http://ip2country.911cha.com/" target="_blank">IP所在国家查询</a></p>
</div>
</td></tr>
</table><br />
</div>
</div>
<div id="footer">&copy; 2009 <a href="http://www.911cha.com/">实用查询大全</a></div>
<div style="display:none;"><script language="javascript" src="http://www.911cha.com/out.js"></script></div>
</body>
</html>