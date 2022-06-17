<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                             "Помилка входу в модуль users_stats.php</p>"; require "footer.php"; exit(); } 
$_POST['radStatistics'] = isset($_POST['radStatistics']) ? $_POST['radStatistics'] : "Today";
$_POST['chkIPIgnoreStatistics'] = isset($_POST['chkIPIgnoreStatistics']) ? 
																	$_POST['chkIPIgnoreStatistics'] : ""; ?>
<p style="text-align: center; color: black; margin-top: 0.5em; margin-bottom: 0.1em;">
Активність користувачів станом на <?php echo date("d.m.Y H:i"); ?> : &nbsp; 
<input type="radio" name="radStatistics" value="Today" onclick="submit()" 
	<?php if ($_POST['radStatistics'] == "Today") echo "checked"; ?>>	за сьогодні &nbsp; &nbsp; 
<input type="radio" name="radStatistics" value="Yesterday" onclick="submit()" 
	<?php if ($_POST['radStatistics'] == "Yesterday") echo "checked"; ?>>	за вчора &nbsp; &nbsp; 
<input type="radio" name="radStatistics" value="Total" onclick="submit()" 
	<?php if ($_POST['radStatistics'] == "Total") echo "checked"; ?>>	за весь період &nbsp; &nbsp; 
<input type="radio" name="radStatistics" value="TotalAdmin" onclick="submit()" 
	<?php if ($_POST['radStatistics'] == "TotalAdmin") echo "checked"; ?>> за весь період (з адмінами)
 &nbsp; &nbsp; <?php echo	paramChekerInline("chkIPIgnoreStatistics", $_POST['chkIPIgnoreStatistics'], 
																						"Сховати IP", "onchange=\"submit()\""); ?></p>
<?php // if (!empty($_POST['radStatistics'])) require "users_stats.php";// Статистика роботи користувачів
$Admin2 = "";
Switch ($_POST['radStatistics']) {
	case 'Today' : $StatsDate = date("Y-m-d"); 
		$DateInitCond = "AND LEFT(a.dateIns, 10) = (@sdate := '".$StatsDate."') "; 
		$DateNextCond = "AND LEFT(a.dateIns, 10) = @sdate"; 
		$Admin = ""; break;
	case 'Yesterday' : $StatsDate = date("Y-m-d", time() - 3600*24);
		$DateInitCond = "AND LEFT(a.dateIns, 10) = (@sdate := '".$StatsDate."') ";
		$DateNextCond = "AND LEFT(a.dateIns, 10) = @sdate"; $Admin = ""; break;
	case 'Total' : $DateInitCond = ""; $DateNextCond = ""; 
		$Admin = "AND a.ipAddress NOT IN (SELECT ipAddress FROM catalogAdminIPAddress) 
							AND a.ipAddress NOT LIKE '$SuperAdminIPAddressPool'"; break;
	case 'TotalAdmin' : $DateInitCond = ""; $DateNextCond = ""; 
		$Admin2 = "OR a.userRole = 'ROLE_ADMIN' "; $Admin = ""; break;
} 
$ipAddress = (empty($_POST['chkIPIgnoreStatistics'])) ? ", a.ipAddress" : "";
$Stats_query = "
	(SELECT a.userId, a.userRole, CONCAT(c.Description, ' ', 
																			b.dekan_fam_init) AS fullname$ipAddress, count(*) AS cou, c.rank AS uRank
	 FROM `catalogs_log` a, `catalogDekan` b, `catalogRoles` c
	 WHERE a.userId = b.id AND b.role = c.id $DateInitCond AND 
	 (a.userRole = 'ROLE_DEKAN' OR a.userRole = 'ROLE_DEKANAT' OR a.userRole = 'ROLE_METHODIST' OR 
	  a.userRole = 'ROLE_STUDY_DEP_BOSS' OR a.userRole = 'ROLE_METHOD_HEAD' OR 
		a.userRole = 'ROLE_VICERECTOR' OR a.userRole = 'ROLE_RECTOR' $Admin2
	 ) $Admin GROUP BY a.userId$ipAddress
	) UNION
	(SELECT a.userId, a.userRole, CONCAT('уповноважений кафедри ', 
																			b.kaf_shufr) AS fullname$ipAddress, count(*) AS cou, c.rank AS uRank
	 FROM `catalogs_log` a, `catalogDepartOper` b, `catalogRoles` c 
	 WHERE a.userId = b.id AND b.role = c.id AND a.userRole = 'ROLE_DEP_OPER' $DateNextCond $Admin 
	 GROUP BY a.userId$ipAddress
	) UNION 
	(SELECT a.userId, a.userRole, CONCAT('завідувач кафедри ', 
																			b.chief_fam_init) AS fullname$ipAddress, count(*) AS cou, c.rank AS uRank
	 FROM `catalogs_log` a, `catalogDepartChief` b, `catalogRoles` c 
	 WHERE a.userId = b.id AND b.role = c.id AND a.userRole = 'ROLE_ZAVKAF' $DateNextCond $Admin 
	 GROUP BY a.userId$ipAddress
	) UNION 
	(SELECT a.userId, a.userRole, CONCAT('викладач ', b.teacher_surname, ' ', LEFT(b.teacher_name, 1), '.', 
																			LEFT(b.teacher_pobatkovi, 1), '.') AS fullname$ipAddress, count(*) AS cou, c.rank AS uRank
	 FROM `catalogs_log` a, `catalogTeacher` b, `catalogRoles` c 
	 WHERE a.userId = b.id AND b.role = c.id AND a.userRole = 'ROLE_TEACHER' $DateNextCond $Admin 
			/* AND a.ipAddress NOT LIKE @myip1 AND NOT a.ipAddress = @myip2 */
	 GROUP BY a.userId$ipAddress
	) UNION 
	(SELECT a.userId, a.userRole, CONCAT('студент ', b.student_name, ' ', LEFT(b.surname, 1), '.', 
																			LEFT(b.pobatkovi, 1), '.') AS fullname$ipAddress, count(*) AS cou, c.rank AS uRank
	 FROM `catalogs_log` a, `catalogStudent` b, `catalogRoles` c 
	 WHERE a.userId = b.id AND b.role = c.id AND a.userRole = 'ROLE_STUDENT' $DateNextCond $Admin 
			/* AND a.ipAddress NOT LIKE @myip1 AND NOT a.ipAddress = @myip2 */
	 GROUP BY a.userId$ipAddress
	) ORDER BY uRank ASC, cou DESC"; ?>
