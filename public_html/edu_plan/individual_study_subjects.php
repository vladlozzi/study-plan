<?php 
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
	                              "Помилка входу в модуль subjects_study_put.php</p>"; 
									 require "footer.php"; exit(); } 
function NotEmpty($numb) { return ($numb > 0) ? $numb : ""; }
function StudentRating($student_id, $subject_id, $sem) { global $conn;
	$StudentRating_query = "SELECT IF(a.so != '', a.so, a.pom) AS so 
													FROM progress_stud_mark_archive a, progress_teacher_mark_archive b
													WHERE a.student_name = ".$student_id." AND a.subject_name = ".$subject_id. " AND 
																b.sem = ".$sem." AND a.teacher_rec_num = b.id AND b.deleted = 0";
//	echo $StudentRating_query;
	$StudentRating_result = mysqli_query($conn, $StudentRating_query) or 
						die("<br>Помилка сервера при запиті<br>".$StudentRating_query." : ".mysqli_error($conn));
	$StudentRating_row = mysqli_fetch_assoc($StudentRating_result);
	return ($StudentRating_row['so'] > 0) ? $StudentRating_row['so']."/".
																					ECTSRating($StudentRating_row['so'])."/".
																					GradeUkr($StudentRating_row['so']) : ""; /*$student_id." / ".$subject_id*/
}
$AudWeeksCount = array(); for ($iSem = 1; $iSem < 9; $iSem++) $AudWeeksCount[$iSem] = 0;
$StudyWeeksCount = array(); for ($iSem = 1; $iSem < 9; $iSem++) $StudyWeeksCount[$iSem] = 0;
$Sched_query = "SELECT * FROM schedule_edu_process WHERE plan_id = ".$query_row['id']." ORDER BY study_year";
$Sched_result = mysqli_query($conn, $Sched_query) or 
						die("<br>Помилка сервера при запиті<br>".$Sched_query." : ".mysqli_error($conn));
while ($Sched_row = mysqli_fetch_array($Sched_result)) {
	$iSemStudy = strval(mb_substr($Sched_row['study_year'],-1,1,"UTF-8")) * 2 - 1; 
	$iSemAud = $iSemStudy; $ExamSw = 0; $VacSw = 0;
	for ($iw = 1; $iw <= 52; $iw++) {
		$week = ($iw > 9) ? $iw : "0".$iw;
		if ((EduFormById($query_row['edu_form_id']) == "Д") and 
			($Sched_row['kind_in_week'.$week] <> "К") and ($Sched_row['kind_in_week'.$week] <> "-"))
			$StudyWeeksCount[$iSemStudy]++;
		if ((EduFormById($query_row['edu_form_id']) == "Д") and 
			(($Sched_row['kind_in_week'.$week] == "К") or ($Sched_row['kind_in_week'.$week] == "-")))
      if ($VacSw == 0) { $iSemStudy++; $VacSw = 1; }
		if (empty($Sched_row['kind_in_week'.$week])) $AudWeeksCount[$iSemAud]++;
		if ($Sched_row['kind_in_week'.$week] == "Е") if ($ExamSw == 0) { $iSemAud++; $ExamSw = 1; }
	}
}
switch (True) {
	case (empty($_POST['chkCur'.$query_row['id']]) and !empty($_POST['chkNext'.$query_row['id']])): 
		$SemStart = $query_row['sem_start_next']; $SemFinal = $query_row['sem_final_next']; break;
	case (!empty($_POST['chkCur'.$query_row['id']]) and empty($_POST['chkNext'.$query_row['id']])): 
		$SemStart = $query_row['sem_start_current']; $SemFinal = $query_row['sem_final_current']; break;
} 
if (!empty($_POST['chkPartOfPlan'])) { $AcadYearStart = ceil($SemStart / 2); 
	$AcadYearFinal = ceil($SemFinal / 2); $iSem = $SemStart; 
} else { $AcadYearStart = 1; 
	$AcadYearFinal = $query_row['edu_term_years'] + 
										(($query_row['edu_term_months'] > 0) ? 1 : 0); $iSem = 1;
} /*echo "<br>".$query_row['edu_term_years']." ".$query_row['edu_term_months']." ".$AcadYearStart." ".$AcadYearFinal;*/ 
$_POST['radAcadYearSelect'] = (isset($_POST['radAcadYearSelect'])) ? $_POST['radAcadYearSelect'] 
																																			: "AY".$AcadYearCur; ?>
