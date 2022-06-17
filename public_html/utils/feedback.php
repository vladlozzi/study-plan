<html>
<head>
	<title>РНП :: Оцінка та пропозиції</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="../styles.css" />
</head>
<body><?php 
ini_set("error_reporting",E_ALL); ini_set("display_errors",1); ini_set("display_startup_errors",1);
define("IN_ADMIN", TRUE); require "../dbu.php"; mb_internal_encoding("UTF-8");
$_POST['sbtSend'] = isset($_POST['sbtSend']) ? $_POST['sbtSend'] : "";
$_POST['radRate'] = isset($_POST['radRate']) ? $_POST['radRate'] : "";
$_POST['txaWishes'] = isset($_POST['txaWishes']) ? $_POST['txaWishes'] : "";
if (!empty($_POST['sbtSend']) and !empty($_POST['radRate'])) {
	$CurrentDate = date('Y-m-d'); $ip=$_SERVER['REMOTE_ADDR'];
	$IsRate_query = "SELECT id FROM user_rate_for_WEP 
									WHERE ipAddress = \"".$ip."\" AND rate_date = \"".$CurrentDate."\"";
	$IsRate_result = mysqli_query($conn, $IsRate_query) or 
			die("Помилка сервера при запиті<br>".$IsRate_query." : ".mysqli_error($conn)); 
	if (mysqli_num_rows($IsRate_result) == 0) {
		$InsertRate_query = "INSERT INTO user_rate_for_WEP VALUES 
															('', '$ip', '$CurrentDate',
															\"".$_POST['radRate']."\", \"".addslashes($_POST['txaWishes'])."\")";
		$InsertRate_result = mysqli_query($conn, $InsertRate_query) or 
				die("Помилка сервера при запиті<br>".$InsertRate_query." : ".mysqli_error($conn)); ?>
<p style="text-align: center; color: blue; font-weight: bold; 
					margin-top: 0.5em; margin-bottom: 0.2em; font-size: 150%;">Дякую!</p><?php
	} else { ?>
<p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.2em; font-size: 115%;">
Сьогодні Ви вже оцінили програму. Можна оцінити ще завтра або пізніше</p><?php
	}
}
$ShowCreate_query = "SHOW CREATE TABLE user_rate_for_WEP";
$ShowCreate_result = mysqli_query($conn, $ShowCreate_query) or 
			die("Помилка сервера при запиті<br>".$ShowCreate_query." : ".mysqli_error($conn));
$ShowCreate_row = mysqli_fetch_array($ShowCreate_result);
$Matches = preg_split("/[,()']+/", $ShowCreate_row['Create Table']); 
/*print_r($Matches);echo "<br>"; */ $ib = 0; 
while ($ib < count($Matches)) { //echo "<br>*".$Matches[$ib]."*"; 
	if (trim($Matches[$ib]) == "`user_rate` set") break;
	$ib++; 
} $ib++; $ie = $ib;
while ($ie < count($Matches)) { 
	if (trim($Matches[$ie]) == "COLLATE utf8_unicode_ci NOT NULL COMMENT") break;
	$ie++;
} 
?>
<form method="post" target="_self"><table><tr><td>
<span style="font-size: 110%; font-weight: bold; color: Blue;"><br>
Оцініть, будь ласка, програму "Робочі навчальні плани": </span><span style="font-size: 110%;"><?php 
for ($im = $ie-1; $im > $ib-1; $im--) { ?>
<input type="radio" name="radRate" value="<?php echo $Matches[$im]; ?>"> <? echo $Matches[$im]." &nbsp;";
} ?></span><br><span style="color: DarkGreen; font-family: sans-serif;">Оцінок: 
<?php $Rates_query = "SELECT * FROM user_rate_for_WEP"; $Rates_result = mysqli_query($conn, $Rates_query) 
					or die("Помилка сервера при запиті<br>".$Rates_query." : ".mysqli_error($conn));
echo mysqli_num_rows($Rates_result); ?> &nbsp; &nbsp; &nbsp; Середня оцінка: <?php 
if (mysqli_num_rows($Rates_result) > 49) { 
	$balsum = 0; 
	while ($Rates_row = mysqli_fetch_array($Rates_result)) {
		switch ($Rates_row['user_rate']) {
			case 'дуже добре': $balsum += 5; break;
			case 'добре': $balsum += 4; break;
			case 'задовільно': $balsum += 3; break;
			case 'погано': $balsum += 2; break;
		}
	} echo round($balsum / mysqli_num_rows($Rates_result), 1)." із 5";
} else echo "буде доступна після 49 оцінок"; ?></span><br><br>
<span style="font-size: 110%; font-weight: bold; color: Blue">Пропозиції:</span><br>
<textarea name="txaWishes" cols=125 rows=20></textarea></td></tr><tr><td>
<input type="submit" name="sbtSend" value="Надіслати оцінку і пропозиції" style="font-weight: bold; color: DarkBlue;" >
</td></tr></table>
</form>	
</body>
</html>
