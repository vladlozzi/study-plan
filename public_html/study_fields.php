<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль study_fields.php</p>"; require "footer.php"; exit(); }
$FSQuery = "SELECT * FROM catalogFieldStudy ORDER BY list DESC, field_code ASC";
?><br>
<table style="margin-left: 0%; width: 70%;">
	<tr><td colspan=6><?php $_POST['addfs'] = isset($_POST['addfs']) ? $_POST['addfs'] : 0;
		echo paramCheker("addfs",$_POST['addfs'],"Додати нову галузь знань",
								"onchange=\"submit()\""); ?></td></tr>
<?php
if (!empty($_POST['addfs'])) {
	$_POST['fstoadd'] = isset($_POST['fstoadd']) ? $_POST['fstoadd'] : "";
	$_POST['fsctoadd'] = isset($_POST['fsctoadd']) ? $_POST['fsctoadd'] : "";
	$_POST['lsttoadd'] = isset($_POST['lsttoadd']) ? $_POST['lsttoadd'] : "";
	if (!empty($_POST['fstoadd']) and !empty($_POST['fsctoadd']) and !empty($_POST['lsttoadd'])) { 
		// перевірка, чи вже є така
		$IsFSQuery = "SELECT * FROM catalogFieldStudy WHERE field_name=\"".$_POST['fstoadd']."\" AND 
																			 list=\"".$_POST['lsttoadd']."\"";
		$query_result = mysqli_query($conn, $IsFSQuery) or 
					die("<tr><td colspan=6>Помилка сервера при запиті<br>".$IsFSQuery.
						" : ".mysqli_error($conn)."</td></tr></table>");
		$ifn = 0; while ($query_row = mysqli_fetch_array($query_result)) $ifn++;
		$IsFSQuery = "SELECT * FROM catalogFieldStudy WHERE field_code=\"".$_POST['fsctoadd']."\"";
		$query_result = mysqli_query($conn, $IsFSQuery) or 
					die("<tr><td colspan=6>Помилка сервера при запиті<br>".$IsFSQuery.
						" : ".mysqli_error($conn)."</td></tr></table>");
      $ifc = 0; while ($query_row = mysqli_fetch_array($query_result)) $ifc++;
		if (($ifn == 0) and ($ifc == 0)) {
			$AddFSQuery = "insert into catalogFieldStudy values
									(\"\",\"".$_POST['fsctoadd'].
									"\",\"".$_POST['fstoadd'].
									"\",\"".$_POST['lsttoadd'].
									"\")"; // echo $AddFSQuery;
		   $query_result = mysqli_query($conn, $AddFSQuery) or 
						die("<tr><td colspan=6>Помилка сервера при запиті<br>".$AddFQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
		} else {
?>
<tr><td colspan=6 style="color: red; font-weight: normal;">
			Галузь знань із назвою <? echo bold($_POST['fstoadd']); ?>, 
			шифром <? echo bold($_POST['fsctoadd']); ?> в довіднику вже є!</td></tr>
<?php
		}
	}
?><tr><td colspan=6>
Назва галузі знань: <input type="text" name="fstoadd" style="font-weight: bold; width: 300px;" 
													value="<?php echo $_POST['fstoadd']; ?>" /> &nbsp; 
Шифр: <input type="text" name="fsctoadd" style="font-weight: bold; width: 50px;" 
			value="<?php echo $_POST['fsctoadd']; ?>" /> &nbsp; 
Перелік: <input type="text" name="lsttoadd" style="font-weight: bold; width: 50px;" 
			value="<?php echo $_POST['lsttoadd']; ?>" /> &nbsp; 
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
}
// Завантажити перелік
$query_result = mysqli_query($conn, $FSQuery) or 
			die("Помилка сервера при запиті<br>".$FSQuery." : ".mysqli_error($conn));
if (!empty($_POST['delfs'])) { // Натиснуто кнопку "Видалити"
	while ($query_row = mysqli_fetch_array($query_result)) { // Обробка позначок "На видалення"
		if (!empty($_POST['delfs'.$query_row['id']])) { // обробка позначки "На видалення"
			$DeleteFSQuery = "DELETE FROM catalogFieldStudy WHERE id='".$query_row['id']."'";
			$df_result = mysqli_query($conn, $DeleteFSQuery) or 
				die("Помилка сервера при запиті<br>".$DeleteFSQuery." : ".mysqli_error($conn));
		}
	}
}
$query_result = mysqli_query($conn, $FSQuery) or die("Помилка сервера при запиті<br>".$FSQuery." : ".mysqli_error($conn));
while ($query_row = mysqli_fetch_array($query_result)) {
	// Обробка позначок "Змінити"
	$_POST['tbxfs'.$query_row['id']] = isset($_POST['tbxfs'.$query_row['id']]) ?
														$_POST['tbxfs'.$query_row['id']] : "";
	$_POST['tbxfsc'.$query_row['id']] = isset($_POST['tbxfsc'.$query_row['id']]) ?
														$_POST['tbxfsc'.$query_row['id']] : "";
	$_POST['tbxlst'.$query_row['id']] = isset($_POST['tbxlst'.$query_row['id']]) ?
														$_POST['tbxlst'.$query_row['id']] : "";
	if (!empty($_POST['tbxfs'.$query_row['id']]) and
		 !empty($_POST['tbxfsc'.$query_row['id']])) { // обробка позначки "Змінити"
		// перевірка, чи вже є така
		$fq1 = "SELECT * FROM catalogFieldStudy 
						WHERE field_name=\"".$_POST['tbxfs'.$query_row['id']]."\" AND 
								list=\"".$_POST['tbxlst'.$query_row['id']]."\" AND
								id <> '".$query_row['id']."'";
		$fq1_result = mysqli_query($conn, $fq1) or 
					die("<tr><td colspan=5>Помилка сервера при запиті<br>".$fq1.
						" : ".mysqli_error($conn)."</td></tr></table>");
      $ifn = 0; while ($fq1_row = mysqli_fetch_array($fq1_result)) $ifn++;
		$fq2 = "SELECT * FROM catalogFieldStudy 
					WHERE field_code=\"".$_POST['tbxfsc'.$query_row['id']]."\" AND 
							id <> '".$query_row['id']."'";
		$fq2_result = mysqli_query($conn, $fq2) or 
					die("<tr><td colspan=5>Помилка сервера при запиті<br>".$fq2.
						" : ".mysqli_error($conn)."</td></tr></table>");
      $ifc = 0; while ($fq2_row = mysqli_fetch_array($fq2_result)) $ifc++;
		if (($ifn == 0) and ($ifc == 0)) {
			$UpdateFSQuery = "UPDATE catalogFieldStudy 
					SET field_name = \"".$_POST['tbxfs'.$query_row['id']]."\",
						field_code = \"".$_POST['tbxfsc'.$query_row['id']]."\",
						list = \"".$_POST['tbxlst'.$query_row['id']]."\"
										WHERE id='".$query_row['id']."'";
			$uf_result = mysqli_query($conn, $UpdateFSQuery) or 
					die("<tr><td colspan=5>Помилка сервера при запиті<br>".$UpdateFSQuery.
						" : ".mysqli_error($conn)."</td></tr></table>");
		} else {
?>
<tr><td colspan=6 style="color: red; font-weight: normal;">
			Галузь знань із назвою <? echo bold($_POST['tbxfs'.$query_row['id']]); ?>, 
			шифром <? echo bold($_POST['tbxfsc'.$query_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
		}
	}
}
//	mysql_free_result($query_result);
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>Назва галузі знань</th>
		<th rowspan=2>Шифр</th><th rowspan=2>Перелік</th><th colspan=2>Дії з об'єктом</th></tr>
	<tr><th>Змінити назву</th><th>До видалення</th></tr>
<?php
// Завантажити перелік
$query_result = mysqli_query($conn, $FSQuery) or 
				die("Помилка сервера при запиті<br>".$FSQuery." : ".mysqli_error($conn));
$icnt = 0;
while ($query_row = mysqli_fetch_array($query_result)) { 
	$_POST['cbx'.$query_row['id']] = isset($_POST['cbx'.$query_row['id']]) ? 
															$_POST['cbx'.$query_row['id']] : "";
	$_POST['delfs'.$query_row['id']] = isset($_POST['delfs'.$query_row['id']]) ?
															$_POST['delfs'.$query_row['id']] : "";
?>
	<tr>		<td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['field_name']; ?></td>
		<td><?php echo $query_row['field_code']; ?></td>                        
		<td><?php echo $query_row['list']; ?></td>                        
		<td><input type="checkbox" id="сbx<?php echo $query_row['id']; ?>" 
						name="cbx<?php echo $query_row['id']; ?>" class="del" />
			<label for="сbx<?php echo $query_row['id']; ?>" class="del">Розкрити</label>
			<div> Назва: 
				<input type="textbox" name="tbxfs<?php echo $query_row['id']; ?>" 
						ondblclick="submit()" style="font-weight: bold;"
						value="<?php echo $query_row['field_name']; ?>" /><br>
				Шифр: 	
				<input type="textbox" name="tbxfsc<?php echo $query_row['id']; ?>" 
						style="font-weight: bold;"
						value="<?php echo $query_row['field_code']; ?>" /><br>
				Перелік: 	
				<input type="textbox" name="tbxlst<?php echo $query_row['id']; ?>" 
						style="font-weight: bold;"
						value="<?php echo $query_row['list']; ?>" /><br>
				<input type="submit" name="sbt" value="Зберегти" />			
			</div>
		</td>
		<td><?php echo paramCheker("delfs".$query_row['id'],$_POST['delfs'.$query_row['id']],"",""); ?></td>
	</tr>
<?php 
	$icnt++;
}
if ($TrueAdmin) { ?>
		<tr><td colspan=5>Усього: <?php echo bold($icnt); ?></td>
		<td><input type="checkbox" id="delfs" name="delfs" 
								onclick="if (confirm('Дійсно видалити позначені галузі знань?')) submit();" class="del" />
						<label for="delfs" class="del">Видалити</label></td></tr>
<?php
} ?>
</table>
