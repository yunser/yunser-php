<?php

/**
 * 上传图片到微博图床
 * @author mengkun  http://mkblog.cn
 * @param $file 图片文件/图片url
 * @param $multipart 是否采用multipart方式上传
 * @return 返回的json数据
 */
function upload($file, $multipart = true) {
    $cookie = 'SINAGLOBAL=6290877024365.984.1548079798666; _s_tentry=fashion.ifeng.com; Apache=286685051883.51514.1552056147408; ULV=1552056147607:4:1:1:286685051883.51514.1552056147408:1550896057143; YF-V5-G0=e6f12d86f222067e0079d729f0a701bc; login_sid_t=3575a5aba5183c64801ac837f8b95b6f; cross_origin_proto=SSL; Ugrow-G0=169004153682ef91866609488943c77f; wb_view_log=1680*10502; WBStorage=201903142035|undefined; UOR=,,www.baidu.com; SUBP=0033WrSXqPxfM725Ws9jqgMF55529P9D9WWU9akNRRvl7.4GOdIVi-T95JpX5K2hUgL.Foec1hqc1Kq4e0M2dJLoIE-LxK-LB.zLB.2LxK-LBonL1hq_i--Ri-isiKLhi--RiKnRi-8W; ALF=1584103506; SSOLoginState=1552567507; SCF=Av_n9d12MQrel7w2mZvx8K0YtXpcgQs8fwe_0xdHyFJx53b9-j9-Lt6ZQpsrES3WNrkM8y11VB-dkSCE45swqEg.; SUB=_2A25xjjyDDeRhGeVI41QX-SjFyDuIHXVS-ilLrDV8PUNbmtBeLU3kkW9NTyniySX7sLRC2ZuT3ckHfwS8EQM54Ux2; SUHB=010Wusxwbce5vH; un=1418503647@qq.com; wvr=6; YF-Page-G0=280e58c5ca896750f16dcc47ceb234ed; wb_view_log_3686696937=1680*10502; webim_unReadCount=%7B%22time%22%3A1552567524007%2C%22dm_pub_total%22%3A1%2C%22chat_group_pc%22%3A0%2C%22allcountNum%22%3A39%2C%22msgbox%22%3A0%7D';    // 微博cookie
    $url = 'http://picupload.service.weibo.com/interface/pic_upload.php'
    .'?mime=image%2Fjpeg&data=base64&url=0&markpos=1&logo=&nick=0&marks=1&app=miniblog';
    if($multipart) {
        $url .= '&cb=http://weibo.com/aj/static/upimgback.html?_wv=5&callback=STK_ijax_'.time();
        if (class_exists('CURLFile')) {     // php 5.5
            $post['pic1'] = new CURLFile(realpath($file));
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
    if(!isset($match[1])) return '';
    return $match[1];
}
