<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
	                              "Помилка входу в модуль practiques_certification.php</p>"; 
									 require "footer.php"; exit(); }
$AllDepartsQuery = "SELECT id, replace(nazva_kaf,\"Кафедра \",\"\") AS nazva_kaf 
					FROM catalogDepartment ORDER BY nazva_kaf"; // echo "<p>".$AllDepartsQuery."</p>";
// Обробка розділу "Практична підготовка"
if (isset($_POST['sbtAddPractique'])) {
	$AddPractQuery = "INSERT INTO plan_work_practicals VALUES(\"\",".$query_row['id'].str_repeat(",\"\"",8).")";
//	echo $AddNormSubjQuery;
	$query2_result = mysqli_query($conn, $AddPractQuery) or 
					die("<br>Помилка сервера при запиті<br>".$AddPractQuery." : ".mysqli_error($conn));
}
$IdsPractQuery = "SELECT id FROM plan_work_practicals 
						WHERE plan_id = \"".$query_row['id']."\" ORDER BY id";

$query3_result = mysqli_query($conn, $IdsPractQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsPractQuery." : ".mysqli_error($conn));
while ($query3_row = mysqli_fetch_array($query3_result))
	if (isset($_POST['sbtSavePractique'.$query3_row['id']])) {
		$SavePractiqueQuery = "UPDATE plan_work_practicals SET
												practicals_name = \"".$_POST['tbxPract'.$query3_row['id'].'Name']."\",
												practicals_code = \"".$_POST['tbxPract'.$query3_row['id'].'Code']."\",
												depart_id = \"".$_POST['DepPract'.$query3_row['id']]."\",
												practicals_sem = \"".$_POST['tbxPract'.$query3_row['id'].'Sem']."\",
												practicals_credits = \"".$_POST['tbxPract'.$query3_row['id'].'Credits']."\",
												practicals_comment = \"".$_POST['tbxPract'.$query3_row['id'].'Comm']."\"
									WHERE id = \"".$query3_row['id']."\"";
		$query4_result = mysqli_query($conn, $SavePractiqueQuery) or 
					die("<br>Помилка сервера при запиті<br>".$SavePractiqueQuery." : ".mysqli_error($conn));
	}

if (isset($_POST['sbtDeletePractique'])) {
	$query5_result = mysqli_query($conn, $IdsPractQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsPractQuery." : ".mysqli_error($conn));
	while ($query5_row = mysqli_fetch_array($query5_result))
		if (isset($_POST['chkPract'.$query5_row['id']])) {
			$DeletePractiquesQuery = "DELETE FROM plan_work_practicals WHERE id = \"".$query5_row['id']."\"";
			$query6_result = mysqli_query($conn, $DeletePractiquesQuery) or 
					die("<br>Помилка сервера при запиті<br>".$DeletePractiquesQuery." : ".mysqli_error($conn));
		}
}
// Обробка розділу "Атестація"
if (isset($_POST['sbtAddCertif'])) {
	$AddCertifQuery = "INSERT INTO plan_work_certification VALUES(\"\",".$query_row['id'].str_repeat(",\"\"",8).")";
//	echo $AddNormSubjQuery;
	$query2_result = mysqli_query($conn, $AddCertifQuery) or 
					die("<br>Помилка сервера при запиті<br>".$AddCertifQuery." : ".mysqli_error($conn));
}
$IdsCertifQuery = "SELECT id FROM plan_work_certification 
						WHERE plan_id = \"".$query_row['id']."\" ORDER BY id";

