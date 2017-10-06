<?php
  
  require './functions.php';

  // 查询所有导航菜单
  $navs = query('SELECT `value` FROM options WHERE `key`="nav_menus"');
  // 导航菜单以 json 格式数据存储的，需要转成数组
  $navs = json_decode($navs[0]['value'], true);

  // 查询所有轮播图
  $slides = query('SELECT `value` FROM options WHERE `key`="home_slides"');

  // 轮播图以 json 格式数据存储的，需要转成数组
  $slides = json_decode($slides[0]['value'], true);

  // 查询网站设置信息
  $settings = query('SELECT * FROM options WHERE id<9');

  // 获取文章id
  // 根据 id 查询文章
  $rows = query('SELECT * FROM posts WHERE id=' . $_GET['pid']);

?>

<?php include './inc/head.php'; ?>
<body>
  <div class="wrapper">
    <?php include './inc/nav.php'; ?>
    <?php include './inc/aside.php'; ?>
    <div class="content">
      <div class="article">
        <div class="breadcrumb">
          <dl>
            <dt>当前位置：</dt>
            <dd><a href="javascript:;">奇趣事</a></dd>
            <dd>变废为宝！将手机旧电池变为充电宝的Better RE移动电源</dd>
          </dl>
        </div>
        <h2 class="title">
          <a href="javascript:;"><?php echo $rows[0]['title']; ?></a>
        </h2>
        <div class="meta">
          <span>DUX主题小秘 发布于 2015-06-29</span>
          <span>分类: <a href="javascript:;">奇趣事</a></span>
          <span>阅读: (2421)</span>
          <span>评论: (143)</span>
        </div>
        <div style="padding-top: 20px;font-size: 14px; color: #666; line-height: 2">
          <?php echo $rows[0]['content']; ?>
        </div>
      </div>
      <div class="panel hots">
        <h3>热门推荐</h3>
        <ul>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_2.jpg" alt="">
              <span>星球大战:原力觉醒视频演示 电影票68</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_3.jpg" alt="">
              <span>你敢骑吗？全球第一辆全功能3D打印摩托车亮相</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_4.jpg" alt="">
              <span>又现酒窝夹笔盖新技能 城里人是不让人活了！</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_5.jpg" alt="">
              <span>实在太邪恶！照亮妹纸绝对领域与私处</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <?php include './inc/foot.php'; ?>
  </div>
</body>
</html>
