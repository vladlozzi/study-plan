<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль departs_heads.php</p>"; require "footer.php"; exit(); }
// Перелік кафедр для вибору
$DepartsQuery = "SELECT id, nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf";
$DepHQuery = "SELECT a.id, a.departmentId, b.shufr_kaf, b.nazva_kaf, 
									a.chiefName, a.chief_fam_init, a.ch_login, a.ch_psswd, c.Description
				FROM catalogDepartChief a, catalogDepartment b, catalogRoles c
				WHERE a.departmentId = b.id AND a.role = c.id
				ORDER BY b.nazva_kaf";
?><br>
<table style="margin-left: 0%; width: 100%;">
	<tr><td colspan=9><?php $_POST['adddh'] = isset($_POST['adddh']) ? $_POST['adddh'] : 0;
echo paramCheker("adddh",$_POST['adddh'],"Додати нового завідувача",
								"onchange=\"submit()\""); ?></td></tr>
<?php
	if (!empty($_POST['adddh'])) {
		$_POST['deptoadd'] = isset($_POST['deptoadd']) ? $_POST['deptoadd'] : "";
		$_POST['dhftoadd'] = isset($_POST['dhftoadd']) ? $_POST['dhftoadd'] : "";
		$_POST['dhitoadd'] = isset($_POST['dhitoadd']) ? $_POST['dhitoadd'] : "";
		$_POST['dhltoadd'] = isset($_POST['dhltoadd']) ? $_POST['dhltoadd'] : "";
		$_POST['dhptoadd'] = isset($_POST['dhptoadd']) ? $_POST['dhptoadd'] : "";
		if (!empty($_POST['deptoadd']) and !empty($_POST['dhftoadd']) and !empty($_POST['dhitoadd']) and 
			 !empty($_POST['dhltoadd']) and !empty($_POST['dhptoadd'])
			) { 
			// перевірка, чи вже є завідувач цієї кафедрі
			$HQuery = "SELECT * FROM catalogDepartChief 
							WHERE departmentId=\"".$_POST['deptoadd']."\" AND role = 9";
			$query_result = mysqli_query($conn, $HQuery) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$HQuery.
							" : ".mysqli_error()."</td></tr></table>");
			$idhn = 0; while ($query_row = mysqli_fetch_array($query_result)) $idhn++;
			// перевірка, чи унікальний логін
			$LQuery = "SELECT * FROM catalogDepartChief WHERE ch_login=\"".$_POST['dhltoadd']."\"";
			$query_result = mysqli_query($conn, $LQuery) or 
						die("<tr><td colspan=10>Помилка сервера при запиті<br>".$LQuery.
							" : ".mysqli_error()."</td></tr></table>");
			$ilog = 0; while ($query_row = mysqli_fetch_array($query_result)) $ilog++;
			if (($idhn == 0) and ($ilog == 0)) {
				$AddHQuery = "insert into catalogDepartChief values
										(\"\", \"".$_POST['dhftoadd']."\", \"".$_POST['dhitoadd']."\",
										 \"".$_POST['deptoadd']."\", \"".$_POST['dhltoadd']."\",
										 \"".$_POST['dhptoadd']."\", 9
										)"; // echo $AddTQuery;
			   $query_result = mysqli_query($conn, $AddHQuery) or 
							die("<tr><td colspan=10>Помилка сервера при запиті<br>".$AddHQuery.
								" : ".mysqli_error()."</td></tr></table>");
?>
<tr><td colspan=9 style="color: green; font-weight: normal;">
			Завідувач <? echo bold($_POST['dhftoadd']); ?> успішно доданий на вибрану кафедру</td></tr>
<?php
			} else {
				if ($idhn > 0) {
?>
<tr><td colspan=9 style="color: red; font-weight: normal;">
			На вибраній кафедрі завідувач вже є в довіднику!</td></tr>
<?php
				}
				if ($ilog > 0) {
?>
<tr><td colspan=8 style="color: red; font-weight: normal;">
			Користувач з логіном <? echo bold($_POST['dhltoadd']); ?> у системі вже є!</td></tr>
<?php
				}

			}
		}
?><tr><td colspan=9>
<?php echo selectCommonSelect
		("На кафедру: ", "deptoadd", $conn, $DepartsQuery, "id", $_POST['deptoadd'], "nazva_kaf", ""); ?><br>
Прізвище, ім'я та по батькові: 
<input type="text" name="dhftoadd" style="font-weight: bold; width: 500px;" 
			value="<?php echo $_POST['dhftoadd']; ?>" /> 
&nbsp; &nbsp; Прізвище та ініціали: 
<input type="text" name="dhitoadd" style="font-weight: bold; width: 300px;" 
			value="<?php echo $_POST['dhitoadd']; ?>" /><br>
