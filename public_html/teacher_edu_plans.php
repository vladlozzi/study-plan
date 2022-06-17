<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль teacher_edu_plans.php</p>"; require "footer.php"; exit(); }
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
// Перелік форм навчання для вибору
$EduFormsQuery = "SELECT id, edu_form	FROM catalogEduForm ORDER BY id"; // echo "<p>".$EduFormsQuery."</p>";
// Перелік Інститутів (факультетів) для вибору
$FacultiesQuery = "SELECT id, fakultet_name	FROM catalogFakultet ORDER BY fakultet_name";
// Перелік кафедр 
$DepartsQuery = "SELECT id, nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf"; // echo "<p>".$DepartsQuery."</p>";
// Кпфедра, на якій працює залогінений викладач
$DepartTeacherQuery = "SELECT kaf_link FROM catalogTeacher WHERE id = ".$_SESSION['user_id'];
$DepartTeacherQuery_result = mysqli_query($conn, $DepartTeacherQuery) or 
			die("Помилка сервера при запиті<br>".$DepartTeacherQuery." : ".mysqli_error($conn));
$DepartTeacherQuery_row = mysqli_fetch_array($DepartTeacherQuery_result);
$depart_id = $DepartTeacherQuery_row['kaf_link'];

$_POST['selCatalogSelect'] = isset($_POST['selCatalogSelect']) ? 
															$_POST['selCatalogSelect'] : "DepartSubject";
?>
<p style="text-align: center;"><span style="color: blue; font-weight: bold;">Увага!</span> Замовити Вашу поштову скриньку @nung.edu.ua можна
<a href="https://docs.google.com/spreadsheets/d/1Lc9lTSTlc5-IGFPU4OJSvI_oxbcXtDo3/" target="_blank">за цим посиланням</a>.<br>
Заповніть табличку, збережіть її у файлі Excel і надішліть файл на it@nung.edu.ua.</p>
<p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.2em; font-size: 133%;">
Показати: &nbsp; 
<select name="selCatalogSelect" onchange="submit()" style="color: blue; font-size: 100%;">
	<option <?php if ($_POST['selCatalogSelect'] == "DepartSubject") echo "selected "; ?>
		value="DepartSubject">Витяги з РНП для кафедри</option>
	<option <?php if ($_POST['selCatalogSelect'] == "сatalogSubject") echo "selected "; ?>
		value="сatalogSubject">Дисципліни кафедри</option>
	<option <?php if ($_POST['selCatalogSelect'] == "сatalogTeacher") echo "selected "; ?>
		value="сatalogTeacher">Викладачів</option>
	<option <?php if ($_POST['selCatalogSelect'] == "EduPlansSem") echo "selected "; ?>
		value="EduPlansSem">Чинність РНП</option>
	<option <?php if ($_POST['selCatalogSelect'] == "сatalogSpecialty") echo "selected "; ?>
		value="сatalogSpecialty">Спеціальності</option>
	<option <?php if ($_POST['selCatalogSelect'] == "сatalogEduProgram") echo "selected "; ?>
		value="сatalogEduProgram">Освітні програми (спеціалізації)</option>
	<option <?php if ($_POST['selCatalogSelect'] == "сatalogDepartment") echo "selected "; ?>
		value="сatalogDepartment">Кафедри</option>
	<option <?php if ($_POST['selCatalogSelect'] == "сatalogSubjectCycle") echo "selected "; ?>
		value="сatalogSubjectCycle">Цикли дисциплін</option>
	<option <?php if ($_POST['selCatalogSelect'] == "сatalogGroup") echo "selected "; ?>
		value="сatalogGroup">Академгрупи</option>
	<option <?php if ($_POST['selCatalogSelect'] == "сatalogStudent") echo "selected "; ?>
		value="сatalogStudent">Студентів</option><?php 
if ($_SESSION['user_id'] == 128) { ?>
	<option <?php if ($_POST['selCatalogSelect'] == "UsersActivity") echo "selected "; ?>
		value="UsersActivity">Активність користувачів</option><?php
} 
?>
</select>
</p>
<?php
switch ($_POST['selCatalogSelect']) {
case "WorkStudyPlans":
?>
<p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.5em; font-size: 133%;">
Робочі навчальні плани, в яких Ваша кафедра є <span style="font-weight: bold;">випускною</span>:</p>
<table style="margin-left: 0%; width: 100%;">
	<tr><th>Код</th><th>Реєстровий<br>номер плану</th><th>Ступінь<br>вищої освіти</th>
		<th>Базова освіта<br>(здобута раніше)</th><th>Форма<br>навчання</th>
		<th>Інститут</th><th>Спеціальність або напрям</th><th>Спеціалізація<br>(освітня програма)</th>
		<th>Рік набрання<br>чинності</th><th>Варіанти дії</th>
	</tr>
<?php
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
						a.methodist_visa, a.study_depart_boss_visa, a.vicerector_visa 
				FROM catalogWorkEduPlan a, catalogEduDegree b, catalogEduDegree c, catalogEduForm d, 
						catalogFakultet e, catalogSpecialty f, catalogEduProgram g 
				WHERE a.edu_degree_id = b.id AND a.base_edu_degree_id = c.id 
					AND	a.edu_form_id = d.id AND a.faculty_id = e.id 
					AND a.specialty_id = f.id AND a.edu_program_id = g.id 
					AND a.depart_id = ".$_SESSION['user_description']."
				ORDER BY actualize_year DESC 
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
	<tr><td rowspan=2 style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td rowspan=2 style="text-align: left;"><?php echo $query_row['reg_number']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['base_degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['edu_form']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_codename']; ?></td>                        		
		<td style="text-align: left;"><?php echo $query_row['actualize_year']; ?></td>
		<td>
<?php 
				echo paramCheker("cbxpe".$query_row['id'], $_POST['cbxpe'.$query_row['id']], 
									"Показати/Сховати план", "onchange=\"submit()\"");
?>
		</td>
	</tr><div style="top: 1px;	right: 1px;	position: fixed; 
										color: blue; font-size: 150%; font-weight: bold; ">
					<?php echo $query_row['reg_number']; ?></div>
<?php		require "visas_list_on_edu_plan.php"; 
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
		<td style="text-align: left;"><?php echo $query_row['degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['base_degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['edu_form']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_codename']; ?></td>                        		
		<td style="text-align: left;"><?php echo $query_row['actualize_year']; ?></td>
		<td>
<?php 
			echo paramCheker("cbxpe".$query_row['id'], $_POST['cbxpe'.$query_row['id']], 
								"Показати/Сховати план", "onchange=\"submit()\"");
?>
		</td>
	</tr>
<?php require "visas_list_on_edu_plan.php";
//			}
			$icnt++;
		}
	}  

