<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль time_distrib_to_schedule.php</p>"; 
                            require "footer.php"; exit(); }
$_POST['deptosel'] = isset($_POST['deptosel']) ? $_POST['deptosel'] : "";
$TeachersDepart_query = "SELECT id, CONCAT(teacher_surname, ' ', 
											LEFT(teacher_name, 1), '.', LEFT(teacher_pobatkovi, 1), '.') AS teacher
									FROM catalogTeacher WHERE kaf_link = ".$_POST['deptosel']." AND role = 2
									ORDER BY teacher";
/* $TimeDistrib_query = "SELECT a.*,
								b.naz_dus, c.nazva_grupu, c.num_kurs, d.fakultet_shufr,
								CONCAT(e.teacher_surname, ' ', 
											LEFT(e.teacher_name, 1), '.', LEFT(e.teacher_pobatkovi, 1), '.') AS lector,
								CONCAT(f.teacher_surname, ' ', 
											LEFT(f.teacher_name, 1), '.', LEFT(f.teacher_pobatkovi, 1), '.') AS tpract,
								CONCAT(g.teacher_surname, ' ', 
											LEFT(g.teacher_name, 1), '.', LEFT(g.teacher_pobatkovi, 1), '.') AS tlabor
								FROM (time_distrib_to_create_schedule a, catalogSubject b, 
											catalogGroup c, catalogFakultet d)
								LEFT JOIN catalogTeacher e 
									ON e.id = a.lector_teacher_id 
								LEFT JOIN catalogTeacher f 
									ON f.id = a.practical_teacher_id 
								LEFT JOIN catalogTeacher g 
									ON g.id = a.laboratorials_teacher_id 
								WHERE a.depart_id = ".$_POST['deptosel']." AND a.subject_id = b.id AND 
											a.group_id = c.id AND c.shufr_fak = d.id
								ORDER BY b.naz_dus, a.free_block, a.stream_code"; */
$TimeDistrib_query = "SELECT a.*,
								b.naz_dus, c.group_next_name AS nazva_grupu, c.acad_year_next AS num_kurs, d.fakultet_shufr,
								CONCAT(e.teacher_surname, ' ', 
											LEFT(e.teacher_name, 1), '.', LEFT(e.teacher_pobatkovi, 1), '.') AS lector,
								CONCAT(f.teacher_surname, ' ', 
											LEFT(f.teacher_name, 1), '.', LEFT(f.teacher_pobatkovi, 1), '.') AS tpract,
								CONCAT(g.teacher_surname, ' ', 
											LEFT(g.teacher_name, 1), '.', LEFT(g.teacher_pobatkovi, 1), '.') AS tlabor
								FROM (time_distrib_to_create_schedule a, catalogSubject b, 
											catalogGroupNext c, catalogFakultet d)
								LEFT JOIN catalogTeacher e 
									ON e.id = a.lector_teacher_id 
								LEFT JOIN catalogTeacher f 
									ON f.id = a.practical_teacher_id 
								LEFT JOIN catalogTeacher g 
									ON g.id = a.laboratorials_teacher_id 
								WHERE a.depart_id = ".$_POST['deptosel']." AND a.subject_id = b.id AND 
											a.group_id = c.id AND c.faculty_id = d.id
								ORDER BY b.naz_dus, a.free_block, a.stream_code";
/*								a.id, a.depart_id, a.sem, a.subject_id, a.free_block, a.stream_code,
								a.group_id, a.students_count, 
								a.lectural_hours_per_week, a.lector_teacher_id, 
								a.practical_hours_per_week, a.practical_teacher_id,
								a.subgroup_laboratorials, a.laboratorials_hours_per_week,
								a.laboratorials_teacher_id, a.laboratorials_rooms, a.comment,*/
$_POST['sbtSave'] = isset($_POST['sbtSave']) ? $_POST['sbtSave'] : "";
if (!empty($_POST['sbtSave'])) {
	$TimeDistrib_result = mysqli_query($conn, $TimeDistrib_query) or 
				die("Помилка сервера при запиті<br>".$TimeDistrib_query." : ".mysqli_error($conn));
	while ($TimeDistrib_row = mysqli_fetch_array($TimeDistrib_result)) { 
		$_POST['chkSubj'.$TimeDistrib_row['id']] = 
			isset($_POST['chkSubj'.$TimeDistrib_row['id']]) ? $_POST['chkSubj'.$TimeDistrib_row['id']] : "";
		if (!empty($_POST['chkSubj'.$TimeDistrib_row['id']])) {
			$TimeDistribUpdate_query = "
				UPDATE time_distrib_to_create_schedule
				SET	stream_code = \"".$_POST['tbxStream'.$TimeDistrib_row['id']]."\",
						lector_teacher_id = \"".$_POST['cbxLector'.$TimeDistrib_row['id']]."\",
						lectural_rooms = \"".$_POST['tbxLecRooms'.$TimeDistrib_row['id']]."\",
						practical_teacher_id = \"".$_POST['cbxTPract'.$TimeDistrib_row['id']]."\",
						practicals_rooms = \"".$_POST['tbxPraRooms'.$TimeDistrib_row['id']]."\",
						laboratorials_teacher_id = \"".$_POST['cbxTLabor'.$TimeDistrib_row['id']]."\",
						laboratorials_rooms = \"".$_POST['tbxLabRooms'.$TimeDistrib_row['id']]."\",
						comment = \"".$_POST['tbxComment'.$TimeDistrib_row['id']]."\"
				WHERE id = ".$TimeDistrib_row['id'];
			$TimeDistribUpdate_result = mysqli_query($conn, $TimeDistribUpdate_query) or 
						die("Помилка сервера при запиті<br>".$TimeDistribUpdate_query." : ".mysqli_error($conn));
		}
		unset($_POST['chkSubj'.$TimeDistrib_row['id']]);
	}	
}
$TimeDistrib_result = mysqli_query($conn, $TimeDistrib_query) or 
			die("Помилка сервера при запиті<br>".$TimeDistrib_query." : ".mysqli_error($conn));
