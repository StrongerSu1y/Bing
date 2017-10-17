<?php
header('Content-Type:application/json; charset=utf-8');
//获取接收到的数据 
$str = $_GET['data'];
//获取字符串的长度 
$strlen = strlen($str);
// 模拟的数据
$ab = array('1','1a',
'12','2c','3c','5c','7v','11','aa',
'b12','b2c','b3c','c5c','c7v','d11','daa',
'eb12','eb2c','eb3c','ec5c','ec7v','ed11','edaa'
);
// 新定义了一个数组，存符合满足条件的假数据;
$arr = array();
// 遍历我们的假数据
foreach($ab   as  $key=>$val ){
    if($str == mb_substr ($val,0,$strlen)){       
       $arr[] = $val;
    }

}
//对变量进行json编码
echo  json_encode($arr);

?>