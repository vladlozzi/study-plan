<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль admin.php</p>"; require "footer.php"; exit(); }
$EduFormsQuery = "SELECT id, edu_form	FROM catalogEduForm ORDER BY id"; // echo "<p>".$EduFormsQuery."</p>";
$TrueAdmin = $_SESSION['user_role'] == "ROLE_ADMIN"; $TrueBoss = FALSE;
if ($TrueAdmin) { ?>Адміністратор має повний доступ до довідників. &nbsp; <?php } ?>
<?php 
$RefersQuery="SELECT * FROM catalogCatalog ORDER BY id";
$_POST['refer'] = isset($_POST['refer']) ? $_POST['refer'] : "";
$_POST['chkPlansUnvisaed'] = isset($_POST['chkPlansUnvisaed']) ? $_POST['chkPlansUnvisaed'] : "";
echo selectCommonSelectAutoSubmit                   
		("Виберіть довідник: ", "refer", $conn, $RefersQuery, "id", $_POST['refer'], "refer", "")." &nbsp; ".
		paramChekerInline("chkPlansUnvisaed", $_POST['chkPlansUnvisaed'], 
			"Показати кафедри, які не завершили перевірку РНП", "onchange=\"submit()\"")."<br>";
if (!isset($_POST['refer']) and empty($_POST['chkPlansUnvisaed'])) return;
switch ($_POST['refer']) {
	case "1":	require "faculties.php"; break; // факультети, інститути
	case "2":	require "contingent.php"; break;  // академгрупи
	case "3":	require "departs.php"; break; // кафедри
	case "4":	require "teachers.php"; break; // викладачі
	case "5":	require "dekans.php"; break; // адміністрація інститутів, університету
	case "6":	require "departs_heads.php"; break; // завідувачі кафедр
	case "7":	require "subjects.php"; break; // навчальні дисципліни

	case "15":	require "students.php"; break; // студенти


	case "8":	require "study_fields.php"; break; // галузі знань
	case "9":	require "specs.php"; break; // спеціальності
	case "10":	require "edu_progs.php"; break; // освітні програми всередині спеціальностей, спеціалізації
	case "11":	require "work_edu_plans.php"; break; // робочі навчальні плани
	case "23":	require "departs_proxies.php"; break; // уповноважені від кафедр

	case "30":	require "edu_forms.php"; break; // форми навчання
	case "31":	require "edu_degrees.php"; break; // ступені освіти
	case "32":	require "subjects_cycles.php"; break; // цикли навчальних дисциплін
	case "34":	require "work_edu_plans_semesters.php"; break; // цикли навчальних дисциплін
} 
$_POST['radViewSelect'] = isset($_POST['radViewSelect']) ? $_POST['radViewSelect'] : "UsersActivity"; ?>
<p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.2em; font-size: 133%;">
Показати: &nbsp; 
<input type="radio" name="radViewSelect" value="UsersActivity" onclick="submit()" 
	<?php if ($_POST['radViewSelect'] == "UsersActivity") echo "checked"; ?>>Активність користувачів &nbsp; &nbsp; 
<input type="radio" name="radViewSelect" value="DepartSubject" onclick="submit()" 
	<?php if ($_POST['radViewSelect'] == "DepartSubject") echo "checked"; ?>>Витяги з РНП для кафедр &nbsp; &nbsp; 
<input type="radio" name="radViewSelect" value="PapersCount" onclick="submit()" 
	<?php if ($_POST['radViewSelect'] == "PapersCount") echo "checked"; ?>>Курсові в РНП  &nbsp; &nbsp; 
<input type="radio" name="radViewSelect" value="StudyWorkAmount" onclick="submit()" 
<?php if ($_POST['radViewSelect'] == "StudyWorkAmount") echo "checked"; ?>>Навч.навантаження &nbsp; &nbsp; 
<input type="radio" name="radViewSelect" value="AudTimeDistribution" onclick="submit()" 
	<?php if ($_POST['radViewSelect'] == "AudTimeDistribution") echo "checked"; ?>>Аудиторний час &nbsp; &nbsp; 
<input type="radio" name="radViewSelect" value="Methodist" onclick="submit()" 
	<?php if ($_POST['radViewSelect'] == "Methodist") echo "checked"; ?>>АРМ методиста
</p><?php	
switch ($_POST['radViewSelect']) {
	case "PapersCount": require "papers.php"; break;
	case "StudyWorkAmount": require "select_depart.php";
		if (!empty($_POST['deptosel'])) require "study_work_amount.php"; 
		else require "all_study_work_amount.php"; break;
	case "AudTimeDistribution": require "select_depart.php";
		if (!empty($_POST['deptosel'])) require "time_distrib_to_schedule.php"; break;
	case "DepartSubject": require "select_depart.php"; echo "<br>";
		if (!empty($_POST['deptosel'])) require "depart_subjects.php"; break;
	case "UsersActivity": require "users_stats.php"; break;
	case "Methodist": require "study_depart.php"; break;
}
if (!empty($_POST['chkPlansUnvisaed'])) require "plans_unvisaed.php"; 
?>
