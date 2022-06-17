<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль study_work_amount.php</p>"; 
                            require "footer.php"; exit(); } ?>
<p style="font-size: 130%; color: blue; text-align: center; margin-bottom: 0.2em; margin-top: 0.2em">
<b>УВАГА!</b> Модуль "Навчальне навантаження кафедри" працює в <b>ТЕСТОВОМУ</b> режимі</p>
<p style="font-size: 150%; text-align: center; margin-bottom: 0.2em; margin-top: 0.2em">
Прогнозний обсяг навчальної роботи кафедр університету на 2018/2019 н.р.<br>
станом на <?php echo date("d.m.Y") ; ?> (за новими нормами часу)
</p>
<table style="margin-left: 0%; width: 100%;">
<thead>
	<tr><th rowspan=2>№</th><th rowspan=2>Кафедра</th><th rowspan=2>Штат на<br>01.10.17р.</th>
			<th rowspan=2>Середнє<br>навант.</th>
			<th colspan=17>Обсяг навчальної роботи за видами занять, год.</th></tr>
	<tr><th>Усього</th><th>Лек.</th><th>Лаб.</th><th>Пр.</th><th>Конс.</th><th>Екз.</th><th>Зал.</th><th>Дом.<br>роб.</th>
			<th>КП/Р</th><th>Прак-<br>тики</th><th>ЕК</th><th>Гол.<br>ЕК</th><th>Вип.<br>роб.</th>
			<th>Керівн.<br>аспір.</th><th>Інші</th>
	</tr>
