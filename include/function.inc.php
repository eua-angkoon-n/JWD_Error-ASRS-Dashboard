<?php
 // bin/class.currency.php
 // class Currency โดย http://www.goragod.com (กรกฎ วิริยะ)
 // สงวนลิขสิทธ์ ห้ามซื้อขาย ให้นำไปใช้ได้ฟรีเท่านั้น

 function duration($begin,$end){
    $remain=intval(strtotime($end)-strtotime($begin));
    $wan=floor($remain/86400);
    $l_wan=$remain%86400;
    $hour=floor($l_wan/3600);
    $l_hour=$l_wan%3600;
    $minute=floor($l_hour/60);
    $second=$l_hour%60;
    return "".$wan." วัน ".$hour." ชั่วโมง ".$minute." นาที ".$second." วินาที";
}

 function  chk_iconTimeline($index) {
    switch($index){ //$rowTM[$key]['ref_arr_timeline'];
        case 0:
        case 1:
        default:
            $icon = '<i class="fas fa-file-invoice bg-primary"></i>';
            return $icon;
        break;

        case 4:
            $icon = '<i class="fas fa-clipboard-check bg-success"></i>';
            return $icon;
        break;        

    }
 }

 function searchArray($arrays, $key, $search) {
    $count = 0; 
    foreach($arrays as $object) {
        if(is_object($object)) {
           $object = get_object_vars($object);
        }
        if(array_key_exists($key, $object) && $object[$key] == $search) $count++;
    }
      return $count;
      //return $search.'-------มีจำนวน-------'.$count.'---------------ฟิลด์ที่ค้นหา==='.$key.'----------------'.$object[$key];
  }



//ฟังก์ชั่นหาค่าในอาร์เรย์ว่าอยู่ไอดีไหน **ใช้ชั่วคราวไปก่อน** Function to iteratively search for a given value 
function searchForId($search_value, $array, $id_path) {

	// Iterating over main array
	foreach ($array as $key1 => $val1) {

		$temp_path = $id_path;
		
		// Adding current key to search path
		array_push($temp_path, $key1);

		// Check if this value is an array
		// with atleast one element
		if(is_array($val1) and count($val1)) {

			// Iterating over the nested array
			foreach ($val1 as $key2 => $val2) {

				if($val2 == $search_value) {
						
					// Adding current key to search path
					array_push($temp_path, $key2);
				
          return join($search_value."----", $temp_path);          
				}
			}
		}
		
		elseif($val1 == $search_value) {
			return join($search_value."----", $temp_path);
		}
	}
	
	return null;
}


function write($path, $content, $mode="w+"){
	if (file_exists($path) && !is_writeable($path)){ return false; }
	if ($fp = fopen($path, $mode)){
		fwrite($fp, $content);
		fclose($fp);
	}
	else { return false; }
	return true;
}

##แปลง URL ให้เป็น UTF-8
function utf8_urldecode($str) {
	$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
	return html_entity_decode($str,null,'UTF-8');;
}

function removespecialchars($raw){
     return preg_replace('#[^a-zA-Z0-9-]#u', '', $raw);
}

##เช็คนามสกุลไฟล์
function file_extension($fileName){ return strtolower(substr(strrchr($fileName,'.'),1)); }

##แปลงหน่วยนับหน่วยความจำ
function convert_memuse($size){ $unit=array('ไบต์','กิโลไบต์','เมกกะไบต์','จิกะไบต์','เทระไบต์','เพระไบต์'); return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i]; }

function nowDate($date){
	$d = substr($date, -11, -8);
	$m = substr($date, -14, -12);
	$y = substr($date, -19, -15);
	$thMonth = array("01"=>"มกราคม", "02"=>"กุมภาพันธ์", "03"=>"มีนาคม", "04"=>"เมษายน", "05"=>"พฤษภาคม", "06"=>"มิถุนายน", "07"=>"กรกฏาคม", "08"=>"สิงหาคม", "09"=>"กันยายน", "10"=>"ตุลาคม", "11"=>"พฤศจิกายน", "12"=>"ธันวาคม");
	return ((int) $d).' '.$thMonth[$m].' '.($y+543); 
}

