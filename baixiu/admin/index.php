<?php
  
  // 
  require '../functions.php';
  
  // 检测登录
  checkLogin();

  // 定义导航状态
  $active = 'dashboard';

  $presult = query('SELECT count(*) AS totalPost FROM posts');

  $dresult = query('SELECT count(*) AS totalDrafted FROM posts WHERE status= "drafted"');

  $totalPost = $presult[0]['totalPost'];

  $totalDrafted = $dresult[0]['totalDrafted'];

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <?php include './inc/style.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'; ?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $totalPost; ?></strong>篇文章（<strong><?php echo $totalDrafted; ?></strong>篇草稿）</li>
              <li class="list-group-item"><strong>6</strong>个分类</li>
              <li class="list-group-item"><strong>5</strong>条评论（<strong>1</strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
  <?php include './inc/aside.php'; ?>
  <?php include './inc/script.php'; ?>
  <script>
    NProgress.start();

    NProgress.done();
  </script>
</body>
</html>
