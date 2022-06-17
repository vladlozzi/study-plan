<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль depart_subjects.php</p>"; 
                            require "footer.php"; exit(); }
$_POST['deptosel'] = isset($_POST['deptosel']) ? $_POST['deptosel'] : "";
$_POST['stftosel'] = isset($_POST['stftosel']) ? $_POST['stftosel'] : "";
$_POST['radSemesterSelect'] = isset($_POST['radSemesterSelect']) ? $_POST['radSemesterSelect'] : "All";
$_POST['chkNextYear'] = isset($_POST['chkNextYear']) ? $_POST['chkNextYear'] : "";
$_POST['printver'] = isset($_POST['printver']) ? $_POST['printver'] : "";
echo selectCommonSelect
	("Форма навчання: ", "stftosel", $conn, $EduFormsQuery,
  	"id", $_POST['stftosel'], "edu_form", "onchange=\"submit()\""); ?> &nbsp; &nbsp; 
<input type="radio" name="radSemesterSelect" value="Autumn" onclick="submit()" 
	<?php if ($_POST['radSemesterSelect'] == "Autumn") echo "checked"; ?>> 
	осінній семестр &nbsp; 
<input type="radio" name="radSemesterSelect" value="Spring" onclick="submit()" 
	<?php if ($_POST['radSemesterSelect'] == "Spring") echo "checked"; ?>>
	весняний семестр &nbsp; 
<input type="radio" name="radSemesterSelect" value="All" onclick="submit()" 
	<?php if ($_POST['radSemesterSelect'] == "All") echo "checked"; ?>> 
	обидва семестри &nbsp; &nbsp; <?php
echo paramChekerInline("chkNextYear", $_POST['chkNextYear'], "На наступний навч.рік", "onchange=\"submit()\"").
	" &nbsp; &nbsp; ".paramCheker("printver", $_POST['printver'], "Версія для друку", "onchange=\"submit()\"");
// Перелік дисциплін, закріплених за кафедрами
/*if (!empty($_POST['deptosel']))
	$SubjQuery = "SELECT a.id, b.shufr_kaf, a.shufr_kaf AS shufr_kaf_subj, a.naz_dus 
					FROM catalogSubject a, catalogDepartment b 
					WHERE a.shufr_kaf = b.id AND a.shufr_kaf = \"".$_POST['deptosel']."\"
					ORDER BY a.naz_dus";*/
?>
<table <?php if (empty($_POST['printver'])) { ?> class="scrolling-table" <?php } ?>
			style="margin-left: 0%; width: 1300px;">
<thead style="width: 1300px;">
	<tr style="width: 1300px;">
		<th style="width: 30px;">№</th><th>Назва дисципліни і циклу</th>
		<th style="width: 90px; font-size: 85%">Спеціалізація<br>(осв.прогр.)</th>
		<th style="width: 60px;">Інст.</th>
		<th style="width: 50px;">Курс/<br>сем.</th>
		<th style="width: 60px;">Кредити<br>/години</th>
		<th style="width: 50px;">Лекц.</th><th style="width: 40px;">Лаб.</th>
		<th style="width: 50px;">Прак.</th><th style="width: 40px;">Сам.</th>
		<th style="width: 80px;">Контроль</th><th style="width: 40px;">КП<br>/КР</th>
		<th style="width: 40px;">Дом.<br>роб.</th><th style="width: 150px;">Реєстровий<br>номер РНП</th>
		<th style="width: 40px;">Студ.</th><th style="width: 50px;">СК</th>
	</tr>
</thead>
<tbody style="width: 1300px; height: 500px;"><?php
$StudyFormCond = ($_POST['stftosel'] > 0) ? "AND d.edu_form_id = ".$_POST['stftosel'] : "";
$SubjWEPQuery = "SELECT a.*, b.naz_dus, c.eduprogram_code, d.reg_number, d.id AS planid, 
												d.sem_start_current, d.sem_final_current, d.sem_start_next, d.sem_final_next,
												d.edu_degree_id, d.actualize_year, e.fakultet_shufr, f.stat_coef, 
												REPLACE(g.cycle_name, 'Дисципліни ','') AS cycle_name,
												CASE a.norm_var WHEN 'Нормат' THEN 'нормативна'
																				WHEN 'Вибірк' THEN 'вибіркова' END AS subj_status
								FROM (plan_work_subj_study a, catalogSubject b, 
											catalogEduProgram c, catalogWorkEduPlan d, catalogFakultet e, 
											catalogEduForm f) 
								LEFT JOIN catalogSubjectCycle g ON a.subj_cycle_id = g.id 
								WHERE b.shufr_kaf = ".$_POST['deptosel']." ".$StudyFormCond." 
											AND a.subject_id = b.id AND a.plan_id = d.id AND d.edu_program_id = c.id
											AND d.faculty_id = e.id AND d.edu_form_id = f.id
								ORDER BY b.naz_dus";
$SubjWEPQuery_result = mysqli_query($conn, $SubjWEPQuery) or 
			die("Помилка сервера при запиті<br>".$SubjWEPQuery." : ".mysqli_error($conn));
