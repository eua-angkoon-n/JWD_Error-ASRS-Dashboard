<?php
require_once __DIR__ . "/module_errorlog/errorlog.php";
require_once __DIR__ . "/module_errorcode/errorCode.php";
require_once __DIR__ . "/module_errorMachine/errorMachine.php";
require_once __DIR__ . "/module_errorMachine/errorMachine.php";
require_once __DIR__ . "/module_mainDB/mainDashboard.php";

require_once __DIR__ . "/../login.class.php";

require_once __DIR__ . "/../config/connect_db.inc.php";
require_once __DIR__ . "/../include/class_crud.inc.php";
require_once __DIR__ . "/../include/setting.inc.php";

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if($action == 'Machine'){ // เช็คเปลี่ยนค่า Select ด้านบน
        !isset($_POST['machine']) ? $_POST['machine'] = '' : $_POST['machine'];
        $getMachine = new getMachine($_POST['data'],$_POST['box'],$_POST['machine']);
        $result     = $getMachine->getMachine();
        echo $result; exit;
    }
    // Check the value of the "action" parameter
    if($action != "register_user"){
        $getAction = new getAction($_POST['data']);
        $getAction->getData(
            $wh,
            $date,
            $newDate,
            $arrWH,
            $machine,
            $nameCode,
            $pacaRm
        );
    }

    switch ($action){
        case 'DashBoard':
            $result = array();
            $CtResult  = new MainDashboard($newDate);
            $result['Card']   = $CtResult->getCard();
            $result['Chart']  = $CtResult->getChart();
            $result['Bar']    = $CtResult->getBar();
            // print_r($result['Chart']);
            print_r(json_encode($result));
            break;
        case 'errorLog':
            $CtResult  = new ErrorLog_WH($wh,$newDate,$arrWH,$pacaRm);
            $CtResult2 = new ErrorLog_WHTotal($wh,$newDate,$arrWH,$pacaRm);
            $result    = $CtResult->getChart();
            $result   .= $CtResult2->getChart();
            // echo '<pre>';
            print_r($result);
            // echo '</pre>';
            break;
        case 'errorCode':
            $CtResult  = new ErrorCode_Total($arrWH,$newDate);
            $result    = $CtResult->getChart();
            $Ct2Result = new ErrorCode($arrWH,$newDate,$nameCode);
            $result   .= $Ct2Result->getChart();
            print_r($result);
            break;
        case 'errorMachine':
            $CtResult  = new ErrorMachine_Total($arrWH,$newDate);
            $result    = $CtResult->getChart();
            $Ct2Result = new ErrorMachine($arrWH,$newDate,$machine);
            $result   .= $Ct2Result->getChart();
            print_r($result);
            break;
        case "register_user":
            $Call = new Register($_POST['data']);
            $result = $Call->getIDRegister();
            print_r($result);            
            break;
        case 'errorDetails':
            
            break;
        default:
            echo "Cannot Open Page " . $action;
            break;
    }
} else {
    echo "Page Not Found";
}
exit;



Class getAction
{
    private $wh;
    private $date;
    private $machine;
    private $nameCode;
    private $pacaRm;
    public function __construct($formData)
    {
        parse_str($formData, $data);
        
        $this->wh = $data['dropdownWH'] ?? NULL;
        $this->machine = $data['machine'] ?? NULL;
        $this->date = $data['selectedDateRange'] ?? NULL;
        $this->nameCode = $data['nameCode'] ?? NULL;
        
        $this->pacaRm = array(1=>false,2=>false);
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
        &$machine,
        &$nameCode,
        &$pacaRm
    ) {
        $wh_query = $this->getWH();
        $date     = $this->date;
        $newDate  = $this->getDate();
        $arrWH    = $this->wh;
        $machine  = $this->machine;
        $nameCode = $this->nameCode;
        $pacaRm   = $this->pacaRm;
    }

    public function getWH(){
        $wh = $this->wh;
        if($wh == NULL)
            return false;
        $wh_query ='';
        if(!is_array($wh))
            return " asrs_error_trans.wh = '$wh' ";
       
        foreach ($wh as $key => $value) {
            if($value == "paca1" || $value == "paca2"){
                $this->chkPACA($value);
                continue;
            }

            $wh_query .= count($wh) > 1 && $key == 0 ? ' ( ' : ' ';
        
            $wh_query .= count($wh) > 1 && $key == 0 ? ' asrs_error_trans.wh = "' .$value. '" ' : ' OR asrs_error_trans.wh = "' .$value. '" ';
        
            $wh_query .= count($wh) > 1 && array_key_last($wh) == $key ? ') ' : '';
        }
        count($wh) == 1 ? $wh_query = str_replace('OR', '', $wh_query) : $wh_query;
        if($wh_query == ''){
            return false;
        }
        
        return $wh_query;
    }

    public function chkPACA($value){
        $pacaRm = $this->pacaRm;
        if($value == "paca1"){
            $this->pacaRm[1] = true;
        }
        if($value == "paca2"){
            $this->pacaRm[2] = true;
        }
    }

    public function getBlock($wh) {
        $Room = Setting::$PACARoom[$wh];
        $blockQ = '(';
        foreach ($Room as $index => $no) {
             $blockQ .= " `Transfer_Equipment`='$no'  ";
             if ($index < count($Room) - 1) {
                $blockQ .= ' OR ';
            }
        }
        $blockQ .= ' ) ';
        return $blockQ;
    }
}

