<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль subjects.php</p>"; require "footer.php"; exit(); }
// Перелік кафедр для вибору
$DepartsQuery = "SELECT id, nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf";
// Перелік дисциплін, закріплених за кафедрами
$SubjQuery = "SELECT a.id, b.shufr_kaf, a.shufr_kaf AS shufr_kaf_subj, a.naz_dus 
				FROM catalogSubject a, catalogDepartment b 
				WHERE a.shufr_kaf = b.id ORDER BY b.shufr_kaf, a.naz_dus";
?><br>
<table style="margin-left: 0%; width: 1300px;">
<thead>
	<tr style="width: 1300px;"><td colspan=5>
	<tr style="width: 1300px;"><th>Код</th>
		<th style="width: 110px;">Шифр кафедри</th><th>Повна назва дисципліни</th>
		<th></th><th></th></tr>
</thead>
<tbody style="width: 1300px;">
<?php
// Завантажити перелік дисциплін
	$query_result = mysqli_query($conn, $SubjQuery) or 
			die("Помилка сервера при запиті<br>".$SubjQuery." : ".mysqli_error($conn));
	$icnt = 0;
	while ($query_row = mysqli_fetch_array($query_result)) { 
		$_POST['cbxs'.$query_row['id']] = isset($_POST['cbxs'.$query_row['id']]) ? 
															$_POST['cbxs'.$query_row['id']] : "";
		$_POST['dels'.$query_row['id']] = isset($_POST['dels'.$query_row['id']]) ?
															$_POST['dels'.$query_row['id']] : "";
?>
	<tr style="width: 1300px;">	<td style="text-align: right; font-size: 150%;">
													<?php echo $query_row['id']; ?></td>
		<td style="text-align: left; width: 110px; font-size: 150%;">
				<?php echo $query_row['shufr_kaf']; ?></td>                        
		<td style="text-align: left; font-size: 150%;"><?php echo $query_row['naz_dus']; ?></td>                        
		<td></td>
		<td></td>
	</tr>
<?php 
		$icnt++;
	}
?>
</tbody>
<tfoot>
<?php
	if ($TrueAdmin) { ?>
<tfoot style="width: 1300px;">
		<tr style="width: 1300px;">
		<td colspan=4 style="text-align: right; width: 1201px;">Усього: <?php echo bold($icnt); ?></td>
		<td style="width: 90px;"></td></tr>
</tfoot>
<?php
	} ?>
</table>
