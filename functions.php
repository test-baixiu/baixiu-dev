<?php

/**
 * 封装公共的函数
 * 
 */
require_once 'config.php';



/***
 * 获取当前用户信息，如果没有，则直接跳转到登陆页面
 */
function bx_get_current_user()
{
        // if (isset($GLOBALS['is_login'])) {
        //         return $GLOBALS['is_login'];
        // }
        session_start();
        if (empty($_SESSION['is_login'])) {
                header('Location: /admin/login.php');
                exit(); ///不需要执行后面的代码
        }
        // $GLOBALS['is_login'] = $_SESSION['is_login'];
        return $_SESSION['is_login'];
}

/**
 * 通过数据库查询，来获取数据
 * 索引数组套关联数组
 */
function bx_fetch($sql)
{
        $conn = mysqli_connect(BX_DB_HOST, BX_DB_USER, BX_DB_PASS, BX_DB_NAME);
        $conn->query("SET NAMES utf8");
        if (!$conn) {
                exit('数据库连接失败');
        }

        $query = mysqli_query($conn, $sql);
        if (!$query) {
                return false;
        }
        while ($row = mysqli_fetch_assoc($query)) {
                $result[] = $row;
        }

        // 清除获取的数据，断开连接
        mysqli_free_result($query);
        mysqli_close($conn);

        return $result;
}
/**
 * 获取单条数据
 * 关联数组
 */
function bx_fetch_one($sql)
{
        $res = bx_fetch($sql);
        return isset($res) ? $res[0] : null;
        // $conn = mysqli_connect(BX_DB_HOST, BX_DB_USER, BX_DB_PASS, BX_DB_NAME);
        // if (!$conn) {
        //         exit('连接数据库失败');
        // }
        // $query = mysqli_query($conn, $sql);
        // if (!$query) {
        //         return false; //查询失败
        // }
        // return mysqli_fetch_assoc($query);
}

// 执行一个增删改语句
function bx_execute($sql)
{
        $conn = mysqli_connect(BX_DB_HOST, BX_DB_USER, BX_DB_PASS, BX_DB_NAME);
        $conn->query("SET NAMES utf8");
        if (!$conn) {
                exit('数据库连接失败');
        }

        $query = mysqli_query($conn, $sql);
        if (!$query) {
                return false;
        }

        $affected_rows = mysqli_affected_rows($conn);
        mysqli_close($conn);

        return $affected_rows;
}
