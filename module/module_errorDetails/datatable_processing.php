<?PHP
ob_start();
session_start();
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . "/../../config/connect_db.inc.php";
require_once __DIR__ . "/../../include/class_crud.inc.php";
require_once __DIR__ . "/../../include/function.inc.php";
require_once __DIR__ . "/../../include/setting.inc.php";

$column = $_POST['order']['0']['column'] + 1;
$search = $_POST["search"]["value"];
$start  = $_POST["start"];
$length = $_POST["length"];
$dir    = $_POST['order']['0']['dir'];
$draw   = $_POST["draw"];

$dataGet = array(
    'column' => $column,
    'search' => $search,
    'length' => $length,
    'start'  => $start,
    'dir'    => $dir,
    'draw'   => $draw
);

$Call   = new DataTable($_POST['formData'],$dataGet);
$result = $Call->getTable(); 
// print_r($result);
echo json_encode($result);
exit;
Class TableProcessing {
    protected $search;
    protected $query_search;
    protected $length;
    protected $start;
    protected $pStart;
    protected $limit;
    protected $column_sort;
    protected $orderBY;
    protected $column;
    protected $dir;
    protected $draw;
    public function __construct($dataGet)
    {
        $this->pStart = $dataGet['start'];
        $this->length = $dataGet['length'];
        $this->search = $dataGet['search'];
        $this->dir    = $dataGet['dir'];
        $this->draw   = $dataGet['draw'];

        $this->query_search = $this->getQuery_search($dataGet['search']);
        $this->length = $this->getLength($dataGet['start']);
        $this->start = $this->getStart($dataGet['start']);
        $this->limit = $this->getLimit();
        $this->column = $this->getColumn($dataGet['column']);
        $this->column_sort = $this->getColumn_sort();
        $this->orderBY = $this->getOrderBY();
    }

    public function getQuery_search($search){
        $query_search = "";
        if (!empty($search)) {
            $query_search = $this->getStringSearch($search);
        } else {
            $query_search = "";
        }
        return $query_search;
    }

    public function getStringSearch($search) {
        $arrSearch = Setting::$DataTableSearch;
        
        // Initialize an empty array to store individual search conditions
        $conditions = [];
    
        // Loop through the array of search fields
        foreach ($arrSearch as $value) {
            // Add each search condition to the array
            $conditions[] = "`$value` LIKE '%" . $search . "%'";
        }
    
        // Join the individual conditions with "OR" to create the final condition
        $finalCondition = implode(" OR ", $conditions);
    
        // Construct the SQL query string
        $sql = "AND ($finalCondition)";
    
        return $sql;
    }

    public function getLength($start){
        if ($start == 0) {
            $length = $this->length;
        } else {
            $length = $this->length;
        }
        return $length;
    }

    public function getStart($start){
        // if($start == 0) 
        //     return 0;
        $start = ($start - 1) * $this->length;
        return $start;
    }

    public function getLimit(){
        $limit = "LIMIT " . $this->pStart . ", " . $this->length . "";
        $this->length == -1 ? $limit = "" : '';
        return $limit;
    }

    public function getColumn($column){
        empty($column) ? $column = 0 : $column;
        return $column;
    }

    public function getColumn_sort(){
        $column_sort = Setting::$DataTableCol;
        return $column_sort;
    }

    public function getOrderBY(){
        $column_sort = $this->column_sort;
        $orderBY = $column_sort[$this->column];
        return $orderBY;
    }
}

