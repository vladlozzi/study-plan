<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
	                              "Помилка входу в модуль subjects_study_put.php</p>"; 
									 require "footer.php"; exit(); }
$NormVarSubjQuery = "SELECT * FROM plan_work_subj_study 
										WHERE plan_id = \"".$query_row['id']."\" AND norm_var = \"$NormVarUkr\"
								ORDER BY subj_cycle_id"; //	echo $NormVarSubjQuery;
$query10_result = mysqli_query($conn, $NormVarSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$NormVarSubjQuery." : ".mysqli_error($conn));
$Sum1LecCycle = 0; $Sum1LabCycle = 0; $Sum1PrcCycle = 0; $Sum1IndCycle = 0; 
$Sum2LecCycle = 0; $Sum2LabCycle = 0; $Sum2PrcCycle = 0; $Sum2IndCycle = 0; 
$Sum3LecCycle = 0; $Sum3LabCycle = 0; $Sum3PrcCycle = 0; $Sum3IndCycle = 0; 
$Sum4LecCycle = 0; $Sum4LabCycle = 0; $Sum4PrcCycle = 0; $Sum4IndCycle = 0; 
$Sum5LecCycle = 0; $Sum5LabCycle = 0; $Sum5PrcCycle = 0; $Sum5IndCycle = 0; 
$Sum6LecCycle = 0; $Sum6LabCycle = 0; $Sum6PrcCycle = 0; $Sum6IndCycle = 0; 
$Sum7LecCycle = 0; $Sum7LabCycle = 0; $Sum7PrcCycle = 0; $Sum7IndCycle = 0; 
$Sum8LecCycle = 0; $Sum8LabCycle = 0; $Sum8PrcCycle = 0; $Sum8IndCycle = 0; 
while ($query10_row = mysqli_fetch_array($query10_result)) { $SubjCnt++;
	if ($query10_row['subj_cycle_id'] > 0) { $CyclesRowsCount++;
		$CyclesArray[$CyclesRowsCount] = $query10_row['subj_cycle_id'];
		if (!((mb_strpos(SubjectDepartCodeById($query10_row['subject_id']),"Фізична культура")!==false)
			or (mb_strpos(SubjectDepartCodeById($query10_row['subject_id']),"Фізичне виховання")!==false))) {
			$Sum1LecCycle += $query10_row['sem1_lectural_hours']; 
			$Sum1LabCycle += $query10_row['sem1_laboratorials_hours'];
			$Sum1PrcCycle += $query10_row['sem1_practicals_hours']; 
			$Sum1IndCycle += $query10_row['sem1_individual_work_hours'];
			$Sum2LecCycle += $query10_row['sem2_lectural_hours']; 
			$Sum2LabCycle += $query10_row['sem2_laboratorials_hours'];
			$Sum2PrcCycle += $query10_row['sem2_practicals_hours']; 
			$Sum2IndCycle += $query10_row['sem2_individual_work_hours'];
			$Sum3LecCycle += $query10_row['sem3_lectural_hours']; 
			$Sum3LabCycle += $query10_row['sem3_laboratorials_hours'];
			$Sum3PrcCycle += $query10_row['sem3_practicals_hours']; 
			$Sum3IndCycle += $query10_row['sem3_individual_work_hours'];
			$Sum4LecCycle += $query10_row['sem4_lectural_hours']; 
			$Sum4LabCycle += $query10_row['sem4_laboratorials_hours'];
			$Sum4PrcCycle += $query10_row['sem4_practicals_hours']; 
			$Sum4IndCycle += $query10_row['sem4_individual_work_hours'];
			$Sum5LecCycle += $query10_row['sem5_lectural_hours']; 
			$Sum5LabCycle += $query10_row['sem5_laboratorials_hours'];
			$Sum5PrcCycle += $query10_row['sem5_practicals_hours']; 
			$Sum5IndCycle += $query10_row['sem5_individual_work_hours'];
			$Sum6LecCycle += $query10_row['sem6_lectural_hours']; 
			$Sum6LabCycle += $query10_row['sem6_laboratorials_hours'];
			$Sum6PrcCycle += $query10_row['sem6_practicals_hours']; 
			$Sum6IndCycle += $query10_row['sem6_individual_work_hours'];
			$Sum7LecCycle += $query10_row['sem7_lectural_hours']; 
			$Sum7LabCycle += $query10_row['sem7_laboratorials_hours'];
			$Sum7PrcCycle += $query10_row['sem7_practicals_hours']; 
			$Sum7IndCycle += $query10_row['sem7_individual_work_hours'];
			$Sum8LecCycle += $query10_row['sem8_lectural_hours']; 
			$Sum8LabCycle += $query10_row['sem8_laboratorials_hours'];
			$Sum8PrcCycle += $query10_row['sem8_practicals_hours']; 
			$Sum8IndCycle += $query10_row['sem8_individual_work_hours'];
		}
		if ($CyclesArray[$CyclesRowsCount] != $CyclesArray[$CyclesRowsCount - 1]) { 
			if (!empty($_POST['chkSubjSums']) and ($CyclesCount > 0) and ($SubjCnt > 1)) 
				require "cycles_sum_put.php";
			$CyclesCount++; ?>
		<tr><td colspan=66><p id="CycleHeader<? echo $CyclesCount; ?>" 
				style="margin-top: 0px; margin-bottom: 0px;"><span style="text-transform: uppercase;">
				Цикл <?php echo $CyclesCount." - ".CycleNameById($query10_row['subj_cycle_id']); ?></span> 
				: кредитів ЄКТС &mdash; CycleCreditsAbs (CycleCreditsPerc% від обсягу <?php 
			switch ($NormVarEng) { case "Norm": ?>нормативної<?php break; 
																case "Var": ?>вибіркової<?php break; } ?> 
				частини)</p></td></tr>
<?php			
		} 
	}
?>
	<tr><td rowspan=2><?php echo $query10_row['id']; ?><br>
			<span style="font-weight: bold;"><?php echo $query10_row['free_block']; ?></span>
<?php	
		$_POST['chk'.$NormVarEng.'Subj'.$query10_row['id']] = 
			isset($_POST['chk'.$NormVarEng.'Subj'.$query10_row['id']]) ? 
				$_POST['chk'.$NormVarEng.'Subj'.$query10_row['id']] : "";
	echo paramCheker('chk'.$NormVarEng.'Subj'.$query10_row['id'], 
								$_POST['chk'.$NormVarEng.'Subj'.$query10_row['id']], "", ""); ?></td>
		<td rowspan=2 style="text-align: left; vertical-align: middle;">
<?php 
	if ($query10_row['id'] == $SubjectToEdit[$NormVarEng]) { ?>
			<input style="width: 400px;" list="lstSubjDepartCodeSubjId" 
				name="txtSubject<?php echo $query10_row['id']; ?>"
				value="<?php echo ($query10_row['subject_id'] > 0) ? 
											SubjectDepartCodeSubjId($query10_row['subject_id']) : " "; ?>" ><?php
	} else {
			echo ($query10_row['subject_id'] > 0) ?         
					SubjectDepartCodeById($query10_row['subject_id']) : " "; 
	}?>	</td>
<?php
	if (!empty($_POST['chkSubjSums'])) { 
		$sum_lec_subj = 0; $sum_lab_subj = 0; $sum_prc_subj = 0; $sum_ind_subj = 0;
		for ($isem = 1; $isem <=8; $isem++) {
			$sum_lec_subj += $query10_row['sem'.$isem.'_lectural_hours']; 
			$sum_lab_subj += $query10_row['sem'.$isem.'_laboratorials_hours']; 
			$sum_prc_subj += $query10_row['sem'.$isem.'_practicals_hours']; 
			$sum_ind_subj += $query10_row['sem'.$isem.'_individual_work_hours'];
		}
		$hours_subj = $sum_lec_subj + $sum_lab_subj + $sum_prc_subj + $sum_ind_subj;
		if ((mb_strpos(SubjectDepartCodeById($query10_row['subject_id']),"Фізична культура")!==false)
				or (mb_strpos(SubjectDepartCodeById($query10_row['subject_id']),"Фізичне виховання")!==false)) 
				{ $hours_subj = 0; $sum_lec_subj = 0; $sum_lab_subj = 0; $sum_prc_subj = 0; $sum_ind_subj = 0; }
		if (!((mb_strpos(SubjectDepartCodeById($query10_row['subject_id']),"Фізична культура")!==false)
				or (mb_strpos(SubjectDepartCodeById($query10_row['subject_id']),"Фізичне виховання")!==false))
				and ((mb_convert_encoding($query10_row['free_block'], "UTF-8") == "а") 
				or empty($query10_row['free_block']))
				) {
			$Total1Lec += $query10_row['sem1_lectural_hours']; 
			$Total1Lab += $query10_row['sem1_laboratorials_hours'];
			$Total1Prc += $query10_row['sem1_practicals_hours']; 
			$Total1Ind += $query10_row['sem1_individual_work_hours'];
			$Total2Lec += $query10_row['sem2_lectural_hours']; 
			$Total2Lab += $query10_row['sem2_laboratorials_hours'];
			$Total2Prc += $query10_row['sem2_practicals_hours']; 
			$Total2Ind += $query10_row['sem2_individual_work_hours'];
			$Total3Lec += $query10_row['sem3_lectural_hours']; 
			$Total3Lab += $query10_row['sem3_laboratorials_hours'];
			$Total3Prc += $query10_row['sem3_practicals_hours']; 
			$Total3Ind += $query10_row['sem3_individual_work_hours'];
			$Total4Lec += $query10_row['sem4_lectural_hours']; 
			$Total4Lab += $query10_row['sem4_laboratorials_hours'];
			$Total4Prc += $query10_row['sem4_practicals_hours']; 
			$Total4Ind += $query10_row['sem4_individual_work_hours'];
			$Total5Lec += $query10_row['sem5_lectural_hours']; 
			$Total5Lab += $query10_row['sem5_laboratorials_hours'];
			$Total5Prc += $query10_row['sem5_practicals_hours']; 
			$Total5Ind += $query10_row['sem5_individual_work_hours'];
			$Total6Lec += $query10_row['sem6_lectural_hours']; 
			$Total6Lab += $query10_row['sem6_laboratorials_hours'];
			$Total6Prc += $query10_row['sem6_practicals_hours']; 
			$Total6Ind += $query10_row['sem6_individual_work_hours'];
			$Total7Lec += $query10_row['sem7_lectural_hours']; 
			$Total7Lab += $query10_row['sem7_laboratorials_hours'];
			$Total7Prc += $query10_row['sem7_practicals_hours']; 
			$Total7Ind += $query10_row['sem7_individual_work_hours'];
			$Total8Lec += $query10_row['sem8_lectural_hours']; 
			$Total8Lab += $query10_row['sem8_laboratorials_hours'];
			$Total8Prc += $query10_row['sem8_practicals_hours']; 
			$Total8Ind += $query10_row['sem8_individual_work_hours'];
			if ($hours_subj > 0) {
				$Exam1Sem += ((mb_convert_encoding($query10_row['sem1_sem_test'], "UTF-8") === "екз.") ? 1 : 0);
				$Test1Sem += ((mb_convert_encoding($query10_row['sem1_sem_test'], "UTF-8") === "зал.") ? 1 : 0);
				$Papr1Sem += ((mb_convert_encoding($query10_row['sem1_acad_year_paper'], "UTF-8") == "") ? 0 : 1);
				$Home1Sem += $query10_row['sem1_home_tasks'];
				$Exam2Sem += ((mb_convert_encoding($query10_row['sem2_sem_test'], "UTF-8") === "екз.") ? 1 : 0);
				$Test2Sem += ((mb_convert_encoding($query10_row['sem2_sem_test'], "UTF-8") === "зал.") ? 1 : 0);
				$Papr2Sem += ((mb_convert_encoding($query10_row['sem2_acad_year_paper'], "UTF-8") == "") ? 0 : 1);
				$Home2Sem += $query10_row['sem2_home_tasks'];
				$Exam3Sem += ((mb_convert_encoding($query10_row['sem3_sem_test'], "UTF-8") === "екз.") ? 1 : 0);
				$Test3Sem += ((mb_convert_encoding($query10_row['sem3_sem_test'], "UTF-8") === "зал.") ? 1 : 0);
				$Papr3Sem += ((mb_convert_encoding($query10_row['sem3_acad_year_paper'], "UTF-8") =="") ? 0 : 1);
				$Home3Sem += $query10_row['sem3_home_tasks'];
				$Exam4Sem += ((mb_convert_encoding($query10_row['sem4_sem_test'], "UTF-8") === "екз.") ? 1 : 0);
				$Test4Sem += ((mb_convert_encoding($query10_row['sem4_sem_test'], "UTF-8") === "зал.") ? 1 : 0);
				$Papr4Sem += ((mb_convert_encoding($query10_row['sem4_acad_year_paper'], "UTF-8") == "") ? 0 : 1);
				$Home4Sem += $query10_row['sem4_home_tasks'];
				$Exam5Sem += ((mb_convert_encoding($query10_row['sem5_sem_test'], "UTF-8") === "екз.") ? 1 : 0);
				$Test5Sem += ((mb_convert_encoding($query10_row['sem5_sem_test'], "UTF-8") === "зал.") ? 1 : 0);
				$Papr5Sem += ((mb_convert_encoding($query10_row['sem5_acad_year_paper'], "UTF-8") == "") ? 0 : 1);
				$Home5Sem += $query10_row['sem5_home_tasks'];
				$Exam6Sem += ((mb_convert_encoding($query10_row['sem6_sem_test'], "UTF-8") === "екз.") ? 1 : 0);
				$Test6Sem += ((mb_convert_encoding($query10_row['sem6_sem_test'], "UTF-8") === "зал.") ? 1 : 0);
				$Papr6Sem += ((mb_convert_encoding($query10_row['sem6_acad_year_paper'], "UTF-8") == "") ? 0 : 1);
				$Home6Sem += $query10_row['sem6_home_tasks'];
				$Exam7Sem += ((mb_convert_encoding($query10_row['sem7_sem_test'], "UTF-8") === "екз.") ? 1 : 0);
				$Test7Sem += ((mb_convert_encoding($query10_row['sem7_sem_test'], "UTF-8") === "зал.") ? 1 : 0);
				$Papr7Sem += ((mb_convert_encoding($query10_row['sem7_acad_year_paper'], "UTF-8") == "") ? 0 : 1);
				$Home7Sem += $query10_row['sem7_home_tasks'];
				$Exam8Sem += ((mb_convert_encoding($query10_row['sem8_sem_test'], "UTF-8") === "екз.") ? 1 : 0);
				$Test8Sem += ((mb_convert_encoding($query10_row['sem8_sem_test'], "UTF-8") === "зал.") ? 1 : 0);
				$Papr8Sem += ((mb_convert_encoding($query10_row['sem8_acad_year_paper'], "UTF-8") == "") ? 0 : 1);
				$Home8Sem += $query10_row['sem8_home_tasks'];
			}
		}
		$credits_subj = round($hours_subj / $HoursPerCredit, 1);
		$CycleCreditsAbs[$NormVarEng][$CyclesCount] += $credits_subj;
		if ((mb_convert_encoding($query10_row['free_block'], "UTF-8") == "а") or empty($query10_row['free_block'])) {
			$TotalCredits += $credits_subj; $TotalHours += $hours_subj; 
			if ($NormVarEng == "Norm") $NormCredAbs += $credits_subj;
			if ($NormVarEng == "Var") $VarCredAbs += $credits_subj;
				$TotalLec += $sum_lec_subj; $TotalLab += $sum_lab_subj; 
				$TotalPrc += $sum_prc_subj; $TotalInd += $sum_ind_subj;
		}
?>
			<td rowspan=2 style="font-size: 120%; font-weight: bold;
													 vertical-align: middle;"><?php echo $credits_subj; ?></td>
			<td rowspan=2 style="font-size: 120%; font-weight: bold;
													 vertical-align: middle;"><?php echo $hours_subj; ?></td>
			<td rowspan=2 style="font-size: 120%; font-weight: bold;
													 vertical-align: middle;"><?php echo $sum_lec_subj; ?></td>
			<td rowspan=2 style="font-size: 120%; font-weight: bold;
													 vertical-align: middle;"><?php echo $sum_lab_subj; ?></td>
			<td rowspan=2 style="font-size: 120%; font-weight: bold;
													 vertical-align: middle;"><?php echo $sum_prc_subj; ?></td>
			<td rowspan=2 style="font-size: 120%; font-weight: bold;
													 vertical-align: middle;"><?php echo $sum_ind_subj; ?></td><?php
	}
	for ($isem = 1; $isem<=8; $isem++) { ?>
			<td style="border-left: 2px solid blue;"><?php 
		if ($query10_row['id'] == $SubjectToEdit[$NormVarEng]) {
?>			<input style="margin-left: 0px; margin-right: 0px; 
						font-size: 100%; width: 25px; border: 0px;" 
				type="text" name="sem<?php echo $isem."lech".$query10_row['id']; ?>"
            value="<?php echo $query10_row['sem'.$isem.'_lectural_hours']; ?>" ><?php
		} else echo ($query10_row['sem'.$isem.'_lectural_hours'] > 0) ? 
								$query10_row['sem'.$isem.'_lectural_hours'] : ""; 
?>			</td>
			<td><?php 
		if ($query10_row['id'] == $SubjectToEdit[$NormVarEng]) { ?>
				<input style="margin-left: 0px; margin-right: 0px; 
						font-size: 100%; width: 25px; border: 0px;" 
				type="text" name="sem<?php echo $isem."labh".$query10_row['id']; ?>"
            value="<?php echo $query10_row['sem'.$isem.'_laboratorials_hours']; ?>" ><?php
		} else echo ($query10_row['sem'.$isem.'_laboratorials_hours'] > 0) ? 
								$query10_row['sem'.$isem.'_laboratorials_hours'] : ""; 
?>		</td>
			<td><?php 
		if ($query10_row['id'] == $SubjectToEdit[$NormVarEng]) { ?>
				<input style="margin-left: 0px; margin-right: 0px; 
						font-size: 100%; width: 25px; border: 0px;" 
				type="text" name="sem<?php echo $isem."prh".$query10_row['id']; ?>"
            value="<?php echo $query10_row['sem'.$isem.'_practicals_hours']; ?>" ><?php
		} else echo ($query10_row['sem'.$isem.'_practicals_hours']) ? 
								$query10_row['sem'.$isem.'_practicals_hours'] : ""; 
?>		</td>
			<td style="border-right: 2px solid blue;"><?php 
		if ($query10_row['id'] == $SubjectToEdit[$NormVarEng]) { ?>
			<input style="margin-left: 0px; margin-right: 0px; 
						font-size: 100%; width: 25px; border: 0px;" 
				type="text" name="sem<?php echo $isem."indh".$query10_row['id']; ?>"
            value="<?php echo $query10_row['sem'.$isem.'_individual_work_hours']; ?>" ><?php
		} else echo ($query10_row['sem'.$isem.'_individual_work_hours'] > 0) ? 
								$query10_row['sem'.$isem.'_individual_work_hours'] : ""; 
?>			</td><?php
	} ?>
	</tr>
	<tr><?php
	for ($isem = 1; $isem<=8; $isem++) {  
?>
			<td colspan=2 style="border-left: 2px solid blue;"><?php 
		if ($query10_row['id'] == $SubjectToEdit[$NormVarEng]) { ?>
				<input style="margin-left: 0px; margin-right: 0px; 
						font-size: 100%; width: 35px; border: 0px;" 
				type="text" pattern="(|екз.|зал.|д.з.)"
				name="sem<?php echo $isem."semt".$query10_row['id']; ?>"
            value="<?php echo $query10_row['sem'.$isem.'_sem_test']; ?>" ><?php
		} else echo $query10_row['sem'.$isem.'_sem_test']; ?>
			</td>
			<td><?php 
		if ($query10_row['id'] == $SubjectToEdit[$NormVarEng]) { ?>
				<input style="margin-left: 0px; margin-right: 0px; 
						font-size: 100%; width: 35px; border: 0px;" 
				type="text" pattern="(|КП|КР)"
				name="sem<?php echo $isem."aypap".$query10_row['id']; ?>"
            value="<?php echo $query10_row['sem'.$isem.'_acad_year_paper']; ?>" ><?php
		} else echo $query10_row['sem'.$isem.'_acad_year_paper']; ?>
			</td>
			<td style="border-right: 2px solid blue;"><?php 
		if ($query10_row['id'] == $SubjectToEdit[$NormVarEng]) { ?>
				<input style="margin-left: 0px; margin-right: 0px; 
						font-size: 100%; width: 25px; border: 0px;" 
				type="text" 
				name="sem<?php echo $isem."homet".$query10_row['id']; ?>"
            value="<?php echo $query10_row['sem'.$isem.'_home_tasks']; ?>" ><?php
		} else echo ($query10_row['sem'.$isem.'_home_tasks'] > 0) ?
								$query10_row['sem'.$isem.'_home_tasks'] : ""; ?>
			</td><?php
	} ?>
	</tr><?php 
	if ($query10_row['id'] == $SubjectToEdit[$NormVarEng]) { ?>
			<tr><td colspan=66 style="text-align: center;">
					<input style="font-weight: bold; color: blue;" type="submit" 
							name="sbtSaveSubj<?php echo $query10_row['id']; ?>" 
							value="Зберегти дисципліну з кодом <?php echo $query10_row['id']; ?>">
				</td>
			</tr><?php		
	}
}
?>