Логін: 
<input type="text" name="dhltoadd" style="font-weight: bold;" value="<?php echo $_POST['dhltoadd']; ?>" />
&nbsp; &nbsp; Пароль (до 10 символів): 
<input type="text" name="dhptoadd" style="font-weight: bold; width: 140px;" 
		 value="<?php echo $_POST['dhptoadd']; ?>" />
&nbsp; &nbsp; &nbsp; &nbsp;
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
	}
// Завантажити перелік завідувачів і змінити позначених
	$query2_result = mysqli_query($conn, $DepHQuery) 
									 or die("Помилка сервера при запиті<br>".$DepHQuery." : ".mysqli_error());
	while ($query_row = mysqli_fetch_array($query2_result)) {
		// Обробка позначок "Змінити"
		$_POST['sbtdh'.$query_row['id']] = isset($_POST['sbtdh'.$query_row['id']]) ?
															$_POST['sbtdh'.$query_row['id']] : "";
		$_POST['dep'.$query_row['id']] = isset($_POST['dep'.$query_row['id']]) ?
															$_POST['dep'.$query_row['id']] : "";
		$_POST['dhname'.$query_row['id']] = isset($_POST['dhname'.$query_row['id']]) ?
															$_POST['dhname'.$query_row['id']] : "";
		$_POST['dhinit'.$query_row['id']] = isset($_POST['dhinit'.$query_row['id']]) ?
															$_POST['dhinit'.$query_row['id']] : "";
		$_POST['dhl'.$query_row['id']] = isset($_POST['dhl'.$query_row['id']]) ?
															$_POST['dhl'.$query_row['id']] : "";
		$_POST['dhp'.$query_row['id']] = isset($_POST['dhp'.$query_row['id']]) ?
															$_POST['dhp'.$query_row['id']] : "";
		if (!empty($_POST['sbtdh'.$query_row['id']]) and
			 !empty($_POST['dep'.$query_row['id']]) and
			 !empty($_POST['dhname'.$query_row['id']]) and
			 !empty($_POST['dhinit'.$query_row['id']]) and
			 !empty($_POST['dhl'.$query_row['id']]) and
			 !empty($_POST['dhp'.$query_row['id']])) { // обробка кнопки "Зберегти зміни"
			// перевірка, чи вже є такий
			$HSQuery = "SELECT * FROM catalogDepartChief
							WHERE departmentId = \"".$_POST['dep'.$query_row['id']]."\" AND 
									chiefName = \"".$_POST['dhname'.$query_row['id']]."\" AND 
									role = 9 AND id <> ".$query_row['id'];
			$HSQuery_result = mysqli_query($conn, $HSQuery) or 
						die("<tr><td colspan=9>Помилка сервера при запиті<br>".$HSQuery.
							" : ".mysqli_error()."</td></tr></table>");
			$idhn = 0; while ($HSQuery_row = mysqli_fetch_array($HSQuery_result)) $idtn++;
			// перевірка, чи унікальний логін
			$HLQuery = "SELECT * FROM catalogDepartChief 
							WHERE ch_login = \"".$_POST['dhl'.$query_row['id']]."\" AND 
									id <> ".$query_row['id'];
			$HLQuery_result = mysqli_query($conn, $HLQuery) or 
						die("<tr><td colspan=9>Помилка сервера при запиті<br>".$HLQuery.
							" : ".mysqli_error()."</td></tr></table>");
			$ilog = 0; while ($HLQuery_row = mysqli_fetch_array($HLQuery_result)) $ilog++;
			if (($idhn == 0) and ($ilog == 0)) {
				$UpdateDHQuery = "UPDATE catalogDepartChief SET 
											departmentId = \"".$_POST['dep'.$query_row['id']]."\" ,
											chiefName = \"".$_POST['dhname'.$query_row['id']]."\" ,
					 						chief_fam_init = \"".$_POST['dhinit'.$query_row['id']]."\" ,
											ch_login = \"".$_POST['dhl'.$query_row['id']]."\" ,
											ch_psswd = \"".$_POST['dhp'.$query_row['id']]."\" 
										WHERE id = ".$query_row['id']; // echo $UpdateDHQuery;
			   $query_result = mysqli_query($conn, $UpdateDHQuery) or 
							die("<tr><td colspan=9>Помилка сервера при запиті<br>".$UpdateDHQuery.
								" : ".mysqli_error()."</td></tr></table>");
			} else {
				if ($idhn > 0) {
?>
<tr><td colspan=9 style="color: red; font-weight: normal;">
			Завідувач <?php echo bold($_POST['dhn'.$query_row['id']])." на кафедрі ".
												  bold($_POST['dep'.$query_row['id']]); ?> у довіднику вже є!</td></tr>
<?php
				}
				if ($ilog > 0) {
?>
<tr><td colspan=9 style="color: red; font-weight: normal;">
			Користувач з логіном <?php echo bold($_POST['dhl'.$query_row['id']]); ?> у довіднику вже є!</td></tr>
<?php
				}
			}
		}
	}
