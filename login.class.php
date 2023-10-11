<?php 
ob_start();
session_start();
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Bangkok');

require_once ('include/class_crud.inc.php');
require_once ('include/setting.inc.php');
require_once ('include/function.inc.php');


Class Login{

    public function getSite(){
        return $this->SQLSite();
    }

    public function getDept(){
        return $this->SQLDept();
    }

    public function SQLSite(){
        $sql  = "SELECT * ";  
        $sql .= "FROM tb_site ";
        $sql .= "WHERE site_status=1 ";
        $sql .= "ORDER BY site_initialname DESC";

        $conn = connect_database("login");
        $obj  = new CRUD($conn);

        try{
            $fetchRow = $obj->fetchRows($sql);
            $site = "";
            if (count($fetchRow)>0) {
                foreach($fetchRow as $key => $value) { 
                    //$rowSite[$key]['id_site']==1 ? $selected='selected' : $selected='';
                    $site .=  '<option  value="'.$fetchRow[$key]['id_site'].'">'.$fetchRow[$key]['site_initialname'].' - '.$fetchRow[$key]['site_name'].'</option>';
                }
            }
            return $site;
        } catch(Exception $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $conn = null;
        }
    }

    public function SQLDept(){
        $sql  = "SELECT * ";  
        $sql .= "FROM tb_dept ";
        $sql .= "WHERE dept_status=1 ";
        $sql .= "ORDER BY id_dept ASC";

        $conn = connect_database("login");
        $obj  = new CRUD($conn);

        try{
            $fetchRow = $obj->fetchRows($sql);
            $site = "";
            if (count($fetchRow)>0) {
                foreach($fetchRow as $key => $value) { 
                    $site .=  '<option  value="'.($key+1).'">'.$fetchRow[$key]['dept_name'].' - '.$fetchRow[$key]['dept_initialname'].'</option>';
                }
            }
            return $site;
        } catch(Exception $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $conn = null;
        }
    }

    public function getLogin($email,$pass,$slt_manage_site){
        if(isset($email) && isset($pass) ){
            $email = trim($email);
            $pass = trim($pass);
            $password = sha1(Setting::$keygen.$pass); //เก็บรหัสผ่านในรูปแบบ sha1 
        

            $query_login  = "SELECT tb_user.*, tb_dept.dept_initialname, tb_dept.dept_name, tb_site.site_initialname, tb_site_responsibility.ref_id_site AS chk_ref_id_site ";
            $query_login .= "FROM tb_user ";
            $query_login .= "LEFT JOIN tb_dept ON (tb_dept.id_dept=tb_user.ref_id_dept) "; 
            $query_login .= "LEFT JOIN tb_site_responsibility ON (tb_site_responsibility.ref_id_user=tb_user.id_user) "; 
            $query_login .= "LEFT JOIN tb_site ON (tb_site.id_site=$slt_manage_site) "; 
            $query_login .= "WHERE tb_user.email='$email' ";
            $query_login .= "AND tb_user.password='$password' ";
            $query_login .= "AND (tb_site_responsibility.ref_id_site=$slt_manage_site OR tb_user.ref_id_site=$slt_manage_site) ";

            $conn = connect_database("login");
            $obj  = new CRUD($conn);
            try{
                $Row = $obj->customSelect($query_login);  

                if(empty($Row['id_user'])){
                    return '<script>sweetAlert("ผิดพลาด...", "ไม่พบชื่อผู้ใช้งานตามที่ระบุ", "error");</script>';
                }

                if (((!empty($Row) && ($Row['chk_ref_id_site']!='' || $Row['ref_id_site']==$slt_manage_site)) || $Row['class_user']==5) && $Row['status_user']==1){
        
                    $_SESSION['sess_id_user'] = $Row['id_user'];
                    $_SESSION['sess_no_user'] = $Row['no_user'];
                    $_SESSION['sess_email'] = $Row['email'];
                    $_SESSION['sess_ref_id_site'] = intval($slt_manage_site);
                    $_SESSION['sess_site_initialname'] = $Row['site_initialname'];
                    $_SESSION['sess_fullname'] = $Row['fullname'];
                    $_SESSION['sess_class_user'] = $Row['class_user'];
                    $_SESSION['sess_id_dept'] = $Row['ref_id_dept'];
                    $_SESSION['sess_dept_name'] = $Row['dept_name'];
                    $_SESSION['sess_dept_initialname'] = $Row['dept_initialname'];      
                    $_SESSION['sess_status_user'] = $Row['status_user'];
                    $_SESSION['sess_popup_howto'] = 0;
                 
                    $fetchPermission= $obj->fetchRows("SELECT tb_permission.* FROM tb_permission WHERE ref_class_user=".$Row['class_user']."");
                    foreach($fetchPermission as $key=>$value){
                      $_SESSION['module_access'] =  $fetchPermission[$key]['module_name'].'-'.$fetchPermission[$key]['accept_denied'];
                    }
                    
                    return true;
        
                  } else {
                    if($Row['status_user']==2){       
                        return '<script>sweetAlert("ถูกระงับใช้งาน", "คุณถูกระงับการใช้งาน \r\n กรุณาติดต่อฝ่าย IT เพื่อตรวจสอบ", "error");</script>';
                      }else if($Row['status_user']==3){        
                        return '<script>sweetAlert("รออนุมัติ...", "ชื่อผู้ใช้งานนี้ \r\nอยู่ระหว่างรออนุมัติการใช้", "error");</script>';
                      }else{
                        return '<script>sweetAlert("ผิดพลาด...", "ชื่อผู้ใช้ระบบหรือเลือกไซต์งานไม่ถูกต้อง ", "error");</script>';
                      }
                  }

            } finally {
                $conn = null;
            }
        } 
    }
}

