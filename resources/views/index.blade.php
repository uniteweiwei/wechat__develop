<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta property="qc:admins" content="120520210761324614456637571115406000" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<link rel="stylesheet" href="/css/bootstrap.min.css">
<style>
.goods {
    margin: 2% 0;
}
.goods img {
    width: 200px;
}
#navb li {
    float: left;
    width: 33%;
    text-align: center;
    list-style: none;
    line-height: 50px;
}
body{
    padding-bottom: 70px;
}

.col-xs-6 goods{
    float: left;
    width: 500px;
}
h1{

    text-align: center;
}
</style>
<body>
    <h1>简洁大气的商城</h1>
    <div class="container">
        <div class="row"> 
        @foreach($goods as $g)
            <div class="col-xs-6 goods">
           
                <a href="/goods/{{$g->gid}}"><img src="/images/goods.jpg" alt=""></a>
                <p>
                    {{ $g->goods_name }}
                    &yen;<span>{{$g->price}}</span>               
                 
                </p>
                <p>
                    {{$g->descs}}
                </p>


            </div>
        @endforeach

        </div>
        <div class="col-xs-12 navbar-fixed-bottom">
          <ul class="navbar-fixed-bottom navbar-default row" id="navb">
            <li><a href="/">首页</a></li>
            <li><a href="/center">个人中心</a></li>
            <li><a href="">帮助</a></li>
          </ul>
        </div>
    </div>
</body>
<script src="http://libs.useso.com/js/jquery/2.1.0/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
</html>