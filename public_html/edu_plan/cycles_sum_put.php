<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
	                              "Помилка входу в модуль cycles_sum_put.php</p>"; 
									 require "footer.php"; exit(); }
$Sum1LecCycle -= $query10_row['sem1_lectural_hours']; $Sum1LabCycle -= $query10_row['sem1_laboratorials_hours'];
$Sum1PrcCycle -= $query10_row['sem1_practicals_hours']; $Sum1IndCycle -= $query10_row['sem1_individual_work_hours'];
$Sum2LecCycle -= $query10_row['sem2_lectural_hours']; $Sum2LabCycle -= $query10_row['sem2_laboratorials_hours'];
$Sum2PrcCycle -= $query10_row['sem2_practicals_hours']; $Sum2IndCycle -= $query10_row['sem2_individual_work_hours'];
$Sum3LecCycle -= $query10_row['sem3_lectural_hours'];	$Sum3LabCycle -= $query10_row['sem3_laboratorials_hours'];
$Sum3PrcCycle -= $query10_row['sem3_practicals_hours']; $Sum3IndCycle -= $query10_row['sem3_individual_work_hours'];$Sum4LecCycle -= $query10_row['sem4_lectural_hours']; $Sum4LabCycle -= $query10_row['sem4_laboratorials_hours'];
$Sum4PrcCycle -= $query10_row['sem4_practicals_hours']; $Sum4IndCycle -= $query10_row['sem4_individual_work_hours'];
$Sum5LecCycle -= $query10_row['sem5_lectural_hours']; $Sum5LabCycle -= $query10_row['sem5_laboratorials_hours'];
$Sum5PrcCycle -= $query10_row['sem5_practicals_hours']; $Sum5IndCycle -= $query10_row['sem5_individual_work_hours'];
$Sum6LecCycle -= $query10_row['sem6_lectural_hours']; $Sum6LabCycle -= $query10_row['sem6_laboratorials_hours'];
$Sum6PrcCycle -= $query10_row['sem6_practicals_hours']; $Sum6IndCycle -= $query10_row['sem6_individual_work_hours'];
$Sum7LecCycle -= $query10_row['sem7_lectural_hours']; $Sum7LabCycle -= $query10_row['sem7_laboratorials_hours'];
$Sum7PrcCycle -= $query10_row['sem7_practicals_hours']; $Sum7IndCycle -= $query10_row['sem7_individual_work_hours'];
$Sum8LecCycle -= $query10_row['sem8_lectural_hours']; $Sum8LabCycle -= $query10_row['sem8_laboratorials_hours'];
$Sum8PrcCycle -= $query10_row['sem8_practicals_hours']; $Sum8IndCycle -= $query10_row['sem8_individual_work_hours']; 
$SumLecCycle = $Sum1LecCycle + $Sum2LecCycle + $Sum3LecCycle + $Sum4LecCycle + 
							$Sum5LecCycle + $Sum6LecCycle + $Sum7LecCycle + $Sum8LecCycle;
$SumLabCycle = $Sum1LabCycle + $Sum2LabCycle + $Sum3LabCycle + $Sum4LabCycle + 
							$Sum5LabCycle + $Sum6LabCycle + $Sum7LabCycle + $Sum8LabCycle;
$SumPrcCycle = $Sum1PrcCycle + $Sum2PrcCycle + $Sum3PrcCycle + $Sum4PrcCycle + 
							$Sum5PrcCycle + $Sum6PrcCycle + $Sum7PrcCycle + $Sum8PrcCycle;
$SumIndCycle = $Sum1IndCycle + $Sum2IndCycle + $Sum3IndCycle + $Sum4IndCycle + 
							$Sum5IndCycle + $Sum6IndCycle + $Sum7IndCycle + $Sum8IndCycle; 
