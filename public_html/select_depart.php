<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль admin.php</p>"; require "footer.php"; exit(); }
$DepartsQuery = "SELECT id, REPLACE(nazva_kaf, \"Кафедра \", \"\") AS nazva_kaf FROM catalogDepartment ORDER BY nazva_kaf";
$_POST['deptosel'] = isset($_POST['deptosel']) ? $_POST['deptosel'] : "";
		echo "<br>".selectCommonSelectAutoSubmit
			("Виберіть кафедру:", "deptosel", $conn, $DepartsQuery, "id", $_POST['deptosel'], "nazva_kaf", "");
?>
