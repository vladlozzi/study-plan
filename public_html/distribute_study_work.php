<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                      "Помилка входу в модуль distribute_study_work.php</p>"; require "footer.php"; exit(); }
function PractName($in) {
	return ((stristr($in, " практика") === FALSE) ? $in." практика" : $in);
}
// Перелік форм навчання для вибору
$EduFormsQuery = "SELECT id, edu_form	FROM catalogEduForm ORDER BY id"; // echo "<p>".$EduFormsQuery."</p>";
$_POST['sbtDistrib'] = isset($_POST['sbtDistrib']) ? $_POST['sbtDistrib'] : "";
$_POST['chkTruncateTimeAmount'] = isset($_POST['chkTruncateTimeAmount']) ? 
					$_POST['chkTruncateTimeAmount'] : ""; 
if (!empty($_POST['chkTruncateTimeAmount'])) {
	$Trunc_query = "TRUNCATE time_amount_for_departs";
	$Trunc_result = mysqli_query($conn,  $Trunc_query) or 
			die("Помилка сервера при запиті<br>".$Trunc_query." : ".mysqli_error($conn));
} 
$TimeAmount_query = "SELECT * FROM time_amount_for_departs LIMIT 1";
$TimeAmount_result = mysqli_query($conn,  $TimeAmount_query) or 
					die("Помилка сервера при запиті<br>".$TimeAmount_query." : ".mysqli_error($conn));
if (mysqli_num_rows($TimeAmount_result) > 0) { ?>
<p style="font-size: 133%; text-align: center; color: red; border: 2px solid red; padding: 5px;"><?php
	echo paramChekerInline("chkTruncateTimeAmount", $_POST['chkTruncateTimeAmount'], 
			"Увага! В базі є дані від старого обсягу годин. Підтвердіть очищення", 
			"onchange=\"submit()\""); ?>
</p><?php return;
}  ?>
<p id="wait" class="blink" style="font-size: 150%; text-align: center; color: blue;">
Зачекайте! Формуємо обсяг годин по кафедрах<br>на основі чинних РНП і контингенту студентів ...</p>
<table>
	<tr><th></th><th>Кафедра</th><th>Дисципліна</th><th>Блок</th><th>Форма навч.</th>
			<th>Академгрупа</th><th>Студ.</th><th>ОП</th><th>Інститут</th><th>Семестр</th>
			<th>Ауд.</th><th>Лек.</th><th>Пр.</th><th>Лаб.</th><th>Конс.</th>
			<th>Екз.</th><th>Зал.</th><th>Дом.з.</th><th>КП/Р</th><th>Практики</th>
			<th>ЕК</th><th>Гол.ЕК</th><th>Вип.роб.</th><th>Асп.</th><th>Інші</th>
			<th>Шифр РНП</th>
	</tr>