function nowDateEN($date){
	$d = substr($date, -11, -8);
	$m = substr($date, -14, -12);
	$y = substr($date, -19, -15);
	$thMonth = array("01"=>"January", "02"=>"February", "03"=>"March", "04"=>"April", "05"=>"May", "06"=>"June", "07"=>"July", "08"=>"August", "09"=>"September", "10"=>"October", "11"=>"November", "12"=>"December");
	return ((int) $d).' '.$thMonth[$m].' '.($y); 
}

function nowDateShort($date){
	$exDate = explode("-",$date);
	$thMonth = array("01"=>"ม.ค.", "02"=>"ก.พ.", "03"=>"มี.ค.", "04"=>"เม.ย.", "05"=>"พ.ค.", "06"=>"มิ.ย.", "07"=>"ก.ค.", "08"=>"ส.ค.", "09"=>"ก.ย.", "10"=>"ต.ค.", "11"=>"พ.ย.", "12"=>"ธ.ค.");
	return ((int) $exDate[2]).' '.$thMonth[$exDate[1]].' '.substr(($exDate[0]+543),2); 
}

function shortDateEN($date){
	$d = substr($date, -11, -8);
	$m = substr($date, -14, -12);
	$y = substr($date, -19, -15);
    $exDate = explode("-",$date);
	//$thMonth = array("01"=>"Jan", "02"=>"Feb", "03"=>"Mar", "04"=>"Apr", "05"=>"May", "06"=>"Jun", "07"=>"Jul", "08"=>"Aug", "09"=>"Sep", "10"=>"Oct", "11"=>"Nov", "12"=>"Dec");
	$thMonth = array("01"=>"01", "02"=>"02", "03"=>"03", "04"=>"04", "05"=>"05", "06"=>"06", "07"=>"07", "08"=>"08", "09"=>"09", "10"=>"10", "11"=>"11", "12"=>"12");	
	return ((int) $d).'/'.$thMonth[$m].'/'.($y); 
}

//00:00:00
function nowTime($date){ $h = substr($date, -8, -6); $m = substr($date, -5, -3); $s = substr($date, -2, 2);  return $h.':'.$m.':'.$s.' น.'; }	

function timeAgo($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "เมื่อสักครู่นี้";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "ประมาณ 1 นาทีที่ผ่านมา";
        }
        else{
            return "ประมาณ $minutes นาที";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "ประมาณ 1 ชั่วโมง";
        }else{
            return "ประมาณ $hours ชั่วโมง";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "ประมาณ 1 วัน";
        }else{
            return "ประมาณ $days วัน";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "1 อาทิตย์";
        }else{
            return "$weeks อาทิตย์ที่ผ่านมา";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "ประมาณ 1 เดือน";
        }else{
            return "$months เดือน";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}

function fb_date($timestamp){	
/*ถ้าเก็บเวลาในรูปแบบ timestamp (ตัวอย่าง 1300950558)
$date_you=1300950558;
echo fb_date($date_you);
ถ้าเก็บเวลาในรูปแบบ  datetime (ตัวอย่าง 2011-03-24 15:30:50)
$date_you="2011-03-24 15:30:50";
echo fb_date(strtotime($date_you));
*/
$difference = time() - $timestamp;
$periods = array("วินาที", "นาที", "ชั่วโมง");
$ending="ผ่านมา";
if($difference<60){
$j=0;
$periods[$j].=($difference != 1)?"":"";
	$difference=($difference==3 || $difference==4)?"ไม่กี่":$difference;
	$text = "$difference $periods[$j] $ending";
	}elseif($difference<3600){
	$j=1;
	$difference=round($difference/60);
	$periods[$j].=($difference != 1)?"":"";
	$difference=($difference==3 || $difference==4)?"ไม่กี่":$difference;
	$text = "$difference $periods[$j] $ending"; 
	}elseif($difference<86400){
	$j=2;
	$difference=round($difference/3600);
	$periods[$j].=($difference != 1)?"":"";
	$difference=($difference != 1)?$difference:"ประมาณ";
	$text = "$difference $periods[$j] $ending"; 
	}elseif($difference<172800){
	$difference=round($difference/86400);
	$periods[$j].=($difference != 1)? " ":" ";
	$text = "เมื่อวานนี้ ".date("g:ia",$timestamp); 
	}else{
	if($timestamp<strtotime(date("Y-01-01 00:00:00"))){
	$text = date("l j, Y",$timestamp)." เมื่อxx ".date("g:ia",$timestamp); 
	}else{
	$text = date("l j",$timestamp)." เมื่อzz ".date("g:ia",$timestamp); 
	}
	}
	return $text;
	}