?>
	<tr><td colspan=10>Кількість РНП на Вашій кафедрі: <?php echo bold($icnt); ?></td></tr>
</table>
<?php 
	mysqli_data_seek($query_result, 0); 
	while ($query_row = mysqli_fetch_array($query_result)) { 
		if (!empty($_POST['cbxpe'.$query_row['id']])) { 
			$mode = "VIEW";
			require "./edu_plan/study_plan_header.php";
			require "./edu_plan/schedule_edu_process.php";
			require "./edu_plan/plan_subjects_study.php";
			require "./edu_plan/practiques_certification.php";
			require "./edu_plan/study_plan_visaed.php";
			require "./edu_plan/study_plan_depart_head_visa.php";
		}
	}
?><br><hr size=8 style="color: red; background-color: red;">
<p style="text-align: center; color: blue; margin-top: 1.0em; margin-bottom: 0.5em; font-size: 133%;">
Загальний перелік робочих навчальних планів</p>
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
						a.methodist_visa, a.study_depart_boss_visa, a.vicerector_visa 
				FROM catalogWorkEduPlan a, catalogEduDegree b, catalogEduDegree c, catalogEduForm d, 
						catalogFakultet e, catalogSpecialty f, catalogEduProgram g 
				WHERE a.edu_degree_id = b.id AND a.base_edu_degree_id = c.id 
					AND	a.edu_form_id = d.id AND a.faculty_id = e.id 
					AND a.specialty_id = f.id AND a.edu_program_id = g.id $FilterCond
				ORDER BY actualize_year DESC
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
	<tr><td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['reg_number']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['base_degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['edu_form']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_codename']; ?></td>                        		
		<td style="text-align: left;"><?php echo $query_row['actualize_year']; ?></td>
		<td>
<?php 
				echo paramCheker("cbxpv".$query_row['id'], $_POST['cbxpv'.$query_row['id']], 
									"Показати/Сховати план", "onchange=\"submit()\"");
?>
		</td>
	</tr><div style="top: 33px;	right: 1px;	position: fixed; 
										color: blue; font-size: 150%; font-weight: bold; ">
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
	<tr><td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['reg_number']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['base_degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['edu_form']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_codename']; ?></td>                        		
		<td style="text-align: left;"><?php echo $query_row['actualize_year']; ?></td>
		<td>
<?php 
			echo paramCheker("cbxpv".$query_row['id'], $_POST['cbxpv'.$query_row['id']], 
								"Показати/Сховати план", "onchange=\"submit()\"");
?>
		</td>
	</tr>
<?php 
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
			$mode = "VIEW"; $frag = "NO";
			require "./edu_plan/study_plan_header.php";
			require "./edu_plan/schedule_edu_process.php";
			require "./edu_plan/plan_subjects_study.php";
			require "./edu_plan/practiques_certification.php";
			require "./edu_plan/study_plan_visaed.php";
		}
	}
	break;
case "сatalogSubject": $TrueAdmin = FALSE; 
		$_POST['deptosel'] = (isset($_POST['deptosel'])) ? $_POST['deptosel'] : $depart_id;
		require "subjects.php"; break;
case "DepartSubject": 
		$_POST['deptosel'] = (isset($_POST['deptosel'])) ? $_POST['deptosel'] : $depart_id;
		require "depart_subjects.php"; break;
case "сatalogSubjectCycle": $TrueAdmin = FALSE; require "subjects_cycles.php"; break;
case "сatalogSpecialty": $TrueAdmin = FALSE; require "specs.php"; break;
case "сatalogEduProgram":	$TrueAdmin = FALSE; require "edu_progs.php"; break;
case "сatalogDepartment":	$TrueAdmin = FALSE; require "departs.php"; break;
case "сatalogTeacher": $TrueAdmin = FALSE; require "teachers.php"; break;
case "сatalogGroup": $TrueAdmin = FALSE; require "groups.php"; break;
case "сatalogStudent": $TrueAdmin = FALSE; require "students.php"; break;
case "EduPlansSem": $TrueAdmin = FALSE; require "work_edu_plans_semesters.php"; break;
case "UsersActivity": require "users_stats.php"; break;
} ?>