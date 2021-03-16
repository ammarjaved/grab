<?php
session_start();
include("connection.php");
class ExportExcel extends connection
{
    function __construct()
    {
        $this->connectionDB();

    }
    public function loadData()
    {

        //$key=$_GET['key'];


        $filename = "grab_data.xls"; // File Name

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");


        $date1=$_REQUEST['sd'];
        $date2=$_REQUEST['ed'];


            $sql = "select id, name, business_type, lot_no, street_name, post_code, state, xy, 
			area_building_name_neighbourhood, city_name, image_path, grab_street, alternative_name
			from poi_data where date_time::date>='$date1'::date and date_time::date<='$date2'::date and name is not null and business_type is not null  and
                street_name is not null and  post_code is not null and state is not null and xy is not null 
                and area_building_name_neighbourhood is not null and city_name is not null and image_path is not null and
                grab_street is not null and image_path<>'';";

          //  echo $sql;
		  
      // exit();
        $result_query = pg_query($sql);
        $flag = false;
        while ($row = pg_fetch_assoc($result_query)) {
			
			$sql_status="update poi_data set status='exported' where id=".$row["id"];
			pg_query($sql_status);
			   $path=$row["image_path"];
			   $pic=explode('/',$path);
			   $size=sizeof($pic)-1;
			   $row["image_path"]='exported_images/'.$pic[$size];
			   
			//   echo $pic[$size];
			 copy('../..'.$path, '../../exported_images/'.$pic[$size]);
           //  exit();
			
            if (!$flag) {
                // display field/column names as first row
                echo implode("\t", array_keys($row)) . "\r\n";
                $flag = true;
            }
            echo implode("\t", array_values($row)) . "\r\n";
			
        }
    }

}

$excel = new ExportExcel();
echo $excel->loadData();
?>