/*
$big_array = array();
for ($i = 0; $i < 1000000; $i++)
{
   $big_array[] = $i;
}
echo 'After building the array.<br>';
print_mem();
unset($big_array);
echo 'After unsetting the array.<br>';
print_mem();
*/
function print_mem()
{
   /* Currently used memory */
   $mem_usage = memory_get_usage();
   
   /* Peak memory usage */
   $mem_peak = memory_get_peak_usage();
   //echo 'The script is now using: <strong>' . round($mem_usage / 1024) . 'KB</strong> of memory.<br>';
   //echo 'Peak usage: <strong>' . round($mem_peak / 1024) . 'KB</strong> of memory.<br><br>';
   echo ' Memory Used: <strong>' . round($mem_usage / 1024) . 'KB</strong>.';
}

function dateRange( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
    $dates = [];
    $current = strtotime( $first );
    $last = strtotime( $last );

    while( $current <= $last ) {

        $dates[] = date( $format, $current );
        $current = strtotime( $step, $current );
    }

    return $dates;
}

function timeDifference($date,$date2){
    $from_time = strtotime($date); 
    $to_time = strtotime($date2); 
    $diff_minutes = round(abs($from_time - $to_time) / 60,2);

    return $diff_minutes;
}

function dates_month($month, $year) {
    $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $dates_month = array();

    for ($i = 1; $i <= $num; $i++) {
        $mktime = mktime(0, 0, 0,   $year,$month,$i,);
        $date = $year.'-'.$month.'-'.$i;
        $dates_month[$i] = $date;
    }

    return $dates_month;
}

function nowDates_month($month, $year) {
    $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $dates_month = array();
    $nowDate = date('d');

    for ($i = 1; $i <= $nowDate; $i++) {
        $mktime = mktime(0, 0, 0,   $year,$month,$i,);
        $date = $year.'-'.$month.'-'.$i;
        $dates_month[$i] = $date;
    }

    return $dates_month;
}

function SortStatus($fetch){

    $TotalWait_approved = 0;
    $TotalNo_approved = 0;
    $TotalRepairing = 0;
    $TotalWait_repair = 0;
    $TotalWait_accept = 0;
    $TotalWait_hand_over = 0;
    $TotalHand_over = 0;
    $TotalCancel = 0;

    foreach ($fetch as $key => $value) {

        if ($value['status_approved'] == 0 && $value['allotted_date'] == null && $value['maintenance_request_status'] == 1
            && $value['duration_serv_end'] == null && $value['hand_over_date'] == null) {
            $TotalWait_approved++;
        } else if ($value['status_approved'] == 1 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
            && $value['allotted_accept_date'] == null && $value['ref_user_id_accept_request'] == null && $value['duration_serv_start'] == null
            && $value['duration_serv_end'] == null && $value['hand_over_date'] == null) {
            $TotalWait_accept++;
        } else if ($value['status_approved'] == 1 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
            && $value['allotted_accept_date'] != null && $value['ref_user_id_accept_request'] != null && $value['duration_serv_start'] == null
            && $value['duration_serv_end'] == null && $value['hand_over_date'] == null) {
            $TotalWait_repair++;
        } else if ($value['status_approved'] == 1 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
            && $value['allotted_accept_date'] != null && $value['ref_user_id_accept_request'] != null && $value['duration_serv_start'] != null
            && $value['duration_serv_end'] == null && $value['hand_over_date'] == null) {
            $TotalRepairing++;
        } else if ($value['status_approved'] == 1 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
            && $value['allotted_accept_date'] != null && $value['ref_user_id_accept_request'] != null && $value['duration_serv_start'] != null
            && $value['duration_serv_end'] != null && $value['hand_over_date'] == null) {
            $TotalWait_hand_over++;
        } else if ($value['status_approved'] == 1 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
            && $value['duration_serv_start'] != null && $value['duration_serv_end'] != null && $value['hand_over_date'] != null) {
            $TotalHand_over++;
        } else if ($value['status_approved'] == 2 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
            && $value['duration_serv_end'] == null && $value['hand_over_date'] == null) {
            $TotalNo_approved++;
        } else if ($value['maintenance_request_status'] == 2) {
            $TotalCancel++;
        }

    }

    $arrTotal = array(
        "Wait_approved" => $TotalWait_approved,
        "Wait_accept" => $TotalWait_accept,
        "Wait_repair" => $TotalWait_repair,
        "Repairing" => $TotalRepairing,
        "Wait_hand_over" => $TotalWait_hand_over,
        "Hand_over" => $TotalHand_over,
        "No_approved" => $TotalNo_approved,
        "Cancel" => $TotalCancel,
    );

    return $arrTotal;

}

