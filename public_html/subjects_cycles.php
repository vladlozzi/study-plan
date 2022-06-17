<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль subjects_cycles.php</p>"; 
										require "footer.php"; exit(); }
// Перелік циклів дисциплін
$SubjCyclesQuery = "SELECT id, cycle_name, cycle_rank	FROM catalogSubjectCycle ORDER BY cycle_name";
?><br>
<table class="scrolling-table" style="margin-left: 0%; width: 1300px;">
<thead>
	<tr style="width: 1300px;"><td colspan=6>
<?php 
$_POST['addсс'] = isset($_POST['addсс']) ? $_POST['addсс'] : 0;
if ($TrueAdmin) echo paramCheker("addсс", $_POST['addсс'], "Додати новий цикл дисциплін",
																	"onchange=\"submit()\""); ?></td></tr>
<?php 
if (!empty($_POST['addсс'])) {
	$_POST['ranktoadd'] = isset($_POST['ranktoadd']) ? $_POST['ranktoadd'] : "";
	$_POST['cycletoadd'] = isset($_POST['cycletoadd']) ? $_POST['cycletoadd'] : "";
	if (!empty($_POST['ranktoadd']) and !empty($_POST['cycletoadd'])) { 
		// перевірка, чи вже є такий дисципліна на кафедрі
		$IsSubCQuery = "SELECT * FROM catalogSubjectCycle 
							WHERE cycle_name = \"".$_POST['cycletoadd']."\"";
		$IsSubCQuery_result = mysqli_query($conn, $IsSubCQuery) or 
					die("<tr style=\"width: 1300px;\"><td colspan=5>Помилка сервера при запиті<br>".$IsSubCQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
		$icycles = mysqli_num_rows($IsSubCQuery_result);
		if ($icycles == 0) {
			$AddSubCQuery = "INSERT INTO catalogSubjectCycle VALUES
									(\"\",\"".$_POST['cycletoadd']."\",
									 \"".$_POST['ranktoadd']."\")"; // echo $AddSubСQuery;
			$AddSubCQuery_result = mysqli_query($conn, $AddSubCQuery) or 
							die("<tr><td colspan=5>Помилка сервера при запиті<br>".$AddSubCQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
?>
<tr style="width: 1300px;"><td colspan=6 style="color: green; font-weight: normal;">
			Цикл дисциплін "<? echo bold($_POST['cycletoadd']); ?>" успішно додано</td></tr>
<?php
		} else {
?>
<tr style="width: 1300px;"><td colspan=6 style="color: red; font-weight: normal;">
			Цикл дисциплін  "<? echo bold($_POST['cycletoadd']); ?>" в довіднику вже є!</td></tr>
<?php
		}
	}
?><tr style="width: 1300px;"><td colspan=6>
Назва циклу дисциплін: 
<input type="text" name="cycletoadd" 
	style="font-weight: bold; width: 640px;" value="<?php echo $_POST['cycletoadd']; ?>" /> &nbsp; 
Ранг: 
<input type="text" name="ranktoadd" 
	style="font-weight: bold; width: 30px;" value="<?php echo $_POST['ranktoadd']; ?>" /> &nbsp; 
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
}

// Завантажити перелік циклів дисциплін перед видаленням
$query1_result = mysqli_query($conn, $SubjCyclesQuery) 
						or die("Помилка сервера при запиті<br>".$SubjCyclesQuery." : ".mysqli_error($conn));
if (!empty($_POST['delc'])) { // Натиснуто кнопку "Видалити"
	while ($query1_row = mysqli_fetch_array($query1_result)) { // Обробка позначок "На видалення"
		if (!empty($_POST['delc'.$query1_row['id']])) { // обробка позначки "На видалення"
			$DeleteSubjCQuery = "DELETE FROM catalogSubjectCycle WHERE id='".$query1_row['id']."'";
			$dc_result = mysqli_query($conn, $DeleteSubjCQuery) or 
				die("Помилка сервера при запиті<br>".$DeleteSubjCQuery." : ".mysqli_error($conn));
		}
	}
}

// Завантажити перелік циклів дисциплін і змінити позначений цикл
$query2_result = mysqli_query($conn, $SubjCyclesQuery) 
						or die("Помилка сервера при запиті<br>".$SubjCyclesQuery." : ".mysqli_error($conn));
while ($query2_row = mysqli_fetch_array($query2_result)) {
	// Обробка позначок "Змінити"
	$_POST['sbt'.$query2_row['id']] = isset($_POST['sbt'.$query2_row['id']]) ?
														$_POST['sbt'.$query2_row['id']] : "";
	$_POST['tbxr'.$query2_row['id']] = isset($_POST['tbxr'.$query2_row['id']]) ?
														$_POST['tbxr'.$query2_row['id']] : "";
	$_POST['tbxc'.$query2_row['id']] = isset($_POST['tbxc'.$query2_row['id']]) ?
														$_POST['tbxc'.$query2_row['id']] : "";
//		echo " | id=".$query2_row['id'];
	
	if (!empty($_POST['sbt'.$query2_row['id']]) and
		 !empty($_POST['tbxr'.$query2_row['id']]) and
		 !empty($_POST['tbxc'.$query2_row['id']])) { // обробка кнопки "Зберегти зміни"
		// перевірка, чи вже є такий цикл дисциплін
		$CUQuery = "SELECT * FROM catalogSubjectCycle
						WHERE cycle_name = \"".$_POST['tbxc'.$query2_row['id']]."\" AND id <> ".$query2_row['id'];
		$CUQuery_result = mysqli_query($conn, $CUQuery) or 
					die("<tr><td colspan=5>Помилка сервера при запиті<br>".$CUQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
		$isubjc = mysqli_num_rows($CUQuery_result);
		if ($isubjc == 0) {
			$UpdateCQuery = "UPDATE catalogSubjectCycle
									SET cycle_rank  = \"".$_POST['tbxr'.$query2_row['id']]."\", 
										cycle_name = \"".$_POST['tbxc'.$query2_row['id']]."\" 
									WHERE id = ".$query2_row['id']; // echo " ",$UpdateGQuery;
			$UpdateCQuery_result = mysqli_query($conn, $UpdateCQuery) or 
								die("<tr><td colspan=5>Помилка сервера при запиті<br>".$UpdateCQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
		} else {
?>
<tr style="width: 1300px;"><td colspan=6 style="color: red; font-weight: normal;">
			Цикл дисциплін "<?php echo bold($_POST['tbxc'.$query2_row['id']]); ?>" в довіднику вже є!</td></tr>
<?php
		}
	}
}

?>
	<tr style="width: 1300px;"><th>Код</th><th>Назва циклу дисциплін</th>
		<th style="width: 30px;">Ранг</th><th style="width: 90px;">Кількість<br>у РНП</th>
		<th>Змінити</th><th>До видалення</th></tr>
</thead>
<tbody style="width: 1300px; height: 400px;">
<?php
// Завантажити перелік циклів дисциплін
	$query_result = mysqli_query($conn, $SubjCyclesQuery) or 
			die("Помилка сервера при запиті<br>".$SubjCyclesQuery." : ".mysqli_error($conn));
	$icnt = 0; $icnta = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$PositionsQuery = "SELECT COUNT(id) AS cnt FROM plan_work_subj_study 
											WHERE subj_cycle_id = ".$query_row['id'];
		$PQ_result = mysqli_query($conn, $PositionsQuery) 
								or die("Помилка сервера при запиті<br>".$PositionsQuery." : ".mysqli_error($conn));
		$PQ_row = mysqli_fetch_array($PQ_result); if ($PQ_row['cnt'] == 0) $icnta++;
		$_POST['cbxc'.$query_row['id']] = isset($_POST['cbxc'.$query_row['id']]) ? 
															$_POST['cbxc'.$query_row['id']] : "";
		$_POST['delc'.$query_row['id']] = isset($_POST['delc'.$query_row['id']]) ?
															$_POST['delc'.$query_row['id']] : "";
?>
	<tr style="width: 1300px;">	<td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['cycle_name']; ?></td>                        
		<td style="text-align: center; width: 30px;"><?php echo $query_row['cycle_rank']; ?></td>                        
		<td style="width: 90px;"><?php	echo ($PQ_row['cnt'] == 0) 
							? "<span style=\"color: red; font-size: 80%;\">в РНП немає</span>"	: $PQ_row['cnt']; ?>
		</td>
		<td>
<?php 
		if ($TrueAdmin) echo paramCheker("cbxc".$query_row['id'], $_POST['cbxc'.$query_row['id']], 
																			"Так/Ні", "onchange=\"submit()\"");
		if (!empty($_POST['cbxc'.$query_row['id']])) {
?>
			<div>Назва циклу дисциплін: 
				<input type="textbox" name="tbxc<?php echo $query_row['id']; ?>" 
						style="font-weight: bold; width: 400px;"
						value="<?php echo $query_row['cycle_name']; ?>" /><br>
				Ранг: 
				<input type="textbox" name="tbxr<?php echo $query_row['id']; ?>" 
					style="font-weight: bold; width: 30px;" value="<?php echo $query_row['cycle_rank']; ?>" /> &nbsp; 
				<input type="submit" name="sbt<?php echo $query_row['id']; ?>" value="Зберегти" />
			</div><?php
		} ?>
		</td>
		<td><?php 
		if ($TrueAdmin and ($PQ_row['cnt'] == 0)) {
			echo paramChekerRedInline("delc".$query_row['id'],$_POST['delc'.$query_row['id']],$query_row['id'],"");
		} ?>
		</td>
	</tr>
<?php 
		$icnt++;
	}
?>
</tbody>
<tfoot style="width: 1300px;">
	<tr style="width: 1300px;">
	<td colspan=5 style="text-align: right; width: 1201px;">
		Усього циклів: <?php echo bold($icnt); ?>, з них немає в РНП: <?php echo bold($icnta); ?></td>
	<td style="width: 90px;"><?php
if ($TrueAdmin) { ?>
	<input type="checkbox" id="delc" name="delc" 
							onclick="if (confirm('Дійсно видалити позначені цикли?')) submit();" class="del" />
						<label for="delc" class="del">Видалити</label><?php
} ?>
	</td></tr>
</tfoot>
</table>
