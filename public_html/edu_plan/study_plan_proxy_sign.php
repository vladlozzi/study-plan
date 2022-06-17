<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
						"Помилка входу в модуль study_plan_proxy_sign.php</p>"; require "footer.php"; exit(); }
$_POST['chkSign'] = (isset($_POST['chkSign'])) ? $_POST['chkSign'] : "";
if (!empty($_POST['chkSubjSums']) and ($_SESSION['chkProxySign'] == 0)) {
	if (!empty($_POST['chkSign'])) {
		$UpdateSignQuery = "UPDATE catalogWorkEduPlan 
												SET proxy_signature = '".md5($_SESSION['login']).date("d.m.Y")."' 
												WHERE id = ".$query_row['id'];
		$UpdateSignQuery_result = mysqli_query($conn, $UpdateSignQuery) or 
					die("<br>Помилка сервера при запиті<br>".$UpdateSignQuery." : ".mysqli_error($conn));;
	}
?>
	<p style="text-align: center; margin-bottom: 0.2em; margin-top: 0.3em; font-size: 125%;
				color: blue; background-color: RGB(224,224,224);">
<?php	echo paramCheker('chkSign', $_POST['chkSign'], "Підписати", "onchange=\"submit()\""); ?>
	</p><?php
} ?>
