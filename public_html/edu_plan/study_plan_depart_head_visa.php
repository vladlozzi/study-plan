<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
						"Помилка входу в модуль depart_head_visa.php</p>"; require "footer.php"; exit(); }
$_POST['chkDepartHeadVisa'] = (isset($_POST['chkDepartHeadVisa'])) ? $_POST['chkDepartHeadVisa'] : "";
// echo "<br>".$_SESSION['chkDepartHeadVisa'];
if (!empty($_POST['chkSubjSums']) and ($_SESSION['chkDepartHeadVisa'] == 0) 
			and	!empty($query_row['proxy_signature'])) {
	if (!empty($_POST['chkDepartHeadVisa'])) {
		$UpdateSignQuery = "UPDATE catalogWorkEduPlan 
												SET depart_head_visa = '".md5($_SESSION['login']).date("d.m.Y")."' 
												WHERE id = ".$query_row['id'];
		$UpdateSignQuery_result = mysqli_query($conn, $UpdateSignQuery) or 
					die("<br>Помилка сервера при запиті<br>".$UpdateSignQuery." : ".mysqli_error($conn));;
	} ?>
	<p style="text-align: center; margin-bottom: 0.2em; margin-top: 0.3em; font-size: 125%;
				color: blue; background-color: RGB(224,224,224);">
<?php	echo paramCheker('chkDepartHeadVisa', $_POST['chkDepartHeadVisa'], 
												"Завізувати", "onchange=\"submit()\""); ?>
	</p><?php
} ?>
