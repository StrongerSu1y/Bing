<?php
  
  require '../functions.php';

  // 检测登录
  checkLogin();

  // 定义二级导航
  $actives = array('category', 'post','posts');
  // 定义二级导航状态
  $active = 'category';

  // 获取 action 值，根action的值，决定处理哪些（增删改查）逻辑
  $action = isset($_GET['action']) ? $_GET['action'] : 'add';
  // 编辑和删除必须拥有一个条件
  // 一般根主键(id)来确定
  $cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 0;

  // 分类列表数据
  $lists = query('SELECT * FROM categories');

  if($action == 'add') { // 添加
    $title = '添加分类目录';
    $btnText = '添 加';
    
    // 只有点击添加按钮时才有必要操作
    // 数据库
    if(!empty($_GET)) {
      unset($_GET['id']);
      unset($_GET['action']);

      $result = insert('categories', $_GET);

      if($result) {
        header('Location: /admin/categories.php');
        exit;
      }
    }
  } else if($action == 'edit') { // 编辑操作
    // 文字显示
    $action = 'update';
    $btnText = '修 改';
    $title = '修改分类目录';

    // 根据 id 查询分类
    $sql = 'SELECT * FROM categories WHERE id=' . $cat_id;

    // 执行查询
    $rows = query($sql);
  } else if($action == 'delete') { // 删除操作

    $sql = 'DELETE FROM categories WHERE id=' . $cat_id;

    $result = delete($sql);

    if($result) {
      header('Location: /admin/categories.php');
      exit;
    }
  } else if($action == 'update') { // 更新资料

    // action 只是用来判断业务逻辑
    // 不需要进行数据更新（表中也没有此字段）
    unset($_GET['action']);
    // 获得分类id，做更新的条件
    $cat_id = $_GET['id'];

    // id 是主键，不允许被修改
    unset($_GET['id']);

    // 执行更新操作
    $result = update('categories', $_GET, $cat_id);

    // 执行成功刷新当前页面
    if($result) {
      header('Location: /admin/categories.php');
      exit;
    }

    // 错误信息提示
    $message = '更新失败！';
  }

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
  <?php include './inc/style.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($message)) { ?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $message; ?>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col-md-4">
          <form action="/admin/categories.php" method="get">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <input type="hidden" name="id" value="<?php echo $cat_id; ?>">
            <h2><?php echo $title; ?></h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" value="<?php echo isset($rows[0]['name']) ? $rows[0]['name'] : '' ; ?>" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" value="<?php echo isset($rows[0]['slug']) ? $rows[0]['slug'] : '' ; ?>" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit"><?php echo $btnText; ?></button>
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
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($lists as $key=>$val) { ?>
              <tr>
                <td class="text-center">
                  <input type="checkbox">
                </td>
                <td><?php echo $val['name']; ?></td>
                <td><?php echo $val['slug']; ?></td>
                <td class="text-center">
                  <a href="/admin/categories.php?action=edit&cat_id=<?php echo $val['id']; ?>" class="btn btn-info btn-xs">编辑</a>
                  <a href="/admin/categories.php?action=delete&cat_id=<?php echo $val['id']; ?>" class="btn btn-danger btn-xs">删除</a>
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
</body>
</html>
