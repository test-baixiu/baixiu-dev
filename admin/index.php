<?php
// session_start();
// if (empty($_SESSION['is_login'])) {
//         header('Location: /admin/login.php');
// }
require dirname(__FILE__) . '/../functions.php';
bx_get_current_user();
// echo dirname(__FILE__);
// /var/www/baixiu-dev/admin
// /var/www/baixiu-dev/functions.php

$posts_count = bx_fetch_one('select count(1) from posts;');

// var_dump($posts_count[0]['count(1)']);
$posts_drafted_count = bx_fetch_one('select count(1) from posts where status = "drafted";');
// var_dump($posts_drafted_count);
$categories_count = bx_fetch_one('select count(1) from categories;');
$comments_count = bx_fetch_one('select count(1) from comments;');
$comments_held_count = bx_fetch_one('select count(1) from comments where status = "held";');
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
        <meta charset="utf-8">
        <title>Dashboard &laquo; Admin</title>
        <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
        <link rel="stylesheet" href="/static/assets/css/admin.css">
        <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>

<body>
        <script>
                NProgress.start()
        </script>
        <div class="main">
                <?php include 'includes/navbar.php'; ?>
                <div class="container-fluid">
                        <div class="jumbotron text-center">
                                <h1>One Belt, One Road</h1>
                                <p>Thoughts, stories and ideas.</p>
                                <p><a class="btn btn-primary btn-lg" href="post-add.php" role="button">写文章</a></p>
                        </div>
                        <div class="row">
                                <div class="col-md-4">
                                        <div class="panel panel-default">
                                                <div class="panel-heading">
                                                        <h3 class="panel-title">站点内容统计：</h3>
                                                </div>
                                                <ul class="list-group">
                                                        <li class="list-group-item"><strong><?php echo $posts_count['count(1)']; ?></strong>篇文章（<strong><?php echo $posts_drafted_count['count(1)']; ?></strong>篇草稿）</li>
                                                        <li class="list-group-item"><strong><?php echo $categories_count['count(1)']; ?></strong>个分类</li>
                                                        <li class="list-group-item"><strong><?php echo $comments_count['count(1)']; ?></strong>条评论（<strong><?php echo $comments_held_count['count(1)']; ?></strong>条待审核）</li>
                                                </ul>
                                        </div>
                                </div>
                                <div class="col-md-4">
                                        <canvas id="chart"></canvas>
                                </div>
                                <div class="col-md-4"></div>
                        </div>
                </div>
        </div>
        <?php $current_page = 'index'; ?>
        <?php include 'includes/sidebar.php'; ?>

        <script src="/static/assets/vendors/jquery/jquery.js"></script>
        <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
        <script src="/static/assets/vendors/chart/chart.js"></script>
        <script>
                var ctx = document.getElementById('chart').getContext('2d');
                var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'line',

                        // The data for our dataset
                        data: {
                                labels: ['posts', 'February', 'March', 'April', 'May', 'June', 'July'],
                                datasets: [{
                                        label: 'My First dataset',
                                        backgroundColor: 'rgb(255, 99, 132)',
                                        borderColor: 'rgb(255, 99, 132)',
                                        data: [<?php echo $posts_drafted_count['count(1)']; ?>, 10, 5, 2, 6, 3, 5]
                                }]
                        },

                        // Configuration options go here
                        options: {}
                });
        </script>
        <script>
                NProgress.done()
        </script>
</body>

</html>