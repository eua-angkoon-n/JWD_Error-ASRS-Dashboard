<?php 

$call    = new tab2_List();
$machine = $call->getMachine();
$errName = $call->getErrorName();
$type = $call->getType();

class tab2_List {
    public function getMachine(){
        $sql  = "SELECT id, Machine ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh = 'b8' ";
        $sql .= "GROUP BY Machine ";
        $sql .= "ORDER BY Machine ASC ";

        try {
            $con = connect_database();
            $obj = new CRUD($con);

            $row = $obj->fetchRows($sql);
            // return 'sadad';
            $r   = $this->createOption($row, 'Machine');

            return $r;
        } catch (PDOException $e) {
            return "Database Error : " . $e->getMessage();
        } catch (Exception $e){
            return "Error : " . $e->getMessage();
        }finally {
            $con = NULL;
        }
    }

    public function getErrorName(){
        $sql  = "SELECT id, Error_Name ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh = 'b8' ";
        $sql .= "GROUP BY Error_Name ";
        $sql .= "ORDER BY Error_Name ASC ";

        try {
            $con = connect_database();
            $obj = new CRUD($con);

            $row = $obj->fetchRows($sql);
            $r   = $this->createOption($row, 'Error_Name');

            return $r;
        } catch (PDOException $e) {
            return "Database Error : " . $e->getMessage();
        } catch (Exception $e){
            return "Error : " . $e->getMessage();
        }finally {
            $con = NULL;
        }
    }

    private function createOption($row, $t){
        $r  = "";
        // return "<option value='0'>sadsad</option>";
        if(empty($row))
            return $r;

        foreach ($row as $k => $v){
            $r .= "<option value='".$v[$t]."'>".$v[$t]."</option>";
        }
        return $r;
    }

    public function getType(){
        $machine = Setting::$PCSMachine;
        $r  = "";
        foreach($machine as $k => $v){
            if($k == "stvb9") continue;
            $r .= "<option value='".$v."'>".strtoupper($k)."</option>";
        }
        return $r;
    }
}
?>