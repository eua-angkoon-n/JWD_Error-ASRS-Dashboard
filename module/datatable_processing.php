<?PHP
ob_start();
session_start();
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . "/../config/connect_db.inc.php";
require_once __DIR__ . "/../include/class_crud.inc.php";
require_once __DIR__ . "/../include/function.inc.php";

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

// new TableProcessing($dataGet);
$Call   = new DataTable($_POST['formData'],$dataGet);
$result = $Call->getTable(); 
// echo $result;
echo json_encode($result);
exit;
Class TableProcessing {
    protected $search;
    protected $query_search;
    protected $length;
    protected $start;
    protected $limit;
    protected $column_sort;
    protected $orderBY;
    protected $column;
    protected $dir;
    protected $draw;
    public function __construct($dataGet)
    {
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
            $query_search = " AND (wh LIKE '%" . $search . "%' OR `Error Name` LIKE '%" . $search . "%') ";
        } else {
            $query_search = "";
        }
        return $query_search;
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
        $start = ($start - 1) * $this->length;
        return $start;
    }

    public function getLimit(){
        $limit = "LIMIT " . max(0, $this->start) . ", " . $this->length . "";
        $this->length == -1 ? $limit = "" : '';
        return $limit;
    }

    public function getColumn($column){
        empty($column) ? $column = 0 : $column;
        return $column;
    }

    public function getColumn_sort(){
        $colunm_sort = array( 
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
        return $colunm_sort;
    }

    public function getOrderBY(){
        $column_sort = $this->column_sort;
        $orderBY = $column_sort[$this->column];
        return $orderBY;
    }
}

Class DataTable {

    protected $search;
    protected $query_search;
    protected $length;
    protected $start;
    protected $limit;
    protected $column_sort;
    protected $orderBY;
    protected $column;
    protected $dir;
    protected $draw;
    
    private $wh;
    private $machine;
    private $errorName;
    private $date;
    public function __construct($formData,$dataGet){


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

        parse_str($formData, $data);
        $newDate = NULL;
        if(!IsNullOrEmptyString($data['selectedDateRange'])){
            $newDate = explode("||//",$data['selectedDateRange']);
        }

        $this->wh        = $data['dropdownWH'] ?? NULL;
        $this->machine   = $data['machine']    ?? NULL;
        $this->errorName = $data['NameCode']   ?? NULL;
        $this->date      = $newDate            ?? NULL;
        
    }

    public function getTable(){
        return $this->SqlQuery();
    }

    public function SqlQuery(){
        $sql      = $this->getSQL(true);
        $sqlCount = $this->getSQL(false);

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
        $wh        = $this->wh;
        $machine   = $this->machine;
        $errorName = $this->errorName;
        $date      = $this->date;

        if($OrderBY){
            $sql  = "SELECT * ";         
        } else {
            $sql  = "SELECT count(id) AS total_row ";
        }
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE 1=1 ";
        if(!isAll($wh))
            $sql .= "AND wh = '$wh' ";
        if(!isAll($machine))
            $sql .= "AND Machine = '$machine' ";
        if(!isAll($errorName))
            $sql .= "AND (`Error Code` = '$errorName' OR `Error Name` = '$errorName') ";
        if(!empty($date))
            $sql .= "AND tran_date_time BETWEEN '".$date[0]."' AND '".$date[1]."' ";
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
            $No = ($numRow - $this->draw);
            foreach ($fetchRow as $key => $value) {

                $dataRow = array();
                $dataRow[] = $No . '.';
              
                $dataRow[] = ($fetchRow[$key]['wh']                         == '' ? '-' : $fetchRow[$key]['wh']);
                $dataRow[] = ($fetchRow[$key]['tran_date_time']             == '' ? '-' : $fetchRow[$key]['tran_date_time']);
                $dataRow[] = ($fetchRow[$key]['Control WCS']                == '' ? '-' : $fetchRow[$key]['Control WCS']);
                $dataRow[] = ($fetchRow[$key]['Control CELL']               == '' ? '-' : $fetchRow[$key]['Control CELL']);
                $dataRow[] = ($fetchRow[$key]['Machine']                    == '' ? '-' : $fetchRow[$key]['Machine']);
                $dataRow[] = ($fetchRow[$key]['Position']                   == '' ? '-' : $fetchRow[$key]['Position']);
                $dataRow[] = ($fetchRow[$key]['Transport Data Total']       == '' ? '-' : $fetchRow[$key]['Transport Data Total']);
                $dataRow[] = ($fetchRow[$key]['Error Code']                 == '' ? '-' : $fetchRow[$key]['Error Code']);
                $dataRow[] = ($fetchRow[$key]['Error Name']                 == '' ? '-' : $fetchRow[$key]['Error Name']);
                $dataRow[] = ($fetchRow[$key]['Transfer Equipment #']       == '' ? '-' : $fetchRow[$key]['Transfer Equipment #']);
                $dataRow[] = ($fetchRow[$key]['Cycle']                      == '' ? '-' : $fetchRow[$key]['Cycle']);
                $dataRow[] = ($fetchRow[$key]['Destination']                == '' ? '-' : $fetchRow[$key]['Destination']);
                $dataRow[] = ($fetchRow[$key]['Final Destination Location'] == '' ? '-' : $fetchRow[$key]['Final Destination Location']);
                $dataRow[] = ($fetchRow[$key]['Load Size Info (Height)']    == '' ? '-' : $fetchRow[$key]['Load Size Info (Height)']);
                $dataRow[] = ($fetchRow[$key]['Load Size Info (Width)']     == '' ? '-' : $fetchRow[$key]['Load Size Info (Width)']);
                $dataRow[] = ($fetchRow[$key]['Load Size Info (Length)']    == '' ? '-' : $fetchRow[$key]['Load Size Info (Length)']);
                $dataRow[] = ($fetchRow[$key]['Load Size Info (Other)']     == '' ? '-' : $fetchRow[$key]['Load Size Info (Other)']);
                $dataRow[] = ($fetchRow[$key]['Weight']                     == '' ? '-' : $fetchRow[$key]['Weight']);
                $dataRow[] = ($fetchRow[$key]['Barcode Data']               == '' ? '-' : $fetchRow[$key]['Barcode Data']);
                
 
              
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

    public function getQuery_search($search){
        $query_search = "";
        if (!empty($search)) {
            $query_search = " AND (wh LIKE '%" . $search . "%' OR `Error Name` LIKE '%" . $search . "%') ";
        } else {
            $query_search = "";
        }
        return $query_search;
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
        $start = ($start - 1) * $this->length;
        return $start;
    }

    public function getLimit(){
        $limit = " LIMIT " . max(0, $this->start) . ", " . $this->length . "";
        $this->length == -1 ? $limit = "" : '';
        return $limit;
    }

    public function getColumn($column){
        empty($column) ? $column = 0 : $column;
        return $column;
    }

    public function getColumn_sort(){
        $colunm_sort = array( 
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
        return $colunm_sort;
    }

    public function getOrderBY(){
        $column_sort = $this->column_sort;
        $orderBY = $column_sort[$this->column];
        return $orderBY;
    }

    
}

?>