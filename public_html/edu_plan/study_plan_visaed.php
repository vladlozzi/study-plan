<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
						"Помилка входу в модуль study_plan_visaed.php</p>"; require "footer.php"; exit(); }
?>
<table style="width: 100%; border: 0px;"><tr>
		<td style="border: 0px;">
<p style="text-align: center; margin-bottom: 0.5em; margin-top: 1.0em; font-size: 125%; ">
Проректор з науково-педагогічної роботи ___________ <?php echo ViceRNameByLogin(md5("prorector_m")); ?></p>
<p style="text-align: center; margin-bottom: 0.5em; margin-top: 1.0em; font-size: 125%; ">
Начальник навчального відділу ___________ <?php echo BossNameByLogin(md5("nv_boss")); ?></p>
		</td><td style="border: 0px;">
<p style="text-align: center; margin-bottom: 0.5em; margin-top: 1.0em; font-size: 125%; ">
Директор інституту ___________ <?php echo DekanNameByFacultyId($query_row['faculty_id']); ?></p>
<p style="text-align: center; margin-bottom: 0.5em; margin-top: 1.0em; font-size: 125%; ">
Завідувач випускної кафедри ____________ <?php echo DepartHeadNameByDepartId($query_row['depart_id']); ?></p>
		</td>
	</tr></table>
<?php
$_POST['chkUnSignProxy'] = (isset($_POST['chkUnSignProxy'])) ? $_POST['chkUnSignProxy'] : "";
$_POST['chkUnVisaDepartHead'] = (isset($_POST['chkUnVisaDepartHead'])) ? 
																$_POST['chkUnVisaDepartHead'] : "";
$_POST['chkUnVisaDekan'] = (isset($_POST['chkUnVisaDekan'])) ? $_POST['chkUnVisaDekan'] : "";
$_POST['chkUnVisaMethodist'] = (isset($_POST['chkUnVisaMethodist'])) ? $_POST['chkUnVisaMethodist'] : "";
$_POST['chkUnVisaBoss'] = (isset($_POST['chkUnVisaBoss'])) ? $_POST['chkUnVisaBoss'] : "";
if (!empty($_POST['chkUnSignProxy'])) {
	$UnSignProxyQuery = "UPDATE catalogWorkEduPlan 
											 SET proxy_signature = \"\" WHERE id = ".$query_row['id'];
	$UnSignProxyQuery_result = mysqli_query($conn, $UnSignProxyQuery) or 
				die("<br>Помилка сервера при запиті<br>".$UnSignProxyQuery." : ".mysqli_error($conn));
}
if (!empty($_POST['chkUnVisaDepartHead'])) {
	$UnVisaDepartHeadQuery = "UPDATE catalogWorkEduPlan 
											 SET proxy_signature = \"\", depart_head_visa = \"\" 
											 WHERE id = ".$query_row['id'];
	$UnVisaDepartHeadQuery_result = mysqli_query($conn, $UnVisaDepartHeadQuery) or 
				die("<br>Помилка сервера при запиті<br>".$UnVisaDepartHeadQuery." : ".mysqli_error($conn));
}
if (!empty($_POST['chkUnVisaDekan'])) {
	$UnVisaDekanQuery = "UPDATE catalogWorkEduPlan 
											 SET proxy_signature = \"\", depart_head_visa = \"\", dekan_visa = \"\"
											 WHERE id = ".$query_row['id'];
	$UnVisaDekanQuery_result = mysqli_query($conn, $UnVisaDekanQuery) or 
				die("<br>Помилка сервера при запиті<br>".$UnVisaDekanQuery." : ".mysqli_error($conn));
}
if (!empty($_POST['chkUnVisaMethodist'])) {
	$UnVisaMethodistQuery = "UPDATE catalogWorkEduPlan 
											 SET methodist_visa = \"\"
											 WHERE id = ".$query_row['id'];
	$UnVisaMethodistQuery_result = mysqli_query($conn, $UnVisaMethodistQuery) or 
				die("<br>Помилка сервера при запиті<br>".$UnVisaMethodistQuery." : ".mysqli_error($conn));
}
if (!empty($_POST['chkUnVisaBoss'])) {
	$UnVisaBossQuery = "UPDATE catalogWorkEduPlan 
											 SET proxy_signature = \"\", depart_head_visa = \"\", dekan_visa = \"\", 
														methodist_visa = \"\", study_depart_boss_visa = \"\"
											 WHERE id = ".$query_row['id'];
	$UnVisaBossQuery_result = mysqli_query($conn, $UnVisaBossQuery) or 
				die("<br>Помилка сервера при запиті<br>".$UnVisaBossQuery." : ".mysqli_error($conn));
}

if (!empty(trim($query_row['vicerector_visa']))) {
?>
	<p style="text-align: center; margin-bottom: 0.5em; margin-top: 1.0em; font-size: 125%; ">
	Проректор з науково-педагогічної роботи
	<span style="font-weight: bold; font-style: italic; text-decoration: underline;">
	<?php echo substr($query_row['vicerector_visa'], 0, 16)." / ".
						 substr($query_row['vicerector_visa'], 32, 10); ?>
	</span> &nbsp; <?php echo ViceRNameByLogin(md5("prorector_m")); ?>
	</p>
<?php	
}

