<?PHP
session_start();
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Bangkok');	
error_reporting(error_reporting() & ~E_NOTICE);
require_once (__DIR__ . '/login.class.php');

$Call = new Login();
$Site = $Call->getSite();
$Dept = $Call->getDept();
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <?php include(__DIR__ . '/header_login.php'); ?>
</head>

<body class="hold-transition login-page" style="background:url(dist/img/bg_login.png) no-repeat; background-position: 50% center;">

<section class="h-100">
    <div class="container h-100 col-md-12">
        <div class="row justify-content-md-center h-100">
            <div class="card-wrapper">
                <div class="brand text-center">
                    <img src="dist/img/SCGJWDLogo.png" alt="logo" class="logo img-responsive m-0">
                </div>
                <div class="card fat">
                    <div class="card-body">
                        <h4 class="card-title text-center w-100 text-bold" style="line-height:1.8rem;">Error ASRS Login
                        </h4><br />
                        <!--ฟอร์มลงทะเบียน-->
                        <form method="POST" id="frm_register" name="frm_register" class="my-login-validation "
                            novalidate=""><br />
                            <div class="text-md text-bold text-red mt-2 mb-1 text-center">ลงทะเบียนใช้งานระบบ</div>

                            <div class="form-group">
                                <label for="no_user">รหัสพนักงาน</label>
                                <input type="text" maxlength="7" id="no_user" name="no_user" placeholder="รหัสพนักงาน"
                                    class="numberonly form-control w-10" onKeyPress="return IsNumeric(event);"
                                    aria-describedby="inputGroupPrepend" autocomplete="off" />
                                <div class="invalid-feedback">กรอกรหัสพนักงาน</div>
                            </div>

                            <div class="form-group">
                                <label for="fullname">ชื่อ-นามสกุล พนักงาน</label>
                                <input type="text" maxlength="40" id="fullname" name="fullname"
                                    placeholder="ชื่อ-นามสกุล" class="form-control w-10" autocomplete="off" />
                                <div class="invalid-feedback">กรอกชื่อ-นามสกุล พนักงาน</div>
                            </div>

                            <div class="form-group">
                                <label for="email">ระบุอีเมล์ที่ใช้งาน</label>
                                <input type="email" class="form-control" id="email_regis" name="email_regis" required
                                    autofocus>
                                <div class="invalid-feedback">Email is invalid</div>
                            </div>

                            <div class="form-group">
                                <label for="password">รหัสผ่าน</label>
                                <input id="password_regis" type="password" class="form-control" name="password_regis"
                                    autocomplete="off" required>
                                <div class="invalid-feedback">Password is required</div>
                            </div>

                            <div class="form-group">
                                <label for="slt_manage_site">ไซต์ที่งาน:</label> <br />
                                <select class="custom-select custom-select-md rounded-3" id="slt_regis_site"
                                    name="slt_regis_site" style="width:260px;">
                                    <option value="0">เลือกไซต์งาน</option>
                                    <?PHP echo $Site; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="slt_regis_dept">แผนกของคุณ:</label><br />
                                <select class="custom-select" id="slt_regis_dept" name="slt_regis_dept" required>
                                    <option value="0">เลือกแผนก</option>
                                    <?PHP echo $Dept; ?>
                                </select>
                            </div>

                            <div class="form-group m-0">
                                <button type="button" class="btn btn-success btn-block"
                                    id="chk_register">ลงทะเบียนใช้งาน</button>
                            </div>
                            <div class="mt-4 text-center"><a href="#" class="btn-back text-pimary"><i
                                        class="fas fa-undo-alt"></i> คลิกที่นี่เพื่อกลับไปหน้าล็อกอิน</a></div>
                        </form>

                        <!-------------------------------------------------------------->
                        <form method="POST" id="frm_login" name="frm_login" class="my-login-validation" novalidate="">
                            <br />
                            <div class="form-group">
                                <label for="email">E-Mail Address</label>
                                <input id="email" type="email" class="form-control" name="email" value="" required
                                    autofocus>
                                <div class="invalid-feedback">Email is invalid</div>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" type="password" class="form-control" name="password"
                                    autocomplete="off" required>
                                <div class="invalid-feedback">Password is required</div>
                            </div>

                            <div class="form-group">
                                <label for="slt_manage_site">ไซต์ที่งาน:</label> <br />
                                <select class="custom-select custom-select-md rounded-3" id="slt_manage_site"
                                    name="slt_manage_site" style="width:260px;">
                                    <option value="0">เลือกไซต์งาน</option>
                                    <?PHP echo $Site; ?>
                                </select>
                            </div>

                            <div class="form-group m-0">
                                <button type="submit" class="btn btn-primary btn-block" id="chk_login">เข้าระบบ</button>
                            </div>
                            <div class="mt-4 text-center"><a href="#" class="btn-register text-pimary"><i
                                        class="fas fa-user-plus"></i>คลิกที่นี่เพื่อลงทะเบียนใช้งาน</a></div>
                        </form>
                    </div>
                </div>
                <div class="footer text-center">
                    Copyright &copy; 2022 &mdash; SCG JWD Logistics
                </div>
            </div>
        </div>
    </div>
