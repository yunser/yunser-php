<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<?php

/ 本地仓库路径
$local = '/var/www/html/awaimai';

// 安全验证字符串，为空则不验证
$token = '123456';


// 如果启用验证，并且验证失败，返回错误
$httpToken = isset($_SERVER['HTTP_X_GITLAB_TOKEN']) ? $_SERVER['HTTP_X_GITLAB_TOKEN'] : '';
if ($token && $httpToken != $token) {
    header('HTTP/1.1 403 Permission Denied');
    die('Permission denied.');
}

// 如果仓库目录不存在，返回错误
if (!is_dir($local)) {
    header('HTTP/1.1 500 Internal Server Error');
    die('Local directory is missing');
}

//如果请求体内容为空，返回错误
$payload = file_get_contents('php://input');
if (!$payload) {
    header('HTTP/1.1 400 Bad Request');
    die('HTTP HEADER or POST is missing.');
}

/*
 * 这里有几点需要注意：
 *
 * 1.确保PHP正常执行系统命令。写一个PHP文件，内容：
 * `<?php shell_exec('ls -la')`
 * 在通过浏览器访问这个文件，能够输出目录结构说明PHP可以运行系统命令。
 *
 * 2、PHP一般使用www-data或者nginx用户运行，PHP通过脚本执行系统命令也是用这个用户，
 * 所以必须确保在该用户家目录（一般是/home/www-data或/home/nginx）下有.ssh目录和
 * 一些授权文件，以及git配置文件，如下：
 * ```
 * + .ssh
 *   - authorized_keys
 *   - config
 *   - id_rsa
 *   - id_rsa.pub
 *   - known_hosts
 * - .gitconfig
 * ```
 *
 * 3.在执行的命令后面加上2>&1可以输出详细信息，确定错误位置
 *
 * 4.git目录权限问题。比如：
 * `fatal: Unable to create '/data/www/html/awaimai/.git/index.lock': Permission denied`
 * 那就是PHP用户没有写权限，需要给目录授予权限:
 * ``
 * sudo chown -R :www-data /data/www/html/awaimai`
 * sudo chmod -R g+w /data/www/html/awaimai
 * ```
 *
 * 5.SSH认证问题。如果是通过SSH认证，有可能提示错误：
 * `Could not create directory '/.ssh'.`
 * 或者
 * `Host key verification failed.`
 *
 */
echo shell_exec("cd /root/deploy && ./clipboard-front-deploy.sh");
die("done " . date('Y-m-d H:i:s', time()));

</body>
</html>