$query3_result = mysqli_query($conn, $IdsCertifQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsCertifQuery." : ".mysqli_error($conn));
while ($query3_row = mysqli_fetch_array($query3_result))
	if (isset($_POST['sbtSaveCertif'.$query3_row['id']])) {
		$SaveCertifQuery = "UPDATE plan_work_certification SET
												certif_name = \"".$_POST['tbxCertif'.$query3_row['id'].'Name']."\",
												certif_code = \"".$_POST['tbxCertif'.$query3_row['id'].'Code']."\",
												depart_id = \"".$_POST['DepCertif'.$query3_row['id']]."\",
												certif_sem = \"".$_POST['tbxCertif'.$query3_row['id'].'Sem']."\",
												certif_credits = \"".$_POST['tbxCertif'.$query3_row['id'].'Credits']."\",
												certif_comment = \"".$_POST['tbxCertif'.$query3_row['id'].'Comm']."\"
									WHERE id = \"".$query3_row['id']."\"";
		$query4_result = mysqli_query($conn, $SaveCertifQuery) or 
					die("<br>Помилка сервера при запиті<br>".$SaveCertifQuery." : ".mysqli_error($conn));
	}

if (isset($_POST['sbtDeleteCertif'])) {
	$query5_result = mysqli_query($conn, $IdsCertifQuery) or 
					die("<br>Помилка сервера при запиті<br>".$IdsCertifQuery." : ".mysqli_error($conn));
	while ($query5_row = mysqli_fetch_array($query5_result))
		if (isset($_POST['chkCertif'.$query5_row['id']])) {
			$DeleteCertifQuery = "DELETE FROM plan_work_certification WHERE id = \"".$query5_row['id']."\"";
			$query6_result = mysqli_query($conn, $DeleteCertifQuery) or 
					die("<br>Помилка сервера при запиті<br>".$DeleteCertifQuery." : ".mysqli_error($conn));
		}
}

?>
<p style="text-align: center; text-transform: uppercase;
				font-size: 125%; font-weight: bold; margin-bottom: 0.2em;">
		3. Практична підготовка</p>
<table style="margin-left: 15%; margin-right: 15%; width: 70%;">
	<tr>
		<th>№</th><th>Назва практики</th><th>Код<br>практики</th>
		<th>Кафедра, яка<br>забезпечує</th><th>Семестр</th>
		<th>Кількість<br>кредитів<br>ЄКТС</th><th>Примітки</th>
	</tr>
<?php
$PracticalsQuery = "SELECT * FROM plan_work_practicals
										WHERE plan_id = \"".$query_row['id']."\"";

$query10_result = mysqli_query($conn, $PracticalsQuery) or 
					die("<br>Помилка сервера при запиті<br>".$PracticalsQuery." : ".mysqli_error($conn));