<p style="text-align: center; text-transform: uppercase; 
					font-size: 125%; font-weight: bold; margin-bottom: 0px; margin-top: 0px;">
		2. План вивчення дисциплін</p>
<p style="text-align: center; color: blue; font-size: 125%; margin-bottom: 0px; margin-top: 0px;">
Показати дисципліни на курс: &nbsp; <?php
for ($AcadYear = $AcadYearStart; $AcadYear <= $AcadYearFinal; $AcadYear++) { ?>
<input type="radio" name="radAcadYearSelect" value="AY<? echo $AcadYear; ?>" onclick="submit()" <?php 
	if ($_POST['radAcadYearSelect'] == "AY".$AcadYear) echo "checked"; ?>><?php echo $AcadYear."-й"; ?>
&nbsp; <?php
} ?></p><?php $AcadYearStart = strval(str_replace("AY", "", $_POST['radAcadYearSelect'])); 
							$AcadYearFinal = strval(str_replace("AY", "", $_POST['radAcadYearSelect'])); 
							$iSem = $AcadYearStart * 2 - 1;
for ($AcadYear = $AcadYearStart; $AcadYear <= $AcadYearFinal; $AcadYear++) { 
?>
<table style="width: 90%;">
	<thead>
		<tr><th rowspan=3>№</th><th rowspan=3 style="width: 450px; font-size: 125%;">
					Навчальні дисципліни, практики, атестація</th>
				<th colspan="<?php if ($stud == "YES") echo 16; else echo 14; ?>" style="font-size: 125%;">
					Розподіл навчального часу на <?php echo $AcadYear; ?>-му курсі, год.<?php
	if ($stud == "YES") echo ", і результати навчання";	?>
				</th></tr>
		<tr><th colspan="<?php if ($stud == "YES") echo 8; else echo 7; ?>" 
						style="font-size: 125%;"><?php echo $iSem; ?>-й семестр <?php 
	if (($AudWeeksCount[$iSem] > 0) and (EduFormById($query_row['edu_form_id']) == "Д")) 
		echo "(".$AudWeeksCount[$iSem]." аудит. тиж.)"; ?></th>
				<th colspan="<?php if ($stud == "YES") echo 8; else echo 7; ?>" 
						style="font-size: 125%;"><?php echo $iSem + 1; ?>-й семестр <?php 
	if (($AudWeeksCount[$iSem + 1] > 0) and (EduFormById($query_row['edu_form_id']) == "Д")) 
		echo "(".$AudWeeksCount[$iSem + 1]." аудит. тиж.)"; ?></th></tr>
		<tr><th>Лекції</th><th>Лабор.</th><th>Практ.</th><th>Самост.</th>
				<th>КП / КР</th><th>Дом.завд.</th><th>Контроль</th><?php
				if ($stud == "YES") { ?> <th>Бали / оцінка</th><?php } ?>
				<th>Лекції</th><th>Лабор.</th><th>Практ.</th><th>Самост.</th>
				<th>КП / КР</th><th>Дом.завд.</th><th>Контроль</th><?php
				if ($stud == "YES") { ?> <th>Бали / оцінка</th><?php } ?></tr>
	</thead>
	<tbody>
<?php 
  $SubjWEP_query = "SELECT a.*, b.naz_dus AS subject_name 
										FROM plan_work_subj_study a, catalogSubject b 
										WHERE a.subject_id = b.id AND 
												( a.sem".$iSem."_lectural_hours > 0 OR 
													a.sem".$iSem."_laboratorials_hours > 0 OR
													a.sem".$iSem."_practicals_hours > 0 OR 
													a.sem".$iSem."_individual_work_hours > 0 OR
													a.sem".$iSem."_acad_year_paper != '' OR 
													a.sem".$iSem."_home_tasks > 0 OR
													a.sem".$iSem."_sem_test != '' OR 
													a.sem".($iSem+1)."_lectural_hours > 0 OR 
													a.sem".($iSem+1)."_laboratorials_hours > 0 OR
													a.sem".($iSem+1)."_practicals_hours > 0 OR 
													a.sem".($iSem+1)."_individual_work_hours > 0 OR
													a.sem".($iSem+1)."_acad_year_paper != '' OR 
													a.sem".($iSem+1)."_home_tasks > 0 OR
													a.sem".($iSem+1)."_sem_test != '' ) AND 
												a.plan_id = ".$query_row['id']." 
										ORDER BY b.naz_dus";
	$SubjWEP_result = mysqli_query($conn, $SubjWEP_query) or 
					die("<br>Помилка сервера при запиті<br>".$SubjWEP_query." : ".mysqli_error($conn)); $SubjCnt = 0;
	while ($SubjWEP_row = mysqli_fetch_array($SubjWEP_result)) { $SubjCnt++; ?>
		<tr><td><?php echo $SubjCnt; ?></td>
				<td style="text-align: left;"><?php echo $SubjWEP_row['subject_name']; ?></td>
				<td><?php echo NotEmpty($SubjWEP_row['sem'.$iSem.'_lectural_hours']); ?></td>
				<td><?php echo NotEmpty($SubjWEP_row['sem'.$iSem.'_laboratorials_hours']); ?></td>
				<td><?php echo NotEmpty($SubjWEP_row['sem'.$iSem.'_practicals_hours']); ?></td>
				<td><?php echo NotEmpty($SubjWEP_row['sem'.$iSem.'_individual_work_hours']); ?></td>
				<td><?php echo $SubjWEP_row['sem'.$iSem.'_acad_year_paper']; ?></td>
				<td><?php echo NotEmpty($SubjWEP_row['sem'.$iSem.'_home_tasks']); ?></td>
				<td><?php 
		echo $SubjWEP_row['sem'.$iSem.'_sem_test']; ?></td><?php	
		if ($stud == "YES") { ?> <td><?php 
			echo StudentRating($_SESSION['user_id'], $SubjWEP_row['subject_id'], $iSem); ?></td><?php } ?>
				<td style="border-left: 3px solid blue"><?php 
		echo NotEmpty($SubjWEP_row['sem'.($iSem + 1).'_lectural_hours']); ?></td>
				<td><?php echo NotEmpty($SubjWEP_row['sem'.($iSem + 1).'_laboratorials_hours']); ?></td>
				<td><?php echo NotEmpty($SubjWEP_row['sem'.($iSem + 1).'_practicals_hours']); ?></td>
				<td><?php echo NotEmpty($SubjWEP_row['sem'.($iSem + 1).'_individual_work_hours']); ?></td>
				<td><?php echo $SubjWEP_row['sem'.($iSem + 1).'_acad_year_paper']; ?></td>
				<td><?php echo NotEmpty($SubjWEP_row['sem'.($iSem + 1).'_home_tasks']); ?></td>
				<td><?php echo $SubjWEP_row['sem'.($iSem + 1).'_sem_test']; ?></td><?php	
		if ($stud == "YES") { ?> <td><?php 
			echo StudentRating($_SESSION['user_id'], $SubjWEP_row['subject_id'], $iSem + 1); ?></td><?php } ?>
		</tr> <?php
	}	?>
	</tbody>
</table><?php $iSem += 2; if ($AcadYear < $AcadYearFinal) echo "<br>";
} ?>
