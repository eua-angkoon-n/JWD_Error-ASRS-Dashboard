<?php
require_once __DIR__ . "/module_errorlog/errorlog.php";

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    // Check the value of the "action" parameter
    $getAction = new getAction($_POST['data']);
    $getAction->getData(
        $wh,
        $month,
        $year
    );
    switch ($action){
        case 'errorLog':
            $CtResult = new ErrorLog_WH($wh,$month,$year);
            $getAction->getCompare($date,$WH);
            $Ct2Result = new ErrorLog_WHCompare($WH,$date);
            $result  = $CtResult->getChart();
            $result .= $Ct2Result->getChart(); 
            print_r($result);
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
    private $month;
    private $year;

    public function __construct($formData)
    {
        parse_str($formData, $data);

        $this->wh = $data['dropdownWH'] ?? NULL;
        $this->month = $data['dropdownMonth'] ?? NULL;
        $this->year = $data['dropdownYear'] ?? NULL;
    }

    public function getData(
        &$wh_query,
        &$month_query,
        &$year_query
    ){
        $wh_query = $this->getWH();
        $month_query = $this->getMonth();
        $year_query = $this->getYear();
    }

    public function getWH(){
        $wh = $this->wh;
        if($wh == NULL)
            return false;
        $wh_query =' ';
       
        foreach ($wh as $key => $value) {
            $wh_query .= count($wh) > 1 && $key == 0 ? ' ( ' : ' ';
        
            $wh_query .= count($wh) > 1 && $key == 0 ? ' asrs_error_trans.wh = "' .$value. '" ' : ' OR asrs_error_trans.wh = "' .$value. '" ';
        
            $wh_query .= count($wh) > 1 && array_key_last($wh) == $key ? ') ' : '';
        }
        count($wh) == 1 ? $wh_query = str_replace('OR', '', $wh_query) : $wh_query;
        
        return $wh_query;
    }

    public function getMonth(){
        $month = $this->month;
        if($month == NULL)
            return false;
        $month_query =' ';
        //MONTH(`tran_date_time`) = 7
        foreach ($month as $key => $value) {
            $month_query .= count($month) > 1 && $key == 0 ? ' ( ' : ' ';
        
            $month_query .= count($month) > 1 && $key == 0 ? ' MONTH(`tran_date_time`) = "' .$value. '" ' : ' OR MONTH(`tran_date_time`) = "' .$value. '" ';
        
            $month_query .= count($month) > 1 && array_key_last($month) == $key ? ') ' : '';
        }
        count($month) == 1 ? $month_query = str_replace('OR', '', $month_query) : $month_query;
        
        return $month_query;
    }

    public function getYear(){
        $year = $this->year;
        if($year == NULL)
            return false;
        $year_query =' ';
        
        foreach ($year as $key => $value) {
            $year_query .= count($year) > 1 && $key == 0 ? ' ( ' : ' ';
        
            $year_query .= count($year) > 1 && $key == 0 ? ' YEAR(`tran_date_time`) = "' .$value. '" ' : ' OR YEAR(`tran_date_time`) = "' .$value. '" ';
        
            $year_query .= count($year) > 1 && array_key_last($year) == $key ? ') ' : '';
        }
        count($year) == 1 ? $year_query = str_replace('OR', '', $year_query) : $year_query;
        
        return $year_query;
    }

    public function getCompare(&$mt_date, &$WH){
        $Year = $this->year;
        $Month = $this->month;
        if($Year == NULL || $Month == NULL)
            return NULL;

        foreach ($Year as $key => $value) {
            $Year = $value;
            foreach ($Month as $key => $values) {
                $dateReq = $Year . '-' . $values;
                $mt_date[] = $dateReq;
            }
        }
        $WH = $this->wh;
    }
   
}
?>
