<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль work_edu_plans_filter.php</p>"; require "footer.php"; exit(); }
$_POST['deg'.$tn.'toshow'] = isset($_POST['deg'.$tn.'toshow']) ? $_POST['deg'.$tn.'toshow'] : "";
$_POST['bdg'.$tn.'toshow'] = isset($_POST['bdg'.$tn.'toshow']) ? $_POST['bdg'.$tn.'toshow'] : "";
$_POST['edf'.$tn.'toshow'] = isset($_POST['edf'.$tn.'toshow']) ? $_POST['edf'.$tn.'toshow'] : "";
$_POST['spc'.$tn.'toshow'] = isset($_POST['spc'.$tn.'toshow']) ? $_POST['spc'.$tn.'toshow'] : "";
$_POST['spz'.$tn.'toshow'] = isset($_POST['spz'.$tn.'toshow']) ? $_POST['spz'.$tn.'toshow'] : "";
$_POST['dep'.$tn.'toshow'] = isset($_POST['dep'.$tn.'toshow']) ? $_POST['dep'.$tn.'toshow'] : "";
$_POST['fac'.$tn.'toshow'] = isset($_POST['fac'.$tn.'toshow']) ? $_POST['fac'.$tn.'toshow'] : "";
$_POST['acy'.$tn.'toshow'] = isset($_POST['acy'.$tn.'toshow']) ? $_POST['acy'.$tn.'toshow'] : "";
$_POST['nap'.$tn.'toshow'] = isset($_POST['nap'.$tn.'toshow']) ? $_POST['nap'.$tn.'toshow'] : "";
$_POST['prs'.$tn.'toshow'] = isset($_POST['prs'.$tn.'toshow']) ? $_POST['prs'.$tn.'toshow'] : "";
$_POST['dhv'.$tn.'toshow'] = isset($_POST['dhv'.$tn.'toshow']) ? $_POST['dhv'.$tn.'toshow'] : "";
$_POST['dkv'.$tn.'toshow'] = isset($_POST['dkv'.$tn.'toshow']) ? $_POST['dkv'.$tn.'toshow'] : "";
$_POST['mev'.$tn.'toshow'] = isset($_POST['mev'.$tn.'toshow']) ? $_POST['mev'.$tn.'toshow'] : "";
$_POST['sdv'.$tn.'toshow'] = isset($_POST['sdv'.$tn.'toshow']) ? $_POST['sdv'.$tn.'toshow'] : "";
$_POST['sbtShowPlans'.$tn] = isset($_POST['sbtShowPlans'.$tn]) ? $_POST['sbtShowPlans'.$tn] : "";
$_POST['rstShowReset'.$tn] = isset($_POST['rstShowReset'.$tn]) ? $_POST['rstShowReset'.$tn] : "";
if (!empty($_POST['sbtShowPlans'.$tn])) {
	$FilterConditions[$itn] .= (!empty($_POST['deg'.$tn.'toshow'])) ? " AND a.edu_degree_id = ".$_POST['deg'.$tn.'toshow'] : "";
	$FilterConditions[$itn] .= (!empty($_POST['bdg'.$tn.'toshow'])) ? " AND a.base_edu_degree_id = ".$_POST['bdg'.$tn.'toshow'] : "";
	$FilterConditions[$itn] .= (!empty($_POST['spc'.$tn.'toshow'])) ? " AND a.specialty_id = ".$_POST['spc'.$tn.'toshow'] : "";
	$FilterConditions[$itn] .= (!empty($_POST['spz'.$tn.'toshow'])) ? " AND a.edu_program_id = ".$_POST['spz'.$tn.'toshow'] : "";
	$FilterConditions[$itn] .= (!empty($_POST['edf'.$tn.'toshow'])) ? " AND a.edu_form_id = ".$_POST['edf'.$tn.'toshow'] : "";
	$FilterConditions[$itn] .= (!empty($_POST['acy'.$tn.'toshow'])) ? " AND a.actualize_year = \"".$_POST['acy'.$tn.'toshow']."\"" : "";
	$FilterConditions[$itn] .= (!empty($_POST['nap'.$tn.'toshow'])) ? "" : " AND (a.sem_start_current > 0 AND a.sem_final_current > 0)";
	$FilterConditions[$itn] .= (!empty($_POST['fac'.$tn.'toshow'])) ? " AND a.faculty_id = ".$_POST['fac'.$tn.'toshow'] : "";
	$FilterConditions[$itn] .= (!empty($_POST['dep'.$tn.'toshow'])) ? " AND a.depart_id = ".$_POST['dep'.$tn.'toshow'] : "";
	$FilterConditions[$itn] .= (!empty($_POST['prs'.$tn.'toshow'])) ? " AND TRIM(a.proxy_signature) <> ''" : "";
	$FilterConditions[$itn] .= (!empty($_POST['dhv'.$tn.'toshow'])) ? " AND TRIM(a.depart_head_visa) <> ''" : "";
	$FilterConditions[$itn] .= (!empty($_POST['dkv'.$tn.'toshow'])) ? " AND TRIM(a.dekan_visa) <> ''" : "";
	$FilterConditions[$itn] .= (!empty($_POST['mev'.$tn.'toshow'])) ? " AND TRIM(a.methodist_visa) <> ''" : "";
	$FilterConditions[$itn] .= (!empty($_POST['sdv'.$tn.'toshow'])) ? " AND TRIM(a.study_depart_boss_visa) <> ''" : "";
} else $FilterConditions[$itn] .= " AND (a.sem_start_current > 0 AND a.sem_final_current > 0)";
if (!empty($_POST['rstShowReset'.$tn])) { $FilterConditions[$itn] = " AND (a.sem_start_current > 0 AND a.sem_final_current > 0)";
	$_POST['deg'.$tn.'toshow'] = ""; $_POST['bdg'.$tn.'toshow'] = ""; $_POST['edf'.$tn.'toshow'] = ""; 
	$_POST['spc'.$tn.'toshow'] = ""; $_POST['spz'.$tn.'toshow'] = ""; $_POST['dep'.$tn.'toshow'] = ""; 
	$_POST['fac'.$tn.'toshow'] = ""; $_POST['acy'.$tn.'toshow'] = "";	$_POST['nap'.$tn.'toshow'] = ""; 
	$_POST['prs'.$tn.'toshow'] = ""; $_POST['dhv'.$tn.'toshow'] = ""; $_POST['dkv'.$tn.'toshow'] = "";
	$_POST['mev'.$tn.'toshow'] = ""; $_POST['sdv'.$tn.'toshow'] = "";
	$_POST['sbtShowPlans'.$tn] = ""; $_POST['rstShowReset'.$tn] = "";
}
?>&nbsp; &nbsp; <span style="font-size: 120%; font-weight: bold;">
									Пошук робочих навчальних планів за показниками:</span><br><?php
