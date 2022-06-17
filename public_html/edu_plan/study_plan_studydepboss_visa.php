<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
						"Помилка входу в модуль study_plan_studydepboss_visa.php</p>"; require "footer.php"; exit(); }
$_POST['chkStudyDepBossVisa'] = (isset($_POST['chkStudyDepBossVisa'])) 
																	? $_POST['chkStudyDepBossVisa'] : "";
if (!empty($_POST['chkSubjSums']) and ($_SESSION['chkStudyDepBossVisa'] == 0) 
			and	!empty($query_row['methodist_visa'])) {
	if (!empty($_POST['chkStudyDepBossVisa'])) {
		$UpdateSignQuery = "UPDATE catalogWorkEduPlan 
												SET study_depart_boss_visa = '".md5($_SESSION['login']).date("d.m.Y")."' 
												WHERE id = ".$query_row['id'];
		$UpdateSignQuery_result = mysqli_query($conn, $UpdateSignQuery) or 
					die("<br>Помилка сервера при запиті<br>".$UpdateSignQuery." : ".mysqli_error($conn));;
	} ?>
	<p style="text-align: center; margin-bottom: 0.2em; margin-top: 0.3em; font-size: 125%;
				color: blue; background-color: RGB(224,224,224);">
<?php	echo paramCheker('chkStudyDepBossVisa', $_POST['chkStudyDepBossVisa'], 
												"Завізувати", "onchange=\"submit()\""); ?>
	</p><?php
} ?>
