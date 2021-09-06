<?php
class DB
{
  public static function api_key()
  {
    return 'AES';
  }
  public static function connect($type)
  {

    $data=array('host'=>'localhost','user'=>'sibin','password'=>'SibinThomas@123','db'=>'Santax','tables'=>'*');
    $host=$data['host'];
    $user=$data['user'];
    $pass=$data['password'];
    $dbname=$data['db'];
    $tables=$data['tables'];
    if($type=='connection')
    {
    $link = mysqli_connect($host,$user,$pass, $dbname);
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }
    else
    {
    return $link;
    }
    }
    if($type=='table')
    {
      return $tables;
    }
  }
}
?>