Class Register{
    private $output;
    public function __construct($data){
        parse_str($_POST['data'], $output);
        $this->output = $output;
    }

    public function getIDRegister(){
        return $this->TryRegister();
    }

    public function TryRegister(){
        $output = $this->output;
        $mail_dup = $this->chkMailDup();
        // return $mail_dup;
        if($mail_dup){
            return "mail_dup";
        }
        
        $output['password_regis'] = sha1(Setting::$keygen.$output['password_regis']); 
        $insertRow = [
            'no_user' => (!empty($output['no_user'])) ? $output['no_user'] : '',
            'password' => (!empty($output['password_regis'])) ? $output['password_regis'] : '',
            'email' => (!empty($output['email_regis'])) ? $output['email_regis'] : '',
            'line_token' => NULL,
            'fullname' => (!empty($output['fullname'])) ? $output['fullname'] : '',
            'sex' => NULL,
            'phone' => NULL,
            'photo' => NULL,
            'class_user' => 1,
            'ref_id_site' => (!empty($output['slt_regis_site'])) ? $output['slt_regis_site'] : '',
            'ref_id_dept' => (!empty($output['slt_regis_dept'])) ? $output['slt_regis_dept'] : '',
            'ref_id_position' => NULL,
            'status_user' => 3,
            'create_date' => (date('Y-m-d H:i:s')),
            'ref_id_user_add' => 0,
            'edit_date' => NULL,
            'ref_id_user_edit' => NULL,
            'latest_login' => NULL,
            'ip_address' => NULL,
        ];

        $conn  = connect_database("login");
        $obj   = new CRUD($conn);

        try{
            $rowID = $obj->addRow($insertRow, "tb_user");
            if($rowID!=NULL){
                $insertRow = [				
                    'ref_id_user' => $rowID,
                    'ref_id_site' => (!empty($output['slt_regis_site'])) ? $output['slt_regis_site'] : '',
                ];
                $rowSite = $obj->addRow($insertRow, "tb_site_responsibility");
            }
            return $rowID;
        } catch (PDOException $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $conn = null;
        }
    }

    public function chkMailDup(){
        $email = $this->output['email_regis'];
        $sql   = "SELECT count(id_user) AS total_row ";
        $sql  .= "FROM tb_user ";
        $sql  .= "WHERE email = '".(trim($email))."';";

        $conn  = connect_database("login");
        $obj   = new CRUD($conn);

        try{
            $totalRow =$obj->getCount($sql);
            if($totalRow!=0){
                return $totalRow;
            }
            return false;
        } catch (PDOException $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $conn = null;
        }
    }
}
?>