<?php
class Processing{

  function __construct(){

  }
  public function Start_Time(){
  return time()+ microtime(true);
  }
  public  function End_Time(){
  return time()+ microtime(true);
  }
  public function Total_Time($ini_t,$end_t){
  return round($end_t - $ini_t,4);
  }
  public function show_msg($time){
   echo "Process Time: $time Second.";
  }
}
?>