</section>


<script>

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

$(document).ready(function () { //When the page has loaded

  $('form#frm_register').hide();
  
  $('.btn-back').click(function(){
        $("form#frm_register").trigger("reset");
        $('form#frm_login').fadeIn(1000).show();
        $('form#frm_register').fadeOut(1000).hide();
  });

  $('.btn-register').click(function(){
        $('form#frm_login').fadeOut(1000).hide();
        $('form#frm_register').fadeIn(1000).show();
  });


  $(document).on('click','#chk_register',function(e){    
    if($("#no_user").val()==""){
      sweetAlert("ผิดพลาด...", "กรุณากรอกรหัสพนักงาน", "error"); //The error will display
      return false;
    }else if($("#no_user").val().length<5){
      sweetAlert("ผิดพลาด...", "รหัสพนักงานไม่ถูกต้อง", "error"); //The error will display
      return false;
    }else if (!isEmail($("#email_regis").val())){
      sweetAlert("ผิดพลาด...", "รูปแบบอีเมล์ไม่ถูกต้อง!", "error"); //The error will display
      return false;
    }else if($("#password_regis").val()==""){
      sweetAlert("ผิดพลาด...", "กรุณากรอกรหัสผ่าน", "error"); //The error will display
      return false;
    }else if($("#fullname").val()==""){
      sweetAlert("ผิดพลาด...", "กรุณากรอกชื่อ-นามสกุล", "error"); //The error will display
      return false;
    }else if($('#slt_regis_site option:selected').val()<=0){
      sweetAlert("ผิดพลาด...", "เลือกไซต์งานของคุณ", "error"); //The error will display
      return false;
    }else if($('#slt_regis_dept option:selected').val()<=0){
      sweetAlert("ผิดพลาด...", "เลือกแผนกของคุณ", "error"); //The error will display
      return false;
    }else{
        var frmData = $("form#frm_register").serialize();
        $.ajax({
        url: "module/ajax_action.php",
        type: "POST",
        data: {'action':'register_user', data:frmData},
        success: function (data) {
          console.log(data); 
          data = $.trim(data.replace(/\s+/g," "));
          if(data=='mail_dup'){           
            sweetAlert("อีเมล์นี้ถูกใช้งานแล้ว!", "อีเมล์ "+($('#email_regis').val())+" \r\n ถูกใช้งานแล้ว", "error");
            return false;
          }
          if ($.isNumeric(data)) {
            swal({
              title: "ลงทะเบียนสำเร็จ!",
              text: "กรุณารออนุมัติการใช้งาน. หรือแจ้งอีเมล์ที่ใช้ลงทะเบียน \r\n ในไลน์กลุ่มเพื่อเปิดใช้งาน",
              type: "success",
              //timer: 3000
            }, 
            function(){
              window.location.href = "./";
            })
          }
        },
        error: function (response) {
          console.log("ไม่สำเร็จ! มีบางอย่างผิดพลาด!"+response);
          sweetAlert("ไม่สำเร็จ!", 'กรุณาติดต่อฝ่าย IT', "error");
          return false;
        },
      });
    }
    e.preventDefault();
});

 //sweetAlert("ผิดพลาด...", "รูปแบบอีเมล์ไม่ถูกต้อง!", "error"); //The error will display

  $("#chk_login").click(function(){
  if (!isEmail($("#email").val())){
    sweetAlert("ผิดพลาด...", "รูปแบบอีเมล์ไม่ถูกต้อง!", "error"); //The error will display
		return false;
 	}else if($("#password").val()==""){
    sweetAlert("ผิดพลาด...", "กรุณากรอกรหัสผ่าน", "error"); //The error will display
		return false;
  }else if($('#slt_manage_site option:selected').val()<=0){
    sweetAlert("ผิดพลาด...", "เลือกไซต์งานของคุณ", "error"); //The error will display
		return false;
  }else{
		return true;  
  }

  });

});

</script>


<?PHP

    if(isset($_POST['email']) && isset($_POST['password']) ){
        $GetLogin = $Call->getLogin($_POST['email'],$_POST['password'],$_POST['slt_manage_site']);
        if(!is_string($GetLogin)){
            header('Location:./');
        } else {
            echo $GetLogin;
            exit;
        }
    }

?>


</body>
</html>