<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Good;
use Cart;
use App\Item;
use App\Order;
use App\User;
use App\Fee;

class ShopController extends Controller
{
    public function index()
    {
        	$goods = Good::all();

        	return view('index',compact('goods'));
    }

    public function goods($id)
    {
    	   $goods = Good::find($id);


        	return view('goods',compact('goods'));
    }

    public function cart($id)
    {
        	$goods = Good::find($id);
        	Cart::add(array(
    		    'id' => $goods->gid,
    		    'name' => $goods->goods_name,
    		    'price' => $goods->price,
    		    'quantity' => 1,
    		    'attributes' => array()
    		));
    	   return redirect('/cart_all');
    }

    public function cart_all()
    {
        	$carts = Cart::getContent();
        	$total = Cart::getTotal();
        	return view('cart',compact('carts','total'));
    }

    public function done(Request $req,Order $order,Item $item)
    {

          $order->ordsn = date('Ymd').mt_rand(10000,99999);
          $order->xm = $req->xm;
          $order->tel = $req->tel;
          $order->address = $req->address;
          $order->money = Cart::getTotal();
          $order->ispay = 0;
          $order->ordtime = time();
          $order->save(); 

          foreach(Cart::getContent() as $i) {

            $item->oid = $order->oid;
            $item->gid = $i->id;
            $item->goods_name= $i->name;
            $item->price = $i->price;
            $item->amount = $i->quantity;
            $item->save();

          }

          return view('done',compact('item','order'));
    }


    public function payok(Request $req)
    {

      $order = Order::where('oid',$req->oid)->first();

      $order->ispay = 1;
      $order->save();


      if(!session()->has('user')){
        return redirect('center');
      }
      $openid = session()->get('user')['id'];
      // dd(session()->get('user'));
      $user = User::where('openid',$openid)->first();
      //dd($user);

      $rate = [0.5,0.25,0.1];
          foreach([$user->p1, $user->p2, $user->p3] as $k=>$p ) {
          if($p > 0) {
            $fee = new Fee();
            $fee->uid = $p;
            $fee->byid= $user->uid;
            $fee->oid= $order->oid;
            $fee->amount= $order->money * $rate[$k];
            $fee->save();
           } 
        } 
      return "success";
    }
}
