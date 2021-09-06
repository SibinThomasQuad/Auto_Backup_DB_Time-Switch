<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('TimeMachine/travel.php');
require('config.php');
if($_GET['api_key'] == DB::api_key())
{
$process=$_GET['process'];
switch ($process) {
  case "backup":
    if(TimeTravel::backup_tables(DB::connect('connection'),DB::connect('table')) == 1)
    {
    $array_result=array('status'=>true,'message'=>'Data backup done');
    echo json_encode($array_result);
    break;
    }
  case "showtime":
        $time_list=TimeTravel::show_time();
        echo json_encode($time_list);
  break;
  case "restore":
        if(TimeTravel::restore(DB::connect('connection'),$_GET['time'])==1)
        {
          $array_result=array('status'=>true,'message'=>'Data restore done');
          echo json_encode($array_result);
        }
  break;
  default:
    $array_result=array('status'=>false,'message'=>'No process selected');
    echo json_encode($array_result);
}
}
else {
  $array_result=array('status'=>false,'message'=>'Access Denied');
  echo json_encode($array_result);
}
?>