function DataTableStatus($value){

    if ($value['status_approved'] == 0 && $value['allotted_date'] == null && $value['maintenance_request_status'] == 1
    && $value['duration_serv_end'] == null && $value['hand_over_date'] == null) {
    $req_textstatus = '<span class="text-bold text-danger">รออนุมัติ/จ่ายงาน</span>';
} else if ($value['status_approved'] == 1 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
    && $value['allotted_accept_date'] == null && $value['ref_user_id_accept_request'] == null && $value['duration_serv_start'] == null 
    && $value['duration_serv_end'] == null && $value['hand_over_date'] == null) {
    $req_textstatus = '<span class="text-bold text-danger">รอช่างรับงานซ่อม</span>';
} else if ($value['status_approved'] == 1 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
    && $value['allotted_accept_date'] != null && $value['ref_user_id_accept_request'] != null && $value['duration_serv_start'] == null 
    && $value['duration_serv_end'] == null && $value['hand_over_date'] == null) {
$req_textstatus = '<span class="text-bold text-danger">รอซ่อม</span>';
} else if ($value['status_approved'] == 1 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
    && $value['allotted_accept_date'] != null && $value['ref_user_id_accept_request'] != null && $value['duration_serv_start'] != null 
    && $value['duration_serv_end'] == null && $value['hand_over_date'] == null) {
    $req_textstatus = '<span class="text-bold text-success">กำลังซ่อม</span>';
} else if ($value['status_approved'] == 1 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
    && $value['allotted_accept_date'] != null && $value['ref_user_id_accept_request'] != null && $value['duration_serv_start'] != null
    && $value['duration_serv_end'] != null && $value['hand_over_date'] == null) {
    $req_textstatus = '<span class="text-bold text-success"> งานรอส่งมอบ</span>';
} else if ($value['status_approved'] == 1 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
    && $value['duration_serv_start'] != null && $value['duration_serv_end'] != null && $value['hand_over_date'] != null) {
    $req_textstatus = '<span class="text-bold text-success"> ปิดงานและส่งมอบแล้ว</span>';
} else if ($value['status_approved'] == 2 && $value['allotted_date'] != null && $value['maintenance_request_status'] == 1
    && $value['duration_serv_end'] == null && $value['hand_over_date'] == null) {
    $req_textstatus = '<span class="text-bold text-danger">ไม่อนุมัติ</span>';
} else if ($value['maintenance_request_status'] == 2) {
    $req_textstatus = '<span class="text-bold text-gray">ยกเลิกใบแจ้งซ่อม</span>';
} else {
    $req_textstatus = '-';
}

return $req_textstatus;

}

function unique_multidim_array($array, $key) {
    $temp_array = array();
    $duplicated_values = array();

    foreach ($array as $val) {
        $current_key = $val[$key];

        if (!in_array($current_key, array_column($temp_array, $key))) {
            // ยังไม่มีค่าที่ซ้ำใน $temp_array จึงเก็บค่าตัวแปรไว้เช็คกับตัวต่อไปก่อน
            $temp_array[] = $val;
        } else {
            // มีค่าที่ซ้ำอยู่ใน $temp_array จึงเก็บค่านี้ไว้ใน $duplicated_values เพื่อให้ลบทีหลัง
            $duplicated_values[] = $val;
        }
    }

    // ลบข้อมูลที่ซ้ำออกจาก $temp_array
    foreach ($duplicated_values as $duplicated_val) {
        $index = array_search($duplicated_val[$key], array_column($temp_array, $key));
        if ($index !== false) {
            $duplicated_values[] = $temp_array[$index];
            unset($temp_array[$index]);
        }
    }

    // รีเครื่องหมายกำกับอาร์เรย์ใหม่เพื่อให้มีดัชนีเป็นตัวเลขใหม่
    $temp_array = array_values($temp_array);
    $duplicated_values = array_values($duplicated_values);

    return array($temp_array, $duplicated_values);
}

