
<?php 

$api = 'https://graph.qq.com/oauth2.0/token';
$code = $_GET['code'];
$data = [
	'client_id' => '101327787',
	'client_secret' => '39086995fdcdba0d1f8f4b00e79a8295',
	'grant_type' => 'authorization_code',
	'code' => $code,
	'redirect_uri' => 'http://1576a324.ittun.com/qqauth.php'
];

// 创建一个新cURL资源
$ch = curl_init($api);

//启用时会发送一个常规的POST请求,就像表单提交的一样。 
curl_setopt($ch, CURLOPT_POST, 1);

//全部数据使用HTTP协议中的"POST"操作来发送。//http_build_query::生成 URL-encode 之后的请求字符串
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// 抓取URL并把它传递给浏览器
$rs = curl_exec($ch);
// 关闭cURL资源，并且释放系统资源
curl_close($ch);
//$rs = json_decode($rs,true);
$rs = explode("&", $rs);
$at = substr($rs[0], 13);
$api2 = "https://graph.qq.com/oauth2.0/me?access_token=$at";
$username=file_get_contents($api2);
$user_id = substr($username, 45,32);

$api3 = "https://graph.qq.com/user/get_user_info?access_token=$at&oauth_consumer_key=101327787&openid=$user_id ";

$mes = file_get_contents($api3);

print_r($mes);
exit();







