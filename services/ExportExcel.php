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


       // $filename = "grab_data.xls"; // File Name
        $date1=$_REQUEST['sd'];
        $date2=$_REQUEST['ed'];
        $status=$_REQUEST['status'];
        $filename=$date1.'_'.$date2.".xls";
        if (!file_exists("../../".$date1."_".$date2)) {
            mkdir("../../" . $date1 . "_" . $date2);
        }

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");





            $sql = 'select id, name as "POI Name", business_type as "Business Type", lot_no as "Lot No", street_name as "Street Name", post_code as "Post Code", state as "State", xy, 
			area_building_name_neighbourhood as "Area", city_name as "City Name", image_path as "Photo", grab_street as "Grab Street", alternative_name as "Alternative Name",created_by
			from poi_data where date_time::date>='."'".$date1."'".'::date and date_time::date<='."'".$date2."'".'::date and name is not null and business_type is not null  and
                street_name is not null and  post_code is not null and state is not null and xy is not null 
                and area_building_name_neighbourhood is not null and city_name is not null and image_path is not null and
                grab_street is not null and image_path<>'."''".' ;';

          //  echo $sql;
		  
      // exit();
        $result_query = pg_query($sql);
        $flag = false;
        while ($row = pg_fetch_assoc($result_query)) {
			

			   $path=$row["Photo"];
			   $pic=explode('/',$path);
			   $size=sizeof($pic)-1;
			  // $row["Photo"]=$date1."_".$date2.'/'.$pic[$size];
               $row["Photo"]=$date1."_".$date2.'/'.$row["id"].'.jpg';

            $sql_status11="select user_name from tbl_users where id=". $row["created_by"];
            $result_query11=pg_query($sql_status11);
            $row1 = pg_fetch_assoc($result_query11);
            $row["created_by"]=$row1['user_name'];
			   
			//   echo $pic[$size];
            if($status=='yes') {
               // copy('../..' . $path, '../../'.$date1."_".$date2.'/' . $pic[$size]);
                copy('../..' . $path, '../../'.$date1."_".$date2.'/' . $row["id"].'.jpg');

                $sql_status="update poi_data set status='exported' where id=".$row["id"];
                pg_query($sql_status);
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