<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль papers.php</p>"; require "footer.php"; exit(); }
// Кількість курсових робіт (проектів) у РНП за спеціальностями
$_POST['radDegreeSelect'] = isset($_POST['radDegreeSelect']) ? $_POST['radDegreeSelect'] : "Bachelor";
?><p style="text-align: center; margin-top: 0.5em; margin-bottom: 0.1em; font-size: 133%;">
Розподіл курсових проектів (робіт) у базових РНП спеціальностей Переліку-2015</p>
<p style="text-align: center; margin-top: 0.5em; margin-bottom: 0.1em; font-size: 110%;">
Виберіть ступінь вищої освіти &nbsp; 
<input type="radio" name="radDegreeSelect" value="Bachelor" onclick="submit()" 
	<?php if ($_POST['radDegreeSelect'] == "Bachelor") echo "checked"; ?>> 
	Бакалавр (на базі середньої освіти)&nbsp; &nbsp; 
<input type="radio" name="radDegreeSelect" value="Master" onclick="submit()" 
	<?php if ($_POST['radDegreeSelect'] == "Master") echo "checked"; ?>> 
	Магістр &nbsp; &nbsp; 
<input type="radio" name="radDegreeSelect" value="Total" onclick="submit()" 
	<?php if ($_POST['radDegreeSelect'] == "Total") echo "checked"; ?>> 
	Бак.+Маг.
<?php
switch ($_POST['radDegreeSelect']) {
	case "Bachelor" : $DegreeCond = "c.edu_degree_id = 2 AND c.base_edu_degree_id = 9"; 
										$DegreeSelected = "бакалавра"; break;
	case "Master" : $DegreeCond = "c.edu_degree_id = 3"; $DegreeSelected = "магістра"; break;
	case "Total" : 
			$DegreeCond = "(c.edu_degree_id = 2 AND c.base_edu_degree_id = 9 OR c.edu_degree_id = 3)"; 
			$DegreeSelected = ""; break;
}
$Papers_query = "
	SELECT a.specialty_name, b.eduprogram_name, 
		CONCAT(b.eduprogram_code, ' (', f.chief_fam_init, ')') AS head,
		SUM(IF((d.sem1_acad_year_paper = ''),0,1)+
			IF((d.sem2_acad_year_paper = ''),0,1)+
			IF((d.sem3_acad_year_paper = ''),0,1)+
			IF((d.sem4_acad_year_paper = ''),0,1)+
			IF((d.sem5_acad_year_paper = ''),0,1)+
			IF((d.sem6_acad_year_paper = ''),0,1)+
			IF((d.sem7_acad_year_paper = ''),0,1)+
			IF((d.sem8_acad_year_paper = ''),0,1)) AS papers_count 
	FROM catalogSpecialty a, catalogEduProgram b,	catalogWorkEduPlan c, plan_work_subj_study d,
			catalogDepartment e, catalogDepartChief f
	WHERE 
		b.specialty_id = a.id AND a.list = 2015 AND 
		c.edu_program_id = b.id AND c.edu_form_id = 1 AND $DegreeCond 
		 AND d.plan_id = c.id AND c.depart_id = e.id AND c.depart_id = f.departmentId
	GROUP BY b.eduprogram_name ORDER BY papers_count DESC";
//	ORDER BY specialty_name, eduprogram_name";
?><br>
<table style="margin: auto; width: 75%;">
	<tr><th>№</th><th>Назва спеціальності</th>
		<th>Назва спеціалізації (осв. програми)</th>
		<th>Керівник</th>
		<th>Кількість&nbsp;КП&nbsp;(КР)<br>у РНП <?php echo $DegreeSelected; ?></th></tr>
<?php
$Papers_result = mysqli_query($conn, $Papers_query) or 
		die("Помилка сервера при запиті<br>".$Papers_query." : ".mysqli_error($conn));
$icnt = 0; $total = 0;
while ($Papers_row = mysqli_fetch_array($Papers_result)) { $icnt++; $total += $Papers_row['papers_count'];
	$bkgr = ($icnt % 2 == 0) ? " background-color: RGB(247, 247, 255);" : "";
?>
	<tr><td <? echo "style=\"text-align: right;$bkgr\""; ?>><?php echo $icnt; ?></td>
		<td <? echo "style=\"text-align: left;$bkgr\""; ?>><?php echo $Papers_row['specialty_name']; ?></td>
		<td <? echo "style=\"text-align: left;$bkgr\""; ?>><?php echo $Papers_row['eduprogram_name']; ?></td>
		<td <? echo "style=\"text-align: left;$bkgr\""; ?>><?php echo $Papers_row['head']; ?></td>
		<td <? echo "style=\"text-align: center;$bkgr\""; ?>><?php echo $Papers_row['papers_count']; ?></td></tr>
<?php 
} ?>
	<tr><th style="text-align: right;" colspan=4>Разом: </th><th><? echo $total; ?></th></tr>
</table>
    	