// Завантажити перелік для видалення
	$DH_query = "SELECT * FROM catalogDepartChief ORDER BY id";
	$query_result = mysqli_query($conn, $DH_query) or 
			die("Помилка сервера при запиті<br>".$DH_query." : ".mysqli_error($conn));
	if (!empty($_POST['deldh'])) { // Натиснуто кнопку "Видалити"
		while ($query_row = mysqli_fetch_array($query_result)) { // Обробка позначок "На видалення"
			if (!empty($_POST['deldh'.$query_row['id']])) { // обробка позначки "На видалення"
				$DeleteDH_query = "DELETE FROM catalogDepartChief WHERE id='".$query_row['id']."'";
				$dh_result = mysqli_query($conn, $DeleteDH_query) or 
					die("Помилка сервера при запиті<br>".$DeleteDH_query." : ".mysqli_error($conn));
			}
		}
	}
?>

	<tr><th rowspan=2>Код</th><th rowspan=2>№</th><th rowspan=2>Кафедра</th>
		<th rowspan=2>Прізвище, ім'я та по батькові</th><th rowspan=2>Прізвище та ініціали</th>
		<th rowspan=2>Логін</th><th rowspan=2>Пароль</th>
		<th colspan=2>Дії з об'єктом</th></tr>
	<tr><th>Змінити кафедру, П.І.Б., логін, пароль</th><th>До видалення</th></tr>
<?php
// Завантажити перелік
	$query_result = mysqli_query($conn, $DepHQuery) or 
			die("Помилка сервера при запиті<br>".$DepHQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { $icnt++;
		$_POST['cbxdh'.$query_row['id']] = isset($_POST['cbxdh'.$query_row['id']]) ? 
															$_POST['cbxdh'.$query_row['id']] : "";
		$_POST['deldh'.$query_row['id']] = isset($_POST['deldh'.$query_row['id']]) ?
															$_POST['deldh'.$query_row['id']] : "";
?>
	<tr>		<td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: right;"><?php echo $icnt; ?></td>
		<td style="text-align: left;"><?php echo $query_row['nazva_kaf'].
															" (".$query_row['shufr_kaf'].")"; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['chiefName']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['chief_fam_init']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['ch_login']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['ch_psswd']; ?></td>                        		
		<td>
<?php 
		if ($TrueAdmin) echo paramCheker("cbxdh".$query_row['id'], $_POST['cbxdh'.$query_row['id']], 
																			"Розкрити/Сховати", "onchange=\"submit()\"");
		if (!empty($_POST['cbxdh'.$query_row['id']])) {
?>
			<div><?php 
			echo selectCommonSelect
			("Кафедра: ", "dep".$query_row['id'], $conn, $DepartsQuery, "id", 
				$query_row['departmentId'], "nazva_kaf", "style=\"font-weight: bold;\""); ?><br>
				Прізвище, імя, по батькові: 
				<input type="text" name="dhname<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['chiefName']; ?>" 
						style="font-weight: bold; width: 300px;"/><br>
				Прізвище та ініціали: 
				<input type="text" name="dhinit<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['chief_fam_init']; ?>" 
						style="font-weight: bold; width: 200px;" /><br>
				Логін: 
				<input type="text" name="dhl<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['ch_login']; ?>" style="font-weight: bold;" />
				&nbsp; &nbsp;
				Пароль: 
				<input type="text" name="dhp<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['ch_psswd']; ?>" style="width: 100px;" /><br>
				<input type="submit" name="sbtdh<?php echo $query_row['id']; ?>" value="Зберегти" />
			</div>
<?php
		} ?>
		</td>
		<td><?php echo paramCheker("deldh".$query_row['id'],$_POST['deldh'.$query_row['id']],"",""); ?></td>
	</tr>
<?php 
	}
	if ($TrueAdmin) { ?>
		<tr><td colspan=8 style="text-align: right;">Усього: <?php echo bold($icnt); ?></td>
		<td><input type="checkbox" id="deldh" name="deldh" 
								onclick="if (confirm('Дійсно видалити позначених завідувачів?')) submit();" class="del" />
						<label for="deldh" class="del">Видалити</label></td></tr>
<?php
	} ?>
</table>
