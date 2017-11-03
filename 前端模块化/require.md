## 前端模块化

### 为什么要前端模块化？

* 将公共功能进行封装实现复用
* 灵活解决依赖
* 解决全局变量污染

### 如何实现前端模块化？

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
    
    <!-- 通过标签加载js是不能做到模块化的 -->
    <script src="./js/jquery.min.js"></script>
    <script src="./js/jquery.cookie.js"></script>
    <script src="./js/a.js"></script>
</body>
</html>
```

Javascript 语言本身是不具备模块化能力的，需要自已进行封装来实现模块的定义以及加载，其实现遵照一定的规范（COMMONJS）

Nodejs 是运行在服务端的Javascript，它是按着commonjs的规范实现的模块化

经过实践发现浏览器端Javascript按着commonjs的规范实现模块化时，有很不足之处，此时就有人在commonjs的基础上重新定了一个规范AMD(Async Module Define)，其代表就是require.js

随前端开发演变，又有人定义另外一个浏览器端模块化的规范CMD(Common Module Define)，淘宝的玉伯定义的，其代表是seajs

UMD

### 前端模块化使用requirejs

> requirejs 本身是一个js文件，它按照AMD规范实来实现模块的定义和加载规则，应用在浏览器端

#### 定义模块

通过define()方法来定义一个模块

* 基本定义

```javascript
// 模块定义
define(function () {

  alert('hello');

});
```

* 依赖注入

```javascript
// 定义模块，使用依赖注入解决依赖
define(['./a', './b', './c'], function (a, b, c) {

  console.log(a + '现在是:' + b + '今天的任务是' + c.join('、'));

});
```

* 返回值

定义模块时，可以有返回值，但并不是必须，返回数据类型没有任何约束，通常返回值是对象更有意义。

```javascript
define(function () {

  // 处理逻辑
  function sayHello(name) {
    alert('你好' + name + '你好帅!');
  }

  var todos = ['起床', '上课'];

  return {
    name: 'laozhao',
    sayHello: sayHello,
    todos: todos
  }

  // return sayHello;

});
```

#### 加载模块

通过require()方法来加载一个模块

* 基本使用

```html
<script src="./libs/require.js"></script>
<script>
  require(['./libs/a']);
</script>
```

* 加载有依赖的模块

```html
<script src="./libs/require.js"></script>
<script>
  require(['./libs/a']);
</script>
```

* 依赖注入

```html
<h2></h2>
<script src="./libs/require.js"></script>
<script>
  require(['./libs/main'], function (main) {
    // console.log(main);
    document.querySelector('h2').innerHTML = main;
  });
</script>
```

#### 入口文件

通过为引入require的标签加入data-main属性，其属性值为某一个模块的路径，此模块可以自动被加载并执行。

```html
<script src="./libs/require.js" data-main="./libs/a.js"></script>
```

#### 加载路径

requirejs加载模块时，路径是遵照一些规则的，分成以下几种情况

* 当没有入口文件时，加载路径以引入requirejs的页面为准
* 当存在入口文件时，加载路径以入口文件为准
* 通过配置可以自定义路径

```javascript
// 通过配置文件可以改变
require.config({
  baseUrl: './src'
});

require(['a']);
```

#### 配置项

* 配置基础路径

```javascript
// 通过配置文件可以改变
require.config({
  baseUrl: './src'
});
```

* 配置路径

```javascript
require.config({
  baseUrl: './',
  paths: {
    jquery: 'assets/jquery/jquery',
    template: 'assets/artTemplate/template-native'
  }
});
```

* 配置不支持模块的插件

```javascript
require.config({
  baseUrl: './',
  paths: {
    jquery: 'assets/jquery/jquery',
    template: 'assets/artTemplate/template-native',
    bootstrap: 'assets/bootstrap/js/bootstrap.min'
  },
  shim: {
    bootstrap: {
      deps: ['jquery']
    }
  }
});
```

匿名模块和具名模块

```javascript
// 匿名
define([], function () {
  // code...
  // return 
});

// 具名
define('demo', [], function () {
  // code...
});
```