<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль study_work_amount.php</p>"; 
                            require "footer.php"; exit(); }
$_POST['deptosel'] = isset($_POST['deptosel']) ? $_POST['deptosel'] : "";
$StreamsIsPresent_query = "SELECT id FROM time_amount_for_departs 
									WHERE depart_id = ".$_POST['deptosel']." AND stream_code != ''";
$StreamsIsPresent_result = mysqli_query($conn, $StreamsIsPresent_query) or 
				die("Помилка сервера при запиті<br>".$StreamsIsPresent_query." : ".mysqli_error($conn));

$_POST['chkStreamSort'] = isset($_POST['chkStreamSort']) ? 
														$_POST['chkStreamSort'] : 
														((mysqli_num_rows($StreamsIsPresent_result) > 0) ? "on" : "");
$_POST['chkStreamAuto'] = isset($_POST['chkStreamAuto']) ? $_POST['chkStreamAuto'] : "";
$_POST['stftosel'] = isset($_POST['stftosel']) ? $_POST['stftosel'] : "";
$_POST['radSemesterSelect'] = isset($_POST['radSemesterSelect']) ? $_POST['radSemesterSelect'] : "All";
$_POST['sbtSave'] = isset($_POST['sbtSave']) ? $_POST['sbtSave'] : "";

$OrderByStream = (empty($_POST['chkStreamSort'])) ? "" : ", a.stream_code";
$TimeAmount1_query = "SELECT a.*, b.naz_dus AS subject_name, c.group_next_name, 
														d.fakultet_shufr, e.reg_number, e.base_edu_degree_id
											FROM (time_amount_for_departs a, catalogSubject b, 
														catalogGroupNext c, catalogFakultet d, catalogWorkEduPlan e)
											WHERE a.depart_id = ".$_POST['deptosel']." AND a.subject_id = b.id AND 
														a.group_id = c.id AND c.faculty_id = d.id AND a.plan_id = e.id 
											ORDER BY a.edu_form_id, b.naz_dus, a.WEP_LectH".$OrderByStream;