while ($query10_row = mysqli_fetch_array($query10_result)) {
?>
	<tr>
		<td style="text-align: right;"><?php echo $query10_row['id']."&nbsp;&nbsp;";
		$_POST['chkPract'.$query10_row['id']] = 
			isset($_POST['chkPract'.$query10_row['id']]) ? $_POST['chkPract'.$query10_row['id']] : "";
		echo paramCheker('chkPract'.$query10_row['id'], $_POST['chkPract'.$query10_row['id']], "", ""); ?></td>
		<td style="text-align: left;">
			<?php if (isset($_POST['sbtEditPractique']) and !empty($_POST['chkPract'.$query10_row['id']])) { ?>
			<input type="textbox" value="<?php echo $query10_row['practicals_name']; ?>" 
				name="tbxPract<?php echo $query10_row['id']; ?>Name" style="width: 300px;">
			<?php
					} else echo "&nbsp; ".$query10_row['practicals_name']; ?>
		</td>
		<td><?php if (isset($_POST['sbtEditPractique']) and !empty($_POST['chkPract'.$query10_row['id']])) { ?>
			<input type="textbox" value="<?php echo $query10_row['practicals_code']; ?>" 
				name="tbxPract<?php echo $query10_row['id']; ?>Code" style="width: 25px;" pattern="{НП|ВП|ПП}">
			<?php
					} else echo $query10_row['practicals_code']; ?></td>
		<td><?php if (isset($_POST['sbtEditPractique']) and !empty($_POST['chkPract'.$query10_row['id']])) 
						echo selectCommonSelect("", "DepPract".$query10_row['id'], $conn, $AllDepartsQuery, "id", 
									$query10_row['depart_id'], "nazva_kaf", "");
					 else echo DepartCodeById($query10_row['depart_id']); ?></td>
		<td><?php if (isset($_POST['sbtEditPractique']) and !empty($_POST['chkPract'.$query10_row['id']])) { ?>
			<input type="textbox" value="<?php echo $query10_row['practicals_sem']; ?>" 
				name="tbxPract<?php echo $query10_row['id']; ?>Sem" style="width: 25px;">
			<?php
					} else echo ($query10_row['practicals_sem'] > 0) ? $query10_row['practicals_sem'] : ""; ?></td>
		<td><?php if (isset($_POST['sbtEditPractique']) and !empty($_POST['chkPract'.$query10_row['id']])) { ?>
			<input type="textbox" value="<?php echo $query10_row['practicals_credits']; ?>" 
				name="tbxPract<?php echo $query10_row['id']; ?>Credits" style="width: 25px;">
			<?php
					} else echo ($query10_row['practicals_credits'] > 0) ? $query10_row['practicals_credits'] : ""; ?></td>
		<td style="text-align: left;">
			<?php if (isset($_POST['sbtEditPractique']) and !empty($_POST['chkPract'.$query10_row['id']])) { ?>
			<input type="textbox" value="<?php echo $query10_row['practicals_comment']; ?>" 
				name="tbxPract<?php echo $query10_row['id']; ?>Comm" style="width: 200px;">
			<?php
					} else echo "&nbsp; ",$query10_row['practicals_comment']; ?></td>
	</tr>

<?php
	if (isset($_POST['sbtEditPractique']) and !empty($_POST['chkPract'.$query10_row['id']])) { ?>
		<tr><td colspan=7><input style="font-weight: bold; color: blue;" type="submit" 
				name="sbtSavePractique<?php echo $query10_row['id']; ?>" value="Зберегти практику" ></td></tr>
<?php
	}
}
if (($mode == "EDIT") and (empty($_POST['chkSubjSums'])) 
		 and ($_SESSION['chkProxySign'] == 0)) {
?>
	<tr><td colspan=7 style="text-align: right;">
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtAddPractique" 
				value="Додати практику"><? echo str_repeat(" &nbsp;",10);?>
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtEditPractique" 
				value="Редагувати позначену"><? echo str_repeat(" &nbsp;",25);?>
			<input style="font-weight: bold; color: red;" type="submit" name="sbtDeletePractique" 
				value="Видалити позначені">
		</td></tr>
<?php	} ?>		
</table>

<p style="text-align: center; font-size: 125%; text-transform: uppercase; 
				font-weight: bold; margin-bottom: 0.2em;">
		4. Атестація</p>
<table style="margin-left: 15%; margin-right: 15%; width: 70%;">
	<tr>
		<th>№</th><th>Форма атестації</th><th>Код<br>атестації</th>
		<th>Кафедра, яка<br>забезпечує</th><th>Семестр</th>
		<th>Кількість<br>кредитів<br>ЄКТС</th><th>Примітки</th>
	</tr>
<?php
$CertifQuery = "SELECT * FROM plan_work_certification
										WHERE plan_id = \"".$query_row['id']."\"";

$query10_result = mysqli_query($conn, $CertifQuery) or 
					die("<br>Помилка сервера при запиті<br>".$CertifQuery." : ".mysqli_error($conn));
