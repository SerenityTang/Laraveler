<div class="account-l fl">
    <span class="list-title">帮助中心</span>
    <ul id="accordion" class="accordion">
        <li>
            <div class="link"><i class="fa fa-leaf"></i>积分<i class="fa fa-chevron-down"></i></div>
            <ul class="submenu">
                <li id="shop" class="{{ App\Helpers\Helpers::setActive('help/credit/introduce', 'current') }}"><a
                            href="{{ url('help/credit/introduce') }}">积分介绍</a></li>
                <li id="shop" class="{{ App\Helpers\Helpers::setActive('help/credit/rule', 'current') }}"><a
                            href="{{ url('help/credit/rule') }}">积分规则</a></li>
            </ul>
        </li>
        <li>
            <div class="link"><i class="fa fa-cny" style="padding-left: 5px;"></i>L币<i class="fa fa-chevron-down"></i>
            </div>
            <ul class="submenu">
                <li id="shop" class="{{ App\Helpers\Helpers::setActive('help/coin/introduce', 'current') }}"><a
                            href="{{ url('help/coin/introduce') }}">L币介绍</a></li>
            </ul>
        </li>
    </ul>
</div>