?>
<p style="font-size: 200%; text-align: center;">Розподіл аудиторного часу <?php 
if ($_POST['deptosel'] == 80) echo "на кафедрах іноземних мов"; 
else echo "на кафедрі ".DepartCodeById($_POST['deptosel']); ?> для складання розкладу занять<br>
(осінній семестр 2018/2019 н.р.)
</p>
<table style="margin-left: 0%; width: 100%;">
<thead>
	<tr><th rowspan=2>№</th><th rowspan=2>Назва дисципліни</th><th rowspan=2>Інститут</th>
		<th rowspan=2>Курс /<br>семестр</th><th rowspan=2>Академгрупа<br>(кількість студентів)</th>
		<th rowspan=2>№<br>потоку</th><th rowspan=2>Ауд.<br>годин/<br>тиж.</th>
		<th colspan=3>Лекції</th><th colspan=3>Практичні</th><th colspan=3>Лабораторні</th><th rowspan=2>Примітки</th></tr>
	<tr>
		<th>Год.</th><th>П.І.Б. викладача</th><th>№<br>ауд.</th>
		<th>Год.</th><th>П.І.Б. викладача</th><th>№<br>ауд.</th>
		<th>Год.</th><th>П.І.Б. викладача</th><th>№<br>лабораторії</th></tr>
