<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль specs.php</p>"; require "footer.php"; exit(); }
// Перелік галузей знань для вибору
$FieldsQuery = "SELECT id, CONCAT(field_code, \" \",field_name, \" (Перелік \", list, \")\") AS field_codename 
					 FROM catalogFieldStudy ORDER BY field_codename";
?><br><table style="margin-left: 0%; width: 100%;">
	<tr><td colspan=9><?php $_POST['addspec'] = isset($_POST['addspec']) ? $_POST['addspec'] : 0;
if ($TrueAdmin) echo paramCheker("addspec",$_POST['addspec'],"Додати нову спеціальність",
								"onchange=\"submit()\""); ?></td></tr>
<?php 
	if (!empty($_POST['addspec'])) {
		$_POST['fstoadd'] = isset($_POST['fstoadd']) ? $_POST['fstoadd'] : "";
		$_POST['sntoadd'] = isset($_POST['sntoadd']) ? $_POST['sntoadd'] : "";
		$_POST['bctoadd'] = isset($_POST['bctoadd']) ? $_POST['bctoadd'] : "";
		$_POST['mctoadd'] = isset($_POST['mctoadd']) ? $_POST['mctoadd'] : "";
		$_POST['uctoadd'] = isset($_POST['uctoadd']) ? $_POST['uctoadd'] : "";
		$_POST['lytoadd'] = isset($_POST['lytoadd']) ? $_POST['lytoadd'] : "";
		$_POST['nstoadd'] = isset($_POST['nstoadd']) ? $_POST['nstoadd'] : "";
		
		if (!empty($_POST['fstoadd']) and !empty($_POST['sntoadd']) and !empty($_POST['bctoadd']) and 
				!empty($_POST['mctoadd']) and !empty($_POST['uctoadd']) and !empty($_POST['lytoadd'])	and 
				!empty($_POST['nstoadd'])
			) { 
			// перевірка, чи вже є такі спеціальність і рік Переліку
			$SQuery = "SELECT * FROM catalogSpecialty 
							WHERE specialty_name=\"".$_POST['sntoadd']."\" AND
									list=\"".$_POST['lytoadd']."\"
						 ";
			$query_result = mysqli_query($conn, $SQuery) or 
						die("<tr><td colspan=9>Помилка сервера при запиті<br>".$SQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$isl = 0; while ($query_row = mysqli_fetch_array($query_result)) $isl++;
			if ($isl == 0) {
				$AddSQuery = "insert into catalogSpecialty values
										(\"\",\"".$_POST['fstoadd']."\",
										 \"".$_POST['sntoadd']."\", \"".$_POST['bctoadd']."\",
										 \"".$_POST['mctoadd']."\", \"".$_POST['uctoadd']."\",
										 \"".$_POST['lytoadd']."\", \"".$_POST['nstoadd']."\"
										)"; // echo $AddSQuery;
			   $query_result = mysqli_query($conn, $AddSQuery) or 
							die("<tr><td colspan=9>Помилка сервера при запиті<br>".$AddSQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
?>
<tr><td colspan=9 style="color: green; font-weight: normal;">
			Спеціальність (напрям підготовки) <? echo bold($_POST['sntoadd']); ?> успішно додано</td></tr>
<?php
			} else {
?>
<tr><td colspan=9 style="color: red; font-weight: normal;">
			Спеціальність (напрям підготовки) <? echo bold($_POST['sntoadd']); ?> 
			за Переліком <? echo bold($_POST['lytoadd']); ?> в довіднику вже є!</td></tr>
<?php
			}
		}
?><tr><td colspan=9>
<?php echo selectCommonSelect
		("Галузь знань: ", "fstoadd", $conn, $FieldsQuery, "id", $_POST['fstoadd'], 
			"field_codename", "style=\"font-weight: bold;\""); ?><br>
Назва спеціальності (напряму підготовки): 
<input type="text" name="sntoadd" style="font-weight: bold; width: 700px" 
		value="<?php echo $_POST['sntoadd']; ?>" /><br>
Код спеціальності або напряму: 
<input type="text" name="bctoadd" style="font-weight: bold; width: 80px;" value="<?php echo $_POST['bctoadd']; ?>" />
&nbsp; &nbsp; Код магістра: 
<input type="text" name="mctoadd" style="font-weight: bold; width: 100px;" value="<?php echo $_POST['mctoadd']; ?>" />
&nbsp; &nbsp; Шифр ІФНТУНГ:
<input type="text" name="uctoadd" style="font-weight: bold; width: 50px;" value="<?php echo $_POST['uctoadd']; ?>" />
&nbsp; &nbsp; Рік Переліку: 
<input type="text" name="lytoadd" style="font-weight: bold; width: 80px;" value="<?php echo $_POST['lytoadd']; ?>" />
<br>Нормативна кількість студентів на 1 ставку ПВС (за пост. КМУ 1134-2002): 
<input type="text" name="nstoadd" style="font-weight: bold; width: 80px;" value="<?php echo $_POST['nstoadd']; ?>" />
&nbsp; &nbsp; &nbsp; &nbsp;
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
	}

// Завантажити перелік спеціальностей і видалити позначені
	$SQuery = "SELECT * FROM catalogSpecialty ORDER BY id";
	$query1_result = mysqli_query($conn, $SQuery) or die("Помилка сервера при запиті<br>".$SQuery." : ".mysqli_error($conn));
	if (!empty($_POST['delspec'])) { // Натиснуто кнопку "Видалити"
		while ($query_row = mysqli_fetch_array($query1_result)) { // Обробка позначок "На видалення"
			if (!empty($_POST['delspec'.$query_row['id']])) { // обробка позначки "На видалення"
				$DeleteSQuery = "DELETE FROM catalogSpecialty WHERE id='".$query_row['id']."'";
				$dT_result = mysqli_query($conn, $DeleteSQuery) or 
					die("Помилка сервера при запиті<br>".$DeleteSQuery." : ".mysqli_error($conn));
			}
		}
	}
// Завантажити перелік спеціальностей і змінити позначені
	$query2_result = mysqli_query($conn, $SQuery) or die("Помилка сервера при запиті<br>".$SQuery." : ".mysqli_error($conn));
	while ($query_row = mysqli_fetch_array($query2_result)) {
		// Обробка позначок "Змінити"
		$_POST['sbts'.$query_row['id']] = isset($_POST['sbts'.$query_row['id']]) ?
															$_POST['sbts'.$query_row['id']] : "";
		$_POST['fs'.$query_row['id']] = isset($_POST['fs'.$query_row['id']]) ?
															$_POST['fs'.$query_row['id']] : "";
		$_POST['sn'.$query_row['id']] = isset($_POST['sn'.$query_row['id']]) ?
															$_POST['sn'.$query_row['id']] : "";
		$_POST['bc'.$query_row['id']] = isset($_POST['bc'.$query_row['id']]) ?
															$_POST['bc'.$query_row['id']] : "";
		$_POST['mc'.$query_row['id']] = isset($_POST['mc'.$query_row['id']]) ?
															$_POST['mc'.$query_row['id']] : "";
		$_POST['uc'.$query_row['id']] = isset($_POST['uc'.$query_row['id']]) ?
															$_POST['uc'.$query_row['id']] : "";
		$_POST['ly'.$query_row['id']] = isset($_POST['ly'.$query_row['id']]) ?
															$_POST['ly'.$query_row['id']] : "";
		$_POST['ns'.$query_row['id']] = isset($_POST['ns'.$query_row['id']]) ?
															$_POST['ns'.$query_row['id']] : "";
		if (!empty($_POST['sbts'.$query_row['id']]) and !empty($_POST['fs'.$query_row['id']]) and
			 !empty($_POST['sn'.$query_row['id']]) and !empty($_POST['bc'.$query_row['id']]) and
			 !empty($_POST['mc'.$query_row['id']]) and !empty($_POST['uc'.$query_row['id']]) and
			 !empty($_POST['ly'.$query_row['id']]) and !empty($_POST['uc'.$query_row['id']]) ) { // обробка кнопки "Зберегти зміни"
			// перевірка, чи вже є така спеціальність
			$LQuery = "SELECT * FROM catalogSpecialty 
							WHERE specialty_name = \"".$_POST['sn'.$query_row['id']]."\" AND 
									list = \"".$_POST['ly'.$query_row['id']]."\" AND 
									id <> ".$query_row['id'];
			$LQuery_result = mysqli_query($conn, $LQuery) or 
						die("<tr><td colspan=9>Помилка сервера при запиті<br>".$LQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$isn = 0; while ($LQuery_row = mysqli_fetch_array($LQuery_result)) $isn++;
			if ($isn == 0) {
				$UpdateTQuery = "UPDATE catalogSpecialty SET 
											fieldstudy_id = \"".$_POST['fs'.$query_row['id']]."\" ,
											specialty_name = \"".$_POST['sn'.$query_row['id']]."\" ,
					 						specialty_b_code = \"".$_POST['bc'.$query_row['id']]."\" ,
											specialty_m_code = \"".$_POST['mc'.$query_row['id']]."\" ,
											specialty_char_code = \"".$_POST['uc'.$query_row['id']]."\" ,
											list = \"".$_POST['ly'.$query_row['id']]."\" ,
											norm_st = \"".$_POST['ns'.$query_row['id']]."\" 
										WHERE id = ".$query_row['id']; // echo $UpdateTQuery;
			   $query_result = mysqli_query($conn, $UpdateTQuery) or 
							die("<tr><td colspan=9>Помилка сервера при запиті<br>".$UpdateTQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
			} else {
?>
<tr><td colspan=9 style="color: red; font-weight: normal;">
			Спеціальність <? echo bold($_POST['sn'.$query_row['id']]); ?> 
			за Переліком <? echo bold($_POST['ly'.$query_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
			}
		}
	}
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>Галузь знань</th>
		<th rowspan=2>Назва спеціальності (напряму підготовки)</th>
		<th rowspan=2>Код<br>спеціальності<br>(напряму)</th>
		<th rowspan=2>Рік<br>Переліку</th><th rowspan=2>Шифр<br>ІФНТУНГ</th>
		<th rowspan=2>Нормативна кількість<br>студентів<br>на ставку ПВС</th>
		<th colspan=2>Дії з об'єктом</th></tr>
	<tr><th>Змінити назву</th><th>До видалення</th></tr>
<?php
// Завантажити перелік
	$SpecsQuery = "
				SELECT a.id, b.id as fieldstudy_id, 
						CONCAT(b.field_code, \" \", b.field_name, \" (Перелік \", b.list, \")\") AS field_codename, 
						a.specialty_name, a.specialty_b_code, a.specialty_m_code, 
						a.specialty_char_code, a.list, a.norm_st
				FROM catalogSpecialty a, catalogFieldStudy b 
				WHERE a.fieldstudy_id = b.id 
				ORDER BY field_codename, a.specialty_name
		";
	$query_result = mysqli_query($conn, $SpecsQuery) or 
			die("Помилка сервера при запиті<br>".$SpecsQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbxs'.$query_row['id']] = isset($_POST['cbxs'.$query_row['id']]) ? 
															$_POST['cbxs'.$query_row['id']] : "";
		$_POST['delspec'.$query_row['id']] = isset($_POST['delspec'.$query_row['id']]) ?
															$_POST['delspec'.$query_row['id']] : "";
?>
	<tr><td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['field_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['specialty_name']; ?></td>
		<td style="text-align: center;"><?php echo $query_row['specialty_b_code']; ?></td>
		<td style="text-align: center;"><?php echo $query_row['list']; ?></td>
		<td style="text-align: center;"><?php echo $query_row['specialty_char_code']; ?></td>
		<td style="text-align: center;"><?php echo $query_row['norm_st']; ?></td>
		<td>
<?php 
		if ($TrueAdmin) echo paramCheker("cbxs".$query_row['id'], $_POST['cbxs'.$query_row['id']], 
																			"Розкрити/Сховати", "onchange=\"submit()\"");
		if (!empty($_POST['cbxs'.$query_row['id']])) {
?>
			<div><?php 
			echo selectCommonSelect
			("Галузь знань: ", "fs".$query_row['id'], $conn, $FieldsQuery, "id", 
				$query_row['fieldstudy_id'], "field_codename", ""); ?><br>
				Спеціальність (напрям): 
				<input type="text" name="sn<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['specialty_name']; ?>" style="font-weight: bold; width: 450px;"/><br>
				Код спеціальності або напряму: 
				<input type="text" name="bc<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['specialty_b_code']; ?>" style="width: 80px;" />
				&nbsp; &nbsp;
				Код магістра: 
				<input type="text" name="mc<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['specialty_m_code']; ?>" style="width: 80px;" /><br> 
				Шифр ІФНТУНГ: 
				<input type="text" name="uc<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['specialty_char_code']; ?>" style="width: 40px;" />
				&nbsp; &nbsp;
				Рік Переліку: 
				<input type="text" name="ly<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['list']; ?>" style="width: 80px;" /><br>
				Нормативна кількість студентів на 1 ставку ПВС (за пост. КМУ 1134-2002): 
				<input type="text" name="ns<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['norm_st']; ?>" style="width: 40px;" /><br>
				<input type="submit" name="sbts<?php echo $query_row['id']; ?>" value="Зберегти" />
			</div>
<?php
		}
?>
		</td>
		<td><?php 
		if ($TrueAdmin) 
			echo paramCheker("delspec".$query_row['id'],$_POST['delspec'.$query_row['id']],"",""); ?></td>
	</tr>
<?php 
		$icnt++;
	}
?><tr><td colspan=8 style="text-align: right;">Усього: <?php echo bold($icnt); ?></td>
		<td><?php	
if ($TrueAdmin) { ?>
		  <input type="checkbox" id="delspec" name="delspec" 
								onclick="if (confirm('Дійсно видалити позначені спеціальності?')) submit();" class="del" />
						<label for="delspec" class="del">Видалити</label><?php
} ?>
		</td></tr>
</table>
