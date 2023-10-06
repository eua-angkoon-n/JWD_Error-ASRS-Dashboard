<?php 
require_once __DIR__ . "/../../config/connect_db.inc.php";
require_once __DIR__ . "/../../include/class_crud.inc.php";
require_once __DIR__ . "/../../include/function.inc.php";

Class MainDashboard {
    private $date;
    private $fetchCard;
    private $fetchChart;
    private $fetchBar;
    public function __construct($date){
        $this->date       = $date ?? NULL;
        $this->fetchCard  = $this->SQLQuery();
        $this->fetchChart = $this->chartQuery();
        $this->fetchBar   = $this->barQuery();
    }

    public function getCard(){
        return $this->createArrError();
    }

    public function getChart(){
        return $this->fetchChart;
    }

    public function getBar(){
        return $this->fetchBar;
    }

    public function SQLQuery(){
        $date = $this->date;

        $sql  = "SELECT wh,count(*) as count ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE ";
        $sql .= "MONTH(asrs_error_trans.tran_date_time) = MONTH(CURRENT_DATE) ";
        // if($date[0] == $date[1])
        //     $sql .= "date(asrs_error_trans.tran_date_time) = '".$date[1]."'";
        // else
        //     $sql .= "tran_date_time BETWEEN '". $date[1] ."' AND '". $date[0] ."' ";
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
        $counts    = $this->fetchCard;
        $Warehouse = Setting::$Warehouse;

        $filteredData = array();

        foreach ($Warehouse as $key => $description) {
            $found = false;
            if($counts){
                foreach ($counts as $countItem) {
                    if ($countItem['wh'] === $key) {
                        $filteredData[$key] = $countItem['count'];
                        $found = true;
                        break;
                    }
                }        
            }
            if (!$found) {
                $filteredData[$key] = 0; // Set to 0 if the key is not found in counts
            }
        }
        return $filteredData;
    }

    public function chartQuery(){
        $sql  = "SELECT "; 
        $sql .= "YEAR(tran_date_time) AS Year, ";
        $sql .= "MONTH(tran_date_time) AS Month, ";
        $sql .= "count(id) AS TotalValue ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE ";
        $sql .= "YEAR(tran_date_time) = YEAR(CURRENT_DATE) ";
        $sql .= "GROUP BY YEAR(tran_date_time), MONTH(tran_date_time) ";
        $sql .= "ORDER BY YEAR(tran_date_time), MONTH(tran_date_time);";
        // return $sql;
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

    public function barQuery(){
        $sql  = "SELECT count(id) as Total, `Error Code`, `Error Name` ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE month(tran_date_time) = month(CURRENT_DATE) ";
        $sql .= "GROUP BY `Error Code`, `Error Name` ";
        $sql .= "ORDER BY Total DESC "; 
        $sql .= "LIMIT 5";

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


}
?>