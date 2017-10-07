$(document).ready(function () {
    //qbh-list-search-finish没有运动
    var flag = false;
    var flag1 = false;
    var flag2 = false;
    
    $('#fullpage').fullpage({
        sectionsColor: ['#fadd67', '#84a2d4', '#ef674d', '#ffeedd', '#d04759', '#84d9ed', '#4fdded', '#fff'],
        navigation: true,
        //锚点
        // anchors: ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth'],
        // 滚动到某一屏后的回调函数，接收 anchorLink 和 index 两个参数，
        //anchorLink 是锚链接的名称，index 是序号，从1开始计算
        afterLoad: function (anchorLink, index) {
            if(index == 1) {
                $(".down").fadeIn();
            }

            if (index == 2 && flag == false) {
                //搜索框显示并移动到中间
                $(".qbh-list .qbh-list-search").show().animate({
                    "right": 370
                }, 500, function () {
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
                        },1000,function(){
                            flag = true;
                        });
                        //同时沙发显示并放大
                        $(".qbh-list .qbh-list-sofas").show().animate({
                            "height": 218
                        },1000)
                        //文字变化
                        $(".qbh-list .qbh-list-wordb").animate({
                            "opacity": 1
                        },function (){
                            $(".down").fadeIn();
                        })
                    })
                })
            }

            if(index == 8) {
                //获取鼠标位置
                $(document).mousemove(function (ev) {
                    var mX = ev.pageX;
                    var mY = ev.pageY+10;
                    $(".qbh-shopping .qbh-shopping-hands").css({
                        left:mX,
                        top:mY
                    })
                })
                $(".qbh-shopping .qbh-shopping-start").mouseenter(function(){
                    $(".qbh-shopping .qbh-shopping-dong").show();
                })
                $(".qbh-shopping .qbh-shopping-dong").mouseleave(function(){
                    $(".qbh-shopping .qbh-shopping-dong").hide();
                })
            }
        },

        // 滚动前的回调函数，接收 index、nextIndex 和 direction 3个参数：index 是离开的“页面”的序号，从1开始计算；
        // nextIndex 是滚动到的“页面”的序号，从1开始计算；
        // direction 判断往上滚动还是往下滚动，值是 up 或 down。
        onLeave:function(index,nextIndex,direction) {
            $(".down").fadeOut();
            var wHeight = $(window).height();

            if(index == 2 && nextIndex == 3 && flag == true) {
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
                    },function(){
                        $(".down").fadeIn();
                    })
                })
            };

            //第四屏
            if(index == 3 && nextIndex == 4 && flag1 == false) {
                $(".qbh-list .qbh-list-sofa-pic").hide();
                $(".qbh-buy .qbh-buy-rotate-sofa").show().animate({
                    "bottom":-((wHeight-250)+50),
                    "right":260
                },1000,function(){
                    flag1 = true;
                    $(".qbh-buy .qbh-buy-list-sofa").show();            
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
                            },function(){
                                $(".down").fadeIn();                        
                            })
                        })
                    })
                })
            }

            //第五屏
            if(index == 4) {
                $(".qbh-payment .qbh-payment-hands").show().animate({
                    "bottom":0
                },1000,function(){
                    $(".qbh-payment .qbh-payment-mouse-end").animate({
                        "opacity":1
                    },10,function(){
                        //沙发掉下
                        $(".qbh-payment .qbh-payment-rotate-sofa-start").show().animate({
                            "bottom":245
                        },800,function(){
                            //沙发下移出现
                            $(".qbh-payment .qbh-payment-rotate-sofa-end").animate({
                                "bottom":70
                            },500)
                            //账单上移
                            $(".qbh-payment .qbh-payment-bill").animate({
                                "bottom":378
                            },500,function(){
                                $(".down").fadeIn();                        
                            })
                        })
                    })
                })
            }

            //第五屏到第六屏
            if(index == 5 && nextIndex == 6 && flag2 == false) {
                flag2 = true;
                //沙发掉落
                $(".qbh-payment .qbh-payment-rotate-sofa-continue").show().animate({
                    "bottom":-255,
                    "width":80
                },900);
                //盒子接住
                $(".qbh-delivery .qbh-delivery-box").show().animate({
                    "left":356,
                    "bottom":435
                },800,function() {
                    $(".qbh-payment .qbh-payment-rotate-sofa-continue").hide();
                    //盒子落车
                    $(".qbh-delivery .qbh-delivery-box").animate({
                        "left":485,
                        "bottom":56,
                        "width":40
                    },800,function(){
                        //盒子隐藏
                        $(".qbh-delivery .qbh-delivery-box").hide();
                        //背景图移动
                        $(".qbh-delivery").animate({
                            "backgroundPositionX":'100%'
                        },2000,function(){
                            //标语
                            $(".qbh-delivery .qbh-delivery-font-end").animate({
                                "opacity":1
                            })
                            //送货员显示
                            $(".qbh-delivery .qbh-delivery-deliveryman").animate({
                                "width":252,
                                "bottom":117
                            },1000,function() {
                                //快递员移动
                                $(".qbh-delivery .qbh-delivery-deliveryman").animate({
                                    "right":600
                                },1000,function(){
                                    //收货提示
                                    $(".qbh-delivery .qbh-delivery-shouhuo").show();
                                    //门打开
                                    $(".qbh-delivery .qbh-delivery-door").show();
                                    //买家
                                    $(".qbh-delivery .qbh-delivery-buyer").animate({
                                        "width":87
                                    },function(){
                                        $(".down").fadeIn();                        
                                    })
                                })
                            })
                        })
                        //商家标语
                        $(".qbh-delivery .qbh-delivery-shangjia").animate({
                            "opacity":1
                        },800);
                        $(".qbh-delivery .qbh-delivery-truck-font").animate({
                            "opacity":1
                        },1500);
                    })
                })
            }

            //第六屏到第七屏
            if(index == 6 && nextIndex == 7) {
                $(".qbh-appraise .qbh-appraise-star").animate({
                    "width":100
                },1500,function() {
                    $(".qbh-appraise .qbh-appraise-haoping").show().animate({
                        "width":72
                    },500,function(){
                        $(".down").fadeIn();                        
                    })
                })
            }
        }
    })
})