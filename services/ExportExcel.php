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
        $status=$_REQUEST['status'];


            $sql = 'select id, name as "POI Name", business_type as "Business Type", lot_no as "Lot No", street_name as "Street Name", post_code as "Post Code", state as "State", xy, 
			area_building_name_neighbourhood as "Area", city_name as "City Name", image_path as "Photo", grab_street as "Grab Street", alternative_name as "Alternative Name"
			from poi_data where date_time::date>='."'".$date1."'".'::date and date_time::date<='."'".$date2."'".'::date and name is not null and business_type is not null  and
                street_name is not null and  post_code is not null and state is not null and xy is not null 
                and area_building_name_neighbourhood is not null and city_name is not null and image_path is not null and
                grab_street is not null and image_path<>'."''".';';

          //  echo $sql;
		  
      // exit();
        $result_query = pg_query($sql);
        $flag = false;
        while ($row = pg_fetch_assoc($result_query)) {
			
			$sql_status="update poi_data set status='exported' where id=".$row["id"];
			pg_query($sql_status);
			   $path=$row["Photo"];
			   $pic=explode('/',$path);
			   $size=sizeof($pic)-1;
			   $row["Photo"]='exported_images/'.$pic[$size];
			   
			//   echo $pic[$size];
            if($status=='yes') {
                copy('../..' . $path, '../../exported_images/' . $pic[$size]);
            }
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