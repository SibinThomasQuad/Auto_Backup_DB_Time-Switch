<?php
//Core function
class TimeTravel
{
public static function show_time()
{

  $fileList = glob('Times/*');

//Loop through the array that glob returned.
$times=array();
foreach($fileList as $filename){
   $time=str_replace("Times/","",$filename);
   $time=str_replace(".sql","",$time);
   array_push($times,$time);
}
return $times;

}
public static function restore($link,$time){
  $file=$time.'.sql';
  $sql=file_get_contents("Times/".$file);
  if(mysqli_query($link, $sql))
  {
  return 1;
  }


}
public static function backup_tables($link,$tables) {



    mysqli_query($link, "SET NAMES 'utf8'");

    //get all of the tables
    if($tables == '*')
    {
        $tables = array();
        $result = mysqli_query($link, 'SHOW TABLES');
        while($row = mysqli_fetch_row($result))
        {
            $tables[] = $row[0];
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }

    $return = '';
    //cycle through
    foreach($tables as $table)
    {
        $result = mysqli_query($link, 'SELECT * FROM '.$table);
        $num_fields = mysqli_num_fields($result);
        $num_rows = mysqli_num_rows($result);

        $return.= 'DROP TABLE IF EXISTS '.$table.';';
        $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";
        $counter = 1;

        //Over tables
        for ($i = 0; $i < $num_fields; $i++)
        {   //Over rows
            while($row = mysqli_fetch_row($result))
            {
                if($counter == 1){
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                } else{
                    $return.= '(';
                }

                //Over fields
                for($j=0; $j<$num_fields; $j++)
                {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                }

                if($num_rows == $counter){
                    $return.= ");\n";
                } else{
                    $return.= "),\n";
                }
                ++$counter;
            }
        }
        $return.="\n\n\n";
    }

    //save file
    $fileName = date('Y-m-d').':'.date("h:i:sa").'.sql';
    $handle = fopen('Times/'.$fileName,'w+');
    fwrite($handle,$return);
    if(fclose($handle)){
        return 1;

    }
}
}


?>