Class getMachine
{
    private $wh;
    private $box;
    private $machine;
    private bool $onlyCode;
    private $notFromTable;
    public function __construct($formData,$box,?string $machine)
    {
        $this->notFromTable = false;
        $this->wh  = $formData;
        $this->box = $box;
        $this->machine = $machine;
        $this->onlyCode = $this->checkOnlyCode();
    }

    public function getMachine(){
        $code    = $this->createList(false);
        if($this->onlyCode){
            $machine = 0;
        } else{
            $machine = $this->createList(true);
        }

        $result = array (
            'machine' => $machine,
            'code'    => $code
        );
        return json_encode($result);
    }

    public function createList(bool $isMachine){
        $wh       = $this->wh;
        $onlyCode = $this->onlyCode;
        if(!$onlyCode){
            if ($isMachine){
                if($wh == 'All'){
                    $sql  = $this->SqlMcNError(true,true);
                } else{
                    $sql  = $this->SqlMcNError(true,false);
                }
            } else {
                if($wh == 'All'){
                    $sql  = $this->SqlMcNError(false,true);
                } else {
                    $sql  = $this->SqlMcNError(false,false);
                }
            }
        } else {
            $sql = $this->SqlOnlyError();
        }

        try {
            $con = connect_database();
            $obj = new CRUD($con);

            $fetch    = $obj->fetchRows($sql);
            $options  = "<option value='All' selected>All</option>";
            foreach ($fetch as $key => $value){
                if ($isMachine){
                    $options .=  "<option value='".$value['Machine']."'>".$value['Machine']."</option>";
                } else {
                    $v = $value['Error_Name'];
                    if (IsNullOrEmptyString($v)){
                        $v = $value['Error_Code'];
                    }
                    $options .=  "<option value='$v'>$v</option>";
                }
            }

        } finally {
            $con = NULL;
        }
        return $options;
    }

    public function checkOnlyCode(){
        $box = $this->box;
        if($box == "Code")
            return true;
        else
            if ($box == "NotTable")
                $this->notFromTable = true;   
            return false;

        
    }

    public function SqlMcNError(bool $isMachine,bool $all){
        $wh = $this->wh;
        if($wh == 'paca1' || $wh == 'paca2'){
            $wh = 'paca';
        }
        if($isMachine){
            $sql  = "SELECT DISTINCT(Machine),wh "; 
            $sql .= "FROM asrs_error_trans ";
            $sql .= "WHERE ";
            if($all)
                $sql .= "1=1 ";
            else
                $sql .= "wh = '".strtolower($wh)."' ";
            $sql .= "ORDER BY Machine ASC";  
        } else {
            $sql  = "SELECT `Error_Code`,`Error_Name` ";
            $sql .= "FROM asrs_error_trans ";
            $sql .= "WHERE ";
            if($all)
                $sql .= "1=1 ";
            else
                $sql .= "wh = '".strtolower($wh)."' ";
            $sql .= "GROUP BY `Error_Name`, `Error_Code` ";
            $sql .= "ORDER BY `Error_Name` ASC";
        }
        return $sql;
    }

    public function SqlOnlyError(){
        $wh = $this->wh;
        if($wh == 'paca1' || $wh == 'paca2'){
            $wh = 'paca';
        }
        $mc   = $this->machine;
            $sql  = "SELECT `Error_Code`,`Error_Name` ";
            $sql .= "FROM asrs_error_trans ";
            $sql .= "WHERE ";
            if($wh == 'All') 
                $sql .= "1=1 ";
            else  
                $sql .= "wh = '$wh' ";
            if($mc != 'All') {
                $sql .= "AND ";
                $sql .= "Machine = '$mc' ";
            }
            $sql .= "GROUP BY `Error_Name`, `Error_Code` ";
            $sql .= "ORDER BY `Error_Name` ASC";

        return $sql;
    }
        
}
?>
