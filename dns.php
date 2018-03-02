<?php
/*仅适用于Linux*/	

header('Content-type:application/json');
header("Access-Control-Allow-Origin: *");

$query = $_GET['q'];
$query = strtolower(trim($query));
$query = preg_replace('/^http:\/\//i','',$query);
if (empty($query)) {
	echo 'query 参数不能为空';
	exit();
}

$t = $_GET['t']; // 类型

function err($count, $type){
	if($count == -1) echo "<tr style=\"background-color:#FFFFCC;text-align:center;padding:5px\"><td style=\"padding:5px\">". strtoupper($type) ."记录</td><td colspan=\"4\" style=\"color:#FF0000\">没有相关记录或者查询超时,请稍后重试!</td></tr>";
}

function getDNS($type, $host, $target, $pri, $ttl){
	echo "<tr style=\"background-color:#FFFFFF;text-align:center;padding:5px\">
				<td style=\"padding:5px\">". $type ."记录</td>
				<td>". $host ."</td>
				<td>". $target ."</td>
				<td>". $pri ."</td>
				<td>". $ttl ."</td>";
}

if ("WINNT" == PHP_OS || "WIN32" == PHP_OS) {
	echo '本程序完全依赖dns_get_record, 不能运行在Windows平台, 请使用Linux/UNIX!';
	exit();
}

switch($t){
	default:
		echo '错误的参数';
		exit();
		break;
	case 'ns':	//=== NS记录 ===//
		$CACHE = dns_get_record($query, DNS_NS);
		$count = count($CACHE) - "1";
		// for ($i = 0; $i <= $count; $i++){
		// 	getDNS('NS',$CACHE[$i]['host'], $CACHE[$i]['target'], '-', $CACHE[$i]['ttl']);
		// }
		if ($count == -1) {
			echo '没有相关记录或者查询超时,请稍后重试!';
		} else {
			echo json_encode($CACHE);
		}
		break;	
	case 'a':	//=== A记录 ===//
		$CACHE = dns_get_record($query, DNS_A);
		$count = count($CACHE) - "1";
		// for ($i = 0; $i <= $count; $i++){
		// 	getDNS('A', $CACHE[$i]['host'], $CACHE[$i]['ip'], '-', $CACHE[$i]['ttl']);
		// }
		if ($count == -1) {
			echo '没有相关记录或者查询超时,请稍后重试!';
		} else {
			echo json_encode($CACHE);
		}
		break;
		
	case 'aaaa'://=== AAAA记录 ===//
		$CACHE = dns_get_record($query, DNS_AAAA);
		$count = count($CACHE) - "1";
		// for ($i = 0; $i <= $count; $i++){
		// 	getDNS('AAAA', $CACHE[$i]['host'], $CACHE[$i]['target'], '-', $CACHE[$i]['ttl']);
		// }
		if ($count == -1) {
			echo '没有相关记录或者查询超时,请稍后重试!';
		} else {
			echo json_encode($CACHE);
		}
		break;
		
	case 'mx'://=== MX记录 ===//
		$CACHE = dns_get_record($query, DNS_MX);
		$count = count($CACHE) - "1";
		// for ($i = 0; $i <= $count; $i++){
		// 	getDNS('MX', $CACHE[$i]['host'], $CACHE[$i]['target'], $CACHE[$i]['pri'], $CACHE[$i]['ttl']);
		// }
		if ($count == -1) {
			echo '没有相关记录或者查询超时,请稍后重试!';
		} else {
			echo json_encode($CACHE);
		}
		// err($count,'mx');
		break;
		
	case 'cname'://=== CNAME记录 ===//
		$CACHE = dns_get_record($query, DNS_CNAME);
		$count = count($CACHE) - "1";
		// for ($i = 0; $i <= $count; $i++){
		// 	getDNS('CNAME', $CACHE[$i]['host'], $CACHE[$i]['target'], '-', $CACHE[$i]['ttl']);
		// }
		// err($count,'cname');
		if ($count == -1) {
			echo '没有相关记录或者查询超时,请稍后重试!';
		} else {
			echo json_encode($CACHE);
		}
		break;
		
	case 'txt'://=== TXT记录 ===//
		$CACHE = dns_get_record($query, DNS_TXT);
		$count = count($CACHE) - "1";
		// for ($i = 0; $i <= $count; $i++){
		// 	getDNS('TXT', $CACHE[$i]['host'], $CACHE[$i]['txt'], '-', $CACHE[$i]['ttl']);
		// }
		// err($count,'txt');
		if ($count == -1) {
			echo '没有相关记录或者查询超时,请稍后重试!';
		} else {
			echo json_encode($CACHE);
		}
		break;
		
	case 'srv'://=== SRV记录 ===//
		$CACHE = dns_get_record($query, DNS_SRV);
		$count = count($CACHE) - "1";
		// for ($i = 0; $i <= $count; $i++){
		// 	getDNS('SRV', $CACHE[$i]['host'], $CACHE[$i]['target'], '-', $CACHE[$i]['ttl']);
		// }
		// err($count,'srv');
		if ($count == -1) {
			echo '没有相关记录或者查询超时,请稍后重试!';
		} else {
			echo json_encode($CACHE);
		}
		break;
}	
