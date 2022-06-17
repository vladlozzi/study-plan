<?php if (!defined("IN_ADMIN")) { 
				echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
             "Помилка входу в модуль groups_next_pages.php</p>"; require "footer.php"; exit(); }
$FacultiesQuery = "SELECT * FROM catalogFakultet ORDER BY fakultet_name";
$PlansQuery = "SELECT id, reg_number FROM catalogWorkEduPlan ORDER BY reg_number";
$TotalGroupsQuery = "
				SELECT a.*, b.fakultet_shufr, c.reg_number AS work_edu_plan 
				FROM catalogGroupNext a, catalogFakultet b, catalogWorkEduPlan c
				WHERE a.faculty_id = b.id AND a.plan_id = c.id
				ORDER BY b.fakultet_shufr, a.acad_year_next, a.group_next_name"; $rpp = 20; // кількість на сторінку
$query_result = mysqli_query($conn, $TotalGroupsQuery) or 
	die("Помилка сервера при запиті<br>".$TotalGroupsQuery." : ".mysqli_error($conn));
$TotalRows = mysqli_num_rows($query_result); $nPages = ceil($TotalRows / $rpp); 
if ($TotalRows > 0) {
	$_POST['radPageSelect'] = isset($_POST['radPageSelect']) ? 
															$_POST['radPageSelect'] : "Page1";
?><p style="text-align: center;">Сторінка: <?php 
	for ($iPage = 1; $iPage <= $nPages; $iPage++) { ?>
<input type="radio" name="radPageSelect" value="Page<? echo $iPage; ?>" onclick="submit()" 
	<?php if ($_POST['radPageSelect'] == "Page".$iPage) echo "checked"; ?>> 
	<?php echo $iPage; ?> &nbsp; &nbsp; <?php
	}
?></p><?php
	for ($iPage = 1; $iPage <= $nPages; $iPage++) { 
		if ($_POST['radPageSelect'] == "Page".$iPage) 
			$PageGroupsQuery = $TotalGroupsQuery." LIMIT ".(($iPage-1)*$rpp).", $rpp";
	} 
} ?>
<table style="margin-left: 0%; width: 1200px;">
<thead>
	<tr><td colspan=12><?php $_POST['addg'] = isset($_POST['addg']) ? $_POST['addg'] : 0;
