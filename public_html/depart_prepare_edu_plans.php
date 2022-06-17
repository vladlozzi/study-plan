<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль depart_prepare_edu_plans.php</p>"; 
									require "footer.php"; exit(); }
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
<p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.2em; font-size: 133%;">
Показати: &nbsp; 
<input type="radio" name="radCatalogSelect" value="WorkStudyPlans" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "WorkStudyPlans") echo "checked"; ?>> 
	Робочі навчальні плани &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogSubject" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogSubject") echo "checked"; ?>>
	Дисципліни (довідник) &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="DepartSubject" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "DepartSubject") echo "checked"; ?>>
	Витяги з РНП для кафедри &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="StudyWorkAmount" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "StudyWorkAmount") echo "checked"; ?>>
	Навч.навантаження &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="AudTimeDistribution" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "AudTimeDistribution") echo "checked"; ?>>
	Розподіл аудит. часу
</p>
<p style="text-align: center; color: blue; margin-top: 0px; margin-bottom: 0.5em; font-size: 100%;">
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
	Викладачів &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogGroup" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogGroup") echo "checked"; ?>>
	Академгрупи &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogStudent" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogStudent") echo "checked"; ?>>
	Студентів &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="сatalogSubjectCycle" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "сatalogSubjectCycle") echo "checked"; ?>>
	Цикли дисциплін &nbsp; &nbsp; 
<input type="radio" name="radCatalogSelect" value="EduPlansSem" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "EduPlansSem") echo "checked"; ?>>
	Чинність РНП &nbsp; &nbsp;
<input type="radio" name="radCatalogSelect" value="DiplomaSupplement" onclick="submit()" 
	<?php if ($_POST['radCatalogSelect'] == "DiplomaSupplement") echo "checked"; ?>>
	Додатки до дипломів
