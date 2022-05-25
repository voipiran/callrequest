<?php defined("INTERNAL_ACCESS") or die('Not Wise!'); ?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>پنل مدیریت | صفحه پیام ها</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/css/adminlte.min.css">
    <!-- bootstrap rtl -->
    <link rel="stylesheet" href="assets/css/bootstrap-rtl.min.css">
    <!-- template rtl version -->
    <link rel="stylesheet" href="assets/css/custom-style.css">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="logout.php" class="nav-link">خروج</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
                        class="fa fa-th-large"></i></a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">پنل مدیریت</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="settings.php" class="nav-link">
                                <i class="nav-icon fa fa-gear"></i>
                                <p>تنظیمات</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fa fa-file-text"></i>
                                <p>رشته ها</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>صصفحه پیام ها</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="breadcrumb-item active">صصفحه پیام ها</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <?php if (strlen($error) > 0) { ?>

                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?php echo $error ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <br>
            <?php } ?>

            <?php if (strlen($success) > 0) { ?>

                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?php echo $success ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <br>
            <?php } ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">ویرایشگر</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <?php if(!empty($result)){ ?>
                                <form method="post" name="edit-record" class="form-horizontal">
                                    <input type="hidden" name="edit-record">
                                    <div class="card-body">
                                        <?php foreach ($result as $row){ ?>

                                            <div class="form-group">
                                                <label for="input-<?php echo htmlspecialchars($row->prompt) ?>" class="col-sm-10 control-label"><?php echo htmlspecialchars($row->prompt) ?></label>

                                                <div class="col-sm-10">
                                                    <input value="<?php echo htmlspecialchars($row->text) ?>" type="text" name="<?php echo $row->prompt ?>" class="form-control" id="input-<?php echo htmlspecialchars($row->prompt) ?>" placeholder="مقدار <?php echo $row->prompt ?> را وارد کنید">
                                                </div>
                                            </div>
                                            <small class="col-sm-10"><?php echo htmlspecialchars($row->description) ?></small>
                                            <hr>
                                        <?php } ?>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-info">ثبت تغییرات</button>
                                    </div>
                                    <!-- /.card-footer -->
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">افزودن رشته</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->

                            <form method="post" class="form-horizontal">
                                <input type="hidden" name="new-record">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="input-new-parameter" class="col-sm-10 control-label">پارامتر</label>

                                        <div class="col-sm-10">
                                            <input type="text" name="new-prompt" class="form-control" id="input-new-parameter" placeholder="نام پارامتر را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-new-value" class="col-sm-10 control-label">مقدار</label>

                                        <div class="col-sm-10">
                                            <input type="text" name="new-text" class="form-control" id="input-new-value" placeholder="رشته را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-new-description" class="col-sm-10 control-label">توضیح</label>

                                        <div class="col-sm-10">
                                            <textarea name="new-description" class="form-control" id="input-new-description" placeholder="توضیح رشته را وارد کنید"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info">افزودن</button>
                                </div>
                                <!-- /.card-footer -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>&copy; <?php echo date('Y') ?> تمامی حقوق محفوظ است. سیستم درخواست تماس از طریق وب  <a target="_blank" href="https://www.voipiran.io </a>.</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="assets/js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<!-- SlimScroll -->
<script src="assets/js/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="assets/js/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="assets/js/demo.js"></script>
</body>
</html>
