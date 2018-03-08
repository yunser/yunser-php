<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>  
<link rel="shortcut icon" href="/favicon.ico"/>
<link rel="bookmark" href="/favicon.ico"/> -->
</head>  
<body>  
<div id="warp">
  <div class="center">
        <?PHP  
        
        header('Content-type:application/json');
        header("Access-Control-Allow-Origin: *");
$output = "";  
if(isset($_GET['action'])&&$_GET['action'] == 'make'){  
    if(isset($_FILES['upimage']['tmp_name']) && $_FILES['upimage']['tmp_name'] && is_uploaded_file($_FILES['upimage']['tmp_name'])){  
        if($_FILES['upimage']['type']>210000){  
            echo "你上传的文件体积超过了限制 最大不能超过200K";  
            exit();  
        }
        // echo $_FILES['upimage']['type'];
        // exit();
        $fileext = array("image/pjpeg","image/gif","image/png","image/x-png");  
        if(!in_array($_FILES['upimage']['type'],$fileext)){  
            echo "你上传的文件格式不正确 仅支持 jpg，gif，png";  
            exit();  
        }  
        if($im = @imagecreatefrompng($_FILES['upimage']['tmp_name']) or $im = @imagecreatefromgif($_FILES['upimage']['tmp_name']) or $im = @imagecreatefromjpeg($_FILES['upimage']['tmp_name'])){  
            $imginfo = @getimagesize($_FILES['upimage']['tmp_name']);  
            if(!is_array($imginfo)){  
                echo "图形格式错误！";  
            }  
            switch($_POST['size']){  
                case 1;  
                    $resize_im = @imagecreatetruecolor(16,16);  
                    $size = 16;  
                    break;  
                case 2;  
                    $resize_im = @imagecreatetruecolor(32,32);  
                    $size = 32;  
                    break;  
                case 3;  
                    $resize_im = @imagecreatetruecolor(48,48);  
                    $size = 48;  
                    break;  
                default;  
                    $resize_im = @imagecreatetruecolor(32,32);  
                    $size = 32;  
                    break;  
            }  
            imagecopyresampled($resize_im,$im,0,0,0,0,$size,$size,$imginfo[0],$imginfo[1]);  
            include "phpthumb.ico.php";  
            $icon = new phpthumb_ico();  
            $gd_image_array = array($resize_im);  
            $icon_data = $icon->GD2ICOstring($gd_image_array);  
            $filename = "temp/".date("Ymdhis").rand(1,1000).".ico";  
            if(file_put_contents($filename, $icon_data)){  
                $output = "生成成功！请点右键->另存为 保存到本地<br><a href=\"".$filename."\" target=\"_blank\">点击下载</a>";  
            }  
        }else{  
            echo "生成错误请重试！";  
        }  
    }      
}  
?>  
		

		<form action="index.php?action=make" method="post" enctype='multipart/form-data'>  
		<table width="90%" align="center">  
			<tr>  
			  <td height="40"><h3>请上传你要转换成.<a href="http://ico.sevem.cn" target="_blank">ico</a>的图片</h3>
			  支持格式 png、jpg、gif在线转换成.<a href="http://ico.sevem.cn" target="_blank">ico</a>图标。如何你想制作更丰富的.<a href="http://ico.sevem.cn" target="_blank">ico</a>图标请<a href="#ico">下载ICO制作软件</a></td>  
			</tr>  
			<tr>  
			  <td height="40"><input type="file" name="upimage" size="30">目标尺寸：  
				<input type="radio" name="size" value="1" id="s1"><label for="s1">16*16</label>  
				<input type="radio" name="size" value="2" id="s2" checked><label for="s2">32*32</label>  
				<input type="radio" name="size" value="3" id="s3"><label for="s3">48*48</label>  
			  </td>  
			</tr>  
			  
			<tr>  
			  <td height="40" align="center"><input type="submit" style="width:150px; height:30px;" value="在线生成favicon.ico图标"></td>  
			</tr>  
			<?PHP  
			if($output){  
				echo "<tr><td><div style=\"border:1px solid #D8D8B2;background-color:#FFFFDD;padding:10px\">".$output."</div></td></tr>";  
			}  
			?>  
		</table>  
		<div style="">
<?php 
  $doc = new DOMDocument(); 
  $doc->load( 'http://link.qim.net.cn/xml.xml' ); 
   
  $links = $doc->getElementsByTagName( "link" ); 
  foreach( $links as $link ) 
  { 

  $publishers = $link->getElementsByTagName( "homepage" ); 
  $homepage = $publishers->item(0)->nodeValue; 
   
  $titles = $link->getElementsByTagName( "title" ); 
  $title = $titles->item(0)->nodeValue; 
   
  $contents = $link->getElementsByTagName( "content" ); 
  $content = $contents->item(0)->nodeValue; 
   
  echo "<a href='$homepage' title='$content' target='_blank' />$title</a><br>"; 
  } 
  ?> 

</div>

		</form> 
</div>
</body>  
</html>