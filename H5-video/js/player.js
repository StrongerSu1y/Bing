// 功能
// 1.视频的播放与暂停 当在暂停状态 显示播放按钮  当在播放状态 显示暂停按钮
// 2.总时间的显示
// 3.当前时间的显示
// 4.进度条的显示
// 5.跳跃播放
// 6.全屏播放 MDN(查询各种方法的网站)

// 获取元素
var video = document.querySelector('video');
var playBtn = document.querySelector('.play-button');
var iconCircle = document.querySelector('.play-button i');
var sum = document.querySelector('.sum');
var current = document.querySelector('.current');
var progressBar = document.querySelector('.progress-bar');
var progress = document.querySelector('.progress');
var fullscreen = document.querySelector('.fullscreen');

var guanbi = document.querySelector('.guanbi');
var guanggao = document.querySelector('.guanggao');

// 1.视频的播放与暂停 当在暂停状态 显示播放按钮  当在播放状态 显示暂停按钮
// 步骤:
  // 1.1 获取元素  
  // 1.2 给按钮添加点击事件
playBtn.onclick = function(){
  // 1.3 判断是在暂停还是播放状态 获取在暂停状态的属性 paused
  if(video.paused) {
  // 1.4 如果是暂停状态 那么调用播放方法 play()
      video.play();
      iconCircle.classList.toggle("fa-pause-circle");
      guanggao.style.display = 'none';
      //删除类名 fa-pause-circle
  } else {
  // 1.5 如果是播放状态 那么调用暂停方法 pause();
      video.pause();
      iconCircle.classList.toggle("fa-pause-circle");

      guanggao.style.display = 'block';
  }
}
guanbi.onclick = function (){
  guanggao.style.display = 'none';  
}

//总时间显示
//当视频可以播放的时候 
video.oncanplay = function (){
    //获取视频总时间 duration
    // console.log(video.duration);  1570.32 -> 00:26:10
    //  2.4  把总时间变成小时h 分钟m 秒s
    var h = Math.floor(video.duration / 60 / 60);
    var m = Math.floor(video.duration / 60 % 60);
    var s = Math.floor(video.duration % 60);
    //  2.5  判断h,m,s是否大于9 如果大于9 那么显示自身 否则加0;
    h = h > 9 ? h : '0' + h;
    m = m > 9 ? m : '0' + m;
    s = s > 9 ? s : '0' + s;
    sum.innerHTML = h + ":" + m + ":" + s;
}

//当前时间显示
video.ontimeupdate = function (){
  // 3.3.获取当前时间   属性currentTime可以获取当前播放的进度(时间)
  //  3.4  把总时间变成小时h 分钟m 秒s
  var h = Math.floor(video.currentTime / 60 / 60);
  var m = Math.floor(video.currentTime / 60 % 60);
  var s = Math.floor(video.currentTime % 60);
  //  2.5  判断h,m,s是否大于9 如果大于9 那么显示自身 否则加0;
  h = h > 9 ? h : '0' + h;
  m = m > 9 ? m : '0' + m;
  s = s > 9 ? s : '0' + s;
  current.innerHTML = h + ":" + m + ":" + s;


  //进度条
  // 步骤:
  // 4.1.获取元素
  // 4.2.给progress-bar设置宽度 = 当前时间/总时间*100+'%';
  progressBar.style.width = video.currentTime / video.duration * 100 + '%';
}

//跳跃播放

//给progress添加点击事件
progress.onclick = function(e){
  //获取鼠标在progress上的点击的x轴的坐标  
  var x = e.offsetX;
  //获取进度条总宽度
  var pWidth = this.offsetWidth;
  //当前时间=鼠标在progress上的点击的x轴的坐标/进度条的总宽度*视频总时间
  video.currentTime = x / pWidth * video.duration;

}

//全屏
fullscreen.onclick = function(){
  video.webkitRequestFullScreen();
  video.webkitExitFullscreen();
}





