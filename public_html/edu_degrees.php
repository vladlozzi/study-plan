<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль edu_degrees.php</p>"; require "footer.php"; exit(); }
?><br>
<table style="margin-left: 0%; width: 70%;">
	<tr><td colspan=5><?php $_POST['adddeg'] = isset($_POST['adddeg']) ? $_POST['adddeg'] : 0;
		echo paramCheker("adddeg",$_POST['adddeg'],"Додати новий ступінь освіти",
								"onchange=\"submit()\""); ?></td></tr>
<?php
	if (!empty($_POST['adddeg'])) {
		$_POST['dgtoadd'] = isset($_POST['dgtoadd']) ? $_POST['dgtoadd'] : "";
		$_POST['sctoadd'] = isset($_POST['sctoadd']) ? $_POST['sctoadd'] : "";
		if (!empty($_POST['dgtoadd']) and !empty($_POST['sctoadd'])) { 
			// перевірка, чи вже є такий ступінь
			$DgQuery = "SELECT * FROM catalogEduDegree WHERE degree_name=\"".$_POST['dgtoadd']."\"";
			$query_result = mysqli_query($conn, $DgQuery) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$DgQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
         $ifacn = 0; while ($query_row = mysqli_fetch_array($query_result)) $ifacn++;
			if ($ifacn == 0) {
				$AddDgQuery = "INSERT INTO catalogEduDegree VALUES
										(\"\",\"".$_POST['dgtoadd']."\",\"".$_POST['sctoadd']."\")"; // echo $AddFQuery;
			   $query_result = mysqli_query($conn, $AddDgQuery) or 
							die("<tr><td colspan=5>Помилка сервера при запиті<br>".$AddDgQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
?>
<tr><td colspan=5 style="color: green; font-weight: normal;">
			Ступінь <? echo bold($_POST['dgtoadd']); ?>, 
			з коефіцієнтом <? echo bold($_POST['sctoadd']); ?> успішно додано</td></tr>
<?php
			} else {
?>
<tr><td colspan=5 style="color: red; font-weight: normal;">
			Ступінь <? echo bold($_POST['dgtoadd']); ?>, 
			з коефіцієнтом <? echo bold($_POST['sctoadd']); ?> в довіднику вже є!</td></tr>
<?php
			}
		}
?><tr><td colspan=5>
Назва ступеню: <input type="text" name="dgtoadd" style="font-weight: bold;" 
													value="<?php echo $_POST['dgtoadd']; ?>" /> &nbsp; 
Коефіцієнт для штатів ПВС (за Постановою КМУ 1134/2002): <input type="text" name="sctoadd" style="font-weight: bold; width: 50px;" 
			value="<?php echo $_POST['sctoadd']; ?>" /> &nbsp; 
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
	}
// Завантажити перелік
	$DgQuery = "SELECT * FROM catalogEduDegree ORDER BY id";
	$query_result = mysqli_query($conn, $DgQuery) or 
			die("Помилка сервера при запиті<br>".$FQuery." : ".mysqli_error($conn));
	if (!empty($_POST['deldeg'])) { // Натиснуто кнопку "Видалити"
		while ($query_row = mysqli_fetch_array($query_result)) { // Обробка позначок "На видалення"
			if (!empty($_POST['deldg'.$query_row['id']])) { // обробка позначки "На видалення"
				$DeleteDgQuery = "DELETE FROM catalogEduDegree WHERE id='".$query_row['id']."'";
				$df_result = mysqli_query($conn, $DeleteDgQuery) or 
					die("Помилка сервера при запиті<br>".$DeleteDgQuery." : ".mysqli_error($conn));
			}
		}
	}
	$query_result = mysqli_query($conn, $DgQuery) or 
			die("Помилка сервера при запиті<br>".$DgQuery." : ".mysqli_error($conn));
	while ($query_row = mysqli_fetch_array($query_result)) {
		// Обробка позначок "Змінити"
		$_POST['tbxdg'.$query_row['id']] = isset($_POST['tbxdg'.$query_row['id']]) ?
															$_POST['tbxdg'.$query_row['id']] : "";
		$_POST['tbxsc'.$query_row['id']] = isset($_POST['tbxsc'.$query_row['id']]) ?
															$_POST['tbxsc'.$query_row['id']] : "";
		if (!empty($_POST['tbxdg'.$query_row['id']]) and
			 !empty($_POST['tbxsc'.$query_row['id']])) { // обробка позначки "Змінити"
			// перевірка, чи вже є такий
			$dgq1 = "SELECT * FROM catalogEduDegree
						WHERE degree_name=\"".$_POST['tbxdg'.$query_row['id']]."\" AND 
								id <> '".$query_row['id']."'";
			$dgq1_result = mysqli_query($conn, $dgq1) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$dgq1.
							" : ".mysqli_error($conn)."</td></tr></table>");
         $idn = 0; while ($dgq1_row = mysqli_fetch_array($dgq1_result)) $idn++;
			if ($idn == 0) {
				$UpdateDgQuery = "UPDATE catalogEduDegree 
						SET degree_name = \"".$_POST['tbxdg'.$query_row['id']]."\",
							stuff_coef = \"".$_POST['tbxsc'.$query_row['id']]."\"
											WHERE id='".$query_row['id']."'";
				$udf_result = mysqli_query($conn, $UpdateDgQuery) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$UpdateDgQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			} else {
?>
<tr><td colspan=5 style="color: red; font-weight: normal;">
			Ступінь <? echo bold($_POST['tbxdg'.$query_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
			}
		}
	}
//	mysqli_free_result($query_result);
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>Назва ступеню освіти</th>
		<th rowspan=2>Коефіцієнт для штатів ПВС<br>(за Постановою КМУ 1134-2002)</th><th colspan=2>Дії з об'єктом</th></tr>
	<tr><th>Змінити назву</th><th>До видалення</th></tr>
<?php
// Завантажити перелік
	$DegsQuery = "SELECT * FROM catalogEduDegree ORDER BY id";
	$query_result = mysqli_query($conn, $DegsQuery) or 
			die("Помилка сервера при запиті<br>".$DegsQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbx'.$query_row['id']] = isset($_POST['cbx'.$query_row['id']]) ? 
															$_POST['cbx'.$query_row['id']] : "";
		$_POST['deldg'.$query_row['id']] = isset($_POST['deldg'.$query_row['id']]) ?
															$_POST['deldg'.$query_row['id']] : "";
?>
	<tr>		<td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['degree_name']; ?></td>                        
		<td><?php echo $query_row['stuff_coef']; ?></td>                        
		<td><input type="checkbox" id="сbx<?php echo $query_row['id']; ?>" 
						name="cbx<?php echo $query_row['id']; ?>" class="del" />
			<label for="сbx<?php echo $query_row['id']; ?>" class="del">Розкрити</label>
			<div> Назва ступеню: 
				<input type="textbox" name="tbxdg<?php echo $query_row['id']; ?>" 
						ondblclick="submit()" style="font-weight: bold;"
						value="<?php echo $query_row['degree_name']; ?>" /><br>
				Коефіцієнт: 	
				<input type="textbox" name="tbxsc<?php echo $query_row['id']; ?>" 
						style="font-weight: bold;"
						value="<?php echo $query_row['stuff_coef']; ?>" /><br>
				<input type="submit" name="sbt" value="Зберегти" />			
			</div>
		</td>
		<td><?php echo paramCheker("deldg".$query_row['id'],$_POST['deldg'.$query_row['id']],"",""); ?></td>
	</tr>
<?php 
		$icnt++;
	}
	if ($TrueAdmin) { ?>
		<tr><td colspan=4>Усього: <?php echo bold($icnt); ?></td>
		<td><input type="checkbox" id="deldeg" name="deldeg" 
								onclick="if (confirm('Дійсно видалити позначені ступені?')) submit();" class="del" />
						<label for="deldeg" class="del">Видалити</label></td></tr>
<?php
	} ?>
</table>
