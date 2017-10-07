<?php

  require '../functions.php';

  checkLogin();

  // 设置导航菜单
  $cogs = array('menus', 'slides', 'settings');

  // 当前导航
  $active = 'slides';

  // 查询所有轮播图
  $lists = query('SELECT `value` FROM options WHERE `key`="home_slides"');

  // 轮播图在数据库中是以 json 格式存在的
  // 第二个参数 true 强制转成数组
  $lists = json_decode($lists[0]['value'], true);
  
  // 将$lists数组中的单元得新取出
  // 生成一个新的数组，目的是为了避免
  // 索引值不更新的问题
  $lists = array_values($lists);

  // 获取操作类型，默认值设为 add
  $action = isset($_GET['action']) ? $_GET['action'] : 'add';

  if(!empty($_FILES)) { // 图片上传
    // 定义上传目录
    $path = '../uploads/slides/';

    // 检测目录是否存在
    if(!file_exists($path)) {
      mkdir($path);
    }

    // 获取文件后缀
    $ext = explode('.', $_FILES['image']['name'])[1];
    // 生成文件名，使用时间戳避免重复
    $filename = time();

    // 拼凑完整路径
    $realpath = $path . $filename . '.' . $ext;

    // 转移上传目录
    move_uploaded_file($_FILES['image']['tmp_name'], $realpath);

    // 返回绝对路径给浏览器，实现预览效果
    echo substr($realpath, 2);

    exit;
  }

  if(!empty($_POST)) { // 添加

    /* 这里出现过 “弱智bug” */
    
    // 添加轮播图实现上是在 json 数据中
    // 添加一些数据
    
    // 将轮播图信息 $_POST 追加到一个数组中
    // 然后将这个数据转成 json ，从页实现数据的追加
    $lists[] = $_POST;

    // 将追加了数据的数组转成 json 字符串
    $json = json_encode($lists, JSON_UNESCAPED_UNICODE);

    // 这里当时出现了两次错误
    // 第1次，关联数组，写成了索引数组
    // $result = update('options', array('value', $json), 10);
    
    // 第2次，关联数组的 “key”，写错了，我们应该更新 value 字段
    // $result = update('options', array('home_slides'=>$json), 10);
    
    // 正确写法
    $result = update('options', array('value'=>$json), 10);

    if($result) {
      header('Location: /admin/slides.php');
      exit;
    }
  }

  if($action == 'delete') { // 删除操作
    // 获取索引值
    $sid = $_GET['sid'];

    // 根据索引值删除数组中的某个单元
    // 来实现数据的删除
    unset($lists[$sid]);

    // 将删除了某个单的数组重新转成 json 
    $json = json_encode($lists, JSON_UNESCAPED_UNICODE);

    // 将json数据更新到数据库
    $result = update('options', array('value'=>$json), 10);

    if($result) {
      header('Location: /admin/slides.php');
      exit;
    }
  }

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Slides &laquo; Admin</title>
  <?php include './inc/style.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>图片轮播</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
          <form action="/admin/slides.php" method="post">
            <h2>添加新轮播内容</h2>
            <div class="form-group">
              <label for="image">图片</label>
              <!-- show when image chose -->
              <img class="help-block thumbnail" style="display: none">
              <input id="image" class="form-control" type="file">
              <input type="hidden" name="image">
            </div>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="link">链接</label>
              <input id="link" class="form-control" name="link" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="80">
                  序号
                </th>
                <th class="text-center">图片</th>
                <th>文本</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($lists as $key=>$val) { ?>
              <tr>
                <td class="text-center">
                  <?php echo $key+1; ?>
                </td>
                <td class="text-center">
                  <img class="slide" src="<?php echo $val['image']; ?>">
                </td>
                <td><?php echo $val['text']; ?></td>
                <td><?php echo $val['link']; ?></td>
                <td class="text-center">
                  <a href="/admin/slides.php?action=delete&sid=<?php echo $key; ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php include './inc/aside.php'; ?>
  <?php include './inc/script.php'; ?>
  <script>
    $('#image').on('change', function() {

      // this.files[0]; // 选择第一个上传文件的信息
      var data = new FormData();
      data.append('image', this.files[0]);

      var xhr = new XMLHttpRequest;

      xhr.open('post', '/admin/slides.php');

      xhr.send(data);

      xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200) {
          // console.log(xhr.responseText);
          
          // 预览效果
          $('.thumbnail').attr('src', xhr.responseText).show();

          // 存储路径上，当提交表单时可以存入数据库
          $('input[name="image"]').val(xhr.responseText);
        }
      }

    })
  </script>
</body>
</html>
