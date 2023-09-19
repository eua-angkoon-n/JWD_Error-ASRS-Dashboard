<?php 
require_once __DIR__ . "/../config/connect_db.inc.php";
require_once __DIR__ . "/../include/class_crud.inc.php";
require_once __DIR__ . "/../include/function.inc.php";
class DashBoard {

    private $action;
    private $SiteName;
    public function __construct($action) {
        $this->action = $action;
    }
 public function getSiteSelect(){
    return $this->siteSelect();
 }
 public function getMachine(){
    return $this->Machine();
 }

 public function siteSelect(){
    $action = $this->action;
    try {
        $con = connect_database();
        $obj = new CRUD($con);
        $fetchSite = $obj -> fetchRows("SELECT * FROM asrs_error_wh WHERE 1=1 ORDER BY site_name ASC");
        $siteSelect = '';
        foreach($fetchSite as $key => $value){
            $s = false;
            if($action == "errorLog"){
                $siteSelect.= '<li><label><input type="checkbox" name="dropdownWH[]" value="' . $value['site_name'] . '" checked> ' . $value['site_name'] . '</label></li>';
                continue;
            }
            if ($value['id'] == 1){
                $this->SiteName = $value['site_name'];
                $s = true; 
            }
            $siteSelect.= "<option value='".$value['site_name']."' ".( $s ? 'selected' : '').">".$value['site_name']."</option>";
        }
        return $siteSelect;
    } catch (PDOException $e){
        return "Database connection failed: " . $e->getMessage();
    } catch (Exception $e) {
        return "An error occurred: " . $e->getMessage();
    } finally {
        $con = null;
    }
 }
 public function Machine(){
    $SiteName    = $this->SiteName;
    $currentDate = new DateTime();
    $currentDate->sub(new DateInterval('P30D'));
    $thirtyDaysAgo = $currentDate->format('Y-m-d');
    try {
        $con   = connect_database();
        $obj   = new CRUD($con);
        $fetch = $obj->fetchRows("SELECT DISTINCT(Machine),wh FROM asrs_error_trans WHERE wh = '$SiteName' ORDER BY Machine ASC");
        $Most  = $obj->customSelect("SELECT count(*) as c,Machine FROM asrs_error_trans
            WHERE wh = '$SiteName'
            AND tran_date_time >= '$thirtyDaysAgo'
            group by Machine
            ORDER BY c DESC
            LIMIT 1");
        $machine = "<option value='All'>All</option>";
        foreach ($fetch as $key => $value){
            $machine .=  "<option value='".$value['Machine']."' ".($value['Machine']== $Most['Machine'] ? 'selected' : '').">".$value['Machine']."</option>";
        }
        return $machine;
    } catch (PDOException $e){
        return "Database connection failed: " . $e->getMessage();
    } catch (Exception $e) {
        return "An error occurred: " . $e->getMessage();
    } finally {
        $con = null;
    }
 }
}

?>