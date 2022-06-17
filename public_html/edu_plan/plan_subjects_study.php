<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
	                              "Помилка входу в модуль plan_work_study.php</p>"; 
									 require "footer.php"; exit(); }

$SubjectIdChecked = Array(); $SubjectIdChecked['Norm'] = 0; $SubjectIdChecked['Var'] = 0;
$SubjectToEdit = Array(); $SubjectToEdit['Norm'] = 0; $SubjectToEdit['Var'] = 0;
$NormCycleCreditsAbs = Array(); for ($ic = 1; $ic < 33; $ic++) $NormCycleCreditsAbs[$ic] = 0;
$VarCycleCreditsAbs = Array(); for ($ic = 1; $ic < 33; $ic++) $VarCycleCreditsAbs[$ic] = 0;
$CycleCreditsAbs = Array('Norm'=>$NormCycleCreditsAbs, 'Var'=>$VarCycleCreditsAbs);
// print_r($SubjectToEdit); print_r($SubjectIdChecked);
$NormVarUkr = "Нормат"; $NormVarEng = "Norm"; require "./edu_plan/subjects_study_processing.php";
$NormVarUkr = "Вибірк"; $NormVarEng = "Var"; require "./edu_plan/subjects_study_processing.php";
$CyclesRowsCount = 0; $CyclesCount = 0; $CyclesArray = Array();	$CyclesArray[0] = 0;
?>
<p style="text-align: center; text-transform: uppercase; 
					font-size: 125%; font-weight: bold; margin-bottom: 0px;">2. План вивчення дисциплін</p>
<table style="margin-top: 0px; margin-left: 0%; width: 100%;">
<thead>
	<tr>
		<th rowspan=5>Код<br>пози-<br>ції</th><th rowspan=5>Назва дисципліни<br> і кафедра, що її забезпечує</th>
<?php
$_POST['chkSubjSums'] = (isset($_POST['chkSubjSums'])) ? $_POST['chkSubjSums'] : "";
if (!empty($_POST['chkSubjSums'])) { ?><th colspan=6>Сумарна кількість</th><?php  
	$TotalCredits = 0; $TotalHours = 0; $TotalLec = 0; $TotalLab = 0; $TotalPrc = 0; $TotalInd = 0;
	$Total1Lec = 0; $Total1Lab = 0; $Total1Prc = 0; $Total1Ind = 0;
	$Total2Lec = 0; $Total2Lab = 0; $Total2Prc = 0; $Total2Ind = 0;
	$Total3Lec = 0; $Total3Lab = 0; $Total3Prc = 0; $Total3Ind = 0;
	$Total4Lec = 0; $Total4Lab = 0; $Total4Prc = 0; $Total4Ind = 0;
	$Total5Lec = 0; $Total5Lab = 0; $Total5Prc = 0; $Total5Ind = 0;
	$Total6Lec = 0; $Total6Lab = 0; $Total6Prc = 0; $Total6Ind = 0;
	$Total7Lec = 0; $Total7Lab = 0; $Total7Prc = 0; $Total7Ind = 0;
	$Total8Lec = 0; $Total8Lab = 0; $Total8Prc = 0; $Total8Ind = 0;
	$Exam1Sem = 0; $Test1Sem = 0; $Papr1Sem = 0; $Home1Sem = 0;
	$Exam2Sem = 0; $Test2Sem = 0; $Papr2Sem = 0; $Home2Sem = 0;
	$Exam3Sem = 0; $Test3Sem = 0; $Papr3Sem = 0; $Home3Sem = 0;
	$Exam4Sem = 0; $Test4Sem = 0; $Papr4Sem = 0; $Home4Sem = 0;
	$Exam5Sem = 0; $Test5Sem = 0; $Papr5Sem = 0; $Home5Sem = 0;
	$Exam6Sem = 0; $Test6Sem = 0; $Papr6Sem = 0; $Home6Sem = 0;
	$Exam7Sem = 0; $Test7Sem = 0; $Papr7Sem = 0; $Home7Sem = 0;
	$Exam8Sem = 0; $Test8Sem = 0; $Papr8Sem = 0; $Home8Sem = 0;
	$Sum1LecCycle = 0; $Sum1LabCycle = 0; $Sum1PrcCycle = 0; $Sum1IndCycle = 0; 
	$Sum2LecCycle = 0; $Sum2LabCycle = 0; $Sum2PrcCycle = 0; $Sum2IndCycle = 0; 
	$Sum3LecCycle = 0; $Sum3LabCycle = 0; $Sum3PrcCycle = 0; $Sum3IndCycle = 0; 
	$Sum4LecCycle = 0; $Sum4LabCycle = 0; $Sum4PrcCycle = 0; $Sum4IndCycle = 0; 
	$Sum5LecCycle = 0; $Sum5LabCycle = 0; $Sum5PrcCycle = 0; $Sum5IndCycle = 0; 
	$Sum6LecCycle = 0; $Sum6LabCycle = 0; $Sum6PrcCycle = 0; $Sum6IndCycle = 0; 
	$Sum7LecCycle = 0; $Sum7LabCycle = 0; $Sum7PrcCycle = 0; $Sum7IndCycle = 0; 
	$Sum8LecCycle = 0; $Sum8LabCycle = 0; $Sum8PrcCycle = 0; $Sum8IndCycle = 0;
} ?>
		<th colspan=64>Розподіл годин за семестрами і видами занять</th>
	</tr>
	<tr>
