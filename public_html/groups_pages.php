<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль groups_pages.php</p>"; require "footer.php"; exit(); }
$FacultiesQuery = "SELECT * FROM catalogFakultet ORDER BY fakultet_name";
$PlansQuery = "SELECT id, reg_number FROM catalogWorkEduPlan ORDER BY reg_number";
$TotalGroupsQuery = "
				SELECT a.*, b.fakultet_shufr, c.reg_number AS work_edu_plan
				FROM catalogGroup a, catalogFakultet b, catalogWorkEduPlan c
				WHERE a.shufr_fak = b.id AND a.plan_id = c.id /* AND b.id = 4 AND (a.num_kurs = 'Б1' OR a.num_kurs = 'М1') */
				ORDER BY b.fakultet_shufr, a.num_kurs, a.nazva_grupu"; $rpp = 600; // кількість на сторінку
$FullTimeGroupsQuery = "
				SELECT a.*, b.fakultet_shufr, c.reg_number AS work_edu_plan
				FROM catalogGroup a, catalogFakultet b, catalogWorkEduPlan c
				WHERE a.shufr_fak = b.id AND a.plan_id = c.id AND (a.nazva_grupu LIKE BINARY '%з%') AND c.reg_number LIKE BINARY '%.Д'
				ORDER BY b.fakultet_shufr, a.num_kurs, a.nazva_grupu"; $rpp = 600; // кількість на сторінку
$query_result = mysqli_query($conn, $TotalGroupsQuery) or 
	die("Помилка сервера при запиті<br>".$TotalGroupsQuery." : ".mysqli_error($conn)); /*
$query_result = mysqli_query($conn, $FullTimeGroupsQuery) or 
	die("Помилка сервера при запиті<br>".$FullTimeGroupsQuery." : ".mysqli_error($conn)); */
$TotalRows = mysqli_num_rows($query_result); $nPages = ceil($TotalRows / $rpp);
//$_POST['chkEditMode'] = isset($_POST['chkEditMode']) ? $_POST['chkEditMode'] : "";
$_POST['radPageSelect'] = isset($_POST['radPageSelect']) ? $_POST['radPageSelect'] : "Page1";
?><p style="text-align: center;">Сторінка: <?php 
for ($iPage = 1; $iPage <= $nPages; $iPage++) { ?>
<input type="radio" name="radPageSelect" value="Page<? echo $iPage; ?>" onclick="submit()" 
	<?php if ($_POST['radPageSelect'] == "Page".$iPage) echo "checked"; ?>> 
	<?php echo $iPage; ?> &nbsp; &nbsp; <?php
}
//if ($TrueAdmin) 
//	echo paramChekerInline("chkEditMode", $_POST['chkEditMode'], "Редагування", "onchange=\"submit()\"");
?></p><?php
$PageGroupsQuery = $TotalGroupsQuery." LIMIT $rpp"; // $PageGroupsQuery = $FullTimeGroupsQuery." LIMIT $rpp";
for ($iPage = 1; $iPage <= $nPages; $iPage++) {
	if ($_POST['radPageSelect'] == "Page".$iPage) { 
    $PageGroupsQuery = $TotalGroupsQuery." LIMIT ".(($iPage-1)*$rpp).", $rpp";
//    $PageGroupsQuery = $FullTimeGroupsQuery." LIMIT ".(($iPage-1)*$rpp).", $rpp";
  }
} ?>
<table style="margin-left: 0%; width: 1200px;">
<thead>
	<tr><td colspan=8><?php $_POST['addg'] = isset($_POST['addg']) ? $_POST['addg'] : 0;
