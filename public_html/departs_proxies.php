<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль departs_proxies.php</p>"; require "footer.php"; exit(); }
// Перелік кафедр для вибору
$DepartsQuery = "SELECT id, nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf";
$DepPQuery = "SELECT a.id, a.kaf_link, b.shufr_kaf, b.nazva_kaf, a.login, a.password, a.email
				FROM catalogDepartOper a, catalogDepartment b
				WHERE a.kaf_link = b.id
				ORDER BY b.nazva_kaf";
?><br>
<table style="margin-left: 0%; width: 100%;">
	<tr><td colspan=8><?php $_POST['adddp'] = isset($_POST['adddp']) ? $_POST['adddp'] : 0;
		echo paramCheker("adddp",$_POST['adddp'],"Додати нового уповноваженого",
								"onchange=\"submit()\""); ?></td></tr>
<?php
// Завантажити перелік уповноважених і змінити позначених
	$query2_result = mysqli_query($conn, $DepPQuery) 
									 or die("Помилка сервера при запиті<br>".$DepPQuery." : ".mysqli_error());
	while ($query_row = mysqli_fetch_array($query2_result)) {
		// Обробка позначок "Змінити"
		$_POST['sbtdp'.$query_row['id']] = isset($_POST['sbtdp'.$query_row['id']]) ?
															$_POST['sbtdp'.$query_row['id']] : "";
		$_POST['dep'.$query_row['id']] = isset($_POST['dep'.$query_row['id']]) ?
															$_POST['dep'.$query_row['id']] : "";
		$_POST['dpl'.$query_row['id']] = isset($_POST['dpl'.$query_row['id']]) ?
															$_POST['dpl'.$query_row['id']] : "";
		$_POST['dpp'.$query_row['id']] = isset($_POST['dpp'.$query_row['id']]) ?
															$_POST['dpp'.$query_row['id']] : "";
		$_POST['dpm'.$query_row['id']] = isset($_POST['dpm'.$query_row['id']]) ?
															$_POST['dpm'.$query_row['id']] : "";
		if (!empty($_POST['sbtdp'.$query_row['id']]) and
			 !empty($_POST['dep'.$query_row['id']]) and
			 !empty($_POST['dpl'.$query_row['id']]) and
			 !empty($_POST['dpp'.$query_row['id']])) { // обробка кнопки "Зберегти зміни"
			// перевірка, чи вже є такий
			$PLQuery = "SELECT * FROM catalogDepartOper
							WHERE login = \"".$_POST['dpl'.$query_row['id']]."\" AND 
									id <> ".$query_row['id'];
			$PLQuery_result = mysqli_query($conn, $PLQuery) or 
						die("<tr><td colspan=7>Помилка сервера при запиті<br>".$PLQuery.
							" : ".mysqli_error()."</td></tr></table>");
			$ilog = 0; while ($PLQuery_row = mysqli_fetch_array($PLQuery_result)) $ilog++;
			if ($ilog == 0) {
				$UpdateDPQuery = "UPDATE catalogDepartOper SET 
											kaf_link = \"".$_POST['dep'.$query_row['id']]."\" ,
											login = \"".$_POST['dpl'.$query_row['id']]."\" ,
											password = \"".$_POST['dpp'.$query_row['id']]."\" ,
											email = \"".$_POST['dpm'.$query_row['id']]."\" 
										WHERE id = ".$query_row['id']; // echo $UpdateDHQuery;
			   $query_result = mysqli_query($conn, $UpdateDPQuery) or 
							die("<tr><td colspan=7>Помилка сервера при запиті<br>".$UpdateDPQuery.
								" : ".mysqli_error()."</td></tr></table>");
			} else {
?>
<tr><td colspan=8 style="color: red; font-weight: normal;">
			Користувач з логіном <?php echo bold($_POST['dpl'.$query_row['id']]); ?> у довіднику вже є!</td></tr>
<?php
			}
		}
	}
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>№</th><th rowspan=2>Кафедра</th>
		<th rowspan=2>Логін</th><th rowspan=2>Пароль</th><th rowspan=2>email</th>
		<th colspan=2>Дії з об'єктом</th></tr>
	<tr><th>Змінити кафедру, логін, пароль</th><th>До видалення</th></tr>
