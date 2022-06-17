<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль main.php</p>"; require "footer.php"; exit(); }
require "tegs.php"; require "chekers.php"; require "functions.php"; // echo dirname(__FILE__);
// require_once dirname(__FILE__).'/classes/PHPExcel.php';

if (isset($_SESSION['user_role'])) { // echo $_SESSION['user_role'];
	switch ($_SESSION['user_role']) {
		case 'ROLE_ADMIN' : $current_role = "адміністратор(-ка)"; break;
		case 'ROLE_ZAVKAF' : $current_role = "завідувач(-ка) кафедри"; break;
		case 'ROLE_DEP_OPER' : $current_role = "уповноважена особа кафедри"; break;
		case 'ROLE_DEKAN' : $current_role = "директор(-ка) інституту"; break;
		case 'ROLE_DEKANAT' : $current_role = ""; break;
		case 'ROLE_TEACHER' : $current_role = "викладач(-ка)"; break;
		case 'ROLE_STUDENT' : $current_role = "студент(-ка)"; break;
		case 'ROLE_PRACTIQ_LAB_HEAD' : $current_role = "зав.лабораторії організації практики"; break;
		case 'ROLE_NMU' : $current_role = "працівник навчального відділу"; break;
		case 'ROLE_METHODIST' : $current_role = "методист навчального відділу"; break;
		case 'ROLE_METHOD_HEAD' : $current_role = "голова методичної ради"; break;
		case 'ROLE_STUDY_DEP_BOSS' : $current_role = "начальник навчального відділу"; break;
		case 'ROLE_VICERECTOR' : $current_role = "проректор"; break;
		case 'ROLE_RECTOR' : $current_role = "ректор"; break;
		case 'ROLE_PERSONNEL_MAN_BOSS': $current_role = "начальник відділу кадрів"; break;
		case 'ROLE_ACCOUNTANT': $current_role = "бухгалтер"; break;
		case 'ROLE_PSYCHO': $current_role = "психолог"; break;
		default : $current_role = "користувач"; break;
	}
	echo str_replace("Kiev","Kyiv",date("Y-m-d H:i:s (e P)")).
		". Ви увійшли як ".
		bold(mb_ereg_replace("проректор директор", "директор", $current_role." ".$_SESSION['user_fullname']));
?>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php
if ((isset($_SESSION['user_id'])) and ($_SESSION['user_role'] != "ROLE_ADMIN")) { ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="./utils/feedback.php" 
	style="color: Red; font-weight: bold; font-family: sans-serif; font-size: 140%;
					border: dotted Tomato 2px; border-bottom: none;" 
	target="_blank">Оцінка та пропозиції</a><?php
} ?>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
<a href="index.php?logout=1" 
	style="color: Tomato; font-weight: bold; font-family: sans-serif;">Вийти</a><br>
<p style="color: Blue; font-weight: bold; font-family: sans-serif; font-size: 80%;
	text-align: center; margin-top: 0.2em; margin-bottom: 0.2em;">
Для пошуку на сторінці натисніть Ctrl+F або скористайтеся меню браузера</p>
<form id="formDatas" method="post" target="_self">
<?php
	switch ($_SESSION['user_role'])	{
		case 'ROLE_ADMIN' : require "admin.php"; break;
		case 'ROLE_DEP_OPER' : require "depart_prepare_edu_plans.php"; break;
		case 'ROLE_STUDENT' : require "student.php"; break;
		case 'ROLE_TEACHER' : require "teacher_edu_plans.php"; break;
		case 'ROLE_ZAVKAF' : require "depart_head_edu_plans.php"; break;
		case 'ROLE_DEKAN' : require "dekan.php";  break;
		case 'ROLE_DEKANAT' : require "dekan.php";  break;
		case 'ROLE_PRACTIQ_LAB_HEAD' : require "vicerector.php"; break;
		case 'ROLE_METHODIST' : require "study_depart.php"; break;
		case 'ROLE_STUDY_DEP_BOSS' : require "study_depart.php"; break;
		case 'ROLE_METHOD_HEAD' : require "vicerector.php"; break;
		case 'ROLE_VICERECTOR' : require "vicerector.php"; break;
		case 'ROLE_RECTOR' : require "vicerector.php"; break;
		case 'ROLE_PERSONNEL_MAN_BOSS': require "person_man_boss.php"; break;
		case 'ROLE_PSYCHO' : require "vicerector.php"; break;
		default : echo "<br><h2>Для цього користувача жодних дій у РНП і довідниках не передбачено</h2><br>"; break;
	}
?>
</form>
<?php
} else {
	require "login.php";
}
?>
