<?php 
try {
    $con = connect_database();
    $obj = new CRUD($con);
    
        $sql = $obj->fetchRows("SELECT * FROM tb_user WHERE fullname LIKE '%เอื้ออังกูร%'");
        print_r($sql);

    
} catch( Exception $e ) {     
    echo "Caught exception : <b>".$e->getMessage()."</b><br/>";
} finally {
    $con = null;
}

?>