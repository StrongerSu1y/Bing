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

  // 取出最新文章
  $postSql = 'SELECT posts.id, posts.title, posts.created, users.nickname, categories.name, posts.content, posts.feature, posts.likes, posts.views FROM posts LEFT JOIN users ON posts.user_id=users.id LEFT JOIN categories ON posts.category_id = categories.id ORDER BY id DESC LIMIT 0, 10';

  $posts = query($postSql);

?>


<?php include './inc/head.php'; ?>
<body>
  <div class="wrapper">
    <?php include './inc/nav.php'; ?>
    <?php include './inc/aside.php'; ?>
    <div class="content">
      <div class="swipe">
        <ul class="swipe-wrapper">
          <?php foreach($slides as $key=>$val) { ?>
          <li>
            <a href="<?php echo $val['link']; ?>">
              <img src="<?php echo $val['image']; ?>">
              <span><?php echo $val['text']; ?></span>
            </a>
          </li>
          <?php } ?>
        </ul>
        <p class="cursor"><span class="active"></span><span></span><span></span><span></span></p>
        <a href="javascript:;" class="arrow prev"><i class="fa fa-chevron-left"></i></a>
        <a href="javascript:;" class="arrow next"><i class="fa fa-chevron-right"></i></a>
      </div>
      <div class="panel focus">
        <h3>焦点关注</h3>
        <ul>
          <li class="large">
            <a href="javascript:;">
              <img src="uploads/hots_1.jpg" alt="">
              <span>XIU主题演示</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_2.jpg" alt="">
              <span>星球大战：原力觉醒视频演示 电影票68</span>
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
              <span>又现酒窝夹笔盖新技能 城里人是不让人活了！</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="panel top">
        <h3>一周热门排行</h3>
        <ol>
          <li>
            <i>1</i>
            <a href="javascript:;">你敢骑吗？全球第一辆全功能3D打印摩托车亮相</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
          <li>
            <i>2</i>
            <a href="javascript:;">又现酒窝夹笔盖新技能 城里人是不让人活了！</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span class="">阅读 (18206)</span>
          </li>
          <li>
            <i>3</i>
            <a href="javascript:;">实在太邪恶！照亮妹纸绝对领域与私处</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
          <li>
            <i>4</i>
            <a href="javascript:;">没有任何防护措施的摄影师在水下拍到了这些画面</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
          <li>
            <i>5</i>
            <a href="javascript:;">废灯泡的14种玩法 妹子见了都会心动</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
        </ol>
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
      <div class="panel new">
        <h3>最新发布</h3>
        <?php foreach($posts as $key=>$val) { ?>
        <div class="entry">
          <div class="head">
            <span class="sort"><?php echo $val['name']; ?></span>
            <a href="/detail.php?pid=<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a>
          </div>
          <div class="main">
            <p class="info"><?php echo $val['nickname']; ?> 发表于 <?php echo $val['created']; ?></p>
            <p class="brief"><?php echo mb_substr($val['content'], 0, 100); ?></p>
            <p class="extra">
              <span class="reading">阅读(<?php echo $val['views']; ?>)</span>
              <span class="comment">评论(0)</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<?php echo $val['likes']; ?>)</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：<span><?php echo $val['name']; ?></span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="<?php echo $val['feature']; ?>" alt="">
            </a>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
    <?php include './inc/foot.php'; ?>
  </div>
  <script src="assets/vendors/jquery/jquery.js"></script>
  <script src="assets/vendors/swipe/swipe.js"></script>
  <script>
    //
    var swiper = Swipe(document.querySelector('.swipe'), {
      auto: 3000,
      transitionEnd: function (index) {
        // index++;

        $('.cursor span').eq(index).addClass('active').siblings('.active').removeClass('active');
      }
    });

    // 上/下一张
    $('.swipe .arrow').on('click', function () {
      var _this = $(this);

      if(_this.is('.prev')) {
        swiper.prev();
      } else if(_this.is('.next')) {
        swiper.next();
      }
    })
  </script>
</body>
</html>
