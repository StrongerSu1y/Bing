<?php
  
  // 包含功能文件
  require '../functions.php';

  // 检测登录
  checkLogin();

  // 定义导航状态
  $active = 'users';

  $message = '';
  $title = '添加用户';
  $btnText = '添加';

  // 获取地址参数
  $action = isset($_GET['action']) ? $_GET['action'] : 'add';

  if(!empty($_POST)) {

    // 添加
    if($action == 'add') {
      // 以post 提交并且提交了数据
      
      // print_r($_POST);
      
      // 获取用户提交的数据
      // $slug = $_POST['slug'];

      // $email = $_POST['email'];

      // $password = $_POST['password'];

      // $status = 'unactivated';
      
      // 笨方法插入操作
      // $result = insert('INSERT INTO users (id, slug, email, password, status) VALUES(null, "'.$slug.'", "'.$email.'", "'.$password.'", "'.$status.'")');
      
      // 为数组添加新的单元
      $_POST['status'] = 'unactivated';
      // 执行插入操作（新方法替换上述插入操作）
      $result = insert('users', $_POST);

      // 插入成功
      if($result) {
        header('Location: /admin/users.php');
      } else { // 插入失败
        $message = '添加新用户失败!';
      }
    }

    // 更新
    if($action == 'update') {
      // 获取用户id，根据用户id对数据进行修改
      $id = $_POST['id'];

      // id 字段是主键，不可被修改
      // 所以要从数组中将其删除
      unset($_POST['id']);

      // 执行修改操作
      $result = update('users', $_POST, $id);

      if($result) {
        header('Location: /admin/users.php');
        exit;
      }
    }

    // 批量删除
    if($action == 'deleteAll') {
      // echo json_encode($_POST);
      // 
      // print_r($_POST['ids']);
      // DELETE FROM users WHERE id in (1,  3, 5);
      // 拼凑语句
      $sql = 'DELETE FROM users WHERE id in (' . implode(',',  $_POST['ids']) . ')';

      // 删除
      $result = delete($sql);

      // 响应头设置，jQuery自动解析 json
      header('Content-Type: applicaion/json');

      if($result) { // 成功提示信息
        $info = array('code'=>10000, 'message'=>'删除成功!');

        echo json_encode($info);
      } else {
        // 失败提示信息
        $info = array('code'=>10001, 'message'=>'删除失败!');

        echo json_encode($info);
      }

      exit;
    }
  }

  // 查询所有用户
  $lists = query('SELECT * FROM users');

  // 获得用户id
  $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

  if($action == 'edit') { // 编辑

    // 操作名
    $action = 'update';
    $title = '编辑用户';
    $btnText = '更新';

    // 查询结果
    $rows = query('SELECT * FROM users WHERE id=' . $user_id);

  } else if($action == 'delete') { // 删除
    // 执行删除操作
    $result = delete('DELETE FROM users WHERE id=' . $user_id);
    
    if($result) { // 删除成功
      header('Location: /admin/users.php');
      exit;
    }
  }

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
  <?php include './inc/style.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($message)) { ?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $message; ?>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col-md-4">
          <form action="/admin/users.php?action=<?php echo $action; ?>" method="post">
            <h2><?php echo $title; ?></h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <?php if($action != 'add') { ?>
              <input type="hidden" name="id" value="<?php echo $rows[0]['id']; ?>">
              <?php } ?>
              <input id="email" class="form-control" name="email" type="email" value="<?php echo isset($rows[0]['email']) ? $rows[0]['email'] : ''; ?>" placeholder="邮箱">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" value="<?php echo isset($rows[0]['slug']) ? $rows[0]['slug'] : ''; ?>" placeholder="slug">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" value="<?php echo isset($rows[0]['nickname']) ? $rows[0]['nickname'] : ''; ?>" type="text" placeholder="昵称">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="text" value="<?php echo isset($rows[0]['password']) ? $rows[0]['password'] : ''; ?>" placeholder="密码">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit"><?php echo $btnText; ?></button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm delete" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40">
                  <input type="checkbox" id="toggle">
                </th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($lists as $key=>$val) { ?>
              <tr>
                <td class="text-center">
                  <input type="checkbox" value="<?php echo $val['id']; ?>" class="chk">
                </td>
                <td class="text-center"><img class="avatar" src="<?php echo $val['avatar']; ?>">
                <td><?php echo $val['email']; ?></td>
                <td><?php echo $val['slug']; ?></td>
                <td><?php echo $val['nickname']; ?></td>
                <?php if($val['status'] == 'activated') { ?>
                <td>已激活</td>
                <?php } else if($val['status'] == 'unactivated') { ?>
                <td>未激活</td>
                <?php } else if($val['status'] == 'forbidden') { ?>
                <td>已禁用</td>
                <?php } else { ?>
                <td>已删除</td>
                <?php } ?>
                <td class="text-center">
                  <a href="/admin/users.php?action=edit&user_id=<?php echo $val['id']; ?>" class="btn btn-default btn-xs">编辑</a>
                  <a href="/admin/users.php?action=delete&user_id=<?php echo $val['id']; ?>" class="btn btn-danger btn-xs">删除</a>
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
    $('#toggle').on('click', function () {
      // this 原生DOM 
      // console.log(this.checked);
      // 全选/取消全选
      if(this.checked) { // 全选
        $('.chk').prop('checked', true);
        $('.delete').show();
      } else { // 取消全选
        $('.chk').prop('checked', false);
        $('.delete').hide();
      }

      // toggle = show / hide
      // $('.delete').toggle();
    })

    // 单个选择
    $('.chk').on('change', function () {
      // 获得当前选中用户的个数
      var size = $('.chk:checked').size();
      // 如果大于0则显示批量按钮
      if(size > 0) {
        $('.delete').show();
        return;
      }

      // 如果小于等于0则隐藏批量按钮
      $('.delete').hide();
    })

    // 批量删除操作
    $('.delete').on('click', function () {
      // 获得所有被选中用户的 id
      var ids = [];
      $('.chk:checked').each(function () {
        // console.log($(this).val());
        ids.push($(this).val());
      });

      // 发送 ajax 请求
      $.ajax({
        url: '/admin/users.php?action=deleteAll',
        type: 'post',
        // 将所有的选中的用户的 id 传给后端
        data: {ids : ids},
        success: function (info) {
          // console.log(info);
          // 提示信息
          alert(info.message);

          if(info.code == 10000) {
            // 成功后刷新当前页
            location.reload();
          }
        }
      });
    })
  </script>
</body>
</html>
