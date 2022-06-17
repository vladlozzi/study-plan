<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
						"Помилка входу в модуль study_plan_methodist_visa.php</p>"; require "footer.php"; exit(); }
$_POST['chkMethodistVisa'] = (isset($_POST['chkMethodistVisa'])) ? $_POST['chkMethodistVisa'] : "";
if (!empty($_POST['chkSubjSums']) and ($_SESSION['chkMethodistVisa'] == 0) 
			and	!empty($query_row['dekan_visa'])) {
	if (!empty($_POST['chkMethodistVisa'])) {
		$UpdateSignQuery = "UPDATE catalogWorkEduPlan 
												SET methodist_visa = '".md5($_SESSION['login']).date("d.m.Y")."' 
												WHERE id = ".$query_row['id'];
		$UpdateSignQuery_result = mysqli_query($conn, $UpdateSignQuery) or 
					die("<br>Помилка сервера при запиті<br>".$UpdateSignQuery." : ".mysqli_error($conn));;
	} ?>
	<p style="text-align: center; margin-bottom: 0.2em; margin-top: 0.3em; font-size: 125%;
				color: blue; background-color: RGB(224,224,224);">
<?php	echo paramCheker('chkMethodistVisa', $_POST['chkMethodistVisa'], 
												"Завізувати", "onchange=\"submit()\""); ?>
	</p><?php
} ?>
