<?php

  // login.php 程序需要承担两个功能

  // a) 以 get 方式发起的请求，展示页面
  // b) 以 post 方式发起的请求，处理登录逻辑
  
  // 只有 post 时才执行
  
  // if(empty($_POST)) {
    // 没有以 post 方式提或者 没有设置 name 属性
  // }
  
  $message = '';

  if(!empty($_POST)) {
    // 肯定是以 post 方式提交的
    
    $email = $_POST['email']; // 用户以 post 方式提交的邮箱
    $password = $_POST['password']; // 用户以 post 方式提交的密码

    // 验证登陆需要先查一查有没有对应用户
    // 如果有对应用户，再查询密码是否匹配
    
    // 连接数据库服务器
    $connect = mysqli_connect('localhost', 'root', '123456');

    // 选择数据库
    mysqli_select_db($connect, 'baixiu');

    // 设置编码，防止乱码
    mysqli_set_charset($connect, 'utf8');

    // select * from users where email="admin@baixiu.com"
    $result = mysqli_query($connect, 'SELECT * FROM users WHERE email="' . $email . '"');

    // 取出资源中的结果
    // 如果用户名正确则能查出结果
    // 如果不正确结果为 null
    $row = mysqli_fetch_assoc($result);

    if($row) { // 有结果，存在邮箱
      // 再检测密码是否一致
      if($row['password'] == $password) {

        // 通过会话记录下登录的状态
        // 这样的话浏览器再次发起请求时
        // 可以检测判断这个状态
        
        // 存一个 session ，服务器会自动的设置一个响应头
        // Set-Cookie 给浏览器，然后浏览器在本地存一个 cookie
        // 这个 cookie 默认叫 PHPSESSID
        
        // PHP 通过超全局数组 $_SESSION 存一个 session 
        
        // PHP 中使用 session 需要先开启 session 
        session_start();

        // 将用户信息记录在服务端的 session 中
        $_SESSION['user_info'] = $row;

        // / ~~ http://域名或ip/
        header('Location: /admin');

        // 退出程序
        exit;
      } else { // 不一致
        // 错误提示
        $message = '用户或密码错误!';
      }
    } else { // 没有结果，不存在邮箱
      // 错误提示
      $message = '邮箱不存在!';
    }
  }

  // if(isset($_POST['abc'])) {
  //   echo 'post';
  // }

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>

  <?php include './inc/style.php'; ?>

  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form action="./login.php" method="post" class="login-wrap">
      <img class="avatar" src="../assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if(!empty($message)) { ?>
      <div class="alert alert-danger">
        <strong>错误！</strong> <?php echo $message; ?>
      </div>
      <?php } ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="email" name="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <input class="btn btn-primary btn-block" type="submit" value="登 录">
    </form>
  </div>
</body>
</html>
