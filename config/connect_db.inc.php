<?php
require_once __DIR__. '/../config/mysecret.php';

function connect_database($DBMode = "")
{
  $con = MySecret::$conNow;
  switch($con){
    case 'db':
      if($DBMode == "login")
        $database = MySecret::$dbDatabaseLogin;
      else
        $database = MySecret::$dbDatabase;
      $user = MySecret::$dbUser;
      $password =  MySecret::$dbPass;
      $host = MySecret::$dbHost;
      $port = MySecret::$dbPort;
      break;
    case 'local';
      if($DBMode == "login")
        $database = MySecret::$dbDatabaseLogin;
      else
        $database = MySecret::$LocalDatabase;
      $user = MySecret::$LocalUser;
      $password =  MySecret::$LocalPass;
      $host = MySecret::$LocalHost;
      $port = MySecret::$LocalPort;
      break;
    }

  try {
    $options  = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_PERSISTENT => true,
      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'"
    ];
    $db = new PDO(
      "mysql:host=$host;port=$port;dbname=$database;charset=utf8",
      $user,
      $password,
      $options
    );
    //$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  } catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
}


?>    
