<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
	                              "Помилка входу в модуль schedule_edu_process.php</p>"; 
									 require "footer.php"; exit(); }
$AudWeeksCount = array(); for ($iSem = 1; $iSem < 9; $iSem++) $AudWeeksCount[$iSem] = 0;
$StudyWeeksCount = array(); for ($iSem = 1; $iSem < 9; $iSem++) $StudyWeeksCount[$iSem] = 0;
?>
<p style="text-align: center;  text-transform: uppercase;
				font-size: 125%; font-weight: bold; margin-bottom: 0px;">
		1. Графік освітнього процесу<?php
$_POST['chkSchedule'] = isset($_POST['chkSchedule']) ? $_POST['chkSchedule'] : "";
if ($stud == "YES") { ?> &nbsp; <span style="text-transform: none; font-weight: normal;"><?php 
	echo paramChekerInLine("chkSchedule", $_POST['chkSchedule'], 
												"Показати графік", "onchange=\"submit()\""); ?></span><?php
} else $_POST['chkSchedule'] = "on"; ?></p><?php 
if ($_POST['chkSchedule'] == "on") { ?>
<table style="margin-left: 0%; width: 100%;">
	<tr>
		<th rowspan=5>№</th><th rowspan=5>Курс</th>
		<th colspan=52>Форми освітнього процесу і канікули за тижнями</th>
	</tr>
	<tr>
	<?php for ($iw = 1; $iw <= 52; $iw++) { ?><th><?php echo $iw;?></th><?php } ?>
	</tr>
	<tr>
	<?php for ($im = 1; $im <= 12; $im++) { ?>
			<?php switch ($im) {
						case  1: $wc = 5; $mn="Вересень"; break; 
						case  2: $wc = 4; $mn="Жовтень"; break; 
						case  3: $wc = 4; $mn="Листопад"; break; 
						case  4: $wc = 5; $mn="Грудень"; break; 
						case  5: $wc = 4; $mn="Січень"; break; 
						case  6: $wc = 4; $mn="Лютий"; break; 
						case  7: $wc = 5; $mn="Березень"; break; 
						case  8: $wc = 4; $mn="Квітень"; break; 
						case  9: $wc = 4; $mn="Травень"; break;
						case 10: $wc = 5; $mn="Червень"; break; 
						case 11: $wc = 4; $mn="Липень"; break;
						case 12: $wc = 4; $mn="Серпень"; break;					
				} ?>
					<th colspan=<? echo $wc;?>><? echo $mn;?></th>
	<?php } ?>
	</tr>
	<tr>
	<?php for ($iw = 1; $iw <= 52; $iw++) { ?>
			<th>
		<?php switch ($iw) {
		/*09*/	case  1: $wb =  1; break; case  2: $wb =  8; break; 
					case  3: $wb = 15; break; case  4: $wb = 22; break; case  5: $wb = 29; break; 
		/*10*/	case  6: $wb =  6; break; case  7: $wb = 13; break; 
					case  8: $wb = 20; break; case  9: $wb = 27; break;
		/*11*/	case 10: $wb =  3; break; case 11: $wb = 10; break;
					case 12: $wb = 17; break; case 13: $wb = 24; break;
		/*12*/	case 14: $wb =  1; break; case 15: $wb =  8; break; case 16: $wb = 15; break;
					case 17: $wb = 22; break; case 18: $wb = 29; break;
		/*01*/	case 19: $wb =  5; break; case 20: $wb = 12; break;
					case 21: $wb = 19; break; case 22: $wb = 26; break;
		/*02*/	case 23: $wb =  2; break; case 24: $wb =  9; break;
					case 25: $wb = 16; break; case 26: $wb = 23; break;
		/*03*/	case 27: $wb =  2; break; case 28: $wb =  9; break; case 29: $wb = 16; break;
					case 30: $wb = 23; break; case 31: $wb = 30; break;
		/*04*/	case 32: $wb =  6; break; case 33: $wb = 13; break; 
					case 34:	$wb = 20; break; case 35: $wb = 27; break;
		/*05*/	case 36: $wb =  4; break; case 37: $wb = 11; break;
					case 38: $wb = 18; break; case 39: $wb = 25; break;
		/*06*/	case 40: $wb =  1; break; case 41: $wb =  8; break; case 42: $wb = 15; break;
					case 43: $wb = 22; break; case 44: $wb = 29; break;
		/*07*/	case 45: $wb =  6; break; case 46: $wb = 13; break; 
					case 47: $wb = 20; break; case 48: $wb = 27; break;
		/*08*/	case 49: $wb =  3; break; case 50: $wb = 10; break;
					case 51: $wb = 17; break; case 52: $wb = 24; break;
				}
				echo $wb;?></th>
<?php } ?>
	</tr>
	<tr>
	<?php for ($iw = 1; $iw <= 52; $iw++) { ?>
			<th>
		<?php switch ($iw) {
		/*09*/	case  1: $we =  7; break; case  2: $we = 14; break; 
					case  3: $we = 21; break; case  4: $we = 28; break; case  5: $we =  5; break; 
		/*10*/	case  6: $we = 12; break; case  7: $we = 19; break; 
					case  8: $we = 26; break; case  9: $we =  2; break;
		/*11*/	case 10: $we =  9; break; case 11: $we = 16; break;
					case 12: $we = 23; break; case 13: $we = 30; break;
		/*12*/	case 14: $we =  7; break; case 15: $we = 14; break; 
					case 16: $we = 21; break; case 17: $we = 28; break; case 18: $we =  4; break;
		/*01*/	case 19: $we = 11; break; case 20: $we = 18; break;
					case 21: $we = 25; break; case 22: $we =  1; break;
		/*02*/	case 23: $we =  8; break; case 24: $we = 15; break;
					case 25: $we = 22; break; case 26: $we =  1; break;
		/*03*/	case 27: $we =  8; break; case 28: $we = 15; break; case 29: $we = 22; break;
					case 30: $we = 29; break; case 31: $we =  5; break;
		/*04*/	case 32: $we = 12; break; case 33: $we = 19; break; 
					case 34:	$we = 26; break; case 35: $we =  3; break;
		/*05*/	case 36: $we = 10; break; case 37: $we = 17; break;
					case 38: $we = 24; break; case 39: $we = 31; break;
		/*06*/	case 40: $we =  7; break; case 41: $we = 14; break; case 42: $we = 21; break;
					case 43: $we = 28; break; case 44: $we =  5; break;
		/*07*/	case 45: $we = 12; break; case 46: $we = 19; break; 
					case 47: $we = 26; break; case 48: $we =  2; break;
		/*08*/	case 49: $we =  9; break; case 50: $we = 16; break;
					case 51: $we = 23; break; case 52: $we = 31; break;
				}
				echo $we;?></th>
<?php } ?>
	</tr>
<?php
$SchedQuery = "SELECT * FROM schedule_edu_process WHERE plan_id = ".$query_row['id']." ORDER BY study_year";
// echo $SchedQuery;
$query1_result = mysqli_query($conn, $SchedQuery) or 
						die("<br>Помилка сервера при запиті<br>".$SchedQuery." : ".mysqli_error($conn));
if (mysqli_num_rows($query1_result) == 0) {
	$degree = mb_substr(trim($query_row['reg_number']),-7,1,"UTF-8");
//	echo $degree." '".trim($query_row['reg_number'])."'";
	$study_years = $query_row['edu_term_years'] + ($query_row['edu_term_months'] > 0 ? 1 : 0);
	for ($study_year = 1; $study_year <= $study_years; $study_year++) { 
		$InsertStudyYearQuery = "INSERT INTO schedule_edu_process VALUES (NULL, ".$query_row['id'].", '".
												$degree.$study_year."', ".str_repeat(" '',",52)."'')";
		$query2_result = mysqli_query($conn, $InsertStudyYearQuery) or 
						die("<br>Помилка сервера при запиті<br>".$InsertStudyYearQuery." : ".mysqli_error($conn));
	}                              
} 
if (!empty($_POST['sbtSched'])) { // якщо натиснуто кнопку "Зберегти"
	$SchedIdQuery = "SELECT id, study_year FROM schedule_edu_process 
							WHERE plan_id = ".$query_row['id']." ORDER BY study_year";
	$query3_result = mysqli_query($conn, $SchedIdQuery) or 
						die("<br>Помилка сервера при запиті<br>".$SchedIdQuery." : ".mysqli_error($conn));
	while ($query3_row = mysqli_fetch_array($query3_result)) {
		$s_y = mb_substr($query3_row['study_year'],-1,1,"UTF-8");
		$set_expr = "";
		for ($iw = 1; $iw <= 9; $iw++) 
			$set_expr .= "kind_in_week0$iw = \"".$_POST['ksp_y'.$s_y.'w0'.$iw]."\", ";
		for ($iw = 10; $iw <= 51; $iw++) 
			$set_expr .= "kind_in_week$iw = \"".$_POST['ksp_y'.$s_y.'w'.$iw]."\", ";
      $iw = 52; $set_expr .= "kind_in_week$iw = \"".$_POST['ksp_y'.$s_y.'w'.$iw]."\"";
		$SchedUpdateQuery = "UPDATE schedule_edu_process SET $set_expr	WHERE id = ".$query3_row['id'];
//		echo $SchedUpdateQuery."<br>";
		mysqli_query($conn, $SchedUpdateQuery) or 
			die("<br>Помилка сервера при запиті<br>".$SchedUpdateQuery." : ".mysqli_error($conn));
	}
}
$query1_result = mysqli_query($conn, $SchedQuery) or 
						die("<br>Помилка сервера при запиті<br>".$SchedQuery." : ".mysqli_error($conn));
//	$study_years = $query_row['edu_term_years'] + ($query_row['edu_term_months'] > 0 ? 1 : 0);
while ($query1_row = mysqli_fetch_array($query1_result)) { ?>
	<tr><td><?php echo $query1_row['id']; ?></td>
			<td><?php echo $query1_row['study_year']; ?></td><?php
	$iSemStudy = strval(mb_substr($query1_row['study_year'],-1,1,"UTF-8")) * 2 - 1; 
	$iSemAud = $iSemStudy; $ExamSw = 0; $VacSw = 0;
	for ($iw = 1; $iw <= 52; $iw++) { ?><td style="margin-left: 0px; margin-right: 0px;"><?php 
		$week = ($iw > 9) ? $iw : "0".$iw;
		if ((EduFormById($query_row['edu_form_id']) == "Д") and 
			($query1_row['kind_in_week'.$week] <> "К") and ($query1_row['kind_in_week'.$week] <> "-"))
			$StudyWeeksCount[$iSemStudy]++;
		if ((EduFormById($query_row['edu_form_id']) == "Д") and 
			(($query1_row['kind_in_week'.$week] == "К") or ($query1_row['kind_in_week'.$week] == "-")))
      if ($VacSw == 0) { $iSemStudy++; $VacSw = 1; }
		if (empty($query1_row['kind_in_week'.$week])) $AudWeeksCount[$iSemAud]++;
		if ($query1_row['kind_in_week'.$week] == "Е") if ($ExamSw == 0) { $iSemAud++; $ExamSw = 1; }
		?>
		<input style="margin-left: 0px; margin-right: 0px; 
						font-size: 75%; width: 15px; border: 0px;" 
				type="text" pattern="(|МК|Е|НП|ВП|ПП|ДЕ|КР|К|ВЗ|-)"
				name="ksp_y<?echo mb_substr($query1_row['study_year'],-1,1,"UTF-8")."w".$week;?>"
            Value="<?php echo $query1_row['kind_in_week'.$week];?>" ></td>
<?php 
	};
?> </tr><?php
} // print_r($AudWeeksCount);
?>
</table>Позначення: &nbsp;<span style="border: 1px solid black;">&nbsp; &nbsp; &nbsp;</span>&nbsp; &mdash; 
<?php 
	if (EduFormById($query_row['edu_form_id']) == "Д") { 
?>аудиторні заняття; МК &mdash; міжсесійний контроль; Е &mdash; екзаменаційна сесія<?php } 
	else { ?>самостійна робота; Е &mdash; екзаменаційно-лабораторна сесія<?php } ?>; 
НП &mdash; навчальна практика; ВП &mdash; виробнича практика або стажування; ПП &mdash; переддипломна практика;<br>
ДЕ &mdash; державний екзамен; КР &mdash; кваліфікаційна робота; 
К &mdash; канікули; ВЗ &mdash; військові збори; - (дефіс) &mdash; відсутність освітнього процесу
<?php $_POST['chkSubjSums'] = isset($_POST['chkSubjSums']) ? $_POST['chkSubjSums'] : "";
	if (($mode == "EDIT") and ($_SESSION['chkProxySign'] == 0) and empty($_POST['chkSubjSums'])) { ?>
<p style="text-align: right; margin-top: 0px; margin-bottom: 0.5em;">
	<input style="font-weight: bold; color: green;" type="submit" name="sbtSched" 
			value="Зберегти графік після редагування">
</p><?php
	}
} ?>