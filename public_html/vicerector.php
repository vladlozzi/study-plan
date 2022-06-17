<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль vicerector.php</p>"; require "footer.php"; exit(); }
$TrueAdmin = FALSE; $TrueBoss = TRUE;
// Перелік ступенів в.о. для вибору
$DegreesQuery = "SELECT id, degree_name FROM catalogEduDegree WHERE id < 9 ORDER BY id";
$BaseDegreesQuery = "SELECT id, degree_name FROM catalogEduDegree ORDER BY id DESC";
// Перелік спеціальностей для вибору
$SpecialtiesQuery = "SELECT id, 
														CONCAT(specialty_b_code,\" \",specialty_name,\" (Перелік \",list,\")\") AS specialty_codename
										FROM catalogSpecialty ORDER BY specialty_name";
// Перелік спеціалізацій для вибору
$EduProgramsQuery = "SELECT id, CONCAT(\"(\",eduprogram_code,\") \",eduprogram_name) AS eduprogram_codename
										FROM catalogEduProgram ORDER BY eduprogram_name"; // echo "<p>".$EduProgramsQuery."</p>";
$EduProgramsQueries = array();
// Перелік форм навчання для вибору
$EduFormsQuery = "SELECT id, edu_form FROM catalogEduForm ORDER BY id"; // echo "<p>".$EduFormsQuery."</p>";
// Перелік Інститутів (факультетів) для вибору
$FacultiesQuery = "SELECT id, fakultet_name FROM catalogFakultet ORDER BY fakultet_name";
// Перелік кафедр 
$DepartsQuery = "SELECT id, nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf"; // echo "<p>".$DepartsQuery."</p>";
$_POST['radCatalogSelect'] = isset($_POST['radCatalogSelect']) ? 
															$_POST['radCatalogSelect'] : "WorkStudyPlans";
$_POST['deptosel'] = isset($_POST['deptosel']) ? $_POST['deptosel'] : "";
?>
<p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.1em; font-size: 133%;">
Показати: &nbsp; 
<input type="radio" name="radCatalogSelect" value="WorkStudyPlans" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "WorkStudyPlans") echo "checked"; ?>> 
	Робочі навчальні плани &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogSpecialty" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogSpecialty") echo "checked"; ?>> 
	Спеціальності &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogEduProgram" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogEduProgram") echo "checked"; ?>> 
	Освітні програми (спеціалізації) &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogDepartment" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogDepartment") echo "checked"; ?>> 
	Кафедри &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogTeacher" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogTeacher") echo "checked"; ?>> 
	Викладачів<br>
<input type="radio" name="radCatalogSelect" value="сatalogSubject" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogSubject") echo "checked"; ?>>
	Дисципліни &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogSubjectCycle" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogSubjectCycle") echo "checked"; ?>>
	Цикли дисциплін &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogGroup" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogGroup") echo "checked"; ?>>
	Академгрупи &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogStudent" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogStudent") echo "checked"; ?>>
	Студентів  &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="PapersCount" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "PapersCount") echo "checked"; ?>>
	Курсові в РНП  &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="AudHours" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "AudHours") echo "checked"; ?>>
	Аудиторні в РНП  &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="UsersActivity" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "UsersActivity") echo "checked"; ?>>
	Активність користувачів<br>
<input type="radio" name="radCatalogSelect" value="StudyWorkAmount" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "StudyWorkAmount") echo "checked"; ?>>Навч.навантаження кафедр
</p>
<?php
switch ($_POST['radCatalogSelect']) {
	case "WorkStudyPlans":	require "work_edu_plans.php"; break;
	case "сatalogSpecialty":	require "specs.php"; break;
	case "сatalogEduProgram":	require "edu_progs.php"; break;
	case "сatalogDepartment":	require "departs.php"; break;
	case "сatalogTeacher":	require "teachers.php"; break;
	case "сatalogSubject":	require "subjects.php"; break;
	case "сatalogSubjectCycle":	require "subjects_cycles.php"; break;
	case "сatalogGroup":	require "groups.php"; break;
	case "сatalogStudent":	require "students.php"; break;
	case "AudTimeDistribution":
		$DepartsQuery = "SELECT id, nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf";
		$_POST['deptosel'] = isset($_POST['deptosel']) ? $_POST['deptosel'] : "";
		echo "<br>".selectCommonSelectAutoSubmit
			("Виберіть кафедру:", "deptosel", $conn, $DepartsQuery, "id", $_POST['deptosel'], "nazva_kaf", "");
		if (!empty($_POST['deptosel'])) require "time_distrib_to_schedule.php"; break;
	case "Unvisaed":	require "plans_unvisaed.php"; break;
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
	case "PapersCount": require "papers.php"; break;
	case "AudHours": require "aud_hours.php"; break;
	case "UsersActivity": require "users_stats.php"; break;
	case "StudyWorkAmount": require "select_depart.php";
		if (!empty($_POST['deptosel'])) require "study_work_amount.php"; 
		else require "all_study_work_amount.php"; break;
} 
?>