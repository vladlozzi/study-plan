<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль study_depart.php</p>"; require "footer.php"; exit(); }
$TrueAdmin = (($_SESSION['user_role'] == "ROLE_ADMIN") and ($_SESSION['user_id'] == 48)) or 
						($_SESSION['user_role'] == "ROLE_METHODIST");
// echo "<br>".$_SESSION['user_role']." ".$_SESSION['user_id']." -> ".($TrueAdmin ? 1 : 0)."<br>";
$TrueBoss = $_SESSION['user_role'] == "ROLE_STUDY_DEP_BOSS";
$_POST['refer'] = isset($_POST['refer']) ? $_POST['refer'] : "";
$_POST['chkPlansUnvisaed'] = isset($_POST['chkPlansUnvisaed']) ? $_POST['chkPlansUnvisaed'] : "";
$_POST['chkGenerateTimeDistrib'] = isset($_POST['chkGenerateTimeDistrib']) ? 
																	$_POST['chkGenerateTimeDistrib'] : "";
$_POST['chkGenerateTimeAmount'] = isset($_POST['chkGenerateTimeAmount']) ? 
																	$_POST['chkGenerateTimeAmount'] : "";
$_POST['chkShowTimeDistrib'] = isset($_POST['chkShowTimeDistrib']) ? $_POST['chkShowTimeDistrib'] : "";
$_POST['radCatalogSelect'] = isset($_POST['radCatalogSelect']) ? $_POST['radCatalogSelect'] : "";
$_POST['deptosel'] = isset($_POST['deptosel']) ? $_POST['deptosel'] : "";

if (! (($_SESSION['user_role'] == "ROLE_ADMIN") and ($_SESSION['user_id'] == 48)) ) {
	$RefersQuery="SELECT * FROM catalogCatalog 
							WHERE (id>=1 AND id<=4) OR (id>=6 AND id<=11) OR (id>=30 AND id<=34) ORDER BY refer";
	echo selectCommonSelectAutoSubmit
		("Виберіть довідник: ", "refer", $conn, $RefersQuery, "id", $_POST['refer'], "refer", "")." &nbsp; &nbsp; ".
		paramChekerInline("chkPlansUnvisaed", $_POST['chkPlansUnvisaed'], 
			"Показати кафедри, які не завершили перевірку РНП", "onchange=\"submit()\""); ?><br><?php
}
if ($TrueAdmin)
	echo paramChekerInline("chkGenerateTimeDistrib", $_POST['chkGenerateTimeDistrib'], 
			"Сформувати розподіл аудиторних годин по кафедрах", "onchange=\"submit()\"")." &nbsp; &nbsp; ";
echo paramChekerInline("chkShowTimeDistrib", $_POST['chkShowTimeDistrib'], 
			"Показати розподіл аудиторних годин по кафедрах", "onchange=\"submit()\"")."<br> &nbsp; &nbsp; ";
if ($TrueAdmin)
	echo paramChekerInline("chkGenerateTimeAmount", $_POST['chkGenerateTimeAmount'], 
			"Сформувати плановий обсяг навчального навантаження для кафедр на наступний навчальний рік", 
			"onchange=\"submit()\"");
if (!isset($_POST['refer']) and empty($_POST['chkPlansUnvisaed']) and 
		empty($_POST['chkGenerateTimeDistrib']) and empty($_POST['сhkShowTimeDistrib'])) return; 
if ($TrueBoss) { ?>
<p style="text-align: center; color: blue; margin-top: 0px; margin-bottom: 0px; ">Показати: 
<input type="radio" name="radCatalogSelect" value="DepartsSubjects" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "DepartsSubjects") echo "checked"; ?>>
	Витяги з РНП для кафедр &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="StudyWorkAmount" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "StudyWorkAmount") echo "checked"; ?>>
	Навч. навантаження &nbsp; &nbsp; 	
