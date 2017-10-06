<?php
  
  // 包含公共文件
  require '../functions.php';

  // 检测登录
  checkLogin();

  // 定义二级导航
  $actives = array('category', 'post', 'posts');

  // 定义导航状态
  $active = 'post';

  $action = isset($_GET['action']) ? $_GET['action'] : 'add';

  // 由于上传文件时 $_POST 数组为空
  // 所以判断条件使用 || 这样可以使得
  // 上传文件的逻辑可以被执行
  if(!empty($_POST) || $action == 'upfile') {

    if($action == 'add') {
      // 将用户提交上的数据插入数据库
      $result = insert('posts', $_POST);

      // 如果成功跳转至列表
      if($result) {
        header('Location: /admin/posts.php');
        exit;
      }

      // 错误提示
      $message = '添加文章失败!';
    } else if($action == 'upfile') { // 文件上传
      
      // 设置一个上传目录
      $path = '../uploads/thumbs';

      // 检测目录是否存在
      if(!file_exists($path)) {
        mkdir($path);
      }

      // 根据文件名截取文件后缀
      $ext = explode('.', $_FILES['feature']['name'])[1];
      // 以时间戳做为文件名，一定程度上避免重复
      $filename = time();

      // 拼凑完整路径
      $dest = $path . '/' . $filename . '.' . $ext;

      // 转移上传文件
      move_uploaded_file($_FILES['feature']['tmp_name'], $dest);

      // 处理成网络路径
      echo substr($dest, 2);

      exit;
    } else if($action == 'update') { // 更新

      // 获取文章id，根据文章id更新
      $id = $_POST['id'];

      // id 是主键不允许更新
      unset($_POST['id']);

      // 执行更新
      $result = update('posts', $_POST, $id);

      if($result) {
        header('Location: /admin/posts.php');
        exit;
      }
    }
  }

  // 取出现有所有分类
  $sql = 'SELECT * FROM categories';
  // 
  $lists = query($sql);

  if($action == 'edit') {

    // 
    $action = 'update';
    // 获取文章id
    $pid = $_GET['pid'];

    // 查询文章原始信息
    $sql = 'SELECT * FROM posts WHERE id=' . $pid;

    $rows = query($sql);

  }

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <?php include './inc/style.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form action="/admin/post.php?action=<?php echo $action; ?>" method="post" class="row">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_info']['id']; ?>">
        <input type="hidden" name="id" value="<?php echo $pid; ?>">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" value="<?php echo isset($rows[0]['title']) ? $rows[0]['title'] : ''; ?>" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea style="height: 200px" id="content" name="content" cols="30" rows="10" placeholder="内容">
              <?php echo isset($rows[0]['content']) ? $rows[0]['content'] : ''; ?>
            </textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" value="<?php echo isset($rows[0]['slug']) ? $rows[0]['slug'] : ''; ?>" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <?php if(empty($rows[0]['feature'])) { ?>
            <img class="help-block thumbnail" style="display: none">
            <?php } else { ?>
              <img class="help-block thumbnail" src="<?php echo $rows[0]['feature']; ?>">
            <?php } ?>
            <input id="feature" class="form-control" type="file">
            <input type="hidden" value="<?php echo isset($rows[0]['feature']) ? $rows[0]['feature'] : '' ?>" name="feature" id="thumb">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category_id">
              <?php foreach($lists as $key=>$val) { ?>
              <option value="<?php echo $val['id']; ?>" <?php if(isset($rows) && $rows[0]['category_id'] == $val['id']) { ?> selected <?php } ?>>
                <?php echo $val['name']; ?>
                </option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" value="<?php echo isset($rows[0]['created']) ? $rows[0]['created'] : ''; ?>" type="text">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted" <?php if(isset($rows) && $rows[0]['status'] == 'drafted') { ?> selected <?php } ?>>草稿</option>
              <option value="published" <?php if(isset($rows) && $rows[0]['status'] == 'published') { ?> selected <?php } ?>>已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php include './inc/aside.php'; ?>
  <?php include './inc/script.php'; ?>
  <script src="/assets/vendors/ueditor/ueditor.config.js"></script>
  <script src="/assets/vendors/ueditor/ueditor.all.min.js"></script>
  <script>
    // 富文本编辑器
    UE.getEditor('content', {
      autoHeightEnabled: true
    });

    $('#feature').on('change', function() {
      // 通过原生 DOM 可以获得文件信息
      // this.files[0];
      // 通过H5内置的对象 FormData 可以实现文件数据的
      // 管理
      var data = new FormData();
      data.append('feature', this.files[0]);
      
      var xhr = new XMLHttpRequest;

      xhr.open('post', '/admin/post.php?action=upfile');

      xhr.send(data);

      xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200) {
          console.log(xhr.responseText);
          // 预览图片
          $('.thumbnail').attr('src', xhr.responseText).show();

          // 将图片的路径做为隐藏表单的值
          // 提交给服务端进行存储
          $('#thumb').val(xhr.responseText);
        }
      }
    })
  </script>
</body>
</html>