<?php $icnt = 0; $AcadYear = "_next"; // обсяг на наступний навчальний рік
if (TRUE) { // тимчасово
$SubjWEPQuery = "SELECT a.*, b.shufr_kaf AS depart_id, d.edu_program_id, d.reg_number, d.id AS planid, 
												d.sem_start_current, d.sem_final_current, d.sem_start_next, d.sem_final_next,
												d.edu_degree_id, d.actualize_year, d.faculty_id, d.edu_form_id
								FROM plan_work_subj_study a, catalogSubject b, catalogWorkEduPlan d 
								WHERE 1 AND a.subject_id = b.id AND 
												INSTR(b.naz_dus, '<b>') = 0 AND 
												a.plan_id = d.id 
								ORDER BY b.shufr_kaf, d.edu_form_id, a.subject_id";
$SubjWEPQuery_result = mysqli_query($conn, $SubjWEPQuery) or 
			die("Помилка сервера при запиті<br>".$SubjWEPQuery." : ".mysqli_error($conn));
while ($SubjWEPQuery_row = mysqli_fetch_array($SubjWEPQuery_result)) {
	if (($SubjWEPQuery_row['sem_start'.$AcadYear] < 1) or 
			($SubjWEPQuery_row['sem_final'.$AcadYear] < 1)) continue;
	for ($iSem = $SubjWEPQuery_row['sem_start'.$AcadYear]; 
				$iSem <= $SubjWEPQuery_row['sem_final'.$AcadYear]; $iSem++) { 
		$StudyForm = EduFormById($SubjWEPQuery_row['edu_form_id']);
		$cDegree = "";
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
		$SemAud = $SemLec + $SemLab + $SemPrc;
		if ($SemLec + $SemLab + $SemPrc + $SemInd > 0) { 
			$GroupsForPosition_query = "SELECT * FROM catalogGroupNext 
																		WHERE plan_id = ".$SubjWEPQuery_row['planid'].
																	 " AND acad_year_next = \"".$cAcadYear."\"";
			$GroupsForPosition_result = mysqli_query($conn, $GroupsForPosition_query) or 
									die("Помилка сервера при запиті<br>".$GroupsForPosition_query." : ".mysqli_error($conn));
			$GroupsForPosition_row = Array();
			if (mysqli_num_rows($GroupsForPosition_result) == 0) { $GroupsForPosition_row['id'] = 0;
				$GroupsForPosition_row['group_next_name'] = ""; ?>
<tr>
	<td><?php $icnt++; echo $icnt; ?></td>
	<td><?php echo /*"(".$SubjWEPQuery_row['depart_id'].") ".*/$Depart; ?></td>
	<td><?php echo /*"(".$SubjWEPQuery_row['subject_id'].") ".*/SubjectById($SubjWEPQuery_row['subject_id']); ?></td>
	<td><?php echo $SubjWEPQuery_row['free_block']; ?></td><td><?php echo $StudyForm; ?></td>
	<td><?php echo $GroupsForPosition_row['id'].$GroupsForPosition_row['group_next_name']; ?></td>
	<td></td>
	<td><?php echo /*"(".$SubjWEPQuery_row['edu_program_id'].") ".*/
								EduProgramById($SubjWEPQuery_row['edu_program_id']); ?></td>
	<td><?php echo $SubjWEPQuery_row['faculty_id']; ?></td>
	<td><?php echo $cAcadYear." / ".$iSem; ?></td><td><?php echo $SemAud; ?></td>
	<td><?php echo $SemLec; ?></td><td><?php echo $SemPrc; ?></td><td><?php echo $SemLab; ?></td>
	<td></td><td></td><td></td><td></td><td><? echo $SemPap; ?></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td><?php echo $SubjWEPQuery_row['reg_number']." (".$SubjWEPQuery_row['planid'].")"; ?></td>
</tr><?php
					$InsertPosition_query = "INSERT INTO time_amount_for_departs VALUES('',
																		\"".$SubjWEPQuery_row['depart_id']."\", 
																		\"".$SubjWEPQuery_row['edu_form_id']."\", 
																		\"".$cAcadYear."\",\"".$iSem."\",
																		\"".$SubjWEPQuery_row['subject_id']."\",'',
																		\"".$SubjWEPQuery_row['free_block']."\",\"".$SemLec."\",'',
																		\"".$GroupsForPosition_row['id']."\",
																		'','',
																		\"".$SemLec."\",\"".$SemPrc."\",\"".$SemLab."\", 
																		'','','','','','',
																		'','','','','','',\"".$SubjWEPQuery_row['planid']."\",''
																	)";
					$InsertPosition_result = mysqli_query($conn, $InsertPosition_query) or 
											die("Помилка сервера при запиті<br>".$InsertPosition_query." : ".mysqli_error($conn));
			} else {
				while ($GroupsForPosition_row = mysqli_fetch_array($GroupsForPosition_result)) { 
// кількість студентів і кількість підгруп, якщо є лабораторні заняття
//					$StudCount_query = "SELECT stud_count FROM catalogStudent 
//															WHERE role=1 AND group_link = \"".$GroupsForPosition_row['id']."\"";
//					$StudCount_result = mysqli_query($conn, $StudCount_query) or 
////									die("Помилка сервера при запиті<br>".$StudCount_query." : ".mysqli_error($conn));
//					$StudCount_row = mysqli_fetch_array($StudCount_result); 
					$StudCount = $GroupsForPosition_row['stud_count'];
					$TConsCur = round(2 + 0.2 * $StudCount, 0); // консультації поточні, протягом семестру
					$TConsExam = ($SemTst == "екз.") ? 2 : 0; // консультація перед екзаменом
					$TCons = $TConsCur + $TConsExam;
					$TExam = ($SemTst == "екз.") ? round(2 + 0.33 * $StudCount, 0) : 0;
					$TCred = ($SemTst == "зал.") ? 2 : 0;
					$Depart = DepartCodeById($SubjWEPQuery_row['depart_id']);
					$DepartGroup = DepartGroupById($SubjWEPQuery_row['depart_id']);
					$THome = ($Depart == "БМ" or $Depart == "ПМ" or 
										$Depart == "ВМАТ" or $Depart == "ІКГ") ? round(0.5 * $StudCount * $SemHom, 0) : 0;
					$THome = ($StudyForm == "З" and !($Depart == "БМ" or $Depart == "ПМ" or 
										$Depart == "ВМАТ" or $Depart == "ІКГ")) ? round(0.33 * $StudCount * $SemHom, 0) : $THome;
					$TPap1s = 0; switch ($SemPap) { // більше годин для випускних кафедр 
													case "КП" : $TPap1s = ($DepartGroup == "1") ? 4 : 3; break; 
													case "КР" : $TPap1s = ($DepartGroup == "1") ? 3 : 2; break; };
					$TPap = $TPap1s * $StudCount;
					$SubgroupsCount = (($StudCount > 20) and ($SemLab > 0)) ? 2 : 1; ?>
<tr><td><?php $icnt++; echo $icnt; ?></td>
		<td><?php echo /*"(".$SubjWEPQuery_row['depart_id'].") ".*/$Depart; ?></td>
		<td><?php echo /*"(".$SubjWEPQuery_row['subject_id'].") ".*/SubjectById($SubjWEPQuery_row['subject_id']); ?></td>
		<td><?php echo $SubjWEPQuery_row['free_block']; ?></td><td><?php echo $StudyForm; ?></td>
		<td><?php echo $GroupsForPosition_row['id']." - ".$GroupsForPosition_row['group_next_name'].
									(($SubgroupsCount > 1) ? " (".$SubgroupsCount.")" : ""); ?></td>
		<td><?php echo $GroupsForPosition_row['stud_count']; ?></td>
		<td><?php echo /*"(".$SubjWEPQuery_row['edu_program_id'].") ".*/
									EduProgramById($SubjWEPQuery_row['edu_program_id']); ?></td>
		<td><?php echo $SubjWEPQuery_row['faculty_id']; ?></td>
		<td><?php echo $cAcadYear." / ".$iSem; ?></td><td><?php echo $SemAud; ?></td>
		<td><?php echo $SemLec; ?></td><td><?php echo $SemPrc; ?></td>
		<td><?php echo $SemLab.(($SubgroupsCount > 1) ? "/".$SubgroupsCount * $SemLab : ""); ?></td>
		<td><?php echo ($TCons > 0) ? $TConsCur." + ".$TConsExam : ""; ?></td>                    
		<td><?php echo ($TExam > 0) ? $TExam : ""; ?></td>
		<td><?php echo ($TCred > 0) ? $TCred : ""; ?></td>
		<td><?php echo ($THome > 0) ? $THome : ""; ?></td>
		<td><?php echo ($TPap > 0) ? $SemPap." / ".$TPap : ""; ?></td><td></td>
		<td></td><td></td><td></td><td></td><td></td>
		<td><?php echo $SubjWEPQuery_row['reg_number']." (".$SubjWEPQuery_row['planid'].")"; ?></td>
</tr><?php 
					$InsertPosition_query = "INSERT INTO time_amount_for_departs VALUES('',
																		\"".$SubjWEPQuery_row['depart_id']."\", 
																		\"".$SubjWEPQuery_row['edu_form_id']."\", 
																		\"".$cAcadYear."\",\"".$iSem."\",
																		\"".$SubjWEPQuery_row['subject_id']."\",'',
																		\"".$SubjWEPQuery_row['free_block']."\",\"".$SemLec."\",'',
																		\"".$GroupsForPosition_row['id']."\",
																		\"".$StudCount."\",\"".$SubgroupsCount."\",
																		\"".$SemLec."\",\"".$SemPrc."\",\"".$SubgroupsCount*$SemLab."\", 
																		\"".$TConsCur."\",\"".$TConsExam."\",\"".$TExam."\",\"".$TCred."\",
																		\"".$THome."\",\"".$TPap."\",
																		'','','','','','',\"".$SubjWEPQuery_row['planid']."\",''
																	)";
					$InsertPosition_result = mysqli_query($conn, $InsertPosition_query) or 
											die("Помилка сервера при запиті<br>".$InsertPosition_query." : ".mysqli_error($conn));
				}
			}
		}
	}
}
} // тимчасово

