<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль header.php</p>"; require "footer.php"; exit(); }
?>
<h4 style="text-align: center; margin-top: 0.2em; margin-bottom: 0.2em;">
<?php echo $UniversityName; ?></h4>
<h3 style="text-align: center; font-family: sans-serif; margin-top: 0.3em; margin-bottom: 0.3em;">
	Робочі навчальні плани</h3>
<h4 style="text-align: center; margin-top: 0.2em; margin-bottom: 0.4em;">
	та довідники для систем 
		<!-- <a href="https://teach-supp.nung.edu.ua" target="_blank">Моніторинг успішності</a>, -->
		<!-- <a href="https://vidvid.nung.edu.ua" target="_blank">Моніторинг відвідування</a>, -->
		<a href="https://teach-rating.nung.edu.ua" target="_blank">Рейтинг НПП</a>, 
		<a href="https://stud-info.nung.edu.ua" target="_blank">Викладач очима студентів</a>
</h4>
<datalist id="KindsOfStudy">
	<option value=""><option value="МК"> <option value="Е"><option value="НП">
	<option value="ВП"><option value="ПП"> <option value="ДЕ"><option value="КР">
	<option value="ВЗ"><option value="К"> <option value="-">
</datalist>
<?php // створення переліку дисциплін длЯ введення через <input list>
$SubjectDepartCodeSubjectIdQuery = "SELECT a.naz_dus, b.shufr_kaf, a.id
												FROM catalogSubject a, catalogDepartment b
												WHERE a.shufr_kaf = b.id
												ORDER BY naz_dus";
$query100_result = mysqli_query($conn, $SubjectDepartCodeSubjectIdQuery) or 
					die("<br>Помилка сервера при запиті<br>".$SubjectDepartCodeSubjectIdQuery." : "
					.mysqli_error($conn));
?>
<datalist id="lstSubjDepartCodeSubjId">
<?php
while ($query100_row = mysqli_fetch_array($query100_result)) {
?>
	<option><?php echo $query100_row['naz_dus']." &mdash; каф.&nbsp;".
						$query100_row['shufr_kaf']." (ID:".$query100_row['id'].")"; ?></option>
<?php
}
?>
</datalist>