while ($query10_row = mysqli_fetch_array($query10_result)) {
?>
	<tr>
		<td style="text-align: right;"><?php echo $query10_row['id']."&nbsp;&nbsp;";
		$_POST['chkCertif'.$query10_row['id']] = 
			isset($_POST['chkCertif'.$query10_row['id']]) ? $_POST['chkCertif'.$query10_row['id']] : "";
		echo paramCheker('chkCertif'.$query10_row['id'], $_POST['chkCertif'.$query10_row['id']], "", ""); ?></td>
		<td style="text-align: left;">
			<?php if (isset($_POST['sbtEditCertif']) and !empty($_POST['chkCertif'.$query10_row['id']])) { ?>
			<input type="textbox" value="<?php echo $query10_row['certif_name']; ?>" 
				name="tbxCertif<?php echo $query10_row['id']; ?>Name" style="width: 300px;">
			<?php
					} else echo "&nbsp; ".$query10_row['certif_name']; ?>
		</td>
		<td><?php if (isset($_POST['sbtEditCertif']) and !empty($_POST['chkCertif'.$query10_row['id']])) { ?>
			<input type="textbox" value="<?php echo $query10_row['certif_code']; ?>" 
				name="tbxCertif<?php echo $query10_row['id']; ?>Code" style="width: 25px;" pattern="{ДЕ|БР|МР}">
			<?php
					} else echo $query10_row['certif_code']; ?></td>
		<td><?php if (isset($_POST['sbtEditCertif']) and !empty($_POST['chkCertif'.$query10_row['id']])) 
						echo selectCommonSelect("", "DepCertif".$query10_row['id'], $conn, $AllDepartsQuery, "id", 
									$query10_row['depart_id'], "nazva_kaf", "");
					 else echo DepartCodeById($query10_row['depart_id']); ?></td>
		<td><?php if (isset($_POST['sbtEditCertif']) and !empty($_POST['chkCertif'.$query10_row['id']])) { ?>
			<input type="textbox" value="<?php echo $query10_row['certif_sem']; ?>" 
				name="tbxCertif<?php echo $query10_row['id']; ?>Sem" style="width: 25px;">
			<?php
					} else echo ($query10_row['certif_sem'] > 0) ? $query10_row['certif_sem'] : ""; ?></td>
		<td><?php if (isset($_POST['sbtEditCertif']) and !empty($_POST['chkCertif'.$query10_row['id']])) { ?>
			<input type="textbox" value="<?php echo $query10_row['certif_credits']; ?>" 
				name="tbxCertif<?php echo $query10_row['id']; ?>Credits" style="width: 25px;">
			<?php
					} else echo ($query10_row['certif_credits'] > 0) ? $query10_row['certif_credits'] : ""; ?></td>
		<td style="text-align: left;">
			<?php if (isset($_POST['sbtEditCertif']) and !empty($_POST['chkCertif'.$query10_row['id']])) { ?>
			<input type="textbox" value="<?php echo $query10_row['certif_comment']; ?>" 
				name="tbxCertif<?php echo $query10_row['id']; ?>Comm" style="width: 200px;">
			<?php
					} else echo "&nbsp; ",$query10_row['certif_comment']; ?></td>
	</tr>

<?php
	if (isset($_POST['sbtEditCertif']) and !empty($_POST['chkCertif'.$query10_row['id']])) { ?>
		<tr><td colspan=7><input style="font-weight: bold; color: blue;" type="submit" 
				name="sbtSaveCertif<?php echo $query10_row['id']; ?>" value="Зберегти атестацію" ></td></tr>
<?php
	}
}
if (($mode == "EDIT") and (empty($_POST['chkSubjSums'])) 
		 and ($_SESSION['chkProxySign'] == 0)) {
?>
	<tr><td colspan=7 style="text-align: right;">
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtAddCertif" 
				value="Додати атестацію"><? echo str_repeat(" &nbsp;",10);?>
			<input style="font-weight: bold; color: blue;" type="submit" name="sbtEditCertif" 
				value="Редагувати позначену"><? echo str_repeat(" &nbsp;",25);?>
			<input style="font-weight: bold; color: red;" type="submit" name="sbtDeleteCertif" 
				value="Видалити позначені">
		</td></tr>
<?php	} ?>		
</table>
