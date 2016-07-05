<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use App\User;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Raw;

class WxController extends Controller
{
	public $app;

	public function __construct(){
		$options = [
		    'debug'  => true,
		    'app_id' => 'wxf12741e48c54909b',
		    'secret' => '20fc6575caf0f3e9a8eb6026b4795252',
		    'token'  => 'sw',

		    'log' => [
		        'level' => 'debug',
		        'file'  => 'C:\XAMPP\htdocs\fenxiao\public\easywechat.log', // XXX: 绝对路径！！！！
		    ],
		];

		$this->app = new Application($options);
	}


    public function index()
    {
    	$server = $this->app->server;	


    	$server->setMessageHandler(function($message){

		    if ($message->MsgType == 'event') {

		        switch ($message->Event) {
		            case 'subscribe':
		            	return $this->pay_att($message);
		                break; 
	                case 'unsubscribe':
		            	return $this->not_pay_att($message);
		                break;
		            default:
		            	return "sorry";
		                break;
		        }
		    }

		    if($message->MsgType == 'text'){

		    	$text = new Text(['content' => '功能正在完善中']);
		    	return $text;    	

		    }		    

		    if($message->MsgType == 'voice'){

		    	return $this->find_music($message);

		    }		    

		    if($message->MsgType == 'image'){

		    	return $this->image($message);

		    }		    

		    if($message->MsgType == 'location'){

		    	return $this->location($message);
		    }		    

		});

		
	
		$response = $server->serve();
		return $response;
    }

    public function location($message)
    {

        $wd = $message->Location_X;
        $jd = $message->Location_Y;
    	$url = "http://restapi.amap.com/v3/place/around?&keywords=ATM&key=e9c94537835cfca4d56663a03331ec2b&location={$wd},{$jd}&output=json&radius=1300";
                $rs = json_decode(file_get_contents($url),true);
                $rs = $rs["pois"]; 
                $str = '';
                for( $i = 0; $i < count($rs); $i++ ){
                    $str .="您要找的{$rs[$i]['name']}\n";
                    $str .="地址位于{$rs[$i]['address']}\n";
                    $str .="距离你{$rs[$i]['distance']}米\n\n";
                }
        return $str;
    }

    public function image($message)
    {

    	$pic = $message->PicUrl;
                $url = "http://apicn.faceplusplus.com/v2/detection/detect?api_key=735256d807a0141d0fb6c4d283ac84bf&api_secret=f0c6d8MONE_uUfNP2VE5fvX3Y1AdHxDo&url={$pic}&attribute=glass,gender,age";
                $rs = json_decode(file_get_contents($url),true);
                $rs = $rs['face'];
                //print_r($rs);

                for ( $i=0, $str=''; $i<count($rs) ; $i++ ) {
                    //$str .= "年龄大概是".$rs[$i]['attribute']['age']['value']."岁,";
                    $sex = $rs[$i]['attribute']['gender']['value'];
                    $glass = $rs[$i]['attribute']['glass']['value'];
                    $age = $rs[$i]['attribute']['age']['value'];
                    $num = count($rs);

                    if($sex == "Male"){
                        $sex = str_replace($sex, '男生', $sex);
                    }else{
                        $sex="女生";
                    }
                    if($glass=="None"){
                        $glass = str_replace($glass, '没有戴', $glass);
                    }else{
                        $glass="有戴";
                    }


                    $str .= "图片里面有".$num."个人:\n";
                    $str .= "年龄大概".$age."岁\n";
                    $str .= $sex."\n";
                    $str .= $glass."眼镜\n";

                }

                return $str;  	
    }

    public function pay_att($message)
    {
    	//qrscene_123123
    	$qrid = false;
    	if(isset($message->EventKey)){
    		$qrid = substr($message->EventKey, 8);
    	}


    	$user = User::where('openid',$message->FromUserName)->first();
	    $userservice = $this->app->user;
	    $fans = $userservice->get($message->FromUserName);  
			

			if( $user && $user->state==0 ) {
    			$user->state = 1;
    			$user->save();
    		}

    		if( !$user ){
	      	    $user = new User();
			    $user->openid = $message->FromUserName;
			    $user->subtime = time();
			    $user->name = $fans->nickname;   
			    if($qrid){
			    	//层级关系			    
				    $prow = User::find($qrid);
				    $user->p1 = $qrid;
				    $user->p2 = $prow->p1;
				    $user->p3 = $prow->p2;
				    
			    }

			    $user->save(); 
			    //生成二维码 
			    $user->qrimg = $this->qr($user->uid);
    			$user->save();			
    		}		
		$buttons = [
		    [
		        "type" => "click",
		        "name" => "音乐速递",
		        "key"  => "music"
		    ],
		    [
		        "name"       => "推荐阅读",
		        "sub_button" => [
		            [
		                "type" => "view",
		                "name" => "搜索",
		                "url"  => "http://www.soso.com/"
		            ],
		            [
		                "type" => "view",
		                "name" => "视频",
		                "url"  => "http://v.qq.com/"
		            ],
		            [
		                "type" => "click",
		                "name" => "赞一下我们",
		                "key" => "V1001_GOOD"
		            ],
		        ],
		    ],		    

		    [
		        "name"       => "更多福利",
		        "sub_button" => [
		            [
		                "type" => "pic_photo_or_album",
                    	"name"     => "系统拍照发图", 
                    	"key"      => "system_pic", 
                   		"sub_button" => [ ]
		            ],
		            [
		                "type" => "scancode_push",
		                "name" => "扫码推事件",
	                    "key"=> "rselfmenu_0_1", 
	                    "sub_button"=> [ ]
		            ],
		            [
		            "name"  => "发送位置", 
		            "type"  => "location_select", 
		            "key"  => "send_loc"
		            ],
		        ],
		    ],
		];


		$id_user = $message->FromUserName;
		$menu = $this->app->menu;
		$menu = $menu->add($buttons);
		// $menus = $menu->test($id_user);
		// $menu = $menu->destroy();
    		


		return "welcome".$menu;	  	
    }

    public function not_pay_att($message)
    {
    	$user = User::where('openid',$message->FromUserName)->first();
    		if($user){
    			$user->state = 0;
    			$user->save();
    		}   
	}

	public function qr($uid) {
	    $qrcode = $this->app->qrcode;
	    $result = $qrcode->forever($uid);
	    $ticket = $result->ticket;
	    $url = $qrcode->url($ticket);
	    $img = file_get_contents($url);
	    $qr = $this->mkd() . '/qr_'.$uid.'.jpg';
	    file_put_contents(public_path() . $qr , $img);
	    return $qr;
	}
	// 根据年月日生成目录
	protected function mkd() {
	    $today = date('/Y/m');
	    if( !is_dir( public_path() . $today ) ) {
	        mkdir( public_path() . $today , 0777 , true);
	    }
	    return $today;
	}

	public function find_music($message)
	{
		$message = new Raw('<xml>
			<ToUserName><![CDATA[toUser]]></ToUserName>
			<FromUserName><![CDATA[fromUser]]></FromUserName>
			<CreateTime>1357290913</CreateTime>
			<MsgType><![CDATA[voice]]></MsgType>
			<MediaId><![CDATA[media_id]]></MediaId>
			<Format><![CDATA[Format]]></Format>
			<Recognition><![CDATA[腾讯微信团队]]></Recognition>
			<MsgId>1234567890123456</MsgId>
			</xml>');

		// $mes = $message->Recongnition;
		$m_id = $message->MediaId;
		return $m_id;

		
	}



}
