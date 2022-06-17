<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль contingent.php</p>"; require "footer.php"; exit(); }
$_POST['radContingentSelect'] = isset($_POST['radContingentSelect']) ? 
															$_POST['radContingentSelect'] : "Current"; ?>
<p style="text-align: center; color: blue; margin-top: 0.5em; margin-bottom: 0.1em; font-size: 133%;">
Виберіть контингент: &nbsp; 
<input type="radio" name="radContingentSelect" value="Current" onclick="submit()" 
	<?php if ($_POST['radContingentSelect'] == "Current") echo "checked"; ?>> 
	на поточний навчальний рік &nbsp; &nbsp; 
<input type="radio" name="radContingentSelect" value="Next" onclick="submit()" 
	<?php if ($_POST['radContingentSelect'] == "Next") echo "checked"; ?>> 
	на наступний навчальний рік
</p>
<?php
switch ($_POST['radContingentSelect']) {
	case "Current":	require "groups_pages.php"; break;
	case "Next": require "groups_next_pages.php"; break;
} 
?>