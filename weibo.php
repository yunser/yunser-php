<?php
/**
 * 上传图片到微博图床
 * @author Youngxj & mengkun & 阿珏
 * @param $file 图片文件/图片url
 * @param $multipart 是否采用multipart方式上传
 * @return 返回的json数据
 * @code  200:正常;201:错误;203:cookie获取失败;404:请勿直接访问
 * @ps    图片尺寸可供选择:square、thumb150、orj360、orj480、mw690、mw1024、mw2048、small、bmiddle、large 默认为:thumb150,请自行替换
 */
header("Access-Control-Allow-Origin:*");
header('Content-type: application/json');
error_reporting(0);
if (!is_file('sina_config.php')) {
  CookieSet('SUB;','0');
}
include 'sina_config.php';
//账号
$sinauser = '1418503647@qq.com';
//密码
$sinapwd = 'cjhbw8273047';

if (time() - $config['time'] >20*3600||$config['cookie']=='SUB;') {
  $cookie = login($sinauser,$sinapwd);
  if($cookie&&$cookie!='SUB;'){
    CookieSet($cookie,$time = time());
  }else{
    return error('203','获取cookie出现错误，请检查账号状态或者重新获取cookie');
  }
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {exit;}
$type=$_GET['type'];
if($type=='multipart'){
  $multipart = true;
  $file = $_FILES["file"]["tmp_name"];
}elseif(isset($_GET['img'])){
  $multipart = false;
  $file = $_GET['img'];
}else{
  return error('404','请勿直接访问');
}
if (isset($file) && $file != "") {
  include 'sina_config.php';
  $cookie = $config['cookie'];
  echo upload($file, $multipart,$cookie);
}else{
  return error('201','上传错误');
}

function CookieSet($cookie,$time){
  $newConfig = '<?php 
  $config = array(
    "cookie" => "'.$cookie.'",
    "time" => "'.$time.'",
  );';
  @file_put_contents('sina_config.php', $newConfig);
}

function error($code,$msg){
  $arr = array('code'=>$code,'msg'=>$msg);
  echo json_encode($arr);
}
/**
     * 新浪微博登录(无加密接口版本)
     * @param  string $u 用户名
     * @param  string $p 密码
     * @return string    返回最有用最精简的cookie
     */
function login($u,$p){
  $loginUrl = 'https://login.sina.com.cn/sso/login.php?client=ssologin.js(v1.4.15)&_=1403138799543';
  $loginData['entry'] = 'sso';
  $loginData['gateway'] = '1';
  $loginData['from'] = 'null';
  $loginData['savestate'] = '30';
  $loginData['useticket'] = '0';
  $loginData['pagerefer'] = '';
  $loginData['vsnf'] = '1';
  $loginData['su'] = base64_encode($u);
  $loginData['service'] = 'sso';
  $loginData['sp'] = $p;
  $loginData['sr'] = '1920*1080';
  $loginData['encoding'] = 'UTF-8';
  $loginData['cdult'] = '3';
  $loginData['domain'] = 'sina.com.cn';
  $loginData['prelt'] = '0';
  $loginData['returntype'] = 'TEXT';
  return loginPost($loginUrl,$loginData); 
}

/**
     * 发送微博登录请求
     * @param  string $url  接口地址
     * @param  array  $data 数据
     * @return json         算了，还是返回cookie吧//返回登录成功后的用户信息json
     */
function loginPost($url,$data){
  $tmp = '';
  if(is_array($data)){
    foreach($data as $key =>$value){
      $tmp .= $key."=".$value."&";
    }
    $post = trim($tmp,"&");
  }else{
    $post = $data;
  }
  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL,$url); 
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
  curl_setopt($ch,CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch,CURLOPT_POST,1);
  curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
  $return = curl_exec($ch);
  curl_close($ch);
  return 'SUB' . getSubstr($return,"Set-Cookie: SUB",'; ') . ';';
}

/**
 * 取本文中间
 */
function getSubstr($str,$leftStr,$rightStr){
  $left = strpos($str, $leftStr);
  //echo '左边:'.$left;
  $right = strpos($str, $rightStr,$left);
  //echo '<br>右边:'.$right;
  if($left <= 0 or $right < $left) return '';
  return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}


function upload($file, $multipart = true,$cookie) {
  $url = 'http://picupload.service.weibo.com/interface/pic_upload.php'.'?mime=image%2Fjpeg&data=base64&url=0&markpos=1&logo=&nick=0&marks=1&app=miniblog';
  if($multipart) {
    $url .= '&cb=http://weibo.com/aj/static/upimgback.html?_wv=5&callback=STK_ijax_'.time();
    if (class_exists('CURLFile')) {     // php 5.5
      $post['pic1'] = new \CURLFile(realpath($file));
    } else {
      $post['pic1'] = '@'.realpath($file);
    }
  } else {
    $post['b64_data'] = base64_encode(file_get_contents($file));
  }
  // Curl提交
  $ch = curl_init($url);
  curl_setopt_array($ch, array(
    CURLOPT_POST => true,
    CURLOPT_VERBOSE => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array("Cookie: $cookie"),
    CURLOPT_POSTFIELDS => $post,
  ));
  $output = curl_exec($ch);
  curl_close($ch);
  // 正则表达式提取返回结果中的json数据
  preg_match('/({.*)/i', $output, $match);
  if(!isset($match[1])) return error('201','上传错误');
  $a=json_decode($match[1],true);
  $width = $a['data']['pics']['pic_1']['width'];
  $size = $a['data']['pics']['pic_1']['size'];
  $height = $a['data']['pics']['pic_1']['height'];
  $pid = $a['data']['pics']['pic_1']['pid'];
  if(!$pid){return error('201','上传错误');}
  $arr = array('code'=>'200','width'=>$width,"height"=>$height,"size"=>$size,"pid"=>$pid,"url"=>"http://ws3.sinaimg.cn/thumb150/".$pid.".jpg");
  return json_encode($arr);
}
