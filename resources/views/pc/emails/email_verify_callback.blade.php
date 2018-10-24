<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="referrer" content="origin">
    <meta name="renderer" content="webkit"/>
    <title>Laraveler - 中文领域的Laravel技术问答交流社区</title>
    <style>
        body {
            background: #fff;
            color: #000;
            font-weight: normal;
            font-family: "lucida Grande", Verdana, "Microsoft YaHei";
            padding: 0 7px 6px 4px;
            margin: 0;
        }

        .logo {
            padding: 5px 15px;
            border-bottom: 1px solid #ddd;
            box-shadow: 0px 2px 10px 0px rgba(0, 0, 0, 0.1), 0 1px rgba(0, 0, 0, 0.1);
        }

        p.tip {
            font-size: 20px;
            font-weight: 500;
            text-align: center;
            margin-top: 280px;
        }

        p.return {
            text-align: center;
        }

        a.return-btn {
            width: 500px;
            height: 50px;
            text-align: center;
            text-decoration: none;
            margin: 5px auto;
            background-color: #22d7bb;
            color: #fff;
            padding: 10px 15px;
            font-size: 15px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
<div class="logo">
    <a class="navbar-brand all-index-logo" href="{{ url('/') }}" title="Serenity" style="padding: inherit;">
        <img class="all-index-logo-img" src="/imgs/logo.png" width="190" height="50"/>
    </a>
</div>
@if($status == 1)
    <p class="tip">恭喜您，邮箱地址绑定验证成功</p>
@elseif($status == 2)
    <p class="tip">邮箱地址绑定验证链接已失效</p>
@elseif($status == 3)
    <p class="tip">邮箱地址绑定验证链接已过期，请重新验证</p>
@endif
<p class="return"><a href="{{ url('/') }}" class="return-btn">返回首页</a></p>
</body>
</html>