if (!empty($_POST['chkStreamAuto'])) { // формуємо лекційні потоки
	$ClearStreams_query = "UPDATE time_amount_for_departs 
												SET stream_code = '', lectural_hours = WEP_LectH
												WHERE depart_id = ".$_POST['deptosel']." AND WEP_LectH > 0";
	$ClearStreams_result = mysqli_query($conn, $ClearStreams_query) or 
				die("Помилка сервера при запиті<br>".$ClearStreams_query." : ".mysqli_error($conn));
	// генеруємо номери потоків
	$SubjectsStreams_query = "SELECT MAX(id) AS id_bottom, COUNT(id) AS id_count,
																	edu_form_id, subject_id, sem, WEP_LectH  
														FROM time_amount_for_departs 
														WHERE depart_id = ".$_POST['deptosel']." AND WEP_LectH > 0
														GROUP BY edu_form_id, subject_id, sem, WEP_LectH";
	$SubjectsStreams_result = mysqli_query($conn, $SubjectsStreams_query) or 
				die("Помилка сервера при запиті<br>".$SubjectsStreams_query." : ".mysqli_error($conn)); $iStream = 0;
	while ($SubjectsStreams_row = mysqli_fetch_array($SubjectsStreams_result)) { 
		if ($SubjectsStreams_row['id_count'] > 1) { $iStream++;
			$SetStream_query = "UPDATE time_amount_for_departs 
													SET stream_code = ".$iStream.", lectural_hours = 0
													WHERE depart_id = ".$_POST['deptosel']." AND 
																edu_form_id = ".$SubjectsStreams_row['edu_form_id']." AND
																subject_id = ".$SubjectsStreams_row['subject_id']." AND
																sem = ".$SubjectsStreams_row['sem']." AND
																WEP_LectH = ".$SubjectsStreams_row['WEP_LectH']." AND
																id <> ".$SubjectsStreams_row['id_bottom'];
			$SetStream_result = mysqli_query($conn, $SetStream_query) or 
					die("Помилка сервера при запиті<br>".$SetStream_query." : ".mysqli_error($conn)); 
			$SetStream_query = "UPDATE time_amount_for_departs 
													SET stream_code = ".$iStream."
													WHERE depart_id = ".$_POST['deptosel']." AND 
																edu_form_id = ".$SubjectsStreams_row['edu_form_id']." AND
																subject_id = ".$SubjectsStreams_row['subject_id']." AND
																sem = ".$SubjectsStreams_row['sem']." AND
																WEP_LectH = ".$SubjectsStreams_row['WEP_LectH']." AND
																id = ".$SubjectsStreams_row['id_bottom'];
			$SetStream_result = mysqli_query($conn, $SetStream_query) or 
					die("Помилка сервера при запиті<br>".$SetStream_query." : ".mysqli_error($conn)); 
		}
	}
}
if (!empty($_POST['sbtSave'])) {
	$TimeAmount_result = mysqli_query($conn, $TimeAmount1_query) or 
				die("Помилка сервера при запиті<br>".$TimeAmount1_query." : ".mysqli_error($conn));
	while ($TimeAmount_row = mysqli_fetch_array($TimeAmount_result)) { 
		$_POST['chkSubj'.$TimeAmount_row['id']] = 
			isset($_POST['chkSubj'.$TimeAmount_row['id']]) ? $_POST['chkSubj'.$TimeAmount_row['id']] : "";
		if (!empty($_POST['chkSubj'.$TimeAmount_row['id']])) {
			$TimeAmountUpdate_query = "
				UPDATE time_amount_for_departs
				SET	stream_code = \"".$_POST['tbxStream'.$TimeAmount_row['id']]."\",
						lectural_hours = \"".$_POST['tbxLectH'.$TimeAmount_row['id']]."\"
				WHERE id = ".$TimeAmount_row['id'];
			$TimeAmountUpdate_result = mysqli_query($conn, $TimeAmountUpdate_query) or 
						die("Помилка сервера при запиті<br>".$TimeAmountUpdate_query." : ".mysqli_error($conn));
		}	unset($_POST['chkSubj'.$TimeAmount_row['id']]);
	}	
}
?>
<p style="font-size: 130%; color: blue; text-align: center; margin-bottom: 0.2em; margin-top: 0.2em">
<b>УВАГА!</b> Модуль "Навчальне навантаження кафедри" працює в <b>ТЕСТОВОМУ</b> режимі</p>
<p style="font-size: 150%; text-align: center; margin-bottom: 0.2em; margin-top: 0.2em">
Обсяг навчальної роботи <?php 
if ($_POST['deptosel'] == 80) echo "кафедр, які викладають іноземні мови,"; 
else echo " кафедри ".DepartCodeById($_POST['deptosel']); ?> на 2018/2019 н.р. 
станом на <?php echo date("d.m.Y"); ?>
</p>
<p style="font-size: 110%; text-align: center; margin-bottom: 0.2em; margin-top: 0.2em"><?php
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
if (empty($_POST['stftosel']) and $_POST['radSemesterSelect'] == "All" and !$TrueBoss) { ?>
<input type="checkbox" name="chkStreamAuto" <?php if (!empty($_POST['chkStreamAuto'])) echo "checked"; ?>
	onchange="
		if (confirm('Ця операція видалить усі лекційні потоки, сформовані раніше. Продовжити?')) submit();"> 
	Сформувати потоки автоматично
<?php 
/*	echo paramChekerInline('chkStreamAuto', $_POST['chkStreamAuto'], "Сформувати потоки автоматично", 
													"onchange='
			if confirm('Ця операція видалить усі лекційні потоки, сформовані раніше. Продовжити?') submit();'"); */
} ?>
</p>
<table style="margin-left: 0%; width: 100%;">
<thead>
	<tr><th rowspan=3>№</th><th rowspan=3>Дисципліна (блок)</th><th rowspan=3>Інст.</th>
			<th rowspan=3>Група</th><th rowspan=3>Студ.</th><th rowspan=3>Сем.</th>
			<th colspan=15>Обсяг навчальної роботи за видами занять, год.</th><th rowspan=3>Шифр РНП</th></tr>
	<tr><th colspan=3>Лекції</th>
			<th rowspan=2>Лаб.</th><th rowspan=2>Пр.</th><th rowspan=2>Конс.</th>
			<th rowspan=2>Екз.</th><th rowspan=2>Зал.</th><th rowspan=2>Дом.<br>роб.</th><th rowspan=2>КП/Р</th>
			<th rowspan=2>Прак-<br>тики</th><th rowspan=2>ЕК</th><th rowspan=2>Гол.<br>ЕК</th>
			<th rowspan=2>Вип.<br>роб.</th><th rowspan=2>Сума</th></tr>
	<tr><th>РНП</th><th colspan=2>
	<?php echo paramChekerInline('chkStreamSort', $_POST['chkStreamSort'], "Потік", "onchange=\"submit()\""); ?>
	</th></tr>
