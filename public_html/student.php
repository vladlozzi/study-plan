<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль student.php</p>"; require "footer.php"; exit(); }
$TrueAdmin = FALSE; $TrueBoss = FALSE;
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
															$_POST['radCatalogSelect'] : "StudyPlan";
$AcadYearCur = mb_substr(AcadYearByGroupId($_SESSION['user_description']), -1, 1); ?>
<p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.1em; font-size: 125%;">
<b>УВАГА!</b> Модуль "ОСОБИСТИЙ КАБІНЕТ СТУДЕНТА" запущено в <b>ТЕСТОВОМУ</b> режимі та доопрацьовується<br>
Ви навчаєтеся на <?php echo bold($AcadYearCur); ?>-му курсі 
в академгрупі <?php echo bold(GroupById($_SESSION['user_description'])); ?>, 
<?php echo bold(FacultyById(FacultyIdByGroupId($_SESSION['user_description']))); 
$FinCiti_query = "SELECT finance, ukrainian FROM catalogStudent WHERE id = ".$_SESSION['user_id'];
$FinCiti_result = mysqli_query($conn, $FinCiti_query) or 
				die("Помилка сервера при запиті<br>".$FinCiti_query." : ".mysqli_error($conn));;
$FinCiti_row = mysqli_fetch_assoc($FinCiti_result);
?>,<br>джерело фінансування: <?php echo bold(mb_strtolower($FinCiti_row['finance'])); 
if ($FinCiti_row['ukrainian'] == 'Ні') echo ", ".bold("іноземець"); 
?><br>Показати: &nbsp; 
<input type="radio" name="radCatalogSelect" value="StudyPlan" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "StudyPlan") echo "checked"; ?>> 
	Індивідуальний навчальний план &nbsp; &nbsp; <?php
if ($FinCiti_row['finance'] == "Контракт") { ?>
<input type="radio" name="radCatalogSelect" value="PaymentForStudy" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "PaymentForStudy") echo "checked"; ?>> 
	Стан оплати за навчання &nbsp; &nbsp; <?php 
} ?>
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
	Студентів
</p><?php
switch ($_POST['radCatalogSelect']) {
	case "StudyPlan":	
		$PlanForGroup_query = "
			SELECT a.*, b.degree_name, c.degree_name AS base_degree_name, 
						d.edu_form, e.fakultet_name, 
						CONCAT(f.specialty_b_code,\" \",f.specialty_name,\" (Перелік \",f.list,\")\") AS specialty_codename, 
						CONCAT(\"(\",g.eduprogram_code,\") \",g.eduprogram_name) AS eduprogram_codename, h.plan_id
			FROM catalogWorkEduPlan a, catalogEduDegree b, catalogEduDegree c, catalogEduForm d, 
						catalogFakultet e, catalogSpecialty f, catalogEduProgram g, catalogGroup h
			WHERE a.edu_degree_id = b.id AND a.base_edu_degree_id = c.id 
				AND	a.edu_form_id = d.id AND a.faculty_id = e.id 
				AND a.specialty_id = f.id AND a.edu_program_id = g.id
				AND a.id = h.plan_id AND h.id = ".$_SESSION['user_description'];
		$PlanForGroup_result = mysqli_query($conn, $PlanForGroup_query) or 
						die("Помилка сервера при запиті<br>".$PlanForGroup_query." : ".mysqli_error($conn));;
		$query_row = mysqli_fetch_assoc($PlanForGroup_result);
		?>
<p style="text-align: center;">
	<span style="text-transform: uppercase; font-size: 125%; font-weight: bold;">
		Індивідуальний навчальний план</span><br><span style="font-size: 133%;">
		(на основі робочого навчального плану № <?php 
				echo $query_row['reg_number']." / ".$query_row['plan_id']; ?>)<br>та його виконання</span><?php 
				$frag = "YES"; $mode = "VIEW"; $stud = "YES";
				require "./edu_plan/study_plan_header.php"; 
				require "./edu_plan/schedule_edu_process.php"; 
				require "./edu_plan/individual_study_subjects.php"; ?></p><?php
		break;
	case "PaymentForStudy":	require "payment_study.php"; break;
	case "сatalogSpecialty":	require "specs.php"; break;
	case "сatalogEduProgram":	require "edu_progs.php"; break;
	case "сatalogDepartment":	require "departs.php"; break;
	case "сatalogTeacher":	require "teachers.php"; break;
	case "сatalogSubject":	
		$_POST['hidesubj'] = isset($_POST['hidesubj']) ? $_POST['hidesubj'] : "on"; require "subjects.php"; break;
	case "сatalogSubjectCycle":	require "subjects_cycles.php"; break;
	case "сatalogGroup":	require "groups.php"; break;
	case "сatalogStudent":	require "students.php"; break;
} 
?>