Class DataTable extends TableProcessing {
    
    protected $wh;
    protected $machine;
    protected $errorName;
    protected $date;
    public function __construct($formData,$TableSET){
        parent::__construct($TableSET); //ส่งค่าไปที่ DataTable Class

        parse_str($formData, $data);
        $newDate = NULL;
        if(!IsNullOrEmptyString($data['selectedDateRange'])){
            $newDate = explode("||//",$data['selectedDateRange']);
        }

        $this->wh        = $data['dropdownWH'] ?? NULL;
        $this->machine   = $data['machine']    ?? NULL;
        $this->errorName = $data['NameCode']   ?? NULL;
        $this->date      = $newDate            ?? getLast30Day();
        
    }
    public function getTable(){
        return $this->SqlQuery();
    }

    public function SqlQuery(){
        $sql      = $this->getSQL(true);
        $sqlCount = $this->getSQL(false);

        // return $sql;
        try {
            $con = connect_database();
            $obj = new CRUD($con);

            $fetchRow = $obj->fetchRows($sql);
            $numRow   = $obj->getCount($sqlCount);

            $Result   = $this->createArrayDataTable($fetchRow, $numRow);
            
            return $Result;
        } catch (PDOException $e) {
            return "Database connection failed: " . $e->getMessage();
        
        } catch (Exception $e) {
            return "An error occurred: " . $e->getMessage();
        
        } finally {
            $con = null;
        }
    }

    public function getSQL(bool $OrderBY){
        $getWH = $this->wh;
        $wh   = chkSite($getWH);
        $machine   = $this->machine;
        $errorName = $this->errorName;
        $date      = $this->date;

        if($OrderBY)
            $sql  = "SELECT * ";
        else
            $sql  = "SELECT count(id) AS total_row ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE 1=1 ";
        if(!isAll($wh))
            $sql .= "AND $wh ";
        if(!isAll($machine))
            $sql .= "AND Machine = '$machine' ";
        if(!isAll($errorName))
            $sql .= "AND (`Error_Code` = '$errorName' OR `Error_Name` = '$errorName') ";
        if(!empty($date)) {
            $sql .= "AND ";
            if($date[1] != $date[0]){
                $sql .= "tran_date_time ";
                $sql .= "BETWEEN '$date[1]' AND '$date[0]' ";
            }
            else {
                $sql .= "date(tran_date_time) ";
                $sql .= "= '".$date[0]."' ";
            }
        }
        $sql .= "$this->query_search ";
        if($OrderBY) {
            $sql .= "ORDER BY ";
            $sql .= "$this->orderBY ";
            $sql .= "$this->dir ";
            $sql .= "$this->limit ";
        }
        
        return $sql;
    }
    
    public function createArrayDataTable($fetchRow, $numRow){

        $arrData = null;
        $output = array(
            "draw" => intval($this->draw),
            "recordsTotal" => intval(0),
            "recordsFiltered" => intval(0),
            "data" => $arrData,
        );

        if (count($fetchRow) > 0) {
            $No = ($numRow - $this->pStart);
            foreach ($fetchRow as $key => $value) {

                $dataRow = array();
                $dataRow[] = $No . '.';
              
                $dataRow[] = ($fetchRow[$key]['wh']                         == '' ? '-' : strtoupper($fetchRow[$key]['wh']));
                $dataRow[] = ($fetchRow[$key]['tran_date_time']             == '' ? '-' : date("d/m/Y H:i:s", strtotime($fetchRow[$key]['tran_date_time'])));
                $dataRow[] = ($fetchRow[$key]['Control_WCS']                == '' ? '-' : $fetchRow[$key]['Control_WCS']);
                $dataRow[] = ($fetchRow[$key]['Control_CELL']               == '' ? '-' : $fetchRow[$key]['Control_CELL']);
                $dataRow[] = ($fetchRow[$key]['Machine']                    == '' ? '-' : $fetchRow[$key]['Machine']);
                $dataRow[] = ($fetchRow[$key]['Position']                   == '' ? '-' : $fetchRow[$key]['Position']);
                $dataRow[] = ($fetchRow[$key]['Transport_Data_Total']       == '' ? '-' : $fetchRow[$key]['Transport_Data_Total']);
                $dataRow[] = ($fetchRow[$key]['Error_Code']                 == '' ? '-' : $fetchRow[$key]['Error_Code']);
                $dataRow[] = ($fetchRow[$key]['Error_Name']                 == '' ? '-' : $fetchRow[$key]['Error_Name']);
                $dataRow[] = ($fetchRow[$key]['Transfer_Equipment']       == '' ? '-' : $fetchRow[$key]['Transfer_Equipment']);
                $dataRow[] = ($fetchRow[$key]['Cycle']                      == '' ? '-' : $fetchRow[$key]['Cycle']);
                $dataRow[] = ($fetchRow[$key]['Destination']                == '' ? '-' : $fetchRow[$key]['Destination']);
                $dataRow[] = ($fetchRow[$key]['Final_Destination_Location'] == '' ? '-' : $fetchRow[$key]['Final_Destination_Location']);
                $dataRow[] = ($fetchRow[$key]['Load_Size_Info_Height']    == '' ? '-' : $fetchRow[$key]['Load_Size_Info_Height']);
                $dataRow[] = ($fetchRow[$key]['Load_Size_Info_Width']     == '' ? '-' : $fetchRow[$key]['Load_Size_Info_Width']);
                $dataRow[] = ($fetchRow[$key]['Load_Size_Info_Length']    == '' ? '-' : $fetchRow[$key]['Load_Size_Info_Length']);
                $dataRow[] = ($fetchRow[$key]['Load_Size_Info_Other']     == '' ? '-' : $fetchRow[$key]['Load_Size_Info_Other']);
                $dataRow[] = ($fetchRow[$key]['Weight']                     == '' ? '-' : $fetchRow[$key]['Weight']);
                $dataRow[] = ($fetchRow[$key]['Barcode_Data']               == '' ? '-' : $fetchRow[$key]['Barcode_Data']);
                
 
              
                $arrData[] = $dataRow;
                $No--;
            }
        }

        $output = array(
            "draw" => intval($this->draw),
            "recordsTotal" => intval($numRow),
            "recordsFiltered" => intval($numRow),
            "data" => $arrData,
        );

        return $output;
    }

    
}

?>