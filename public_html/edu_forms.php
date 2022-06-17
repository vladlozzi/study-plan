<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль edu_forms.php</p>"; require "footer.php"; exit(); }
?><br>
<table style="margin-left: 0%; width: 70%;">
	<tr><td colspan=5><?php $_POST['addsf'] = isset($_POST['addsf']) ? $_POST['addsf'] : 0;
		echo paramCheker("addsf",$_POST['addsf'],"Додати нову форму навчання",
								"onchange=\"submit()\""); ?></td></tr>
<?php
	if (!empty($_POST['addsf'])) {
		$_POST['sfntoadd'] = isset($_POST['sfntoadd']) ? $_POST['sfntoadd'] : "";
		$_POST['sfctoadd'] = isset($_POST['sfctoadd']) ? $_POST['sfctoadd'] : "";
		if (!empty($_POST['sfntoadd']) and !empty($_POST['sfctoadd'])) { 
			// перевірка, чи вже є така форма навчання
			$FQuery = "SELECT * FROM catalogEduForm WHERE edu_form=\"".$_POST['sfntoadd']."\"";
			$query_result = mysqli_query($conn, $FQuery) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$FQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
         $isfn = 0; while ($query_row = mysqli_fetch_array($query_result)) $isfn++;
			if ($isfn == 0) {
				$AddFQuery = "INSERT INTO catalogEduForm VALUES
										(\"\",\"".$_POST['sfntoadd']."\",\"".$_POST['sfctoadd']."\")"; // echo $AddFQuery;
			   $query_result = mysqli_query($conn, $AddFQuery) or 
							die("<tr><td colspan=5>Помилка сервера при запиті<br>".$AddFQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
?>
<tr><td colspan=5 style="color: green; font-weight: normal;">
			Форму навчання <? echo bold($_POST['sfntoadd']); ?>, 
			з коефіцієнтом <? echo bold($_POST['sfctoadd']); ?> успішно додано</td></tr>
<?php
			} else {
?>
<tr><td colspan=5 style="color: red; font-weight: normal;">
			Форма навчання <? echo bold($_POST['sfntoadd']); ?>, 
			з коефіцієнтом <? echo bold($_POST['sfctoadd']); ?> в довіднику вже є!</td></tr>
<?php
			}
		}
?><tr><td colspan=5>
Назва нової форми навчання: <input type="text" name="sfntoadd" style="font-weight: bold;" 
													value="<?php echo $_POST['sfntoadd']; ?>" />
Коефіцієнт: <input type="text" name="sfctoadd" style="font-weight: bold;" 
			value="<?php echo $_POST['sfctoadd']; ?>" /> &nbsp; 
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
	}
// Завантажити перелік
	$FQuery = "SELECT * FROM catalogEduForm ORDER BY id";
	$query_result = mysqli_query($conn, $FQuery) or 
			die("Помилка сервера при запиті<br>".$FQuery." : ".mysqli_error($conn));
	if (!empty($_POST['delsf'])) { // Натиснуто кнопку "Видалити"
		while ($query_row = mysqli_fetch_array($query_result)) { // Обробка позначок "На видалення"
			if (!empty($_POST['delsf'.$query_row['id']])) { // обробка позначки "На видалення"
				$DeleteFQuery = "DELETE FROM catalogEduForm WHERE id='".$query_row['id']."'";
				$df_result = mysqli_query($conn, $DeleteFQuery) or 
					die("Помилка сервера при запиті<br>".$DeleteFQuery." : ".mysqli_error($conn));
			}
		}
	}
	$query_result = mysqli_query($conn, $FQuery) or 
			die("Помилка сервера при запиті<br>".$FQuery." : ".mysqli_error($conn));
	while ($query_row = mysqli_fetch_array($query_result)) {
		// Обробка позначок "Змінити"
		$_POST['tbxsf'.$query_row['id']] = isset($_POST['tbxsf'.$query_row['id']]) ?
															$_POST['tbxsf'.$query_row['id']] : "";
		$_POST['tbxsfc'.$query_row['id']] = isset($_POST['tbxsfc'.$query_row['id']]) ?
															$_POST['tbxsfc'.$query_row['id']] : "";
		if (!empty($_POST['tbxsf'.$query_row['id']]) and
			 !empty($_POST['tbxsfc'.$query_row['id']])) { // обробка позначки "Змінити"
			// перевірка, чи вже є такий
			$fq1 = "SELECT * FROM catalogEduForm
						WHERE edu_form=\"".$_POST['tbxsf'.$query_row['id']]."\" AND 
								id <> '".$query_row['id']."'";
			$fq1_result = mysqli_query($conn, $fq1) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$fq1.
							" : ".mysqli_error($conn)."</td></tr></table>");
         $isfn = 0; while ($fq1_row = mysqli_fetch_array($fq1_result)) $isfn++;
			if ($isfn == 0) {
				$UpdateFQuery = "UPDATE catalogEduForm 
						SET edu_form = \"".$_POST['tbxsf'.$query_row['id']]."\",
							stat_coef = \"".$_POST['tbxsfc'.$query_row['id']]."\"
											WHERE id='".$query_row['id']."'";
				$uf_result = mysqli_query($conn, $UpdateFQuery) or 
						die("<tr><td colspan=5>Помилка сервера при запиті<br>".$UpdateFQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			} else {
?>
<tr><td colspan=5 style="color: red; font-weight: normal;">
			Форма навчання <? echo bold($_POST['tbxsf'.$query_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
			}
		}
	}
//	mysqli_free_result($query_result);
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>Назва форми навчання</th>
		<th rowspan=2>Коефіцієнт для штатів ПВС<br>(за Постановою КМУ 1134-2002)</th><th colspan=2>Дії з об'єктом</th></tr>
	<tr><th>Змінити назву</th><th>До видалення</th></tr>
<?php
// Завантажити перелік
	$FormsQuery = "SELECT * FROM catalogEduForm ORDER BY id";
	$query_result = mysqli_query($conn, $FormsQuery) or 
			die("Помилка сервера при запиті<br>".$FormsQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbx'.$query_row['id']] = isset($_POST['cbx'.$query_row['id']]) ? 
															$_POST['cbx'.$query_row['id']] : "";
		$_POST['delsf'.$query_row['id']] = isset($_POST['delsf'.$query_row['id']]) ?
															$_POST['delsf'.$query_row['id']] : "";
?>
	<tr>		<td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['edu_form']; ?></td>                        
		<td><?php echo $query_row['stat_coef']; ?></td>                        
		<td><input type="checkbox" id="сbx<?php echo $query_row['id']; ?>" 
						name="cbx<?php echo $query_row['id']; ?>" class="del" />
			<label for="сbx<?php echo $query_row['id']; ?>" class="del">Розкрити</label>
			<div> Назва форми навчання: 
				<input type="textbox" name="tbxsf<?php echo $query_row['id']; ?>" 
						ondblclick="submit()" style="font-weight: bold;"
						value="<?php echo $query_row['edu_form']; ?>" /><br>
				Коефіцієнт: 	
				<input type="textbox" name="tbxsfc<?php echo $query_row['id']; ?>" 
						style="font-weight: bold;"
						value="<?php echo $query_row['stat_coef']; ?>" /><br>
				<input type="submit" name="sbt" value="Зберегти" />			
			</div>
		</td>
		<td><?php echo paramCheker("delsf".$query_row['id'],$_POST['delsf'.$query_row['id']],"",""); ?></td>
	</tr>
<?php 
		$icnt++;
	}
	if ($TrueAdmin) { ?>
		<tr><td colspan=4>Усього: <?php echo bold($icnt); ?></td>
		<td><input type="checkbox" id="delsf" name="delsf" 
								onclick="if (confirm('Дійсно видалити позначені форми навчання?')) submit();" class="del" />
						<label for="delsf" class="del">Видалити</label></td></tr>
<?php
	} ?>
</table>
