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
                $output = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $filename;
            }  
        }else{  
            echo "生成错误请重试！";  
        }  
    }      
}  

echo $output;
