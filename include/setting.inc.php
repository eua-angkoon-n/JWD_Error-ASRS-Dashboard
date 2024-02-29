<?php
class Setting
{
    public static $AppTimeZone = 'Asia/Bangkok';
    public static $DefaultProvinceTH = 'สมุทรสาคร';
    public static $DefaultProvince = 'Samut Sakhon';
    public static $SiteList = array('PCS', 'JPK', 'JPAC', 'PACM', 'PLP', 'PACS', 'PACA', 'PACT');
    public static $classArr = array(0=> "ไม่พบข้อมูล", 1 => "ผู้ใช้ระบบ", 2 => "ช่างซ่อม", 3 => "หัวหน้าช่าง", 4=>"ผู้บริหาร", 5=>"ผู้จัดการระบบ");	
    public static $keygen = 'Pcs@'; //sha1+password
    public static $title_site = array
    (
        "login" => "Error ASRS Login",
        "DashBoard" => "Error ASRS Dashboard",
        "errorLog" => "Warehouse Error ASRS", 
        "errorMachine" => "Error ASRS | Error Machine",
        "errorCode" => "Error ASRS | Error Name/Code",
        "errorDetails" => "Error ASRS | Details",
        "pcsb8" => "PCS Warehouse 8",
        "pcsb9" => "PCS Warehouse 9",
        "pacaFrozen" => "PACA Frozen Room",
        "pacaTemp" => "PACA Temp Control Room",
        "pacm" => "PACM",
        "pacs" => "PACS",
        "pact" => "PACT",
    );
    public static $title_act = array
    (
        "DashBoard" => "Error ASRS Dashboard",
        "errorLog" => "Warehouse Error ASRS Log", 
        "errorMachine" => "ASRS Machine Error Log",
        "errorCode" => "ASRS Name/Code Error",
        "errorDetails" => "ASRS Error Log Details",
        "pcsb8" => "PCS Warehouse 8",
        "pcsb9" => "PCS Warehouse 9",
        "pacaFrozen" => "PACA Frozen Room",
        "pacaTemp" => "PACA Temp Control Room",
        "pacm" => "PACM",
        "pacs" => "PACS",
        "pact" => "PACT",
    );
    public static $breadcrumb_txt = array
    (
        "DashBoard" => "Error ASRS Dashboard",
        "errorLog" => "Error Log", 
        "errorMachine" => "Error Machine",
        "errorCode" => "Error Name,Code", 
        "errorDetails" => "Error Details",
        "pcsb8" => "PCS Warehouse 8",
        "pcsb9" => "PCS Warehouse 9",
        "pacaFrozen" => "PACA Frozen Room",
        "pacaTemp" => "PACA Temp Control Room",
        "pacm" => "PACM",
        "pacs" => "PACS",
        "pact" => "PACT",
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
        "#003C87",  // Navy Blue
        "#022a5c"
    );  

    public static $AdditionalBlueColors = array(
        "#3459B8", "#5077C6", "#6D94D4", "#89B2E2", "#A6CFF0",
        "#C4ECFF", "#7BA3CC", "#5389B4", "#306FA0", "#0D559C",
        "#003C87", "#022a5c", "#365a8c", "#5278aa", "#6f96c8",
        "#8bb4e6", "#a8d2f4", "#c5efff", "#7ca4cd", "#549abd",
        "#3172ad", "#0e58a7", "#00408e", "#032a62", "#26507f",
        "#406b9c", "#5c87b9", "#77a3d6", "#93bfe3", "#afdceb",
        "#cbeaf3", "#82b5da", "#5a9bc9", "#3281b9", "#0a67a8",
        "#004d98", "#003474", "#02204a", "#264270", "#405d8c",
        "#5c79a8", "#7785c4", "#9391e0", "#afaefb", "#cbd9ff",
        "#85b6e4", "#5da2d4", "#349ec4", "#0c8ab4", "#0061a3",
        "#00478f", "#003065", "#02173b", "#243661", "#3e5280",
        "#59609e", "#736abe", "#8e84de", "#aa9efa", "#c6b6ff",
        "#90b9f5", "#67a6e5", "#3d93d5", "#1480c5", "#006cb5",
        "#00528f", "#003766", "#011e3c", "#23325a", "#3e4977",
        "#595594", "#7350b2", "#8e6cdb", "#aa88f9", "#c6a6ff",
        "#9abfff", "#71acff", "#4888ff", "#1f74ff", "#0050ff",
        "#0035e5", "#001bba", "#000291", "#000069", "#000045",
        "#000022", "#131c2e", "#28344d", "#3d4b6d", "#52618c",
        "#6768ac", "#7c7fcc", "#9186eb", "#a696ff", "#bda7ff",
        "#d2b7ff", "#ead7ff", "#b4a2da", "#8d7ec2", "#664ba9",
        "#3f2890", "#1a0077", "#0d0050", "#060027", "#08010a"
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
        "Error_Name",
        "Error_Code"
    );

