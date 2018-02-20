<?php
/**
 * wechat php test
 */
include 'api.php';
//include 'php/db.php';

// 定义TOKEN
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
$wechatObj->responseMsg();



class wechatCallbackapiTest
{
	  
	private $strAbout = "零度开发团队，创建于2015年3月，致力于服务性的校园资讯平台开发，努力打造一个集多功能于一身的校园资讯平台，提供众多服务如校园咨询、兼职招聘、周边服务、宿舍超市等功能。";
	private $strMenu = "回复数字 选择服务\n1.查看菜单\n2.查看心屋官网\n\n<a href='http://114.215.171.206/se/u13551101047/hearthouse/phone/gdei/'>点击查看广二师校园资讯</a>\n\n<a href='http://114.215.171.206/se/u13551101047/hearthouse/phone/weixin_help.html'>点击查看更多查询功能</a>"; 
	
	
	public function dealCmd($postObj, $cmd, $content)
	{
		switch ($cmd)
		{
			case 'cjh':
				$this->responseText($postObj, '陈建杭');
				break;
			case ".":
				$this->responseText($postObj, test());
				break;
			case "db":
				$this->responseText($postObj, dbTest());
				break;
		
			case "0":
				$this->responseText($postObj, test());
				break;
				
			case "1":
				$this->responseText($postObj, $this->strMenu);//str_replace("VERSION", uniqid(), )
				break;
				
			case "2":
				$contentStr = "点击进入<a href='http://114.215.171.206/se/u13551101047/hearthouse/phone/index.html'>心屋官网手机版</a>\n点击进入<a href='http://114.215.171.206/se/u13551101047/hearthouse/index.html'>心屋官网电脑版</a>";
				$this->responseText($postObj, $contentStr);
				break;
				
			case "3":
				$this->responseText($postObj, $this->strAbout);
				break;
				//$url = "http://file.bmob.cn/M00/63/00/oYYBAFU5EsyAZxT3AASDsm86lRQ597.png";
				//$url = "logo.png";
				//echo $this->responseImage($postObj, $url);
	
			case "5":
				$contentStr = "回复：笑话（或xh）可以查看笑话，每次都会回复不同的笑话";
				$this->responseText($postObj, $contentStr);
				//$contentStr = "https://www.baidu.com/img/bdlogo.png";
				//echo $this->resNews($postObj, $contentStr);
				//$this->responseNews($postObj);
				break;
	
			case "7":
				$contentStr = "回复：人品+空格+姓名（或者rp+空格+姓名），就可以查看人品啦，不管你信不信，反正我是信了。";
				$this->responseText($postObj, $contentStr);
				break;
		
			case "9":
				$contentStr = "回复：缘分（或yf）+男方姓名+加号(+)+女方姓名，如：缘分 罗密欧+朱丽叶";
				$this->responseText($postObj, $contentStr);
				break;
				
			case "？":
			case "帮助":
				$this->responseText($postObj, $this->strMenu);
				break;
			case "?":
				$contentStr = "<a href='http://114.215.171.206/se/u13551101047/hearthouse/phone/weixin_help.html'>点击查看帮助</a>";
				$this->responseText($postObj, $contentStr);
				break;
			case "phone":
				$this->responseText($postObj, $content);
				break;
				
			case "英":
				$this->responseText($postObj, cToE($content));
				break;
				
			case "中":
				$this->responseText($postObj, eToC($content));
				break;
				
			case "日":
				$this->responseText($postObj, cToJ($content));
				break;
			
			case "fk":	
			case "反馈":
				if ($content == "")
				{
					$this->responseText($postObj, "反馈内容不能为空。");
				}
				else
				{
					$this->responseText($postObj, "反馈成功，感谢您的参与，我们将做得更好。");
				}
				
				break;
				
			case "gdei":	
			case "广二师":
				if ($content == "")
				{
					$url = '<a href="http://114.215.171.206/se/u13551101047/hearthouse/phone/gdei/guide.html">点击查看</a>';			
					$this->responseText($postObj, $url);
				}
				else
				{
					switch ($content)
					{
						case "新生":
							$url = '<a href="http://114.215.171.206/se/u13551101047/hearthouse/phone/gdei/guide.html">点击查看</a>';			
							$this->responseText($postObj, $url);
							break;
						default:
							$url = '<a href="http://114.215.171.206/se/u13551101047/hearthouse/phone/gdei/guide.html">点击查看</a>';			
							$this->responseText($postObj, $url);
							break;
					}
				}
				
				
				break;
				
			case "jk":	
			case "健康":
				$this->responseText($postObj, bmi($content));
				break;
				
			case "kd":	
			case "快递":
				$contentStr = "<a href='http://apix.sinaapp.com/express2/index.php'>点击快递查询</a>";
				$this->responseText($postObj, $contentStr);
				break;
				
			case "nl":	
			case "农历":
				$this->responseText($postObj, lunar($content));
				break;
				
			case "rp":	
			case "人品":
				$this->responseText($postObj, rp($content));
				break;
					
			case "sj":	
			case "手机":
				$this->responseText($postObj, phone($content));
				break;
				
			case "slj":	
			case "四六级":
				$contentStr = "<a href='http://www.chsi.com.cn/cet/'>点击进入四六级查询</a>";
				$this->responseText($postObj, $contentStr);
				break;
				
			case "tq":	
			case "天气":
				if ($content == "")
				{
					$this->responseText($postObj, weather("广州"));
				}
				else
				{
					$this->responseText($postObj, weather($content));
				}
				
				break;
				
			case "xh":	
			case "笑话":
				$this->responseText($postObj, joke());
				break;
				
		
			case "tp":
			case "投票":	
				if ($content == "")
				{
					$this->responseText($postObj, "输入格式不正确");
				}
				else
				{
					switch ($content)
					{
						case "1":
							$url = '<a href="http://mp.weixin.qq.com/s?__biz=MzA4Nzc3Njg3MA==&mid=205435757&idx=1&sn=b6e930ff7c3942d748db268a894d0413#rd">点击投票</a>';			
							$this->responseText($postObj, $url);
							break;
						default:
							$url = '输入格式不正确';			
							$this->responseText($postObj, $url);
							break;
					}
				}
				break;
			
			default:  
				$url = '输入格式不正确';			
				$this->responseText($postObj, $url);
				break;
		}
	}
	
	
	// 验证签名，用于申请 成为开发者 时向微信发送验证信息
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

