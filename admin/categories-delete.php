<?php
require_once '../functions.php';

if (empty($_GET['id'])) {
        exit('错误');
        return;
}
$id = (int) $_GET['id'];
$rows = bx_execute("delete from categories where id = " . $id);
header("Location: /admin/categories.php");
