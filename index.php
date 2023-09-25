<?PHP
ob_start();
session_start();

require_once __DIR__ . '/config/connect_db.inc.php';
require_once __DIR__ . '/include/class_crud.inc.php';
require_once __DIR__ . '/include/function.inc.php';
require_once __DIR__ . '/include/setting.inc.php';
require_once __DIR__ . '/include/timer.inc.php';

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set(Setting::$AppTimeZone);
$Time = new Processing;
$start = $Time->Start_Time();

isset($_REQUEST['module']) ? $module = $_REQUEST['module'] : $module = '';

switch ($module) {
    case "errorCode" :
        $include_module = __DIR__ . "/module/dashboard.inc.php";
        $action = "errorCode";
        $module == "errorCode" ? $active_errorCode = "active" : $active_errorCode = "";
        break;
    case "errorMachine" :
        $include_module = __DIR__ . "/module/dashboard.inc.php";
        $action = "errorMachine";
        $module == "errorMachine" ? $active_errorMachine = "active" : $active_errorMachine = "";
        break;
    case "MachineDetails" :
        $include_module = __DIR__ . "/module/dashboard.inc.php";
        $action = "MachineDetails";
        $module == "MachineDetails" ? $active_MachineDetails = "active" : $active_MachineDetails = "";
        break;
    case "errorDetails" :
        $include_module = __DIR__ . "/module/table.inc.php";
        $action = "errorDetails";
        $module == "errorDetails" ? $active_errorDetails = "active" : $active_errorDetails = "";
        break;
    default:
        $include_module = __DIR__ . "/module/dashboard.inc.php";
        $action = "errorLog";
        $module == "dashboard" || $module == "" ? $active_errorlog = "active" : $active_errorlog = ""; #ไฮไลท์เมนูด้านซ้าย
      break;
}
$title_site = Setting::$title_site[$action];
$title_act = Setting::$title_act[$action];
$breadcrumb_txt = Setting::$breadcrumb_txt[$action];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include( __DIR__ . "/header.php"); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <!--sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed sidebar-closed sidebar-collapse layout-navbar-fixed-->
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>

       <?php include( __DIR__ . "/navbar.php"); ?>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color:#00387c;">
            <!-- Brand Logo -->
            <a href="./" class="brand-link">
                <img src="dist/img/logo_2.png" alt="JWD Logo" class="w-100 p-0 m-0">
                <!--<img src="dist/img/logo_2.png" alt="JWD Logo" class="brand-image brand-text" >-->
                <span class="font-weight-bold p-1 mt-2 text-pcs-ct">
                    <?PHP echo $title_site; ?></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar"><br><br>
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-1 mb-3 d-flex">
                    <div class="image">
                        <img src="dist/img/user2-160x160.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            <?PHP echo "ยังไม่เสร็จ _SESSION['sess_fullname']"; ?></a>
                        <span class="text-white">ระดับ:
                            <?PHP echo "ยังไม่เสร็จ classArr[_SESSION['sess_class_user']];" ?> /
                            <?PHP echo "ยังไม่เสร็จ _SESSION['sess_dept_initialname'];" ?></span>
                        <a href="?module=profile" class="d-block text-yellow">[แก้ไขข้อมูลส่วนตัว]</a>
                    </div>
                </div>

                <!-- Sidebar Menu active-->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="./" class="nav-link <?PHP echo $active_errorlog; ?>">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>Warehouse</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?module=errorMachine" class="nav-link <?PHP echo $active_errorMachine;?>">
                                <i class="nav-icon fas fa-truck-loading"></i> 
                                <p>Machine</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?module=errorCode" class="nav-link <?PHP echo $active_errorCode;?>">
                                <i class="nav-icon fas fa-exclamation"></i> 
                                <p>Error Name/Code</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?module=errorDetails" class="nav-link <?PHP echo $active_errorDetails;?>">
                                <i class="nav-icon fas fa-table"></i> 
                                <p>Error Log Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?module=logout" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                        <li>&nbsp;</li>
                        <li>&nbsp;</li>
                        <li>&nbsp;</li>
                        <li>&nbsp;</li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">

            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <?PHP include $include_module; ?>
            <!-- Main content -->

        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer no-print">
            <strong>Copyright &copy; 2022 <a href="#">jwdcoldchain.com</a>.</strong> All rights reserved.
            <?PHP
            $end = $Time->End_Time();
            $total = $Time->Total_Time($start, $end);
            $Time->show_msg($total);
            echo print_mem();
            ?>
            <div class="float-right d-none d-sm-inline-block">
                <b>Phase 1 / Version</b> 1.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->


    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

    <a href="#" class="scrollup"><i class="fas fa-angle-double-up"></i> เลื่อนขึ้น</a>
</body>

</html>
<?PHP //$text;
exit();?>