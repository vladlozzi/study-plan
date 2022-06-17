<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль diploma_suppl.php</p>"; 
                            require "footer.php"; exit(); }
$GroupsForDepart_query = "SELECT a.id, a.nazva_grupu 
													FROM catalogGroup a, catalogWorkEduPlan b
													WHERE b.depart_id = ".$_POST['deptosel']." AND 
														(a.num_kurs = \"М2\")
														AND a.plan_id = b.id
													ORDER BY a.nazva_grupu";

$_POST['grouptosel'] = (isset($_POST['grouptosel'])) ? $_POST['grouptosel'] : "";
$_POST['studtosel'] = (isset($_POST['studtosel'])) ? $_POST['studtosel'] : "";
echo selectCommonSelectAutoSubmit
	("Виберіть академгрупу: ", "grouptosel", $conn, $GroupsForDepart_query, "id", 
		$_POST['grouptosel'], "nazva_grupu", "")." &nbsp; &nbsp; &nbsp; ";
if (!empty($_POST['grouptosel'])) {
	$StudentsInGroup_query =  "SELECT id, 
															CONCAT(student_name, ' ', surname, ' ', pobatkovi) AS student_fullname
														FROM catalogStudent 
														WHERE group_link = ".$_POST['grouptosel']." 
														ORDER BY student_fullname";
	echo selectCommonSelectAutoSubmit
		("Виберіть студента: ", "studtosel", $conn, $StudentsInGroup_query, "id", 
			$_POST['studtosel'], "student_fullname", "");
	if (!empty($_POST['studtosel'])) {
		// Чи є дисципліни для студента у базі додатків
		$SubjectsForStudentInDSTable_query =  "SELECT id FROM diploma_supplement 
																					WHERE student_id = ".$_POST['studtosel'];
		$SubjectsForStudentInDSTable_result = mysqli_query($conn, $SubjectsForStudentInDSTable_query) or 
			die("Помилка сервера при запиті<br>". $SubjectsForStudentInDSTable_query." : ".mysqli_error($conn));
		$_POST['sbtSaveScores'] = isset($_POST['sbtSaveScores']) ? $_POST['sbtSaveScores'] : "";
		if (!empty($_POST['sbtSaveScores'])) {
			while ($SubjectsForStudentInDSTable_row = mysqli_fetch_array($SubjectsForStudentInDSTable_result)) {
				$_POST['bals'.$SubjectsForStudentInDSTable_row['id']] =
					isset($_POST['bals'.$SubjectsForStudentInDSTable_row['id']]) ?
						$_POST['bals'.$SubjectsForStudentInDSTable_row['id']] : "";
				$UpdateSubjectInDS_query = "UPDATE diploma_supplement 
																		SET bals = ".$_POST['bals'.$SubjectsForStudentInDSTable_row['id']]."
																		WHERE id = ".$SubjectsForStudentInDSTable_row['id'];
				$UpdateSubjectInDS_result = mysqli_query($conn, $UpdateSubjectInDS_query) or 
					die("Помилка сервера при запиті<br>". $UpdateSubjectInDS_query." : ".mysqli_error($conn));
			}
		}
		if (mysqli_num_rows($SubjectsForStudentInDSTable_result) == 0) { // немає - беремо дисципліни з РНП групи
			$SubjectsForStudentFromPlan_query = "SELECT a.*, b.actualize_year, d.naz_dus AS subject_name
																					FROM plan_work_subj_study a, catalogWorkEduPlan b, 
																							 catalogGroup c, catalogSubject d 
																					WHERE a.plan_id = c.plan_id AND b.id = c.plan_id 
																								AND a.subject_id = d.id
																								AND c.id = ".$_POST['grouptosel'];
			$SubjectsForStudentFromPlan_result = mysqli_query($conn, $SubjectsForStudentFromPlan_query) or 
				die("Помилка сервера при запиті<br>". $SubjectsForStudentFromPlan_query." : ".mysqli_error($conn));
//			echo "<br>".mysqli_num_rows($SubjectsForStudentFromPlan_result);
			while ($SubjectsForStudentFromPlan_row = mysqli_fetch_array($SubjectsForStudentFromPlan_result)) {
				$HoursPerCredit = (substr($SubjectsForStudentFromPlan_row['actualize_year'],0,4) < 2015) ? 36 : 30;
				$Hours = 0; $AYPaper = FALSE;
				for ($iSem = 1; $iSem <= 8; $iSem++) { $Hours += 
					$SubjectsForStudentFromPlan_row['sem'.$iSem.'_lectural_hours'] + 
					$SubjectsForStudentFromPlan_row['sem'.$iSem.'_laboratorials_hours'] + 
					$SubjectsForStudentFromPlan_row['sem'.$iSem.'_practicals_hours'] + 
					$SubjectsForStudentFromPlan_row['sem'.$iSem.'_individual_work_hours'];
					$AYPaper = $AYPaper || ($SubjectsForStudentFromPlan_row['sem'.$iSem.'_acad_year_paper'] != "");
				} $Credits = $Hours / $HoursPerCredit - (($AYPaper) ? 1 : 0);
				switch (TRUE) {
					case (strstr($SubjectsForStudentFromPlan_row['subject_name'],
													" практика")) : $TitleId = 3; break;
					case ($SubjectsForStudentFromPlan_row['subject_name'] == "Магістерська робота") :
						$TitleId = 4; break;
					default : $TitleId = 0; break;
				}
				$InsertSubjectIntoDS_query = "INSERT INTO diploma_supplement VALUES ('',
																				".$_POST['studtosel'].", ".$TitleId.", 
																				".$SubjectsForStudentFromPlan_row['subject_id'].",
																				".$Credits.",'')";
				$InsertSubjectIntoDS_result = mysqli_query($conn, $InsertSubjectIntoDS_query) or 
					die("Помилка сервера при запиті<br>". $InsertSubjectIntoDS_query." : ".mysqli_error($conn));
				if ($AYPaper) {
					$InsertSubjectIntoDS_query = "INSERT INTO diploma_supplement VALUES ('',
																				".$_POST['studtosel'].", 2, 
																				".$SubjectsForStudentFromPlan_row['subject_id'].",
																				1,'')";
					$InsertSubjectIntoDS_result = mysqli_query($conn, $InsertSubjectIntoDS_query) or 
						die("Помилка сервера при запиті<br>". $InsertSubjectIntoDS_query." : ".mysqli_error($conn));
				}
			}
		}
//				echo "<br>".$SubjectsForStudentFromPlan_row['subject_name'];
		$SubjectsForStudentInDS_query = "SELECT a.*, b.naz_dus AS subject_name, b.subject_name_eng,
																			c.subject_title
																			FROM (diploma_supplement a, catalogSubject b)
																			LEFT JOIN catalogSubjectTitle c ON c.id = a.subject_title_id 
																			WHERE a.subject_id = b.id
																								AND a.student_id = ".$_POST['studtosel']."
																			ORDER BY a.subject_title_id, subject_name";
		$SubjectsForStudentInDS_result = mysqli_query($conn, $SubjectsForStudentInDS_query) or 
				die("Помилка сервера при запиті<br>". $SubjectsForStudentInDS_query." : ".mysqli_error($conn));
	$_POST['chkEditMode'] = (isset($_POST['chkEditMode'])) ? $_POST['chkEditMode'] : "";
	if ($_SESSION['user_role'] == "ROLE_ZAVKAF")
		echo " &nbsp; &nbsp; ".
			paramChekerInline("chkEditMode", $_POST['chkEditMode'], "Редагування", "onchange=\"submit()\"");
?>
<table style="margin: auto; width: 70%;">
	<tr><th>№ /<br>No.</th><th>Назва елементу навчальної програми (дисципліни) / <br>Course unit title</th>
	<th>Кредити /<br>Credits</th><th>Бали /<br>Score</th>
	<th>Рейтинг ЄКТС /<br>ECTS rating points</th><th>Оцінка / Grade</th></tr><?php
		$icnt = 0; $CredSum = 0; $ScoreSum = 0; $GradeSum = 0; $SubjTitleIdPrev = 0;
		while ($SubjectsForStudentInDS_row = mysqli_fetch_array($SubjectsForStudentInDS_result)) { $icnt++; 
			if (!empty($SubjectsForStudentInDS_row['subject_title'])) { 
				if ($SubjectsForStudentInDS_row['subject_title_id'] != $SubjTitleIdPrev) { ?>
			  	<tr><td colspan=6><?php echo bold($SubjectsForStudentInDS_row['subject_title']); ?></td></tr><?php
				  $SubjTitleIdPrev = $SubjectsForStudentInDS_row['subject_title_id'];
				}
			} ?>
			<tr><td><?php echo $icnt; ?></td>
					<td	style="text-align: left;">
						<?php echo $SubjectsForStudentInDS_row['subject_name']." / ".
											$SubjectsForStudentInDS_row['subject_name_eng']; ?></td>
					<td><?php echo $SubjectsForStudentInDS_row['credits']; ?></td>
					<td><?php 
			if (!empty($_POST['chkEditMode'])) { ?>
							<input type="text" name="bals<?php echo $SubjectsForStudentInDS_row['id']; ?>" 
								value="<?php echo $SubjectsForStudentInDS_row['bals']; ?>" 
								style="width: 50px; text-align: center; font-size: 120%;" ><?php
			} else echo $SubjectsForStudentInDS_row['bals']; ?>
					</td>
					<td><?php echo ECTSRating($SubjectsForStudentInDS_row['bals']); ?></td>
					<td><?php echo Grade($SubjectsForStudentInDS_row['bals']); ?></td>
			</tr><?php $CredSum += $SubjectsForStudentInDS_row['credits'];
			$ScoreSum += $SubjectsForStudentInDS_row['bals'];
			$GradeSum += GradeNumeric($SubjectsForStudentInDS_row['bals']);
		} $ScoreAverage = round($ScoreSum / $icnt, 0); $GradeAverage = round($GradeSum / $icnt, 1); ?>
<tr><th colspan=2>Загалом / Total</th>
		<th><?php echo $CredSum; ?></th><th><?php echo $ScoreAverage; ?></th><th></th>
		<th><?php echo $GradeAverage; ?></th></tr>
</table><?php 
		if (!empty($_POST['chkEditMode'])) { ?>
<p style="text-align: center;"><input type="submit" name="sbtSaveScores" value="Зберегти" ></p>
<?php
		}
	}
} ?>