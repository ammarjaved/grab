<?php
session_start();
include("connection.php");
class Start extends connection
{
    function __construct()
    {
        $this->connectionDB();

    }
    public function loadData()
    {


       $poi=pg_escape_string($_REQUEST['poi']);
        $bt=pg_escape_string($_REQUEST['bt']);
        $lot_no=pg_escape_string($_REQUEST['lot_no']);
        $st_name=pg_escape_string($_REQUEST['st_name']);
         $p_code=pg_escape_string($_REQUEST['p_code']);
         $state=pg_escape_string($_REQUEST['state']);
         $coor=pg_escape_string($_REQUEST['coor']);
        $wkt=pg_escape_string($_REQUEST['geom']);
        $cn=pg_escape_string($_REQUEST['cn']);
        $nh=pg_escape_string($_REQUEST['nh']);
        $uid=pg_escape_string($_REQUEST['uid']);
        $img_path=pg_escape_string($_REQUEST['img_path']);
        $gs=pg_escape_string($_REQUEST['gs']);
        $an=pg_escape_string($_REQUEST['an']);
        $mukim=pg_escape_string($_REQUEST['mukim']);
        $daerah=pg_escape_string($_REQUEST['daerah']);







        $sql_dist="INSERT INTO public.poi_data(
         name, business_type, lot_no, street_name, post_code, state, xy, geom, area_building_name_neighbourhood, city_name, created_by,image_path,grab_street,alternative_name,mukim,daerah)
        VALUES ('".$poi."', '".$bt."', '".$lot_no."', '".$st_name."', '".$p_code."', '".$state."', '".$coor."', st_geomFromText('$wkt',4326), '".$nh."', '".$cn."','".$uid."','".$img_path."','".$gs."','".$an."','".$mukim."','".$daerah."');";



//       echo $sql_dist;
//      exit();
        $result_query_dist = pg_query($sql_dist);
        if($result_query_dist)
        {
            return "data successfully saved";
        }else{
            return "failed";
        }




        $this->closeConnection();
    }

}

$json = new Start();
echo $json->loadData();
?>