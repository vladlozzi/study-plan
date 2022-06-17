<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль study_plan_header.php</p>"; require "footer.php"; exit(); }
$_SESSION['chkProxySign'] = 
	(substr($query_row['proxy_signature'], 0, 32) == md5($_SESSION['login'])) ? 1 : 0;
$_SESSION['chkDepartHeadVisa'] = 
	(substr($query_row['depart_head_visa'], 0, 32) == md5($_SESSION['login'])) ? 1 : 0;
$_SESSION['chkDekanVisa'] = 
	(substr($query_row['dekan_visa'], 0, 32) == md5($_SESSION['login'])) ? 1 : 0;
$_SESSION['chkMethodistVisa'] = 
	(substr($query_row['methodist_visa'], 0, 32) == md5($_SESSION['login'])) ? 1 : 0;
$_SESSION['chkStudyDepBossVisa'] = 
	(substr($query_row['study_depart_boss_visa'], 0, 32) == md5($_SESSION['login'])) ? 1 : 0;
if (!empty($query_row['proxy_signature'])) $_POST['chkSubjSums'] = "on";
if ($frag == "NO") { ?>
<table style="width: 100%; border: 0px;">
	<tr>
		<td style="width: 360px; border: 0px;">
			<p style="text-align: center; 
				font-size: 100%; font-weight: bold; margin-bottom: 0px;">
				<span style="text-transform: uppercase; font-size: 133%;">Затверджую</span><br><br><br>
				Ректор _______________ Крижанівський Є.І.<br>
				"____"__________ 20___р.
			</p>
		</td>
		<td style="border: 0px;">
			<p style="text-align: center; 
				color: black; font-size: 120%; font-weight: bold; margin-bottom: 0px;">
				<span style="text-transform: uppercase;"><?php echo $MinistryName; ?></span>
				<br><?php echo $UniversityName; ?></p>
			<p style="text-align: center; 
				color: black; font-size: 150%; font-weight: bold; margin-bottom: 0px;">
				<span style="text-transform: uppercase;">Робочий навчальний план</span><br>
				підготовки фахівців із вищою освітою</p>
		</td>
		<td style="width: 360px; border: 0px;"> </td>
	</tr>
</table>
			<p style="text-align: right; font-size: 125%; margin-top: 0px; margin-bottom: 0.2em;">
				Реєстровий № <span style="color: blue; font-weight: bold;">
				<?php echo $query_row['reg_number']; ?></span></p><?php
} ?>	<table style="width: 90%;"><tr><td style="text-align: left;">
				Ступінь вищої освіти &mdash; <span style="font-weight: bold;">
				<?php echo $query_row['degree_name']; ?></span></td><td style="text-align: left;">
				Базова освіта (здобута раніше) &mdash; <span style="font-weight: bold;">
				<?php echo $query_row['base_degree_name']; ?></span></td></tr><tr><td style="text-align: left;">
				Спеціальність або напрям &mdash; <span style="font-weight: bold;">
				<?php echo $query_row['specialty_codename']; ?></span></td><td style="text-align: left;">
				Строк навчання &mdash; <span style="font-weight: bold;">
				<?php echo $query_row['edu_term_years']." р. ".$query_row['edu_term_months']." міс."; ?>
				</span></td></tr><tr><td style="text-align: left;">
				Спеціалізація (освітня програма) &mdash; <span style="font-weight: bold;">
				<?php echo str_replace("<br>"," ",$query_row['eduprogram_codename']); ?></span></td>
				<td style="text-align: left;">Інститут &mdash; <span style="font-weight: bold;">
				<?php echo str_replace("Інститут ","",$query_row['fakultet_name']); ?>
				</span></td></tr><tr><td style="text-align: left;">
				Форма навчання &mdash; <span style="font-weight: bold;">
				<?php echo $query_row['edu_form']; ?></span></td>
				<td style="text-align: left;">
				Випускна кафедра &mdash; <span style="font-weight: bold;">
				<?php echo str_replace("Кафедра ","",DepartmentById($query_row['depart_id'])); ?>
				</span></td></tr>
			</table><?php
if ($frag == "NO") { ?>
			<p style="text-align: center;">
				Набирає чинності з <span style="font-weight: bold;">
				<?php echo $query_row['actualize_year']; ?></span> н.р. &nbsp; &nbsp;
				Затверджено Вченою радою університету <span style="font-weight: bold;">
				<?php echo date_format(date_create($query_row['stamp_date']),"d.m.Y"); ?>р.</span>, 
				протокол № <span style="font-weight: bold;">
				<?php echo $query_row['protocol_number']; ?></span>
			</p><?php 
} $HoursPerCredit = (substr($query_row['actualize_year'], 0, 4) >= 2015) ? 30 : 36; ?>