if ($TrueAdmin) echo paramCheker("addg", $_POST['addg'], "Додати нову академгрупу", "onchange=\"submit()\""); 
?></td></tr><?php
if (!empty($_POST['addg'])) {
	$_POST['ftoadd'] = isset($_POST['ftoadd']) ? $_POST['ftoadd'] : "";
	$_POST['ctoadd'] = isset($_POST['ctoadd']) ? $_POST['ctoadd'] : "";
	$_POST['gtoadd'] = isset($_POST['gtoadd']) ? $_POST['gtoadd'] : "";
	$_POST['ptoadd'] = isset($_POST['ptoadd']) ? $_POST['ptoadd'] : "";
	$_POST['tstoadd']	= isset($_POST['tstoadd']) ? $_POST['tstoadd'] : "";
	$_POST['bstoadd']	= isset($_POST['bstoadd']) ? $_POST['bstoadd'] : "";
	$_POST['fstoadd']	= isset($_POST['fstoadd']) ? $_POST['fstoadd'] : "";
	$_POST['estoadd']	= isset($_POST['estoadd']) ? $_POST['estoadd'] : "";
	$_POST['ostoadd']	= isset($_POST['ostoadd']) ? $_POST['ostoadd'] : "";
	if (!empty($_POST['ftoadd']) and !empty($_POST['ctoadd']) and 
			!empty($_POST['gtoadd']) and !empty($_POST['ptoadd'])) { 
	// перевірка, чи вже є така група
		$GTestQuery = "SELECT * FROM catalogGroupNext WHERE group_next_name = \"".$_POST['gtoadd']."\" ";
		$query_result = mysqli_query($conn,  $GTestQuery) or 
					die("<tr><td colspan=8>Помилка сервера при запиті<br>".$GTestQuery.
						" : ".mysqli_error($conn)."</td></tr></table>");
		$ig = 0; while ($query_row = mysqli_fetch_array($query_result)) $ig++;
		if ($ig == 0) {
			$AddGQuery = "insert into catalogGroupNext values
									(\"\",\"".$_POST['ftoadd']."\", NULL, \"".$_POST['ctoadd']."\", 
										\"".$_POST['gtoadd']."\", \"".$_POST['tstoadd']."\", 
										\"".$_POST['bstoadd']."\", \"".$_POST['fstoadd']."\", 
										\"".$_POST['estoadd']."\", \"".$_POST['ostoadd']."\",  \"".$_POST['ptoadd']."\"										
									)"; // echo $AddGQuery;
			$query_result = mysqli_query($conn, $AddGQuery) or 
						die("<tr><td colspan=8>Помилка сервера при запиті<br>".$AddGQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
?>
<tr><td colspan=12 style="color: green; font-weight: normal;">
			Академгрупу <?php echo bold($_POST['gtoadd']); ?> успішно додано</td></tr>
<?php
 		} else {
?>
<tr><td colspan=12 style="color: red; font-weight: normal;">
			Академгрупа <?php echo bold($_POST['gtoadd']); ?> в довіднику вже є!</td></tr>
<?php
		}
	}
?><tr><td colspan=12><?php
	echo selectCommonSelect
		("До інституту (факультету): ", "ftoadd", $conn, $FacultiesQuery, "id", 
			$_POST['ftoadd'], "fakultet_name", "style=\"font-weight: bold;\""); ?><br>
Курс (напр., Б2 - II курс бакалаврату): 
<input type="text" name="ctoadd" style="font-weight: bold; width: 30px;" 
			value="<?php echo $_POST['ctoadd']; ?>" /> &nbsp; 
Шифр академгрупи: 
<input type="text" name="gtoadd" style="font-weight: bold;" value="<?php echo $_POST['gtoadd']; ?>" /><br> 
Кількість студентів: загальна <input type="text" name="tstoadd" style="font-weight: bold; width: 40px;" 
																		value="<?php echo $_POST['tstoadd']; ?>" /> &nbsp; 
бюджетники <input type="text" name="bstoadd" style="font-weight: bold; width: 40px;" 
									value="<?php echo $_POST['bstoadd']; ?>" /> &nbsp; 
іноземці <input type="text" name="fstoadd" style="font-weight: bold; width: 40px;" 
								value="<?php echo $_POST['fstoadd']; ?>" /><br>
у яких основна іноземна мова - англійська <input type="text" name="estoadd" 
								style="font-weight: bold; width: 40px;" value="<?php echo $_POST['estoadd']; ?>" /> &nbsp; 
у яких основна іноземна мова - інша <input type="text" name="ostoadd" style="font-weight: bold; width: 40px;" 
																					value="<?php echo $_POST['ostoadd']; ?>" /><br>
<?php 
	echo selectCommonSelect
		("Робочий навчальний план для академгрупи: ", "ptoadd", $conn, $PlansQuery, "id", 
			$_POST['ptoadd'], "reg_number", 
			"style=\"width: 185px; font-weight: bold; \""); ?> &nbsp;  
<input type="submit" name="sbt" value="Зберегти" /></td></tr>
<?php
}
// Завантажити перелік груп і видалити позначені
$PageGroupsQuery = (isset($PageGroupsQuery)) ? $PageGroupsQuery : $TotalGroupsQuery;
$query_result = mysqli_query($conn, $PageGroupsQuery) or 
								die("Помилка сервера при запиті<br>".$PageGroupsQuery." : ".mysqli_error($conn));
if (!empty($_POST['delgn'])) { // Натиснуто кнопку "Видалити"
	while ($query_row = mysqli_fetch_array($query_result)) { // Обробка позначок "На видалення"
		if (!empty($_POST['delgn'.$query_row['id']])) { // обробка позначки "На видалення"
			$DeleteGQuery = "DELETE FROM catalogGroupNext WHERE id='".$query_row['id']."'";
			$dg_result = mysqli_query($conn,  $DeleteGQuery) or 
				die("Помилка сервера при запиті<br>".$DeleteGQuery." : ".mysqli_error($conn));
		}
	}
}
// Завантажити перелік груп і змінити позначені групи
$query2_result = mysqli_query($conn, $PageGroupsQuery) or 
									die("Помилка сервера при запиті<br>".$GQuery." : ".mysqli_error($conn));
while ($query2_row = mysqli_fetch_array($query2_result)) {
//	echo "<br>id=".$query2_row['id']."|fak=".$query2_row['shufr_fak']."|".$query2_row['nazva_grupu'];
	// Обробка позначок "Змінити"
	$_POST['sbtn'.$query2_row['id']] = isset($_POST['sbtn'.$query2_row['id']]) ?
														$_POST['sbtn'.$query2_row['id']] : "";
	$_POST['facg'.$query2_row['id']] = isset($_POST['facg'.$query2_row['id']]) ?
														$_POST['facg'.$query2_row['id']] : "";
	$_POST['tbxc'.$query2_row['id']] = isset($_POST['tbxc'.$query2_row['id']]) ?
														$_POST['tbxc'.$query2_row['id']] : "";
	$_POST['tbxg'.$query2_row['id']] = isset($_POST['tbxg'.$query2_row['id']]) ?
														$_POST['tbxg'.$query2_row['id']] : "";
	$_POST['ts'.$query2_row['id']] = isset($_POST['ts'.$query2_row['id']]) ?
														$_POST['ts'.$query2_row['id']] : 0;
	$_POST['bs'.$query2_row['id']] = isset($_POST['bs'.$query2_row['id']]) ?
														$_POST['bs'.$query2_row['id']] : 0;
	$_POST['fs'.$query2_row['id']] = isset($_POST['fs'.$query2_row['id']]) ?
														$_POST['fs'.$query2_row['id']] : 0;
	$_POST['es'.$query2_row['id']] = isset($_POST['es'.$query2_row['id']]) ?
														$_POST['es'.$query2_row['id']] : 0;
	$_POST['os'.$query2_row['id']] = isset($_POST['os'.$query2_row['id']]) ?
														$_POST['os'.$query2_row['id']] : 0;
	$_POST['plg'.$query2_row['id']] = isset($_POST['plg'.$query2_row['id']]) ?
														$_POST['plg'.$query2_row['id']] : "";
//	echo " | sbt=".$_POST['sbt'.$query2_row['id']]." | facg".$query2_row['id']."=".$_POST['facg'.$query2_row['id']];
	if (!empty($_POST['sbtn'.$query2_row['id']]) and
		 !empty($_POST['facg'.$query2_row['id']]) and
		 !empty($_POST['tbxc'.$query2_row['id']]) and
		 !empty($_POST['tbxg'.$query2_row['id']]) and
		 !empty($_POST['plg'.$query2_row['id']])) { // обробка кнопки "Зберегти зміни"
//		echo " | id=".$query2_row['id'];
		// перевірка, чи вже є така група
		$GUQuery = "SELECT * FROM catalogGroupNext
						WHERE group_next_name = \"".$_POST['tbxg'.$query2_row['id']]."\" AND id <> ".$query2_row['id'];
		$GUQuery_result = mysqli_query($conn,  $GUQuery) or 
					die("<tr><td colspan=8>Помилка сервера при запиті<br>".$GUQuery.
							" : ".mysqli_error($conn)."</td></tr></table>");
		$ig = 0; while ($GUQuery_row = mysqli_fetch_array($GUQuery_result)) $ig++; 
//		echo " | "."ig=".$ig;
		if ($ig == 0) {
			$UpdateGQuery = "UPDATE catalogGroupNext
									SET faculty_id = \"".$_POST['facg'.$query2_row['id']]."\", 
										acad_year_next = \"".$_POST['tbxc'.$query2_row['id']]."\", 
										group_next_name = \"".$_POST['tbxg'.$query2_row['id']]."\", 
										stud_count = \"".$_POST['ts'.$query2_row['id']]."\", 
										budj_count = \"".$_POST['bs'.$query2_row['id']]."\", 
										foreigners_count = \"".$_POST['fs'.$query2_row['id']]."\", 
										english_count = \"".$_POST['es'.$query2_row['id']]."\", 
										other_lang_count = \"".$_POST['os'.$query2_row['id']]."\", 
										plan_id = \"".$_POST['plg'.$query2_row['id']]."\"
									WHERE id = ".$query2_row['id']; // echo " ",$UpdateGQuery;
			$UpdateGQuery_result = mysqli_query($conn,  $UpdateGQuery) or 
								die("<tr><td colspan=8>Помилка сервера при запиті<br>".$UpdateGQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
		} else {
?>
<tr><td colspan=8 style="color: red; font-weight: normal;">
			Академгрупа <?php echo bold($_POST['tbxg'.$query2_row['id']]); ?> в довіднику вже є!</td></tr>
<?php
		}
	}
} ?>
	<tr><th rowspan=2 style="width: 80px;">Код</th><th rowspan=2 style="width: 80px;">Шифр<br>інституту</th>
		<th rowspan=2 style="width: 40px;">Курс</th><th rowspan=2 style="width: 110px;">Шифр<br>академгрупи</th>
		<th rowspan=2 style="width: 135px;">Реєстровий<br>номер РНП</th><th colspan=5>Кількість студентів</th>
		<th rowspan=2>Редагування</th><th rowspan=2 style="width: 110px;">До видалення</th></tr>
	<tr><th style="width: 40px;">заг.</th><th style="width: 40px;">бюдж.</th>
		<th style="width: 40px;">іноз.</th><th style="width: 40px;">англ.</th>
		<th style="width: 80px;">інша ін.м.</th></tr>
</thead>
<tbody>
<?php
// Завантажити перелік груп
	$PageGroupsQuery = (isset($PageGroupsQuery)) ? $PageGroupsQuery : $TotalGroupsQuery;
	$query_result = mysqli_query($conn, $PageGroupsQuery) or 
			die("Помилка сервера при запиті<br>".$PageGroupsQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbxg'.$query_row['id']] = isset($_POST['cbxg'.$query_row['id']]) ? 
															$_POST['cbxg'.$query_row['id']] : 0;
		$_POST['delgn'.$query_row['id']] = isset($_POST['delgn'.$query_row['id']]) ?
															$_POST['delgn'.$query_row['id']] : "";
?>
	<tr>	<td style="text-align: right; width: 80px;"><?php echo $query_row['id']; ?></td>
		<td style="width: 80px;"><?php echo $query_row['fakultet_shufr']; ?></td>                        
		<td style="width: 40px;"><?php echo $query_row['acad_year_next']; ?></td>                        
		<td style="text-align: left; width: 110px;"><?php echo $query_row['group_next_name']; ?></td>
		<td style="text-align: left; width: 135px;"><?php echo $query_row['work_edu_plan']; ; ?></td>
		<td style="width: 40px;"><?php echo $query_row['stud_count']; ?></td>
		<td style="width: 40px;"><?php echo $query_row['budj_count']; ?></td>
		<td style="width: 40px;"><?php echo $query_row['foreigners_count']; ?></td>
		<td style="width: 40px;"><?php echo $query_row['english_count']; ?></td>
		<td style="width: 40px;"><?php echo $query_row['other_lang_count']; ?></td>
		<td>
<?php 
		if ($TrueAdmin) echo paramChekerInline("cbxg".$query_row['id'], $_POST['cbxg'.$query_row['id']], 
																			"Розкрити", "onchange=\"submit()\"")." &nbsp; &nbsp; ";
		if (!empty($_POST['cbxg'.$query_row['id']])) {
?>
			<div><?php 
				echo selectCommonSelect
					("Інститут (факультет): ", "facg".$query_row['id'], $conn, $FacultiesQuery, "id", 
						$query_row['faculty_id'], "fakultet_name", 
						"style=\"width: 385px; font-weight: bold; \""); ?><br>
				Курс: 
				<input type="textbox" name="tbxc<?php echo $query_row['id']; ?>" 
						style="width: 20px; font-weight: bold;"
						value="<?php echo $query_row['acad_year_next']; ?>" /> &nbsp; &nbsp; 
				Шифр академгрупи: 
				<input type="textbox" name="tbxg<?php echo $query_row['id']; ?>" 
						style="font-weight: bold; width: 120px"
						value="<?php echo $query_row['group_next_name']; ?>" /><br>
				Кількість студентів: загальна 
				<input type="textbox" name="ts<?php echo $query_row['id']; ?>"
					style="font-weight: bold; width: 40px;" value="<?php echo $query_row['stud_count']; ?>" /><br> 
				бюджетники <input type="textbox" name="bs<?php echo $query_row['id']; ?>" 
					style="font-weight: bold; width: 40px;" value="<?php echo $query_row['budj_count']; ?>" /> &nbsp; 
				іноземці <input type="textbox" name="fs<?php echo $query_row['id']; ?>" 
					style="font-weight: bold; width: 40px;" value="<?php echo $query_row['foreigners_count']; ?>" /><br>
				у яких основна іноземна мова - англійська 
				<input type="textbox" name="es<?php echo $query_row['id']; ?>" 
					style="font-weight: bold; width: 40px;" value="<?php echo $query_row['english_count']; ?>" /><br> 
				у яких основна іноземна мова - інша 
				<input type="textbox" name="os<?php echo $query_row['id']; ?>" style="font-weight: bold; width: 40px;" 
					value="<?php echo $query_row['other_lang_count']; ?>" /><br>
<?php 
				$FacultyPlansQuery = "SELECT id, reg_number FROM catalogWorkEduPlan 
											WHERE faculty_id = \"".$query_row['faculty_id']."\" ORDER BY reg_number";
				echo selectCommonSelect
					("Робочий навчальний план: ", "plg".$query_row['id'], $conn, $FacultyPlansQuery, "id", 
						$query_row['plan_id'], "reg_number", 
						"style=\"width: 185px; font-weight: bold; \""); ?><br>
				<input type="submit" name="sbtn<?php echo $query_row['id']; ?>" value="Зберегти" />
			</div>
<?php
		}
?>
		</td>
		<td style="width: 110px;"><?php 
		if ($TrueAdmin) echo paramChekerInline("delgn".$query_row['id'],$_POST['delgn'.$query_row['id']],"","").
													" ".$query_row['group_next_name']; ?></td>
	</tr>
<?php 
		$icnt++;
	}
?>
</tbody>
<tfoot>
	<tr>
		<td colspan=11 style="text-align: right;">
			Кількість академгруп: <?php echo bold($TotalRows); ?>, на цій сторінці: <?php echo bold($icnt); ?></td>
		<td><?php 
if ($TrueAdmin) { ?>
		<input type="checkbox" id="delgn" name="delgn" 
							onclick="if (confirm('Дійсно видалити позначені академгрупи?')) submit();" class="del" />
		<label for="delgn" class="del">Видалити</label><?php
} ?></td></tr>
</tfoot>
</table>
