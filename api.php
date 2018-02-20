<?php

/* 封装get请求 */
function myGet($url)
{
	$ch = curl_init();  
	$timeout = 5;  
	curl_setopt ($ch, CURLOPT_URL, $url);  
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);  
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
	$file_contents = curl_exec($ch);  
	curl_close($ch);  
	$en_contents = mb_convert_encoding($file_contents, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');  // 对页面内容进行编码 
	return $en_contents;  
}


function test()
{
	$url = "http://apix.sinaapp.com/dream/?appkey=trialuser&content=".$text;
	$ret =  myGet($url);
	//%E8%80%81%E5%B8%88
}


function weather($cityName)
{
	require_once 'weather.php';
	
	return Weather::getInfo($cityName);
}


function simsimiChat($msg)
{
	$url = "http://api.mrtimo.com/Simsimi.ashx?parm=".$msg;
	return myGet($url);
}


/* 查询今天农历 */
function lunar()
{
	require_once 'lunar.php';
    $today = date("Y-m-d");
    $lunar = new Lunar();
    //公历转农历
    $nl = date("Y-m-d",$lunar->S2L($today));
    //农历转公历
    $gl = date("Y-m-d",$lunar->L2S($nl));
	
	return "今天的农历是：$nl";    
    //echo "今天公历是:$today<br/>";
    //echo "转为农历是:$nl<br/>";
    //echo "转回公历是:$gl<br/>";
}


function nameMatch($text)
{
	require_once 'yuanfen.php';
	return myNameMatch($text);
}


/* 健康指数查询 */
function bmi($text)
{
	if (preg_match("/^身高([\d\D]+?)体重([\d\D]+?)$/", $text, $matches))
	{
		//echo "匹配结果：".$matches[1].",".$matches[2]."|";
		return bmi2(intval($matches[1]), intval($matches[2]));
	}
	else
	{
		return "输入格式有误";
	}
}


function bmi2($tall, $weight)
{
	$score = ceil($weight / ($tall * $tall / 10000)) - 1;
	$standardWeight =  sprintf("%0.1f", 21 * ($tall * $tall / 10000));
	$a = getBMIValue($score);
	return "你的理想体重应为：".$standardWeight."公斤\n你的身体健康指数：".$score
		."\n健康评价：".$a[0]."\n建议：".$a[1];
}
function getBMIValue($score)
{
	$result = array("state", "suject");
	
	if ($score <= 15)
	{
		$result[0] = "唉！前胸贴后背,你怎么像个电线杆子？";
		$result[1] = "瘦得离谱，估计你最好去医院找大夫看看！";
	}
	else if ($score >= 16 && $score <= 17)
	{
		$result[0] = "哇！你没有受到虐待吧？！三餐都不能吃饱吗？";
		$result[1] = "强化饮食营养补充，多作中强度的增肌锻炼！";
	}
	else if ($score >= 18 && $score <= 20)
	{
		$result[0] = "夷！你有一点偏瘦哦，你应该多吃点东西啊！";
		$result[1] = "增加饮食摄入量，适当补充高蛋白，适当锻炼！";
	}
	else if ($score == 21)
	{
		$result[0] = "呀！羡慕啊,你这可是魔鬼身材啊(~_~)！";
		$result[1] = "注意均衡饮食，保持良好的作息，适当锻炼！";
	}
	else if ($score >= 22 && $score <= 26)
	{
		$result[0] = "嗯，保持得还可以，属于比较正常的身材！";
		$result[1] = "注意均衡饮食，保持良好的作息，适当锻炼！";
	}
	else if ($score >= 27 && $score <= 30)
	{
		$result[0] = "哎！你已经有一些偏胖，要开始留意你的体重了！";
		$result[1] = "减少零食，注意均衡饮食，适当锻炼！";
	}
	else
	{
		$result[0] = "哇！你好胖啊！你得开始减肥了，是真的！";
		$result[1] = "杜绝零食，少吃肉食，以素食为主，增强锻炼！";
	}

	return $result;
}


function rp($name)
{
	require_once 'rp.php';
	return getRP($name);
}
//http://apix.sinaapp.com/dream/?appkey=trialuser&content=%E8%80%81%E5%B8%88

/* 中译日 */
function cToJ($text)
{
	$url = "http://brisk.eu.org/api/translate.php?from=zh-CN&to=ja&text=".$text;
	$ret =  myGet($url);
	$obj = json_decode($ret);
	return $obj->{'res'};
}

/* 英译中 */
function eToC($text)
{
	$url = "http://brisk.eu.org/api/translate.php?from=en&to=zh-CN&text=".$text;
	$ret =  myGet($url);
	$obj = json_decode($ret);
	return $obj->{'res'};
}


/* 中译英 */
function cToE($text)
{
	$url = "http://brisk.eu.org/api/translate.php?text=".$text;
	$ret =  myGet($url);
	$obj = json_decode($ret);
	return $obj->{'res'};
}


/*
 * 手机归属地查询api 
 * $ phone 手机号码
 * 返回查询结果
 */
function phone($phone)
{
	if (empty($phone))
	{
		return "手机号不能为空";
	}
	else
	{
		if (!preg_match("/^1[34578]\d{9}$/", $phone))
		{
			return "手机号不正确";
		}
		else
		{
			/*return "手机号正确";*/
			$url = "http://www.096.me/api.php?mode=txt&phone=".$phone;
			$response =  myGet($url);
			$a = explode('||', $response); 
			$result = "手机号：".$a[0]."\n信息：".$a[1]."\n吉凶测算：".$a[2];
			return $result;
		}
		
	}	
}


/* 笑话 */
function joke()
{
	require_once 'joke.php';
	
	return Joke::getJoke();
}

?>
