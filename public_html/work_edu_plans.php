<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль work_edu_plans.php</p>"; require "footer.php"; exit(); }
// Перелік ступенів в.о. для вибору 
$DegreesQuery = "SELECT id, degree_name FROM catalogEduDegree WHERE id < 9 ORDER BY id";
$BaseDegreesQuery = "SELECT id, degree_name FROM catalogEduDegree ORDER BY id DESC";

// Перелік спеціальностей для вибору
$SpecialtiesQuery = "SELECT id, 
								CONCAT(specialty_b_code,\" \",specialty_name,\" (Перелік \",list,\")\") AS specialty_codename
								FROM catalogSpecialty ORDER BY specialty_name";
// Перелік спеціалізацій для вибору
$EduProgramsQuery = "SELECT id, CONCAT(\"(\",eduprogram_code,\") \",eduprogram_name) AS eduprogram_codename
							FROM catalogEduProgram ORDER BY eduprogram_name"; // echo "<p>".$EduProgramsQuery."</p>";
$EduProgramsQueries = array();
// Перелік форм навчання для вибору
$EduFormsQuery = "SELECT id, edu_form	FROM catalogEduForm ORDER BY id"; // echo "<p>".$EduFormsQuery."</p>";
// Перелік Інститутів (факультетів) для вибору
$FacultiesQuery = "SELECT id, fakultet_name	FROM catalogFakultet ORDER BY fakultet_name";
// Перелік кафедр для вибору випускної кафедри
$DepartsQuery = "SELECT id, replace(nazva_kaf,\"Кафедра \",\"\") AS nazva_kaf 
						FROM catalogDepartment WHERE depart_group_id = 1 ORDER BY nazva_kaf"; // echo "<p>".$DepartsQuery."</p>";
$FilterConditions = array();
$tn = ""; $itn=1; $FilterConditions[$itn] = ""; require "work_edu_plans_filter.php";
?>
<table style="margin-left: 0%; width: 100%;">
	<tr><td colspan=11><?php $_POST['addp'] = isset($_POST['addp']) ? $_POST['addp'] : 0;
		if ($TrueAdmin) 
			echo paramCheker("addp", $_POST['addp'], "Додати навчальний план", "onchange=\"submit()\""); ?>
			</td></tr>
<?php 
	if (!empty($_POST['addp'])) {
		$_POST['degtoadd'] = isset($_POST['degtoadd']) ? $_POST['degtoadd'] : "";
		$_POST['terytoadd'] = isset($_POST['terytoadd']) ? $_POST['terytoadd'] : "";
		$_POST['termtoadd'] = isset($_POST['termtoadd']) ? $_POST['termtoadd'] : "";
		$_POST['bdgtoadd'] = isset($_POST['bdgtoadd']) ? $_POST['bdgtoadd'] : "";
		$_POST['edftoadd'] = isset($_POST['edftoadd']) ? $_POST['edftoadd'] : "";
		$_POST['spctoadd'] = isset($_POST['spctoadd']) ? $_POST['spctoadd'] : "";
		$_POST['spztoadd'] = isset($_POST['spztoadd']) ? $_POST['spztoadd'] : "";
		$_POST['deptoadd'] = isset($_POST['deptoadd']) ? $_POST['deptoadd'] : "";
		$_POST['factoadd'] = isset($_POST['factoadd']) ? $_POST['factoadd'] : "";
		$_POST['acytoadd'] = isset($_POST['acytoadd']) ? $_POST['acytoadd'] : "";
		$_POST['stdtoadd'] = isset($_POST['stdtoadd']) ? $_POST['stdtoadd'] : "";
		$_POST['prntoadd'] = isset($_POST['prntoadd']) ? $_POST['prntoadd'] : "";
		$_POST['sbtAddPlan'] = isset($_POST['sbtAddPlan']) ? $_POST['sbtAddPlan'] : "";
		// echo $_POST['degtoadd']," | ".$_POST['terytoadd']." | ".$_POST['termtoadd']." | ".
		//			$_POST['bdgtoadd']." | ".$_POST['edftoadd']." | ".
		//			$_POST['spctoadd']."| ".$_POST['spztoadd']."| ".$_POST['acytoadd'];
		if (!empty($_POST['degtoadd']) and !empty($_POST['terytoadd']) and !empty($_POST['termtoadd'])	and 
				!empty($_POST['bdgtoadd']) and !empty($_POST['edftoadd']) and !empty($_POST['spctoadd']) and 
				!empty($_POST['spztoadd']) and !empty($_POST['acytoadd']) and !empty($_POST['sbtAddPlan'])
			) { 
			// перевірка, чи вже є навчальний план з такими параметрами 
			$PQuery = "SELECT * FROM catalogWorkEduPlan 
								WHERE edu_degree_id=\"".$_POST['degtoadd']."\" AND
									base_edu_degree_id=\"".$_POST['bdgtoadd']."\" AND
									edu_form_id=\"".$_POST['edftoadd']."\" AND
									specialty_id=\"".$_POST['spctoadd']."\" AND 
									edu_program_id=\"".$_POST['spztoadd']."\" AND 
									actualize_year=\"".$_POST['acytoadd']."\"
								";
			$query_result = mysqli_query($conn, $PQuery) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$PQuery." : ".mysqli_error($conn)."</td></tr></table>");
			$ipn = 0; while ($query_row = mysqli_fetch_array($query_result)) $ipn++;
			if ($ipn == 0) {
				// сформувати реєстровий номер навчального плану
				$ep = EduProgramById($_POST['spztoadd']); // echo " - $ep<br>"; // спеціалізація
				$bd = BaseDegreeById($_POST['bdgtoadd']); // echo " - $bd<br>"; // базовий ступінь
				$ef = EduFormById($_POST['edftoadd']); // echo " - $ef<br>"; // форма навчання
				$ay = substr($_POST['acytoadd'], 2, 2)."р."; // echo " - $ay<br>"; // дві останні цифри року набрання чинності
				$dg = DegreeById($_POST['degtoadd']); // echo " - $dg<br>"; // ступінь, який здобувають
				$reg_number = $ep.$bd.".".$dg.".".$ay.$ef;
				$AddPQuery = "insert into catalogWorkEduPlan values
											(NULL,\"".$reg_number."\", \"".$_POST['degtoadd']."\",
												\"".$_POST['bdgtoadd']."\", \"".$_POST['spctoadd']."\",
												\"".$_POST['spztoadd']."\", NULL, \"".$_POST['edftoadd']."\",
												\"".$_POST['terytoadd']."\", \"".$_POST['termtoadd']."\",
												\"".$_POST['acytoadd']."\", \"".$_POST['factoadd']."\",
												\"".$_POST['deptoadd']."\", \"".$_POST['stdtoadd']."\",
												\"".$_POST['prntoadd']."\", '', '', '', '', '', '', 0, 0, 0, 0
											)"; // echo $AddPQuery;
			   $query_result = mysqli_query($conn, $AddPQuery) or 
							die("<tr><td colspan=11>Помилка сервера при запиті<br>".$AddPQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
?>
<tr><td colspan=11 style="color: green; font-weight: normal;">
			Навчальний план успішно додано</td></tr>
<?php
			} else {
				if ($ipn > 0) {
		$_POST['degtoadd'] = isset($_POST['degtoadd']) ? $_POST['degtoadd'] : "";
?>
<tr><td colspan=11 style="color: red; font-weight: normal;">
			Такий навчальний план у довіднику вже є!</td></tr>
<?php
				}
			}
		}
?><tr><td colspan=11>
<?php 
		echo selectCommonSelect
		("Ступінь / ОКР: ", "degtoadd", $conn, $DegreesQuery, "id", $_POST['degtoadd'], "degree_name", ""); ?>
&nbsp; &nbsp; Строк навчання: 
<input type="text" name="terytoadd" style="font-weight: bold; font-size: 125%; width: 12px; text-align: right; " 
				value="<?php echo $_POST['terytoadd']; ?>" />р. 
<input type="text" name="termtoadd" style="font-weight: bold; font-size: 125%; width: 24px; text-align: right; " 
				value="<?php echo $_POST['termtoadd']; ?>" />м.
<?php 
		echo selectCommonSelect
		(" на базі ступеню / ОКР: ", "bdgtoadd", $conn, $BaseDegreesQuery, "id", $_POST['bdgtoadd'], "degree_name", "")." &nbsp; &nbsp; ";
		echo selectCommonSelect
		(" Форма навчання: ", "edftoadd", $conn, $EduFormsQuery, "id", $_POST['edftoadd'], "edu_form", ""); ?><br>
<?php
		echo selectCommonSelectAutoSubmit
		("Спеціальність / напрям: ", "spctoadd", $conn, $SpecialtiesQuery, "id", $_POST['spctoadd'], "specialty_codename", "")."<br>";
		$EduProgramsQuery = str_replace("ORDER ","WHERE specialty_id = \"".$_POST['spctoadd']."\" ORDER ", $EduProgramsQuery); // echo $EduProgramsQuery;
		echo selectCommonSelect
		("Спеціалізація (освітня програма): ", "spztoadd", $conn, $EduProgramsQuery, "id", $_POST['spztoadd'], "eduprogram_codename", "")."<br>";
		echo selectCommonSelect
		("Інститут (факультет): ", "factoadd", $conn, $FacultiesQuery, "id", $_POST['factoadd'], "fakultet_name", "")." &nbsp; &nbsp; ";
		echo selectCommonSelect
		("Випускна кафедра: ", "deptoadd", $conn, $DepartsQuery, "id", $_POST['deptoadd'], "nazva_kaf", ""); ?><br>
Набирає чинності з: 
<input type="text" name="acytoadd" style="font-weight: bold; font-size: 125%; width: 120px;" 
				value="<?php echo $_POST['acytoadd']; ?>" />н.р. &nbsp; &nbsp; 
Дата затвердження: 
<input type="date" name="stdtoadd" style="font-weight: bold; font-size: 125%; width: 165px;" 
				value="<?php echo $_POST['stdtoadd']; ?>" /> &nbsp; 
№ протоколу: 
<input type="text" name="prntoadd" style="font-weight: bold; font-size: 125%; width: 65px;" 
				value="<?php echo $_POST['prntoadd']; ?>" /> &nbsp; &nbsp; 
<input type="submit" name="sbtAddPlan" value="Зберегти" style="font-weight: bold; color: blue;" /><br></td></tr>
<?php
	}

// Завантажити перелік планів і видалити позначені
	$PQuery = "SELECT * FROM catalogWorkEduPlan ORDER BY id";
	$query1_result = mysqli_query($conn, $PQuery) or die("Помилка сервера при запиті<br>".$PQuery." : ".mysqli_error($conn));
	if (!empty($_POST['delps'])) { // Натиснуто кнопку "Видалити"
		while ($query_row = mysqli_fetch_array($query1_result)) { // Обробка позначок "На видалення"
			if (!empty($_POST['delp'.$query_row['id']])) { // обробка позначки "На видалення"
				$DeletePQuery = "DELETE FROM catalogWorkEduPlan WHERE id='".$query_row['id']."'";
				$dT_result = mysqli_query($conn, $DeletePQuery) or 
					die("Помилка сервера при запиті<br>".$DeletePQuery." : ".mysqli_error($conn));
			}
		}
	}
// Завантажити перелік планів і змінити позначені
	$query2_result = mysqli_query($conn, $PQuery) or die("Помилка сервера при запиті<br>".$PQuery." : ".mysqli_error($conn));
	while ($query_row = mysqli_fetch_array($query2_result)) {
		// Обробка позначок "Змінити"
		$_POST['sbp'.$query_row['id']] = isset($_POST['sbp'.$query_row['id']]) ? $_POST['sbp'.$query_row['id']] : "";
		$_POST['deg'.$query_row['id']] = isset($_POST['deg'.$query_row['id']]) ? $_POST['deg'.$query_row['id']] : "";
		$_POST['tery'.$query_row['id']] = isset($_POST['tery'.$query_row['id']]) ? $_POST['tery'.$query_row['id']] : "";
		$_POST['term'.$query_row['id']] = isset($_POST['term'.$query_row['id']]) ? $_POST['term'.$query_row['id']] : "";
		$_POST['bdg'.$query_row['id']] = isset($_POST['bdg'.$query_row['id']]) ? $_POST['bdg'.$query_row['id']] : "";
		$_POST['edf'.$query_row['id']] = isset($_POST['edf'.$query_row['id']]) ? $_POST['edf'.$query_row['id']] : "";
		$_POST['spctoedit'.$query_row['id']] = isset($_POST['spctoedit'.$query_row['id']]) ? $_POST['spctoedit'.$query_row['id']] : "";
		$_POST['spz'.$query_row['id']] = isset($_POST['spz'.$query_row['id']]) ? $_POST['spz'.$query_row['id']] : "";
		$_POST['dep'.$query_row['id']] = isset($_POST['dep'.$query_row['id']]) ? $_POST['dep'.$query_row['id']] : "";
		$_POST['fac'.$query_row['id']] = isset($_POST['fac'.$query_row['id']]) ? $_POST['fac'.$query_row['id']] : "";
		$_POST['acy'.$query_row['id']] = isset($_POST['acy'.$query_row['id']]) ? $_POST['acy'.$query_row['id']] : "";
		$_POST['std'.$query_row['id']] = isset($_POST['std'.$query_row['id']]) ? $_POST['std'.$query_row['id']] : "";
		$_POST['prn'.$query_row['id']] = isset($_POST['prn'.$query_row['id']]) ? $_POST['prn'.$query_row['id']] : "";
		if (!empty($_POST['sbp'.$query_row['id']]) and
			 !empty($_POST['deg'.$query_row['id']]) and
			 !empty($_POST['tery'.$query_row['id']]) and
			 !empty($_POST['term'.$query_row['id']]) and
			 !empty($_POST['bdg'.$query_row['id']]) and
			 !empty($_POST['edf'.$query_row['id']]) and
			 !empty($_POST['spctoedit'.$query_row['id']]) and
			 !empty($_POST['spz'.$query_row['id']]) and
			 !empty($_POST['dep'.$query_row['id']]) and
			 !empty($_POST['fac'.$query_row['id']]) and
			 !empty($_POST['acy'.$query_row['id']])
			 ) { // обробка кнопки "Зберегти зміни"
			// перевірка, чи вже є навчальний план з такими параметрами 
			$PQuery = "
								SELECT * FROM catalogWorkEduPlan 
								WHERE edu_degree_id=\"".$_POST['deg'.$query_row['id']]."\" AND
									base_edu_degree_id=\"".$_POST['bdg'.$query_row['id']]."\" AND
									edu_form_id=\"".$_POST['edf'.$query_row['id']]."\" AND
									specialty_id=\"".$_POST['spctoedit'.$query_row['id']]."\" AND 
									edu_program_id=\"".$_POST['spz'.$query_row['id']]."\" AND 
									actualize_year=\"".$_POST['acy'.$query_row['id']]."\" AND 
									id <> \"".$query_row['id']."\"
								"; // echo $PQuery;
			$query_result = mysqli_query($conn, $PQuery) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$PQuery." : ".mysqli_error($conn)."</td></tr></table>");
			$ipn = 0; while ($query_row2 = mysqli_fetch_array($query_result)) $ipn++;
			if ($ipn == 0) {
				// сформувати новий реєстровий номер навчального плану
				$ep = EduProgramById($_POST['spz'.$query_row['id']]); // echo " - $ep<br>"; // спеціалізація
				$bd = BaseDegreeById($_POST['bdg'.$query_row['id']]); // echo " - $bd<br>"; // базовий ступінь
				$ef = EduFormById($_POST['edf'.$query_row['id']]); // echo " - $ef<br>"; // форма навчання
				$ay = substr($_POST['acy'.$query_row['id']], 2, 2)."р."; // echo " - $ay<br>"; // дві останні цифри року набрання чинності
				$dg = DegreeById($_POST['deg'.$query_row['id']]); // echo " - $dg<br>"; // ступінь, який здобувають
				$reg_number = $ep.$bd.".".$dg.".".$ay.$ef;
				$UpdPQuery = "UPDATE catalogWorkEduPlan SET	
												reg_number = \"".$reg_number."\", 
												edu_degree_id = \"".$_POST['deg'.$query_row['id']]."\", 
												base_edu_degree_id = \"".$_POST['bdg'.$query_row['id']]."\", 
												specialty_id = \"".$_POST['spctoedit'.$query_row['id']]."\", 
												edu_program_id = \"".$_POST['spz'.$query_row['id']]."\", 
												edu_form_id = \"".$_POST['edf'.$query_row['id']]."\", 
												edu_term_years = \"".$_POST['tery'.$query_row['id']]."\", 
												edu_term_months = \"".$_POST['term'.$query_row['id']]."\", 
												actualize_year = \"".$_POST['acy'.$query_row['id']]."\", 
												faculty_id = \"".$_POST['fac'.$query_row['id']]."\", 
												depart_id = \"".$_POST['dep'.$query_row['id']]."\", 
												stamp_date = \"".$_POST['std'.$query_row['id']]."\", 
												protocol_number = \"".$_POST['prn'.$query_row['id']]."\"
											WHERE id = \"".$query_row['id']."\"
										"; // echo $UpdPQuery;
			   $query_result = mysqli_query($conn, $UpdPQuery) or 
							die("<tr><td colspan=11>Помилка сервера при запиті<br>".$UpdPQuery.
								" : ".mysqli_error($conn)."</td></tr></table>");
			} else {
				if ($ipn > 0) {
?>
<tr><td colspan=11 style="color: red; font-weight: normal;">
			Навчальний план з такими параметрами вже є в довіднику!</td></tr>
<?php
				}
			}
		}
	}
