function handleFiles(e){
        var id = $(e).attr("id");
        console.log(id);
        url = getObjectURL(e.files[0]);
        qrcode.decode(url);
        qrcode.callback = function(imgMsg){
            $('#'+id).val(imgMsg);
        }
}
function getObjectURL(file){
    let url = null ; 
    if (window.createObjectURL!=undefined) { // basic
        url = window.createObjectURL(file) ;
    } else if (window.URL!=undefined) { // mozilla(firefox)
        url = window.URL.createObjectURL(file) ;
    } else if (window.webkitURL!=undefined) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file) ;
    }
    return url ;
}
$(document).ready(function() {
    $("input").change(function($this) {
        handleFiles(this);
    });
    $('#shorten').click(function(){
        $('#qrcode').show();
        var alipay = urlEncode($('#alipay_url').val());
        var wechat = urlEncode($('#wechat_url').val());
        var qq = urlEncode($('#qq_url').val());
        drawAndShareImage('http://qrpay.uomg.com/payqr.html?ali='+alipay+'&vx='+wechat+'&qq='+qq);
    });
});

function urlEncode(String) {
    return encodeURIComponent(String).replace(/'/g,"%27").replace(/"/g,"%22");  
}
function drawAndShareImage(url){
    var canvas = document.createElement("canvas");
    canvas.width = 800;
    canvas.height = 1200;
    var context = canvas.getContext("2d");
    context.rect(0,0,canvas.width,canvas.height);
    context.fillStyle = "#fff";
    context.fill();

    var myImage = new Image();
    myImage.src = "code.png";    //背景图片  你自己本地的图片或者在线图片
    myImage.crossOrigin = 'Anonymous';

    myImage.onload = function(){
        context.drawImage(myImage ,0,0);

        var myImage2 = new Image();
        myImage2.src = "https://api.yum6.cn/qrcode.php?url="+urlEncode(url);   //你自己本地的图片或者在线图片
        myImage2.crossOrigin = 'Anonymous';
        
        myImage2.onload = function(){
            context.drawImage(myImage2 , 120 , 120 , 560 , 560);
            var base64 = canvas.toDataURL("image/png");  //"image/png" 这里注意一下
            $('#qrcode').attr('src' , base64);
        }
    }
}