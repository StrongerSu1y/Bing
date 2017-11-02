
function jQuery() {

    return {
        css: function () {
            console.log('我是 css 方法')
        },
        attr: function () {}
    }
}

var $ = jQuery;

define(function () {
    return jQuery;
})