<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль functions.php</p>"; require "footer.php"; exit(); }

function SubjectById($id) { // назва дисципліни за id
	global $conn; // echo $id." - ep ";
	$Query = "SELECT naz_dus FROM catalogSubject WHERE id = $id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=66>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); 
	return $query_row['naz_dus'];
}

function SubjectDepartCodeById($id) { // назва дисципліни і абревіатура кафедри за id
	global $conn; // echo $id." - ep ";
	$Query = "SELECT a.naz_dus, b.shufr_kaf 
					FROM catalogSubject a, catalogDepartment b
					WHERE a.id = $id AND a.shufr_kaf = b.id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=66>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); 
	return $query_row['naz_dus']." &mdash; каф.&nbsp;".$query_row['shufr_kaf'];
}

function SubjectDepartCodeSubjId($id) { // назва дисципліни і абревіатура кафедри за id
	global $conn; // echo $id." - ep ";
	$Query = "SELECT a.naz_dus, b.shufr_kaf, a.id
					FROM catalogSubject a, catalogDepartment b
					WHERE a.id = $id AND a.shufr_kaf = b.id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=66>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); 
	return $query_row['naz_dus']." &mdash; каф.&nbsp;".$query_row['shufr_kaf']." (ID:".$query_row['id'].")";
}

function DepartCodeById($id) { // абревіатура кафедри за id
	global $conn; // echo $id." - ep ";
	$Query = "SELECT shufr_kaf FROM catalogDepartment WHERE id = $id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=66>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); 
	return $query_row['shufr_kaf'];
}

function DepartGroupById($id) { // група кафедри (1 - випускна, 2 - фундаментальна, 3 - гуманітарна) за id
	global $conn; // echo $id." - ep ";
	$Query = "SELECT depart_group_id FROM catalogDepartment WHERE id = $id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=66>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); 
	return $query_row['depart_group_id'];
}

function EduProgramById($id) { // код спеціалізації за id
	global $conn; // echo $id." - ep ";
	$Query = "SELECT eduprogram_code FROM catalogEduProgram WHERE id=$id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); // echo $query_row['eduprogram_code'];
	return $query_row['eduprogram_code'];
}

function DegreeById($id) { // код ступеню за id
	global $conn; // echo $id." - dg ";
	$Query = "SELECT degree_name FROM catalogEduDegree WHERE id=$id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); // echo $query_row['degree_name'];
	return mb_strtoupper(mb_substr($query_row['degree_name'],0,1));
}

function DegreeNameById($id) { // назва ступеню за id
	global $conn; // echo $id." - dg ";
	$Query = "SELECT degree_name FROM catalogEduDegree WHERE id=$id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); // echo $query_row['degree_name'];
	return $query_row['degree_name'];
}

function BaseDegreeById($id) { // код базового ступеню (ОКР) за id
	global $conn; // echo $id." - bd ";
	$Query = "SELECT degree_name FROM catalogEduDegree WHERE id=$id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); // echo $query_row['degree_name'];
	return (($query_row['degree_name'] == "ОКР молодший спеціаліст") ? "(к)" : "");
}

function BaseDegreeNameById($id) { // код базового ступеню (ОКР) за id
	global $conn; // echo $id." - bd ";
	$Query = "SELECT degree_name FROM catalogEduDegree WHERE id=$id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); // echo $query_row['degree_name'];
	return $query_row['degree_name'];
}

function EduFormById($id) { // код форми навчання за id
	global $conn; // echo $id." - ef ";
	$Query = "SELECT edu_form FROM catalogEduForm WHERE id=$id"; // echo $Query." ";
		$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result);  // echo $query_row['edu_form'];
	return mb_strtoupper(mb_substr($query_row['edu_form'],0,1));
}

function DepartmentById($id) { // назва кафедри за id
	global $conn; // echo $id." - ef ";
	$Query = "SELECT nazva_kaf FROM catalogDepartment WHERE id=$id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result);  // echo $query_row['edu_form'];
	return $query_row['nazva_kaf'];
}

function CycleNameById($id) { // назва циклу дисциплін за id
	global $conn; // echo $id." - ef ";
	$Query = "SELECT cycle_name FROM catalogSubjectCycle WHERE id=$id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result);  // echo $query_row['edu_form'];
	return $query_row['cycle_name'];
}

function DepartHeadNameByDepartId($depart_id) { // ПІБ завідувача кафедри
	global $conn; // echo $id." - ef ";
	$Query = "SELECT chief_fam_init FROM catalogDepartChief WHERE departmentId = $depart_id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result);  // echo $query_row['edu_form'];
	return $query_row['chief_fam_init'];
}

