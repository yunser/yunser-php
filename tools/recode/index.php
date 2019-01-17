<?php
/*
Title:三合一收款码制作
Subtitle:Recode
Plugin Name:recode
Description:QQ,支付宝,微信收款码三合一制作
Author:Youngxj
Author Email:blog@youngxj.cn
Author URL:https://www.youngxj.cn/
Version:1.1
*/
$CONF = require('../../function.config.php');
$self = $_SERVER['PHP_SELF'];
preg_match_all('/'.$CONF['config']['TOOLS_T'].'\/(.*)\//', $self, $name);
$id = $name[1][0];
include '../../header.php';
?>
<div class="container clearfix">
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo $title;?><small class="text-capitalize">-<?php echo $subtitle;?></small></div>
		<div class="panel-body">
			<div id="content" class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
				<form class="am-form" id="shorturl">
					<div class="am-form-group am-g-collapse">
						<div class="am-u-sm-8">
							<input type="text" name="alipay_url" id="alipay_url" placeholder="支付宝收款链接">
						</div>
						<div class="am-form-group am-form-file">
							<button type="button" class="am-btn am-btn-danger am-btn-sm"><i class="am-icon-cloud-upload"></i> 选择支付宝收款码</button>
							<input id="alipay_url" type="file" accept="image/*">
						</div>
					</div>
					<div class="am-form-group am-g-collapse">
						<div class="am-u-sm-8">
							<input type="text" name="wechat_url" id="wechat_url" value="" placeholder="微信收款链接">
						</div>
						<div class="am-form-group am-form-file">
							<button type="button" class="am-btn am-btn-danger am-btn-sm"><i class="am-icon-cloud-upload"></i> 选择 微 信 收款码</button> <input id="wechat_url" type="file" accept="image/*">
						</div>
					</div>
					<div class="am-form-group am-g-collapse">
						<div class="am-u-sm-8">
							<input type="text" name="qq_url" id="qq_url" value="" placeholder="手机QQ收款链接">
						</div>
						<div class="am-form-group am-form-file">
							<button type="button" class="am-btn am-btn-danger am-btn-sm"><i class="am-icon-cloud-upload"></i> 选择 Ｑ Ｑ 收款码</button> <input id="qq_url" type="file" accept="image/*">
						</div>
					</div>           
					<br>
					<div class="am-cf">
						<input type="button" id="shorten" value="立即生成" class="am-btn am-btn-primary am-btn-sm am-fl">
					</div>
				</form>
				<img id="qrcode" class="am-center am-img-thumbnail am-img-responsive" style="width: 206px;display: none;">
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">工具简介</div>
		<div class="panel-body">
			<p>在线生成支持QQ、支付宝、微信三合一收款码的小工具</p>
			<p>保存三个平台的收款码即可，然后上传图片自动识别收款码地址</p>
			<p>点击生成之后就可以得到一张能识别三个平台收款码的图片</p>
			<p>最后保存图片即可使用</p>
		</div>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="css/app.css">
<link rel="stylesheet" type="text/css" href="https://cdn.bootcss.com/amazeui/2.5.2/css/amazeui.min.css">
<script type="text/javascript" src="js/amazeui.min.js"></script>
<script type="text/javascript" src="js/validator.min.js"></script>
<script type="text/javascript" src="js/jquery.qrcode.min.js"></script>
<script type="text/javascript" src="js/llqrcode.js"></script>
<script type="text/javascript" src="js/index.js"></script>
<script type="text/javascript" src="js/external.js"></script>


<?php include '../../footer.php';?>