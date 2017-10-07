<?php 

  require '../functions.php';

  checkLogin();

  // 设置导航菜单
  $cogs = array('menus', 'slides', 'settings');

  // 当前导航
  $active = 'menus';

  // 查询所有导航菜单
  $sql = 'SELECT `value` FROM options WHERE `key`="nav_menus"';
  $lists = query($sql);
  $json = $lists[0]['value'];

  // 导航是以 json 格式存储的，第二参数为 true ，强制转成数组
  $data = json_decode($json, true);

  // 获取操作方式
  $action = isset($_GET['action']) ? $_GET['action'] : 'add';

  if($action == 'delete') { // 删除操作
    // 根据超索引值删除元素
    $index = $_GET['index'];

    // 如果从数组中删除某个单元
    unset($data[$index]);

    // 将$data数组中的单元取出重新生成一个数组
    // （这里是为了解决 PHP 数组转 json 时格式问题）
    // 需要 [{},{}] 格式，而非 {"0": {}, "1": {}}
    $data = array_values($data);

    // 将PHP数组再次转成 json 进行重新存储
    // JSON_UNESCAPED_UNICODE 设置汉字不被编码
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);

    // 执行更新操作
    $result = update('options', array('value'=>$json), 9);

    if($result) {
      header('Location: /admin/menus.php');
      exit;
    }
  }

  if(!empty($_POST)) { // 添加操作

    // $_POST 是表单提交数据
    // 将表单数据追加到数组中，实现导航的添加
    $data[] = $_POST;

    // 导航在数据库中是以 json 格式存储的，
    // 所以要转成 json 后再存储
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);

    // 执行更新操作
    $result = update('options', array('value'=>$json), 9);

    if($result) {
      header('Location: /admin/menus.php');
      exit;
    }
  }

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Navigation menus &laquo; Admin</title>
  <?php include './inc/style.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>导航菜单</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
          <form action="/admin/menus.php" method="post">
            <h2>添加新导航链接</h2>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="title">标题</label>
              <input id="title" class="form-control" name="title" type="text" placeholder="标题">
            </div>
            <div class="form-group">
              <label for="title">图标</label>
              <input id="title" class="form-control" name="icon" type="text" placeholder="自定义图标">
            </div>
            <div class="form-group">
              <label for="href">链接</label>
              <input id="href" class="form-control" name="link" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添 加</button>
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
                <th>文本</th>
                <th>标题</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data as $key=>$val) { ?>
              <tr>
                <td class="text-center">
                  <input type="checkbox">
                </td>
                <td>
                    <i class="<?php echo $val['icon']; ?>"></i>
                    <?php echo $val['text']; ?>
                </td>
                <td><?php echo $val['title']; ?></td>
                <td><?php echo $val['link']; ?></td>
                <td class="text-center">
                  <a href="/admin/menus.php?action=delete&index=<?php echo $key; ?>" class="btn btn-danger btn-xs">删除</a>
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