$PractiquesWEPQuery = "SELECT a.*, d.edu_program_id, d.reg_number, d.id AS planid, 
												d.sem_start_current, d.sem_final_current, d.sem_start_next, d.sem_final_next,
												d.edu_degree_id, d.actualize_year, d.faculty_id, d.edu_form_id
								FROM plan_work_practicals a, catalogWorkEduPlan d 
								WHERE 1 AND a.plan_id = d.id 
								ORDER BY a.depart_id, d.edu_form_id, a.practicals_sem";
$PractiquesWEPQuery_result = mysqli_query($conn, $PractiquesWEPQuery) or 
			die("Помилка сервера при запиті<br>".$PractiquesWEPQuery." : ".mysqli_error($conn));
while ($PractiquesWEPQuery_row = mysqli_fetch_array($PractiquesWEPQuery_result)) {
	if (($PractiquesWEPQuery_row['sem_start'.$AcadYear] < 1) or 
			($PractiquesWEPQuery_row['sem_final'.$AcadYear] < 1)) continue;
	for ($iSem = $PractiquesWEPQuery_row['sem_start'.$AcadYear]; 
				$iSem <= $PractiquesWEPQuery_row['sem_final'.$AcadYear]; $iSem++) {
		if ($PractiquesWEPQuery_row['practicals_sem'] != $iSem) continue;
		$StudyForm = EduFormById($PractiquesWEPQuery_row['edu_form_id']);
		$cDegree = ""; $Depart = DepartCodeById($PractiquesWEPQuery_row['depart_id']);
		switch ($PractiquesWEPQuery_row['edu_degree_id']) { case 2: $cDegree="Б"; break; 
																									case 3: $cDegree="М"; break; 
																									case 4: $cDegree="Д"; break; 
																								}	$cAcadYear = $cDegree.ceil($iSem / 2);
		$GroupsForPosition_query = "SELECT * FROM catalogGroupNext 
																WHERE plan_id = ".$PractiquesWEPQuery_row['planid'].
															 " AND acad_year_next = \"".$cAcadYear."\"";
		$GroupsForPosition_result = mysqli_query($conn, $GroupsForPosition_query) or 
									die("Помилка сервера при запиті<br>".$GroupsForPosition_query." : ".mysqli_error($conn));
		$GroupsForPosition_row = Array();
		if (mysqli_num_rows($GroupsForPosition_result) == 0) { $GroupsForPosition_row['id'] = 0;
			$GroupsForPosition_row['group_next_name'] = ""; ?>
<tr>
	<td><?php $icnt++; echo $icnt; ?></td>
	<td><?php echo $Depart; ?></td>
	<td><?php echo PractName($PractiquesWEPQuery_row['practicals_name']); ?></td>
	<td><?php echo $PractiquesWEPQuery_row['practicals_code']; ?></td><td><?php echo $StudyForm; ?></td>
	<td><?php echo $GroupsForPosition_row['id'].$GroupsForPosition_row['group_next_name']; ?></td>
	<td></td>
	<td><?php echo /*"(".$PractiquesWEPQuery_row['edu_program_id'].") ".*/
								EduProgramById($PractiquesWEPQuery_row['edu_program_id']); ?></td>
	<td><?php echo $PractiquesWEPQuery_row['faculty_id']; ?></td>
	<td><?php echo $cAcadYear." / ".$iSem; ?></td><td></td>
	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td><?php echo $PractiquesWEPQuery_row['reg_number']." (".$PractiquesWEPQuery_row['planid'].")"; ?></td>
</tr><?php
			} else {
				while ($GroupsForPosition_row = mysqli_fetch_array($GroupsForPosition_result)) { 
// кількість студентів і кількість підгруп, якщо є лабораторні заняття
//					$StudCount_query = "SELECT stud_count FROM catalogStudent 
//															WHERE role=1 AND group_link = \"".$GroupsForPosition_row['id']."\"";
//					$StudCount_result = mysqli_query($conn, $StudCount_query) or 
////									die("Помилка сервера при запиті<br>".$StudCount_query." : ".mysqli_error($conn));
//					$StudCount_row = mysqli_fetch_array($StudCount_result); 
					$StudCount = $GroupsForPosition_row['stud_count']; $TPraq = 0;
					switch ($PractiquesWEPQuery_row['practicals_code']) { 
						case "НП": $TPraq = round(6 * 6 * $PractiquesWEPQuery_row['practicals_credits'] / 1.5); break; 
						case "ВП": $TPraq = round(1 * $StudCount * $PractiquesWEPQuery_row['practicals_credits'] / 1.5); break; 
						case "ПП": $TPraq = round(1 * $StudCount * $PractiquesWEPQuery_row['practicals_credits'] / 1.5); break; 
					} ?>
<tr><td><?php $icnt++; echo $icnt; ?></td>
	<td><?php echo /*"(".$PractiquesWEPQuery_row['depart_id'].") ".*/$Depart; ?></td>
	<td><?php echo PractName($PractiquesWEPQuery_row['practicals_name']); ?></td>
	<td><?php echo $PractiquesWEPQuery_row['practicals_code']; ?></td><td><?php echo $StudyForm; ?></td>
	<td><?php echo $GroupsForPosition_row['id']." - ".$GroupsForPosition_row['group_next_name']; ?></td>
	<td><?php echo $GroupsForPosition_row['stud_count']; ?></td>
	<td><?php echo /*"(".$PractiquesWEPQuery_row['edu_program_id'].") ".*/
								EduProgramById($PractiquesWEPQuery_row['edu_program_id']); ?></td>
	<td><?php echo $PractiquesWEPQuery_row['faculty_id']; ?></td>
	<td><?php echo $cAcadYear." / ".$iSem; ?></td><td></td><td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td><?php echo ($TPraq > 0) ? $TPraq : ""; ?></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td><?php echo $PractiquesWEPQuery_row['reg_number']." (".$PractiquesWEPQuery_row['planid'].")"; ?></td>
</tr><?php 
					$InsertPosition_query = "INSERT INTO time_amount_for_departs VALUES('',
																		\"".$PractiquesWEPQuery_row['depart_id']."\", 
																		\"".$PractiquesWEPQuery_row['edu_form_id']."\", 
																		\"".$cAcadYear."\",\"".$iSem."\",'',
																		\"".PractName($PractiquesWEPQuery_row['practicals_name'])."\",
																		\"".$PractiquesWEPQuery_row['practicals_code']."\",'','',
																		\"".$GroupsForPosition_row['id']."\",
																		\"".$StudCount."\",'','','','','','','','','','',
																		\"".$TPraq."\",'','','','','',\"".$PractiquesWEPQuery_row['planid']."\",''
																	)";
					$InsertPosition_result = mysqli_query($conn, $InsertPosition_query) or 
											die("Помилка сервера при запиті<br>".$InsertPosition_query." : ".mysqli_error($conn));
			}
		}
	}
} 

