<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль vicerector.php</p>"; require "footer.php"; exit(); }
$TrueAdmin = FALSE;
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
$EduFormsQuery = "SELECT id, edu_form	FROM catalogEduForm ORDER BY id"; // echo "<p>".$EduFormsQuery."</p>";
// Перелік Інститутів (факультетів) для вибору
$FacultiesQuery = "SELECT id, fakultet_name	FROM catalogFakultet ORDER BY fakultet_name";
// Перелік кафедр 
$DepartsQuery = "SELECT id, nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf"; // echo "<p>".$DepartsQuery."</p>";
$_POST['radCatalogSelect'] = isset($_POST['radCatalogSelect']) ? 
															$_POST['radCatalogSelect'] : "WorkStudyPlans";

?>
<p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.1em; font-size: 133%;">
Показати: &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogDepartment" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogDepartment") echo "checked"; ?>> 
	Кафедри &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogTeacher" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogTeacher") echo "checked"; ?>> 
	Викладачів &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogSpecialty" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogSpecialty") echo "checked"; ?>> 
	Спеціальності &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogEduProgram" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogEduProgram") echo "checked"; ?>> 
	Освітні програми (спеціалізації)<br>
<input type="radio" name="radCatalogSelect" value="сatalogGroup" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogGroup") echo "checked"; ?>>
	Академгрупи &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogStudent" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogStudent") echo "checked"; ?>>
	Студентів
</p>
<?php
switch ($_POST['radCatalogSelect']) {
case "сatalogSpecialty":	require "specs.php"; break;
case "сatalogEduProgram":	require "edu_progs.php"; break;
case "сatalogDepartment":	$TrueAdmin = TRUE; require "departs.php"; break;
case "сatalogTeacher": $TrueAdmin = TRUE; require "teachers.php"; break;
case "сatalogGroup":	require "groups.php"; break;
case "сatalogStudent":	require "students.php"; break;
} ?>