</thead>
<tbody>
<?php 
$StudyFormCond = ($_POST['stftosel'] > 0) ? "AND a.edu_form_id = ".$_POST['stftosel'] : "";
$SemCond = "";
switch ($_POST['radSemesterSelect']) { 
	case "Autumn": $SemCond = "AND MOD(a.sem, 2) > 0"; break;
	case "Spring": $SemCond = "AND MOD(a.sem, 2) = 0"; break;
} $TimeAmount1_query = str_replace("ORDER BY", $StudyFormCond." ORDER BY", $TimeAmount1_query);
$TimeAmount1_query = str_replace("ORDER BY", $SemCond." ORDER BY", $TimeAmount1_query);
$TimeAmount_result = mysqli_query($conn, $TimeAmount1_query) or 
			die("Помилка сервера при запиті<br>".$TimeAmount1_query." : ".mysqli_error($conn));
$icnt = 0; $SumLec = 0; $SumLab = 0; $SumPrac = 0; $SumCons = 0; $SumExam = 0; $SumCred = 0;
$SumHome = 0; $SumPap = 0; $SumPraq = 0; $SumExamBd = 0; $SumHead = 0; $SumThes = 0;
// суми доя потоків
$SumStudStr = 0; $SumLecStr = 0; $SumLabStr = 0; $SumPracStr = 0; $SumConsStr = 0; $SumExamStr = 0; $SumCredStr = 0;
$SumHomeStr = 0; $SumPapStr = 0; $TotalSumStr = 0;
$stream_top = ""; $stream_bottom = "";
while ($TimeAmount_row = mysqli_fetch_array($TimeAmount_result)) { $icnt++; 
/*	if (!empty($TimeAmount_row['stream_code']) and ($icnt > 1) and 
			($stream_top != $TimeAmount_row['stream_code'])) { 
		$stream_top = $TimeAmount_row['stream_code']; ?><tr><?php
		$SumStudStr = 0; $SumLecStr = 0; $SumLabStr = 0; $SumPracStr = 0; $SumConsStr = 0; 
		$SumExamStr = 0; $SumCredStr = 0;	$SumHomeStr = 0; $SumPapStr = 0; $TotalSumStr = 0;
		for ($itd = 0; $itd < 22; $itd++) { ?><th></th><?php } ?></tr><?php
	} */
	if ($stream_top != $TimeAmount_row['stream_code']) { 
		$stream_bottom = $stream_top;	$stream_top = $TimeAmount_row['stream_code'];
		if (!empty($stream_bottom)) { ?>
		<tr><th colspan=4 style="text-align: right;">Усього в потоці №<? echo $stream_bottom; ?></th>
			<th><?php	echo $SumStudStr; ?></th><th colspan=3></th>
			<th><?php	echo $SumLecStr; ?></th><th><?php echo ($SumLabStr > 0) ? $SumLabStr : ""; ?></th>
			<th><?php	echo ($SumPracStr > 0) ? $SumPracStr : ""; ?></th>
			<th><?php	echo ($SumConsStr > 0) ? $SumConsStr : ""; ?></th>
			<th><?php echo ($SumExamStr > 0) ? $SumExamStr : ""; ?></th>
			<th><?php	echo ($SumCredStr > 0) ? $SumCredStr : ""; ?></th>
			<th><?php	echo ($SumHomeStr > 0) ? $SumHomeStr : ""; ?></th>
			<th><?php	echo ($SumPapStr > 0) ? $SumPapStr : ""; ?></th><th colspan=4></th>
			<th><?php	echo $TotalSumStr; ?></th><th></th></tr><?php
		}
		$SumStudStr = 0; $SumLecStr = 0; $SumLabStr = 0; $SumPracStr = 0; $SumConsStr = 0; 
		$SumExamStr = 0; $SumCredStr = 0;	$SumHomeStr = 0; $SumPapStr = 0; $TotalSumStr = 0; 
		if (!empty($stream_top)) { ?>
		<tr><th colspan=22 style="font-size: 80%; padding: 0px 0px;">Потік № <? echo $stream_top; ?>:</th>
		</tr><?php
		}
	}
	$_POST['chkSubj'.$TimeAmount_row['id']] = 
		isset($_POST['chkSubj'.$TimeAmount_row['id']]) ? $_POST['chkSubj'.$TimeAmount_row['id']] : "";
?>
	<tr><td><?php echo $icnt.".".$TimeAmount_row['id']; ?></td>
			<td><?php echo $TimeAmount_row['subject_name'].((!empty($TimeAmount_row['free_block'])) ? 
											" (".$TimeAmount_row['free_block'].")" : ""); ?></td>
			<td><?php echo $TimeAmount_row['fakultet_shufr']; ?></td>
			<td><?php echo $TimeAmount_row['group_next_name']; ?></td>
			<td><?php echo $TimeAmount_row['students_count']; $SumStudStr += $TimeAmount_row['students_count']; ?></td>
			<td><?php $degree_fl = mb_substr($TimeAmount_row['acad_year'], 0, 1); $ay = "";
								switch (TRUE) {
									case ($degree_fl == "М") : $ay = "м."; break;
									case ($degree_fl == "Б") and ($TimeAmount_row['base_edu_degree_id'] == 10) : 
											$ay = "к."; break;
								}
								echo $ay.$TimeAmount_row['sem']; ?></td>
			<td><?php echo ($TimeAmount_row['WEP_LectH'] > 0) ? $TimeAmount_row['WEP_LectH'] : ""; ?></td>
			<td><?php 
	if ((($TimeAmount_row['WEP_LectH'] > 0) or !empty($TimeAmount_row['stream_code'])) and !$TrueBoss)
		echo paramChekerInline('chkSubj'.$TimeAmount_row['id'], 
														$_POST['chkSubj'.$TimeAmount_row['id']], "", "onchange=\"submit()\""); 
	if (empty($_POST['chkSubj'.$TimeAmount_row['id']]))
		echo $TimeAmount_row['stream_code']; 
	else { ?>
				<input type="textbox" name="tbxStream<?php echo $TimeAmount_row['id']; ?>" 
						style="font-weight: bold; width: 30px;"
						value="<?php echo $TimeAmount_row['stream_code']; ?>" /><?php					
	} ?></td>
			<td><?php $SumLecStr += $TimeAmount_row['lectural_hours'];
	if (empty($_POST['chkSubj'.$TimeAmount_row['id']]))
		echo ($TimeAmount_row['lectural_hours'] > 0) ? $TimeAmount_row['lectural_hours'] : "";
	else { ?>
				<input type="textbox" name="tbxLectH<?php echo $TimeAmount_row['id']; ?>" 
						style="font-weight: bold; width: 25px;"
						value="<?php echo $TimeAmount_row['lectural_hours']; ?>" /><?php
	} ?></td>
			<td><?php echo ($TimeAmount_row['laboratorials_hours'] > 0) ? $TimeAmount_row['laboratorials_hours'] : ""; 
								$SumLabStr += $TimeAmount_row['laboratorials_hours']; ?></td>
			<td><?php echo ($TimeAmount_row['practicals_hours'] > 0) ? $TimeAmount_row['practicals_hours'] : ""; 
								$SumPracStr += $TimeAmount_row['practicals_hours']; ?></td>
			<td><?php echo (($TimeAmount_row['consult_current_hours'] + 
												$TimeAmount_row['consult_exam_hours']) > 0) ? 
											$TimeAmount_row['consult_current_hours'].' + '.
												$TimeAmount_row['consult_exam_hours'] : ""; 
								$SumConsStr += $TimeAmount_row['consult_current_hours'] + 
															$TimeAmount_row['consult_exam_hours']; ?></td>
			<td><?php echo ($TimeAmount_row['exam_hours'] > 0) ? $TimeAmount_row['exam_hours'] : ""; 
								$SumExamStr += $TimeAmount_row['exam_hours']; ?></td>
			<td><?php echo ($TimeAmount_row['credit_hours'] > 0) ? $TimeAmount_row['credit_hours'] : ""; 
								$SumCredStr += $TimeAmount_row['credit_hours']; ?></td>
			<td><?php echo ($TimeAmount_row['home_tasks_hours'] > 0) ? $TimeAmount_row['home_tasks_hours'] : ""; 
								$SumHomeStr += $TimeAmount_row['home_tasks_hours']; ?></td>
			<td><?php echo ($TimeAmount_row['research_paper_hours'] > 0) ? 
												$TimeAmount_row['research_paper_hours'] : ""; 
								$SumPapStr += $TimeAmount_row['research_paper_hours']; ?></td>
			<td></td><td></td><td></td><td></td>
			<td><?php echo $TimeAmount_row['lectural_hours'] + $TimeAmount_row['laboratorials_hours'] + 
										$TimeAmount_row['practicals_hours'] + 
										$TimeAmount_row['consult_current_hours'] + $TimeAmount_row['consult_exam_hours'] +
										$TimeAmount_row['exam_hours'] + $TimeAmount_row['credit_hours'] + 
										$TimeAmount_row['home_tasks_hours'] + $TimeAmount_row['research_paper_hours']; 
								$TotalSumStr += $TimeAmount_row['lectural_hours'] + $TimeAmount_row['laboratorials_hours'] + 
										$TimeAmount_row['practicals_hours'] + 
										$TimeAmount_row['consult_current_hours'] + $TimeAmount_row['consult_exam_hours'] +
										$TimeAmount_row['exam_hours'] + $TimeAmount_row['credit_hours'] + 
										$TimeAmount_row['home_tasks_hours'] + $TimeAmount_row['research_paper_hours']; ?></td>
			<td><?php echo ($TimeAmount_row['reg_number'])."/".$TimeAmount_row['plan_id']; ?></td>
	</tr><?php
	if (!empty($_POST['chkSubj'.$TimeAmount_row['id']])) { ?>
	<tr><td colspan=19>
				<input type="submit" name="sbtSave" value="Зберегти" style="font-weight: bold; color: green;" >
			</td></tr><?php 
	} $SumLec += $TimeAmount_row['lectural_hours'];	$SumLab += $TimeAmount_row['laboratorials_hours']; 
	$SumPrac += $TimeAmount_row['practicals_hours']; 
	$SumCons += $TimeAmount_row['consult_current_hours'] + $TimeAmount_row['consult_exam_hours'];
	$SumExam += $TimeAmount_row['exam_hours']; $SumCred += $TimeAmount_row['credit_hours'];	
  $SumHome += $TimeAmount_row['home_tasks_hours']; $SumPap += $TimeAmount_row['research_paper_hours'];
}
if (!empty($stream_top)) { ?>
	<tr><th colspan=4 style="text-align: right;">Усього в потоці №<? echo $stream_top; ?></th>
			<th><?php	echo $SumStudStr; ?></th><th colspan=3></th>
			<th><?php	echo $SumLecStr; ?></th><th><?php echo ($SumLabStr > 0) ? $SumLabStr : ""; ?></th>
			<th><?php	echo ($SumPracStr > 0) ? $SumPracStr : ""; ?></th>
			<th><?php	echo ($SumConsStr > 0) ? $SumConsStr : ""; ?></th>
			<th><?php echo ($SumExamStr > 0) ? $SumExamStr : ""; ?></th>
			<th><?php	echo ($SumCredStr > 0) ? $SumCredStr : ""; ?></th>
			<th><?php	echo ($SumHomeStr > 0) ? $SumHomeStr : ""; ?></th>
			<th><?php	echo ($SumPapStr > 0) ? $SumPapStr : ""; ?></th><th colspan=4></th>
			<th><?php	echo $TotalSumStr; ?></th><th></th></tr><?php
}

