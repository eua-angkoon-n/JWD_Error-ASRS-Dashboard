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
    public function getErrorNameNCode(){
        return $this->NameNCode();
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
               $machine .=  "<option value='".$value['Machine']."'>".$value['Machine']."</option>";
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
    public function NameNCode(){
        $SiteName    = $this->SiteName;
        $sql  = "SELECT `Error Code`,`Error Name` ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE wh = '$SiteName' ";
        $sql .= "GROUP BY `Error Name`, `Error Code` ";
        $sql .= "ORDER BY `Error Name` ASC";

        try {
            $con = connect_database();
            $obj = new CRUD($con);

            $fetch    = $obj->fetchRows($sql);
            $options  = "<option value='All' selected>All</option>";
            foreach ($fetch as $key => $value){
                $v = $value['Error Name'];
                if (IsNullOrEmptyString($v)){
                    $v = $value['Error Code'];
                }
                $options .=  "<option value='$v'>$v</option>";
            }

        } finally {
            $con = NULL;
        }
        return $options;
    }
}

class mainBoard {
    private array $wh;
    private $folderPath;

    public function __construct(){
        $this->wh = Setting::$Warehouse;
        $this->folderPath = __DIR__ . '/..' . Setting::$ErrorFilePath;
    }

    public function getCard(){
        return $this->CreateCard();
    }

    public function CreateCard() {
        $result = "";
        $last   = $this->getLastModificationTimesByUniqueName();
        foreach ($this->wh as $wh => $name) {
            $result .= '<div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="col-12 mb-0 pt-1">
                                <div class="card card-outline card-primary">
                                    <div class="card-header ">
                                        <h2 style="font-size:2rem"><strong>'.$name.'</strong></h2>
                                    </div>
                                <div class="card-body text-right">
                                    <h1 class="d-inline" id="'.$wh.'" style="font-size:3.5rem">0</h1>
                                    <h3 class="d-inline">&nbsp;Error</h3><br>
                                    <h6 class="d-inline"style="font-size:0.8rem">Last Modified </h6><br>
                                    <h6 class="d-inline" >'.$last[$wh]['time'].'</h6><br>
                                    <h6 class="d-inline"style="font-size:0.8rem">'.str_replace("/","",$last[$wh]['name']).'</h6>
                                </div>
                                </div>
                            </div>
                        </div>';
        }
        return $result;
    }
    public function getAllFile(){
      $this->ReadAllFile($this->folderPath,$result);
        return $result;
    }

    public function ReadAllFile($folderPath, &$resultArray, $currentFolder = '') {
        $items = glob($folderPath . '/*');

        if ($items === false) {
            return;
        }
    
        foreach ($items as $item) {
            if (is_file($item) && pathinfo($item, PATHINFO_EXTENSION) === 'csv') {
                // This is an .xlsx file, so store its name, date/time, and folder name
                $resultArray[] = [
                    'folder' => str_replace("/","",$currentFolder) ,
                    'name' => basename($item),
                    'date' => date('Y-m-d H:i:s', filemtime($item)),
                ];
            } elseif (is_dir($item)) {
                // This is a subfolder, so recurse into it with the updated folder name
                $this->ReadAllFile($item, $resultArray, $currentFolder . '/' . basename($item));
            }
        }
    }

    public function getLastModificationTimesByUniqueName(){
        $result = [];
        $this->readAllFilesByUniqueName($this->folderPath, $result);
        return $result;
    }

    public function readAllFilesByUniqueName($folderPath, &$resultArray, $currentFolder = '') {
        $items = glob($folderPath . '/*');

        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if (is_file($item)) {
                $fileName = strtolower(basename($item)); // Convert filename to lowercase

                // Extract the unique name from the filename
                $matches = [];
                if (preg_match('/error_history_(.*?)\s/', $fileName, $matches)) {
                    $uniqueName = $matches[1];

                    // Check if the unique name is in the $Warehouse array
                    if (array_key_exists($uniqueName, Setting::$Warehouse)) {
                        // Get the last modification time
                        $modificationTime = filemtime($item);
                        $formattedTime = date('d.M.y H:i:s', $modificationTime);

                        // Check if we've already encountered this unique name
                        if (!isset($resultArray[$uniqueName])) {
                            
                            $resultArray[$uniqueName] = [
                                'name' =>  $currentFolder,
                                'time' => $formattedTime,
                            ];
                        } else {
                            // If the current time is more recent, update it
                            if ($modificationTime > strtotime($resultArray[$uniqueName]['time'])) {
                                $resultArray[$uniqueName]['name'] = $currentFolder;
                                $resultArray[$uniqueName]['time'] = $formattedTime;
                            }
                        }
                    }
                }
            } elseif (is_dir($item)) {
                // This is a subfolder, so recurse into it with the updated folder name
                $this->readAllFilesByUniqueName($item, $resultArray, $currentFolder . '/' . basename($item));
            }
        }
    }


}


?>