function unique_multidim_array_key($array, $key) {

    $temp_array = array();

    $i = 0;

    $key_array = array();

    

    foreach($array as $val) {

        if (!in_array($val[$key], $key_array)) {

            $key_array[$i] = $val[$key];

            $temp_array[$i] = $val;

        }

        $i++;

    }

    return $temp_array;

}

function IVForeach($data){
    $response = '';
    foreach($data as $key => $value){
        if(array_key_last($data)>2){
            if(2 == $key){
                $response.= $value[0].' และอื่นๆ';
                break;
            }
        }else{
            if(array_key_last($data)== $key){
                $response.= $value[0].'.';
                break;
            }
        }
        $response.= $value[0].', ';
    }
    return $response;
}

function generatePattern($data , $name, $extension) {
    // Get current date and time in the desired format
    $day = date('d');
    $month = date('m');
    $year = date('y');
    $hour = date('H');
    $minute = date('i');
    $second = date('s');

    // Combine the values to create the pattern
    $pattern = $day . $month . $year . $hour . $minute . $second;

    $response = $name.$pattern.$extension; 

    return $response;
}

function convertDatePattern($dateString) {
    // Split the input date string into day, month, and year components
    list($day, $month, $year) = explode('/', $dateString);

    // Assume that "20" should be prefixed to the year to form "2020" or "2023" in this case
    if (strlen($year) == 2) {
        $year = "20{$year}";
    }

    // Create a DateTime object with the given components
    $date = DateTime::createFromFormat('Y-m-d', "{$year}-{$month}-{$day}");

    // Format the DateTime object into the desired output format
    return $date->format('Y-m-d');
}

function convertDateFormat($inputDate) {
    $outputDate = date('d/m/y', strtotime($inputDate));
    return $outputDate;
}

function formatNumberWithCommas($number) {
    return number_format($number, 0, '.', ',');
}

