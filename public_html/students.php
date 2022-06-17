<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль students.php</p>"; require "footer.php"; exit(); }
$PrivInfoA = ($_SESSION['user_role'] == 'ROLE_ADMIN'); $PrivInfoP = ($_SESSION['user_role'] == 'ROLE_PSYCHO');
$PrivInfo1 = ($_SESSION['user_role'] != 'ROLE_STUDENT'); // echo $_SESSION['user_role'] . " / ". $PrivInfoA . " / " . $PrivInfoP . " PrivInfo1 = " . $PrivInfo1;
$PrivInfo2 = ($_SESSION['user_role'] == 'ROLE_RECTOR');
$PrivInfo3 = ($_SESSION['user_role'] == 'ROLE_VICERECTOR');
$_POST['cbxShowOnlyEmptyEmail'] = isset($_POST['cbxShowOnlyEmptyEmail']) ? $_POST['cbxShowOnlyEmptyEmail'] : "";
if ($PrivInfo1) {
	echo paramCheker("cbxShowOnlyEmptyEmail",$_POST['cbxShowOnlyEmptyEmail'],"Показати лише тих, у кого не введено email",
							"onchange=\"submit()\"");
}
//document.getElementById("wait").style.display = "inline"
// echo "<br>".$PrivInfo1." | ".$PrivInfo2." | ".$PrivInfo3;
$PrivateInfo = $PrivInfo1 + $PrivInfo2 + $PrivInfo3; // echo " / ".$PrivateInfo;
$GroupsQuery = "SELECT * FROM catalogGroup ORDER BY nazva_grupu";
?><br>
<table style="margin-left: 0%; width: 100%;">
	<tr><td colspan=16><?php $_POST['adds'] = isset($_POST['adds']) ? $_POST['adds'] : 0;
if ($TrueAdmin) echo paramCheker("adds",$_POST['adds'],"Додати нового студента",
							"onchange=\"submit()\""); ?></td></tr>
<?php 
	if (!empty($_POST['adds'])) {
		$_POST['grtoadd'] = isset($_POST['grtoadd']) ? $_POST['grtoadd'] : "";
		$_POST['sstoadd'] = isset($_POST['sstoadd']) ? $_POST['sstoadd'] : "";
		$_POST['sntoadd'] = isset($_POST['sntoadd']) ? $_POST['sntoadd'] : "";
		$_POST['sbtoadd'] = isset($_POST['sbtoadd']) ? $_POST['sbtoadd'] : "";
		$_POST['sltoadd'] = isset($_POST['sltoadd']) ? $_POST['sltoadd'] : "";
		$_POST['swtoadd'] = isset($_POST['swtoadd']) ? $_POST['swtoadd'] : "";
		if (!empty($_POST['grtoadd']) and !empty($_POST['sstoadd']) and
			 !empty($_POST['sntoadd']) and !empty($_POST['sbtoadd']) and
			 !empty($_POST['sltoadd']) and !empty($_POST['swtoadd'])
			) { 
			// перевірка, чи вже є такий викладач на кафедрі
			$SQuery = "SELECT * FROM catalogStudent 
							WHERE group_link=\"".$_POST['grtoadd']."\" AND
									student_name=\"".$_POST['sstoadd']."\" AND
									surname=\"".$_POST['sntoadd']."\" AND
									pobatkovi=\"".$_POST['sbtoadd']."\" AND role = 1
						 ";
			$query_result = mysqli_query($conn, $SQuery) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$SQuery.
							" : ".mysqli_error()."</td></tr></table>");
			$igsn = 0; while ($query_row = mysqli_fetch_array($query_result)) $igsn++;
			// перевірка, чи унікальний логін
			$LQuery = "SELECT * FROM catalogStudent WHERE login=\"".$_POST['sltoadd']."\"";
			$query_result = mysqli_query($conn, $LQuery) or 
						die("<tr><td colspan=10>Помилка сервера при запиті<br>".$LQuery.
							" : ".mysqli_error()."</td></tr></table>");
			$ilog = 0; while ($query_row = mysqli_fetch_array($query_result)) $ilog++;
			if (($igsn == 0) and ($ilog == 0)) {
				$AddSQuery = "insert into catalogStudent values
										(\"\",
										 \"".$_POST['sstoadd']."\", \"".$_POST['sntoadd']."\",
										 \"".$_POST['sbtoadd']."\", \"".$_POST['grtoadd']."\", \"\", 
										 \"".$_POST['swtoadd']."\", \"1\", \"".$_POST['sltoadd']."\", 
										 \"\", \"\", \"\", \"\", 
										 \"".$_POST['sstoadd']." ".$_POST['sntoadd']." ".$_POST['sbtoadd']."\"
										)"; // echo $AddTQuery;
			   $query_result = mysqli_query($conn, $AddSQuery) or 
							die("<tr><td colspan=10>Помилка сервера при запиті<br>".$AddSQuery.
								" : ".mysqli_error()."</td></tr></table>");
?>
<tr><td colspan=11 style="color: green; font-weight: normal;">
			Студент <?php echo bold($_POST['sstoadd']." ".$_POST['sntoadd']." ".$_POST['sbtoadd']); ?> 
			успішно доданий у вибрану групу </td></tr>
<?php
			} else {
				if ($igsn > 0) {
?>
<tr><td colspan=11 style="color: red; font-weight: normal;">
			Студент <?php echo bold($_POST['sstoadd']." ".$_POST['sntoadd']." ".$_POST['sbtoadd']); ?> 
			у складі вибраної групи вже є!</td></tr>
<?php
				}
				if ($ilog > 0) {
?>
<tr><td colspan=11 style="color: red; font-weight: normal;">
			Користувач з логіном <?php echo bold($_POST['sltoadd']); ?> у системі вже є!</td></tr>
<?php
				}

			}
		}
