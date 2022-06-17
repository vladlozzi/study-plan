<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль papers.php</p>"; require "footer.php"; exit(); }
// Кількість курсових робіт (проектів) у РНП за спеціальностями
$_POST['radDegreeSelect'] = isset($_POST['radDegreeSelect']) ? $_POST['radDegreeSelect'] : "Bachelor";
?><p style="text-align: center; margin-top: 0.5em; margin-bottom: 0.1em; font-size: 133%;">
Розподіл часу на аудиторні заняття у базових РНП спеціальностей Переліку-2015</p>
<p style="text-align: center; margin-top: 0.5em; margin-bottom: 0.1em; font-size: 110%;">
Виберіть ступінь вищої освіти &nbsp; 
<input type="radio" name="radDegreeSelect" value="Bachelor" onclick="submit()" 
	<?php if ($_POST['radDegreeSelect'] == "Bachelor") echo "checked"; ?>> 
	Бакалавр (на базі середньої освіти)&nbsp; &nbsp; 
<input type="radio" name="radDegreeSelect" value="Master" onclick="submit()" 
	<?php if ($_POST['radDegreeSelect'] == "Master") echo "checked"; ?>> 
	Магістр
<?php
switch ($_POST['radDegreeSelect']) {
	case "Bachelor" : $DegreeCond = "c.edu_degree_id = 2 AND c.base_edu_degree_id = 9"; 
										$DegreeSelected = "бакалавра"; break;
	case "Master" : $DegreeCond = "c.edu_degree_id = 3"; $DegreeSelected = "магістра"; break;
/*	case "Total" : 
			$DegreeCond = "(c.edu_degree_id = 2 AND c.base_edu_degree_id = 9 OR c.edu_degree_id = 3)"; 
			$DegreeSelected = ""; break; */
}
$Auds_query = "
	SELECT a.specialty_name, b.eduprogram_name, 
		CONCAT(b.eduprogram_code, ' (', f.chief_fam_init, ')') AS head,
		SUM(d.sem1_lectural_hours + d.sem1_laboratorials_hours + d.sem1_practicals_hours) AS sem1_aud_hours,
		SUM(d.sem2_lectural_hours + d.sem2_laboratorials_hours + d.sem2_practicals_hours) AS sem2_aud_hours,
		SUM(d.sem3_lectural_hours + d.sem3_laboratorials_hours + d.sem3_practicals_hours) AS sem3_aud_hours,
		SUM(d.sem4_lectural_hours + d.sem4_laboratorials_hours + d.sem4_practicals_hours) AS sem4_aud_hours,
		SUM(d.sem5_lectural_hours + d.sem5_laboratorials_hours + d.sem5_practicals_hours) AS sem5_aud_hours,
		SUM(d.sem6_lectural_hours + d.sem6_laboratorials_hours + d.sem6_practicals_hours) AS sem6_aud_hours,
		SUM(d.sem7_lectural_hours + d.sem7_laboratorials_hours + d.sem7_practicals_hours) AS sem7_aud_hours,
		SUM(d.sem8_lectural_hours + d.sem8_laboratorials_hours + d.sem8_practicals_hours) AS sem8_aud_hours
	FROM catalogSpecialty a, catalogEduProgram b,	catalogWorkEduPlan c, plan_work_subj_study d,
			catalogDepartment e, catalogDepartChief f
	WHERE 
		b.specialty_id = a.id AND a.list = 2015 AND 
		c.edu_program_id = b.id AND c.edu_form_id = 1 AND (d.free_block = \"\" OR d.free_block=\"а\") AND $DegreeCond 
		 AND d.plan_id = c.id AND c.depart_id = e.id AND c.depart_id = f.departmentId
	GROUP BY b.eduprogram_name 
	ORDER BY specialty_name, eduprogram_name";
?><br>
<table style="margin: auto; width: 75%;">
<thead>
	<tr><th rowspan=2>№</th><th rowspan=2>Назва спеціальності</th>
		<th rowspan=2>Назва спеціалізації (осв. програми)</th>
		<th rowspan=2>Керівник</th>
		<th colspan=8>Кількість аудиторних годин<br>за тиждень у РНП<br><?php echo $DegreeSelected; ?> за семестрами</th>
	</tr>
	<tr><?php for ($isem = 1; $isem <=8; $isem++) { ?><th><?php echo $isem; ?></th><?php } ?></tr>
</thead>
<tbody><?php
$Auds_result = mysqli_query($conn, $Auds_query) or 
		die("Помилка сервера при запиті<br>".$Auds_query." : ".mysqli_error($conn));
$icnt = 0; $total = 0;
while ($Auds_row = mysqli_fetch_array($Auds_result)) { $icnt++; 
	$bkgr = ($icnt % 2 == 0) ? " background-color: RGB(247, 247, 255);" : "";
?>
	<tr><td <? echo "style=\"text-align: right;$bkgr\""; ?>><?php echo $icnt; ?></td>
		<td <? echo "style=\"text-align: left;$bkgr\""; ?>><?php echo $Auds_row['specialty_name']; ?></td>
		<td <? echo "style=\"text-align: left;$bkgr\""; ?>><?php echo $Auds_row['eduprogram_name']; ?></td>
		<td <? echo "style=\"text-align: left;$bkgr\""; ?>><?php echo $Auds_row['head']; ?></td><?php
	for ($isem = 1; $isem <=8; $isem++) { ?>
		<td <? echo "style=\"text-align: center;$bkgr\""; ?>><?php 
		switch (TRUE) {
			case ($Auds_row['sem'.$isem.'_aud_hours'] % 18 == 0): $AudsPerWeek = $Auds_row['sem'.$isem.'_aud_hours'] / 18; break;
			case ($Auds_row['sem'.$isem.'_aud_hours'] % 17 == 0): $AudsPerWeek = $Auds_row['sem'.$isem.'_aud_hours'] / 17; break;
			case ($Auds_row['sem'.$isem.'_aud_hours'] % 16 == 0): $AudsPerWeek = $Auds_row['sem'.$isem.'_aud_hours'] / 16; break;
			default: 
				if ($isem == 8) $AudsPerWeek = round($Auds_row['sem'.$isem.'_aud_hours'] / 16, 0); else
												$AudsPerWeek = round($Auds_row['sem'.$isem.'_aud_hours'] / 18, 0);
				break;
		}
		echo ($AudsPerWeek > 0) ? $AudsPerWeek : ""; ?></td><?php 
	} ?>
</tr>
<?php 
} ?>
</tbody></table>
    	