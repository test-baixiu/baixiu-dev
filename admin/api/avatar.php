
<?php
require_once '../../config.php';

if (empty($_GET['email'])) {
        exit('缺少必要参数');
}
$email = $_GET['email'];

$conn = mysqli_connect(BX_DB_HOST, BX_DB_USER, BX_DB_PASS, BX_DB_NAME);
if (!$conn) {
        exit('数据库连接失败');
}

$sql = "select avatar from users where email ='{$email}' limit 1;";
$res = mysqli_query($conn, $sql);

if (!$res) {
        exit('查询失败');
}

$row = mysqli_fetch_assoc($res);

echo $row['avatar'];
