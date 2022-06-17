<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль teachers.php</p>"; require "footer.php"; exit(); }
// Перелік кафедр для вибору
$DepartsQuery = "SELECT id, nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf";
// echo ($_SESSION['user_role'] == "ROLE_ADMIN") ? "T" : "F";
?><br>
<table style="margin-left: 0%; width: 100%;">
	<tr><td colspan=10><?php $_POST['addt'] = isset($_POST['addt']) ? $_POST['addt'] : 0;
if ($TrueAdmin)	echo paramCheker("addt",$_POST['addt'],"Додати нового викладача",
																	"onchange=\"submit()\""); ?></td></tr>
<?php 
	if (!empty($_POST['addt'])) {
		$_POST['sbtAddSave'] = isset($_POST['sbtAddSave']) ? $_POST['sbtAddSave'] : "";
		$_POST['deptoadd'] = isset($_POST['deptoadd']) ? $_POST['deptoadd'] : "";
		$_POST['tstoadd'] = isset($_POST['tstoadd']) ? $_POST['tstoadd'] : "";
		$_POST['tntoadd'] = isset($_POST['tntoadd']) ? $_POST['tntoadd'] : "";
		$_POST['tbtoadd'] = isset($_POST['tbtoadd']) ? $_POST['tbtoadd'] : "";
		$_POST['totoadd'] = isset($_POST['totoadd']) ? $_POST['totoadd'] : "https://orcid.org/";
		$_POST['tctoadd'] = isset($_POST['tctoadd']) ? $_POST['tctoadd'] : 
			"https://scopus.com/authid/detail.uri?authorId=";
		if ($_SESSION['user_role'] == "ROLE_ADMIN") {
			$_POST['tltoadd'] = isset($_POST['tltoadd']) ? $_POST['tltoadd'] : "";
			$_POST['tptoadd'] = isset($_POST['tptoadd']) ? $_POST['tptoadd'] : "";
		} 
		$NotEmptyCond = !empty($_POST['deptoadd']) && !empty($_POST['tstoadd']) && 
										!empty($_POST['tntoadd']) && !empty($_POST['tbtoadd']); // echo ($NotEmptyCond) ? "True" : "False";
		$NotAdmCond = $NotEmptyCond && ($_SESSION['user_role'] != "ROLE_ADMIN"); // echo ($NotAdmCond) ? "True" : "False";
		$TrueAdmCond = $_SESSION['user_role'] == "ROLE_ADMIN" && $NotEmptyCond && 
				!empty($_POST['tltoadd']) && !empty($_POST['tptoadd']); // echo ($TrueAdmCond) ? "True" : "False";
		if (!empty($_POST['sbtAddSave']) and ($NotAdmCond or $TrueAdmCond)) { 
			// перевірка, чи вже є такий викладач на кафедрі
			$TQuery = "SELECT * FROM catalogTeacher 
							WHERE kaf_link=\"".$_POST['deptoadd']."\" AND
									teacher_surname=\"".$_POST['tstoadd']."\" AND
									teacher_name=\"".$_POST['tntoadd']."\" AND
									teacher_pobatkovi=\"".$_POST['tbtoadd']."\" AND role = 2
						 "; // echo $TQuery;
			$query_result = mysqli_query($conn, $TQuery) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$TQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$idtn = 0; while ($query_row = mysqli_fetch_array($query_result)) $idtn++;
			if ($_SESSION['user_role'] == "ROLE_ADMIN") {
			// перевірка, чи унікальний логін
				$LQuery = "SELECT * FROM catalogTeacher WHERE teacherLogin=\"".$_POST['tltoadd']."\"";
				$query_result = mysqli_query($conn, $LQuery) or 
						die("<tr><td colspan=10>Помилка сервера при запиті<br>".$LQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
				$ilog = 0; while ($query_row = mysqli_fetch_array($query_result)) $ilog++;
			}
			if ($_SESSION['user_role'] == "ROLE_ADMIN" and $idtn == 0 and $ilog == 0 or
					$_SESSION['user_role'] != "ROLE_ADMIN" and $idtn == 0) {
				$AddTQuery = "insert into catalogTeacher values
										(NULL,\"".$_POST['deptoadd']."\",
										 \"".$_POST['tstoadd']."\", \"".$_POST['tntoadd']."\",
										 \"".$_POST['tbtoadd']."\",
										\"".(($_SESSION['user_role'] == "ROLE_ADMIN") ? $_POST['tltoadd'] : "")."\",
										\"".(($_SESSION['user_role'] == "ROLE_ADMIN") ? $_POST['tptoadd'] : "")."\", 
										\"\",\"\",\"2\",\"".$_POST['totoadd']."\",\"".$_POST['tctoadd']."\", \"\", 0
										)"; // echo $AddTQuery;
				$query_result = mysqli_query($conn, $AddTQuery) or 
							die("<tr><td colspan=10>Помилка сервера при запиті<br>".$AddTQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
				logData($_SESSION['user_id'], $_SESSION['user_role'], '0', 
								str_replace("'", "\'", $AddTQuery));

?>
<tr><td colspan=10 style="color: green; font-weight: normal;">
			Викладач <?php echo bold($_POST['tstoadd']." ".$_POST['tntoadd']." ".$_POST['tbtoadd']); ?> 
			успішно доданий на вибрану кафедру</td></tr><?php
			} else {
				if ($idtn > 0) { ?>
<tr><td colspan=10 style="color: red; font-weight: normal;">
			Викладач <?php echo bold($_POST['tstoadd']." ".$_POST['tntoadd']." ".$_POST['tbtoadd']); ?> 
			на вибраній кафедрі в довіднику вже є!</td></tr><?php
				}
				if ($_SESSION['user_role'] == "ROLE_ADMIN" and $ilog > 0) { ?>
<tr><td colspan=10 style="color: red; font-weight: normal;">
			Користувач з логіном <?php echo bold($_POST['tltoadd']); ?> у системі вже є!</td></tr>
<?php
				}
			}
		}
?><tr><td colspan=10>
<?php echo selectCommonSelect
		("На кафедру: ", "deptoadd", $conn, $DepartsQuery, "id", $_POST['deptoadd'], "nazva_kaf", ""); ?><br>
Прізвище: 
<input type="text" name="tstoadd" style="font-weight: bold;" value="<?php echo $_POST['tstoadd']; ?>" /> 
&nbsp; &nbsp; Ім&#x2bc;я: 
<input type="text" name="tntoadd" style="font-weight: bold; width: 100px;" 
		 value="<?php echo $_POST['tntoadd']; ?>" />
&nbsp; &nbsp; По батькові: 
<input type="text" name="tbtoadd" style="font-weight: bold; width: 200px;" 
		 value="<?php echo $_POST['tbtoadd']; ?>" /><br>
ORCID iD (з <?php echo "https://orcid.org/"; ?>): 
<input type="text" name="totoadd" style="font-weight: bold; width: 400px;" 
		 value="<?php echo $_POST['totoadd']; ?>" /><br>
Scopus Author&#x2bc;s ID (повна http-адреса): 
<input type="text" name="tctoadd" style="font-weight: bold; width: 400px;" 
		 value="<?php echo $_POST['tctoadd']; ?>" /><br><?php 
		if ($_SESSION['user_role'] == "ROLE_ADMIN") {
?>Логін: 
<input type="text" name="tltoadd" style="font-weight: bold;" value="<?php echo $_POST['tltoadd']; ?>" />
&nbsp; &nbsp; Пароль (до 10 символів): 
<input type="text" name="tptoadd" style="font-weight: bold; width: 140px;" 
		 value="<?php echo $_POST['tptoadd']; ?>" />
&nbsp; &nbsp; &nbsp; &nbsp;<?php
		} ?>
<input type="submit" name="sbtAddSave" value="Зберегти" /></td></tr>
<?php
	}

// Завантажити перелік викладачів і видалити позначених
	$TQuery = "SELECT * FROM catalogTeacher ORDER BY id";
	$query1_result = mysqli_query($conn, $TQuery) or die("Помилка сервера при запиті<br>".$TQuery." : ".mysqli_error($conn));
	if (!empty($_POST['delte'])) { // Натиснуто кнопку "Видалити"
		while ($query_row = mysqli_fetch_array($query1_result)) { // Обробка позначок "На видалення"
			if (!empty($_POST['delt'.$query_row['id']])) { // обробка позначки "На видалення"
				$DeleteTQuery = "DELETE FROM catalogTeacher WHERE id = ".$query_row['id'];
				$dT_result = mysqli_query($conn, $DeleteTQuery) or 
					die("Помилка сервера при запиті<br>".$DeleteTQuery." : ".mysqli_error($conn));
				logData($_SESSION['user_id'], $_SESSION['user_role'], '0', $DeleteTQuery);
			}
		}
	}
// Завантажити перелік викладачів і змінити позначених
	$query2_result = mysqli_query($conn, $TQuery) or die("Помилка сервера при запиті<br>".$TQuery." : ".mysqli_error($conn));
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
		$_POST['to'.$query_row['id']] = isset($_POST['to'.$query_row['id']]) ?
															$_POST['to'.$query_row['id']] : "";
		$_POST['tc'.$query_row['id']] = isset($_POST['tc'.$query_row['id']]) ?
															$_POST['tc'.$query_row['id']] : "";
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
			$TDQuery = "SELECT * FROM catalogTeacher 
							WHERE kaf_link = \"".$_POST['dep'.$query_row['id']]."\" AND 
									teacher_surname = \"".$_POST['ts'.$query_row['id']]."\" AND 
									teacher_name = \"".$_POST['tn'.$query_row['id']]."\" AND 
									teacher_pobatkovi = \"".$_POST['tb'.$query_row['id']]."\" AND 
									role = 2 AND id <> ".$query_row['id'];
			$TDQuery_result = mysqli_query($conn, $TDQuery) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$TQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$idtn = 0; while ($TDQuery_row = mysqli_fetch_array($TDQuery_result)) $idtn++;
			// перевірка, чи унікальний логін
			$LQuery = "SELECT * FROM catalogTeacher 
							WHERE teacherLogin = \"".$_POST['tl'.$query_row['id']]."\" AND 
									id <> ".$query_row['id'];
			$LQuery_result = mysqli_query($conn, $LQuery) or 
						die("<tr><td colspan=10>Помилка сервера при запиті<br>".$LQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$ilog = 0; while ($LQuery_row = mysqli_fetch_array($LQuery_result)) $ilog++;
			if (($idtn == 0) and ($ilog == 0)) {
				$UpdateTQuery = "UPDATE catalogTeacher SET 
											kaf_link = \"".$_POST['dep'.$query_row['id']]."\" ,
											teacher_surname = \"".$_POST['ts'.$query_row['id']]."\" ,
											teacher_name = \"".$_POST['tn'.$query_row['id']]."\" ,
											teacher_pobatkovi = \"".$_POST['tb'.$query_row['id']]."\" ,
											orcid = \"".$_POST['to'.$query_row['id']]."\" ,
											scopus_id = \"".$_POST['tc'.$query_row['id']]."\" ,
											teacherLogin = \"".$_POST['tl'.$query_row['id']]."\" ,
											teacherPsswd = \"".$_POST['tp'.$query_row['id']]."\" 
										WHERE id = ".$query_row['id']; // echo $UpdateTQuery;
				$query_result = mysqli_query($conn, $UpdateTQuery) or 
							die("<tr><td colspan=10>Помилка сервера при запиті<br>".$UpdateTQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
				logData($_SESSION['user_id'], $_SESSION['user_role'], '0', 
								str_replace("'", "\'", $UpdateTQuery));
				$_POST['sbtt'.$query_row['id']] = ""; $_POST['cbxt'.$query_row['id']] = "";
			} else {
				if ($idtn > 0) {
?>
<tr><td colspan=10 style="color: red; font-weight: normal;">
			Викладач <? echo bold($_POST['ts'.$query_row['id']]." ".
										 $_POST['tn'.$query_row['id']]." ".$_POST['tb'.$query_row['id']]); ?> 
			на кафедрі <? echo bold($_POST['dep'.$query_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
				}
				if ($ilog > 0) {
?>
<tr><td colspan=10 style="color: red; font-weight: normal;">
			Користувач з логіном <? echo bold($_POST['tl'.$query_row['id']]); ?> у системі вже є!</td></tr>
<?php
				}
			}
		}
	}
?>
	<tr><th rowspan=2>Код</th><th rowspan=2 style="width: 80px;">Кафедра</th>
		<th rowspan=2 style="width: 350px;">Прізвище, ім&#x2bc;я, по батькові, email</th>
		<th rowspan=2>ORCID iD</th><th rowspan=2>Scopus<br>Author&#x2bc;s<br>Id</th><th rowspan=2>Посада</th>
		<th rowspan=2 style="width: 200px;">Логін</th><th rowspan=2 style="width: 100px;">Пароль</th>
    <th colspan=2>Дії з об&#x2bc;єктом</th></tr>
	<tr><th>Змінити кафедру,<br>ПІБ, логін, пароль</th><th>До видалення</th></tr>
<?php
// Завантажити перелік
	$TeachersQuery = "
				SELECT a.id, b.id as depart_id, b.shufr_kaf, a.orcid, a.scopus_id,
						a.teacher_surname, a.teacher_name, a.teacher_pobatkovi, a.email, a.isDekan, e.placeName,
						a.teacherLogin, a.teacherPsswd, c.Description
				FROM (catalogTeacher a, catalogDepartment b, catalogRoles c)
				LEFT JOIN tsupp_teachrate.teachMatrixNames d ON (d.teacherId = a.id AND d.role = c.userRole)
				LEFT JOIN tsupp_teachrate.cPlace e ON (e.id = d.place)
				WHERE a.kaf_link = b.id AND a.role = c.id
				ORDER BY b.shufr_kaf, a.teacher_surname, a.teacher_name
		";
	$query_result = mysqli_query($conn, $TeachersQuery) or 
			die("Помилка сервера при запиті<br>".$TeachersQuery." : ".mysqli_error($conn));
	$icnt = 0; $icnt_temp = 0; $icnt_decr = 0; $icnt_parttime = 0; $icnt_released = 0; $icnt_hourly = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbxt'.$query_row['id']] = isset($_POST['cbxt'.$query_row['id']]) ? 
															$_POST['cbxt'.$query_row['id']] : "";
		$_POST['delt'.$query_row['id']] = isset($_POST['delt'.$query_row['id']]) ?
															$_POST['delt'.$query_row['id']] : "";
?>
	<tr><td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['shufr_kaf']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['teacher_surname']." ".
																						$query_row['teacher_name']." ".
																						$query_row['teacher_pobatkovi'].
																						((!empty($query_row['email'])) ? ", ".$query_row['email'] : "").
																						(($query_row['isDekan'] == 1) ? ", директор інституту" : ""); ?>
		</td>
		<td style="text-align: left; font-size: 80%;">
			<a href= <?php echo "\"".$query_row['orcid']."\""; ?> target="_blank"><?php 
		echo str_replace("http://orcid.org/", "", str_replace("https://orcid.org/", "", $query_row['orcid']));
		?></a></td>
		<td style="text-align: left; font-size: 80%;">
			<a href= <?php echo "\"".$query_row['scopus_id']."\""; ?> target="_blank"><?php 
		echo str_replace("https://scopus.com/authid/detail.uri?authorId=", "",
					str_replace("www2.", "", str_replace("www.", "", $query_row['scopus_id'])));
		?></a></td>
		<td style="text-align: left;"><?php echo $query_row['placeName']; ?></td>
		<td style="text-align: left;"><?php 
		if ($_SESSION['user_role'] == "ROLE_ADMIN") echo $query_row['teacherLogin']; ?></td>
		<td style="text-align: left;"><?php 
		if ($_SESSION['user_role'] == "ROLE_ADMIN") echo $query_row['teacherPsswd']; ?></td>                        		
		<td>
<?php 
		if ($TrueAdmin) echo paramCheker("cbxt".$query_row['id'], $_POST['cbxt'.$query_row['id']], 
																			"Розкрити/Сховати", "onchange=\"submit()\"");
		if (!empty($_POST['cbxt'.$query_row['id']])) {
?>
			<div><?php 
			echo selectCommonSelect
			("Кафедра: ", "dep".$query_row['id'], $conn, $DepartsQuery, "id", 
				$query_row['depart_id'], "nazva_kaf", "style=\"font-weight: bold;\""); ?><br>
				Прізвище: 
				<input type="text" name="ts<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['teacher_surname']; ?>" style="font-weight: bold; width: 150px;"/><br>
				Ім&#x2bc;я: 
				<input type="text" name="tn<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['teacher_name']; ?>" style="width: 100px;" />
				&nbsp; &nbsp;
				По&nbsp;батькові:&nbsp;<input type="text" name="tb<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['teacher_pobatkovi']; ?>" /><br>
				ORCID iD:&nbsp;<input type="text" name="to<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['orcid']; ?>" style="width: 400px;"/><br>
				Scopus Author&#x2bc;s Id: <input type="text" name="tc<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['scopus_id']; ?>" style="width: 400px;"/><br>
				<?php
			if ($_SESSION['user_role'] == "ROLE_ADMIN") { ?>
				Логін: 
				<input type="text" name="tl<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['teacherLogin']; ?>" style="font-weight: bold;" />
				&nbsp; &nbsp;
				Пароль: 
				<input type="text" name="tp<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['teacherPsswd']; ?>" style="width: 100px;" /><br><?php
			}	?>
				<input type="submit" name="sbtt<?php echo $query_row['id']; ?>" value="Зберегти" />
			</div>
<?php
		} // Перевірка, чи є посилання на викладача в семестрових відомостях і рейтингу НПП
		$TeacherInProgressTeacherMarkAndTeachMatrix_query = "
				(	SELECT id FROM progress_teacher_mark 
					WHERE teacher_link = ".$query_row['id']."
				) UNION 
				(	SELECT id FROM tsupp_teachrate.teachMatrix 
					WHERE teacherId = ".$query_row['id']."
				)";
		$TeacherInProgressTeacherMarkAndTeachMatrix_result = 
			mysqli_query($conn, $TeacherInProgressTeacherMarkAndTeachMatrix_query) or 
			die("Помилка сервера при запиті<br>".$TeacherInProgressTeacherMarkAndTeachMatrix_query." : ".mysqli_error($conn));
?>	</td>
		<td><?php if ($TrueAdmin and (mysqli_num_rows($TeacherInProgressTeacherMarkAndTeachMatrix_result) == 0)) 
								echo paramChekerInline("delt".$query_row['id'],$_POST['delt'.$query_row['id']],"","").
									((mb_strpos($query_row['teacher_pobatkovi'], "звільн")) ? 
										"<span style=\"color: red;\">".
										($query_row['teacher_surname']."&nbsp;".mb_substr($query_row['teacher_name'],0,1).".".
											mb_substr($query_row['teacher_pobatkovi'],0,1).".")."</span>" : 
										bold($query_row['teacher_surname']."&nbsp;".mb_substr($query_row['teacher_name'],0,1).".".
										mb_substr($query_row['teacher_pobatkovi'],0,1).".")
									);
							else echo $query_row['teacher_surname']."&nbsp;".mb_substr($query_row['teacher_name'],0,1).".".
												mb_substr($query_row['teacher_pobatkovi'],0,1)."."; 
?>	</td>
	</tr>
<?php 
		$icnt++; 
		if (mb_strpos($query_row['teacher_pobatkovi'], "тимчасов")) $icnt_temp++; 
		if (mb_strpos($query_row['teacher_pobatkovi'], "декрет")) $icnt_decr++; 
		if (mb_strpos($query_row['teacher_pobatkovi'], "сумісн")) $icnt_parttime++; 
		if (mb_strpos($query_row['teacher_pobatkovi'], "погодин")) $icnt_hourly++;
		if (mb_strpos($query_row['teacher_pobatkovi'], "звільн")) $icnt_released++;
	}
?>
	<tr><td colspan=9 style="text-align: right; color: blue;">Усього в базі: <?php echo bold($icnt); ?>,
			зокрема постійно - 
			<?php echo bold($icnt - $icnt_temp - $icnt_decr - $icnt_parttime - $icnt_released - $icnt_hourly); ?>, 
							тимчасово - <?php echo bold($icnt_temp); ?>, у декреті - <?php echo bold($icnt_decr); ?>, 
 							за сумісництвом - <?php echo bold($icnt_parttime); ?>, 
 							погодинно - <?php echo bold($icnt_hourly); ?>,
 							звільнено - <?php echo bold($icnt_released); ?>
			</td>
		<td><?php
if ($TrueAdmin) { ?>
			<input type="checkbox" id="delte" name="delte" 
								onclick="if (confirm('Дійсно видалити позначених викладачів?')) submit();" class="del" />
						<label for="delte" class="del">Видалити</label><?php
} ?>
		</td></tr>
</table>
