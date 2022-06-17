<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль edu_progs.php</p>"; require "footer.php"; exit(); }
// Перелік спеціальностей для вибору
$SpecialtiesQuery = "SELECT id, CONCAT(specialty_b_code, \" (\",specialty_char_code, \") \", specialty_name) AS specialty_codename 
					 FROM catalogSpecialty ORDER BY specialty_codename";
$DepartmentsQuery = "SELECT id, CONCAT(shufr_kaf, \" - \", nazva_kaf) AS depart_name 
					 FROM catalogDepartment WHERE depart_group_id = 1 ORDER BY nazva_kaf";

?><br><table style="margin-left: 0%; width: 100%;">
	<tr><td colspan=7><?php $_POST['addeprog'] = isset($_POST['addeprog']) ? $_POST['addeprog'] : 0;
if ($TrueAdmin) echo paramCheker("addeprog",$_POST['addeprog'],"Додати нову освітню програму (спеціалізацію)",
								"onchange=\"submit()\""); ?></td></tr>
<?php 
	if (!empty($_POST['addeprog'])) {
		$_POST['sptoadd'] = isset($_POST['sptoadd']) ? $_POST['sptoadd'] : "";
		$_POST['epntoadd'] = isset($_POST['epntoadd']) ? $_POST['epntoadd'] : "";
		$_POST['epctoadd'] = isset($_POST['epctoadd']) ? $_POST['epctoadd'] : "";
		$_POST['deptoadd'] = isset($_POST['deptoadd']) ? $_POST['deptoadd'] : "";
		if (!empty($_POST['sptoadd']) and !empty($_POST['epntoadd']) and !empty($_POST['epctoadd']))
			{ 
			// перевірка, чи вже є така спеціалізація на вибраній спеціальності
			$EPQuery = "SELECT * FROM catalogEduProgram
									WHERE eduprogram_name=\"".$_POST['epntoadd']."\" AND
												specialty_id = \"".$_POST['sptoadd']."\"
								";
			$query_result = mysqli_query($conn, $EPQuery) or 
						die("<tr><td colspan=7>Помилка сервера при запиті<br>".$EPQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$iep = 0; while ($query_row = mysqli_fetch_array($query_result)) $iep++;
			if ($iep == 0) {
				$AddEPQuery = "insert into catalogEduProgram values
										(\"\",\"".$_POST['epctoadd']."\",
										 \"".$_POST['epntoadd']."\", \"".$_POST['sptoadd']."\", \"".$_POST['deptoadd']."\")"; // echo $AddSQuery;
			   $query_result = mysqli_query($conn, $AddEPQuery) or 
							die("<tr><td colspan=7>Помилка сервера при запиті<br>".$AddEPQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
?>
<tr><td colspan=7 style="color: green; font-weight: normal;">
			Освітню програму (спеціалізацію) <? echo bold($_POST['epntoadd']); ?> успішно додано</td></tr>
<?php
			} else {
?>
<tr><td colspan=7 style="color: red; font-weight: normal;">
			Освітня програма (спеціалізація) <? echo bold($_POST['epntoadd']); ?> в довіднику вже є!</td></tr>
<?php
			}
		}
?><tr><td colspan=7>
<?php echo selectCommonSelect
		("Спеціальність (напрям підготовки): ", "sptoadd", $conn, $SpecialtiesQuery, "id", $_POST['sptoadd'], 
			"specialty_codename", "style=\"font-weight: bold;\""); ?><br>
Освітня програма (спеціалізація): 
<input type="text" name="epntoadd" style="font-weight: bold; width: 750px" 
		value="<?php echo $_POST['epntoadd']; ?>" />&nbsp; &nbsp; &nbsp; &nbsp;
Шифр ІФНТУНГ: 
<input type="text" name="epctoadd" style="font-weight: bold; width: 100px;" value="<?php echo $_POST['epctoadd']; ?>" /><br>
<?php echo selectCommonSelect
		("Випускна кафедра: ", "deptoadd", $conn, $DepartmentsQuery, "id", $_POST['deptoadd'], "depart_name", "style=\"font-weight: bold;\""); ?>
&nbsp; &nbsp; &nbsp; &nbsp;
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
	}

// Завантажити перелік спеціальностей і видалити позначені
	$EPQuery = "SELECT * FROM catalogEduProgram ORDER BY id";
	$query1_result = mysqli_query($conn, $EPQuery) or die("Помилка сервера при запиті<br>".$EPQuery." : ".mysqli_error($conn));
	if (!empty($_POST['deleduprog'])) { // Натиснуто кнопку "Видалити"
		while ($query_row = mysqli_fetch_array($query1_result)) { // Обробка позначок "На видалення"
			if (!empty($_POST['delep'.$query_row['id']])) { // обробка позначки "На видалення"
				$DeleteEPQuery = "DELETE FROM catalogEduProgram WHERE id='".$query_row['id']."'";
				$dep_result = mysqli_query($conn, $DeleteEPQuery) or 
					die("Помилка сервера при запиті<br>".$DeleteEPQuery." : ".mysqli_error($conn));
			}
		}
	}
// Завантажити перелік освітніх програм і змінити позначені
	$query2_result = mysqli_query($conn, $EPQuery) or die("Помилка сервера при запиті<br>".$EPQuery." : ".mysqli_error($conn));
	while ($query_row = mysqli_fetch_array($query2_result)) {
		// Обробка позначок "Змінити"
		$_POST['sbtep'.$query_row['id']] = isset($_POST['sbtep'.$query_row['id']]) ?
															$_POST['sbtep'.$query_row['id']] : "";
		$_POST['sp'.$query_row['id']] = isset($_POST['sp'.$query_row['id']]) ?
															$_POST['sp'.$query_row['id']] : "";
		$_POST['epn'.$query_row['id']] = isset($_POST['epn'.$query_row['id']]) ?
															$_POST['epn'.$query_row['id']] : "";
		$_POST['epc'.$query_row['id']] = isset($_POST['epc'.$query_row['id']]) ?
															$_POST['epc'.$query_row['id']] : "";
		$_POST['dep'.$query_row['id']] = isset($_POST['dep'.$query_row['id']]) ?
															$_POST['dep'.$query_row['id']] : "";

		if (!empty($_POST['sbtep'.$query_row['id']]) and
			 !empty($_POST['sp'.$query_row['id']]) and
			 !empty($_POST['epn'.$query_row['id']]) and
			 !empty($_POST['epc'.$query_row['id']]) and
			 !empty($_POST['dep'.$query_row['id']])
			 ) { // обробка кнопки "Зберегти зміни"
			// перевірка, чи вже є така спеціалізація
			$EPQuery = "SELECT * FROM catalogEduProgram 
							WHERE eduprogram_name = \"".$_POST['epn'.$query_row['id']]."\" AND 
									specialty_id = \"".$_POST['sp'.$query_row['id']]."\" AND 
									id <> ".$query_row['id'];
			$EPQuery_result = mysqli_query($conn, $EPQuery) or 
						die("<tr><td colspan=7>Помилка сервера при запиті<br>".$EPQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$isn = 0; while ($Query_row = mysqli_fetch_array($EPQuery_result)) $isn++;
			if ($isn == 0) {
				$UpdateEPQuery = "UPDATE catalogEduProgram SET 
											specialty_id = \"".$_POST['sp'.$query_row['id']]."\" ,
											eduprogram_name = \"".$_POST['epn'.$query_row['id']]."\" ,
					 						eduprogram_code = \"".$_POST['epc'.$query_row['id']]."\" ,
											depart_id = \"".$_POST['dep'.$query_row['id']]."\"  
										WHERE id = ".$query_row['id']; // echo $UpdateEPQuery;
			  $query_result = mysqli_query($conn, $UpdateEPQuery) or 
							die("<tr><td colspan=7>Помилка сервера при запиті<br>".$UpdateEPQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
			} else {
?>
<tr><td colspan=7 style="color: red; font-weight: normal;">
			Освітня програма (спеціалізація) <? echo bold($_POST['epn'.$query_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
			}
		}
	}
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>Спеціальність (напрям)</th>
		<th rowspan=2>Освітня програма (спеціалізація)</th>
		<th rowspan=2>Шифр<br>ІФНТУНГ</th>		<th rowspan=2>Випускна<br>кафедра</th>
		<th colspan=2>Дії з об'єктом</th></tr>
	<tr><th>Змінити назву</th><th>До видалення</th></tr>
<?php
// Завантажити перелік
	$EduProgsQuery = "
				SELECT a.id, b.id as specialty_id, 
						CONCAT(b.specialty_b_code, \" (\",b.specialty_char_code, \") \", b.specialty_name) AS specialty_codename, 
						a.eduprogram_name, a.eduprogram_code, a.depart_id, c.shufr_kaf
				FROM catalogEduProgram a, catalogSpecialty b, catalogDepartment c
				WHERE a.specialty_id = b.id And a.depart_id = c.id
				ORDER BY specialty_codename, a.eduprogram_name
		";
	$query_result = mysqli_query($conn, $EduProgsQuery) or 
			die("Помилка сервера при запиті<br>".$EduProgsQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbxep'.$query_row['id']] = isset($_POST['cbxep'.$query_row['id']]) ? 
															$_POST['cbxep'.$query_row['id']] : "";
		$_POST['delep'.$query_row['id']] = isset($_POST['delep'.$query_row['id']]) ?
															$_POST['delep'.$query_row['id']] : "";
?>
	<tr><td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_name']; ?></td>
		<td style="text-align: center;"><?php echo $query_row['eduprogram_code']; ?></td>
		<td style="text-align: center;"><?php echo $query_row['shufr_kaf']; ?></td>
		<td>
<?php 
		if ($TrueAdmin) echo paramCheker("cbxep".$query_row['id'], $_POST['cbxep'.$query_row['id']], 
																			"Розкрити/Сховати", "onchange=\"submit()\"");
		if (!empty($_POST['cbxep'.$query_row['id']])) {
?>
			<div><?php 
			echo selectCommonSelect
				("Спеціальність (напрям): ", "sp".$query_row['id'], $conn, $SpecialtiesQuery, "id", $query_row['specialty_id'], 
					"specialty_codename", "style=\"font-weight: bold;\""); ?><br>
				Освітня програма (спеціалізація): 
				<input type="text" name="epn<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['eduprogram_name']; ?>" style="font-weight: bold; width: 450px;"/><br>
				Шифр ІФНТУНГ: 
				<input type="text" name="epc<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['eduprogram_code']; ?>" style="width: 100px;" /><br>
			<?php echo selectCommonSelect
				("Випускна кафедра: ", "dep".$query_row['id'], $conn, $DepartmentsQuery, "id", $query_row['depart_id'], 
					"depart_name", "style=\"font-weight: bold;\""); ?><br>
				<input type="submit" name="sbtep<?php echo $query_row['id']; ?>" value="Зберегти" />
			</div>
<?php
		}
?>
		</td>
		<td><?php 
		if ($TrueAdmin) echo paramCheker("delep".$query_row['id'],$_POST['delep'.$query_row['id']],"",""); ?></td>
	</tr>
<?php 
		$icnt++;
	}
?><tr><td colspan=6 style="text-align: right;">Усього: <?php echo bold($icnt); ?></td>
			<td><?php
if ($TrueAdmin) { ?>
		<input type="checkbox" id="deleduprog" name="deleduprog" 
								onclick="if (confirm('Дійсно видалити позначені освітні програми?')) submit();" class="del" />
						<label for="deleduprog" class="del">Видалити</label><?php
} ?>	</td></tr>
</table>