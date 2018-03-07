<link rel="stylesheet" href="{{ asset('libs/jquery-checkbox/css/jquery-labelauty.css') }}">
<style>
    p {font-size: 14px;color: #555;}
    ul {list-style-type: none;width: 332px;margin: 0 auto;}
    ul li {float: left;margin-right: 40px;}
</style>

<ul class="dowebok">
    <p>请选择您当前的职业状态：</p>
    <li><input type="radio" id="stu" name="radio" data-labelauty="学生"></li>
    <li><input type="radio" id="working" name="radio" data-labelauty="在职"></li>
    <li><input type="radio" id="wait_employ" name="radio" data-labelauty="待业"></li>
</ul>

<script src="{{ asset('libs/jQuery/jQuery-2.2.0.min.js') }}"></script>
<script src="{{ asset('libs/jquery-checkbox/js/jquery-labelauty.js') }}"></script>
<script>
    $(function(){
        $(':input').labelauty();
    });
</script>
<script>
    $(function () {

    });
</script>