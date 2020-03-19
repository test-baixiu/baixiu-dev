<?php
require_once '../config.php';

function login()
{
        if (empty($_POST['email'])) {
                $GLOBALS['message'] = '你邮箱呢??';
                return;
        }
        if (empty($_POST['password'])) {
                $GLOBALS['message'] = '你密码呢??';
                return;
        }
        $email = $_POST['email'];
        $password = $_POST['password'];

        $conn = mysqli_connect(BX_DB_HOST, BX_DB_USER, BX_DB_PASS, BX_DB_NAME);
        $conn->query("SET NAMES utf8");

        if (!$conn) {
                exit('<h1>数据库连接失败</h1>');
        }

        $sql = "select * from users where email ='{$email}'";
        $query = mysqli_query($conn, $sql);

        if (!$query) {
                $GLOBALS['message'] = '登陆失败，请重试';
                return;
        }
        $user = mysqli_fetch_assoc($query);
        if (!$user) {
                $GLOBALS['message'] = '邮箱与密码不匹配';
                return;
        }
        if ($user['password'] != $password) {
                $GLOBALS['message'] = '邮箱与密码不匹配';
                return;
        }

        session_start();

        $_SESSION['is_login'] = $user;
        header('Location: /admin/index.php');
        exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        login();
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
        <meta charset="utf-8">
        <title>Sign in &laquo; Admin</title>
        <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="/static/assets/css/admin.css">
        <link rel="stylesheet" href="/static/assets/vendors/animate.css/animate.min.css">
        </link>
</head>

<body>
        <div class="login">
                <form class="login-wrap <?php echo isset($message) ? 'animated shake' : '' ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" novalidate autocomplete="off">
                        <img class="avatar" src="/static/assets/img/default.png">
                        <!-- 有错误信息时展示 -->
                        <?php if (isset($message)) : ?>
                                <div class="alert alert-danger">
                                        <strong>错误！</strong><?php echo $message; ?>
                                </div>
                        <?php endif; ?>
                        <div class="form-group">
                                <label for="email" class="sr-only">邮箱</label>
                                <input id="email" type="email" name="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                        </div>
                        <div class="form-group">
                                <label for="password" class="sr-only">密码</label>
                                <input id="password" type="password" name="password" class="form-control" placeholder="密码">
                        </div>
                        <button class="btn btn-primary btn-block">登 录</button>
                </form>
        </div>
        <script src="/static/assets/vendors/jquery/jquery.js"></script>
        <script>
                $(function($) {

                        var emailFormat = /^[a-zA-Z0-9]+@+[a-zA-Z0-9]+\.+[a-zA-Z0-9]+$/;

                        $('#email').blur(function() {

                                var value = $(this).val();
                                if (!value || !emailFormat.test(value)) return

                                $.get('/admin/api/avatar.php', {
                                        email: value
                                }, function(res) {
                                        $avatar_img = $('.avatar').attr('src');
                                        if (!res || res === $avatar_img) return;
                                        $('.avatar').fadeOut(function() {
                                                $(this).on('load', function() {
                                                        $(this).fadeIn()
                                                }).attr('src', res)
                                        })
                                })
                        })
                });

                // 单独作用域，确保页面加载完后执行
                // 目标：在用户输入完邮箱后，拿到页面上展示这个邮箱对应的头像
                // 实现：
                //      - 时机：邮箱文本框失去焦点，并且能够拿到邮箱
                //      - 事情：获取这个文本框中的邮箱对应的头像地址，展示到页面上
        </script>
</body>

</html>