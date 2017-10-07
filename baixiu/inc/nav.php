    <div class="topnav">
      <ul>
        <?php foreach($navs as $key=>$val) { ?>
          <li>
            <a href="<?php echo $val['link']; ?>" title="<?php echo $val['title']; ?>">
              <i class="<?php echo $val['icon']; ?>"></i>
              <?php echo $val['text']; ?>
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>
    <div class="header">
      <h1 class="logo">
        <a href="/"><img src="<?php echo $settings[1]['value']; ?>" alt=""></a>
      </h1>
      <ul class="nav">
        <?php foreach($navs as $key=>$val) { ?>
          <li>
            <a href="<?php echo $val['link']; ?>" title="<?php echo $val['title']; ?>">
              <i class="<?php echo $val['icon']; ?>"></i>
              <?php echo $val['text']; ?>
            </a>
          </li>
        <?php } ?>
      </ul>
      <div class="search">
        <form>
          <input type="text" class="keys" placeholder="输入关键字">
          <input type="submit" class="btn" value="搜索">
        </form>
      </div>
      <div class="slink">
        <a href="javascript:;">链接01</a> | <a href="javascript:;">链接02</a>
      </div>
    </div>