<?php
// Завантажити перелік
	$query_result = mysqli_query($conn, $DepPQuery) or 
			die("Помилка сервера при запиті<br>".$DepPQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { $icnt++;
		$_POST['sendlp'.$query_row['id']] = isset($_POST['sendlp'.$query_row['id']]) ? 
															$_POST['sendlp'.$query_row['id']] : "";
		$_POST['cbxdp'.$query_row['id']] = isset($_POST['cbxdp'.$query_row['id']]) ? 
															$_POST['cbxdp'.$query_row['id']] : "";
		$_POST['deldp'.$query_row['id']] = isset($_POST['deldp'.$query_row['id']]) ?
															$_POST['deldp'.$query_row['id']] : "";
?>
	<tr>		<td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: right;"><?php echo $icnt; ?></td>
		<td style="text-align: left;"><?php echo $query_row['nazva_kaf'].
															" (".$query_row['shufr_kaf'].")"; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['login']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['password']; ?></td>                        		
		<td style="text-align: left;"><input type="checkbox" value="<? echo $_POST['sendlp'.$query_row['id']]; ?>"
								id="sendlp<?php echo $query_row['id']; ?>" name="sendlp<?php echo $query_row['id']; ?>" 
								onclick="if (confirm('Дійсно надіслати логін і пароль уповноваженому ?')) submit();" class="del" />
						<label for="sendlp<?php echo $query_row['id']; ?>" class="del">
								<?php echo $query_row['email']; ?></label>
		</td>                        		
		<td>
<?php 
		if ($TrueAdmin) echo paramCheker("cbxdp".$query_row['id'], $_POST['cbxdp'.$query_row['id']], 
																			"Розкрити/Сховати", "onchange=\"submit()\"");
		if (!empty($_POST['cbxdp'.$query_row['id']])) {
?>
			<div><?php 
			echo selectCommonSelect
			("Кафедра: ", "dep".$query_row['id'], $conn, $DepartsQuery, "id", 
				$query_row['kaf_link'], "nazva_kaf", "style=\"font-weight: bold;\""); ?><br>
				Логін: 
				<input type="text" name="dpl<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['login']; ?>" style="font-weight: bold;" />
				&nbsp; &nbsp;
				Пароль: 
				<input type="text" name="dpp<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['password']; ?>" style="width: 100px;" />
				<br>email: 
				<input type="text" name="dpm<?php echo $query_row['id']; ?>" 
						value="<?php echo $query_row['email']; ?>" style="width: 200px;" />
				<input type="submit" name="sbtdp<?php echo $query_row['id']; ?>" value="Зберегти" />
			</div>
<?php
		} ?>
		</td>
		<td><?php echo paramCheker("deldp".$query_row['id'],$_POST['deldp'.$query_row['id']],"",""); ?></td>
	</tr>
<?php 
	}
	if ($TrueAdmin) { ?>
		<tr><td colspan=7 style="text-align: right;">Усього: <?php echo bold($icnt); ?></td>
		<td><input type="checkbox" id="deldp" name="deldp" 
								onclick="if (confirm('Дійсно видалити позначених уповноважених?')) submit();" class="del" />
						<label for="deldp" class="del">Видалити</label></td></tr>
<?php
	} ?>
</table><br>
<p><?php
$_POST['cbxListAllDeparts'] = isset($_POST['cbxListAllDeparts']) ?	$_POST['cbxListAllDeparts'] : "";
echo paramCheker("cbxListAllDeparts", $_POST['cbxListAllDeparts'], 
																			"Перелік email усіх кафедр", "onchange=\"submit()\"");
if (!empty($_POST['cbxListAllDeparts'])) {
// Завантажити перелік
	$query_result = mysqli_query($conn, $DepPQuery) or 
			die("Помилка сервера при запиті<br>".$DepPQuery." : ".mysqli_error($conn));
	while ($query_row = mysqli_fetch_array($query_result)) echo $query_row['email'].',';
} ?></p>