$SumHoursCycle = $SumLecCycle + $SumLabCycle + $SumPrcCycle + $SumIndCycle; 
$SumCredCycle = $SumHoursCycle / $HoursPerCredit; ?>
<tr><th colspan=2 style="font-size: 120%">Усього в циклі <?php echo $CyclesCount; ?></th>
		<th style="font-size: 120%"><?php echo $SumCredCycle; ?></th>
		<th style="font-size: 120%"><?php echo $SumHoursCycle; ?></th>
		<th style="font-size: 120%"><?php echo $SumLecCycle; ?></th>
		<th style="font-size: 120%"><?php echo $SumLabCycle; ?></th>
		<th style="font-size: 120%"><?php echo $SumPrcCycle; ?></th>
		<th style="font-size: 120%"><?php echo $SumIndCycle; ?></th>
		<th><?php echo $Sum1LecCycle; ?></th><th><?php echo $Sum1LabCycle; ?></th>
		<th><?php echo $Sum1PrcCycle; ?></th><th><?php echo $Sum1IndCycle; ?></th>
		<th><?php echo $Sum2LecCycle; ?></th><th><?php echo $Sum2LabCycle; ?></th>
		<th><?php echo $Sum2PrcCycle; ?></th><th><?php echo $Sum2IndCycle; ?></th>
		<th><?php echo $Sum3LecCycle; ?></th><th><?php echo $Sum3LabCycle; ?></th>
		<th><?php echo $Sum3PrcCycle; ?></th><th><?php echo $Sum3IndCycle; ?></th>
		<th><?php echo $Sum4LecCycle; ?></th><th><?php echo $Sum4LabCycle; ?></th>
		<th><?php echo $Sum4PrcCycle; ?></th><th><?php echo $Sum4IndCycle; ?></th>
		<th><?php echo $Sum5LecCycle; ?></th><th><?php echo $Sum5LabCycle; ?></th>
		<th><?php echo $Sum5PrcCycle; ?></th><th><?php echo $Sum5IndCycle; ?></th>
		<th><?php echo $Sum6LecCycle; ?></th><th><?php echo $Sum6LabCycle; ?></th>
		<th><?php echo $Sum6PrcCycle; ?></th><th><?php echo $Sum6IndCycle; ?></th>
		<th><?php echo $Sum7LecCycle; ?></th><th><?php echo $Sum7LabCycle; ?></th>
		<th><?php echo $Sum7PrcCycle; ?></th><th><?php echo $Sum7IndCycle; ?></th>
		<th><?php echo $Sum8LecCycle; ?></th><th><?php echo $Sum8LabCycle; ?></th>
		<th><?php echo $Sum8PrcCycle; ?></th><th><?php echo $Sum8IndCycle; ?></th>
</tr><?php
$Sum1LecCycle = $query10_row['sem1_lectural_hours']; $Sum1LabCycle = $query10_row['sem1_laboratorials_hours'];
$Sum1PrcCycle = $query10_row['sem1_practicals_hours']; $Sum1IndCycle = $query10_row['sem1_individual_work_hours'];
$Sum2LecCycle = $query10_row['sem2_lectural_hours']; $Sum2LabCycle = $query10_row['sem2_laboratorials_hours'];
$Sum2PrcCycle = $query10_row['sem2_practicals_hours']; $Sum2IndCycle = $query10_row['sem2_individual_work_hours'];
$Sum3LecCycle = $query10_row['sem3_lectural_hours'];	$Sum3LabCycle = $query10_row['sem3_laboratorials_hours'];
$Sum3PrcCycle = $query10_row['sem3_practicals_hours']; $Sum3IndCycle = $query10_row['sem3_individual_work_hours'];
$Sum4LecCycle = $query10_row['sem4_lectural_hours']; $Sum4LabCycle = $query10_row['sem4_laboratorials_hours'];
$Sum4PrcCycle = $query10_row['sem4_practicals_hours']; $Sum4IndCycle = $query10_row['sem4_individual_work_hours'];
$Sum5LecCycle = $query10_row['sem5_lectural_hours']; $Sum5LabCycle = $query10_row['sem5_laboratorials_hours'];
$Sum5PrcCycle = $query10_row['sem5_practicals_hours']; $Sum5IndCycle = $query10_row['sem5_individual_work_hours'];
$Sum6LecCycle = $query10_row['sem6_lectural_hours']; $Sum6LabCycle = $query10_row['sem6_laboratorials_hours'];
$Sum6PrcCycle = $query10_row['sem6_practicals_hours']; $Sum6IndCycle = $query10_row['sem6_individual_work_hours'];
$Sum7LecCycle = $query10_row['sem7_lectural_hours']; $Sum7LabCycle = $query10_row['sem7_laboratorials_hours'];
$Sum7PrcCycle = $query10_row['sem7_practicals_hours']; $Sum7IndCycle = $query10_row['sem7_individual_work_hours'];
$Sum8LecCycle = $query10_row['sem8_lectural_hours']; $Sum8LabCycle = $query10_row['sem8_laboratorials_hours'];
$Sum8PrcCycle = $query10_row['sem8_practicals_hours']; $Sum8IndCycle = $query10_row['sem8_individual_work_hours']; ?>
