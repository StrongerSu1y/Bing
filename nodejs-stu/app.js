
let http = require('http');

let url = require('url');

let path = require('path');

let fs = require('fs');

let db = require('./database/students.json');

let template = require('art-template');

// 配置模板引擎
template.defaults.root = './views';
template.defaults.extname = '.html';

let app = http.createServer();

app.listen(3000, (err) => {
    if(!err) {
        console.log('服务器已启动');
    }
})

app.on('request', (req, res) => {
    // 路径
    let {pathname} = url.parse(req.url);

    // 静态资源目录
    let realPath = path.join('public', pathname);

    // 对模板引擎进封装
    res.render = function (tpl, data) {

        let html = template(tpl, data);

        // res.write(html);
        res.end(html);
    }

    switch(pathname) {
        case '/':
        case '/add':
            res.render('add', {});
        break;

        case '/list':
            res.render('list', {list: db});
        break;

        case '/create':
            // 接收 get 参数
            let {query} = url.parse(req.url, true);

            // 追加数据
            db.push(query);

            // 写入文件
            fs.writeFile('./database/students.json', JSON.stringify(db), (err) => {
                if(!err) {
                    res.writeHead(302, {
                        // 跳转至列表页
                        'Location': '/list'
                    })
                }
                res.end();
            });
        break;

        default :
            // 处理静态资源
            fs.readFile(realPath, (err, data) => {
                if(!err) {
                    res.end(data);
                }
            })

    }

})