$TimeAmount2_query = "SELECT a.*, c.group_next_name, d.fakultet_shufr, 
														e.reg_number, e.base_edu_degree_id
											FROM (time_amount_for_departs a,  
														catalogGroupNext c, catalogFakultet d, catalogWorkEduPlan e)
											WHERE a.depart_id = ".$_POST['deptosel']." AND a.subject_id = 0 AND 
														a.group_id = c.id AND c.faculty_id = d.id AND a.plan_id = e.id 
											ORDER BY a.edu_form_id, a.subject_name";
$TimeAmount2_query = str_replace("ORDER BY", $StudyFormCond." ORDER BY", $TimeAmount2_query);
$TimeAmount2_query = str_replace("ORDER BY", $SemCond." ORDER BY", $TimeAmount2_query);
$TimeAmount_result = mysqli_query($conn, $TimeAmount2_query) or 
			die("Помилка сервера при запиті<br>".$TimeAmount2_query." : ".mysqli_error($conn));
while ($TimeAmount_row = mysqli_fetch_array($TimeAmount_result)) { $icnt++; ?>
	<tr><td><?php echo $icnt.".".$TimeAmount_row['id']; ?></td>
			<td><?php echo $TimeAmount_row['subject_name'].((!empty($TimeAmount_row['free_block'])) ? 
											" (".$TimeAmount_row['free_block'].")" : ""); ?></td>
			<td><?php echo $TimeAmount_row['fakultet_shufr']; ?></td>
			<td><?php echo $TimeAmount_row['group_next_name']; ?></td>
			<td><?php echo $TimeAmount_row['students_count']; ?></td>
			<td><?php $degree_fl = mb_substr($TimeAmount_row['acad_year'], 0, 1); $ay = "";
								switch (TRUE) {
									case ($degree_fl == "М") : $ay = "м."; break;
									case ($degree_fl == "Б") and ($TimeAmount_row['base_edu_degree_id'] == 10) : 
											$ay = "к."; break;
								}
								echo $ay.$TimeAmount_row['sem']; ?></td>
			<?php for ($itd = 0; $itd < 10; $itd++) { ?><td></td><?php } ?>
			<td><?php echo ($TimeAmount_row['practical_training_hours'] > 0) ? 
											$TimeAmount_row['practical_training_hours'] : ""; ?></td>
			<td><?php echo ($TimeAmount_row['examination_board_hours'] > 0) ? 
											$TimeAmount_row['examination_board_hours'] : ""; ?></td>
			<td><?php echo ($TimeAmount_row['head_exam_board_hours'] > 0) ? 
											$TimeAmount_row['head_exam_board_hours'] : ""; ?></td>
			<td><?php echo ($TimeAmount_row['final_thesis_hours'] > 0) ? 
											$TimeAmount_row['final_thesis_hours'] : ""; ?></td>
			<td><?php echo $TimeAmount_row['practical_training_hours'] + $TimeAmount_row['examination_board_hours']
									+ $TimeAmount_row['head_exam_board_hours'] + $TimeAmount_row['final_thesis_hours']; ?></td>
			<td><?php echo $TimeAmount_row['reg_number']."/".$TimeAmount_row['plan_id']; ?></td>
	</tr><?php 
	$SumPraq += $TimeAmount_row['practical_training_hours'];
	$SumExamBd += $TimeAmount_row['examination_board_hours'];
	$SumHead += $TimeAmount_row['head_exam_board_hours']; $SumThes += $TimeAmount_row['final_thesis_hours'];
} 
if (($_POST['radSemesterSelect'] == "All") and empty($_POST['stftosel'])) {
// керівництво аспірантами та інші години
	$_POST['tbxps'] = isset($_POST['tbxps']) ? $_POST['tbxps'] : 0;
	$_POST['tbxap'] = isset($_POST['tbxap']) ? $_POST['tbxap'] : 0;
	$_POST['tbxoh'] = isset($_POST['tbxoh']) ? $_POST['tbxoh'] : 0;
	$_POST['sbtSavePostAppl'] = isset($_POST['sbtSavePostAppl']) ? $_POST['sbtSavePostAppl'] : "";
	if (!empty($_POST['sbtSavePostAppl'])) {
		$UpdateDepart_query = "UPDATE catalogDepartment SET	
														postgrads = \"".$_POST['tbxps']."\", 
														applicants = \"".$_POST['tbxap']."\"
													WHERE id = ".$_POST['deptosel'];
		$UpdateDepart_result = mysqli_query($conn, $UpdateDepart_query) or 
					die("Помилка сервера при запиті<br>".$UpdateDepart_query." : ".mysqli_error($conn));
	}
	$_POST['sbtSaveOtherHours'] = isset($_POST['sbtSaveOtherHours']) ? $_POST['sbtSaveOtherHours'] : "";
	if (!empty($_POST['sbtSaveOtherHours'])) {
		$UpdateDepart_query = "UPDATE catalogDepartment SET	other_hours = \"".$_POST['tbxoh']."\" 
													WHERE id = ".$_POST['deptosel'];
		$UpdateDepart_result = mysqli_query($conn, $UpdateDepart_query) or 
					die("Помилка сервера при запиті<br>".$UpdateDepart_query." : ".mysqli_error($conn));
	}
	$Depart_query = "SELECT * FROM catalogDepartment WHERE id = ".$_POST['deptosel'];
	$Depart_result = mysqli_query($conn, $Depart_query) or 
				die("Помилка сервера при запиті<br>".$Depart_query." : ".mysqli_error($conn));
	$Depart_row = mysqli_fetch_array($Depart_result);
	$_POST['tbxps'] = $Depart_row['postgrads']; $_POST['tbxap'] = $Depart_row['applicants'];
	$_POST['tbxoh'] = $Depart_row['other_hours']; ?>
<tr><td colspan=4>Керівництво аспірантами та здобувачами: <br>Кількість аспірантів: 
				<input type="textbox" name="tbxps" style="font-weight: bold; width: 25px; text-align: right;" 
					value="<?php echo $_POST['tbxps']; ?>" />ос., &nbsp; здобувачів: 
				<input type="textbox" name="tbxap" style="font-weight: bold; width: 25px; text-align: right;"
					value="<?php echo $_POST['tbxap']; ?>" />ос. &nbsp; &nbsp; <?php
	if (!$TrueBoss) { ?><input type="submit" name="sbtSavePostAppl" value="Зберегти" 
															style="font-weight: bold; color: green;" ><?php } ?>
	</td><?php for ($itd = 0; $itd < 16; $itd++) { ?><td></td><?php } ?>
	<td style="vertical-align: middle;"><?php 
	$PostgraduateHours = $Depart_row['postgrads'] * 50 + $Depart_row['applicants'] * 25;
	echo $PostgraduateHours; ?></td></tr>
<tr><td colspan=4>Інші види навчальної роботи, не передбачені в РНП: 
			<input type="textbox" name="tbxoh" style="font-weight: bold; width: 35px; text-align: right;"
				value="<?php echo $_POST['tbxoh']; ?>" />год. &nbsp; 
 <?php
	if (!$TrueBoss) { ?><input type="submit" name="sbtSaveOtherHours" value="Зберегти" 
															style="font-weight: bold; color: green;" ><?php } ?>
	</td><?php	for ($itd = 0; $itd < 16; $itd++) { ?><td></td><?php } ?>
	<td><? echo $Depart_row['other_hours']; ?></td></tr><?php
} ?>
<tr><th colspan=8>Разом</th>
		<th><?php echo $SumLec; ?></th><th><?php echo $SumLab; ?></th><th><?php echo $SumPrac; ?></th>
		<th><?php echo $SumCons; ?></th><th><?php echo $SumExam; ?></th><th><?php echo $SumCred; ?></th>
	  <th><?php echo $SumHome; ?></th><th><?php echo $SumPap; ?></th><th><?php echo $SumPraq; ?></th>
		<th><?php echo $SumExamBd; ?></th><th><?php echo $SumHead; ?></th><th><?php echo $SumThes; ?></th>
		<th><?php 