<?php
if (!empty($_POST['chkSubjSums'])) { ?><th rowspan=4>креди-<br>тів<br>ЄКТС</th><th rowspan=4>годин</th>
																			 <th colspan=4>у т.ч.</th><?php }
		for ($iс = 1; $iс<=4; $iс++) { ?><th colspan=8><?php echo $iс;?>-й курс</th><?php } ?>
	</tr>
	<tr>
<?php
if (!empty($_POST['chkSubjSums'])) { ?><th rowspan=3>лек.</th><th rowspan=3>лаб.</th>
												 <th rowspan=3>пр.</th><th rowspan=3>сам.</th><?php } 
      for ($isem = 1; $isem<=8; $isem++) { ?>
			<th colspan=4 style="font-size: 80%"><?php echo $isem;?>-й сем. <?php 
        if (EduFormById($query_row['edu_form_id']) == "Д") {
					echo ($AudWeeksCount[$isem] > 0) ? $AudWeeksCount[$isem] : 0; ?> ауд.тиж.<?php } ?>
			</th>
		<?php } ?>
	</tr>
	<tr>
		<?php for ($isem = 1; $isem<=8; $isem++) { ?>
			<th style="font-size: 80%">лек.</th><th style="font-size: 80%">лаб.</th>
			<th style="font-size: 80%">пр.</th><th style="font-size: 80%">сам.</th>
		<?php } ?>
	</tr>
	<tr>
		<?php for ($isem = 1; $isem<=8; $isem++) { ?>
			<th colspan=2 style="font-size: 80%">контроль</th><th style="font-size: 80%">к.п.</th>
			<th style="font-size: 80%">дом.з.</th>
		<?php } ?>
	</tr>
</thead>
<tbody>
	<tr><td colspan=66><p id="NormPartHeader" style="text-align: center; font-size: 120%; 
				margin-top: 8px; margin-bottom: 3px; font-weight: bold;">
				<span style="text-transform: uppercase;">Нормативна частина</span><?php 
		if (!empty($_POST['chkSubjSums'])) { ?>
				: кредитів ЄКТС &mdash; NormCredAbs (NormCredPerc% від загального обсягу)<?php
		} ?></p></td>
	</tr>
<?php $NormVarUkr = "Нормат"; $NormVarEng = "Norm"; $SubjCnt=0; $NormCredAbs = 0;
		require "./edu_plan/subjects_study_put.php"; 
		if (!empty($_POST['chkSubjSums']) and ($SubjCnt > 0)) require "./edu_plan/cycles_sum_put.php";
	  if (($mode == "EDIT") and (empty($_POST['chkSubjSums'])) 
				and ($_SESSION['chkProxySign'] == 0)) {
?>
	<tr><td colspan=66 style="text-align: right;">Дисциплін у нормативній частині: 
			<span style="font-weight: bold;"><? echo $SubjCnt; ?></span> &nbsp; &nbsp;
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtAddNormSubj" 
				value="Додати дисципліну до плану"><? echo str_repeat(" &nbsp;",2);?>
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtEditNormSubj" 
				value="Редагувати позначену"><? echo str_repeat(" &nbsp;",2);?>
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtAddNormSubjToCycle" 
				value="Додати позначені до циклу"><? echo str_repeat(" &nbsp;",2);?>
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtDeleteNormSubjFromCycle" 
				value="Видалити позначені з циклу"><? echo str_repeat(" &nbsp;",2);?>
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtMoveSubjToVar" 
				value='Перемістити позначені у "Вибіркові"'><? echo str_repeat(" &nbsp;",6);?>
			<input style="font-weight: bold; color: red;" type="submit" name="sbtDeleteNormSubj" 
				onclick="if (confirm('Дійсно видалити позначені дисципліни?')) submit();"
				value="Видалити позначені з плану">
<?php
			if (isset($_POST['sbtAddNormSubjToCycle'])) { 
				$CyclesQuery = "SELECT * FROM catalogSubjectCycle ORDER BY cycle_rank";
				echo selectCommonSelect("<br>Виберіть цикл, до якого слід додати позначені дисципліни: ", 
							"selCyclesNormSubj", $conn, $CyclesQuery, "id", "", "cycle_name", ""); ?>
				<input style="font-weight: bold; color: green;" type="submit" name="sbtSaveNormSubjToCycle" 
					value="Зберегти"><? echo str_repeat(" &nbsp;",25);
			} 
?>
		</td>	
  	</tr>
<?php
		} 