function tableImage($fetchRow){
    // return 'sadd';
    global $pathImg;
    global $font_path;
    
    
    // Function to draw a table cell with text and borders
    function drawTableCell($image, $x, $y, $width, $height, $text, $font, $font_size, $text_color, $border_color) {
        $cell_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, $x, $y, $x + $width, $y + $height, $cell_color);
        imagerectangle($image, $x, $y, $x + $width, $y + $height, $border_color); // Border
        imagettftext($image, $font_size, 0, $x + 5, $y + ($height) - ($font_size / 2), $text_color, $font, $text);
    }

    $arrData = array();
    foreach($fetchRow as $key => $value){
    
        $arrData[] = array(
            $value['allowance_no'],
            convertDateFormat($value['date']),
            $value['customer_name'],
            !empty($value['employee_no'])?$value['employee_no']:'-',
            !empty($value['employee_name'])?$value['employee_name']:'-',
            $value['vehicle_reg'],
            $value['location_pickup'],
            $value['location_delivery'],
            formatNumberWithCommas($value['total_price']),
            formatNumberWithCommas($value['fee_customer']),
            formatNumberWithCommas($value['fee_employee']),
            formatNumberWithCommas($value['allowance_short']),
            formatNumberWithCommas($value['allowance_medium']),
            formatNumberWithCommas($value['allowance_long']),
            formatNumberWithCommas($value['allowance_overnight']),
            formatNumberWithCommas($value['allowance_holiday']),
            formatNumberWithCommas($value['allowance_total']),  
        );
    }
    // Draw the table
    $table_x = 15;
    $table_y = 15;
    $cell_width = 180;
    $cell_height = 30;
    $font_size = 16;
    
    $header_data = array(
        array('                                                                                                                             รายงานค่าเบี้ยเลี้ยง', 
        '       ค่าขึ้น-ลงสินค้า',
        '                        ค่าเบี้ยเลี้ยง',
    ),
        // array('', 'วันที่', 'ชื่อลูกค้า', 'รหัสพนักงาน', 'ชื่อ-นามสกุล', 'ทะเบียน', 'ที่รับของ', 'ที่ส่งของ', 'ราคารวม', 'เก็บลูกค้า', 'จ่าย พขร.', 'สั้น', 'กลาง', 'ยาว', 'ค้างคืน', 'วันหยุด', 'ทั้งหมด',),
    );
    $subHeader_data = array(
         array('      เลขที่', 
         '       วันที่', 
         '                        ชื่อลูกค้า', 
         '  รหัสพนักงาน', 
         '            ชื่อ-นามสกุล', 
         '          ทะเบียน', 
         '          ที่รับของ', 
         '          ที่ส่งของ', 
         '  ราคารวม', 
         '  เก็บลูกค้า', 
         '  จ่าย พขร.', 
         '  สั้น', 
         '  กลาง', 
         '  ยาว', 
         ' ค้างคืน', 
         'วันหยุด', 
         '   ทั้งหมด',
        ),
    );
    
    $cell_data = $arrData;
    $column_widthsHeader = array(1330, 160, 330);
    // Set custom widths for each column
    $column_widths = array(100, 100, 300, 100, 200, 150, 150, 150, 80, 80, 80, 50, 50, 50, 50, 50, 80,);
    
    $table_y_subheader = $table_y + $cell_height; // Adjust the Y position for sub-header
    $total_rows = count($cell_data) + 1.5; // Include header and sub-header
    $total_height = $table_y_subheader + ($total_rows * $cell_height);
    
    $png_image = imagecreatetruecolor(1850, $total_height);
    $white = imagecolorallocate($png_image, 255, 255, 255);
    $black = imagecolorallocate($png_image, 0, 0, 0);
    imagefill($png_image, 0, 0, $white);
    $text_color = $black;
    $border_color = $black;
    
    // Draw the header row
    for ($col = 0; $col < count($header_data[0]); $col++) {
        $cell_width = $column_widthsHeader[$col]; // Set the width for the current column
        drawTableCell(
            $png_image,
            $table_x + array_sum(array_slice($column_widthsHeader, 0, $col)), // Calculate the x-position based on previous column widths
            $table_y,
            $cell_width,
            $cell_height,
            $header_data[0][$col],
            $font_path,
            $font_size,
            $text_color,
            $border_color
        );
    }
    
    // Draw the Sub header row
    for ($col = 0; $col < count($subHeader_data[0]); $col++) {
        $cell_width = $column_widths[$col]; // Set the width for the current column
        drawTableCell(
            $png_image,
            $table_x + array_sum(array_slice($column_widths, 0, $col)), // Calculate the x-position based on previous column widths
            $table_y_subheader, // Use the adjusted Y position
            $cell_width,
            $cell_height,
            $subHeader_data[0][$col],
            $font_path,
            $font_size,
            $text_color,
            $border_color
        );
    }
    
    // Draw the content rows
    for ($row = 1; $row < count($cell_data) + 1; $row++) {
        for ($col = 0; $col < count($cell_data[$row - 1]); $col++) {
            $cell_width = $column_widths[$col]; // Set the width for the current column
            drawTableCell(
                $png_image,
                $table_x + array_sum(array_slice($column_widths, 0, $col)), // Calculate the x-position based on previous column widths
                $table_y_subheader + ($row * $cell_height), // Adjust Y position to account for sub-header
                $cell_width,
                $cell_height,
                $cell_data[$row - 1][$col],
                $font_path,
                $font_size,
                $text_color,
                $border_color
            );
        }
    }
    
    // Continue with the existing code to add other elements to the image
    
    // header('Content-type: image/png');
    
    $image_filename = generatePattern('','','.png');
    $original_image_path = $pathImg . $image_filename;
    
    imagepng($png_image, $original_image_path);
    imagedestroy($png_image);

    $original_size = filesize($original_image_path);
    if ($original_size > 1024 * 1024) {
        $resized_image_path = $pathImg . generatePattern('', 'temp', '.png');
        $resized_image = imagescale(imagecreatefrompng($original_image_path), 800); // Replace $width and $height with the desired dimensions
        imagepng($resized_image, $resized_image_path);
        imagedestroy($resized_image);

        return array(
            'original' => $original_image_path,
            'resized' => $resized_image_path
        );
    } else {
        return array(
            'original' => $original_image_path,
            'resized' => $original_image_path // Return the original image path as resized
        );
    }
    }
    
