$(function () {

    'use strict';


    var $citypicker1 = $('#city-picker1');

    $citypicker1.citypicker();

    var $citypicker2 = $('#city-picker2');

    $citypicker2.citypicker({//广东/广州市/番禺区/石楼镇
        province: '广东',
        city: '广州市',
        district: '番禺区',
        county: '石楼镇'
    });

    var $citypicker3 = $('#city-picker3');

    $('#reset').click(function () {
        $citypicker3.citypicker('reset');
    });

    $('#destroy').click(function () {
        $citypicker3.citypicker('destroy');
    });

    $('#get-code').click(function () {//获取省市区县id
        var count = $('#code-count').data('count');
        var code = $citypicker3.data('citypicker').getCode(count);
        $(this).find('.code').text(': ' + code);
    });

    $('.dropup .dropdown-menu a').click(function () {
        var $btn = $('#code-count');
        $btn.data('count', $(this).data('count')).find('.text').text($(this).text());
        if ($('#get-code .code').text()) {
            $('#get-code').trigger('click');
        }
    });

});
