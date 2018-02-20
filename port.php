<?php


$blackDomain = array('localhost', 'hechaocheng.cn', 'hechaoliang.cn'); // 屏蔽查询
$blackIP = array('10', '127', '168', '192'); // 屏蔽查询内网IP

class HccTools
{
    var $typ = array(' 未知 ', 'FTP', 'SSH', 'TELNET', 'SMTP', 'Whois', 'DNS', 'HTTP', 'POP3', 'NTP', 'IMAP', 'HTTPS', 'IMAP:SSL', 'SMTP:SSL', 'POP3:SSL', 'MSSQL/udp', 'MYSQL', 'TERMINAL', 'QQ Client', 'WWW 代理');
    var $por = array(0, 21, 22, 23, 25, 43, 53, 80, 110, 123, 143, 443, 993, 994, 995, 1433, 3306, 3389, 4000, 8080);

    public function runtime()
    {
        list($h, $c) = explode(' ', microtime());
        return ((float)$h + (float)$c);
    }

    public function check($ip, $port)
    {
        if (!function_exists('fsockopen'))
            return '终止:当前主机环境不支持 fsockopen! ';

        $start = $this->runtime();
        $fp = @fsockopen($ip, $port, $ErrCode, $ErrMsg, 0.2);
        $finish = $this->runtime();
        $execute = ceil(($finish - $start) * 1000);
        $service = array_search($port, $this->por);

        if (!$fp) {
            $fp && fclose($fp);
            return array(0, $port, $this->typ[$service], ' - ');
        } else {
            $fp && fclose($fp);
            return array(1, $port, $this->typ[$service], $execute);
        }
    }
}

error_reporting(E_ALL ^ E_NOTICE);
ini_set('max_execution_time', 0);
date_default_timezone_set('PRC');
header('Content-type:application/json');
header("Access-Control-Allow-Origin: *");
setcookie('HccToos', time(), time() + 2);
define(H, substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1));
define(S, $_SERVER['SERVER_NAME']);

$port = $_GET['port'];
$domain = $_GET['domain'];
//$port = '21,80,443,3306,3389,8080';
//$domain = 'www.baidu.com';

$Query = preg_replace("/\/(.*)$/is", '', preg_replace("/^(.*):\/\//is", '', $domain));
if (in_array(strtok($Query, '.'), $blackIP) || array_search($Query, $blackDomain))
    $limit = '操作受限:该请求不允许查询.';
if (!empty($Query) && isset($_COOKIE['HccToos']) && !isset($limit))
    $limit = '查询间隔时间太短,请稍后重试!';
$tools = new HccTools;



$ports = array();

if (!empty($Query) && !empty($port) && empty($limit) && !isset($_COOKIE['HccToos'])) {
    $port = explode(',', $port);
    $cc = array_filter(preg_replace('/\D/', '', array_filter($port)));
    sort($cc);
    $st = $tools->runtime();
    $count = 0;
    for ($i = 0; $i < count($cc); $i++) {
        if ($cc[$i] < 63365) {
            $tmp = $tools->check($Query, $cc[$i]);

            if (is_numeric($tmp[3])) {
                $time = $tmp[3];
            } else {
                $time = '';
            }

            $status = $tmp[0] == 1 ? '<font color="red">开放</font>' : '<font color="green">关闭</font>';
            $statu2 = $tmp[0] == 1 ? ' style="background-color:#EDE1E3"' : '';

            $port2['port'] = $tmp[1];
            $port2['service'] = $tmp[2];
            $port2['time'] = $time;
            $port2['opened'] = $tmp[0];
            $ports[]=$port2;
            $count++;
        }
    }
    $et = $tools->runtime();
    $rt = ceil(($et - $st) * 1000);
}

$json['code'] = 0;
$json['number'] = $count;
$json['time'] = $rt;
$json['data'] = $ports;
echo json_encode($json);
?>