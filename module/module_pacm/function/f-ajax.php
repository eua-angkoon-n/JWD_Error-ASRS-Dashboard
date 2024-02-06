<?PHP
ob_start();
session_start();
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Bangkok');	

require_once __DIR__ . "/../../../config/connect_db.inc.php";
require_once __DIR__ . "/../../../include/setting.inc.php";

require_once __DIR__ . "/../../../include/class_crud.inc.php";
require_once __DIR__ . "/../../../include/function.inc.php";

$action = $_POST['action'];
// print_r ($_POST);
// exit;
switch($action){
    case 'getDashboard':
        $call   = new Dashboard($_POST['date'], $_POST['interval']);
        $h = $call->getData();
        $result = json_encode($h);
        break;
}

print_r($result);
exit;

Class Dashboard {
    private $start;
    private $end;
    private $interval;
    public function __construct($date, $interval){
        $d = explode("||//", $date);
        $this->start    = $d[0]." 00:00:00";
        $this->end      = $d[1]." 23:59:59";
        $this->interval = $interval;
    }

    public function getData(){
        $a = array(
          'total_machine' => $this->total_machine(),
          'err_log' => $this->err_log(),
          'err_machine' => $this->err_machine(),
          'err_chart' => $this->err_chart(),
          'last_tran' => $this->last_tran(),
          'last_update' => $this->last_update(),
          'interval_chart' => $this->interval_chart()
        );
        return $a;
    }

    private function total_machine(){
        $sql  = "SELECT Machine ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh = 'pacm' ";
        $sql .= "GROUP BY Machine ";

        try{
            $con = connect_database();
            $obj = new CRUD($con);

            $r   = $obj->countAll($sql);

            return $r;

        } catch (PDOException $e){
            return "Database Error : " . $e->getMessage();
        } catch (Exception $e){
            return "Error : " . $e->getMessage();
        } finally {
            $con = NULL;
        }
    }

    private function err_log(){
        $sql  = "SELECT id ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh = 'pacm' ";
        $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";

        try{
            $con = connect_database();
            $obj = new CRUD($con);

            $r   = $obj->countAll($sql);

            return $r;

        } catch (PDOException $e){
            return "Database Error : " . $e->getMessage();
        } catch (Exception $e){
            return "Error : " . $e->getMessage();
        } finally {
            $con = NULL;
        }
    }

    private function err_machine(){
        $sql  = "SELECT Machine ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh = 'pacm' ";
        $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";
        $sql .= "GROUP BY Machine ";

        try{
            $con = connect_database();
            $obj = new CRUD($con);

            $r   = $obj->countAll($sql);

            return $r;

        } catch (PDOException $e){
            return "Database Error : " . $e->getMessage();
        } catch (Exception $e){
            return "Error : " . $e->getMessage();
        } finally {
            $con = NULL;
        }
    }

    private function err_chart(){
        $sql  = "SELECT Error_Name, count(*) as total ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh = 'pacm' ";
        $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";
        $sql .= "GROUP BY Error_Name ";
        $sql .= "ORDER BY total DESC ";

        try{
            $con = connect_database();
            $obj = new CRUD($con);

            $r   = $obj->fetchRows($sql);

            if(empty($r))
                return array('No Data', 0);
            return $r;

        } catch (PDOException $e){
            return "Database Error : " . $e->getMessage();
        } catch (Exception $e){
            return "Error : " . $e->getMessage();
        } finally {
            $con = NULL;
        }
    }
    private function last_tran(){
        $sql  = "SELECT  tran_date_time, Machine ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh = 'pacm' ";
        $sql .= "ORDER BY tran_date_time DESC ";
        $sql .= "LIMIT 1 ";

        try{
            $con = connect_database();
            $obj = new CRUD($con);

            $r   = $obj->customSelect($sql);

            if(empty($r))
                return array('tran_date_time'=> '-', 'Machine'=> '-');

            $dateString = $r['tran_date_time'];
            $dateTime = new DateTime($dateString);
            
            // ใช้ format() เพื่อกำหนดรูปแบบใหม่
            $formattedDate = $dateTime->format('d.M.y H:i:s');

            return array('tran_date_time'=> $formattedDate, 'Machine'=> $r['Machine']);

        } catch (PDOException $e){
            return "Database Error : " . $e->getMessage();
        } catch (Exception $e){
            return "Error : " . $e->getMessage();
        } finally {
            $con = NULL;
        }
    }

    private function last_update(){
        $sql  = "SELECT * ";
        $sql .= "FROM asrs_error_attachment ";
        $sql .= "WHERE wh = 'pacm' ";

        try{
            $con = connect_database();
            $obj = new CRUD($con);

            $r   = $obj->customSelect($sql);

            if(empty($r))
                return array('name'=>'-', 'date'=>'-');
            return $r;

        } catch (PDOException $e){
            return "Database Error : " . $e->getMessage();
        } catch (Exception $e){
            return "Error : " . $e->getMessage();
        } finally {
            $con = NULL;
        }
    }

    private function interval_chart(){
        $sql = $this->getIntervalQuery();
        // return $sql;
        try{
            $con = connect_database();
            $obj = new CRUD($con);

            if($this->interval == 'week'){
                $obj->fetchRows(Setting::$SQLSET);
            }
            $r   = $obj->fetchRows($sql);

            if(empty($r))
                return array( 0 => array('interval'=>''));
            $arr = $this->getArrChart($r);
            return $arr;

        } catch (PDOException $e){
            return "Database Error : " . $e->getMessage();
        } catch (Exception $e){
            return "Error : " . $e->getMessage();
        } finally {
            $con = NULL;
        }
    }

    private function getIntervalQuery(){
        switch($this->interval){
            case 'hour':
                $sql  = "SELECT DATE_FORMAT(tran_date_time, '%Y-%m-%d %H:00:00') AS time_interval, COUNT(id) AS count ";
                $sql .= "FROM asrs_error_trans ";
                $sql .= "WHERE wh = 'pacm' ";
                $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";
                $sql .= "GROUP BY DATE_FORMAT(tran_date_time, '%Y-%m-%d %H:00:00') ";
            break;
            case 'day':
                $sql  = "SELECT DATE(tran_date_time) AS time_interval, COUNT(id) AS count ";
                $sql .= "FROM asrs_error_trans ";
                $sql .= "WHERE wh = 'pacm' ";
                $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";
                $sql .= "GROUP BY DATE(tran_date_time) ";
                break;
            case 'week':
                $sql  = "SELECT 
                            CONCAT(YEAR(tran_date_time), '-', MONTH(tran_date_time), '-', 
                            CASE 
                                WHEN DAY(tran_date_time) BETWEEN 1 AND 7 THEN 1
                                WHEN DAY(tran_date_time) BETWEEN 8 AND 14 THEN 2
                                WHEN DAY(tran_date_time) BETWEEN 15 AND 21 THEN 3
                                WHEN DAY(tran_date_time) BETWEEN 22 AND 28 THEN 4
                                ELSE 5
                            END) AS time_interval, 
                            COUNT(id) AS count 
                         FROM asrs_error_trans 
                         WHERE wh = 'pacm' 
                         AND tran_date_time BETWEEN '$this->start' AND '$this->end' 
                         GROUP BY 
                            CONCAT(YEAR(tran_date_time), '-', MONTH(tran_date_time), '-', 
                            CASE 
                                WHEN DAY(tran_date_time) BETWEEN 1 AND 7 THEN 1
                                WHEN DAY(tran_date_time) BETWEEN 8 AND 14 THEN 2
                                WHEN DAY(tran_date_time) BETWEEN 15 AND 21 THEN 3
                                WHEN DAY(tran_date_time) BETWEEN 22 AND 28 THEN 4
                                ELSE 5
                            END)";
                break;
            case 'month':
                $sql  = "SELECT DATE_FORMAT(tran_date_time, '%Y-%m') AS time_interval, COUNT(id) AS count ";
                $sql .= "FROM asrs_error_trans ";
                $sql .= "WHERE wh = 'pacm' ";
                $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";
                $sql .= "GROUP BY DATE_FORMAT(tran_date_time, '%Y-%m')";
                break;    
            case 'year':
                $sql  = "SELECT DATE_FORMAT(tran_date_time, '%Y') AS time_interval, COUNT(id) AS count ";
                $sql .= "FROM asrs_error_trans ";
                $sql .= "WHERE wh = 'pacm' ";
                $sql .= "GROUP BY DATE_FORMAT(tran_date_time, '%Y')";
                break;          
        }
        return $sql;
    }

    private function getArrChart($r){
        $arrChart = [];
    
        // เตรียม array ที่มีข้อมูลทั้งหมดที่ได้จากการ query
        foreach ($r as $row) {
            $arrChart[$row['time_interval']] = intval($row['count']);
        }
    
        // สร้าง array ที่เต็มเป็นชั่วโมงและมีค่าเป็น 0 สำหรับช่วงเวลาที่ไม่มีข้อมูล
        $startTime = strtotime($this->start);
        $endTime = strtotime($this->end);
        $interval = new DateInterval('PT1H'); // ช่วงเวลา 1 ชั่วโมง
        $currentTime = $startTime;
    
        while ($currentTime <= $endTime) {
            switch($this->interval){
                case 'hour':
                    $Time = date('Y-m-d H:00:00', $currentTime);
                    $currentTime = strtotime('+1 hour', $currentTime); // เพิ่มเวลาไปอีก 1 ชั่วโมง
                    break;
                case 'day':
                    $Time = date('Y-m-d', $currentTime);
                    $currentTime = strtotime('+1 day', $currentTime);
                    break;
                case 'week':
                    $weekOfYear = date('W', $currentTime);
                    $year = date('Y', $currentTime);
                    $Time = $year . '-' . $weekOfYear;
                    $currentTime = strtotime('+1 week', $currentTime);
                    break;
                case 'month':
                    $Time = date('Y-m', $currentTime);
                    $currentTime = strtotime('+1 month', $currentTime);
                    break;
                case 'year':
                    $Time = date('Y', $currentTime);
                    $currentTime = strtotime('+1 year', $currentTime);
                    break;
            }
            
            // เช็คว่าสัปดาห์นี้อยู่ในช่วง $this->start ถึง $this->end หรือไม่
            if (!isset($arrChart[$Time]) && $this->interval === 'week') {
                
            }
            elseif (!isset($arrChart[$Time])) {
                $arrChart[$Time] = 0; // กำหนดค่าเป็น 0 สำหรับช่วงเวลาที่ไม่มีข้อมูล
            }
        }
    
        // เรียงลำดับ array ตามวันและเวลา
        ksort($arrChart);
    
        // แปลง array เป็นรูปแบบที่ต้องการก่อน return
        $formattedArrChart = [];
        $i = 0;
        foreach ($arrChart as $Time => $value) {
            $formattedArrChart[] = [
                'date' => $Time, // แปลงเป็น object DateTime
                'value' => $value,
                'interval' => $this->interval,
                'color' => Setting::$AdditionalBlueColors[0]
            ];
            $i++;
        }
    
        return $formattedArrChart;
    }
    
}