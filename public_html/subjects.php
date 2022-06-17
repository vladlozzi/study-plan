<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль subjects.php</p>"; require "footer.php"; exit(); }
// Перелік кафедр для вибору
$DepartsQuery = "SELECT id, nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf";
$_POST['printver'] = isset($_POST['printver']) ? $_POST['printver'] : "";
$_POST['deptosel'] = isset($_POST['deptosel']) ? $_POST['deptosel'] : "";
$_POST['hidesubj'] = isset($_POST['hidesubj']) ? $_POST['hidesubj'] : "";
echo selectCommonSelectAutoSubmit
	("На кафедрі: ", "deptosel", $conn, $DepartsQuery, "id", $_POST['deptosel'], "nazva_kaf", "");
echo "&nbsp; &nbsp;".paramChekerInline("hidesubj", $_POST['hidesubj'], 
			"Сховати дисципліни, яких немає в робочих навчальних планах", "onchange=\"submit()\"");
echo "&nbsp; &nbsp;".paramCheker("printver", $_POST['printver'], 
			"Версія для друку", "onchange=\"submit()\"");
// Перелік дисциплін, закріплених за кафедрами
if (empty($_POST['deptosel']))
	$SubjQuery = "SELECT a.id, b.shufr_kaf, a.shufr_kaf AS shufr_kaf_subj, a.naz_dus, a.subject_name_eng
					FROM catalogSubject a, catalogDepartment b 
					WHERE a.shufr_kaf = b.id ORDER BY b.shufr_kaf, a.naz_dus";
else /* Перелік дисциплін, закріплених за вибраною кафедроб */
	$SubjQuery = "SELECT a.id, b.shufr_kaf, a.shufr_kaf AS shufr_kaf_subj, a.naz_dus, a.subject_name_eng 
					FROM catalogSubject a, catalogDepartment b 
					WHERE a.shufr_kaf = b.id AND a.shufr_kaf = \"".$_POST['deptosel']."\"
					ORDER BY a.naz_dus";
?>
<table <?php if (empty($_POST['printver'])) { ?> class="scrolling-table" <?php } ?>
			style="margin-left: 0%; width: 1300px;">
<thead>
	<tr style="width: 1300px;"><td colspan=7>
<?php 
$_POST['addsu'] = isset($_POST['addsu']) ? $_POST['addsu'] : 0;
if (empty($_POST['printver']))
	if ($TrueAdmin) echo paramCheker("addsu", $_POST['addsu'], "Додати нову дисципліну",
																		"onchange=\"submit()\""); ?></td></tr>
