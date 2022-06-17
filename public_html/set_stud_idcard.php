<?php
define("IN_ADMIN", TRUE); require "dbu.php";
echo "Зберігаю номери студентських квитків у catalogStudent...<br>";
$SetStudentIdCard_query = "
	UPDATE catalogStudent a, student_id_card b 
	SET a.id_card = CONCAT(b.seria, ' ', b.number)
	WHERE 
		CONCAT(a.student_name, ' ', a.surname, ' ', a.pobatkovi) = 
		CONCAT(b.student_family_name, ' ', b.student_given_name, ' ', b.student_surname)";
$SetStudentIdCard_result = mysqli_query($conn, $SetStudentIdCard_query) or 
				die("Помилка сервера при запиті<br>".$SetStudentIdCard_query." : ".mysqli_error($conn));
echo "Номери студентських квитків успішно збережено в catalogStudent";
?>