$icnt = 0; $StudsCreds = 0; $AcadYear = (empty($_POST['chkNextYear'])) ? "_current" : "_next";
while ($SubjWEPQuery_row = mysqli_fetch_array($SubjWEPQuery_result)) {
	if (($SubjWEPQuery_row['sem_start'.$AcadYear] < 1) or 
			($SubjWEPQuery_row['sem_final'.$AcadYear] < 1)) continue;
	for ($iSem = $SubjWEPQuery_row['sem_start'.$AcadYear]; 
				$iSem <= $SubjWEPQuery_row['sem_final'.$AcadYear]; $iSem++) { 
		$cDegree="";
		switch ($SubjWEPQuery_row['edu_degree_id']) { case 2: $cDegree="Б"; break; 
																									case 3: $cDegree="М"; break; 
																									case 4: $cDegree="Д"; break; 
																								}	$cAcadYear = $cDegree.ceil($iSem / 2);
		$SemTst = $SubjWEPQuery_row['sem'.$iSem.'_sem_test'];
		$SemPap = $SubjWEPQuery_row['sem'.$iSem.'_acad_year_paper'];
		$SemHom = $SubjWEPQuery_row['sem'.$iSem.'_home_tasks'];
		$SemLec = $SubjWEPQuery_row['sem'.$iSem.'_lectural_hours'];
		$SemLab = $SubjWEPQuery_row['sem'.$iSem.'_laboratorials_hours'];
		$SemPrc = $SubjWEPQuery_row['sem'.$iSem.'_practicals_hours'];
		$SemInd = $SubjWEPQuery_row['sem'.$iSem.'_individual_work_hours'];
		$SemHours = $SemLec + $SemLab + $SemPrc + $SemInd;
		$HoursPerCredit = (substr($SubjWEPQuery_row['actualize_year'], 0, 4) >= 2015) ? 30 : 36;
		$SemCredits = round($SemHours / $HoursPerCredit, 1);
		if (($SemLec + $SemLab + $SemPrc + $SemInd > 0) and (
					($_POST['radSemesterSelect'] == "All") or 
					($_POST['radSemesterSelect'] == "Autumn") and ($iSem % 2 > 0) or
					($_POST['radSemesterSelect'] == "Spring") and ($iSem % 2 == 0))
			) { ?>
<tr style="width: 1300px;">
	<td style="width: 30px;"><?php $icnt++; echo $icnt; ?></td>
	<td style="text-align: left;"><?php echo $SubjWEPQuery_row['naz_dus']." (".$SubjWEPQuery_row['subj_status'].
																					", цикл ".$SubjWEPQuery_row['cycle_name'].")"; ?></td>
	<td style="width: 90px;"><?php echo $SubjWEPQuery_row['eduprogram_code']; ?></td>
	<td style="width: 60px;"><?php echo $SubjWEPQuery_row['fakultet_shufr']; ?></td>
	<td style="width: 50px;"><?php echo $cAcadYear." \ ".$iSem; ?></td>
	<td style="width: 60px;"><?php echo $SemCredits." \ ".$SemHours; ?></td>
	<td style="width: 50px;"><?php echo $SemLec; ?></td><td style="width: 40px;"><?php echo $SemLab; ?></td>
	<td style="width: 50px;"><?php echo $SemPrc; ?></td><td style="width: 40px;"><?php echo $SemInd; ?></td>
	<td style="width: 80px;"><?php echo $SemTst; ?></td><td style="width: 40px;"><?php echo $SemPap; ?></td>
	<td style="width: 40px;"><?php echo $SemHom; ?></td><td style="width: 150px; font-size: 85%;"><?php 
			echo $SubjWEPQuery_row['reg_number']." (".$SubjWEPQuery_row['planid'].")"; ?></td>
	<td style="width: 40px;"><?php
			$StudsPlan_query = "SELECT COUNT(*) AS studs 
													FROM catalogStudent a, catalogGroup b 
													WHERE b.plan_id = ".$SubjWEPQuery_row['planid']." AND a.group_link = b.id
												"; 
			$StudsPlan_result = mysqli_query($conn, $StudsPlan_query) or 
					die("Помилка сервера при запиті<br>".$StudsPlan_query." : ".mysqli_error($conn));
			$StudsPlan_row = mysqli_fetch_array($StudsPlan_result); echo $StudsPlan_row['studs'];	?>
	</td><td style="width: 50px;">
		<?	$StCr = 1 / $SubjWEPQuery_row['stat_coef'] * $SemCredits * $StudsPlan_row['studs']; 
				echo str_replace(".", ",",$StCr); ?></td>
</tr><?php $StudsCreds += $StCr;
		}
	}
}
?>
</tbody>
<tfoot style="width: 1300px;">
<tr style="width: 1300px;">
		<th colspan=15 style="text-align: right; width: 1240px;">Обсяг студенто-кредитів на кафедрі </th>
		<th style="width: 50px;"><? echo round($StudsCreds); ?></th>
</tr>
</tfoot>
</table>
