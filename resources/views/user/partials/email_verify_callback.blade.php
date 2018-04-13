<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="referrer" content="origin">
    <meta name="renderer" content="webkit" />
    <title>Laraveler - 中文领域的Laravel技术问答交流社区</title>
    <style>
        body {background:#fff;color:#000;font-weight:normal;font-family:"lucida Grande",Verdana,"Microsoft YaHei";padding:0 7px 6px 4px;margin:0;}
        .logo {
            padding: 5px 15px;
            border-bottom: 1px solid #ddd;
            box-shadow: 0px 2px 10px 0px rgba(0,0,0,0.1), 0 1px rgba(0,0,0,0.1);
        }
        p {font-size: 20px;font-weight: 500;text-align: center;}
    </style>
</head>
<body>
    <div class="logo">
        <a class="navbar-brand all-index-logo" href="{{ url('/') }}" title="Serenity" style="padding: inherit;">
            <img class="all-index-logo-img" src="/imgs/logo.png" width="190" height="50"/>
        </a>
    </div>
    @if(status == 1)
        <p>邮箱地址绑定成功</p>
    @elseif(status == 2)
        <p>邮箱地址绑定失败</p>
    @endif
</body>
</html>