?><tr><td colspan=11>
<?php echo selectCommonSelect
		("В академгрупу: ", "grtoadd", $conn, $GroupsQuery, "id", $_POST['grtoadd'], "nazva_grupu", ""); ?><br>
Прізвище: 
<input type="text" name="sstoadd" style="font-weight: bold; width: 300px;" 
			value="<?php echo $_POST['sstoadd']; ?>" /> 
&nbsp; &nbsp; Імʼя: 
<input type="text" name="sntoadd" style="font-weight: bold; width: 100px;" 
		 value="<?php echo $_POST['sntoadd']; ?>" />
&nbsp; &nbsp; По батькові: 
<input type="text" name="sbtoadd" style="font-weight: bold; width: 150px;" 
		 value="<?php echo $_POST['sbtoadd']; ?>" /><br>
Логін: 
<input type="text" name="sltoadd" style="font-weight: bold;" value="<?php echo $_POST['sltoadd']; ?>" />
&nbsp; &nbsp; Пароль (до 10 символів): 
<input type="text" name="swtoadd" style="font-weight: bold; width: 140px;" 
		 value="<?php echo $_POST['swtoadd']; ?>" />
&nbsp; &nbsp; &nbsp; &nbsp;
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
	}
// Завантажити перелік викладачів і видалити позначених
	$TQuery = "SELECT * FROM catalogStudent ORDER BY id";
	$query1_result = mysqli_query($conn, $TQuery) or die("Помилка сервера при запиті<br>".$TQuery." : ".mysqli_error());
	if (!empty($_POST['delte'])) { // Натиснуто кнопку "Видалити"
		while ($query_row = mysqli_fetch_array($query1_result)) { // Обробка позначок "На видалення"
			if (!empty($_POST['delt'.$query_row['id']])) { // обробка позначки "На видалення"
				$DeleteTQuery = "DELETE FROM catalogStudent WHERE id='".$query_row['id']."'";
				$dT_result = mysqli_query($conn, $DeleteTQuery) or 
					die("Помилка сервера при запиті<br>".$DeleteTQuery." : ".mysqli_error());
			}
		}
	}
