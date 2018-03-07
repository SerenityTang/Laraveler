(function(factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as anonymous module.
        define('ChineseDistricts', [], factory);
    } else {
        // Browser globals.
        factory();
    }
})(function() {
    var ChineseDistricts;
    $.ajax({
        type: "GET", //发送请求类型
        url: "", //请求路径
        //async:false,//默认为true,是异步请求
        dataType: "script", //返回类型
        cache: false, //不使用当前浏览器的缓存
        success: function(data) { //请求成功后调用的回调函数
            //请求成功
            ChineseDistricts = data.result.data;
        },
        // errors: function() {
        //     //Exception
        //     alert("errors");
        // }
    });
    if (typeof window !== 'undefined') {
        window.ChineseDistricts = ChineseDistricts;
    }
    return ChineseDistricts;
});