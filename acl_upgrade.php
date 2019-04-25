<?php
include_once "/kssl/WEBUI/www/global/common.php";
include_once "/kssl/WEBUI/www/global/config.php";
include_once "/kssl/WEBUI/www/user/app_helper.php";

$DB_APP_FILE_523="sqlite:/root/apps.db";
$DB_APP_FILE="sqlite:/kssl/HRP/cfg/default/apps.db";

function getAllAclInfo_523()
{
    
    global $DB_APP_FILE_523;
    $db = new PDO($DB_APP_FILE_523);
    $sql_str = "SELECT * FROM acls";
    
    $stmt = $db->prepare($sql_str);
    $stmt->execute();
    
    $i = 0;
    $user_list = array();
    while($row = $stmt->fetch()) 
    {
        $user_list[$i] = array();
        $user_list[$i]["AclID"] = $row["AclID"];
        $user_list[$i]["AppID"] = $row["AppID"];
        $user_list[$i]["RoleID"] = $row["RoleID"];
        $user_list[$i]["AllowIpSubnets"] = $row["AllowIpSubnets"];
        $user_list[$i]["AllowTimeBegin"] = $row["AllowTimeBegin"];
        $user_list[$i]["AllowTimeEnd"] = $row["AllowTimeEnd"];
        $i++;
    }
    return $user_list;
}

function add_acl_rule_rz($aclarr)
{
    global $DB_APP_FILE;
    $db = new PDO($DB_APP_FILE);
	
	try{
	    $sql_str = "insert into acls(AppID, RoleId, AllowIpSubnets, AllowTimeBegin, AllowTimeEnd, AclDescription) values(?,?,?,?,?,?)";
        $stmt = $db->prepare($sql_str);
        $stmt->bindParam(1,$aclarr['AppID']);
        $stmt->bindParam(2,$aclarr['RoleID']);
        $stmt->bindParam(3,$aclarr['AclIp']);
        $stmt->bindParam(4,$aclarr['AclBegtime']);
        $stmt->bindParam(5,$aclarr['AclEndtime']);
        $stmt->bindParam(6,$aclarr['AclDescription']);
        $stmt->execute();
	}
    catch (PDOException $e) {
        return "fail";
    }
    return "success";
}

//获取523网关中的acl
$acl_lists = getAllAclInfo_523();

foreach($acl_lists as $acl_list) {
	//添加AllowIpSubnets\AllowTimeBegin\AllowTimeEnd
	$acl_list["AclIp"] = "0.0.0.0/1,128.0.0.0/1";
	$acl_list["AclDescription"] = "";
	$acl_list["AclBegtime"] = "0:0";
	$acl_list["AclEndtime"] = "23:59";
	add_acl_rule_rz($acl_list);
}
?>