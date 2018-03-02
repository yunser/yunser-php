<?php

header('Content-type:application/json');
header("Access-Control-Allow-Origin: *");

$domain = $_GET['domain'];
$whoisservers = array(
	"aero"=>"whois.aero",
	"arpa" =>"whois.iana.org",
	"asia" =>"whois.nic.asia",
	"at" =>"whois.nic.at",
	"be" =>"whois.dns.be",
	"biz" =>"whois.biz",
	"br" =>"whois.registro.br",
	"ca" =>"whois.cira.ca",
	"cc" =>"whois.nic.cc",
	"cn" =>"whois.cnnic.net.cn",
	"com" =>"whois.verisign-grs.com",
	"gov" =>"whois.nic.gov",
	"in" =>"whois.inregistry.net",
	"co.in" =>"whois.inregistry.net",
	"net.in" =>"whois.inregistry.net",
	"org.in" =>"whois.inregistry.net",
	"ind.in" =>"whois.inregistry.net",
	"firm.in" =>"whois.inregistry.net",
	"info" =>"whois.afilias.info",
	"int" =>"whois.iana.org",
	"is" =>"whois.isnic.is",
	"it" =>"whois.nic.it",
	"jobs" =>"jobswhois.verisign-grs.com",
	"me" =>"whois.meregistry.net",
	"mil" =>"whois.nic.mil",
	"mobi" =>"whois.dotmobiregistry.net",
	"museum" =>"whois.museum",
	"name" =>"whois.nic.name",
	"net" =>"whois.verisign-grs.net",
	"org" =>"whois.pir.org",
	"pro" =>"whois.registrypro.pro",
	"tc" =>"whois.adamsnames.tc",
	"tel" =>"whois.nic.tel",
	"travel" =>"whois.nic.travel",
	"tv" => "whois.www.tv",
	"co.uk" =>"whois.nic.uk",
	"org.uk" =>"whois.nic.uk",
	"us" =>"whois.nic.us",
	"ws" =>"whois.website.ws");

function LookupDomain($domain){
	global $whoisservers;
	$whoisserver = "";

	$dotpos=strpos($domain,".");
	$domtld=substr($domain,$dotpos+1);

	$whoisserver = $whoisservers[$domtld];

	if(!$whoisserver) {
		return "Error: No appropriate Whois server found for <b>$domain</b> domain!";
	}
	$result = QueryWhoisServer($whoisserver, $domain);
	if(!$result) {
		return "Error: No results retrieved $domain !";
	}

	preg_match("/Whois Server: (.*)/", $result, $matches);
	$secondary = $matches[1];
	if($secondary) {
		$result = QueryWhoisServer($secondary, $domain);
	}
		return  $result;
}

function QueryWhoisServer($whoisserver, $domain) {
	$port = 43;
	$timeout = 10;
	$fp = @fsockopen($whoisserver, $port, $errno, $errstr, $timeout) or die("Socket Error " . $errno . " - " . $errstr);
	fputs($fp, $domain . "\r\n");
	$out = "";
	while(!feof($fp)){
		$out .= fgets($fp);
	}
	fclose($fp);
	return $out;
}

if($domain) {
	if(!preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", $domain)) {
		die("查询域名WHOIS格式, 比如. <i>cnyinxingshu.com</i>!");
	}
	$result = LookupDomain($domain);
	echo $result;
} else {
	echo '找不到信息';
}