function DekanNameByFacultyId($faculty_id) { // ПІБ директора інституту
	global $conn; // echo $id." - ef ";
	$Query = "SELECT dekan_fam_init FROM catalogDekan WHERE fakul_id = $faculty_id and role = 4"; 
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result);  // echo $query_row['edu_form'];
	return $query_row['dekan_fam_init'];
}

function MethodistNameByLogin($mlogin) {
	global $conn; // echo $id." - ef ";
	$Query = "SELECT dekan_fam_init FROM catalogDekan WHERE md5(login) = \"$mlogin\" and role = 19"; 
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result);  // echo $query_row['edu_form'];
	return $query_row['dekan_fam_init'];
}

function BossNameByLogin($blogin) {
	global $conn; // echo $id." - ef ";
	$Query = "SELECT dekan_fam_init FROM catalogDekan WHERE md5(login) = \"$blogin\" and role = 20"; 
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result);  // echo $query_row['edu_form'];
	return $query_row['dekan_fam_init'];
}

function ViceRNameByLogin($blogin) {
	global $conn; // echo $id." - ef ";
	$Query = "SELECT dekan_fam_init FROM catalogDekan WHERE md5(login) = \"$blogin\" and role = 21"; 
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=11>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result);  // echo $query_row['edu_form'];
	return $query_row['dekan_fam_init'];
}

function GroupById($id) { // назва академгрупи за id
	global $conn; // echo $id." - ep ";
	$Query = "SELECT nazva_grupu FROM catalogGroup WHERE id = $id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=66>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); 
	return $query_row['nazva_grupu'];
}

function AcadYearByGroupId($id)	{ // курс за id академгрупи
	global $conn; // echo $id." - ep ";
	$Query = "SELECT num_kurs FROM catalogGroup WHERE id = $id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=66>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); 
	return $query_row['num_kurs'];
}

function FacultyIdByGroupId($id)	{ // факультет за id академгрупи
	global $conn; // echo $id." - ep ";
	$Query = "SELECT shufr_fak FROM catalogGroup WHERE id = $id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=66>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); 
	return $query_row['shufr_fak'];
}

function FacultyById($id)	{ // назва факультету за id 
	global $conn; // echo $id." - ep ";
	$Query = "SELECT fakultet_name FROM catalogFakultet WHERE id = $id"; // echo $Query." ";
	$query_result = mysqli_query($conn, $Query) or 
						die("<tr><td colspan=66>Помилка сервера при запиті<br>".$Query." : ".mysqli_error($conn)."</td></tr></table>");
	$query_row = mysqli_fetch_assoc($query_result); 
	return $query_row['fakultet_name'];
}

function ECTSRating($bals) {
	switch (TRUE) {
		case ($bals >= 90) && ($bals <= 100) : $rating = "A"; break;
		case ($bals >= 82) && ($bals <=  89) : $rating = "B"; break;
		case ($bals >= 75) && ($bals <=  81) : $rating = "C"; break;
		case ($bals >= 67) && ($bals <=  74) : $rating = "D"; break;
		case ($bals >= 60) && ($bals <=  66) : $rating = "Е"; break;
		case ($bals >= 35) && ($bals <=  59) : $rating = "FX"; break;
		case ($bals >=  1) && ($bals <=  34) : $rating = "F"; break;
		default: $rating = "";
	}
	return $rating;
}

function Grade($bals) {
	switch (TRUE) {
		case ($bals >= 90) && ($bals <= 100) : $grade = "Відмінно/Excellent"; break;
		case ($bals >= 75) && ($bals <=  89) : $grade = "Добре/Good"; break;
		case ($bals >= 60) && ($bals <=  74) : $grade = "Задовільно/Passed"; break;
		case ($bals >=  1) && ($bals <=  59) : 
			$grade = "<span style=\"color: red; \">Незадовільно/Fail</span>"; break;
		default: $grade = "";
	}
	return $grade;
}

function GradeNumeric($bals) {
	switch (TRUE) {
		case ($bals >= 90) && ($bals <= 100) : $grade = 5; break;
		case ($bals >= 75) && ($bals <=  89) : $grade = 4; break;
		case ($bals >= 60) && ($bals <=  74) : $grade = 3; break;
		case ($bals >=  1) && ($bals <=  59) : $grade = 2; break;
		default: $grade = "";
	}
	return $grade;
}

function GradeUkr($bals) {
	switch (TRUE) {
		case ($bals >= 90) && ($bals <= 100) : $grade = "Відм."; break;
		case ($bals >= 75) && ($bals <=  89) : $grade = "Добре"; break;
		case ($bals >= 60) && ($bals <=  74) : $grade = "Задов."; break;
		case ($bals >=  1) && ($bals <=  59) : 
			$grade = "<span style=\"color: red; \">Незад."; break;
		default: $grade = "";
	}
	return $grade;
}

function red($str) {
	return '<span style="color: red; font-weight: bold; font-size: 125%;">'.$str.'</span>';
}


?>