<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="referrer" content="origin">
        <meta name="renderer" content="webkit" />
        <title>Laraveler - 邮箱验证</title>
        <style>
            body {background:#fff;color:#000;font-weight:normal;font-family:"lucida Grande",Verdana,"Microsoft YaHei";padding:0 7px 6px 4px;margin:0;}
            p.bind {font-size: 16px;}
            .confirm-btn {font-size: 14px;width: 65px;height: 30px;color: #fff;line-height: 11px;background-color: #22d7bb;border: none;
                position: relative;margin-top: 4%;padding: 3px 5px;border-radius: 4px;}
        </style>
    </head>
    <body>
        <h2>亲爱的 {{ $data['user']->username }} ，您好</h2>

        <p class="bind">您申请的绑定邮箱地址验证，请点击 <a href="{{ $data['email_verify_url'] }}" role="button" class="confirm-btn" style="text-decoration: none;">这里</a> 确认，O(∩_∩)O谢谢！！！</p>

        <p class="bind">如果上面的按钮无法点击，您也可以尝试复制（右键复制）下面的地址，并粘帖到浏览器的地址栏中访问。</p>

        <p><a href="{{ $data['email_verify_url'] }}">{{ $data['email_verify_url'] }}</a></p>
        <br>
        <p>Laraveler - 中文领域的Laravel技术问答交流社区祝您使用愉快！！！</p>
    </body>
</html>