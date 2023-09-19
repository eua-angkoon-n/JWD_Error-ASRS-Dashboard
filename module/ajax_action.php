<?php
require_once __DIR__ . "/module_errorlog/errorlog.php";
require_once __DIR__ . "/module_errorcode/errorCode.php";
require_once __DIR__ . "/moule_errorMachine/errorMachine.php";

require_once __DIR__ . "/../config/connect_db.inc.php";
require_once __DIR__ . "/../include/class_crud.inc.php";

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if($action == 'Machine'){
        $getMachine = new getMachine($_POST['data']);
        $result = $getMachine->getMachine();
        echo $result; exit;
    }
    // Check the value of the "action" parameter
    $getAction = new getAction($_POST['data']);
    $getAction->getData(
        $wh,
        $date,
        $newDate,
        $arrWH,
        $machine
    );
    switch ($action){
        case 'errorLog':
            $CtResult  = new ErrorLog_WH($wh,$newDate,$arrWH);
            $CtResult2 = new ErrorLog_WHTotal($wh,$newDate,$arrWH);
            $result    = $CtResult->getChart(); 
            $result   .= $CtResult2->getChart();
            print_r($result);
            break;
        case 'errorCode':
            $CtResult  = new ErrorCode_Total($wh,$newDate);
            $result    = $CtResult->getChart();
            $errorCode = $CtResult->getErrorCode();
            $Ct2Result = new ErrorCode($arrWH,$newDate,$errorCode);
            $result   .= $Ct2Result->getChart();
            print_r($result);
            break;
        case 'errorMachine':
            $CtResult  = new ErrorMachine_Total($wh,$newDate);
            $result    = $CtResult->getChart();
            $Ct2Result = new ErrorMachine($wh,$newDate,$machine);
            $result   .= $Ct2Result->getChart();
            print_r($result);
            break;
        case 'MachineDetails':
            
            break;
        default:
            echo "No Action Name " . $action;
            break;
    }
} else {
    echo "Action parameter not set";
}
exit;



Class getAction
{
    private $wh;
    private $date;
    private $machine;
    public function __construct($formData)
    {
        parse_str($formData, $data);
  
        $this->wh = $data['dropdownWH'] ?? NULL;
        $this->machine = $data['machine'] ?? NULL;
        $this->date = $data['selectedDateRange'] ?? NULL;
    }

    public function getDate(){
        $date = $this->date;
        $a = explode("||//",$date);
        return $a;
    }

    public function getData(
        &$wh_query,
        &$date,
        &$newDate,
        &$arrWH,
        &$machine
    ) {
        $wh_query = $this->getWH();
        $date     = $this->date;
        $newDate  = $this->getDate();
        $arrWH    = $this->wh;
        $machine  = $this->machine;

    }

    public function getWH(){
        $wh = $this->wh;
        if($wh == NULL)
            return false;
        $wh_query =' ';
        if(!is_array($wh))
            return " asrs_error_trans.wh = '$wh' ";
       
        foreach ($wh as $key => $value) {
            $wh_query .= count($wh) > 1 && $key == 0 ? ' ( ' : ' ';
        
            $wh_query .= count($wh) > 1 && $key == 0 ? ' asrs_error_trans.wh = "' .$value. '" ' : ' OR asrs_error_trans.wh = "' .$value. '" ';
        
            $wh_query .= count($wh) > 1 && array_key_last($wh) == $key ? ') ' : '';
        }
        count($wh) == 1 ? $wh_query = str_replace('OR', '', $wh_query) : $wh_query;
        
        return $wh_query;
    }

    // public function getMonth(){
    //     $month = $this->month;
    //     if($month == NULL)
    //         return false;
    //     $month_query =' ';
    //     //MONTH(`tran_date_time`) = 7
    //     foreach ($month as $key => $value) {
    //         $month_query .= count($month) > 1 && $key == 0 ? ' ( ' : ' ';
        
    //         $month_query .= count($month) > 1 && $key == 0 ? ' MONTH(`tran_date_time`) = "' .$value. '" ' : ' OR MONTH(`tran_date_time`) = "' .$value. '" ';
        
    //         $month_query .= count($month) > 1 && array_key_last($month) == $key ? ') ' : '';
    //     }
    //     count($month) == 1 ? $month_query = str_replace('OR', '', $month_query) : $month_query;
        
    //     return $month_query;
    // }

    // public function getYear(){
    //     $year = $this->year;
    //     if($year == NULL)
    //         return false;
    //     $year_query =' ';
        
    //     foreach ($year as $key => $value) {
    //         $year_query .= count($year) > 1 && $key == 0 ? ' ( ' : ' ';
        
    //         $year_query .= count($year) > 1 && $key == 0 ? ' YEAR(`tran_date_time`) = "' .$value. '" ' : ' OR YEAR(`tran_date_time`) = "' .$value. '" ';
        
    //         $year_query .= count($year) > 1 && array_key_last($year) == $key ? ') ' : '';
    //     }
    //     count($year) == 1 ? $year_query = str_replace('OR', '', $year_query) : $year_query;
        
    //     return $year_query;
    // }

    // public function getCompare(&$mt_date, &$WH){
    //     $Year = $this->year;
    //     $Month = $this->month;
    //     if($Year == NULL || $Month == NULL)
    //         return NULL;

    //     foreach ($Year as $key => $value) {
    //         $Year = $value;
    //         foreach ($Month as $key => $values) {
    //             $dateReq = $Year . '-' . $values;
    //             $mt_date[] = $dateReq;
    //         }
    //     }
    //     $WH = $this->wh;
    // }
   
}

Class getMachine
{
    private $wh;
    public function __construct($formData)
    {
       $this->wh = $formData;
    }

    public function getMachine(){
        return $this->createList();
    }

    public function createList(){
        $wh   = $this->wh;
        $sql  = "SELECT DISTINCT(Machine),wh "; 
        $sql .= "FROM asrs_error_trans "; 
        $sql .= "WHERE wh = '".strtolower($wh)."' ";
        $sql .= "ORDER BY Machine ASC";

        try {
            $con = connect_database();
            $obj = new CRUD($con);

            $fetch   = $obj->fetchRows($sql);
            $options = "<option value='All'>All</option>";
            foreach ($fetch as $key => $value){
                $options .=  "<option value='".$value['Machine']."' ".($key== 0 ? 'selected' : '').">".$value['Machine']."</option>";
            }
        } finally {
            $con = NULL;
        }
        return $options;
    }
}
?>
