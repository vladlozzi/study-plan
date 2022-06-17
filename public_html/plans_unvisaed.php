<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль plans_unvisaed.php.php</p>"; require "footer.php"; exit(); }
// Перелік випускних кафедр, які не завершили перевірку РНП
$UnvisaedQuery = "SELECT a.nazva_kaf, 
										SUM(IF(b.proxy_signature = '', 1, 0)) AS proxy_sign_none,
										SUM(IF(b.depart_head_visa = '', 1, 0)) AS deph_visa_none
									FROM catalogDepartment a, catalogWorkEduPlan b 
									WHERE a.id=b.depart_id AND LEFT(b.actualize_year, 4) <> '0000' 
									GROUP BY b.depart_id ORDER BY a.nazva_kaf";
?><p style="font-size: 133%; text-align: center;">
Випускні кафедри, які не завершили перевірку РНП<br>станом на <?php echo date("d.m.Y H:i"); ?></p>
<table style="margin: auto; width: 50%;">
	<tr><th rowspan=2>№</th><th rowspan=2>Кафедра</th><th colspan=2>Кількість РНП,<br>не підписаних</th></tr>
	<tr><th>уповно-<br>важеним</th><th>завідувачем<br>кафедри</th></tr><?php 
$query_result = mysqli_query($conn, $UnvisaedQuery) or 
		die("Помилка сервера при запиті<br>".$UnvisaedQuery." : ".mysqli_error($conn));
$icnt = 0; $unsigned_by_proxy_count = 0; $unvisaed_by_deph_count = 0;
while ($query_row = mysqli_fetch_array($query_result)) {
	$unsigned_by_proxy_count += $query_row['proxy_sign_none'];
	$unvisaed_by_deph_count += $query_row['deph_visa_none'];
	if (($query_row['proxy_sign_none'] > 0) or ($query_row['deph_visa_none'] > 0)) { $icnt++; ?>
	<tr><td><?php echo $icnt; ?> </td>
			<td style="text-align: left;"><?php echo $query_row['nazva_kaf']; ?></td>
			<td><?php echo ($query_row['proxy_sign_none'] > 0) ? $query_row['proxy_sign_none'] : "--"; ?></td>
			<td><?php echo ($query_row['deph_visa_none'] > 0) ? $query_row['deph_visa_none'] : "--"; ?></td>
	</tr><?php
	}
} ?>
<tr><th colspan=2 style="font-size: 110%; text-align: right;">Загальна кількість непідписаних РНП </th>
		<th style="font-size: 110%;"><?php echo $unsigned_by_proxy_count; ?></th>
		<th style="font-size: 110%;"><?php echo $unvisaed_by_deph_count; ?></th></tr>
</table>
