<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль faculties.php</p>"; require "footer.php"; exit(); }
?><br>
<table style="margin-left: 0%; width: 70%;">
	<tr><td colspan=10><?php $_POST['addf'] = isset($_POST['addf']) ? $_POST['addf'] : 0;
		echo paramCheker("addf",$_POST['addf'],"Додати новий інститут (факультет)",
								"onchange=\"submit()\""); ?></td></tr>
<?php
	if (!empty($_POST['addf'])) {
		$_POST['ftoadd'] = isset($_POST['ftoadd']) ? $_POST['ftoadd'] : "";
		$_POST['fctoadd'] = isset($_POST['fctoadd']) ? $_POST['fctoadd'] : "";
		if (!empty($_POST['ftoadd']) and !empty($_POST['fctoadd'])) { 
			// перевірка, чи вже є такий
			$FQuery = "SELECT * FROM catalogFakultet WHERE fakultet_name=\"".$_POST['ftoadd']."\"";
			$query_result = mysqli_query($conn, $FQuery) or 
						die("<tr><td colspan=10>Помилка сервера при запиті<br>".$FQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
         $ifacn = 0; while ($query_row = mysqli_fetch_array($query_result)) $ifacn++;
			$FQuery = "SELECT * FROM catalogFakultet WHERE fakultet_shufr=\"".$_POST['fctoadd']."\"";
			$query_result = mysqli_query($conn, $FQuery) or 
						die("<tr><td colspan=10>Помилка сервера при запиті<br>".$FQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
         $ifacc = 0; while ($query_row = mysqli_fetch_array($query_result)) $ifacc++;
			if (($ifacn == 0) and ($ifacc == 0)) {
				$AddFQuery = "insert into catalogFakultet values
										(\"\",\"".$_POST['ftoadd']."\",\"".$_POST['fctoadd']."\")"; // echo $AddFQuery;
			   $query_result = mysqli_query($conn, $AddFQuery) or 
							die("<tr><td colspan=10>Помилка сервера при запиті<br>".$AddFQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
			} else {
?>
<tr><td colspan=10 style="color: red; font-weight: normal;">
			Інститут (факультет) з назвою <? echo bold($_POST['ftoadd']); ?>, 
			кодом <? echo bold($_POST['fctoadd']); ?> в довіднику вже є!</td></tr>
<?php
			}
		}
?><tr><td colspan=10>
Назва нового інституту (факультету): <input type="text" name="ftoadd" style="font-weight: bold;" 
													value="<?php echo $_POST['ftoadd']; ?>" />
Шифр: <input type="text" name="fctoadd" style="font-weight: bold;" 
			value="<?php echo $_POST['fctoadd']; ?>" /> &nbsp; 
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
	}
// Завантажити перелік
	$FQuery = "SELECT * FROM catalogFakultet ORDER BY id";
	$query_result = mysqli_query($conn, $FQuery) or 
			die("Помилка сервера при запиті<br>".$FQuery." : ".mysqli_error($conn));
	if (!empty($_POST['delfac'])) { // Натиснуто кнопку "Видалити"
		while ($query_row = mysqli_fetch_array($query_result)) { // Обробка позначок "На видалення"
			if (!empty($_POST['delf'.$query_row['id']])) { // обробка позначки "На видалення"
				$DeleteFQuery = "DELETE FROM catalogFakultet WHERE id='".$query_row['id']."'";
				$df_result = mysqli_query($conn, $DeleteFQuery) or 
					die("Помилка сервера при запиті<br>".$DeleteFQuery." : ".mysqli_error($conn));
			}
		}
	}
	$query_result = mysqli_query($conn, $FQuery) or 
			die("Помилка сервера при запиті<br>".$FQuery." : ".mysqli_error($conn));
	while ($query_row = mysqli_fetch_array($query_result)) {
		// Обробка позначок "Змінити"
		$_POST['tbxf'.$query_row['id']] = isset($_POST['tbxf'.$query_row['id']]) ?
															$_POST['tbxf'.$query_row['id']] : "";
		$_POST['tbxfc'.$query_row['id']] = isset($_POST['tbxfc'.$query_row['id']]) ?
															$_POST['tbxfc'.$query_row['id']] : "";
		if (!empty($_POST['tbxf'.$query_row['id']]) and
			 !empty($_POST['tbxfc'.$query_row['id']])) { // обробка позначки "Змінити"
			// перевірка, чи вже є такий
			$fq1 = "SELECT * FROM catalogFakultet 
						WHERE fakultet_name=\"".$_POST['tbxf'.$query_row['id']]."\" AND 
								id <> '".$query_row['id']."'";
			$fq1_result = mysqli_query($conn, $fq1) or 
						die("<tr><td colspan=10>Помилка сервера при запиті<br>".$fq1.
							" : ".mysqli_error($conn)."</td></tr></table>");
         $ifacn = 0; while ($fq1_row = mysqli_fetch_array($fq1_result)) $ifacn++;
			$fq2 = "SELECT * FROM catalogFakultet 
						WHERE fakultet_shufr=\"".$_POST['tbxfc'.$query_row['id']]."\" AND 
								id <> '".$query_row['id']."'";
			$fq2_result = mysqli_query($conn, $fq2) or 
						die("<tr><td colspan=10>Помилка сервера при запиті<br>".$fq2.
							" : ".mysqli_error($conn)."</td></tr></table>");
         $ifacc = 0; while ($fq2_row = mysqli_fetch_array($fq2_result)) $ifacc++;
			if (($ifacn == 0) and ($ifacc == 0)) {
				$UpdateFQuery = "UPDATE catalogFakultet 
						SET fakultet_name = \"".$_POST['tbxf'.$query_row['id']]."\",
							fakultet_shufr = \"".$_POST['tbxfc'.$query_row['id']]."\"
											WHERE id='".$query_row['id']."'";
				$uf_result = mysqli_query($conn, $UpdateFQuery) or 
						die("<tr><td colspan=10>Помилка сервера при запиті<br>".$UpdateFQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
?>
<tr><td colspan=10 style="color: green; font-weight: normal;">
			Інститут (факультет) з назвою <? echo bold($_POST['tbxf'.$query_row['id']]); ?>, 
			кодом <? echo bold($_POST['tbxfc'.$query_row['id']]); ?> успішно збережено</td></tr>
<?php
			} else {
?>
<tr><td colspan=10 style="color: red; font-weight: normal;">
			Інститут (факультет) з назвою <? echo bold($_POST['tbxf'.$query_row['id']]); ?>, 
			кодом <? echo bold($_POST['tbxfc'.$query_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
			}
		}
	}
//	mysqli_free_result($query_result);
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>Назва інституту (факультету)</th>
		<th rowspan=2>Шифр</th><th rowspan=2>Кільк.<br>груп</th><th rowspan=2>Кільк.&nbsp;студентів<br>(у т.ч. д.ф.н.)</th>
		<th rowspan=2>Кільк.<br>кафедр</th><th rowspan=2>Кільк.<br>виклад.</th><th rowspan=2>Кільк.<br>РНП</th>
		<th colspan=2>Дії з обʼєктом</th></tr>
	<tr><th>Редагування</th><th>До видалення</th></tr>
<?php
// Завантажити перелік
	$FacQuery = "SELECT * FROM catalogFakultet ORDER BY fakultet_name";
	$query_result = mysqli_query($conn, $FacQuery) or 
			die("Помилка сервера при запиті<br>".$FacQuery." : ".mysqli_error($conn));

	$icnt = 0; $GroupsTotal = 0; $StudsTotal = 0; $StudsFullTimeTotal = 0; $DepartsTotal = 0; $TeachersTotal = 0; $PlansTotal = 0;
	while ($query_row = mysqli_fetch_array($query_result)) {
		$_POST['cbx'.$query_row['id']] = isset($_POST['cbx'.$query_row['id']]) ? 
															$_POST['cbx'.$query_row['id']] : "";
		$_POST['delf'.$query_row['id']] = isset($_POST['delf'.$query_row['id']]) ?
															$_POST['delf'.$query_row['id']] : "";

		$Groups_query = "SELECT id FROM catalogGroup WHERE shufr_fak = ".$query_row['id'];
		$Groups_result = mysqli_query($conn, $Groups_query) or 
			die("Помилка сервера при запиті<br>".$Groups_query." : ".mysqli_error($conn));
		$GroupsCount = mysqli_num_rows($Groups_result); $GroupsTotal += $GroupsCount;

		$Studs_query = "SELECT a.id FROM catalogStudent a, catalogGroup b 
										WHERE a.group_link = b.id AND b.shufr_fak = ".$query_row['id'];
		$Studs_result = mysqli_query($conn, $Studs_query) or 
			die("Помилка сервера при запиті<br>".$Studs_query." : ".mysqli_error($conn));
		$StudsCount = mysqli_num_rows($Studs_result); $StudsTotal += $StudsCount;

		$StudsFullTime_query = 'SELECT a.id FROM catalogStudent a, catalogGroup b, catalogWorkEduPlan c 
														WHERE a.group_link = b.id AND b.plan_id = c.id AND 
																	c.edu_form_id = 1 AND b.shufr_fak = '.$query_row['id'];
		$StudsFullTime_result = mysqli_query($conn, $StudsFullTime_query) or 
			die("Помилка сервера при запиті<br>".$StudsFullTime_query." : ".mysqli_error($conn));
		$StudsFullTimeCount = mysqli_num_rows($StudsFullTime_result); $StudsFullTimeTotal += $StudsFullTimeCount;

		$Departs_query = "SELECT id FROM catalogDepartment WHERE fakultet_id = ".$query_row['id'];
		$Departs_result = mysqli_query($conn, $Departs_query) or 
			die("Помилка сервера при запиті<br>".$Departs_query." : ".mysqli_error($conn));
		$DepartsCount = mysqli_num_rows($Departs_result); $DepartsTotal += $DepartsCount;

		$Teachers_query = "SELECT a.id FROM catalogTeacher a, catalogDepartment b 
											WHERE a.kaf_link = b.id AND b.fakultet_id = ".$query_row['id'];
		$Teachers_result = mysqli_query($conn, $Teachers_query) or 
			die("Помилка сервера при запиті<br>".$Teachers_query." : ".mysqli_error($conn));
		$TeachersCount = mysqli_num_rows($Teachers_result); $TeachersTotal += $TeachersCount;

		$Plans_query = "SELECT id FROM catalogWorkEduPlan	WHERE faculty_id = ".$query_row['id'];
		$Plans_result = mysqli_query($conn, $Plans_query) or 
			die("Помилка сервера при запиті<br>".$Plans_query." : ".mysqli_error($conn));
		$PlansCount = mysqli_num_rows($Plans_result); $PlansTotal += $PlansCount;
?>
	<tr>		<td style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>                        
		<td><?php echo $query_row['fakultet_shufr']; ?></td><td><?php echo $GroupsCount; ?></td>
		<td><?php echo $StudsCount." (".$StudsFullTimeCount.")"; ?></td>
		<td><?php echo $DepartsCount; ?></td><td><?php echo $TeachersCount; ?></td><td><?php echo $PlansCount; ?></td>
		<td><input type="checkbox" id="сbx<?php echo $query_row['id']; ?>" 
						name="cbx<?php echo $query_row['id']; ?>" class="del" />
			<label for="сbx<?php echo $query_row['id']; ?>" class="del">Розкрити</label>
			<div> Назва: 
				<input type="textbox" name="tbxf<?php echo $query_row['id']; ?>" 
						ondblclick="submit()" style="font-weight: bold;"
						value="<?php echo $query_row['fakultet_name']; ?>" /><br>
				Шифр: 	
				<input type="textbox" name="tbxfc<?php echo $query_row['id']; ?>" 
						style="font-weight: bold;"
						value="<?php echo $query_row['fakultet_shufr']; ?>" /><br>
				<input type="submit" name="sbt" value="Зберегти" />			
			</div>
		</td>
		<td><?php
		if ($TrueAdmin and ($GroupsCount == 0) and ($StudsCount == 0) and ($DepartsCount == 0) and ($TeachersCount == 0))
			echo paramChekerInline("delf".$query_row['id'],$_POST['delf'.$query_row['id']],"","")."&nbsp;";
		echo $query_row['fakultet_shufr']; ?></td>
	</tr>
<?php 
		$icnt++;
	}
	if ($TrueAdmin) { ?>
		<tr><td colspan=3>Усього: <?php echo bold($icnt); ?></td>
		<td><?php	echo bold($GroupsTotal); ?></td><td><?php	echo bold($StudsTotal." (".$StudsFullTimeTotal.")"); ?></td>
		<td><?php	echo bold($DepartsTotal); ?></td><td><?php	echo bold($TeachersTotal); ?></td>
		<td><?php	echo bold($PlansTotal); ?></td><td></td>
		<td><input type="checkbox" id="delfac" name="delfac" 
								onclick="if (confirm('Дійсно видалити позначені інститути?') submit();" class="del" />
						<label for="delfac" class="del">Видалити</label></td></tr>
<?php
	} ?>
</table>
