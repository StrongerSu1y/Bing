<?php

  require '../functions.php';

  checkLogin();

  // 定义二级导航
  $actives = array('category', 'post', 'posts');
  // 定义导航状态
  $active = 'posts';

  // 假设有 102 条数据
  $sql = 'SELECT count(*) AS total FROM posts';
  $total = query($sql)[0]['total'];

  // 假定 每页 显示 5 条
  $pageSize = 10;

  // 根据上述两个值可以确定总共有多少页
  $pageCount = ceil($total / $pageSize);

  // 总显示多少个页码（页码长度）
  // 假定 6 个长度
  $pageLimit = 6;

  // 2  3  4  5  6  7
  // 4  5  6  7  8  9
  // 6  7  8  9  10

  // 获得用户操作的当前页码
  $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

  // 上一页
  $prevPage = $currentPage - 1;

  // 下一页
  $nextPage = $currentPage + 1;

  // 根据当前页码计算页码的起点
  $start = $currentPage - floor($pageLimit / 2);

  // 判断起点的边界不能小于1
  if($start < 1) {
      $start = 1;
  }

  // 根据页码的起点计算终点（长度是固定的）
  $end = $start + $pageLimit - 1;

  // 判断终点的边界值不能大小总的页数
  if($end >= $pageCount) {
      $end = $pageCount;

      // 当页码终点确定后
      // 为了保证长度固定，可以根据终点
      // 重新计算起点
      $start = $end - $pageLimit + 1;

      // 同样需要判断起点边界不能小于1
      if($start < 1) {
          $start = 1;
      }
  }

  // print_r(range(1, $pageCount));
  // 所有可能的页码
  $pages = range($start, $end);

  $offset = ($currentPage - 1) * $pageSize;

  // 联表查询数据
  // 页面需要展示的数据来自于多张表
  $sql = 'SELECT posts.id, posts.title, posts.created, posts.status, users.nickname, categories.name FROM posts LEFT JOIN users ON posts.user_id=users.id LEFT JOIN categories ON posts.category_id = categories.id LIMIT ' . $offset . ', ' . $pageSize;

  // 结果
  $lists = query($sql);

  // 删除
  if($_GET['action'] == 'delete') {
    $sql = 'DELETE FROM posts WHERE id=' . $_GET['pid'];

   $result = delete($sql);

   if($result) {
    header('Location: /admin/posts.php?page=' . $currentPage);
    exit;
   }
  }

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <?php include './inc/style.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <form class="form-inline">
          <select name="" class="form-control input-sm">
            <option value="">所有分类</option>
            <option value="">未分类</option>
          </select>
          <select name="" class="form-control input-sm">
            <option value="">所有状态</option>
            <option value="">草稿</option>
            <option value="">已发布</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <?php if($currentPage > 1) { ?>
            <li>
              <a href="/admin/posts.php?page=<?php echo $prevPage; ?>"> 上一页
              </a>
            </li>
          <?php } ?>

          <?php foreach($pages as $key=>$val) { ?>
            <?php if($currentPage == $val) { ?>
            <li class="active" >
                <a href="/admin/posts.php?page=<?php echo $val; ?>">
                  <?php echo $val; ?>
                </a>
            </li>
            <?php } else { ?>
            <li>
                <a href="/admin/posts.php?page=<?php echo $val; ?>">
                  <?php echo $val; ?>
                </a>
            </li>
            <?php } ?>
          <?php } ?>

          <?php if($currentPage < $pageCount) { ?>
            <li>
              <a href="/admin/posts.php?page=<?php echo $nextPage; ?>">
                下一页
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="80">序号</th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($lists as $key=>$val) { ?>
          <tr>
            <td class="text-center">
              <?php echo $key+1; ?>
            </td>
            <td><?php echo $val['title']; ?></td>
            <td><?php echo $val['nickname']; ?></td>
            <?php if(empty($val['name'])) { ?>
            <td>未分类</td>
            <?php } else { ?>
            <td><?php echo $val['name']; ?></td>
            <?php } ?>
            <td class="text-center"><?php echo $val['created']; ?></td>
            <?php if($val['status'] == 'published') { ?>
            <td class="text-center">已发布</td>
            <?php } else { ?>
            <td class="text-center">草稿</td>
            <?php } ?>
            <td class="text-center">
              <a href="/admin/post.php?action=edit&pid=<?php echo $val['id']; ?>" class="btn btn-default btn-xs">编辑</a>
              <a href="/admin/posts.php?page=<?php echo $currentPage; ?>&action=delete&pid=<?php echo $val['id']; ?>" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php include './inc/aside.php'; ?>
  <?php include './inc/script.php'; ?>
</body>
</html>