?>		
	<tr><td colspan=66><p id="VarPartHeader" style="text-align: center; font-size: 120%; 
				margin-top: 8px; margin-bottom: 3px; font-weight: bold;">
				<span style="text-transform: uppercase;">Вибіркова частина</span><?php 
		if (!empty($_POST['chkSubjSums'])) { ?>
				: кредитів ЄКТС &mdash; VarCredAbs (VarCredPerc% від загального обсягу)<?php
		} ?></p></td>
	</tr>
<?php $NormVarUkr = "Вибірк"; $NormVarEng = "Var"; $SubjCnt = 0; $VarCredAbs = 0;
		require "./edu_plan/subjects_study_put.php"; 
		if (!empty($_POST['chkSubjSums']) and ($SubjCnt > 0)) require "./edu_plan/cycles_sum_put.php";
		if (($mode == "EDIT") and (empty($_POST['chkSubjSums'])) 
				and ($_SESSION['chkProxySign'] == 0)) {
?>
	<tr><td colspan=66 style="text-align: right;">Дисциплін у вибірковій частині: 
			<span style="font-weight: bold;"><? echo $SubjCnt; ?></span> &nbsp; &nbsp;
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtAddVarSubj" 
				value="Додати дисципліну до плану"><? echo str_repeat(" &nbsp;",2);?>
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtEditVarSubj" 
				value="Редагувати позначену"><? echo str_repeat(" &nbsp;",2);?>
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtAddVarSubjToCycle" 
				value="Додати позначені до циклу"><? echo str_repeat(" &nbsp;",2);?>
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtDeleteVarSubjFromCycle" 
				value="Видалити позначені з циклу"><? echo str_repeat(" &nbsp;",2);?>
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtMoveSubjToNorm" 
				value='Перемістити позначені в "Нормативні"'><? echo str_repeat(" &nbsp;",6);?>
			<input style="font-weight: bold; color: red;" type="submit" name="sbtDeleteVarSubj" 
				onclick="if (confirm('Дійсно видалити позначені дисципліни?')) submit();"
				value="Видалити позначені з плану">
<?php
			if (isset($_POST['sbtAddVarSubjToCycle'])) { 
				$CyclesQuery = "SELECT * FROM catalogSubjectCycle ORDER BY cycle_name";
				echo selectCommonSelect("<br>Виберіть цикл, до якого слід додати позначені дисципліни: ", 
							"selCyclesVarSubj", $conn, $CyclesQuery, "id", "", "cycle_name", ""); 
				echo str_repeat("&nbsp;",5);
?>				Введіть блок: 
				<input style="font-weight: bold; width: 60px;" type="text" name="txtBlockVarSubj" 
						pattern=" |а|б|в|г|д" placeholder="а,б,в,г,д">
				<input style="font-weight: bold; color: green;" type="submit" name="sbtSaveVarSubjToCycle" 
					value="Зберегти"><? echo str_repeat(" &nbsp;",30);
			} 
?>		</td>	
  	</tr>
<?php	} ?>
</tbody><?php
if (!empty($_POST['chkSubjSums'])) { 
	$Total1Sem = $Total1Lec + $Total1Lab + $Total1Prc + $Total1Ind;
	$Total2Sem = $Total2Lec + $Total2Lab + $Total2Prc + $Total2Ind;
	$Total3Sem = $Total3Lec + $Total3Lab + $Total3Prc + $Total3Ind;
	$Total4Sem = $Total4Lec + $Total4Lab + $Total4Prc + $Total4Ind;
	$Total5Sem = $Total5Lec + $Total5Lab + $Total5Prc + $Total5Ind;
	$Total6Sem = $Total6Lec + $Total6Lab + $Total6Prc + $Total6Ind;
	$Total7Sem = $Total7Lec + $Total7Lab + $Total7Prc + $Total7Ind;
	$Total8Sem = $Total8Lec + $Total8Lab + $Total8Prc + $Total8Ind;
	$Au1Sem = $Total1Lec + $Total1Lab + $Total1Prc;
	$Au2Sem = $Total2Lec + $Total2Lab + $Total2Prc;
	$Au3Sem = $Total3Lec + $Total3Lab + $Total3Prc;
	$Au4Sem = $Total4Lec + $Total4Lab + $Total4Prc;
	$Au5Sem = $Total5Lec + $Total5Lab + $Total5Prc;
	$Au6Sem = $Total6Lec + $Total6Lab + $Total6Prc;
	$Au7Sem = $Total7Lec + $Total7Lab + $Total7Prc;
	$Au8Sem = $Total8Lec + $Total8Lab + $Total8Prc;
	$Week1Sem = ($StudyWeeksCount[1] > 0) ? round($Total1Sem / $StudyWeeksCount[1], 0) : 0;
	$Week2Sem = ($StudyWeeksCount[2] > 0) ? round($Total2Sem / $StudyWeeksCount[2], 0) : 0;
	$Week3Sem = ($StudyWeeksCount[3] > 0) ? round($Total3Sem / $StudyWeeksCount[3], 0) : 0;
	$Week4Sem = ($StudyWeeksCount[4] > 0) ? round($Total4Sem / $StudyWeeksCount[4], 0) : 0;
	$Week5Sem = ($StudyWeeksCount[5] > 0) ? round($Total5Sem / $StudyWeeksCount[5], 0) : 0;
	$Week6Sem = ($StudyWeeksCount[6] > 0) ? round($Total6Sem / $StudyWeeksCount[6], 0) : 0;
	$Week7Sem = ($StudyWeeksCount[7] > 0) ? round($Total7Sem / $StudyWeeksCount[7], 0) : 0;
	$Week8Sem = ($StudyWeeksCount[8] > 0) ? round($Total8Sem / $StudyWeeksCount[8], 0) : 0;
	$WeekAu1Sem = ($AudWeeksCount[1] > 0) ? round($Au1Sem / $AudWeeksCount[1], 0) : 0; 
	$WeekAu2Sem = ($AudWeeksCount[2] > 0) ? round($Au2Sem / $AudWeeksCount[2], 0) : 0; 
	$WeekAu3Sem = ($AudWeeksCount[3] > 0) ? round($Au3Sem / $AudWeeksCount[3], 0) : 0;  
	$WeekAu4Sem = ($AudWeeksCount[4] > 0) ? round($Au4Sem / $AudWeeksCount[4], 0) : 0; 
	$WeekAu5Sem = ($AudWeeksCount[5] > 0) ? round($Au5Sem / $AudWeeksCount[5], 0) : 0; 
	$WeekAu6Sem = ($AudWeeksCount[6] > 0) ? round($Au6Sem / $AudWeeksCount[6], 0) : 0; 
	$WeekAu7Sem = ($AudWeeksCount[7] > 0) ? round($Au7Sem / $AudWeeksCount[7], 0) : 0; 
	$WeekAu8Sem = ($AudWeeksCount[8] > 0) ? round($Au8Sem / $AudWeeksCount[8], 0) : 0; 
?>
<tfoot>
	<tr>  <th colspan=2 style="font-size: 125%;">Усього в плані</th>
			<th style="font-size: 125%;"><?php echo $TotalCredits; ?></th>
			<th style="font-size: 125%;"><?php echo $TotalHours; ?></th>
			<th style="font-size: 125%;"><?php echo $TotalLec; ?></th>
			<th style="font-size: 125%;"><?php echo $TotalLab; ?></th>
			<th style="font-size: 125%;"><?php echo $TotalPrc; ?></th>
			<th style="font-size: 125%;"><?php echo $TotalInd; ?></th>
			<th><?php echo $Total1Lec; ?></th><th><?php echo $Total1Lab; ?></th>
			<th><?php echo $Total1Prc; ?></th><th><?php echo $Total1Ind; ?></th>
			<th><?php echo $Total2Lec; ?></th><th><?php echo $Total2Lab; ?></th>
			<th><?php echo $Total2Prc; ?></th><th><?php echo $Total2Ind; ?></th>
			<th><?php echo $Total3Lec; ?></th><th><?php echo $Total3Lab; ?></th>
			<th><?php echo $Total3Prc; ?></th><th><?php echo $Total3Ind; ?></th>
			<th><?php echo $Total4Lec; ?></th><th><?php echo $Total4Lab; ?></th>
			<th><?php echo $Total4Prc; ?></th><th><?php echo $Total4Ind; ?></th>
			<th><?php echo $Total5Lec; ?></th><th><?php echo $Total5Lab; ?></th>
			<th><?php echo $Total5Prc; ?></th><th><?php echo $Total5Ind; ?></th>
			<th><?php echo $Total6Lec; ?></th><th><?php echo $Total6Lab; ?></th>
			<th><?php echo $Total6Prc; ?></th><th><?php echo $Total6Ind; ?></th>
			<th><?php echo $Total7Lec; ?></th><th><?php echo $Total7Lab; ?></th>
			<th><?php echo $Total7Prc; ?></th><th><?php echo $Total7Ind; ?></th>
			<th><?php echo $Total8Lec; ?></th><th><?php echo $Total8Lab; ?></th>
			<th><?php echo $Total8Prc; ?></th><th><?php echo $Total8Ind; ?></th>
	</tr>
	<tr><td colspan=8></td><?php
      for ($isem = 1; $isem<=8; $isem++) { ?>
			<th colspan=4 style="font-size: 80%">
					<?php echo $isem;?>-й сем. <?php 
        if (EduFormById($query_row['edu_form_id']) == "Д") {
					echo ($StudyWeeksCount[$isem] > 0) ? $StudyWeeksCount[$isem] : 0; ?> тиж.<?php } ?>
			</th>
		<?php } ?>
	</tr>
	<tr><td colspan=8 style="text-align: right;">Загальне навчальне навантаження, год. &nbsp;</td>
			<td colspan=4><?php echo $Total1Sem; ?></td><td colspan=4><?php echo $Total2Sem; ?></td>
			<td colspan=4><?php echo $Total3Sem; ?></td><td colspan=4><?php echo $Total4Sem; ?></td>
			<td colspan=4><?php echo $Total5Sem; ?></td><td colspan=4><?php echo $Total6Sem; ?></td>
			<td colspan=4><?php echo $Total7Sem; ?></td><td colspan=4><?php echo $Total8Sem; ?></td>
	</tr>
<?php 
	if (EduFormById($query_row['edu_form_id']) == "З") { ?>
	<tr><td colspan=8 style="text-align: right;">Загальне аудиторне навантаження, год. &nbsp;</td>
			<td colspan=4><?php echo $Au1Sem; ?></td><td colspan=4><?php echo $Au2Sem; ?></td>
			<td colspan=4><?php echo $Au3Sem; ?></td><td colspan=4><?php echo $Au4Sem; ?></td>
			<td colspan=4><?php echo $Au5Sem; ?></td><td colspan=4><?php echo $Au6Sem; ?></td>
			<td colspan=4><?php echo $Au7Sem; ?></td><td colspan=4><?php echo $Au8Sem; ?></td>
	</tr>
<?php
	}
	if (EduFormById($query_row['edu_form_id']) == "Д") { ?>
	<tr><td colspan=8 style="text-align: right;">Тижневе навчальне навантаження, год. &nbsp;</td>
			<th colspan=4><?php echo $Week1Sem; ?></th><th colspan=4><?php echo $Week2Sem; ?></th>
			<th colspan=4><?php echo $Week3Sem; ?></th><th colspan=4><?php echo $Week4Sem; ?></th>
			<th colspan=4><?php echo $Week5Sem; ?></th><th colspan=4><?php echo $Week6Sem; ?></th>
			<th colspan=4><?php echo $Week7Sem; ?></th><th colspan=4><?php echo $Week8Sem; ?></th>
	</tr>
	<tr><td colspan=8 style="text-align: right;">Тижневе аудиторне навантаження, год. &nbsp;</td>
			<td colspan=4><?php echo $WeekAu1Sem; ?></td><td colspan=4><?php echo $WeekAu2Sem; ?></td>
			<td colspan=4><?php echo $WeekAu3Sem; ?></td><td colspan=4><?php echo $WeekAu4Sem; ?></td>
			<td colspan=4><?php echo $WeekAu5Sem; ?></td><td colspan=4><?php echo $WeekAu6Sem; ?></td>
			<td colspan=4><?php echo $WeekAu7Sem; ?></td><td colspan=4><?php echo $WeekAu8Sem; ?></td>
	</tr><?php
	} ?>
	<tr><td colspan=8 style="text-align: right;">Кількість екзаменів &nbsp;</td>
			<th colspan=4><?php echo $Exam1Sem; ?></th><th colspan=4><?php echo $Exam2Sem; ?></th>
			<th colspan=4><?php echo $Exam3Sem; ?></th><th colspan=4><?php echo $Exam4Sem; ?></th>
			<th colspan=4><?php echo $Exam5Sem; ?></th><th colspan=4><?php echo $Exam6Sem; ?></th>
			<th colspan=4><?php echo $Exam7Sem; ?></th><th colspan=4><?php echo $Exam8Sem; ?></th>
	</tr>
	<tr><td colspan=8 style="text-align: right;">Кількість заліків &nbsp;</td>
			<td colspan=4><?php echo $Test1Sem; ?></td><td colspan=4><?php echo $Test2Sem; ?></td>
			<td colspan=4><?php echo $Test3Sem; ?></td><td colspan=4><?php echo $Test4Sem; ?></td>
			<td colspan=4><?php echo $Test5Sem; ?></td><td colspan=4><?php echo $Test6Sem; ?></td>
			<td colspan=4><?php echo $Test7Sem; ?></td><td colspan=4><?php echo $Test8Sem; ?></td>
	</tr>
	<tr><td colspan=8 style="text-align: right;">Кількість курсових робіт (проектів) &nbsp;</td>
			<th colspan=4><?php echo $Papr1Sem; ?></th><th colspan=4><?php echo $Papr2Sem; ?></th>
			<th colspan=4><?php echo $Papr3Sem; ?></th><th colspan=4><?php echo $Papr4Sem; ?></th>
			<th colspan=4><?php echo $Papr5Sem; ?></th><th colspan=4><?php echo $Papr6Sem; ?></th>
			<th colspan=4><?php echo $Papr7Sem; ?></th><th colspan=4><?php echo $Papr8Sem; ?></th>
	</tr>
	<tr><td colspan=8 style="text-align: right;">Кількість домашніх робіт &nbsp;</td>
			<td colspan=4><?php echo $Home1Sem; ?></td><td colspan=4><?php echo $Home2Sem; ?></td>
			<td colspan=4><?php echo $Home3Sem; ?></td><td colspan=4><?php echo $Home4Sem; ?></td>
			<td colspan=4><?php echo $Home5Sem; ?></td><td colspan=4><?php echo $Home6Sem; ?></td>
			<td colspan=4><?php echo $Home7Sem; ?></td><td colspan=4><?php echo $Home8Sem; ?></td>
	</tr>
	<tr><td colspan=8 style="text-align: right;">Кількість кредитів за семестр &nbsp;</td>
			<th colspan=4 style="font-size: 120%;"><?php $c1s = round($Total1Sem / $HoursPerCredit, 0);
																									echo $c1s; ?></th>
			<th colspan=4 style="font-size: 120%;"><?php $c2s = round($Total2Sem / $HoursPerCredit, 0);
																									echo $c2s; ?></th>
			<th colspan=4 style="font-size: 120%;"><?php $c3s = round($Total3Sem / $HoursPerCredit, 0);
																									echo $c3s; ?></th>
			<th colspan=4 style="font-size: 120%;"><?php $c4s = round($Total4Sem / $HoursPerCredit, 0);
																									echo $c4s; ?></th>
			<th colspan=4 style="font-size: 120%;"><?php $c5s = round($Total5Sem / $HoursPerCredit, 0);
																									echo $c5s; ?></th>
			<th colspan=4 style="font-size: 120%;"><?php $c6s = round($Total6Sem / $HoursPerCredit, 0);
																									echo $c6s; ?></th>
			<th colspan=4 style="font-size: 120%;"><?php $c7s = round($Total7Sem / $HoursPerCredit, 0);
																									echo $c7s; ?></th>
			<th colspan=4 style="font-size: 120%;"><?php $c8s = round($Total8Sem / $HoursPerCredit, 0);
																									echo $c8s; ?></th>
	</tr>
	<tr><td colspan=8 style="text-align: right;">Кількість кредитів за навчальний рік &nbsp;</td>
			<th colspan=8 style="font-size: 120%;"><?php echo $c1s + $c2s; ?></th>
			<th colspan=8 style="font-size: 120%;"><?php echo $c3s + $c4s;; ?></th>
			<th colspan=8 style="font-size: 120%;"><?php echo $c5s + $c6s;; ?></th>
			<th colspan=8 style="font-size: 120%;"><?php echo $c7s + $c8s;; ?></th>
	</tr>
</tfoot><?php
} ?>
</table><?php 
if (!empty($_POST['chkSubjSums'])) {
	$NormCredPerc = round($NormCredAbs / $TotalCredits * 100); $VarCredPerc = 100 - $NormCredPerc; 
	if ($CyclesCount > 0) {
	  for ($ic = 1; $ic < 33; $ic++) if ($CycleCreditsAbs['Norm'][$ic] > 0) {
				$CycleCreditsPerc = round($CycleCreditsAbs['Norm'][$ic] / $NormCredAbs * 100); ?>
<script>
var CycleHeader = document.getElementById("CycleHeader<?php echo $ic; ?>").innerHTML;
var CycleHeaderNew = CycleHeader.replace("CycleCreditsAbs", "<?php echo $CycleCreditsAbs['Norm'][$ic]; ?>");
var CycleHeaderNew = CycleHeaderNew.replace("CycleCreditsPerc", "<?php echo $CycleCreditsPerc; ?>");
document.getElementById("CycleHeader<?php echo $ic; ?>").innerHTML = CycleHeaderNew;
</script><?php
			} 
  	for ($ic = 1; $ic < 33; $ic++) if ($CycleCreditsAbs['Var'][$ic] > 0) {
				$CycleCreditsPerc = round($CycleCreditsAbs['Var'][$ic] / $VarCredAbs * 100); ?>
<script>
var CycleHeader = document.getElementById("CycleHeader<?php echo $ic; ?>").innerHTML;
var CycleHeaderNew = CycleHeader.replace("CycleCreditsAbs", "<?php echo $CycleCreditsAbs['Var'][$ic]; ?>");
var CycleHeaderNew = CycleHeaderNew.replace("CycleCreditsPerc", "<?php echo $CycleCreditsPerc; ?>");
document.getElementById("CycleHeader<?php echo $ic; ?>").innerHTML = CycleHeaderNew;
</script><?php
			}
	  for ($ic = 1; $ic <= $CyclesCount; $ic++) { ?>
<script>
var CycleHeader = document.getElementById("CycleHeader<?php echo $ic; ?>").innerHTML;
var CycleHeaderNew = CycleHeader.replace("CycleCreditsAbs", "<?php echo '0'; ?>");
var CycleHeaderNew = CycleHeaderNew.replace("CycleCreditsPerc", "<?php echo '0'; ?>");
document.getElementById("CycleHeader<?php echo $ic; ?>").innerHTML = CycleHeaderNew;
</script><?php
		}
	} ?>
<script> 
var NormPartHeader = document.getElementById("NormPartHeader").innerHTML;
var NormPartHeaderNew = NormPartHeader.replace("NormCredAbs","<?php echo $NormCredAbs; ?>");
var NormPartHeaderNew = NormPartHeaderNew.replace("NormCredPerc","<?php echo $NormCredPerc; ?>");
document.getElementById("NormPartHeader").innerHTML = NormPartHeaderNew;
var VarPartHeader = document.getElementById("VarPartHeader").innerHTML;
var VarPartHeaderNew = VarPartHeader.replace("VarCredAbs","<?php echo $VarCredAbs; ?>");
var VarPartHeaderNew = VarPartHeaderNew.replace("VarCredPerc","<?php echo $VarCredPerc; ?>");
document.getElementById("VarPartHeader").innerHTML = VarPartHeaderNew;
</script><?php
} ?>
<p style="text-align: center; margin-bottom: 0.2em; margin-top: 0.3em; font-size: 125%;
				color: green; background-color: RGB(224,224,224);"><?php
echo paramCheker('chkSubjSums', $_POST['chkSubjSums'], "Показати суми", "onchange=\"submit()\""); ?>
</p>
