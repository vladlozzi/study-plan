<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                      "Помилка входу в модуль distribute_aud_time.php</p>"; require "footer.php"; exit(); }
// Перелік форм навчання для вибору
$EduFormsQuery = "SELECT id, edu_form	FROM catalogEduForm ORDER BY id"; // echo "<p>".$EduFormsQuery."</p>";
$_POST['stftosel'] = isset($_POST['stftosel']) ? $_POST['stftosel'] : "";
$_POST['radSemesterSelect'] = isset($_POST['radSemesterSelect']) ? $_POST['radSemesterSelect'] : "Spring";
$_POST['chkNextYear'] = isset($_POST['chkNextYear']) ? $_POST['chkNextYear'] : ""; ?> &nbsp; &nbsp; <?php
$_POST['sbtDistrib'] = isset($_POST['sbtDistrib']) ? $_POST['sbtDistrib'] : "";
$_POST['chkTruncateTimeDistrib'] = isset($_POST['chkTruncateTimeDistrib']) ? 
					$_POST['chkTruncateTimeDistrib'] : ""; 
if (!empty($_POST['chkTruncateTimeDistrib'])) {
	$Trunc_query = "TRUNCATE time_distrib_to_create_schedule";
	$Trunc_result = mysqli_query($conn,  $Trunc_query) or 
			die("Помилка сервера при запиті<br>".$Trunc_query." : ".mysqli_error($conn));
} 
echo "<br>".selectCommonSelect
	("Форма навчання: ", "stftosel", $conn, $EduFormsQuery,
  	"id", $_POST['stftosel'], "edu_form", ""); ?> &nbsp; &nbsp; 
<input type="radio" name="radSemesterSelect" value="Autumn" 
	<?php if ($_POST['radSemesterSelect'] == "Autumn") echo "checked"; ?>> 
	осінній семестр &nbsp; 
<input type="radio" name="radSemesterSelect" value="Spring" 
	<?php if ($_POST['radSemesterSelect'] == "Spring") echo "checked"; ?>> 
	весняний семестр &nbsp; &nbsp; 
<?php echo paramChekerInline("chkNextYear", $_POST['chkNextYear'], "На наступний навч.рік", ""); ?> 
&nbsp; &nbsp; <input type="submit" name="sbtDistrib" value="Далі" style="font-weight: bold; color: blue;" >
<?php
if (empty($_POST['sbtDistrib']) or empty($_POST['stftosel'])) return;
$AudTimeDistrib_query = "SELECT * FROM time_distrib_to_create_schedule LIMIT 1";
$AudTimeDistrib_result = mysqli_query($conn,  $AudTimeDistrib_query) or 
					die("Помилка сервера при запиті<br>".$AudTimeDistrib_query." : ".mysqli_error($conn));
if (mysqli_num_rows($AudTimeDistrib_result) > 0) { ?>
<p style="font-size: 133%; text-align: center; color: red; border: 2px solid red; padding: 5px;"><?php
    echo paramChekerInline("chkTruncateTimeDistrib", $_POST['chkTruncateTimeDistrib'], 
			"Увага! В базі є дані від старого розподілу. Підтвердіть очищення", 
			"onchange=\"submit()\""); ?>
</p><?php
} ?>
<p id="wait" class="blink" style="font-size: 150%; text-align: center; color: blue;">
Зачекайте! Формуємо розподіл аудиторного часу<br>на основі чинних РНП і контингенту студентів ...</p>
<table><?php
$StudyFormCond = ($_POST['stftosel'] > 0) ? "AND d.edu_form_id = ".$_POST['stftosel'] : "";
$SubjWEPQuery = "SELECT a.*, b.shufr_kaf AS depart_id, d.edu_program_id, d.reg_number, d.id AS planid, 
												d.sem_start_current, d.sem_final_current, d.sem_start_next, d.sem_final_next,
												d.edu_degree_id, d.actualize_year, d.faculty_id
								FROM plan_work_subj_study a, catalogSubject b, catalogWorkEduPlan d 
								WHERE 1 ".$StudyFormCond." 
											AND a.subject_id = b.id AND a.plan_id = d.id 
								ORDER BY b.shufr_kaf, a.subject_id";
$SubjWEPQuery_result = mysqli_query($conn, $SubjWEPQuery) or 
			die("Помилка сервера при запиті<br>".$SubjWEPQuery." : ".mysqli_error($conn));
