<?php
    

    // 导入配置文件
    require __DIR__ . '/config.php';

    // 开启 session
    session_start();
    
    // 检测登录
    function checkLogin() {
        // 如果读不到 user_info 这个 session 
        // 认为是未登录
        if(!isset($_SESSION['user_info'])) {
            header('Location: /admin/login.php');
            exit;
        }
    }

    // 封装连接数据操作
    function connect() {
        $connection = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);

        if(!$connection) {
            die('数据库连接失败！');

            // 等同于 echo 数据库连接失失败！ + return;
        }

        // 选择数据库
        mysqli_select_db($connection, DB_NAME);

        // 设置编码，防止乱码
        mysqli_set_charset($connection, DB_CHARSET);

        return $connection;
    }

    // 查询
    function query($sql) {
        // 连接数据库
        $connection = connect();

        // 结果集
        $result = mysqli_query($connection, $sql);

        // 将结果集转成数组
        $rows = fetch($result);

        return $rows;
    }

    // 插入
    function insert($table, $arr) {
        // 连接数据库
        $connection = connect();
        // 获得数组中所有的key
        $keys = array_keys($arr);
        // 获得数组中所有的value
        $values = array_values($arr);

        // 根据数组拼凑sql语句
        // INSERT INTO 表名 (字段名, 字段名) VALUES (值, 值)
        $sql = "INSERT INTO " . $table . " (" . implode(", ", $keys) . ") VALUES('" . implode("', '", $values) . "')";

        // echo $sql;exit;

        // 执行插入语句
        $result = mysqli_query($connection, $sql);

        // 返回插入结果
        return $result;
    }

    // 数据提取
    function fetch($result) {
        // 定义变量存放资源中取出的数据
        
        $rows = array();
        // 逐条从资源中取出数据
        // 当数据取完后，返回值为 null，则终止执行
        while($row = mysqli_fetch_assoc($result)) {
            // 将取出的数据存到数组中
            $rows[] = $row;
        }

        return $rows;
    }

    // 删除
    function delete($sql) {

        // DELETE FROM 表名 WHERE 条件
        $connection = connect();

        $result = mysqli_query($connection, $sql);

        return $result;
    }

    // 修改
    function update($table, $arr, $id) {
        // UPDATE 表名 set 字段名=值, 字段名=值...
        $connection = connect();

        $str = "";
        // 将关联数组处理成 字段名=值, 字段名=值... 格式
        foreach($arr as $key=>$val) {
            $str .= $key . "=" . "'" . $val . "', ";
        }

        // 截掉多余的 , 
        $str = substr($str, 0, -2);

        // 拼凑修改语句
        $sql = "UPDATE " . $table . " SET " . $str . " WHERE id=" . $id;

        // echo $sql;exit;
        // 执行语句
        $result = mysqli_query($connection, $sql);

        return $result;
    }
