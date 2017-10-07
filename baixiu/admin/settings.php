<?php

  require '../functions.php';

  checkLogin();

  // 设置导航菜单
  $cogs = array('menus', 'slides', 'settings');

  // 当前导航
  $active = 'settings';

  if(!empty($_FILES)) { // 网站 logo 上传

    // 定义上传目录
    $path = '../uploads/';

    // 检测目录是否存在
    if(!file_exists($path)) {
      mkdir($path);
    }

    // 获取文件后缀
    $ext = explode('.', $_FILES['logo']['name'])[1];
    // 定义文件名，通过时间戳定义文件名可以避免重复
    $filename = time();

    // 拼凑完整路径
    $realpath = $path . $filename . '.' . $ext;

    // 转移上传文件
    move_uploaded_file($_FILES['logo']['tmp_name'], $realpath);

    // 返回绝对路径给浏览器
    echo substr($realpath, 2);

    exit;
  }

  if(!empty($_POST)) { // 添加/更新网站设置
    // 连接数据库
    $connection = connect();

    // 遍历数据
    foreach($_POST as $key=>$val) {
      // 多次执行 sql 语句
      $sql = 'UPDATE options SET value="' . $val . '" WHERE `key`="' . $key . '"';

      mysqli_query($connection, $sql);
    }
  }

  // 查询网站设置信息
  $rows = query('SELECT * FROM options WHERE id<9');

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Settings &laquo; Admin</title>
  <?php include './inc/style.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>网站设置</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form action="/admin/settings.php" method="post" class="form-horizontal">
        <div class="form-group">
          <label for="site_logo" class="col-sm-2 control-label">网站图标</label>
          <div class="col-sm-6">
            <input id="site_logo" name="site_logo" value="<?php echo $rows[1]['value']; ?>" type="hidden">
            <label class="form-image">
              <input id="logo" type="file">
              <img id="preview" src="<?php echo $rows[1]['value']; ?>">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="site_name" class="col-sm-2 control-label">站点名称</label>
          <div class="col-sm-6">
            <input id="site_name" name="site_name" class="form-control" type="type" value="<?php echo $rows[2]['value']; ?>" placeholder="站点名称">
          </div>
        </div>
        <div class="form-group">
          <label for="site_description" class="col-sm-2 control-label">站点描述</label>
          <div class="col-sm-6">
            <textarea id="site_description" name="site_description" class="form-control" placeholder="站点描述" cols="30" rows="6"><?php echo $rows[3]['value']; ?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="site_keywords" class="col-sm-2 control-label">站点关键词</label>
          <div class="col-sm-6">
            <input id="site_keywords" name="site_keywords" class="form-control" type="type" value="<?php echo $rows[4]['value']; ?>" placeholder="站点关键词">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">评论</label>
          <div class="col-sm-6">
            <div class="checkbox">
              <label>
                <input id="comment_status" name="comment_status" type="checkbox" <?php if($rows[6]['value'] == 1) { ?> checked <?php } ?> value="1">开启评论功能
              </label>
            </div>
            <div class="checkbox">
              <label><input id="comment_reviewed" name="comment_reviewed" <?php if($rows[7]['value'] == 1) { ?> checked <?php } ?> type="checkbox" value="1">评论必须经人工批准</label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-6">
            <button type="submit" class="btn btn-primary">保存设置</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php include './inc/aside.php'; ?>
  <?php include './inc/script.php'; ?>
  <script>
    $('#logo').on('change', function () {

      var data = new FormData();
      data.append('logo', this.files[0]);

      var xhr = new XMLHttpRequest;

      xhr.open('post', '/admin/settings.php');

      xhr.send(data);

      xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200) {
          console.log(xhr.responseText);

          // 预览效果
          $('#preview').attr('src', xhr.responseText);

          // 存储图片路径，将来提交时可以存到数据库中
          $('#site_logo').val(xhr.responseText);
        }
      }

    })
  </script>
</body>
</html>