function IsNullOrEmptyString($str) {
    return (!isset($str) || trim($str) === '');
}

function formatColorChart($color) {
    $row  = "[";
    foreach ($color as $BarColor){
        $row .= "'$BarColor', ";
    }
    $row .= "]";
    return $row;
}

function getDatesBetween($startDate, $endDate) {
    $startDate = new DateTime($startDate);
    $endDate = new DateTime($endDate);
    
    $dateArray = array();
    
    while ($startDate <= $endDate) {
        $dateArray[] = $startDate->format('Y-m-d');
        $startDate->modify('+1 day');
    }
    if (empty($dateArray))
        return false;
    return $dateArray;
}

function formatDateToChart($inputDate) {
    $dateTime = new DateTime($inputDate);
    $year = $dateTime->format('Y');
    $month = $dateTime->format('n') - 1; // Subtract 1 from the month
    $day = $dateTime->format('j');
    
    return "new Date($year, $month, $day)";
}

function getDateDay($date,&$start,&$end) {
    if (!$date)
        return false;
    foreach ($date as $key => $day) {
        if($key == 1)
            $start = $day;
        else if ($key == 0)
            $end = $day;
    }
    return true;
}

function isAll($value){
    if($value == 'All')
        return true;
    else
        return false;
}
function getLast30Day(){
    // Get the current date as a DateTime object
    $currentDate = new DateTime();

    // Subtract 30 days from the current date
    $startDate = clone $currentDate;
    $startDate->modify('-29 days');

    // Format the start and end dates as strings
    $startDateStr = $startDate->format('Y-m-d'); // Format: YYYY-MM-DD
    $currentDateStr = $currentDate->format('Y-m-d');

    $dateRange = array(
        0 => $currentDateStr,
        1 => $startDateStr
    );
    return $dateRange;
}

function getChartHundred($line,$HundredColor){
    $startIndex = 0;
    $endIndex = $line - 1;
    $colorOptions = '';
    for ($i = $startIndex; $i <= $endIndex && $i < count($HundredColor); $i++) {
        $colorOptions .= "
            $i: { 
                color: '".$HundredColor[$i]."'
            },";
    }
    return $colorOptions;
}

function chkPACA($arr,&$paca1,&$paca2){
    $paca1 = $arr[1];
    $paca2 = $arr[2];
}

function createDateRangeArray($datestart, $dateend, $wh, $count) {
    $dateRangeArray = array();
    if($datestart && $dateend){
        $currentDate = new DateTime($datestart);
        $endDate = new DateTime($dateend);
    
        while ($currentDate <= $endDate) {
            $dateRangeArray[] = array(
                'wh' => $wh,
                'day' => $currentDate->format('Y-m-d'),
                'count' => $count,
            );
            $currentDate->modify('+1 day');
        }

    } else {
        $dateRangeArray[] = array(
            'wh' => $wh,
            'count' => $count,
        );
    }

    return $dateRangeArray;
}

function chkSite($needle){
    $vain = $needle;
    if($needle == strtolower(Setting::$pacaVain[1]) || $needle == strtolower(Setting::$pacaVain[2])){
        if($needle == strtolower(Setting::$pacaVain[1]))
            $key = 3;
        if($needle == strtolower(Setting::$pacaVain[2]))
            $key = 4;
        $Rm    = Setting::$PACARoom[Setting::$pacaVain[$key]];    
        
        $vain  = "asrs_error_trans.wh = 'paca' ";
        $vain .= "AND ";
        $vain .= "(asrs_error_trans.`Transfer_Equipment` IN ( ";
        $vain .= implode(', ', $Rm);
        $vain .= " )) ";  
    } else {
        $vain = "asrs_error_trans.wh = '$vain' ";
    }
    return $vain;
}

function CustomDate($value, $from, $to){
    $s  = DateTime::createFromFormat($from , $value);

    if ($s) {
        // Format the date according to the desired output format
        $formattedDate = $s->format($to);
        return $formattedDate;
    } else {
        return "Invalid date format provided!". $s;
    }
}
?>