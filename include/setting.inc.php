<?php
class Setting
{
    public static $AppTimeZone = 'Asia/Bangkok';
    public static $DefaultProvinceTH = 'สมุทรสาคร';
    public static $DefaultProvince = 'Samut Sakhon';
    public static $SiteList = array('PCS', 'JPK', 'JPAC', 'PACM', 'PLP', 'PACS', 'PACA', 'PACT');
    public static $title_site = array
    (
        "DashBoard" => "Error ASRS Dashboard",
        "errorLog" => "Warehouse Error ASRS", 
        "errorMachine" => "Error ASRS | Error Machine",
        "errorCode" => "Error ASRS | Error Name/Code",
        "errorDetails" => "Error ASRS | Details",
    );
    public static $title_act = array
    (
        "DashBoard" => "Error ASRS Dashboard",
        "errorLog" => "Warehouse Error ASRS Log", 
        "errorMachine" => "ASRS Machine Error Log",
        "errorCode" => "ASRS Name/Code Error",
        "errorDetails" => "ASRS Error Log Details",
    );
    public static $breadcrumb_txt = array
    (
        "DashBoard" => "Error ASRS Dashboard",
        "errorLog" => "Error Log", 
        "errorMachine" => "Error Machine",
        "errorCode" => "Error Name,Code", 
        "errorDetails" => "Error Details",
    );
    public static $noreply_mail = "no-reply@cc.pcs-plp.com";
    public static $pass_mail = "Pcs@1234";
    public static $warning_text = array(
        0=> "คุณไม่มีสิทธิ์ใช้งานในส่วนนี้", 
        1 => "คุณไม่มีสิทธิ์เข้าดูข้อมูลส่วนนี้", 
        2 => "คุณไม่มีสิทธิ์จัดการข้อมูลส่วนนี้",
        3=>"กรุณาติดต่อแผนก IT/MIS เพื่อสอบถามข้อมูลเพิ่มเติม โทร. 1111"
    );	//ข้อความ เกี่ยวกับความปลอดภัย

    public static $arr_day_of_week = array('','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์','อาทิตย์');
    public static $arr_day_of_weekEN = array('','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');	
    public static $arr_mouth = array('มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');	
    public static $arr_mouthEN = array('January','February','March','April','May','June','July','August','September','October','November','December');	

    public static $arr_newMonths = array(
        '01' => 'มกราคม',
        '02' => 'กุมภาพันธ์',
        '03' => 'มีนาคม',
        '04' => 'เมษายน',
        '05' => 'พฤษภาคม',
        '06' => 'มิถุนายน',
        '07' => 'กรกฎาคม',
        '08' => 'สิงหาคม',
        '09' => 'กันยายน',
        '10' => 'ตุลาคม',
        '11' => 'พฤศจิกายน',
        '12' => 'ธันวาคม'
    );
    public static $arr_newMonthsEN = array(
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December'
    );
    public static $ColumnBarColor = array(
        "#3459B8", // Dark Blue
        "#5077C6",
        "#6D94D4",
        "#89B2E2",
        "#A6CFF0", // Pale Blue
        "#C4ECFF", // Lighter Blue
        "#7BA3CC", // Medium Blue
        "#5389B4",
        "#306FA0",
        "#0D559C", // Deep Blue
        "#003C87"  // Navy Blue
    );
    public static $SQLSET = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
    public static $DataTableCol = array( 
        0 => "asrs_error_trans.id",
        1 => "asrs_error_trans.id",
        2 => "asrs_error_trans.wh",
        3 => "asrs_error_trans.tran_date_time",
        4 => "asrs_error_trans.`Control WCS`",
        5 => "asrs_error_trans.`Control CELL`",
        6 => "asrs_error_trans.Machine",
        7 => "asrs_error_trans.Position",
        8 => "asrs_error_trans.`Transport Data Total`",
        9 => "asrs_error_trans.`Error Code`",
        10 => "asrs_error_trans.`Error Name`",
        11 => "asrs_error_trans.`Transfer Equipment #`",
        12 => "asrs_error_trans.Cycle",
        13 => "asrs_error_trans.Destination",
        14 => "asrs_error_trans.`Final Destination`",
        15 => "asrs_error_trans.`Load Size Info (Height)`",
        16 => "asrs_error_trans.`Load Size Info (Width)`",
        17 => "asrs_error_trans.`Load Size Info (Length)`",
        18 => "asrs_error_trans.`Load Size Info (Other)`",
        19 => "asrs_error_trans.Weight",
        20 => "asrs_error_trans.`Barcode Data`",
    );
    public static $DataTableSearch = array(
        "wh",
        "Error Name",
        "Error Code"
    );

    public static $Warehouse = array(
        "b8" => "PCS B8",
        "b9" => "PCS B9",
        "paca" => "PACA",
        "pacm" => "PACM",
        "pacs" => "PACS",
        "pact" => "PACT"
    );

    public static $ErrorFilePath = '/temp/bot/jaibot/gr/เก็บข้อมูล Error ASRS';
}