if ($TrueAdmin) echo paramCheker("addg", $_POST['addg'], "Додати нову академгрупу", "onchange=\"submit()\""); 
?></td></tr><?php
if (!empty($_POST['addg'])) {
	$_POST['ftoadd'] = isset($_POST['ftoadd']) ? $_POST['ftoadd'] : "";
	$_POST['ctoadd'] = isset($_POST['ctoadd']) ? $_POST['ctoadd'] : "";
	$_POST['gtoadd'] = isset($_POST['gtoadd']) ? $_POST['gtoadd'] : "";
	$_POST['ptoadd'] = isset($_POST['ptoadd']) ? $_POST['ptoadd'] : "";
	if (!empty($_POST['ftoadd']) and !empty($_POST['ctoadd']) and 
			!empty($_POST['gtoadd']) and !empty($_POST['ptoadd'])) { 
	// перевірка, чи вже є така група
		$GTestQuery = "SELECT * FROM catalogGroup WHERE nazva_grupu = \"".$_POST['gtoadd']."\" ";
		$query_result = mysqli_query($conn,  $GTestQuery) or 
					die("<tr><td colspan=8>Помилка сервера при запиті<br>".$GTestQuery.
						" : ".mysqli_error($conn)."</td></tr></table>");
		$ig = 0; while ($query_row = mysqli_fetch_array($query_result)) $ig++;
		if ($ig == 0) {
			$AddGQuery = "insert into catalogGroup values
									(NULL,\"".$_POST['ftoadd']."\", \"".$_POST['ctoadd']."\", 
										\"".$_POST['gtoadd']."\", \"".$_POST['ptoadd']."\", 0
									)"; // echo $AddGQuery;
			$query_result = mysqli_query($conn, $AddGQuery) or 
						die("<tr><td colspan=8>Помилка сервера при запиті<br>".$AddGQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$selectIdQuery = "SELECT id FROM catalogGroup WHERE nazva_grupu = \"".$_POST['gtoadd']."\" ";
			$query_result = mysqli_query($conn, $selectIdQuery) or 
						die("<tr><td colspan=8>Помилка сервера при запиті<br>".$selectIdQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$query_row = mysqli_fetch_array($query_result); ?>
<tr><td colspan=8 style="color: green; font-weight: normal;">
			Академгрупу <?php echo bold($_POST['gtoadd']); ?> успішно додано. Код: <?php echo bold($query_row['id']); ?></td></tr><?php
		} else {
			$selectIdQuery = "SELECT id FROM catalogGroup WHERE nazva_grupu = \"".$_POST['gtoadd']."\" ";
			$query_result = mysqli_query($conn, $selectIdQuery) or 
						die("<tr><td colspan=8>Помилка сервера при запиті<br>".$selectIdQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$query_row = mysqli_fetch_array($query_result); ?>
<tr><td colspan=8 style="color: red; font-weight: normal;">
			Академгрупа <?php echo bold($_POST['gtoadd']); ?> в довіднику вже є! Код: <?php echo bold($query_row['id']); ?></td></tr><?php
		}
	}
?><tr><td colspan=8><?php
	echo selectCommonSelect
		("До інституту (факультету): ", "ftoadd", $conn, $FacultiesQuery, "id", 
			$_POST['ftoadd'], "fakultet_name", "style=\"font-weight: bold;\""); ?><br>
Курс (напр., Б2 - II курс бакалаврату): 
<input type="text" name="ctoadd" style="font-weight: bold; width: 30px;" 
			value="<?php echo $_POST['ctoadd']; ?>" /> &nbsp; 
Шифр академгрупи: 
<input type="text" name="gtoadd" style="font-weight: bold;" value="<?php echo $_POST['gtoadd']; ?>" /><br> 
<?php 
	echo selectCommonSelect
		("Робочий навчальний план для академгрупи: ", "ptoadd", $conn, $PlansQuery, "id", 
			$_POST['ptoadd'], "reg_number", 
			"style=\"width: 185px; font-weight: bold; \""); ?> &nbsp;  
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
}
// Завантажити перелік груп і видалити позначені
// $GQuery = "SELECT * FROM catalogGroup ORDER BY id";
$query_result = mysqli_query($conn, $PageGroupsQuery) or 
								die("Помилка сервера при запиті<br>".$PageGroupsQuery." : ".mysqli_error($conn));
if (!empty($_POST['delg'])) { // Натиснуто кнопку "Видалити"
	while ($query_row = mysqli_fetch_array($query_result)) { // Обробка позначок "На видалення"
		if (!empty($_POST['delg'.$query_row['id']])) { // обробка позначки "На видалення"
			$DeleteGQuery = "DELETE FROM catalogGroup WHERE id='".$query_row['id']."'";
			$dg_result = mysqli_query($conn,  $DeleteGQuery) or 
				die("Помилка сервера при запиті<br>".$DeleteGQuery." : ".mysqli_error($conn));
		}
	}
}
// Завантажити перелік груп і змінити позначені групи
// $GQuery = "SELECT * FROM catalogGroup ORDER BY id";
$query2_result = mysqli_query($conn, $PageGroupsQuery) or 
									die("Помилка сервера при запиті<br>".$GQuery." : ".mysqli_error($conn));
while ($query2_row = mysqli_fetch_array($query2_result)) {
//	echo "<br>id=".$query2_row['id']."|fak=".$query2_row['shufr_fak']."|".$query2_row['nazva_grupu'];
	// Обробка позначок "Змінити"
	$_POST['sbt'.$query2_row['id']] = isset($_POST['sbt'.$query2_row['id']]) ?
														$_POST['sbt'.$query2_row['id']] : "";
	$_POST['facg'.$query2_row['id']] = isset($_POST['facg'.$query2_row['id']]) ?
														$_POST['facg'.$query2_row['id']] : "";
	$_POST['tbxc'.$query2_row['id']] = isset($_POST['tbxc'.$query2_row['id']]) ?
														$_POST['tbxc'.$query2_row['id']] : "";
	$_POST['tbxg'.$query2_row['id']] = isset($_POST['tbxg'.$query2_row['id']]) ?
														$_POST['tbxg'.$query2_row['id']] : "";
	$_POST['cbxy'.$query2_row['id']] = isset($_POST['cbxy'.$query2_row['id']]) ?
														$_POST['cbxy'.$query2_row['id']] : $query2_row['to_next_year'];
	$_POST['plg'.$query2_row['id']] = isset($_POST['plg'.$query2_row['id']]) ?
														$_POST['plg'.$query2_row['id']] : "";
//	echo " | sbt=".$_POST['sbt'.$query2_row['id']]." | facg".$query2_row['id']."=".$_POST['facg'.$query2_row['id']];
	if (!empty($_POST['sbt'.$query2_row['id']]) and
		 !empty($_POST['facg'.$query2_row['id']]) and
		 !empty($_POST['tbxc'.$query2_row['id']]) and
		 !empty($_POST['tbxg'.$query2_row['id']]) and
		 !empty($_POST['plg'.$query2_row['id']])) { // обробка кнопки "Зберегти зміни"
//		echo " | id=".$query2_row['id'];
		// перевірка, чи вже є така група
		$GUQuery = "SELECT * FROM catalogGroup
						WHERE nazva_grupu = \"".$_POST['tbxg'.$query2_row['id']]."\" AND id <> ".$query2_row['id'];
		$GUQuery_result = mysqli_query($conn,  $GUQuery) or 
					die("<tr><td colspan=8>Помилка сервера при запиті<br>".$GUQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
		$ig = 0; while ($GUQuery_row = mysqli_fetch_array($GUQuery_result)) $ig++; 
//		echo " | "."ig=".$ig;
		if ($ig == 0) {
			$UpdateGQuery = "UPDATE catalogGroup
									SET shufr_fak = \"".$_POST['facg'.$query2_row['id']]."\", 
										num_kurs = \"".$_POST['tbxc'.$query2_row['id']]."\", 
										nazva_grupu = \"".$_POST['tbxg'.$query2_row['id']]."\", 
										to_next_year = \"".(($_POST['cbxy'.$query2_row['id']] == 'on') ? 1 : 0)."\", 
										plan_id = \"".$_POST['plg'.$query2_row['id']]."\"
									WHERE id = ".$query2_row['id']; // echo " ",$UpdateGQuery;
			$UpdateGQuery_result = mysqli_query($conn,  $UpdateGQuery) or 
								die("<tr><td colspan=8>Помилка сервера при запиті<br>".$UpdateGQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
			$_POST['cbxg'.$query2_row['id']] = "";
		} else {
?>
<tr><td colspan=8 style="color: red; font-weight: normal;">
			Академгрупа <?php echo bold($_POST['tbxg'.$query2_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
		}
	}
}
?>
	<tr><th style="width: 80px;">Код</th><th style="width: 80px;">Шифр інституту</th>
		<th style="width: 40px;">Курс</th><th style="width: 110px;">Шифр академгрупи</th>
		<th style="width: 135px;">Реєстровий номер РНП</th><th style="width: 50px;">Кільк. студ.</th>
		<th>Редагування</th><th style="width: 140px;">До видалення</th></tr>
</thead>
<tbody>
<?php
// Завантажити перелік груп
	$query_result = mysqli_query($conn,  $PageGroupsQuery) or 
			die("Помилка сервера при запиті<br>".$PageGroupsQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbxg'.$query_row['id']] = isset($_POST['cbxg'.$query_row['id']]) ? 
															$_POST['cbxg'.$query_row['id']] : 0;
		$_POST['cbxy'.$query_row['id']] = isset($_POST['cbxy'.$query_row['id']]) ? 
															$_POST['cbxy'.$query_row['id']] : $query_row['to_next_year'];
		$_POST['delg'.$query_row['id']] = isset($_POST['delg'.$query_row['id']]) ?
															$_POST['delg'.$query_row['id']] : "";
?>
	<tr>	<td style="text-align: right; width: 80px;"><?php echo $query_row['id']; ?></td>
		<td style="width: 80px;"><?php echo $query_row['fakultet_shufr']; ?></td>                        
		<td style="width: 40px;"><?php echo $query_row['num_kurs']; ?></td>                        
		<td style="text-align: left; width: 110px;"><?php echo $query_row['nazva_grupu']; ?></td>
		<td style="text-align: left; width: 135px;"><?php echo $query_row['work_edu_plan']; ; ?></td>
		<td style="width: 50px;">
<?php
		$StudQuery = "SELECT id FROM catalogStudent WHERE group_link = ".$query_row['id'];
		$StudQuery_result = mysqli_query($conn,  $StudQuery) or 
			die("Помилка сервера при запиті<br>".$StudQuery." : ".mysqli_error($conn));
		$nstud = mysqli_num_rows($StudQuery_result); echo $nstud;
?>
		</td>
		<td>
<?php 
		if ($TrueAdmin) echo paramChekerInline("cbxg".$query_row['id'], $_POST['cbxg'.$query_row['id']], 
																			"Розкрити", "onchange=\"submit()\"")." &nbsp; &nbsp; ";
		if ($TrueAdmin) echo paramCheker("cbxy".$query_row['id'], $_POST['cbxy'.$query_row['id']], 
																			"Включити до контингенту наступного навч.року", "onchange=\"submit()\"");
		if (!empty($_POST['cbxy'.$query_row['id']])) {
			$UpdateNY_query = "UPDATE catalogGroup SET to_next_year = 1
												WHERE id = ".$query_row['id']; // echo $UpdateNY_query;
			$UpdateNY_result = mysqli_query($conn, $UpdateNY_query) or 
								die("<tr><td colspan=8>Помилка сервера при запиті<br>".$UpdateNY_query.
								" : ".mysqli_error($conn)."</td></tr></table>");
			$acad_year_next = mb_substr($query_row['num_kurs'], 0, 1).
												(mb_substr($query_row['num_kurs'], 1, 1) + 1); // встановлення наступного курсу
			// Чи є група в контингенті наступного року?
			$InGrNext_query = "SELECT * FROM catalogGroupNext WHERE group_id = ".$query_row['id'];
			$InGrNext_result = mysqli_query($conn, $InGrNext_query) or 
														die("Помилка сервера при запиті<br>".$InGrNext_query." : ".mysqli_error($conn));
		  if (mysqli_num_rows($InGrNext_result) == 0) {
				$IntoGrNext_query = "INSERT INTO catalogGroupNext VALUES (NULL,".$query_row['shufr_fak'].",
														".$query_row['id'].",'".$acad_year_next."','".$query_row['nazva_grupu']."',
														$nstud,0,0,0,0,'".$query_row['plan_id']."')";
				$IntoGrNext_result = mysqli_query($conn, $IntoGrNext_query) or 
														die("Помилка сервера при запиті<br>".$IntoGrNext_query." : ".mysqli_error($conn));
			} else { // зміна в групі наступного року
				$UpdateGrNext_query = "UPDATE catalogGroupNext 
															SET faculty_id = ".$query_row['shufr_fak'].", 
																	acad_year_next = '".$acad_year_next."', 
																	group_next_name = '".$query_row['nazva_grupu']."'	
															WHERE group_id = ".$query_row['id'];
				$UpdateGrNext_result = mysqli_query($conn, $UpdateGrNext_query) or 
														die("Помилка сервера при запиті<br>".$UpdateGrNext_query." : ".mysqli_error($conn));
			}
		} else { // видалення групи з контингенту наступного року
			$UpdateNY_query = "UPDATE catalogGroup SET to_next_year = 0
												WHERE id = ".$query_row['id']; // echo $UpdateNY_query;
			$UpdateNY_result = mysqli_query($conn, $UpdateNY_query) or 
							die("<tr><td colspan=8>Помилка сервера при запиті<br>".$UpdateNY_query.
							" : ".mysqli_error($conn)."</td></tr></table>");
			$DelGrNext_query = "DELETE FROM catalogGroupNext WHERE group_id = ".$query_row['id'];
			$DelGrNext_result = mysqli_query($conn, $DelGrNext_query) or 
														die("Помилка сервера при запиті<br>".$DelGrNext_query." : ".mysqli_error($conn));
		}

		if (!empty($_POST['cbxg'.$query_row['id']])) {
?>
			<div><?php
				echo selectCommonSelect
					("Інститут (факультет): ", "facg".$query_row['id'], $conn, $FacultiesQuery, "id", 
						$query_row['shufr_fak'], "fakultet_name", 
						"style=\"width: 385px; font-weight: bold; \""); ?><br>
				Курс: 
				<input type="textbox" name="tbxc<?php echo $query_row['id']; ?>" 
						style="width: 20px; font-weight: bold;"
						value="<?php echo $query_row['num_kurs']; ?>" /> &nbsp; &nbsp; 
				Шифр академгрупи: 
				<input type="textbox" name="tbxg<?php echo $query_row['id']; ?>" 
						style="font-weight: bold;"
						value="<?php echo $query_row['nazva_grupu']; ?>" /><br><?php 
				$FacultyPlansQuery = "SELECT id, reg_number FROM catalogWorkEduPlan 
											WHERE faculty_id = \"".$query_row['shufr_fak']."\" ORDER BY reg_number";
				echo selectCommonSelect
					("Робочий навчальний план: ", "plg".$query_row['id'], $conn, $FacultyPlansQuery, "id", 
						$query_row['plan_id'], "reg_number", 
						"style=\"width: 185px; font-weight: bold; \""); ?><br>
				<input type="submit" name="sbt<?php echo $query_row['id']; ?>" value="Зберегти" />
			</div>
<?php
		}
?>
		</td>
		<td style="width: 140px;"><?php 
		if ($TrueAdmin and ($nstud == 0)) echo paramChekerInline("delg".$query_row['id'],$_POST['delg'.$query_row['id']],"","").
												" ".$query_row['nazva_grupu']; ?></td>
	</tr>
<?php 
		$icnt++;
	}
?>
</tbody>
<tfoot>
	<tr>
		<td colspan=7 style="text-align: right;">
			Кількість академгруп: <?php echo bold($TotalRows); ?>, на цій сторінці: <?php echo bold($icnt); ?></td>
		<td><?php 
if ($TrueAdmin) { ?>
		<input type="checkbox" id="delg" name="delg" 
							onclick="if (confirm('Дійсно видалити позначені академгрупи?')) submit();" class="del" />
		<label for="delg" class="del">Видалити</label><?php
} ?></td></tr>
</tfoot>
</table>