echo selectCommonSelect
		("Ступінь / ОКР: ", "deg".$tn."toshow", $conn, $DegreesQuery, "id", $_POST['deg'.$tn.'toshow'], "degree_name", ""); ?>
&nbsp; &nbsp;<?php 
		echo selectCommonSelect
		(" на базі ступеню / ОКР: ", "bdg".$tn."toshow", $conn, $BaseDegreesQuery, "id", $_POST['bdg'.$tn.'toshow'], "degree_name", "")." &nbsp; &nbsp; ";
		echo selectCommonSelect
		(" Форма навчання: ", "edf".$tn."toshow", $conn, $EduFormsQuery, "id", $_POST['edf'.$tn.'toshow'], "edu_form", ""); ?>
<br><?php
		echo selectCommonSelectAutoSubmit
		("Спеціальність / напрям: ", "spc".$tn."toshow", $conn, $SpecialtiesQuery, "id", $_POST['spc'.$tn.'toshow'], "specialty_codename", "")."<br>";
		$EduProgramsQueries[$itn] = str_replace("ORDER ","WHERE specialty_id = \"".$_POST['spc'.$tn.'toshow']."\" ORDER ", $EduProgramsQuery); // echo $EduProgramsQuery;
		echo selectCommonSelect
		("Спеціалізація (освітня програма): ", "spz".$tn."toshow", $conn, $EduProgramsQueries[$itn], "id", $_POST['spz'.$tn.'toshow'], "eduprogram_codename", ""); ?>
&nbsp; &nbsp; Набирає чинності з: 
<input type="text" name="acy<?php echo $tn; ?>toshow" style="font-weight: bold; font-size: 125%; width: 120px;" 
				value="<?php echo $_POST['acy'.$tn.'toshow']; ?>" /> н.р.&nbsp; &nbsp;<?php 
echo paramChekerInLine("nap".$tn."toshow", $_POST['nap'.$tn.'toshow'], "У т.ч. нечинні РНП", ""); ?>
<br><?php
		echo selectCommonSelect
		("Інститут (факультет): ", "fac".$tn."toshow", $conn, $FacultiesQuery, "id", $_POST['fac'.$tn.'toshow'], "fakultet_name", "")." &nbsp; &nbsp; ";
		echo selectCommonSelect
		("Випускна кафедра: ", "dep".$tn."toshow", $conn, $DepartsQuery, "id", $_POST['dep'.$tn.'toshow'], "nazva_kaf", ""); ?>
<br><?php 
echo paramChekerInLine("prs".$tn."toshow", $_POST['prs'.$tn.'toshow'], "Підписані уповноваженими", ""); ?>
&nbsp; &nbsp; <?php
echo paramChekerInLine("dhv".$tn."toshow", $_POST['dhv'.$tn.'toshow'], "Підписані зав. кафедр", ""); ?>
&nbsp; &nbsp; <?php
echo paramChekerInLine("dkv".$tn."toshow", $_POST['dkv'.$tn.'toshow'], "Підписані директорами інститутів", ""); ?>
&nbsp; &nbsp; <?php
echo paramChekerInLine("mev".$tn."toshow", $_POST['mev'.$tn.'toshow'], "Підписані методистом НВ", ""); ?>
&nbsp; &nbsp; <?php
echo paramChekerInLine("sdv".$tn."toshow", $_POST['sdv'.$tn.'toshow'], "Підписані начальником НВ", ""); ?>
&nbsp; &nbsp; <input type="submit" name="sbtShowPlans<?php echo $tn; ?>" value="Знайти" 
										style="font-weight: bold; color: blue;" />
&nbsp; &nbsp; <input type="submit" name="rstShowReset<?php echo $tn; ?>" value="Скинути" 
										style="font-weight: bold; color: red;" />