$SumStud = $SumLec + $SumLab + $SumPrac + $SumCons + $SumExam + $SumCred + $SumHome + $SumPap + 
					$SumPraq + $SumExamBd + $SumHead + $SumThes;
$TotalTime = $SumStud + ((($_POST['radSemesterSelect'] == "All") and empty($_POST['stftosel'])) ? 
									$PostgraduateHours + $Depart_row['other_hours'] : 0); echo $TotalTime; ?></th>
	</tr>
	<tr><th colspan=8></th><th>Лек.</th><th>Лаб.</th><th>Пр.</th><th>Конс.</th>
			<th>Екз.</th><th>Зал.</th><th>Дом.з.</th><th>КП/Р</th><th>Прак-<br>тики</th>
			<th>ЕК</th><th>Гол.<br>ЕК</th><th>Вип.<br>роб.</th><th>Усього</th>
	</tr>
</tbody>
</table><?php 
if (($_POST['radSemesterSelect'] == "All") and empty($_POST['stftosel'])) { ?>
<p style="text-align: center; font-size: 120%; ">
Затверджений штат кафедри станом на 01.10.2017р., ставок - <?php 
	$DepartSalaries_query = "SELECT teachers_salaries FROM catalogDepartment WHERE id = ".$_POST['deptosel'];
	$DepartSalaries_result = mysqli_query($conn, $DepartSalaries_query) or 
					die("Помилка сервера при запиті<br>".$DepartSalaries_query." : ".mysqli_error($conn));
	$DepartSalaries_row = mysqli_fetch_array($DepartSalaries_result); 
	echo $DepartSalaries_row['teachers_salaries'];
?> &nbsp; &nbsp; &nbsp; Прогнозне навантаження на 1 ставку, год. - <?php 
	echo round($TotalTime / $DepartSalaries_row['teachers_salaries']); ?></p><?php
}
?>
