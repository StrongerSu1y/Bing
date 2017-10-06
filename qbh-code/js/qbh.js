$(document).ready(function () {
    $('#fullpage').fullpage({
        sectionsColor: ['#fadd67', '#84a2d4', '#ef674d', '#ffeedd', '#d04759', '#84d9ed', '#4fdded', '#fff'],
        navigation: true,
        //锚点
        // anchors: ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth'],
        // 滚动到某一屏后的回调函数，接收 anchorLink 和 index 两个参数，
        //anchorLink 是锚链接的名称，index 是序号，从1开始计算
        afterLoad: function (anchorLink, index) {
            if (index == 2) {
                //搜索框显示并移动到中间
                $(".qbh-list .qbh-list-search").show().animate({
                    "right": 370
                }, 1000, function () {
                    //字体显示
                    $(".qbh-list .qbh-list-search-font").animate({
                        "opacity": 1
                    }, 1000, function () {
                        //search隐藏 换finish显示并移动右上角
                        $(".qbh-list .qbh-list-search").hide();
                        $(".qbh-list .qbh-list-search-finish").show().animate({
                            "bottom": 449,
                            "right": 250,
                            "height": 30,
                            // "left":'none'
                        });
                        //同时沙发显示并放大
                        $(".qbh-list .qbh-list-sofas").show().animate({
                            "height": 218
                        })
                        //文字变化
                        $(".qbh-list .qbh-list-wordb").animate({
                            "opacity": 1
                        })
                    })
                })
            }
        },

        // 滚动前的回调函数，接收 index、nextIndex 和 direction 3个参数：index 是离开的“页面”的序号，从1开始计算；
        // nextIndex 是滚动到的“页面”的序号，从1开始计算；
        // direction 判断往上滚动还是往下滚动，值是 up 或 down。
        onLeave:function(index,nextIndex,direction) {
            var wHeight = $(window).height();
            if(index == 2 && nextIndex == 3) {
                //第二屏沙发掉下
                $(".qbh-list .qbh-list-sofa-pic").show().animate({
                    //沙发定位bottom = -(浏览器高度-250)
                    "bottom":-(wHeight-250),
                    "right":522,
                    "width":204
                },1000,function(){
                    $(".qbh-buy .qbh-buy-choose-end").animate({
                        "opacity":1
                    })
                    $(".qbh-buy .qbh-buy-add-cart-end").animate({
                        "opacity":1
                    })
                })
            };

            //第四屏
            if(index == 3 && nextIndex == 4) {
                $(".qbh-list .qbh-list-sofa-pic").hide();
                $(".qbh-buy .qbh-buy-rotate-sofa").show().animate({
                    "bottom":-((wHeight-250)+50),
                    "right":260
                },1000,function(){
                    $(".qbh-buy .qbh-buy-rotate-sofa").hide();
                    $(".qbh-info .qbh-info-rotate-sofa").show();
                    $(".qbh-info .qbh-info-cart-box").animate({
                        "left":2000
                    },1500,function(){
                        $(".qbh-info .qbh-info-address").animate({
                            "opacity":1
                        },1000,function(){
                            $(".qbh-info .qbh-info-wordb").animate({
                                "opacity":1
                            })
                            $(".qbh-info .qbh-info-address-font").animate({
                                "opacity":1
                            })
                        })
                    })
                })
            }
        }
    })
})