// Завантажити перелік викладачів і змінити позначених
	$query2_result = mysqli_query($conn, $TQuery) or die("Помилка сервера при запиті<br>".$TQuery." : ".mysqli_error());
	while ($query_row = mysqli_fetch_array($query2_result)) {
		// Обробка позначок "Змінити"
		$_POST['sbtt'.$query_row['id']] = isset($_POST['sbtt'.$query_row['id']]) ?
															$_POST['sbtt'.$query_row['id']] : "";
		$_POST['dep'.$query_row['id']] = isset($_POST['dep'.$query_row['id']]) ?
															$_POST['dep'.$query_row['id']] : "";
		$_POST['ts'.$query_row['id']] = isset($_POST['ts'.$query_row['id']]) ?
															$_POST['ts'.$query_row['id']] : "";
		$_POST['tn'.$query_row['id']] = isset($_POST['tn'.$query_row['id']]) ?
															$_POST['tn'.$query_row['id']] : "";
		$_POST['tb'.$query_row['id']] = isset($_POST['tb'.$query_row['id']]) ?
															$_POST['tb'.$query_row['id']] : "";
		$_POST['tl'.$query_row['id']] = isset($_POST['tl'.$query_row['id']]) ?
															$_POST['tl'.$query_row['id']] : "";
		$_POST['tp'.$query_row['id']] = isset($_POST['tp'.$query_row['id']]) ?
															$_POST['tp'.$query_row['id']] : "";
		if (!empty($_POST['sbtt'.$query_row['id']]) and
			 !empty($_POST['dep'.$query_row['id']]) and
			 !empty($_POST['ts'.$query_row['id']]) and
			 !empty($_POST['tn'.$query_row['id']]) and
			 !empty($_POST['tb'.$query_row['id']]) and
			 !empty($_POST['tl'.$query_row['id']]) and
			 !empty($_POST['tp'.$query_row['id']])) { // обробка кнопки "Зберегти зміни"
			// перевірка, чи вже є такий викладач на кафедрі
			$TDQuery = "SELECT * FROM catalogStudent 
							WHERE kaf_link = \"".$_POST['dep'.$query_row['id']]."\" AND 
									teacher_surname = \"".$_POST['ts'.$query_row['id']]."\" AND 
									teacher_name = \"".$_POST['tn'.$query_row['id']]."\" AND 
									teacher_pobatkovi = \"".$_POST['tb'.$query_row['id']]."\" AND 
									role = 2 AND id <> ".$query_row['id'];
			$TDQuery_result = mysqli_query($conn, $TDQuery) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$TQuery.
							" : ".mysqli_error()."</td></tr></table>");
			$idtn = 0; while ($TDQuery_row = mysqli_fetch_array($TDQuery_result)) $idtn++;
			// перевірка, чи унікальний логін
			$LQuery = "SELECT * FROM catalogStudent 
							WHERE teacherLogin = \"".$_POST['tl'.$query_row['id']]."\" AND 
									id <> ".$query_row['id'];
			$LQuery_result = mysqli_query($conn, $LQuery) or 
						die("<tr><td colspan=10>Помилка сервера при запиті<br>".$LQuery.
							" : ".mysqli_error()."</td></tr></table>");
			$ilog = 0; while ($LQuery_row = mysqli_fetch_array($LQuery_result)) $ilog++;
			if (($idtn == 0) and ($ilog == 0)) {
				$UpdateTQuery = "UPDATE catalogStudent SET 
											kaf_link = \"".$_POST['dep'.$query_row['id']]."\" ,
											teacher_surname = \"".$_POST['ts'.$query_row['id']]."\" ,
											teacher_name = \"".$_POST['tn'.$query_row['id']]."\" ,
											teacher_pobatkovi = \"".$_POST['tb'.$query_row['id']]."\" ,
											teacherLogin = \"".$_POST['tl'.$query_row['id']]."\" ,
											teacherPsswd = \"".$_POST['tp'.$query_row['id']]."\" 
										WHERE id = ".$query_row['id']; // echo $UpdateTQuery;
			   $query_result = mysqli_query($conn, $UpdateTQuery) or 
							die("<tr><td colspan=10>Помилка сервера при запиті<br>".$UpdateTQuery.
								" : ".mysqli_error()."</td></tr></table>");
			} else {
				if ($idtn > 0) {
?>
<tr><td colspan=8 style="color: red; font-weight: normal;">
			Викладач <?php echo bold($_POST['ts'.$query_row['id']]." ".
										 $_POST['tn'.$query_row['id']]." ".$_POST['tb'.$query_row['id']]); ?> 
			на кафедрі <?php echo bold($_POST['dep'.$query_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
				}
				if ($ilog > 0) {
?>
<tr><td colspan=8 style="color: red; font-weight: normal;">
			Користувач з логіном <?php echo bold($_POST['tl'.$query_row['id']]); ?> у системі вже є!</td></tr>
<?php
				}
			}
		}
	}
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>Шифр факультету</th><th rowspan=2>Курс</th>
		<th rowspan=2>Шифр академгрупи</th><th rowspan=2>Прізвище, імʼя, по батькові</th>
		<th rowspan=2>email</th><th rowspan=2 style="width: 120px;">№ студ.<br>квитка</th>
		<th rowspan=2>Логін</th><th rowspan=2>Пароль</th>
		<th rowspan=2>ID фізкульт. групи</th><th rowspan=2>Група здоровʼя</th>
		<th style="font-size: 70%;" colspan=3>д.о. - доступ обмежено</th>
		<th colspan=2>Дії з обʼєктом</th></tr>
	<tr><th>Здоровʼя</th><th>Громад.<br>України</th>
		<th>Джерело фінанс.</th><th>Змінити групу, ПІБ тощо</th><th>До видалення</th></tr>
<?php
	$condEmail = !empty($_POST['cbxShowOnlyEmptyEmail']) ? "AND a.email = '' " : "";
// Завантажити перелік студентів
	$SQuery = "SELECT a.*, b.nazva_grupu, b.num_kurs, c.fakultet_shufr 
					FROM catalogStudent a, catalogGroup b, catalogFakultet c 
					WHERE a.group_link = b.id AND b.shufr_fak = c.id $condEmail
					ORDER BY c.fakultet_shufr, b.num_kurs, b.nazva_grupu, a.student_name, a.surname, a.pobatkovi"; // echo $SQuery;
	$query_result = mysqli_query($conn, $SQuery) or 
			die("Помилка сервера при запиті<br>".$SQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) {
		$_POST['cbxs'.$query_row['id']] = isset($_POST['cbxs'.$query_row['id']]) ? 
															$_POST['cbxg'.$query_row['id']] : "";
		$_POST['dels'.$query_row['id']] = isset($_POST['dels'.$query_row['id']]) ?
															$_POST['delg'.$query_row['id']] : ""; ?>
	<tr><td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: center;"><?php echo $query_row['fakultet_shufr']; ?></td>
		<td style="text-align: center;"><?php echo $query_row['num_kurs']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['nazva_grupu']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['student_name']." ".
																							$query_row['surname']." ".
																							$query_row['pobatkovi']; ?></td>
		<td style="text-align: left;"><?php if ($PrivInfo1) echo (!empty($query_row['email'])) ? $query_row['email'] : red("НЕ ВВЕДЕНО"); ?></td>
		<td style="width: 120px;"><?php echo $query_row['id_card']; ?></td>
		<td style="text-align: left;"><?php if ($TrueAdmin) echo $query_row['login']; ?></td>
		<td style="text-align: left;"><?php if ($TrueAdmin) echo $query_row['passport_number']; ?></td>
		<td><?php echo $query_row['sport_department']; ?></td>
		<td><?php echo $query_row['health_group']; ?></td>
		<td><?php if ($PrivateInfo) echo $query_row['show_health']; else echo "д.о."; ?></td>
		<td><?php if ($PrivateInfo)	echo $query_row['ukrainian']; else echo "д.о."; ?></td>
		<td><?php if ($PrivateInfo)	echo $query_row['finance']; else echo "д.о."; ?></td>
		<td></td>
		<td><?php 
		if ($TrueAdmin) echo paramCheker("dels".$query_row['id'],$_POST['dels'.$query_row['id']],"",""); ?></td>
	</tr><?php 
		$icnt++;
	} ?>
<tr><td colspan=15 style="text-align: right;">Кількість студентів: <?php echo bold($icnt); ?></td>
		<td><?php
if ($TrueAdmin) { ?>
			<input type="checkbox" id="dels" name="dels" 
								onclick="if (confirm('Дійсно видалити позначених студентів?')) submit();" class="del" />
						<label for="dels" class="del">Видалити</label><?php
} ?></td></tr>
</table>
