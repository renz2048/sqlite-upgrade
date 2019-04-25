<?php
include_once "/kssl/WEBUI/www/global/common.php";
include_once "/kssl/WEBUI/www/global/config.php";
include_once "/kssl/WEBUI/www/user/user_helper.php";

$DB_FILE_523="sqlite:/root/global_user.db";
$DB_FILE="sqlite:/kssl/WEBUI/www/global/global_user.db";
function get_all_role_523()
{
    global $DB_FILE_523;
    $db = new PDO($DB_FILE_523);
    
    $sql_str = "SELECT * FROM tb_role";
    $stmt = $db->prepare($sql_str);
    $stmt->execute();
    
    $i = 0;
    $role_list = array();
    while($row = $stmt->fetch()) 
    {
        $role_list[$i] = array();
        $role_list[$i]["id"] = $row["RoleId"];
        $role_list[$i]["name"] = $row["RoleName"];
        $role_list[$i]["type"] = $row["RoleType"];
        $role_list[$i]["rule_name"] = $row["RuleName"];
        $role_list[$i]["rule_value"] = $row["RuleValue"];
        $role_list[$i]["description"] = $row["RoleDescription"];
        $i++;
    }
    return $role_list;
}
//增加角色,如果添加成功，返回角色ID
/*
function add_role($role)
{
    global $RULE_ROLE_USER_NAME;
    global $DB_USER_TYPE_RULE;
    global $DB_ROLE_TYPE_RULE;
    
    $role_name = $role["name"];
    $role_type = $role["type"];
    $role_description = $role["description"];
    
    if(tb_count("tb_role","RoleName",$role_name) != "0")
        return "fail";
    try{
        global $DB_FILE;
        $db = new PDO($DB_FILE);
        $sql_str = "insert into tb_role(RoleName,RoleType,RoleDescription) values(?,?,?)";
        $stmt = $db->prepare($sql_str);
        $stmt->bindParam(1,$role_name);
        $stmt->bindParam(2,$role_type);
        $stmt->bindParam(3,$role_description);
        $stmt->execute();
        
        $tmp_role = array();
        $tmp_role = tb_search("tb_role","RoleName",$role_name);
        if($role_type == $DB_ROLE_TYPE_RULE )
        {
            $user = array();
            $user["name"] = $RULE_ROLE_USER_NAME."_".$tmp_role['RoleId'];
            $user["type"] = $DB_USER_TYPE_RULE;
            $user["company"]= "";
            $user["department"]= "";
            $user["email"]= "";
            $user["tel"]= "";
            $user["issuer"] = "";
            $user["password"] = "";
            $user["rule_name"] = "";
            $user["rule_value"] = "";
            add_user($user);
            $tmp_user = array();
            $tmp_user = tb_search("tb_user","UserName",$user["name"]);
            add_user2role($tmp_role['RoleId'],$tmp_user['UserId']);
        }
        return $tmp_role['RoleId'];
    }
    catch (PDOException $e) {
        die();
    }
}
*/

    $role_list = get_all_role_523();
    foreach($role_list as $role) {
    add_role($role);
    }

?>