</thead>
<tbody>
<?php $icnt = 0; $SumLec = 0; $SumLab = 0; $SumPrac = 0; $SumConsCur = 0; $SumConsExam = 0;
$SumCons = 0; $SumExam = 0; $SumCred = 0; $SumHome = 0; $SumPap = 0; 
$SumPraq = 0; $SumExamBd = 0; $SumHead = 0; $SumThes = 0; 
$SumPost = 0; $SumOther = 0; $SumSalaries = 0;
$TimeAmount_query = "
	SELECT a.depart_group_id, a.id, REPLACE(a.nazva_kaf, \"Кафедра \", \"\") AS depart_name, 
				a.postgrads * 50 + a.applicants * 25 AS postgrads_hours, a.other_hours, a.teachers_salaries,
				SUM(b.lectural_hours) AS lec, SUM(b.practicals_hours) AS pr, SUM(b.laboratorials_hours) AS lab, 
				SUM(b.consult_current_hours) AS cons_cur, 
				SUM(b.consult_exam_hours) AS cons_exam, 
				SUM(b.consult_current_hours + b.consult_exam_hours) AS cons, 
				SUM(b.exam_hours) AS exam, SUM(b.credit_hours) AS cred, 
				SUM(b.home_tasks_hours) AS home, SUM(b.research_paper_hours) AS pap, 
				SUM(b.practical_training_hours) AS prqt, SUM(b.examination_board_hours) AS exambd, 
				SUM(b.head_exam_board_hours) AS head, SUM(b.final_thesis_hours) AS final, 
				SUM(b.postgraduate_hours) AS pgrad, SUM(b.other_hours) AS other, COUNT(*) AS rows
	FROM catalogDepartment a, time_amount_for_departs b, catalogGroupNext c
	WHERE b.depart_id = a.id AND b.group_id = c.id 
	GROUP BY a.id ORDER BY a.depart_group_id, depart_name
";
$TimeAmount_result = mysqli_query($conn, $TimeAmount_query) or 
			die("Помилка сервера при запиті<br>".$TimeAmount_query." : ".mysqli_error($conn));
while ($TA_row = mysqli_fetch_array($TimeAmount_result)) { $icnt++; 
// кафедрі фізвиховання і спорту поточні консультації не враховувати 
	if ($TA_row['id'] == 45) $TA_row['cons_cur'] = 0; ?>
	<tr><td><?php echo $icnt; ?></td>
			<td style="text-align: left; width: 500px;"><?php echo $TA_row['depart_name']; 
	$DSum = $TA_row['lec'] + $TA_row['lab'] + $TA_row['pr'] + $TA_row['cons_cur'] + $TA_row['cons_exam'] + 
					$TA_row['exam'] + $TA_row['cred'] + $TA_row['home'] + $TA_row['pap'] + 
					$TA_row['prqt'] + $TA_row['exambd'] + $TA_row['head'] + $TA_row['final'] + 
					$TA_row['postgrads_hours'] + $TA_row['other_hours']; 
	$Streams_query = "SELECT DISTINCT stream_code FROM time_amount_for_departs 
										WHERE stream_code != '' and depart_id = ".$TA_row['id'];
	$Streams_result = mysqli_query($conn, $Streams_query) or 
			die("Помилка сервера при запиті<br>".$Streams_query." : ".mysqli_error($conn));
	if (mysqli_num_rows($Streams_result) > 0) { ?><span style="color: blue; font-weight: bold;">
		 (П)</span><?php } echo " - ".$TA_row['rows'];
	$TimePerSalary = ($TA_row['teachers_salaries'] > 0) ? 
								round($DSum / $TA_row['teachers_salaries']) : 0; ?></td>
			<td><?php echo $TA_row['teachers_salaries']; ?></td><td <?php 
	if ($TimePerSalary > $MaxTimePerSalary) echo 'style="color: red"'; ?>><?php 
		echo ($TimePerSalary > 0) ? $TimePerSalary : ""; ?></td>
			<td><?php echo $DSum; ?></td>
			<td><?php echo $TA_row['lec']; ?></td><td><?php echo $TA_row['lab']; ?></td>
			<td><?php echo $TA_row['pr']; ?></td><td><?php 
								echo $TA_row['cons_cur']."+".$TA_row['cons_exam']; ?></td>
			<td><?php echo $TA_row['exam']; ?></td><td><?php echo $TA_row['cred']; ?></td>
		  <td><?php echo $TA_row['home']; ?></td><td><?php echo $TA_row['pap']; ?></td>
			<td><?php echo $TA_row['prqt']; ?></td><td><?php echo $TA_row['exambd']; ?></td>
			<td><?php echo $TA_row['head']; ?></td><td><?php echo $TA_row['final']; ?></td>
			<td><?php echo $TA_row['postgrads_hours']; ?></td><td><?php echo $TA_row['other_hours']; ?></td>
	</tr><?php 
	if ($TA_row['id'] != 12) { // не кафедра військової підготовки
		$SumLec += $TA_row['lec']; $SumLab += $TA_row['lab']; $SumPrac += $TA_row['pr'];
		$SumConsCur += $TA_row['cons_cur']; $SumConsExam += $TA_row['cons_exam'];
		$SumCons += $TA_row['cons']; $SumExam += $TA_row['exam']; $SumCred += $TA_row['cred'];
		$SumHome += $TA_row['home']; $SumPap += $TA_row['pap']; $SumPraq += $TA_row['prqt']; 
		$SumExamBd += $TA_row['exambd']; $SumHead += $TA_row['head']; $SumThes += $TA_row['final']; 
		$SumPost += $TA_row['postgrads_hours']; $SumOther += $TA_row['other_hours'];
	}
}
$SumStudy = $SumLec + $SumLab + $SumPrac + $SumConsCur + $SumConsExam + 
						$SumExam + $SumCred + $SumHome + $SumPap + 
						$SumPraq + $SumExamBd + $SumHead + $SumThes + $SumPost + $SumOther; 
$Salaries_query = "SELECT SUM(teachers_salaries) AS total_salaries FROM catalogDepartment";
$Salaries_result = mysqli_query($conn, $Salaries_query) or 
			die("Помилка сервера при запиті<br>".$Salaries_query." : ".mysqli_error($conn));
$Salaries_row = mysqli_fetch_array($Salaries_result); ?>
<tr>
	<th colspan=2>Разом</th><th><?php echo round($Salaries_row['total_salaries'], 2);  ?></th>
	<th><?php echo round($SumStudy / $Salaries_row['total_salaries']); ?></th><th><?php echo $SumStudy; ?></th>
	<th><?php echo $SumLec; ?></th><th><?php echo $SumLab; ?></th><th><?php echo $SumPrac; ?></th>
	<th><?php echo $SumConsCur."+<br>".$SumConsExam/* ."=<br>".$SumCons */; ?></th>
	<th><?php echo $SumExam; ?></th><th><?php echo $SumCred; ?></th>
  <th><?php echo $SumHome; ?></th><th><?php echo $SumPap; ?></th><th><?php echo $SumPraq; ?></th>
	<th><?php echo $SumExamBd; ?></th><th><?php echo $SumHead; ?></th><th><?php echo $SumThes; ?></th>
	<th><?php echo $SumPost; ?></th><th><?php echo $SumOther; ?></th></tr>
	<tr><th colspan=2></th><th>Штат на<br>01.10.17р.</th>
			<th>Середнє<br>навант.</th><th>Усього</th>
			<th>Лек.</th><th>Лаб.</th><th>Пр.</th><th>Конс.</th>
			<th>Екз.</th><th>Зал.</th><th>Дом.з.</th><th>КП/Р</th><th>Прак-<br>тики</th>
			<th>ЕК</th><th>Гол.<br>ЕК</th><th>Вип.<br>роб.</th><th>Керівн.<br>аспір.</th><th>Інші</th>
	</tr><tr><td colspan=22>Примітка. Знаком "П" позначені кафедри, які сформували лекційні потоки</td></tr>
</tbody>
</table>