$CertifsWEPQuery = "SELECT a.*, d.edu_program_id, d.reg_number, d.id AS planid, 
												d.sem_start_current, d.sem_final_current, d.sem_start_next, d.sem_final_next,
												d.edu_degree_id, d.actualize_year, d.faculty_id, d.edu_form_id
								FROM plan_work_certification a, catalogWorkEduPlan d 
								WHERE 1 AND a.plan_id = d.id 
								ORDER BY a.depart_id, d.edu_form_id, a.certif_sem";
$CertifsWEPQuery_result = mysqli_query($conn, $CertifsWEPQuery) or 
			die("Помилка сервера при запиті<br>".$CertifsWEPQuery." : ".mysqli_error($conn));
while ($CertifsWEPQuery_row = mysqli_fetch_array($CertifsWEPQuery_result)) {
	if (($CertifsWEPQuery_row['sem_start'.$AcadYear] < 1) or 
			($CertifsWEPQuery_row['sem_final'.$AcadYear] < 1)) continue;
	for ($iSem = $CertifsWEPQuery_row['sem_start'.$AcadYear]; 
				$iSem <= $CertifsWEPQuery_row['sem_final'.$AcadYear]; $iSem++) {
		if ($CertifsWEPQuery_row['certif_sem'] != $iSem) continue;
		$StudyForm = EduFormById($CertifsWEPQuery_row['edu_form_id']);
		$cDegree = ""; $Depart = ($CertifsWEPQuery_row['depart_id'] > 0) ?
															DepartCodeById($CertifsWEPQuery_row['depart_id']) : "";
		switch ($CertifsWEPQuery_row['edu_degree_id']) { case 2: $cDegree="Б"; break; 
																									case 3: $cDegree="М"; break; 
																									case 4: $cDegree="Д"; break; 
																								}	$cAcadYear = $cDegree.ceil($iSem / 2);
		$GroupsForPosition_query = "SELECT * FROM catalogGroupNext 
																WHERE plan_id = ".$CertifsWEPQuery_row['planid'].
															 " AND acad_year_next = \"".$cAcadYear."\"";
		$GroupsForPosition_result = mysqli_query($conn, $GroupsForPosition_query) or 
									die("Помилка сервера при запиті<br>".$GroupsForPosition_query." : ".mysqli_error($conn));
		$GroupsForPosition_row = Array();
		if (mysqli_num_rows($GroupsForPosition_result) == 0) { $GroupsForPosition_row['id'] = 0;
			$GroupsForPosition_row['group_next_name'] = ""; 
			if (stristr($CertifsWEPQuery_row['certif_name'], "(консул") === FALSE) {
?>
<tr>
	<td><?php $icnt++; echo $icnt; ?></td>
	<td><?php echo $Depart; ?></td>
	<td><?php echo $CertifsWEPQuery_row['certif_name']; ?></td>
	<td><?php echo $CertifsWEPQuery_row['certif_code']; ?></td><td><?php echo $StudyForm; ?></td>
	<td><?php echo $GroupsForPosition_row['id'].$GroupsForPosition_row['group_next_name']; ?></td>
	<td></td>
	<td><?php echo /*"(".$PractiquesWEPQuery_row['edu_program_id'].") ".*/
								EduProgramById($CertifsWEPQuery_row['edu_program_id']); ?></td>
	<td><?php echo $CertifsWEPQuery_row['faculty_id']; ?></td>
	<td><?php echo $cAcadYear." / ".$iSem; ?></td><td></td>
	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td><?php echo $CertifsWEPQuery_row['reg_number']." (".$CertifsWEPQuery_row['planid'].")"; ?></td>
</tr><?php
			}
		} else {
			while ($GroupsForPosition_row = mysqli_fetch_array($GroupsForPosition_result)) {
// кількість студентів і кількість підгруп, якщо є лабораторні заняття
//					$StudCount_query = "SELECT stud_count FROM catalogStudent 
//															WHERE role=1 AND group_link = \"".$GroupsForPosition_row['id']."\"";
//					$StudCount_result = mysqli_query($conn, $StudCount_query) or 
////									die("Помилка сервера при запиті<br>".$StudCount_query." : ".mysqli_error($conn));
//					$StudCount_row = mysqli_fetch_array($StudCount_result); 
				$StudCount = $GroupsForPosition_row['stud_count']; 
				$TExamBoard = 0; $THeadExamBoard = 0; $TFinalThesis = 0;
				if (stristr($CertifsWEPQuery_row['certif_name'], "конс") === FALSE) {
					switch ($CertifsWEPQuery_row['certif_code']) { 
						case "БР": $TExamBoard = round(3 * 0.5 * $StudCount); $THeadExamBoard = round(0.5 * $StudCount);
											$TFinalThesis = 13 * $StudCount; break;
						case "МР": $TExamBoard = round(3 * 0.5 * $StudCount); $THeadExamBoard = round(0.5 * $StudCount);
											$TFinalThesis = 33 * $StudCount; break;
						case "ДЕ": $TExamBoard = round(3 * 0.5 * $StudCount); $THeadExamBoard = round(0.5 * $StudCount); break; 
					} ?>
<tr><td><?php $icnt++; echo $icnt; ?></td>
	<td><?php echo /*"(".$PractiquesWEPQuery_row['depart_id'].") ".*/$Depart; ?></td>
	<td><?php echo $CertifsWEPQuery_row['certif_name']; ?></td>
	<td><?php echo $CertifsWEPQuery_row['certif_code']; ?></td><td><?php echo $StudyForm; ?></td>
	<td><?php echo $GroupsForPosition_row['id']." - ".$GroupsForPosition_row['group_next_name']; ?></td>
	<td><?php echo $GroupsForPosition_row['stud_count']; ?></td>
	<td><?php echo /*"(".$PractiquesWEPQuery_row['edu_program_id'].") ".*/
								EduProgramById($CertifsWEPQuery_row['edu_program_id']); ?></td>
	<td><?php echo $CertifsWEPQuery_row['faculty_id']; ?></td>
	<td><?php echo $cAcadYear." / ".$iSem; ?></td><td></td><td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td>
	<td><?php echo ($TExamBoard > 0) ? $TExamBoard : ""; ?></td>
	<td><?php echo ($THeadExamBoard > 0) ? $THeadExamBoard : ""; ?></td>
	<td><?php echo ($TFinalThesis > 0) ? $TFinalThesis : ""; ?></td><td></td><td></td>	
	<td><?php echo $CertifsWEPQuery_row['reg_number']." (".$CertifsWEPQuery_row['planid'].")"; ?></td>
</tr><?php 
					$InsertPosition_query = "INSERT INTO time_amount_for_departs VALUES('',
																		\"".$CertifsWEPQuery_row['depart_id']."\", 
																		\"".$CertifsWEPQuery_row['edu_form_id']."\", 
																		\"".$cAcadYear."\",\"".$iSem."\",'',
																		\"".$CertifsWEPQuery_row['certif_name']."\",
																		\"".$CertifsWEPQuery_row['certif_code']."\",'','',
																		\"".$GroupsForPosition_row['id']."\",
																		\"".$StudCount."\",'','','','','','','','','','','',
																		\"".$TExamBoard."\",\"".$THeadExamBoard."\",\"".$TFinalThesis."\",
																		'','',\"".$CertifsWEPQuery_row['planid']."\",''
																	)";
					$InsertPosition_result = mysqli_query($conn, $InsertPosition_query) or 
											die("Помилка сервера при запиті<br>".$InsertPosition_query." : ".mysqli_error($conn));
				
				}
			}
		}
	}
} ?>
</table>
<script> 
document.getElementById("wait").innerHTML = "Обсяг годин по кафедрах сформовано";
document.getElementById("wait").className = "";
</script>
