<?php 


/*
https://api.weibo.com/oauth2/access_token
HTTP请求方式

POST
请求参数
  	必选 	类型及范围 	说明
client_id 	true 	string 	申请应用时分配的AppKey。
client_secret 	true 	string 	申请应用时分配的AppSecret。
grant_type 	true 	string 	请求的类型，填写authorization_code


grant_type为authorization_code时

  	必选 	类型及范围 	说明
code 	true 	string 	调用authorize获得的code值。
redirect_uri 	true 	string 	回调地址，需需与注册应用里的回调地址一致。
*/
$code = $_GET['code'];

$api = 'https://api.weibo.com/oauth2/access_token';

$data = [
	'client_id' => '3121123350',
	'client_secret' => '7e50a4fe0f408b229be38118a9391af3',
	'grant_type' => 'authorization_code',
	'code' => $code,
	'redirect_uri' => 'http://1576a324.ittun.com/auth.php'
];
// 创建一个新cURL资源
$ch = curl_init($api);

//启用时会发送一个常规的POST请求,就像表单提交的一样。 
curl_setopt($ch, CURLOPT_POST, 1);

//全部数据使用HTTP协议中的"POST"操作来发送。//http_build_query::生成 URL-encode 之后的请求字符串
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// 抓取URL并把它传递给浏览器
$rs = curl_exec($ch);
// 关闭cURL资源，并且释放系统资源

curl_close($ch);
$rs = json_decode($rs,true);
print_r($rs);
$at = $rs['access_token'];
$uid = $rs['uid'];



$api2 = 'https://api.weibo.com/2/users/show.json?access_token='.$at.'&uid='.$uid;

$rs = file_get_contents($api2);

$user = json_decode($rs,true);
print_r($user);










 ?>