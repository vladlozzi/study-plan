<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль dbu.php</p>"; require "footer.php"; exit(); }
$php_timezn = 'Europe/Kiev'; date_default_timezone_set($php_timezn);
$mysql_timezn = (new DateTime('now', new DateTimeZone($php_timezn)))->format('P'); // echo $mysql_timezn;

$host = "localhost";
$user = "admin";
$pass = "";
$dbname = "tsupp_controwl";
$conn = mysqli_connect($host, $user, $pass)
	or die("Помилка входу на сервер БД ".$host.": ".mysqli_connect_error());
$d_s = mysqli_select_db($conn, $dbname)
	or die("Помилка вибору бази даних: ".mysqli_error($conn));
mysqli_query($conn, "SET NAMES 'utf8';")
	or die("Помилка встановлення кодування UTF-8: ".mysqli_error($conn));
mysqli_query($conn, "SET time_zone = '$mysql_timezn';")
	or die("Помилка встановлення часового поясу: ".mysqli_error($conn));
mysqli_query($conn,"SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));")
	or die("Помилка вимкнення режиму ONLY_FULL_GROUP_BY".mysqli_error($conn));
?>