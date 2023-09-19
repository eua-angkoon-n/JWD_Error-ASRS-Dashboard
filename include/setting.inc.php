<?php
class Setting
{
    public static $AppTimeZone = 'Asia/Bangkok';
    public static $DFFWDConst = 'ask_dialog_flow';
    public static $RETIMGURLConst = 'RETIMGURLConst';

    public static $INTENT_IMG_LOCATION = '/images/intent/';
    public static $INTENT_IMG_PREFIX = 'INTENT_IMG_';
    public static $TRAIN_IMG_PREFIX = 'TRAIN_IMG_';

    public static $TRAIN_CLIP_LOCATION = '/var/www/clips/';

    public static $DefaultProvinceTH = 'สมุทรสาคร';
    public static $DefaultProvince = 'Samut Sakhon';

    public static $strDOWCut = array("อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์");
    public static $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");

    public static $SiteList = array('PCS', 'JPK', 'JPAC', 'PACM', 'PLP', 'PACS', 'PACA', 'PACT');
    public static $UserHasNoRight = "ขอโทษค่ะ คุณไม่มีสิทธิใช้คำสั่งนี้ค่ะ\n";

    public static $LineReplyTimeOutSec = 28;
    public static $DFWebhookTimeOutSec = 5;

    public static $BG_Command = 'bg';
    public static $title_site = array
    (
        "errorLog" => "Warehouse Error ASRS", 
        "errorMachine" => "Error ASRS | Error Machine",
        "errorCode" => "Error ASRS | Error Name/Code",
        "errorDetails" => "Error ASRS | Details",
    );
    public static $title_act = array
    (
        "errorLog" => "Warehouse Error ASRS Log", 
        "errorMachine" => "ASRS Machine Error Log",
        "errorCode" => "ASRS Name/Code Error",
        "errorDetails" => "ASRS Error Log Details",
    );
    public static $breadcrumb_txt = array
    (
        "errorLog" => "Error Log", 
        "errorMachine" => "Error Machine",
        "errorCode" => "Error Name,Code", 
        "errorDetails" => "Error Details",
    );
    
    public static $noreply_mail = "no-reply@cc.pcs-plp.com";
    public static $pass_mail = "Pcs@1234";

    public static $req_digit = "-RQ-"; //ตัวย่อหน้าเลขที่ใบเบิก
    
    public static $keygen = 'Pcs@'; //sha1+password
     
    public static $btn_perPage = 10;#จำนวนปุ่มแสดงเลขหน้า
    public static $limit_perPage = 10; #จำนวนข้อมูลที่แสดงต่อ 1 หน้า *ทั้งโปรแกรม
     
    public static $imagesize = 5100;
     
    public static $pathImgDefault = "uploads/default.png";
     
    public static $path_machine= "uploads-asset/";
    public static $path_machine_Default = "uploads-asset/default.png";
     
    public static $pathUser= "uploads-user/";
     
    public static $pathUserDefault = "uploads-user/default.png";
     
    public static $pathReq= "upload-pic-req/";
     
    public static $pathPdf= "pdf/";
     
    public static $noimg = "noimg.gif";
     
    public static $timeDiff = 7200; // เวลา (นาที) = 5 วัน

    public static $deptArr = array( 
        array(0,'',''),
        array(1,'MA','Management'),
        array(2,'PLP','Pacific Logistics Pro'),
        array(3,'WH','Warehouse'),
        array(4,'QA','xxxxxxxxxxxxxxx'),
        array(5,'Safety','xxxxxxxxxxxxxxx'),
        array(6,'CS','Customer Service'),
        array(7,'AC','Account'),
        array(8,'EN','xxxxxxxxxxxxxxx'),
        array(9,'HR','xxxxxxxxxxxxxxx'),
        array(10,'IT/MIS','xxxxxxxxxxxxxxx'),
        array(11,'INV','Inventory'),
        array(12,'MT','xxxxxxxxxxxxxxx'),    
        array(13,'MK','xxxxxxxxxxxxxxx'),
        array(14,'PC','xxxxxxxxxxxxxxx')
    );
    public static $warning_text = array(
        0=> "คุณไม่มีสิทธิ์ใช้งานในส่วนนี้", 
        1 => "คุณไม่มีสิทธิ์เข้าดูข้อมูลส่วนนี้", 
        2 => "คุณไม่มีสิทธิ์จัดการข้อมูลส่วนนี้",
        3=>"กรุณาติดต่อแผนก IT/MIS เพื่อสอบถามข้อมูลเพิ่มเติม โทร. 1111"
    );	//ข้อความ เกี่ยวกับความปลอดภัย

    public static $arr_day_of_week = array('','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์','อาทิตย์');	
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

    public static $pathFile = "upload/";
    public static $pathImg = "uploads/";
    public static $pathURL = "https://ebooking.cc.pcs-plp.com/euaangkoon_test/linebot/";

    public static $font_path = './fonts/thsarabunnew.ttf';

    public static $LineToken = "Rwfo0ellYbzELwL5kO21mHgyNEQfmOZLEEf99KIQXHoRyQrOnPeWrRij47c8O+EvVulfOXrJbyWrosYNEKNuGGSvS21+H5gB8MZnDsjd/Ftyt8LzLljmeQwwhvltDGgogYNCyFYSoT5s7dOUDr37xwdB04t89/1O/w1cDnyilFU=";

    public static $arrColumnTitle = array(
        0 => 'เลขที่',
        1 => 'วันที่',
        2 => 'ชื่อลูกค้า',
        3 => 'รหัสพนักงาน',
        4 => 'ชื่อ',
        5 => 'นามสกุล',
        6 => 'ทะเบียน',
        7 => 'ที่รับของ',
        8 => 'ที่ส่งของ',
        9 => 'ราคารวม',
        10 => 'เก็บลูกค้า',
        11 => 'จ่าย พขร.',
        12 => 'สั้น',
        13 => 'กลาง',
        14 => 'ยาว',
        15 => 'ค้างคืน',
        16 => 'วันหยุด',
        17 => 'TOTAL'
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
}
