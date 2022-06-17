<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль work_edu_plans_semesters.php</p>"; require "footer.php"; exit(); }
$TotalEduPlansQuery = "SELECT a.*, b.degree_name, c.degree_name AS base_degree_name, 
						d.edu_form, e.fakultet_name, 
						CONCAT(f.specialty_b_code,\" \",f.specialty_name,\" (Перелік \",f.list,\")\") AS specialty_codename, 
						CONCAT(\"(\",g.eduprogram_code,\") \",g.eduprogram_name) AS eduprogram_codename 
				FROM catalogWorkEduPlan a, catalogEduDegree b, catalogEduDegree c, catalogEduForm d, 
						catalogFakultet e, catalogSpecialty f, catalogEduProgram g 
				WHERE a.edu_degree_id = b.id AND a.base_edu_degree_id = c.id 
					AND	a.edu_form_id = d.id AND a.faculty_id = e.id 
					AND a.specialty_id = f.id AND a.edu_program_id = g.id 
				ORDER BY reg_number
"; 
$rpp = 500; // кількість на сторінку
$query_result = mysqli_query($conn, $TotalEduPlansQuery) or 
	die("Помилка сервера при запиті<br>".$TotalEduPlansQuery." : ".mysqli_error($conn));
$TotalRows = mysqli_num_rows($query_result); $nPages = ceil($TotalRows / $rpp); 
$_POST['chkEditMode'] = isset($_POST['chkEditMode']) ? $_POST['chkEditMode'] : "";
$_POST['radPageSelect'] = isset($_POST['radPageSelect']) ? 
															$_POST['radPageSelect'] : "Page1";
?><p style="text-align: center;">Сторінка: <?php 
for ($iPage = 1; $iPage <= $nPages; $iPage++) { ?>
<input type="radio" name="radPageSelect" value="Page<? echo $iPage; ?>" onclick="submit()" 
	<?php if ($_POST['radPageSelect'] == "Page".$iPage) echo "checked"; ?>> 
	<?php echo $iPage; ?> &nbsp; &nbsp; <?php
}
if ($TrueAdmin) 
	echo paramChekerInline("chkEditMode", $_POST['chkEditMode'], "Редагування", "onchange=\"submit()\"");
?></p><?php
$EduPlansQuery = $TotalEduPlansQuery;
for ($iPage = 1; $iPage <= $nPages; $iPage++) { 
	if ($_POST['radPageSelect'] == "Page".$iPage) 
		$EduPlansQuery = $TotalEduPlansQuery." LIMIT ".(($iPage-1)*$rpp).", $rpp";
}
$_POST['BottomSaveSemesters'] = isset($_POST['BottomSaveSemesters']) ? $_POST['BottomSaveSemesters'] : "";
if (!empty($_POST['BottomSaveSemesters'])) {
	$query_result = mysqli_query($conn, $EduPlansQuery) or 
		die("Помилка сервера при запиті<br>".$EduPlansQuery." : ".mysqli_error($conn));
	$iRows = mysqli_num_rows($query_result); $iCnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { $iCnt++;
		$_POST['ssc'.$query_row['id']] = 
			isset($_POST['ssc'.$query_row['id']]) ? $_POST['ssc'.$query_row['id']] : 0;
		$_POST['sfc'.$query_row['id']] = 
			isset($_POST['sfc'.$query_row['id']]) ? $_POST['sfc'.$query_row['id']] : 0;
		$_POST['ssn'.$query_row['id']] = 
			isset($_POST['ssn'.$query_row['id']]) ? $_POST['ssn'.$query_row['id']] : 0;
		$_POST['sfn'.$query_row['id']] = 
			isset($_POST['sfn'.$query_row['id']]) ? $_POST['sfn'.$query_row['id']] : 0;
		$UpdateSemQuery = "UPDATE catalogWorkEduPlan SET
												sem_start_current = ".$_POST['ssc'.$query_row['id']].", 
												sem_final_current = ".$_POST['sfc'.$query_row['id']].", 
												sem_start_next = ".$_POST['ssn'.$query_row['id']].", 
												sem_final_next = ".$_POST['sfn'.$query_row['id']]." 
											WHERE id = ".$query_row['id']; // echo "<br>".$UpdateSemQuery;
		$UpdateSemQuery_result = mysqli_query($conn, $UpdateSemQuery) or 
			die("Помилка сервера при запиті<br>".$UpdateSemQuery." : ".mysqli_error($conn));
	}
}
?>
<table style="margin-left: 0%; width: 100%;"><thead>
	<tr><th rowspan=2>Код</th><th rowspan=2>Реєстровий<br>номер плану</th>
		<th colspan=3>На поточний навчальний рік</th><th colspan=3>На наступний навчальний рік</th>
		<th rowspan=2>Код</th><th rowspan=2>Реєстровий<br>номер плану</th>
		<th colspan=3>На поточний навчальний рік</th><th colspan=3>На наступний навчальний рік</th></tr>
	<tr><th>Початковий<br>семестр</th><th>Кінцевий<br>семестр</th><th>Сформувати<br>витяг</th>
		<th>Початковий<br>семестр</th><th>Кінцевий<br>семестр</th><th>Сформувати<br>витяг</th>
		<th>Початковий<br>семестр</th><th>Кінцевий<br>семестр</th><th>Сформувати<br>витяг</th>
		<th>Початковий<br>семестр</th><th>Кінцевий<br>семестр</th><th>Сформувати<br>витяг</th></tr></thead>
<tbody><?php // Завантажити перелік
$query_result = mysqli_query($conn, $EduPlansQuery) or 
			die("Помилка сервера при запиті<br>".$EduPlansQuery." : ".mysqli_error($conn));
$iRows = mysqli_num_rows($query_result); $iCnt = 0;
while ($query_row = mysqli_fetch_array($query_result)) { $iCnt++;
	$_POST['chkCur'.$query_row['id']] = (isset($_POST['chkCur'.$query_row['id']])) ? $_POST['chkCur'.$query_row['id']] : "";
	$_POST['chkNext'.$query_row['id']] = (isset($_POST['chkNext'.$query_row['id']])) ? $_POST['chkNext'.$query_row['id']] : "";
	if ($iCnt % 2 > 0) echo "<tr>"; ?>
		<td style="text-align: right; border-left: 4px solid green;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['reg_number']; ?></td>                        
		<td><?php
	if (empty($_POST['chkEditMode']))
		echo ($query_row['sem_start_current'] > 0) ? $query_row['sem_start_current'] : ""; 
	else { ?><input style="width: 30px;" type="number" min="0" max="8" value="<?php 
		echo $query_row['sem_start_current']; ?>" name="<?php echo "ssc".$query_row['id']; ?>" ><?php
	} ?></td>
		<td><?php 
	if (empty($_POST['chkEditMode'])) {	echo ($query_row['sem_final_current'] > 0) ? 
																						$query_row['sem_final_current'] : ""; }
	else { ?><input style="width: 30px;" type="number" min="0" max="8" value="<?php 
		echo $query_row['sem_final_current']; ?>"
		name=<?php echo "\"sfc".$query_row['id']."\""; ?>><?php
	} ?></td>
		<td style="border-right: 2px solid blue;"><?php
	if ($query_row['sem_start_current'] > 0 and $query_row['sem_final_current'] > 0)	
		echo paramChekerInline("chkCur".$query_row['id'], $_POST['chkCur'.$query_row['id']], 
														"", "onchange=\"submit()\""); ?></td>
		<td style="border-left: 2px solid blue;"><?php 
	if (empty($_POST['chkEditMode']))
		echo ($query_row['sem_start_next'] > 0) ? $query_row['sem_start_next'] : "";
	else { ?><input style="width: 30px;" type="number" min="0" max="8" value=<?php 
		echo "\"".$query_row['sem_start_next']."\""; ?>
		name=<?php echo "\"ssn".$query_row['id']."\""; ?>><?php
	} ?></td>
		<td><?php 
	if (empty($_POST['chkEditMode']))
		echo ($query_row['sem_final_next'] > 0) ? $query_row['sem_final_next'] : "";
	else { ?><input style="width: 30px;" type="number" min="0" max="8" value=<?php 
		echo "\"".$query_row['sem_final_next']."\""; ?>
		name=<?php echo "\"sfn".$query_row['id']."\""; ?>><?php
	} ?></td>
		<td style="border-right: 4px solid green;"><?php
	if ($query_row['sem_start_next'] > 0 and $query_row['sem_final_next'] > 0) 
		echo paramChekerInline("chkNext".$query_row['id'], $_POST['chkNext'.$query_row['id']], 
														"", "onchange=\"submit()\""); ?></td><?php
	if (($iCnt % 2 == 0) or ($iCnt % 2 > 0) and ($iCnt == $iRows)) echo "</tr>"; 
} ?>
</tbody>
<tfoot><tr><th colspan=16><?php echo "У довіднику $TotalRows РНП, по $rpp на сторінку"; ?></th></tr></tfoot>
</table>
<p style="text-align: center;"><?php
if (!empty($_POST['chkEditMode'])) { ?>
	<input style="font-weight: bold; color: green;" type="submit" name="BottomSaveSemesters" 
			value="Зберегти після редагування"><?php
} ?></p><?php
// Завантажити перелік повторно і вивести витяг з позначеного РНП
$query_result = mysqli_query($conn, $EduPlansQuery) or 
			die("Помилка сервера при запиті<br>".$EduPlansQuery." : ".mysqli_error($conn));
while ($query_row = mysqli_fetch_array($query_result)) {
	if (!empty($_POST['chkCur'.$query_row['id']]) or !empty($_POST['chkNext'.$query_row['id']])) { 
		$_POST['radFragment'] = isset($_POST['radFragment']) ? $_POST['radFragment'] : "ForStudent"; ?>
<p style="text-align: center; color: black; margin-top: 0.5em; margin-bottom: 0.1em;">
Виберіть форму витягу: &nbsp; 
<input type="radio" name="radFragment" value="ForStudent" onclick="submit()" 
	<?php if ($_POST['radFragment'] == "ForStudent") echo "checked"; ?>> 
Індивід. навч. план студента &nbsp; &nbsp; 
<input type="radio" name="radFragment" value="ForSchedule" onclick="submit()" 
	<?php if ($_POST['radFragment'] == "ForSchedule") echo "checked"; ?>>	Для складання розкладу &nbsp; &nbsp; <?php
		$_POST['chkPartOfPlan'] = (isset($_POST['chkPartOfPlan'])) ? $_POST['chkPartOfPlan'] : "";
		echo paramChekerInline("chkPartOfPlan", $_POST['chkPartOfPlan'], 
																"У межах чинності", "onchange=\"submit()\""); ?></p><?php
		switch ($_POST['radFragment']) {
			case "ForStudent":
				$GroupsNext_query = "SELECT group_next_name FROM catalogGroupNext 
															WHERE plan_id = ".$query_row['id']." 
															ORDER BY acad_year_next, group_next_name";
				$GroupsNext_result = mysqli_query($conn, $GroupsNext_query) or 
							die("Помилка сервера при запиті<br>".$GroupsNext_query." : ".mysqli_error($conn));
				$GroupsNext = mysqli_num_rows($GroupsNext_result); $GroupsNextNames = "";
				if ($GroupsNext == 1) { 
						$GroupsNext_row = mysqli_fetch_array($GroupsNext_result);
						$GroupsNextNames = "и ".$GroupsNext_row['group_next_name'];
				}
				if ($GroupsNext > 1) { $GroupsNextNames = " ";
					while ($GroupsNext_row = mysqli_fetch_array($GroupsNext_result)) {
						$GroupsNextNames .= $GroupsNext_row['group_next_name'].", ";
					} $GroupsNextNames = mb_substr($GroupsNextNames, 0, -2);
				}
?>
	<hr size=8 style="color: blue; background-color: blue;">
	<p style="text-align: center;">
	<span style="text-transform: uppercase; font-size: 125%; font-weight: bold;">
		Індивідуальний навчальний план</span><br><span style="font-size: 133%;">№ <?php 
				echo $query_row['reg_number']." / ".$query_row['id']; ?></span><br>
	<span style="font-size: 125%;">
		для студента академгруп<?php echo $GroupsNextNames; ?></span><?php 
				$frag = "YES"; $stud = "NO";
				require "./edu_plan/study_plan_header.php"; 
				require "./edu_plan/individual_study_subjects.php"; ?></p>
	<hr size=8 style="color: blue; background-color: blue;"><?php
		    break;
		}
	}
} ?>
</p>