$icnt = 0; $AcadYear = (empty($_POST['chkNextYear'])) ? "_current" : "_next";
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
		$SemAud = $SemLec + $SemLab + $SemPrc;
		if (($SemLec + $SemLab + $SemPrc + $SemInd > 0) and (
					($_POST['radSemesterSelect'] == "All") or 
					($_POST['radSemesterSelect'] == "Autumn") and ($iSem % 2 > 0) or
					($_POST['radSemesterSelect'] == "Spring") and ($iSem % 2 == 0))
			) { 
			if ($SemAud > 0) { // Шукаємо академгрупи для позиції 
				if (empty($_POST['chkNextYear'])) { // на поточний навчальний рік
					$GroupsForPosition_query = "SELECT * FROM catalogGroup WHERE plan_id = ".$SubjWEPQuery_row['planid'].
																		 " AND num_kurs = \"".$cAcadYear."\"";
					$GroupsForPosition_result = mysqli_query($conn, $GroupsForPosition_query) or 
										die("Помилка сервера при запиті<br>".$GroupsForPosition_query." : ".mysqli_error($conn));
					$GroupsForPosition_row = Array();
					if (mysqli_num_rows($GroupsForPosition_result) == 0) { $GroupsForPosition_row['id'] = 0;
						$GroupsForPosition_row['nazva_grupu'] = ""; ?>
<tr>
	<td><?php $icnt++; echo $icnt; ?></td>
	<td><?php echo $SubjWEPQuery_row['depart_id']; ?></td>
	<td><?php echo $SubjWEPQuery_row['subject_id']; ?></td>
	<td><?php echo $SubjWEPQuery_row['free_block']; ?></td>
	<td><?php echo $GroupsForPosition_row['id']; ?></td>
	<td><?php echo $GroupsForPosition_row['nazva_grupu']; ?></td><td>0</td>
	<td><?php echo $SubjWEPQuery_row['edu_program_id']; ?></td>
	<td><?php echo $SubjWEPQuery_row['faculty_id']; ?></td>
	<td><?php echo $cAcadYear." / ".$iSem; ?></td>
	<td><?php echo $SemAud; ?></td>
	<td><?php echo $SemLec; ?></td><td><?php echo $SemPrc; ?></td><td><?php echo $SemLab; ?></td>
	<td><?php echo $SubjWEPQuery_row['reg_number']." (".$SubjWEPQuery_row['planid'].")"; ?></td>
</tr><?php
					} else {
						while ($GroupsForPosition_row = mysqli_fetch_array($GroupsForPosition_result)) { 
// кількість студентів і кількість підгруп, якщо є лабораторні заняття
							$StudCount_query = "SELECT COUNT(*) AS stud_count FROM catalogStudent 
																WHERE role=1 AND group_link = \"".$GroupsForPosition_row['id']."\"";
							$StudCount_result = mysqli_query($conn, $StudCount_query) or 
										die("Помилка сервера при запиті<br>".$StudCount_query." : ".mysqli_error($conn));
							$StudCount_row = mysqli_fetch_array($StudCount_result); 
							$StudCount = $StudCount_row['stud_count'];
							$SubgroupsCount = (($StudCount > 20) and ($SemLab > 0)) ? 2 : 1;
							for ($Subgroup = 1; $Subgroup <= $SubgroupsCount; $Subgroup++) { ?>
<tr><td><?php 	$icnt++; echo $icnt; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['depart_id']; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['subject_id']; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['free_block']; ?></td>
		<td><?php 	echo $GroupsForPosition_row['id']; ?></td>
		<td><?php 	echo $GroupsForPosition_row['nazva_grupu'].(($SubgroupsCount > 1) ? "/".$Subgroup : ""); ?></td>
		<td><?php 	echo $StudCount/$SubgroupsCount; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['edu_program_id']; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['faculty_id']; ?></td>
		<td><?php 	echo $cAcadYear." / ".$iSem; ?></td><td><?php echo $SemAud; ?></td>
		<td><?php 	echo $SemLec; ?></td><td><?php echo $SemPrc; ?></td><td><?php echo $SemLab; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['reg_number']." (".$SubjWEPQuery_row['planid'].")"; ?></td>
</tr><?php
								$WeekLec = ($SemLec % 18 == 0) ? $SemLec / 18 :
														(($SemLec % 16 == 0) ? $SemLec / 16 : $SemLec);
								$WeekPrc = ($SemPrc % 18 == 0) ? $SemPrc / 18 :
														(($SemPrc % 16 == 0) ? $SemPrc / 16 : $SemPrc);
								$WeekLab = ($SemLab % 18 == 0) ? $SemLab / 18 :
														(($SemLab % 16 == 0) ? $SemLab / 16 : $SemLab);
								$InsertPosition_query = "INSERT INTO time_distrib_to_create_schedule VALUES('',
																			\"".$SubjWEPQuery_row['depart_id']."\", \"".$iSem."\",
																			\"".$SubjWEPQuery_row['subject_id']."\",
																			\"".$SubjWEPQuery_row['free_block']."\", \"\",
																			\"".$GroupsForPosition_row['id']."\",
																			\"".$StudCount/$SubgroupsCount."\",
																			\"".$WeekLec."\", 0, \"\", 
																			\"".$WeekPrc."\", 0, \"\", \"".$Subgroup."\",
																			\"".$WeekLab."\", 0, '', ''
																			)";
								$InsertPosition_result = mysqli_query($conn, $InsertPosition_query) or 
											die("Помилка сервера при запиті<br>".$InsertPosition_query." : ".mysqli_error($conn));

							}
						}
					}
				} else { // на наступний навчальний рік
					$GroupsForPosition_query = "SELECT * FROM catalogGroupNext WHERE plan_id = ".$SubjWEPQuery_row['planid'].
																		 " AND acad_year_next = \"".$cAcadYear."\"";
					$GroupsForPosition_result = mysqli_query($conn, $GroupsForPosition_query) or 
										die("Помилка сервера при запиті<br>".$GroupsForPosition_query." : ".mysqli_error($conn));
					$GroupsForPosition_row = Array();
					if (mysqli_num_rows($GroupsForPosition_result) == 0) { $GroupsForPosition_row['id'] = 0;
						$GroupsForPosition_row['group_next_name'] = ""; ?>
<tr>
	<td><?php $icnt++; echo $icnt; ?></td>
	<td><?php echo $SubjWEPQuery_row['depart_id']; ?></td>
	<td><?php echo $SubjWEPQuery_row['subject_id']; ?></td>
	<td><?php echo $SubjWEPQuery_row['free_block']; ?></td>
	<td><?php echo $GroupsForPosition_row['id']; ?></td>
	<td><?php echo $GroupsForPosition_row['group_next_name']; ?></td><td>0</td>
	<td><?php echo $SubjWEPQuery_row['edu_program_id']; ?></td>
	<td><?php echo $SubjWEPQuery_row['faculty_id']; ?></td>
	<td><?php echo $cAcadYear." / ".$iSem; ?></td>
	<td><?php echo $SemAud; ?></td>
	<td><?php echo $SemLec; ?></td><td><?php echo $SemPrc; ?></td><td><?php echo $SemLab; ?></td>
	<td><?php echo $SubjWEPQuery_row['reg_number']." (".$SubjWEPQuery_row['planid'].")"; ?></td>
</tr><?php
					} else {
						while ($GroupsForPosition_row = mysqli_fetch_array($GroupsForPosition_result)) { 
// кількість студентів і кількість підгруп, якщо є лабораторні заняття
							$StudCount = $GroupsForPosition_row['stud_count'];
							$SubgroupsCount = (($StudCount > 20) and ($SemLab > 0)) ? 2 : 1;
							for ($Subgroup = 1; $Subgroup <= $SubgroupsCount; $Subgroup++) { ?>
<tr><td><?php 	$icnt++; echo $icnt; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['depart_id']; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['subject_id']; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['free_block']; ?></td>
		<td><?php 	echo $GroupsForPosition_row['id']; ?></td>
		<td><?php 	echo $GroupsForPosition_row['group_next_name'].(($SubgroupsCount > 1) ? "/".$Subgroup : ""); ?></td>
		<td><?php 	echo $StudCount/$SubgroupsCount; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['edu_program_id']; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['faculty_id']; ?></td>
		<td><?php 	echo $cAcadYear." / ".$iSem; ?></td><td><?php echo $SemAud; ?></td>
		<td><?php 	echo $SemLec; ?></td><td><?php echo $SemPrc; ?></td><td><?php echo $SemLab; ?></td>
		<td><?php 	echo $SubjWEPQuery_row['reg_number']." (".$SubjWEPQuery_row['planid'].")"; ?></td>
</tr><?php
								$WeekLec = ($SemLec % 18 == 0) ? $SemLec / 18 :
														(($SemLec % 16 == 0) ? $SemLec / 16 : $SemLec);
								$WeekPrc = ($SemPrc % 18 == 0) ? $SemPrc / 18 :
														(($SemPrc % 16 == 0) ? $SemPrc / 16 : $SemPrc);
								$WeekLab = ($SemLab % 18 == 0) ? $SemLab / 18 :
														(($SemLab % 16 == 0) ? $SemLab / 16 : $SemLab);
								$InsertPosition_query = "INSERT INTO time_distrib_to_create_schedule VALUES('',
																			\"".$SubjWEPQuery_row['depart_id']."\", \"".$iSem."\",
																			\"".$SubjWEPQuery_row['subject_id']."\",
																			\"".$SubjWEPQuery_row['free_block']."\", \"\",
																			\"".$GroupsForPosition_row['id']."\",
																			\"".$StudCount/$SubgroupsCount."\",
																			\"".$WeekLec."\", 0, \"\", 
																			\"".$WeekPrc."\", 0, \"\", \"".$Subgroup."\",
																			\"".$WeekLab."\", 0, '', ''
																			)";
								$InsertPosition_result = mysqli_query($conn, $InsertPosition_query) or 
											die("Помилка сервера при запиті<br>".$InsertPosition_query." : ".mysqli_error($conn));

							}
						}
					}
				}
			}
		}
	}
} ?>
</table>
<script> 
document.getElementById("wait").innerHTML = "Розподіл аудиторного часу сформовано";
document.getElementById("wait").className = "";
</script>