<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль departs.php</p>"; require "footer.php"; exit(); }
$FacultiesQuery = "SELECT * FROM catalogFakultet ORDER BY fakultet_name";
$DepartGroupsQuery = "SELECT * FROM catalogDepartGroup ORDER BY id";?><br>
<table style="margin-left: 0%; width: 100%;">
	<tr><td colspan=15><?php $_POST['addd'] = isset($_POST['addd']) ? $_POST['addd'] : 0;
if ($TrueAdmin) 
	echo paramCheker("addd", $_POST['addd'], "Додати нову кафедру", "onchange=\"submit()\""); ?></td></tr>
<?php 
if (!empty($_POST['addd'])) {
	$_POST['ftoadd'] = isset($_POST['ftoadd']) ? $_POST['ftoadd'] : "";
	$_POST['dtoadd'] = isset($_POST['dtoadd']) ? $_POST['dtoadd'] : "";
	$_POST['dctoadd'] = isset($_POST['dctoadd']) ? $_POST['dctoadd'] : "";
	$_POST['dgtoadd'] = isset($_POST['dgtoadd']) ? $_POST['dgtoadd'] : 0;
	$_POST['tstoadd'] = isset($_POST['tstoadd']) ? $_POST['tstoadd'] : 0;
	$_POST['phtoadd'] = isset($_POST['phtoadd']) ? $_POST['phtoadd'] : "";
	if (!empty($_POST['ftoadd']) and !empty($_POST['dtoadd']) and !empty($_POST['dctoadd']) and !empty($_POST['dgtoadd'])) { 
	// перевірка, чи вже є така кафедра
		$DQuery = "SELECT * FROM catalogDepartment 
						WHERE nazva_kaf = \"".$_POST['dtoadd']."\" OR
								shufr_kaf = \"".$_POST['dctoadd']."\"
					 ";
		$query_result = mysqli_query($conn, $DQuery) or 
					die("<tr><td colspan=9>Помилка сервера при запиті<br>".$DQuery.
						" : ".mysqli_error($conn)."</td></tr></table>");
		$idp = 0; while ($query_row = mysqli_fetch_array($query_result)) $idp++;
		if ($idp == 0) {
			$AddDQuery = "insert into catalogDepartment values
									(\"\",\"".$_POST['dctoadd']."\",\"".$_POST['dtoadd']."\", \"".$_POST['tstoadd']."\", 
										\"\", \"\", \"\", \"".$_POST['ftoadd']."\", \"\", \"".$_POST['dgtoadd']."\", 
										\"".$_POST['phtoadd']."\"
									)"; // echo $AddDQuery;
		   $query_result = mysqli_query($conn, $AddDQuery) or 
						die("<tr><td colspan=9>Помилка сервера при запиті<br>".$AddDQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
 		} else {

?>
<tr><td colspan=15 style="color: red; font-weight: normal;">
			Кафедра з назвою <?php echo bold($_POST['dtoadd']); ?>, 
			шифром <?php echo bold($_POST['dctoadd']); ?> в довіднику вже є!</td></tr>
<?php
		}
	}
?><tr><td colspan=15><?php
	echo selectCommonSelect
		("До інституту (факультету): ", "ftoadd", $conn, $FacultiesQuery, "id", 
			$_POST['ftoadd'], "fakultet_name", "style=\"font-weight: bold;\""); ?><br>
Назва нової кафедри: <input type="text" name="dtoadd" style="font-weight: bold; width: 400px;" 
													value="<?php echo $_POST['dtoadd']; ?>" /> &nbsp; &nbsp; 
Шифр: <input type="text" name="dctoadd" style="font-weight: bold; width: 100px;" 
			value="<?php echo $_POST['dctoadd']; ?>" /> &nbsp; &nbsp; 
<?php
	echo selectCommonSelect
		("Група кафедр: ", "dgtoadd", $conn, $DepartGroupsQuery, "id", 
			$_POST['dgtoadd'], "depart_group_name", "style=\"font-weight: bold;\""); ?><br>
Кількість ставок: <input type="text" name="tstoadd" style="font-weight: bold; width: 50px;" 
			value="<?php echo $_POST['tstoadd']; ?>" /> &nbsp; &nbsp; 
Номери телефонів: <input type="text" name="phtoadd" style="font-weight: bold; width: 160px;" 
			value="<?php echo $_POST['phtoadd']; ?>" /> &nbsp; &nbsp; 
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
}
// Завантажити перелік кафедр і видалити позначені
$DQuery = "SELECT * FROM catalogDepartment ORDER BY id";
$query_result = mysqli_query($conn, $DQuery) or 
		die("Помилка сервера при запиті<br>".$DQuery." : ".mysqli_error($conn));
if (!empty($_POST['deldep'])) { // Натиснуто кнопку "Видалити"
	while ($query_row = mysqli_fetch_array($query_result)) { // Обробка позначок "На видалення"
		if (!empty($_POST['deld'.$query_row['id']])) { // обробка позначки "На видалення"
			$DeleteDQuery = "DELETE FROM catalogDepartment WHERE id='".$query_row['id']."'";
			$df_result = mysqli_query($conn, $DeleteDQuery) or 
				die("Помилка сервера при запиті<br>".$DeleteDQuery." : ".mysqli_error($conn));
		}
	}
}

// Завантажити перелік кафедр і змінити позначені кафедри
$query2_result = mysqli_query($conn, $DQuery) or die("Помилка сервера при запиті<br>".$DQuery." : ".mysqli_error($conn));
while ($query_row = mysqli_fetch_array($query2_result)) {
	// Обробка позначок "Змінити"
	$_POST['sbtd'.$query_row['id']] = isset($_POST['sbtd'.$query_row['id']]) ?
														$_POST['sbtd'.$query_row['id']] : "";
	$_POST['facd'.$query_row['id']] = isset($_POST['facd'.$query_row['id']]) ?
														$_POST['facd'.$query_row['id']] : "";
	$_POST['facdr'.$query_row['id']] = isset($_POST['facdr'.$query_row['id']]) ?
														$_POST['facdr'.$query_row['id']] : "";
	$_POST['tbxdn'.$query_row['id']] = isset($_POST['tbxdn'.$query_row['id']]) ?
														$_POST['tbxdn'.$query_row['id']] : "";
	$_POST['tbxdc'.$query_row['id']] = isset($_POST['tbxdc'.$query_row['id']]) ?
														$_POST['tbxdc'.$query_row['id']] : "";
	$_POST['tbxts'.$query_row['id']] = isset($_POST['tbxts'.$query_row['id']]) ?
														$_POST['tbxts'.$query_row['id']] : 0;
	$_POST['tbxps'.$query_row['id']] = isset($_POST['tbxps'.$query_row['id']]) ?
														$_POST['tbxps'.$query_row['id']] : 0;
	$_POST['tbxap'.$query_row['id']] = isset($_POST['tbxap'.$query_row['id']]) ?
														$_POST['tbxap'.$query_row['id']] : 0;
	$_POST['tbxoh'.$query_row['id']] = isset($_POST['tbxoh'.$query_row['id']]) ?
														$_POST['tbxoh'.$query_row['id']] : 0;
	$_POST['tbxph'.$query_row['id']] = isset($_POST['tbxph'.$query_row['id']]) ?
														$_POST['tbxph'.$query_row['id']] : "";
	if (!empty($_POST['sbtd'.$query_row['id']]) and
		 !empty($_POST['facd'.$query_row['id']]) and
		 !empty($_POST['tbxdn'.$query_row['id']]) and
		 !empty($_POST['tbxdc'.$query_row['id']])) { // обробка кнопки "Зберегти зміни"
		// перевірка, чи вже є така кафедра
		$DUQuery = "SELECT * FROM catalogDepartment
						WHERE (nazva_kaf = \"".$_POST['tbxdn'.$query_row['id']]."\" OR
								shufr_kaf = \"".$_POST['tbxdc'.$query_row['id']]."\") AND id <> ".$query_row['id'];
		$DUQuery_result = mysqli_query($conn, $DUQuery) or 
					die("<tr><td colspan=9>Помилка сервера при запиті<br>".$DUQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
		$idep = 0; while ($DUQuery_row = mysqli_fetch_array($DUQuery_result)) $idep++;
		if ($idep == 0) {
			$UpdateDQuery = "UPDATE catalogDepartment
									SET fakultet_id = \"".$_POST['facd'.$query_row['id']]."\", 
										faculty_id_for_rate = \"".$_POST['facdr'.$query_row['id']]."\", 
										nazva_kaf = \"".$_POST['tbxdn'.$query_row['id']]."\", 
										shufr_kaf = \"".$_POST['tbxdc'.$query_row['id']]."\", 
										teachers_salaries = \"".$_POST['tbxts'.$query_row['id']]."\", 
										postgrads = \"".$_POST['tbxps'.$query_row['id']]."\", 
										applicants = \"".$_POST['tbxap'.$query_row['id']]."\", 
										other_hours = \"".$_POST['tbxoh'.$query_row['id']]."\", 
										depart_group_id = \"".$_POST['dg'.$query_row['id']]."\", 
										phone = \"".$_POST['tbxph'.$query_row['id']]."\"
									WHERE id = ".$query_row['id']; // echo $UpdateDQuery;
			$UpdateDQuery_result = mysqli_query($conn, $UpdateDQuery) or 
								die("<tr><td colspan=9>Помилка сервера при запиті<br>".$UpdateDQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
		} else {
?>
<tr><td colspan=15 style="color: red; font-weight: normal;">
			Кафедра з назвою <?php echo bold($_POST['tbxdn'.$query_row['id']]); ?>, 
			шифром <?php echo bold($_POST['tbxdc'.$query_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
		}
	}
}
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>Шифр інституту</th>
		<th rowspan=2>Шифр кафедри</th><th rowspan=2>Повна назва кафедри (телефони)</th>
		<th colspan=7>Кількість</th>
		<th rowspan=2>Група<br>кафедр</th><th colspan=2>Дії з об''єктом</th></tr>
	<tr><th>викладачів</th><th>ставок</th><th>аспі-<br>рантів</th>
			<th>здобу-<br>вачів</th><th>інших<br>годин</th><th>дисци-<br>плін</th>
			<th>позицій<br>у РНП</th>			
			<th>Редагування</th><th>До видалення</th></tr><?php
// Завантажити перелік кафедр
	$DepQuery = "SELECT a.*, b.fakultet_shufr
				FROM catalogDepartment a, catalogFakultet b
				WHERE a.fakultet_id = b.id 
				ORDER BY b.fakultet_shufr, a.nazva_kaf";
	$query_result = mysqli_query($conn, $DepQuery) or 
			die("Помилка сервера при запиті<br>".$DepQuery." : ".mysqli_error($conn));
	$icnt = 0; $tcnt = 0; $scnt = 0; $pgcnt = 0; $appcnt = 0; $ohcnt = 0; $subjcnt = 0; $prcercnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) {
		$TeachersD_query = "SELECT * FROM catalogTeacher WHERE role = 2 AND 
																			 kaf_link = ".$query_row['id'];
		$TeachersD_result = mysqli_query($conn, $TeachersD_query) or 
								die("<tr><td colspan=15>Помилка сервера при запиті<br>".$TeachersD_query.
								" : ".mysqli_error($conn)."</td></tr></table>");
		$TeachersD_count = mysqli_num_rows($TeachersD_result);
		$SubjectsD_query = "SELECT * FROM catalogSubject WHERE shufr_kaf = ".$query_row['id'];
		$SubjectsD_result = mysqli_query($conn, $SubjectsD_query) or 
								die("<tr><td colspan=15>Помилка сервера при запиті<br>".$SubjectsD_query.
								" : ".mysqli_error($conn)."</td></tr></table>");
		$SubjectsD_count = mysqli_num_rows($SubjectsD_result);
		$PractiquesD_query = "SELECT * FROM plan_work_practicals WHERE depart_id = ".$query_row['id'];
		$PractiquesD_result = mysqli_query($conn, $PractiquesD_query) or 
								die("<tr><td colspan=15>Помилка сервера при запиті<br>".$Practiques_query.
								" : ".mysqli_error($conn)."</td></tr></table>");
		$PractiquesD_count = mysqli_num_rows($PractiquesD_result);
		$CertifsD_query = "SELECT * FROM plan_work_certification WHERE depart_id = ".$query_row['id'];
		$CertifsD_result = mysqli_query($conn, $CertifsD_query) or 
								die("<tr><td colspan=15>Помилка сервера при запиті<br>".$CertifsD_query.
								" : ".mysqli_error($conn)."</td></tr></table>");
		$CertifsD_count = mysqli_num_rows($CertifsD_result);

		$_POST['cbxd'.$query_row['id']] = isset($_POST['cbxd'.$query_row['id']]) ? 
															$_POST['cbxd'.$query_row['id']] : "";
		$_POST['deld'.$query_row['id']] = isset($_POST['deld'.$query_row['id']]) ?
															$_POST['deld'.$query_row['id']] : "";
?>
	<tr>	<td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['fakultet_shufr']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['shufr_kaf']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['nazva_kaf']." (".$query_row['phone'].")"; ?></td>
		<td><?php echo $TeachersD_count; $tcnt += $TeachersD_count; ?></td>
		<td><?php echo $query_row['teachers_salaries']; 
					$scnt += $query_row['teachers_salaries']; ?></td>
		<td><?php echo $query_row['postgrads']; $pgcnt += $query_row['postgrads']; ?></td>
		<td><?php echo $query_row['applicants']; $appcnt += $query_row['applicants']; ?></td>
		<td><?php echo $query_row['other_hours']; $ohcnt += $query_row['other_hours']; ?></td>
		<td><?php echo $SubjectsD_count; $subjcnt += $SubjectsD_count; ?></td>
		<td><?php echo $PractiquesD_count + $CertifsD_count; $prcercnt += $PractiquesD_count + $CertifsD_count; ?></td>
		<td><?php echo $query_row['depart_group_id']; ?></td>
		<td>
<!--
		<input type="checkbox" id="сbxd<?php echo $query_row['id']; ?>" 
						name="cbxd<?php echo $query_row['id']; ?>" onclick="submit()" class="del" />
			<label for="сbxd<?php echo $query_row['id']; ?>" class="del">Розкрити/Сховати</label>
-->
<?php
		if ($TrueAdmin) echo paramCheker("cbxd".$query_row['id'], $_POST['cbxd'.$query_row['id']], 
																			"Розкрити", "onchange=\"submit()\"");
		if (!empty($_POST['cbxd'.$query_row['id']])) {
?>
			<div><?php 
				echo selectCommonSelect
					("Інститут (факультет): ", "facd".$query_row['id'], $conn, $FacultiesQuery, "id", 
						$query_row['fakultet_id'], "fakultet_name", "style=\"font-weight: bold;\""); ?><br>
				Назва кафедри: 
				<input type="textbox" name="tbxdn<?php echo $query_row['id']; ?>" 
						ondblclick="submit()" style="font-weight: bold; width: 400px;"
						value="<?php echo $query_row['nazva_kaf']; ?>" /><br>
				Шифр кафедри: 
				<input type="textbox" name="tbxdc<?php echo $query_row['id']; ?>" 
						style="font-weight: bold; width: 100px;"
						value="<?php echo $query_row['shufr_kaf']; ?>" /> &nbsp; &nbsp; 
				Кількість ставок: 
				<input type="textbox" name="tbxts<?php echo $query_row['id']; ?>" 
						style="font-weight: bold; width: 50px;"
						value="<?php echo $query_row['teachers_salaries']; ?>" /><br>
				Аспірантів: 
				<input type="textbox" name="tbxps<?php echo $query_row['id']; ?>" 
						style="font-weight: bold; width: 25px;"
						value="<?php echo $query_row['postgrads']; ?>" /> &nbsp; &nbsp; 
				Здобувачів: 
				<input type="textbox" name="tbxap<?php echo $query_row['id']; ?>" 
						style="font-weight: bold; width: 25px;"
						value="<?php echo $query_row['applicants']; ?>" /> &nbsp; &nbsp; 
				Інші години поза РНП: 				
				<input type="textbox" name="tbxoh<?php echo $query_row['id']; ?>" 
						style="font-weight: bold; width: 45px;"
						value="<?php echo $query_row['other_hours']; ?>" /><br>
				<?php echo selectCommonSelect
					("Група кафедр: ", "dg".$query_row['id'], $conn, $DepartGroupsQuery, "id", 
						$query_row['depart_group_id'], "depart_group_name", "style=\"font-weight: bold;\""); ?><br>
				<?php echo selectCommonSelect
					("Інститут (факультет) у рейтингу НПП: ", "facdr".$query_row['id'], $conn, $FacultiesQuery, "id", 
						$query_row['faculty_id_for_rate'], "fakultet_name", "style=\"font-weight: bold;\""); ?><br>
				Номери телефонів: 
				<input type="textbox" name="tbxph<?php echo $query_row['id']; ?>" 
						style="font-weight: bold; width: 160px;"
						value="<?php echo $query_row['phone']; ?>" /> &nbsp; &nbsp; 
				<input type="submit" name="sbtd<?php echo $query_row['id']; ?>" value="Зберегти" />
			</div>
<?php	}	?>
		</td>
		<td><?php 
		if ($TrueAdmin and ($TeachersD_count == 0) and ($query_row['teachers_salaries'] == 0) 
					and ($query_row['postgrads'] == 0) and ($query_row['applicants'] == 0) and ($query_row['other_hours'] == 0)
					and ($SubjectsD_count + $PractiquesD_count + $CertifsD_count == 0)) { ?>
			<label><input type="checkbox" class="checkbox" id="deld<?php echo $query_row['id']; ?>" 
								name="deld<?php echo $query_row['id']; ?>" 
					<?php if ($_POST['deld'.$query_row['id']]) echo "checked"; ?> 					 	
			><span class="checkbox-custom"></span>
			</label><?php 
		} //								echo paramChekerInline("deld".$query_row['id'],$_POST['deld'.$query_row['id']],"","");
		echo $query_row['shufr_kaf']; ?></td>
	</tr>
<?php 
		$icnt++;
	}
?>
		<tr><th colspan=3 style="text-align: right;">Усього:</th><th><?php echo bold($icnt); ?></th>
			<th><?php echo bold($tcnt); ?></th><th><?php echo bold($scnt); ?></th>
			<th><?php echo bold($pgcnt); ?></th><th><?php echo bold($appcnt); ?></th>
			<th><?php echo bold($ohcnt); ?></th><th><?php echo bold($subjcnt); ?></th><th></th><th></th><th></th>
			<th><?php
if ($TrueAdmin) { ?>
					<input type="checkbox" id="deldep" name="deldep" 
									onclick="if (confirm('Дійсно видалити позначені кафедри?')) submit();" class="del" />
					<label for="deldep" class="del">Видалити</label><?php
} ?>	</th></tr>
</table>