</p>
<?php
switch ($_POST['radCatalogSelect']) {
case "WorkStudyPlans":
?>
Для реєстрації в базі даних <span style="font-weight: bold;">нового робочого навчального плану</span>
 або видалення непотрібного зверніться до методиста навчального відділу.<br>
Електронний цифровий підпис згенеровано, зберігається в базі даних і 
<span style="font-weight: bold;">накласти його можна, натиснувши позначку "Підписати" 
при перегляді РНП із сумами.</span>
<p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.5em; font-size: 133%;">
Для редагування Вам доступні ЧИННІ робочі навчальні плани, 
в яких Ваша кафедра є <span style="font-weight: bold;">випускною</span>, а саме:</p>
<table style="margin-left: 0%; width: 100%;">
	<tr><th>Код</th><th>Реєстровий<br>номер плану</th><th>Ступінь<br>вищої освіти</th>
		<th>Базова освіта<br>(здобута раніше)</th><th>Форма<br>навчання</th>
		<th>Інститут</th><th>Спеціальність або напрям</th><th>Спеціалізація<br>(освітня програма)</th>
		<th>Рік набрання<br>чинності</th><th>Варіанти дії</th>
	</tr>
<?php

// Завантажити перелік робочих навчальних планів
	$EduPlansQuery = "
				SELECT a.id, a.reg_number, a.edu_degree_id, b.degree_name, 
						a.edu_term_years, a.edu_term_months, 
						a.base_edu_degree_id, c.degree_name AS base_degree_name, 
						a.edu_form_id, d.edu_form, 
						a.faculty_id, e.fakultet_name, 
						a.specialty_id, 
							CONCAT(f.specialty_b_code,\" \",f.specialty_name,\" (Перелік \",f.list,\")\") AS specialty_codename, 
						a.edu_program_id, 
							CONCAT(g.eduprogram_code,\"<br>\",g.eduprogram_name) AS eduprogram_codename, 
						a.depart_id,
						a.actualize_year, a.stamp_date, a.protocol_number, 
						a.proxy_signature, a.depart_head_visa, a.dekan_visa, 
						a.methodist_visa, a.study_depart_boss_visa, a.vicerector_visa, 
						a.sem_start_current, a.sem_final_current 
				FROM catalogWorkEduPlan a, catalogEduDegree b, catalogEduDegree c, catalogEduForm d, 
						catalogFakultet e, catalogSpecialty f, catalogEduProgram g 
				WHERE a.edu_degree_id = b.id AND a.base_edu_degree_id = c.id 
					AND	a.edu_form_id = d.id AND a.faculty_id = e.id 
					AND a.specialty_id = f.id AND a.edu_program_id = g.id 
					AND a.depart_id = ".$_SESSION['user_description']."
					AND (a.sem_start_current > 0 AND a.sem_final_current > 0)
				ORDER BY actualize_year DESC, degree_name ASC
		";
	$query_result = mysqli_query($conn, $EduPlansQuery) or 
			die("Помилка сервера при запиті<br>".$EduPlansQuery." : ".mysqli_error($conn));

	// Шукаємо, чи є позначений план
	$CheckedEPlanId = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbxpe'.$query_row['id']] = isset($_POST['cbxpe'.$query_row['id']]) ? 
															$_POST['cbxpe'.$query_row['id']] : "";
		if (!empty($_POST['cbxpe'.$query_row['id']])) { 
			$CheckedEPlanId = $query_row['id'];
		}
	}
	mysqli_data_seek($query_result, 0); 
	if ($CheckedEPlanId > 0) { // якщо є позначений, то показуємо тільки його
		$icnt = 0;
		while ($query_row = mysqli_fetch_array($query_result)) { 
			$_POST['cbxpe'.$query_row['id']] = isset($_POST['cbxpe'.$query_row['id']]) ? 
															$_POST['cbxpe'.$query_row['id']] : "";
			if (!empty($_POST['cbxpe'.$query_row['id']])) {
?>
	<tr style="background-color: LightBlue;">
		<td rowspan=2 style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td rowspan=2 style="text-align: left;"><?php echo $query_row['reg_number']; ?></td>
		<td><?php echo $query_row['degree_name']; ?></td>
		<td><?php echo $query_row['base_degree_name']; ?></td>
		<td><?php echo $query_row['edu_form']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_codename']; ?></td>
		<td><?php echo $query_row['actualize_year']; ?></td>
		<td>
<?php 
				echo paramCheker("cbxpe".$query_row['id'], $_POST['cbxpe'.$query_row['id']], 
									"Показати/Сховати план", "onchange=\"submit()\"");
?>
		</td>
	</tr><div style="top: 1px;	right: 1px;	position: fixed; background-color: LightBlue; 
						font-size: 150%; font-weight: bold; font-family: sans-serif;">
					<?php echo $query_row['reg_number']; ?></div>
<?php 	require "visas_list_on_edu_plan.php";
			}
			$icnt++;
		}
	} else { // якщо нема позначених, то показуємо всі
		$icnt = 0;
		while ($query_row = mysqli_fetch_array($query_result)) { 
			$_POST['cbxpe'.$query_row['id']] = isset($_POST['cbxpe'.$query_row['id']]) ? 
															$_POST['cbxpe'.$query_row['id']] : "";
?>
	<tr><td rowspan=2 style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td rowspan=2 style="text-align: left;"><?php echo $query_row['reg_number']; ?></td>
		<td><?php echo $query_row['degree_name']; ?></td>
		<td><?php echo $query_row['base_degree_name']; ?></td>
		<td><?php echo $query_row['edu_form']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_codename']; ?></td>
		<td><?php echo $query_row['actualize_year']; ?></td>
		<td>
<?php 
			echo paramCheker("cbxpe".$query_row['id'], $_POST['cbxpe'.$query_row['id']], 
								"Показати/Сховати план", "onchange=\"submit()\"");
?>
		</td>
	</tr>
<?php require "visas_list_on_edu_plan.php";
			$icnt++;
		}
	}  
?><tr><td colspan=10>Загальна кількість РНП для кафедри: <?php echo bold($icnt); ?></td></tr>
</table>
<!--
<p style="text-align: left; color: red; margin-top: 0.5em; margin-bottom: 0.5em;">
УВАГА! Редагування буде доступне після введення програми в експлуатацію</p>
-->
<?php 
	mysqli_data_seek($query_result, 0); 
	
	while ($query_row = mysqli_fetch_array($query_result)) { 
		if (!empty($_POST['cbxpe'.$query_row['id']])) { 
			$mode = "EDIT";  $frag = "NO"; $stud = "NO";?>
<div id="WorkStudyPlan"><?php
			require "./edu_plan/study_plan_header.php";
			require "./edu_plan/schedule_edu_process.php";
			require "./edu_plan/plan_subjects_study.php";
			require "./edu_plan/practiques_certification.php";
			require "./edu_plan/study_plan_visaed.php";
			require "./edu_plan/study_plan_proxy_sign.php"; ?>
</div><?php
		}
	}
?><br><hr size=8 style="color: red; background-color: red;">
<p style="text-align: center; color: blue; margin-top: 1.0em; margin-bottom: 0.5em; font-size: 133%;">
Робочі навчальні плани, доступні Вам для перегляду</p>
<?php $FilterConditions = array(); $tn="o"; $itn=1; $FilterConditions[$itn] = "";
			require "work_edu_plans_filter.php"; ?>
<table style="margin-left: 0%; width: 100%;">
	<tr><th>Код</th><th>Реєстровий<br>номер плану</th><th>Ступінь вищої освіти</th>
		<th>Базова освіта<br>(здобута раніше)</th><th>Форма навчання</th>
		<th>Інститут</th><th>Спеціальність або напрям</th><th>Спеціалізація<br>(освітня програма)</th>
		<th>Рік набрання чинності</th><th>Варіанти дії</th>
	</tr>
<?php $FilterCond = $FilterConditions[$itn];
// Завантажити перелік
	$EduPlansQuery = "
				SELECT a.id, a.reg_number, a.edu_degree_id, b.degree_name, 
						a.edu_term_years, a.edu_term_months, 
						a.base_edu_degree_id, c.degree_name AS base_degree_name, 
						a.edu_form_id, d.edu_form, 
						a.faculty_id, e.fakultet_name, 
						a.specialty_id, 
							CONCAT(f.specialty_b_code,\" \",f.specialty_name,\" (Перелік \",f.list,\")\") AS specialty_codename, 
						a.edu_program_id, 
							CONCAT(g.eduprogram_code,\"<br>\",g.eduprogram_name) AS eduprogram_codename, 
						a.depart_id,
						a.actualize_year, a.stamp_date, a.protocol_number, 
						a.proxy_signature, a.depart_head_visa, a.dekan_visa, 
						a.methodist_visa, a.study_depart_boss_visa, a.vicerector_visa, 
						a.sem_start_current, a.sem_final_current 
				FROM catalogWorkEduPlan a, catalogEduDegree b, catalogEduDegree c, catalogEduForm d, 
						catalogFakultet e, catalogSpecialty f, catalogEduProgram g 
				WHERE a.edu_degree_id = b.id AND a.base_edu_degree_id = c.id 
					AND	a.edu_form_id = d.id AND a.faculty_id = e.id 
					AND a.specialty_id = f.id AND a.edu_program_id = g.id $FilterCond
				ORDER BY actualize_year DESC, specialty_codename ASC, degree_name ASC
		";
	$query_result = mysqli_query($conn, $EduPlansQuery) or 
			die("Помилка сервера при запиті<br>".$EduPlansQuery." : ".mysqli_error($conn));
	// Шукаємо, чи є позначений план
	$CheckedVPlanId = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbxpv'.$query_row['id']] = isset($_POST['cbxpv'.$query_row['id']]) ? 
															$_POST['cbxpv'.$query_row['id']] : "";
		if (!empty($_POST['cbxpv'.$query_row['id']])) { 
			$CheckedVPlanId = $query_row['id'];
		}
	}
	mysqli_data_seek($query_result, 0); 
	if ($CheckedVPlanId > 0) { // якщо є позначений, то показуємо тільки його
		$icnt = 0;
		while ($query_row = mysqli_fetch_array($query_result)) { 
			$_POST['cbxpv'.$query_row['id']] = isset($_POST['cbxpv'.$query_row['id']]) ? 
															$_POST['cbxpv'.$query_row['id']] : "";
			if (!empty($_POST['cbxpv'.$query_row['id']])) {
?>
	<tr style="background-color: YellowGreen;">
		<td rowspan=2 style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td rowspan=2 style="text-align: left;"><?php echo $query_row['reg_number']; ?></td>
		<td><?php echo $query_row['degree_name']; ?></td>
		<td><?php echo $query_row['base_degree_name']; ?></td>
		<td><?php echo $query_row['edu_form']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_codename']; ?></td>
		<td><?php echo $query_row['actualize_year']; ?></td>
		<td>
<?php 
				echo paramCheker("cbxpv".$query_row['id'], $_POST['cbxpv'.$query_row['id']], 
									"Показати/Сховати план", "onchange=\"submit()\"");
?>
		</td>
	</tr><?php 
				require "visas_list_on_edu_plan.php"; ?>
	<div style="top: 33px;	right: 1px;	position: fixed; background-color: YellowGreen; 
										font-size: 150%; font-weight: bold; font-family: sans-serif;">
					<?php echo $query_row['reg_number']; ?></div>
<?php 
			}
			$icnt++;
		}
	} else { // якщо нема позначених, то показуємо всі
		$icnt = 0;
		while ($query_row = mysqli_fetch_array($query_result)) { 
			$_POST['cbxpv'.$query_row['id']] = isset($_POST['cbxpv'.$query_row['id']]) ? 
															$_POST['cbxpv'.$query_row['id']] : "";
?>
	<tr><td rowspan=2 style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td rowspan=2 style="text-align: left;"><?php echo $query_row['reg_number']; ?></td>
		<td><?php echo $query_row['degree_name']; ?></td>
		<td><?php echo $query_row['base_degree_name']; ?></td>
		<td><?php echo $query_row['edu_form']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_codename']; ?></td>
		<td><?php echo $query_row['actualize_year']; ?></td>
		<td>
<?php 
			echo paramCheker("cbxpv".$query_row['id'], $_POST['cbxpv'.$query_row['id']], 
								"Показати/Сховати план", "onchange=\"submit()\"");
?>
		</td>
	</tr>
<?php require "visas_list_on_edu_plan.php";
//			
			$icnt++;
		}
	}  
?>
	<tr><td colspan=10>Загальна кількість вибраних РНП: <?php echo bold($icnt); ?></td></tr>
</table>
<?php 
	mysqli_data_seek($query_result, 0); 
	
	while ($query_row = mysqli_fetch_array($query_result)) { 
		if (!empty($_POST['cbxpv'.$query_row['id']])) { 
			$mode = "VIEW"; $frag = "NO"; $stud = "NO";
			require "./edu_plan/study_plan_header.php";
			require "./edu_plan/schedule_edu_process.php";
			require "./edu_plan/plan_subjects_study.php";
			require "./edu_plan/practiques_certification.php";
			require "./edu_plan/study_plan_visaed.php";
		}
	}
	break;
case "сatalogSubject": $TrueAdmin = TRUE; 
	$_POST['deptosel'] = (isset($_POST['deptosel'])) ? $_POST['deptosel'] : $_SESSION['user_description'];
	require "subjects.php"; break;
case "DepartSubject": 
	$_POST['deptosel'] = (isset($_POST['deptosel'])) ? $_POST['deptosel'] : $_SESSION['user_description'];
	require "depart_subjects.php"; break;
case "сatalogSubjectCycle": $TrueAdmin = FALSE; require "subjects_cycles.php"; break;
case "сatalogSpecialty": $TrueAdmin = FALSE; require "specs.php"; break;
case "сatalogEduProgram":	$TrueAdmin = FALSE; require "edu_progs.php"; break;
case "сatalogDepartment":	$TrueAdmin = FALSE; require "departs.php"; break;
case "сatalogTeacher": $TrueAdmin = FALSE; require "teachers.php"; break;
case "сatalogGroup": $TrueAdmin = FALSE; require "groups.php"; break;
case "сatalogStudent": $TrueAdmin = FALSE; require "students.php"; break;
case "EduPlansSem": $TrueAdmin = FALSE; require "work_edu_plans_semesters.php"; break;
case "AudTimeDistribution": $TrueAdmin = FALSE; $TrueBoss = FALSE; ?>
<!-- <p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.2em; font-size: 533%;">
НЕЗАБАРОМ БУДЕ!</p> --><?php 
		$_POST['deptosel'] = (isset($_POST['deptosel'])) ? $_POST['deptosel'] : $_SESSION['user_description'];
		require "time_distrib_to_schedule.php"; break;
	case "DiplomaSupplement": ?>
<!-- <p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.2em; font-size: 533%;">
НЕЗАБАРОМ БУДЕ ГОТОВЕ! --></p><?php 
		$_POST['deptosel'] = (isset($_POST['deptosel'])) ? $_POST['deptosel'] : $_SESSION['user_description'];
		require "diploma_suppl.php"; break;
	case "StudyWorkAmount": $TrueAdmin = FALSE; $TrueBoss = FALSE;  
		$_POST['deptosel'] = (isset($_POST['deptosel'])) ? $_POST['deptosel'] : $_SESSION['user_description'];
		/* if ($_POST['deptosel'] == 16)  */ require "study_work_amount.php"; break;
} ?><br>