<table style="margin: auto; width: 50%;">
	<tr><th>№</th><th>Користувач</th><th>Кількість<br>операцій</th><th>IP-адреса</th></tr><?php 
$Stats_result = mysqli_query($conn, $Stats_query) or 
		die("Помилка сервера при запиті<br>".$Stats_query." : ".mysqli_error($conn)); $icnt = 0; $opers = 0;
while ($Stats_row = mysqli_fetch_array($Stats_result)) { $icnt++; $opers += $Stats_row['cou']; ?>
	<tr><td><?php echo $icnt; ?> </td>
			<td style="text-align: left;"><?php 
	echo mb_ereg_replace("проректор директор", "директор", $Stats_row['fullname']); ?></td>
			<td><?php echo $Stats_row['cou']; ?></td>
			<td><?php 
	if (empty($_POST['chkIPIgnoreStatistics'])) {
		echo $Stats_row['ipAddress']; 
		if ($Stats_row['userRole'] != 'ROLE_ADMIN') {
			$AdminIPAddress_query = "
				SELECT ipAddress FROM catalogAdminIPAddress 
				WHERE LOCATE(REPLACE(ipAddress, '%', ''), '".trim($Stats_row['ipAddress'])."') = 1"; 
			$AdminIPAddress_result = mysqli_query($conn, $AdminIPAddress_query) or 
				die("Помилка сервера при запиті<br>".$AdminIPAddress_query." : ".mysqli_error($conn));
			if (mysqli_num_rows($AdminIPAddress_result) > 0) echo "<br>з місця адміна";
		}
	}	?></td>
	</tr><?php
} ?>
	<tr><th colspan=2 style="text-align: right;">Загальна кількість операцій</th>
			<th><?php echo $opers; ?></th><th></th></tr>
</table>
