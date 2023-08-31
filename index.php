<?php
mb_internal_encoding('utf-8');

require_once __DIR__ . '/include/setting.inc.php';
require_once __DIR__ . '/config/connect_db.inc.php';
require_once __DIR__ . '/include/class_crud.inc.php';

date_default_timezone_set(Setting::$AppTimeZone);
header('Content-Type: text/html; charset=utf-8');



?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?PHP echo Setting::$title_site; ?></title>
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
</head>

<body ><!--sidebar-collapse sidebar-mini layout-fixed layout-navbar-fixed sidebar-closed sidebar-collapse layout-navbar-fixed-->
<div>
    <?php 
    // echo '<pre>';
    // print_r($sql);
    echo $sqlCode;
    echo '<br>';
    // echo $store;
    echo '<br>';
    // echo $rowIDs.' rows';
    // echo '</pre>';
    ?>    
</div>
</body>
</html>



<?PHP exit(); ?>
