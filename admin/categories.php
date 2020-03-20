<?php
require dirname(__FILE__) . '/../functions.php';
bx_get_current_user();


function add_categories()
{
        if (empty($_POST['name']) || empty($_POST['slug'])) {
                $GLOBALS['message'] = '请完整填写表单';
                return;
        }
        $name = $_POST['name'];
        $slug = $_POST['slug'];
        $rows = bx_execute("insert into categories value (null,'{$name}','{$slug}');");
        $GLOBALS['message'] = $rows <= 0 ? '添加失败' : '添加成功';
}
function edit_categories()
{
        if (empty($_POST['name']) || empty($_POST['slug'])) {
                $GLOBALS['message'] = '请完整填写表单';
                return;
        }
        $name = $_POST['name'];
        $slug = $_POST['slug'];
        $rows = bx_execute("update categories set name='{$name}',slug='{$slug}' where id = '{$_GET['id']}';");
        $GLOBALS['message'] = $rows <= 0 ? '添加失败' : '添加成功';
}
//修改逻辑
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_GET['id'])) {
                add_categories();
        } else {
                edit_categories();
        }
}

if (!empty($_GET['id'])) {
        $current_edit_category = bx_fetch_one('select * from categories where id = ' . $_GET['id']);
}

//全部分类的数组
$categories = bx_fetch('select * from categories;');
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
        <meta charset="utf-8">
        <title>Categories &laquo; Admin</title>
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
                        <div class="page-title">
                                <h1>分类目录</h1>
                        </div>
                        <!-- 有错误信息时展示 -->
                        <?php if (isset($message)) : ?>
                                <div class="alert <?php echo $message === '添加成功' ? 'alert-success' : 'alert-danger' ?>">
                                        <strong><?php echo $message === '添加成功' ? '成功！' : '错误！' ?></strong><?php echo $message; ?>
                                </div>
                        <?php endif ?>
                        <div class="row">
                                <div class="col-md-4">
                                        <?php if (isset($current_edit_category)) : ?>
                                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $current_edit_category['id']; ?>" method="post" autocomplete="off" novalidate>
                                                        <h2>修改《<?php echo $current_edit_category['name']; ?>》</h2>
                                                        <div class="form-group">
                                                                <label for="name">名称</label>
                                                                <input id="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo $current_edit_category['name']; ?>">
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="slug">别名</label>
                                                                <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo $current_edit_category['slug']; ?>">
                                                                <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
                                                        </div>
                                                        <div class="form-group">
                                                                <button class="btn btn-primary" type="submit">添加</button>
                                                        </div>
                                                </form>
                                        <?php endif ?>
                                        <!-- <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off" novalidate>
                                                <h2>添加新分类目录</h2>
                                                <div class="form-group">
                                                        <label for="name">名称</label>
                                                        <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
                                                </div>
                                                <div class="form-group">
                                                        <label for="slug">别名</label>
                                                        <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
                                                        <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
                                                </div>
                                                <div class="form-group">
                                                        <button class="btn btn-primary" type="submit">添加</button>
                                                </div>
                                        </form> -->
                                </div>
                                <div class="col-md-8">
                                        <div class="page-action">
                                                <!-- show when multiple checked -->
                                                <a id="delete_more" class="btn btn-danger btn-sm" href="/admin/categories-delete.php" style="display: none;">批量删除</a>
                                        </div>
                                        <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                        <tr>
                                                                <th class="text-center" width="40"><input id="checkAll" type="checkbox"></th>
                                                                <th>名称</th>
                                                                <th>Slug</th>
                                                                <th class="text-center" width="100">操作</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                        <?php foreach ($categories as $item) : ?>
                                                                <tr>
                                                                        <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
                                                                        <td><?php echo $item['name']; ?></td>
                                                                        <td><?php echo $item['slug']; ?></td>
                                                                        <td class="text-center">
                                                                                <a href="categories.php?id=<?php echo $item['id']; ?>" class="btn btn-info btn-xs">编辑</a>
                                                                                <a href="categories-delete.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">删除</a>
                                                                        </td>
                                                                </tr>
                                                        <?php endforeach ?>
                                                </tbody>
                                        </table>
                                </div>
                        </div>
                </div>
        </div>

        <?php include 'includes/sidebar.php'; ?>

        <script src="/static/assets/vendors/jquery/jquery.js"></script>
        <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
        <script>
                //不要重复使用无意义的选择器，应该采用变量使其本地化
                $(function($) {
                        var $tbodyCheckBoxs = $('tbody input');
                        var $btn_detele = $('#delete_more');
                        var $checkAll = $('#checkAll');
                        var allCheckeds = [];
                        $tbodyCheckBoxs.on('change', function() {
                                var id = $(this).data('id');
                                //h5中，自定义属性，会保存在dataset中：var id = this.dataset['id'];
                                //jq获取元素，使用attr去获取设置的id属性： $(this).attr(''data-id');
                                //jq中的data函数 : $(this).data('id');

                                if ($(this).prop('checked')) {
                                        allCheckeds.push(id);
                                } else {
                                        allCheckeds.splice(allCheckeds.indexOf(id), 1);
                                }
                                // console.log(allCheckeds);
                                // 判断目前数组中是否有选择了的，如果有，则显示批量删除的按键
                                allCheckeds.length ? $btn_detele.fadeIn() : $btn_detele.fadeOut();
                                // $btn_detele.attr('href', "/admin/categories-delete.php?id=" + allCheckeds.join(","));
                                // $btn_detele.attr('href', "/admin/categories-delete.php?id=" + allCheckeds);
                                // console.log("/admin/categories - delete.php ? id = " + allCheckeds.join(", "));
                                $btn_detele.prop('search', '?id=' + allCheckeds);
                        })




                        /*
                        version1
                         */
                        // var $checkAll = $('#checkAll');
                        // $checkAll.on('change', function() {
                        //         if ($checkAll.prop('checked')) {
                        //                 $tbodyCheckBoxs.each(function(i, item) {
                        //                         $(item).prop('checked', 'checked');
                        //                 })
                        //                 $btn_detele.fadeIn();
                        //         } else {
                        //                 $tbodyCheckBoxs.each(function(i, item) {
                        //                         $(item).prop('checked', '');
                        //                 })
                        //                 $btn_detele.fadeOut();
                        //         }
                        // })
                        // //多选框如果出现了改变，则执行内置函数
                        // $tbodyCheckBoxs.on('change', function() {
                        //         var flag = false;
                        //         $tbodyCheckBoxs.each(function(i, item) {
                        //                 // console.log($(item).prop('checked'));
                        //                 if ($(item).prop('checked')) {
                        //                         flag = true;
                        //                 }
                        //         })
                        //         flag ? $btn_detele.fadeIn() : $btn_detele.fadeOut();
                        // });
                })
        </script>
        <script>
                NProgress.done()
        </script>
</body>

</html>