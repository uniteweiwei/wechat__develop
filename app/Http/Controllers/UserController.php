<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
class UserController extends Controller
{
	protected $app;

    public function __construct()
    {
    	$options = [
		    'debug'  => true,
		    'app_id' => 'wxf12741e48c54909b',
		    'secret' => '20fc6575caf0f3e9a8eb6026b4795252',
		    'token'  => 'sw',

		    'log' => [
		        'level' => 'debug',
		        'file'  => 'C:\XAMPP\htdocs\fenxiao\public\easywechat.log', // XXX: 绝对路径！！！！
		    ],		  

		    'oauth' => [
		      'scopes'   => ['snsapi_userinfo'],
		      'callback' => '/login',
		  ],
		];
		$this->app = new Application($options);		
    }

    public function center(Request $req)
    {  	
		// 未登录
		if ( !$req->session()->has('user') ) {
			$oauth = $this->app->oauth;
		    return $oauth->redirect();
		}
		return 'session success backup';
    }		

    public function login(Request $req)
	{
		$oauth = $this->app->oauth;
		$user = $oauth->user();
		$req->session()->put('user',$user);
		//session(['user'=>$user]);
		$data = session('user');
		//dd($data);
		return redirect('/center');
	}

	public function logout(Request $req)
	{
		$req->session()->flush();
		return "clear success";
	}
}