	// 响应消息，处理并回复用户发送过来的消息，也是用的最多的一个函数，几乎所有的功能都在这里实现。
    public function responseMsg()
    {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];	//获取POST数据

		if (!empty($postStr))	// 如果接收到了数据
		{
			/* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
			   the best way is to check the validity of xml by yourself */
			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);	// 用SimpleXML解析POST过来的XML数据
			
			$msgType = trim($postObj->MsgType);
			
			//---------- 返 回 数 据 ---------- //
			switch ($msgType)
			{
				case "text":	// 文本消息
					$this->handleText($postObj);	/* $this->不能省 */
					break;
					
				case "event":	// 事件推送
					$this->handleEvent($postObj);
					break;
					
				case "image":	// 图片消息
					break;
					
				case "location":	// 地理位置消息
					$this->handleLocation($postObj);
					break;
					 
				default:
					$this->handleText($postObj);
					break;
					
			}
			
        }
		else 
		{
        	echo "出错啦（没有收到数据）！";
        	exit;
        }
    }


	/* 相应文本消息 */
	public function handleText($postObj)
	{
		$fromUsername = $postObj->FromUserName;	// 获取发送方帐号（OpenID）
		$toUsername = $postObj->ToUserName;		// 获取接收方账号
		$keyword = trim($postObj->Content);		// 获取消息内容
		$time = time();							// 获取当前时间戳
		
		
		if (!empty( $keyword ))
		{
			if (preg_match("/^([^\s]+?)\s+?([\d\D]+?)$/", $keyword, $matches))
			{
				$this->dealCmd($postObj, $matches[1], $matches[2]);
			}
			else
			{
				$this->dealCmd($postObj, $keyword, "");
			}
		}
		else
		{
			echo "请输入内容！";
		}
	}
	
	
	/* 相应事件推送 */
	public function handleEvent($object)
	{
		$contentStr = "";
		switch ($object->Event)		// 事件类型又分为三种
		{
			case "subscribe":	// 订阅
				$contentStr = "感谢您关注【心屋】\n"
				."微信号：hearthouse01\n"
				."我们将努力打造最好的校园资讯平台。\n"
				."目前平台功能如下：\n"
				.str_replace("VERSION", uniqid(), $this->strMenu);
				break;
				
			default:
				$contentStr = "Unknow Event: ".$object->Event;
				break;
		}
		
		$resultStr = $this->responseText($object, $contentStr);
		return $resultStr;

	}
	
	
	/* 地理位置消息 */
	public function handleLocation($object)
	{
		$fromUsername = $object->FromUserName;	// 获取发送方帐号（OpenID）
		$toUsername = $object->ToUserName;		// 获取接收方账号
		$keyword = trim($object->Content);		// 获取消息内容
		$time = time();							// 获取当前时间戳
		
		$locationX = $object->Location_X;
		$locationY = $object->Location_Y;
		
		$resultStr = $this->responseText($object, "你的坐标是（".$locationX."，".$locationY."）\n"
			);
		return $resultStr;
	}
	
	
	/* 回复文本消息 */
	public function responseText($object, $content, $flag=0)
	{
		// 返回消息模板
		$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
		// 格式化消息模板		
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
		
        echo $resultStr;
	}
	
	
	/*
	 *图片消息回复
	 *@param array object
	 *@param string 通过上传多媒体文件，得到的id。
	 *@return string
	 */
	public function responseImage($object, $media_id)
	{
		$xmlImage = "<xml>";
		$xmlImage .= "<ToUserName><![CDATA[%s]]></ToUserName>";
		$xmlImage .= "<FromUserName><![CDATA[%s]]></FromUserName>";
		$xmlImage .= "<CreateTime>%s</CreateTime>";
		$xmlImage .= "<MsgType><![CDATA[image]]></MsgType>";
		$xmlImage .= "<Image><MediaId><![CDATA[%s]]></MediaId></Image>";
		$xmlImage .= "</xml>";
		
		$resultStr = sprintf($xmlImage, $object->FromUserName, $object->ToUserName, time(), $media_id);
		
		return $resultStr;
	}
	
	
	
	/* 图片链接，支持JPG、PNG格式，较好的效果为大图640*320，小图80*80。 */
	/* 回复图文消息 */
	public function responseNews($object)
	{
		$xml = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>2</ArticleCount>
				<Articles>
					<item>
						<Title><![CDATA['title1']]></Title> 
						<Description><![CDATA['description1']]></Description>
						<PicUrl><![CDATA['http://file.bmob.cn/M00/63/00/oYYBAFU5EsyAZxT3AASDsm86lRQ597.png']]></PicUrl>
						<Url><![CDATA['http://file.bmob.cn/M00/63/00/oYYBAFU5EsyAZxT3AASDsm86lRQ597.png']]></Url>
					</item>
					<item>
						<Title><![CDATA['title2']]></Title>
						<Description><![CDATA['description2']]></Description>
						<PicUrl><![CDATA['http://file.bmob.cn/M00/63/00/oYYBAFU5EsyAZxT3AASDsm86lRQ597.png']]></PicUrl>
						<Url><![CDATA['http://file.bmob.cn/M00/63/00/oYYBAFU5EsyAZxT3AASDsm86lRQ597.png']]></Url>
					</item>
				</Articles>
				</xml>";
		
		$resultStr = sprintf($xml, $object->FromUserName, $object->ToUserName, time());
		
		echo $resultStr;
		//微信公众平台图文回复的消息一次最多10条
	}
	
	
	/*
	 *图文消息回复
	 *@param array object
	 *@param array newsData 二维数组 必须包含[Title][Description][PicUrl][Url]字段
	 *@return string
	 */
	public function resNews($object,$newsData=array())
	{
		$CreateTime=time();
		$FuncFlag=0;
		$newTplHeader="<xml>
		<ToUserName><![CDATA[{$object->FromUserName}]]></ToUserName>
		<FromUserName><![CDATA[{$object->ToUserName}]]></FromUserName>
		<CreateTime>{$CreateTime}</CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<Content><![CDATA[%s]]></Content>
		<ArticleCount>%s</ArticleCount><Articles>";
		$newTplItem="<item>
		<Title><![CDATA[%s]]></Title>
		<Description><![CDATA[%s]]></Description>
		<PicUrl><![CDATA[%s]]></PicUrl>
		<Url><![CDATA[%s]]></Url>
		</item>";
		$newTplFoot="</Articles>
		<FuncFlag>%s</FuncFlag>
		</xml>";
		$Content='';
		$itemsCount=count($newsData);
		$itemsCount=$itemsCount<10?$itemsCount:10;//微信公众平台图文回复的消息一次最多10条
		if($itemsCount){
		foreach($newsData as $key=>$item){
		if($key<=9){
		$Content.=sprintf($newTplItem,$item['Title'],$item['Description'],$item['PicUrl'],$item['Url']);
		}
		}
		}
		$header=sprintf($newTplHeader,0,$itemsCount);
		$footer=sprintf($newTplFoot,$FuncFlag);
		echo $header.$Content.$footer;exit();
	}
 
	// 封装的验证
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>