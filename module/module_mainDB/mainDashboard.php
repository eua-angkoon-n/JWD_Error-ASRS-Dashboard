<?php 
require_once __DIR__ . "/../../config/connect_db.inc.php";
require_once __DIR__ . "/../../include/class_crud.inc.php";
require_once __DIR__ . "/../../include/function.inc.php";

Class MainDashboard {
    private $date;
    private $fetchRow;
    public function __construct($date){
        $this->date      = $date ?? NULL;
        $this->fetchRow  = $this->SQLQuery();
    }

    public function test(){
        return $this->createArrError();
    }

    public function getData(){
        return $this->date;
    }

    public function SQLQuery(){
        $date = $this->date;

        $sql  = "SELECT wh,count(*) as count ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE ";
        if($date[0] == $date[1])
            $sql .= "tran_date_time = '". $date[0] ."' ";
        else
            $sql .= "tran_date_time BETWEEN '". $date[1] ."' AND '". $date[0] ."' ";
        $sql .= "GROUP BY wh";

        $con  = connect_database();
        $obj  = new CRUD($con);

        try{
            $fetchRow = $obj->fetchRows($sql);

            if(empty($fetchRow))
                return false;
            else {
                return $fetchRow;
            }
        } catch(Exception $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $con = null;
        }
    }

    public function createArrError(){
        $counts    = $this->fetchRow;
        $Warehouse = Setting::$Warehouse;

        $filteredData = array();

        foreach ($Warehouse as $key => $description) {
            $found = false;
    
            foreach ($counts as $countItem) {
                if ($countItem['wh'] === $key) {
                    $filteredData[$key] = $countItem['count'];
                    $found = true;
                    break;
                }
            }
    
            if (!$found) {
                $filteredData[$key] = ""; // Set to empty string if the key is not found in counts
            }
        }
    
        return json_encode($filteredData);
    }
}
?>