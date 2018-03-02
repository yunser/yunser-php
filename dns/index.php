<?php
/*仅适用于Linux*/	
$toolsName = "DNS检测/查询记录";//工具名称
$query = $_GET['q'];
$query = strtolower(trim($query));
$query = preg_replace('/^http:\/\//i','',$query);
$t = $_GET['t'];
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if(!empty($query)){echo $toolsName . " - " . $query;}else{echo $toolsName;}?></title>
<style type="text/css">
<!--
body{text-align:center;margin:0px}
body,td,textarea,p,select,input{font-size:12px;line-height:120%;word-break:break-all;font-family: Arial, Helvetica, sans-serif}
a:link {text-decoration: none;}
a:visited {text-decoration:none;}
a:hover {text-decoration: underline;}
a:active {text-decoration: none;}
td,input,button {color:#333;font:12px Tahoma,Arial, sans-serif;line-height:16px;}
input,button{vertical-align:middle;}
.HCC_search{width:120px;height:10px;vertical-align:top;background-position:0px center;background-image:url(http://static.hechaocheng.cn/PublicHCC/images/serach_green_16x16.png);background-repeat:no-repeat;padding:2px 2px 2px 16px;color:green;border:1px solid #cde4f2;}
.ToolsHCC		{position: relative;margin:margin:0px}
#ToolsHCC_body	{width:960px;height:auto;overflow:hidden;margin-top:0px;margin-right: auto;margin-bottom: 0;margin-left: auto;}
.ToolsHCC_Item	{width:960px;border:0px;margin-left:0px;margin-top:0px;margin-right:0px;margin-bottom:0px}
.ToolsHCC_Item_Bar	{margin-top:0px;width:100%;border-collapse:collapse;border:solid 1px #d4e6f7;margin-bottom:10px;font-size:18px;color:#000000;padding:0px 5px;}
.ToolsHCC_Item_Title{height:25px;background-color:#d4e6f7;padding:0px 2px 3px 4px;background-image:url('http://static.hechaocheng.cn/PublicHCC/images/submit.gif');text-align:left}
.Tools_index{padding-top:10px;padding-bottom:10px;background-position:center;text-align:center;background:url('http://static.hechaocheng.cn/PublicHCC/images/box-bg.gif') repeat-x 50% top;}
.input{border:1px solid #94c6e1;background:#fff;color:#22ac38;font-weight:bold;padding:5px;margin-bottom:5px;font-size:13px;}
.submit	{border:1px solid #c5e2f2;background:#cde4f2 url('http://static.hechaocheng.cn/PublicHCC/images/submit.gif') repeat-x 50% top;cursor:pointer;width: auto;padding:5px 5px 5px 5px;vertical-align:top;}
.HCC_Info{width:924px;border-left:#3cc051/*#468847*/ 5px solid;background-color:#EBFCEE;padding:2px 3px 5px 5px;font-weight:normal;font-size:13px;color:#777;margin-bottom:10px}
-->
</style>
</head>
<body>
<div id="ToolsHCC_body">
	<div class="ToolsHCC">
	<table class="ToolsHCC_Item">
	<tr>
		<td colspan="2">
		<table class="ToolsHCC_Item_Bar">
  		<tr>
			<td class="ToolsHCC_Item_Title"><form action="http://s.hechaocheng.cn/cse/search" method="get" target="_blank"><input type="hidden" name="s" value="17714625536540733018"><input type="hidden" name="tn" value="98064500_hao_pg">
				<span style="float:right"><a href="http://hechaocheng.cn/php-tools-dns/" target="_blank" style="color:red" title="下载[DNS检测/查询]源码">下载本工具源码<img src="http://static.hechaocheng.cn/PublicHCC/images/hot_17x7.gif" width="17" height="11" border="0"></a><input type="text" name="q" id="q" $="true" class="HCC_search" required="" placeholder="查找资料" sug="text" /></span>
				<div style="width:680px"><a href="http://<?=$_SERVER['SERVER_NAME'];?>/" style="color:blue"><img src="http://hechaocheng.cn/favicon.ico" width="14" height="14" border="0" style="vertical-align:text-bottom"><?=$toolsName?></a></div>
			</form></td>
		</tr>
		<tr><td align="center" style="padding:10px;background-color:#FFFFCC;color:blue">致谷歌搜索忠实用户：<a href="http://s1.blsun.net/" target="_blank" style="color:blue">Google</a>镜像搜索上线啦!有了 <a href="http://s1.blsun.net/" target="_blank" style="color:red">http://s1.BLsun.Net</a> 就再也不用翻墙挂代理了,如此方便,你还不赶快来试试吧!!<br /><font color="#999999">提示: 如果出现无法访问该镜像,请尝试改变前缀s1为其他的前缀(例如:s2、s3、s4、s5....)即可[前缀存在的情况下]</font></td>
		</tr>
 		<tr>
			<td class="Tools_index">
			<?php
			if("WINNT" != PHP_OS && "WIN32" != PHP_OS){?>
            <form id="hechaocheng" method="GET">
			<span style="line-height:22px;color:#333;font-size:14px;width:354px;color:#22ac38">HTTP://</span>
			<input type="text"  class="input" onFocus="this.select()" name="q"  value="<?=$query;?>" autofocus="autofocus" required="" placeholder="hechaocheng.cn" />
			<input type="submit" class="submit" value="查询" /> <font color="red">新功能:</font><a href="http://7sbpvy.com2.z0.glb.qiniucdn.com/" target="_blank" title="Gravatar头像被墙的最佳解决方案" style="color:red">Gravatar头像缓存上线啦!</a>
			<p style="margin-top:10px">查询类型：
			<input type='radio' name="t" value="ns" id="ns" <?php if(empty($t) || 'ns' == $t){echo "checked=\"checked\"";}?> /><label for="ns">NS记录</label>
			<input type='radio' name="t" value="a" id="a" <?php if('a' == $t){echo "checked=\"checked\"";}?> /><label for="a">A记录</label>
			<input type='radio' name="t" value="cname" id="cname" <?php if('cname' == $t){echo "checked=\"checked\"";}?> /><label for="cname">CNAME记录</label>
			<input type='radio' name="t" value="mx" id="mx" <?php if('mx' == $t){echo "checked=\"checked\"";}?> /><label for="mx">MX记录</label>
			<input type='radio' name="t" value="txt" id="txt" <?php if('txt' == $t){echo "checked=\"checked\"";}?> /><label for="txt">TXT记录</label>
			<input type='radio' name="t" value="aaaa" id="aaaa" <?php if('aaaa' == $t){echo "checked=\"checked\"";}?> /><label for="aaaa">AAAA记录</label>
			</p>
			</form>
			<center><div style="width:728px;height:auto;text-align:left">
			<?php if(!empty($query)){?>
			<table style="width:728px;background-color:#B2B2B2;border:0;margin-top:15px;margin-bottom:15px" align="center" cellpadding="5" cellspacing="1">
			<tr style="background-color:#D4E6F7;text-align:center">
				<td style="padding:5px" width="80">记录类型</td>
				<td>主机记录</td>
				<td>记录值</td>
				<td width="60">MX优先级</td>
				<td width="40" title="生存时间"><a href="http://s.hechaocheng.cn/cse/search?s=17714625536540733018&tn=98064500_hao_pg&q=ttl" target="_blank" style="color:#000">TTL</a></td>
			</tr>
			<?php
			switch($t){
				default:echo "<tr style=\"background-color:#FFFFCC;text-align:center;padding:5px\"><td colspan=\"5\" style=\"color:#FF0000\">错误的参数!</td></tr>";break;
				case 'ns':	//=== NS记录 ===//
					$CACHE = dns_get_record($query, DNS_NS);
					$count = count($CACHE) - "1";
					for ($i = 0; $i <= $count; $i++){
						getDNS('NS',$CACHE[$i]['host'], $CACHE[$i]['target'], '-', $CACHE[$i]['ttl']);
					}
					err($count,'ns');break;
					
				case 'a':	//=== A记录 ===//
					$CACHE = dns_get_record($query, DNS_A);
					$count = count($CACHE) - "1";
					for ($i = 0; $i <= $count; $i++){
						getDNS('A', $CACHE[$i]['host'], $CACHE[$i]['ip'], '-', $CACHE[$i]['ttl']);
					}
					err($count,'a');break;
					
				case 'aaaa'://=== AAAA记录 ===//
					$CACHE = dns_get_record($query, DNS_AAAA);
					$count = count($CACHE) - "1";
					for ($i = 0; $i <= $count; $i++){
						getDNS('AAAA', $CACHE[$i]['host'], $CACHE[$i]['target'], '-', $CACHE[$i]['ttl']);
					}
					err($count,'aaaa');break;
					
				case 'mx'://=== MX记录 ===//
					$CACHE = dns_get_record($query, DNS_MX);
					$count = count($CACHE) - "1";
					for ($i = 0; $i <= $count; $i++){
						getDNS('MX', $CACHE[$i]['host'], $CACHE[$i]['target'], $CACHE[$i]['pri'], $CACHE[$i]['ttl']);
					}
					err($count,'mx');break;
					
				case 'cname'://=== CNAME记录 ===//
					$CACHE = dns_get_record($query, DNS_CNAME);
					$count = count($CACHE) - "1";
					for ($i = 0; $i <= $count; $i++){
						getDNS('CNAME', $CACHE[$i]['host'], $CACHE[$i]['target'], '-', $CACHE[$i]['ttl']);
					}
					err($count,'cname');break;
					
				case 'txt'://=== TXT记录 ===//
					$CACHE = dns_get_record($query, DNS_TXT);
					$count = count($CACHE) - "1";
					for ($i = 0; $i <= $count; $i++){
						getDNS('TXT', $CACHE[$i]['host'], $CACHE[$i]['txt'], '-', $CACHE[$i]['ttl']);
					}
					err($count,'txt');break;
					
				case 'srv'://=== SRV记录 ===//
					$CACHE = dns_get_record($query, DNS_SRV);
					$count = count($CACHE) - "1";
					for ($i = 0; $i <= $count; $i++){
						getDNS('SRV', $CACHE[$i]['host'], $CACHE[$i]['target'], '-', $CACHE[$i]['ttl']);
					}
					err($count,'srv');break;

				/*更多查询请自行添加*/
			
			}	
			 
			?>
			<tr style="background-color:#FFFFFF;text-align:center;padding:5px">
				<td colspan="5">
					<a href="http://hechaocheng.cn/tools/alexa.php?q=<?=$query;?>" target="_blank" style="color:green" title="查询网站<?=$query;?>的世界排名">Alexa世界排名</a> |
					<a href="http://hechaocheng.cn/tools/pr.php?q=<?=$query;?>" target="_blank" style="color:red" title="查询网站<?=$query;?>的PR值">GOOGLE PR</a> |
					<a href="http://hechaocheng.cn/tools/status.php?q=<?=$query;?>" target="_blank" style="color:blue" title="查询网站<?=$query;?>的HTTP状态">HTTP状态查询</a> |
					<a href="http://hechaocheng.cn/tools/meta.php?q=<?=$query;?>" target="_blank" style="color:green" title="查询网站<?=$query;?>的META信息">网页META信息</a> |
					<a href="http://hechaocheng.cn/tools/source_code.php?q=<?=$query;?>" target="_blank" style="color:blue" title="查询网站<?=$query;?>的网页源代码">查看网页源代码</a> |
					<a href="http://hechaocheng.cn/tools/whois.php?q=<?=$query;?>" target="_blank" style="color:red" title="查询网站<?=$query;?>的Whois信息">Whois信息</a> |
					<a href="http://hechaocheng.cn/tools/ip.php?q=<?=$query;?>" target="_blank" style="color:green" title="查询网站<?=$query;?>的服务器IP">服务器IP查询</a>
				</td>
			</tr>
			<tr style="background-color:#FFFFFF;text-align:center;padding:5px">
				<td colspan="5">
				<?php if(!file_exists('./ads.js')){?>
				<script type="text/javascript">
				<!--
				google_ad_client = "ca-pub-0445875970223431";
				google_ad_slot = "3209110809";
				google_ad_width = 728;
				google_ad_height = 90;
				//-->
				</script>
				<script src="http://pagead2.googlesyndication.com/pagead/show_ads.js" type="text/javascript"></script><?php }else{?>
				<script type="text/javascript" src="./ads.js"></script>
				<?php }?>
				</td>
			</tr>
			</table>
			<?php }?>
			</div></center>
			<?php }else{ echo "<p style=\"padding:50px 0px;color:red;font-weight:bold;\">本程序完全依赖dns_get_record, 不能运行在Windows平台, 请使用Linux/UNIX!</p>";}?>
			</td>
		</tr>
		<tr><td style="text-align:left;padding:2px 5px 5px 5px;background:#eff8fc" title="工具描述">
		1.名称服务器[NS]记录
			<div class="HCC_Info">描述:</div>
		2.主机[A]记录
			<div class="HCC_Info">描述: 主机地址记录。在 DNS 域名与 IP 地址之间建立映射关系。</div>
		3.别名[CNAME]
			<div class="HCC_Info">描述: 用来表示用在该区域中的其它资源记录类型中已指定名称的替补或别名 DNS 域名。</div>
		4.邮件交换[MX]记录
			<div class="HCC_Info">描述: 用来向特定邮件交换器提供消息路由，该主机作为指定 DNS 域名的邮件交换器。MX 记录需要一个16-位整数来表示消息路由中的主机优先级，多个邮件交换在消息一中被指定。对于这个记录类型中的每个邮件交换主机，需要一个相应的主机地址类型记录。</div>
		3.主机信息[HINFO]
			<div class="HCC_Info">描述: 用来说明映射到特定 DNS 主机名的 CPU 类型和操作系统类型的 RFC-1700 保留字符串类型，这个信息可以被应用程序通信协议使用。	</div>
		5.邮箱或通信信息 MINFO
			<div class="HCC_Info">描述: 用来指定负责维护该记录中特定通信名单或邮箱的联系域邮箱名称。同时，还被用来指定接收与该记录中特定通信名单或邮箱有关的错误信息的邮箱。</div>
		8.服务记录 [SRV]
			<div class="HCC_Info">描述: SRV 资源记录允许管理员使用单一 DNS 域的多个服务器，容易的用管理功能将 TCP/IP 服务从一个主机移到另一个主机，并且将服务提供的程序主机分派为服务的主服务器，将其它的分派为辅助的</div>
		</td></tr>
		<tr><td style="text-align:center;background-color:#eff8fc;padding:3px;color:#999999"><font color="#000">Copyright <a href="http://<?=$_SERVER['SERVER_NAME'];?>/" style="color:#000"><?=$_SERVER['SERVER_NAME'];?></a> All Rights Reserved</font><br>Powered by <!-- 亲,你真的忍心删了我吗? --><a href="http://hechaocheng.cn/" target="_blank" style="color:#999999">Hechaocheng</a><br /><a href="http://shang.qq.com/wpa/qunwpa?idkey=cffe2a2cd11a784ad710b879ae697e68dc6a5d599ba21baaa5dbc76779965d23" target="_blank"><img width="90" height="22" border="0" src="http://pub.idqqimg.com/wpa/images/group.png" /></a></td></tr>
		</table>
		</td>
	</tr>
</table>
</div>
</div>

</body>
<script type="text/javascript">
var inputTips = {
	"x":-1,	/*负数向左移动*/
	"y":-2,	/*负数向上移动*/
	"w":"",	/*宽度*/
	"c":"#FFF",	/*颜色*/
	"t":"#FFF",	/*文字颜色*/
	"m":"14px",	/*文字大小*/
	"f":"Microsoft Yahei",	/*文字样式*/
	"b":"",	/*边框颜色*/
	"h":"",	/*过文件颜色*/
	"s":true	/*自动提交(true和false)*/
}
function submint(str){return true;}
b.q("q",inputTips,submint);
/*$='true|false'*/
</script>
</html>