if (!empty(trim($query_row['study_depart_boss_visa']))) {
?>
	<p style="text-align: center; margin-bottom: 0.5em; margin-top: 1.0em; font-size: 125%; ">
	Начальник навчального відділу 
	<span style="font-weight: bold; font-style: italic; text-decoration: underline;">
	<?php echo substr($query_row['study_depart_boss_visa'], 0, 16)." / ".
						 substr($query_row['study_depart_boss_visa'], 32, 10); ?> 
	</span> &nbsp; <?php echo BossNameByLogin(substr($query_row['study_depart_boss_visa'], 0, 32)); ?>
	</p>
<?php	
}

if (!empty(trim($query_row['methodist_visa']))) {
?>
	<p style="text-align: center; margin-bottom: 0.5em; margin-top: 1.0em; font-size: 125%; ">
	Методист навчального відділу 
	<span style="font-weight: bold; font-style: italic; text-decoration: underline;">
	<?php echo substr($query_row['methodist_visa'], 0, 16)." / ".
						 substr($query_row['methodist_visa'], 32, 10); ?>
	</span> &nbsp; <?php echo MethodistNameByLogin(substr($query_row['methodist_visa'], 0, 32)); ?>
	</p>
<?php	
}

if (!empty(trim($query_row['dekan_visa']))) {
?>
	<p style="text-align: center; margin-bottom: 0.5em; margin-top: 1.0em; font-size: 125%; ">
	Директор інституту  
	<span style="font-weight: bold; font-style: italic; text-decoration: underline;">
	<?php echo substr($query_row['dekan_visa'], 0, 16)." / ".
						substr($query_row['dekan_visa'], 32, 10); ?>
	</span> &nbsp; <?php echo DekanNameByFacultyId($query_row['faculty_id']);
		if (empty(trim($query_row['methodist_visa'])) and
				empty(trim($query_row['study_depart_boss_visa'])) and 
				empty(trim($query_row['vicerector_visa'])) and 
				$_SESSION['user_role'] == "ROLE_DEKAN" and
				md5($_SESSION['login']) == substr($query_row['dekan_visa'], 0, 32)
			) {
			echo " &nbsp; &nbsp; ".
				paramChekerInline("chkUnVisaDekan", $_POST['chkUnVisaDekan'], 
													"Зняти візу", "onchange=\"submit()\"");
		}	?>
	</p>
<?php	
}

if (!empty(trim($query_row['depart_head_visa']))) {
?>
	<p style="text-align: center; margin-bottom: 0.5em; margin-top: 1.0em; font-size: 125%; ">
	Завідувач випускної кафедри 
	<span style="font-weight: bold; font-style: italic; text-decoration: underline;">
	<?php echo substr($query_row['depart_head_visa'], 0, 16)." / ".
						 substr($query_row['depart_head_visa'], 32, 10); ?></span> &nbsp; 
	<?php	echo DepartHeadNameByDepartId($query_row['depart_id']);
		if (empty(trim($query_row['dekan_visa'])) and
				empty(trim($query_row['methodist_visa'])) and
				empty(trim($query_row['study_depart_boss_visa'])) and 
				empty(trim($query_row['vicerector_visa'])) and 
				$_SESSION['user_role'] == "ROLE_ZAVKAF" and
				md5($_SESSION['login']) == substr($query_row['depart_head_visa'], 0, 32)
			) {
			echo " &nbsp; &nbsp; ".
				paramChekerInline("chkUnVisaDepartHead", $_POST['chkUnVisaDepartHead'], 
													"Зняти візу", "onchange=\"submit()\"");
		}
	?>	
	</p>
<?php	
}

if (!empty(trim($query_row['proxy_signature']))) {
?>
	<p style="text-align: center; margin-bottom: 0.5em; margin-top: 1.0em; font-size: 125%; ">
	Уповноважений випускної кафедри 
	<span style="font-weight: bold; font-style: italic; text-decoration: underline;">
	<?php 
		echo substr($query_row['proxy_signature'], 0, 16)." / ".substr($query_row['proxy_signature'], 32, 10); 
	?></span><?php
		if (empty(trim($query_row['depart_head_visa'])) and 
				empty(trim($query_row['dekan_visa'])) and
				empty(trim($query_row['methodist_visa'])) and
				empty(trim($query_row['study_depart_boss_visa'])) and 
				empty(trim($query_row['vicerector_visa'])) and 
				$_SESSION['user_role'] == "ROLE_DEP_OPER" and
				md5($_SESSION['login']) == substr($query_row['proxy_signature'], 0, 32)
			) {
			echo " &nbsp; &nbsp; ".
				paramChekerInline("chkUnSignProxy", $_POST['chkUnSignProxy'], 
													"Зняти підпис", "onchange=\"submit()\"");
		}
	?>
	</p>
<?php	
}
?>