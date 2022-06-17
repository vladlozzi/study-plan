<!DOCTYPE html>
<html>
<head>
	<title>Робочі навчальні плани :: ІФНТУНГ</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="styles.css" />
	<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
<div id="wrapper"><?php
// echo "<br><h1><center>Технічні роботи, вибачте за незручності!</center></h1>";
// echo "<br><h2><center>Відновлення роботи - 09.03.2016 о 08:00</center></h2>";
ini_set("error_reporting",E_ALL); ini_set("display_errors",1); ini_set("display_startup_errors",1);
ini_set("session.gc_maxlifetime", 86400); ini_set("session.save_path","./sessions");
define("IN_ADMIN", TRUE);
// echo mb_internal_encoding();
mb_internal_encoding("UTF-8"); require "logger.php"; 
$MinistryName = "Міністерство освіти і науки України";
$UniversityName = "Івано-Франківський національний технічний університет нафти і газу";
$MaxTimePerSalary = 600;
$SuperAdminIPAddressPool = "188.231.232.12";
require "auth.php"; require "header.php"; require "main.php"; require "footer.php"; ?>
</div>
</body>
</html>