<?php 
if (!empty($_POST['addsu'])) {
	$_POST['deptoadd'] = isset($_POST['deptoadd']) ? $_POST['deptoadd'] : $_POST['deptosel'];
	$_POST['subtoadd'] = isset($_POST['subtoadd']) ? $_POST['subtoadd'] : "";
	$_POST['subentoadd'] = isset($_POST['subentoadd']) ? $_POST['subentoadd'] : "";
	if (!empty($_POST['deptoadd']) and !empty($_POST['subtoadd'])) { 
		// перевірка, чи вже є така дисципліна на кафедрі
		$IsSubQuery = "SELECT * FROM catalogSubject 
							WHERE shufr_kaf = \"".$_POST['deptoadd']."\" AND
									naz_dus = \"".$_POST['subtoadd']."\"";
		$IsSubQuery_result = mysqli_query($conn, $IsSubQuery) or 
					die("<tr style=\"width: 1300px;\"><td colspan=5>Помилка сервера при запиті<br>".$IsSubQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
		$isub = mysqli_num_rows($IsSubQuery_result);
		if ($isub == 0) {
			$AddSubQuery = "insert into catalogSubject values
									(\"\",\"".$_POST['deptoadd']."\",
										\"".$_POST['subtoadd']."\", \"".$_POST['subentoadd']."\", 
										\"\", \"\", \"\")"; // echo $AddSubQuery;
			$AddSubQuery_result = mysqli_query($conn, $AddSubQuery) or 
							die("<tr><td colspan=5>Помилка сервера при запиті<br>".$AddSubQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
			logData($_SESSION['user_id'], $_SESSION['user_role'], '0', 
								str_replace("'", "\'", $AddSubQuery));
?>
<tr style="width: 1300px;"><td colspan=7 style="color: green; font-weight: normal;">
			Дисципліну "<? echo bold($_POST['subtoadd']); ?>" успішно додано на вибрану кафедру</td></tr>
<?php
		} else {
?>
<tr style="width: 1300px;"><td colspan=7 style="color: red; font-weight: normal;">
			Дисципліна "<? echo bold($_POST['subtoadd']); ?>" на вибраній кафедрі вже є!</td></tr>
<?php
		}
	}
?><tr style="width: 1300px;"><td colspan=7>
<?php echo selectCommonSelect
		("На кафедру: ", "deptoadd", $conn, $DepartsQuery, "id", $_POST['deptoadd'], "nazva_kaf", ""); ?><br>
Назва дисципліни (укр.): 
<input type="text" name="subtoadd" 
	style="font-weight: bold; width: 640px;" value="<?php echo $_POST['subtoadd']; ?>" /><br>
Назва дисципліни (англ.): 
<input type="text" name="subentoadd" 
	style="font-weight: bold; width: 640px;" value="<?php echo $_POST['subentoadd']; ?>" /><br>
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
}

// Завантажити перелік дисциплін перед видаленням
$query1_result = mysqli_query($conn, $SubjQuery) or die("Помилка сервера при запиті<br>".$SubjQuery." : ".mysqli_error($conn));
if (!empty($_POST['dels'])) { // Натиснуто кнопку "Видалити"
	while ($query1_row = mysqli_fetch_array($query1_result)) { // Обробка позначок "На видалення"
		if (!empty($_POST['dels'.$query1_row['id']])) { // обробка позначки "На видалення"
			$DeleteSubjQuery = "DELETE FROM catalogSubject WHERE id = ".$query1_row['id'];
			$ds_result = mysqli_query($conn, $DeleteSubjQuery) or 
				die("Помилка сервера при запиті<br>".$DeleteSubjQuery." : ".mysqli_error($conn));
			logData($_SESSION['user_id'], $_SESSION['user_role'], '0', $DeleteSubjQuery);
		}
	}
}

// Завантажити перелік дисциплін і змінити позначену дисципліну
$query2_result = mysqli_query($conn, $SubjQuery) or die("Помилка сервера при запиті<br>".$SubjQuery." : ".mysqli_error($conn));
while ($query2_row = mysqli_fetch_array($query2_result)) {
	// Обробка позначок "Змінити"
	$_POST['sbt'.$query2_row['id']] = isset($_POST['sbt'.$query2_row['id']]) ?
														$_POST['sbt'.$query2_row['id']] : "";
	$_POST['deps'.$query2_row['id']] = isset($_POST['deps'.$query2_row['id']]) ?
														$_POST['deps'.$query2_row['id']] : "";
	$_POST['tbxs'.$query2_row['id']] = isset($_POST['tbxs'.$query2_row['id']]) ?
														$_POST['tbxs'.$query2_row['id']] : "";
	$_POST['tbxse'.$query2_row['id']] = isset($_POST['tbxse'.$query2_row['id']]) ?
														$_POST['tbxse'.$query2_row['id']] : "";
	if (!empty($_POST['sbt'.$query2_row['id']]) and
		 !empty($_POST['deps'.$query2_row['id']]) and
		 !empty($_POST['tbxs'.$query2_row['id']])) { // обробка кнопки "Зберегти зміни"
//		echo " | id=".$query2_row['id'];
		// перевірка, чи вже є така дисципліна
		$SUQuery = "SELECT * FROM catalogSubject
						WHERE naz_dus = \"".$_POST['tbxs'.$query2_row['id']]."\" AND 
								shufr_kaf = \"".$_POST['deps'.$query2_row['id']]."\" AND id <> ".$query2_row['id'];
		$SUQuery_result = mysqli_query($conn, $SUQuery) or 
					die("<tr><td colspan=5>Помилка сервера при запиті<br>".$SUQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
		$isubj = mysqli_num_rows($SUQuery_result);
		if ($isubj == 0) {
			$UpdateSQuery = "UPDATE catalogSubject
									SET shufr_kaf = \"".$_POST['deps'.$query2_row['id']]."\", 
										naz_dus = \"".$_POST['tbxs'.$query2_row['id']]."\", 
										subject_name_eng = \"".$_POST['tbxse'.$query2_row['id']]."\" 
									WHERE id = ".$query2_row['id']; // echo " ",$UpdateGQuery;
			$UpdateSQuery_result = mysqli_query($conn, $UpdateSQuery) or 
								die("<tr><td colspan=5>Помилка сервера при запиті<br>".$UpdateGQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
			logData($_SESSION['user_id'], $_SESSION['user_role'], '0', 
								str_replace("'", "\'", $UpdateSQuery));
		} else {
?>
<tr style="width: 1300px;"><td colspan=5 style="color: red; font-weight: normal;">
			Дисципліна <?php echo bold($_POST['tbxs'.$query2_row['id']]); ?> на вибраній кафедрі вже є!</td></tr>
<?php
		}
	}
}

?>
	<tr style="width: 1300px;"><th>Код</th><th style="width: 80px;">Шифр кафедри</th>
		<th style="width: 40px;">№</th><th>Повна назва дисципліни укр./англ.</th>
		<th style="width: 130px;">Кількість РНП</th>
		<th><?php if (empty($_POST['printver'])) { ?>Редагування<?php } ?></th>
		<th><?php if (empty($_POST['printver'])) { ?>До видалення<?php } ?></th></tr>
</thead>
<tbody style="width: 1300px; height: 525px;">
<?php
// Завантажити перелік дисциплін
	$query_result = mysqli_query($conn, $SubjQuery) or 
			die("Помилка сервера при запиті<br>".$SubjQuery." : ".mysqli_error($conn));
	$icnt = 0; $icnta = 0; $icntshown = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { $icnt++;
		$PositionsQuery = "SELECT COUNT(id) AS cnt FROM plan_work_subj_study 
											WHERE subject_id = ".$query_row['id'];
		$PQ_result = mysqli_query($conn, $PositionsQuery) 
								or die("Помилка сервера при запиті<br>".$PositionsQuery." : ".mysqli_error($conn));
		$PQ_row = mysqli_fetch_array($PQ_result);
		$_POST['cbxs'.$query_row['id']] = isset($_POST['cbxs'.$query_row['id']]) ? 
															$_POST['cbxs'.$query_row['id']] : "";
		$_POST['dels'.$query_row['id']] = isset($_POST['dels'.$query_row['id']]) ?
															$_POST['dels'.$query_row['id']] : "";
		if (empty($_POST['hidesubj']) or $PQ_row['cnt'] > 0) { $icntshown++;
			$bkgr = ($icntshown % 2 == 0) ? " background-color: RGB(247, 247, 255);" : "";
?>
	<tr style="width: 1300px;">	
		<td style=<? echo "\"text-align: right; $bkgr\""; ?>><?php echo $query_row['id']; ?></td>
		<td style=<? echo "\"text-align: left; width: 80px; $bkgr\""; ?>><?php echo $query_row['shufr_kaf']; ?></td>                        
		<td style=<? echo "\"text-align: right; width: 40px; $bkgr\""; ?>><?php echo $icntshown; ?></td>
		<td style=<? echo "\"text-align: left; $bkgr\""; ?>>
			<?php echo $query_row['naz_dus']." / ".$query_row['subject_name_eng']; ?></td>                        
		<td style=<? echo "\"width: 130px; $bkgr\""; ?>><?php 
			echo ($PQ_row['cnt'] == 0)  
							? "<span style=\"color: red; font-size: 100%;\">в РНП немає</span>"	: $PQ_row['cnt']; 
			if ($PQ_row['cnt'] > 0) {
				$Plans_query = "SELECT a.id, a.plan_id, b.reg_number
												FROM plan_work_subj_study a, catalogWorkEduPlan b 
												WHERE a.plan_id = b.id AND a.subject_id = ".$query_row['id']." 
												ORDER BY a.plan_id";
				$Plans_result = mysqli_query($conn, $Plans_query) 
												or die("Помилка сервера при запиті<br>".$Plans_query." : ".mysqli_error($conn)); 
?>		<details><summary>Коди,&nbsp;шифр</summary><?php
				while ($Plans_row = mysqli_fetch_array($Plans_result)) {
					echo $Plans_row['plan_id']." - "; 
?>			<span style="font-size: 70%;"><?php
					echo $Plans_row['reg_number']; ?></span><?php
					echo " (".$Plans_row['id'].")<br>";
				} 
?>		</details><?php
			}
			if ($PQ_row['cnt'] == 0) $icnta++; ?></td>                        
		<td style=<? echo "\"$bkgr\""; ?>><?php 
			if (empty($_POST['printver']))
				if ($TrueAdmin) echo paramChekerInline("cbxs".$query_row['id'], $_POST['cbxs'.$query_row['id']], 
																								"Розкрити", "onchange=\"submit()\"");
			if (!empty($_POST['cbxs'.$query_row['id']])) {
?>
			<div><?php 
				echo selectCommonSelect
					("Кафедра: ", "deps".$query_row['id'], $conn, $DepartsQuery, "id", 
						$query_row['shufr_kaf_subj'], "nazva_kaf", 
						"style=\"width: 385px; font-weight: bold; \""); ?><br>
				Назва дисципліни (укр.): 
				<input type="textbox" name="tbxs<?php echo $query_row['id']; ?>" 
						style="font-weight: bold; width: 400px;"
						value="<?php echo $query_row['naz_dus']; ?>" /><br>
				Назва дисципліни (англ.): 
				<input type="textbox" name="tbxse<?php echo $query_row['id']; ?>" 
						style="font-weight: bold; width: 400px;"
						value="<?php echo $query_row['subject_name_eng']; ?>" /><br>
				<input type="submit" name="sbt<?php echo $query_row['id']; ?>" value="Зберегти" />
			</div>
<?php		} ?>
		</td>
		<td style=<?php echo "\"$bkgr\""; ?>>
<?php 	if (empty($_POST['printver']) and $TrueAdmin) { 
					if ($PQ_row['cnt'] == 0) { ?>
				<label><input type="checkbox" class="checkbox" id="dels<?php echo $query_row['id']; ?>" 
								name="dels<?php echo $query_row['id']; ?>" 
							<?php if ($_POST['dels'.$query_row['id']]) echo "checked"; ?> 					 	
						>
						<span class="checkbox-custom"></span>
				</label>
<?php 		} ?> 
				<span><?php echo $icntshown; ?></span>
<?php		} ?>
		</td>
	</tr>
<?php 
		}
	}
?>
</tbody>
<tfoot style="width: 1300px;">
		<tr style="width: 1300px;">
		<td colspan=4 style="text-align: right; width: 1201px;">
				Усього: <?php echo bold($icnt); ?>, з них немає в РНП: 
		<?php echo (empty($_POST['hidesubj'])) ? bold($icnta) : bold($icnt - $icntshown); ?></td><td></td><td></td>
		<td style="width: 90px;"><?php 
if (empty($_POST['printver']) and $TrueAdmin) { ?>
			<input type="checkbox" id="dels" name="dels" 
				onclick="if (confirm('Дійсно видалити позначені дисципліни?')) submit();" class="del" />
			<label for="dels" class="del">Видалити</label><?php
} ?>
		</td></tr>
</tfoot>
</table>
