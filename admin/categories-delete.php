<?php
require_once '../functions.php';

if (empty($_GET['id'])) {
        exit('错误');
        return;
}
$id =  $_GET['id'];
$rows = bx_execute('delete from categories where id in( ' . $id . ');');
header("Location: /admin/categories.php");