    public static $Warehouse = array(
        "b8" => "PCS B8",
        "b9" => "PCS B9",
        "paca1" => "PACA Frozen",
        "paca2" => "PACA Temp Control",
        "pacm" => "PACM",
        "pacs" => "PACS",
        "pact" => "PACT"
    );

    public static $ErrorFilePath = '/temp/bot/jaibot/gr/เก็บข้อมูล Error ASRS';

    public static $HundredColor = array (
        '#0054FF', '#FF0000', '#00FF00', '#0000FF', '#FF00FF', '#FFFF00', '#00FFFF', '#FFA500', '#800080', '#008000',
        '#008080', '#800000', '#808000', '#8000FF', '#0080FF', '#00FF80', '#FF8000', '#C0C0C0', '#808080', '#FFC0CB',
        '#FF69B4', '#FF1493', '#FF00FF', '#FF4500', '#2E8B57', '#B22222', '#4B0082', '#D2691E', '#ADFF2F', '#FFD700',
        '#DC143C', '#BDB76B', '#A0522D', '#2E8B57', '#F0E68C', '#DDA0DD', '#ADFF2F', '#FF69B4', '#8A2BE2', '#A52A2A',
        '#FFFFE0', '#FA8072', '#FFE4B5', '#F5DEB3', '#D3D3D3', '#FF6347', '#DA70D6', '#20B2AA', '#87CEFA', '#00FA9A',
        '#98FB98', '#F0FFF0', '#7FFF00', '#DB7093', '#F5F5F5', '#FFFAF0', '#D8BFD8', '#DEB887', '#40E0D0', '#6A5ACD',
        '#00CED1', '#FF00FF', '#FF6A6A', '#00FFFF', '#20B2AA', '#E9967A', '#FF1493', '#FFFACD', '#ADD8E6', '#90EE90',
        '#FFD700', '#F5DEB3', '#F0E68C', '#FFA07A', '#CD853F', '#FFB6C1', '#FFC0CB', '#FFE4E1', '#8B4513', '#0000CD',
        '#FF4500', '#00FF7F', '#48D1CC', '#87CEEB', '#00FA9A', '#98FB98', '#FF00FF', '#FF69B4', '#7B68EE', '#0000CD',
        '#8A2BE2', '#D2691E', '#FFD700', '#FF4500', '#DB7093', '#20B2AA', '#7FFF00', '#00FFFF', '#F5F5F5', '#FFFAF0',
        '#D8BFD8', '#DEB887', '#40E0D0', '#6A5ACD', '#00CED1', '#FF00FF', '#FF6A6A', '#00FFFF', '#20B2AA', '#E9967A'
    );

    public static $PACAChart = array(
        'crane' => "#1c3379",
        'conveyor' => "#dc3545",
        'melsec' => "#aaaaaa",
        'stv' => "#ffc107"
    );
    
    public static $PACARoom = array(
        'PACA Frozen' => array ('0001', '0002', '0003', '0004', '0005', '0006', '0007', '0008', '0009', '0010', '0011', '0012', '0013', '0014', '0015', '0016', '0017', '0018', '0019', '0020', '0021', '1101', '1102', '1103', '1104', '1105', '1106', '1107', '1108', '1109', '1110'),
        'PACA Temp Control' => array ('0022', '0023', '0024', '0025', '0026', '0027', '0028', '0029', '0030', '0031', '0032', '0033', '0034', '0035', '0036', '0037', '0038', '0039', '0040', '0041', '0042', '1201', '1202', '1203', '1204', '1205', '1206', '1207', '1208', '1209', '1210',)
    );

    public static $pacaVain = array(
        0 => 'paca',
        1 => 'paca1',
        2 => 'paca2',
        3 => 'PACA Frozen',
        4 => 'PACA Temp Control'
    );

    public static $PCSMachine = array(
        'crane' => 'SRA',
        'conveyor' => 'A',
        'stv' => 'SLA',
        'stvb9' => 'SS'
    );
    public static $PACMMachine = array(
        'crane' => 'SRMA',
        'conveyor' => 'A',
        'stv' => 'SS'
    );

    public static $PACSMachine = array(
        'crane' => 'RMA',
        'conveyor' => '1',
        'stv' => 'SS'
    );

    public static $PACTMachine = array(
        'crane' => 'RMA',
        'conveyor' => array('1','D-'),
        'stv' => 'SS'
    );
       
}