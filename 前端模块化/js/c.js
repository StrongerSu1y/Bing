
// define 可以传入一个回调函数
// define 可以传入一个数组参数，用来指定依赖关系
// 如果存在依赖关系，那么回调函数可以通过
// 形参来接收被依赖模块的返回值，按顺序一一对应
define(['./d', './f', './e'], function (d, fff) {
    // 
    console.log('c');

    // d 是d模块的返回值
    console.log(d);

    // ppp 是e模块的返回值
    console.log(ppp);

    var name = '小明';

    var sayHi = function () {
        console.log('小明你好');
    }

    // 公开内容数据
    return {
        name: name,
        sayHi: sayHi
    }

});