</thead>
<tbody>
<?php $icnt = 0;
while ($TimeDistrib_row = mysqli_fetch_array($TimeDistrib_result)) { $icnt++; 
	$_POST['chkSubj'.$TimeDistrib_row['id']] = 
		isset($_POST['chkSubj'.$TimeDistrib_row['id']]) ? $_POST['chkSubj'.$TimeDistrib_row['id']] : "";
	$_POST['cbxLector'.$TimeDistrib_row['id']] = 
		isset($_POST['cbxLector'.$TimeDistrib_row['id']]) ? $_POST['cbxLector'.$TimeDistrib_row['id']] : 
																										$TimeDistrib_row['lector_teacher_id'];
	$_POST['cbxTPract'.$TimeDistrib_row['id']] = 
		isset($_POST['cbxTPract'.$TimeDistrib_row['id']]) ? $_POST['cbxTPract'.$TimeDistrib_row['id']] : 
																										$TimeDistrib_row['practical_teacher_id']; 
	$_POST['cbxTLabor'.$TimeDistrib_row['id']] = 
		isset($_POST['cbxTLabor'.$TimeDistrib_row['id']]) ? $_POST['cbxTLabor'.$TimeDistrib_row['id']] : 
																										$TimeDistrib_row['laboratorials_teacher_id']; 
?>
	<tr><td><?php echo $icnt.".".$TimeDistrib_row['id']; 
	if (!$TrueBoss)
		echo paramChekerInline('chkSubj'.$TimeDistrib_row['id'], 
														$_POST['chkSubj'.$TimeDistrib_row['id']], "", "onchange=\"submit()\""); ?></td>
		<td><?php echo $TimeDistrib_row['naz_dus'].
										($TimeDistrib_row['free_block'] != " " ? 
											"" : " (".$TimeDistrib_row['free_block'].")"); ?></td>
		<td><?php echo $TimeDistrib_row['fakultet_shufr']; ?></td>
		<td><?php echo $TimeDistrib_row['num_kurs']." / ".$TimeDistrib_row['sem']; ?></td>
		<td><?php echo $TimeDistrib_row['nazva_grupu'].
									 (($TimeDistrib_row['laboratorials_hours_per_week'] > 0) ? 
									" / ".$TimeDistrib_row['subgroup_laboratorials'] : "")." (".
									(($TimeDistrib_row['subgroup_laboratorials'] == 1) ? 
												intval(ceil($TimeDistrib_row['students_count'])) : 
												intval($TimeDistrib_row['students_count']))." ст.)"; ?></td>
		<td><?php if (empty($_POST['chkSubj'.$TimeDistrib_row['id']]))
								echo $TimeDistrib_row['stream_code']; 
							else { ?>
				<input type="textbox" name="tbxStream<?php echo $TimeDistrib_row['id']; ?>" 
						style="font-weight: bold; width: 40px;"
						value="<?php echo $TimeDistrib_row['stream_code']; ?>" /><?php					
							} ?></td>
		<td><?php echo $TimeDistrib_row['lectural_hours_per_week'] + 
										$TimeDistrib_row['practical_hours_per_week'] + 
										$TimeDistrib_row['laboratorials_hours_per_week']; ?></td>
		<td><?php if ($TimeDistrib_row['lectural_hours_per_week'] > 0) 
								echo ($TimeDistrib_row['lectural_hours_per_week'] == 
										intval($TimeDistrib_row['lectural_hours_per_week']) ? 
									 intval($TimeDistrib_row['lectural_hours_per_week']) : 
									 $TimeDistrib_row['lectural_hours_per_week']); ?></td>
		<td><?php if (empty($_POST['chkSubj'.$TimeDistrib_row['id']]))
								echo $TimeDistrib_row['lector']; 
							else echo selectCommonSelect
										("", "cbxLector".$TimeDistrib_row['id'], $conn, $TeachersDepart_query, "id", 
											$TimeDistrib_row['lector_teacher_id'], "teacher", 
											"style=\"width: 130px; \""); ?></td>
		<td><?php if (empty($_POST['chkSubj'.$TimeDistrib_row['id']]))
								echo $TimeDistrib_row['lectural_rooms']; 
							else { ?>
				<input type="textbox" name="tbxLecRooms<?php echo $TimeDistrib_row['id']; ?>" 
						style="font-weight: bold; width: 100px;"
						value="<?php echo $TimeDistrib_row['lectural_rooms']; ?>" /><?php					
							} ?></td>
		<td><?php if ($TimeDistrib_row['practical_hours_per_week'] > 0) 
								echo ($TimeDistrib_row['practical_hours_per_week'] == 
										intval($TimeDistrib_row['practical_hours_per_week']) ? 
									 intval($TimeDistrib_row['practical_hours_per_week']) : 
									 $TimeDistrib_row['practical_hours_per_week']); ?></td>
		<td><?php if (empty($_POST['chkSubj'.$TimeDistrib_row['id']]))
								echo $TimeDistrib_row['tpract']; 
							else echo selectCommonSelect
										("", "cbxTPract".$TimeDistrib_row['id'], $conn, $TeachersDepart_query, "id", 
											$TimeDistrib_row['practical_teacher_id'], "teacher", 
											"style=\"width: 130px; \""); ?></td>
		<td><?php if (empty($_POST['chkSubj'.$TimeDistrib_row['id']]))
								echo $TimeDistrib_row['practicals_rooms']; 
							else { ?>
				<input type="textbox" name="tbxPraRooms<?php echo $TimeDistrib_row['id']; ?>" 
						style="font-weight: bold; width: 100px;"
						value="<?php echo $TimeDistrib_row['practicals_rooms']; ?>" /><?php					
							} ?></td>
		<td><?php if ($TimeDistrib_row['laboratorials_hours_per_week'] > 0) 
							echo ($TimeDistrib_row['laboratorials_hours_per_week'] == 
										intval($TimeDistrib_row['laboratorials_hours_per_week']) ? 
									 intval($TimeDistrib_row['laboratorials_hours_per_week']) :
									 $TimeDistrib_row['laboratorials_hours_per_week']); ?></td>
		<td><?php if (empty($_POST['chkSubj'.$TimeDistrib_row['id']]))
								echo $TimeDistrib_row['tlabor']; 
							else echo selectCommonSelect
										("", "cbxTLabor".$TimeDistrib_row['id'], $conn, $TeachersDepart_query, "id", 
											$TimeDistrib_row['laboratorials_teacher_id'], "teacher", 
											"style=\"width: 130px; \""); ?></td>
		<td><?php if (empty($_POST['chkSubj'.$TimeDistrib_row['id']]))
								echo $TimeDistrib_row['laboratorials_rooms']; 
							else { ?>
				<input type="textbox" name="tbxLabRooms<?php echo $TimeDistrib_row['id']; ?>" 
						style="font-weight: bold; width: 100px;"
						value="<?php echo $TimeDistrib_row['laboratorials_rooms']; ?>" /><?php					
							} ?></td>
		<td><?php if (empty($_POST['chkSubj'.$TimeDistrib_row['id']]))
								echo $TimeDistrib_row['comment']; 
							else { ?>
				<input type="textbox" name="tbxComment<?php echo $TimeDistrib_row['id']; ?>" 
						style="font-weight: bold; width: 100px;"
						value="<?php echo $TimeDistrib_row['comment']; ?>" /><?php					
							} ?></td>
	</tr><?php 
	if (!empty($_POST['chkSubj'.$TimeDistrib_row['id']])) { ?>
	<tr><td colspan=17>
				<input type="submit" name="sbtSave" value="Зберегти" style="font-weight: bold; color: green;" >
			</td></tr><?php
	}
} ?>
</tbody>
</table>
