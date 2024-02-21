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
    private $crane;
    private $conveyor;
    private $stv;
    public function __construct($date, $interval){
        $d = explode("||//", $date);
        $this->start    = $d[0]." 00:00:00";
        $this->end      = $d[1]." 23:59:59";
        $this->interval = $interval;
        $this->crane    = Setting::$PCSMachine['crane'];
        $this->conveyor = Setting::$PCSMachine['conveyor'];
        $this->stv      = Setting::$PCSMachine['stvb9'];
    }

    public function getData(){
        $a = array(
            'err_total' => $this->err_count(false),
            'err_crane' => $this->err_count($this->crane),
            'err_conveyor' => $this->err_count($this->conveyor),
            'err_stv' => $this->err_count($this->stv),

            'interval_chart' => $this->interval_chart(),
            'err_chart' => $this->err_chart(),

            'last_tran' => $this->last_tran(),
            'last_update' => $this->last_update()
        );
        return $a;
    }

    private function err_count($q){
        $sql  = "SELECT Machine ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh = 'b9' ";
        $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";
        if($q)
            $sql .= "AND Machine LIKE '$q%'";
        // return $sql ;
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
        $sql  = "SELECT Error_Name, count(*) as total, ";
        $sql .= "CASE ";
        $sql .= "    WHEN Machine LIKE '$this->crane%' THEN 'crane' ";
        $sql .= "    WHEN Machine LIKE '$this->conveyor%' THEN 'conveyor' ";
        $sql .= "    WHEN Machine LIKE '$this->stv%' THEN 'STV' ";
        $sql .= "END AS type ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh = 'b9' ";
        $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";
        $sql .= "GROUP BY Error_Name, type ";
        $sql .= "ORDER BY total DESC ";
        $sql .= "LIMIT 15";

        try{
            $con = connect_database();
            $obj = new CRUD($con);

            $obj->fetchRows(Setting::$SQLSET);
            $r   = $obj->fetchRows($sql);

            if(empty($r))
                return array('No Data', 0);
                $arr = array();
            foreach($r as $k => $v){
                $machine = $v['type'];
                $color = '';

                if ($machine == "crane") {
                    $color = Setting::$PACAChart['crane'];
                } elseif ($machine == "conveyor") {
                    $color = Setting::$PACAChart['conveyor'];
                } elseif ($machine == "STV") {
                    $color = Setting::$PACAChart['stv'];
                }
                
                $arr[] = [
                    "Error_Name" => $v['Error_Name'],
                    "total"      => $v['total'],
                    "color"      => $color
                ];
            } 
            return $arr;

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
        $sql .= "WHERE wh = 'b9' ";
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
        $sql .= "WHERE wh = 'b9' ";

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
                $sql  = "SELECT DATE_FORMAT(tran_date_time, '%Y-%m-%d %H:00:00') AS time_interval, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->crane%' THEN 1 ELSE NULL END) AS crane, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->conveyor%' THEN 1 ELSE NULL END) AS conveyor, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->stv%' THEN 1 ELSE NULL END) AS stv ";
                $sql .= "FROM asrs_error_trans ";
                $sql .= "WHERE wh = 'b9' ";
                $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";
                $sql .= "GROUP BY DATE_FORMAT(tran_date_time, '%Y-%m-%d %H:00:00') ";
            break;
            case 'day':
                $sql  = "SELECT DATE(tran_date_time) AS time_interval, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->crane%' THEN 1 ELSE NULL END) AS crane, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->conveyor%' THEN 1 ELSE NULL END) AS conveyor, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->stv%' THEN 1 ELSE NULL END) AS stv ";
                $sql .= "FROM asrs_error_trans ";
                $sql .= "WHERE wh = 'b9' ";
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
                            COUNT(CASE WHEN Machine LIKE '$this->crane%' THEN 1 ELSE NULL END) AS crane, 
                            COUNT(CASE WHEN Machine LIKE '$this->conveyor%' THEN 1 ELSE NULL END) AS conveyor,
                            COUNT(CASE WHEN Machine LIKE '$this->stv%' THEN 1 ELSE NULL END) AS stv
                         FROM asrs_error_trans 
                         WHERE wh = 'b9' 
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
                $sql  = "SELECT DATE_FORMAT(tran_date_time, '%Y-%m') AS time_interval, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->crane%' THEN 1 ELSE NULL END) AS crane, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->conveyor%' THEN 1 ELSE NULL END) AS conveyor, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->stv%' THEN 1 ELSE NULL END) AS stv ";
                $sql .= "FROM asrs_error_trans ";
                $sql .= "WHERE wh = 'b9' ";
                $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";
                $sql .= "GROUP BY DATE_FORMAT(tran_date_time, '%Y-%m')";
                break;    
            case 'year':
                $sql  = "SELECT DATE_FORMAT(tran_date_time, '%Y') AS time_interval, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->crane%' THEN 1 ELSE NULL END) AS crane, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->conveyor%' THEN 1 ELSE NULL END) AS conveyor, ";
                $sql .= "COUNT(CASE WHEN Machine LIKE '$this->stv%' THEN 1 ELSE NULL END) AS stv ";
                $sql .= "FROM asrs_error_trans ";
                $sql .= "WHERE wh = 'b9' ";
                $sql .= "GROUP BY DATE_FORMAT(tran_date_time, '%Y')";
                break;          
        }
        return $sql;
    }

    private function getArrChart($r){
        $arrChart = [];
    
        // เตรียม array ที่มีข้อมูลทั้งหมดที่ได้จากการ query
        foreach ($r as $row) {
            $arrChart[$row['time_interval']] = [
                'crane' => intval($row['crane']),
                'conveyor' => intval($row['conveyor']),
                'stv' => intval($row['stv']),
            ];
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
            
        }
    
        // เรียงลำดับ array ตามวันและเวลา
        ksort($arrChart);
    
        // แปลง array เป็นรูปแบบที่ต้องการก่อน return
        $formattedArrChart = [];
        $i = 0;
        foreach ($arrChart as $Time => $value) {
            $formattedArrChart[] = [
                'date' => $Time, // แปลงเป็น object DateTime
                'crane' => $value['crane'],
                'conveyor' => $value['conveyor'],
                'stv' => $value['stv'],
                'interval' => $this->interval,
                'color1' => Setting::$PACAChart['crane'],
                'color2' => Setting::$PACAChart['conveyor'],
                'color3' => Setting::$PACAChart['stv'],
            ];
            $i++;
        }
    
        return $formattedArrChart;
    }
    
}