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
        "errorLog" => "Error ASRS", 
        "errorCode" => "Error ASRS | Error Code",
        "errorMachine" => "Error ASRS | Error Machine",
        "MachineDetails" => "Error ASRS | Machine Details",
        2 => "ระบบ แจ้งซ่อมออนไลน์ | E-Service",
        3 => "PCS E-Service",
        4 => "E-Service"
    );
    public static $title_act = array
    (
        "errorLog" => "Error ASRS Log", 
        "errorCode" => "ASRS Error Code",
        "errorMachine" => "ASRS Error Machine",
        "MachineDetails" => "ASRS Machine Details",
        2 => "ระบบ แจ้งซ่อมออนไลน์ | E-Service",
        3 => "PCS E-Service",
        4 => "E-Service"
    );
    public static $breadcrumb_txt = array
    (
        "errorLog" => "Error ASRS Log", 
        "errorCode" => "ASRS Error Code", 
        "errorMachine" => "ASRS Error Machine",
        "MachineDetails" => "ASRS Machine Details",
        2 => "ระบบ แจ้งซ่อมออนไลน์ | E-Service",
        3 => "PCS E-Service",
        4 => "E-Service"
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

    public static $branchArr = array( 
        array(0,'',''),
        array(1,'PCS','Pacific Cold Chain'),
        array(2,'JPAC','xxxxxxxxxxxxxxx'),
        array(3,'JPK','xxxxxxxxxxxxxxx'),
        array(4,'PACM','xxxxxxxxxxxxxxx'),
        array(5,'PACS','xxxxxxxxxxxxxxx'),
        array(6,'PACT','xxxxxxxxxxxxxxx'),
        array(7,'PACA','xxxxxxxxxxxxxxx'),
    );

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

    public static $arr_timeline = array( 
        array(0, '<i class="fas fa-caret-right bg-warning"></i>'),
        array(1,'เปิดใบแจ้งซ่อมเลขที่', '<i class="fas fa-file-invoice bg-primary"></i>'),
        array(2,'ยกเลิกใบแจ้งซ่อม', '<i class="fas fa-caret-right bg-warning"></i>'),
        array(3,'ไม่อนุมัติใบแจ้งซ่อม', '<i class="fas fa-caret-right bg-warning"></i>'),
        array(4,'อนุมัติใบแจ้งซ่อม และ จ่ายงาน', '<i class="fas fa-clipboard-check bg-success"></i>'),
        array(5,'ช่างซ่อมปฏิเสธงานซ่อม', '<i class="fas fa-caret-right bg-warning"></i>'),
        array(6,'เริ่มซ่อม', '<i class="fas fa-tools bg-red"></i>'),
        array(7,'แก้ไขผู้รับผิดชอบงานซ่อม', '<i class="fas fa-user-friends bg-warning"></i>'),
        array(8,'แก้ไขอาการเสีย/ปัญหาที่พบ', '<i class="fas fa-info-circle bg-warning"></i>'),
        array(9,'อัพเดทข้อมูลสรุปผลการซ่อม', '<i class="fas fa-file-signature bg-warning"></i>'),
        array(10,'อัพเดทข้อมูลส่งซ่อมภายนอก', '<i class="fas fa-truck bg-warning"></i>'),
        array(11,'อัพเดทข้อมูลรายการอะไหล่ที่เปลี่ยน', '<i class="fas fa-caret-right bg-warning"></i>'),
        array(12,'อัพเดทข้อมูลภาพถ่ายหลังซ่อม', '<i class="fas fa-caret-right bg-warning"></i>'),
        array(13,'ปิดงานซ่อม ส่งหัวหน้าช่างประเมิณเพื่อส่งมอบงาน', '<i class="fas fa-file-signature bg-warning"></i>'),    
        array(14,'ส่งมอบงาน รอผู้แจ้งซ่อมประเมิณ', '<i class="fas fa-handshake bg-success"></i>'),    
        array(15,'ประเมิณผลการซ่อม', '<i class="fas fa-poll bg-success"></i>'),
        array(16,'ซ่อมแล้ว', '<i class="fas fa-flag-checkered bg-success"></i>'),
        array(17,'ส่งข้อความ/ติดตามงานซ่อม', '<i class="fas fa-comments bg-info"></i>'),
        array(18,'อัพเดทประเภทใบแจ้งซ่อม', '<i class="fas fa-file-signature bg-warning"></i>'),
        array(19,'ช่างรับทราบ, รับงานซ่อม', '<i class="fas fa-user-check bg-warning"></i>'),    
        array(20,'ส่งข้อความ/ติดตามงานซ่อม', '<i class="fas fa-comments bg-blue"></i>'),
        array(21,'ยกเลิกส่งมอบ ให้ช่างแก้ไขงานซ่อมใหม่', '<i class="fas fa-undo-alt bg-red"></i>'),    
        array(22,'ลบข้อมูลรายการอะไหล่ที่เปลี่ยน', '<i class="fas fa-comments bg-blue"></i>'),    
        array(23,'ลบรูปถ่ายใบแจ้งซ่อม', '<i class="fas fa-comments bg-blue"></i>'),    
        array(24,'ไม่อนุมัติใบแจ้งซ่อม', '<i class="fas fa-comments bg-blue"></i>'),    
    );

    public static $arr_test_tm_test = array( 
        "0" => array("id_timeline" => "1", "ref_id_maintenance_request" => "1", "timeline_date" => "2023-04-09", "ref_id_user" => "1", "ref_arr_timeline" => "1","title_timeline" => "1", "detail_timeline" => "1",),
        "1" => array("id_timeline" => "1", "ref_id_maintenance_request" => "1", "timeline_date" => "2023-04-01", "ref_id_user" => "1", "ref_arr_timeline" => "1","title_timeline" => "1", "detail_timeline" => "1",),
        "2" => array("id_timeline" => "2", "ref_id_maintenance_request" => "2", "timeline_date" => "2023-04-03", "ref_id_user" => "2", "ref_arr_timeline" => "2","title_timeline" => "2", "detail_timeline" => "2",),
        "3" => array("id_timeline" => "3", "ref_id_maintenance_request" => "3", "timeline_date" => "2023-04-07", "ref_id_user" => "3", "ref_arr_timeline" => "3","title_timeline" => "3", "detail_timeline" => "3",),
        "4" => array("id_timeline" => "4", "ref_id_maintenance_request" => "4", "timeline_date" => "2023-04-07", "ref_id_user" => "4", "ref_arr_timeline" => "4","title_timeline" => "4", "detail_timeline" => "4",),
        "5" => array("id_timeline" => "5", "ref_id_maintenance_request" => "5", "timeline_date" => "2023-04-07", "ref_id_user" => "5", "ref_arr_timeline" => "5","title_timeline" => "5", "detail_timeline" => "5",),
        "6" => array("id_timeline" => "6", "ref_id_maintenance_request" => "6", "timeline_date" => "2023-04-10", "ref_id_user" => "6", "ref_arr_timeline" => "6","title_timeline" => "6", "detail_timeline" => "6",),
        "7" => array("id_timeline" => "7", "ref_id_maintenance_request" => "7", "timeline_date" => "2023-04-01", "ref_id_user" => "7", "ref_arr_timeline" => "7","title_timeline" => "7", "detail_timeline" => "7",),
        "8" => array("id_timeline" => "8", "ref_id_maintenance_request" => "8", "timeline_date" => "2023-04-05", "ref_id_user" => "8", "ref_arr_timeline" => "8","title_timeline" => "8", "detail_timeline" => "8",),
        "9" => array("id_timeline" => "9", "ref_id_maintenance_request" => "9", "timeline_date" => "2023-04-06", "ref_id_user" => "9", "ref_arr_timeline" => "9","title_timeline" => "9", "detail_timeline" => "9",),
        "10" => array("id_timeline" => "10", "ref_id_maintenance_request" => "10", "timeline_date" => "2023-04-08", "ref_id_user" => "10", "ref_arr_timeline" => "10","title_timeline" => "10", "detail_timeline" => "10",),
        "11" => array("id_timeline" => "11", "ref_id_maintenance_request" => "11", "timeline_date" => "2023-04-10", "ref_id_user" => "11", "ref_arr_timeline" => "11","title_timeline" => "11", "detail_timeline" => "11",),
        "12" => array("id_timeline" => "12", "ref_id_maintenance_request" => "12", "timeline_date" => "2023-04-05", "ref_id_user" => "12", "ref_arr_timeline" => "12","title_timeline" => "12", "detail_timeline" => "12",),
        "13" => array("id_timeline" => "13", "ref_id_maintenance_request" => "13", "timeline_date" => "2023-04-03", "ref_id_user" => "13", "ref_arr_timeline" => "13","title_timeline" => "13", "detail_timeline" => "13",),
        "14" => array("id_timeline" => "14", "ref_id_maintenance_request" => "14", "timeline_date" => "2023-04-03", "ref_id_user" => "14", "ref_arr_timeline" => "14","title_timeline" => "14", "detail_timeline" => "14",),
        "15" => array("id_timeline" => "15", "ref_id_maintenance_request" => "15", "timeline_date" => "2023-04-08", "ref_id_user" => "15", "ref_arr_timeline" => "15","title_timeline" => "15", "detail_timeline" => "15",),
        "16" => array("id_timeline" => "16", "ref_id_maintenance_request" => "16", "timeline_date" => "2023-04-05", "ref_id_user" => "16", "ref_arr_timeline" => "16","title_timeline" => "16", "detail_timeline" => "16",),
        "17" => array("id_timeline" => "17", "ref_id_maintenance_request" => "17", "timeline_date" => "2023-04-07", "ref_id_user" => "17", "ref_arr_timeline" => "17","title_timeline" => "17", "detail_timeline" => "17",),
        "18" => array("id_timeline" => "18", "ref_id_maintenance_request" => "18", "timeline_date" => "2023-04-10", "ref_id_user" => "18", "ref_arr_timeline" => "18","title_timeline" => "18", "detail_timeline" => "18",),
        "19" => array("id_timeline" => "19", "ref_id_maintenance_request" => "19", "timeline_date" => "2023-04-02", "ref_id_user" => "19", "ref_arr_timeline" => "19","title_timeline" => "19", "detail_timeline" => "19",),
        "20" => array("id_timeline" => "20", "ref_id_maintenance_request" => "20", "timeline_date" => "2023-04-01", "ref_id_user" => "20", "ref_arr_timeline" => "20","title_timeline" => "20", "detail_timeline" => "20",),
        "21" => array("id_timeline" => "21", "ref_id_maintenance_request" => "21", "timeline_date" => "2023-04-07", "ref_id_user" => "21", "ref_arr_timeline" => "21","title_timeline" => "21", "detail_timeline" => "21",),
        "22" => array("id_timeline" => "22", "ref_id_maintenance_request" => "22", "timeline_date" => "2023-04-05", "ref_id_user" => "22", "ref_arr_timeline" => "22","title_timeline" => "22", "detail_timeline" => "22",),
        "23" => array("id_timeline" => "23", "ref_id_maintenance_request" => "23", "timeline_date" => "2023-04-06", "ref_id_user" => "23", "ref_arr_timeline" => "23","title_timeline" => "23", "detail_timeline" => "23",),
        "24" => array("id_timeline" => "24", "ref_id_maintenance_request" => "24", "timeline_date" => "2023-04-05", "ref_id_user" => "24", "ref_arr_timeline" => "24","title_timeline" => "24", "detail_timeline" => "24",),
        "25" => array("id_timeline" => "25", "ref_id_maintenance_request" => "25", "timeline_date" => "2023-04-06", "ref_id_user" => "25", "ref_arr_timeline" => "25","title_timeline" => "25", "detail_timeline" => "25",),
        "26" => array("id_timeline" => "26", "ref_id_maintenance_request" => "26", "timeline_date" => "2023-04-09", "ref_id_user" => "26", "ref_arr_timeline" => "26","title_timeline" => "26", "detail_timeline" => "26",),
        "27" => array("id_timeline" => "27", "ref_id_maintenance_request" => "27", "timeline_date" => "2023-04-05", "ref_id_user" => "27", "ref_arr_timeline" => "27","title_timeline" => "27", "detail_timeline" => "27",),
        "28" => array("id_timeline" => "28", "ref_id_maintenance_request" => "28", "timeline_date" => "2023-04-06", "ref_id_user" => "28", "ref_arr_timeline" => "28","title_timeline" => "28", "detail_timeline" => "28",),
        "29" => array("id_timeline" => "29", "ref_id_maintenance_request" => "29", "timeline_date" => "2023-04-09", "ref_id_user" => "29", "ref_arr_timeline" => "29","title_timeline" => "29", "detail_timeline" => "29",),
        "30" => array("id_timeline" => "30", "ref_id_maintenance_request" => "30", "timeline_date" => "2023-04-04", "ref_id_user" => "30", "ref_arr_timeline" => "30","title_timeline" => "30", "detail_timeline" => "30",),    
    );  

    public static $arrTopicSurvey = array(
        "0"=> "ระยะเวลาทำงาน", 
        "1" => "ความสะอาด เศษโลหะ, เศษวัสดุ, ฝุ่น",
        "2" => "ความแข็งแรง สวยงาม",
        "3"=> "วัสดุซ่อมได้มาตรฐาน", 
        "4" => "ไม่ก่อให้เกิดความเสียหายต่อส่วนอื่นๆ",
        "5" => "ทดสอบเครื่องจักร",
        "6" => "เสร็จสมบูรณ์ ไม่ต้องต่อเติมภายหลัง",
        "7" => "สอดคล้องระเบียบบริษัทและลูกค้า"
    );

    public static $statusUserArr = array("0"=> "ไม่พบข้อมูล", "1" => "ใช้งานได้","2" => "ระงับใช้งาน");

    public static $menuTypeArr = array("0"=> "ไม่พบข้อมูล", "1" => "หมวดหลัก","2" => "หมวดย่อย");

    public static $statusArr = array("0"=> "ไม่พบข้อมูล", "1" => "ใช้งานได้","2" => "ระงับใช้งาน"); //ใช้กับ User,วัสดุ,หมวด,หน่วยนับ


    public static $paidStatusArr = array("0"=> "ไม่พบข้อมูล", "1" => "รออนุมัติ","2" => "ไม่อนุมัติ", "3" => "รอจ่าย", "4" => "จ่ายแล้ว");

    public static $statusReqArr = array("0"=> "ไม่พบข้อมูล", "1" => "ใช้งานได้","2" => "ระงับใช้งาน");

    public static $urgentArr = array("0"=> "ไม่พบข้อมูล", "1" => "ด่วน","2" => "ไม่ด่วน");

    public static $classArr = array(0=> "ไม่พบข้อมูล", 1 => "ผู้ใช้ระบบ", 2 => "ช่างซ่อม", 3 => "หัวหน้าช่าง", 4=>"ผู้บริหาร", 5=>"ผู้จัดการระบบ");	

    public static $ref_id_job_typeArr = array(0=> "ไม่พบข้อมูล", 1 => "แจ้งช่างซ่อม", 2 => "ช่างซ่อมเอง", 3 => "ส่งซ่อมภายนอก", 4 => "แจ้งช่างสร้าง");	//ประเภทงานซ่อม

    public static $related_to_saftyArr = array(0=> "ไม่พบข้อมูล", 1 => "ไม่ใช่", 2 => "ใช่");	//ข้อความ เกี่ยวกับความปลอดภัย

    public static $warning_text = array(
        0=> "คุณไม่มีสิทธิ์ใช้งานในส่วนนี้", 
        1 => "คุณไม่มีสิทธิ์เข้าดูข้อมูลส่วนนี้", 
        2 => "คุณไม่มีสิทธิ์จัดการข้อมูลส่วนนี้",
        3=>"กรุณาติดต่อแผนก IT/MIS เพื่อสอบถามข้อมูลเพิ่มเติม โทร. 1111"
    );	//ข้อความ เกี่ยวกับความปลอดภัย

    public static $module_name = array(
        "โมดูลจัดการระบบ" => array(
            "detail" => "สามารถ เพิ่ม, ลบ, แก้ไข, ระงับข้อมูลได้ ในเมนู เครื่องจักร-อุปกรณ์ (Master), ประเภทเครื่องจักร-อุปกรณ์, สิทธิ์การใช้งาน, ไซต์งาน, อาคาร, สถานที่, แผนก, หน่วยนับ, แบรนด์, ซัพพลายเออร์",
        ),
        "โมดูลตั้งค่าใบแจ้งซ่อม" => array(
            "detail" => "สามารถ เพิ่ม, ลบ, แก้ไข, ระงับข้อมูลได้ ในเมนู ประเภทใบแจ้งซ่อม, รหัสอาการเสีย, รหัสสาเหตุการเสีย, รหัสการซ่อม,วิธีซ่อม, สาเหตุการปฏิเสธงานซ่อม",
        ),
        "โมดูลแจ้งซ่อม" => array(
            "detail" => "สามารถแจ้งซ่อมได้ ดูความคืบหน้า พิมพ์ใบแจ้งซ่อมของแผนกผู้ใช้งานได้",
        ),
        "โมดูลจ่ายงานซ่อม" => array(
            "detail" => "จัดการใบแจ้งซ่อม-จ่ายงานได้",
        )
    );

    public static $text_timeline = array(
        0=> "ไม่พบข้อมูล", 
        1 =>"เปิดใบแจ้งซ่อม", 
        2 =>"อนุมัติใบแจ้งซ่อม", 
        3 =>"ไม่อนมุติใบแจ้งซ่อม", 
        4=>"จ่ายงานให้ (ชื่อช่าง)",
        5=>"รอช่างรับงาน",
    );	

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
