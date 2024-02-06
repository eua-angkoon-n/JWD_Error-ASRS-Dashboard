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

if(empty($_SESSION['sess_id_user'])){ 
    $_SESSION = []; //empty array. 
    session_destroy(); 
    die(include('login.php')); 
}

$Time = new Processing;
$start = $Time->Start_Time();

isset($_REQUEST['module']) ? $module = $_REQUEST['module'] : $module = '';

switch ($module) {
    case "pcsb8" :
        $include_module = __DIR__ . "/module/module_pcsb8/view.php";
        $action = "pcsb8";
        $module == "pcsb8" ? $active_pcsb8 = "active" : $active_pcsb8 = "";
        break;
    case "pcsb9" :
        $include_module = __DIR__ . "/module/module_pcsb9/view.php";
        $action = "pcsb9";
        $module == "pcsb9" ? $active_pcsb9 = "active" : $active_pcsb9 = "";
        break;
    case "pacaFrozen" :
        $include_module = __DIR__ . "/module/module_pacaFrozen/view.php";
        $action = "pacaFrozen";
        $module == "pacaFrozen" ? $active_paca1 = "active" : $active_paca1 = "";
        break;
    case "pacaTemp" :
        $include_module = __DIR__ . "/module/module_pacaTemp/view.php";
        $action = "pacaTemp";
        $module == "pacaTemp" ? $active_paca2 = "active" : $active_paca2 = "";
        break;
    case "pacm" :
        $include_module = __DIR__ . "/module/module_pacm/view.php";
        $action = "pacm";
        $module == "pacm" ? $active_pacm = "active" : $active_pacm = "";
        break;
    case "pacs" :
        $include_module = __DIR__ . "/module/module_pacs/view.php";
        $action = "pacs";
        $module == "pacs" ? $active_pacs = "active" : $active_pacs = "";
        break;
    case "pact" :
        $include_module = __DIR__ . "/module/module_pact/view.php";
        $action = "pact";
        $module == "pact" ? $active_pact = "active" : $active_pact = "";
        break;
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
    case "errorLog" :
        $include_module = __DIR__ . "/module/dashboard.inc.php";
        $action = "errorLog";
        $module == "errorLog"? $active_errorlog = "active" : $active_errorlog = ""; #ไฮไลท์เมนูด้านซ้าย
      break;
    case 'logout':
        include('logout.php');
      break;
    default:
        $include_module = __DIR__ . "/module/main.inc.php";
        $action = "DashBoard";
        $module == "dashboard" || $module == "" ? $active_DashBoard = "active" : $active_DashBoard = ""; #ไฮไลท์เมนูด้านซ้าย
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
            <img class="animation__shake" src="dist/img/SCGJWDLogo.png" alt="AdminLTELogo" height="40" width="200">
        </div>

       <?php include( __DIR__ . "/navbar.php"); ?>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color:#000043;">
            <!-- Brand Logo -->
            <a href="./" class="brand-link">
                <img src="dist/img/SCGJWDLogo.png" alt="JWD Logo" class="w-100 p-0 m-0">
                <!--<img src="dist/img/logo_2.png" alt="JWD Logo" class="brand-image brand-text" >-->
                <span class="font-weight-bold p-1 mt-2 text-pcs-ct" style="background-color:#f15c22;color:white">
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
                            <?PHP echo $_SESSION['sess_fullname']; ?></a>
                        <span class="text-white">ระดับ:
                            <?PHP echo Setting::$classArr[$_SESSION['sess_class_user']]; ?> /
                            <?PHP echo $_SESSION['sess_dept_initialname']; ?></span>
                        <!-- <a href="?module=profile" class="d-block text-yellow">[แก้ไขข้อมูลส่วนตัว]</a> -->
                    </div>
                </div>

                <!-- Sidebar Menu active-->
                <nav class="mt-3">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="./" class="nav-link <?PHP echo $active_DashBoard; ?>">
                                <i class="nav-icon fas fa-chalkboard"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        
                        <li class='nav-item menu-open'>
                            <a href='#' class='nav-link'><i class='nav-icon fas fa-warehouse'></i>
                                <p>PCS<i class='right fas fa-angle-left'></i></p>
                            </a>
                            <ul class='nav nav-treeview ml-2'>
                                <li class="nav-item">
                                    <a href="?module=pcsb8" class="nav-link <?PHP echo $active_pcsb8; ?>">
                                        <i class="nav-icon fas fa-caret-right"></i>
                                        <p>Building 8</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="?module=pcsb9" class="nav-link <?PHP echo $active_pcsb9;?>">
                                        <i class="nav-icon fas fa-caret-right"></i> 
                                        <p>Building 9</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class='nav-item menu-open'>
                            <a href='#' class='nav-link'><i class='nav-icon fas fa-warehouse'></i>
                                <p>PACA<i class='right fas fa-angle-left'></i></p>
                            </a>
                            <ul class='nav nav-treeview ml-2'>
                                <li class="nav-item">
                                    <a href="?module=pacaFrozen" class="nav-link <?PHP echo $active_paca1; ?>">
                                        <i class="nav-icon fas fa-caret-right"></i>
                                        <p>Frozen Room</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="?module=pacaTemp" class="nav-link <?PHP echo $active_paca2;?>">
                                        <i class="nav-icon fas fa-caret-right"></i> 
                                        <p>Temp Control Room</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="?module=pacm" class="nav-link <?PHP echo $active_pacm; ?>">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>PACM</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="?module=pacs" class="nav-link <?PHP echo $active_pacs; ?>">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>PACS</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="?module=pact" class="nav-link <?PHP echo $active_pact; ?>">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>PACT</p>
                            </a>
                        </li>

                        <li class='nav-item'>
                            <a href='#' class='nav-link'><i class='nav-icon fas fa-poll'></i>
                                <p>Other<i class='right fas fa-angle-left'></i></p>
                            </a>
                            <ul class='nav nav-treeview ml-2'>
                                <li class="nav-item">
                                    <a href="?module=errorLog" class="nav-link <?PHP echo $active_errorlog; ?>">
                                        <i class="nav-icon fas fa-warehouse"></i>
                                        <p>Warehouse</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="?module=errorCode" class="nav-link <?PHP echo $active_errorCode;?>">
                                        <i class="nav-icon fas fa-exclamation"></i> 
                                        <p>Error Name/Code</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="?module=errorMachine" class="nav-link <?PHP echo $active_errorMachine;?>">
                                        <i class="nav-icon fas fa-truck-loading"></i> 
                                        <p>Machine</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="?module=errorDetails" class="nav-link <?PHP echo $active_errorDetails;?>">
                                        <i class="nav-icon fas fa-table"></i> 
                                        <p>Error Log Details</p>
                                    </a>
                                </li>
                            </ul>
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