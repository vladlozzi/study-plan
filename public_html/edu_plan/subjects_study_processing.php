<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
	                              "Помилка входу в модуль plan_work_study.php</p>"; 
									 require "footer.php"; exit(); }
$IdsSubjQuery = "SELECT id FROM plan_work_subj_study 
						WHERE plan_id = \"".$query_row['id']."\" AND norm_var = \"$NormVarUkr\"
						ORDER BY id";
$query0_result = mysqli_query($conn, $IdsSubjQuery) or 
				die("<br>Помилка сервера при запиті<br>".$IdsSubjQuery." : ".mysqli_error($conn));
if (mysqli_num_rows($query0_result) == 0) { // якщо дисциплін немає, додаємо 10 порожніх рядків
	$AddSubjQuery = "INSERT INTO plan_work_subj_study VALUES(\"\",".$query_row['id'].",\"$NormVarUkr\",
									\"\",\"\",\"\",\"\",0".str_repeat(",\"\"",64+3).")";
	for ($iSubjRow = 1; $iSubjRow <= 20; $iSubjRow++)
		$query00_result = mysqli_query($conn, $AddSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$AddSubjQuery." : ".mysqli_error($conn));
}

if (isset($_POST['sbtAdd'.$NormVarEng.'Subj'])) {
	$AddSubjQuery = "INSERT INTO plan_work_subj_study VALUES(\"\",".$query_row['id'].",\"$NormVarUkr\",
									\"\",\"\",\"\",\"\",0".str_repeat(",\"\"",64+3).")";
//	echo $AddNormSubjQuery;
	$query2_result = mysqli_query($conn, $AddSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$AddSubjQuery." : ".mysqli_error($conn));
}
if (isset($_POST['sbtEdit'.$NormVarEng.'Subj'])) {
	$query3_result = mysqli_query($conn, $IdsSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsSubjQuery." : ".mysqli_error($conn));
	while ($query3_row = mysqli_fetch_array($query3_result))
		if (isset($_POST['chk'.$NormVarEng.'Subj'.$query3_row['id']])) 
			{ $SubjectIdChecked[$NormVarEng] = $query3_row['id']; break; } 
	if ($SubjectIdChecked[$NormVarEng] > 0) {
		$CheckedSubjQuery = "SELECT * FROM plan_work_subj_study 
												WHERE id = \"".$SubjectIdChecked[$NormVarEng]."\"";
		echo $CheckedNormSubjQuery;
		$query4_result = mysqli_query($conn, $CheckedSubjQuery) or 
						die("<br>Помилка сервера при запиті<br>".$CheckedSubjQuery." : ".mysqli_error($conn));
		$SubjectToEdit[$NormVarEng] = $SubjectIdChecked[$NormVarEng];
	}
}
$q5_result = mysqli_query($conn, $IdsSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsSubjQuery." : ".mysqli_error($conn));
while ($q5_row = mysqli_fetch_array($q5_result)) {
	if (!empty($_POST['sbtSaveSubj'.$q5_row['id']])) { // Збереження даних для позначеної дисципліни
//		echo "Зберігаємо... ";
		$SavingSubjCatalogId = 0;// читаємо код дисципліни в довіднику дисциплін
		$txtSubject = $_POST['txtSubject'.$q5_row['id']];
//		echo $txtSubject." ";
		$txtSubjectSplitted = Array();
		preg_match_all("#\b([a-zA-Z0-9А-Яа-яЁёІіЇїЄєҐґ':]+)\b#u", $txtSubject, $txtSubjectSplitted);
		$txtSubjectSplitted_count = count($txtSubjectSplitted);
		$begin_array = reset($txtSubjectSplitted); // print_r($begin_array);
		$end_elem = end($begin_array); // print_r($end_elem);
		$n = sscanf($end_elem, "ID:%d", $SavingSubjCatalogId); 
//		echo " | ".$SavingSubjCatalogId."<br>";
		if ($SavingSubjCatalogId > 0) {
			$UpdatedFields = ""; // формуємо перелік полів для збереження
			for ($isem = 1; $isem<=8; $isem++) {
				$UpdatedFields .= ", sem".$isem."_sem_test = \"".$_POST['sem'.$isem.'semt'.$q5_row['id']]."\"";
				$UpdatedFields .= ", sem".$isem."_acad_year_paper = \"".$_POST['sem'.$isem.'aypap'.$q5_row['id']]."\"";
				$UpdatedFields .= ", sem".$isem."_lectural_hours = \"".$_POST['sem'.$isem.'lech'.$q5_row['id']]."\"";			
				$UpdatedFields .= ", sem".$isem."_laboratorials_hours = \"".$_POST['sem'.$isem.'labh'.$q5_row['id']]."\"";			
				$UpdatedFields .= ", sem".$isem."_practicals_hours = \"".$_POST['sem'.$isem.'prh'.$q5_row['id']]."\"";			
				$UpdatedFields .= ", sem".$isem."_individual_work_hours = \"".$_POST['sem'.$isem.'indh'.$q5_row['id']]."\"";			
				$UpdatedFields .= ", sem".$isem."_home_tasks = \"".$_POST['sem'.$isem.'homet'.$q5_row['id']]."\"";			
				$UpdatedFields .= ", sem".$isem."_sem_hours = \"".
										(	$_POST['sem'.$isem."lech".$q5_row['id']] + 
											$_POST['sem'.$isem."labh".$q5_row['id']] +
											$_POST['sem'.$isem."prh".$q5_row['id']] +
											$_POST['sem'.$isem."indh".$q5_row['id']]
										)."\"";			
			}			
			$SaveSubjQuery = "UPDATE plan_work_subj_study SET 
									subject_id = \"$SavingSubjCatalogId\"
									$UpdatedFields 
									WHERE id = ".$q5_row['id'];
//			echo $SaveSubjQuery;
			$q6_result = mysqli_query($conn, $SaveSubjQuery) or 
						die("<br>Помилка сервера при запиті<br>".$SaveSubjQuery." : ".mysqli_error($conn));
		}
	}
}
if (isset($_POST['sbtDelete'.$NormVarEng.'Subj'])) {
	$q7_result = mysqli_query($conn, $IdsSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsSubjQuery." : ".mysqli_error($conn));
	while ($q7_row = mysqli_fetch_array($q7_result))
		if (isset($_POST['chk'.$NormVarEng.'Subj'.$q7_row['id']])) { 
			$DeleteCheckedSubjQuery = "DELETE FROM plan_work_subj_study WHERE id = ".$q7_row['id'];
			$q8_result = mysqli_query($conn, $DeleteCheckedSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$DeleteCheckedSubjQuery." : ".mysqli_error($conn));
		} 
}
if (isset($_POST['sbtMoveSubjToNorm'])) {
	$IdsSubjQuery = "SELECT id FROM plan_work_subj_study 
						WHERE plan_id = ".$query_row['id']." ORDER BY id";
	$qs_result = mysqli_query($conn, $IdsSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsSubjQuery." : ".mysqli_error($conn));
	while ($qs_row = mysqli_fetch_array($qs_result))
		if (isset($_POST['chkVarSubj'.$qs_row['id']])) { 
			$MoveCheckedSubjQuery = "UPDATE plan_work_subj_study 
												SET norm_var = \"Нормат\", free_block = \"\" WHERE id = ".$qs_row['id'];
			$qu_result = mysqli_query($conn, $MoveCheckedSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$MoveCheckedSubjQuery." : ".mysqli_error($conn));
		} 
}
if (isset($_POST['sbtMoveSubjToVar'])) {
	$IdsSubjQuery = "SELECT id FROM plan_work_subj_study 
						WHERE plan_id = ".$query_row['id']." ORDER BY id";
	$qs_result = mysqli_query($conn, $IdsSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsSubjQuery." : ".mysqli_error($conn));
	while ($qs_row = mysqli_fetch_array($qs_result))
		if (isset($_POST['chkNormSubj'.$qs_row['id']])) { 
			$MoveCheckedSubjQuery = "UPDATE plan_work_subj_study 
												SET norm_var = \"Вибірк\" WHERE id = ".$qs_row['id'];
			$qu_result = mysqli_query($conn, $MoveCheckedSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$MoveCheckedSubjQuery." : ".mysqli_error($conn));
		} 
}
if (isset($_POST['sbtSaveNormSubjToCycle'])) {
	$IdsSubjQuery = "SELECT id FROM plan_work_subj_study 
						WHERE plan_id = ".$query_row['id']." ORDER BY id";
	$qs_result = mysqli_query($conn, $IdsSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsSubjQuery." : ".mysqli_error($conn));
	while ($qs_row = mysqli_fetch_array($qs_result))
		if (isset($_POST['chkNormSubj'.$qs_row['id']])) { 
			$SaveCheckedSubjQuery = "UPDATE plan_work_subj_study 
												SET subj_cycle_id = ".$_POST['selCyclesNormSubj']." 
												WHERE id = ".$qs_row['id'];
			$qu_result = mysqli_query($conn, $SaveCheckedSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$SaveCheckedSubjQuery." : ".mysqli_error($conn));
		} 
}
if (isset($_POST['sbtSaveVarSubjToCycle'])) {
	$IdsSubjQuery = "SELECT id FROM plan_work_subj_study 
						WHERE plan_id = ".$query_row['id']." ORDER BY id";
	$qs_result = mysqli_query($conn, $IdsSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsSubjQuery." : ".mysqli_error($conn));
	while ($qs_row = mysqli_fetch_array($qs_result))
		if (isset($_POST['chkVarSubj'.$qs_row['id']])) { 
			$SaveCheckedSubjQuery = "UPDATE plan_work_subj_study 
												SET subj_cycle_id = ".$_POST['selCyclesVarSubj'].", 
													free_block = \"".$_POST['txtBlockVarSubj']."\" 
												WHERE id = ".$qs_row['id'];
			$qu_result = mysqli_query($conn, $SaveCheckedSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$SaveCheckedSubjQuery." : ".mysqli_error($conn));
		} 
}
if (isset($_POST['sbtDeleteNormSubjFromCycle'])) {
	$IdsSubjQuery = "SELECT id FROM plan_work_subj_study 
						WHERE plan_id = ".$query_row['id']." ORDER BY id";
	$qs_result = mysqli_query($conn, $IdsSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsSubjQuery." : ".mysqli_error($conn));
	while ($qs_row = mysqli_fetch_array($qs_result))
		if (isset($_POST['chkNormSubj'.$qs_row['id']])) { 
			$SaveCheckedSubjQuery = "UPDATE plan_work_subj_study 
												SET subj_cycle_id = 0, free_block = \"\" WHERE id = ".$qs_row['id'];
			$qu_result = mysqli_query($conn, $SaveCheckedSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$SaveCheckedSubjQuery." : ".mysqli_error($conn));
		} 
}
if (isset($_POST['sbtDeleteVarSubjFromCycle'])) {
	$IdsSubjQuery = "SELECT id FROM plan_work_subj_study 
						WHERE plan_id = ".$query_row['id']." ORDER BY id";
	$qs_result = mysqli_query($conn, $IdsSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsSubjQuery." : ".mysqli_error($conn));
	while ($qs_row = mysqli_fetch_array($qs_result))
		if (isset($_POST['chkVarSubj'.$qs_row['id']])) { 
			$SaveCheckedSubjQuery = "UPDATE plan_work_subj_study 
												SET subj_cycle_id = 0, free_block = \"\" WHERE id = ".$qs_row['id'];
			$qu_result = mysqli_query($conn, $SaveCheckedSubjQuery) or 
					die("<br>Помилка сервера при запиті<br>".$SaveCheckedSubjQuery." : ".mysqli_error($conn));
		} 
}
?>