?>
	<tr><th rowspan=2>Код</th><th rowspan=2>Реєстровий<br>номер плану</th>
		<th rowspan=2>Ступінь вищої освіти</th><th rowspan=2>Базова освіта<br>(здобута раніше)</th>
		<th rowspan=2>Форма<br>навчання</th><th rowspan=2>Інститут</th>
		<th rowspan=2>Спеціальність<br>або напрям</th><th rowspan=2>Спеціалізація<br>(освітня програма)</th>
		<th rowspan=2>Рік набрання<br>чинності</th>
		<th colspan=2>Дії з обʼєктом</th></tr>
	<tr><th>Розкрити/Сховати заголовок або весь РНП</th><th>До видалення</th></tr>
<?php $FilterCond = $FilterConditions[$itn];
// Завантажити перелік
	$EduPlansQuery = "
				SELECT a.id, a.reg_number, a.edu_degree_id, b.degree_name, 
						a.edu_term_years, a.edu_term_months, 
						a.base_edu_degree_id, c.degree_name AS base_degree_name, 
						a.edu_form_id, d.edu_form, 
						a.faculty_id, e.fakultet_name, 
						a.specialty_id, 
						CONCAT(f.specialty_b_code,\" \",f.specialty_name,\" (Перелік \",f.list,\")\") AS specialty_codename, 
						a.edu_program_id, 
						CONCAT(\"(\",g.eduprogram_code,\") \",g.eduprogram_name) AS eduprogram_codename, 
						a.qualification_name,	a.depart_id,
						a.actualize_year, a.stamp_date, a.protocol_number,
						a.proxy_signature,  a.depart_head_visa, a.dekan_visa, 
						a.methodist_visa, a.study_depart_boss_visa, a.vicerector_visa, 
						a.sem_start_current, a.sem_final_current 
				FROM catalogWorkEduPlan a, catalogEduDegree b, catalogEduDegree c, catalogEduForm d, 
						catalogFakultet e, catalogSpecialty f, catalogEduProgram g 
				WHERE a.edu_degree_id = b.id AND a.base_edu_degree_id = c.id 
					AND	a.edu_form_id = d.id AND a.faculty_id = e.id 
					AND a.specialty_id = f.id AND a.edu_program_id = g.id $FilterCond 
				ORDER BY id 
		";
	$query_result = mysqli_query($conn, $EduPlansQuery) or 
			die("Помилка сервера при запиті<br>".$EduPlansQuery." : ".mysqli_error($conn));
	// Шукаємо, чи є позначений план
	$CheckedEPlanId = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbxp'.$query_row['id']] = isset($_POST['cbxp'.$query_row['id']]) ? 
															$_POST['cbxp'.$query_row['id']] : "";
		if (!empty($_POST['cbxp'.$query_row['id']])) { 
			$CheckedEPlanId = $query_row['id'];
		}
	}
	mysqli_data_seek($query_result, 0); 
	$CheckedAPlanId = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbxa'.$query_row['id']] = isset($_POST['cbxa'.$query_row['id']]) ? 
															$_POST['cbxa'.$query_row['id']] : "";
		if (!empty($_POST['cbxa'.$query_row['id']])) { 
			$CheckedAPlanId = $query_row['id'];
		}
	}
	mysqli_data_seek($query_result, 0); 
	if ($CheckedEPlanId > 0 or $CheckedAPlanId > 0) { // якщо є позначений, то показуємо тільки його
		$icnt = 0;
		while ($query_row = mysqli_fetch_array($query_result)) { 
			$_POST['cbxp'.$query_row['id']] = isset($_POST['cbxp'.$query_row['id']]) ? 
															$_POST['cbxp'.$query_row['id']] : "";
			$_POST['cbxa'.$query_row['id']] = isset($_POST['cbxa'.$query_row['id']]) ? 
															$_POST['cbxa'.$query_row['id']] : "";
			$_POST['delp'.$query_row['id']] = isset($_POST['delp'.$query_row['id']]) ?
															$_POST['delp'.$query_row['id']] : "";
			if (!empty($_POST['cbxp'.$query_row['id']]) or !empty($_POST['cbxa'.$query_row['id']])) {
?>
	<tr style="background-color: LightBlue;">
		<td rowspan=2 style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['reg_number']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['base_degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['edu_form']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_codename']; ?></td>                        		
		<td style="text-align: left;"><?php echo $query_row['actualize_year']; ?></td>
		<td rowspan=2>
<?php   if ($TrueAdmin)
					echo paramCheker("cbxp".$query_row['id'], $_POST['cbxp'.$query_row['id']], 
								"Редагувати заголовок", "onchange=\"submit()\"");
				if (empty($_POST['cbxa'.$query_row['id']])) {
?>
			<div style="background-color: White;">
<?php		 
					$_POST['deg'.$query_row['id']] = isset($_POST['deg'.$query_row['id']]) ? $_POST['deg'.$query_row['id']] : "";
					$_POST['tery'.$query_row['id']] = isset($_POST['tery'.$query_row['id']]) ? $_POST['tery'.$query_row['id']] : "";
					$_POST['term'.$query_row['id']] = isset($_POST['term'.$query_row['id']]) ? $_POST['term'.$query_row['id']] : "";
					$_POST['bdg'.$query_row['id']] = isset($_POST['bdg'.$query_row['id']]) ? $_POST['bdg'.$query_row['id']] : "";
					$_POST['edf'.$query_row['id']] = isset($_POST['edf'.$query_row['id']]) ? $_POST['edf'.$query_row['id']] : "";
					$_POST['spctoedit'.$query_row['id']] = isset($_POST['spctoedit'.$query_row['id']]) ? $_POST['spctoedit'.$query_row['id']] : "";
					$_POST['spz'.$query_row['id']] = isset($_POST['spz'.$query_row['id']]) ? $_POST['spz'.$query_row['id']] : "";
					$_POST['dep'.$query_row['id']] = isset($_POST['dep'.$query_row['id']]) ? $_POST['dep'.$query_row['id']] : "";
					$_POST['fac'.$query_row['id']] = isset($_POST['fac'.$query_row['id']]) ? $_POST['fac'.$query_row['id']] : "";
					$_POST['acy'.$query_row['id']] = isset($_POST['acy'.$query_row['id']]) ? $_POST['acy'.$query_row['id']] : "";
					$_POST['std'.$query_row['id']] = isset($_POST['std'.$query_row['id']]) ? $_POST['std'.$query_row['id']] : "";
					$_POST['prn'.$query_row['id']] = isset($_POST['prn'.$query_row['id']]) ? $_POST['prn'.$query_row['id']] : "";
					echo selectCommonSelect
					("Ступінь / ОКР: ", "deg".$query_row['id'], $conn, $DegreesQuery, 
					"id", $query_row['edu_degree_id'], "degree_name", ""); ?> &nbsp; &nbsp; 
Строк навчання: 
<input type="text" name="tery<?php echo $query_row['id']; ?>" 
				style="font-weight: bold; font-size: 125%; width: 12px; text-align: right; " 
				value="<?php echo $query_row['edu_term_years']; ?>" />р. 
<input type="text" name="term<?php echo $query_row['id']; ?>" 
				style="font-weight: bold; font-size: 125%; width: 24px; text-align: right; " 
				value="<?php echo $query_row['edu_term_months']; ?>" />м. 
<?php 
					echo selectCommonSelect
					("на базі ступеню / ОКР: ", "bdg".$query_row['id'], $conn, $BaseDegreesQuery, 
						"id", $query_row['base_edu_degree_id'], "degree_name", "")."<br>";
					echo selectCommonSelect
					(" Форма навчання: ", "edf".$query_row['id'], $conn, $EduFormsQuery, 
						"id", $query_row['edu_form_id'], "edu_form", ""); ?><br>
<?php			echo selectCommonSelect
					("Спеціальність / напрям: ", "spctoedit".$query_row['id'], $conn, $SpecialtiesQuery,
						"id", $query_row['specialty_id'], "specialty_codename", "")."<br>";
					$EduProgramsQuery = str_replace("ORDER ",
											"WHERE specialty_id = \"".$query_row['specialty_id']."\" ORDER ", 
											$EduProgramsQuery); // echo $EduProgramsQuery;
					echo selectCommonSelect
					("Спеціалізація: ", "spz".$query_row['id'], $conn, $EduProgramsQuery, 
						"id", $query_row['edu_program_id'], "eduprogram_codename", "")."<br>";
					echo selectCommonSelect
					("Інститут (факультет): ", "fac".$query_row['id'], $conn, $FacultiesQuery, 
						"id", $query_row['faculty_id'], "fakultet_name", "")."<br>";
					echo selectCommonSelect
					("Випускна кафедра: ", "dep".$query_row['id'], $conn, $DepartsQuery, 
						"id", $query_row['depart_id'], "nazva_kaf", ""); ?><br>
Набирає чинності з: 
<input type="text" name="acy<?php echo $query_row['id']; ?>" 
				style="font-weight: bold; font-size: 125%; width: 120px;" 
				value="<?php echo $query_row['actualize_year']; ?>" />н.р. &nbsp; &nbsp; 
Дата затвердження: 
<input type="date" name="std<?php echo $query_row['id']; ?>" 
				style="font-weight: bold; font-size: 125%; width: 165px;" 
				value="<?php echo $query_row['stamp_date']; ?>" /> &nbsp; 
№ протоколу: 
<input type="text" name="prn<?php echo $query_row['id']; ?>" 
				style="font-weight: bold; font-size: 125%; width: 75px;" 
				value="<?php echo $query_row['protocol_number']; ?>" /> &nbsp; &nbsp; 
<input type="submit" name="sbp<?php echo $query_row['id']; ?>" value="Зберегти" 
				style="font-weight: bold; color: blue;" /></td>
			</div>
<?php	} else echo paramCheker("cbxa".$query_row['id'], $_POST['cbxa'.$query_row['id']], 
												"Показати весь РНП", "onchange=\"submit()\"");
			$PositionsQuery = "SELECT COUNT(id) AS cnt FROM plan_work_subj_study 
													WHERE subject_id > 0 and plan_id = ".$query_row['id'];
			$PQ_result = mysqli_query($conn, $PositionsQuery) 
										or die("Помилка сервера при запиті<br>".$PositionsQuery." : ".mysqli_error($conn));
			$PQ_row = mysqli_fetch_array($PQ_result); 
			echo "Дисциплін: ".bold($PQ_row['cnt']); ?>
		</td>
		<td rowspan=2><?php 
			if ($TrueAdmin and ($PQ_row['cnt'] == 0)) echo paramChekerRedInline
				("delp".$query_row['id'],$_POST['delp'.$query_row['id']],$query_row['id'],""); ?></td>
	</tr><div style="top: 1px;	right: 1px;	position: fixed; background-color: LightBlue; 
										font-size: 150%; font-weight: bold; font-family: sans-serif;">
<?php echo $query_row['reg_number']; ?></div>
<?php   require "visas_list_on_edu_plan.php";
			}
			$icnt++;
		}
	} else { // показуємо всі плани
		$icnt = 0;
		while ($query_row = mysqli_fetch_array($query_result)) { 
			$_POST['cbxp'.$query_row['id']] = isset($_POST['cbxp'.$query_row['id']]) ? 
															$_POST['cbxp'.$query_row['id']] : "";
			$_POST['cbxa'.$query_row['id']] = isset($_POST['cbxa'.$query_row['id']]) ? 
															$_POST['cbxa'.$query_row['id']] : "";
			$_POST['delp'.$query_row['id']] = isset($_POST['delp'.$query_row['id']]) ?
															$_POST['delp'.$query_row['id']] : "";
?>
	<tr><td rowspan=2 style="text-align: right;"><?php echo $query_row['id']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['reg_number']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['base_degree_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['edu_form']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['fakultet_name']; ?></td>                        
		<td style="text-align: left;"><?php echo $query_row['specialty_codename']; ?></td>
		<td style="text-align: left;"><?php echo $query_row['eduprogram_codename']; ?></td>                        		
		<td style="text-align: left;"><?php echo $query_row['actualize_year']; ?></td>
		<td rowspan=2><?php 
			if ($TrueAdmin)
				echo paramCheker("cbxp".$query_row['id'], $_POST['cbxp'.$query_row['id']], 
								"Редагувати заголовок", "onchange=\"submit()\"");
			echo paramCheker("cbxa".$query_row['id'], $_POST['cbxa'.$query_row['id']], 
								"Показати весь РНП", "onchange=\"submit()\"");
			$PositionsQuery = "SELECT COUNT(id) AS cnt FROM plan_work_subj_study 
													WHERE subject_id > 0 and plan_id = ".$query_row['id'];
			$PQ_result = mysqli_query($conn, $PositionsQuery) 
										or die("Помилка сервера при запиті<br>".$PositionsQuery." : ".mysqli_error($conn));
			$PQ_row = mysqli_fetch_array($PQ_result); 
			echo "Дисциплін: ".bold($PQ_row['cnt']); ?>
		</td>
		<td rowspan=2><?php 
			if ($TrueAdmin and ($PQ_row['cnt'] == 0))	echo paramChekerRedInline
				("delp".$query_row['id'],$_POST['delp'.$query_row['id']],$query_row['id'],""); ?></td>
	</tr><?php   require "visas_list_on_edu_plan.php";
  		$icnt++;
		}
	}
?>
		<tr><td colspan=10 style="text-align: right;">Загальна кількість вибраних РНП: 
																									<?php echo bold($icnt); ?></td>
		<td><?php
	if ($TrueAdmin) { ?>
		<input type="checkbox" id="delps" name="delps" 
								onclick="if (confirm('Дійсно видалити позначені РНП?')) submit();" class="del" />
						<label for="delps" class="del">Видалити</label><?php
	} ?></td></tr>
</table>
<?php mysqli_data_seek($query_result, 0);
while ($query_row = mysqli_fetch_array($query_result)) { 
	if (!empty($_POST['cbxa'.$query_row['id']])) { 
		$mode = "VIEW"; $frag = "NO"; $stud = "NO";
		require "./edu_plan/study_plan_header.php";
		require "./edu_plan/schedule_edu_process.php";
		require "./edu_plan/plan_subjects_study.php";
		require "./edu_plan/practiques_certification.php";
		require "./edu_plan/study_plan_visaed.php";
		switch ($_SESSION['user_role']) {
			case "ROLE_METHODIST": require "./edu_plan/study_plan_methodist_visa.php"; break;
			case "ROLE_STUDY_DEP_BOSS": require "./edu_plan/study_plan_studydepboss_visa.php"; break;
		}
	}
}
?>