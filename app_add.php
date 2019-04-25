<?php
include_once "/kssl/WEBUI/www/global/common.php";
include_once "/kssl/WEBUI/www/global/config.php";
include_once "/kssl/WEBUI/www/user/app_helper.php";

$DB_APP_FILE_523="sqlite:/root/apps.db";
$DB_APP_FILE="sqlite:/kssl/HRP/cfg/default/apps.db";
function get_all_apps_523()
{
	global $DB_APP_FILE_523;
	
	$db = new PDO($DB_APP_FILE_523);
	$sql_str = "SELECT * FROM UserMapApps";
	
	$stmt = $db->prepare($sql_str);
	$stmt->execute();
	
	$i = 0;
	$app_list = array();
	while($row = $stmt->fetch()) 
	{
		$app_list[$i] = array();
		$app_list[$i]["AppID"] = $row["AppID"];
		$app_list[$i]["AppName"] = $row["AppName"];
		$app_list[$i]["AppProtocol"] = $row["AppProtocol"];
		$app_list[$i]["AppIp"] = $row["AppIp"];
		$app_list[$i]["AppPort"] = $row["AppPort"];
		$app_list[$i]["AppMethod"] = $row["AppMethod"];
		if(($row["TransACL"] != "0") && ($row["TransACL"] != "1")) {
            $row["TransACL"] = "0";
        }
		$app_list[$i]["TransACL"] = $row["TransACL"];
		
        $protocol_disps = array( "1"  => "tcp", "2" => "http",  "3" =>"https" );
      if( $row["AppProtocol"] == "2" && $row["AppPort"] == "80" )
        $row["AppURL"] = "^http://".$app["AppIp"]."/.*$";
      else if( $row["AppProtocol"] == "3" && $row["AppPort"] == "443" )
        $row["AppURL"] = "^https://".$row["AppIp"]."/.*$";
      else if( $row["AppProtocol"] == "1" && $row["AppPort"] == "0" )
        $row["AppURL"] = "^tcp://".$row["AppIp"].":.*$";
      else if( $row["AppProtocol"] == "1" )
        $row["AppURL"] = "^tcp://".$row["AppIp"].":".$row["AppPort"]."$";
      else
        $row["AppURL"] = "^".$protocol_disps[$row["AppProtocol"]]."://".$row["AppIp"].":".$row["AppPort"]."/.*$";
	
	
		$app_list[$i]["AppURL"] = $row["AppURL"];
	
		$i++;
	}
	return $app_list;
}
//增加应用
function add_app_rz($app)
{
    try{
        global $DB_APP_FILE;
        $db = new PDO($DB_APP_FILE);
        $sql_str = "insert into UserMapApps(AppName,AppProtocol,AppIp,AppPort,AppURL,AppMethod,TransACL,AppID) values(?,?,?,?,?,?,?,?)";
        $appName = $app["AppName"];
        $stmt = $db->prepare($sql_str);
        $stmt->bindParam(1,$appName);
        $stmt->bindParam(2,$app["AppProtocol"]);
        $stmt->bindParam(3,$app["AppIp"]);
        $stmt->bindParam(4,$app["AppPort"]);
        $stmt->bindParam(5,$app["AppURL"]);
        $stmt->bindParam(6,$app["AppMethod"]);
        $stmt->bindParam(7,$app["TransACL"]);
        $stmt->bindParam(8,$app["AppID"]);
        $stmt->execute();
    }
    catch (PDOException $e) {
        return "fail";
    }
    return "success";
}

$applist = get_all_apps_523();
foreach($applist as $app) {
	add_app_rz($app);
}
?>