<input type="radio" name="radCatalogSelect" value="AudHours" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "AudHours") echo "checked"; ?>>
	Аудиторні в РНП  &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="DiplomaSupplement" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "DiplomaSupplement") echo "checked"; ?>>
	Додатки до дипломів
</p><?php 
}
switch ($_POST['refer']) {
	case "1":	 require "faculties.php"; break; // факультети, інститути
	case "2":	 require "groups_pages.php"; break;  // академгрупи
	case "3":	 require "departs.php"; break; // кафедри
	case "4":	 require "teachers.php"; break; // викладачі
	case "6":	 require "departs_heads.php"; break; // завідувачі кафедр
	case "7":	 $TrueAdmin = FALSE; require "subjects.php"; break; // навчальні дисципліни
	case "8":	 require "study_fields.php"; break; // галузі знань
	case "9":	 require "specs.php"; break; // спеціальності
	case "10": require "edu_progs.php"; break; // освітні програми всередині спеціальностей, спеціалізації
	case "11": require "work_edu_plans.php"; break; // робочі навчальні плани
	case "30": require "edu_forms.php"; break; // форми навчання
	case "31": require "edu_degrees.php"; break; // ступені вищої освіти
	case "32": $TrueAdmin = FALSE; require "subjects_cycles.php"; break; // цикли навчальних дисциплін
	case "34": require "work_edu_plans_semesters.php"; break; // чинність РНП
}
if (!empty($_POST['chkPlansUnvisaed'])) require "plans_unvisaed.php";
if (!empty($_POST['chkGenerateTimeDistrib'])) require "distribute_aud_time.php";
if (!empty($_POST['chkGenerateTimeAmount'])) require "distribute_study_work.php";
if (!empty($_POST['chkShowTimeDistrib'])) {
	$DepartsQuery = "SELECT id, nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf";
	echo "<br>".selectCommonSelectAutoSubmit
		("Виберіть кафедру:", "deptosel", $conn, $DepartsQuery, "id", $_POST['deptosel'], "nazva_kaf", "")."<br>";
	if (!empty($_POST['deptosel'])) require "time_distrib_to_schedule.php";
}
switch ($_POST['radCatalogSelect']) {
	case "DepartsSubjects":   
		$EduFormsQuery = "SELECT id, edu_form	FROM catalogEduForm ORDER BY id"; // echo "<p>".$EduFormsQuery."</p>";
		$DepartsQuery = "SELECT id, replace(nazva_kaf,\"Кафедра \",\"\") AS nazva_kaf 
						FROM catalogDepartment ORDER BY nazva_kaf"; // echo "<p>".$DepartsQuery."</p>";
		echo "<br>".selectCommonSelectAutoSubmit
			("Виберіть кафедру:", "deptosel", $conn, $DepartsQuery, "id", $_POST['deptosel'], "nazva_kaf", "")."<br>";
		if (!empty($_POST['deptosel'])) require "depart_subjects.php"; break;
	case "DiplomaSupplement": 
		$DepartsQuery = "SELECT id, replace(nazva_kaf,\"Кафедра \",\"\") AS nazva_kaf 
						FROM catalogDepartment WHERE depart_group_id = 1 ORDER BY nazva_kaf"; // echo "<p>".$DepartsQuery."</p>";
		echo "<br>".selectCommonSelectAutoSubmit
			("Виберіть випускну кафедру:", "deptosel", $conn, $DepartsQuery, "id", 
				$_POST['deptosel'], "nazva_kaf", "")."<br>"; 
		if (!empty($_POST['deptosel'])) require "diploma_suppl.php"; break;
	case "AudHours": require "aud_hours.php"; break;
	case "StudyWorkAmount": require "select_depart.php";
		if (!empty($_POST['deptosel'])) require "study_work_amount.php"; 
		else require "all_study_work_amount.php"; break;
} 
if (! (($_SESSION['user_role'] == "ROLE_ADMIN") and ($_SESSION['user_id'] == 48)) ) {
	require "users_stats.php";
}
?>