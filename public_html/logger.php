<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль logger.php</p>"; require "footer.php"; exit(); }
function logData($userId, $userRole, $changeId, $description) { global $conn;
	$logger_query = "
insert into catalogs_log(dateIns, ipAddress, userId, userRole, changeId, changeDescription) 
values('".date("Y-n-d G:i:s")."',
 '".$_SERVER['REMOTE_ADDR']."',
 '".$userId."',
 '".$userRole."',
 '".$changeId."',
 '".$description."')";
	$logger_sql = mysqli_query($conn, $logger_query)
                    or die("Помилка запису в лог - зверніться до адміністратора:<br>".$logger_query." - ".mysqli_error($conn));
}

function logCompareData($previos, $current, $param, $paramId) {
	if($previos != $current) {
//--------------------------------------------change-------------------------
		logData($_SESSION['user_id'], $_SESSION['user_role'], $paramId, "change ".$param." - from - ".$previos." - to - ".$current." -");
	}
}
function logUpdateStudData($value) {
//--------------------------------------------change-------------------------
	logData($_SESSION['user_id'], $_SESSION['user_role'], "0", $value);
}
?>
