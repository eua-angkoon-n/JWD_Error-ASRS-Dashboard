<?PHP
ob_start();
session_start();
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . "/../../../config/connect_db.inc.php";
require_once __DIR__ . "/../../../include/setting.inc.php";

require_once __DIR__ . "/../../../include/class_crud.inc.php";
require_once __DIR__ . "/../../../include/function.inc.php";

require_once __DIR__ . "/../../../include/dataTable_processing.php";

Class DataTable extends TableProcessing {
    public $start;
    public $end;
    public $machine;
    public $type;
    public $name;
    
    public function __construct($TableSET, $data){
        parent::__construct($TableSET); //ส่งค่าไปที่ DataTable Class

        parse_str($data, $v);
        $d = explode("-", $v['reservationtime']);

        $this->start   = CustomDate(trim($d[0]), 'm/d/Y H:i', 'Y-m-d H:i:00');
        $this->end     = CustomDate(trim($d[1]), 'm/d/Y H:i', 'Y-m-d H:i:00');
        $this->machine = $v['machine'];
        $this->machine = isset($v['machine']) ? $v['machine'] : false;
        $this->name    = isset($v['errorName']) ? $v['errorName'] : false;
    
        $this->type    = isset($v['type']) ? $v['type'] : false;
    }
    public function getTable(){
        // return $this->name;
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
            // return $fetchRow;

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


        if($OrderBY){
            $sql = "SELECT * ";
            
        } else {
            $sql  = "SELECT count(asrs_error_trans.id) AS total_row ";
        }
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh='pacm' ";
        $sql .= "AND tran_date_time BETWEEN '$this->start' AND '$this->end' ";
        $sql .= $this->Select($this->machine,'Machine');
        $sql .= $this->Select($this->name,'Error_name');
        $sql .= $this->Type();

        $sql .= "$this->query_search ";
        if($OrderBY) {
            $sql .= "ORDER BY ";
            $sql .= "$this->orderBY ";
            $sql .= "$this->dir ";
            $sql .= "$this->limit ";
        }

        return $sql;
    }

    private function Type(){
        $data = $this->type;
        if(!$data)
            return "";
        $likeClauses = array_map(function($data) {
            return "Machine LIKE '{$data}%'";
        }, $data);
    
        $query = implode(' OR ', $likeClauses);
        return "AND (".$query.") ";
    }
    
    private function Select($data, $type){
        $r = "";
        if($data){
            $Quoted = array_map(function($value) {
                return "'$value'";
            }, $data);

            $v = implode(',',$Quoted);
            $r = "AND $type IN ($v) ";
        }
        return $r;
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
                $dataRow[] = "<h6 class='text-center'>$No.</h6>";
                $dataRow[] = CustomDate($value['tran_date_time'], 'Y-m-d H:i:s', 'd/m/Y H:i:s');
                $dataRow[] = !IsNullOrEmptyString($value['Machine']) ? $value['Machine'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Error_Name']) ? $value['Error_Name'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Error_Code']) ? $value['Error_Code'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Control_WCS']) ? $value['Control_WCS'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Position']) ? $value['Position'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Transfer_Equipment']) ? $value['Transfer_Equipment'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Cycle']) ? $value['Cycle'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Destination']) ? $value['Destination'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Load_Size_Info_Height']) ? $value['Load_Size_Info_Height'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Load_Size_Info_Width']) ? $value['Load_Size_Info_Width'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Load_Size_Info_Length']) ? $value['Load_Size_Info_Length'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Load_Size_Info_Other']) ? $value['Load_Size_Info_Other'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Weight']) ? $value['Weight'] : '-'; 
                $dataRow[] = !IsNullOrEmptyString($value['Barcode_Data']) ? $value['Barcode_Data'] : '-'; 
    
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

//////////////////////////////////////////////////////////////////////////////////
$column = $_POST['order']['0']['column'] + 1;
$search = $_POST["search"]["value"];
$start  = $_POST["start"];
$length = $_POST["length"];
$dir    = $_POST['order']['0']['dir'];
$draw   = $_POST["draw"];

$action = $_POST['action'];

$DataTableSearch = array(
    "Control_WCS", "Position", "Transfer_Equipment", "Cycle", "Destination", "Load_Size_Info_Height", "Load_Size_Info_Width", "Load_Size_Info_Length", "Load_Size_Info_Other", "Weight", "Barcode_Data"
);

switch($action){
    default:
        $DataTableCol = array( 
            0 => "asrs_error_trans.id",
            1 => "asrs_error_trans.id",
            2 => "asrs_error_trans.tran_date_time",
            3 => "asrs_error_trans.Machine",
            4 => "asrs_error_trans.Error_Name",
            5 => "asrs_error_trans.Error_Code",
            6 => "asrs_error_trans.Control_WCS",
            7 => "asrs_error_trans.Position",
            8 => "asrs_error_trans.Transfer_Equipment",
            9 => "asrs_error_trans.Cycle",
           10 => "asrs_error_trans.Destination",
           11 => "asrs_error_trans.Load_Size_Info_Height",
           12 => "asrs_error_trans.Load_Size_Info_Width",
           13 => "asrs_error_trans.Load_Size_Info_Length",
           14 => "asrs_error_trans.Load_Size_Info_Other",
           15 => "asrs_error_trans.Weight",
           16 => "asrs_error_trans.Barcode_Data",
        );
    break;
}

$dataGet = array(
    'column'     => $column,
    'search'     => $search,
    'length'     => $length,
    'start'      => $start,
    'dir'        => $dir,
    'draw'       => $draw,
    'dataCol'    => $DataTableCol,
    'dataSearch' => $DataTableSearch
);


switch($action) {
    default:
        $Call   = new DataTable($dataGet, $_POST['formData']);
        $result = $Call->getTable(); 
    break;
}
// print_r($_POST['formData']);
// exit;
///////////////////////////////////////////////////////////////////////////////////
// print_r ($result);
echo json_encode($result);
exit;
?>